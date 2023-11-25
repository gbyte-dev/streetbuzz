<?php

    require_once('../../helpers/func_main.php');
    require_once('../../conf_system.php');
    require_once('classes/class_api.m3.php');
    header("Access-Control-Allow-Methods: PUT, GET, POST");
    //Parse data from JSON    
       
    $userid    	= $_POST["userid"];   
    // $token     	= $_POST["token"];     
    $postdata   = $_POST["messgedata"];     
    $title      = $_POST["title"];     
    $postid     = $_POST["postid"];      
    // $unlinkimages[]   =   explode(",",$_POST["unlinkimages"]);      
    $coverimage = $_POST["coverimage"];   
       
    //Set default values    
    $message='Success';    
    $statuscode=0;    
    $oauth_access_token = '';        
    $api = new API();    
   
   /*if (!$api->validateToken($userid, $token))    {        
        $message='Invalid token';
        $statuscode=100;
        http_response_code(401);
    }
    else
    { */
        //echo "aass"; die;
        $postid = $api->submitEditPost($userid,$postdata,$title,$postid);
//  } 
       
    $response=array();
    $response["status"]=array();
    
    $status =   array(
                        "message" => $message,
                        "statuscode" => $statuscode
                    );
    
    $response["status"] = $status;    
    $response["post"] = $postid;
    
    http_response_code(200);    
    echo json_encode($response);
  ?>