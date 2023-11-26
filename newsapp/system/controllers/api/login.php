<?php
require_once('../../helpers/func_main.php');
require_once('../../conf_system.php');
require_once('classes/class_api.m.php');
// Convert data to json    
$data = json_decode(file_get_contents('php://input'), true);

//START: Cors error fix
//Temporary fix for cors error by Suresh. Will be improved later for production.
header("Access-Control-Allow-Origin: *");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}
//END: Cors error fix 
//Parse data from JSON
$username = $data["user"]["username"];
$password = md5($data["user"]["password"]);
$device_token = isset($data["user"]["device_token"]) ? $data["user"]["device_token"] : NULL;

//$develop = $data["user"]["develop"];       

// Set default values
$message = 'Success';
$statuscode = 0;
$oauth_access_token = '';

// Create class object  
$api = new API();
//Get the user id if exists
$userid = $api->checkUser($username, $password);
$password = "";
if ($userid == 0) {
    // if not, send message 	    
    $message = 'Invalid username or password';
    $statuscode = 101;
    $user_id = $api->checkUser($username);
    if ($user_id == 0) {
        // if user not exists, send message     	    
        $message = 'User doesn\'t exist';
        $statuscode = 100;
    }
} else {
    // Generate new oauth_access_token token
    $oauth_access_token = $api->generateToken($userid, $device_token);
}

$response = array();
$response["user"] = array();
$response["status"] = array();
$response["settings"] = array();
//$user = $api->getUserDetails($userid);

if ($userid != 0) {
    $user = $api->getUserDetails($userid);
} else {
    $user[] = array(
        "userid" => $userid,
        "access_token" => $oauth_access_token
    );
}
$status = array(
    "message" => $message,
    "statuscode" => $statuscode
);

$settings = array(
    "imageurl" => $api->imageUrl(),
    "profileImageurl" => $api->profileImageUrl()
);
if (!empty($user)) {
    $is_admin = $user['is_network_admin'];
    $is_reporter = $user['is_reporter'];
    $role = 'user';
    if ($is_admin == 1) {
        $role = 'admin';
    } else if ($is_reporter == 1) {
        $role = 'reporter';
    }
}
$user[0]['role'] = $role;

$response["user"] = $user;
$response["status"] = $status;
$response["settings"] = $settings;
http_response_code(200);

// show response data in json format

echo json_encode($response);
$userid = '';
$username = '';
$password = '';
$message = '';
$statuscode = 0;
$oauth_access_token = '';
