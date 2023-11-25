<?php

/**
 * Used for news person registration
 * @package     Register
 * @category    Register
 */
class RegisterWebModel extends My_Model {

    public function __construct() {
        parent::__construct();
    }

    function save_reporter_data($data) {
        
    	$is_reporter = $data['is_reporter'];
    	$user_id = $data['user_id'];
    	$update_data = array();
    	$update_data['reporter_status'] = $data['status'];
    	if(empty($is_reporter)) {    		
    		$update_data['is_reporter'] = 1;
    		$update_data['reporter_reg_date'] = $data['registration_date'];
    	}

        $this->db->where('id', $user_id);
        $this->db->update('users',$update_data);
        
        if($data['reporter_current_status'] != $data['status']) {
            $this->save_reporter_status_history($data);            
        } 
    }

    function save_reporter_status_history($data) {
        $this->db->select('history_id');
        $this->db->from('sb_reporter_status_history');
        $this->db->where('user_id', $data['user_id']);
        $this->db->order_by('history_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $today = date("Y-m-d");
        $insert_data = array();
        $insert_data['start_date'] = $today;    
        if($query->num_rows() > 0) {
            $row = $query->row_array();
            $this->db->where('history_id', $row['history_id']);
            $this->db->set('end_date', $today);
            $this->db->update('sb_reporter_status_history');
        } else {
            $insert_data['start_date'] = $data['registration_date'];    
        }
                
        $insert_data['user_id'] = $data['user_id'];
        $insert_data['reporter_status'] = $data['status'];
        $this->db->insert('sb_reporter_status_history',$insert_data);
        
    }

    function save_reporter_coverage_category($coverage_category_ids, $user_id) {
        $coverage_categories = array();
        $date = time();
        foreach ($coverage_category_ids as $coverage_category_id) {
            $coverage_category = array();
            $coverage_category['user_id'] = $user_id;
            $coverage_category['category_id'] = $coverage_category_id;
            $coverage_category['added_date'] = $date;
            $coverage_categories[] = $coverage_category;
        }
        if (!empty($coverage_categories)) {   
            
           
            $this->db->insert_on_duplicate_update_batch('sb_reporter_coverage_category', $coverage_categories);
           
            
            
            $this->db->where_not_in('category_id', $coverage_category_ids);
            $this->db->where('user_id', $user_id);
            $this->db->delete('sb_reporter_coverage_category');
            
            
        }
    }

    function save_reporter_coverage_location($coverage_location_ids, $user_id) {
        $coverage_locations = array();
        $date =time();
        foreach ($coverage_location_ids as $coverage_location_id) {
            $coverage_location = array();
            $coverage_location['user_id'] = $user_id;
            $coverage_location['location_id'] = $coverage_location_id;
            $coverage_location['added_date'] = $date;
            $coverage_locations[] = $coverage_location;
        }
        if (!empty($coverage_locations)) {            
            $this->db->insert_on_duplicate_update_batch('sb_reporter_coverage_location', $coverage_locations);
            
            $this->db->where_not_in('location_id', $coverage_location_ids);
            $this->db->where('user_id', $user_id);
            $this->db->delete('sb_reporter_coverage_location');
        }

    }

    function save_reporter_coverage_language($coverage_language_ids, $user_id) {
        $coverage_languages = array();
        $date =time();
        foreach ($coverage_language_ids as $coverage_language_id) {
            $coverage_language = array();
            $coverage_language['user_id'] = $user_id;
            $coverage_language['language_id'] = $coverage_language_id;
            $coverage_language['added_date'] = $date;
            $coverage_languages[] = $coverage_language;
        }
        if (!empty($coverage_languages)) {            
            $this->db->insert_on_duplicate_update_batch('sb_reporter_coverage_language', $coverage_languages);
            
            $this->db->where_not_in('language_id', $coverage_language_ids);
            $this->db->where('user_id', $user_id);
            $this->db->delete('sb_reporter_coverage_language');
        }
    }

}
