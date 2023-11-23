<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api_a.php');


  $data = json_decode(file_get_contents('php://input'),true); 

    $statuscode=0;

    //Parse data from JSON
    
    $who = $data["user"]["who"]; 
    $whom = $data["user"]["whom"]; 
    $status = $data["user"]["status"]; 
    
    $message='Success';
    
    $api = new API();
    
    $output = $api->follow($who,$whom,$status);
    
    
      $oauth_access_token = '';
    
 
    
    $oauth_access_token = $api->generateToken($who);
    
    $user =array(
                "userid" => $who,
                'token' =>$oauth_access_token,
                'count' => $output
            );
    
    
	$status =array(
            "message" => $output,
            "statuscode" => $statuscode
        );
        
   
    $response["user"] = $user;
     $response["status"] = $status;
    
    http_response_code(200);
  
    // show response data in json format
    echo json_encode($response);
