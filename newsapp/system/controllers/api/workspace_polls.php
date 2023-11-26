<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
 	require_once('classes/class_api_a.php') ;
 //  require_once('classes/class_api.m2.php');

  $data = json_decode(file_get_contents('php://input'),true); 
    $userid = $data["user"]["userid"]; 
    $token  = $data["user"]["access_token"]; 
    $pagenumber  = $data["user"]["pagenumber"]; 
    $pagerecordcount  = $data["user"]["pagerecordcount"]; 
    $tab = $data["user"]["serach_tab"];

     $api = new API();
       if (!$api->validateToken($userid, $token))
    {
       
        $message='Invalid token';
        $statuscode=100;
        http_response_code(401);
    }
    else
    {
     if($tab == "my_polls"){
        $output = $api->mypolls_workspace($userid,$pagenumber,$pagerecordcount);
     }
      if($tab == "trend_polls"){
        $output = $api->trendpolls_workspace($userid,$pagenumber,$pagerecordcount);
     }
     $message='Success';
     $statuscode=0;
        	$status =array(
            "message" => $message,
            "statuscode" => $statuscode
        );


    
    $response["polls"] = $output;
     $response["status"] = $status;
    }
    
    http_response_code(200);
  
    // show response data in json format
    echo json_encode($response);
    
   


?>