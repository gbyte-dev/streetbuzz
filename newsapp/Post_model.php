<?php

/**
 * Used for post
 * @package     Post
 * @category    Post
 */
class Post_model extends My_Model {

    public function __construct() {
        parent::__construct();
    }

    function top_post($data) {
        $user_id    = $data['user_id'];
        $start_date = $data['start_date'];

        
        $post_type  = isset($data['post_type']) ? $data['post_type'] : array(1,2,3);
        if(empty($post_type)) {
            $post_type = array(1,2,3);
        }
        $post_type_str = implode(',', $post_type);

        $page_no    = isset($data['page_no']) ? $data['page_no'] : 1;
        $page_size  = isset($data['page_size']) ? $data['page_size'] : 3;
        $offset     = pagination_offset($page_no, $page_size);
        
        $start_date_time_stamp = strtotime($start_date);
               
        
       // $this->db->select("p.likes, p.comments, p.reshares, p.share_count, p.post_views_cnt");
        $this->db->select('IFNULL(pd.likes,0) as likes', FALSE);
        $this->db->select('IFNULL(pd.comments,0) as comments', FALSE);
        $this->db->select('IFNULL(pd.reshares,0) as reshares', FALSE);
        $this->db->select('IFNULL(pd.shares,0) as share_count', FALSE);
        $this->db->select('IFNULL(pd.views,0) as post_views_cnt', FALSE);
        $this->db->select("SUM(IFNULL(pd.likes,0)+IFNULL(pd.comments,0)+IFNULL(pd.reshares,0)+IFNULL(pd.shares,0)) as popularity");

        
        $this->db->select("b.id AS pid, u.username AS postusername");
        $this->db->select('IFNULL(u.avatar,"") as postuserimage', FALSE);
        $this->db->select('IFNULL(u.cover,"") as coverimage', FALSE);
        $this->db->select("p.id AS postid, p.user_id AS postuserid, p.posttype, p.message, 
        p.mentioned, p.posttags, p.date, p.date_lastedit, p.date_lastcomment, p.group_name,
        p.parent_id, p.post_level, '' as `category`, 'public' AS `type`");
        $this->db->select('IFNULL(p.thumb,"") as thumb', FALSE);
        $this->db->select('IFNULL(p.location,"") as location', FALSE);
        $this->db->select("if((p.posttype = 2), p.title,  SUBSTRING(p.message, 1, 75)) AS title");
        $this->db->select("if(((Select count(id) from  posts_social_share where post_id =p.id and user_id=b.user_id) > 0), 1,  0) AS isshared");
        $this->db->select('GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements');

        $this->db->from("post_userbox b");
        $this->db->join('posts p', 'p.id = b.post_id AND p.post_level=0 AND p.posttype IN(' . $post_type_str . ') AND p.date >= "'.$start_date_time_stamp.'"');
        $this->db->join('posts_details pd', 'pd.post_id = p.id', 'LEFT');
        $this->db->join('users u', 'u.id = b.user_id');
        $this->db->join('posts_attachments pa', 'pa.post_id=b.post_id', 'LEFT');

        $this->db->where("b.user_id", $user_id);
        $this->db->group_by('p.id');
        $this->db->order_by('popularity', 'DESC');
        $this->db->limit($page_size,$offset);
        $query = $this->db->get();
        $results = array();
        if($query->num_rows() > 0) {
            $results = $query->result_array();

           

            foreach ($results as $key => $result) {
                $post_id = $result['postid'];
                $post_owner_id = $result['postuserid'];

                if(empty($result["attachements"])) {
                    $result["attachements"] = '';
                }

                $result['isliked'] = $this->is_liked($user_id, $post_id);
                $result['isbuzzed'] = $this->is_buzzed($user_id, $post_id);
                $result['isshared'] = $this->is_profile_shared($user_id, $post_owner_id);
                $result['isfollwed'] = $this->is_followed($user_id, $post_owner_id);
                unset($result['popularity']);
                $results[$key] = $result;
            }
        }
        return $results;   
    	
    }

    function guest_timeline($data) {
        $user_id = 0;
        $location = isset($data['location']) ? $data['location'] : array();

        $district = isset($location['district']) ? trim($location['district']) : '';
        $state = isset($location['state']) ? trim($location['state']) : '';
        $country = isset($location['country']) ? trim($location['country']) : '';

        $start_date = date('Y-m-d', strtotime('-10 days', strtotime(date("Y-m-d")))); 
        $start_date_time_stamp = strtotime($start_date);
        
        $page_no    = isset($data['page_no']) ? $data['page_no'] : 1;
        $page_size  = isset($data['page_size']) ? $data['page_size'] : 3;
        $offset     = pagination_offset($page_no, $page_size);
                       
        $this->db->select("u.username AS postusername");
        $this->db->select('IFNULL(u.avatar,"") as postuserimage', FALSE);
        $this->db->select('IFNULL(u.cover,"") as coverimage', FALSE);
        $this->db->select("p.id AS postid, p.user_id AS postuserid, p.posttype, p.message, 
        p.mentioned, p.posttags, p.date, p.date_lastedit, p.date_lastcomment, p.group_name,
        p.parent_id, p.post_level, '' as `category`, 'public' AS `type`");

        $this->db->select('IFNULL(pd.likes,0) as likes', FALSE);
        $this->db->select('IFNULL(pd.comments,0) as comments', FALSE);
        $this->db->select('IFNULL(pd.reshares,0) as reshares', FALSE);
        $this->db->select('IFNULL(pd.shares,0) as share_count', FALSE);
        $this->db->select('IFNULL(pd.views,0) as post_views_cnt', FALSE);
        $this->db->select("SUM(IFNULL(pd.likes,0)+IFNULL(pd.comments,0)+IFNULL(pd.reshares,0)+IFNULL(pd.shares,0)+IFNULL(pd.views,0)) as popularity");

        $this->db->select('IFNULL(p.thumb,"") as thumb', FALSE);
        $this->db->select('IFNULL(p.location,"") as location', FALSE);
        $this->db->select("if((p.posttype = 2), p.title,  SUBSTRING(p.message, 1, 75)) AS title");
        $this->db->select("if(((Select count(id) from  posts_social_share where post_id =p.id and user_id=p.user_id) > 0), 1,  0) AS isshared");
        $this->db->select('IFNULL(GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data),"") as attachements');

        $this->db->from("posts p");
        $this->db->join('users u', 'u.id = p.user_id');
        $this->db->join('posts_details pd', 'pd.post_id = p.id', 'LEFT');
        $this->db->join('posts_attachments pa', 'pa.post_id=p.id', 'LEFT');
        if(!empty($district) || !empty($state) || !empty($country)) {
            $this->db->join('sb_reporter_coverage_location cl', 'cl.user_id = p.user_id');
            $this->db->join('sb_location_master lm', 'lm.id = cl.location_id');
            if(!empty($country)) {
                $this->db->where('LOWER(lm.location_country)', strtolower($country),NULL,FALSE);
            }
            if(!empty($state)) {
                $this->db->where('LOWER(lm.location_state)', strtolower($state),NULL,FALSE);
            }
            if(!empty($district)) {
                $this->db->where('LOWER(lm.location_district)', strtolower($district),NULL,FALSE);
            }
        }
        $this->db->where('p.post_level', 0);
        $this->db->where('p.date >=', $start_date_time_stamp);
        
        $this->db->group_by('p.id');

        $this->db->order_by('popularity', 'DESC');
        $this->db->order_by('p.date', 'DESC');
        $this->db->limit($page_size,$offset);
        $query = $this->db->get();
        $results = array();
        if($query->num_rows() > 0) {
            $results = $query->result_array();

            $url = "http://"; 
            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                $url = "https://";
            }
	        $url.= $_SERVER['HTTP_HOST'];  
            $profile_base_url =       $url."/".PROJECT_FOLDER_NAME."/storage/avatars/thumbs1/";
            $attachment_base_url =       $url."/".PROJECT_FOLDER_NAME."/storage/attachments/1/";
            foreach ($results as $key => $result) {
                $post_id = $result['postid'];
                $post_owner_id = $result['postuserid'];

                if(empty($result["attachements"])) {
                    $result["attachements"] = '';
                }

                $result['isliked']      = $this->is_liked($user_id, $post_id);
                $result['isbuzzed']     = $this->is_buzzed($user_id, $post_id);
                $result['isshared']     = $this->is_profile_shared($user_id, $post_owner_id);
                $result['isfollwed']    = $this->is_followed($user_id, $post_owner_id);

                $result["profile_base_url"]     =       $profile_base_url;
                $result["attachment_base_url"]  =       $attachment_base_url;

                if ($result["posttype"] == 4) {                    
                    $result["event_detail"]     = $this->get_event_post($user_id, $post_id);
                } else if ($result["posttype"] == 5) {                    
                    $poll_details     = $this->get_poll_post($user_id, $post_id);
                    $result = array_merge($result, $poll_details);
                }
                unset($result['popularity']);
                $results[$key] = $result;
            }
        }
        
        return $results;   
    	
    }

    function poll_near_you($data) {
        $user_id = 0;
        $location = isset($data['location']) ? $data['location'] : array();

        $district = isset($location['district']) ? trim($location['district']) : '';
        $state = isset($location['state']) ? trim($location['state']) : '';
        $country = isset($location['country']) ? trim($location['country']) : '';

        $start_date = date('Y-m-d', strtotime('-30 days', strtotime(date("Y-m-d")))); 
        $start_date_time_stamp = strtotime($start_date);
        
        $page_no    = isset($data['page_no']) ? $data['page_no'] : 1;
        $page_size  = isset($data['page_size']) ? $data['page_size'] : 3;
        $offset     = pagination_offset($page_no, $page_size);
                       
        $this->db->select("u.username AS postusername");
        $this->db->select('IFNULL(u.avatar,"") as postuserimage', FALSE);
        $this->db->select('IFNULL(u.cover,"") as coverimage', FALSE);
        $this->db->select("p.id AS postid, p.user_id AS postuserid, p.posttype, p.message, 
        p.mentioned, p.posttags, p.date, p.date_lastedit, p.date_lastcomment, p.group_name,
        p.parent_id, p.post_level, '' as `category`, 'public' AS `type`");
        
        $this->db->select('IFNULL(pd.likes,0) as likes', FALSE);
        $this->db->select('IFNULL(pd.comments,0) as comments', FALSE);
        $this->db->select('IFNULL(pd.reshares,0) as reshares', FALSE);
        $this->db->select('IFNULL(pd.shares,0) as share_count', FALSE);
        $this->db->select('IFNULL(pd.views,0) as post_views_cnt', FALSE);
        $this->db->select("SUM(IFNULL(pd.likes,0)+IFNULL(pd.comments,0)+IFNULL(pd.reshares,0)+IFNULL(pd.shares,0)+IFNULL(pd.views,0)) as popularity");

        $this->db->select('IFNULL(p.status,"") as status', FALSE);
        $this->db->select('IFNULL(p.thumb,"") as thumb', FALSE);
        $this->db->select('IFNULL(p.location,"") as location', FALSE);
        
        $this->db->select("if((p.posttype = 2), p.title,  SUBSTRING(p.message, 1, 75)) AS title");
        $this->db->select("if(((Select count(id) from  posts_social_share where post_id =p.id and user_id=p.user_id) > 0), 1,  0) AS isshared");
        $this->db->select('IFNULL(GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data),"") as attachements');

        $this->db->from("posts p");
        $this->db->join('posts_details pd', 'pd.post_id = p.id', 'LEFT');
        $this->db->join('users u', 'u.id = p.user_id');
        $this->db->join('posts_attachments pa', 'pa.post_id=p.id', 'LEFT');
        if(!empty($district) || !empty($state) || !empty($country)) {
            $this->db->join('sb_reporter_coverage_location cl', 'cl.user_id = p.user_id');
            $this->db->join('sb_location_master lm', 'lm.id = cl.location_id');
            if(!empty($country)) {
                $this->db->where('LOWER(lm.location_country)', strtolower($country),NULL,FALSE);
            }
            if(!empty($state)) {
                $this->db->where('LOWER(lm.location_state)', strtolower($state),NULL,FALSE);
            }
            if(!empty($district)) {
                $this->db->where('LOWER(lm.location_district)', strtolower($district),NULL,FALSE);
            }
        }
        $this->db->where('p.post_level', 0);
        $this->db->where('p.posttype', 5);
        $this->db->where('p.date >=', $start_date_time_stamp);
        
        $this->db->group_by('p.id');

        $this->db->order_by('popularity', 'DESC');
        $this->db->order_by('p.date', 'DESC');
        $this->db->limit($page_size,$offset);
        $query = $this->db->get();
        $results = array();
        if($query->num_rows() > 0) {
            $results = $query->result_array();

            $url = "http://"; 
            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                $url = "https://";
            }
	        $url.= $_SERVER['HTTP_HOST'];  
            $profile_base_url =       $url."/".PROJECT_FOLDER_NAME."/storage/avatars/thumbs1/";
            $attachment_base_url =       $url."/".PROJECT_FOLDER_NAME."/storage/attachments/1/";
            foreach ($results as $key => $result) {
                $post_id = $result['postid'];
                $post_owner_id = $result['postuserid'];

                if(empty($result["attachements"])) {
                    $result["attachements"] = '';
                }

                $result['isliked']      = $this->is_liked($user_id, $post_id);
                $result['isbuzzed']     = $this->is_buzzed($user_id, $post_id);
                $result['isshared']     = $this->is_profile_shared($user_id, $post_owner_id);
                $result['isfollwed']    = $this->is_followed($user_id, $post_owner_id);

                $result["profile_base_url"]     =       $profile_base_url;
                $result["attachment_base_url"]  =       $attachment_base_url;

                                    
                $poll_details     = $this->get_poll_post($user_id, $post_id);
                $result = array_merge($result, $poll_details);
                unset($result['popularity']);
                $results[$key] = $result;
            }
        }
        
        return $results;   
    	
    }

    function event_near_you($data) {
        $user_id = 0;
        $lat    = $data['latitude'];
        $lng    = $data['longitude'];
        
        $page_no    = isset($data['page_no']) ? $data['page_no'] : 1;
        $page_size  = isset($data['page_size']) ? $data['page_size'] : 3;
        $offset     = pagination_offset($page_no, $page_size);
        //6371 f k,
        //3959  f m,     
        $this->db->select("(6371*acos(cos(radians(" . $lat . "))*cos(radians(e.latitude))*cos(radians(e.longitude)-radians(" . $lng . "))+sin(radians(" . $lat . "))*sin(radians(e.latitude)))) as Distance", false);
        $this->db->select("u.username AS postusername");
        $this->db->select('IFNULL(u.avatar,"") as postuserimage', FALSE);
        $this->db->select('IFNULL(u.cover,"") as coverimage', FALSE);
        $this->db->select("p.id AS postid, p.user_id AS postuserid, p.posttype, p.message, 
        p.mentioned, p.posttags, p.date, p.date_lastedit, p.date_lastcomment, p.group_name,
        p.parent_id, p.post_level, '' as `category`, 'public' AS `type`");
        
        $this->db->select('IFNULL(pd.likes,0) as likes', FALSE);
        $this->db->select('IFNULL(pd.comments,0) as comments', FALSE);
        $this->db->select('IFNULL(pd.reshares,0) as reshares', FALSE);
        $this->db->select('IFNULL(pd.shares,0) as share_count', FALSE);
        $this->db->select('IFNULL(pd.views,0) as post_views_cnt', FALSE);
        $this->db->select("SUM(IFNULL(pd.likes,0)+IFNULL(pd.comments,0)+IFNULL(pd.reshares,0)+IFNULL(pd.shares,0)+IFNULL(pd.views,0)) as popularity");

        $this->db->select('IFNULL(p.status,"") as status', FALSE);
        $this->db->select('IFNULL(p.thumb,"") as thumb', FALSE);
        $this->db->select('IFNULL(p.location,"") as location', FALSE);
        
        $this->db->select("if((p.posttype = 2), p.title,  SUBSTRING(p.message, 1, 75)) AS title");
        $this->db->select("if(((Select count(id) from  posts_social_share where post_id =p.id and user_id=p.user_id) > 0), 1,  0) AS isshared");
        
        $this->db->from("events e");
        $this->db->join('event_posts ep', 'ep.event_id = e.id');
        $this->db->join('posts p', 'p.id = ep.post_id');
        $this->db->join('posts_details pd', 'pd.post_id = p.id', 'LEFT');
        $this->db->join('users u', 'u.id = p.user_id');
    

        $this->db->having('Distance <= 10', null, false);

        
        $this->db->where('p.post_level', 0);
        
        $this->db->group_by('p.id');

        $this->db->order_by('popularity', 'DESC');
        $this->db->order_by('p.date', 'DESC');
        $this->db->limit($page_size,$offset);
        $query = $this->db->get();
        $results = array();
        if($query->num_rows() > 0) {
            $results = $query->result_array();

            $url = "http://"; 
            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                $url = "https://";
            }
	        $url.= $_SERVER['HTTP_HOST'];  
            $profile_base_url =       $url."/".PROJECT_FOLDER_NAME."/storage/avatars/thumbs1/";
            $attachment_base_url =       $url."/".PROJECT_FOLDER_NAME."/storage/attachments/1/";
            foreach ($results as $key => $result) {
                $post_id = $result['postid'];
                $post_owner_id = $result['postuserid'];
                
                $result['isliked']      = $this->is_liked($user_id, $post_id);
                $result['isbuzzed']     = $this->is_buzzed($user_id, $post_id);
                $result['isshared']     = $this->is_profile_shared($user_id, $post_owner_id);
                $result['isfollwed']    = $this->is_followed($user_id, $post_owner_id);

                $result["profile_base_url"]     =       $profile_base_url;
                $result["attachment_base_url"]  =       $attachment_base_url;

                                    
                $result["event_detail"]     = $this->get_event_post($user_id, $post_id);
                unset($result['popularity']);
                unset($result['Distance']);
                $results[$key] = $result;
            }
        }
        
        return $results;   
    	
    }

    function get_event_post($user_id, $post_id) {
        $event_details = array();
        $this->db->select('event_id');
        $this->db->from('event_posts');
        $this->db->where('post_id', $post_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            $row = $query->row_array();
            $event_id = $row['event_id'];
            if($event_id) {
                $this->db->select('id as event_id,admin_id as user_id,address,location,event_name,start_date,start_time,end_date,end_time,status');
                $this->db->from('events');
                $this->db->where('id', $event_id);
                $this->db->limit(1);
                $query_event = $this->db->get();
                if($query_event->num_rows() > 0) {
                    $event_details = $query_event->row_array();

                    $event_details['event_status'] = '';
                    if($user_id) {
                        $this->db->select('event_status');
                        $this->db->from('post_userbox');
                        $this->db->where('user_id', $user_id);
                        $this->db->where('post_id', $post_id);
                        $this->db->limit(1);
                        $query_event_status = $this->db->get();
                        if($query_event_status->num_rows() > 0) {
                            $event_status_row = $query_event_status->row_array();
                            $event_details['event_status'] = $event_status_row['event_status'];
                        }
                    }

                    $this->db->select('*');
                    $this->db->from('posts_attachments');
                    $this->db->where('type', 'image');
                    $this->db->where('post_id', $post_id);
                    $query_event_images = $this->db->get();
                    $event_details['event_attachment'] = array();
                    if($query_event_images->num_rows() > 0) {
                        $event_details['event_attachment'] = $query_event_images->result_array();
                    }

                    $this->db->select('*');
                    $this->db->from('posts_attachments');
                    $this->db->where('type', 'file');
                    $this->db->where('post_id', $post_id);
                    $query_event_video = $this->db->get();
                    $event_details['video_attachment'] = array();
                    if($query_event_video->num_rows() > 0) {
                        $video_attachment = $query_event_video->result_array();

                        if(!empty($video_attachment)){
                            $videos = $video_attachment[0]['data'];
                            $unserialize = unserialize($videos);
                            $video_attachment_data = $unserialize->file_original;
                            $event_details['video_attachment'] = $video_attachment_data;
                        }

                    }

                    $this->db->select('count(id) as joincount');
                    $this->db->from('post_userbox');
                    $this->db->where('event_status', 1);
                    $this->db->where('post_id', $post_id);
                    $query_event_join = $this->db->get();
                    $event_details['joincount'] = 0;
                    if($query_event_join->num_rows() > 0) {
                        $event_join = $query_event_join->row_array();
                        $event_details['joincount'] = $event_join['joincount'];
                    }
                }
            }
        }
        return $event_details;                    
    }

    function get_poll_post($user_id, $post_id) {
        $poll_details = array();
        $this->db->select('poll_id, poll_question');
        $this->db->from('polls');
        $this->db->where('posts_id', $post_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            $row = $query->row_array();
            $poll_id = $row['poll_id'];
            if($poll_id) {
                $poll_details['poll_id'] = $poll_id;
                $poll_details['poll_question'] = $row['poll_question']; 

                $this->db->select('count(id) as joincount');
                $this->db->from('post_poll_votes');
                $this->db->where('POLL_ID', $poll_id);
                $query_poll_join = $this->db->get();
                $poll_details['total_vote'] = 0;
                if($query_poll_join->num_rows() > 0) {
                    $poll_join = $query_poll_join->row_array();
                    $poll_details['total_vote'] = $poll_join['joincount'];
                }

                $poll_details['is_vote'] = 0;
                if($user_id) {
                    $this->db->select('ANSWER_ID');
                    $this->db->from('post_poll_votes');
                    $this->db->where('POLL_ID', $poll_id);
                    $this->db->where('VOTER_USER_ID', $user_id);
                    $this->db->limit(1);
                    $query_poll_vote = $this->db->get();                    
                    if($query_poll_vote->num_rows() > 0) {
                        $poll_vote = $query_poll_vote->row_array();
                        $poll_details['is_vote'] = $poll_vote['ANSWER_ID'];
                        if($poll_vote['ANSWER_ID'] == null){
                            $poll_details['is_vote'] = 0;
                        }
                    }
                }


                $this->db->select('*');
                $this->db->from('polls_answers');
                $this->db->where('poll_id', $poll_id);
                $query_polls_answers = $this->db->get();
                $poll_value = array();
                if($query_polls_answers->num_rows() > 0) {
                    $polls_answers = $query_polls_answers->result_array();
                    foreach($polls_answers as $polls_answer) {
                        $this->db->select('count(id) as pollvote');
                        $this->db->from('post_poll_votes');
                        $this->db->where('ANSWER_ID', $polls_answer['poll_answer_id']);
                        $query_poll_vote = $this->db->get();
                        $polls_answer['votes'] = 0;
                        if($query_poll_vote->num_rows() > 0) {
                            $poll_vote = $query_poll_vote->row_array();
                            $polls_answer['votes'] = $poll_vote['pollvote'];
                        }
                        $poll_value[] = $polls_answer;
                    }                    
                }
                $poll_details['poll_option'] = $poll_value;

                $this->db->select('*');
                $this->db->from('posts_attachments');
                $this->db->where('type', 'image');
                $this->db->where('post_id', $post_id);
                $this->db->limit(1);
                $query_poll_images = $this->db->get();
                $poll_details['poll_attachment'] = array();
                if($query_poll_images->num_rows() > 0) {
                    $poll_details['poll_attachment'] = $query_poll_images->result_array();
                }

            }
        }
        return $poll_details;
    }

    function is_liked($user_id, $post_id) {
        if($user_id) {
            $this->db->select('id');
            $this->db->from('post_likes');
            $this->db->where('user_id', $user_id);
            $this->db->where('post_id', $post_id);
            $this->db->limit(1);
            $query = $this->db->get();
            if($query->num_rows() > 0) {
                return 1;
            }
        }
        return 0;
    }

    function get_liked_count($post_id) {        
        $this->db->select('COUNT(id) as cnt');
        $this->db->from('post_likes');
        $this->db->where('post_id', $post_id);
        $result = $this->db->get();
        $count_data=$result->row_array();
        return $count_data['cnt'];
    }

    function get_comment_count($post_id) {        
        $this->db->select('COUNT(id) as cnt');
        $this->db->from('posts_comments');
        $this->db->where('post_id', $post_id);
        $result = $this->db->get();
        $count_data=$result->row_array();
        return $count_data['cnt'];
    }

    function get_reshares_count($post_id) {        
        $this->db->select('COUNT(id) as cnt');
        $this->db->from('post_reshares');
        $this->db->where('post_id', $post_id);
        $result = $this->db->get();
        $count_data=$result->row_array();
        return $count_data['cnt'];
    }

    function get_view_count($post_id) {        
        $this->db->select('cnt');
        $this->db->from('post_views_list');
        $this->db->where('post_id', $post_id);
        $result = $this->db->get();
        $count_data=$result->row_array();
        return $count_data['cnt'];
    }

    function get_share_count($post_id) {        
        $this->db->select('COUNT(id) as cnt');
        $this->db->from('posts_social_share');
        $this->db->where('post_id', $post_id);
        $result = $this->db->get();
        $count_data=$result->row_array();
        return $count_data['cnt'];
    }

    function is_buzzed($user_id, $post_id) {
        if($user_id) {
            $this->db->select('id');
            $this->db->from('post_reshares');
            $this->db->where('user_id', $user_id);
            $this->db->where('post_id', $post_id);
            $this->db->limit(1);
            $query = $this->db->get();
            if($query->num_rows() > 0) {
                return 1;
            }
        }
        return 0;
    }

    function is_profile_shared($user_id, $post_owner_id) {
        if($user_id) {
            $this->db->select('id');
            $this->db->from('profile_share');
            $this->db->where('who', $user_id);
            $this->db->where('whom', $post_owner_id);
            $this->db->limit(1);
            $query = $this->db->get();
            if($query->num_rows() > 0) {
                return 1;
            }
        }
        return 0;
    }

    function is_followed($user_id, $post_owner_id) {
        if($user_id) {
            $this->db->select('id');
            $this->db->from('users_followed');
            $this->db->where('who', $user_id);
            $this->db->where('whom', $post_owner_id);
            $this->db->limit(1);
            $query = $this->db->get();
            if($query->num_rows() > 0) {
                return 1;
            }
        }    
        return 0;
    }
}