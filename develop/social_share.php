<?php

require_once('../../helpers/func_main.php');
require_once('../../conf_system.php');
require_once('classes/class_api_a.php');

$data = json_decode(file_get_contents('php://input'), true);

$userid = $data["post"]["userid"];
$postid = $data["post"]["postid"];
$media = $data["post"]["media"];
$platform_id = $data["post"]["platform_id"];
$token  = $data["post"]["access_token"];

$message = 'Success';
$statuscode = 0;
$api = new API();

$status = array(
  "message" => $message,
  "statuscode" => $statuscode
);
if ($api->validateToken($userid, $token)) {
   $output = $api->social_share($userid, $postid, $media, $platform_id);
  if (!empty($output)) {
    $status["posts_social_share_id"] = $output;
  } else {
    $status["message"] = 'Invalid post id';
    $status["statuscode"] = 100;
    http_response_code(401);
  }
} else {
  $status["message"] = 'Invalid token';
  $status["statuscode"] = 100;
  http_response_code(401);
}
$response=array();
$response["status"] =  $status;
// show response data in json format
echo json_encode($response);
?>