<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api.m3.php');


  $data = json_decode(file_get_contents('php://input'),true); 

    $statuscode=0;
    
        $userid = $data["post"]["userid"]; 
        $postid = $data["post"]["postid"];  
        //$token     = $data["post"]["access_token"]; 
        $output = 'null';
         
        
        $message='Success';
        $statuscode=0;
         $api = new API();
       /*
if (!$api->validateToken($userid, $token))
    {
        $message='Invalid token';
		$statuscode=100;
        http_response_code(401);
    }
    else
    {
         */
         $output = $api->event_details($userid,$postid);
         
  /*/  }   */
              $user =array(
                'details' => $output
            );
    
    
    	$status =array(
                "message" => 'success',
                "statuscode" => $statuscode
            );
    
    
    $response["event"] = $user;
     $response["status"] = $status;
    
    http_response_code(200);
  
    // show response data in json format
    echo json_encode($response);


?>