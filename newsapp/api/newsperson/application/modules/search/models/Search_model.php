<?php

class Search_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    /** 
     * get the list of all user
     * @return array
     */
    public function users($post_value) {
       
        $search_key = isset($post_value['search_key']) ? $post_value['search_key'] : '';

        $this->db->select("id as user_id, email,username, fullname, phone_no, is_reporter, is_bank_detail_validated, reporter_status");
        $this->db->select('IFNULL(avatar,"") as avatar', FALSE);
         $this->db->select('IFNULL(reporter_reg_date,"") as reporter_reg_date', FALSE);
        
        $this->db->from("users");

        if (!empty($search_key)) {
            $this->db->where("(email like '%" . $search_key . "%' or username like '%" . $search_key . "%' or fullname like '%" . $search_key . "%')");
        }
        $this->db->where("active", 1);
        $this->db->order_by("id","ASC");
        $this->db->limit(20);

        $query = $this->db->get();
        $results = array();
        if($query->num_rows() > 0) {
            $results = $query->result_array();   
            $this->load->model(array('user/User_model'));
            foreach ($results as $key => $result) {
                $result['coverage_category'] = $this->User_model->get_reporter_coverage_category($result['user_id']);
                $result['coverage_location'] = $this->User_model->get_reporter_coverage_location($result['user_id']);
                $result['coverage_language'] = $this->User_model->get_reporter_coverage_language($result['user_id']);

                $results[$key] = $result;
            }
            
        }
        return $results;   
    }  

    /** 
     * get the list of coverage category
     * @return array
     */
    public function coverage_category($post_value) {
        $search_key = isset($post_value['search_key']) ? $post_value['search_key'] : '';

        $this->db->select("cat_id as id, cat_name as name");        
        $this->db->from("categeory_master");

        if (!empty($search_key)) {
            $this->db->where("(cat_name like '%" . $search_key . "%')");
        }

        $this->db->order_by("cat_name","ASC");
        $this->db->limit(20);

        $query = $this->db->get();
        $results = array();
        if($query->num_rows() > 0) {
            $results = $query->result_array(); 
        }
        return $results;   
    } 

    /** 
     * get the list of coverage location
     * @return array
     */
    public function coverage_location($post_value) {
        $search_key = isset($post_value['search_key']) ? $post_value['search_key'] : '';

        $this->db->select("id, CONCAT(location_district,' ',location_state,' ',location_country) AS location");        
        $this->db->select("location_district AS district, location_state AS state, location_country AS country");
        $this->db->from("sb_location_master");

        if(!empty($search_key)) {
            $search_key = $this->db->escape_like_str($search_key);
            $this->db->where('(location_district LIKE "%'.$search_key.'%" OR location_state LIKE "%'.$search_key.'%" OR location_country LIKE "%'.$search_key.'%" OR CONCAT(location_district," ",location_state," ",location_country) LIKE "%'.$search_key.'%")',NULL,FALSE);
        }
       
        $this->db->order_by("location","ASC");
        $this->db->limit(30);

        $query = $this->db->get();
        $results = array();
        if($query->num_rows() > 0) {
            $results = $query->result_array(); 
        }
        return $results;   
    } 
    /** 
     * get the list of coverage language 
     * @return array
     */
    public function coverage_language($post_value) {
        $search_key = isset($post_value['search_key']) ? $post_value['search_key'] : '';

        $this->db->select("id, language_name as name");        
        $this->db->from("sb_languages");

        if (!empty($search_key)) {
            $this->db->where("(language_name like '%" . $search_key . "%')");
        }

        $this->db->order_by("language_name","ASC");
        $this->db->limit(20);

        $query = $this->db->get();
        $results = array();
        if($query->num_rows() > 0) {
            $results = $query->result_array(); 
        }
        return $results;   
    } 
    
    /** 
     * get the list of trending tags
     * @return array
     */
    public function trending_tags($post_value) {
        $search_key = isset($post_value['search_key']) ? $post_value['search_key'] : '';

        $this->db->select("id, tag_name as name");        
        $this->db->from("post_tags");

        if (!empty($search_key)) {
            $this->db->where("(tag_name like '%" . $search_key . "%')");
        }
        $this->db->where("tag_name !=",'');
        $this->db->group_by('tag_name'); 

        $this->db->order_by("id","DESC");
        $this->db->limit(20);

        $query = $this->db->get();
        $results = array();
        if($query->num_rows() > 0) {
            $results = $query->result_array(); 
        }
        return $results;   
    } 
    /** 
     * get the list of trending tags
     * @return array
     */
    public function locationtrending_tags($post_value) {
        $search_key = isset($post_value['search_key']) ? $post_value['search_key'] : 'india';
        
        $this->db->select("id, tag_name as name");        
        $this->db->from("post_tags");
        $this->db->where("user_location =",$search_key);
        $this->db->group_by('tag_name'); 

        $this->db->order_by("id","DESC");
        $this->db->limit(10);

        $query = $this->db->get();
        $results = array();
        if($query->num_rows() > 0) {
            $results = $query->result_array(); 
        }
        return $results;   
    } 


    /** 
     * get the list of all user
     * @return array
     */
    public function profile($data) {
       
        $search_key = isset($data['search_key']) ? $data['search_key'] : '';
        $page_no    = isset($data['page_no']) ? $data['page_no'] : 1;
        $page_size  = isset($data['page_size']) ? $data['page_size'] : 5;
        $offset     = pagination_offset($page_no, $page_size);

        $this->db->select("id as user_id, username, fullname, num_followers");
        $this->db->select('IFNULL(avatar,"") as avatar', FALSE);
        
        $this->db->from("users");

        if (!empty($search_key)) {
            $this->db->where("(email like '%" . $search_key . "%' or username like '%" . $search_key . "%' or fullname like '%" . $search_key . "%')");
        }
        $this->db->where("active", 1);
        $this->db->order_by("fullname","ASC");
        $this->db->limit($page_size,$offset);

        $query = $this->db->get();
        $results = array();
        if($query->num_rows() > 0) {
            $results = $query->result_array();
        }
        return $results;   
    } 

    /** 
     * get the list of all user
     * @return array
     */
    public function buzzes($data) {
       
        $search_key = isset($data['search_key']) ? $data['search_key'] : '';
        $tag_name     = isset($data['tag_name']) ? $data['tag_name'] : '';
        $user_id     = isset($data['user_id']) ? $data['user_id'] : 0;
        $post_type  = isset($data['post_type']) ? $data['post_type'] : array();
        $post_ids = array();
        if(!empty($tag_name)) {
            $search_key = '';
            $post_ids = $this->get_tag_post_ids($tag_name);
            if(empty($post_ids)) {
                return array();
            }
        }

        $page_no    = isset($data['page_no']) ? $data['page_no'] : 1;
        $page_size  = isset($data['page_size']) ? $data['page_size'] : 5;
        $offset     = pagination_offset($page_no, $page_size);

        $this->db->select("u.username AS postusername");
        $this->db->select('IFNULL(u.avatar,"") as postuserimage', FALSE);
        $this->db->select("p.id AS postid, p.user_id AS postuserid, p.posttype, p.message, p.date");

        $this->db->select("SUM(IFNULL(pd.likes,0)+IFNULL(pd.comments,0)+IFNULL(pd.reshares,0)+IFNULL(pd.shares,0)) as popularity");
        $this->db->select('IFNULL(p.thumb,"") as thumb', FALSE);
        $this->db->select('IFNULL(p.title,"") as title', FALSE);
       // $this->db->select('IFNULL(GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data),"") as attachements');
        $this->db->select("pa.data");
        $this->db->from("posts p");
        $this->db->join('users u', 'u.id = p.user_id');
        $this->db->join('posts_details pd', 'pd.post_id = p.id', 'LEFT');
        $this->db->join('posts_attachments pa', 'pa.post_id=p.id', 'LEFT');

        $this->db->join('polls pl', 'pl.posts_id=p.id', 'LEFT');
        $this->db->join('polls_answers pla', 'pla.poll_id=pl.poll_id', 'LEFT');

        $this->db->join('event_posts ep', 'ep.post_id=p.id', 'LEFT');
        $this->db->join('events e', 'e.id=ep.event_id', 'LEFT');
        
        if($post_ids) {
            $this->db->where_in('p.id', $post_ids);
        } else if (!empty($search_key)) {
            $this->db->where("(
                p.message like '%" . $search_key . "%' or p.title like '%" . $search_key . "%' 
                or pl.poll_question like '%" . $search_key . "%' or pla.answer like '%" . $search_key . "%' 
                or e.event_name like '%" . $search_key . "%' or e.event_description like '%" . $search_key . "%'
                )");
        }

        if(!empty($post_type)) {
            $this->db->where_in('p.posttype', $post_type);
        }

        $this->db->limit($page_size,$offset);
        $this->db->group_by('p.id');
        $query = $this->db->get();
        $results = array();
        if($query->num_rows() > 0) {
            $this->load->model(array('post/Post_model'));  
            $results = $query->result_array();
            foreach ($results as $key => $result) {
                $post_id = $result['postid'];

                $result["title"] =  strip_tags($result["title"]); 

                $result["attach_data"] = NULL;
                if(!empty($result["data"])) {
                    $att_data = (array)unserialize($result["data"]);                    
                    $file_original = isset($att_data['file_original']) ? $att_data['file_original'] : '';
                    if(!empty($file_original)) {
                        $result["attach_data"]["file_original"] = $file_original;
                    }
                }
                unset($result["data"]);

              /*  if(empty($result["attachements"])) {
                    $result["attachements"] = '';
                }
                */
                if ($result["posttype"] == 4) {                    
                    $result["event_detail"]     = $this->Post_model->get_event_post($user_id, $post_id);
                } else if ($result["posttype"] == 5) {                    
                    $poll_details     = $this->Post_model->get_poll_post($user_id, $post_id);
                    $result = array_merge($result, $poll_details);
                }
                if (!empty($search_key)) {
                    $message = $result['message'];
                    $pos = stripos($message, $search_key);
                    $start_index = $pos - 100;
                    $end_index = $pos + 100;
                    if($start_index < 0) {
                        $start_index = 0;
                    }
                    $result['message'] = substr($message, $start_index, $end_index);
                }

                unset($result['popularity']);
                $results[$key] = $result;
            }
        }
        return $results;   
    }

    function get_tag_post_ids($tag_name) {
        $this->db->select("GROUP_CONCAT(DISTINCT post_id) as post_ids");        
        $this->db->from("post_tags");
        $this->db->where("LOWER(tag_name)", strtolower($tag_name));
        $this->db->order_by("post_id","DESC");
        $query = $this->db->get();
        $post_ids = array();
        if($query->num_rows() > 0) {
            $row = $query->row_array(); 
            $post_ids = explode(',',$row['post_ids']);
        }
        return $post_ids;  
    }

    /** 
     * used to search all handles
     * @return array
     */
    public function handle($data) {
        $search_key = isset($data['search_key']) ? $data['search_key'] : '';
        $page_no    = isset($data['page_no']) ? $data['page_no'] : 1;
        $page_size  = isset($data['page_size']) ? $data['page_size'] : 20;
        $offset     = pagination_offset($page_no, $page_size);

        $this->db->select("id as user_id, username");
        $this->db->select('IFNULL(avatar,"") as avatar', FALSE);        
        $this->db->from("users");

        if (!empty($search_key)) {
            $this->db->where("(username like '%" . $search_key . "%')");
        }
        $this->db->where("active", 1);
        $this->db->order_by("id","ASC");
        $this->db->limit($page_size,$offset);

        $query = $this->db->get();
        $results = (object)[];
        if($query->num_rows() > 0) {
            $results = $query->result_array(); 
        }
        return $results;   
    } 
    
}