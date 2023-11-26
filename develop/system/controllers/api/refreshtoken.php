<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api.php');

   	// Convert data to json
    $data = json_decode(file_get_contents('php://input'),true); 

    //Parse data from JSON
    $userid   = $data["user"]["userid"]; 
    $token    = $data["user"]["token"]; 

   $message='Success';
   $statuscode=0;
   $oauth_access_token = '';
   
    // Create class object
   $api = new API();

    
    //
    $response=array();
	$response["user"]=array();
	$response["status"]=array();
	
 
	$oauth_access_token = $api->refreshToken($userid,$token);
     if ($oauth_access_token == null)
    {
        $message='Error while refreshing token. Please login again.';
        $statuscode=110;
    }
    else
    {
	    $user =array(
                "userid" => $userid,
                "token" => $oauth_access_token
            );
    }
	$status =array(
            "message" => $message,
            "statuscode" => $statuscode
        );
  
    array_push($response["user"], $user);
    array_push($response["status"], $status);
        
    http_response_code(200);
  
    // show response data in json format
    echo json_encode($response);

    $userid = ''; 
    $username = ''; 
    $password = ''; 
    $message='';
    $statuscode=0;
    $oauth_access_token = '';

   
?>