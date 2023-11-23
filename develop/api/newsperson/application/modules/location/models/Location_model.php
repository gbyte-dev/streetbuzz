<?php

class Location_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    
    /** 
     * get the list of all location
     * @return array
     */
    public function locations($data) {
        $search_key = isset($data['search_key']) ? $data['search_key'] : '';
        $page_no    = isset($data['page_no']) ? $data['page_no'] : 1;
        $page_size  = isset($data['page_size']) ? $data['page_size'] : 20;
        $offset     = pagination_offset($page_no, $page_size);

        $this->db->select("id, CONCAT(location_district,' ',location_state,' ',location_country) AS location");        
        $this->db->select("location_district AS district, location_state AS state, location_country AS country");
        $this->db->from("sb_location_master");

        if(!empty($search_key)) {
            $search_key = $this->db->escape_like_str($search_key);
            $this->db->where('(location_district LIKE "%'.$search_key.'%" OR location_state LIKE "%'.$search_key.'%" OR location_country LIKE "%'.$search_key.'%" OR CONCAT(location_district," ",location_state," ",location_country) LIKE "%'.$search_key.'%")',NULL,FALSE);
        }
       
        $this->db->order_by("location","ASC");
        $this->db->limit($page_size,$offset);

        $query = $this->db->get();
        $results = array();
        if($query->num_rows() > 0) {
            $results = $query->result_array(); 
        }
        return $results;   
    }     

    /**
     * used to save location handle
     */
    function save_handle($location_id, $user_handles, $state_handles, $capital_handles, $national_handles, $international_handles) {
        $date = time();
        $insert_data = array();
        $insert_data['added_date'] = $date; 
        $insert_data['modified_date'] = $date; 
        $insert_data['location_id'] = $location_id; 
        $insert_data['user_handles'] = trim(implode(',', $user_handles),',');
        $insert_data['state_handles'] = trim(implode(',', $state_handles),',');
        $insert_data['capital_handles'] = trim(implode(',', $capital_handles),',');
       // $insert_data['country_handles'] = trim(implode(',', $country_handles),',');
        $insert_data['national_handles'] = trim(implode(',', $national_handles),',');
        $insert_data['international_handles'] = trim(implode(',', $international_handles),',');
        $this->db->insert('sb_location_handle',$insert_data);
    }

    /**
     * used to save location handle
     */
    function update_handle($handle_id, $user_handles, $state_handles, $capital_handles, $national_handles, $international_handles) {
        $date = time();
        $insert_data = array();
        $insert_data['modified_date'] = $date;
        $insert_data['user_handles'] = trim(implode(',', $user_handles),',');
        $insert_data['state_handles'] = trim(implode(',', $state_handles),',');
        $insert_data['capital_handles'] = trim(implode(',', $capital_handles),',');        
        $insert_data['national_handles'] = trim(implode(',', $national_handles),',');
        $insert_data['international_handles'] = trim(implode(',', $international_handles),',');
        $this->db->where('handle_id', $handle_id);
        $this->db->update('sb_location_handle',$insert_data);
    }

     /** 
     * used to get all location handles
     * @return array
     */
    public function handles($data) {
        $page_no    = isset($data['page_no']) ? $data['page_no'] : 1;
        $page_size  = isset($data['page_size']) ? $data['page_size'] : 20;
        $offset     = pagination_offset($page_no, $page_size);

        $location_id = check_array_key($data, 'location_id');
        $user_handle_id = check_array_key($data, 'user_handle_id');
        $state_handle_id = check_array_key($data, 'state_handle_id');
        $capital_handle_id = check_array_key($data, 'capital_handle_id');       
        $national_handle_id = check_array_key($data, 'national_handle_id');
        $international_handle_id = check_array_key($data, 'international_handle_id');
        $or_condition = 0;

        $this->db->select("CONCAT(l.location_district,' ',l.location_state,' ',l.location_country) AS location");        
        $this->db->select("l.location_district AS district, l.location_state AS state, l.location_country AS country");        
        $this->db->select("lh.handle_id, lh.user_handles, lh.state_handles, lh.capital_handles, lh.national_handles, lh.international_handles"); 

        $this->db->from("sb_location_handle lh");
        $this->db->join("sb_location_master l", "l.id = lh.location_id");
        
        if($location_id || $user_handle_id || $state_handle_id || $capital_handle_id || $national_handle_id || $international_handle_id) {
            $this->db->group_start();        
            if(!empty($location_id)) {
                $or_condition = 1;
                $this->db->where('lh.location_id', $location_id, FALSE);
            }
            if(!empty($user_handle_id)) {
                if($or_condition == 1) {
                    $this->db->or_where(" FIND_IN_SET('".$user_handle_id."', lh.user_handles) > ",0);
                } else {
                    $this->db->where(" FIND_IN_SET('".$user_handle_id."', lh.user_handles) > ",0);
                }            
                $or_condition = 1;
            }
            if(!empty($state_handle_id)) {
                if($or_condition == 1) {
                    $this->db->or_where(" FIND_IN_SET('".$state_handle_id."', lh.state_handles) > ",0);
                } else {
                    $this->db->where(" FIND_IN_SET('".$state_handle_id."', lh.state_handles) > ",0);
                }            
                $or_condition = 1;
            }
            if(!empty($capital_handle_id)) {
                if($or_condition == 1) {
                    $this->db->or_where(" FIND_IN_SET('".$capital_handle_id."', lh.capital_handles) > ",0);
                } else {
                    $this->db->where(" FIND_IN_SET('".$capital_handle_id."', lh.capital_handles) > ",0);
                }            
                $or_condition = 1;
            }
            
            if(!empty($national_handle_id)) {
                if($or_condition == 1) {
                    $this->db->or_where(" FIND_IN_SET('".$national_handle_id."', lh.national_handles) > ",0);
                } else {
                    $this->db->where(" FIND_IN_SET('".$national_handle_id."', lh.national_handles) > ",0);
                }            
                $or_condition = 1;
            }
            if(!empty($international_handle_id)) {
                if($or_condition == 1) {
                    $this->db->or_where(" FIND_IN_SET('".$international_handle_id."', lh.international_handles) > ",0);
                } else {
                    $this->db->where(" FIND_IN_SET('".$international_handle_id."', lh.international_handles) > ",0);
                }            
                $or_condition = 1;
            }
            $this->db->group_end();
        }

        $this->db->order_by("lh.handle_id","DESC");
        $this->db->limit($page_size,$offset);

        $query = $this->db->get();
        $results = (object)[];
        if($query->num_rows() > 0) {
            $results = $query->result_array();
            foreach ($results as $key => $result) {
                $user_handles = $result['user_handles'];
                $state_handles = $result['state_handles'];
                $capital_handles = $result['capital_handles'];
                $national_handles = $result['national_handles'];
                $international_handles = $result['international_handles'];


                $user_handles = explode(',',$user_handles);
                $result['user_handles'] = $this->get_handle_details($user_handles);

                $state_handles = explode(',',$state_handles);
                $result['state_handles'] = $this->get_handle_details($state_handles);

                $capital_handles = explode(',',$capital_handles);
                $result['capital_handles'] = $this->get_handle_details($capital_handles);

                $national_handles = explode(',',$national_handles);
                $result['national_handles'] = $this->get_handle_details($national_handles);

                $international_handles = explode(',',$international_handles);
                $result['international_handles'] = $this->get_handle_details($international_handles);

                $results[$key] = $result;
            }
        }
        return $results;   
    } 

    function get_handle_details($handles_ids) {
        $results = [];
        if(!empty($handles_ids)) {
            $this->db->select("id as user_id, username");     
            $this->db->from("users");          
            $this->db->where_in("id", $handles_ids);
            $this->db->order_by("id","ASC");
            $query = $this->db->get();
            if($query->num_rows() > 0) {
                $results = $query->result_array(); 
            }        
        }
        return $results; 
    }

    function get_location_handles($location_id) {
        $this->db->select("lh.user_handles, lh.state_handles, lh.capital_handles, lh.national_handles, lh.international_handles"); 
        $this->db->from("sb_location_handle lh");
        $this->db->where('lh.location_id', $location_id, FALSE);
        $this->db->limit(1);
        $query = $this->db->get();
        $handles = array();
        if($query->num_rows() > 0) {
            $result = $query->row_array(); 

            $user_handles = $result['user_handles'];
            $state_handles = $result['state_handles'];
            $capital_handles = $result['capital_handles'];
            $national_handles = $result['national_handles'];
            $international_handles = $result['international_handles'];

            $user_handles = explode(',',$user_handles); 
            $state_handles = explode(',',$state_handles); 
            $capital_handles = explode(',',$capital_handles);
            $national_handles = explode(',',$national_handles);
            $international_handles = explode(',',$international_handles);
                
            $handles = array_unique(array_merge($user_handles,$state_handles,$capital_handles,$national_handles,$international_handles));
        } 
        return $handles;  
    }

    /**
     * Used to check location handle
     */
    function generate_timeline($user_id, $location_id) {
        $handles = $this->get_location_handles($location_id);
        if(!empty($handles)) {
            $this->load->model(array('user/Follow_model'));
            foreach($handles as $key => $value) {
                if(!empty($value) && $user_id != $value) {
                    //echo "<br>".$user_id.' => '.$value;
                    $this->Follow_model->auto_follow($user_id, $value);
                    $this->assign_timeline_post($user_id, $value);
                }
            }
        }        
    }

    function assign_timeline_post($user_id, $owner_id) { 
        $start_date = date('Y-m-d', strtotime('-'.TIME_LINE_DAYS.' days', strtotime(date("Y-m-d")))); 
        $start_date_time_stamp = strtotime($start_date);
        $this->db->select('p.id as post_id');
        $this->db->select($user_id.' as user_id', false);
        $this->db->from("posts p");
        $this->db->where('p.post_level', 0);
        $this->db->where('p.user_id', $owner_id);
        $this->db->where('p.date >=', $start_date_time_stamp);
        $this->db->order_by('p.id');
        $query = $this->db->get();
        $results = array();
        if($query->num_rows() > 0) {
            $results = $query->result_array();
            $results = array_chunk($results, 300); 
            foreach($results as $result) {
                $this->db->insert_on_duplicate_update_batch('post_userbox', $result);
            }
        }
    }
    
    
}