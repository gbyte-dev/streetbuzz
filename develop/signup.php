<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api.php');
  
   	// Convert data to json
    $data = json_decode(file_get_contents('php://input'),true); 

    //Parse data from JSON
    $fullname   = $data["user"]["fullname"]; 
    $email      = $data["user"]["email"]; 
    $phone     = $data["user"]["phone"]; 
    $username   = $data["user"]["username"]; 
    $password   = md5($data["user"]["password"]); 
    $dob        = $data["user"]["dob"]; 
    $gender     = $data["user"]["gender"]; 
    $location     = $data["user"]["location"]; 
    $device_token = isset($data["user"]["device_token"]) ? $data["user"]["device_token"] : NULL;
   
  // echo $password;
  //return;
    // Set default values
   $message='Success';
   $statuscode=0;
   $oauth_access_token = '';
   
    // Create class object
    
   $api = new API();
   //Get the user id if exists
 // echo $api->checkUser($username)."A";
  //echo $api->checkUser($email)."B";
  //echo $api->checkUser($phone)."C";
  
    if (
      ($api->checkUser($username) != 0)
      or
      ($api->checkUser($email) != 0)
      or
      ($api->checkUser($phone) != 0)
    )
    {
        
      // if not, send message 
	  $message='User already exists';
	  $statuscode=102;
    }
    else
    {
        
        // Insert data
        $userid = $api->signUpUser($fullname,$email,$phone,$username,$password,$dob,$gender,$location);
        
          
	    // Generate new oauth_access_token token 
	    $oauth_access_token = $api->generateToken($userid, $device_token);
  	    $message='Success';
	    $statuscode=0;

	}   
 
    //
    $response=array();
	$response["user"]=array();
	$response["status"]=array();
	$response["settings"]=array();
	
    if ($userid != 0)
    {
        $user = $api->getUserDetails($userid);
    }
    else
    {
	    $user =array(
                "userid" => $userid,
                "token" => $oauth_access_token
            );
        $user = array($user);    
    }
	$status =array(
            "message" => $message,
            "statuscode" => $statuscode
        );
  
  
    $settings =array(
            "imageurl" => $api->imageUrl(),
            "profileImageurl" => $api->profileImageUrl()
        );
	 
	 
	 $response["settings"] = $settings;
//    array_push($response["user"], $user);
//    array_push($response["status"], $status);
    $response["user"] = $user;
    $response["status"] = $status;
      
    http_response_code(200);
  
    // show response data in json format
    echo json_encode($response);

 
?>