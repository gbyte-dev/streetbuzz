<?php       
// ini_set('upload_max_filesize', '20000M');
// ini_set('post_max_size', '20000M');                               
// ini_set('max_input_time', 3000);                                
// ini_set('max_execution_time', 3000);
require_once('../../helpers/func_main.php');
require_once('../../conf_system.php');
require_once('classes/class_api.m1.php');

// header('Content-Type: application/json; charset=utf-8');
// header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
// define ("MAX_SIZE","10000");


       
   $userid    	= $_POST["userid"];   
   //   echo $userid; exit;
   //$token     	= $_POST["token"];     
   $postdata   = $_POST["messgedata"];     
   $parentid   = $_POST["parentid"];      
   $title   =       $_POST["title"];     
   $coverimage = "";
   $uploadimages = array();
   if(isset($_POST["coverimage"]) && !empty($_POST["coverimage"])){
      $coverimage   =    $_POST["coverimage"]; 
      $uploadimages = pathinfo($coverimage);
   }       
    //   echo json_encode($userid,$token);
        // echo $userid;
        // echo "bhanu";
        
   if ($parentid == null || empty($parentid)) {
      $parentid = 0;
   }

   //Set default values    
   $message='Success';    
   $statuscode=0;    
   $oauth_access_token = '';
   // Create class object    
   $api = new API();    
   // echo json_encode("2");
   //Get the user id if exists   
/*    if (!$api->validateToken($userid, $token)){  
      // echo json_encode("4");
      $message='Invalid token';
      $statuscode=100;
      http_response_code(401);
   }
   else
   {      */
      $postid = $api->submitPost($userid,$postdata,$parentid,$title,$coverimage,$uploadimages,2);
      
// }

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
