<?php
error_reporting(E_ALL);

ini_set('upload_max_filesize', '200M');
ini_set('post_max_size', '200M');                               
ini_set('max_input_time', 3000);                                
ini_set('max_execution_time', 3000);
require_once('classes/class_ssh.php');
/*Archival Database*/
$servername = "182.18.139.51";
$username = "streetbu_sb_live";
$password = "Hanuman321#";
$dbname = "streetbu_sb_live";
$moveurl ="";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//echo "Current character set: ".$conn->character_set_name();die;
/* change character set to utf8 */
if (!$conn->set_charset("utf8")) {
    die("Error loading character set utf8: ".$conn->error);
}

/* SSH Account */
/*$sshhost = '182.18.139.51';
$sshusername = 'root'; 
$sshpassword = 'CE!6xqLSD#c4c$';
$sshport = '2232';
$ssh = new SSH();
try {
    $ssh->connect($sshhost, $sshusername, $sshpassword, $sshport);
}
catch(Exception $e) {
    echo "SSH2 Connection: ".$errorMessage = $e->getMessage();die;
} */
/* End SSH Account*/


$sql = "SELECT * FROM archive_info";
$result = $conn->query($sql);
$res =mysqli_fetch_assoc($result);

$desturl ="/home/sreenivas/public_html/storage/attachments/1";

