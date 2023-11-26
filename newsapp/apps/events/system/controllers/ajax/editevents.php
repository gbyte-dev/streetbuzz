<?php
	global $user, $db2, $C;

	if( isset($_POST['title']) ) {
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
		$event_id       =$_POST['event_id'];
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
			$error = FALSE;

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
			$evdata = $db2->query('SELECT address,start_date,start_time,end_date,end_time  FROM events WHERE id="'.$event_id.'" order by id desc LIMIT 1');
			$evendata = $db2->fetch_object($evdata);
			
		
			$event_save = $db2->query('UPDATE `events`  SET 
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
				is_private= "'.$private.'" WHERE id='.$event_id 
			);		
			if(!empty($event_id)){
				$group_id    = !empty($_POST['group_id'])?$db2->escape($_POST['group_id']):'0';
				$id= $event_id;
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
				$evebtdata = $db2->query('SELECT post_id  FROM event_posts WHERE event_id="'.$event_id.'" order by id desc LIMIT 1');
				
				while($ev = $db2->fetch_object($evebtdata)){
					$postidold[]      =$ev->post_id;

				}
				$userdatav = $db2->query('SELECT pu.user_id,pu.post_id,pu.status FROM event_posts as ep
				inner join post_userbox as pu ON ep.post_id = pu.post_id		

				WHERE ep.event_id="'.$event_id.'" AND ep.post_id="'.$postidold[0].'" ');
				

				
				if($publish_now == 1){
					if( $user->id > 0 ) {
						$db2->query('UPDATE users SET num_posts=num_posts+1, lastpost_date="'.time().'" WHERE id="'.$user->id.'" LIMIT 1');
						$db2->query('INSERT INTO '.($is_private?'posts_pr_comments_watch':'posts_comments_watch').' SET user_id="'.$user->id.'", post_id="'.$id.'", newcomments=0');
						$db2->query('INSERT INTO posts SET user_id="'.$user->id.'",posttags="'.$street_count.'", group_id="'.$group_id.'", date="'.time().'", date_lastcomment="'.time().'", attached="1"');
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
						while($o = $db2->fetch_object($userdatav)) {
							$db2->query('UPDATE post_userbox SET event_status=4  WHERE post_id="'.$postidold[0].'" AND user_id="'.$o->user_id.'" ');
							if($evendata->address != $address || $evendata->start_date != $start_date ||  $evendata->start_time != $start_time ||  $evendata->end_date != $end_date || $evendata->end_time != $end_time  ){
								
								$db2->query('INSERT INTO post_userbox SET user_id="'.$o->user_id.'", post_id="'.$pid.'",status="'.$o->status.'" ');

							}else{
								$db2->query('INSERT INTO post_userbox SET user_id="'.$o->user_id.'", post_id="'.$pid.'",status="'.$o->status.'",event_status=5 ');

				
							}
							
							
                        }
						

                        foreach($strret_arr as $keys=>$vals){
							$db2->query('INSERT INTO post_tags SET user_id="'.$user->id.'",tag_name="'.$vals.'",post_id="'.$pid.'",group_id="'.$group_id.'", date="'.time().'"'); 
                        }	
					
					}				
					if(!$post_event){
						
					}
				}	
/* 
				$group_id    = !empty($event->group_id)?$event->group_id:'0';
				if(!empty($_POST['role_name'])){
					$i=0;
					foreach($_POST['role_name'] as $role_name){
						$db2->query('INSERT INTO `event_roles` (role_name, role_desc, group_id, event_id, created_at) 
						VALUES ("'.$role_name.'","'.$_POST['role_description'][$i++].'", "'.$group_id.'","'.$event->id.'", "'.date('Y-m-d H:i:s').'")');
						$roles[$role_name] = $db2->insert_id();
					}
				}*/
								

				/*
				$additional_group_info = '';
				if( !empty($group_id) ){
					$group_details = $this->network->get_group_by_id(intval($group_id), true);
					$additional_group_info = empty( $group_details->title ) ? '' : ' : '.$group_details->title;
				}
				
				$j=0;*/
				/*if(!empty($_POST['username'])){
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
				}*/
				
			}else{
				//$page->redirect('plugin/events/home/tab:list_events');	
			}
		}
	}	

	
	?>