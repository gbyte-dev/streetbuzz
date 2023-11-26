<?php

    require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_editpost.php');


    $data = json_decode(file_get_contents('php://input'),true); 

    $statuscode=0;


       
        $user_id = $data["post"]["userid"]; 
        $post_id = $data["post"]["postid"];
        $post_message = $data["post"]["message"]; 
      
        $message='Success';
        $statuscode=0;
        $api = new API();

        $output = $api->textpost_edit($user_id,$post_id,$post_message);


        $status =array(
            "message" => 'success',
            "statuscode" => $statuscode
        );
    
        $response["postid"]=$post_id;
        $response["status"] = $status;

         http_response_code(200);

        echo json_encode($response);

?>