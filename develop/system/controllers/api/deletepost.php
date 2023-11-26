<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api_a.php');


  $data = json_decode(file_get_contents('php://input'),true); 

   
      $postid = $data["user"]["postid"]; 
      $token  = $data["user"]["access_token"];
      $userid = $data["user"]["userid"];
      
      
      
        $message='Success';
    $statuscode=0;
         $api = new API();
         
           $output = $api->delete_this_post($postid,$userid);
           
           if($output=="1"){
      
	$status =array(
            "message" => 'success',
            "statuscode" => $statuscode
        );
           }
           
           
           else {
               $status =array(
            "message" => $output,
            "statuscode" => $statuscode
        );
           }
  
    
     $response["status"] = $status;
    
    http_response_code(200);
  
    // show response data in json format
    echo json_encode($response);
      
    
    
    
    
    ?>