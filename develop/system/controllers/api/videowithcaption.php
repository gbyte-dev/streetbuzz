<?php  

//  die('------');
    require_once('../../helpers/func_main.php');
    require_once('../../conf_system.php');
    require_once('classes/class_api.m3.php');

session_start();

$get_user_id = $_SESSION['NETWORKS_USR_DATA']['1']['LOGGED_USER']; 
$user_id = $get_user_id->id;


        global $db2, $C;
            $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
            
            $link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);
if(isset($_GET['action'])){
$postids = $_GET['postids'];

if($_GET['action']=='delete'){
    // die('delete');
$id = $_GET['id'];

$query = 'DELETE FROM `posts_attachments` WHERE id='.$id.' AND post_id='.$postids;
$res =  $db2->query($query);
if($res){
    echo '';
}else{
    echo '';
}
}
elseif($_GET['action']=='update'){
//   print_r($_FILES); die('--');
    //die('-----');
    $contentss = $_POST['content'];
//     echo $contentss;
// die('update');
$idss = $_GET['id'];

 if(!empty($contentss)){
$query = 'UPDATE `posts_attachments` SET `content`="'.$contentss.'" WHERE id='.$idss.' AND post_id='.$postids;

$res =  $db2->query($query);

}

            if(!empty($_FILES["filethum"]["tmp_name"])){
               // die('fileVal');
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
            $data_video['ff'] = $n->attach_image($upload_name, $avatar_name);
            //print_r($data_video['ff']);
            
    $data_video['idss']=$idss;             
    $data_video['postid']=$postids;     
    $userid = $postids; 
    
    $videos = $upload_name;     
    $parentid = '';
      if ($parentid == null)
      {
        $parentid = 0;
      }
    $message='Success';
    $statuscode=0;
    $oauth_access_token = '';
    $api = new API();
        $postid = $api->editPhotoWithCaption($userid,$messagetitle,$videos,$parentid,$data_video);
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
    

$r= $db2->query('SELECT `id`,`data`,`type`,`content` FROM `posts_attachments` WHERE id='.$idss.' AND post_id='.$postids); 
    
$result = mysqli_fetch_array($r);  
$imagevals=$result['data'];
$tmp = @unserialize(stripslashes($imagevals));
$imagedes = $C->STORAGE_URL.'attachments/1/'.($tmp->file_original);

    
    $append = '<a target="_blank" href="'.$imagedes.'" class="lightbox-image image-thumb cboxElement"><img width="100%" height="250px" alt="Image" src="'.$imagedes.'"></a>';
    
    echo $append;
        }

}else{
    
    $postids = $_GET['action'];
              
    $videos = $upload_name;     
    $parentid = '';
      if ($parentid == null)
      {
        $parentid = 0;
      }
    $images=$_FILES["file"]['name'];
    $statuscode=0;
    $oauth_access_token = '';

            $post_level  = 1;
            if ((int)$parentid==0)
            {
                $post_level = 0;
            } 
            
            $query = 'SELECT attached from posts where id='.$postids;
            $res =  $db2->query($query);
            $result = mysqli_fetch_array($res);  
            
            $totalImages = $result['attached']+count($images);
           
            $db2->query('UPDATE `posts` SET `attached`="'.$totalImages.'" WHERE id='.$postids);
            
            
              $data_video['postid'] = $postids;

              $post_id=$data_video['postid'];
              
              
            $query = 'SELECT who from users_followed where whom='.$user_id;
            $res =  $db2->query($query);
            $followers = $res->fetch_all(MYSQLI_ASSOC);
            

           // $followers = $this->getAllFollowersUserID($user_id);

            // $db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$user_id.','.$post_id.')');
            
            foreach ($followers as $i =>$array_expression)
            {
                $db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$followers[$i]["who"].','.$post_id.')');
            }
            
            
    
            // if(!empty($_FILES["file"]["tmp_name"])){
            for($j=0; $j < count($_FILES["file"]['name']); $j++)
            { 
            $n = new newpost();
            $upload_dir = $C->STORAGE_DIR.'tmp/';
            $server_url = $C->STORAGE_URL.'tmp/';
            $avatar_name = $_FILES["file"]["name"]["$j"];
            $avatar_tmp_name = $_FILES["file"]["tmp_name"]["$j"];
            
            $temp = explode(".", $_FILES["file"]["name"]["$j"]);
            $digits = 17;
            $r_n = rand(pow(10, $digits-1), pow(10, $digits)-1);
            $newfilename1 = $r_n . '.' . end($temp);
            $upload_name = $upload_dir.strtolower($newfilename1);
            $upload_name = preg_replace('/\s+/', '-', $upload_name); 

            $imagecaption="Image";
            move_uploaded_file($avatar_tmp_name , $upload_name); 
            
            $data_video['ff'] = $n->attach_image($upload_name, $avatar_name);
              
              
            //   $data_video['ff'] = $n->attach_image($upload_name, $avatar_name);
              
              
            // $data_video['videothumbnail']=$newfilename1;
            
            
 
    $userid = $user_id;     
    $data_video['caption'] = $_POST['caption']["$j"];
    
    $videos = $upload_name;     
    $parentid = '';
      if ($parentid == null)
      {
        $parentid = 0;
      }
    $message='Success';
    $statuscode=0;
    $oauth_access_token = '';
    $api = new API();
        $postid = $api->submitPhotoWithCaption($userid,$messagetitle,$videos,$parentid,$data_video);
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
        }

 header("Location: ".$C->SITE_URL."dashboard");  
    
    
}





}  else{      
    $postids = $_GET['postids'];        
    $videos = $upload_name;     
    $parentid = '';
      if ($parentid == null)
      {
        $parentid = 0;
      }
    $images=$_FILES["file"]['name'];
    $statuscode=0;
    $oauth_access_token = '';

            $post_level  = 1;
            if ((int)$parentid==0)
            {
                $post_level = 0;
            } 
            
            $db2->query('Insert into posts (user_id,message,date,date_lastcomment,ip_addr,parent_id,post_level,attached,posttype) VALUES ('.$user_id.',"'.mysqli_real_escape_string($link,$postdata).'","'.time().'","'.time().'","'.ip2long($_SERVER['REMOTE_ADDR']).'",'.$parentid.','.$post_level.','.count($images).',1)');
            
            
                        $data_video['postid'] = (int) $db2->insert_id();

              $post_id=$data_video['postid'];
              
              
            $query = 'SELECT who from users_followed where whom='.$user_id;
            $res =  $db2->query($query);
            $followers = $res->fetch_all(MYSQLI_ASSOC);
            

           // $followers = $this->getAllFollowersUserID($user_id);

            $db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$user_id.','.$post_id.')');
            
            foreach ($followers as $i =>$array_expression)
            {
                $db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$followers[$i]["who"].','.$post_id.')');
            }
            
            
    
            // if(!empty($_FILES["file"]["tmp_name"])){
            for($j=0; $j < count($_FILES["file"]['name']); $j++)
            { 
            $n = new newpost();
            $upload_dir = $C->STORAGE_DIR.'tmp/';
            $server_url = $C->STORAGE_URL.'tmp/';
            $avatar_name = $_FILES["file"]["name"]["$j"];
            $avatar_tmp_name = $_FILES["file"]["tmp_name"]["$j"];
            
            $temp = explode(".", $_FILES["file"]["name"]["$j"]);
            $digits = 17;
            $r_n = rand(pow(10, $digits-1), pow(10, $digits)-1);
            $newfilename1 = $r_n . '.' . end($temp);
            $upload_name = $upload_dir.strtolower($newfilename1);
            $upload_name = preg_replace('/\s+/', '-', $upload_name); 

            $imagecaption="Image";
            move_uploaded_file($avatar_tmp_name , $upload_name); 
            
            $data_video['ff'] = $n->attach_image($upload_name, $avatar_name);
              
              
            //   $data_video['ff'] = $n->attach_image($upload_name, $avatar_name);
              
              
            // $data_video['videothumbnail']=$newfilename1;
            
            
 
    $userid = $user_id;     
    $data_video['caption'] = $_POST['caption']["$j"];
    
    $videos = $upload_name;     
    $parentid = '';
      if ($parentid == null)
      {
        $parentid = 0;
      }
    $message='Success';
    $statuscode=0;
    $oauth_access_token = '';
    $api = new API();
        $postid = $api->submitPhotoWithCaption($userid,$messagetitle,$videos,$parentid,$data_video);
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
}
?>