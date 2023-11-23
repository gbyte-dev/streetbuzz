<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api_a.php');


  $data = json_decode(file_get_contents('php://input'),true); 

    $statuscode=0;


       
        $user_id = $data["post"]["userid"]; 
        $post_id = $data["post"]["postid"]; 
        $title = $data["post"]["title"]; 
        $start_date = $data["post"]["start_date"]; 
        $end_date = $data["post"]["end_date"];
        $start_time = $data["post"]["start_time"]; 
        $end_time  = $data["post"]["end_time"];
        $describe = $data["post"]["describe"]; 
        $hash_tag = $data["post"]["hash_tag"]; 
        $group  = $data["post"]["group"];
        $user = $data["post"]["user"]; 
        $link  = $data["post"]["link"];
        $images = $data["post"]["images"]; 
        $venue = $data["post"]["venue"]; 
        $geoloc = $data["post"]["geoloc"];   
          
      
        $message='Success';
        $statuscode=0;
        $api = new API();


 $output = $api->event_edit($user_id,$post_id,$title,$start_date,$end_date,$start_time,$end_time,$describe,$hash_tag,$group,$user,$link,$images,$venue, $geoloc);
            
    
    	$status =array(
                "message" => 'success',
                "statuscode" => $statuscode
            );
        
         $response["status"] = $status;
    
             http_response_code(200);
  
            echo json_encode($response);


?>