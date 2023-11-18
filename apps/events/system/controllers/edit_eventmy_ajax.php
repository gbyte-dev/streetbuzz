<?php

	

	$submit = FALSE;
	$error = FALSE;
	$errmsg = '';
	$event_src = $db2->query('SELECT events.*, users.is_network_admin FROM ( events, users ) WHERE events.id="'.(int)$page->event.'" AND users.id = "'.(int)$this->user->id.'" LIMIT 1');
	$event = $db2->fetch_object($event_src);

	

	if( isset($_POST['title']) ) {
		print_r($_POST);exit;
	
		$group_id = !empty($_POST['group_id'])?$db2->escape($_POST['group_id']):'0';
		$location = '';
		$address = $db2->escape($_POST['address']);
		$event_type = 'Normal';
		$display_type = $db2->escape($_POST['display_type']);
		$title = $db2->escape($_POST['title']);
		$description = $db2->escape($_POST['description']);
		$start_date = date('Y-m-d',strtotime($_POST['start_date']));
		$end_date = date('Y-m-d',strtotime($_POST['end_date']));
		$start_time = date('H:i:s',strtotime($_POST['start_time']));
		$end_time = date('H:i:s',strtotime($_POST['end_time']));
		$private = date('H:i:s',strtotime($_POST['is_private']));
		$street_group = $db2->escape($_POST['street_group']);
		$street_user = $db2->escape($_POST['street_user']);
		$url = $db2->escape($_POST['url']);
		$hastag = $db2->escape($_POST['hastag']);
		$hastagarr        =explode("#",trim($hastag));
		$strret_arr       =array_filter($hastagarr);
		$street_count     =count($strret_arr);
		$content='';
		$con ='';
		$publish_now = 1;
		foreach($strret_arr as $keys=>$vals){
							if($keys ==1){
								$con .='<span><a href="'.$C->SITE_URL.'/search/tab:tags/s:'.$vals.'"><strong>#'.$vals.'</strong></a>';
							}else{
								$con .='<strong>#'.$vals.'</strong></span>';
							}
							
						}
		
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

		/*if(!empty($_FILES['attachment']['name'][0])){
			$k=0;
			foreach($_FILES['attachment']['name'] as $file){
				$maxsize    = 2097152;
				$allowedExts = array("pdf", "gif", "jpeg", "jpg", "png", 'docx', 'doc', 'pptx', 'ppt', 'xls', 'xlsx');
				$temp = explode(".", $file);
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
			}
		}*/
		
		if(!empty($event->group_id)){
			$g	= $this->network->get_group_by_id(intval($event->group_id), true);
		}
	

		$submit = TRUE;
		if($error === FALSE){
			$publish_now = '';
			$publish_date = '';
			$is_private = '0';
				$pub_select_day = '0000-00-00 00:00:00';
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
			$publish_now = 1;
			$evdata = $db2->query('SELECT address,start_date,start_time,end_date,end_time  FROM events WHERE id="'.$event->id.'" order by id desc LIMIT 1');
			$evendata = $this->db2->fetch_object($evdata);
			
			
		
			$event_save = $this->db2->query('UPDATE `events`  SET 
				modified_at=now(),
				location= "'.$location.'",
				group_id= "'.$group_id.'",
				address= "'.$address.'",
				event_type= "'.$event_type.'",
				display_type= "'.$display_type.'",
				event_name= "'.$title.'",
				event_description= "'.$description.'",
				start_date= "'.$start_date.'",
				start_time= "'.$start_time.'",
				activity_pub_date= "'.$pub_select_day.'",
				publish_now= "'.$publish_now.'",
				publish_date= "'.$publish_date.'",
				end_date= "'.$end_date.'",
				end_time= "'.$end_time.'",
				status= "1",
				street_group="'.$street_group.'",
				street_user="'.$street_user.'",
				tag_name="'.$hastag.'",
				url="'.$url.'",
				is_private= "'.$private.'" WHERE id='.$page->params->event 
			);		
			if(!empty($event->id)){
				$group_id    = !empty($_POST['group_id'])?$this->db2->escape($_POST['group_id']):'0';
				$id= $event->id;
				$date_time = date('M d, Y h:i A', strtotime($start_date.' '.$start_time));
				
				$post_event = FALSE;
				/* Update exisisting activity feeds */
				/*$res = $db2->query('SELECT posts.id FROM posts LEFT JOIN event_posts ON (event_posts.post_id = posts.id) WHERE event_posts.event_id="'.$id.'" AND posts.user_id="'.$this->user->id.'"'); 
				if($db2->num_rows($res) > 0)
				{
					$post_event = TRUE;
					while($obj = $db2->fetch_object($res))
					{
						$pid  = $obj->id;
						$db2->query('UPDATE posts_attachments SET type="link",data="'.$db2->escape(serialize($answer)).'" WHERE post_id="'.$pid.'"');
					}
				}*/
				$evebtdata = $db2->query('SELECT post_id  FROM event_posts WHERE event_id="'.$event->id.'" order by id desc LIMIT 1');
				
				while($ev = $this->db2->fetch_object($evebtdata)){
					$postidold[]      =$ev->post_id;

				}
				$userdatav = $db2->query('SELECT pu.user_id,pu.post_id,pu.status FROM event_posts as ep
				inner join post_userbox as pu ON ep.post_id = pu.post_id		

				WHERE ep.event_id="'.$event->id.'" AND ep.post_id="'.$postidold[0].'" ');
				$userdatavstatus = $db2->query('SELECT pu.user_id,pu.post_id FROM event_posts as ep
				inner join post_userbox as pu ON ep.post_id = pu.post_id		

				WHERE ep.event_id="'.$event->id.'" AND ep.post_id="'.$postidold[0].'" AND status=1 ');

				
				if($publish_now == 1){
					if( $this->user->id > 0 ) {
						$db2->query('UPDATE users SET num_posts=num_posts+1, lastpost_date="'.time().'" WHERE id="'.$this->user->id.'" LIMIT 1');
						$db2->query('INSERT INTO '.($is_private?'posts_pr_comments_watch':'posts_comments_watch').' SET user_id="'.$this->user->id.'", post_id="'.$id.'", newcomments=0');
						$db2->query('INSERT INTO posts SET user_id="'.$this->user->id.'",posttags="'.$street_count.'", group_id="'.$group_id.'", date="'.time().'", date_lastcomment="'.time().'", attached="1"');
                        $pid = $db2->insert_id();
						$db2->query('UPDATE event_posts SET edit_status=4  WHERE post_id="'.$postidold[0].'" AND event_id="'.$id.'" ');
                       
					    $db2->query('INSERT INTO event_posts SET event_id="'.$id.'", post_id="'.$pid.'", created = "'.date('Y-m-d H:i:s').'"');
						$content_title = '<div class="title"><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/event.png"> <a href="'.$C->SITE_URL.'plugin/events/view/id:'.$id.'/postid:'.$pid.'"><strong>Event Name:</strong> '.$title.'</a></div>';
						$content='';
						$content .= '<p class="address_view">
										<span><strong>Location:</strong> '.$address.'</span>
									</p>
									<p class="address_view">
										<span><strong>URL:</strong> <a href="'.$url.'"  target="_blank">'.$url.'</a></span>
									</p>								
									<span class="time"><strong>Date and Time:</strong> '.$date_time.'</span>
									<span><stong>Hash Tags:</strong>'.$con.'</span>';
				//$left = ($page->params->group)?(strlen($this->user->info->username)+strlen($g->title)+8):(strlen($this->user->info->username)+4);
				$answer = (object)array(
						'link'=>$C->SITE_URL . $user->info->username.'/tab:events/action:view/id:'.$id, 
						'title'=>$content_title,
						'description' =>$content,
						'hits'=> 'link'
					);
						$db2->query('INSERT INTO posts_attachments SET post_id="'.$pid.'", type="link",data="'.$db2->escape(serialize($answer)).'"');
						while($o = $this->db2->fetch_object($userdatav)) {
							$db2->query('UPDATE post_userbox SET event_status=4  WHERE post_id="'.$postidold[0].'" AND user_id="'.$o->user_id.'" ');
							if($evendata->address != $address || $evendata->start_date != $start_date ||  $evendata->start_time != $start_time ||  $evendata->end_date != $end_date || $evendata->end_time != $end_time  ){
								$db2->query('INSERT INTO post_userbox SET user_id="'.$o->user_id.'", post_id="'.$pid.'",status="'.$o->status.'" ');

							}else{
								$db2->query('INSERT INTO post_userbox SET user_id="'.$o->user_id.'", post_id="'.$pid.'",status="'.$o->status.'",event_status=5 ');

				
							}
							
							
                        }
						while($o = $this->db2->fetch_object($userdatavstatus)) {
							if($o->user_id != $this->user->id ){
							$db2->query('INSERT INTO users_dashboard_tabs SET user_id="'.$o->user_id.'", tab="notifications",state=1,newposts=1  ');
							$db2->query('INSERT INTO notifications SET to_user_id="'.$o->user_id.'",notif_type="event",in_group_id="'.$group_id.'",from_user_id="'.$this->user->id.'", notif_object_type="user",date="'.time().'",post_id="'.$pid.'"  ');

							}

						}
                        foreach($strret_arr as $keys=>$vals){
							$db2->query('INSERT INTO post_tags SET user_id="'.$this->user->id.'",tag_name="'.$vals.'",post_id="'.$pid.'",group_id="'.$group_id.'", date="'.time().'"'); 
                        }	
					
					}				
					if(!$post_event){
						
					}
				}		
				//attachment
				
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
									VALUES ("'.$event->id.'","'.$this->user->id.'", "Admin", "'.$file->name[$k].'", "'.$data->filesize.'","'.$file_type.'","'.$org_file.'","'.$attachment_url.'")');
						$k++;
					}
				}
				//insert roles and role resoureces
				$group_id    = !empty($event->group_id)?$event->group_id:'0';
				if(!empty($_POST['role_name'])){
					$i=0;
					foreach($_POST['role_name'] as $role_name){
						$this->db2->query('INSERT INTO `event_roles` (role_name, role_desc, group_id, event_id, created_at) 
						VALUES ("'.$role_name.'","'.$_POST['role_description'][$i++].'", "'.$group_id.'","'.$event->id.'", "'.date('Y-m-d H:i:s').'")');
						$roles[$role_name] = $db2->insert_id();
					}
				}
				
				$additional_group_info = '';
				if( !empty($group_id) ){
					$group_details = $this->network->get_group_by_id(intval($group_id), true);
					$additional_group_info = empty( $group_details->title ) ? '' : ' : '.$group_details->title;
				}
				
				$j=0;
				if(!empty($_POST['username'])){
					foreach($_POST['username'] as $username){
						$user_name = $this->network->get_user_by_username($username);
						if(empty($roles[$_POST['role_id'][$j]])){
							$event_role_src = $db2->query('SELECT * FROM event_roles WHERE event_id = "'.$event->id.'" and role_name="'.$_POST['role_id'][$j].'" LIMIT 1');
							$event_role = $db2->fetch_object($event_role_src);
							$role_id = $event_role->id;
						}else{
							$role_id = $roles[$_POST['role_id'][$j]];
						}
						$this->db2->query('INSERT INTO `event_role_resource` (user_id, group_id, role_id, created_at) 	VALUES ("'.$user_name->id.'","'.$group_id.'", "'.$role_id.'", "'.date('Y-m-d H:i:s').'")');
						
						//sending the messages
						$subject = 'Event Role Added to your Group'.$additional_group_info;
						$message_html = '';
						$message_html .= ucwords($title).' has been added to your group'.$additional_group_info;
						$message_html .= '<br /><b>Event Name: </b>'.ucwords($title);
						$message_html .= '<br /><b>When: </b>'.$start_date.' '.$start_time;
						$message_html .= '<br /><b>Address: </b>'.$location.' '.$address;
						$message_html .= '<br /><b>Your Role: </b>'.$_POST['role_id'][$j];
						$message_html .= '<br /> --------------------------------------------------------<br />';
						$message_html .= 'This is an automatically generated email from an unattended inbox. <b>Please Do Not Reply</b>. Replies to this message will not be seen.';
						$message_html = '<br /> Hello '.$user_name->fullname.', <br />'.$message_html;
						do_send_mail_html($user_name->email, $subject, '', $message_html, '');
						$j++;
					}
				}
				if(!empty($event->group_id)){
					$page->redirect('plugin/events/home/group:'.$group_id);	
				}else{
					if(!empty($page->params->username))
						$page->redirect('plugin/events/home/tab:list_events/username:'.$page->params->username);	
					else
						$page->redirect('plugin/events/home');
				}
			}else{
				$page->redirect('plugin/events/home/tab:list_events');	
			}
		}
	}
	if(empty($event->group_id)){
		if($page->params->user == $user->id && $page->param('username')){
			
			$u = $this->network->get_user_by_id(intval($page->params->user));
			if( !$u ){
				$page->redirect('dashboard');
			}
			
			$udtls = $this->network->get_user_details_by_id(intval($page->params->user));
			$udtls = ($udtls === FALSE || empty($udtls))? array() : $udtls;


		}
	}else{
		$g	= $this->network->get_group_by_id(intval($event->group_id), true);
		
		$group_members 	= array_keys( $this->network->get_group_members($g->id) );
		
		$group = new group( $g );
		$group_sub_categories = $group->getGroupCategories(true);
		$sub_categories = array();
		foreach($group_sub_categories as $key => $value){
			$sub_categories[$value->id] = ucfirst(strtolower($value->title));
		}
		
		$i_am_member		= ($this->user->is_logged && in_array($this->user->id, $group_members))? TRUE : FALSE;
		$i_am_network_admin	= ( $this->user->is_logged && $this->user->info->is_network_admin > 0 );
		$i_am_admin			= $i_am_network_admin;

		if( !$i_am_network_admin ) {
			$i_am_admin	= $this->db2->fetch('SELECT id FROM groups_admins WHERE group_id="'.$g->id.'" AND user_id="'.$this->user->id.'" LIMIT 1') ? TRUE : FALSE;
		}

	
	}	
	
	if( ($submit && !$error) || $page->param('msg') == 'grpsaved' ){
		$this->setVar('main_content_placeholder', okMessage($page->lang('admtrms_ok_ttl'), $page->lang('admadm_frm_ok_txt') ) );
	}else if( $submit && $error ){
		$this->setVar('main_content_placeholder', errorMessage('Error', $errmsg) );
	}

	$start_date = date('M d, Y', strtotime($event->start_date));	
	$start_time = date('h:i A', strtotime($event->start_time));	
	$end_date	= date('M d, Y', strtotime($event->end_date));	
	$end_time	= date('h:i A', strtotime($event->end_time));	
	
	if($event->event_type=='Major'){
		$eventtype='<select id="event_type" name="event_type" ><option value="Normal">Regular</option><option selected=selected value="Major">Important</option></select>';
	}else{
		$eventtype='<select id="event_type" name="event_type" ><option selected=selected value="Normal">Regular</option><option value="Major">Important</option></select>';
	}

	if(!empty($event->group_id)){

		$display_type = !empty($event->group_id)?'group':'community';
		$event_type = 'Normal' ;
		$group_id = ($event->group_id)?$event->group_id:'';

		$roles_list = roles_list($event->group_id, $event->id);

		$pub_now = ($event->publish_now==1)?'checked':'';
		$publish_date = ($event->publish_date==1)?'checked':'';
		$activity_pub_date = ($event->activity_pub_date!='0000-00-00 00:00:00')?date('M d, Y', strtotime($event->activity_pub_date)):date('M d, Y');
		$activity_pub_time = ($event->activity_pub_date!='0000-00-00 00:00:00')?date('h:i A', strtotime($event->activity_pub_date)):date('h:i A');
		$disabledval = ($event->publish_date==1)?'':'disabled';
	
		$str = '<input type="checkbox" name="publish_now" '.$pub_now.' id="publish_now" value="1"><label class="js-publish">Publish Now </label><br />';
		$str .= '<input type="checkbox" name="publish_date" '.$publish_date.' id="publish_date" value="1"><label class="js-publish">Publish on date</label>';


		$role_select		= role_select($event->group_id, $event->id);
		$role_select_user	= role_user_select($event->group_id, $event->id);
		$roles_resouce_list	= roles_resource_list($event->group_id, $event->id);		

	}else{
		
		$display_type = !empty($event->group_id)?'group':'community';
		$event_type = 'Normal' ;
		$group_id = ($event->group_id)?$event->group_id:'';

		
		$pub_now = ($event->publish_now==1)?'checked':'';
		$publish_date = ($event->publish_date==1)?'checked':'';
		$activity_pub_date = ($event->activity_pub_date!='0000-00-00 00:00:00')?date('M d, Y', strtotime($event->activity_pub_date)):date('M d, Y');
		$activity_pub_time = ($event->activity_pub_date!='0000-00-00 00:00:00')?date('h:i A', strtotime($event->activity_pub_date)):date('h:i A');
		$disabledval = ($event->publish_date==1)?'':'disabled';
	
		$str = '<input type="checkbox" name="publish_now" '.$pub_now.' id="publish_now" value="1"><label class="js-publish">Publish Now </label><br />';
		$str .= '<input type="checkbox" name="publish_date" '.$publish_date.' id="publish_date" value="1"><label class="js-publish">Publish on date</label>';

		$roles_list = roles_list($event->group_id, $event->id);
		
		
	}
		
	//attachement list
	$data_attach = '<div>';
	$evt_attach= $db2->query('SELECT * FROM event_attachemnts WHERE event_id="'.$event->id.'"');
	while ($attchement = $db2->fetch_object($evt_attach)) {
		$window_pop = "MyWindow_new123=window.open('$attchement->link','MyWindowz','width=600,height=500'); return false;";
		$data_attach.='<div> <a href="'.$attchement->link.'" style="list-style:none; cursor:pointer; color:blue; float:left;" onclick="'.$window_pop.'" >'.$attchement->filename.'</li> <a class="event_attach_remove" rel="'.$attchement->id.'" href="'. $C->SITE_URL .'plugin/events/remove_event/attach_id:'.$attchement->id.'">&nbsp;&nbsp;&nbsp;&nbsp;Remove</a></div> <br />';
	}
	$data_attach .= '</div>';


	ob_start();
	require_once($C->PLUGINS_DIR.'events/static/templates/blocks/edit_eventmy_new_ajax.php');
	$edit_event_new = ob_get_clean();
	echo $edit_event_new;

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//funtions list
	function role_user_select($group_id, $event_id)
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
	
	
	function cutString($pmStr, $pmPosition = 15)
	{
		if(!trim($pmStr)) return false;
		return (strlen($pmStr) > (int)$pmPosition) ? substr($pmStr, 0, (int)$pmPosition).".." : trim($pmStr);
	}
	function roles_list($group_id, $event_id)
	{
		global $db2,$C;
		$role_result = $db2->query('SELECT * FROM event_roles WHERE group_id="'.$group_id.'" AND event_id="'.$event_id.'" order by id desc;');
		$output="<table id='myTable'  width='100%'><th>Role</th><th>Description</th><th></th>";
		if($db2->num_rows($role_result) > 0)
		{
			$i=1;
			while($obj = $db2->fetch_object($role_result))
			{
				if($obj->status==1)
					$status="Active";
				else
					$status="In-Active";
				$created = date("F j, Y", strtotime($obj->created_at));
				$desc	 = cutString($obj->role_desc,40);
				$delete	= "<img height='14' width='14' src='".$C->SITE_URL."apps/events/static/images/delete.png'>";
				$url	= $C->SITE_URL."plugin/events/roles/event:$obj->event_id/id:$obj->id/type:delete/group:$group_id";
				$output.="<tr><td>$obj->role_name</td><td>$desc</td>
							<td><a href='".$url."' onclick='return confirm(\"Are you sure want to delete?\");' title='Delete'>$delete</a></td></tr>";
				$i++;
			}
		}
		else { //$output.="<tr><td colspan='6'>No Roles Found!</td></tr>"; 
		}
		$output.="</table>";
		return $output;
	}
	function role_select($group_id, $event_id)
	{
		global $db2,$C;
		$res 	= $db2->query('SELECT * from event_roles where group_id="'.$group_id.'" AND event_id="'.$event_id.'" order by role_name ASC');
		$output ="<select id='role_name_new' name='role_name_new'>";
		if($db2->num_rows($res) > 0)
		{
			while($obj = $db2->fetch_object($res))
			{
				$output.="<option value='".$obj->role_name."'>".$obj->role_name."</option>";
			}
		}
		$output.="</select>";
		return $output;
	}

	function roles_resource_list($group_id, $event_id)
	{
		global $db2,$C;
		$role_result = $db2->query('SELECT err.id,err.user_id,err.created_at,er.role_name, usr.username FROM event_role_resource as err
									inner join event_roles as er on er.id=err.role_id
									inner join users as usr on usr.id=err.user_id
									WHERE err.group_id="'.$group_id.'" AND er.event_id="'.$event_id.'" order by err.id desc;');
		$output="<table id='role_resource' width='100%'><th>Username</th><th>Role</th><th></th>";
		if($db2->num_rows($role_result) > 0)
		{
			$i=1;
			while($obj = $db2->fetch_object($role_result))
			{
				$created = date("F j, Y", strtotime($obj->created_at));
				$delete	= "<img height='14' width='14' src='".$C->SITE_URL."apps/events/static/images/delete.png'>";
				$url	= $C->SITE_URL."plugin/events/resource/event:$event_id/id:$obj->id/type:delete/group:$group_id";
				$output.="<tr><td>$obj->username</td><td>$obj->role_name</td>
						 <td><a href='".$url."' onclick='return confirm(\"Are you sure want to delete?\");' title='Delete'>$delete</a></td>
						 </tr>";
				$i++;
			}
		}
		else { //$output.="<tr><td colspan='6'>No Roles Found!</td></tr>";
		}
		$output.="</table>";
		return $output;
	}		
?>