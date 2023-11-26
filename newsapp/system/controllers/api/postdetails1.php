<?php
	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api.m3.php');

   	// Convert data to json
    $data = json_decode(file_get_contents('php://input'),true); 

    //Parse data from JSON
    $userid = $data["user"]["userid"]; 
    $token  = $data["user"]["access_token"]; 
    $postid = $data["user"]["postid"]; 
    $pagenumber  = $data["user"]["pagenumber"]; 
    $pagerecordcount  = $data["user"]["pagerecordcount"]; 
 
    // Set default values
    $message='Success';
    $statuscode=0;

    // Create class object
    $api = new API();
  /*if ($api->validateToken($userid, $token))
    { 
        $dashboard=$api->loadPostDetails1($userid,$postid,$pagenumber,$pagerecordcount);
        //$user = $api->getUserDetails($userid);
        http_response_code(200);
    }
    else
    {
        $message='Invalid token';
        $statuscode=100;
        http_response_code(401);
    } */
    $dashboard=$api->loadPostDetails1($userid,$postid,$pagenumber,$pagerecordcount);
        //$user = $api->getUserDetails($userid);
        http_response_code(200);
    $response=array();
	$response["dashboard"]=array();
	$response["status"]=array();
	$response["settings"]=array();
	//$response["user"]=array();
    $settings =array(
            "imageurl" => $api->imageUrl(),
            "profileImageurl" => $api->profileImageUrl(),
            "postBaseUrl" => $api->postBaseUrl()
        );
	$status =array(
            "message" => $message,
            "statuscode" => $statuscode
        );
    $response["dashboard"] = $dashboard;
    $response["settings"] = $settings;
    //$response["user"] = $user;
    $response["status"] =  $status;

    //array_push($response["user"], $user);
    //array_push($response["status"], $status);
    //array_push($response["dashboard"], $dashboard);

    // show response data in json format
    echo json_encode($response);
?>