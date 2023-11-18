<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('notification_api.php');


  $data = json_decode(file_get_contents('php://input'),true);
    $statuscode=0;

    //Parse data from JSON
    
    $userid = $data["user"]["userid"];
    $type = $data["user"]["type"];




    
    $message='Success';
    
    $api = new API();

    $output = $api->userlist($userid,$type);
    // $api->changeAvatar($output);
    
    $oauth_access_token = '';
    
 
    
    $oauth_access_token = $api->generateToken($userid);
    
    // $temp= array();
    // if(!empty($output))
    //   array_push($temp,$output);

    $user =array(
                "userid" => $userid,
                'token' =>$oauth_access_token,
                'type' => $type,
                'data' => $output
            );
        
   
    $response["user"] = $user;
    
    http_response_code(200);
  
    echo json_encode($response);


?>