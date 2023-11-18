<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api_a.php');


  $data = json_decode(file_get_contents('php://input'),true); 

    $statuscode=0;

         $userid = $data["post"]["userid"]; 
        $post_id = $data["post"]["postid"]; 
       
       
        $message='Success';
        $statuscode=0;
         $api = new API();
         
         
         
         if (!$api->validateToken($userid, $token))
    {
        $message='Invalid token';
        $statuscode=100;
        http_response_code(401);
    }
    else
    {
         
         $output = $api->event_delete($post_id);
             
    	$user =array(
                    "user_id" => $userid
            );
    
    	$status =array(
                "message" => 'success',
                "statuscode" => $statuscode
            );
        }
    
    $response["user"] = $user;
     $response["status"] = $status;
    
    http_response_code(200);
  
    // show response data in json format
    echo json_encode($response);


?>