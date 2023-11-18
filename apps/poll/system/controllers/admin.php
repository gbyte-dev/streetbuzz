<?php  

if( !$this->network->id ) {
	$this->redirect('home');
}
if( !$this->user->is_logged ) {
	$this->redirect('signin');
}
$tmp = array(
            '##BEFORE##' =>'',
            '##HOUR##' => 'hour',
            '##HOURS##' =>'hours',
            '##MIN##' => 'min',
            '##MINS##' =>'min',
            '##SEC##' =>'sec',
            '##SECS##' =>'sec',
            '##AND##' =>'and',
            '##AGO##' =>'ago',
            '##NOW##' =>'just published'
        );


if(!empty($_POST['answerid']))
	{
		$obj=$data[0];
	$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);

		$db2 = & $this->network->db2;
		$userid=$user->id;
		$answerid=$_POST['answerid'];
		$pollid=$_POST['pollid'];
		$this->db2->query("INSERT INTO post_poll_votes SET 
							POLL_ID = '".$pollid."', 
							ANSWER_ID = '".$answerid."', 
							VOTER_USER_ID = '".$userid."'", FALSE);
		//echo $query="delete from post_poll_votes";
		//$this->db2->query($query,true);
		$poll_id = $this->db2->insert_id();
		
		//$this->redirect($C->SITE_URL);

		//counting of answer
	$pollper =$buff->getpercentagesofpollanswers($pollid);
	$totalpollcnt =$buff->totalpollcnt($pollid);
	$uservote  =$buff->userpollanswer($user->id,$pollid);
	$pollhtml ='';
	$pollhtml .='<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content">';


 foreach($pollper as $keys=>$vals){
	 if($vals->answer !=''){
	 $percentage = ($vals->cnt/$totalpollcnt->totalcnt)*100;
	 	 if($vals->poll_answer_id == $uservote){
		 $userclass=' <span class="glyphicon glyphicon-ok"></span>';
	 }else{
		$userclass=''; 
	 }
	 if($percentage <10){
		 $width= '10';
		 
	 }else{
		  $width= $percentage;
		 
	 }
	 if($keys == 0){
		 $clor ='success';
	 }elseif($keys == 1){
		  $clor ='info';
		 
	 }elseif($keys == 2){
		 $clor ='warning';
	 }
	 elseif($keys == 3){
          $clor ='danger';
		 
	 }
	 elseif($keys == 4){
		$clor ='least'; 
	 }
    $pollhtml .='<strong>'.$vals->answer.'</strong>
    <div class="progress">
    <div class="progress-bar progress-bar-'.$clor.'" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width:'.$width.'%">
      <strong>'.$this->network->format_num($vals->usercnt).' vote ('.round($percentage,2).'%)</strong>'.$userclass.'  
    </div>
  </div>';
 }
 }
 
 $pollhtml .='</div>';
		
		
		
		echo $pollhtml;exit;


	}
	if(!empty($_POST['vote_update_pollid']))
	{
		$obj=$data[0];
	$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);

		$db2 = & $this->network->db2;
		$userid=$user->id;
		$answerid=$_POST['vote_update_answerid'];
		$pollid=$_POST['vote_update_pollid'];
		if($answerid !=0){
		$this->db2->query("UPDATE  post_poll_votes SET 
							ANSWER_ID = '".$answerid."' WHERE  VOTER_USER_ID = '".$userid."' AND  POLL_ID='".$pollid."' ", FALSE);
		}
		//echo $query="delete from post_poll_votes";
		//$this->db2->query($query,true);
		
		//$this->redirect($C->SITE_URL);

		//counting of answer
		$pollper =$buff->getpercentagesofpollanswers($pollid);
	    $totalpollcnt =$buff->totalpollcnt($pollid);
		$uservote  =$buff->userpollanswer($user->id,$pollid);

	$pollhtml ='';
	if(!empty($_POST['eventtype'])){
			$pollhtml .='<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content" id="replace'.$_POST['eventtype'].''.$_POST['vote_update_pollid'].'">';

		
	}else{
		if($_POST['maintype'] == 0){
			$pollhtml .='<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content" id="replace'.$_POST['vote_update_pollid'].'">';

			
		}else{
            $pollhtml .='<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content" id="replace1'.$_POST['vote_update_pollid'].'">';

		}

	}


 foreach($pollper as $keys=>$vals){
	 if($vals->answer !=''){
	 $percentage = ($vals->cnt/$totalpollcnt->totalcnt)*100;
	 if($vals->poll_answer_id == $uservote){
		 $userclass=' <span class="glyphicon glyphicon-ok"></span>';
	 }else{
		$userclass=''; 
	 }
	 if($percentage <10){
		 $width= '10';
		 
	 }else{
		  $width= $percentage;
		 
	 }
	 if($keys == 0){
		 $clor ='success';
	 }elseif($keys == 1){
		  $clor ='info';
		 
	 }elseif($keys == 2){
		 $clor ='warning';
	 }
	 elseif($keys == 3){
          $clor ='danger';
		 
	 }
	 elseif($keys == 4){
		$clor ='least'; 
	 }
    $pollhtml .='<strong>'.$vals->answer.'</strong>
    <div class="progress">
    <div class="progress-bar progress-bar-'.$clor.'" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width:'.$width.'%">
      <strong>'.$this->network->format_num($vals->usercnt).'('.round($percentage,2).'%)</strong>'.$userclass.'  
    </div>
  </div>';
 }
 }
 
 $pollhtml .='</div>';
		
		
		
		echo $pollhtml;exit;


	}
	if(!empty($_POST['vote_pollid']))
	{
		$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);

		$db2 = & $this->network->db2;
		$userid=$user->id;
		$poll =$buff->replay_is_poll($_POST['vote_post_id']);
		$child =$buff->findoutchild($_POST['vote_post_id']);
		if(!empty($child)){
			$css ="poll-radio";
		}else{
			$css ="poll-radio";
		}
		
		if(!empty($_POST['eventtype'])){
			$pollhtml ='<div id="replace'.$_POST['eventtype'].''.$_POST['vote_pollid'].'" class="'.$css.'" >';

		}else{
			if($_POST['maintype'] ==0){
				$pollhtml ='<div id="replace'.$_POST['vote_pollid'].'" class="'.$css.'">';

			}else{
				$pollhtml ='<div id="replace1'.$_POST['vote_pollid'].'" class="'.$css.'">';

				
			}
			
			

			
		}
		$answerid =$buff->userpollanswer($userid,$_POST['vote_pollid']);
		foreach($poll as $keys=>$row)
			{
				if($answerid == $row->poll_answer_id){
					$checked="checked";
				}else{
					$checked ='';
				}
				if(!empty($_POST['eventtype'])){

					
				}
				if(!empty($_POST['eventtype'])){
              $inputradio ='<input '.$checked.' onclick="changeurl('.$row->poll_id.','.$row->poll_answer_id.','.$_POST['eventtype'].')" id="'.$keys.$row->poll_id.'" type="radio" name="radio'.$row->poll_id.'"  class="radios option'.$row->poll_answer_id.' radio'.$row->poll_id.' "/>';
					
				}else{
				$inputradio ='<input '.$checked.' onclick="changeurl('.$row->poll_id.','.$row->poll_answer_id.')" id="'.$keys.$row->poll_id.'" type="radio" name="radio'.$row->poll_id.'"  class="radios option'.$row->poll_answer_id.' radio'.$row->poll_id.' "/>';
	
				}
				 $pollhtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">

    <!-- start : poll results  111111111-->

     <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content poll-parent-radio-margin">
    <ul class="list-unstyled">
    <li>
	'.$inputradio.'
<label for="'.$keys.$row->poll_id.'">&nbsp;</label>'.$row->answer.'</li>
 
    </ul> 

    </div>

    <!-- end : poll results -->

    </div>';
			}
	$pollhtml .='</div>';
	$mainpoll =0;
	$pollhtml .='<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">';
	if(!empty($_POST['eventtype'])){
			$pollhtml .='<a  class="button-vote" style="cursor:default"  onclick="voteopention('.$mainpoll.','.$userid.','.$_POST['vote_post_id'].','.$_POST['vote_pollid'].','.$_POST['eventtype'].')"  id="suboption'.$_POST['eventtype'].''.$_POST['vote_pollid'].'" >Change Vote</a>';
             if($poll[0]->user_id == $user->id){
		$pollhtml .='<a type="submit" class="download'.$_POST['eventtype'].''.$poll[0]->poll_id.'"  href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'" ><button type="submit" class="button-submit-results">Download Results</button></a>';
             }
		
	}else{
		
				$pollhtml .=' <a  class="button-vote" style="cursor:default"  onclick="voteopention('.$mainpoll.','.$userid.','.$_POST['vote_post_id'].','.$_POST['vote_pollid'].','.$_POST['maintype'].')"  id="pollvote'.$_POST['vote_pollid'].'" >Change Vote</a>';
                 if($poll[0]->user_id == $user->id){
		$pollhtml .='<a type="submit"  class="download'.$_POST['maintype'].''.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'" ><button type="submit" class="button-submit-results">Download Results</button></a>';

			
		}
	}
	$pollhtml .='</div>';
			echo $pollhtml;exit;
	}



// change1

