<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api.m3.php');
  
   	// Convert data to json
    $data = json_decode(file_get_contents('php://input'),true); 

    //Parse data from JSON
    $userid          = $data["search"]["userid"]; 
    $token           = $data["search"]["access_token"]; 
    $searchkey       = $data["search"]["searchkey"]; 
    $searchtype       = $data["search"]["searchtype"]; 
   
   // Set default values
   $message='Success';
   $statuscode=0;
   $oauth_access_token = '';
   
    // Create class object
   $api = new API();

    if (!$api->validateToken($userid, $token))
    {
        $message='Invalid token';
        $statuscode=100;
        http_response_code(401);
    }
    elseif ($api->checkSearckey($userid,$searchkey,$searchtype) != 0)
    {
    	  $message='Duplicate record. This search key already saved';
          $statuscode=102;
        // http_response_code(102);
    }
    else
    {
        // Insert data
        $search_id = $api->saveSearchKey($userid,$searchkey,$searchtype);
  	    $message='Success';
        http_response_code(200);
	}   
 	              

    //
    $response=array();
	$response["status"]=  array(
            "message" => $message,
            "statuscode" => $statuscode
        );

    // show response data in json format
    echo json_encode($response);

 
?>