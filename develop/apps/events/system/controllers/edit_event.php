<?php

	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	
	if(!empty($this->params->group)){
		$g	= $this->network->get_group_by_id($this->params->group);
		if( ! $g ) {
			$this->redirect('dashboard');
		}
		if( $g->is_private && !$this->user->is_logged ) {
			$this->redirect('home');
		}

		if( $g->is_private && !$this->user->info->is_network_admin ) {
			$u	= $this->network->user_admin_group_ids($this->user->id);
			if( !$u || $u[$g->id] != 1 ) {
				$this->redirect('dashboard');
			}
		}
	}
		
	require $C->INCPATH.'helpers/func_additional.php';		
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/dashboard.php');
	$this->load_langfile('inside/group.php');
	$this->load_langfile('inside/dashboard.php');
	$this->load_langfile('inside/groups_new.php');
	$this->load_langfile('inside/admin.php');
		$this->load_langfile('inside/user.php');

	$submit = FALSE;
	$error = FALSE;
	$errmsg = '';
	$event_src = $db2->query('SELECT events.*, users.is_network_admin FROM ( events, users ) WHERE events.id="'.(int)$this->params->event.'" AND users.id = "'.(int)$this->user->id.'" LIMIT 1');
	$event = $db2->fetch_object($event_src);

	if( empty($event->admin_id) ){
		$this->redirect('signin');
	}
	if( $this->user->id != $event->admin_id && empty($event->is_network_admin) ){
		$this->redirect('dashboard');
	}

	if( isset($_POST['title']) ) {
	
		$group_id = !empty($_POST['group_id'])?$db2->escape($_POST['group_id']):'0';
		$location = '';
		$address = $db2->escape($_POST['address']);
		$event_type = $db2->escape($_POST['event_type']);
		$display_type = $db2->escape($_POST['display_type']);
		$title = $db2->escape($_POST['title']);
		$description = $db2->escape($_POST['description']);
		$start_date = date('Y-m-d',strtotime($_POST['start_date']));
		$end_date = date('Y-m-d',strtotime($_POST['end_date']));
		$start_time = date('H:i:s',strtotime($_POST['start_time']));
		$end_time = date('H:i:s',strtotime($_POST['end_time']));
		$private = date('H:i:s',strtotime($_POST['is_private']));
		
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

		/*if (strlen($location) == 0) {
			$errmsg.="Please enter event Location. <br />";
			$error = TRUE;
		}

		if (strlen($address) == 0) {
			$errmsg.="Please enter event address. <br />";
			$error = TRUE;
		}

		if (strlen($description) == 0){
			$errmsg.="Please enter description. <br />";
			$error = TRUE;
		}*/

				if(!empty($_FILES['attachment']['name'][0])){
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
		}
		
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
				is_private= "'.$private.'" WHERE id='.$this->params->event 
			);		
			if(!empty($event->id)){
				$group_id    = !empty($_POST['group_id'])?$this->db2->escape($_POST['group_id']):'0';
				$id= $event->id;
				$date_time = date('M d, Y h:i A', strtotime($start_date.' '.$start_time));
				$content_title = '<div class="title"><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/event.png"> <a href="'.$C->SITE_URL.'plugin/events/view/id:'.$id.'">'.$title.'</a></div>';
				$content = '<p class="address_view">
								<span>'.$address.'</span>
							</p>				
					<span class="time">'.$date_time.'</span>
				';
				//$left = ($this->params->group)?(strlen($this->user->info->username)+strlen($g->title)+8):(strlen($this->user->info->username)+4);
				$answer = (object)array(
						'link'=>$C->SITE_URL."plugin/events/view/id:".$id, 
						'title'=>$content_title,
						'description' =>$content,
						'hits'=> 'link'
					);
				$post_event = FALSE;
				/* Update exisisting activity feeds */
				$res = $db2->query('SELECT posts.id FROM posts LEFT JOIN event_posts ON (event_posts.post_id = posts.id) WHERE event_posts.event_id="'.$id.'" AND posts.user_id="'.$this->user->id.'"'); 
				if($db2->num_rows($res) > 0)
				{
					$post_event = TRUE;
					while($obj = $db2->fetch_object($res))
					{
						$pid  = $obj->id;
						$db2->query('UPDATE posts_attachments SET type="link",data="'.$db2->escape(serialize($answer)).'" WHERE post_id="'.$pid.'"');
					}
				}
				
				if($publish_now == 1){

					if( $this->user->id > 0 ) {
						$db2->query('UPDATE users SET num_posts=num_posts+1, lastpost_date="'.time().'" WHERE id="'.$this->user->id.'" LIMIT 1');
						$db2->query('INSERT INTO '.($is_private?'posts_pr_comments_watch':'posts_comments_watch').' SET user_id="'.$this->user->id.'", post_id="'.$id.'", newcomments=0');
					}				
					if(!$post_event){
						$db2->query('INSERT INTO posts SET user_id="'.$this->user->id.'", group_id="'.$group_id.'", date="'.time().'", date_lastcomment="'.time().'", attached="1"'); 
						$pid = $db2->insert_id();

						$db2->query('INSERT INTO event_posts SET event_id="'.$id.'", post_id="'.$pid.'", created = "'.date('Y-m-d H:i:s').'"');
						
						$db2->query('INSERT INTO post_userbox SET user_id="'.$this->user->id.'", post_id="'.$pid.'"');
						$db2->query('INSERT INTO posts_attachments SET post_id="'.$pid.'", type="link",data="'.$db2->escape(serialize($answer)).'"');
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
						$subject = 'Event Role Added in your Group : '.ucwords($title);
						$message_html .= ucwords($title).' has been added to your group';
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
					$this->redirect($C->SITE_URL . $user->info->username.'/tab:events/group:'.$group_id);	
				}else{
					if(!empty($this->params->username))
						$this->redirect($C->SITE_URL . $user->info->username.'/tab:events/action:list_events');	
					else
						$this->redirect($C->SITE_URL . $user->info->username.'/tab:events');
				}
			}else{
				$this->redirect($C->SITE_URL . $user->info->username.'/tab:events/action:list_events');	
			}
		}
	}
		if(empty($event->group_id)){
			if($this->params->user == $user->id && $this->param('username')){
				
				$u = $this->network->get_user_by_id(intval($this->params->user));
				if( !$u ){
					$this->redirect('dashboard');
				}
				
				$is_my_profile	= ($this->user->is_logged && $u->id==$this->user->id);
				$he_follows 	= $this->network->get_user_follows($u->id, TRUE, 'hefollows')->follow_users;
				if( $this->user->is_logged ){
					$i_follow 	= ( !$is_my_profile )? $this->network->get_user_follows($this->user->id, FALSE, 'hefollows')->follow_users : $he_follows;
				}else{
					$i_follow 	= array();
				}
				
				$i_follow = array_keys($i_follow);

				$is_admin_or_follows_me = ( $this->user->is_logged && $this->user->info->is_network_admin || isset( $he_follows[$this->user->id] ) );
				$is_profile_protected = ( $u->is_profile_protected && !$is_admin_or_follows_me && !$is_my_profile);
				$is_posts_protected = ( $u->is_posts_protected && !$is_admin_or_follows_me && !$is_my_profile);

				$tab = '';
				if( $this->param('tab') ){
					$tab = $this->param('tab');
				}
				
				$subtab = 'all';
				if( $this->param('subtab') ){
					$subtab = $this->param('subtab');
				}
				
				if($tab =='friends' && $subtab != 'ifollow' && $subtab !='followers' && $subtab !='incommon'){
					$subtab = 'ifollow';
				}
				
				$paging_url	= $C->SITE_URL.$u->username.'/tab:'.$tab.'/subtab:'.$subtab.'/pg:';
				
				$udtls = $this->network->get_user_details_by_id(intval($this->params->user));
				$udtls = ($udtls === FALSE || empty($udtls))? array() : $udtls;

				//TEMPLATE START 
				$tpl = new template( array('page_title' => $u->username. ' - ' .$C->SITE_TITLE, 'header_page_layout'=>'sc') );
				
				$tpl->initRoutine('UserLeftColumn', array( &$u, &$he_follows ));
				$tpl->routine->load();

				$menu = array( 	array('url' => $u->username.'/tab:updates', 	'css_class' => (($tab === 'updates')? ' selected' : ''), 	'title' => $this->lang('usr_tab_updates') ),
				);
				
				if( !$is_profile_protected ){
					$menu[] = array('url' => $u->username.'/tab:info', 		'css_class' => (($tab === 'info')? ' selected' : ''), 		'title' => $this->lang('usr_tab_info') );
					$menu[] = array('url' => $u->username.'/tab:friends', 	'css_class' => (($tab === 'friends')? ' selected' : ''), 	'title' => $this->lang('usr_tab_coleagues') );
					$menu[] = array('url' => $u->username.'/tab:groups', 	'css_class' => (($tab === 'groups')? ' selected' : ''), 	'title' => $this->lang('usr_tab_groups') );
					$menu[] = array('url' => 'plugin/events/home/username:'.$u->username, 	'css_class' => (($this->params->username)? ' selected' : ''), 	'title' => 'Events' );
				}
				
				$tpl->layout->setVar( 'subheader_placeholder', $tpl->designer->createMenu( 'navigation', $menu ) ); unset($menu);

				$tpl->layout->useBlock('user-header-info');
				$tpl->layout->block->setVar('user_header_username', getThisUserCommunityName($u));	
				$tpl->layout->block->setVar('user_header_position', htmlspecialchars($u->location)); //should be position
				$tpl->layout->block->setVar('user_header_activity', $this->lang('usr_top_activity_count', array('#NUM_FOLLOWERS#'=>$u->num_followers, '#NUM_FOLLOWING#'=>count($he_follows), '#NUM_POSTS#'=>$u->num_posts )));

				if( $this->user->is_logged ){
					$tpl->layout->block->setVar('user_header_follow_button', $is_my_profile? '' : 
												(!in_array($u->id, $i_follow)? $tpl->designer->usersSettingsMenu($u->id, true) : $tpl->designer->usersSettingsMenu($u->id, false)) 
					);
				}
				
				$tpl->layout->block->save('main_content_top_placeholder', true);

			}else{		
				$tpl = new template( array('page_title' => $this->lang('dashboard_page_title', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );
				$tpl->initRoutine('DashboardLeftMenu', array());
				$tpl->routine->load();
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
			$if_can_invite =  $group->ifCanInvite();
			
			//TEMPLATE CODE START
			$tpl = new template( array('page_title' => $g->title. ' - ' .$C->SITE_TITLE, 'header_page_layout'=>'sc') );
			
			$invite_button = '';
			if( $if_can_invite ){
				$invite_button =
				'
				<div>
					<div class="options-container" align="center">
						<a href="' . $C->SITE_URL . $g->groupname .'/invite" class="action-btn user-action add" style="float:left; margin:0px;">
							<span class="tooltip">
								<span>'.$this->lang('group_left_invite_btn').'</span>
							</span>
						</a>
					</div>
					<div class="clear"></div>
				</div>';
			}
			
			$menu_items = array(
				array(
					'url'=> '#', 
					'text'=> $this->lang('grp_toplnks_unfollow'), 
					'data_attributes' => array(
							'role' => 'services', 
							'namespace' => 'groups',  
							'action' => 'leave', 
							'value' => $g->id
						)
					),
			);
			
			

			$tpl->layout->useBlock('group-header-info');
			$tpl->layout->block->setVar('group_header_username', $g->title);
			$tpl->layout->block->setVar('group_header_icon', ($g->is_public? 'public' : 'private')); //should be position
			$tpl->layout->block->setVar('group_header_activity', $this->lang('group_header_descr_activity', array('#NUM_MEMBERS#'=> $g->num_followers, '#NUM_POSTS#'=>$g->num_posts) ) );
			
			if(!empty($event->group_id)){
				if( $user->is_logged ){

					if( $this->user->is_logged ){
						if($i_am_member == true ){ 
							$tpl->layout->block->setVar(
									'group_header_settings_button', 
									$tpl->designer->dropDownMenu("Settings", $menu_items, '', 'action-btn options', true)
							); 
						} else {
							$tpl->layout->block->setVar(
									'group_header_settings_button',
									'<a class="action-btn user-action add" data-action="join" data-value="'.$g->id.'" data-namespace="groups" data-role="services"><span class="tooltip"><span>'.$this->lang('grp_toplnks_follow').'</span></a>'
							);
						}
						unset($menu_items);
					} 

					$tpl->layout->block->save('main_content_top_placeholder', true);		
				}
				$tpl->layout->setVar( 'left_content', 	
					
						$tpl->designer->createInfoBlock('',
								'<img src="'.$C->STORAGE_URL.'avatars/'. (empty($g->avatar)? $C->DEF_AVATAR_GROUP : $g->avatar).'" alt="'.$g->groupname.'">'.
								'<div class="group-description">'.$g->about_me.'</div>'.
								'<div class="group-statistics">
									<strong>'.$g->num_followers.'</strong> '.$this->lang('grp_tab_members').' <br />'.
									'<strong>'.$g->num_posts.'</strong> '.$this->lang('usrlist_numposts').'
								</div>'.
								'<div class="recent-visitors">'.
									'<h3 class="sub-title">'.$this->lang('group_latest_members').'</h3>'.
									$tpl->designer->createUserLinks( $group->getGroupMembers($group_members), 'thumbs3' ).
									$invite_button .
									'<div class="clear"></div>
								</div>
								'
						)													
				);
				$tab='';
				$menu = array( 	array('url' => $g->groupname.'/tab:updates', 	'css_class' => (($tab === 'updates')? ' selected' : ''), 	'title' => $this->lang('grp_tab_updates') ),
								array('url' => $g->groupname.'/tab:members', 	'css_class' => (($tab === 'members')? ' selected' : ''), 	'title' => $this->lang('grp_tab_members') ),
				);
				
				$tpl->layout->setVar( 'subheader_placeholder', $tpl->designer->createMenu( 'navigation', $menu, 'group_navigation_top_menu' ) ); 
				unset($menu);		
				$tpl->layout->setVar( 'main_content_placeholder', $tpl->designer->createMenu('tabs-navigation', $menu, 'groups_top_tab_menu') );
				
			}		
		}	
	if( ($submit && !$error) || $this->param('msg') == 'grpsaved' ){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->okMessage($this->lang('admtrms_ok_ttl'), $this->lang('admadm_frm_ok_txt') ) );
	}else if( $submit && $error ){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->errorMessage('Error', $errmsg) );
	}
	
	

	if(!empty($event->group_id)){
		
		$tpl->layout->useBlock('edit_event', 'events');
		$g	= $this->network->get_group_by_id(intval($event->group_id), true);


		$tpl->layout->block->setVar('event_title', 'Edit Event ');	
				
		
		$display_type = !empty($event->group_id)?'group':'community';
		$event_type = 'normal' ;
		$group_id = ($event->group_id)?$event->group_id:'';
		$tpl->layout->block->setVar('eventtitle', $event->event_name);	
		$tpl->layout->block->setVar('display_type', $display_type);	
		$tpl->layout->block->setVar('start_date', date('M d, Y', strtotime($event->start_date)));	
		$tpl->layout->block->setVar('start_time', date('h:i A', strtotime($event->start_time)));	
		$tpl->layout->block->setVar('end_date', date('M d, Y', strtotime($event->end_date)));	
		$tpl->layout->block->setVar('end_time', date('h:i A', strtotime($event->end_time)));	
		$tpl->layout->block->setVar('location', $event->location);	
		$tpl->layout->block->setVar('address', $event->address);	
		$tpl->layout->block->setVar('description', $event->event_description);	
		$tpl->layout->block->setVar('event_type', $event->event_type);	
		if($event->event_type=='Major')
			$eventtype='<select id="event_type" name="event_type" ><option value="normal">Regular</option><option selected=selected value="Major">Important</option></select>';
		else	
			$eventtype='<select id="event_type" name="event_type" ><option selected=selected  value="normal">Regular</option><option value="Major">Important</option></select>';
		
		$tpl->layout->block->setVar('eventtype', $eventtype);	
		$tpl->layout->block->setVar('roles_list', roles_list($event->group_id, $event->id));

		$pub_now = ($event->publish_now==1)?'checked':'';
		$publish_date = ($event->publish_date==1)?'checked':'';
		$activity_pub_date = ($event->activity_pub_date!='0000-00-00 00:00:00')?date('M d, Y', strtotime($event->activity_pub_date)):date('M d, Y');
		$activity_pub_time = ($event->activity_pub_date!='0000-00-00 00:00:00')?date('h:i A', strtotime($event->activity_pub_date)):date('h:i A');
		$disabledval = ($event->publish_date==1)?'':'disabled';
		$tpl->layout->block->setVar('disabledval', $disabledval);	
		$str = '<input type="checkbox" name="publish_now" '.$pub_now.' id="publish_now" value="1"><label class="js-publish">Publish Now </label><br />';
		$str .= '<input type="checkbox" name="publish_date" '.$publish_date.' id="publish_date" value="1"><label class="js-publish">Publish on date</label>';
		$tpl->layout->block->setVar('pub_select_day', $activity_pub_date);	
		$tpl->layout->block->setVar('pub_time', $activity_pub_time);	
		$tpl->layout->block->setVar('publish_now', $str);	
		
		$tpl->layout->block->setVar('group_id', $event->group_id);
		
		$tpl->layout->block->setVar('role_select', role_select($event->group_id, $event->id));
		$tpl->layout->block->setVar('role_select_user', role_user_select($event->group_id, $event->id));
		$tpl->layout->block->setVar('roles_resouce_list', roles_resource_list($event->group_id, $event->id));		
	
	}else{
		$tpl->layout->useBlock('edit_community', 'events');
		
		if(!empty($this->params->username))
			$tpl->layout->setVar('main_content_placeholder', '<h1>Edit Event</h1>');
		else
			$tpl->layout->setVar('main_content_top_placeholder', '<h1 style="margin:10px">Edit Event</h1><div class="break"></div>');
		
		$display_type = !empty($event->group_id)?'group':'community';
		$event_type = 'normal' ;
		$group_id = ($event->group_id)?$event->group_id:'';
		$tpl->layout->block->setVar('eventtitle', $event->event_name);	
		$tpl->layout->block->setVar('display_type', $display_type);	
		$tpl->layout->block->setVar('start_date', date('M d, Y', strtotime($event->start_date)));	
		$tpl->layout->block->setVar('start_time', date('h:i A', strtotime($event->start_time)));	
		$tpl->layout->block->setVar('end_date', date('M d, Y', strtotime($event->end_date)));	
		$tpl->layout->block->setVar('end_time', date('h:i A', strtotime($event->end_time)));	
		$tpl->layout->block->setVar('location', $event->location);	
		$tpl->layout->block->setVar('address', $event->address);	
		
		
		$pub_now = ($event->publish_now==1)?'checked':'';
		$publish_date = ($event->publish_date==1)?'checked':'';
		$activity_pub_date = ($event->activity_pub_date!='0000-00-00 00:00:00')?date('M d, Y', strtotime($event->activity_pub_date)):date('M d, Y');
		$activity_pub_time = ($event->activity_pub_date!='0000-00-00 00:00:00')?date('h:i A', strtotime($event->activity_pub_date)):date('h:i A');
		$disabledval = ($event->publish_date==1)?'':'disabled';
		$tpl->layout->block->setVar('disabledval', $disabledval);	
		$str = '<input type="checkbox" name="publish_now" '.$pub_now.' id="publish_now" value="1"><label class="js-publish">Publish Now </label><br />';
		$str .= '<input type="checkbox" name="publish_date" '.$publish_date.' id="publish_date" value="1"><label class="js-publish">Publish on date</label>';
		$tpl->layout->block->setVar('pub_select_day', $activity_pub_date);	
		$tpl->layout->block->setVar('pub_time', $activity_pub_time);	
		$tpl->layout->block->setVar('publish_now', $str);	
		
		$tpl->layout->block->setVar('description', $event->event_description);	
		$tpl->layout->block->setVar('event_type', $event->event_type);	
		if($event->event_type=='Major')
			$eventtype='<select id="event_type" name="event_type" ><option value="normal" >normal</option><option value="Major"  selected=selected >Major</option></select>';
		else	
			$eventtype='<select id="event_type" name="event_type" ><option value="normal"   selected=selected >normal</option><option value="Major">Major</option></select>';
		
		$tpl->layout->block->setVar('eventtype', $eventtype);	
		$tpl->layout->block->setVar('roles_list', roles_list($event->group_id, $event->id));
		
		$tpl->layout->block->setVar('group_id', $event->group_id);
		}
		
		//attachement list
		$data_attach = '<div>';
		$evt_attach= $db2->query('SELECT * FROM event_attachemnts WHERE event_id="'.$event->id.'"');
		while ($attchement = $db2->fetch_object($evt_attach)) {
			$window_pop = "MyWindow_new123=window.open('$attchement->link','MyWindowz','width=600,height=500'); return false;";
			$data_attach.='<div> <a href="'.$attchement->link.'" style="list-style:none; cursor:pointer; color:blue; float:left;" onclick="'.$window_pop.'" >'.$attchement->filename.'</li> <a class="event_attach_remove" rel="'.$attchement->id.'" href="'. $C->SITE_URL .'plugin/events/remove_event/attach_id:'.$attchement->id.'">&nbsp;&nbsp;&nbsp;&nbsp;Remove</a></div> <br />';
		}
		$data_attach .= '</div>';
		$tpl->layout->block->setVar('attchmentlist', $data_attach);	

	$tpl->layout->block->save( 'main_content', true );	

	$tpl->display();
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
		//$output="<table id='myTable'  width='100%'><th>S.No</th><th>Role Name</th><th>Role Description</th><th>Status</th><th>Created</th><th>Action</th>";
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
				//$output.="<tr><td>$i</td><td>$obj->role_name</td><td>$desc</td><td>$status</td><td>$created</td><td><a href='".$url."' onclick='return confirm(\"Are you sure want to delete?\");' title='Delete'>$delete</a></td></tr>";
				$output.="<tr><td>$obj->role_name</td><td>$desc</td><td><a href='".$url."' onclick='return confirm(\"Are you sure want to delete?\");' title='Delete'>$delete</a></td></tr>";
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
									WHERE err.group_id="'.$group_id.'" AND er.event_id="'.$event_id.'"  order by err.id desc;');
		//$output="<table id='role_resource'  width='100%'><th>S.No</th><th>Username</th><th>Role Name</th><th>Created</th><th>Action</th>";
		$output="<table id='role_resource'  width='100%'><th>Username</th><th>Role</th><th></th>";
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