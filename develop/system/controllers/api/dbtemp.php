<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api.php');

// Create a stream
   	// Convert data to json
    $data = json_decode(file_get_contents('php://input'),true); 
    
    function get_contents() {
  file_get_contents("http://example.com");
  var_dump($http_response_header);
}
get_contents();

    echo var_dump($http_response_header);
exit;
    
    //Parse data from JSON
    $userid = $data["user"]["userid"]; 
    $token  = $data["user"]["access_token"]; 
    $pagenumber  = $data["user"]["pagenumber"]; 
    $pagerecordcount  = $data["user"]["pagerecordcount"]; 

    
    // Set default values
    $message='Success';
    $statuscode=0;
    
    // Create class object
    $api = new API();
 
    if ($api->validateToken($userid, $token))
    {
        
        $dashboard=$api->loadPosts($userid,$pagenumber,$pagerecordcount);
        $user = $api->getUserDetails1($userid);
        http_response_code(200);

    }
    else
    {
        
        $message='Invalid token';
        $statuscode=100;
        http_response_code(401);
    }
    
	
    //
    $response=array();
	$response["dashboard"]=array();
	$response["status"]=array();
	$response["settings"]=array();
	$response["user"]=array();

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
    array_push($response["status"], $status);
    $response["settings"] = $settings;
    array_push($response["user"], $user);
    
   
    // show response data in json format
    echo json_encode($response);

 

   
?>