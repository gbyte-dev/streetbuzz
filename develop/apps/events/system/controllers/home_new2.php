<?php

	global $page,$C, $db2, $user, $network;
					

	$page->load_langfile('inside/global.php');
	$page->load_langfile('inside/dashboard.php');
	$page->load_langfile('inside/group.php');
	$page->load_langfile('inside/dashboard.php');
	$page->load_langfile('inside/groups_new.php');
	$page->load_langfile('inside/user.php');
        
        require $C->INCPATH.'helpers/func_html.php';

	if(!empty($page->params->action) && $page->params->action=='list_events' ){
		
		//TEMPLATE CODE START
		$add_link ='';
		
		if(empty($page->params->group)){
			$add_link = '';
			if($page->params->user == $user->id && $page->param('username')){
				
				$u = $this->network->get_user_by_id(intval($page->params->user));
				if( !$u ){
					$page->redirect('dashboard');
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
				if( $page->param('tab') ){
					$tab = $page->param('tab');
				}
				
				$subtab = 'all';
				if( $page->param('subtab') ){
					$subtab = $page->param('subtab');
				}
				
				if($tab =='friends' && $subtab != 'ifollow' && $subtab !='followers' && $subtab !='incommon'){
					$subtab = 'ifollow';
				}
				
				$paging_url	= $C->SITE_URL.$u->username.'/tab:'.$tab.'/subtab:'.$subtab.'/pg:';
				
				$udtls = $this->network->get_user_details_by_id(intval($page->params->user));
				$udtls = ($udtls === FALSE || empty($udtls))? array() : $udtls;

				//TEMPLATE START 
				$tpl = new template( array('page_title' => $u->username. ' - ' .$C->SITE_TITLE, 'header_page_layout'=>'sc') );
				
				$tpl->initRoutine('UserLeftColumn', array( &$u, &$he_follows ));
				$tpl->routine->load();

				$menu = array( 	array('url' => $u->username.'/tab:updates', 	'css_class' => (($tab === 'updates')? ' selected' : ''), 	'title' => $page->lang('usr_tab_updates') ),
				);
				
				if( !$is_profile_protected ){
					$menu[] = array('url' => $u->username.'/tab:info', 		'css_class' => (($tab === 'info')? ' selected' : ''), 		'title' => $page->lang('usr_tab_info') );
					$menu[] = array('url' => $u->username.'/tab:friends', 	'css_class' => (($tab === 'friends')? ' selected' : ''), 	'title' => $page->lang('usr_tab_coleagues') );
					$menu[] = array('url' => $u->username.'/tab:groups', 	'css_class' => (($tab === 'groups')? ' selected' : ''), 	'title' => $page->lang('usr_tab_groups') );
					$menu[] = array('url' => $C->SITE_URL . $user->info->username.'/tab:events/username:'.$u->username, 	'css_class' => (($page->params->username)? ' selected' : ''), 	'title' => 'Events' );
				}
				
				$tpl->layout->setVar( 'subheader_placeholder', createMenu( 'navigation', $menu ) ); unset($menu);

				$tpl->layout->useBlock('user-header-info');
				$tpl->layout->block->setVar('user_header_username', getThisUserCommunityName($u));	
				$tpl->layout->block->setVar('user_header_position', htmlspecialchars($u->location)); //should be position
				$tpl->layout->block->setVar('user_header_activity', $page->lang('usr_top_activity_count', array('#NUM_FOLLOWERS#'=>$u->num_followers, '#NUM_FOLLOWING#'=>count($he_follows), '#NUM_POSTS#'=>$u->num_posts )));

				if( $this->user->is_logged ){
					$tpl->layout->block->setVar('user_header_follow_button', $is_my_profile? '' : 
												(!in_array($u->id, $i_follow)? usersSettingsMenu($u->id, true) : usersSettingsMenu($u->id, false)) 
					);
				}
				
				$tpl->layout->block->save('main_content_top_placeholder', true);
				$add_link .= '<a href="' . $C->SITE_URL . $user->info->username.'/tab:events"  title="Calendar View "  class="calander-view btn btn-primary"><span>Calendar View</span></a>';
				//$add_link.= '<a href="' . $C->SITE_URL . $user->info->username.'/tab:events/action:add_event" class="btn blue calander-add calander-view" title="Create Event" ><span>Create Event</span></a>';

			}else{		

				$add_link .= '<a href="' . $C->SITE_URL . $user->info->username.'/tab:events"  title="Calendar View" class="btn btn-primary calander-view" ><span>Calendar View</span></a>';
				//$add_link .= '<a href="' . $C->SITE_URL . $user->info->username.'/tab:events/action:add_event" title="Calendar Create"  class="btn blue calander-view calander-add"><span>Create Event</span></a>';
				$event_content = '<h1 style="margin:10px; clear:both;">Event List</h1><div class="break"></div>';	

			}
				
		}elseif(empty($page->params->group) && $page->params->user == $user->id){
				
				$u = $this->network->get_user_by_id(intval($page->params->user));
				if( !$u ){
					$page->redirect('dashboard');
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
				if( $page->param('tab') ){
					$tab = $page->param('tab');
				}
				
				$subtab = 'all';
				if( $page->param('subtab') ){
					$subtab = $page->param('subtab');
				}
				
				if($tab =='friends' && $subtab != 'ifollow' && $subtab !='followers' && $subtab !='incommon'){
					$subtab = 'ifollow';
				}
				
				$paging_url	= $C->SITE_URL.$u->username.'/tab:'.$tab.'/subtab:'.$subtab.'/pg:';
				
				$udtls = $this->network->get_user_details_by_id(intval($page->params->user));
				$udtls = ($udtls === FALSE || empty($udtls))? array() : $udtls;

				//TEMPLATE START 
				$tpl = new template( array('page_title' => $u->username. ' - ' .$C->SITE_TITLE, 'header_page_layout'=>'sc') );
				
				$tpl->initRoutine('UserLeftColumn', array( &$u, &$he_follows ));
				$tpl->routine->load();

				$menu = array( 	array('url' => $u->username.'/tab:updates', 	'css_class' => (($tab === 'updates')? ' selected' : ''), 	'title' => $page->lang('usr_tab_updates') ),
				);
				
				if( !$is_profile_protected ){
					$menu[] = array('url' => $u->username.'/tab:info', 		'css_class' => (($tab === 'info')? ' selected' : ''), 		'title' => $page->lang('usr_tab_info') );
					$menu[] = array('url' => $u->username.'/tab:friends', 	'css_class' => (($tab === 'friends')? ' selected' : ''), 	'title' => $page->lang('usr_tab_coleagues') );
					$menu[] = array('url' => $u->username.'/tab:groups', 	'css_class' => (($tab === 'groups')? ' selected' : ''), 	'title' => $page->lang('usr_tab_groups') );
					
				}
				
				$tpl->layout->setVar( 'subheader_placeholder', createMenu( 'navigation', $menu ) ); unset($menu);

				$tpl->layout->useBlock('user-header-info');
				$tpl->layout->block->setVar('user_header_username', getThisUserCommunityName($u));	
				$tpl->layout->block->setVar('user_header_position', htmlspecialchars($u->location)); //should be position
				$tpl->layout->block->setVar('user_header_activity', $page->lang('usr_top_activity_count', array('#NUM_FOLLOWERS#'=>$u->num_followers, '#NUM_FOLLOWING#'=>count($he_follows), '#NUM_POSTS#'=>$u->num_posts )));
				
				if( $this->user->is_logged ){
					$tpl->layout->block->setVar('user_header_follow_button', $is_my_profile? '' : 
												(!in_array($u->id, $i_follow)? usersSettingsMenu($u->id, true) : usersSettingsMenu($u->id, false)) 
					);
				}
				
				$tpl->layout->block->save('main_content_top_placeholder', true);
		}else{
			$g	= $this->network->get_group_by_id(intval($page->params->group), true);
			
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
			$add_link .= '';
			$add_link .= '<a href="' . $C->SITE_URL .$C->SITE_URL . $user->info->username.'/tab:events/group:'.$g->id.'"  title="Calendar View"  class="btn btn-primary calander-view"><span>Calendar View</span></a>';
			
			if(!empty($i_am_admin)){
				$add_link .= '<a href="' . $C->SITE_URL . $user->info->username.'/tab:events/action:add_event/group:'.$g->id.'" title="Add Event"  class="btn blue calander-view calander-add"><span>Create Event</span></a>';
			}

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
								<span>'.$page->lang('group_left_invite_btn').'</span>
							</span>
						</a>
					</div>
					<div class="clear"></div>
				</div>';
			}
			
			$menu_items = array(
				array(
					'url'=> '#', 
					'text'=> $page->lang('grp_toplnks_unfollow'), 
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
			$tpl->layout->block->setVar('group_header_activity', $page->lang('group_header_descr_activity', array('#NUM_MEMBERS#'=> $g->num_followers, '#NUM_POSTS#'=>$g->num_posts) ) );
			
			if(!empty($page->params->group)){
				if( $user->is_logged ){

					if( $this->user->is_logged ){
						if($i_am_member == true ){ 
							$tpl->layout->block->setVar(
									'group_header_settings_button', 
									dropDownMenu("Settings", $menu_items, '', 'action-btn options', true)
							); 
						} else {
							$tpl->layout->block->setVar(
									'group_header_settings_button',
									'<a class="action-btn user-action add" data-action="join" data-value="'.$g->id.'" data-namespace="groups" data-role="services"><span class="tooltip"><span>'.$page->lang('grp_toplnks_follow').'</span></a>'
							);
						}
						unset($menu_items);
					} 

					$tpl->layout->block->save('main_content_top_placeholder', true);		
				}
				$tpl->layout->setVar( 'left_content', 	
					
						createInfoBlock('',
								'<img src="'.$C->STORAGE_URL.'avatars/'. (empty($g->avatar)? $C->DEF_AVATAR_GROUP : $g->avatar).'" alt="'.$g->groupname.'">'.
								'<div class="group-description">'.$g->about_me.'</div>'.
								'<div class="group-statistics">
									<strong>'.$g->num_followers.'</strong> '.$page->lang('grp_tab_members').' <br />'.
									'<strong>'.$g->num_posts.'</strong> '.$page->lang('usrlist_numposts').'
								</div>'.
								'<div class="recent-visitors">'.
									'<h3 class="sub-title">'.$page->lang('group_latest_members').'</h3>'.
									createUserLinks( $group->getGroupMembers($group_members), 'thumbs3' ).
									$invite_button .
									'<div class="clear"></div>
								</div>
								'
						)													
				);
				$tab='';
				$menu = array( 	array('url' => $g->groupname.'/tab:updates', 	'css_class' => (($tab === 'updates')? ' selected' : ''), 	'title' => $page->lang('grp_tab_updates') ),
								array('url' => $g->groupname.'/tab:members', 	'css_class' => (($tab === 'members')? ' selected' : ''), 	'title' => $page->lang('grp_tab_members') ),
				);
				
				$tpl->layout->setVar( 'subheader_placeholder', createMenu( 'navigation', $menu, 'group_navigation_top_menu' ) ); 
				unset($menu);		
				$tpl->layout->setVar( 'main_content_placeholder', createMenu('tabs-navigation', $menu, 'groups_top_tab_menu') );
				
			}		
		}
		
		$this->setVar('main_content_placeholder', $add_link, 'replace' );
			
		if(!empty($page->params->group)){

			$num_results = $db2->fetch_field('SELECT count(*) FROM events WHERE ( display_type="community"  OR group_id = "'.$page->params->group.'" ) AND `start_date` >= CURDATE()');			

			$num_pages	= ceil($num_results / $C->PAGING_NUM_GROUPS); 
			$pg	= $page->param('pg') ? intval($page->param('pg')) : 1;
			$pg	= min($pg, $num_pages);
			$pg	= max($pg, 1);
			$from	= ($pg - 1) * $C->PAGING_NUM_GROUPS;

			$paging_url = $C->SITE_URL . $user->info->username.'/tab:events/action:list_events/group:'.$page->params->group.'/pg:';		
			$res = $db2->query('SELECT * FROM events WHERE 
			( display_type="community" OR group_id = "'.$page->params->group.'" ) AND `start_date` >= CURDATE() ORDER BY start_date LIMIT '.$from.', '.$C->PAGING_NUM_USERS);
                        
        }elseif(!empty($page->params->username)){

			$groups = $this->network->get_user_follows($this->user->id, '', 'hisgroups');
			if(!empty($groups->follow_groups)){
				$follow_group = $groups->follow_groups;
				$groups = array_flip($follow_group);
				$group_ids = implode(',', $groups);
			}else{
				$group_ids = 0;
			}
			$num_results = $db2->fetch_field('SELECT count(*) FROM events WHERE (display_type="community" OR (display_type="group" AND group_id IN ('.$group_ids.'))) AND `start_date` >= CURDATE() ORDER BY start_date');
			$num_pages	= ceil($num_results / $C->PAGING_NUM_GROUPS); 
			$pg	= $page->param('pg') ? intval($page->param('pg')) : 1;
			$pg	= min($pg, $num_pages);
			$pg	= max($pg, 1);
			$from	= ($pg - 1) * $C->PAGING_NUM_GROUPS;	

			$paging_url = $C->SITE_URL . $user->info->username.'/tab:events/action:list_events/username:'.$page->params->username.'/pg:';		
			//$res = $db2->query('SELECT * FROM events WHERE (display_type="community" OR (display_type="group" AND group_id IN ('.$group_ids.')))  ORDER BY FIELD(status,1,2,0), created_at DESC LIMIT '.$from.', '.$C->PAGING_NUM_USERS);
			//$res = $db2->query('SELECT * FROM events WHERE admin_id="'.$this->user->id.'" AND status=1 ORDER BY created_at DESC LIMIT '.$from.', '.$C->PAGING_NUM_USERS);
			$res = $db2->query('SELECT * FROM events WHERE (display_type="community" OR (display_type="group" AND group_id IN ('.$group_ids.'))) AND `start_date` >= CURDATE() ORDER BY start_date LIMIT '.$from.', '.$C->PAGING_NUM_USERS);
		
		}else{

			$groups = $this->network->get_user_follows($this->user->id, '', 'hisgroups');
			if(!empty($groups->follow_groups)){
				$follow_group = $groups->follow_groups;
				$groups = array_flip($follow_group);
				$group_ids = implode(',', $groups);
			}
			
			$pg	= $page->param('pg') ? intval($page->param('pg')) : 1;
			$remove_ids = array();
			
			$now_date = date('Y-m-d H:i:s');
			
			if( $pg == 1 ){
				
				// get all ids between start, now and end date
				$content_between = '';
				$get_dates_ids = $db2->query('SELECT * FROM events WHERE 
				(display_type="community" OR (display_type="group" AND group_id IN ('.$group_ids.'))) AND
				CONCAT(`start_date`," ",`start_time`) < "'.$now_date.'" AND 
				CONCAT(`end_date`," ",`end_time`) > "'.$now_date.'" ORDER BY start_date, start_time');
				
				if( $db2->num_rows($get_dates_ids) > 0 ){
					while($get_dates_obj = $db2->fetch_object($get_dates_ids)){
						if(!empty($page->params->group)){
				
								$title = '';
								if($get_dates_obj->event_type=='major'){
									$title = '<img class="tBoxImg" src="'.$C->SITE_URL.'apps/events/static/images/major-event.png" />';
								}
								$title_link = "<div class='tBox'>$title<a class='view' title='view event' data='$get_dates_obj->id' href='".$C->SITE_URL . "plugin/events/view/id:$get_dates_obj->id/gr:".$page->params->group."'>".$get_dates_obj->event_name."</a></div>";				
						}else{
								$title = '';
								if($get_dates_obj->event_type=='major'){
									$title = '<img class="tBoxImg" src="'.$C->SITE_URL.'apps/events/static/images/major-event.png" />';
								}
								if(!empty($page->params->username)){
									$title_link = "<div class='tBox'>$title<a class='view' title='view event' data='$get_dates_obj->id' href='".$C->SITE_URL . "plugin/events/view/id:$get_dates_obj->id/username:".$page->params->username."'>".$get_dates_obj->event_name."</a></div>";
								}else{
									$title_link = "<div class='tBox'>$title<a class='view' title='view event' data='$get_dates_obj->id' href='".$C->SITE_URL . "plugin/events/view/id:$get_dates_obj->id'>".$get_dates_obj->event_name."</a></div>";
								}
						}
						$url = '';
						$url.='<a class="outlookExport" title ="Outlook Export" href="' . $C->SITE_URL .'plugin/events/ical/event:'.$get_dates_obj->id.'"><img src="'.$C->SITE_URL.'apps/events/static/images/o-export.png" ></a>';
						if(!empty($setting->google_app_key) && !empty($setting->google_secret_key)){
								$url.='<a class="googleExport" title ="Google Export"  href="' . $C->SITE_URL .'plugin/events/google_sync/event:'.$get_dates_obj->id.'" ><img src="'.$C->SITE_URL.'apps/events/static/images/g-export.png" ></a>';
						}
						if(!empty($setting->facebook_app_key) && !empty($setting->facebook_secret_key)){
								$url.='<a class="facebookExport" title ="Facebook Export"  href="' . $C->SITE_URL .'plugin/events/facebook_sync/event:'.$get_dates_obj->id.'"><img width="35px" src="'.$C->SITE_URL.'apps/events/static/images/f-export.png" ></a>';
						}
						
						$remove_ids[] = $get_dates_obj->id;
						$address = $get_dates_obj->address;
						$start_date = date('M d, Y', strtotime($get_dates_obj->start_date));
						$start_time = date('h:i A', strtotime($get_dates_obj->start_time));
						$end_date   = date('M d, Y', strtotime($get_dates_obj->end_date));
						$end_time   = date('h:i A', strtotime($get_dates_obj->end_time));
						$view_link  = $url;
						$status = ($get_dates_obj->status == 1)?'Active':(($get_dates_obj->status == 2)?'Cancelled':'Expired');

						if( !empty($get_dates_obj->group_id) ){
							
							$get_group_name = $db2->fetch_object($db2->query('SELECT title FROM groups WHERE id = "'.(int)$get_dates_obj->group_id.'"'));
							if( !empty($get_group_name->title) ){
								$title_link = $title_link . '<div class="in_group"> in '.$get_group_name->title .'</div>';
							}
							
						}

						$content_between .= '
						<div class="eventsList today_event">
							<ul class="eList box">
								<li>
									<div class="TE-txt met-list">
										<div class="title">'.$title_link.' </div>
										<div class="eDetails" >
										<span><p><span><span>'.$address.'</span></span></p></span>
										<span class="time"><time>'.$start_date.'</time> &nbsp; <b>|</b> &nbsp;<span class="c">'. $start_time .'</span></span>
										</div>
										<div class="title" >Status: '.$status.'</div>
									</div>
									<div class="btn-row addthis_default_style addthis_toolbox">'.$view_link.'<div class="atclear" tabindex="1000"></div>
									</div>
								</li>	
							</ul>
						</div>';	
					}
				}
				
			}
			
			$additional_sql = ( empty($remove_ids) ? '' : ' AND id NOT IN('.implode(',', $remove_ids).') ' );
			
			$num_results = $db2->fetch_field(' SELECT count(*)
                FROM events  AS e
				inner join event_posts as ep ON ep.event_id	=e.id
				inner join post_userbox as pu ON pu.post_id	=ep.post_id

			 WHERE pu.user_id="'.(int)$user->id.'" AND pu.event_status=1 AND e.status=1 AND   (e.display_type="community" OR (e.display_type="group" AND e.group_id IN ('.$group_ids.'))) AND CONCAT(`start_date`," ",`start_time`) >= "'.$now_date.'" '.$additional_sql);
			$num_pages	= ceil($num_results / $C->PAGING_NUM_GROUPS);
			$pg	= min($pg, $num_pages);
			$pg	= max($pg, 1);
			$from	= ($pg - 1) * $C->PAGING_NUM_GROUPS;	
			// AND `start_date` >= CURDATE()
			$paging_url = $C->SITE_URL . $user->info->username.'/tab:events/action:list_events/pg:';		
			$res = $db2->query('SELECT e.id,e.event_type,e.event_name,e.event_description,e.address,e.start_date,e.end_date,e.start_time,e.end_time,e.status
                FROM events  AS e
				inner join event_posts as ep ON ep.event_id	=e.id
				inner join post_userbox as pu ON pu.post_id	=ep.post_id
			
			WHERE  pu.user_id="'.(int)$user->id.'" AND pu.event_status=1 AND e.status=1 AND 
			(e.display_type="community" OR (e.display_type="group" AND e.group_id IN ('.$group_ids.'))) AND 
			CONCAT(`start_date`," ",`start_time`) >= "'.$now_date.'" '.$additional_sql.' ORDER BY e.start_date, e.start_time LIMIT '.$from.', '.$C->PAGING_NUM_USERS);

		}
		
		$res_new = $db2->query('SELECT * FROM event_settings LIMIT 1');
		$setting = $db2->fetch_object($res_new);
        $calander_list ='';
		
		if( $db2->num_rows($res) > 0 )
		{
			if(!empty( $event_content )){
				$calander_list .= $event_content;    
			}
			if(!empty( $content_between )){
				$calander_list .= $content_between;    
			}
			while($obj = $db2->fetch_object($res))
			{ 
				if(!empty($page->params->group)){
						//$url = "<div style='float:left; width:100%;'><a class='view' title='view event' data='$obj->id' href='".$C->SITE_URL."plugin/events/view/id:$obj->id/group:".$page->params->group."'>View</a></div>";				
						$title = '';
						if($obj->event_type=='major'){
								$title = '<img class="tBoxImg" src="'.$C->SITE_URL.'apps/events/static/images/major-event.png" />';
						}
						$title_link = "<div class='tBox'>$title<a class='view' title='view event' data='$obj->id' href='".$C->SITE_URL . "plugin/events/view/id:$obj->id/gr:".$page->params->group."'>".$obj->event_name."</a></div>";				
				}else{
						$title = '';
						if($obj->event_type=='major'){
								$title = '<img class="tBoxImg" src="'.$C->SITE_URL.'apps/events/static/images/major-event.png" />';
						}
						if(!empty($page->params->username)){
						//	$url = "<div style='float:left; width:100%;'><a class='view' title='view event' data='$obj->id' href='".$C->SITE_URL."plugin/events/view/id:$obj->id/username:".$page->params->username."'>View</a></div>";
							$title_link = "<div class='tBox'>$title<a class='view' title='view event' data='$obj->id' href='".$C->SITE_URL . "plugin/events/view/id:$obj->id/username:".$page->params->username."'>".$obj->event_name."</a></div>";
						}else{
						//	$url = "<div style='float:left; width:100%;'><a class='view' title='view event' data='$obj->id' href='".$C->SITE_URL."plugin/events/view/id:$obj->id'>View</a></div>";
							$title_link = "<div class='tBox'>$title<a class='view' title='view event' data='$obj->id' href='".$C->SITE_URL . "plugin/events/view/id:$obj->id'>".$obj->event_name."</a></div>";
						}
				}
				$url = '';
				$url.='<a class="outlookExport" title ="Outlook Export" href="' . $C->SITE_URL .'plugin/events/ical/event:'.$obj->id.'"><img src="'.$C->SITE_URL.'apps/events/static/images/o-export.png" ></a>';
				if(!empty($setting->google_app_key) && !empty($setting->google_secret_key)){
						$url.='<a class="googleExport" title ="Google Export"  href="' . $C->SITE_URL .'plugin/events/google_sync/event:'.$obj->id.'" ><img src="'.$C->SITE_URL.'apps/events/static/images/g-export.png" ></a>';
				}
				if(!empty($setting->facebook_app_key) && !empty($setting->facebook_secret_key)){
						$url.='<a class="facebookExport" title ="Facebook Export"  href="' . $C->SITE_URL .'plugin/events/facebook_sync/event:'.$obj->id.'"><img width="35px" src="'.$C->SITE_URL.'apps/events/static/images/f-export.png" ></a>';
				}

				$address = $obj->address;
				$start_date = date('M d, Y', strtotime($obj->start_date));
				$start_time = date('h:i A', strtotime($obj->start_time));
				$end_date   = date('M d, Y', strtotime($obj->end_date));
				$end_time   = date('h:i A', strtotime($obj->end_time));
				$view_link  = $url;
				$status = ($obj->status == 1)?'Active':(($obj->status == 2)?'Cancelled':'Expired');

				if( !empty($obj->group_id) ){
					
					$get_group_name = $db2->fetch_object($db2->query('SELECT title FROM groups WHERE id = "'.(int)$obj->group_id.'"'));
					if( !empty($get_group_name->title) ){
						$title_link = $title_link . '<div class="in_group"> in '.$get_group_name->title .'</div>';
					}
					
				}
				
				$calemdar_class = '';
				if( !empty($ids_between) && in_array($obj->id, $ids_between) ){
					$calemdar_class = ' today_event';
				}

				$calander_list .= '
				<div class="eventsList'.$calemdar_class.'">
					<ul class="eList box">
						<li>
							<div class="TE-txt met-list">
								<div class="title">'.$title_link.' </div>
								<div class="eDetails" >
								<span><p><span><span>'.$address.'</span></span></p></span>
								<span class="time"><time>'.$start_date.'</time> &nbsp; <b>|</b> &nbsp;<span class="c">'. $start_time .'</span></span>
								</div>
								<div class="title" >Status: '.$status.'</div>
							</div>
							<div class="btn-row addthis_default_style addthis_toolbox">'.$view_link.'<div class="atclear" tabindex="1000"></div>
							</div>
						</li>	
					</ul>
				</div>';


			}

			$this->setVar( 'main_content', $calander_list );
			$this->setVar( 'main_content_bottom', pager( $num_results, $num_pages, $pg, $paging_url ) );
			
		}else{
			$this->setVar( 'main_content_bottom', 'No Events Found');
		}	
		
	}
	elseif(!empty($page->params->action) && $page->params->action=='add_event' ){
		
		require_once($C->PLUGINS_DIR.'events/system/controllers/add_event_new.php');
		
	}
	elseif(!empty($page->params->action) && $page->params->action=='edit_event' ){

		require_once($C->PLUGINS_DIR.'events/system/controllers/edit_event_new.php');
		
	}
	elseif(!empty($page->params->action) && $page->params->action=='view' ){
		require_once($C->PLUGINS_DIR.'events/system/controllers/view_new.php');
	}
	elseif(!empty($page->params->group)){
		// var_dump( 222222 ); exit;

		/*
		$g	= $this->network->get_group_by_id(intval($page->params->group), true);
		
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
		$tpl = new template( array('page_title' => $g->groupname. ' - ' .$C->SITE_TITLE, 'header_page_layout'=>'sc') );
		
		$invite_button = '';
		if( $if_can_invite ){
			$invite_button =
			'
			<div>
				<div class="options-container" align="center">
					<a href="' . $C->SITE_URL . $g->groupname .'/invite" class="action-btn user-action add" style="float:left; margin:0px;">
						<span class="tooltip">
							<span>'.$page->lang('group_left_invite_btn').'</span>
						</span>
					</a>
				</div>
				<div class="clear"></div>
			</div>';
		}
		
		$menu_items = array(
			array(
				'url'=> '#', 
				'text'=> $page->lang('grp_toplnks_unfollow'), 
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
		$tpl->layout->block->setVar('group_header_activity', $page->lang('group_header_descr_activity', array('#NUM_MEMBERS#'=> $g->num_followers, '#NUM_POSTS#'=>$g->num_posts) ) );
		
		if(!empty($page->params->group)){
			if( $user->is_logged ){

				if( $this->user->is_logged ){
					if($i_am_member == true ){ 
						$tpl->layout->block->setVar(
								'group_header_settings_button', 
								dropDownMenu("Settings", $menu_items, '', 'action-btn options', true)
						); 
					} else {
						$tpl->layout->block->setVar(
								'group_header_settings_button',
								'<a class="action-btn user-action add" data-action="join" data-value="'.$g->id.'" data-namespace="groups" data-role="services"><span class="tooltip"><span>'.$page->lang('grp_toplnks_follow').'</span></a>'
						);
					}
					unset($menu_items);
				} 

				$tpl->layout->block->save('main_content_top_placeholder', true);		
			}
			$tpl->layout->setVar( 'left_content', 	
				
					createInfoBlock('',
							'<img src="'.$C->STORAGE_URL.'avatars/'. (empty($g->avatar)? $C->DEF_AVATAR_GROUP : $g->avatar).'" alt="'.$g->groupname.'">'.
							'<div class="group-description">'.$g->about_me.'</div>'.
							'<div class="group-statistics">
								<strong>'.$g->num_followers.'</strong> '.$page->lang('grp_tab_members').' <br />'.
								'<strong>'.$g->num_posts.'</strong> '.$page->lang('usrlist_numposts').'
							</div>'.
							'<div class="recent-visitors">'.
								'<h3 class="sub-title">'.$page->lang('group_latest_members').'</h3>'.
								createUserLinks( $group->getGroupMembers($group_members), 'thumbs3' ).
								$invite_button .
								'<div class="clear"></div>
							</div>
							'
					)													
			);
			$tab='';
			$menu = array( 	array('url' => $g->groupname.'/tab:updates', 	'css_class' => (($tab === 'updates')? ' selected' : ''), 	'title' => $page->lang('grp_tab_updates') ),
							array('url' => $g->groupname.'/tab:members', 	'css_class' => (($tab === 'members')? ' selected' : ''), 	'title' => $page->lang('grp_tab_members') ),
			);
			
			$tpl->layout->setVar( 'subheader_placeholder', createMenu( 'navigation', $menu, 'group_navigation_top_menu' ) ); 
			unset($menu);		
			$tpl->layout->setVar( 'main_content_placeholder', createMenu('tabs-navigation', $menu, 'groups_top_tab_menu') );
			
		}
		
		$table = new tableCreator();
		$rows = array(
			$table->hiddenField( 'group_id', $page->params->group)
		);
		$add_link ='';
		$add_link .= '<a href="' . $C->SITE_URL .$C->SITE_URL . $user->info->username.'/tab:events/tab:list_events/group:'.$page->params->group.'"  title="List View"  class="btn btn-primary calander-view"><span>List View</span></a>';
		if(!empty($i_am_admin)){
			$add_link .= '<a href="' . $C->SITE_URL . $user->info->username.'/tab:events/action:add_event/group:'.$page->params->group.'" title="Create Event"  class="btn blue calander-add calander-view"><span>Create Event</span></a>';
		}

		$tpl->layout->setVar('add_link', $add_link );
		$tpl->layout->setVar('main_content', $table->createTableInput( $rows ) );
		
		$tpl->layout->useBlock('calander_template', 'events');
		$tpl->layout->block->save('main_content');
		
		$tpl->display();
		*/

		
		$table = new tableCreator();
		$rows = array(
			$table->hiddenField( 'group_id', $page->params->group)
		);
		$add_link = '<a href="' . $C->SITE_URL .$C->SITE_URL . $user->info->username.'/tab:events/tab:list_events/group:'.$page->params->group.'"  title="List View"  class="btn btn-primary calander-view"><span>List View</span></a>';
		$i_am_admin = ( $this->user->is_logged && $this->user->info->is_network_admin > 0 );
		if(!empty($i_am_admin)){
			$add_link .= '<a href="' . $C->SITE_URL . $user->info->username.'/tab:events/action:add_event/group:'.$page->params->group.'" title="Create Event"  class="btn blue calander-add calander-view"><span>Create Event</span></a>';
		}

		// get template
		ob_start();
		require_once($C->PLUGINS_DIR.'events/static/templates/blocks/calander_template_new.php');
		$calendar_template = ob_get_clean();
		
		$this->setVar( 'main_content', $calendar_template, 'replace');
		$this->setVar( 'main_content_placeholder', '', 'replace');
		$this->setVar( 'main_content_bottom', '', 'replace');
		
	}
	else{

		//TEMPLATE CODE START
		$add_link = '';
		if($page->params->user == $user->id && $page->param('username')){
			
			$u = $this->network->get_user_by_id(intval($page->params->user));
			if( !$u ){
				$page->redirect('dashboard');
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
			if( $page->param('tab') ){
				$tab = $page->param('tab');
			}
			
			$subtab = 'all';
			if( $page->param('subtab') ){
				$subtab = $page->param('subtab');
			}
			
			if($tab =='friends' && $subtab != 'ifollow' && $subtab !='followers' && $subtab !='incommon'){
				$subtab = 'ifollow';
			}
			
			$paging_url	= $C->SITE_URL.$u->username.'/tab:'.$tab.'/subtab:'.$subtab.'/pg:';
			
			$udtls = $this->network->get_user_details_by_id(intval($page->params->user));
			$udtls = ($udtls === FALSE || empty($udtls))? array() : $udtls;

			//TEMPLATE START 
			$tpl = new template( array('page_title' => $u->username. ' - ' .$C->SITE_TITLE, 'header_page_layout'=>'sc') );
			
			$tpl->initRoutine('UserLeftColumn', array( &$u, &$he_follows ));
			$tpl->routine->load();

			$menu = array( 	array('url' => $u->username.'/tab:updates', 	'css_class' => (($tab === 'updates')? ' selected' : ''), 	'title' => $page->lang('usr_tab_updates') ),
			);
			
			if( !$is_profile_protected ){
				$menu[] = array('url' => $u->username.'/tab:info', 		'css_class' => (($tab === 'info')? ' selected' : ''), 		'title' => $page->lang('usr_tab_info') );
				$menu[] = array('url' => $u->username.'/tab:friends', 	'css_class' => (($tab === 'friends')? ' selected' : ''), 	'title' => $page->lang('usr_tab_coleagues') );
				$menu[] = array('url' => $u->username.'/tab:groups', 	'css_class' => (($tab === 'groups')? ' selected' : ''), 	'title' => $page->lang('usr_tab_groups') );
				$menu[] = array('url' => $C->SITE_URL . $user->info->username.'/tab:events/username:'.$u->username, 	'css_class' => (($page->params->username)? ' selected' : ''), 	'title' => 'Events' );
			}
			
			$tpl->layout->setVar( 'subheader_placeholder', createMenu( 'navigation', $menu ) ); unset($menu);

			$tpl->layout->useBlock('user-header-info');
			$tpl->layout->block->setVar('user_header_username', getThisUserCommunityName($u));	
			$tpl->layout->block->setVar('user_header_position', htmlspecialchars($u->location)); //should be position
			$tpl->layout->block->setVar('user_header_activity', $page->lang('usr_top_activity_count', array('#NUM_FOLLOWERS#'=>$u->num_followers, '#NUM_FOLLOWING#'=>count($he_follows), '#NUM_POSTS#'=>$u->num_posts )));

			if( $this->user->is_logged ){
				$tpl->layout->block->setVar('user_header_follow_button', $is_my_profile? '' : 
											(!in_array($u->id, $i_follow)? usersSettingsMenu($u->id, true) : usersSettingsMenu($u->id, false)) 
				);
			}
			
			$tpl->layout->block->save('main_content_top_placeholder', true);
			$add_link .= '<a href="' . $C->SITE_URL .$C->SITE_URL . $user->info->username.'/tab:events/tab:list_events/username:'.$page->params->username.'" title="List View"  class="btn btn-primary  calander-view" ><span>List View</span></a>';
			//$add_link.= '<input type="hidden" id="username" value="'.$page->params->username.'" /><a href="' . $C->SITE_URL . $user->info->username.'/tab:events/action:add_event/username:'.$page->params->username.'" class="btn blue calander-add calander-view" title="Create Event" ><span>Create Event</span></a>';
			//$add_calendar="<div id='calendar'></div><div id='check_data'></div>";
		}else{		

                    $add_link .= '<a href="' . $C->SITE_URL . $user->info->username.'/tab:events/action:list_events" title="List View"  class="btn btn-primary calander-view" > <span>List View</span></a>';
                    //$add_link.= '<a href="' . $C->SITE_URL . $user->info->username.'/tab:events/action:add_event" class="btn blue calander-add calander-view" title="Create Event"><span>Create Event</span></a>';
					//$add_calendar="<div id='calendar'></div><div id='check_data'></div>";
		}

                // get template
				
                ob_start();
                include($C->PLUGINS_DIR.'events/static/templates/blocks/calander_template_new_tab1.php');
                $calendar_template = ob_get_clean();
				echo $calendar_template;
	}
	//TEMPLATE CODE END

?>