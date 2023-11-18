<?php

/**
* Used for news person details
* @package     User
* @category    User
*/
class User_model extends My_Model {

public function __construct() {
parent::__construct();
}

/**
* Get reporter coverage category
*/
function get_reporter_coverage_category($user_id) {
$this->db->select("c.cat_id as id, c.cat_name as name");   
$this->db->from("sb_reporter_coverage_category rc");     
$this->db->join('categeory_master c', 'c.cat_id=rc.category_id');
$this->db->where('rc.user_id', $user_id);
$this->db->order_by("c.cat_name","ASC");
$query = $this->db->get();
$results = array();
if($query->num_rows() > 0) {
$results = $query->result_array(); 
}
return $results;
}

/**
* Get reporter coverage location 
*/
function get_reporter_coverage_location($user_id) {
$this->db->select("l.id, CONCAT(l.location_district,' ',l.location_state,' ',l.location_country) AS location");          
$this->db->from("sb_reporter_coverage_location rl");     
$this->db->join('sb_location_master l', 'l.id=rl.location_id');
$this->db->where('rl.user_id', $user_id);
$this->db->order_by("l.location","ASC");
$query = $this->db->get();
$results = array();
if($query->num_rows() > 0) {
$results = $query->result_array(); 
}
return $results;
}

/**
* Get reporter coverage language 
*/
function get_reporter_coverage_language($user_id) {
$this->db->select("l.id, l.language_name as name");          
$this->db->from("sb_reporter_coverage_language rl");     
$this->db->join('sb_languages l', 'l.id=rl.language_id');
$this->db->where('rl.user_id', $user_id);
$this->db->order_by("l.language_name","ASC");
$query = $this->db->get();
$results = array();
if($query->num_rows() > 0) {
$results = $query->result_array(); 
}
return $results;        
}

/**
* Get user viewership earning 
*/
function viewership_earning($post_value) {
$page_no    = isset($post_value['page_no']) ? $post_value['page_no'] : 1;
$page_size      = isset($post_value['page_size']) ? $post_value['page_size'] : 20;
$offset     = pagination_offset($page_no, $page_size);

$this->db->select('start_date, end_date, total_views, total_news, total_earning, total_payment, divisor, multiplier');
$this->db->select('IFNULL(payment_date,"") as payment_date', FALSE);
$this->db->from('sb_reporter_viewership');
$this->db->where('user_id', $post_value['user_id']);
$this->db->order_by("start_date","DESC");
$this->db->limit($page_size,$offset);
$query = $this->db->get();
$results = array();
if($query->num_rows() > 0) {
$results = $query->result_array(); 
}
return $results;
}

/**
* Get user total earning 
*/
function total_earning($user_id) {
$this->db->select_sum('total_earning');
$this->db->from('sb_reporter_viewership');
$this->db->where('user_id', $user_id);
$this->db->limit(1);
$query = $this->db->get();
$total_earning = 0;
if($query->num_rows() > 0) {
$row = $query->row_array();
if(!empty($row['total_earning'])) {
$total_earning = $row['total_earning'];
}                        
}
return $total_earning;
}

/**
* Get user total payment 
*/
function total_payment($user_id) {
$this->db->select_sum('total_earning');
$this->db->from('sb_reporter_viewership');
$this->db->where('user_id', $user_id);
$this->db->where('payment_date IS NOT NULL', null, false);
$this->db->limit(1);
$query = $this->db->get();
$total_earning = 0;
if($query->num_rows() > 0) {
$row = $query->row_array();
if(!empty($row['total_earning'])) {
$total_earning = $row['total_earning'];
}                        
}
return $total_earning;
}

/**
* Get reporter pending  payment 
*/
function get_reporter_pending_payment($post_value) {
$search_key = isset($post_value['search_key']) ? $post_value['search_key'] : '';
$month = isset($post_value['month']) ? $post_value['month'] : '';
$year = isset($post_value['year']) ? $post_value['year'] : date("Y");
if(empty($year)) {
$year = date("Y");
}
$coverage_location = isset($post_value['coverage_location']) ? $post_value['coverage_location'] : array();
$coverage_category = isset($post_value['coverage_category']) ? $post_value['coverage_category'] : array();
$this->db->select('u.fullname, u.username, srv.reporter_viewership_id, srv.start_date, srv.end_date, srv.total_views, srv.total_news, srv.total_earning');        
$this->db->from('sb_reporter_viewership srv');
$this->db->join('users u', 'u.id = srv.user_id');
$this->db->where('srv.payment_date IS NULL', null, false);
if (!empty($search_key)) {
$this->db->where("(u.username like '%" . $search_key . "%')");
}

if(!empty($coverage_location) && is_array($coverage_location)) {
$this->db->where("srv.user_id IN (SELECT user_id FROM sb_reporter_coverage_location WHERE location_id IN (" . implode(',', $coverage_location) . "))", null, false);
}

if(!empty($coverage_category) && is_array($coverage_category)) {
$this->db->where("srv.user_id IN (SELECT user_id FROM sb_reporter_coverage_category WHERE category_id IN (" . implode(',', $coverage_category) . "))", null, false);
}

if(!empty($year)) {
$this->db->where("year(srv.start_date)", $year);

if(!empty($month)) {
$this->db->where("month(srv.start_date)", $month);
}
}

$this->db->order_by("srv.start_date","ASC");
$query = $this->db->get();
$results = array();
if($query->num_rows() > 0) {
$results = $query->result_array(); 
}
return $results;

}

/**
* Used to update payment status
*/
function update_payment_status($post_value) {
$date = date("Y-m-d");
$this->db->set('payment_date', $date);
$this->db->where('reporter_viewership_id', $post_value['reporter_viewership_id']);
$this->db->where('payment_date IS NULL', null, false);
$this->db->update('sb_reporter_viewership');
}


function viewership_details($post_value) {
$day_filter = isset($post_value['day_filter']) ? $post_value['day_filter'] : 1;
$current_date = date("Y-m-d",strtotime("-1 days"));
$previous_date = date("Y-m-d",strtotime("-2 days"));
$current_date_end = '';
if($day_filter==2) {
$current_date = date("Y-m-d",strtotime("-8 days"));
$previous_date = date("Y-m-d",strtotime("-16 days"));
}

if($day_filter==3) {
$current_date = date("Y-m-d",strtotime("-29 days"));
$previous_date = date("Y-m-d",strtotime("-58 days"));
}

if($day_filter==4) {
$current_date = date('Y-m-01', strtotime('1 months ago'));
$current_date_end = date('Y-m-t',strtotime('1 months ago'));

$previous_date = date('Y-m-01', strtotime('2 months ago'));          
}

if($day_filter==5) {
$current_date = date('Y-m-01', strtotime('3 months ago'));
$current_date_end = date('Y-m-t',strtotime('3 months ago'));

$previous_date = date('Y-m-01', strtotime('6 months ago'));           
}

$current_data  = array();
$previous_data = array();

$current_data['total_followers'] = 0; 


$this->db->select('sum(total_followers) as total_followers');
$this->db->from('sb_user_day_wise_followers');
$this->db->where('follower_count_date >=', $current_date);
if($current_date_end) {
$this->db->where('follower_count_date <=', $current_date_end);            
}
$this->db->where('user_id', $post_value['user_id']);
$query = $this->db->get();
$results = array();
if($query->num_rows() > 0) {
$row = $query->row_array(); 
if(!empty($row['total_followers'])) {
$current_data['total_followers'] = $row['total_followers'];
}            
}
$query->free_result();

$previous_data['total_followers'] = 0; 
$this->db->select('sum(total_followers) as total_followers');
$this->db->from('sb_user_day_wise_followers');
$this->db->where('follower_count_date >=', $previous_date);
$this->db->where('follower_count_date <', $current_date);
$this->db->where('user_id', $post_value['user_id']);
$query = $this->db->get();
$results = array();
if($query->num_rows() > 0) {
$row = $query->row_array(); 
$previous_data['total_followers'] = $current_data['total_followers'] - $row['total_followers'];
}
$query->free_result();

$current_data['total_photo_likes'] = 0;
$current_data['total_long_read_likes'] = 0;
$current_data['total_video_likes'] = 0;

$current_data['total_photo_comments'] = 0;
$current_data['total_long_read_comments'] = 0;
$current_data['total_video_comments'] = 0;

$current_data['total_photo_rebuzzes'] = 0;
$current_data['total_long_read_rebuzzes'] = 0;
$current_data['total_video_rebuzzes'] = 0;

$current_data['total_photo_shares'] = 0;
$current_data['total_long_read_shares'] = 0;
$current_data['total_video_shares'] = 0;

$current_data['total_photo_views'] = 0;
$current_data['total_long_read_views'] = 0;
$current_data['total_video_views'] = 0;

$current_data['total_views'] = 0;
$current_data['total_engagement'] = 0;

//sql start first


$this->db->select('sum(total_photo_likes) as total_photo_likes');
$this->db->select('sum(total_long_read_likes) as total_long_read_likes');
$this->db->select('sum(total_video_likes) as total_video_likes');

$this->db->select('sum(total_photo_comments) as total_photo_comments');
$this->db->select('sum(total_long_read_comments) as total_long_read_comments');
$this->db->select('sum(total_video_comments) as total_video_comments');

$this->db->select('sum(total_photo_rebuzzes) as total_photo_rebuzzes');
$this->db->select('sum(total_long_read_rebuzzes) as total_long_read_rebuzzes');
$this->db->select('sum(total_video_rebuzzes) as total_video_rebuzzes');

$this->db->select('sum(total_photo_shares) as total_photo_shares');
$this->db->select('sum(total_long_read_shares) as total_long_read_shares');
$this->db->select('sum(total_video_shares) as total_video_shares');

$this->db->select('sum(total_photo_views) as total_photo_views');
$this->db->select('sum(total_long_read_views) as total_long_read_views');
$this->db->select('sum(total_video_views) as total_video_views');

$this->db->from('sb_user_news_engagement');
$this->db->where('user_id', $post_value['user_id']);
$this->db->where('engagement_date >=', $current_date);
if($current_date_end) {
$this->db->where('engagement_date <=', $current_date_end);            
}
$query = $this->db->get();



//sql end first


if($query->num_rows() > 0) {
    
 
$row = $query->row_array(); 
$current_data['total_photo_likes'] = !empty($row['total_photo_likes']) ? $row['total_photo_likes'] : 0; 
$current_data['total_long_read_likes'] = !empty($row['total_long_read_likes']) ? $row['total_long_read_likes'] : 0; 
$current_data['total_video_likes'] = !empty($row['total_video_likes']) ? $row['total_video_likes'] : 0; 

$current_data['total_photo_comments'] = !empty($row['total_photo_comments']) ? $row['total_photo_comments'] : 0; 
$current_data['total_long_read_comments'] = !empty($row['total_long_read_comments']) ? $row['total_long_read_comments'] : 0; 
$current_data['total_video_comments'] = !empty($row['total_video_comments']) ? $row['total_video_comments'] : 0;

$current_data['total_photo_rebuzzes'] = !empty($row['total_photo_rebuzzes']) ? $row['total_photo_rebuzzes'] : 0; 
$current_data['total_long_read_rebuzzes'] = !empty($row['total_long_read_rebuzzes']) ? $row['total_long_read_rebuzzes'] : 0; 
$current_data['total_video_rebuzzes'] = !empty($row['total_video_rebuzzes']) ? $row['total_video_rebuzzes'] : 0;

$current_data['total_photo_shares'] = !empty($row['total_photo_shares']) ? $row['total_photo_shares'] : 0; 
$current_data['total_long_read_shares'] = !empty($row['total_long_read_shares']) ? $row['total_long_read_shares'] : 0; 
$current_data['total_video_shares'] = !empty($row['total_video_shares']) ? $row['total_video_shares'] : 0;

$current_data['total_photo_views'] = !empty($row['total_photo_views']) ? $row['total_photo_views'] : 0; 
$current_data['total_long_read_views'] = !empty($row['total_long_read_views']) ? $row['total_long_read_views'] : 0; 
$current_data['total_video_views'] = !empty($row['total_video_views']) ? $row['total_video_views'] : 0;

$current_data['total_views'] = $current_data['total_photo_views'] + $current_data['total_long_read_views'] + $current_data['total_video_views'];
$current_data['total_engagement'] = $current_data['total_photo_likes'] + $current_data['total_long_read_likes'] + $current_data['total_video_likes']  
+ $current_data['total_photo_comments'] + $current_data['total_long_read_comments'] + $current_data['total_video_comments'] 
+ $current_data['total_photo_rebuzzes'] + $current_data['total_long_read_rebuzzes'] + $current_data['total_video_rebuzzes']
+ $current_data['total_photo_shares'] + $current_data['total_long_read_shares'] + $current_data['total_video_shares'];

}
$query->free_result();

$previous_data['total_photo_likes'] = 0;
$previous_data['total_long_read_likes'] = 0;
$previous_data['total_video_likes'] = 0;

$previous_data['total_photo_comments'] = 0;
$previous_data['total_long_read_comments'] = 0;
$previous_data['total_video_comments'] = 0;

$previous_data['total_photo_rebuzzes'] = 0;
$previous_data['total_long_read_rebuzzes'] = 0;
$previous_data['total_video_rebuzzes'] = 0;

$previous_data['total_photo_shares'] = 0;
$previous_data['total_long_read_shares'] = 0;
$previous_data['total_video_shares'] = 0;

$previous_data['total_photo_views'] = 0;
$previous_data['total_long_read_views'] = 0;
$previous_data['total_video_views'] = 0;

$previous_data['total_views'] = 0;
$previous_data['total_engagement'] = 0;


//sql start second


$this->db->select('sum(total_photo_likes) as total_photo_likes');
$this->db->select('sum(total_long_read_likes) as total_long_read_likes');
$this->db->select('sum(total_video_likes) as total_video_likes');

$this->db->select('sum(total_photo_comments) as total_photo_comments');
$this->db->select('sum(total_long_read_comments) as total_long_read_comments');
$this->db->select('sum(total_video_comments) as total_video_comments');

$this->db->select('sum(total_photo_rebuzzes) as total_photo_rebuzzes');
$this->db->select('sum(total_long_read_rebuzzes) as total_long_read_rebuzzes');
$this->db->select('sum(total_video_rebuzzes) as total_video_rebuzzes');

$this->db->select('sum(total_photo_shares) as total_photo_shares');
$this->db->select('sum(total_long_read_shares) as total_long_read_shares');
$this->db->select('sum(total_video_shares) as total_video_shares');

$this->db->select('sum(total_photo_views) as total_photo_views');
$this->db->select('sum(total_long_read_views) as total_long_read_views');
$this->db->select('sum(total_video_views) as total_video_views');

$this->db->from('sb_user_news_engagement');
$this->db->where('user_id', $post_value['user_id']);
$this->db->where('engagement_date >=', $previous_date);
$this->db->where('engagement_date <', $current_date);
$query = $this->db->get();


//sql end second

if($query->num_rows() > 0) {
$row = $query->row_array(); 


/*$previous_data['total_photo_likes'] = $current_data['total_photo_likes'] - $row['total_photo_likes']; 
$previous_data['total_long_read_likes'] = $current_data['total_long_read_likes'] - $row['total_long_read_likes']; 
$previous_data['total_video_likes'] = $current_data['total_video_likes'] - $row['total_video_likes']; 

$previous_data['total_photo_comments'] = $current_data['total_photo_comments'] - $row['total_photo_comments']; 
$previous_data['total_long_read_comments'] = $current_data['total_long_read_comments'] - $row['total_long_read_comments']; 
$previous_data['total_video_comments'] = $current_data['total_video_comments'] - $row['total_video_comments'];

$previous_data['total_photo_rebuzzes'] = $current_data['total_photo_rebuzzes'] - $row['total_photo_rebuzzes']; 
$previous_data['total_long_read_rebuzzes'] = $current_data['total_long_read_rebuzzes'] - $row['total_long_read_rebuzzes']; 
$previous_data['total_video_rebuzzes'] = $current_data['total_video_rebuzzes'] - $row['total_video_rebuzzes'];

$previous_data['total_photo_shares'] = $current_data['total_photo_shares'] - $row['total_photo_shares']; 
$previous_data['total_long_read_shares'] = $current_data['total_long_read_shares'] - $row['total_long_read_shares']; 
$previous_data['total_video_shares'] = $current_data['total_video_shares'] - $row['total_video_shares'];

$previous_data['total_photo_views'] = $current_data['total_photo_views'] - $row['total_photo_views']; 
$previous_data['total_long_read_views'] = $current_data['total_long_read_views'] - $row['total_long_read_views']; 
$previous_data['total_video_views'] = $current_data['total_video_views'] - $row['total_video_views'];
*/


$previous_data['total_photo_likes'] =  $row['total_photo_likes']; 
$previous_data['total_long_read_likes'] =  $row['total_long_read_likes']; 
$previous_data['total_video_likes'] =  $row['total_video_likes']; 

$previous_data['total_photo_comments'] =  $row['total_photo_comments']; 
$previous_data['total_long_read_comments'] =  $row['total_long_read_comments']; 
$previous_data['total_video_comments'] =  $row['total_video_comments'];

$previous_data['total_photo_rebuzzes'] = $row['total_photo_rebuzzes']; 
$previous_data['total_long_read_rebuzzes'] = $row['total_long_read_rebuzzes']; 
$previous_data['total_video_rebuzzes'] =  $row['total_video_rebuzzes'];

$previous_data['total_photo_shares'] =  $row['total_photo_shares']; 
$previous_data['total_long_read_shares'] =  $row['total_long_read_shares']; 
$previous_data['total_video_shares'] =  $row['total_video_shares'];

$previous_data['total_photo_views'] =  $row['total_photo_views']; 
$previous_data['total_long_read_views'] =   $row['total_long_read_views']; 
$previous_data['total_video_views'] = $row['total_video_views'];




$previous_data['total_views'] = $previous_data['total_photo_views'] + $previous_data['total_long_read_views'] + $previous_data['total_video_views'];
$previous_data['total_engagement'] = $previous_data['total_photo_likes'] + $previous_data['total_long_read_likes'] + $previous_data['total_video_likes']  
+ $previous_data['total_photo_comments'] + $previous_data['total_long_read_comments'] + $previous_data['total_video_comments'] 
+ $previous_data['total_photo_rebuzzes'] + $previous_data['total_long_read_rebuzzes'] + $previous_data['total_video_rebuzzes']
+ $previous_data['total_photo_shares'] + $previous_data['total_long_read_shares'] + $previous_data['total_video_shares'];
}
$query->free_result();


$this->load->model(array('post/Post_model'));
$post_data = array('user_id' => $post_value['user_id'], 'start_date' => $current_date, 'post_type' => array());

$top_news = array();
$top_photo_story = array();
$top_long_read_news = array();
$top_video_news = array();
if($current_data['total_engagement'] > 0) {
$top_news = $this->Post_model->top_post($post_data);
}

if(($current_data['total_photo_likes'] + $current_data['total_photo_comments'] + $current_data['total_photo_rebuzzes'] + $current_data['total_photo_shares'])> 0) {
$post_data['post_type'] = array(1);
$top_photo_story = $this->Post_model->top_post($post_data);
}

if(($current_data['total_long_read_likes'] + $current_data['total_long_read_comments'] + $current_data['total_long_read_rebuzzes'] + $current_data['total_long_read_shares'])> 0) {
$post_data['post_type'] = array(2);
$top_long_read_news = $this->Post_model->top_post($post_data);
}

if(($current_data['total_video_likes'] + $current_data['total_video_comments'] + $current_data['total_video_rebuzzes'] + $current_data['total_video_shares'])> 0) {
$post_data['post_type'] = array(3);
$top_video_news = $this->Post_model->top_post($post_data);
}

return array('current_data' => $current_data, 'previous_data' => $previous_data, 'top_news' => $top_news, 'top_photo_story' => $top_photo_story, 'top_long_read_news' => $top_long_read_news, 'top_video_news' => $top_video_news);
}

function top_news_persons($data) {
$user_id    = isset($data['user_id']) ? $data['user_id'] : 0;
$followers_ids = array();
if(!empty($user_id)) {
$followers_ids = $this->get_user_follower_ids($user_id);
}

$location = isset($data['location']) ? $data['location'] : array();

$district = isset($location['district']) ? trim($location['district']) : '';
$state = isset($location['state']) ? trim($location['state']) : '';
$country = isset($location['country']) ? trim($location['country']) : '';
if(empty($district) && empty($state) && empty($country)) {
return array();
}
$page_no    = isset($data['page_no']) ? $data['page_no'] : 1;
$page_size  = isset($data['page_size']) ? $data['page_size'] : 3;
$offset     = pagination_offset($page_no, $page_size);

$this->db->select('tp.user_id');
$this->db->select("u.username");
$this->db->select('IFNULL(u.avatar,"") as userimage', FALSE);
$this->db->select('IFNULL(u.cover,"") as coverimage', FALSE);
$this->db->from('sb_top_newspersons tp');
$this->db->join('users u', 'u.id = tp.user_id and u.is_reporter=1 and u.reporter_status=1 and u.active=1');
if(!empty($district) || !empty($state) || !empty($country)) {
$this->db->join('sb_location_master lm', 'lm.id = tp.location_id');
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
if(!empty($followers_ids)) {
$this->db->where_not_in('tp.user_id', $followers_ids);
}

$this->db->group_by('tp.user_id');
$this->db->order_by('tp.display_order', 'ASC');
$this->db->order_by('u.num_followers', 'DESC');

$this->db->limit($page_size,$offset);
$query = $this->db->get(); 
$results = $query->result_array();
return $results;
}

function get_user_follower_ids($user_id) {
$this->db->simple_query('SET SESSION group_concat_max_len=150000');

$this->db->select(' GROUP_CONCAT(uf.who) as who_ids');
$this->db->from('users_followed uf');
$this->db->join('users u','u.id = uf.who AND u.active=1');
$this->db->where('uf.whom', $user_id);
$this->db->order_by('uf.who', 'ASC');
$query = $this->db->get();
//echo $this->db->last_query(); die;
$followers_ids = array();
if ($query->num_rows()) {
$result = $query->row_array();
if (!empty($result['who_ids'])) {
$who_ids = $result['who_ids'];   
$followers_ids = explode(',', $who_ids);                     
}
}
return $followers_ids;
}

function toggle_favourite($data) {
$who = $data['logged_in_user_id'];
$whom = $data['user_id'];
$count = 1;

$this->db->select('id');
$this->db->where(array('who' => $who,'whom' => $whom));
$query = $this->db->get('user_favourites');
if($query->num_rows() > 0) {
$count = -1;
$row = $query->row_array();
$this->db->where('id',$row['id']);
$this->db->delete('user_favourites');
} else {
$date  = time();
$input = array(
'who'    => $who,
'whom'  => $whom,
'date'  => $date
);            
$this->db->insert('user_favourites', $input); 

$this->load->model(array('notification/Notification_model'));
$notify_data = array();
$notify_data['from_user_id'] = $who;
$notify_data['to_user_id'] = $whom;
$notify_data['post_id'] = $whom;
$notify_data['noti_type'] = 'loved';
$notify_data['post_type'] = 'profile';
$notify_data['standard_notify_type'] = 'ntf_me_on_post_profileloved';
$this->Notification_model->insert_active_profile($notify_data);
}
$this->update_favourite_count($whom, $count);



return $count;
}

public function update_favourite_count($user_id, $count=1){
$set_field  = "num_favourites";         
$this->db->where('id', $user_id);
$this->db->set($set_field, "$set_field+($count)", FALSE);
$this->db->update('users');  
}

public function reset_password($data) {  

$user_id = $data['user_id'];
$data = array(
'password'=>md5($data['password'])
);
$this->db->where('id',$user_id);
$this->db->update('users',$data);
}

function sign_up($data) {
$this->db->insert('users',$data);
return $this->db->insert_id();
}

/**
* for generate user_name from email
* @param string $email
* @return string
*/
public function generate_user_name($email) {
$user_name = explode('@', $email);
$user_name = $user_name[0];
$a = 0;
do {
if ($a !== 0) {
$user_name = $user_name . $a;
}
$a++;
}

// Already in the DB? Fail. Try again
while (self::_user_name_exists($user_name));

return $user_name;
}

/**
* to check user_name exist or not
* @param string $user_name
* @return boolean
*/
public function _user_name_exists($user_name) {
$this->db->select('id');
$this->db->where('username', $user_name);
$this->db->limit(1);
$query = $this->db->get('users');
$num = $query->num_rows();
if($num > 0){
return true;
}
return false;
}

public function generate_otp($data) {
$type = isset($data['type']) ? $data['type'] : 2;
$value = $data['value'];

$otp_data = array();
$condition = array("type_value" => $value, "type" => $type);

$otp_data["added_date"] = time();
$otp_data["modified_date"] = time();
$otp_data["type_value"] = $value;
$otp_data["type"] = $type;

$this->db->trans_start();        
$otp = $this->random_unique_string('sb_manage_otp', 'code', array(), 'nozero', 6);
$otp_data["code"] = $otp;

$record = $this->get_single_row("*", 'sb_manage_otp', $condition);

if (empty($record)) {
$this->db->insert('sb_manage_otp', $otp_data);
$otp_id = $this->db->insert_id();
} else {
unset($otp_data['type_value']);
unset($otp_data['added_date']);
$now = time();

$otp_id = $record['otp_id'];
$created_date = $record['added_date'];
$future_time = strtotime(date("Y-m-d h:i:s",$created_date) . ' +10 minutes');

if ($future_time > $now) {
$otp = $record['code'];
$this->db->where(array('type_value' => $value));
$this->db->update('sb_manage_otp', array('modified_date' => time()));
} else {
$this->db->where(array('type_value' => $value));
$this->db->update('sb_manage_otp', $otp_data);
}
}
$this->db->trans_complete();
return $otp;
}

public function check_otp($data) {

$type = isset($data['type']) ? $data['type'] : 2;
$value = $data['value'];
$otp_code = $data['OTP'];

$this->db->select('otp_id, modified_date as added_date');
$this->db->from('sb_manage_otp');
$this->db->where('code', $otp_code);
$this->db->where('type_value', $value);
$this->db->where('type', $type);
$this->db->limit(1);

$row = $this->db->get()->row_array();
//print_r($row);

if (!$row) {
return 1;
}

$now = time(); //13:06
//echo "<br>";
$added_date = $row['added_date'];  //13:05

$time = strtotime(date("Y-m-d h:i:s",$added_date) . ' +5 minutes');  //13:10

if($now < $time) {
//die('===================');
$otp_id = $row['otp_id'];
$this->db->where(array('otp_id' => $otp_id));
$this->db->delete('sb_manage_otp');
return 2;
} else {
return 3;  
}
}
public function getUserFollowing($userid) {
$this->db->select('id');
$this->db->where('who', $userid);
$query = $this->db->get('users_followed');
$num = $query->num_rows();
if($num > 0){
$result = $query->result_array();
return count($result);


}
return 0;
}
function userextradata($data,$tablename) {
$this->db->insert($tablename,$data);
return $this->db->insert_id();
}
function socialusersignup($data) {
$email = $data["email"];$username = $data["username"];$referdby ="admin";$type =$data["refer_type"];$street_userpassword = $data["password"];$phone=$data["phone_no"];$gender=$data["gender"];$birthdate=$data["birthdate"];$fullname=$data["fullname"];$tmplang=$data["language"];$tmpzone=$data["timezone"];$lastlogin_date=$data["reg_date"];$lastlogin_ip=$data["reg_ip"];$locationid = $data["location_id"];
$fbid=NULL;$gpid=NULL;
if(!empty($data["fb_id"])){
$fbid = $data["fb_id"];

}
if(!empty($data["gp_id"])){
$gpid = $data["gp_id"];

}


$quey =  'INSERT INTO users SET  email="'.($email).'",username="'.($username).'",referdby="'.($referdby).'", refer_type="'.($type).'", password="'.($street_userpassword).'",phone_no="'.($phone).'", gender="'.($gender).'", birthdate="'.($birthdate).'", fullname="'.($fullname).'", language="'.$tmplang.'", timezone="'.$tmpzone.'", reg_date="'.$lastlogin_date.'", reg_ip="'.$lastlogin_ip.'", lastlogin_date="'.$lastlogin_date.'", lastlogin_ip="'.$lastlogin_ip.'" ,location_id="'.$locationid.'",active=1';
$this->db->query($quey);
return $this->db->insert_id();

}


}
