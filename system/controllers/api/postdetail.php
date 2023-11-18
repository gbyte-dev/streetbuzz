<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api_a.php');


  $data = json_decode(file_get_contents('php://input'),true); 

    $statuscode=0;


    $postid = $data["user"]["postid"]; 
    //$token  = $data["user"]["access_token"];
    
    
          $oauth_access_token = '';
        $message='Success';
       $statuscode=0;
         $api = new API();
         
         
         
          $output = $api->post_detail($postid);
         
         
         if($output=="0"){
            	$status =array(
            "message" => 'Post Not Found',
            "statuscode" => '0',
        ); 
        
        $user =array(
                'post' => $output,
            );
         }
         
         else {
            $status =array(
            "message" => 'Success',
            "statuscode" => '100',
        );
        
        
        
          $user =array(
                'post' => $output,
            );
        
         }
        
        
         $response["user"] = $user;
     $response["status"] = $status;
    
    http_response_code(200);
  
    // show response data in json format
    echo json_encode($response);



?>