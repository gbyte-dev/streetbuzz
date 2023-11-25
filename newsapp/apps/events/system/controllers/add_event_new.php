<?php		

  		
	require $C->INCPATH.'helpers/func_additional.php';
	$designer = pageDesignerFactory::select();
	$submit = FALSE;
	$error = FALSE;
	$errmsg = '';
	if( isset($_POST['title']) ) {
		 $submit = TRUE;
		$display_type = $db2->escape($_POST['display_type']);
		$title = $db2->escape($_POST['title']);
		$start_date = $db2->escape($_POST['start_date']);
		$start_time = $db2->escape($_POST['start_time']);
		$end_date = $db2->escape($_POST['end_date']);
		$end_time = $db2->escape($_POST['end_time']);
		$location = '';
		$address = $db2->escape($_POST['address']);
		$description = $db2->escape($_POST['description']);
		$private = $db2->escape($_POST['is_private']);
		$publish_now = '';
		$publish_date = '';
		$pub_select_day = '0000-00-00';
		if(!empty($_POST['publish_now'])){
			$publish_now = $db2->escape($_POST['publish_now']);
		}
		if(!empty($_POST['publish_date'])){
			$publish_date = $db2->escape($_POST['publish_date']);
			
			if($publish_date == '1'){
				$pub_select_day =  date('Y-m-d H:i:s', strtotime($db2->escape($_POST['pub_select_day'].' '.$db2->escape($_POST['publish_time']))));
				if(empty($pub_select_day)){
					$pub_select_day = date('Y-m-d H:i:s');
				}
			}
		}
		if($publish_date == '1'){
			$pub_select_day =  date('Y-m-d H:i:s', strtotime($db2->escape($_POST['pub_select_day'].' '.$db2->escape($_POST['publish_time']))));
			if(empty($pub_select_day)){
				$pub_select_day = date('Y-m-d H:i:s');
			}
		}
		$is_private = 0;
		$event_type = $db2->escape($_POST['event_type']);
		$group_id = !empty($_POST['group_id'])?$db2->escape($_POST['group_id']):'0';
			 
		// Validate the input
		if(strlen($title) == 0){
			$error = TRUE;
			$errmsg .= 'Please enter valid event name. <br />';
		}
		if (strlen($start_date) == 0 || strlen($start_time) == 0){
			$errmsg.="Please enter start date and time. <br />";
			$error = TRUE;
		}
		if (strlen($end_date) == 0 || strlen($end_time) == 0){
			$errmsg.="Please enter start date and time. <br />";
			$error = TRUE;
		}
		$curdate=strtotime($start_date.' '.$start_time);
		$mydate=strtotime($end_date.' '.$end_time);

		if($curdate > $mydate){
			$errmsg.="End date must be greater than from date. <br />";
			$error = TRUE;
		}
		if(!empty($_FILES['attachment']['name'][0])){
			$k=0;
			//foreach($_FILES['attachment']['name'] as $file){
				$maxsize    = 2097152;
				$allowedExts = array("pdf", "gif", "jpeg", "jpg", "png", 'docx', 'doc', 'pptx', 'ppt', 'xls', 'xlsx');
				$temp = explode(".", $_FILES['attachment']['name'][$k]);
				$extension = end($temp);
				$file_type = $_FILES["attachment"]["type"][$k]; 
				if ((($file_type == "application/pdf") || 
				($file_type == "application/msword") || 
				($file_type == "application/vnd.ms-excel") || 
				($file_type == "application/octet-stream") || 
				($file_type == "application/vnd.ms-powerpoint") || 
				($file_type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") || 
				($file_type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") || 
				($file_type == "application/vnd.openxmlformats-officedocument.presentationml.presentation") || 
				($file_type == "image/gif") || 
				($file_type == "image/jpeg") || ($file_type== "image/jpg")  || ($file_type == "image/pjpeg")  || ($file_type == "image/x-png")  || ($file_type == "image/png"))  && in_array($extension, $allowedExts)) {
					if ($_FILES["attachment"]["error"][$k] > 0) {
					  $errmsg.="Please upload a valid image, MS office or pdf file. <br />";
					  $error = TRUE;
					}
				 }else{
					  $errmsg.="Please upload a valid image, MS office or pdf file. <br />";
					  $error = TRUE;
				 }
				
				if(($_FILES['attachment']['size'][$k] >= $maxsize) || ($_FILES["attachment"]["size"][$k] == 0)) {
					$errors[] = 'Attachment too large. Attachment must be less than 2 megabytes.';
				}
				$k++;
			//}
		}

		if(!empty($page->params->group)){
			$g	= $this->network->get_group_by_id(intval($page->params->group), true);
		}
		
		if($_SESSION['submitted'] === 1 ){
			  $errmsg.="Double submit detected. Please check your calendar.";
			  $error = TRUE;
		}
		if($error === FALSE){
			$_SESSION['submitted'] = 1;
			//print_R(); exit;
			$date_time = date('M d, Y h:i A', strtotime($start_date.' '.$start_time));
			 
			$id=$this->db2->query('INSERT INTO `events` (publish_now, publish_date, activity_pub_date, created_at, modified_at, group_id, admin_id, location, address, event_type, display_type, 	event_name, event_description, start_date, 	start_time,end_date, time_zone, end_time, is_private, status) 
											VALUES ("'.$publish_now.'","'.$publish_date.'","'.$pub_select_day.'", now(), now(), "'.$group_id.'", "'.$this->user->id.'", "'.$location.'", "'.$address.'", "'.$event_type.'", "'.$display_type.'", "'.$title.'", "'.$description.'", "'.date('Y-m-d H:i:s',strtotime($start_date)).'", "'.date('Y-m-d H:i:s',strtotime($start_time)).'", "'.date('Y-m-d H:i:s',strtotime($end_date)).'", "","'.date('Y-m-d H:i:s',strtotime($end_time)).'","'.$private.'", 1)');
			$id = $db2->insert_id();
			
			if($id){
				if($publish_now == 1){
					$group_id    = !empty($_POST['group_id'])?$this->db2->escape($_POST['group_id']):'0';
					$addition_url = empty($group_id) ? '' : '/group:'.$group_id;
					$content_title = '<div class="title"><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/event.png"> <a href="'.$C->SITE_URL.'plugin/events/view/id:'.$id.$addition_url.'">'.$title.'</a></div>';
					$content = '<p class="address_view">
									<span>'.$address.'</span>
								</p>				
						<span class="time">'.$date_time.'</span>
					';
					//$left = ($page->params->group)?(strlen($this->user->info->username)+strlen($g->title)+8):(strlen($this->user->info->username)+4);
					$answer = (object)array(
							'link'			=> $C->SITE_URL . 'plugin/events/view/id:'.$id.$addition_url, 
							'title'			=> $content_title,
							'description'	=> $content,
							'hits'			=> 'link'
						);
					
					if( $this->user->id > 0 ) {
						$db2->query('UPDATE users SET num_posts=num_posts+1, lastpost_date="'.time().'" WHERE id="'.$this->user->id.'" LIMIT 1');
						$db2->query('INSERT INTO '.($is_private?'posts_pr_comments_watch':'posts_comments_watch').' SET user_id="'.$this->user->id.'", post_id="'.$id.'", newcomments=0');
					}				
					$db2->query('INSERT INTO posts SET user_id="'.$this->user->id.'", group_id="'.$group_id.'", date="'.time().'", date_lastcomment="'.time().'", attached="1"'); 
					$pid = $db2->insert_id();
					
					$db2->query('INSERT INTO event_posts SET event_id="'.$id.'", post_id="'.$pid.'", created = "'.date('Y-m-d H:i:s').'"');

					$db2->query('INSERT INTO post_userbox SET user_id="'.$this->user->id.'", post_id="'.$pid.'"');
					$db2->query('INSERT INTO posts_attachments SET post_id="'.$pid.'", type="link",data="'.$db2->escape(serialize($answer)).'"');
					
					$q =array();

					//insert to followers data
					if($this->user->info->is_posts_protected == 0){
						$u	= $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers;
					}else{
						$u	= array_intersect_key($this->network->get_user_follows($this->user->id, FALSE, 'hefollows')->follow_users, $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers);
					}
							
					$u	= $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers;
					foreach($u as $k=>$v) {
						$q[]	= '("'.$k.'", "'.$pid.'")';
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

					//insert roles and role resoureces
					if(!empty($_POST['role_name'])){
						$i=0;
						foreach($_POST['role_name'] as $role_name){
							$this->db2->query('INSERT INTO `event_roles` (role_name, role_desc, group_id, event_id, created_at) 
							VALUES ("'.$role_name.'","'.$_POST['role_description'][$i++].'", "'.$group_id.'","'.$id.'", "'.date('Y-m-d H:i:s').'")');
							$roles[$role_name] = $db2->insert_id();
						}
					}
					$j=0;
					
					$additional_group_info = '';
					if( !empty($group_id) ){
						$group_details = $this->network->get_group_by_id(intval($group_id), true);
						$additional_group_info = empty( $group_details->title ) ? '' : ' : '.$group_details->title;
					}

					if(!empty($_POST['username'])){
						foreach($_POST['username'] as $username){
							$user_name = $this->network->get_user_by_username($username);
							$role_id = $roles[$_POST['role_id'][$j]];
							$this->db2->query('INSERT INTO `event_role_resource` (user_id, group_id, role_id, created_at) 	VALUES ("'.$user_name->id.'","'.$group_id.'", "'.$role_id.'", "'.date('Y-m-d H:i:s').'")');
							
							//sending the messages
							$subject = 'Event Role Added in your Group'.$additional_group_info;
							$message_html = ucwords($title).' has been added to your group';
							$message_html .= '<br /><b>Event Name: </b>'.ucwords($title);
							$message_html .= '<br /><b>When: </b>'.$date_time;
							$message_html .= '<br /><b>Address: </b>'.$address;
							$message_html .= '<br /><b>Your Role: </b>'.$_POST['role_id'][$j];
							$message_html .= '<br /> --------------------------------------------------------<br />';
							$message_html .= 'This is an automatically generated email from an unattended inbox. <b>Please Do Not Reply</b>. Replies to this message will not be seen.';
							$message_html = '<br /> Hello '.$user_name->fullname.', <br />'.$message_html;
							do_send_mail_html($user_name->email, $subject, '', $message_html, '');
							$j++;
							
	
						}
					}
					
					//event notification start 
					
		
		$followers=$db2->query('select who FROM users_followed WHERE whom='.$this->user->id.'');
 
	
	 	$vla=mysqli_fetch_all($followers);
	 
	        
       $network = & $GLOBALS['network'];
   		foreach($vla as $vl){
      $rules=$db2->query('select ntf_me_if_u_follow_buzz FROM users_notif_rules WHERE user_id='.$vl[0].'');
		   
		   $vlt=mysqli_fetch_assoc($rules);
		   
		   
 	  if($vlt['ntf_me_if_u_follow_buzz']==1 || $vlt['ntf_me_if_u_follow_buzz']==2 ){
    	$notifytype='buzz';
      $type='buzz';
    	$standardnotifytype='ntf_me_if_u_follow_buzz_event';
    // $newisert =insert_active_profilenotifications1($vl[0],$post_id,$notifytype,$type,$standardnotifytype);
    
    
    
    							
	 $sql_user1 = 'SELECT * FROM users WHERE  id="' .$this->user->id . '"';
      $res_user1 = $db2->query($sql_user1);
	 $obj_user1 = $db2->fetch_object($res_user1);
    
                $data = array();
					$data['id'] = $this->user->id;
					$data['postid'] = $pid;
					$data['notification_type'] = 'event';
					$data['username'] = $obj_user1->username;
					send_push_notification($vl[0], $data);
							
    
    
    $ownuserid=$vl[0];
    $postid = $pid;
    $notifytype = $notifytype;
    $type = $type;
    $standrdtype=$standardnotifytype;
    
    
    
    
    //$db2->query(
    
    	$date =time();
     $db2->query('insert into active_notifications  values ("","","'.$this->user->id.'","'.$ownuserid.'","'.$postid.'","'.$notifytype.'","'.$type.'","'.$date.'")');
 
		$groupid =0;
		$notif_object_type ='post';
 	$notif_object_id =$get_user_id->id;
 
 
		$db2->query('insert into notifications  (notif_type, to_user_id, in_group_id, from_user_id,notif_object_type,notif_object_id,date) values  ("'.$standrdtype.'","'. $ownuserid .'","'.$groupid.'","'.$this->user->id.'","'.$notif_object_type.'","'.$postid.'","'.$date.'")');
 


  	

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
 		
					
					//event notification over
					
					if($display_type == 'group' && !empty($group_id)){
						$user_list= $this->network->get_group_members($group_id);
						
						$subject = 'Event Added : '.ucwords($title);
						$message_html = ucwords($title).' has been added to your group'.$additional_group_info;
						$message_html .= '<br /><b>Event Name: </b>'.ucwords($title);
						$message_html .= '<br /><b>When: </b>'.$date_time;
						$message_html .= '<br /><b>Address: </b>'.$address;
						$message_html .= '<br /> --------------------------------------------------------<br />';
						$message_html .= 'This is an automatically generated email from an unattended inbox. <b>Please Do Not Reply</b>. Replies to this message will not be seen.';
						
							//Send mail
						foreach($user_list as $user_id=>$post){
							$user= $this->network->get_user_by_id($user_id);
							$message_html1 = '<br /> Hello '.$user->fullname.', <br />'.$message_html;
							do_send_mail_html($user->email, $subject, '', $message_html1, '');
						}
					}
				}else{
						//insert roles and role resoureces
					if(!empty($_POST['role_name'])){
						$i=0;
						foreach($_POST['role_name'] as $role_name){
							$this->db2->query('INSERT INTO `event_roles` (role_name, role_desc, group_id, event_id, created_at) 
							VALUES ("'.$role_name.'","'.$_POST['role_description'][$i++].'", "'.$group_id.'","'.$id.'", "'.date('Y-m-d H:i:s').'")');
							$roles[$role_name] = $db2->insert_id();
						}
					}
					$j=0;
					if(!empty($_POST['username'])){
						foreach($_POST['username'] as $username){
							$user_name = $this->network->get_user_by_username($username);
							$role_id = $roles[$_POST['role_id'][$j]];
							$this->db2->query('INSERT INTO `event_role_resource` (user_id, group_id, role_id, created_at) 	VALUES ("'.$user_name->id.'","'.$group_id.'", "'.$role_id.'", "'.date('Y-m-d H:i:s').'")');
							$j++;
						}
					}
				}				
				
				if(!empty($_FILES['attachment']['name'][0])){
					$k=0;
					$file	= (object) $_FILES['attachment'];
						foreach($file->name as $file_name){

						$ext	= '';
						$pos	= mb_strpos($file->name[$k], '.');
						if( FALSE !== $pos ) {
							$ext	= '.'.mb_strtolower(mb_substr($file->name[$k],$pos+1));
						}
						$tempfile	= time().rand(1000000,9999999).$ext;
						move_uploaded_file($file->tmp_name[$k], $C->STORAGE_TMP_DIR.$tempfile);
						if( ! file_exists($C->STORAGE_TMP_DIR.$tempfile) ) {
							$data	= FALSE;
							break;
						}
						chmod($C->STORAGE_TMP_DIR.$tempfile, 0777);
						$data	= (object) array (
								'tempfile'	=> $tempfile,
								'filename'	=> $file->name[$k],
								'filetype'	=> $file->type[$k],
								'filesize'	=> filesize($C->STORAGE_TMP_DIR.$tempfile),
						);

						$file_type = detectUploadedFileType( $data->filetype );
						$data->detected_type = $file_type;
						
						$answer = array();
						if( $file_type === 'image' ){
							if( !function_exists('create_thumbnail_image') ){
								require $C->INCPATH.'helpers/func_images.php';
							}
							create_thumbnail_image($tempfile);
						}
						$org_file = $C->STORAGE_TMP_URL.$tempfile;

						$attachment_url = $C->STORAGE_TMP_URL.'thumb_'.$tempfile;
						
						$this->db2->query('INSERT INTO `event_attachemnts` (event_id, user_id, attachment_type, filename, file_size, file_type, link, thumb_link) 
									VALUES ("'.$id.'","'.$this->user->id.'", "Admin", "'.$file->name[$k].'", "'.$data->filesize.'","'.$file_type.'","'.$org_file.'","'.$attachment_url.'")');
						$k++;
					}
				}
			}
			if(!empty($page->params->group)){
				$page->redirect($C->SITE_URL . $user->info->username.'/tab:events/group:'.$page->params->group);	
			}else{
				if(!empty($page->params->username))
					$page->redirect($C->SITE_URL . $user->info->username.'/tab:events/username:'.$page->params->username);	
				else
					$page->redirect($C->SITE_URL . $user->info->username.'/tab:events');
			}
		}
	}

	

		if(!empty($page->params->username)){
			$add_event_new = '<h1>Create Event</h1>';
		}else{
			$add_event_new = '<h1 style="margin:10px">Create Event</h1><div class="break"></div>';
		}

		$display_type = !empty($page->params->group)?'group':'community';
		$start = !empty($page->params->start)?date('m/d/Y', $page->params->start):(isset($_POST['start_date'])?$_POST['start_date']:'');
		$end = !empty($page->params->end)?date('m/d/Y', $page->params->end):(isset($_POST['end_date'])?$_POST['end_date']:'');
		//$group_id = ($page->params->group)?$page->params->group:'';

		ob_start();
		require_once($C->PLUGINS_DIR.'events/static/templates/blocks/add_event_new.php');
		$add_event_new .= ob_get_clean();
		echo $add_event_new;

		//$this->setVar( 'main_content', $add_event_new, 'replace');
			
	

	$_SESSION['submitted'] = 0;

//TEMPLATE CODE END
function roles_list($group_id)
{
	$output="<table id='myTable' width='100%'><th>Role</th><th>Description</th><th></th>";
	$output.="</table>";
	return $output;
}

function cutString($pmStr, $pmPosition = 15)
{
	if(!trim($pmStr)) return false;
	return (strlen($pmStr) > (int)$pmPosition) ? substr($pmStr, 0, (int)$pmPosition).".." : trim($pmStr);
}		

function role_user_select($group_id)
{

	global $db2,$C;
	$res 	= $db2->query("select user_id,username from groups_followed as grp
							inner join users as usr on usr.id=grp.user_id
							where grp.group_id=$group_id");

	$output ="<select multiple='multiple' id='my-select' name='users[]'>";
	if($db2->num_rows($res) > 0)
	{
		while($obj = $db2->fetch_object($res))
		{
			$output.="<option value='".$obj->username."'>".$obj->username."</option>";
		}
	}
	$output.="</select>";
	return $output;
}

	function role_select($group_id)
	{
		global $db2,$C;
		$output ="<select id='role_name_new' name='role_name_new' >";
		$output.="</select>";
		return $output;
	}

	function roles_resource_list($group_id)
	{
		$output="<table id='role_resource' width='100%'><th>Username</th><th>Role</th><th></th>";
		$output.="</table>";
		return $output;
	}	
?>