if($_GET['action'] =="polledit"){
/*    print_r($_POST);
   die('-----');*/
    $post_id = $_POST['postid'];
	$pollquestion = $_POST['question'];
	
	$pollanswers = $_POST['answers'];
	
	//$pollanswers = explode(",",$pollanswers);
	
//	print_r($pollanswers);
//	die('------------');
	
	$pollgroup = $_POST['group'];
	$pollusers = $_POST['users'];


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
			

$imagecaption="Image";
move_uploaded_file($avatar_tmp_name , $upload_name);		    
     $ii = $n->attach_image($upload_name, $avatar_name);

//$db2->query('INSERT INTO `posts_attachments`(`post_id`, `type`, `data`,  `content`) VALUES ('.$poll_id.',\'Image\',\''.$db2->escape(serialize($ii)).'\',\''.$imagecaption.'\')');

// /print_r($_REQUEST); die("==========");

  $image_name=$ii->file_preview;
   $sss = $db2->query('UPDATE posts_attachments set data="'.$db2->escape(serialize($ii)).'" where post_id = "'.$_GET['p_id'].'"');
   
if($sss){
        $imagecaption="Image";
       // $db2->query('INSERT INTO `posts_attachments`(`post_id`, `type`, `data`,  `content`) VALUES ('.$_GET['p_id'].',\'Image\',\''.$db2->escape(serialize($ii)).'\',\''.$imagecaption.'\')');  
}



$data = serialize($ii);


   $file_originalSTART = strpos($data, 's:25:"', 0)+6;
         $file_originalEND  = strpos($data, ';', $file_originalSTART)-1;
         $file_original = substr($data,$file_originalSTART,$file_originalEND-$file_originalSTART);
      
             $file_previewSTART = strpos($data, '"file_preview";s:26:"', 0)+21;
         $file_previewEND  = strpos($data, ';', $file_previewSTART)-1;
         $file_preview = substr($data,$file_previewSTART,$file_previewEND-$file_previewSTART);
      
             $file_thumbnailSTART = strpos($data, '"file_thumbnail";s:26:"', 0)+23;
         $file_thumbnailEND  = strpos($data, ';', $file_thumbnailSTART)-1;
         $file_thumbnail = substr($data,$file_thumbnailSTART,$file_thumbnailEND-$file_thumbnailSTART);
         
         $file_original."+".$file_preview."+".$file_thumbnail;
         $newimg = array("file_original"=>$file_original,"file_preview"=>$file_preview,"file_thumbnail"=>$file_thumbnail);






                              //  $newimg = $this->splitAttachements(serialize($ii));
                                
                                foreach ($newimg as $tmpimg)
                                {
                                    rename($C->STORAGE_TMP_DIR.$tmpimg,$C->STORAGE_DIR.'attachments/1/'.$tmpimg);
                                }



		}
	



	$db2		= & $this->network->db2;

	

	$group_id="";
	if(!empty($pollgroup)){
		$grp = $db2->query('SELECT* FROM groups WHERE title="'.$pollgroup.'"');
		$grpdata = $db2->fetch_object($grp);
		if(!empty($grpdata)){
			$group_id=$grpdata->id;
		}
		$db2->query('UPDATE posts set group_id="'.$group_id.'", group_name="'.$pollgroup.'" where id="'.$post_id.'"');
		
	}
	else{
		$db2->query('UPDATE posts set group_id="0",group_name="" where id="'.$post_id.'"');
	}

	
	$row = $db2->query('SELECT* FROM polls where posts_id="'.$post_id.'"');
	$row = $db2->fetch_object($row);


		$id;
	foreach($row as $key=>$val){
		if($key=='poll_id') $id=$val;
	}

	
	
	
	
	$q = $db2->query('SELECT * FROM polls_answers WHERE poll_id = "'.$id.'"');
	$db2->query('UPDATE polls set poll_question="'.$pollquestion.'" where poll_id = "'.$id.'"');
	$ida=array();
	while($row = mysqli_fetch_assoc($q)){
		foreach($row as $key=>$v){
			if($key=='poll_answer_id')
			array_push($ida,$v);
		}
	}
	$time=time();
	$db2->query('UPDATE posts set date="'.$time.'" Where id="'.$post_id.'"');

	$tmp = array(
		'##BEFORE##' => '',
		'##HOUR##' => 'hour',
		'##HOURS##' => 'hours',
		'##MIN##' => 'min',
		'##MINS##' => 'min',
		'##SEC##' => 'sec',
		'##SECS##' => 'sec',
		'##AND##' => 'and',
		'##AGO##' => 'ago',
		'##NOW##' => 'just published'
	);
	$txt = post::replay_parse_date($time);
	// echo $txt;
	$date = str_replace(array_keys($tmp), array_values($tmp), $txt);

	$l=sizeof($ida);
	$al=sizeof($pollanswers);

	$count=0;
	foreach($pollanswers as $ans){
		if($ans!=""){
			if($count<$l){
				$db2->query('UPDATE polls_answers set answer="'.$ans.'" where (poll_id="'.$id.'" and poll_answer_id="'.$ida[$count].'")');
				$count++;
			}
			else{
				$db2->query("INSERT INTO polls_answers SET 
							poll_id = '".$id."', 
							answer = '".$ans."', 
							votes = '0'");
							$count++;	
			}
		}
		elseif($count>=2 and $count<$l){
			$db2->query('DELETE FROM polls_answers  where (poll_id="'.$id.'" and poll_answer_id="'.$ida[$count].'")');
				$count++;
		}
		elseif($count<2){
			return false;
			exit();
		}
	}
	
	$data = array('1');
	$obj = $data[0];
	$buff = (is_object($obj) && get_class($obj) == 'post') ? $obj :  new post('public', FALSE, $obj);
	// $rehtml = '<p><a href="'.$C->SITE_URL.'/view/post:'.$post_id.'" class="permlink">'.$date.'<span class="glyphicon glyphicon-link"></span></a></p><pre></pre>  <p></p><p></p>';
	
	
	$r      = $this->db2->query('SELECT data,type  FROM  posts_attachments
          WHERE post_id="'.$post_id.'"');

$rows=$r->num_rows;

if($rows>0){
while($result = $this->db2->fetch_object($r)){
    
  //  echo 'SELECT data,type  FROM  posts_attachments
      //    WHERE post_id="'.$parentid.'"'; die;
    $data= $result->data;
        $type= $result->type;
        
        if(!empty($image_name)){
        
        $imagedes = '<a target="_blank" href="'.($C->STORAGE_URL.'attachments/1/'.$image_name).'" class="lightbox-image image-thumb cboxElement "><img width="100%" alt="Image" src="'.$C->STORAGE_URL.'tmp/'.$image_name.'" /></a>';
}
else {
    
     $str = (unserialize($result->data));
	 $ext = $str->file_original;
					 
      $imagedes = '<a target="_blank" href="'.($C->STORAGE_URL.'attachments/1/'.$ext).'" class="lightbox-image image-thumb cboxElement "><img width="100%" alt="Image" src="'.$C->STORAGE_URL.'tmp/'.$ext.'" /></a>';
}
}
}
    
	
	
	
	
	 $imagevals->type='image';
$imagevals->data=$data;

if($type=="image"){
    
 //die('11111');
$tmp = @unserialize(stripslashes($imagevals->data));
if (!$tmp) {
$tmp = preg_replace_callback('!(?<=^|;)s:(\d+)(?=:"(.*?)";(?:}|a:|s:|b:|d:|i:|o:|N;))!s', 'serialize_fix_callback', stripslashes($imagevals->data));
$tmp = @unserialize(stripslashes($tmp));
}


    global $db2, $C;

/*  echo $image_name;
die('------');*/
//echo $image_name; die('------');


//echo $image_name='1627042461740143_large.jpg';die('------');






}
         
   	$rehtml = $buff->pollhtml($post_id,$image_name);
	$rehtml .= '<style>
	
	.btn-white {
		border-color: #0084B4;
		border-color: rgba(0,132,180,.5);
		color: #0084B4;
		background: rgba(255,255,255,0.75);
		border-style: solid;
		border-width: 1px;
		box-shadow: none;
		opacity: .8;
		-ms-filter: "alpha(opacity=80)";
	}
	.btn-white:hover {
		background-color: #1b95e0;
		color: #fff;
	}
	/* Usertype */
	.usertype-dropdown { margin-top: -10px; width:100%; z-index:50; display:none; background:#fff;}
	.usertype-dropdown {font-weight:bold; font-style:italic; color:#6E6E6E; font-size:10px; border:1px solid #C2C2C2; border-top:none;}
	.usertype-dropdown ul {list-style:none; margin:0px; border:0px solid #C2C2C2; border-top:none;}
	.usertype-dropdown ul li {border-bottom:1px solid #F5F5F5; cursor:pointer; display:block; width:100%; margin-left: -54px; padding:1px;}
	.usertype-dropdown ul li.hover {background:#0076a3; color: #fff;}
	.usertype-dropdown ul li.selection {color: #6E6E6E;}
	.usertype-dropdown ul li.selection:hover {color: #fff;}
	</style>';
	echo $rehtml;
	exit();
}




if($_GET['action'] =="eventedit"){
	$post_id = $_POST['postid'];
	$postmessage = $_POST['message'];
	$posttitle = $_POST['title'];
	$poststartdate = $_POST['start_date'];
	$poststarttime = $_POST['start_time'];
	$postenddate=$_POST['end_date'];
	$postendtime = $_POST['end_time'];
	$postaddress = $_POST['address'];
	$posturl = $_POST['url'];
	$postdescription = $_POST['description'];
	$posthastag = $_POST['hastag'];
	$poststreetgroup = $_POST['street_group'];
	$poststreetuser= $_POST['street_user'];


	$db2		= & $this->network->db2;

	$latitude = isset($_POST['lat']) ? $db2->escape($_POST['lat']) : NULL;
	$longitude = isset($_POST['lng']) ? $db2->escape($_POST['lng']) : NULL;
	$location = '';
	if(empty($postaddress)) {
		$latitude = NULL;
		$longitude = NULL;
	}
	if(!empty($latitude) && !empty($longitude)) {
		$location = $latitude.','.$longitude;
	}

	$time=time();
	$que = $db2->query('SELECT * from event_posts where post_id="'.$post_id.'"');
	
	$row = $db2->fetch_object($que);
	// echo $poststartdate,"<br>",$poststarttime,"<br>";
	$id;
	foreach($row as $key=>$val){
		if($key=='event_id') $id=$val;
	}

	$date_time = date('M d, Y h:i A', strtotime($poststartdate.' '.$poststarttime));
	// echo $poststartdate,"<br>",$poststarttime,"<br>";

	if($postdescription !=''){
		$postdescription = $postdescription;
		
	}else{
		$postdescription ='';
		
	}
	if($posturl !=''){
		$posturl = $posturl;
		$urlcon ='<span class="address_view">
								<a href="'.$posturl.'" class="buzz-content"  target="_blank">'.$posturl.'</a></span>';
		
	}else{
		$posturl ='';
		$urlcon ='';
		
	}
	if($posthastag !='' ){
	
	$hastagarr        =explode("#",trim($posthastag));
	$strret_arr       =array_filter($hastagarr);
	foreach($strret_arr as $keys=>$vals){
						if($keys ==1){
							$con .='<span><a href="'.$C->SITE_URL.'/search/tab:tags/s:'.$vals.'"><strong>#'.$vals.'</strong></a>';
						}else{
							$con .='<strong>#'.$vals.'</strong></span>';
						}
						
					}
			$hascon='<span>'.$con.'</span>';

	}else{
				$con ='';
				$hascon ='';

		
	}
	$group_id="";
	// echo "<pre>";
	// print_r($group_id);
	// echo "</pre>";
	if(!empty($poststreetgroup)){
		// echo $poststreetgroup;
		// echo "Yes we are in ..";
		$grp = $db2->query('SELECT* FROM groups WHERE title="'.$poststreetgroup.'"');
		$grpdata = $db2->fetch_object($grp);
		// print_r($grpdata);
		// echo $grpdata;
		// echo "<pre>";
		// print_r($grpdata);
		// echo "</pre>";
		// echo "grp data collected";
		if(!empty($grpdata)){
			$group_id=$grpdata->id;
		}
		// echo "<pre>";
		// print_r($group_id);
		// echo "</pre>";
		$db2->query('UPDATE posts set group_id="'.$group_id.'" where id="'.$post_id.'"');
		// echo "post updated";
		// echo $id;
		$db2->query('UPDATE events set group_id="'.$group_id.'", street_group="'.$poststreetgroup.'" where id="'.$id.'"');
		// echo "updated successfully";
	}
	else{
		$db2->query('UPDATE posts set group_id="0" where id="'.$post_id.'"');
		$db2->query('UPDATE events set group_id="0" where id="'.$id.'"');
	}

	
	$addition_url= empty($group_id) ? '' : '/group:'.$group_id;
	$content_title = '<div class="title"><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/event.png"> <a href="'.$C->SITE_URL.'plugin/events/view/id:'.$id.$addition_url.'">'.$posttitle.'</a></div>';
	$content = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" style="padding:10px 10px 0px 10px;">
    <!-- start : event title -->
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz- zeropadding">
    <ul class="list-inline single-line">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-calendar-event.png" class="img-responsive">
    </li>
    <li>
    <a href="'.$C->SITE_URL.'plugin/events/view/id:'.$id.$addition_url.'/postid:'.$post_id.'" class="buzz-title">
    '.$posttitle.'</a>
    </li>
    </ul>  
    </div>
    <!-- end : event title -->

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
    <!-- start : event location -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-location-event.png" class="img-responsive"></li>
    <li><a href="'.$C->SITE_URL.'search/tab:location/s:'.$post_id.'">'.$postaddress.'</a></li>
    </ul>  
    </div>
    <!-- end : event location -->
    <!-- start : event date & time -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-calendar-event.png" class="img-responsive"></li>
    <li>'.$date_time.'</li>
    </ul>  
    </div>
    <!-- end : event date & time -->
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">';
	if($urlcon !=''){
   $content .='<!-- start : event url -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-url-event.png" class="img-responsive"></li>
    <li>'.$urlcon.'</li>
    </ul>  
    </div>
    <!-- end : event url -->';
	}
	if($hascon !=''){
    $content .='<!-- start : event hashtag -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-hashtag-event.png" class="img-responsive"></li>
    <li>'.$hascon.'	</li>
    </ul>  
    </div>
    <!-- end : event hashtag -->';
	}
    $content .='</div>

  
    </div>
	';
	//$left = ($this->params->group)?(strlen($this->user->info->username)+strlen($g->title)+8):(strlen($this->user->info->username)+4);
	$answer = (object)array(
			// 'link'=>$C->SITE_URL."plugin/events/view/id:".$id.$addition_url, 
			// 'title'=>$content_title,
			'description' =>$content,
			'hits'=> 'link'
		);
	


	$db2->query('UPDATE posts_attachments SET type="link",data="'.$db2->escape(serialize($answer)).'" WHERE post_id="'.$post_id.'"');


	// $qe = "SELECT * FROM event_posts WHERE post_id='".$post_id."' ";
	// $row = $db2->fetch_object($qe);



	
	$db2->query('UPDATE posts set date="'.$time.'" Where id="'.$post_id.'"');

	$db2->query('UPDATE events set event_name="'.$posttitle.'", start_date="'.date('Y-m-d',strtotime($poststartdate)).'", start_time="'.date('H:i:s',strtotime($poststarttime)).'", end_date="'.date('Y-m-d',strtotime($postenddate)).'", end_time="'.date('Y-m-d',strtotime($postendtime)).'", address="'.$postaddress.'", url="'.$posturl.'", event_description="'.$postdescription.'", tag_name="'.$posthastag.'", street_group="'.$poststreetgroup.'", street_user="'.$poststreetuser.'", status="1", latitude="'.$latitude.'", longitude="'.$longitude.'", location="'.$location.'"  Where id="'.$id.'"');

	$tmp = array(
		'##BEFORE##' => '',
		'##HOUR##' => 'hour',
		'##HOURS##' => 'hours',
		'##MIN##' => 'min',
		'##MINS##' => 'min',
		'##SEC##' => 'sec',
		'##SECS##' => 'sec',
		'##AND##' => 'and',
		'##AGO##' => 'ago',
		'##NOW##' => 'just published'
	);
	$txt = post::replay_parse_date($time);
	// echo $txt;
	$date = str_replace(array_keys($tmp), array_values($tmp), $txt);

	// $finalcon='<p><a href="'.$C->SITE_URL.'/view/post:'.$post_id.'" class="permlink">'.$date.'<span class="glyphicon glyphicon-link"></span></a></p><pre></pre>  <p></p><p></p>';
	// $final=eventhtml($post_id);
	// $temp=array();
	// array_push($temp,$finalcon,$final);
	// echo "<pre>";
	// print_r($temp);
	// echo "</pre>";
	// echo "<div>".$final."</div>";
	$data = array('1');
	$obj = $data[0];
	$buff = (is_object($obj) && get_class($obj) == 'post') ? $obj :  new post('public', FALSE, $obj);

	// $rehtml .='<p><a href="'.$C->SITE_URL.'/view/post:'.$post_id.'" class="permlink">'.$date.'<span class="glyphicon glyphicon-link"></span></a></p><pre></pre>  <p></p><p></p>';
	// echo "till now it's fine<br>";
	echo "<br>";
	$rehtml = $buff->eventhtml($post_id);
	// $rehtml = $content;

	echo $rehtml;
	// $rehtml.='<style>
	// .container-buzzurl{
	// 	background: #fff !important;
	// 	border: 1px solid #e1e1e1 !important;
	// 	border-radius: 0px 0px 6px 6px !important;
	// 	padding: 10px !important;
	// 	margin:0px 10px 10px 10px !important;
	// }
	// </style>
	// <style>
	// .image-upload {
	// 	display: inline;
	// }
	// .btn-right {
	// 	float: right;
	// 	margin-top: -16px;
	// }
	// .activity-footer a {
	// 	color: #fff;
	// }
	// .icon-ftr a {
	// 	 color: #00BFFF;
	// }
	// .buttons {
	// 	text-align: left;
	// }
	// @media screen and (max-width: 600px) {
	// .btn-right {
	// 	display: block!important;;
	// 	width: 100%!important;
	// 	margin-top: 0px;
	// }
	// .modal-body {
	// 	padding-bottom: 115px;
	// }
	// }
	// </style>';
	// echo $rethml;

	exit();
}


if($_GET['action'] =="texteditloadDoccoverimagedel"){
    
    $post_id = $_GET['id'];
 if (isset($_GET['work'])) {
 $db2->query("UPDATE posts set coverimage='' Where id='".$post_id."'");   
 }

// if(isset($_FILES['image']['name']))
// {
//  $file = $_FILES['image']['tmp_name'];
//  $file_name = $_FILES['image']['name'];
//  $file_name_array = explode(".", $file_name);
//  $extension = end($file_name_array);
//  $new_image_name = rand() . '.' . $extension;
//  //chmod('upload', 0777);
//  $allowed_extension = array("jpg", "gif", "png", "jpeg");
//  if(in_array($extension, $allowed_extension))
//  {
//   move_uploaded_file($file, 'storage/attachments/1/' . $new_image_name);

//   $url = $C->SITE_URL.'storage/attachments/1/' . $new_image_name;
//   $message = '';
  
//   $db2->query("UPDATE posts set coverimage='$url' Where id='".$post_id."'");
   
//  }
// }


		if(!empty($_FILES["image"]["tmp_name"])){
			$n = new newpost();
        	$upload_dir = $C->STORAGE_DIR.'attachments/1/';
	        $avatar_name = $_FILES["image"]["name"];
			$avatar_tmp_name = $_FILES["image"]["tmp_name"];
			
            $temp = explode(".", $_FILES["image"]["name"]);
            $digits = 17;
            $r_n = rand(pow(10, $digits-1), pow(10, $digits)-1);
            $newfilename = $r_n . '.' . end($temp);
				$upload_name = $upload_dir.strtolower($newfilename);
				$upload_name = preg_replace('/\s+/', '-', $upload_name); 
			

$imagecaption="Image";
move_uploaded_file($avatar_tmp_name , $upload_name);		    
     $ii = $n->attach_image($upload_name, $avatar_name);

 $url = $C->SITE_URL.'storage/attachments/1/'.$newfilename;
 $db2->query("UPDATE posts set coverimage='$url' Where id='".$post_id."'");   
}
}

if($_GET['action'] =="textedit"){
    // print_r($_FILES); die('--------');
	$post_id = $_POST['postid'];
	$postmessage = $_POST['message'];
		$posttile = $_POST['posttile'];

	
	
	$db2		= & $this->network->db2;
	$time= time();
	$db2->query("UPDATE posts set message='".$postmessage."', title = '".$posttile."', date='".$time."' Where id='".$post_id."'");

	$tmp = array(
		'##BEFORE##' => '',
		'##HOUR##' => 'hour',
		'##HOURS##' => 'hours',
		'##MIN##' => 'min',
		'##MINS##' => 'min',
		'##SEC##' => 'sec',
		'##SECS##' => 'sec',
		'##AND##' => 'and',
		'##AGO##' => 'ago',
		'##NOW##' => 'just published'
	);
	$txt = post::replay_parse_date($time);
	// echo $txt;
	$date = str_replace(array_keys($tmp), array_values($tmp), $txt);
	// $t = time();
	// $date = date("Y-m-d",$t);
	// $time = date("h:i:sa",$t);


	$finalcon='<p><a href="'.$C->SITE_URL.'/view/post:'.$post_id.'" class="permlink">'.$date.'<span class="glyphicon glyphicon-link"></span></a></p><h5 style="font-size:17px !important">'.$posttile.'</h5><pre>'.$postmessage.'</pre>  <p></p><p></p>';


	echo $finalcon;
	exit();
}


	
	if($_GET['action']=="download")
{

		$pollid=$_GET['poll_id'];
		$host="localhost";
	$uname="sb_test";
	$pass="Wslu@697";
	$database = "sb_test"; 
    $connection=mysqli_connect($host,$uname,$pass,$database); 

	//or die("Database Connection Failed");
	$selectdb=mysqli_select_db($connection,$database) or 
	die("Database could not be selected"); 		


		
		$output = "";
		$table = ""; // Enter Your Table Name 
		$sql = mysqli_query($connection,"select u.username,pa.answer,FLOOR(DATEDIFF (NOW(), u.birthdate)/365) AS mAge,IF(u.gender='m','Male', 'Female') AS Gender from post_poll_votes as ppv
		inner join  polls as p ON ppv.POLL_ID =p.poll_id
		inner join  polls_answers as pa ON ppv.ANSWER_ID =pa.poll_answer_id
		inner join  users as u ON ppv.	VOTER_USER_ID =u.id
        where ppv.POLL_ID='".$pollid."'");
		$columns_total = mysqli_num_fields($sql);
		$pollsql = mysqli_query($connection,"select poll_question from polls 
        where poll_id='".$pollid."'");
		
		$pollquery = mysqli_fetch_object($pollsql);
		$pollquestion ="Pollquestion:".$pollquery->poll_question;	
		$output .= '"'.$pollquestion.'",';
		$output .="\n";
		$output .="\n";
		$output .="\n";

		// Get The Field Name
		$headingdes = array("Username","Response","Age(Yrs)","Gender");

		for ($i = 0; $i < $columns_total; $i++) {
		$heading = $headingdes[$i];
		$output .= '"'.$heading.'",';
		}
		$output .="\n";
		$output .="\n";

		// Get Records from the table

		while ($row = mysqli_fetch_array($sql)) {
		for ($i = 0; $i < $columns_total; $i++) {
		 if($i ==0){
			 $row[$i]="XXXXXXX";
			 
		 }
			
		$output .='"'.$row["$i"].'",';
		}
		$output .="\n";
		}
		// Download the file


		$filename = "POLL-".$pollid.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);

		echo $output;
		exit;

}
//for showing all comments
if($_GET['action']=="showall")
{
	$parentid=$_GET['parentid'];
	$data=array();
	$r	= $this->db2->query('SELECT posts.*,users.id as userid,users.avatar as pic, users.username as username FROM posts inner join users on posts.user_id=users.id WHERE posts.parent_id="'.$parentid.'" order by date desc', FALSE);
	while($result=$this->db2->fetch_object($r))
	{
		$data[]=$result;
	}
	$cnt='';
	$cnt.='<div class="row col-md-12">';
	$obj=$data[0];
	$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);
	foreach($data as $row)
	{
		$cnt.='<a href="'.userlink($row->username).'" class="pull-left  bizcard" data-userid="'.$row->userid.'"><img src="'.getAvatarUrl($row->pic, 'thumbs1').'" alt="'.$row->username.'" /></a>
			<div class="activity-header commentpost'.$row->id.'">
			<a style="margin-left:0px !important;" href="'.userlink($row->username).'" class="author bizcard" data-userid="'.$row->userid.'">'. $row->username .'</a>
			<div class="activity-options"><a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$row->id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" class="delete">delete</a></div>
			</div>
			<div class="activity-content">'.$row->message.'</div>
			
				<div class="activity-footer meta-info">
				<i class="fa fa-clock-o"></i>&nbsp;&nbsp;<a href="'.$buff->permalink.'" class="permlink">'.post::parse_date($obj->date).'</a>
				<a  style="cursor:pointer" onclick="activityAddCommentchield('.$row->id.')" >Comment</a>
				</div>
				<div class="comment-chield" style="display:none" id="chield'.$row->id.'">
				<div class="comments-editor data-content-placeholder">
				<div>
				<div class="activity-header">
				<a href="'.userlink($user->info->fullname).'" class="author bizcard" data-userid="'.$row->userid.'">'. $user->info->fullname .'</a>
				
				</div>
				
				<div class="comments-editor-content commentcontainer'.$row->id.'">
					<div class="user-status-field htmlarea">
					<div class="commentpoll commentpoll'.$row->id.'">
					</div>
						<div class="textarea-wrap comment">
							<textarea  id="message'.$row->id.'" name="message" >@'.$row->username.' '.'</textarea>
							<div class="textarea-highlighter"><span></span></div>
						</div>
					</div>			
							<div class="buttons">
								<button type="button" id="'.$row->id.'" class="pollcreate left comment-post comment-post'.$row->id.' post-btn btn blue"><span>POLL</span></button>
								
									<button onclick="comment('.$row->parent_id.','.$row->id.')" type="button" id="'.$row->id.'"class="comment-post comment-post'.$row->id.' post-btn btn  blue center">Buzz</button>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		<br><br>';
	}
	$cnt.='</div>';
	echo $cnt;exit;
}
//end of showing all comments
 if($_GET['action']=="editpostcomment")
{
	$db2		= & $this->network->db2;
	$db_user_id		= intval($this->user->id);
	$db_message		= $db2->escape($_POST['message']);
	$dbstarcnt =  substr_count($db_message,'*');
	if($dbstarcnt >=2 ){
		$db_message = preg_replace('/(?:\*)([^*]*)(?:\*)/', '<strong>$1</strong>', $db_message);
        $db_message = preg_replace('/(?:_)([^_]*)(?:_)/', '<i>$1</i>', $db_message); 

	}
	$db_date		= time();
	$db_ip_addr		= ip2long($_SERVER['REMOTE_ADDR']);
	$db_attached	= '0';
	$db_date		= time();
	$db_ip_addr		= ip2long($_SERVER['REMOTE_ADDR']);
	$postid         =$_POST['postid'];
	$message=$_POST['message'];
	$starcnt =  substr_count($message,'*');
	if($starcnt >=2 ){
		$message = preg_replace('/(?:\*)([^*]*)(?:\*)/', '<strong>$1</strong>', $message);
        $message = preg_replace('/(?:_)([^_]*)(?:_)/', '<i>$1</i>', $message); 

	}
	$activities_token=$_POST['token'];
	$sess = &$user->sess;
	if( isset($sess['TEMP_ACTIVITY_POSTS_ATTACHMENTS'][$activities_token]) ){
	$att	= & $sess['TEMP_ACTIVITY_POSTS_ATTACHMENTS'][$activities_token];
	}
	$obj=$data[0];
	
	$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);
	$n = new newpost();
	if(isset($_POST['pageurlpg'])){
		$pageurlpage		= $_POST['pageurlpg'];
		$urlpage          =explode("sbpage",$pageurlpage);
	    $urlpagearr          =array_filter($urlpage);
		foreach($urlpagearr as $urlkey=>$urlval){
					$url[] = $n->make_bitly_url($urlval,"o_76ibu9ktj5","R_199a68d8a2ae4822a715ae4318f92bda","xml",'1','2.0.1');
        }
   $mainurls     = $urlpagearr;
				 $bitlyurls   =  $url;
				$db_message = str_replace($mainurls, $bitlyurls, $db_message);		
	}
	
  	if( isset($att['image']) ){

		foreach($att['image'] as $img){
			$dir	= $C->STORAGE_DIR.'attachments/'.$this->network->id.'/'.$img->tempfile;
			if( $ii = $n->attach_image($C->STORAGE_TMP_DIR.$img->tempfile, $img->filename) ) {
				rm($C->STORAGE_TMP_DIR.$img->tempfile);
			}
		}
    }
	if( isset($att['file']) ){
		foreach($att['file'] as $file){
			if( $ff = $n->attach_file($C->STORAGE_TMP_DIR.$file->tempfile, $file->filename, $file->detected_type) ) {
				rm($C->STORAGE_TMP_DIR.$file->tempfile);
			}
		}
	}
				
	if( isset($att['link']) ){
		foreach($att['link'] as $link){
			$n->attach_link($link);
		}
	}
				
	if( isset($att['videoembed']) ){
		foreach($att['videoembed'] as $vid){
			$n->attach_videoembed($vid);
		}
	}
	if(!empty($att)){
	$file_copy = $n->replayattachments_copy_files();
	
	}
		$extra =$n->set_message_replay($_POST['message']);
	$mentions       = json_decode($extra);

	


	$db_value=0;
	$db2->query('update posts SET api_id="'.$db_value.'", user_id="'.$db_user_id.'",  message="'.$db_message.'",mentioned="'.$db_value.'", posttags="'.$db_value.'", attached="'.$db_value.'", date="'.$db_date.'", date_lastcomment="'.$db_date.'", ip_addr="'.$db_ip_addr.'" where id="'.$postid.'" ');
	if(!empty($att)){
		foreach($n->attached as $k=>$v) {

			$db2->query('update posts_attachments  SET type="'.$db2->escape($k).'", data="'.$db2->escape(serialize($v)).'" WHERE  post_id="'.$postid.'" ');
		}
			
	}
	

	 if(!empty($mentions->posttags)){
		$replayposttags       =$mentions->posttags;
		 $posttagscnt                      =count($replayposttags);
		 $db2->query('UPDATE posts SET posttags="'.$posttagscnt.'" where id="'.$postid.'"');

			foreach($replayposttags as $replaypostkeys=>$replaypostvals){
				$groupid =0;
			$db2->query('update  post_tags SET tag_name="'.$replaypostvals.'", user_id="'.$this->user->id.'",group_id="'.$groupid.'" ,date="'.$db_date.'" WHERE post_id="'.$postid.'" ');

			}
 	
	   }
	   echo "1";exit;
}


if($_GET['action']=="comment")
{
	$chieldid=$_POST['chieldid'];
	$db2		= & $this->network->db2;
	$db_user_id		= intval($this->user->id);
	$db_message		= $db2->escape($_POST['message']);
	$dbstarcnt =  substr_count($db_message,'*');
	if($dbstarcnt >=2 ){
		$db_message = preg_replace('/(?:\*)([^*]*)(?:\*)/', '<strong>$1</strong>', $db_message);
        $db_message = preg_replace('/(?:_)([^_]*)(?:_)/', '<i>$1</i>', $db_message); 

	}
	$db_date		= time();
	$db_ip_addr		= ip2long($_SERVER['REMOTE_ADDR']);
	$db_attached	= '0';
	$db_date		= time();
	$db_ip_addr		= ip2long($_SERVER['REMOTE_ADDR']);
	$parentid=$_POST['postid'];
	$message=$_POST['message'];
	$starcnt =  substr_count($message,'*');
	if($starcnt >=2 ){
		$message = preg_replace('/(?:\*)([^*]*)(?:\*)/', '<strong>$1</strong>', $message);
        $message = preg_replace('/(?:_)([^_]*)(?:_)/', '<i>$1</i>', $message); 

	}
	$alter=$_POST['alterparentid'];
	$activities_token=$_POST['token'];
	$sess = &$user->sess;
	if( isset($sess['TEMP_ACTIVITY_POSTS_ATTACHMENTS'][$activities_token]) ){
	$att	= & $sess['TEMP_ACTIVITY_POSTS_ATTACHMENTS'][$activities_token];
	}
	$obj=$data[0];
	
	$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);
		$ownuserres          =$buff->get_own_user($alter);
	$ownuserid           =$ownuserres->user_id;
	$not_type='ntf_me_on_post_replay';
	$checkuserres =$buff->checkemptyuser($ownuserid);
	if($checkuserres->num_rows == "0"){
		$ownnotification =1;
	}else{
		$ownnotification     =$buff->checknotrules($ownuserid,$not_type);
		if(!empty($ownnotification)){
						$ownnotification = $ownnotification;
					}else{
						$ownnotification =1;
					}


	}

	if($ownnotification ==1 || $ownnotification ==2 || $ownnotification ==3 ){
				
	if($ownuserid != $this->user->id){
	$posttype      =$buff->typeofpostofevent($_POST['activities_id']);
	if($posttype->num_rows > 0){
		$type ="event";
	}else{
	$polltype      =$buff->typeofpostofpoll($_POST['activities_id']);
		if($posttype->num_rows > 0){
			$type ="poll";
		}else{
		$activitiestype      =$buff->typelinks($_POST['activities_id']);
		if(!empty($activitiestype)){
			
		 if($activitiestype->type=="videoembed"){
			 $type="video link";
			 
		 }elseif($activitiestype->type=="image"){
			  $type="image";
			 
		 }elseif($activitiestype->type=="file"){
			  $str = (unserialize($activitiestype->data));
			 $ext = pathinfo($str->file_original, PATHINFO_EXTENSION);
			 if($ext =='wmv' || $ext =='mp4' || $ext =='avi' || $ext =='mov' || $ext =='qt'){
				 $type = "video";
				 
			 }else{
				  $type = "file";
				 
			 }
		}
		}else{
		$type ="buzz";	
		}

		}				
	}
	$notifytype="replay";
	$standardtype ="ntf_me_on_post_replay";


	$newisert =$buff->insert_active_notifications($ownuserid,$alter,$notifytype,$type,$standardtype);
	}
	}
	
    $n = new newpost();
	if(isset($_POST['pageurlpg'])){
		$pageurlpage		= $_POST['pageurlpg'];
		$urlpage          =explode("sbpage",$pageurlpage);
	    $urlpagearr          =array_filter($urlpage);
		foreach($urlpagearr as $urlkey=>$urlval){
					$url[] = $n->make_bitly_url($urlval,"o_76ibu9ktj5","R_199a68d8a2ae4822a715ae4318f92bda","xml",'1','2.0.1');
        }
   $mainurls     = $urlpagearr;
				 $bitlyurls   =  $url;
				$db_message = str_replace($mainurls, $bitlyurls, $db_message);		
	}
	
  	if( isset($att['image']) ){

		foreach($att['image'] as $img){
			$dir	= $C->STORAGE_DIR.'attachments/'.$this->network->id.'/'.$img->tempfile;
			if( $ii = $n->attach_image($C->STORAGE_TMP_DIR.$img->tempfile, $img->filename) ) {
				rm($C->STORAGE_TMP_DIR.$img->tempfile);
			}
		}
    }
	if( isset($att['file']) ){
		foreach($att['file'] as $file){
			if( $ff = $n->attach_file($C->STORAGE_TMP_DIR.$file->tempfile, $file->filename, $file->detected_type) ) {
				rm($C->STORAGE_TMP_DIR.$file->tempfile);
			}
		}
	}
				
	if( isset($att['link']) ){
		foreach($att['link'] as $link){
			$n->attach_link($link);
		}
	}
				
	if( isset($att['videoembed']) ){
		foreach($att['videoembed'] as $vid){
			$n->attach_videoembed($vid);
		}
	}
	if(!empty($att)){
	$file_copy = $n->replayattachments_copy_files();
	
	}
		$extra =$n->set_message_replay($_POST['message']);
	$mentions       = json_decode($extra);

	


	$db_value=0;
	$db2->query('UPDATE users SET num_posts=num_posts+1  where id="'.$this->user->id.'"');
	$db2->query('INSERT INTO posts SET api_id="'.$db_value.'", user_id="'.$db_user_id.'",  message="'.$db_message.'",mentioned="'.$db_value.'", posttags="'.$db_value.'", attached="'.$db_value.'", date="'.$db_date.'", date_lastcomment="'.$db_date.'", ip_addr="'.$db_ip_addr.'",parent_id="'.$chieldid.'" ');
	$replayid = $db2->insert_id();
	if(!empty($att)){
		foreach($n->attached as $k=>$v) {

			$db2->query('INSERT INTO posts_attachments  SET post_id="'.$replayid.'", type="'.$db2->escape($k).'", data="'.$db2->escape(serialize($v)).'" ');
		}
			
	}
	if(!empty($mentions->mentioned)){

	$mentionsdata = $mentions->mentioned;

	foreach($mentionsdata as $mentionskeys=>$mentionvals){
		if($mentionvals != $this->user->id){
		 $mentionscnt   =$buff->findcountmentions($mentionvals);

		 if($mentionscnt > 0 ){
			   $tab  ='@me';       
			 $db2->query('UPDATE users_dashboard_tabs SET 	newposts=	newposts+1  where user_id="'.$mentionvals.'" AND tab="'.$tab.'"');
			 
		 }else{
			  $tab  ='@me'; 
              $state = 1;			  
			 $db2->query('INSERT INTO  users_dashboard_tabs SET user_id="'.$mentionvals.'",tab="'.$tab.'",state="'.$state.'",newposts=	newposts+1 ');
			 
		 }
		$db2->query('INSERT INTO posts_mentioned SET post_id="'.$replayid.'", user_id="'.$mentionvals.'"  ');
	    }
	
		   
		
	}
	
	}

	 if(!empty($mentions->posttags)){
		$replayposttags       =$mentions->posttags;
		 $posttagscnt                      =count($replayposttags);
		 $db2->query('UPDATE posts SET posttags="'.$posttagscnt.'" where id="'.$replayid.'"');

			foreach($replayposttags as $replaypostkeys=>$replaypostvals){
				$groupid =0;
			$db2->query('INSERT INTO post_tags SET tag_name="'.$replaypostvals.'", user_id="'.$this->user->id.'",group_id="'.$groupid.'" ,post_id="'.$replayid.'",date="'.$db_date.'" ');

			}
 	
	   }
	
	$even ="buzz";
	//echo "INSERT INTO post_replay SET parent_id='".$parentid."',alternate_parent_id='".$alter."', replay_id='".$replayid."',action_type='".$even."',series='".$seriesarray."' ";exit;

	$db2->query("INSERT INTO post_replay SET parent_id='".$parentid."',alternate_parent_id='".$alter."', replay_id='".$replayid."',action_type='".$even."' ");
	$db2->query('INSERT INTO post_userbox SET user_id="'.$this->user->id.'", post_id="'.$replayid.'"');

	$q =array();

					//insert to followers data
					if($this->user->info->is_posts_protected == 0){
						$u	= $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers;
					}else{
						$u	= array_intersect_key($this->network->get_user_follows($this->user->id, FALSE, 'hefollows')->follow_users, $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers);
					}
							
					$u	= $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers;
					foreach($u as $k=>$v) {
						if($k !=$this->user->id){

						$q[]	= '("'.$k.'", "'.$replayid.'")';
						}
					}
					
					if( $group_id ) {
						$u	= $this->network->get_group_members($group_id);
						if($u) {
							foreach($u as $k=>$v) {
								$z[]	= '("'.$k.'", "'.$replayid.'")';
							}
						}
						$q	= array_unique($q);
						$q = array_intersect($q,$z);					
					}
					
					if( count($q) > 0 ) { 
						$q	= implode(', ', $q);
						$db2->query('INSERT INTO post_userbox (user_id, post_id) VALUES '.$q);
					}

	
	$data=array();
	if($parentid == $alter){
		/*this condition for new post value doesn't show in timeline in first level*/	
		$seriesarray     =serialize(array($parentid,$replayid));

		$db2->query('UPDATE posts SET date_lastcomment="'.$db_date.'" where id="'.$alter.'"');
		$db2->query("UPDATE post_replay SET series='".$seriesarray."' where replay_id='".$replayid."' ");


		$postlevel = 1;
	$db2->query('UPDATE posts SET post_level="'.$postlevel.'" where id="'.$replayid.'"');



	$r	= $this->db2->query('SELECT p.*,pr.replay_id as replayid,users.id as userid,users.avatar as pic, users.username as username FROM posts as p
     inner join post_replay as pr ON p.id = pr.replay_id 	
	inner join users on p.user_id=users.id WHERE pr.parent_id="'.$parentid.'" order by p.date ASC', FALSE);
	while($result=$this->db2->fetch_object($r))
	{
		$data[]=$result;
	}
	$cnt='';

	$obj=$data[0];
	$parentquery = $this->db2->query('SELECT p.*,users.id as userid,users.avatar as pic, users.username as username FROM posts as p
	inner join users on p.user_id=users.id WHERE p.id="'.$parentid.'" ', FALSE);
	$parentres =$this->db2->fetch_object($parentquery); 
	
	$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);
	$eventdetails = $buff->geteventdetails($parentid);
	$poll  = $buff->replay_is_poll($parentid);
	$assetdata   =$buff->assetdata($parentid);
	$prediction_data =$buff->predictiondata($parentid);

	if(!empty($assetdata)){
		$parentmessage =$buff->assethtml($parentid);
	}elseif(!empty($eventdetails)){
		$parentmessage =$buff->eventhtml($parentid);
    }elseif(!empty($poll)){
		$parentmessage =$buff->pollchildhtml($parentid);
		
	}elseif(!empty($prediction_data)){
			if($prediction_data[0]->status =="OPEN"){

            //calculations for up rate
				$predict_value = $prediction_data[0]->predict_value;
				$prediction_base_price = $prediction_data[0]->prediction_base_price;
				$percentage             =(($predict_value-$prediction_base_price)/($prediction_base_price))*100;
				$percentage = number_format((float)$percentage, 2, '.', '');
				
				if (strpos($percentage, '-') !== false) {
					$con ='down';
	               $imag ='down-arrow-prediction.png';
				}else{
					$con ='up';
					$imag ='up-arrow-prediction.png';
				}
			$parentmessage .='<div class="prediction-buzz-data">'.$prediction_data[0]->asset_name.'($'.$prediction_data[0]->ticker.')<img src="'.$C->SITE_URL.'/static/images/icons/'.$imag.'"> to be '.$con.' by '.$percentage.'% from '.$prediction_data[0]->prediction_base_price.' in '.substr($prediction_data[0]->end_date,0,10).' because of '.$prediction_data[0]->predict_reason.'.</div>';
					}else{
											//calculations for up rate
				$predict_result = $prediction_data[0]->predict_result;
				if($predict_result =='CORRECT'){
					 $imag ='hit.png';
					 $type ="Hit";
					 $percentage='';
					
				}else{
					 $imag ='miss.png';
					 
					 
					  $predict_value = $prediction_data[0]->predict_value;
					  $prediction_base_price = $prediction_data[0]->prediction_base_price;
					  $percentage             =(($predict_value-$prediction_base_price)/($prediction_base_price))*100;
					  $percentage = substr(number_format((float)$percentage, 2, '.', ''),1);
					  $type =" Mis by ".$percentage."%";
					
				}
				if($buff->post_user->id == $user->id){
					$handset ='If you want to change Hindsight reason please <a  class="mymodal" data-toggle="modal" data-target="#myModal-'.$prediction_data[0]->post_id.'"  >click here </a> 

  
  <!-- Modal -->
  <div class="modal fade-'.$prediction_data[0]->post_id.'" id="myModal-'.$prediction_data[0]->post_id.'" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Handset reason </h4>
        </div>
        <div class="modal-body">
		<div class="row">
		 <div>Reason :<input type="text" value="'.$prediction_data[0]->hindsight_reason.'" id="hindsight-'.$prediction_data[0]->post_id.'" onkeyup="validate(this,'.$prediction_data[0]->post_id.')">
		 </div>
		  <div id="handsetreason-error-'.$prediction_data[0]->post_id.'"class="notifyjs-container" style="top: 37px; left: 168px; overflow: hidden; display: hidden;"><div class="notifyjs-bootstrap-base notifyjs-bootstrap-error">
            <span data-notify-text="" class="notifyjs-text">This field is required</span>
         </div></div>
		 		   <button type="button" class="btn btn-default btn-primary"  data-toggle="modal"  onclick="changehandset('.$prediction_data[0]->post_id.')">Change</button>

		</div>


          
        </div>
		<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      
      </div>
      
    </div>
  </div>
  
					
					';
				}else{
					$handset ='';
					
				}
				$parentmessage .='<div style="background-color:#e3f8fe;font-size:12px;height:auto;padding: 10px;"> Your prediction on '.$prediction_data[0]->asset_name.'($'.$prediction_data[0]->ticker.') done  on '.substr($prediction_data[0]->end_date,0,10).' was a '.$type.' <img src="'.$C->SITE_URL.'static/images/icons/'.$imag.'">.'.$handset.' 
				</div>';
						
					}		
	}else{
		$parentmessage =($parentres->message);
		$parentmessage .=($buff->attchmentreplaydisplay($parentid));
		$link =$buff->findlink($parentid);
		if(!empty($link)){
		$parentmessage  .=$buff->linkhtml($parentid);
		}


		
	}
   $txt =post::replay_parse_date($parentres->date);
  $date = str_replace(array_keys($tmp), array_values($tmp), $txt);

		//user post shared or not checking
                $is_reshared    =$buff->is_post_reshared($parentid);
				$reshares       =$buff->loaded_posts_reshares($parentid);
				$resharecnt     =count($reshares);


			        $like_content ='';
					$is_liked  = $buff->new_liked($parentid);
					$likes_number = $buff->new_liked_count($parentid);
					$like_number        =$likes_number->likecount;

					$css="icons";
				$is_spam  = $buff->is_spam($parentid,$buff->post_type);
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'"><em><img  src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				$is_agree = $buff->is_post_agree($user->id,$parentid);
				$is_agree_cnt = $buff->is_post_agree_cnt($parentid);
				 if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   if($like_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'">'.$like_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>').'</a>';
                    if($reshare_content > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'">'.$resharecnt.'</a>';
                    }else{
						$resharecnt ='';
						
					}
						   
		$delete = (($user->is_logged && $buff->if_can_delete())? '<a href="" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentres->id.'"}').'" data-role="services" data-namespace="activities" title="Delete" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>' : '');
       $is_fav  = $buff->isfav($user->id,$parentres->id);
	 
   			if(!empty($is_fav)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$parentres->id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$parentres->id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
			$groups = $buff->getgroupname($parentres->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
        if($parentres->pic !=''){
					$userimg ='<img src="'.getAvatarUrl($parentres->pic, 'thumbs1').'" alt="'.$parentres->username.'" />';
					
				}else{
					$userimg ='<img src="'.$C->STORAGE_URL.'avatars/thumbs1/_noavatar_user.gif" alt="'.$parentres->username.'" />';
					
				}
			$posttypeRes =$buff->checkposttype($parentid);
		     if(!empty($posttypeRes) && $posttypeRes->posttype == 2){
		          $decodemessages =json_decode($parentmessage,true);
		          if(!empty($decodemessages['post'])){
		              $decodemessagespost = $decodemessages["post"];
		               foreach($decodemessagespost as $decodekeys=>$decodevals){
		                   $key = array_keys($decodevals);
		                   $deocdkey =  $key[0];
		                   $deocdevalue = $decodevals[$deocdkey];
		                   $createtext = $buff->createLongreadElements($deocdkey,$deocdevalue);
		                   $finalstr .= $createtext;
		               }
		               $parentmessage = $finalstr;
		          }
		     }
		    




/********************* Start: Timeline Buzz Parent Reply **********************/


	$cnt .='<div class="activity no-comments zzzz replayhide-'.$parentid.'" id="main'.$parentid.'">
   <!-- start Parent -->
   <div class="row" style="border:0px solid red; margin:0px; padding:0px;">

   <div class="janeesh'.$parentid.'" ></div>

   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box">

         <div class="col-md-1 col-lg-1 col-sm-1 col-xs-2 image-div" style="padding:0; overflow:hidden">
         <a href="'.userlink($parentres->username).'" class="pull-left  bizcard" data-userid="'.$parentres->userid.'">'.$userimg.'</a>
			</div>

   <div class="col-md-11 col-lg-11 col-sm-11 col-xs-10" style="padding:0px 3px 0px 8px;">
            <div class="activity-container">
             <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <a href="'.userlink($parentres->username).'" class="author bizcard" data-userid="'.$parentres->userid.'">'. $parentres->username .'</a>
                  <div class="meta-info">'.$grp.'
                  </div>
              <div class="activity-options">'.$delete.''.$fav.'</div>
               </div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 zeropadding">
<a href="'.$C->SITE_URL.'/view/post:'.$parentid.'" class="permlink">'.$date.' <span class="glyphicon glyphicon-link"></span></a>
</div>

               <div class="activity-content">'.$parentmessage.'</div>
             

               <div class="activity-poll col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
               <div class="footer1 activity-footer meta-info">  </div>
            </div>
			   <div id="replaypopup-'.$parentid.'" class="modal fade" ></div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 zeropadding">
             <div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding admin-margin-footer-popup">
                 
				<input type="hidden" id="time-'.$parentid.'" value="1 sec ago" />



<span class="reply icon-ftr icon-ftr-reply">				
				<a  style="cursor:pointer" onclick="parentreplay('.$parentid.','.$parentid.')" ><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
				</span>



				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$parentid.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$parentid.'"}').'">'.($is_agree? '<img  width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Disagree"/>' : '<img width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree"/>').'</a>'.$showagreebtn_btn.'</div>
               	<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>

                  <div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								   
 <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($buff->permalink).'&title='.urlencode(htmlspecialchars($buff->post_message)).'&source='.urlencode($buff->permalink).'&summary='.urlencode($buff->permalink).'"  target="_blank" >Linkedin</a></li>

 <li><a href="http://plus.google.com/share?url='.urlencode($buff->permalink).'"  target="_blank" >Google Plus</a></li>

 <li><a href="http://twitter.com/intent/tweet?text='.urlencode($buff->permalink).': '.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Twitter</a></li>

 <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($buff->permalink).'&t='.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
                

                <div class="like-list icon-ftr">'.$mark_content.'</div>  

        </div> <!--/ end :  activity-footer meta-info -->

</div>
<!--/ end : activity container -->


  
</div><!--/ end : col-md-11 -->


</div><!--/ end : col-md-12 -->


</div><!-- end : activity no-comments-->
   
   ';
   $dcnt = count($data)-1;
   
	foreach($data as $keys=>$row)
	{
		
		
		//user post shared or not checking
                $is_reshared    =$buff->is_post_reshared($row->replayid);
				$reshares       =$buff->loaded_posts_reshares($row->replayid);
				$resharecnt     =count($reshares);
                $txt =post::replay_parse_date($row->date);
		        $date = str_replace(array_keys($tmp), array_values($tmp), $txt);

			        $like_content ='';
					$is_liked  = $buff->new_liked($row->replayid);
					$likes_number = $buff->new_liked_count($row->replayid);
					$like_number        =$likes_number->likecount;
					$css="icons";
				$is_spam  = $buff->is_spam($row->replayid,$buff->post_type);
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'"><em><img  src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				$is_agree = $buff->is_post_agree($user->id,$row->replayid);
				$is_agree_cnt = $buff->is_post_agree_cnt($row->replayid);
				 if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   if($like_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'">'.$like_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>').'</a>';
                    if($reshare_content > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'">'.$resharecnt.'</a>';
                    }else{
						$resharecnt ='';
						
					}
					$delete = (($user->is_logged && $buff->if_can_delete())? '<a href="" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'" data-role="services" data-namespace="activities" title="Delete" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>' : '');
       $is_fav  = $buff->isfav($user->id,$row->replayid);
	 
   			if(!empty($is_fav)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$row->replayid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$row->replayid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
			$groups = $buff->getgroupname($row->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
					
				$buzztype = $buff->getbuzztype($row->replayid);

				
				if($buzztype =="buzz" || $buzztype =="" ){
					$mes = $buff->parsetext($row->message);
					$mes .=($buff->attchmentreplaydisplay($row->replayid));
					$link = $buff->findlink($row->replayid);
					 if(!empty($link)){
						 $mes  .=$buff->timelinelinkhtml($row->replayid);
					 }


                }elseif($buzztype =="event"){
					$mes	    =$buff->eventhtml($row->replayid);
                 }elseif($buzztype =="poll"){
					 $mes	    =$buff->pollchildhtml($row->replayid);
	
				}elseif($buzztype =="intraday"){
			       $mes   =$buff->assethtml($row->replayid);
		
				}	
					if($dcnt == $keys){
						$css="tree1";
						$chi ="child".$parentid;

						
					}else{
						$css="tree";
						$chi='';
					}
					 if($row->pic !=''){
					$img ='<img src="'.getAvatarUrl($row->pic, 'thumbs1').'" alt="'.$row->username.'" />';
					
				}else{
					$img ='<img src="'.$C->STORAGE_URL.'avatars/thumbs1/_noavatar_user.gif" alt="'.$row->username.'" />';
					
				}



/********** START: Timeline Parent Reply > Normal text Buzz >  All Childs ***********/


		$cnt.='<div class="activity no-comments zeropadding commentcontainer'.$replayid.'" id="'.$chi.'" style="border:0px">


<ul class="'.$css.'">
			<li>			
<!-- start Parent -->
<div class="row activity-parent">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box">

<div class="col-md-1 col-lg-1 col-sm-1 col-xs-2 image-div" style="padding:0; overflow:hidden"><a href="'.userlink($row->username).'" class="pull-left  bizcard" data-userid="'.$row->userid.'">'.$img.'</a>
			</div>

<div class="col-md-11 col-lg-11 col-sm-11 col-xs-10" style="padding:0px 3px 0px 8px;">

<div class="activity-container">
<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12"><a href="'.userlink($row->username).'" class="author bizcard" data-userid="'.$row->userid.'">'. $row->username .'</a>
	 <div class="meta-info">'.$grp.'</div>	

<div class="activity-options">'.$delete.''.$fav.'</div>
</div>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'/view/post:'.$row->replayid.'" class="permlink">'.$date.' <span class="glyphicon glyphicon-link"></span></a>
</div>


<div class="activity-content">'.$mes.'</div>
</div>		

<div id="replydis-'.$row->replayid.'"></div>


		
		
		
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
 <div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
				
				<input type="hidden" id="time-'.$row->replayid.'" value="1 sec ago" />

				<span class="reply icon-ftr icon-ftr-reply">
				<a  style="cursor:pointer" onclick="childpopup('.$parentid.','.$row->replayid.')" ><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
				</span>


				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$row->replayid.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$row->replayid.'"}').'">'.($is_agree? '<img  width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Disagree"/>' : '<img width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree"/>').'</a>'.$showagreebtn_btn.'</div>
               	<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>

                  <div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								   
 <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($buff->permalink).'&title='.urlencode(htmlspecialchars($buff->post_message)).'&source='.urlencode($buff->permalink).'&summary='.urlencode($buff->permalink).'"  target="_blank" >Linkedin</a></li>

 <li><a href="http://plus.google.com/share?url='.urlencode($buff->permalink).'"  target="_blank" >Google Plus</a></li>

 <li><a href="http://twitter.com/intent/tweet?text='.urlencode($buff->permalink).': '.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Twitter</a></li>

 <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($buff->permalink).'&t='.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
							
                           <div class="like-list icon-ftr">'.$mark_content.'</div>						
							</div>
				 </div>


</div>

</div>
</div>
<!-- end Parent -->
</li>
</ul>

</div>
';
	}
$cnt.='</div>
   <div>   
   </div>



<script type="text/javascript">
$(document).ready(function(){
var mainheight = $("#main'.$parentid.'").height();
 var childheight = $("#child'.$parentid.'").height();
 var final = mainheight-childheight;
$(".janeesh'.$parentid.'").css("height",final);
			 
});
</script>


';
/********************* End: Timeline Buzz Parent Reply **********************/

	echo $cnt;
	exit;

} elseif($parentid != $alter){
	$seriesquery = $this->db2->query('SELECT series  FROM post_replay as pr
	 WHERE pr.parent_id="'.$parentid.'" AND pr.alternate_parent_id="'.$alter.'" order by id desc limit 1,1 	 ', FALSE);
	 $series=$this->db2->fetch_object($seriesquery);
	 if($series->series !=''){
		 $series = $series;
	}else{
		$seriesquery = $this->db2->query('SELECT series  FROM post_replay as pr
	 WHERE  pr.replay_id="'.$chieldid.'" order by id desc limit 1	 ', FALSE);
	
	 $series=$this->db2->fetch_object($seriesquery);
		
	}
	 $seriesarrayold  = unserialize($series->series);
	 $seriesarraynew     =(array($parentid,$alter,$replayid));
	 $seriesnow                   =array_merge($seriesarrayold,$seriesarraynew);


	 $seriesarray     =serialize($seriesnow);
	 $db2->query("UPDATE post_replay SET series='".$seriesarray."' where replay_id='".$replayid."' ");


	  

	 
			//$db2->query('UPDATE posts SET date_lastcomment="'.$db_date.'" where id="'.$alter.'"');

	$postlevel =1;
		//$db2->query('UPDATE posts SET post_level="'.$postlevel.'" where id="'.$alter.'"');

		$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);


	$r	= $this->db2->query('SELECT  p.*,users.id as userid,users.avatar as pic, users.username as username FROM posts as p
	inner join users on p.user_id=users.id WHERE p.id="'.$replayid.'" order by p.date desc', FALSE);
	$replayparentres=$this->db2->fetch_object($r);
	
			
				$buzztype = $buff->getbuzztype($replayid);
				if($buzztype =="buzz" || $buzztype =="" ){
					$mes = $buff->parsetext($replayparentres->message);
					$mes .=($buff->attchmentreplaydisplay($replayid));
					$link = $buff->findlink($replayid);
					 if(!empty($link)){
					$mes  .=$buff->timelinelinkhtml($replayid);
					}


                }elseif($buzztype =="event"){
					$mes	    =$buff->eventhtml($replayid);
                 }elseif($buzztype =="poll"){
					 $mes	    =$buff->pollchildhtml($replayid);
	
				}elseif($buzztype =="intraday"){
			       $mes   =$buff->assethtml($replayid);
		
				}	
				$txt =post::replay_parse_date($replayparentres->date);
		    $date = str_replace(array_keys($tmp), array_values($tmp), $txt);
			 if(($user->id == $replayparentres->userid )){
					
   
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity"> <img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				} 
       $is_fav  = $buff->isfav($user->id,$replayid);
	 
   			if(!empty($is_fav)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
			$groups = $buff->getgroupname($replayparentres->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
		 if($replayparentres->pic !=''){
					$userimg ='<img src="'.getAvatarUrl($replayparentres->pic, 'thumbs1').'" alt="'.$replayparentres->username.'" />';
					
				}else{
					$userimg ='<img src="'.$C->STORAGE_URL.'avatars/thumbs1/_noavatar_user.gif" alt="'.$replayparentres->username.'" />';
					
				}


/********************* Start: Timeline Buzz Child Reply **********************/


   $user = '@'.$replayparentres->username;
   $cnt .='<div class="activity no-comments  replayhide-'.$replayid.' ">

   <!-- start Parent -->
   <div class="row" style="border:0px solid red; margin:0px; padding:0px;">
   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">
    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-2 image-div" style="padding:0; overflow:hidden">
    <a href="'.userlink($replayparentres->username).'" class="pull-left  bizcard" data-userid="'.$replayparentres->userid.'">'.$userimg.'</a>
	</div><!--/ end : col-md-1 -->

     <div class="col-md-11 col-lg-11 col-sm-11 col-xs-10" style="padding:0px 3px 0px 8px;">
            <div class="activity-container">
              <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <a href="'.userlink($replayparentres->username).'" class="author bizcard" data-userid="'.$replayparentres->userid.'">'. $replayparentres->username .'</a>
                  <div class="meta-info"><a class="author bizcard replies-to" onclick="replaycontent('.$alter.','.$replayid.')" data-userid="'.$replayparentres->userid.'">Replies to @'. $replayparentres->username .'</a>
                  '.$grp.'
				  </div>
              <div class="activity-options">'.$delete.''.$fav.'</div>
               </div>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'/view/post:'.$replayid.'" class="permlink">'.$date.' <span class="glyphicon glyphicon-link"></span></a>
</div>



               <div class="activity-content">'.$mes.'</div>
               <div></div>
               <div class="activity-poll col-md-12 col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
               <div class="footer1 activity-footer meta-info">  </div>
            </div>
			   <div id="replaypopup-'.$replayid.'" class="modal fade" ></div>

            <div class="col-md-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12">
                 
				<input type="hidden" id="time-'.$replayid.'" value="1 sec ago" />


<span class="reply icon-ftr icon-ftr-reply">
				<a  style="cursor:pointer" onclick="parentreplay('.$alter.','.$replayid.')" ><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
				</span>


				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'">'.($is_agree? '<img  width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Disagree"/>' : '<img width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree"/>').'</a>'.$showagreebtn_btn.'</div>
               	<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>

                  <div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								   
 <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($buff->permalink).'&title='.urlencode(htmlspecialchars($buff->post_message)).'&source='.urlencode($buff->permalink).'&summary='.urlencode($buff->permalink).'"  target="_blank" >Linkedin</a></li>

 <li><a href="http://plus.google.com/share?url='.urlencode($buff->permalink).'"  target="_blank" >Google Plus</a></li>

 <li><a href="http://twitter.com/intent/tweet?text='.urlencode($buff->permalink).': '.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Twitter</a></li>

 <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($buff->permalink).'&t='.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
                         
                          <div class="like-list icon-ftr">'.$mark_content.'</div>							
   						
   			</div> <!--/ end :  activity-footer meta-info -->

</div><!--/ end : activity container -->
  
</div><!--/ end : col-md-11 -->


</div><!--/ end : col-md-12 -->


</div><!-- end : activity no-comments-->   
   ';
	 //$cnt           ='<div id="replydis-'.$parentid.'"><a style="color:blue;" class="pull-right" href="#" onclick="replaycontent('.$alter.','.$parentid.')">View Replies</a></div>';
	 echo $cnt;exit;     
	


/********************* End: Timeline Buzz Child Reply **********************/
	
}
	
}
elseif($_GET['action']=="eventcomment"){
	
	$chieldid=$_POST['chieldid'];
	$db2		= & $this->network->db2;
	$db_user_id		= intval($this->user->id);
	$db_message		= $db2->escape($_POST['message']);
	$db_date		= time();
	$db_ip_addr		= ip2long($_SERVER['REMOTE_ADDR']);
	$db_attached	= '0';
	$db_date		= time();
	$db_ip_addr		= ip2long($_SERVER['REMOTE_ADDR']);
	$parentid=$_POST['postid'];
	$message=$_POST['message'];
	$alter=$_POST['alterparentid'];
	
	    $display_type ='community';
		$title = $db2->escape($_POST['title']);
		$start_date = $db2->escape($_POST['start_date']);
		$start_time = $db2->escape($_POST['start_time']);
		$end_date = $db2->escape($_POST['end_date']);
		$end_time = $db2->escape($_POST['end_time']);
		$location = '';
		$address = $db2->escape($_POST['address']);
		if(!empty($_POST['description'])){
		$description = $db2->escape($_POST['description']);
		}else{
			$description ='';
			
		}
		if(!empty($_POST['url'])){
			$url = $db2->escape($_POST['url']);
        }else{
			$url ='';
		}
		if(!empty($_POST['street_group'])){
			$street_group = $db2->escape($_POST['street_group']);
			
			
		}else{
			$street_group ='';
			
		}
		$res         = $db2->query('SELECT id FROM  groups WHERE groupname="'.$street_group.'" OR title="'.$street_group.'" ');
		$sob = $db2->fetch_object($res);
		if(!empty($_POST['street_user'])){
		$street_user = $db2->escape($_POST['street_user']);
		}else{
			$street_user ='';
			
		}
		if(!empty($_POST['hastag'])){
			$hastag = $db2->escape($_POST['hastag']);
			$hastagarr        =explode("#",trim($hastag));
			$strret_arr       =array_filter($hastagarr);
			$street_count     =count($strret_arr);
			$con ='';

			foreach($strret_arr as $keys=>$vals){
							if($keys ==1){
								$con .='<span><a href="'.$C->SITE_URL.'/search/tab:tags/s:'.$vals.'"><strong>#'.$vals.'</strong></a>';
							}else{
								$con .='<strong>#'.$vals.'</strong></span>';
							}
							
		}
		}else{
			$hastag ='';
			$street_count ='';
			$con ='';

			
		}
		
		$publish_now = 1;
		$publish_date = '';
		$pub_select_day = '0000-00-00';
		$content='';
		
		$event_type = 'Normal';
		$date_time = date('M d, Y h:i A', strtotime($start_date.' '.$start_time));

		


		
		$group_id = !empty($sob ->id)?$sob ->id:'0';
		$db2->query('INSERT INTO posts SET user_id="'.$this->user->id.'",posttags="'.$street_count.'", group_id="'.$group_id.'", date="'.time().'", date_lastcomment="'.time().'", attached="1"'); 
       	$replayid = $db2->insert_id();
		$even ="event";
        $db2->query('INSERT INTO post_replay SET parent_id="'.$parentid.'",alternate_parent_id="'.$alter.'", replay_id="'.$replayid.'",action_type="'.$even.'" ');
        $id=$this->db2->query('INSERT INTO `events` (publish_now, publish_date, activity_pub_date, created_at, modified_at, group_id, admin_id, location, address, event_type, display_type, 	event_name, event_description, start_date, 	start_time,end_date, time_zone, end_time, is_private, status,street_group,street_user,tag_name,url) 
											VALUES ("'.$publish_now.'","'.$publish_date.'","'.$pub_select_day.'", now(), now(), "'.$group_id.'", "'.$this->user->id.'", "'.$location.'", "'.$address.'", "'.$event_type.'", "'.$display_type.'", "'.$title.'", "'.$description.'", "'.date('Y-m-d H:i:s',strtotime($start_date)).'", "'.date('Y-m-d H:i:s',strtotime($start_time)).'", "'.date('Y-m-d H:i:s',strtotime($end_date)).'", "","'.date('Y-m-d H:i:s',strtotime($end_time)).'","'.$private.'",1,"'.$street_group.'","'.$street_user.'","'.$hastag.'","'.$url.'")');
		$id = $db2->insert_id();
	   $db2->query('INSERT INTO event_posts SET event_id="'.$id.'", post_id="'.$replayid.'", created = "'.date('Y-m-d H:i:s').'"');
	
	$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);

	   	$ownuserres          =$buff->get_own_user($alter);
	$ownuserid           =$ownuserres->user_id;
	$not_type='ntf_me_on_post_replay';
	$checkuserres =$buff->checkemptyuser($ownuserid);
	if($checkuserres->num_rows == "0"){
		$ownnotification =1;
	}else{
		$ownnotification     =$buff->checknotrules($ownuserid,$not_type);
		if(!empty($ownnotification)){
						$ownnotification = $ownnotification;
			}else{
						$ownnotification =1;
					}
	}

	if($ownnotification ==1 || $ownnotification ==2 || $ownnotification ==3 ){
				
	if($ownuserid != $this->user->id){
	$notifytype="replay";
	$standardtype ="ntf_me_on_post_replay";

	$posttype      =$buff->typeofpostofevent($alter);
	if($posttype->num_rows > 0){
		$type ="event";
	}else{
	$polltype      =$buff->typeofpostofpoll($alter);
		if($posttype->num_rows > 0){
			$type ="poll";
		}else{
		$activitiestype      =$buff->typelinks($alter);
		if(!empty($activitiestype)){
			
		 if($activitiestype->type=="videoembed"){
			 $type="video link";
			 
		 }elseif($activitiestype->type=="image"){
			  $type="image";
			 
		 }elseif($activitiestype->type=="file"){
			  $str = (unserialize($activitiestype->data));
			 $ext = pathinfo($str->file_original, PATHINFO_EXTENSION);
			 if($ext =='wmv' || $ext =='mp4' || $ext =='avi' || $ext =='mov' || $ext =='qt'){
				 $type = "video";
				 
			 }else{
				  $type = "file";
				 
			 }
		}
		}else{
		$type ="buzz";	
		}

		}				
	}

	$newisert =$buff->insert_active_notifications($ownuserid,$alter,$notifytype,$type,$standardtype);
	}
	}
	   	   	  
	   
	   $q =array();

					//insert to followers data
					if($this->user->info->is_posts_protected == 0){
						$u	= $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers;
					}else{
						$u	= array_intersect_key($this->network->get_user_follows($this->user->id, FALSE, 'hefollows')->follow_users, $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers);
					}
							
					$u	= $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers;
					foreach($u as $k=>$v) {
						if($k !=$this->user->id){
						$q[]	= '("'.$k.'", "'.$replayid.'")';
						}
					}
					
					if( $group_id ) {
						$u	= $this->network->get_group_members($group_id);
						if($u) {
							foreach($u as $k=>$v) {
								$z[]	= '("'.$k.'", "'.$replayid.'")';
							}
						}
						$q	= array_unique($q);
						$q = array_intersect($q,$z);					
					}
					
					if( count($q) > 0 ) { 

						$q	= implode(', ', $q);
						
						$db2->query('INSERT INTO post_userbox (user_id, post_id) VALUES '.$q);
					}
					$db2->query('INSERT INTO post_userbox SET user_id="'.$this->user->id.'", post_id="'.$replayid.'",event_status=1,status=1 ');

	   if(!empty($strret_arr )){
					foreach($strret_arr as $keys=>$vals){
						$db2->query('INSERT INTO post_tags SET user_id="'.$this->user->id.'",tag_name="'.$vals.'",post_id="'.$replayid.'",group_id="'.$group_id.'", date="'.time().'"'); 

					
					}
		}
		$group_id    = !empty($_POST['group_id'])?$this->db2->escape($_POST['group_id']):'0';
		$addition_url = empty($group_id) ? '' : '/group:'.$group_id;
		$content='';
					/* $content_title = '<div class="title"><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/event.png"> <a href="'.$C->SITE_URL.'plugin/events/view/id:'.$id.$addition_url.'/postid:'.$pid.'"  class="event-list-title"><strong>Event Name:</strong> '.$title.'</a></div>';
                    $content .= '<span class="event-list-heading">Location:</span> <span class="event-list-txt">'.$address.'</span><br />';      
					if(!empty($_POST['url'])){

					$content .='<span class="event-list-heading">URL:</span> <span class="event-list-txt"><a href="'.$url.'"  target="_blank">'.$url.'</a></span><br />';						
                    }
                    $content .='<span class="event-list-heading">Date and Time:</span> <span class="event-list-txt">'.$date_time.'</span><br />	
						';
					if(!empty($strret_arr )){
						$content .='<span class="event-list-heading">Hash Tags:</span> <span class="event-list-txt">'.$con.'</span><br />';	
                    }
					*/
	



	$content .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" style="padding:10px 10px 0px 10px;">
    <!-- start : event title -->
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz- zeropadding">
    <ul class="list-inline single-line">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-calendar-event.png" class="img-responsive">
    </li>
    <li>
    <a href="'.$C->SITE_URL.'plugin/events/view/id:'.$id.$addition_url.'/postid:'.$pid.'" class="buzz-title">
    '.$title.'</a>
    </li>
    </ul>  
    </div>
    <!-- end : event title -->

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
    <!-- start : event location -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-location-event.png" class="img-responsive"></li>
    <li>'.$address.'</li>
    </ul>  
    </div>
    <!-- end : event location -->
    <!-- start : event date & time -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-calendar-event.png" class="img-responsive"></li>
    <li>'.$date_time.'</li>
    </ul>  
    </div>
    <!-- end : event date & time -->
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">';
	if($url !=''){
   $content .='<!-- start : event url -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-url-event.png" class="img-responsive"></li>
    <li><a href="'.$url.'"  target="_blank">'.$url.'</a></li>
    </ul>  
    </div>
    <!-- end : event url -->';
	}
	if(!empty($strret_arr )){
    $content .='<!-- start : event hashtag -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-hashtag-event.png" class="img-responsive"></li>
    <li>'.$con.'	</li>
    </ul>  
    </div>
    <!-- end : event hashtag -->';
	}
    $content .='</div>

  
    </div>

						';
		            $answer = (object)array(
							
							'description'	=> $content,
							'hits'			=> 'link'
					);
		$db2->query('INSERT INTO posts_attachments SET post_id="'.$replayid.'", type="link",data="'.$db2->escape(serialize($answer)).'"');
		$finalcon ='';
		$finalcon .= '<div class="title"><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/event.png"> <a href="'.$C->SITE_URL.'plugin/events/view/id:'.$id.$addition_url.'/postid:'.$pid.'"  class="event-list-title"><strong>Event Name:</strong> '.$title.'</a></div>';
                    $finalcon .= '<span class="event-list-heading">Location:</span> <span class="event-list-txt">'.$address.'</span><br />';      
					if(!empty($_POST['url'])){

					$finalcon .='<span class="event-list-heading">URL:</span> <span class="event-list-txt"><a href="'.$url.'"  target="_blank">'.$url.'</a></span><br />';						
                    }
                    $finalcon .='<span class="event-list-heading">Date and Time:</span> <span class="event-list-txt">'.$date_time.'</span><br />	
						';
					if(!empty($strret_arr )){
						$finalcon .='<span class="event-list-heading">Hash Tags:</span> <span class="event-list-txt">'.$con.'</span><br />';	
                    }
     $data=array();
	if($parentid == $alter){
		$seriesarray     =serialize(array($parentid,$replayid));

		$db2->query('UPDATE posts SET date_lastcomment="'.$db_date.'" where id="'.$alter.'"');
		$db2->query("UPDATE post_replay SET series='".$seriesarray."' where replay_id='".$replayid."' ");


		$postlevel = 1;
	  $db2->query('UPDATE posts SET post_level="'.$postlevel.'" where id="'.$replayid.'"');

	/*$r	= $this->db2->query('SELECT p.*,users.id as userid,users.avatar as pic, users.username as username FROM posts as p
     inner join post_replay as pr ON p.id = pr.replay_id 	
	inner join users on p.user_id=users.id WHERE pr.replay_id="'.$replayid.'" order by p.date desc', FALSE);*/
	$r	= $this->db2->query('SELECT p.*,pr.replay_id as replayid,users.id as userid,users.avatar as pic, users.username as username FROM posts as p
     inner join post_replay as pr ON p.id = pr.replay_id 	
	inner join users on p.user_id=users.id WHERE pr.parent_id="'.$parentid.'" order by p.date ASC', FALSE);
	while($result=$this->db2->fetch_object($r))
	{
		$data[]=$result;
	}
	$cnt='';
   $dcnt = count($data)-1;

	$obj=$data[0];
	$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);
	$parentquery = $this->db2->query('SELECT p.*,users.id as userid,users.avatar as pic, users.username as username FROM posts as p
	inner join users on p.user_id=users.id WHERE p.id="'.$parentid.'" ', FALSE);
	$parentres =$this->db2->fetch_object($parentquery);
    $eventdetails = $buff->geteventdetails($parentid);
	$poll  = $buff->replay_is_poll($parentid);
	$assetdata   =$buff->assetdata($parentid);
	$prediction_data =$buff->predictiondata($parentid);

	if(!empty($assetdata)){
		$parentmessage = $buff->assethtml($parentid);
	}elseif(!empty($eventdetails)){
		$parentmessage =$buff->eventhtml($parentid);
    }elseif(!empty($poll)){
		$parentmessage =$buff->pollchildhtml($parentid);
		
	}elseif(!empty($prediction_data)){
						if($prediction_data[0]->status =="OPEN"){

            //calculations for up rate
				$predict_value = $prediction_data[0]->predict_value;
				$prediction_base_price = $prediction_data[0]->prediction_base_price;
				$percentage             =(($predict_value-$prediction_base_price)/($prediction_base_price))*100;
				$percentage = number_format((float)$percentage, 2, '.', '');
				
				if (strpos($percentage, '-') !== false) {
					$con ='down';
	               $imag ='down-arrow-prediction.png';
				}else{
					$con ='up';
					$imag ='up-arrow-prediction.png';
				}
			$parentmessage .='<div class="prediction-buzz-data">'.$prediction_data[0]->asset_name.'($'.$prediction_data[0]->ticker.')<img src="'.$C->SITE_URL.'/static/images/icons/'.$imag.'"> to be '.$con.' by '.$percentage.'% from '.$prediction_data[0]->prediction_base_price.' in '.substr($prediction_data[0]->end_date,0,10).' because of '.$prediction_data[0]->predict_reason.'.</div>';
					}else{
											//calculations for up rate
				$predict_result = $prediction_data[0]->predict_result;
				if($predict_result =='CORRECT'){
					 $imag ='hit.png';
					 $type ="Hit";
					 $percentage='';
					
				}else{
					 $imag ='miss.png';
					 
					 
					  $predict_value = $prediction_data[0]->predict_value;
					  $prediction_base_price = $prediction_data[0]->prediction_base_price;
					  $percentage             =(($predict_value-$prediction_base_price)/($prediction_base_price))*100;
					  $percentage = substr(number_format((float)$percentage, 2, '.', ''),1);
					  $type =" Mis by ".$percentage."%";
					
				}
				if($buff->post_user->id == $user->id){
					$handset ='If you want to change Hindsight reason please <a  class="mymodal" data-toggle="modal" data-target="#myModal-'.$prediction_data[0]->post_id.'"  >click here </a> 

  
  <!-- Modal -->
  <div class="modal fade-'.$prediction_data[0]->post_id.'" id="myModal-'.$prediction_data[0]->post_id.'" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Handset reason </h4>
        </div>
        <div class="modal-body">
		<div class="row">
		 <div>Reason :<input type="text" value="'.$prediction_data[0]->hindsight_reason.'" id="hindsight-'.$prediction_data[0]->post_id.'" onkeyup="validate(this,'.$prediction_data[0]->post_id.')">
		 </div>
		  <div id="handsetreason-error-'.$prediction_data[0]->post_id.'"class="notifyjs-container" style="top: 37px; left: 168px; overflow: hidden; display: hidden;"><div class="notifyjs-bootstrap-base notifyjs-bootstrap-error">
            <span data-notify-text="" class="notifyjs-text">This field is required</span>
         </div></div>
		 		   <button type="button" class="btn btn-default btn-primary"  data-toggle="modal"  onclick="changehandset('.$prediction_data[0]->post_id.')">Change</button>

		</div>


          
        </div>
		<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      
      </div>
      
    </div>
  </div>
  
					
					';
				}else{
					$handset ='';
					
				}
				$parentmessage .='<div style="background-color:#e3f8fe;font-size:12px;height:auto;padding: 10px;"> Your prediction on '.$prediction_data[0]->asset_name.'($'.$prediction_data[0]->ticker.') done  on '.substr($prediction_data[0]->end_date,0,10).' was a '.$type.' <img src="'.$C->SITE_URL.'static/images/icons/'.$imag.'">.'.$handset.' 
				</div>';
						
					}
	}else{
		$parentmessage =($parentres->message);
		 $link = $buff->findlink($parentid);
		if(!empty($link)){
				$parentmessage  .=$buff->linkhtml($parentid);
			}	

		
	}

		//user post shared or not checking
                $is_reshared    =$buff->is_post_reshared($parentid);
				$reshares       =$buff->loaded_posts_reshares($parentid);
				$resharecnt     =count($reshares);


			        $like_content ='';
					$is_liked  = $buff->new_liked($parentid);
					$likes_number = $buff->new_liked_count($parentid);
					$like_number        =$likes_number->likecount;

					$css="icons";
				$is_spam  = $buff->is_spam($parentid,$buff->post_type);
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'"><em><img  src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'"><em><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }
				$is_agree = $buff->is_post_agree($user->id,$parentid);
				$is_agree_cnt = $buff->is_post_agree_cnt($parentid);
				 if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   if($like_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'">'.$like_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>').'</a>';
                    if($reshare_content > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'">'.$resharecnt.'</a>';
                    }else{
						$resharecnt ='';
						
					}
					$txt =post::replay_parse_date($parentres->date);
		$date = str_replace(array_keys($tmp), array_values($tmp), $txt);
					$delete = (($user->is_logged && $buff->if_can_delete())? '<a href="" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'" data-role="services" data-namespace="activities" title="Delete" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>' : '');
       $is_fav  = $buff->isfav($user->id,$parentid);
	 
   			if(!empty($is_fav)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$parentid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$parentid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
			$groups = $buff->getgroupname($parentres->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
		 if($parentres->pic !=''){
					$userimg ='<img src="'.getAvatarUrl($parentres->pic, 'thumbs1').'" alt="'.$parentres->username.'" />';
					
				}else{
					$userimg ='<img src="'.$C->STORAGE_URL.'avatars/thumbs1/_noavatar_user.gif" alt="'.$parentres->username.'" />';
					
				}




	/************ Start: Timeline Parent - Buzz Reply Popup > Event Reply *************/
   	


	$cnt .='<div class="activity  no-comments replayhide-'.$parentid.'" id="main'.$parentid.'" >
   <!-- start Parent -->
   <div class="row" style="border:0px solid red; margin:0px; padding:0px;">
   <div class="janeesh'.$parentid.'"></div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">
          <div class="col-md-1 col-lg-1 col-sm-1 col-xs-2 image-div" style="padding:0; overflow:hidden">
          <a href="'.userlink($parentres->username).'" class="pull-left  bizcard" data-userid="'.$parentres->userid.'">'.$userimg.'</a>
			</div><!--/ end : col-md-1 -->

         <div class="col-md-11 col-lg-11 col-sm-11 col-xs-10" style="padding:0px 3px 0px 8px;">
            <div class="activity-container">
             <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <a href="'.userlink($parentres->username).'" class="author bizcard" data-userid="'.$parentres->userid.'">'. $parentres->username .'</a>
                  <div class="meta-info">'.$grp.'
                  </div>
              <div class="activity-options">'.$delete.''.$fav.'</div>
               </div>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'/view/post:'.$parentid.'" class="permlink">'.$date.' <span class="glyphicon glyphicon-link"></span></a>
</div>


               <div class="activity-content">'.$parentmessage.'</div>
               <div></div>
               <div class="activity-poll col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
               <div class="footer1 activity-footer meta-info">  </div>
            </div>
			   <div id="replaypopup-'.$parentid.'" class="modal fade" ></div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
               <div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
                
				<input type="hidden" id="time-'.$parentid.'" value="1 sec ago" />

				<span class="reply icon-ftr icon-ftr-reply">
				<a  style="cursor:pointer" onclick="parentreplay('.$parentid.','.$parentid.')" ><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
				</span>

				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$parentid.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$parentid.'"}').'">'.($is_agree? '<img  width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Disagree"/>' : '<img width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree"/>').'</a>'.$showagreebtn_btn.'</div>
               	<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>

                  <div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							    
 <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($buff->permalink).'&title='.urlencode(htmlspecialchars($buff->post_message)).'&source='.urlencode($buff->permalink).'&summary='.urlencode($buff->permalink).'"  target="_blank" >Linkedin</a></li>

 <li><a href="http://plus.google.com/share?url='.urlencode($buff->permalink).'"  target="_blank" >Google Plus</a></li>

 <li><a href="http://twitter.com/intent/tweet?text='.urlencode($buff->permalink).': '.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Twitter</a></li>

 <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($buff->permalink).'&t='.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
                          

                          <div class="like-list icon-ftr">'.$mark_content.'</div>							
   						
   			</div> <!--/ end :  activity-footer meta-info -->

</div><!--/ end : activity container -->
  
</div><!--/ end : col-md-11 -->


</div><!--/ end : col-md-12 -->


</div><!-- end : activity no-comments-->   
   ';



 	/************ End: Timeline Parent - Buzz Reply Popup > Event Reply *************/



	foreach($data as $keys=>$row)
	{
		
		//user post shared or not checking
                $is_reshared    =$buff->is_post_reshared($row->replayid);
				$reshares       =$buff->loaded_posts_reshares($row->replayid);
				$resharecnt     =count($reshares);


			        $like_content ='';
					$is_liked  = $buff->new_liked($row->replayid);
					$likes_number = $buff->new_liked_count($row->replayid);
					$like_number        =$likes_number->likecount;
					$css="icons";
				$is_spam  = $buff->is_spam($row->replayid,$buff->post_type);
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'"><em><img  src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'"><em><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }
				$is_agree = $buff->is_post_agree($user->id,$row->replayid);
				$is_agree_cnt = $buff->is_post_agree_cnt($row->replayid);
				 if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   if($like_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'">'.$like_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>').'</a>';
                    if($reshare_content > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'">'.$resharecnt.'</a>';
                    }else{
						$resharecnt ='';
						
					}
					$delete = (($user->is_logged && $buff->if_can_delete())? '<a href="" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'" data-role="services" data-namespace="activities" title="Delete" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>' : '');
             $is_fav  = $buff->isfav($user->id,$row->replayid);
	 
   			if(!empty($is_fav)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$row->replayid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$row->replayid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
			$groups = $buff->getgroupname($row->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
					
					if($dcnt == $keys){
						$css="tree1";
						$chi ="child".$parentid;
					}else{
						$css="tree";
						$chi ='';
					}
					$buzztype = $buff->getbuzztype($row->replayid);
					if($buzztype =="buzz" || $buzztype =="" ){
					$mes = $buff->parsetext($row->message);
					 $link = $buff->findlink($chield[$m]->id);
					if(!empty($link)){
					$mes  .=$buff->timelinelinkhtml($row->replayid);
					}
                }elseif($buzztype =="event"){
					$mes	    = $buff->eventhtml($row->replayid);
                 }elseif($buzztype =="poll"){
					 $mes	    =$buff->pollchildhtml($row->replayid);
	
				}elseif($buzztype =="intraday"){
			    //   $mes   = $buff->assethtml($row->replayid);
		
				}	
					
		$txt =post::replay_parse_date($row->date);
		$date = str_replace(array_keys($tmp), array_values($tmp), $txt);
		 if($row->pic !=''){
					$userimg ='<img src="'.getAvatarUrl($row->pic, 'thumbs1').'" alt="'.$row->username.'" />';
					
				}else{
					$userimg ='<img src="'.$C->STORAGE_URL.'avatars/thumbs1/_noavatar_user.gif" alt="'.$row->username.'" />';
					
				}







/***** START : TIMELINE Parent > EVENT Buzz > All Childs *******/


		$cnt.='<div id="'.$chi.'" class="activity no-comments zeropadding commentcontainer'.$replayid.'"  style="border:0px solid red;">

<ul class="'.$css.'">
			<li>
<!-- start Parent -->
<div class="row activity-parent">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box">

 <div class="col-md-1 col-lg-1 col-sm-1 col-xs-2 image-div" style="padding:0; overflow:hidden"><a href="'.userlink($row->username).'" class="pull-left  bizcard" data-userid="'.$row->userid.'">'.$userimg.'</a>
			</div>

 <div class="col-md-11 col-lg-11 col-sm-11 col-xs-10" style="padding:0px 3px 0px 8px;">

<div class="activity-container">
  <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12"><a href="'.userlink($row->username).'" class="author bizcard" data-userid="'.$row->userid.'">'. $row->username .'</a>
   <div class="meta-info">'.$grp.'</div>			
			
<div class="activity-options">'.$delete.''.$fav.'</div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'/view/post:'.$replayid.'" class="permlink">'.$date.' <span class="glyphicon glyphicon-link"></span></a>
</div>


<div class="activity-content">'.$mes.'</div>
</div>		

<div id="replydis-'.$replayid.'"></div>


		
		
		
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12">
				
				<input type="hidden" id="time-'.$replayid.'" value="1 sec ago" />

<span class="reply icon-ftr icon-ftr-reply">
				<a  style="cursor:pointer" onclick="childpopup('.$parentid.','.$replayid.')" ><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
</span

				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'">'.($is_agree? '<img  width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Disagree"/>' : '<img width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree"/>').'</a>'.$showagreebtn_btn.'</div>
               	<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>

                  <div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								   
 <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($buff->permalink).'&title='.urlencode(htmlspecialchars($buff->post_message)).'&source='.urlencode($buff->permalink).'&summary='.urlencode($buff->permalink).'"  target="_blank" >Linkedin</a></li>

 <li><a href="http://plus.google.com/share?url='.urlencode($buff->permalink).'"  target="_blank" >Google Plus</a></li>

 <li><a href="http://twitter.com/intent/tweet?text='.urlencode($buff->permalink).': '.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Twitter</a></li>

 <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($buff->permalink).'&t='.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
							
                           <div class="like-list icon-ftr">'.$mark_content.'</div>						
							</div>
				 </div>

<div></div>
</div>

</div>
</div>
<!-- end Parent -->
</li>
</ul>

</div>';
	}
	$cnt.='</div></div>





	<script>
	$(document).ready(function(){
		var mainheight = $("#main'.$parentid.'").height();
		var childheight = $("#child'.$parentid.'").height();
		var final = mainheight-childheight;
		$(".janeesh'.$parentid.'").css("height",final);


	});
	
	</script>
	
	
	';


	echo $cnt;exit;
}elseif($parentid != $alter){

	$seriesquery = $this->db2->query('SELECT series  FROM post_replay as pr
	 WHERE pr.parent_id="'.$parentid.'" AND pr.alternate_parent_id="'.$alter.'" order by id desc limit 1,1 	 ', FALSE);
	 $series=$this->db2->fetch_object($seriesquery);
	 if($series->series !=''){
		 $series = $series;
	}else{
		$seriesquery = $this->db2->query('SELECT series  FROM post_replay as pr
	 WHERE  pr.replay_id="'.$chieldid.'" order by id desc limit 1	 ', FALSE);
	
	 $series=$this->db2->fetch_object($seriesquery);
		
	}
	 $seriesarrayold  = unserialize($series->series);
	 $seriesarraynew     =(array($parentid,$alter,$replayid));
	 $seriesnow                   =array_merge($seriesarrayold,$seriesarraynew);


	 $seriesarray     =serialize($seriesnow);
	 $db2->query("UPDATE post_replay SET series='".$seriesarray."' where replay_id='".$replayid."' ");


	  

	 
			//$db2->query('UPDATE posts SET date_lastcomment="'.$db_date.'" where id="'.$alter.'"');

	$postlevel =1;
		//$db2->query('UPDATE posts SET post_level="'.$postlevel.'" where id="'.$alter.'"');

		$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);


	$r	= $this->db2->query('SELECT  p.*,users.id as userid,users.avatar as pic, users.username as username FROM posts as p
	inner join users on p.user_id=users.id WHERE p.id="'.$replayid.'" order by p.date desc', FALSE);
	$replayparentres=$this->db2->fetch_object($r);
	
			
				$buzztype = $buff->getbuzztype($replayid);
				if($buzztype =="buzz" || $buzztype =="" ){
					$mes = $buff->parsetext($replayparentres->message);	
					 $link = $buff->findlink($replayid);
					if(!empty($link)){
					$mes  .=$buff->timelinelinkhtml($replayid);
					}
                }elseif($buzztype =="event"){
					$mes	    =$buff->eventhtml($replayid);
                 }elseif($buzztype =="poll"){
					 $mes	    =$buff->pollchildhtml($replayid);
	
				}elseif($buzztype =="intraday"){
			       $mes   =$buff->assethtml($replayid);
		
				}	
				
				$txt =post::replay_parse_date($replayparentres->date);
		        $date = str_replace(array_keys($tmp), array_values($tmp), $txt);
				 if(($user->id == $replayparentres->userid )){
					
   
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity"> <img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				} 
       $is_fav  = $buff->isfav($user->id,$replayid);
	 
   			if(!empty($is_fav)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
			$groups = $buff->getgroupname($replayparentres->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
		 if($replayparentres->pic !=''){
					$userimg ='<img src="'.getAvatarUrl($replayparentres->pic, 'thumbs1').'" alt="'.$replayparentres->username.'" />';
					
				}else{
					$userimg ='<img src="'.$C->STORAGE_URL.'avatars/thumbs1/_noavatar_user.gif" alt="'.$replayparentres->username.'" />';
					
				}
		


	 $user      ='@'.$replayparentres->username;
	 	$cnt .='<div class="activity no-comments replayhide-'.$replayid.' ">
   <!-- start Parent -->
   <div class="row"  style="border:0px solid red; margin:0px; padding:0px;">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">
        <div class="col-md-1 col-lg-1 col-sm-1 col-xs-2 image-div" style="padding:0; overflow:hidden"><a href="'.userlink($replayparentres->username).'" class="pull-left  bizcard" data-userid="'.$replayparentres->userid.'">'.$userimg.'</a>
			</div><!--/ end : col-md-1 -->

        <div class="col-md-11 col-lg-11 col-sm-11 col-xs-10" style="padding:0px 3px 0px 8px;">
            <div class="activity-container">
             <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <a href="'.userlink($replayparentres->username).'" class="author bizcard" data-userid="'.$replayparentres->userid.'">'. $replayparentres->username .'</a>
                  <div class="meta-info"><a class="author bizcard replies-to" onclick="replaycontent('.$alter.','.$replayid.')" data-userid="'.$replayparentres->userid.'">Replies to @'. $replayparentres->username .'</a>
                  '.$grp.'
				  </div>
              <div class="activity-options">'.$delete.''.$fav.'</div>
               </div>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
               <a href="'.$C->SITE_URL.'/view/post:'.$replayid.'" class="permlink">'.$date.' <span class="glyphicon glyphicon-link"></span></a>
</div>


               <div class="activity-content">'.$mes.'</div>
               <div></div>
               <div class="activity-poll col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
               <div class="footer1 activity-footer meta-info">  </div>
            </div>
			   <div id="replaypopup-'.$replayid.'" class="modal fade" ></div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               <div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12">
                
				<input type="hidden" id="time-'.$replayid.'" value="1 sec ago" />

				<span class="reply icon-ftr icon-ftr-reply">
				<a  style="cursor:pointer" onclick="parentreplay('.$alter.','.$replayid.')" ><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
				</span>

				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'">'.($is_agree? '<img  width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Disagree"/>' : '<img width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree"/>').'</a>'.$showagreebtn_btn.'</div>
               	<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>

                  <div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								   
 <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($buff->permalink).'&title='.urlencode(htmlspecialchars($buff->post_message)).'&source='.urlencode($buff->permalink).'&summary='.urlencode($buff->permalink).'"  target="_blank" >Linkedin</a></li>

 <li><a href="http://plus.google.com/share?url='.urlencode($buff->permalink).'"  target="_blank" >Google Plus</a></li>

 <li><a href="http://twitter.com/intent/tweet?text='.urlencode($buff->permalink).': '.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Twitter</a></li>

 <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($buff->permalink).'&t='.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>

                

 <div class="like-list icon-ftr">'.$mark_content.'</div>							
   						
   			</div> <!--/ end :  activity-footer meta-info -->

</div><!--/ end : activity container -->
  
</div><!--/ end : col-md-11 -->


</div><!--/ end : col-md-12 -->


</div><!-- end : activity no-comments-->
   
   ';
	 //$cnt           ='<div id="replydis-'.$parentid.'"><a style="color:blue;" class="pull-right" href="#" onclick="replaycontent('.$alter.','.$parentid.')">View Replies</a></div>';
	 echo $cnt;exit;       
	

	
}
	
	
}elseif($_GET['action'] =='pollcomment'){
		$chieldid=$_POST['chieldid'];

	   $question          =isset($_POST['question']) ? $_POST['question']:'';
	   $group          =isset($_POST['group']) ? $_POST['group']:0;
	   $users          =isset($_POST['users']) ? $_POST['users']:0;
	   $answers          =isset($_POST['answers']) ? $_POST['answers']:array();
	   $res         = $db2->query('SELECT id FROM  groups WHERE groupname="'.$group.'" OR title="'.$group.'" ');
	   $sob = $db2->fetch_object($res);
	   $group_id = !empty($sob ->id)?$sob ->id:'0';
	   $parentid=$_POST['postid'];
	   $alter=$_POST['alterparentid'];
	   $db_api_id		= '0';
		$db_user_id		= intval($this->user->id);
		$db_group_id	= '0';
		$db_to_user		= '0';
		$db_mentioned	= '0';
		$db_attached	= '0'; //change here
		$db_posttags	= '0';
		$db_date		= time();
		$db_ip_addr		= ip2long($_SERVER['REMOTE_ADDR']);
		$db2->query('INSERT INTO posts SET api_id="'.$db_api_id.'", user_id="'.$db_user_id.'", group_id="'.$group_id.'", mentioned="'.$db_mentioned.'", posttags="'.$db_posttags.'", attached="'.$db_attached.'", date="'.$db_date.'", date_lastcomment="'.$db_date.'", ip_addr="'.$db_ip_addr.'" ');
		$replayid = $db2->insert_id();
		if(!empty($replayid)){
			$even ="poll";
	       $db2->query('INSERT INTO post_replay SET parent_id="'.$parentid.'",alternate_parent_id="'.$alter.'", replay_id="'.$replayid.'",action_type="'.$even.'" ');

			$db2->query('INSERT INTO post_userbox SET user_id="'.$this->user->id.'", post_id="'.$replayid.'"');
						$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);

			$ownuserres          =$buff->get_own_user($alter);
			$ownuserid           =$ownuserres->user_id;
			$not_type='ntf_me_on_post_replay';
			$checkuserres =$buff->checkemptyuser($ownuserid);
			if($checkuserres->num_rows == "0"){
				$ownnotification =1;
			}else{
				$ownnotification     =$buff->checknotrules($ownuserid,$not_type);
				if(!empty($ownnotification)){
						$ownnotification = $ownnotification;
					}else{
						$ownnotification =1;
					}



			}
			if($ownnotification ==1 || $ownnotification ==2 || $ownnotification ==3 ){
				
			if($ownuserid != $this->user->id){
			$notifytype="replay";
			$standardtype ="ntf_me_on_post_replay";

			$posttype      =$buff->typeofpostofevent($alter);
			if($posttype->num_rows > 0){
				$type ="event";
			}else{
			$polltype      =$buff->typeofpostofpoll($alter);
				if($posttype->num_rows > 0){
					$type ="poll";
				}else{
				$activitiestype      =$buff->typelinks($alter);
				if(!empty($activitiestype)){
					
				 if($activitiestype->type=="videoembed"){
					 $type="video link";
					 
				 }elseif($activitiestype->type=="image"){
					  $type="image";
					 
				 }elseif($activitiestype->type=="file"){
					  $str = (unserialize($activitiestype->data));
					 $ext = pathinfo($str->file_original, PATHINFO_EXTENSION);
					 if($ext =='wmv' || $ext =='mp4' || $ext =='avi' || $ext =='mov' || $ext =='qt'){
						 $type = "video";
						 
					 }else{
						  $type = "file";
						 
					 }
				}
				}else{
				$type ="buzz";	
				}

				}				
			}

			$newisert =$buff->insert_active_notifications($ownuserid,$alter,$notifytype,$type,$standardtype);
			}
			}
			

			$query = "INSERT INTO polls SET 
							poll_date = '".time()."',  
							poll_question = '".$this->db2->e($question)."', 
							poll_is_active = '0',
							poll_allow_user_answer = '".($answers ? 1 : 0)."',
							posts_id = '".$replayid."'";
			
							
					$this->db2->query($query, FALSE);
					$poll_id = $this->db2->insert_id();
					foreach($answers as $key => $val) 
					{
						if($val !=''){
						$query2 = "INSERT INTO polls_answers SET 
								poll_id = '".$poll_id."',
								answer = '".$this->db2->e($val)."', 
								votes = '0'";
						$this->db2->query($query2, FALSE);
						}
					}
					$q =array();

					//insert to followers data
					if($this->user->info->is_posts_protected == 0){
						$u	= $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers;
					}else{
						$u	= array_intersect_key($this->network->get_user_follows($this->user->id, FALSE, 'hefollows')->follow_users, $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers);
					}
							
					$u	= $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers;
					foreach($u as $k=>$v) {
						if($k !=$this->user->id){

						$q[]	= '("'.$k.'", "'.$replayid.'")';
						}
					}
					
					if( $group_id ) {
						$u	= $this->network->get_group_members($group_id);
						if($u) {
							foreach($u as $k=>$v) {
								$z[]	= '("'.$k.'", "'.$replayid.'")';
							}
						}
						$q	= array_unique($q);
						$q = array_intersect($q,$z);					
					}
					
					if( count($q) > 0 ) { 
						$q	= implode(', ', $q);
						$db2->query('INSERT INTO post_userbox (user_id, post_id) VALUES '.$q);
					}
		}
			if($parentid == $alter){
		$seriesarray     =serialize(array($parentid,$replayid));

		$db2->query('UPDATE posts SET date_lastcomment="'.$db_date.'" where id="'.$alter.'"');
		$db2->query("UPDATE post_replay SET series='".$seriesarray."' where replay_id='".$replayid."' ");


		$postlevel = 1;
	  $db2->query('UPDATE posts SET post_level="'.$postlevel.'" where id="'.$replayid.'"');

	/*$r	= $this->db2->query('SELECT p.*,users.id as userid,users.avatar as pic, users.username as username FROM posts as p
     inner join post_replay as pr ON p.id = pr.replay_id 	
	inner join users on p.user_id=users.id WHERE pr.replay_id="'.$replayid.'" order by p.date desc', FALSE);*/
	$r	= $this->db2->query('SELECT p.*,pr.replay_id as replayid,users.id as userid,users.avatar as pic, users.username as username FROM posts as p
     inner join post_replay as pr ON p.id = pr.replay_id 	
	inner join users on p.user_id=users.id WHERE pr.parent_id="'.$parentid.'" order by p.date ASC', FALSE);
	while($result=$this->db2->fetch_object($r))
	{
		$data[]=$result;
	}
	$pollcnt='';
   $dcnt = count($data)-1;

	$obj=$data[0];
	$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);
	$parentquery = $this->db2->query('SELECT p.*,users.id as userid,users.avatar as pic, users.username as username FROM posts as p
	inner join users on p.user_id=users.id WHERE p.id="'.$parentid.'" ', FALSE);
	$parentres =$this->db2->fetch_object($parentquery);
	 $eventdetails = $buff->geteventdetails($parentid);
	$poll  = $buff->replay_is_poll($parentid);
	$assetdata   =$buff->assetdata($parentid);
	$prediction_data =$buff->predictiondata($parentid);

	if(!empty($assetdata)){
		$parentmessage =$buff->assethtml($parentid);
	}elseif(!empty($eventdetails)){
		$parentmessage =$buff->eventhtml($parentid);
    }elseif(!empty($poll)){
		$parentmessage =$buff->pollchildhtml($parentid);
		
	}elseif(!empty($prediction_data)){
						if($prediction_data[0]->status =="OPEN"){

            //calculations for up rate
				$predict_value = $prediction_data[0]->predict_value;
				$prediction_base_price = $prediction_data[0]->prediction_base_price;
				$percentage             =(($predict_value-$prediction_base_price)/($prediction_base_price))*100;
				$percentage = number_format((float)$percentage, 2, '.', '');
				
				if (strpos($percentage, '-') !== false) {
					$con ='down';
	               $imag ='down-arrow-prediction.png';
				}else{
					$con ='up';
					$imag ='up-arrow-prediction.png';
				}
			$parentmessage .='<div class="prediction-buzz-data">'.$prediction_data[0]->asset_name.'($'.$prediction_data[0]->ticker.')<img src="'.$C->SITE_URL.'/static/images/icons/'.$imag.'"> to be '.$con.' by '.$percentage.'% from '.$prediction_data[0]->prediction_base_price.' in '.substr($prediction_data[0]->end_date,0,10).' because of '.$prediction_data[0]->predict_reason.'.</div>';
					}else{
											//calculations for up rate
				$predict_result = $prediction_data[0]->predict_result;
				if($predict_result =='CORRECT'){
					 $imag ='hit.png';
					 $type ="Hit";
					 $percentage='';
					
				}else{
					 $imag ='miss.png';
					 
					 
					  $predict_value = $prediction_data[0]->predict_value;
					  $prediction_base_price = $prediction_data[0]->prediction_base_price;
					  $percentage             =(($predict_value-$prediction_base_price)/($prediction_base_price))*100;
					  $percentage = substr(number_format((float)$percentage, 2, '.', ''),1);
					  $type =" Mis by ".$percentage."%";
					
				}
				if($buff->post_user->id == $user->id){
					$handset ='If you want to change Hindsight reason please <a  class="mymodal" data-toggle="modal" data-target="#myModal-'.$prediction_data[0]->post_id.'"  >click here </a> 

  
  <!-- Modal -->
  <div class="modal fade-'.$prediction_data[0]->post_id.'" id="myModal-'.$prediction_data[0]->post_id.'" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Handset reason </h4>
        </div>
        <div class="modal-body">
		<div class="row">
		 <div>Reason :<input type="text" value="'.$prediction_data[0]->hindsight_reason.'" id="hindsight-'.$prediction_data[0]->post_id.'" onkeyup="validate(this,'.$prediction_data[0]->post_id.')">
		 </div>
		  <div id="handsetreason-error-'.$prediction_data[0]->post_id.'"class="notifyjs-container" style="top: 37px; left: 168px; overflow: hidden; display: hidden;"><div class="notifyjs-bootstrap-base notifyjs-bootstrap-error">
            <span data-notify-text="" class="notifyjs-text">This field is required</span>
         </div></div>
		 		   <button type="button" class="btn btn-default btn-primary"  data-toggle="modal"  onclick="changehandset('.$prediction_data[0]->post_id.')">Change</button>

		</div>


          
        </div>
		<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      
      </div>
      
    </div>
  </div>
  
					
					';
				}else{
					$handset ='';
					
				}
				$parentmessage .='<div style="background-color:#e3f8fe;font-size:12px;height:auto;padding: 10px;"> Your prediction on '.$prediction_data[0]->asset_name.'($'.$prediction_data[0]->ticker.') done  on '.substr($prediction_data[0]->end_date,0,10).' was a '.$type.' <img src="'.$C->SITE_URL.'static/images/icons/'.$imag.'">.'.$handset.' 
				</div>';
						
					}
	}else{
		$parentmessage =($parentres->message);
		$link = $buff->findlink($parentid);
		if(!empty($link)){
		$parentmessage  .=$buff->linkhtml($parentid);
		}

		
	}
	$txt =post::replay_parse_date($parentres->date);
	$date = str_replace(array_keys($tmp), array_values($tmp), $txt);

  //user post shared or not checking
                $is_reshared    =$buff->is_post_reshared($parentid);
				$reshares       =$buff->loaded_posts_reshares($parentid);
				$resharecnt     =count($reshares);


			        $like_content ='';
					$is_liked  = $buff->new_liked($parentid);
					$likes_number = $buff->new_liked_count($parentid);
					$like_number        =$likes_number->likecount;

					$css="icons";
				$is_spam  = $buff->is_spam($parentid,$buff->post_type);
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'"><em><img  src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				$is_agree = $buff->is_post_agree($user->id,$parentid);
				$is_agree_cnt = $buff->is_post_agree_cnt($parentid);
				 if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   if($like_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'">'.$like_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>').'</a>';
                    if($reshare_content > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'">'.$resharecnt.'</a>';
                    }else{
						$resharecnt ='';
						
					}
						$delete = (($user->is_logged && $buff->if_can_delete())? '<a href="" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'" data-role="services" data-namespace="activities" title="Delete" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>' : '');
       $is_fav  = $buff->isfav($user->id,$parentid);
	 
   			if(!empty($is_fav)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$parentid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$parentid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
			$groups = $buff->getgroupname($parentres->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
		 if($parentres->pic !=''){
					$userimg ='<img src="'.getAvatarUrl($parentres->pic, 'thumbs1').'" alt="'.$parentres->username.'" />';
					
				}else{
					$userimg ='<img src="'.$C->STORAGE_URL.'avatars/thumbs1/_noavatar_user.gif" alt="'.$parentres->username.'" />';
					
				}



   /******* Start: Timeline Parent Reply > Buzz Poll ******/

	$pollcnt .='<div class="activity no-comments replayhide-'.$parentid.'" id="main'.$parentid.'">
   <!-- start Parent -->
   <div class="row" style="border:0px solid red; margin:0px; padding:0px;">

   <div class="janeesh'.$parentid.'"></div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">

    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-2 image-div" style="padding:0; overflow:hidden">
    <a href="'.userlink($parentres->username).'" class="pull-left  bizcard" data-userid="'.$parentres->userid.'">'.$userimg.'</a>
	</div><!--/ end : col-md-1 -->

   <div class="col-md-11 col-lg-11 col-sm-11 col-xs-10" style="padding:0px 3px 0px 8px;">
            <div class="activity-container">
               <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <a href="'.userlink($parentres->username).'" class="author bizcard" data-userid="'.$parentres->userid.'">'. $parentres->username .'</a>
                  <div class="meta-info">'.$grp.'
                  </div>
              <div class="activity-options">'.$delete.''.$fav.'</div>
               </div>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
               <a href="'.$C->SITE_URL.'/view/post:'.$parentid.'" class="permlink">'.$date.'</a>
               </div>


               <div class="activity-content">'.$parentmessage.'</div>
              
               <div class="activity-poll col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
               <div class="footer1 activity-footer meta-info">  </div>
            </div>
			   <div id="replaypopup-'.$parentid.'" class="modal fade" ></div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               <div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12">
                 
				<input type="hidden" id="time-'.$parentid.'" value="1 sec ago" />

				<span class="reply icon-ftr icon-ftr-reply">
				<a  style="cursor:pointer" onclick="parentreplay('.$parentid.','.$parentid.')" ><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
				</span>

				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$parentid.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$parentid.'"}').'">'.($is_agree? '<img  width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Disagree"/>' : '<img width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree"/>').'</a>'.$showagreebtn_btn.'</div>
               	<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>

                  <div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								   
 <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($buff->permalink).'&title='.urlencode(htmlspecialchars($buff->post_message)).'&source='.urlencode($buff->permalink).'&summary='.urlencode($buff->permalink).'"  target="_blank" >Linkedin</a></li>

 <li><a href="http://plus.google.com/share?url='.urlencode($buff->permalink).'"  target="_blank" >Google Plus</a></li>

 <li><a href="http://twitter.com/intent/tweet?text='.urlencode($buff->permalink).': '.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Twitter</a></li>

 <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($buff->permalink).'&t='.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
               

                <div class="like-list icon-ftr">'.$mark_content.'</div>							
   						
   			</div> <!--/ end :  activity-footer meta-info -->

</div><!--/ end : activity container -->
  
</div><!--/ end : col-md-11 -->


</div><!--/ end : col-md-12 -->


</div><!-- end : activity no-comments-->
   ';
   /******* End: Timeline Parent - Buzz Reply > Popup Poll Reply ******/


	foreach($data as $keys=>$row)
	{
		
		//user post shared or not checking
                $is_reshared    =$buff->is_post_reshared($row->replayid);
				$reshares       =$buff->loaded_posts_reshares($row->replayid);
				$resharecnt     =count($reshares);


			        $like_content ='';
					$is_liked  = $buff->new_liked($row->replayid);
					$likes_number = $buff->new_liked_count($row->replayid);
					$like_number        =$likes_number->likecount;
					$css="icons";
				$is_spam  = $buff->is_spam($row->replayid,$buff->post_type);
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'"><em><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }
				$is_agree = $buff->is_post_agree($user->id,$row->replayid);
				$is_agree_cnt = $buff->is_post_agree_cnt($row->replayid);
				 if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   if($like_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'">'.$like_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>').'</a>';
                    if($reshare_content > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'">'.$resharecnt.'</a>';
                    }else{
						$resharecnt ='';
						
					}
					$delete = (($user->is_logged && $buff->if_can_delete())? '<a href="" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'" data-role="services" data-namespace="activities" title="Delete" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>' : '');
       $is_fav  = $buff->isfav($user->id,$row->replayid);
	 
   			if(!empty($is_fav)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$row->replayid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$row->replayid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
			$groups = $buff->getgroupname($row->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
		
				$txt =post::replay_parse_date($row->date);
		     $date = str_replace(array_keys($tmp), array_values($tmp), $txt);

					
					if($dcnt == $keys){
						$css="tree1";
						$chi ="child".$parentid;

					}else{
						$css="tree";
						$chi ="";
					}
					$buzztype = $buff->getbuzztype($row->replayid);
					if($buzztype =="buzz" || $buzztype =="" ){
					$mes = $buff->parsetext($row->message);
					$link = $buff->findlink($chield[$m]->id);
					if(!empty($link)){
					 $mes  .=$buff->timelinelinkhtml($row->replayid);
					}
                }elseif($buzztype =="event"){
					$mes	    =$buff->eventhtml($row->replayid);
                 }elseif($buzztype =="poll"){
					 $mes	    =$buff->pollchildhtml($row->replayid);
	
				}elseif($buzztype =="intraday"){
			       $mes   =$buff->assethtml($row->replayid);
		
				}
              if($row->pic !=''){
					$userimg ='<img src="'.getAvatarUrl($row->pic, 'thumbs1').'" alt="'.$row->username.'" />';
					
				}else{
					$userimg ='<img src="'.$C->STORAGE_URL.'avatars/thumbs1/_noavatar_user.gif" alt="'.$row->username.'" />';
					
				}				
				
			


/******* START : Timeline Parent Reply > Buzz Poll > All Childs *******/


		$pollcnt.='<div class="activity no-comments zeropadding commentcontainer'.$replayid.'" id="'.$chi.'" style="border:0px;">

<ul class="'.$css.'">
			<li>
<!-- start Parent -->
 <div class="row activity-parent">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box">

<div class="col-md-1 col-lg-1 col-sm-1 col-xs-2 image-div" style="padding:0; overflow:hidden"><a href="'.userlink($row->username).'" class="pull-left  bizcard" data-userid="'.$row->userid.'">'.$userimg.'</a>
			</div>

<div class="col-md-11 col-lg-11 col-sm-11 col-xs-10" style="padding:0px 3px 0px 8px;">

<div class="activity-container">
<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12"><a href="'.userlink($row->username).'" class="author bizcard" data-userid="'.$row->userid.'">'. $row->username .'</a>
			 <div class="meta-info">'.$grp.'</div>			
		
<div class="activity-options">'.$delete.''.$fav.'</div>
</div>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'/view/post:'.$replayid.'" class="permlink">'.$date.' <span class="glyphicon glyphicon-link"></span></a>
</div>

<div class="activity-content">'.$mes.'</div>
</div>		

<div id="replydis-'.$replayid.'"></div>

	
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12">
				
				<input type="hidden" id="time-'.$replayid.'" value="1 sec ago" />

				<span class="reply icon-ftr icon-ftr-reply">
				<a  style="cursor:pointer" onclick="childpopup('.$parentid.','.$replayid.')" ><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
				</span>


				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'">'.($is_agree? '<img  width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Disagree"/>' : '<img width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree"/>').'</a>'.$showagreebtn_btn.'</div>
               	<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>

                  <div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								   
 <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($buff->permalink).'&title='.urlencode(htmlspecialchars($buff->post_message)).'&source='.urlencode($buff->permalink).'&summary='.urlencode($buff->permalink).'"  target="_blank" >Linkedin</a></li>

 <li><a href="http://plus.google.com/share?url='.urlencode($buff->permalink).'"  target="_blank" >Google Plus</a></li>

 <li><a href="http://twitter.com/intent/tweet?text='.urlencode($buff->permalink).': '.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Twitter</a></li>

 <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($buff->permalink).'&t='.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
							
                           <div class="like-list icon-ftr">'.$mark_content.'</div>
							</div>
				 </div>

<div></div>
</div>

</div>
</div>
<!-- end Parent -->
</li>
</ul>


</div>';
	}
	$pollcnt.='</div></div>



	<script>
	$(document).ready(function(){
		var mainheight = $("#main'.$parentid.'").height();
		 var childheight = $("#child'.$parentid.'").height();
		  var final = mainheight-childheight;
		  $(".janeesh'.$parentid.'").css("height",final);




		
	});
	</script>
	 
	
	';


	echo $pollcnt;exit;
}elseif($parentid != $alter){
	
	$seriesquery = $this->db2->query('SELECT series  FROM post_replay as pr
	 WHERE pr.parent_id="'.$parentid.'" AND pr.alternate_parent_id="'.$alter.'" order by id desc limit 1,1 	 ', FALSE);
	 $series=$this->db2->fetch_object($seriesquery);
	 if($series->series !=''){
		 $series = $series;
	}else{
		
		$seriesquery = $this->db2->query('SELECT series  FROM post_replay as pr
	 WHERE  pr.replay_id="'.$chieldid.'" order by id desc limit 1	 ', FALSE);
	
	 $series=$this->db2->fetch_object($seriesquery);
		
	}

	 $seriesarrayold  = unserialize($series->series);
	 $seriesarraynew     =(array($parentid,$alter,$replayid));
	 $seriesnow                   =array_merge($seriesarrayold,$seriesarraynew);


	 $seriesarray     =serialize($seriesnow);

	 $db2->query("UPDATE post_replay SET series='".$seriesarray."' where replay_id='".$replayid."' ");


	  

	 
			//$db2->query('UPDATE posts SET date_lastcomment="'.$db_date.'" where id="'.$alter.'"');

	$postlevel =1;
		//$db2->query('UPDATE posts SET post_level="'.$postlevel.'" where id="'.$alter.'"');

		$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);


	$r	= $this->db2->query('SELECT  p.*,users.id as userid,users.avatar as pic, users.username as username FROM posts as p
	inner join users on p.user_id=users.id WHERE p.id="'.$replayid.'" order by p.date desc', FALSE);
	$replayparentres=$this->db2->fetch_object($r);
	
			
				$buzztype = $buff->getbuzztype($replayid);
				if($buzztype =="buzz" || $buzztype =="" ){
					$mes = $buff->parsetext($replayparentres->message);
					 $link = $buff->findlink($replayid);
					if(!empty($link)){
					$mes  .=$buff->timelinelinkhtml($replayid);
					}
                }elseif($buzztype =="event"){
					$mes	    = $buff->eventhtml($replayid);
                 }elseif($buzztype =="poll"){
					 $mes	    = $buff->pollchildhtml($replayid);
	
				}elseif($buzztype =="intraday"){
			       $mes   = $buff->assethtml($replayid);
		
				}
       $txt =post::replay_parse_date($replayparentres->date);
		$date = str_replace(array_keys($tmp), array_values($tmp), $txt);
 if(($user->id == $replayparentres->userid )){
					
   
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity"> <img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				} 
       $is_fav  = $buff->isfav($user->id,$replayid);
	 
   			if(!empty($is_fav)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}		
         $groups = $buff->getgroupname($replayparentres->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
		 if($replayparentres->pic !=''){
					$userimg ='<img src="'.getAvatarUrl($replayparentres->pic, 'thumbs1').'" alt="'.$replayparentres->username.'" />';
					
				}else{
					$userimg ='<img src="'.$C->STORAGE_URL.'avatars/thumbs1/_noavatar_user.gif" alt="'.$replayparentres->username.'" />';
					
				}


/******* Start: Timeline Independent Parent - Buzz Reply > Popup Poll Reply ******/


	 $user = '@'.$replayparentres->username;
	 	$cnt .='<div class="activity no-comments replayhide-'.$replayid.' ">
   <!-- start Parent -->
   <div class="row" style="border:0px solid red; margin:0px; padding:0px;">
   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">
         <div class="col-md-1 col-lg-1 col-sm-1 col-xs-2 image-div" style="padding:0; overflow:hidden">
         <a href="'.userlink($replayparentres->username).'" class="pull-left  bizcard" data-userid="'.$replayparentres->userid.'">'.$userimg.'</a>
		 </div><!--/ end : col-md-1 -->

         <div class="col-md-11 col-lg-11 col-sm-11 col-xs-10" style="padding:0px 3px 0px 8px;">
            <div class="activity-container">
               <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <a href="'.userlink($replayparentres->username).'" class="author bizcard" data-userid="'.$replayparentres->userid.'">'. $replayparentres->username .'</a>
                  <div class="meta-info"><a class="author bizcard replies-to" onclick="replaycontent('.$alter.','.$replayid.')" data-userid="'.$replayparentres->userid.'">Replies to @'. $replayparentres->username .'</a>
                  </div>
              <div class="activity-options">'.$delete.''.$fav.'</div>
               </div>

               
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
               <a href="'.$C->SITE_URL.'/view/post:'.$replayid.'" class="permlink">'.$date.' <span class="glyphicon glyphicon-link"></span></a>
               </div>

               <div class="activity-content">'.$mes.'</div>
               
               <div class="activity-poll col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
               <div class="footer1 activity-footer meta-info">  </div>
            </div>
			   <div id="replaypopup-'.$replayid.'" class="modal fade" ></div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
               <div class="activity-footer meta-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
                 
				<input type="hidden" id="time-'.$replayid.'" value="1 sec ago" />

				<span class="reply icon-ftr icon-ftr-reply">
				<a  style="cursor:pointer" onclick="parentreplay('.$alter.','.$replayid.')" ><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
				</span>


				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'">'.($is_agree? '<img  width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Disagree"/>' : '<img width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree"/>').'</a>'.$showagreebtn_btn.'</div>
               	<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>

                  <div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								   
 <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($buff->permalink).'&title='.urlencode(htmlspecialchars($buff->post_message)).'&source='.urlencode($buff->permalink).'&summary='.urlencode($buff->permalink).'"  target="_blank" >Linkedin</a></li>

 <li><a href="http://plus.google.com/share?url='.urlencode($buff->permalink).'"  target="_blank" >Google Plus</a></li>

 <li><a href="http://twitter.com/intent/tweet?text='.urlencode($buff->permalink).': '.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Twitter</a></li>

 <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($buff->permalink).'&t='.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
               

                 <div class="like-list icon-ftr">'.$mark_content.'</div>							
   						
   			</div> <!--/ end :  activity-footer meta-info -->

</div><!--/ end : activity container -->
  
</div><!--/ end : col-md-11 -->


</div><!--/ end : col-md-12 -->


</div><!-- end : activity no-comments--> 
   
   ';
	 //$cnt           ='<div id="replydis-'.$parentid.'"><a style="color:blue;" class="pull-right" href="#" onclick="replaycontent('.$alter.','.$parentid.')">View Replies</a></div>';
	 echo $cnt;exit;     
}


/******* End: Timeline Independent Parent - Buzz Reply > Popup Poll Reply ******/	

}



elseif($_GET['action'] =='intradaycomment'){
			$chieldid=$_POST['chieldid'];

	
	   $group_id = '0';
	   $parentid=$_POST['postid'];
	   $alter=$_POST['alterparentid'];
	   $postmessage=$_POST['message'];

	   $db_api_id		= '0';
		$db_user_id		= intval($this->user->id);
		$db_group_id	= '0';
		$db_to_user		= '0';
		$db_mentioned	= '0';
		$db_attached	= '0'; //change here
		$db_posttags	= '0';
		$db_date		= time();
		$db_ip_addr		= ip2long($_SERVER['REMOTE_ADDR']);
		 $sharedata             =$_POST[ 'sharemarketdataa' ];
		$sgharedata_filter     = array_filter($sharedata);
		$arr                   = array();
		$sharedata_final       = array_merge($arr,$sgharedata_filter);
		$db2->query('INSERT INTO posts SET api_id="'.$db_api_id.'", user_id="'.$db_user_id.'", group_id="'.$group_id.'",message="'.$postmessage.'", mentioned="'.$db_mentioned.'", posttags="'.$db_posttags.'", attached="'.$db_attached.'", date="'.$db_date.'", date_lastcomment="'.$db_date.'", ip_addr="'.$db_ip_addr.'" ');
		$replayid = $db2->insert_id();
		
		if(!empty($replayid)){
			$even ="intraday";
	       $db2->query('INSERT INTO post_replay SET parent_id="'.$parentid.'",alternate_parent_id="'.$alter.'", replay_id="'.$replayid.'",action_type="'.$even.'" ');

			$db2->query('INSERT INTO post_userbox SET user_id="'.$this->user->id.'", post_id="'.$replayid.'"');
			
			$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);

			$ownuserres          =$buff->get_own_user($alter);
	$ownuserid           =$ownuserres->user_id;
	$not_type='ntf_me_on_post_replay';
	$checkuserres =$buff->checkemptyuser($ownuserid);
	if($checkuserres->num_rows == "0"){
		$ownnotification =1;
	}else{
		$ownnotification     =$buff->checknotrules($ownuserid,$not_type);
		if(!empty($ownnotification)){
						$ownnotification = $ownnotification;
					}else{
						$ownnotification =1;
					}


    }


	if($ownnotification ==1 || $ownnotification ==2 || $ownnotification ==3 ){
				
	if($ownuserid != $this->user->id){
	$notifytype="replay";
	$standardtype ="ntf_me_on_post_replay";

	$posttype      =$buff->typeofpostofevent($alter);
	if($posttype->num_rows > 0){
		$type ="event";
	}else{
	$polltype      =$buff->typeofpostofpoll($alter);
		if($posttype->num_rows > 0){
			$type ="poll";
		}else{
		$activitiestype      =$buff->typelinks($alter);
		if(!empty($activitiestype)){
			
		 if($activitiestype->type=="videoembed"){
			 $type="video link";
			 
		 }elseif($activitiestype->type=="image"){
			  $type="image";
			 
		 }elseif($activitiestype->type=="file"){
			  $str = (unserialize($activitiestype->data));
			 $ext = pathinfo($str->file_original, PATHINFO_EXTENSION);
			 if($ext =='wmv' || $ext =='mp4' || $ext =='avi' || $ext =='mov' || $ext =='qt'){
				 $type = "video";
				 
			 }else{
				  $type = "file";
				 
			 }
		}
		}else{
		$type ="buzz";	
		}

		}				
	}

	$newisert =$buff->insert_active_notifications($ownuserid,$alter,$notifytype,$type,$standardtype);
	}
	}
			if(!empty($sharedata_final)){
					foreach($sharedata_final as $sharekeys=>$sharevals){
						$sharedetails      = $sharevals[0];
						$shareindividual   =explode(',',$sharedetails);
						$stockloss      =str_replace(' ','',$shareindividual[0]);
						$currentprice      =str_replace(' ','',$shareindividual[1]);
						$targetprice      =str_replace(' ','',$shareindividual[2]);
						$ticker      = $sharevals[1];
						$data_status = 'open';
					               
					    $update_date =date('Y-m-d h:i:s');
						$db2->query('INSERT INTO post_dayfeel SET post_id="'.$replayid.'", ticker="'.$ticker.'", updated_date="'.$update_date.'", stoploss_price="'.$stockloss.'", current_price="'.$currentprice.'", predicted_price="'.$targetprice.'", status="'.$data_status.'" ');

					}
			}
			
			
					$q = array();

					//insert to followers data
					if($this->user->info->is_posts_protected == 0){
						$u	= $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers;
					}else{
						$u	= array_intersect_key($this->network->get_user_follows($this->user->id, FALSE, 'hefollows')->follow_users, $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers);
					}
							
					$u	= $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers;
					foreach($u as $k=>$v) {
						if($k !=$this->user->id){

						$q[]	= '("'.$k.'", "'.$replayid.'")';
						}
					}
					
					if( $group_id ) {
						$u	= $this->network->get_group_members($group_id);
						if($u) {
							foreach($u as $k=>$v) {
								$z[]	= '("'.$k.'", "'.$replayid.'")';
							}
						}
						$q	= array_unique($q);
						$q = array_intersect($q,$z);					
					}
					
					if( count($q) > 0 ) { 
						$q	= implode(', ', $q);
						$db2->query('INSERT INTO post_userbox (user_id, post_id) VALUES '.$q);
					}
		}
			if($parentid == $alter){
		$seriesarray     =serialize(array($parentid,$replayid));

		$db2->query('UPDATE posts SET date_lastcomment="'.$db_date.'" where id="'.$alter.'"');
		$db2->query("UPDATE post_replay SET series='".$seriesarray."' where replay_id='".$replayid."' ");


		$postlevel = 1;
	  $db2->query('UPDATE posts SET post_level="'.$postlevel.'" where id="'.$replayid.'"');

	/*$r	= $this->db2->query('SELECT p.*,users.id as userid,users.avatar as pic, users.username as username FROM posts as p
     inner join post_replay as pr ON p.id = pr.replay_id 	
	inner join users on p.user_id=users.id WHERE pr.replay_id="'.$replayid.'" order by p.date desc', FALSE);*/
	$r	= $this->db2->query('SELECT p.*,pr.replay_id as replayid,users.id as userid,users.avatar as pic, users.username as username FROM posts as p
     inner join post_replay as pr ON p.id = pr.replay_id 	
	inner join users on p.user_id=users.id WHERE pr.parent_id="'.$parentid.'" order by p.date ASC', FALSE);
	
	while($result=$this->db2->fetch_object($r))
	{
		$datas[]=$result;
	}
	$intracnt='';
   $dcnt = count($datas)-1;

	$obj=$datas[0];
	$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);
	$parentquery = $this->db2->query('SELECT p.*,users.id as userid,users.avatar as pic, users.username as username FROM posts as p
	inner join users on p.user_id=users.id WHERE p.id="'.$parentid.'" ', FALSE);
	$parentres =$this->db2->fetch_object($parentquery);
     $eventdetails = $buff->geteventdetails($parentid);
	$poll  = $buff->replay_is_poll($parentid);
	$assetdata   =$buff->assetdata($parentid);
	$prediction_data =$buff->predictiondata($parentid);

	if(!empty($assetdata)){
		$parentmessage =$buff->assethtml($parentid);
	}elseif(!empty($eventdetails)){
		$parentmessage =$buff->eventhtml($parentid);
    }elseif(!empty($poll)){
		$parentmessage =$buff->pollchildhtml($parentid);
		
	}elseif(!empty($prediction_data)){
						if($prediction_data[0]->status =="OPEN"){

            //calculations for up rate
				$predict_value = $prediction_data[0]->predict_value;
				$prediction_base_price = $prediction_data[0]->prediction_base_price;
				$percentage             =(($predict_value-$prediction_base_price)/($prediction_base_price))*100;
				$percentage = number_format((float)$percentage, 2, '.', '');
				
				if (strpos($percentage, '-') !== false) {
					$con ='down';
	               $imag ='down-arrow-prediction.png';
				}else{
					$con ='up';
					$imag ='up-arrow-prediction.png';
				}
			$parentmessage .='<div class="prediction-buzz-data">'.$prediction_data[0]->asset_name.'($'.$prediction_data[0]->ticker.')<img src="'.$C->SITE_URL.'/static/images/icons/'.$imag.'"> to be '.$con.' by '.$percentage.'% from '.$prediction_data[0]->prediction_base_price.' in '.substr($prediction_data[0]->end_date,0,10).' because of '.$prediction_data[0]->predict_reason.'.</div>';
					}else{
											//calculations for up rate
				$predict_result = $prediction_data[0]->predict_result;
				if($predict_result =='CORRECT'){
					 $imag ='hit.png';
					 $type ="Hit";
					 $percentage='';
					
				}else{
					 $imag ='miss.png';
					 
					 
					  $predict_value = $prediction_data[0]->predict_value;
					  $prediction_base_price = $prediction_data[0]->prediction_base_price;
					  $percentage             =(($predict_value-$prediction_base_price)/($prediction_base_price))*100;
					  $percentage = substr(number_format((float)$percentage, 2, '.', ''),1);
					  $type =" Mis by ".$percentage."%";
					
				}
				if($buff->post_user->id == $user->id){
					$handset ='If you want to change Hindsight reason please <a  class="mymodal" data-toggle="modal" data-target="#myModal-'.$prediction_data[0]->post_id.'"  >click here </a> 

  
  <!-- Modal -->
  <div class="modal fade-'.$prediction_data[0]->post_id.'" id="myModal-'.$prediction_data[0]->post_id.'" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Handset reason </h4>
        </div>
        <div class="modal-body">
		<div class="row">
		 <div>Reason :<input type="text" value="'.$prediction_data[0]->hindsight_reason.'" id="hindsight-'.$prediction_data[0]->post_id.'" onkeyup="validate(this,'.$prediction_data[0]->post_id.')">
		 </div>
		  <div id="handsetreason-error-'.$prediction_data[0]->post_id.'"class="notifyjs-container" style="top: 37px; left: 168px; overflow: hidden; display: hidden;"><div class="notifyjs-bootstrap-base notifyjs-bootstrap-error">
            <span data-notify-text="" class="notifyjs-text">This field is required</span>
         </div></div>
		 		   <button type="button" class="btn btn-default btn-primary"  data-toggle="modal"  onclick="changehandset('.$prediction_data[0]->post_id.')">Change</button>

		</div>


          
        </div>
		<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      
      </div>
      
    </div>
  </div>
  
					
					';
				}else{
					$handset ='';
					
				}
				$parentmessage .='<div style="background-color:#e3f8fe;font-size:12px;height:auto;padding: 10px;"> Your prediction on '.$prediction_data[0]->asset_name.'($'.$prediction_data[0]->ticker.') done  on '.substr($prediction_data[0]->end_date,0,10).' was a '.$type.' <img src="'.$C->SITE_URL.'static/images/icons/'.$imag.'">.'.$handset.' 
				</div>';
						
					}
	}else{
		$parentmessage =($parentres->message);
        $parentmessage .=($buff->attchmentreplaydisplay($parentid));
		 $link =$buff->findlink($parentid);
		if(!empty($link)){
			$parentmessage .=$buff->linkhtml($parentid);;
		}

		
	}

		//user post shared or not checking
                $is_reshared    =$buff->is_post_reshared($parentid);
				$reshares       =$buff->loaded_posts_reshares($parentid);
				$resharecnt     =count($reshares);


			        $like_content ='';
					$is_liked  = $buff->new_liked($parentid);
					$likes_number = $buff->new_liked_count($parentid);
					$like_number        =$likes_number->likecount;

					$css="icons";
				$is_spam  = $buff->is_spam($parentid,$buff->post_type);
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'"><em><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }
				$is_agree = $buff->is_post_agree($user->id,$parentid);
				$is_agree_cnt = $buff->is_post_agree_cnt($parentid);
				 if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   if($like_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'">'.$like_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>').'</a>';
                    if($reshare_content > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'">'.$resharecnt.'</a>';
                    }else{
						$resharecnt ='';
						
					}
					$delete = (($user->is_logged && $buff->if_can_delete())? '<a href="" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$parentid.'"}').'" data-role="services" data-namespace="activities" title="Delete" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>' : '');
                $is_fav  = $buff->isfav($user->id,$parentid);
	 
   			if(!empty($is_fav)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$parentid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$parentid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
			$groups = $buff->getgroupname($parentres->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
				$txt =post::replay_parse_date($parentres->date);
		$date = str_replace(array_keys($tmp), array_values($tmp), $txt);
		 if($parentres->pic !=''){
					$userimg ='<img src="'.getAvatarUrl($parentres->pic, 'thumbs1').'" alt="'.$parentres->username.'" />';
					
				}else{
					$userimg ='<img src="'.$C->STORAGE_URL.'avatars/thumbs1/_noavatar_user.gif" alt="'.$parentres->username.'" />';
					
				}



	/******* Start: Timeline Intraday - Buzz Reply > Popup Reply ******/	


	$intracnt .='<div class="activity no-comments replayhide-'.$parentid.' " id="main'.$parentid.'">
   <!-- start Parent -->
   <div class="row" style="border:0px solid red; margin:0px; padding:0px;">
   <div class="janeesh'.$parentid.'"></div>

   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">
         <div class="col-md-1 col-lg-1 col-sm-1 col-xs-2 image-div" style="padding:0; overflow:hidden">
         <a href="'.userlink($parentres->username).'" class="pull-left  bizcard" data-userid="'.$parentres->userid.'">'.$userimg.'</a>
			</div><!--/ end : col-md-1 -->

         <div class="col-md-11 col-lg-11 col-sm-11 col-xs-10" style="padding:0px 3px 0px 8px;">
            <div class="activity-container">
               <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <a href="'.userlink($parentres->username).'" class="author bizcard" data-userid="'.$parentres->userid.'">'. $parentres->username .'</a>
                  <div class="meta-info">'.$grp.'
                  </div>
              <div class="activity-options">'.$delete.''.$fav.'</div>
               </div>

               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
               <a href="'.$C->SITE_URL.'/view/post:'.$parentid.'" class="permlink">'.$date.' <span class="glyphicon glyphicon-link"></span></a>
               </div>


               <div class="activity-content">'.$parentmessage.'</div>
               <div></div>
               <div class="activity-poll col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
               <div class="footer1 activity-footer meta-info">  </div>
            </div>
			   <div id="replaypopup-'.$parentid.'" class="modal fade" ></div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
               <div class="activity-footer meta-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
                 
				<input type="hidden" id="time-'.$parentid.'" value="1 sec ago" />

				<span class="reply icon-ftr icon-ftr-reply"><a  style="cursor:pointer" onclick="parentreplay('.$parentid.','.$parentid.')" ><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
				</span>

				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$parentid.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$parentid.'"}').'">'.($is_agree? '<img  width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Disagree"/>' : '<img width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree"/>').'</a>'.$showagreebtn_btn.'</div>
               	<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>

                  <div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								   
 <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($buff->permalink).'&title='.urlencode(htmlspecialchars($buff->post_message)).'&source='.urlencode($buff->permalink).'&summary='.urlencode($buff->permalink).'"  target="_blank" >Linkedin</a></li>

 <li><a href="http://plus.google.com/share?url='.urlencode($buff->permalink).'"  target="_blank" >Google Plus</a></li>

 <li><a href="http://twitter.com/intent/tweet?text='.urlencode($buff->permalink).': '.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Twitter</a></li>

 <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($buff->permalink).'&t='.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
                 

                 <div class="like-list icon-ftr">'.$mark_content.'</div>							
   						
   			</div> <!--/ end :  activity-footer meta-info -->

</div><!--/ end : activity container -->
  
</div><!--/ end : col-md-11 -->


</div><!--/ end : col-md-12 -->


</div><!-- end : activity no-comments--> 
   
   ';


/******* End: Timeline Intraday - Buzz Reply > Popup Reply ******/




	foreach($datas as $keys=>$row)
	{
		
		//user post shared or not checking
                $is_reshared    =$buff->is_post_reshared($row->replayid);
				$reshares       =$buff->loaded_posts_reshares($row->replayid);
				$resharecnt     =count($reshares);


			        $like_content ='';
					$is_liked  = $buff->new_liked($row->replayid);
					$likes_number = $buff->new_liked_count($row->replayid);
					$like_number        =$likes_number->likecount;
					$css="icons";
				$is_spam  = $buff->is_spam($row->replayid,$buff->post_type);
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				$is_agree = $buff->is_post_agree($user->id,$row->replayid);
				$is_agree_cnt = $buff->is_post_agree_cnt($row->replayid);
				 if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   if($like_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'">'.$like_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>').'</a>';
                    if($reshare_content > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'">'.$resharecnt.'</a>';
                    }else{
						$resharecnt ='';
						
					}
					$delete = (($user->is_logged && $buff->if_can_delete())? '<a href="" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$row->replayid.'"}').'" data-role="services" data-namespace="activities" title="Delete" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>' : '');
       $is_fav  = $buff->isfav($user->id,$row->replayid);
	 
   			if(!empty($is_fav)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$row->replayid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$row->replayid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
			$groups = $buff->getgroupname($row->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
		
			$txt =post::replay_parse_date($row->date);
		  $date = str_replace(array_keys($tmp), array_values($tmp), $txt);
					
					
					$buzztype = $buff->getbuzztype($row->replayid);
					if($buzztype =="buzz" || $buzztype =="" ){
					$mes = $buff->parsetext($row->message);
					$mes .= $buff->attchmentreplaydisplay($row->replayid);
						$link = $buff->findlink($row->replayid);
					if(!empty($link)){
					$mes  .=$buff->timelinelinkhtml($row->replayid);
					}
					
                }elseif($buzztype =="event"){
					$mes	    =$buff->eventhtml($row->replayid);
                 }elseif($buzztype =="poll"){
					 $mes	    =$buff->pollchildhtml($row->replayid);
	
				}elseif($buzztype =="intraday"){
			       $mes   =$buff->assethtml($row->replayid);
		
				}	
				
				
                    if($dcnt == $keys){
						$css="tree1";
						$chi ="child".$parentid;

					}else{
						$css="tree";
						$chi ="";
					}
					 if($row->pic !=''){
					$userimg ='<img src="'.getAvatarUrl($row->pic, 'thumbs1').'" alt="'.$row->username.'" />';
					
				}else{
					$userimg ='<img src="'.$C->STORAGE_URL.'avatars/thumbs1/_noavatar_user.gif" alt="'.$row->username.'" />';
					
				}





/******* Start: Timeline Intraday  - Buzz Reply > Child Popup Reply ******/



$intracnt.='<div class="activity no-comments zeropadding commentcontainer'.$replayid.'" id="'.$chi.'" style="border:0;">

<ul class="'.$css.'">
			<li>
<!-- start Parent -->
<div class="row activity-parent">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box">

<div class="col-md-1 col-lg-1 col-sm-1 col-xs-2 image-div" style="padding:0; overflow:hidden">
<a href="'.userlink($row->username).'" class="pull-left  bizcard" data-userid="'.$row->userid.'">'.$userimg.'</a>
			</div>

<div class="col-md-11 col-lg-11 col-sm-11 col-xs-10" style="padding:0px 3px 0px 8px;">

<div class="activity-container">
<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
<a href="'.userlink($row->username).'" class="author bizcard" data-userid="'.$row->userid.'">'. $row->username .'</a>
<div class="meta-info">'.$grp.'</div>			
			
<div class="activity-options">'.$delete.''.$fav.'</div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'/view/post:'.$replayid.'" class="permlink">'.$date.' <span class="glyphicon glyphicon-link"></span></a>
</div>


<div class="activity-content">'.$mes.'</div>
</div>		

<div id="replydis-'.$replayid.'"></div>


		
		
		
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
				
				<input type="hidden" id="time-'.$replayid.'" value="1 sec ago" />

				<span class="reply icon-ftr icon-ftr-reply">
				<a  style="cursor:pointer" onclick="childpopup('.$parentid.','.$replayid.')" ><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
				</span>

				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'">'.($is_agree? '<img  width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Disagree"/>' : '<img width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree"/>').'</a>'.$showagreebtn_btn.'</div>
               	<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>

                  <div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								   
 <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($buff->permalink).'&title='.urlencode(htmlspecialchars($buff->post_message)).'&source='.urlencode($buff->permalink).'&summary='.urlencode($buff->permalink).'"  target="_blank" >Linkedin</a></li>

 <li><a href="http://plus.google.com/share?url='.urlencode($buff->permalink).'"  target="_blank" >Google Plus</a></li>

 <li><a href="http://twitter.com/intent/tweet?text='.urlencode($buff->permalink).': '.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Twitter</a></li>

 <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($buff->permalink).'&t='.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
							
                           <div class="like-list icon-ftr">'.$mark_content.'</div>						
							</div>
				 </div>

</div>

</div>
</div>
<!-- end Parent -->
</li>
</ul>


</div>';
	}
	$intracnt.='</div></div>



	<script>
	$(document).ready(function(){
		var mainheight = $("#main'.$parentid.'").height();
 var childheight = $("#child'.$parentid.'").height();
 var final = mainheight-childheight;
$(".janeesh'.$parentid.'").css("height",final);
		
	});
	</script>
	';
	

/******* End: Timeline Intraday  - Buzz Reply > Child Popup Reply ******/



	echo $intracnt;exit;
}elseif($parentid != $alter){
		$seriesquery = $this->db2->query('SELECT series  FROM post_replay as pr
	 WHERE pr.parent_id="'.$parentid.'" AND pr.alternate_parent_id="'.$alter.'" order by id desc limit 1,1 	 ', FALSE);
	 $series=$this->db2->fetch_object($seriesquery);
	 if($series->series !=''){
		 $series = $series;
	}else{
		$seriesquery = $this->db2->query('SELECT series  FROM post_replay as pr
	 WHERE  pr.replay_id="'.$chieldid.'" order by id desc limit 1	 ', FALSE);
	
	 $series=$this->db2->fetch_object($seriesquery);
		
	}
	 $seriesarrayold  = unserialize($series->series);
	 $seriesarraynew     =(array($parentid,$alter,$replayid));
	 $seriesnow                   =array_merge($seriesarrayold,$seriesarraynew);


	 $seriesarray     =serialize($seriesnow);
	 $db2->query("UPDATE post_replay SET series='".$seriesarray."' where replay_id='".$replayid."' ");


	  

	 
			//$db2->query('UPDATE posts SET date_lastcomment="'.$db_date.'" where id="'.$alter.'"');

	$postlevel =1;
		//$db2->query('UPDATE posts SET post_level="'.$postlevel.'" where id="'.$alter.'"');

		$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);


	$r	= $this->db2->query('SELECT  p.*,users.id as userid,users.avatar as pic, users.username as username FROM posts as p
	inner join users on p.user_id=users.id WHERE p.id="'.$replayid.'" order by p.date desc', FALSE);
	$replayparentres=$this->db2->fetch_object($r);
	
			
				$buzztype = $buff->getbuzztype($replayid);
				if($buzztype =="buzz" || $buzztype =="" ){
					$mes = $buff->parsetext($replayparentres->message);
                   $mes .= $buff->attchmentreplaydisplay($replayid);
					$link = $buff->findlink($replayid);
					if(!empty($link)){
					$mes  .=$buff->timelinelinkhtml($replayid);
					}
                }elseif($buzztype =="event"){
					$mes	    = $buff->eventhtml($replayid);
                 }elseif($buzztype =="poll"){
					 $mes	    = $buff->pollchildhtml($replayid);
	
				}elseif($buzztype =="intraday"){
			       $mes   = $buff->assethtml($replayid);
		
				}	
				$txt =post::replay_parse_date($replayparentres->date);
		$date = str_replace(array_keys($tmp), array_values($tmp), $txt);
		 if(($user->id == $replayparentres->userid )){
					
   
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity"> <img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				} 
       $is_fav  = $buff->isfav($user->id,$replayid);
	 
   			if(!empty($is_fav)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
			$groups = $buff->getgroupname($replayparentres->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}





/******* Start: Timeline Intraday Parent  - Buzz Reply > Popup Reply ******/



	 $user      ='@'.$replayparentres->username;
	 	$cnt .='<div class="activity no-comments replayhide-'.$replayid.' ">
   <!-- start Parent -->
   <div class="row" style="border:0px solid red; margin:0px; padding:0px;">
   
   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">
           <div class="col-md-1 col-lg-1 col-sm-1 col-xs-2 image-div" style="padding:0; overflow:hidden">
           <a href="'.userlink($replayparentres->username).'" class="pull-left  bizcard" data-userid="'.$replayparentres->userid.'"><img src="'.getAvatarUrl($replayparentres->pic, 'thumbs1').'" alt="'.$replayparentres->username.'" /></a>
			</div><!--/ end : col-md-1 -->
         
          <div class="col-md-11 col-lg-11 col-sm-11 col-xs-10" style="padding:0px 3px 0px 8px;">
            <div class="activity-container">
               <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <a href="'.userlink($replayparentres->username).'" class="author bizcard" data-userid="'.$replayparentres->userid.'">'. $replayparentres->username .'</a>
                  <div class="meta-info"><a class="author bizcard replies-to" onclick="replaycontent('.$alter.','.$replayid.')" data-userid="'.$replayparentres->userid.'">Replies to @'. $replayparentres->username .'</a>
                  '.$grp.'
				  </div>
              <div class="activity-options">'.$delete.''.$fav.'</div>
               </div>

               
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
               <a href="'.$C->SITE_URL.'/view/post:'.$replayid.'" class="permlink">'.$date.' <span class="glyphicon glyphicon-link"></span></a>
               </div>


               <div class="activity-content">'.$mes.'</div>
              
               <div class="activity-poll col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
               <div class="footer1 activity-footer meta-info">  </div>
            </div>
			   <div id="replaypopup-'.$replayid.'" class="modal fade" ></div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
                 <div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
                 

				<input type="hidden" id="time-'.$replayid.'" value="1 sec ago" />

				<span class="reply icon-ftr icon-ftr-reply">
				<a  style="cursor:pointer" onclick="parentreplay('.$alter.','.$replayid.')" ><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
				</span>


				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$replayid.'"}').'">'.($is_agree? '<img  width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Disagree"/>' : '<img width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree"/>').'</a>'.$showagreebtn_btn.'</div>
               	<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>

                  <div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								   
 <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($buff->permalink).'&title='.urlencode(htmlspecialchars($buff->post_message)).'&source='.urlencode($buff->permalink).'&summary='.urlencode($buff->permalink).'"  target="_blank" >Linkedin</a></li>

 <li><a href="http://plus.google.com/share?url='.urlencode($buff->permalink).'"  target="_blank" >Google Plus</a></li>

 <li><a href="http://twitter.com/intent/tweet?text='.urlencode($buff->permalink).': '.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Twitter</a></li>

 <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($buff->permalink).'&t='.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
             

                    <div class="like-list icon-ftr">'.$mark_content.'</div>							
   						
   			</div> <!--/ end :  activity-footer meta-info -->

</div><!--/ end : activity container -->
  
</div><!--/ end : col-md-11 -->


</div><!--/ end : col-md-12 -->


</div><!-- end : activity no-comments--> 
   
   ';
	 //$cnt           ='<div id="replydis-'.$parentid.'"><a style="color:blue;" class="pull-right" href="#" onclick="replaycontent('.$alter.','.$parentid.')">View Replies</a></div>';
	 echo $cnt;exit;  
	
}
}
else
{

//die('pppppppppp');


$db2->query('SELECT 1 FROM users WHERE id="'.$this->user->id.'"  LIMIT 1');//AND is_network_admin=1
if( 0 == $db2->num_rows() ) {
	$this->redirect('dashboard');
}

if($_GET['action']=="users")
{

	$tpl = new template( array('page_title' => $this->lang('admpgtitle_administrators', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'c'),false);
}
else if($_GET['action']<>"users" && $_GET['action']<>"answer")
{
	$tpl = new template( array('page_title' => $this->lang('admpgtitle_administrators', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );
}



$this->load_langfile('inside/global.php');
$this->load_langfile('inside/admin.php');
//print_r($C);
$plugin_name = 'poll';	
$main_content = "";

if($_GET['action']=="answer")
	{
		$db2 = & $this->network->db2;
		$userid=$user->id;
		$answerid=$_GET['answerid'];
		$pollid=$_GET['poll_id'];
		$this->db2->query("INSERT INTO post_poll_votes SET 
							POLL_ID = '".$pollid."', 
							ANSWER_ID = '".$answerid."', 
							VOTER_USER_ID = '".$userid."'", FALSE);
		//echo $query="delete from post_poll_votes";
		//$this->db2->query($query,true);
		$poll_id = $this->db2->insert_id();
		$this->redirect($C->SITE_URL);


	}
else if($_GET['action']=="download")
{

		$pollid=$_GET['poll_id'];
		$host="localhost";
		$uname="root";
		$pass="";
		$database = "sharetronix_local"; 

		$connection=mysql_connect($host,$uname,$pass); 

		echo mysql_error();

		//or die("Database Connection Failed");
		$selectdb=mysql_select_db($database) or 
		die("Database could not be selected"); 
		$result=mysql_select_db($database)
		or die("database cannot be selected <br>");
		$output = "";
		$table = ""; // Enter Your Table Name 
		$sql = mysql_query("select * from post_poll_votes where POLL_ID='".$pollid."'");
		$columns_total = mysql_num_fields($sql);

		// Get The Field Name

		for ($i = 0; $i < $columns_total; $i++) {
		$heading = mysql_field_name($sql, $i);
		$output .= '"'.$heading.'",';
		}
		$output .="\n";

		// Get Records from the table

		while ($row = mysql_fetch_array($sql)) {
		for ($i = 0; $i < $columns_total; $i++) {
		$output .='"'.$row["$i"].'",';
		}
		$output .="\n";
		}

		// Download the file

		$filename = "POLL-".$pollid.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);

		echo $output;
		exit;

}
else
{
if(isset($_GET['action']))
{	

	$action = trim($_GET['action']);
	$poll_id = isset($_GET['poll_id']) ? (int)($_GET['poll_id']) : 0;
	
	if ($action == "delete" && $poll_id > 0)
	{
		$this->db2->query("DELETE FROM polls_user_votes WHERE poll_id='".$poll_id."'");
		$this->db2->query("DELETE FROM polls_answers WHERE poll_id='".$poll_id."'");
		$this->db2->query("DELETE FROM polls WHERE poll_id='".$poll_id."'");
		
		$this->redirect($C->SITE_URL."plugin/".$plugin_name."/admin");
	}
	
	if ($action == "activate" && $poll_id > 0)
	{
		//$this->db2->query("UPDATE polls SET poll_is_active='0'");
		$this->db2->query("UPDATE polls SET poll_is_active='1' WHERE poll_id='".$poll_id."'");
		
		$this->redirect($C->SITE_URL."plugin/".$plugin_name."/admin");
	}
	
	if ($action == "deactivate" && $poll_id > 0)
	{
		$this->db2->query("UPDATE polls SET poll_is_active=0 WHERE poll_id='".$poll_id."'");
		
		$this->redirect($C->SITE_URL."plugin/".$plugin_name."/admin");	
	}
	
	if ($action == "add" || $action == "edit" || $action == "users") // || $action == "add_answer"
	{
		$question = "";
		$answers = array(0 => "", 1 => "");
		$allowUserAnswer = false;
	
		if (isset($_POST['save'])) 
		{
			//print_r($_POST); exit;

			$errorMsg = array();			
			$question = isset($_POST['question']) ? htmlspecialchars( trim($_POST['question']) ) : "";
			$answers = isset($_POST['answer']) ? $_POST['answer'] : array();
			$allowUserAnswer = isset($_POST['allowUserAnswer']) ? $_POST['allowUserAnswer'] : false;
			
			if ($question == "")
			{
				$errorMsg[] = "Please enter a valid 'Qiestion'.";
			}
			if (empty($answers) || count($answers) < 2) 
			{
				$errorMsg[] = "Please enter at least two answers.";
			}
			else
			{
				$notEmpty = 0;
				foreach($answers as $key => $val) 
				{
					if (!empty($val)) $notEmpty++;
					if ($notEmpty == 2) break;
				}
				if ($notEmpty < 2)
					$errorMsg[] = "Please enter at least two answers.";					
				
				// $i = 0;
				// foreach($answers as $key => $val) 
				// {
					// $i++;
					// if (empty(trim($val))) 
						// $errorMsg[] = "Please enter a valid 'Answer ".($i)."'.";
				// }
			}
			
			if (empty($errorMsg))
			{
				if ($poll_id > 0)  
				{
					// update poll
					$query = "UPDATE polls SET 
								poll_question = '".$this->db2->e($question)."', 
								poll_allow_user_answer = '".($allowUserAnswer ? 1 : 0)."'
							WHERE poll_id='".$poll_id."'";
					$this->db2->query($query, FALSE);
					
					$query2 = "SELECT poll_answer_id FROM polls_answers WHERE poll_id='".$poll_id."'";					
					$res2 = $this->db2->query($query2);
					$num2 = $res2->num_rows;						
					if ($num2 > 0)
					{
						// update old answers
						while($obj2 = $this->db2->fetch_object($res2))
						{
							$key = $obj2->poll_answer_id;
							if (array_key_exists($key, $answers))
							{
								$answer = trim($answers[$key]);
								if (!empty($answer))
								{
									$query3 = "UPDATE polls_answers 
									SET answer = '".$this->db2->e($answer)."'
									WHERE poll_answer_id = '".$key."' AND poll_id='".$poll_id."'";								
									$this->db2->query($query3, FALSE);
								}
								else
								{
									// delete empty answers
									$this->db2->query("DELETE FROM polls_user_votes WHERE poll_answer_id='".$key."' AND poll_id='".$poll_id."'");									
									$this->db2->query("DELETE FROM polls_answers WHERE poll_answer_id='".$key."' AND poll_id='".$poll_id."'");
								}								
								unset($answers[$key]);
							}
							else
							{							
								$this->db2->query("DELETE FROM polls_user_votes WHERE poll_answer_id='".$key."' AND poll_id='".$poll_id."'", FALSE);
								$this->db2->query("DELETE FROM polls_answers WHERE poll_answer_id='".$key."' AND poll_id='".$poll_id."'", FALSE);
							}
						}
					}
					// insert new answers
					foreach($answers as $key => $val)
					{
						$answer = trim($val);
						if (!empty($answer))
						{
							$this->db2->query("INSERT INTO polls_answers SET 
							poll_id = '".$poll_id."', 
							answer = '".$this->db2->e($answer)."', 
							votes = '0'", FALSE);	
						}
					}
				}
				else
				{					
					// insert
					
					$db2		= & $this->network->db2;
					$is_private	= 'TRUE';
					$db_api_id		= '0';
					$db_user_id		= intval($this->user->id);
					$db_group_id	= '0';
					$db_to_user		= '0';
					$db_mentioned	= '0';
					$db_attached	= '0'; //change here
					$db_posttags	= '0';
					$db_date		= time();
					$db_ip_addr		= ip2long($_SERVER['REMOTE_ADDR']);
					$question=$_POST['question'];
					$name=$_POST['street_group'];
				//	$image=$_POST['street_group'];

					//$data['group']=explode("@",$name);
					if($_POST['street_group'] !="")
					{

						$name1=$name;
						$query = "SELECT id from groups WHERE groupname = '".$name1."' OR title='".$name1."' ";
						$r = $this->db2->query($query);
						while($result=$this->db2->fetch_object($r))
						{
							$data['id']=$result;
						}	
						$groupid=$data['id']->id;
						$db2->query('INSERT INTO posts SET api_id="'.$db_api_id.'", user_id="'.$db_user_id.'", group_id="'.$groupid.'", mentioned="'.$db_mentioned.'", posttags="'.$db_posttags.'", attached="'.$db_attached.'", date="'.$db_date.'", date_lastcomment="'.$db_date.'", ip_addr="'.$db_ip_addr.'" ,group_name="'.$name.'" ');
						
					}
					else
					{


						$groupid=0;


						$db2->query('INSERT INTO posts SET api_id="'.$db_api_id.'", user_id="'.$db_user_id.'",posttype="5", group_id="'.$db_group_id.'", mentioned="'.$db_mentioned.'", posttags="'.$db_posttags.'", attached="'.$db_attached.'", date="'.$db_date.'", date_lastcomment="'.$db_date.'", ip_addr="'.$db_ip_addr.'" ,group_name="'.$name.'" ');
					
		$poll_id = $db2->insert_id();
		

		if(!empty($_FILES["file"]["tmp_name"])){
	//	echo $replayid; die("=========");
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
			

$imagecaption="Image";
move_uploaded_file($avatar_tmp_name , $upload_name);		    
     $ii = $n->attach_image($upload_name, $avatar_name);


		}		
}
					
					
					
					$pid = $db2->insert_id();
						/*		$query = "INSERT INTO polls SET 
											poll_date = '".time()."',  
											poll_question = '".$this->db2->e($question)."', 
											poll_is_active = '0',
											poll_allow_user_answer = '0'";*/
			/*echo $query; 
			die();*/					
				//	$this->db2->query($query, FALSE);
					
					//$db2->query('INSERT INTO event_posts SET event_id="'.$id.'", post_id="'.$pid.'", created = "'.date('Y-m-d H:i:s').'"');
					
					
				if(!empty($_FILES["file"]["tmp_name"])){
$db2->query('INSERT INTO `posts_attachments`(`post_id`, `type`, `data`,  `content`) VALUES ('.$poll_id.',\'Image\',\''.$db2->escape(serialize($ii)).'\',\''.$imagecaption.'\')');



$data = serialize($ii);


   $file_originalSTART = strpos($data, 's:25:"', 0)+6;
         $file_originalEND  = strpos($data, ';', $file_originalSTART)-1;
         $file_original = substr($data,$file_originalSTART,$file_originalEND-$file_originalSTART);
      
             $file_previewSTART = strpos($data, '"file_preview";s:26:"', 0)+21;
         $file_previewEND  = strpos($data, ';', $file_previewSTART)-1;
         $file_preview = substr($data,$file_previewSTART,$file_previewEND-$file_previewSTART);
      
             $file_thumbnailSTART = strpos($data, '"file_thumbnail";s:26:"', 0)+23;
         $file_thumbnailEND  = strpos($data, ';', $file_thumbnailSTART)-1;
         $file_thumbnail = substr($data,$file_thumbnailSTART,$file_thumbnailEND-$file_thumbnailSTART);
         
         $file_original."+".$file_preview."+".$file_thumbnail;
         $newimg = array("file_original"=>$file_original,"file_preview"=>$file_preview,"file_thumbnail"=>$file_thumbnail);






                              //  $newimg = $this->splitAttachements(serialize($ii));
                                
                                foreach ($newimg as $tmpimg)
                                {
                                    rename($C->STORAGE_TMP_DIR.$tmpimg,$C->STORAGE_DIR.'attachments/1/'.$tmpimg);
                                }







}

					$db2->query('INSERT INTO post_userbox SET user_id="'.$this->user->id.'", post_id="'.$pid.'"');
					//$db2->query('INSERT INTO posts_attachments SET post_id="'.$pid.'", type="link",data="'.$db2->escape(serialize($answer)).'"');
					
					$query = "INSERT INTO polls SET 
							poll_date = '".time()."',  
							poll_question = '".$this->db2->e($question)."', 
							poll_is_active = '0',
							poll_allow_user_answer = '".($allowUserAnswer ? 1 : 0)."',
							posts_id = '".$pid."'";
					$this->db2->query($query, FALSE);
					$poll_id = $this->db2->insert_id();
					foreach($answers as $key => $val) 
					{
						$query2 = "INSERT INTO polls_answers SET 
								poll_id = '".$poll_id."',
								answer = '".$this->db2->e($val)."', 
								votes = '0'";
						$this->db2->query($query2, FALSE);
					}
					$q =array();

					//insert to followers data
					if($this->user->info->is_posts_protected == 0){
						$u	= $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers;
					}else{
						$u	= array_intersect_key($this->network->get_user_follows($this->user->id, FALSE, 'hefollows')->follow_users, $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers);
					}
							
					$u	= $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers;
					foreach($u as $k=>$v) {
						if($k !=$this->user->id){

						$q[]	= '("'.$k.'", "'.$pid.'")';
						
						}
					}
					
					if( $group_id ) {
						$u	= $this->network->get_group_members($group_id);
						if($u) {
							foreach($u as $k=>$v) {
								$z[]	= '("'.$k.'", "'.$pid.'")';
							}
						}
						$q	= array_unique($q);
						$q = array_intersect($q,$z);					
					}
					
					
					if( count($q) > 0 ) { 
					    
						$q	= implode(', ', $q);
						$db2->query('INSERT INTO post_userbox (user_id, post_id) VALUES '.$q);
					}
					
					
					
					//notification 
					
					          	$followers=$this->db2->query('select who FROM users_followed WHERE whom='.$this->user->id.'');
              	 
                      	 
		//	$vla=$this->db2->fetch_object($followers);
	
	 	$vla=mysqli_fetch_all($followers);
	 
	        
       $network = & $GLOBALS['network'];
   		foreach($vla as $vl){
      $rules=$this->db2->query('select ntf_me_if_u_follow_buzz FROM users_notif_rules WHERE user_id='.$vl[0].'');
		   
		   $vlt=mysqli_fetch_assoc($rules);
		   
		   
 	  if($vlt['ntf_me_if_u_follow_buzz']==1 || $vlt['ntf_me_if_u_follow_buzz']==2 ){
    	$notifytype='buzz';
      $type='buzz';
    	$standardnotifytype='ntf_me_if_u_follow_buzz_poll';
    // $newisert =insert_active_profilenotifications1($vl[0],$post_id,$notifytype,$type,$standardnotifytype);
    
    
      $sql_user1 = 'SELECT * FROM users WHERE  id="' .$this->user->id . '"';
      $res_user1 = $db2->query($sql_user1);
	 $obj_user1 = $db2->fetch_object($res_user1);
    
                $data = array();
					$data['id'] = $this->user->id;
					$data['postid'] = $pid;
					$data['notification_type'] = 'polls';
					$data['username'] = $obj_user1->username;
					send_push_notification($vl[0], $data);
    
    
    
    
    $ownuserid=$vl[0];
    $postid = $pid;
    $notifytype = $notifytype;
    $type = $type;
    $standrdtype=$standardnotifytype;
    
    
    //$db2->query(
    
    	$date =time();
     $this->db2->query('insert into active_notifications  values ("","","'.$this->user->id.'","'.$ownuserid.'","'.$postid.'","'.$notifytype.'","'.$type.'","'.$date.'")');
 
		$groupid =0;
		$notif_object_type ='post';
 	$notif_object_id =$get_user_id->id;
 
 
		$this->db2->query('insert into notifications  (notif_type, to_user_id, in_group_id, from_user_id,notif_object_type,notif_object_id,date) values  ("'.$standrdtype.'","'. $ownuserid .'","'.$groupid.'","'.$this->user->id.'","'.$notif_object_type.'","'.$postid.'","'.$date.'")');
 


  	

		  $notifytype='notifications';
			$userdash =  $this->db2->fetch_field('SELECT newposts  FROM users_dashboard_tabs WHERE user_id="'.$ownuserid.'" AND tab="'.$notifytype.'"  ');
			
			//	die('hhhhhhh');

			
		if(!empty($userdash)){
			$newpost = $userdash+1;
			$this->db2->query('update users_dashboard_tabs set 	newposts="'.$newpost.'" WHERE user_id="'.$ownuserid.'" ');

			
		}else{
			$tab ="notifications";
			 $state = 1;
			 $this->db2->query('insert into users_dashboard_tabs  values ("'.$ownuserid.'","'. $tab .'","'.$state.'","'.$state.'")');

			
		}
    
    
    
    
    
    
    
    
    
		  }
      
    //   die('fffffff');
		   } 
					
					
					
			// notification over		
					
					
					
					

				}
			if(isset($_GET['from']))
			{
				$this->redirect($C->SITE_URL.'dashboard');
			}	
			else
			{
				$this->redirect($C->SITE_URL."plugin/".$plugin_name."/admin");
			}
			}
		}
		
		// Get data
		if (empty($errorMsg) && $poll_id > 0)
		{
			$query = "	SELECT poll_id, poll_date, poll_question, poll_is_active, poll_allow_user_answer						
						FROM polls p					
						WHERE p.poll_id = '$poll_id'";
			$res = $this->db2->query($query);
			$num = $res->num_rows;
			if ($num > 0)
			{
				$obj = $this->db2->fetch_object($res);
				$question = $obj->poll_question;				
				$allowUserAnswer = $obj->poll_allow_user_answer;
				$answers = array();
				
				$query2 = "	SELECT poll_answer_id, poll_id, answer, votes				
							FROM polls_answers
							WHERE poll_id = '$obj->poll_id' ORDER BY poll_answer_id";
				$res2 = $this->db2->query($query2);
				$num2 = $res2->num_rows;						
				if ($num2 > 0)
				{					
					while($obj2 = $this->db2->fetch_object($res2))
					{
						$answers[$obj2->poll_answer_id] = $obj2->answer;
					}					
				}
			}
		}
		if($_GET['action']=="add")
		{
		$header = "<div>
					<h1 class='title-pages-poll'>".($action == "add" ? "ADD":$action == "users" ? "Add" : "EDIT") ." Poll</h1>
					<a href='".$C->SITE_URL."plugin/".$plugin_name."/admin"."' class='button right'>Back</a>
					<div class='clear'></div>
				</div>";
		}
		else
		{
			if(isset($_GET['option']))
			{
				$header = "<div>
					<h1 class='title-pages-poll'>".($action == "add" ? "ADD":$action == "users" ? "Add" : "EDIT") ." Poll</h1>
					<a href='#' id='removecommentpoll' class='pull-right cancel-link'>Remove Poll</a>
					<div class='clear'></div>
					</div>";
			}
			else
			{
				$header = "
					<div>
					<h1 class='title-pages-poll'>".($action == "add" ? "ADD":$action == "users" ? "Add" : "EDIT") ." Poll</h1>
					<a href='#' id='removepoll' class='pull-right cancel-link' >Remove Poll</a>
					<div class='clear'></div>
				</div>";
			}
			$header.="
				<script>
						$('#removepoll').click(function(){
						
						  		$('#pollstore').css('display','none');
						  		$('#post').show();
						  		$('.status-btn').css('display','block');
						  		$('.characters-counter').css('display','none');
       
       $('.status-btn').css('display','block');
       $('#show-newssection-close1').css('display','none');
        $('#post').toggle();
        
        $('#poll').css('display','none');
   
        $('#videocaption').css('display','none');
        $('#pollstore').css('display','none');
   
        $('#videoupload').css('display','none');
						
					  	});
						$('#removecommentpoll').click(function(){
								$('.comment-post').show();
						  		$('.comment').show();
		 						$('.commentpoll').css('display','none');
						 
					  	});
				</script>

					";
		}		
		
		// Errors
		$error = "";		
		if (!empty($errorMsg))
		{
			$error = "<div class='system-message error'><ul class='poll_error'>";
			foreach($errorMsg as $key => $val)
			{
				$error .= "<li>".$val."</li>";
			}
			$error .= "</ul></div>";
		}		
		
		
					


	$form = "<!-- START : col-md-12 -->
	<div class='col-xs-12 col-md-12 col-md-offset-2'>
    <div class='col-xs-12 col-md-8' style='padding:0;'>


    <div class='col-md-11 col-md-11' style='padding:0;'>
	<span class='add-more'><strong>Ask your question</strong></span> <br />				
	<input type='text' ID='question' name='question' value='".$question."' class='form-control' required='required'>
				
	<div class='answers'>
    </div>";

						$form .= "<div class='form-group'>
									<span class='add-more'>Option 1:</span><br />

									<input type='text' name='answer[0]' id='answer0' value='' class='form-control' >
								  </div>
								  <div class='form-group'>
									<span class='add-more'>Option 2:</span><br />
									<input type='text' name='answer[1]' id='answer1' value='' class='form-control' >
								  </div>

								    <div class='form-group1'>
									</div>

									
									
								  ";
								  $form .='

		       <div class="col-xs-4 col-md-4" style="padding:0;"><div class="clear"></div>
		       <a href="#" class="add-more" id="addanswers"><div style="color: white; width: 35px; height: 35px; background-color: #0076a3; text-align: center; font-size: 31px; border-radius: 100px; "><p style=" font-size: 23px; ">+</p></div></a>
		       </div>
		       <div class="col-xs-6 col-md-6" style="padding:0;">
		       
		       
		       
		       
		       
		       <style>
input[type="file"] {
  display: block;
}
.imageThumb {
    max-height: 75px;
    border: 1px solid;
    padding: 1px;
    cursor: pointer;
    border-radius: 12px;
}
.pip {
 position: relative;
  display: inline-block;
  margin: 10px 10px 0 0;
}
.remove {
    position: absolute;
    top: -12px;
    right: -12px;
    display: block;
    background: red;
    border: 1px solid black;
    color: white;
    text-align: center;
    cursor: pointer;
    width: 25px;
    border-radius: 22px;
}
.remove:hover {
  background: white;
  color: black;
}
img.imageThumb {
    width: 67px;
    height: 67px;
}


.actions {
    margin-left: 115px;
}





.fileUpload {
	background: #00bcbe;
	-webkit-border-radius: 15px;
	-moz-border-radius: 15px;
	border-radius: 15px;
	color: #fff;
	font-size: 1em;
	font-weight: bold;
	margin: 1.25em auto;/*20px/16px 0*/
	overflow: hidden;
	padding: 0.875em;/*14px/16px*/
	position: relative;
	text-align: center;
	width: 120px;
   cursor: pointer;
}
.fileUpload:hover, .fileUpload:active, .fileUpload:focus {
	background: #00a2a4;
  cursor: pointer;
}
.fileUpload input.upload {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
    width: 148px;
    height: 46px;
  cursor: pointer;
}

/*input[type="file"] {
    position: fixed;
    right: 100%;
    bottom: 100%;
} */
.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}
div#field {
    width: 139px;
    color: #0076a3;
    background: white;
    border: 1px solid;
    height: 45px;
    margin: 0px;
    cursor: pointer;
}
img.pipusash {
    height: 210px;
    width: 220px;
    margin-top: 2px;
}
</style>




<div class="fileUpload" id="field">
  <input name="file" type="file" id="files" name="files[]" accept="image/png,image/jpeg,image/jpg" class="upload" onchange="return validateInputfile()" required/>
  <span><i class="fas fa-file-upload"></i>Upload Image</span>
</div>
<div style=" width: 335px; ">
<div id="fieldss" style=" width: 100px; float: left; ">

</div>
<div id="fieldssappend">

</div>
</div>




		       
		       
		      </div> 
		       
		       </div>


<script>

function reply_clickvideothumb(imgsrc){
   $("#fieldssappend").html("<img src="+imgsrc+" class=\"pipusash\" >");
}

$(document).ready(function() {
  if (window.File && window.FileList && window.FileReader) {
    $("#files").on("change", function(e) {
      var files = e.target.files,
        filesLength = files.length;
      for (var i = 0; i < filesLength; i++) {
        var f = files[i]
        var fileReader = new FileReader();
        fileReader.onload = (function(e) {
          var file = e.target;
          
          $("#fieldss").html("<span class=\"pip\">" +
            "<img onclick=\"reply_clickvideothumb(this.src)\" class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
            "<br/><span class=\"remove\">X</span>" +
            "</span>");

         
            
            
          $(".remove").click(function(){
            $(this).parent(".pip").remove();
            $("#fieldssappend").html(" ");
             $("#files").val(function() {
                return this.defaultValue;
            });

          });
          
          // Old code here
          /*$("<img></img>", {
            class: "imageThumb",
            src: e.target.result,
            title: file.name + " | Click to remove"
          }).insertAfter("#files").click(function(){$(this).remove();});*/
          
        });
        fileReader.readAsDataURL(f);
      }
    });
  } else {
    alert("Your browser doesnt support to File API")
  }
});
</script>
		       
               ';

	$form.="</div><!--/ div 'col-md-11' -->
	</div><!--/  'col-md-8' -->
    ";



     $form.="
     <div class='col-xs-12 col-md-8' id='grouptextareapoll' style='padding:0;display:none; margin-top:10px'>
    <div class='col-xs-11 col-md-10' style='padding:0'>
    <div id='grtxt' style='padding:0'><input type='text' class='form-control' class='htmlarea textarea group' id='grouptxt' value='' placeholder='Group' name='street_group' /></div>
	<!-- dropdown --><div class='col-md-12 grptype-dropdown grptype-dropdown' id='grptype-dropdown'></div><!--/ end dropdown -->

    </div>
    <div class='col-xs-1 col-md-1'>
    <a href='#' id='closegrppoll'><span class='glyphicon glyphicon-remove'></span></a>
    </div>
    </div>
     <div class='col-xs-12 col-md-8' id='usertextareapoll' style='padding:0;display:none; margin-top:10px;'>
    <div class='col-xs-11 col-md-10' style='padding:0'>
    <div id='urtxt' style='padding:0'><input type='text' class='form-control' class='group' id='usertxt' value='' placeholder='Users' name='street_user'  /></div>
			    <!-- dropdown --><div class='col-md-12 usertype-dropdown' id='usertype-dropdown'></div><!--/ end dropdown -->

    </div>

    <div class='col-xs-1 col-md-1'>
    <a href='#' id='closeuserpoll'><span class='glyphicon glyphicon-remove'></span></a>
    </div>
    </div>

    <div class='col-xs-12 col-md-12' style='padding:0; margin:15px 0px'>
    
      

";


		if($_GET['action']=="add")
		{
		$form .= "<div class='form-group pt10'>

						<div class='left'>
							<input type='checkbox' name='allowUserAnswer' ".($allowUserAnswer ? "checked='checked'" : "")." value='1'> Users are be able to add their own answer
						</div>
						<div class='right'>
							<a href='?action=add_answer' data-name='add-new-answer'>Add new answer</a>
						</div>
						<div class='clear'></div>
					</div>";
		}	
		// else if($_GET['action']=="users")
		// {
		// 	$form .= "	</div>
		// 			<div class='form-group pt10'>
		// 				<div class='right'>
		// 					<a id='addmore'>Add new answer</a>
		// 				</div>
		// 				<div class='clear'></div>
		// 			</div>
		// 		</div>
		// 		<script>
		// 		$('#addmore').click(function(){
		// 			var data='<div class='form-group'><label>Answer:</label><input type='text' name='answer[]' class='form-control' ></div>';
		// 			$('.answers').html(data);
		// 		});
		// 		</script>
		// 		";
		// }





		$actions = "
		 <div class='actions' style='padding:0'>

		 <input type='submit' id='save' name='save' value='Buzz' class='chieldpoll status-btn post-btn btn blue small pull-left' style='padding: 8px 15px;color:white; margin-bottom:10px;' disabled='disabled'/>
		 <div class='clear'></div>
		 </div>

                        
					    </div><!--/ END:  'col-md-12' -->






					<script>
					$('#answer1').keyup(function(){
						if($('#question').val()=='' || $('#answer0').val()=='' || $('#answer1').val()=='' )
						{
							$('#save').css('disabled','disabled');
						}
						else
						{
							$('#save').removeAttr('disabled');
							$('#save').css('enabled','enabled');
						}
					});
					$('#answer0').keyup(function(){
						if($('#question').val()=='' || $('#answer0').val()=='' || $('#answer1').val()=='' )
						{
							$('#save').css('disabled','disabled');
						}
						else
						{
							$('#save').removeAttr('disabled');
							$('#save').css('enabled','enabled');
						}
					});	
					$('#question').keyup(function(){
						if($('#question').val()=='' || $('#answer0').val()=='' || $('#answer1').val()=='' )
						{
							$('#save').css('disabled','disabled');
						}
						else
						{
							$('#save').removeAttr('disabled');
							$('#save').css('enabled','enabled');
						}
					});
					</script>
					<script>
					    function validateInputfile(){

					        var fup = document.getElementById('files');
                             var fileName = fup.value;
                             var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
                        
                            if(ext =='jpg' || ext=='png' || ext == 'jpeg')
                            {
    
                               return true;
                            }
                            else
                            {
                                alert('Only JPEG,JPG,PNG Images are allowed!');
                                document.getElementById('files').value = null;
                                return false;
                            }
                    }
					</script>
					";

				
		if($_GET['action']=="users")
		{
			$main_content = "<div class='poll-admin'>
							<form enctype='multipart/form-data' method='post' action='".$C->SITE_URL."plugin/poll/admin?action=$action&poll_id=$poll_id&from=users' >" . //novalidate
								$header . $error . "<div class='edit'>" . $form . $actions . "</div>
							</form>
						</div>";
		}
		else
		{
			$main_content = "<div class='poll-admin'>
								<form enctype='multipart/form-data' method='post' action='?action=$action&poll_id=$poll_id' >" . //novalidate
									$header . $error . "<div class='edit'>" . $form . $actions . "</div>
								</form>

							</div>";	
		}

	}
	
	// Details
	if ($action == "details" && $poll_id > 0)
	{
		$query = "	SELECT poll_id, poll_date, poll_question, poll_is_active, poll_allow_user_answer						
					FROM polls p					
					WHERE p.poll_id = '$poll_id'";
		$res = $this->db2->query($query);
		$num = $res->num_rows;
		if ($num > 0)
		{
			$obj = $this->db2->fetch_object($res);
			
			$header = "<div class='header'>
							<h1>".$obj->poll_question."</h1>
							<a href='".$C->SITE_URL."plugin/".$plugin_name."/admin"."' class='button right'>Back</a>
							<div class='clear'></div>
						</div>";					
		
			$query = "	SELECT puv.poll_id, puv.user_id, puv.vote_date, puv.poll_answer_id, pa.answer,
							u.email, u.username, u.fullname
						FROM polls_user_votes puv
							INNER JOIN polls_answers pa ON pa.poll_answer_id = puv.poll_answer_id
							INNER JOIN users u ON u.id = puv.user_id
						WHERE puv.poll_id = '$poll_id'
						ORDER BY puv.vote_date";
			$res = $this->db2->query($query);
			$num = $res->num_rows;
			$userList = "";
			if ($num > 0)
			{
				$userList = "<table class='table'>
								<tr>
									<th>User</th>
									<th>Email</th>
									<th>Answer</th>
									<th>Date</th>
								</tr>";
			
				while($obj = $this->db2->fetch_object($res))
				{	
					$userList .= "<tr>
									<td>".$obj->username."</td>
									<td>".$obj->email."</td>
									<td>".$obj->answer."</td>
									<td>".date("d F Y", $obj->vote_date)."</td>
								</tr>";
				}
				
				$userList .= "</table>";
			}
			else
			{
				$userList = "<div>There is no items.</div>";
			}
			
			$main_content = "<div class='poll-admin'>" . $header . $userList . "</div>";		
		}
	}	
}
else
{
	// View
	$header = "<div class='header'>
					<h1>MANAGE POLLS</h1>
					<div class='clear'></div>
				</div>";
				
	$query = "	SELECT poll_id, poll_date, poll_question, poll_is_active, poll_allow_user_answer,
					(SELECT SUM(votes) FROM polls_answers WHERE poll_id = p.poll_id) as voted_count
				FROM polls p					
				ORDER BY poll_date";
	$res = $this->db2->query($query);
	$num = $res->num_rows;
	$pollList = "";
	if ($num > 0)
	{
		$pollList = "<table class='table'>";
		
		while($obj = $this->db2->fetch_object($res))
		{	
			$pollList .= "<tr>
							<td>
								<div data-name='poll-question'>
									<a href='' class='left icon icon-right'></a>
									<a href=''>".$obj->poll_question."</a>
								</div>"; // ".($obj->poll_is_active ? "icon-down" : "icon-right")."
								
								$query2 = "	SELECT poll_answer_id, poll_id, answer, votes				
											FROM polls_answers
											WHERE poll_id = '$obj->poll_id' ORDER BY poll_answer_id";
									
								$res2 = $this->db2->query($query2);
								$num2 = $res2->num_rows;
										
								if ($num2 > 0)
								{
									$pollList .= "<div data-name='poll-votes' class='poll-votes display-none'>"; // ($obj->poll_is_active ? "" : "display-none")
									while($obj2 = $this->db2->fetch_object($res2))
									{
										$percent = ($obj2->votes > 0 && $obj->voted_count > 0) ? 
											(int)($obj2->votes / $obj->voted_count * 100) : 0;
										$percentFormat = number_format($percent); //, 2, ',', ' ');
										$pollList .= "	<div class='answer'>
															<div>".$obj2->answer."</div>
															<div>
																<div class='percent'>".$percentFormat."%</div>
																<div class='bar-wrap'>
																	<div class='bar' style='width:".$percentFormat."%;'>&nbsp;</div>
																</div>
																<div class='clear'></div>
															</div>
														</div>";
									}
									$pollList .= "<div>".$obj->voted_count." Votes</div>";
									$pollList .= "</div>";
								}
								
			$pollList .= "	</td>
							<td><a href='?action=details&poll_id=".$obj->poll_id."'>View poll details</a></td>
							<td><a href='?action=edit&poll_id=".$obj->poll_id."'>Edit</a></td>
							<td><a href='?action=".($obj->poll_is_active ? "deactivate" : "activate")."&poll_id=".$obj->poll_id."'>".
								($obj->poll_is_active ? "Deactivate" : "Activate")."</a></td>
							<td><a href='?action=delete&poll_id=".$obj->poll_id."' data-name='poll-delete'>
									<img src='".$C->SITE_URL."apps/".$plugin_name."/static/images/icon-delete.png' /></a>
							</td>
						</tr>";
		}
		
		$pollList .= "</table>";
	}
	else
	{
		$pollList = "<div>There is no items.</div>";
	}
			
	$actions = "<div class='actions'>
					<a href='".$C->SITE_URL."plugin/".$plugin_name."/admin?action=add"."' class='button right'>+ Add</a>
					<div class='clear'></div>
				</div>";
	
	$main_content = "<div class='poll-admin'><form enctype='multipart/form-data' method='post' action='?'>" . $header . $pollList . $actions . "</form></div>";
}

$tpl->layout->setVar('main_content', $main_content);

$tpl->initRoutine('AdminLeftMenu', array());
$tpl->routine->load();
$tpl->display();
}

}
 function assethtml($parentid){
	 $data =array();
	 $obj =$data[0];
	 $buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);
     $assetdata   =$buff->assetdata($parentid);
	 if($assetdata[0]->ticker !=''){
	  $str =  $buff->parsetext($assetdata[0]->message);
	  $assetdatahtml ='<div>'.$str.'</div>
			<table class="table table-bordered" width="100%">
    <thead>
      <tr class="box-sub-title intraday-title">
        <th>Asset</th>
        <th>Price @ Buzzing</th>
        <th>Stop Loss</th>
		<th>Target Price</th>
		<th>Current Price</th>
		<th>Result</th>
      </tr>
    </thead>
    <tbody>
			
			';
			foreach($assetdata as $assetkeys=>$assetvals){
				if($assetkeys/2 ==0){
					$css ="#f6fbfc";
					
				}else{
					$css ="#e3f8fe";
				}
				if($assetvals->result == "1"){
					$img ='<img  src="'.$C->SITE_URL.'static/images/tick.png" width="16"/>';
					
				}elseif($assetvals->result =="0"){
					$img ='<img  src="'.$C->SITE_URL.'static/images/wrong.png"/>';
					
				}else{
					$img ='Open';
					
				}
				$assetdatahtml .='
				
  
      <tr style="background-color:'.$css.'; color: #66757F; font-size:12px;font-weight:normal;">
        <td>$'.$assetvals->ticker.'</td>
        <td>2130</td>
        <td>'.$assetvals->stoploss_price.'</td>
		<td>'.$assetvals->predicted_price.'</td>
		<td>'.$assetvals->current_price.'</td>

		<td>'.$img.'</td>
      </tr>
      
    
	
				
				';
				
			}
			$assetdatahtml .='</tbody>
  </table>';

   $parentmessage = $assetdatahtml;
   return $parentmessage;
			
			
		}else{
			 return '';
			
		}	
}
function eventhtml($parentid){
	$data =array();
	 $obj =$data[0];

	 $buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);
	 	 $user->id =$buff->presentuser();

		           $eventdetails = $buff->geteventdetails($parentid);
				   

					if($eventdetails->group_id !=''){
						$groupname               =$buff->getgroupname($eventdetails->group_id);
						}
					$finalcon ='';
					$finalcon .= '<div class="title"><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/event.png"> <a href="'.$C->SITE_URL.'plugin/events/view/id:'.$eventdetails->id.'/postid:'.$eventdetails->post_id.'"  class="event-list-title"><strong>Event Name:</strong> '.$eventdetails->event_name.'</a></div>';
                    $finalcon .= '<span class="event-list-heading">Location:</span> <span class="event-list-txt">'.$eventdetails->address.'</span><br />';      
					if(!empty($eventdetails->url)){

					$finalcon .='<span class="event-list-heading">URL:</span> <span class="event-list-txt"><a href="'.$eventdetails->url.'"  target="_blank">'.$eventdetails->url.'</a></span><br />';						
                    }
					$time =$eventdetails->start_date.' '.$eventdetails->start_time;
					$date_time = date("M d,Y h:i:s A", strtotime($time));

					
                    $finalcon .='<span class="event-list-heading">Date and Time:</span> <span class="event-list-txt">'.$date_time.'</span><br />	
						';
					if(!empty($eventdetails->tag_name)){
						$hastagarr        =explode("#",trim($hastag));
			            $strret_arr       =array_filter($hastagarr);

						foreach($strret_arr as $keys=>$vals){
											if($keys ==1){
												$finalcon .='<span><a href="'.$C->SITE_URL.'/search/tab:tags/s:'.$vals.'"><strong>#'.$vals.'</strong></a>';
											}else{
												$finalcon .='<strong>#'.$vals.'</strong></span>';
											}
											
						}
						$finalcon .='<span class="event-list-heading">Hash Tags:</span> <span class="event-list-txt">'.$finalcon.'</span><br />
						';	
                    }
					if($eventdetails->status ==1){
						$st ="Active";
						
					}else{
						$st ="Cancelled";
						
					}
					if($user->id != $eventdetails->admin_id){
					if($eventdetails->event_status !=2  ){
						
						if( ($eventdetails->event_status =='' &&  $eventdetails->edit_status =='')   ){
						$finalcon .='<div id="acc-'.$eventdetails->post_id.'"><strong>User Response:</strong><input type="radio"  class="accept" name="accept" onclick="myFunction('.$eventdetails->post_id.'1)" value="'.$eventdetails->post_id.'-1">Accept<input type="radio" class="accept" name="accept" onclick="myFunction('.$eventdetails->post_id.'3)"  value="'.$eventdetails->post_id.'-3">Reject</div>';
					    
					}
					if($eventdetails->event_status==1){
						$display ="block";
						
					}else{
						$display ="none";
					}
					if($eventdetails->event_status==3){
						$displayreject ="block";
						
					}else{
						$displayreject ="none";
					}
					
					if( ($eventdetails->event_status !=2 ||  $eventdetails->edit_status!=4) &&  ($eventdetails->event_status !=2 &&   $eventdetails->edit_status!=4) ){
					
					
					$finalcon .='<div style="display:'.$display.';"id="accept-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					$finalcon .='<div style="display:'.$displayreject.';"id="reject-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
                  
					$finalcon .='<input type="hidden" id="attach-'.$eventdetails->post_id.'"  value="'.$eventdetails->attachment_id.'">';
                    }else{
						if((($eventdetails->event_status!=4) &&   ($eventdetails->edit_status==4))){
							
					$finalcon .='<div style="display:'.$display.';"id="accept-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					$finalcon .='<div style="display:'.$displayreject.';"id="reject-'.$link->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
                  
							
						}else{
						$finalcon .='<div>This event was no longer available.</div>';
						}
					}
					if($eventdetails->event_status == 5){
						$finalcon .='<div>This event was modified.</div>';
						
					}
						$finalcon .='<div>Status:'.$st.'</div>';

					
					
					
					}else{
						
						$finalcon .='<div>Event Cancelled</div>';

						
					}
					}else{
						if( $eventdetails->event_status!=2 || $eventdetails->event_status!=4 || $eventdetails->event_status!=5  ){
						$finalcon .='<div><strong>Status:</strong>'.$st.'</div>';

						$finalcon .='<div><a href="'.$C->SITE_URL.'dashboard?pid='.$link->post_id.'"><input class="btn-download-results" type="button" name="download" value="Download Results"></a></div>';

						}else{
							if($eventdetails->status == 2 && $eventdetails->edit_status!=4 ){
						$finalcon .='<div><strong>status:</strong>Cancel</div>';

								
							}else{
							$finalcon .='<div><strong>Status:</strong>'.$st.'</div>';

							$finalcon .='<div>This event was no longer available.</div>';

							}

							
						}
						
					}
					

					$parentmessage = $finalcon;
					return $parentmessage;
	
}





	






function pollhtml($parentid){
	  $data =array();
	  $obj =$data[0];
	  $buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);
	  	$user->id =$buff->presentuser();

		$poll  = $buff->replay_is_poll($parentid);

		$pollanswer=$buff->is_pollanswer($user->id,$poll[0]->poll_id);

		$message ='';
      $message .='<div class="activity-poll"><div class="attachments lightbox-enabled">
	<div class="images">
		<div class="list-link-container" style="margin-bottom:30px">
			<b><label style="color:#2665ca;"> Poll: '.$poll[0]->poll_question.'</label></b>
			<!-- <a target="_blank" href="" class="lightbox-image image-thumb cboxElement "><img alt="filename" src=""></a> -->
			<!-- //this is placeholder for video player <div class="video-placeholder"></div>  -->
		</div>
	
	</div>
	<div class="links">';
	foreach($poll as $keys=>$vals){
				if($vals->answer!="" && count($pollanswer)<=0){

	
		$message .='<div><input onclick="changeurl(this.value,this.id)" id="'.$vals->poll_id.'" class="option'.$vals->poll_answer_id.' radio'.$vals->poll_id.'" name="option" type="radio" value="'.$vals->poll_answer_id.'"/>'.$vals->answer.'</div><br>';
	}else if($vals->answer!="")
	{
	$countpollanswer=$buff->is_countpollanswer($vals->poll_id,$vals->poll_answer_id);
	$message .='<div ><table><tr><td style="margin: 0px;width:200px">'.$vals->answer.'</td><td style="margin: -15px 0px 0px 60px;">'.count($countpollanswer).'</td>
<td style="width: 500px;padding-top: 18px;"><div style="background-color: green!important;width:'.count($countpollanswer).'%;height: 15px;margin: -15px 10px 25px 77px;"></div></td></tr></table></div>';

	}
	}
	$message .='</div>
	<div class="activity-poll-option"><span id="optionerror'.$poll[0]->poll_id.'"></span><br><span><a onclick="checkoption('.$poll[0]->poll_id.')" id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=answer&poll_id='.$poll[0]->poll_id.'&from='.$user->id.'"><button style="color:white;background-color:orange; padding:6px 8px; border:1px solid orange;">Submit</button></a>&nbsp;&nbsp;&nbsp;';
	if($user->id==$vals->user_id)
    {
	$message .='<a id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'"><button  class="btn-download-results">Download Results</button></a>';
	}
	$message .='</span></div>
</div></div>';
$parentmessage = $message;
return $parentmessage;

	
}
?>


<style>
.btn-white {
	border-color: #0084B4;
	border-color: rgba(0,132,180,.5);
    color: #0084B4;
    background: rgba(255,255,255,0.75);
    border-style: solid;
    border-width: 1px;
    box-shadow: none;
    opacity: .8;
    -ms-filter: "alpha(opacity=80)";
}
.btn-white:hover {
	background-color: #1b95e0;
	color: #fff;
}
/* Usertype */
.usertype-dropdown { margin-top: -10px; width:100%; z-index:50; display:none; background:#fff;}
.usertype-dropdown {font-weight:bold; font-style:italic; color:#6E6E6E; font-size:10px; border:1px solid #C2C2C2; border-top:none;}
.usertype-dropdown ul {list-style:none; margin:0px; border:0px solid #C2C2C2; border-top:none;}
.usertype-dropdown ul li {border-bottom:1px solid #F5F5F5; cursor:pointer; display:block; width:100%; margin-left: -54px; padding:1px;}
.usertype-dropdown ul li.hover {background:#0076a3; color: #fff;}
.usertype-dropdown ul li.selection {color: #6E6E6E;}
.usertype-dropdown ul li.selection:hover {color: #fff;}
.grptype-dropdown { margin-top: -10px; width:100%; z-index:50; display:none; background:#fff;}
.grptype-dropdown {font-weight:bold; font-style:italic; color:#6E6E6E; font-size:10px; border:1px solid #C2C2C2; border-top:none;}
.grptype-dropdown ul {list-style:none; margin:0px; border:0px solid #C2C2C2; border-top:none;}
.grptype-dropdown ul li {border-bottom:1px solid #F5F5F5; cursor:pointer; display:block; width:100%; margin-left: 0px; padding:1px;}
.grptype-dropdown ul li.hover {background:#0076a3; color: #fff;}
.grptype-dropdown ul li.selection {color: #6E6E6E;}
.grptype-dropdown ul li.selection:hover {color: #fff;}
</style>


<!-- Group/User script starts -->
<script>
 function selectpollgrp(val) {
$("#grouptxt").val(val);
$(".grptype-dropdown").hide();
}
$(document).ready(function() {
	 $("#grouptxt").keyup(function(){
	 var group = $(this).val();
		$.ajax({
			type: "POST",
			url:"<?php  echo $C->SITE_URL;?>autocomplete",
			data:{poll_group:group},
			
			success: function(data){
				$(".grptype-dropdown").show();
				$(".grptype-dropdown").html(data);
			}
			});
	 
 });
var maxField = 6; //Input fields increment limitation
	var addButton = $('.add-more'); //Add button selector
	var wrapper = $('.form-group1'); //Input field wrapper
	var x = 3; //Initial field counter is 1
	$(addButton).click(function(){ //Once add button is clicked
		var fieldHTML = '<div class="col-xs-12 col-md-12" id="descopt'+x+'" style="padding:0;"><div class="col-xs-11 col-md-11" style="padding:0"><span class="add-more">Option '+x+':</span><input type="text" class="form-control" name="answer['+(x-1)+']" id="answer'+x+'" value=""/></div><div class="col-xs-1 col-md-1" style="padding-top:28px;"><a href="javascript:void(0);" class="remove_button" rel="'+x+'" title="Remove field"><span class="glyphicon glyphicon-remove"></span></a></div></div>'; //New input field html 

		if(x < maxField){ //Check maximum number of input fields
			x++; //Increment field counter
			$(wrapper).append(fieldHTML); // Add field html
		}
	});
	$(wrapper).on('click', '.remove_button', function(e){ //Once remove button is clicked
		var id = $(this).attr('rel');
		e.preventDefault();
		$('#descopt'+id).remove(); //Remove field html
		x--; //Decrement field counter
	});
	    $("#addanswers").click(function () {
			    var counter = 3;

				
	if(counter>10){
            alert("Only 10 textboxes allow");
            return false;
	}  
$(".form_group").append('hi');	
		
	
            
	;

				
	counter++;
     });
$("#grpuser").hide();

$("#grppoll").click(function() {
	$("#grouptextareapoll").css("display","block");
$('#share').val('group');
});

$("#userpoll").click(function() {
	
	$("#usertextareapoll").css("display","block");

$('#share').val('user');
});


$("#closegrppoll").click(function() {
$("#grouptextareapoll").hide(500);
$("#grppoll").css("background-color" , "rgba(255,255,255,0.75)");
$("#grppoll").css("color" , "#1b95e0");
});

$("#closeuserpoll").click(function() {
$("#usertextareapoll").hide(500);
$("#userpoll").css("background-color" , "rgba(255,255,255,0.75)");
$("#userpoll").css("color" , "#1b95e0");
});

});
</script>
<!-- Group/User script ends -->


<!-- statrt Group/Add Users 'active' color -->
<script>
$(document).ready(function() {

$("#grppoll").click(function() {
$("#grppoll").css("background-color" , "#1b95e0");
$("#grppoll").css("color" , "#ffffff");
});

$("#userpoll").click(function() {
$("#userpoll").css("background-color" , "#1b95e0");
$("#userpoll").css("color" , "#ffffff");
});

});
</script>
<!-- end Group/Add Users 'active' color -->


        <script src='<?php echo $C->SITE_URL;?>/static/js/htmlarea_event.js?v=3.6.0'></script>
		<script src='<?php echo $C->SITE_URL;?>/static/js/htmlarea_user.js?v=3.6.0'></script>

