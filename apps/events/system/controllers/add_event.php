<?php	


	if(empty($this->params->group)){
		// redirect 
		$this->redirect($C->SITE_URL . $user->info->username.'/tab:events/action:add_event');
	}
	
	

	
	
	
	
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
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/dashboard.php');
	$this->load_langfile('inside/group.php');
	$this->load_langfile('inside/admin.php');
	$this->load_langfile('inside/dashboard.php');
	$this->load_langfile('inside/groups_new.php');
	$this->load_langfile('inside/user.php');

	//require $C->INCPATH.'helpers/func_additional.php';	
	
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
		$latitude = isset($_POST['lat']) ? $db2->escape($_POST['lat']) : NULL;
		$longitude = isset($_POST['lng']) ? $db2->escape($_POST['lng']) : NULL;
		if(empty($address)) {
			$latitude = NULL;
			$longitude = NULL;
		}
		if(!empty($latitude) && !empty($longitude)) {
			$location = $latitude.','.$longitude;
		}
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
				($file_type == "application/octet-stream") || 
				($file_type == "application/vnd.ms-excel") || 
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

		if(!empty($this->params->group)){
			$g	= $this->network->get_group_by_id(intval($this->params->group), true);
		}
		
		if($_SESSION['submitted'] === 1 ){
			  $errmsg.="Double submit detected. Please check your calendar.";
			  $error = TRUE;
		}
		if($error === FALSE){
			$_SESSION['submitted'] = 1;
			//print_R(); exit;
			$date_time = date('M d, Y h:i A', strtotime($start_date.' '.$start_time));
			 
			$id=$this->db2->query('INSERT INTO `events` (publish_now, publish_date, activity_pub_date, created_at, modified_at, group_id, admin_id, location, address, event_type, display_type, 	event_name, event_description, start_date, 	start_time,end_date, time_zone, end_time, is_private, status, latitude, longitude) 
											VALUES ("'.$publish_now.'","'.$publish_date.'","'.$pub_select_day.'", now(), now(), "'.$group_id.'", "'.$this->user->id.'", "'.$location.'", "'.$address.'", "'.$event_type.'", "'.$display_type.'", "'.$title.'", "'.$description.'", "'.date('Y-m-d H:i:s',strtotime($start_date)).'", "'.date('Y-m-d H:i:s',strtotime($start_time)).'", "'.date('Y-m-d H:i:s',strtotime($end_date)).'", "","'.date('Y-m-d H:i:s',strtotime($end_time)).'","'.$private.'", 1,'.$latitude.','.$longitude.')');
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
					//$left = ($this->params->group)?(strlen($this->user->info->username)+strlen($g->title)+8):(strlen($this->user->info->username)+4);
					$answer = (object)array(
							'link'=>$C->SITE_URL."plugin/events/view/id:".$id.$addition_url, 
							'title'=>$content_title,
							'description' =>$content,
							'hits'=> 'link'
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
						
					/*$pieces = explode("#",$post_tags);
					//print_r($pieces);
					$ctags  = count($pieces);
					for($i=0;$i<$ctags;$i++){
						if($pieces[$i]!=''){
					$db2->query('INSERT INTO post_tags SET tag_name="'.$pieces[$i].'", user_id="'.$this->user->id.'", post_id="'.$pid.'"');
						}
					}*/
					//$this->db2->query('INSERT INTO `posts` (user_id, message, posttags, date) VALUES ("'.$this->user->id.'", "'.$_POST['title'].'", "'.$href.'","'.time().'")');

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
							
							//sending the messages
							$subject = 'Event Role Added in your Group : '.ucwords($title);
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
					
					if($display_type == 'group' && !empty($group_id)){
						$user_list= $this->network->get_group_members($group_id);
						
						$subject = 'Event Added : '.ucwords($title);
						$message_html = ucwords($title).' has been added to your group';
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
			if(!empty($this->params->group)){
				$this->redirect('plugin/events/home/group:'.$this->params->group);	
			}else{
				if(!empty($this->params->username))
					$this->redirect('plugin/events/home/username:'.$this->params->username);	
				else
					$this->redirect('plugin/events/home');
			}
		}
	}else{
	
	}


//TEMPLATE CODE START
		
		if(empty($this->params->group)){
$add_link = '';
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
			
			$tpl->layout->setVar( 'subheader_placeholder', $tpl->designer->createMenu( 'tabs-navigation', $menu ) ); unset($menu);

			//$tpl->layout->useBlock('user-header-info');
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
			$g	= $this->network->get_group_by_id(intval($this->params->group), true);
			
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
			
			if(!empty($this->params->group)){
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
				
				$tpl->layout->setVar( 'subheader_placeholder', $tpl->designer->createMenu( 'tabs-navigation', $menu, 'group_navigation_top_menu' ) ); 
				unset($menu);		
				$tpl->layout->setVar( 'main_content_placeholder', $tpl->designer->createMenu('tabs-navigation', $menu, 'groups_top_tab_menu') );
				
			}		
		}
		
	if( ($submit && !$error) || $this->param('msg') == 'grpsaved' ){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->okMessage($this->lang('admtrms_ok_ttl'), $this->lang('admadm_frm_ok_txt') ) );
	}else if( $submit && $error ){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->errorMessage('Error', $errmsg) );
	}
			
	//$tpl->designer->createMenuLink( array('url'=>'plugin/blog/bpost',  'title'=>'Blog') ) );

	if(!empty($this->params->group)){
		
		$tpl->layout->useBlock('group_event', 'events');
		$g	= $this->network->get_group_by_id(intval($this->params->group), true);
		
		$tpl->layout->setVar('main_content_placeholder', '<h1>Create Event</h1>');
		
		$display_type = !empty($this->params->group)?'group':'community';
		$start = !empty($this->params->start)?date('m/d/Y', $this->params->start):(isset($_POST['start_date'])?$_POST['start_date']:'');
		$end = !empty($this->params->end)?date('m/d/Y', $this->params->end):(isset($_POST['end_date'])?$_POST['end_date']:'');
		$group_id = ($this->params->group)?$this->params->group:'';
		$tpl->layout->block->setVar('display_type', $display_type);	
		$tpl->layout->block->setVar('start', $start);	
		$tpl->layout->block->setVar('end', $end);	
		
		$tpl->layout->block->setVar('roles_list', roles_list($this->params->group));
		
		$tpl->layout->block->setVar('group_id', $this->params->group);
		
		$tpl->layout->block->setVar('role_select', role_select($this->params->group));
		$tpl->layout->block->setVar('role_select_user', role_user_select($this->params->group));
		$tpl->layout->block->setVar('roles_resouce_list', roles_resource_list($this->params->group));		
		
	}else{	
		
		$tpl->layout->useBlock('add_event', 'events');
		if(!empty($this->params->username))
			$tpl->layout->setVar('main_content_placeholder', '<h1>Create Event</h1>');
		else
			$tpl->layout->setVar('main_content_top_placeholder', '<h1 style="margin:10px">Create Event</h1><div class="break"></div>');
		
		$display_type = !empty($this->params->group)?'group':'community';
		$start = !empty($this->params->start)?date('m/d/Y', $this->params->start):(isset($_POST['start_date'])?$_POST['start_date']:'');
		$end = !empty($this->params->end)?date('m/d/Y', $this->params->end):(isset($_POST['end_date'])?$_POST['end_date']:'');
		$group_id = ($this->params->group)?$this->params->group:'';
		$tpl->layout->block->setVar('display_type', $display_type);	
		$tpl->layout->block->setVar('start', $start);	
		$tpl->layout->block->setVar('end', $end);	
		
		/*$table = new tableCreator();
		$table->form_enctype = 'enctype="multipart/form-data"';
		$display_type = !empty($this->params->group)?'group':'community';
		$start = !empty($this->params->start)?date('m/d/Y', $this->params->start):(isset($_POST['start_date'])?$_POST['start_date']:'');
		$end = !empty($this->params->end)?date('m/d/Y', $this->params->end):(isset($_POST['end_date'])?$_POST['end_date']:'');
		$rows = array(
			$table->hiddenField( 'display_type', $display_type),
			$table->inputField( 'Event Title:', 'title', isset($_POST['title'])?$_POST['title']:'' ),
			$table->inputField( 'Start Date:', 'start_date', $start),
			$table->inputField( 'Start Time:', 'start_time', isset($_POST['start_time'])?$_POST['start_time']:'' ),
			$table->inputField( 'End Date:', 'end_date', $end),
			$table->inputField( 'End Time:', 'end_time',  isset($_POST['end_time'])?$_POST['end_time']:''),
			$table->inputField( 'Place', 'location',  isset($_POST['location'])?$_POST['location']:''),
			$table->inputField( 'Address:', 'address', isset($_POST['address'])?$_POST['address']:''),
			$table->textArea( 'Event Description ', 'description', isset($_POST['description'])?$_POST['description']:''),
			$table->hiddenField( 'is_private', 0)
			);
		
		if(!empty($this->params->group)){
			$rows[] = $table->hiddenField( 'group_id', $this->params->group);
			$rows[] = $table->selectField( 'Event Type:', 'event_type', array('normal'=>'normal', 'major'=>'major'), '' );
			$rows[] = $table->fileField( 'Attachement: ', 'attachment', '' );
			$rows[] = $table->submitButton( 'submit', $this->lang('admgnrl_frm_sbm') );
		}else{
			$rows[] = $table->selectField( 'Event Type:', 'event_type', array('normal'=>'normal', 'major'=>'major'), '' );
			$rows[] = $table->fileField( 'Attachement: ', 'attachment', '' );
			$rows[] = $table->submitButton( 'submit', $this->lang('admgnrl_frm_sbm') );
		}
		*/
		//$tpl->layout->setVar('main_content', $table->createTableInput( $rows ) );	
	}	
	
	$tpl->layout->block->save( 'main_content', true );	
	
	$tpl->display();
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