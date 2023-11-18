<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api_a.php');


  $data = json_decode(file_get_contents('php://input'),true); 

    $statuscode=0;
    

        $postid = $data["post"]["postid"]; 
        $userid = $data["post"]["userid"]; 
        $question = $data["post"]["question"];  
        $option = $data["post"]["option"]; 
        $group  = $data["post"]["group"];
        $user = $data["post"]["user"]; 
        $images = $data["post"]["images"]; 


        $message='Success';
        $statuscode=0;
        $api = new API();
         

 
         $output = $api->poll_edit($userid,$question,$option,$group,$user,$postid,$images);
              $user =array(
                'user_id' => $userid
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