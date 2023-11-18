<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api.php');

   	// Convert data to json
    $data = json_decode(file_get_contents('php://input'),true); 

    //Parse data from JSON
    $token    = $data["user"]["token"]; 

   $message='Success';
   $statuscode=0;
   $oauth_access_token = '';
   
    // Create class object
   $api = new API();

    
    //
    $response=array();
	$response["data"]=array();
	$response["status"]=array();
	
 
	$appres = $api->sbappversion($userid,$token);
   
	$status =array(
            "message" => $message,
            "statuscode" => $statuscode
        );
  
    array_push($response["data"], $appres);
    array_push($response["status"], $status);
        
    http_response_code(200);
  
    // show response data in json format
    echo json_encode($response);

   

   
?>