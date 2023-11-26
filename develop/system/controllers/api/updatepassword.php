<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api_a.php');


  $data = json_decode(file_get_contents('php://input'),true); 

   
      $token  = $data["user"]["access_token"];
      $userid = $data["user"]["userid"];
 
      $old_password= md5($data["user"]["old_password"]); 
      $new_password = md5($data["user"]["new_password"]); 
       $confirm_password = md5($data["user"]["confirm_password"]); 
        
   
      
         $message='Success';
         $statuscode=0;
         $api = new API();
         
         
          $output = $api->update_password($userid,$old_password,$new_password,$confirm_password);
          
          $oauth_access_token = $api->generateToken($userid);	
          
             if($output=="0"){
      
	$status =array(
            "message" => 'Current Password Not Match',
            "statuscode" => $statuscode
        );
           }
           
             if($output=="00"){
      
	$status =array(
            "message" => 'Confirm Password Not Match',
            "statuscode" => $statuscode
        );
           }
           
             if($output=="1"){
      
	$status =array(
            "message" => 'Password Updated',
            "statuscode" => '100'
        );
           }
           
             $user[] =array(
	        "userid" => $userid,                
	        "access_token" => $oauth_access_token            
	        );
          
          
         $response["status"] = $status;
         $response["user"] = $user;
    
    http_response_code(200);
  
    // show response data in json format
    echo json_encode($response);
      
         
         ?>