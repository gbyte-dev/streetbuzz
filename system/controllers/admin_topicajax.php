<?php

ini_set('upload_max_filesize', '200M');
ini_set('post_max_size', '200M');                               
ini_set('max_input_time', 3000);                                
ini_set('max_execution_time', 3000);
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	global $db2;
	$startdate =strtotime($_POST['start_date'].'23:59:00');
    $enddate =strtotime($_POST['end_date']."23:59:59");
    $topic_name    = $_POST['topic_name'];
    $description = $_POST['description'];
    $tags        = $_POST['tags'];
    $tpic_category    = $_POST['tpic_category'];
    $topic_location    = $_POST['topic_location'];
    $topic_language    = $_POST['topic_language'];
    if(!empty($_FILES["files"])){
      
    $bigfilename   =time(); // 5dab1961e93a7_1571494241
    $bigextension  = pathinfo( $_FILES["files"]["name"], PATHINFO_EXTENSION ); // jpg
    $bigbasename   = $bigfilename . '.' . $bigextension; // 5dab1961e93a7_1571494241.jpg
    $bigdest = $C->STORAGE_DIR.'topics'.'/'.$bigbasename;
    if(move_uploaded_file($_FILES['files']['tmp_name'],$bigdest)){
        $topic_gallery    = $bigbasename;
            //$db2->query('update ads_info  set big_image="'. $bigbasename .'" where id="'.$adid.'" ');
    
        
    }else{
       $topic_gallery    = '';
    }
    }else{
         $topic_gallery    = '';
    }
   if($_POST['action'] == 'edit'){
       $postid = $_POST['postid'];
      $db2->query('update sb_topics SET  topic_literal="'.$topic_name.'", topic_description="'.$description.'", topic_tags="'.$tags.'", topic_category="'.$tpic_category.'",topic_attach_location="'.$topic_location.'",topic_attach_language="'.$topic_language.'",valid_from	="'.$startdate.'",valid_till	="'.$enddate.'"  where id="'.$postid.'" ');
      if($topic_gallery !=''){
           $db2->query('update sb_topics SET  topic_gallery ="'.$topic_gallery.'"  where id="'.$postid.'" ');
          
      }
       
   }else{
 
    $res = $db2->query('INSERT INTO sb_topics SET  topic_literal="'.$topic_name.'", topic_description="'.$description.'",topic_gallery="'.$topic_gallery.'", topic_tags="'.$tags.'", topic_category="'.$tpic_category.'",topic_attach_location="'.$topic_location.'",topic_attach_language="'.$topic_language.'",valid_from	="'.$startdate.'",valid_till	="'.$enddate.'" ');
   }
    
    if($res){
        $_SESSION['ads_status'] = 1;
    }

   $this->redirect('admin_topics');


?>