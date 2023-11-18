<?php
	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api_ads.php');
   	// Convert data to json
    $data = json_decode(file_get_contents('php://input'),true); 
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
    $userid = $data["user"]["userid"]; 
    $token  = $data["user"]["access_token"];
    if($data["user"]["post_date"]){
    $post_date  = $data["user"]["post_date"];
    }else{
       $post_date = ''; 
    }
    // Set default values
    $message='Success';
    $statuscode=0;
    // Create class object
    $api = new API();
   // if ($api->validateToken($userid, $token))
   if ($userid)
    {
        $adsdetails=$api->loadAds($userid,$post_date);
         $message = "Success";
        http_response_code(200);
        
    }
    else
    {
        $message='Invalid token';
        $statuscode=100;
        http_response_code(401);
    }
    $response=array();
    
	$response["ads"]=array();
	$response["status"]=array();

   
	$status =array(
            "message" => $message,
            "statuscode" => $statuscode
        );
    $response["ads"] = $adsdetails;
   
    $response["status"] =  $status;
    
    echo json_encode($response);
?>