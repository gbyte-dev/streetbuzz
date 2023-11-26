<?php
error_reporting(0);

	if( !$this->network->id ) {
		$this->redirect('home');
	}elseif($C->PROTECT_OUTSIDE_PAGES && !$this->user->is_logged){
		$this->redirect('home');
	}
	
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/user.php');
	global $plugins_manager;
			$user 	= & $GLOBALS['user'];

	$submit	= FALSE;
	$error	= FALSE;
	$errmsg	= '';
	$send_notif	= FALSE;
	$u = $this->network->get_user_by_id(intval($this->params->user));
	if( !$u ){
		$this->redirect('dashboard');
	}
	
	if(isset($_POST['sbm']))
	{	
if( isset($_FILES['profile_avatar']) && is_uploaded_file($_FILES['profile_avatar']['tmp_name']) ) {
		$submit	= TRUE;
		$plugins_manager->onUserSettingsSubmit();
		if( !$plugins_manager->isValidEventCall() ){
			$error = TRUE;
			$errmsg = $plugins_manager->getEventCallErrorMessage();
		}
		
			if( !$error ){
			$f	= (object) $_FILES['profile_avatar'];
			list($w, $h, $tp) = getimagesize($f->tmp_name);
			if( $w==0 || $h==0 ) {
				$error	= TRUE;
				$errmsg	= $this->lang('st_avatar_err_invalidfile');
			}
			elseif( $tp!=IMAGETYPE_GIF && $tp!=IMAGETYPE_JPEG && $tp!=IMAGETYPE_PNG ) {
				$error	= TRUE;
				$errmsg	= $this->lang('st_avatar_err_invalidformat');
			}
			elseif( $w<$C->AVATAR_SIZE || $h<$C->AVATAR_SIZE ) {
				$error	= 'Please select morethan 200 width size';
				$errmsg	= $this->lang('st_avatar_err_toosmall');
			}

			
			if( ! $error ) {
				$avtr	= $this->user->info->avatar;
				if( $avtr != $C->DEF_AVATAR_USER ) {
					rm( $C->STORAGE_DIR.'avatars/'.$avtr );
					rm( $C->STORAGE_DIR.'avatars/thumbs1/'.$avtr );
					rm( $C->STORAGE_DIR.'avatars/thumbs2/'.$avtr );
					rm( $C->STORAGE_DIR.'avatars/thumbs3/'.$avtr );
					rm( $C->STORAGE_DIR.'avatars/thumbs4/'.$avtr );
					rm( $C->STORAGE_DIR.'avatars/thumbs5/'.$avtr );
				}else{
					$avtr	= time().rand(100000,999999).'.png';	
				}
				
				if( $avtr != $C->DEF_AVATAR_USER ) {
					$res	= copy_avatar($f->tmp_name, $avtr);
					if( ! $res) {
						$error	= TRUE;
						$errmsg	= $this->lang('st_avatar_err_cantcopy');
					}
				}else{
					$avtr = '';
				}
				$fullname     =$_POST['profile_name'];
				$location_name     =$_POST['location_name'];
				$bdate_d     =$_POST['day'];
				$bdate_m     =$_POST['profile_birth_month'];
				$bdate_y     =$_POST['profile_birth_year'];
				$referal_type     =$_POST['referal_type'];
				if( $bdate_d==0 || $bdate_m==0 || $bdate_y==0 ) {
				$bdate_m	= 0;
				$bdate_d	= 0;
				$bdate_y	= 0;
				$birthdate	= '0000-00-00';
			}
			else {
				$birthdate	= $bdate_y.'-'.str_pad($bdate_m,2,0,STR_PAD_LEFT).'-'.str_pad($bdate_d,2,0,STR_PAD_LEFT);
			}

              $db2->query('UPDATE users SET avatar="'.$db2->e($avtr).'",fullname="'.$db2->e($fullname).'",refer_type="'.$db2->e($referal_type).'",location="'.$db2->e($location_name).'",birthdate="'.$db2->e($birthdate).'"  WHERE id="'.$this->user->id.'" LIMIT 1');
				$this->network->get_user_by_id($this->user->id, TRUE);
				$this->network->get_online_users(TRUE);
				$send_notif	= TRUE;
				$this->user->info->avatar	= $avtr;
				$this->redirect($C->SITE_URL.$u->username.'/tab:profile/message:1');

			}else{
					$this->redirect($C->SITE_URL.$u->username.'/tab:profile/message:0');

				
			}
			
		}



	}else{
		        $fullname     =$_POST['profile_name'];
				$location_name     =$_POST['location_name'];
				$bdate_d     =$_POST['day'];
				$bdate_m     =$_POST['profile_birth_month'];
				$bdate_y     =$_POST['profile_birth_year'];
				$referal_type     =$_POST['referal_type'];
				if( $bdate_d==0 || $bdate_m==0 || $bdate_y==0 ) {
				$bdate_m	= 0;
				$bdate_d	= 0;
				$bdate_y	= 0;
				$birthdate	= '0000-00-00';
				
		
	       }else{
			   				$birthdate	= $bdate_y.'-'.str_pad($bdate_m,2,0,STR_PAD_LEFT).'-'.str_pad($bdate_d,2,0,STR_PAD_LEFT);
             }
			   $db2->query('UPDATE users SET fullname="'.$db2->e($fullname).'",refer_type="'.$db2->e($referal_type).'",location="'.$db2->e($location_name).'",birthdate="'.$db2->e($birthdate).'"  WHERE id="'.$this->user->id.'" LIMIT 1');
				$this->redirect($C->SITE_URL.$u->username.'/tab:profile/message:1');

	}
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

	$tab = 'updates';
	if( $this->param('tab') ){
		$tab = $this->param('tab');
	}if( $this->param('message') ){
		$D->message = $this->param('message');
	}
	
	$subtab = 'all';
	
	if( $this->param('subtab') ){
		$subtab = $this->param('subtab');
	}
	
	if($tab =='friends' && $subtab != 'ifollow' && $subtab !='followers' && $subtab !='incommon'){
		$subtab = 'ifollow';
	}
	
	 $folow_res           = $db2->query('SELECT whom FROM  users_followed as uf where who="'.$this->user->id.'"' );
	 	
	while($fetchres = $db2->fetch_object($folow_res)){
		$res[] = $fetchres->whom;
	}
	if(!empty($res)){
	$fetchres   =implode(',',$res);
	}else{
		$fetchres ="' '";
		
		
	}
	
	
	$fetch            =$db2->query('SELECT u.id,u.username,u.fullname,u.avatar,u.about_me FROM  street_suggestion as st
	                          INNER JOIN users AS u  ON st.user_id=u.id
                              where st.user_id NOT IN('.$fetchres.')							  
							  group by u.id
	                          order by rand() limit 3 ');
	while($fetchresasw[] = $db2->fetch_object($fetch)){
	}
    $D->follow = ($fetchresasw);
	
	$paging_url	= $C->SITE_URL.$u->username.'/tab:'.$tab.'/subtab:'.$subtab.'/pg:';
	
	$udtls = $this->network->get_user_details_by_id(intval($this->params->user));
	$udtls = ($udtls === FALSE || empty($udtls))? array() : $udtls;

	//TEMPLATE START 
	$tpl = new template( array('page_title' => $u->username. ' - ' .$C->SITE_TITLE, 'header_page_layout'=>'sc') );
	
	$tpl->initRoutine('DashboardUserLeftMenu', array( &$u, &$he_follows, &$udtls ));
	$tpl->routine->load();
	

	$menu = array( 	array('url' => $u->username.'/tab:all', 	'css_class' => (($tab === 'all' || $tab=='' )? 'activeTab' :''), 	'title' => 'Buzzes' ),
	);
	if( !$is_profile_protected ){
		/*$menu[] = array('url' => $u->username.'/tab:info', 		'css_class' => (($tab === 'info')? ' selected' : ''), 		'title' => $this->lang('usr_tab_info') );*/
		$menu[] = array('url' => $u->username.'/tab:friends/subtab:ifollow', 	'css_class' => (($subtab === 'ifollow')? 'activeTab' : ''), 	'title' => 'Following' );
		$menu[] = array('url' => $u->username.'/tab:friends/subtab:followers', 	'css_class' => (($subtab === 'followers')? 'activeTab' : ''), 	'title' =>'Followers' );

		$menu[] = array('url' => $u->username.'/tab:groups', 	'css_class' => (($tab === 'groups')? 'activeTab' : ''), 	'title' => $this->lang('usr_tab_groups') );
		


	}
	if($this->user->id == $u->id){
			$menu[] = array('url' => $u->username.'/tab:profile', 	'css_class' => (($tab === 'profile')? 'activeTab' : ''), 	'title' => 'Edit Profile' );
	}
	/*		$menu[] = array('url' => $u->username.'/tab:likes', 	'css_class' => (($tab === 'likes')? 'activeTab' : ''), 	'title' => 'Likes' );
			$menu[] = array('url' => $u->username.'/tab:bookmarks', 	'css_class' => (($tab === 'bookmarks')? 'activeTab' : ''), 	'title' => 'Bookmarks' );
                        $menu[] = array('url' => $u->username.'/tab:agree', 	'css_class' => (($tab === 'agree')? 'activeTab' : ''), 	'title' => 'Agree2Disagree' );*/
			$menu[] = array('url' => $u->username.'/tab:intraday', 	'css_class' => (($tab === 'intraday')? ' activeTab' : ''), 	'title' => 'Intraday' );





	
	$tpl->layout->setVar( 'subheader_placeholder', $tpl->designer->createMenu( 'tabs-navigation', $menu,'user_page_navigation_top_menu') ); unset($menu);

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
	
	if($tab == 'updates'){
		//$tpl->initRoutine('Postform', array('main_content', $u));
		//$tpl->routine->load();
	}
	$tpl->layout->setVar('in_group', 'about @'.$u->username);
                 $p =new post();
		 $myintradaycal       = $p->myintradaycorrectdatacalculation($user->id);
		 $totalintraday       = $p->totalintraday($user->id);
		 $correctcnt          = $myintradaycal->correctcnt;
		 $totalcnt            = $totalintraday->totalcnt;
		 $correctper          = round(($correctcnt/$totalcnt)*100,2);
		 
		 $myintradayincal       = $p->myintradayincorrectdatacalculation($this->user->id);
		 $incorrectcnt          = $myintradayincal->correctcnt;
		 $incorrectper          = round(($incorrectcnt/$totalcnt)*100,2);
	
	switch($tab){
		case 'info':
					if( !$is_profile_protected ){
					   
						if( !empty($u->reg_date) ){
							$dtls[$this->lang('usr_info_aboutme_datereg')] = strftime($this->lang('usr_info_birthdate_dtformat'), $u->reg_date);
						}
						if( !empty($u->lastlogin_date) ){
							$dtls[$this->lang('usr_info_aboutme_datelgn')] = strftime($this->lang('usr_info_birthdate_dtformat'), $u->lastlogin_date);
						}							
						if( !empty($u->location) ){
							$dtls[$this->lang('usr_info_aboutme_location')] = $u->location;
						}
						if( !empty($u->position) ){
							$dtls[$this->lang('usr_prof_we_section_name')] = $u->position;
						}
						if( !empty($u->about_me) ){
							$dtls[$this->lang('usr_info_section_aboutme')] = $u->about_me;
						}
						if( !empty($u->gender) ){
							$dtls[$this->lang('usr_info_aboutme_gender')] =  $this->lang('usr_info_aboutme_gender_'.$u->gender);
						}
						
						if($is_admin_or_follows_me || $is_my_profile){
							$dtls['Email'] = $u->email;
							
							if(!empty($u->birthdate) && $u->birthdate!= '0000-00-00' ) {
								$dtls[$this->lang('usr_info_aboutme_birthdate')] = date('m/d/Y',strtotime($u->birthdate));
							}
							if(!empty($u->position)) {
								$dtls[$this->lang('usr_prof_we_jobtitle')] = $u->position;
							}
							if( isset($udtls->website) && !empty($udtls->website) && is_valid_url($udtls->website) ){
								$dtls[$this->lang('usr_info_aboutme_website')] = $udtls->website;
							}
							if( isset($udtls->work_phone) && !empty($udtls->work_phone) ){
								$dtls[$this->lang('usr_info_aboutme_wphone')] = $udtls->work_phone;
							}
							if( isset($udtls->personal_phone) && !empty($udtls->personal_phone) ){
								$dtls[$this->lang('usr_info_aboutme_pphone')] = $udtls->personal_phone;
							}
						}
						
						if( $this->user->is_logged && $this->user->info->is_network_admin ){
							$dtls[$this->lang('usr_profile_lastloginip')] = long2ip($u->lastlogin_ip);
							$dtls[$this->lang('usr_profile_regip')] = long2ip($u->reg_ip);
						}
						
						
						$tpl->layout->setVar( 'main_content', $tpl->designer->createTableDetailsBlock( '', $dtls, 'user-details' ) ); unset($dtls);
					}else{	
						$tpl->layout->setVar('main_content', $tpl->designer->createNoPostBox($this->lang('noposts_usrprofileprotected_ttl'), $this->lang('post_profile_protected')));
					}
					break;

		case 'friends':
					if( !$is_profile_protected ){
						/*$menu = array( 	array('url' => $u->username.'/tab:friends/subtab:ifollow', 	 'css_class' => (($subtab === 'ifollow')? ' active' : ''), 		'title' => $this->lang('usr_left_follows') ),
										array('url' => $u->username.'/tab:friends/subtab:followers', 'css_class' => (($subtab === 'followers')? ' active' : ''), 		'title' => $this->lang('usr_left_followers') ),
						);*/
						if( !$is_my_profile ){
							$menu[] = array('url' => $u->username.'/tab:friends/subtab:incommon',  'css_class' => (($subtab === 'incommon')? ' active' : ''), 	'title' => $this->lang('usr_coleagues_subtab3') );
						}
							
						$tpl->layout->setVar( 'main_content_placeholder', $tpl->designer->createMenu( 'tabs-navigation', $menu ) ); unset($menu);
						
	 					$activity = activityFactory::select('user');
						$activity->setTemplate( $tpl );
	 					$activity->setUser( $u );
	 					$activity->loadUsers();
	 					
	 					$tpl->layout->setVar( 'main_content_bottom', $tpl->designer->pager( $activity->num_results, $activity->num_pages, $activity->pg, $paging_url ) );
					}else{
						$tpl->layout->setVar('main_content', $tpl->designer->createNoPostBox($this->lang('noposts_usrprofileprotected_ttl'), $this->lang('post_profile_protected')));
					}
					
					break;
					
		case 'groups':
					if( !$is_profile_protected ){
						$activity = activityFactory::select('user');
						$activity->setTemplate( $tpl );
						$activity->setUser( $u );
						$activity->loadGroups();
						
						$tpl->layout->setVar( 'main_content_bottom', $tpl->designer->pager( $activity->num_results, $activity->num_pages, $activity->pg, $paging_url ) );
					}else{
						$tpl->layout->setVar('main_content', $tpl->designer->createNoPostBox($this->lang('noposts_usrprofileprotected_ttl'), $this->lang('post_profile_protected')));
					}
					break;
		case 'profile':
				if( !$is_profile_protected ){
								$activity = activityFactory::select('user');
								$activity->setTemplate( $tpl );
								$activity->setUser( $u );
								$activity->loadProfile();
								
				}
							break;
		case 'likes':
			if( !$is_posts_protected ){
						$tpl->useStaticHTML();
						$tpl->staticHTML->useActivityContainer();
						
						$activity = activityFactory::select('dashboard');
						$activity->setTemplate( $tpl );
						$activity->setUser( $u );
						$result = $activity->loadPosts();
						//
						if( $this->user->is_logged && isset($result[1]) && $result[1] > 0 ){
							$tpl->layout->useBlock('activity-show-more');
							$tpl->layout->setVar('activities_pager_value', htmlentities('{"activities_type":"user","activities_id":"'.$result[1].'","activities_user":"'.$u->id.'"}'));
							$tpl->layout->block->save('activity_container_show_more');
						}
					}else{
						$tpl->layout->setVar('main_content', $tpl->designer->createNoPostBox($this->lang('noposts_usrprofileprotected_ttl'), $this->lang('noposts_usrprofileprotected_txt', array('#USERNAME#'=>$u->username))));
					}
							break;
							case 'bookmarks':
			if( !$is_posts_protected ){
						$tpl->useStaticHTML();
						$tpl->staticHTML->useActivityContainer();
						
						$activity = activityFactory::select('dashboard');
						$activity->setTemplate( $tpl );
						$activity->setUser( $u );
						$result = $activity->loadPosts();
						//
						if( $this->user->is_logged && isset($result[1]) && $result[1] > 0 ){
							$tpl->layout->useBlock('activity-show-more');
							$tpl->layout->setVar('activities_pager_value', htmlentities('{"activities_type":"user","activities_id":"'.$result[1].'","activities_user":"'.$u->id.'"}'));
							$tpl->layout->block->save('activity_container_show_more');
						}
					}else{
						$tpl->layout->setVar('main_content', $tpl->designer->createNoPostBox($this->lang('noposts_usrprofileprotected_ttl'), $this->lang('noposts_usrprofileprotected_txt', array('#USERNAME#'=>$u->username))));
					}
							break;
                          case 'agree':
			if( !$is_posts_protected ){
						$tpl->useStaticHTML();
						$tpl->staticHTML->useActivityContainer();
						
						$activity = activityFactory::select('dashboard');
						$activity->setTemplate( $tpl );
						$activity->setUser( $u );
						$result = $activity->loadPosts();
						//
						if( $this->user->is_logged && isset($result[1]) && $result[1] > 0 ){
							$tpl->layout->useBlock('activity-show-more');
							$tpl->layout->setVar('activities_pager_value', htmlentities('{"activities_type":"user","activities_id":"'.$result[1].'","activities_user":"'.$u->id.'"}'));
							$tpl->layout->block->save('activity_container_show_more');
						}
					}else{
						$tpl->layout->setVar('main_content', $tpl->designer->createNoPostBox($this->lang('noposts_usrprofileprotected_ttl'), $this->lang('noposts_usrprofileprotected_txt', array('#USERNAME#'=>$u->username))));
					}
							break;
                                case 'intraday':
			if( !$is_posts_protected ){
                                                $menu = array( 	array('url' => $u->username.'/tab:intraday/subtab:correct', 	 'css_class' => (($subtab === 'correct')? ' active' : ''), 		'title' =>'Correct('.$correctper.'%)' ),
										array('url' => $u->username.'/tab:intraday/subtab:incorrect', 'css_class' => (($subtab === 'incorrect')? ' active' : ''), 		'title' =>'In Correct('.$incorrectper.'%)' ),
						);
						$tpl->layout->setVar( 'main_content_placeholder', $tpl->designer->createMenu( 'tabs-navigation', $menu ) ); unset($menu);
						$tpl->useStaticHTML();
						$tpl->staticHTML->useActivityContainer();
						
						$activity = activityFactory::select('dashboard');
						$activity->setTemplate( $tpl );
						$activity->setUser( $u );
						$result = $activity->loadPosts();
						//
						if( $this->user->is_logged && isset($result[1]) && $result[1] > 0 ){
							$tpl->layout->useBlock('activity-show-more');
							$tpl->layout->setVar('activities_pager_value', htmlentities('{"activities_type":"user","activities_id":"'.$result[1].'","activities_user":"'.$u->id.'"}'));
							$tpl->layout->block->save('activity_container_show_more');
						}
					}else{
						$tpl->layout->setVar('main_content', $tpl->designer->createNoPostBox($this->lang('noposts_usrprofileprotected_ttl'), $this->lang('noposts_usrprofileprotected_txt', array('#USERNAME#'=>$u->username))));
					}
				break;



						
		default: 
					/*$menu = array( 	
								array('url' => $u->username.'/tab:updates/subtab:all', 		'css_class' => (($subtab === 'all')? ' active' : ''), 		'title' => $this->lang('tab_user_all') ),
							);
							*/
				
					
					$tpl->layout->setVar( 'main_content_placeholder', $tpl->designer->createMenu( 'tabs-navigation', $menu, 'user_subtab_menu' ) ); unset($menu);
					
					if( !$is_posts_protected ){
						$tpl->useStaticHTML();
						$tpl->staticHTML->useActivityContainer();
						
						$activity = activityFactory::select('user');
						$activity->setTemplate( $tpl );
						$activity->setUser( $u );
						$result = $activity->loadPosts();
						//
						if( $this->user->is_logged && isset($result[1]) && $result[1] > 0 ){
							$tpl->layout->useBlock('activity-show-more');
							$tpl->layout->setVar('activities_pager_value', htmlentities('{"activities_type":"user","activities_id":"'.$result[1].'","activities_user":"'.$u->id.'"}'));
							$tpl->layout->block->save('activity_container_show_more');
						}
					}else{
						$tpl->layout->setVar('main_content', $tpl->designer->createNoPostBox($this->lang('noposts_usrprofileprotected_ttl'), $this->lang('noposts_usrprofileprotected_txt', array('#USERNAME#'=>$u->username))));
					}
					
					break;
	}
	
	$tpl->display();
?>