$today = date("Y-m-d H:i:s");
global $db2;
if ($_POST["month_start_date"]) {
    $startdate =strtotime($_POST['month_start_date'].'00:00:00');
    $enddate =strtotime($_POST['month_end_date']."23:59:59");

    // $newarchivingdays = round(($_POST["archivingdays"]) / 30.4167);
    // $query = $db2->query("SELECT * FROM `posts` WHERE id not in (select id from posts where FROM_UNIXTIME(date,'%Y-%m-%d') >= DATE_FORMAT(now()-interval " . $newarchivingdays . " month,'%Y-%m-%d')) order by id ASC LIMIT 1000");
	$query = $db2->query('SELECT * FROM `posts` WHERE date BETWEEN "'.$startdate.'" AND "'.$enddate.'" order by id ASC ');

	
	
    if($query->num_rows > 0) {   
        while ($result = $db2->fetch_object($query)) {
            $postid = $result->id;
	
            /* post attchments data */
            $postattchmentquery = $db2->query("SELECT * FROM `posts_attachments` WHERE post_id=$postid ");
            //echo $postattchmentquery->num_rows;
            if ($postattchmentquery->num_rows > 0) {
                while ($postattachmentresult = $db2->fetch_object($postattchmentquery)) {
                    $filesarray = unserialize($postattachmentresult->data);
                  
                    if (!is_object($filesarray) && isset($filesarray[0])) {
                        $filesarray = $filesarray[0]; //resolve issue for post id 2767
                    }
                    if(!empty($filesarray)) {
                        if($postattachmentresult->type == 'image' ){
                           
                            if(!file_exists($desturl.'/'.$filesarray->file_original)){
                              
                                try{
                                    if(file_exists($_SERVER['DOCUMENT_ROOT'].'/newsapp/storage/attachments/1/'.$filesarray->file_original)){
                               //     $ssh->move_files("1", $filesarray->file_original);

                                    unlink($C->STORAGE_DIR.'/attachments/1/'.$filesarray->file_original);
                                    }
                                }catch(Exception $e){
                                    $errorMessage =  $e->getMessage();								
                                    $errorMessage =  $db2->escape($errorMessage);					
                                    $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='files',message= '".$errorMessage."',creatrd_date='".$today."' ");

                                }
                            }                                                    

                
                            if(!file_exists($desturl.'/'.$filesarray->file_preview)){
                                try{
                                    if(file_exists($_SERVER['DOCUMENT_ROOT'].'/newsapp/storage/attachments/1/'.$filesarray->file_preview)){
                                          //  $ssh->move_files("1",$filesarray->file_preview);
                                            unlink($C->STORAGE_DIR.'/attachments/1/'.$filesarray->file_preview);
                                    }

                                }catch(Exception $e ){
                                $errorMessage =  $e->getMessage();								
                                    $errorMessage =  $db2->escape($errorMessage);					
                                    $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='files',message= '".$errorMessage."',creatrd_date='".$today."' ");

                                }
                            }
                            if(!file_exists($desturl.'/'.$filesarray->file_thumbnail)) {
                                try{
                                    if(file_exists($_SERVER['DOCUMENT_ROOT'].'/newsapp/storage/attachments/1/'.$filesarray->file_thumbnail)){
                                      //  $ssh->move_files("1",$filesarray->file_thumbnail);
                                        unlink($C->STORAGE_DIR.'/attachments/1/'.$filesarray->file_thumbnail);
                                    }
                                }catch(Exception $e){
                                    $errorMessage =  $e->getMessage();								
                                        $errorMessage =  $db2->escape($errorMessage);					
                                        $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='files',message= '".$errorMessage."',creatrd_date='".$today."' ");

                                }
                            }
                        }
                        if($postattachmentresult->type == 'file' ){

                            try{
                                if(file_exists($_SERVER['DOCUMENT_ROOT'].'/newsapp/storage/attachments/1/'.$filesarray->file_original)){

                                    $ssh->move_files("1", $filesarray->file_original);
                                    unlink($C->STORAGE_DIR.'/attachments/1/'.$filesarray->file_original);
                                }
                            }catch(Exception $e){
                                $errorMessage =  $e->getMessage();								
                                    $errorMessage =  $db2->escape($errorMessage);					
                                    $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='files',message= '".$errorMessage."',creatrd_date='".$today."' ");

                            }

                        } 
				}

                     $checkattachres = "select id from posts_attachments  WHERE id='$postattachmentresult->id' ";
                  
                
                $checkattachresquery = $conn->query($checkattachres);
                if($checkattachresquery->num_rows == 0){
                                
                   $sql ='INSERT INTO  posts_attachments SET  id="'.$postattachmentresult->id.'", post_id="'.$postattachmentresult->post_id.'",type="'.$db2->escape($postattachmentresult->type).'",data="'.$db2->escape($postattachmentresult->data).'",event_status="'.$postattachmentresult->event_status.'" ';
                   if ($conn->query($sql) === TRUE) {
                        $delres =$db2->query("delete from `posts_attachments` WHERE id=$postattachmentresult->id ");
                    } else {
                        $today = date("Y-m-d H:i:s");
					   $conn_error =  $db2->escape($conn->error);
                        $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='post_attachments',message= '".$conn_error."',creatrd_date='".$today."' ");
                    }                                     
                } 
            }
        }

        /*post comments */
        $postcomments = $db2->query("SELECT * FROM `posts_comments` WHERE post_id=$postid ");
		//echo $postcomments->num_rows;
        if ($postcomments->num_rows > 0)
        {
            while ($postcommentsresult = $db2->fetch_object($postcomments))
            {

                $commentid = $postcommentsresult->id;
                $postcomments ="select id from posts_comments  WHERE id='$postcommentsresult->id' ";
                $postcomments = $conn->query($postcomments);

                    if($postcomments->num_rows == 0){
                        $sqlpost = ' INSERT INTO  posts_comments SET  id="'.$postcommentsresult->id.'",api_id="'.$postcommentsresult->api_id.'",post_id="'.$postcommentsresult->post_id.'",user_id="'.$postcommentsresult->user_id.'",message="'.$db2->escape($postcommentsresult->message).'",mentioned="'.$postcommentsresult->mentioned.'",likes="'.$postcommentsresult->likes.'",posttags="'.$postcommentsresult->posttags.'",date="'.$postcommentsresult->date.'",ip_addr="'.$postcommentsresult->ip_addr.'" ';
                        if ($conn->query($sqlpost) === TRUE) {
                            $delres =$db2->query("delete from `posts_comments` WHERE id=$postcommentsresult->id ");

                        } else {
                            $today = date("Y-m-d H:i:s");
                            $conn_error =  $db2->escape($conn->error);
                            $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='post_comments',message= '".$conn_error."',creatrd_date='".$today."' ");
                            
                        } 
                    }

                /*post comments mentioned */
                $postcommentsmentioned = $db2->query("SELECT * FROM `posts_comments_mentioned` WHERE comment_id=$commentid ");
                while ($postcommentsmentionedresult = $db2->fetch_object($postcommentsmentioned))
                {
                        
                    $checkpostcommentsmentioned = "select id from posts_comments_mentioned  WHERE id='$postcommentsmentionedresult->id' ";
                    $checkpostcommentsmentioned = $conn->query($checkpostcommentsmentioned);

                    if($checkpostcommentsmentioned->num_rows == 0){
                        $mentionedsql ='INSERT INTO  posts_comments_mentioned SET id="'.$postcommentsmentionedresult->id.'",comment_id="'.$postcommentsmentionedresult->comment_id.'",user_id="'.$postcommentsmentionedresult->user_id.'" ';
                        if ($conn->query($mentionedsql) === TRUE) {
                                $delres =$db2->query("delete from `posts_comments_mentioned` WHERE id=$postcommentsmentionedresult->id ");

                        } else {
                            $today = date("Y-m-d H:i:s");
                            $conn_error =  $db2->escape($conn->error);
                            $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='posts_comments_mentioned',message= '".$conn_error."',creatrd_date='".$today."' ");
                        }                    
                   
                    }
                }
            }
        }

       /*post comments watch */
        $posts_comments_watch = $db2->query("SELECT * FROM `posts_comments_watch` WHERE post_id=$postid ");

        if ($posts_comments_watch->num_rows > 0)
        {
            while ($posts_comments_watch_result = $db2->fetch_object($posts_comments_watch))
            {
                $archive_posts_comments_watch = "select id from posts_comments_watch  WHERE id='$posts_comments_watch_result->id' ";
                $archive_posts_comments_watch = $conn->query($archive_posts_comments_watch);

                if($archive_posts_comments_watch->num_rows == 0){
                    $watchres ='INSERT INTO  posts_comments_watch SET  id="'.$posts_comments_watch_result->id.'", user_id="'.$posts_comments_watch_result->user_id.'",post_id="'.$posts_comments_watch_result->post_id.'",newcomments="'.$posts_comments_watch_result->newcomments.'" ';
                    if ($conn->query($watchres) === TRUE) {
                        $delres =$db2->query("delete from `posts_comments_watch` WHERE id=$posts_comments_watch_result->id ");

                    } else {
                        $today = date("Y-m-d H:i:s");
                        $conn_error =  $db2->escape($conn->error);
                        $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='posts_comments_watch',message= '".$conn_error."',creatrd_date='".$today."' ");
                    }
                }
            }
        }

     
        /*posts mentioned */
        $posts_mentioned = $db2->query("SELECT * FROM `posts_mentioned` WHERE post_id=$postid ");
        if ($posts_mentioned->num_rows > 0)
        {
            while ($posts_mentioned_result = $db2->fetch_object($posts_mentioned))
            {
                $archive_posts_mentioned = "select id from posts_mentioned  WHERE id ='$posts_mentioned_result->id' ";
                $archive_posts_mentioned = $conn->query($archive_posts_mentioned);

                if($archive_posts_mentioned->num_rows == 0){
                    $res = 'INSERT INTO  posts_mentioned SET id="'.$posts_mentioned_result->id.'", post_id="'.$posts_mentioned_result->post_id.'",user_id="'.$posts_mentioned_result->user_id.'" ';
                    if ($conn->query($res) === TRUE) {
                        $delres =$db2->query("delete from `posts_mentioned` WHERE id=$posts_mentioned_result->id ");

                    } else {
                        $today = date("Y-m-d H:i:s");
                        $conn_error =  $db2->escape($conn->error);
                        $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='posts_mentioned',message= '".$conn_error."',creatrd_date='".$today."' ");
                    }

                }
            }
        }
    
          /*posts spam protector */
        $posts_spamprotector = $db2->query("SELECT * FROM `posts_spamprotector` WHERE post_id=$postid ");
        if ($posts_spamprotector->num_rows > 0)
        {

            while ($posts_spamprotector_result = $db2->fetch_object($posts_spamprotector))
            {
                $archive_posts_spam = "select id from posts_spamprotector  WHERE id ='$posts_spamprotector_result->id' ";
                $archive_posts_spam = $conn->query($archive_posts_spam);
                if($archive_posts_spam->num_rows == 0){
                    $res = 'INSERT INTO  posts_spamprotector SET  id="'.$posts_spamprotector_result->id.'", post_id="'.$posts_spamprotector_result->post_id.'",	post_type="'.$posts_spamprotector_result->post_type.'", marked_by_user_id="'.$posts_spamprotector_result->marked_by_user_id.'",post_author_id="'.$posts_spamprotector_result->post_author_id.'" ';
                    if ($conn->query($res) === TRUE) {
                        $delres =$db2->query("delete from `posts_spamprotector` WHERE id=$posts_spamprotector_result->id ");

                    } else {
                        $today = date("Y-m-d H:i:s");
                        $conn_error =  $db2->escape($conn->error);
                        $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='posts_spamprotector',message= '".$conn_error."',creatrd_date='".$today."' ");
                    
                    }
                }
            }
        }


         /*posts agree  */
        $post_agree = $db2->query("SELECT * FROM `post_agree` WHERE post_id=$postid ");
        if ($post_agree->num_rows > 0)
        {
            while ($post_agree_result = $db2->fetch_object($post_agree))
            {
                $archive_posts_dayfeel ="select id from post_agree  WHERE id ='$post_agree_result->id' ";
                $archive_posts_dayfeel = $conn->query($archive_posts_dayfeel);
                if($archive_posts_dayfeel->num_rows == 0){
					
                    $res = 'INSERT INTO  post_agree SET  id="'.$post_agree_result->id.'", group_id="'.$post_agree_result->group_id.'",user_id="'.$post_agree_result->user_id.'",post_id="'.$post_agree_result->post_id.'",comment_id="'.$post_agree_result->comment_id.'",date="'.$post_agree_result->date.'" ';
                    if ($conn->query($res) === TRUE) {
                        $delres =$db2->query("delete from `post_agree` WHERE id=$post_agree_result->id ");

                    } else {
                        $today = date("Y-m-d H:i:s");
						$conn_error =  $db2->escape($conn->error);
                        $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='posts_agree',message= '".$conn_error."',creatrd_date='".$today."' ");
                    
                    }
                }
            }
        }

        /*posts dayfeel  */
        $post_dayfeel = $db2->query("SELECT * FROM `post_dayfeel` WHERE post_id=$postid ");
        if ($post_dayfeel->num_rows > 0)
        {
            while ($post_dayfeel_result = $db2->fetch_object($post_dayfeel))
            {
                $archive_posts_dayfeel = "select id from post_dayfeel  WHERE 	id	='$post_dayfeel_result->id' ";
                $archive_posts_dayfeel = $conn->query($archive_posts_dayfeel);
                 
                if($archive_posts_dayfeel->num_rows == 0){
                    $res ='INSERT INTO  post_dayfeel SET id="'.$post_dayfeel_result->id.'", post_id="'.$post_dayfeel_result->post_id.'",ticker="'.$post_dayfeel_result->ticker.'",data="'.$db2->escape($post_dayfeel_result->data).'",updated_date="'.$post_dayfeel_result->updated_date.'",predicted_price="'.$post_dayfeel_result->predicted_price.'",stoploss_price="'.$post_dayfeel_result->stoploss_price.'",current_price="'.$post_dayfeel_result->current_price.'",final_price="'.$post_dayfeel_result->final_price.'",result="'.$post_dayfeel_result->result.'",status="'.$post_dayfeel_result->status.'" ';
                    if ($conn->query($res) === TRUE) {
                        $delres =$db2->query("delete from `post_dayfeel` WHERE id=$post_dayfeel_result->id ");

                    } else {
                        $today = date("Y-m-d H:i:s");
                        $conn_error =  $db2->escape($conn->error);
                        $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='posts_dayfeel',message= '".$conn_error."',creatrd_date='".$today."' ");
                    }
                }
            }
        }
        

        /* post_likes  */
        $post_likes = $db2->query("SELECT * FROM `post_likes` WHERE post_id=$postid ");
        if ($post_likes->num_rows > 0)
        {
            while ($post_likes_result = $db2->fetch_object($post_likes))
            {
                $archive_posts_likes ="select id from  post_likes  WHERE 	id ='$post_likes_result->id' ";
                $archive_posts_likes = $conn->query($archive_posts_likes);

                if($archive_posts_likes->num_rows == 0){
                    $res = 'INSERT INTO  post_likes SET  id="'.$post_likes_result->id.'", group_id="'.$post_likes_result->group_id.'",user_id="'.$post_likes_result->user_id.'",post_id="'.$post_likes_result->post_id.'",comment_id="'.$post_likes_result->comment_id.'",date="'.$post_likes_result->date.'" ';
                    if ($conn->query($res) === TRUE) {
                        $delres =$db2->query("delete from `post_likes` WHERE id=$post_likes_result->id ");

                    } else {
                        $today = date("Y-m-d H:i:s");
                        $conn_error =  $db2->escape($conn->error);
                        $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='posts_likes',message= '".$conn_error."',creatrd_date='".$today."' ");
                    
                    }
                }
            }
        }
    
    
        /* post_replay  */
        $post_replay = $db2->query("SELECT * FROM `post_replay` WHERE replay_id=$postid ");
        if ($post_replay->num_rows > 0)
        {
            while ($post_replay_result = $db2->fetch_object($post_replay))
            {
                $archive_posts_replay = "select id from post_replay  WHERE id ='$post_replay_result->id' ";
                $archive_posts_replay = $conn->query($archive_posts_replay);
                
                if($archive_posts_replay->num_rows == 0){
                    $res = 'INSERT INTO  post_replay SET  id="'.$post_replay_result->id.'", parent_id="'.$post_replay_result->parent_id.'",alternate_parent_id="'.$post_replay_result->alternate_parent_id.'",replay_id="'.$post_replay_result->replay_id.'",action_type="'.$post_replay_result->action_type.'",series="'.$db2->escape($post_replay_result->series).'" ';
                    
                    if ($conn->query($res) === TRUE) {
                        $delres =$db2->query("delete from `post_replay` WHERE id=$post_replay_result->id ");

                    } else {
                        $today = date("Y-m-d H:i:s");
                        $conn_error =  $db2->escape($conn->error);
                        $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='posts_replay',message= '".$conn_error."',creatrd_date='".$today."' ");
                    
                    }
                }
            }
        }

        /* post_reshsares  */
        $post_reshares = $db2->query("SELECT * FROM `post_reshares` WHERE post_id=$postid ");
        if ($post_reshares->num_rows > 0) {
            while ($post_reshares_result = $db2->fetch_object($post_reshares))
            {
                $archive_posts_reshares = "select id from  post_reshares  WHERE id ='$post_reshares_result->id' ";
                $archive_posts_reshares = $conn->query($archive_posts_reshares);
                if($archive_posts_reshares->num_rows == 0){
                    $res ='INSERT INTO  post_reshares SET id="'.$post_reshares_result->id.'", post_id="'.$post_reshares_result->post_id.'",user_id="'.$post_reshares_result->user_id.'",date="'.$post_reshares_result->date.'" ';
                    if ($conn->query($res) === TRUE) {
                        $delres =$db2->query("delete from `post_reshares` WHERE id=$post_reshares_result->id ");

                    } else {
                        $today = date("Y-m-d H:i:s");
                        
                        $conn_error =  $db2->escape($conn->error);
                        $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='posts_reshares',message= '".$conn_error."',creatrd_date='".$today."' ");
                    }
                }
            }
        }
   
        /* post_reshsares  */
        $post_tags = $db2->query("SELECT * FROM `post_tags` WHERE post_id=$postid ");
        if ($post_tags->num_rows > 0)
        {
            while ($post_tags_result = $db2->fetch_object($post_tags))
            {
                $archive_posts_tags ="select id from  post_tags  WHERE id ='$post_tags_result->id' ";
                $archive_posts_tags = $conn->query($archive_posts_tags);

                if($archive_posts_tags->num_rows == 0){
                    $res = 'INSERT INTO  post_tags SET  id	="'.$post_tags_result->id.'",tag_name="'.$post_tags_result->tag_name.'",user_id="'.$post_tags_result->user_id.'",group_id="'.$post_tags_result->group_id.'",post_id="'.$post_tags_result->post_id.'",date="'.$post_tags_result->date.'" ';
                    if ($conn->query($res) === TRUE) {
                        $delres =$db2->query("delete from `post_tags` WHERE id=$post_tags_result->id ");

                    } else {
                        $today = date("Y-m-d H:i:s");
                        $conn_error =  $db2->escape($conn->error);
                        $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='posts_tags',message= '".$conn_error."',creatrd_date='".$today."' ");
                    
                    }
                }
            }
        }
    
        /* post_userbox  */
        $post_userbox = $db2->query("SELECT * FROM `post_userbox` WHERE post_id=$postid ");

        if ($post_userbox->num_rows > 0)
        {
            while ($post_userbox_result = $db2->fetch_object($post_userbox))
            {
                $archive_posts_userbox = "select id from  post_userbox  WHERE id ='$post_userbox_result->id' ";
                $archive_posts_userbox = $conn->query($archive_posts_userbox);

            
                if($archive_posts_userbox->num_rows == 0){
                    $res ='INSERT INTO  post_userbox SET  id	="'.$post_userbox_result->id.'",user_id="'.$post_userbox_result->user_id.'",post_id="'.$post_userbox_result->post_id.'",event_status="'.$post_userbox_result->event_status.'",status="'.$post_userbox_result->status.'" ';
                    if ($conn->query($res) === TRUE) {
                        $delres =$db2->query("delete from `post_userbox` WHERE id=$post_userbox_result->id ");
                    } else {
                        $today = date("Y-m-d H:i:s");
                        $conn_error =  $db2->escape($conn->error);						
                        $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='posts_userbox',message= '".$conn_error."',creatrd_date='".$today."' ");
                    }
                }
            }
        }
    
        /* post_views_list  */
        $post_views_list = $db2->query("SELECT * FROM `post_views_list` WHERE post_id=$postid ");
        if ($post_views_list->num_rows > 0)
        {
            while ($post_views_list_result = $db2->fetch_object($post_views_list))
            {
                $archive_posts_viewlist ="select id from  post_views_list  WHERE id ='$post_views_list->id' ";
                $archive_posts_viewlist = $conn->query($archive_posts_viewlist);

                if($archive_posts_viewlist->num_rows == 0){
                    $res = 'INSERT INTO post_views_list SET id	="'.$post_views_list_result->id.'",post_id="'.$post_views_list_result->post_id.'",cnt="'.$post_views_list_result->cnt.'" ';
                    if ($conn->query($res) === TRUE) {
                        $delres =$db2->query("delete from `post_views_list` WHERE id=$post_views_list_result->id ");

                    } else {
                        $today = date("Y-m-d H:i:s");
                        $conn_error =  $db2->escape($conn->error);						
                        $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='posts_view_list',message= '".$conn_error."',creatrd_date='".$today."' ");
                    
                    }
                }
            }
        }

        /* post_views_list  */
        $event_posts = $db2->query("SELECT * FROM `event_posts` WHERE post_id=$postid ");
        if ($event_posts->num_rows > 0)
        {
            while ($event_posts_result = $db2->fetch_object($event_posts))
            {
                $eventid = $event_posts_result->event_id;
                $archive_event_posts = "select id from  event_posts  WHERE id='$event_posts_result->id' ";
                $archive_event_posts = $conn->query($archive_event_posts);

                if($archive_event_posts->num_rows == 0){
                    $res = 'INSERT INTO  event_posts SET  id="'.$event_posts_result->id.'",post_id="'.$event_posts_result->post_id.'",event_id="'.$event_posts_result->event_id.'",created="'.$event_posts_result->created.'",edit_status="'.$event_posts_result->edit_status.'" ';
                        
                    if ($conn->query($res) === TRUE) {
                        $delres =$db2->query("delete from `event_posts` WHERE id=$event_posts_result->id ");
                    } else {
                        $today = date("Y-m-d H:i:s");
                        $conn_error =  $db2->escape($conn->error);						
                        $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='event_post',message= '".$conn_error."',creatrd_date='".$today."' ");
                    }
                } 
                $event_query = $db2->query("SELECT * FROM `events` WHERE id=$eventid ");

                if ($event_query->num_rows > 0) {
                    while ($event_result = $db2->fetch_object($event_query))
                    {
                        $archive_events ="select id from  events  WHERE id ='$event_result->id' ";
                        $archive_events = $conn->query($archive_events);

                        if($archive_events->num_rows == 0){
                            if(!empty($event_result->event_id)){
                                $eventid =$event_result->event_id;
                            }else{
                                $eventid =0;
                            }
                            $res ='INSERT INTO  events SET id="'.$event_result->id.'",event_id="'.$eventid.'",created_at="'.$event_result->created_at.'",modified_at="'.$event_result->modified_at.'",group_id="'.$event_result->group_id.'",admin_id="'.$event_result->admin_id.'",event_type="'.$event_result->event_type.'",address="'.$db2->escape($event_result->address).'",location="'.$db2->escape($event_result->location).'",event_name="'.$db2->escape($event_result->event_name).'",event_description="'.$db2->escape($event_result->event_description).'",start_date="'.$event_result->start_date.'",start_time="'.$event_result->start_time.'",end_date="'.$event_result->end_date.'",end_time="'.$event_result->end_time.'",time_zone="'.$event_result->time_zone.'",activity_pub_date="'.$event_result->activity_pub_date.'",publish_now="'.$event_result->publish_now.'",publish_date="'.$event_result->publish_date.'",is_private="'.$event_result->is_private.'",status="'.$event_result->status.'",street_group="'.$event_result->street_group.'",street_user="'.$event_result->street_user.'",tag_name="'.$db2->escape($event_result->tag_name).'",url="'.$event_result->url.'" ';
                            if ($conn->query($res) === TRUE) {
                                $delres =$db2->query("delete from `events` WHERE id=$event_result->id ");
                            } else {
                                $today = date("Y-m-d H:i:s");
                                $conn_error =  $db2->escape($conn->error);						
                                $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='events',message= '".$conn_error."',creatrd_date='".$today."' ");
                            }
                        }
                    }
                }
                $eventattachment_query = $db2->query("SELECT * FROM `event_attachemnts` WHERE event_id=$eventid ");
                if ($eventattachment_query->num_rows > 0)
                {

                    while ($eventattachment_result = $db2->fetch_object($eventattachment_query))
                    {
                        $archive_events_attachments = "select id from  event_attachemnts  WHERE id ='$eventattachment_result->id' ";
                        $archive_events_attachments = $conn->query($archive_events_attachments);
                        
                        if($archive_events_attachments->num_rows == 0){
                            $res ='INSERT INTO  event_attachemnts SET id="'.$eventattachment_result->id.'",event_id="'.$eventattachment_result->event_id.'",user_id="'.$eventattachment_result->user_id.'",attachment_type="'.$eventattachment_result->attachment_type.'",filename="'.$eventattachment_result->filename.'",file_size="'.$eventattachment_result->file_size.'",file_type="'.$eventattachment_result->file_type.'",link="'.$eventattachment_result->link.'",thumb_link="'.$eventattachment_result->thumb_link.'" ';
                            if ($conn->query($res) === TRUE) {
                                $delres =$db2->query("delete from `event_attachemnts` WHERE id=$eventattachment_result->id ");

                            } else {
                                $today = date("Y-m-d H:i:s");
                                $conn_error =  $db2->escape($conn->error);						
                                $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='event_attachments',message= '".$conn_error."',creatrd_date='".$today."' ");
                            
                            }
                        }
                    }
                }             
            }
        }
       

        $pollsquery = $db2->query("SELECT * FROM `polls` WHERE posts_id=$postid ");

        if ($pollsquery->num_rows > 0) {
            while ($poll_result = $db2->fetch_object($pollsquery))
            {               
                $pollid = $poll_result->poll_id;
                $archive_poll_result = "select id from  polls  WHERE id='$poll_result->id' ";
                $archive_poll_result = $conn->query($archive_poll_result);
                
                if($archive_poll_result->num_rows == 0){
                    $res = 'INSERT INTO  polls SET   id="'.$poll_result->id.'",poll_id="'.$poll_result->poll_id.'",poll_date="'.$poll_result->poll_date.'",poll_question="'.$poll_result->poll_question.'",poll_is_active="'.$poll_result->poll_is_active.'",poll_allow_user_answer="'.$poll_result->poll_allow_user_answer.'",posts_id="'.$poll_result->posts_id.'" ';
                    if ($conn->query($res) === TRUE) {
                        $delres =$db2->query("delete from `polls` WHERE id=$poll_result->id ");
                    } else {
                    }
                }
                $pollanswers_query = $db2->query("SELECT * FROM `polls_answers` WHERE poll_id=$pollid ");
                if ($pollanswers_query->num_rows > 0) {
                    while ($pollsanswers_result = $db2->fetch_object($pollanswers_query))
                    {
                        $archive_poll_answer = "select 	poll_answer_id from  polls_answers  WHERE poll_answer_id ='$pollsanswers_result->poll_answer_id' ";
                        $archive_poll_answer = $conn->query($archive_poll_answer);
                        if($archive_poll_answer->num_rows == 0){
                            $res = 'INSERT INTO polls_answers SET  poll_answer_id="'.$pollsanswers_result->	poll_answer_id.'",poll_id="'.$pollsanswers_result->poll_id.'",answer="'.$pollsanswers_result->answer.'",votes="'.$pollsanswers_result->votes.'" ';
                            if ($conn->query($res) === TRUE) {
                                $delres =$db2->query("delete from `polls_answers` WHERE poll_answer_id=$pollsanswers_result->poll_answer_id ");

                            } else {
                                $today = date("Y-m-d H:i:s");
                                $conn_error =  $db2->escape($conn->error);						
                                $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='poll_answers',message= '".$conn_error."',creatrd_date='".$today."' ");
                            
                            }                   
                        }
                    }
                }
                
                $poll_user_query = $db2->query("SELECT * FROM `post_poll_votes` WHERE POLL_ID=$pollid ");
                if ($poll_user_query->num_rows > 0)
                {
                    while ($poll_user_query_result = $db2->fetch_object($poll_user_query))
                    {
                        $archive_poll_user = "select ID from  post_poll_votes  WHERE ID ='$poll_user_query_result->ID' ";
                        $archive_poll_user = $conn->query($archive_poll_user);
                                
                        if($archive_poll_user->num_rows == 0){
                            $res = 'INSERT INTO post_poll_votes SET  ID="'.$poll_user_query_result->ID.'",POLL_ID="'.$poll_user_query_result->POLL_ID.'",ANSWER_ID="'.$poll_user_query_result->ANSWER_ID.'",VOTER_USER_ID="'.$poll_user_query_result->VOTER_USER_ID.'" ';
                            if ($conn->query($res) === TRUE) {
                                $delres =$db2->query("delete from `post_poll_votes` WHERE ID=$poll_user_query_result->ID ");

                            } else {
                                $today = date("Y-m-d H:i:s");
                                $conn_error =  $db2->escape($conn->error);						
                                $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='post_poll_votes',message= '".$conn_error."',creatrd_date='".$today."' ");
                            }                            
                        }                    
                    }
                }
            }
        }

        
        $archive_posts = "select id from  posts  WHERE id='$result->id' ";
        $archive_posts = $conn->query($archive_posts);
        //echo $archive_posts->num_rows;die;
        
        if($archive_posts->num_rows == 0) {
            $message =  $db2->escape($result->message);					
            if(!empty($result->post_level)){                
                $res = 'INSERT INTO  posts SET  id="'.$result->id.'",api_id="'.$result->api_id.'",user_id="'.$result->user_id.'",group_id="'.$result->group_id.'",message="'.$message.'",mentioned="'.$result->mentioned.'",attached="'.$result->attached.'",posttags="'.$result->posttags.'",comments="'.$result->comments.'",reshares="'.$result->reshares.'",likes="'.$result->likes.'",date="'.$result->date.'",date_lastedit="'.$result->date_lastedit.'",date_lastcomment="'.$result->date_lastcomment.'",ip_addr="'.$result->ip_addr.'",group_name="'.$result->group_name.'",parent_id="'.$result->parent_id.'",status="'.$result->status.'",post_level="'.$result->post_level.'",location="'.$result->location.'",thumb="'.$result->thumb.'" ';
            }else{
                $res = 'INSERT INTO  posts SET  id="'.$result->id.'",api_id="'.$result->api_id.'",user_id="'.$result->user_id.'",group_id="'.$result->group_id.'",message="'.$message.'",mentioned="'.$result->mentioned.'",attached="'.$result->attached.'",posttags="'.$result->posttags.'",comments="'.$result->comments.'",reshares="'.$result->reshares.'",likes="'.$result->likes.'",date="'.$result->date.'",date_lastedit="'.$result->date_lastedit.'",date_lastcomment="'.$result->date_lastcomment.'",ip_addr="'.$result->ip_addr.'",group_name="'.$result->group_name.'",parent_id="'.$result->parent_id.'",status="'.$result->status.'",location="'.$result->location.'",thumb="'.$result->thumb.'" ';
            } 
            if ($conn->query($res) === TRUE) {
                $delres =$db2->query("delete from `posts` WHERE id=$result->id ");
            } else {                
                $today = date("Y-m-d H:i:s");
                $conn_error =  $db2->escape($conn->error);						
                $delres =$db2->query("INSERT INTO archive_logs SET post_id =$result->id,type='posts',message= '".$conn_error."',creatrd_date='".$today."' ");
            
            }
        }  else {
            $delres = $db2->query("delete from `posts` WHERE id=$result->id ");
        }         
        
        /* $res = $db2->query('INSERT INTO   archive_info SET  start_date	="'.$startdate.'",end_date="'.$enddate.'",created_date="'.time().'" ');*/
    }
}
 $res = $db2->query('INSERT INTO   archive_info SET  start_date	="'.$startdate.'",end_date="'.$enddate.'",created_date="'.time().'" ');
 mysqli_close($conn); 

 $data = array("status"=>200);
 echo json_encode($data);
}

?>
