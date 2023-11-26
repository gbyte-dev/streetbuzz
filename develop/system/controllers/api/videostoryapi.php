<?php	
require_once('../../helpers/func_main.php');
require_once('../../conf_system.php');
require_once('classes/class_api.m3.php');
// Convert data to json    
$data = json_decode(file_get_contents('php://input'),true);

//Parse data from JSON
$userid = $data["post"]["userid"];     
//   $token = $data["post"]["token"]; 
$title = $data["post"]["messagetitle"];
$description = $data["post"]["message"];
$videos = $data["post"]["videos"];     
$parentid = $data["post"]["parentid"];
$video_url=$data["post"]["video_url"];
$video_id=$data["post"]["video_id"];

// echo ($video_id); die('=======');


// $parentid = '';

if ($parentid == null) {
$parentid = 0;
}
//Set default values
$message='Success';
$statuscode=0;
$oauth_access_token = '';

// Create class object
$api = new API();
//Get the user id if exists

/* 
if (!$api->validateToken($userid, $token)) {
$message='Invalid token';
$statuscode=100;
http_response_code(401);        
} else { */
$postid = $api->submitVideoStoryPost($userid,$title,$videos,$parentid, '', $description,$video_url,$video_id);		
/* }*/

$response=array();
$response["status"]=array();
$status =array(
"message" => $message,
"statuscode" => $statuscode
);

$response["status"] = $status;
$response["post"] = $postid;
http_response_code(200);
echo json_encode($response);
?>