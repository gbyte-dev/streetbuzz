<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api_a.php');


  $data = json_decode(file_get_contents('php://input'),true); 

    $statuscode=0;
    

    
        $userid = $data["post"]["userid"]; 
        $token     = $data["post"]["fcm_token"]; 
          $output = 'null';
          //echo  $token ;die('++++');

        
        $message='Success';
        $statuscode=0;
         $api = new API();

       $output = $api->fcmtoken($userid, $token);
              $user =array(
                'response' => $output
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