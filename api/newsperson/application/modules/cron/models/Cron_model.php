<?php

/**
 * Used for cron job
 * @package     Cron
 * @category    Cron
 */
class Cron_model extends My_Model {

    public function __construct() {
        parent::__construct();
    }

    function reporter_viewership() {
        $this->db->select('id, reporter_status, reporter_reg_date');
        $this->db->from("users");
        $this->db->where('is_reporter', 1);
        $this->db->where("active", 1);
        $this->db->order_by('reporter_reg_date', 'ASC');
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            $results = $query->result_array();   
            $today = date("Y-m-d");
            foreach ($results as $key => $result) {
                $this->db->select('history_id, start_date, end_date');
                $this->db->from('sb_reporter_status_history');
                $this->db->where('user_id', $result['id']);
                $this->db->order_by('history_id', 'DESC');
                $this->db->limit(1);
                $query = $this->db->get();
                if($query->num_rows() > 0) {
                    $row = $query->row_array();
                    $start_date = $row['start_date'];
                    $start_date = $this->get_start_date($start_date);
                    $end_date = $row['end_date'];
                    if(empty($end_date)) {
                        $end_date = date("Y-m-d",strtotime("-1 days"));
                    }
                    $start_date_time_stamp = strtotime($start_date);
                    $end_date_time_stamp = strtotime($end_date);

                    $this->db->select('p.id');
                    $this->db->from('posts p');
                    $this->db->where('p.date >=', $start_date_time_stamp);
                    $this->db->where('p.date <=', $end_date_time_stamp);
                    $this->db->where('p.user_id', $result['id']);
                    $query = $this->db->get();
                    $total_news = $query->num_rows();
                    if(empty($total_news)) {
                        continue;
                    }

                    $this->db->select('sum(pvdw.cnt) as total_views');
                    $this->db->from('post_views_day_wise pvdw');
                    $this->db->join('posts p', 'p.id = pvdw.post_id AND p.date >= '.$start_date_time_stamp.' AND p.date < '.$end_date_time_stamp.' AND p.user_id='.$result['id']);
                    $this->db->where('pvdw.view_date >=', $start_date);
                    $this->db->where('pvdw.view_date <=', $end_date);
                    $this->db->limit(1);
                    $query = $this->db->get();
                    $total_views = 0;
                    if($query->num_rows() > 0) {
                        $row = $query->row_array();
                        if(!empty($row['total_views'])) {
                            $total_views = $row['total_views'];
                        }                        
                    }

                    $data['start_date'] = $start_date;
                    $data['end_date'] = $end_date;
                    $data['total_views'] = $total_views;
                    $data['total_news'] = $total_news;
                    $data['user_id'] = $result['id'];

                    $total_earning = 0;
                    $viewership_divider = getenv('VIEWERSHIP_DIVIDER');
                    $viewership_multiplier = getenv('VIEWERSHIP_MULTIPLIER');
                    if($total_views > 0) {
                        $total_earning = round(($total_views/$viewership_divider)*$viewership_multiplier, 2);
                    }
                    $data['total_earning'] = $total_earning;
                    $data['total_payment'] = $total_earning;

                    $data['divisor'] = $viewership_divider;
                    $data['multiplier'] = $viewership_multiplier;
                    
                    $this->insert_viewership_earning($data);
                }
            }
        }
    }

    function insert_viewership_earning($data) {
        $this->db->select('reporter_viewership_id');
        $this->db->from('sb_reporter_viewership');
        $this->db->where('user_id', $data['user_id']);
        $this->db->where('start_date', $data['start_date']);
        $this->db->limit(1);
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            $row = $query->row_array();
            $update_data = array();
            $update_data['end_date']    = $data['end_date'];
            $update_data['total_views'] = $data['total_views'];
            $update_data['total_news']  = $data['total_news'];
            $update_data['total_earning']  = $data['total_earning'];

            $update_data['total_payment']  = $data['total_payment'];
            $update_data['divisor']  = $data['divisor'];
            $update_data['multiplier']  = $data['multiplier'];

            $this->db->where('user_id', $row['reporter_viewership_id']);
            $this->db->update('sb_reporter_viewership',$update_data);
        } else {
            $this->db->insert('sb_reporter_viewership',$data);
        }
    }

    function get_start_date($start_date) {
        $today_date    = date("Y-m-d");
        $today_date_obj     = new DateTime($today_date); 
        $start_date_obj     = new DateTime($start_date); 

        $m1 = $today_date_obj->format("m");
        $m2 = $start_date_obj->format("m");
        $y1 = $today_date_obj->format("Y");
        $y2 = $start_date_obj->format("Y");

        $tmp_start_date = $start_date;
        if($y1 > $y2) {
            $tmp_start_date = date("Y-m-01");
        } else {
            if ($m1 > $m2) {
                $tmp_start_date = date("Y-m-01");
                //echo $m1.' is latest than '.$m2;
            } else {
               // echo $m1.' is equal to '.$m2;
            }
        }
        //echo $tmp_start_date." !== ".$today_date."<br>";
        if($tmp_start_date != $today_date) {
            $start_date = $tmp_start_date;
        }
        //echo "\n<br>".$start_date.' start_date'."\n<br>";
        return $start_date;
    }

    function user_followers_count($page_no=1, $page_size=50) {
        $today = date("Y-m-d");

        try {              
            $this->db->select('id as user_id, num_followers as total_followers');
            $this->db->select('"'.$today.'" as follower_count_date');
            $this->db->from('users');
            $this->db->where('num_followers > ', 0);
            $this->db->order_by('id', 'DESC');
            $this->db->limit($page_size, pagination_offset($page_no, $page_size));
            $query = $this->db->get();
            //echo $this->db->last_query();die;
            if ($query->num_rows() > 0) {
                $results = $query->result_array();
                if($results) {
                    $this->db->insert_on_duplicate_update_batch('sb_user_day_wise_followers', $results);
                }
                $query->free_result();
                $this->user_followers_count(++$page_no);
            }
            
        } catch (Exception $e) {
            log_message('error', 'Issue with user followers count cron job');
        }
    }

    function user_day_wise_news_engagement($page_no=1, $page_size=50) {
        $today = date("Y-m-d");

        try {              
            $this->db->select('id as user_id');
            $this->db->from('users');
            $this->db->order_by('id', 'DESC');
            $this->db->limit($page_size, pagination_offset($page_no, $page_size));
            $query = $this->db->get();
            //echo $this->db->last_query();die;
            if ($query->num_rows() > 0) {
                $results = $query->result_array();
                foreach ($results as $key => $value) {
                    $this->db->select("sum(if(p.posttype=1, IFNULL(pd.likes, 0), 'O')) as total_photo_likes", false);
                    $this->db->select("sum(if(p.posttype=1, IFNULL(pd.comments, 0), 'O')) as total_photo_comments", false);
                    $this->db->select("sum(if(p.posttype=1, IFNULL(pd.reshares, 0), 'O')) as total_photo_rebuzzes", false);
                    $this->db->select("sum(if(p.posttype=1, IFNULL(pd.shares, 0), 'O')) as total_photo_shares", false);
                    $this->db->select("sum(if(p.posttype=1, IFNULL(pd.views, 0), 'O')) as total_photo_views", false);

                    $this->db->select("sum(if(p.posttype=2, IFNULL(pd.likes, 0), 'O')) as total_long_read_likes", false);
                    $this->db->select("sum(if(p.posttype=2, IFNULL(pd.comments, 0), 'O')) as total_long_read_comments", false);
                    $this->db->select("sum(if(p.posttype=2, IFNULL(pd.reshares, 0), 'O')) as total_long_read_rebuzzes", false);
                    $this->db->select("sum(if(p.posttype=2, IFNULL(pd.shares, 0), 'O')) as total_long_read_shares", false);
                    $this->db->select("sum(if(p.posttype=2, IFNULL(pd.views, 0), 'O')) as total_long_read_views", false);

                    $this->db->select("sum(if(p.posttype=3, IFNULL(pd.likes, 0), 'O')) as total_video_likes", false);
                    $this->db->select("sum(if(p.posttype=3, IFNULL(pd.comments, 0), 'O')) as total_video_comments", false);
                    $this->db->select("sum(if(p.posttype=3, IFNULL(pd.reshares, 0), 'O')) as total_video_rebuzzes", false);
                    $this->db->select("sum(if(p.posttype=3, IFNULL(pd.shares, 0), 'O')) as total_video_shares", false);
                    $this->db->select("sum(if(p.posttype=3, IFNULL(pd.views, 0), 'O')) as total_video_views", false);

                    //$this->db->select('sum(likes) as total_likes');
                    //$this->db->select('sum(comments) as total_comments');
                    //$this->db->select('sum(reshares) as total_rebuzzes');
                    //$this->db->select('0 as total_shares');
                    $this->db->from('posts p');
                    $this->db->join('posts_details pd', 'pd.post_id = p.id', 'LEFT');
                    $this->db->where_in('posttype', array(1,2,3));
                    $this->db->where('user_id', $value['user_id']);
                    $this->db->order_by('id', 'DESC');
                    $engagement_query = $this->db->get();
                    $engagements = array();
                    $engagements[0]['engagement_date'] = $today;
                    $engagements[0]['user_id'] = $value['user_id'];
                    $total_likes = 0;
                    $total_comments = 0;
                    $total_rebuzzes = 0;
                    $total_shares = 0;
                    $total_views = 0;

                    $engagements[0]['total_photo_likes'] = 0;
                    $engagements[0]['total_photo_comments'] = 0;
                    $engagements[0]['total_photo_rebuzzes'] = 0;
                    $engagements[0]['total_photo_shares'] = 0;
                    $engagements[0]['total_photo_views'] = 0;

                    $engagements[0]['total_long_read_likes'] = 0;
                    $engagements[0]['total_long_read_comments'] = 0;
                    $engagements[0]['total_long_read_rebuzzes'] = 0;
                    $engagements[0]['total_long_read_shares'] = 0;
                    $engagements[0]['total_long_read_views'] = 0;

                    $engagements[0]['total_video_likes'] = 0;
                    $engagements[0]['total_video_comments'] = 0;
                    $engagements[0]['total_video_rebuzzes'] = 0;
                    $engagements[0]['total_video_shares'] = 0;
                    $engagements[0]['total_video_views'] = 0;
                    if($engagement_query->num_rows() > 0) {
                        $row = $engagement_query->row_array();

                        $engagements[0]['total_photo_likes'] = isset($row['total_photo_likes']) ? $row['total_photo_likes'] : 0;
                        $engagements[0]['total_photo_comments'] = isset($row['total_photo_comments']) ? $row['total_photo_comments'] : 0;
                        $engagements[0]['total_photo_rebuzzes'] = isset($row['total_photo_rebuzzes']) ? $row['total_photo_rebuzzes'] : 0;
                        $engagements[0]['total_photo_shares'] = isset($row['total_photo_shares']) ? $row['total_photo_shares'] : 0;
                        $engagements[0]['total_photo_views'] = isset($row['total_photo_views']) ? $row['total_photo_views'] : 0;

                        $engagements[0]['total_long_read_likes'] = isset($row['total_long_read_likes']) ? $row['total_long_read_likes'] : 0;
                        $engagements[0]['total_long_read_comments'] = isset($row['total_long_read_comments']) ? $row['total_long_read_comments'] : 0;
                        $engagements[0]['total_long_read_rebuzzes'] = isset($row['total_long_read_rebuzzes']) ? $row['total_long_read_rebuzzes'] : 0;
                        $engagements[0]['total_long_read_shares'] = isset($row['total_long_read_shares']) ? $row['total_long_read_shares'] : 0;
                        $engagements[0]['total_long_read_views'] = isset($row['total_long_read_views']) ? $row['total_long_read_views'] : 0;

                        $engagements[0]['total_video_likes'] = isset($row['total_video_likes']) ? $row['total_video_likes'] : 0;
                        $engagements[0]['total_video_comments'] = isset($row['total_video_comments']) ? $row['total_video_comments'] : 0;
                        $engagements[0]['total_video_rebuzzes'] = isset($row['total_video_rebuzzes']) ? $row['total_video_rebuzzes'] : 0;
                        $engagements[0]['total_video_shares'] = isset($row['total_video_shares']) ? $row['total_video_shares'] : 0;
                        $engagements[0]['total_video_views'] = isset($row['total_video_views']) ? $row['total_video_views'] : 0;

                        $total_likes = $engagements[0]['total_photo_likes'] + $engagements[0]['total_long_read_likes'] + $engagements[0]['total_video_likes'];
                        $total_comments = $engagements[0]['total_photo_comments'] + $engagements[0]['total_long_read_comments'] + $engagements[0]['total_video_comments'];
                        $total_rebuzzes = $engagements[0]['total_photo_rebuzzes'] + $engagements[0]['total_long_read_rebuzzes'] + $engagements[0]['total_video_rebuzzes'];
                        $total_shares = $engagements[0]['total_photo_shares'] + $engagements[0]['total_long_read_shares'] + $engagements[0]['total_video_shares'];
                        $total_views = $engagements[0]['total_photo_views'] + $engagements[0]['total_long_read_views'] + $engagements[0]['total_video_views'];
                        
                        if($total_likes > 0 || $total_comments > 0 || $total_rebuzzes > 0 || $total_shares > 0 || $total_views > 0) {
                            $this->db->insert_on_duplicate_update_batch('sb_user_news_engagement', $engagements);
                        }                        
                    }
                }
                $query->free_result();
                $this->user_day_wise_news_engagement(++$page_no);
            }
            
        } catch (Exception $e) {
            log_message('error', 'Issue with user day wise news engagement cron job');
        }
    }

    /**
     * Used to mark reporter status as inactive if he has not posted any news in last one month
     */
    function update_reporter_status() {
        $this->db->select('id, reporter_status, reporter_reg_date');
        $this->db->from("users");
        $this->db->where('is_reporter', 1);
        $this->db->where('reporter_status', 1);        
        $this->db->where("active", 1);
        $this->db->order_by('reporter_reg_date', 'DESC');
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            $start_date = date('Y-m-d', strtotime('-30 days', strtotime(date("Y-m-d")))); 
            $start_date_time_stamp = strtotime($start_date);
            $results = $query->result_array();
            $this->load->model(array('register/Register_model'));
            foreach ($results as $key => $result) {
                $this->db->select("p.id");
                $this->db->from('posts p');
                $this->db->where('p.user_id', $result['id']);
                $this->db->where('p.post_level', 0);
                $this->db->where('p.date >=', $start_date_time_stamp);
                $this->db->limit(1);
                $query = $this->db->get();
                if($query->num_rows() == 0) {
                    $data['is_reporter'] = 1;
                    $data['user_id'] = $result['id'];
                    $data['reporter_current_status'] = $result['reporter_status'];   
                    $data['status'] = 3;                
                    $this->Register_model->save_reporter_data($data);
                }
            }
        }
    }

    function top_newspersons() {

        $this->db->where('added_by', 2);
        $this->db->delete('sb_top_newspersons');

        // select GROUP_CONCAT(u.id), cl.location_id from users u join sb_reporter_coverage_location cl on cl.user_id = u.id where u.is_reporter=1 and u.reporter_status=1 and u.active=1 group by cl.location_id order by cl.location_id asc
        $this->db->select('GROUP_CONCAT(u.id) as user_ids, cl.location_id');
        $this->db->from("users u");
        $this->db->join('sb_reporter_coverage_location cl', 'cl.user_id = u.id');
        $this->db->where('u.is_reporter', 1);
        $this->db->where('u.reporter_status', 1);        
        $this->db->where("u.active", 1);
        $this->db->group_by('cl.location_id');
        $this->db->order_by('cl.location_id', 'ASC');
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            $results = $query->result_array();
            foreach ($results as $key => $result) {
                $user_ids = $result['user_ids'];
                $location_id = $result['location_id'];
                $this->most_engaged_newspersons($location_id, $user_ids);
            }
        }
    } 

    function most_engaged_newspersons($location_id, $user_ids) {
        $user_ids = explode(',',$user_ids);
        if($user_ids) {
            //SELECT p.user_id, p.id, SUM(IFNULL(pd.likes,0)+IFNULL(pd.comments,0)+IFNULL(pd.reshares,0)+IFNULL(pd.shares,0)+IFNULL(pd.views,0)) as popularity FROM posts p LEFT JOIN posts_details pd ON pd.post_id = p.id JOIN sb_reporter_coverage_location cl ON cl.user_id = p.user_id and cl.location_id=1 WHERE p.user_id IN (13433,128,198) AND p.post_level=0 GROUP BY p.user_id ORDER BY popularity DESC
            $this->db->select("p.user_id");
            $this->db->select("SUM(IFNULL(pd.likes,0)+IFNULL(pd.comments,0)+IFNULL(pd.reshares,0)+IFNULL(pd.shares,0)+IFNULL(pd.views,0)) as popularity");
            $this->db->from("posts p");
            $this->db->join('posts_details pd', 'pd.post_id = p.id', 'LEFT');        
            $this->db->join('sb_reporter_coverage_location cl', 'cl.user_id = p.user_id and cl.location_id='.$location_id);
            $this->db->where_in('p.user_id', $user_ids);            
            $this->db->where('p.post_level', 0);        
            $this->db->group_by('p.user_id');
            $this->db->order_by('popularity', 'DESC');
            $query = $this->db->get();
            $results = array();
            if($query->num_rows() > 0) {
                $results = $query->result_array();

                $this->db->select('display_order');
                $this->db->from('sb_top_newspersons');
                $this->db->where('location_id', $location_id);
                $this->db->order_by('display_order', 'DESC');
                $this->db->limit(1);
                $top_newsperson = $this->db->get(); 
                $display_order = 1;
                if($top_newsperson->num_rows() > 0) {
                    $row = $top_newsperson->row_array();
                    $display_order = $row['display_order'];
                    ++$display_order;
                } 
                $date = time();
                $insert_data = array();
                $i = 0;
                foreach ($results as $key => $result) {
                    $insert_data[$i]['location_id'] = $location_id;
                    $insert_data[$i]['added_by'] = 2;
                    $insert_data[$i]['added_date'] = $date;
                    $insert_data[$i]['user_id'] = $result['user_id'];
                    $insert_data[$i]['display_order'] = $display_order;
                    ++$i;
                    ++$display_order;
                }

                if (!empty($insert_data)) {            
                    $this->db->insert_on_duplicate_update_batch('sb_top_newspersons', $insert_data);
                }
            }
        }
    }
}