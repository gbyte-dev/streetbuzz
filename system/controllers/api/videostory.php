<?php 

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
  require_once('../../vendor/autoload.php');
  
/////tetst
   require_once('../../helpers/func_main.php');
   require_once('../../conf_system.php');
   require_once('classes/class_api.m3.php');
 
  use FFMpeg\FFMpeg;
  use Aws\S3\S3Client;
  use Aws\S3\Exception\S3Exception;

   $bucket = 'streetbuzzbucket';
   $keyname = 'path/to/image.jpg';
   //$filename = '/path/to/local/image.jpg';
   $region = 'ap-south-1';
   $version = 'latest';
   $accessKeyId = 'AKIAXABO2PU7FU5OIEMB';
   $secretAccessKey = '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';
  $video_url="";
   
   if(isset($_GET['action'])){

   session_start();
   
   $get_user_id = $_SESSION['NETWORKS_USR_DATA']['1']['LOGGED_USER']; 
   $user_id = $get_user_id->id;
   
   $postid = $_GET['postid'];
   
   
           if(!empty($_FILES["filethum"]["tmp_name"])){
           $n = new newpost();
           $upload_dir = $C->STORAGE_DIR.'attachments/1/';
           $server_url = $C->STORAGE_URL.'attachments/1/';
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
           $info = getimagesize($avatar_tmp_name);
            if ($info['mime'] == 'image/jpeg'){ 
                   $image = imagecreatefromjpeg($avatar_tmp_name);
            }

  elseif ($info['mime'] == 'image/gif') {
    $image = imagecreatefromgif($avatar_tmp_name);
  }

  elseif ($info['mime'] == 'image/png'){ 
    $image = imagecreatefrompng($avatar_tmp_name);
  }

  imagejpeg($image, $upload_name, 60);
           $videothumbnail=$newfilename1;
       }
   
           if(!empty($_FILES["file"]["tmp_name"])){
           $n = new newpost();
           $upload_dir = $C->STORAGE_DIR.'attachments/1/';
           $server_url = $C->STORAGE_URL.'attachments/1/';
           $avatar_name = $_FILES["file"]["name"];
           $avatar_tmp_name = $_FILES["file"]["tmp_name"];
           
          // Instantiate an Amazon S3 client
   $s3 = new S3Client([
    'version' => $version,
    'region'  => $region,
    'credentials' => [
        'key'    => $accessKeyId,
        'secret' => $secretAccessKey,
    ],
  ]);

   try {
    // Upload data.
    $result = $s3->putObject([
        'Bucket' => $bucket,
        'Key'    => $avatar_name,
        'SourceFile' => $avatar_tmp_name,
       
    ]);

    // Print the URL of the uploaded image

     $video_url=$result['ObjectURL'];

    } catch (S3Exception $e) {
    // Catch an S3 specific exception.
   
    echo $e->getMessage();
    } 
           
           
      // aws upload over;     
           
           
           
           $temp = explode(".", $_FILES["file"]["name"]);
           $digits = 17;
           $r_n = rand(pow(10, $digits-1), pow(10, $digits)-1);
           $newfilename = $r_n . '.' . end($temp);
           $upload_name = $upload_dir.strtolower($newfilename);
           $upload_name = preg_replace('/\s+/', '-', $upload_name); 
   
           $imagecaption="Video";
          // move_uploaded_file($avatar_tmp_name , $upload_name);            
           $data_video = $n->attach_file($upload_name, $avatar_name);
   
   
       }
   
   
   $userid = $user_id;     
   //   $token = $data["post"]["token"]; 
   $messagetitle = $_POST['video_discription'];
   $videos = $upload_name;
   // $parentid = $data["post"]["parentid"];     
   $parentid = '';
         $videocaption = $_POST['videocaption'];

   
     if ($parentid == null)
     {
       $parentid = 0;
    }
   
   
   $message='Success';
   $statuscode=0;
   $oauth_access_token = '';
   
   
   $api = new API();
   
      $data_vide="";
   $videos="";
       $postid = $api->editsubmitVideoStoryPost_web($userid,$messagetitle,$videos,$postid,$data_video,$videothumbnail,$videocaption,$video_url);
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
  // echo json_encode($response);
   
      header("Location: ".$C->SITE_URL."dashboard");

   }
   
   
   else{
   session_start();
   $get_user_id = $_SESSION['NETWORKS_USR_DATA']['1']['LOGGED_USER']; 
   $user_id = $get_user_id->id;
   
           if(!empty($_FILES["filethum"]["tmp_name"])){
           $n = new newpost();
           $upload_dir = $C->STORAGE_DIR.'attachments/1/';
           $server_url = $C->STORAGE_URL.'attachments/1/';
           $avatar_name = $_FILES["filethum"]["name"];
           $avatar_tmp_name = $_FILES["filethum"]["tmp_name"];
           
           $temp = explode(".", $_FILES["filethum"]["name"]);
           $digits = 17;
           $r_n = rand(pow(10, $digits-1), pow(10, $digits)-1);
           $newfilename1 = $r_n . '.' . end($temp);
           $upload_name = $upload_dir.strtolower($newfilename1);
           $upload_name = preg_replace('/\s+/', '-', $upload_name); 
   
           $imagecaption="Image";
            $info = getimagesize($avatar_tmp_name);
            if ($info['mime'] == 'image/jpeg'){ 
                   $image = imagecreatefromjpeg($avatar_tmp_name);
            }

  elseif ($info['mime'] == 'image/gif') {
    $image = imagecreatefromgif($avatar_tmp_name);
  }

  elseif ($info['mime'] == 'image/png'){ 
    $image = imagecreatefrompng($avatar_tmp_name);
  }

  imagejpeg($image, $upload_name, 60);
          // move_uploaded_file($avatar_tmp_name , $upload_name);            
           //   $ii = $n->attach_image($upload_name, $avatar_name);
          // $data_video['videothumbnail']=$newfilename1;
                  $videothumbnail=$newfilename1;

       }
       
   
           if(!empty($_FILES["file"]["tmp_name"])){

           $n = new newpost();
           $upload_dir = $C->STORAGE_DIR.'attachments/1/';
           $server_url = $C->STORAGE_URL.'attachments/1/';
           $avatar_name = $_FILES["file"]["name"];
           $avatar_tmp_name = $_FILES["file"]["tmp_name"];
         //  $cmdf = "ffmpeg -i $avatar_name -vcodec libx264 -crf 20 output.mp4;

          // $fg = shell_exec($cmdf);
           //print_r($fg);exit;
           
           
           // just for checking 
          /* $ffmpeg = FFMpeg::create();
           $video = $ffmpeg->open($avatar_tmp_name);
           $video->filters()->resize(new \FFMpeg\Coordinate\Dimension(640, 480))->synchronize();
            $video->save(new \FFMpeg\Format\Video\X264(),$avatar_name);*/
           
          /* die("===========");*/
         // just for checking over 
            $temp = explode(".", $_FILES["file"]["name"]);
           $digits = 17;
           $r_n = rand(pow(10, $digits-1), pow(10, $digits)-1);
           $newfilename = $r_n . '.' . end($temp);
       
                   // Instantiate an Amazon S3 client
   $s3 = new S3Client([
    'version' => $version,
    'region'  => $region,
    'credentials' => [
        'key'    => $accessKeyId,
        'secret' => $secretAccessKey,
    ],
  ]);

   try {
    // Upload data.
    $result = $s3->putObject([
        'Bucket' => $bucket,
        'Key'    => $newfilename,
        'SourceFile' => $avatar_tmp_name,
       
    ]);

    // Print the URL of the uploaded image

    $video_url=$result['ObjectURL'];
 //echo $video_url; die('-------------');
    } catch (S3Exception $e) {
    // Catch an S3 specific exception.
   
    echo $e->getMessage();
    } 
           
           
      // aws upload over;  
           

           
        //   $temp = explode(".", $_FILES["file"]["name"]);
        //   $digits = 17;
        //   $r_n = rand(pow(10, $digits-1), pow(10, $digits)-1);
        //   $newfilename = $r_n . '.' . end($temp);
        //   $upload_name = $upload_dir.strtolower($newfilename);
        //   $upload_name = preg_replace('/\s+/', '-', $upload_name); 
   
           $imagecaption="Video";
          
         //  move_uploaded_file($avatar_tmp_name , $upload_name);  

        //   $data_video = $n->attach_file($upload_name, $avatar_name,"file",$newfilename);
       }
/*   echo $upload_name.'==========';
   //$data_video['file_original']=$newfilename;
   
      print_r($data_video);

   die('==========');*/
   
   $userid = $user_id;     
   $messagetitle = $_POST['video_discription'];
      $videocaption = $_POST['videocaption'];

   $videos = $upload_name;
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
   $data_vide="";
   $videos="";
       $postid = $api->submitVideoStoryPost_web($userid,$messagetitle,$videos,$parentid,$data_video,$videothumbnail,$videocaption,$video_url);
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
   
   
   
   //echo json_encode($response);
   }
   
   header("Location: ".$C->SITE_URL."dashboard");
   ?>