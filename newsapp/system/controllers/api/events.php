<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api_a.php');

   $data = json_decode(file_get_contents('php://input'),true); 

    $statuscode=0;


         $images = $data["post"]["images"]; 
        $userid = $data["post"]["userid"]; 
        $title = $data["post"]["title"]; 
        $start_date = $data["post"]["start_date"]; 
        $end_date  = $data["post"]["end_date"];
        $start_time = $data["post"]["start_time"]; 
        $end_time  = $data["post"]["end_time"];
        $venue = $data["post"]["venue"]; 
        $geoloc = $data["post"]["geoloc"]; 
        $describe = $data["post"]["describe"]; 
        $hash_tag = $data["post"]["hash_tag"]; 
        $group  = $data["post"]["group"];
        $user = $data["post"]["user"]; 
        $link  = $data["post"]["link"];
        $files  = $data["post"]["video_file"];
        $parentid = $data["post"]["parentid"];
        $message='Success';
        $statuscode=0;
        $api = new API();
        if($start_date != '' ){
         $orgDate =$start_date;
         $orgDate = str_replace(",","",$orgDate);
         $date = new DateTime($orgDate);
         $start_date = $date->format('Y-m-d');
        }
         if($end_date != '' ){
         $endorgDate =$end_date;
         $endorgDate = str_replace(",","",$endorgDate);
         $enddate = new DateTime($endorgDate);
         $end_date = $enddate->format('Y-m-d');
        }
       // $newDate = date("d-m-Y", strtotime($orgDate));  
       //  print_r($newDate);exit;
         
         
         if (!$api->validateToken($userid, $token))
    {
        $message='Invalid token';
        $statuscode=100;
        http_response_code(401);
    }
    else
    {
         
         $output = $api->event_post($userid,$title,$start_date,$end_date,$start_time,$end_time,$venue,$geoloc,$describe,$hash_tag,$group,$user,$link,$images,$files,$parentid);
              $user =array(
                'user_id' => $userid
            );
    
    
    	$status =array(
                "message" => 'success',
                "statuscode" => $statuscode
            );
        }
    
    $response["user"] = $user;
     $response["status"] = $status;
    
    http_response_code(200);
  
    // show response data in json format
    echo json_encode($response);
    
   


?>