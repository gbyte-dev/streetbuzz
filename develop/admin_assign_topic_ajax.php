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
    if(!empty($_POST['postId'])){
       $postId =  $_POST['postId'];
       $topicId =  $_POST['topicId'];
       $res = $db2->query('update posts set 	topic_id="'.$topicId.'" WHERE id="'.$postId.'" ');
       if($res){
          echo 200; 
       }else{
           echo 401;  
       }

    }
?>