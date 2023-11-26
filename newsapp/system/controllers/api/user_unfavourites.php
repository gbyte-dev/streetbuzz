<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api.m2.php');


  $data = json_decode(file_get_contents('php://input'),true); 

    $statuscode=0;


        $who = $data["user"]["who"]; 
        $whom = $data["user"]["whom"]; 

 
        $message='Success';
    $statuscode=0;
         $api = new API();
            
         $output = $api->user_unfavourites($who,$whom);
              $user =array(
                "userid" => $who            );
    
    
    	$status =array(
                "message" => 'success',
                "statuscode" => $statuscode
            );
        
    
    $response["user"] = $user;
     $response["status"] = $status;
    
    http_response_code(200);
  
    // show response data in json format
    echo json_encode($response);


?>