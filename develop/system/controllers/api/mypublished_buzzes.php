<?php
	
    require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api.m2.php');
   	// Convert data to json
    $data = json_decode(file_get_contents('php://input'),true); 
    //Parse data from JSON
    $userid = $data["user"]["userid"]; 
    //$token  = getallheaders(); 
    $pagenumber  = $data["user"]["pagenumber"]; 
    $pagerecordcount  = $data["user"]["pagerecordcount"]; 
    //$token['access_token'] = 'hhhhhhhh';
  
    if(!empty($data["user"]['access_token'])){
        $token =  $data["user"]['access_token'];  
    
        // Set default values
        $message='Success';
        $statuscode=0;
        // Create class object
        $api = new API();
        if ($api->validateToken($userid, $token))
        {
            $dashboard=$api->userLoadPosts($userid,$pagenumber,$pagerecordcount);
            $user = $api->getUserDetails($userid);
            http_response_code(200);
            $response=array();
            $response["dashboard"]=array();
            $response["status"]=array();
            $response["settings"]=array();
            $response["user"]=array();
            $settings =array(
                    "imageurl" => $api->imageUrl(),
                    "profileImageurl" => $api->profileImageUrl(),
                    "postBaseUrl" => $api->postBaseUrl()
                );
            $status =array(
                    "message" => $message,
                    "statuscode" => $statuscode
                );
            $response["dashboard"] = $dashboard;
            $response["settings"] = $settings;
            $response["user"] = $user;
            $response["status"] =  $status;
        } else {
            $response['status'] = 401;
            $response['message'] = 'Invalid Token'; 
        }
    }else{
        $message='Invalid token';
        $statuscode=100;
        http_response_code(401);
        $response['status'] = 401;
        $response['message'] = 'Please enter valid token';
        
    }
    //array_push($response["user"], $user);
    //array_push($response["status"], $status);
    //array_push($response["dashboard"], $dashboard);
    // show response data in json format
    echo json_encode($response);
?>