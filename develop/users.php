<?php
	global $db2;
	
	$page = & $GLOBALS['page'];
	$user = & $GLOBALS['user'];
	$network = & $GLOBALS['network'];
	$pm 		= & $GLOBALS['plugins_manager'];
	$this->cache	= & $GLOBALS['cache'];
	$page->load_langfile('inside/global.php');
	$page->load_langfile('inside/dashboard.php');
	$page->load_langfile('inside/user.php');
	
	global $C;
	require $C->INCPATH.'helpers/func_html.php';
	require_once $C->INCPATH.'helpers/func_main.php';
	switch( $ajax_action ){
		
		case 'follow': 
			
			$users_id = intval($_POST['users_id']);
			
			$u	= $network->get_user_by_id( $users_id );
			if( ! $u ) {
				echo 'ERROR';
				return;
			}
			if( ! $user->follow($u->id, TRUE) ) {
				echo 'ERROR:'.$page->lang('global_ajax_post_error16');
				return;
			}
			
			if( $errmsg = $pm->getEventCallErrorMessage() ){
				echo 'ERROR:' . $errmsg;
				return;
			}
			
			$designer = pageDesignerFactory::select();
			
			echo $designer->usersSettingsMenu($users_id, FALSE);
			return;
			
			break;
			
		case 'unfollow':
			
			$users_id = intval($_POST['users_id']);
				
			$u	= $network->get_user_by_id( $users_id );
			if( ! $u ) {
				echo 'ERROR';
				return;
			}
			if( ! $user->follow($u->id, FALSE) ) {
				echo 'ERROR';
				return;
			}
			if( $errmsg = $pm->getEventCallErrorMessage() ){
				echo 'ERROR:' . $errmsg;
				return;
			}
			
			$designer = pageDesignerFactory::select();
			
			echo $designer->usersSettingsMenu($users_id, TRUE);
			return;
			
			break;
		
		case 'autocomplete':
			$name		= isset($_POST['users_name']) ? trim($_POST['users_name']) : '';
			if( empty($name) ) {
				echo 'ERROR';
				return;
			}
			if($_POST['usertype'] =='group'){
				$w	= $db2->e($name);

			$r	= $db2->query('SELECT id, groupname, title, avatar FROM groups WHERE is_public=1 AND (groupname LIKE "%'.$w.'%" OR title LIKE "%'.$w.'%") ORDER BY num_followers DESC, groupname ASC LIMIT 5');
			$n	= $db2->num_rows();
			if( $n > 0 ){
				$i = 0;
				$users = array('users'=>array());
				while($obj = $db2->fetch_object($r)) {
					$users['users'][$i]['id'] 			= $obj->id;
					$users['users'][$i]['username'] 	= $obj->groupname;
					$users['users'][$i]['fullname'] 	= $obj->title;
					$users['users'][$i]['profile_url'] 	= userlink($obj->title);
					$users['users'][$i]['avatar_url'] 	= getAvatarUrl( (empty($obj->avatar)? $C->DEF_AVATAR_USER : $obj->avatar), 'thumbs3' );
					$i++;
				}
				
				echo json_encode($users);
				return;
			}else{
				echo 'ERROR';
				return;
			}
				
				
			}elseif($_POST['usertype'] =='hash'){
			$w	= $db2->e($name);
			$res = $network->get_recent_posttags($count=10, $obj_id = FALSE, $type=FALSE, $force_refresh=FALSE,$w);
			$users = array('users'=>array());
			$i = 0;
			if(!empty($res)){
			foreach($res as $keys=>$vals){
				$users['users'][$i]['id'] 			= $vals;
					$users['users'][$i]['username'] 	= $vals;
					$users['users'][$i]['fullname'] 	= $vals;
					$users['users'][$i]['profile_url'] 	= userlink($vals);
					//$users['users'][$i]['avatar_url'] 	= getAvatarUrl( (empty($obj->avatar)? $C->DEF_AVATAR_USER : $obj->avatar), 'thumbs3' );
				   $i++;
			}
			echo json_encode($users);
				return;
			}else{
				echo 'ERROR';
				return;
			}
			
			
		
			}elseif($_POST['usertype'] =='asset'){
					$w	= $db2->e($name);
					if($_POST['ticker1']){
					$ticker            =$_POST['ticker1'];
		                        $htmlcontent       =$_POST['finaldata'];
		                         $tickers           =explode(',',$ticker);
						 foreach($tickers as $tickerskeys=>$tickersval){
							 if(strpos($htmlcontent,$tickersval)){
								   $final[]       ="'".$tickersval."'";
								 
							 }
							 
						 }
						 if(!empty($final)){
							$finalticker    =implode(',',$final);
							$sql ='AND ticker NOT IN('.$finalticker.') ';
							
							 
						 }else{
							 $sql ='';
							 
						 }
						            
	  
						
						
						
					}else{
						$sql ='';
					}
					$r	= $db2->query('SELECT id,asset_name, ticker  FROM assets WHERE status=1 AND (ticker LIKE "%'.$w.'%" OR asset_name LIKE "%'.$w.'%") '.$sql.' ORDER BY id DESC  LIMIT 5');

					$postid    =isset($_POST['postid'])?$_POST['postid']:'';

                    $n	= $db2->num_rows();
					if( $n > 0 ){
						$i = 0;
						$users = array('users'=>array());
						while($obj = $db2->fetch_object($r)) {
							$users['users'][$i]['id'] 			= $obj->id;
							$users['users'][$i]['username'] 	= strtoupper($obj->asset_name);
							$users['users'][$i]['fullname'] 	= strtoupper($obj->ticker);
							$users['users'][$i]['postid'] 	= $postid;

							$i++;
						}
				
				 			echo json_encode($users);
						       return;

			}else{
				echo 'ERROR';
				return;
			}
			


			}elseif($_POST['usertype'] =='prediction'){
				$w	= $db2->e($name);
				$r	= $db2->query('SELECT id,asset_name, ticker  FROM assets WHERE status=1 AND (ticker LIKE "%'.$w.'%" OR asset_name LIKE "%'.$w.'%")  ORDER BY id DESC  LIMIT 5');

					
				$n	= $db2->num_rows();
				if( $n > 0 ){
					$i = 0;
					$users = array('users'=>array());
					while($obj = $db2->fetch_object($r)) {
						$users['users'][$i]['id'] 			= $obj->id;
						$users['users'][$i]['username'] 	= strtoupper($obj->asset_name);
						$users['users'][$i]['fullname'] 	= strtoupper($obj->ticker);
						$i++;
					}
			
			echo json_encode($users);
			return;
			}else{
				echo 'ERROR';
				return;
			}
		

			}else{
			$w	= $db2->e($name);
			$r	= $db2->query('SELECT id, username, fullname, avatar FROM users WHERE active=1 AND (username LIKE "%'.$w.'%" OR fullname LIKE "%'.$w.'%") ORDER BY num_followers DESC, fullname ASC LIMIT 5');
			$n	= $db2->num_rows();
			if( $n > 0 ){
				$i = 0;
				$users = array('users'=>array());
				while($obj = $db2->fetch_object($r)) {
					$users['users'][$i]['id'] 			= $obj->id;
					$users['users'][$i]['username'] 	= $obj->username;
					$users['users'][$i]['fullname'] 	= $obj->fullname;
					$users['users'][$i]['profile_url'] 	= userlink($obj->fullname);
					$users['users'][$i]['avatar_url'] 	= getAvatarUrl( (empty($obj->avatar)? $C->DEF_AVATAR_USER : $obj->avatar), 'thumbs3' );
					$i++;
				}
				
				echo json_encode($users);
				return;
			}else{
				echo 'ERROR';
				return;
			}
				
			}
		break;
				case 'love':
			
			$users_id = intval($_POST['users_id']);
			$id=$network->addfavourite($user->id,$users_id);

			$notifytype="loved";
			$standardnotifytype ="ntf_me_on_post_profileloved";
			$type ="profile";
			$postid=$users_id;
			$newisert =$network->insert_active_profilenotifications($users_id,$postid,$notifytype,$type,$standardnotifytype);

			if(!empty($id)){
			

			$favourites = $network->get_user_favourites($users_id);
			if($favourites > 0){
			$favcnt = '<a href="" class="action-btn user-action disconnect-user" data-role="services" data-namespace="users" data-action="unlove" data-value="'.$users_id.'"  >  <span class="glyphicon glyphicon-heart glyphicon-heart-love"></span></a><span class="count-love"><a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showloves" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$users_id.'"}').'"><span class="count-love">'.$favourites.'</span></a>';
					
					echo '<div class="unlove unlove profile-buttons-love">'.$favcnt.'</div>';
					return;
			}

				
			}
			
			break;
			case 'unlove':
			
			$users_id = intval($_POST['users_id']);
			$id=$network->deletefavourites($user->id,$users_id);
			if(!empty($id)){
			

			$favourites = $network->get_user_favourites($users_id);
			if($favourites > 0){
			$favcnt = '<a href="" data-role="services" class="action-btn user-action disconnect-user" data-namespace="users" data-action="love" data-value="'.$users_id.'" >  <span class="glyphicon glyphicon-heart glyphicon-heart-unlove"></span><a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showloves" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$users_id.'"}').'"><span class="count-love">'.$favourites.'</span></a></a>';
					
					echo '<div class="love  profile-buttons-love">'.$favcnt.'</div>';
					return;
			}else{
				$favcnt = '<a class="action-btn user-action disconnect-user" href="" data-role="services" data-namespace="users" data-action="love" data-value="'.$users_id.'" > <span class="glyphicon glyphicon-heart glyphicon-heart-unlove"></span><a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showloves" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$users_id.'"}').'"><span class="count-love"></span></a> </a>';
					
					echo '<div class="love profile-buttons-love">'.$favcnt.'</div>';
					return;
			}

				
			}
			
			break;
		
		case 'bizcard':
			if( !$this->user->is_logged ){
				return;
			}
			$users_id		= isset($_POST['users_id']) ? trim($_POST['users_id']) : '';
			if( empty($users_id) ) {
				echo 'ERROR';
				return;
			}
			
			$r	= $db2->query('SELECT * FROM users WHERE id="'.intval($users_id).'" LIMIT 1');
			if( $obj = $db2->fetch_object($r) ){
				
				$ifollow 			= $network->get_user_follows($user->id, FALSE, 'hefollows')->follow_users;
				$he_follows 		= $network->get_user_follows($obj->id, FALSE, 'hefollows')->follow_users;
				$if_he_follows_me 	= isset( $he_follows[$user->id] );
				
				$is_profile_protected = ( $obj->is_profile_protected && !$user->info->is_network_admin && !$if_he_follows_me );
				$is_dm_protected = ( $obj->is_dm_protected && !$user->info->is_network_admin && !$if_he_follows_me );
				
				$to_follow = ($obj->id !== $user->id);
				$is_me = ($obj->id === $user->id);
				if( $to_follow ){
					$to_follow = !isset( $ifollow[$obj->id] );
				}
				//checking for unfollowandfollowdata for bizcard
				
				$bizcardcnt 		= $network->ifollowcheckdata($user->id,$users_id);


				$tpl = new template( array(), FALSE ); //some actions here 
				
				$tpl->layout->useBlock('single-user-bizcard');
				$tpl->layout->block->setVar('single_user_bizcard_avatar', '<img src="'.getAvatarUrl((empty($obj->avatar)? $C->DEF_AVATAR_USER : $obj->avatar), 'thumbs1').'" alt="'.getThisUserCommunityName($obj).'" />');
				$tpl->layout->block->setVar('single_user_bizcard_activity', '<a href="'.$C->SITE_URL.$obj->username.'/tab:friends/subtab:followers">'. $page->lang('usr_activity_count_small', array('#NUM_FOLLOWERS#'=>$obj->num_followers, '#NUM_POSTS#'=>$network->format_num($obj->num_posts))));
				$tpl->layout->block->setVar('single_user_bizcard_username', $obj->username);
				$tpl->layout->block->setVar('single_user_bizcard_user_identifier', getThisUserCommunityName($obj));
				
				if( !$is_profile_protected ){
					$tpl->layout->block->setVar('single_user_bizcard_jobtitle', htmlspecialchars($obj->position));
					if( $user->info->is_network_admin || $if_he_follows_me ){
						//$tpl->layout->block->setVar('single_user_bizcard_email', '<a href="mailto:'.$obj->email.'">'.$obj->email.'</a>');
					}
				}
				
				if( !$is_dm_protected && $user->id != $obj->id ){
					$user_message_data = '{"users_id": '.$obj->id.', "users_name":"'.$obj->fullname.'", "users_username":"'.$obj->username.'"}';
					$tpl->layout->block->setVar('user_biz_card_more_personal_info', '<li><a class="plain-btn send-message" data-role="services" data-namespace="users" data-value="'.htmlentities($user_message_data, ENT_COMPAT, 'UTF-8').'" data-action="sendMessagePopup">'.$page->lang('global_send_message_to_user').'</a></li>');
				}
				
				if( $obj->active ){
					$tpl->layout->block->setVar('single_user_bizcard_follow', !$is_me? $tpl->designer->usersSettingsMenu($obj->id, $to_follow) : '');
				}
				
				$tpl->layout->block->save('main_content');
				$single_user_bizcard_avatar ='<img src="'.getAvatarUrl((empty($obj->avatar)? $C->DEF_AVATAR_USER : $obj->avatar), 'thumbs1').'" alt="'.getThisUserCommunityName($obj).'" />';
				$single_user_bizcard_username = $obj->username;
				$single_user_bizcard_user_identifier = getThisUserCommunityName($obj);
				if( !$is_profile_protected ){
					$single_user_bizcard_jobtitle = htmlspecialchars($obj->position);
					if( $user->info->is_network_admin || $if_he_follows_me ){
						  $single_user_bizcard_email= $obj->about_me;
					}
					
					
				}
				
				$single_user_bizcard_activity = '<a href="'.$C->SITE_URL.$obj->username.'/tab:friends/subtab:followers">'. $page->lang('usr_activity_count_small', array('#NUM_FOLLOWERS#'=>$network->format_num($network->followers($users_id)), '#NUM_POSTS#'=>$network->format_num($obj->num_posts)));
				$html = '';
				$html .='<div class="people-section">
					<div class="avatar">'.$single_user_bizcard_avatar.'</div>
								<div class="details">
									<div class="title">
									<a href="'.$C->SITE_URL.''.$single_user_bizcard_username.' ">'.$single_user_bizcard_user_identifier.'</a>
									<span class="job-title">'.$single_user_bizcard_jobtitle.'</span>
								</div>
										<div class="statistics">'.$single_user_bizcard_activity.'</div>
									<ul class="personal-information">
									<li class="personal-information-email">'.$single_user_bizcard_email.'</li>';
									if( !$is_dm_protected && $user->id != $obj->id ){
					$user_message_data = '{"users_id": '.$obj->id.', "users_name":"'.$obj->fullname.'", "users_username":"'.$obj->username.'"}';
					$user_biz_card_more_personal_info =  '<li><a class="plain-btn send-message" data-role="services" data-namespace="users" data-value="'.htmlentities($user_message_data, ENT_COMPAT, 'UTF-8').'" data-action="sendMessagePopup">'.$page->lang('global_send_message_to_user').'</a></li>';
					$html .= $user_biz_card_more_personal_info;
				}
				$html			.='</ul>';
				if($bizcardcnt !=0){
				$followhtml ='<a class="action-btn user-action disconnect-user" data-action="unfollow" data-value="'.$obj->id.'" data-namespace="users" data-role="services"><span class="tooltip"  style="top:-10px; right:0px"><span class="glyphicon glyphicon-user user-icon-flw"></span></span></a>';
					
				}else{
				$followhtml ='<a class="action-btn user-action disconnect-user" data-action="follow" data-value="'.$obj->id.'" data-namespace="users" data-role="services"><span class="tooltip"  style="top:-10px; right:0px"><span class="glyphicon glyphicon-user user-icon-flw"></span></span></a>';

					
				}
				if( $obj->active ){
					$single_user_bizcard_follow =  (!$is_me?$followhtml : '');
					$html .='<div class="options-container">'.$single_user_bizcard_follow.'</div>';

				}



				$html .='</div>
							
							<div class="clear"></div>




				
				</div>';

				
				
				
				echo $html;
				return;
			}
				
			echo 'ERROR';
			return;
			
			break;
			
			
			case 'sendmessage':

				$prv_user_id = isset($_POST[ 'users_id' ])? $_POST[ 'users_id' ] : '';
				$prv_text = isset($_POST[ 'text' ])? trim($_POST[ 'text' ]) : '';
								
				if( !empty($prv_text) && !empty($prv_user_id) ){
					
					$if_he_follows_me 	= $user->if_user_follows_me($prv_user_id);	
					$u = $network->get_user_by_id($prv_user_id); 
					$is_dm_protected = ( $u->is_dm_protected && !$user->info->is_network_admin && !$if_he_follows_me );
					if( $is_dm_protected ){
						echo 'ERROR: '. $page->lang('usr_private_msg_protection_enabled', array('#USERNAME#'=>$u->username));
						return;
					}
					
					$p = new newpost();
					$p->set_to_user($prv_user_id);
					$p->set_message($prv_text);
					$p->save();	
				
					$answer = array('html'=>$page->lang('usr_private_msg_sent')); 
					echo json_encode($answer);
					return; 
				}
				
				echo 'ERROR:'.$page->lang('global_ajax_post_error1');
				return;

				break;			
	}
	 