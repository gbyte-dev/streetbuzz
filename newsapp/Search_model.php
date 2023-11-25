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
}