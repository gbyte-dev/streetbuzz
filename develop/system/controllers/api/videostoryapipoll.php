<?php   

    require_once('../../helpers/func_main.php');
    require_once('../../conf_system.php');
    require_once('classes/class_api.m3.php');

if(isset($_GET['action'])){
    echo "hellooooo";
    
session_start();

$get_user_id = $_SESSION['NETWORKS_USR_DATA']['1']['LOGGED_USER']; 
$user_id = $get_user_id->id;

$postid = $_GET['postid'];


            if(!empty($_FILES["filethum"]["tmp_name"])){
            $n = new newpost();
            $upload_dir = $C->STORAGE_DIR.'tmp/';
            $server_url = $C->STORAGE_URL.'tmp/';
            $avatar_name = $_FILES["filethum"]["name"];
            $avatar_tmp_name = $_FILES["filethum"]["tmp_name"];
            
            $temp = explode(".", $_FILES["filethum"]["name"]);
            $digits = 17;
            $r_n = rand(pow(10, $digits-1), pow(10, $digits)-1);
            $newfilename1 = $r_n . '.' . end($temp);
            $upload_name = $upload_dir.strtolower($newfilename1);
            $upload_name = preg_replace('/\s+/', '-', $upload_name); 

            $imagecaption="Image";
            move_uploaded_file($avatar_tmp_name , $upload_name);            
            //   $ii = $n->attach_image($upload_name, $avatar_name);
            $data_video['videothumbnail']=$newfilename1;
        }

            if(!empty($_FILES["file"]["tmp_name"])){
                       // die('--------');
            $n = new newpost();
            $upload_dir = $C->STORAGE_DIR.'tmp/';
            $server_url = $C->STORAGE_URL.'tmp/';
            $avatar_name = $_FILES["file"]["name"];
            $avatar_tmp_name = $_FILES["file"]["tmp_name"];
            
            $temp = explode(".", $_FILES["file"]["name"]);
            $digits = 17;
            $r_n = rand(pow(10, $digits-1), pow(10, $digits)-1);
            $newfilename = $r_n . '.' . end($temp);
            $upload_name = $upload_dir.strtolower($newfilename);
            $upload_name = preg_replace('/\s+/', '-', $upload_name); 

            $imagecaption="Video";
            move_uploaded_file($avatar_tmp_name , $upload_name);            
            $data_video['ff'] = $n->attach_file($upload_name, $avatar_name, "file");

    
        }

 
    $userid = $user_id;     
    //   $token = $data["post"]["token"]; 
    $messagetitle = $_POST['video_discription'];
    $videos = $upload_name;
    // $parentid = $data["post"]["parentid"];     
    $parentid = '';


      if ($parentid == null)
      {
        $parentid = 0;
     }


    $message='Success';
    $statuscode=0;
    $oauth_access_token = '';


    $api = new API();
   

        $postid = $api->editsubmitVideoStoryPost($userid,$messagetitle,$videos,$postid,$data_video);
        $message='Success';
        $statuscode=0;

    $response=array();
    $response["status"]=array();
    $status =array(
            "message" => $message,
            "statuscode" => $statuscode
        );

    $response["status"] = $status;
    $response["post"] = $postid;
    http_response_code(200);
    
    

    echo json_encode($response);
    
}else{



   
session_start();

$get_user_id = $_SESSION['NETWORKS_USR_DATA']['1']['LOGGED_USER']; 
$user_id = $get_user_id->id;

            if(!empty($_FILES["filethum"]["tmp_name"])){
            $n = new newpost();
            $upload_dir = $C->STORAGE_DIR.'tmp/';
            $server_url = $C->STORAGE_URL.'tmp/';
            $avatar_name = $_FILES["filethum"]["name"];
            $avatar_tmp_name = $_FILES["filethum"]["tmp_name"];
            
            $temp = explode(".", $_FILES["filethum"]["name"]);
            $digits = 17;
            $r_n = rand(pow(10, $digits-1), pow(10, $digits)-1);
            $newfilename1 = $r_n . '.' . end($temp);
            $upload_name = $upload_dir.strtolower($newfilename1);
            $upload_name = preg_replace('/\s+/', '-', $upload_name); 

            $imagecaption="Image";
            move_uploaded_file($avatar_tmp_name , $upload_name);            
            //   $ii = $n->attach_image($upload_name, $avatar_name);
            $data_video['videothumbnail']=$newfilename1;
        }
        

            if(!empty($_FILES["file"]["tmp_name"])){
            $n = new newpost();
            $upload_dir = $C->STORAGE_DIR.'tmp/';
            $server_url = $C->STORAGE_URL.'tmp/';
            $avatar_name = $_FILES["file"]["name"];
            $avatar_tmp_name = $_FILES["file"]["tmp_name"];
            
            $temp = explode(".", $_FILES["file"]["name"]);
            $digits = 17;
            $r_n = rand(pow(10, $digits-1), pow(10, $digits)-1);
            $newfilename = $r_n . '.' . end($temp);
            $upload_name = $upload_dir.strtolower($newfilename);
            $upload_name = preg_replace('/\s+/', '-', $upload_name); 

            $imagecaption="Video";
            move_uploaded_file($avatar_tmp_name , $upload_name);            
            $data_video['ff'] = $n->attach_file($upload_name, $avatar_name);
        }

 
    $userid = $user_id;     
    //   $token = $data["post"]["token"]; 
    $messagetitle = $_POST['video_discription'];
    $videos = $upload_name;
    // $parentid = $data["post"]["parentid"];     
    $parentid = '';


      if ($parentid == null)
      {
        $parentid = 0;
     }


    //Set default values
    $message='Success';
    $statuscode=0;
    $oauth_access_token = '';

    // Create class object
    $api = new API();
    //Get the user id if exists
    
    
 /*   if (!$api->validateToken($userid, $token))
    {
        $message='Invalid token';
        $statuscode=100;
        http_response_code(401);
        
    }
    else
    {*/
        // $userid;
        // $messagetitle;
        
        /*foreach ($images as $image) {
            echo "\n";
            echo "\ncaption==".$image["caption"];
            echo "\nimage  ==".$image["image"];
        }
        echo "\n";
        echo "aaaa";
        exit;*/
// $videos=array(
//     'video'=>$videos
//     );


        $postid = $api->submitVideoStoryPost($userid,$messagetitle,$videos,$parentid,$data_video);
        $message='Success';
        $statuscode=0;
   /* }*/

    $response=array();
    $response["status"]=array();
    $status =array(
            "message" => $message,
            "statuscode" => $statuscode
        );

    $response["status"] = $status;
    $response["post"] = $postid;
    http_response_code(200);
    
    

    echo json_encode($response);
}

 header("Location: ".$C->SITE_URL."dashboard");
?>