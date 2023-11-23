<?php

require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api_a.php');

   	// Convert data to json
    $data = json_decode(file_get_contents('php://input'),true); 

    //Parse data from JSON
    $userid = $data["user"]["userid"]; 
    $password = md5($data["user"]["password"]); 
    //$develop = $data["user"]["develop"];    
     // Set default values
   $message='Success';
   $statuscode=0;
   $oauth_access_token = '';
   
         // Create class object
         $api = new API();

         $output = $api->deleteprofile($userid,$password);
         $oauth_access_token = '';
         
         
         if($output=="0"){
             $message="Password Not Match";
             $statuscode=100;
         }
         
         if($output=="1"){
             $message="Account Deleted";
              $oauth_access_token = $api->generateToken($userid);
         }




             $user =array(
                "userid" => $userid,
                "token" => $oauth_access_token
            );


	$status =array(
            "message" => $message,
            "statuscode" => $statuscode
        );
  
    $response["user"] = $user;
     $response["status"] = $status;
    
    http_response_code(200);
  
    // show response data in json format
    echo json_encode($response);





?>