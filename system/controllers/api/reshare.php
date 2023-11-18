<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api_a.php');


  $data = json_decode(file_get_contents('php://input'),true); 

    $statuscode=0;


        $userid = $data["user"]["userid"]; 
        $postid = $data["user"]["postid"]; 
        $token  = $data["user"]["access_token"];
        
         $message='Success';
         $statuscode=0;
         $api = new API();
         
        $output = $api->post_reshare($userid,$postid);
         
            $oauth_access_token = '';
    
          $oauth_access_token = $api->generateToken($userid);
    
              $user =array(
                "userid" => $userid,
                "token" => $oauth_access_token,
            );
    
    
	$status =array(
            "message" => 'success',
            "statuscode" => $statuscode,
            "count"=> $output
        );

    $response["user"] = $user;
     $response["status"] = $status;
    
    http_response_code(200);
  
    // show response data in json format
    echo json_encode($response);


?>