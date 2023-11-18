<?php 


error_reporting(E_ALL);
ini_set('display_errors', 'On');
error_reporting(1);

header("Access-Control-Allow-Origin: *");        
   	if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
        exit(0);
    }
$messagetitle=$_REQUEST['message'];
$message_textarea=$_REQUEST['messages'];

    require_once('../../helpers/func_main.php');
    require_once('../../conf_system.php');
    require_once('classes/class_api.m3.php');
  

session_start();

$get_user_id = $_SESSION['NETWORKS_USR_DATA']['1']['LOGGED_USER']; 
$user_id = $get_user_id->id;
//print_r($user_id);


  global $db2, $C;
  $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
            
$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);

// $messagetitle = $_POST['message'];
//echo $data=$_GET['action'];
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
        $postid = $api->editPhotoWithCaption($userid,$messagetitle,$videos,$parentid,$data_video,$message_textarea);
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


}
else{
//     print_r($_FILES);
//   die('-------'); 
$MSGG = $_POST['textariatitle'];
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
            $db2->query('UPDATE `posts` SET `title`="'.$MSGG.'", `attached`="'.$totalImages.'",`message`="'.$message_textarea.'" WHERE id='.$postids);
            
            
              $data_video['postid'] = $postids;

              $post_id=$data_video['postid'];
              
              
            $query = 'SELECT who from users_followed where whom='.$user_id;
            $res =  $db2->query($query);
            $followers = $res->fetch_all(MYSQLI_ASSOC);
            

           // $followers = $this->getAllFollowersUserID($user_id);

            // $db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$user_id.','.$post_id.')');
            
            // foreach ($followers as $i => $array_expression)
            // {
            //     $db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$followers[$i]["who"].','.$post_id.')');
            // }
            
            
    
            // if(!empty($_FILES["file"]["tmp_name"])){
            for($j=0; $j < count($_FILES["file"]['name']); $j++)
            { 
            $n = new newpost();
            $upload_dir = $C->STORAGE_DIR.'tmp/';
            $server_url = $C->STORAGE_URL.'tmp/';
            $avatar_name = $_FILES["file"]["name"]["$j"];
            if(!empty($avatar_name)){
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
            }
            
   // echo json_encode($response);
        }

 header("Location: ".$C->SITE_URL."dashboard");  
    
    
}





}  else{    
    
    $postids = $_GET['postids'];       
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


            $db2->query('Insert into posts (user_id,title,date,date_lastcomment,ip_addr,parent_id,post_level,attached,posttype,message) VALUES ('.$user_id.',"'.$messagetitle.'","'.time().'","'.time().'","'.ip2long($_SERVER['REMOTE_ADDR']).'",'.$parentid.','.$post_level.','.count($images).',1,"'.$message_textarea.'")');
            
                $data_video['postid'] = (int) $db2->insert_id();


              $post_id=$data_video['postid'];
            
             
                     //notification start
 
                       	$followers=$db2->query('select who FROM users_followed WHERE whom='.$get_user_id->id.'');
              	 
                      	 
		//	$vla=$this->db2->fetch_object($followers);
	
	 	$vla=mysqli_fetch_all($followers);
	 
	        
     $network = & $GLOBALS['network'];
		foreach($vla as $vl){
   $rules=$db2->query('select ntf_me_if_u_follow_buzz FROM users_notif_rules WHERE user_id='.$vl[0].'');
		   
		   $vlt=mysqli_fetch_assoc($rules);
		   
		   
 	  if($vlt['ntf_me_if_u_follow_buzz']==1 || $vlt['ntf_me_if_u_follow_buzz']==2 ){
 	$notifytype='buzz';
     $type='buzz';
 	$standardnotifytype='ntf_me_if_u_follow_buzz_photo';
    // $newisert =insert_active_profilenotifications1($vl[0],$post_id,$notifytype,$type,$standardnotifytype);
    
    
      $sql_user1 = 'SELECT * FROM users WHERE  id="' .$get_user_id->id. '"';
      $res_user1 = $db2->query($sql_user1);
	 $obj_user1 = $db2->fetch_object($res_user1);
    
                $data = array();
					$data['id'] = $get_user_id->id;
					$data['postid'] = $post_id;
					$data['notification_type'] = 'photostory';
					$data['username'] = $obj_user1->username;
					send_push_notification($vl[0], $data);
    
    
    
    $ownuserid=$vl[0];
    $postid = $post_id;
    $notifytype = $notifytype;
    $type = $type;
    $standrdtype=$standardnotifytype;
    
    
    //$db2->query(
    
    	$date =time();
     $db2->query('insert into active_notifications  values ("","","'. $get_user_id->id .'","'.$ownuserid.'","'.$postid.'","'.$notifytype.'","'.$type.'","'.$date.'")');
 
		$groupid =0;
		$notif_object_type ='post';
 	$notif_object_id =$get_user_id->id;
 
 
		$db2->query('insert into notifications  (notif_type, to_user_id, in_group_id, from_user_id,notif_object_type,notif_object_id,date) values  ("'.$standrdtype.'","'. $ownuserid .'","'.$groupid.'","'.$get_user_id->id.'","'.$notif_object_type.'","'.$postid.'","'.$date.'")');
 


  	

		  $notifytype='notifications';
			$userdash =  $db2->fetch_field('SELECT newposts  FROM users_dashboard_tabs WHERE user_id="'.$ownuserid.'" AND tab="'.$notifytype.'"  ');
			
			//	die('hhhhhhh');

			
		if(!empty($userdash)){
			$newpost = $userdash+1;
			$db2->query('update users_dashboard_tabs set 	newposts="'.$newpost.'" WHERE user_id="'.$ownuserid.'" ');

			
		}else{
			$tab ="notifications";
			 $state = 1;
			 $db2->query('insert into users_dashboard_tabs  values ("'.$ownuserid.'","'. $tab .'","'.$state.'","'.$state.'")');

			
		}

    
		  }
      
    //   die('fffffff');
		   }       
                       // end   notification
              
              
              
              
              
              
              
            $query = 'SELECT who from users_followed where whom='.$user_id;
            $res =  $db2->query($query);
            $followers = $res->fetch_all(MYSQLI_ASSOC);
            
            // print_r(count($_FILES['file']['name']));
            // die('============='); 

           // $followers = $this->getAllFollowersUserID($user_id);

            $db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$user_id.','.$post_id.')');
            
            // foreach ($followers as $i =>$array_expression)
            // {
            //     $db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$followers[$i]["who"].','.$post_id.')');
            // }
            
    
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
              
    //echo $server_url.$newfilename1; 

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