<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api_a.php');


  $data = json_decode(file_get_contents('php://input'),true); 

    $statuscode=0;


         $post_id = $data["post"]["post_id"]; 
         $userid = $data["post"]["userid"]; 

         $message='Success';
         $statuscode=0;
         $api = new API();
         
         
         $output = $api->poll_details($post_id,$userid);
              $user =array(
                'details' => $output
            );
    
    
    	$status =array(
                "message" => 'success',
                "statuscode" => $statuscode
            );
        
    
    $response["user"] = $user;
     $response["status"] = $status;
    
    http_response_code(200);
  
    // show response data in json format
    echo json_encode($response);


?>