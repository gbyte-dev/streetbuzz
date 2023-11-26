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
    
    // Create class object
   $api = new API();

    
    //
    $response=array();
	$response["status"]=array();
	
 
	$flag = $api->expiretoken($userid,$token);
     if ($flag )
    {
        $message='Token expired successfully.';
    }
    else
    {
        $message='Error while expiring token. Please try again.';
        $statuscode=111;
    }
	$status =array(
            "message" => $message,
            "statuscode" => $statuscode
        );
  
    array_push($response["status"], $status);
        
    http_response_code(200);
  
    // show response data in json format
    echo json_encode($response);

?>