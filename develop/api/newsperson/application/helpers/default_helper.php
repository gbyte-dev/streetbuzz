<?php
if (!defined('BASEPATH')) { exit('No direct script access allowed');}
/**
 *
 * @param   date_format
 * @return  Current UTC Date
 */
if (!function_exists('current_date_time')) {

    function get_current_date($format, $time_diff = 0, $plus = 0, $time = 0)
    {
        $CI = &get_instance();
        $CI->load->helper('date');
        $now = now();
        if ($time) {
            $now = $time;
        }
        if ($time_diff) {
            if ($plus) {
                $now = $now + (24 * 60 * 60 * $time_diff);
            } else {
                $now = $now - (24 * 60 * 60 * $time_diff);
            }
        }
        return mdate($format, $now);
    }
}

/**
 * pagination offset.
 * @param int $page_no
 * @param int $lmiit
 * @return int
 */
if (!function_exists('pagination_offset')) {
    function pagination_offset($page_no, $lmiit) {
        if(empty($page_no)) 
        {
            $page_no = 1;
        }
        return ($page_no-1)*$lmiit;
    }
}

/**
 * check_array_key
 * @return string
 */
if (!function_exists('check_array_key')) {

    function check_array_key($array, $key, $default = "")
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }
}

/**
 * Create GUID
 * @return string
 */
if (!function_exists('get_guid')) {

    function get_guid()
    {
        if (function_exists('com_create_guid')) {
            return strtolower(com_create_guid());
        } else {
            mt_srand((float) microtime() * 10000); //optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45); // "-"
            $uuid = substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12);
            return strtolower($uuid);
        }
    }
}


/**
 * [save_location check and insert location]
 * @param  [array] $location [Location data]
 * @return [array]           [array]
 */
if ( ! function_exists('save_location')) {    
    function save_location($data) {
        $CI =& get_instance();
        $CI->db->select('location_id');
        $CI->db->from('sb_locations L');        
        $CI->db->where('unique_id',$data['unique_id']);
        $CI->db->limit('1');
        $query = $CI->db->get();
        $location = $query->row_array();
        
        if (!empty($location)) {
            $CI->db->insert('sb_locations', $data);
            $location_id = $CI->db->insert_id();
        } else {
            $location_id = $location['location_id'];
        }
        return $location_id;
    }  
}

function get_user_ip_address(){
    if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else if(isset($_SERVER['HTTP_X_FORWARDED']) && !empty($_SERVER['HTTP_X_FORWARDED'])){
        $ip = $_SERVER['HTTP_X_FORWARDED'];
    }else if(isset($_SERVER['HTTP_FORWARDED_FOR']) && !empty($_SERVER['HTTP_FORWARDED_FOR'])){
        $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    }else if(isset($_SERVER['HTTP_FORWARDED']) && !empty($_SERVER['HTTP_FORWARDED'])){
        $ip = $_SERVER['HTTP_FORWARDED'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
