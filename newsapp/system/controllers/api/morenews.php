<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api.m3.php');

   	// Convert data to json
    $data = json_decode(file_get_contents('php://input'),true); 

    //Parse data from JSON
    $userid = $data["user"]["userid"]; 
   /// $token  = $data["user"]["access_token"]; 
    $postuserid = $data["user"]["postuserid"]; 
    $postid = $data["user"]["postid"]; 
    $pagenumber  = $data["user"]["pagenumber"]; 
    $pagerecordcount  = $data["user"]["pagerecordcount"]; 

    
    // Set default values
    $message='Success';
    $statuscode=0;
    
    // Create class object
    $api = new API();
 
  /*  if ($api->validateToken($userid, $token))
    {*/
        
        $dashboard=$api->moreNewsPosts($userid,$postuserid,$postid,$pagenumber,$pagerecordcount);
        http_response_code(200);

   /* }
    else
    {
        
        $message='Invalid token';
        $statuscode=100;
        http_response_code(401);

    }*/
    

    //
    $response=array();
	$response["dashboard"]=array();
	$response["status"]=array();
	$response["settings"]=array();
	
    $settings =array(
            "imageurl" => $api->imageUrl(),
            "profileImageurl" => $api->profileImageUrl()
        );
	 
	$status =array(
            "message" => $message,
            "statuscode" => $statuscode
        );

    $response["dashboard"] = $dashboard;
    //array_push($response["dashboard"], $dashboard);
    $response["status"] =  $status;
    $response["settings"] = $settings;
        
  
    // show response data in json format
    echo json_encode($response);

 

   
?>