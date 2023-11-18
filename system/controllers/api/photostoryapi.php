<?php	
    require_once('../../helpers/func_main.php');
    require_once('../../conf_system.php');
    require_once('classes/class_api.m1.php');
   	// Convert data to json    
   	$data = json_decode(file_get_contents('php://input'),true);
   	
   	//Parse data from JSON
   	$userid         = $data["post"]["userid"];     
    $token          = $data["post"]["token"]; 
   	$messagetitle   = $data["post"]["messagetitle"];
   	$images         = $data["post"]["images"];     
   	$parentid       = $data["post"]["parentid"];     

    if ($parentid == null) {
        $parentid = 0;
     }


    //Set default values
    $message='Success';
    $statuscode=0;
    $oauth_access_token = '';

    // Create class object
    $api = new API();
    //Get the user id if exists
    
    $message = 'Success';
    $statuscode = 0;
    $postid = 0;
    if (!$api->validateToken($userid, $token)) {
        $message='Invalid token';
		$statuscode=100;
        http_response_code(401);        
    } else {		
		$postid = $api->submitPhotoStoryPost($userid,$messagetitle,$images,$parentid);		
    }
    $response=array();
	$response["status"]=array();
	$status =array(
            "message" => $message,
            "statuscode" => $statuscode
        );

    $response["status"] = $status;
    $response["post"] = $postid;
    http_response_code(200);
    echo json_encode($response);
?>