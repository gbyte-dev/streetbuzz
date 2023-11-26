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

    $user_location = isset($data["user"]["user_location"]) ? $data["user"]["user_location"] : array();
    $city = isset($user_location["city"]) ? $user_location["city"] : '';
    $district = isset($user_location["district"]) ? $user_location["district"] : '';
    $state = isset($user_location["state"]) ? $user_location["state"] : '';
    $country = isset($user_location["country"]) ? $user_location["country"] : '';
   
   
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
    } else {
        $location_id = 0;
         if(!empty($city) && !empty($district)) {
            require_once('classes/class_network.php');
            $network	= new network();
            $locationres = $network->findsblocation(strtoupper($city),strtoupper($district));
            if(!empty($locationres) && isset($locationres[0]->id) && !empty($locationres[0]->id)){
                $location_id = $locationres[0]->id;
            } else {
                $locationres = $network->findsbstate(strtoupper($state)); 
                if(!empty($locationres) && isset($locationres[0]->id) && !empty($locationres[0]->id)){
                    $state_id = $locationres[0]->id;
                    $country_id = $locationres[0]->country_id;

                    global $db2, $C;
	                $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

                    $db2->query('INSERT INTO  sb_location_master SET  location="'.$db2->e($city).'", location_district="'.$db2->e($district).'",location_state="'.$db2->e($state).'", location_country="'.$db2->e($country).'", state_id="'.$db2->e($state_id).'",country_id="'.$db2->e($country_id).'" ');
                    $location_id	= (int) $db2->insert_id();
                }
            }
        } 
        
	        
        
        
        // Insert data
        $userid = $api->signUpUser($fullname,$email,$phone,$username,$password,$dob,$gender,$location, $location_id);

        $post_data =  array(
            'user_id' => $userid
          );
          if(empty($country)){
            $country = "india";  
          }
            if(!empty($userid) && (!empty($state) || !empty($country))){
            require_once('classes/class_network.php');
           $network	= new network();
           $locationres = [];

           if(!empty($state)){
               $locationres =  $network->getusersoflocations(strtoupper($district),strtoupper($state));
               
           }


           if(empty($locationres)){
               $locationres =  $network->getusersoflocationsforcountry(strtoupper("india"));
           }

           if(!empty($locationres)){
               foreach($locationres as $keys=>$vals){
                   $whom = $vals->user_id;
                    global $db2, $C;
	                $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

                   $db2->query('INSERT INTO  users_followed SET  who="'.$userid.'", whom="'.$whom.'",date="'.time().'", whom_from_postid="1" ');
                   $db2->query('UPDATE users SET num_followers=num_followers+1 WHERE id="'.$whom.'" ');
                   
               }
           }

        }
        call_api('api/newsperson/location/generate_timeline', $post_data);  
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