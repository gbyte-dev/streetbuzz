<?php
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if(!$this->user->is_logged){
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
			$u	= $this->network->get_group_invited_members($g->id);
			if( !$u || !in_array(intval($this->user->id),$u) ) {
				$this->redirect('dashboard');
			}
		}
		
		$this->params->group = $g->id;
	}
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/dashboard.php');
	$this->load_langfile('inside/group.php');
	$this->load_langfile('inside/dashboard.php');
	$this->load_langfile('inside/groups_new.php');
	$this->load_langfile('inside/user.php');

	if(!empty($this->params->tab) && $this->params->tab=='list_events' ){
	
	
	if(empty($this->params->group)){
		// redirect 
		$this->redirect($C->SITE_URL . $user->info->username.'/tab:events/action:list_events');
	}
	
		
		//TEMPLATE CODE START
		$add_link ='';
		
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
				$add_link .= '<a href="' . $C->SITE_URL .'plugin/events/home/username:'.$this->params->username.'"  title="Calendar View "  class="calander-view btn btn-primary"><span>Calendar View</span></a>';
				$add_link.= '<a href="' . $C->SITE_URL .'plugin/events/add_event/username:'.$this->params->username.'" class="btn blue calander-add calander-view" title="Create Event" ><span>Create Event</span></a>';

			}else{		
				
				$tpl = new template( array('page_title' => 'Events - '.$C->SITE_TITLE, 'header_page_layout'=>'sc') );
				$tpl->initRoutine('DashboardLeftMenu', array());
				$tpl->routine->load();
				
				$add_link .= '<a href="' . $C->SITE_URL .'plugin/events/home"  title="Calendar View"  class="btn btn-primary calander-view" ><span>Calendar View</span></a>';
				$add_link .= '<a href="' . $C->SITE_URL .'plugin/events/add_event"  title="Calendar Create"  class="btn blue calander-view calander-add"><span>Create Event</span></a>';
				$event_content= 'Event List';	
				
				$tpl->layout->setVar('main_content_top_placeholder', '<h1 style="margin:10px">'.$event_content.'</h1><div class="break"></div>');
			}
				
		}elseif(empty($this->params->group) && $this->params->user == $user->id){
				
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

				$tab = 'updates';
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
					
				}
				
				$tpl->layout->setVar( 'subheader_placeholder', $tpl->designer->createMenu( 'tabs-navigation', $menu ) ); unset($menu);

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
			$add_link .= '';
			$add_link .= '<a href="' . $C->SITE_URL .'plugin/events/home/group:'.$g->id.'"  title="Calendar View"  class="btn btn-primary calander-view"><span>Calendar View</span></a>';
			
			if(!empty($i_am_admin)){
				$add_link .= '<a href="' . $C->SITE_URL .'plugin/events/add_event/group:'.$g->id.'" title="Add Event"  class="btn blue calander-view calander-add"><span>Create Event</span></a>';
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
		
		//$event_content= 'Event List';	
		
		//$tpl->layout->setVar('main_content_placeholder', '<h1 style="margin:10px">'.$event_content.'</h1>');
		
		$tpl->layout->setVar('main_content_placeholder', $add_link );
		
		
		
		
		
		/*
		
		$tpl->layout->useBlock('calander_list', 'events');

		if(!empty($this->params->group)){

			$num_results = $db2->fetch_field('SELECT count(*)  FROM  events WHERE display_type="community"  OR group_id = "'.$this->params->group.'" ORDER BY created_at DESC');			
			//print_r($num_results); exit;
			$num_pages	= ceil($num_results / $C->PAGING_NUM_GROUPS);
			$pg	= $this->param('pg') ? intval($this->param('pg')) : 1;
			$pg	= min($pg, $num_pages);
			$pg	= max($pg, 1);
			$from	= ($pg - 1) * $C->PAGING_NUM_GROUPS;

			$paging_url=$C->SITE_URL.'plugin/events/home/tab:list_events/group:'.$this->params->group.'/pg:';		
			$res = $db2->query('SELECT * FROM events WHERE  display_type="community" OR group_id = "'.$this->params->group.'" ORDER BY FIELD(status,1,2,0), created_at DESC LIMIT '.$from.', '.$C->PAGING_NUM_USERS);
		}elseif(!empty($this->params->username)){
			$groups = $this->network->get_user_follows($this->user->id, '', 'hisgroups');
			if(!empty($groups->follow_groups)){
				$follow_group = $groups->follow_groups;
				$groups = array_flip($follow_group);
				$group_ids = implode(',', $groups);
			}else{
				$group_ids = 0;
			}
			$num_results = $db2->fetch_field('SELECT count(*) FROM events WHERE (display_type="community" OR (display_type="group" AND group_id IN ('.$group_ids.')))  ORDER BY created_at DESC');
			$num_pages	= ceil($num_results / $C->PAGING_NUM_GROUPS);
			$pg	= $this->param('pg') ? intval($this->param('pg')) : 1;
			$pg	= min($pg, $num_pages);
			$pg	= max($pg, 1);
			$from	= ($pg - 1) * $C->PAGING_NUM_GROUPS;	

			$paging_url=$C->SITE_URL.'plugin/events/home/tab:list_events/username:'.$this->params->username.'/pg:';		
			//$res = $db2->query('SELECT * FROM events WHERE (display_type="community" OR (display_type="group" AND group_id IN ('.$group_ids.')))  ORDER BY FIELD(status,1,2,0), created_at DESC LIMIT '.$from.', '.$C->PAGING_NUM_USERS);
			$res = $db2->query('SELECT * FROM events WHERE display_type="community" AND admin_id="'.$this->user->id.'" AND status=1 ORDER BY created_at DESC LIMIT '.$from.', '.$C->PAGING_NUM_USERS);
		}else{
			$num_results = $db2->fetch_field('SELECT count(*) FROM events WHERE display_type="community"  ORDER BY status DESC');
			$num_pages	= ceil($num_results / $C->PAGING_NUM_GROUPS);
			$pg	= $this->param('pg') ? intval($this->param('pg')) : 1;
			$pg	= min($pg, $num_pages);
			$pg	= max($pg, 1);
			$from	= ($pg - 1) * $C->PAGING_NUM_GROUPS;	

			$paging_url=$C->SITE_URL.'plugin/events/home/tab:list_events/pg:';		
			$res = $db2->query('SELECT * FROM events WHERE display_type="community"  ORDER BY FIELD(status,1,2,0), created_at DESC LIMIT '.$from.', '.$C->PAGING_NUM_USERS);
			//$res = $db2->query('SELECT * FROM events WHERE display_type="community" AND admin_id="'.$this->user->id.'" AND status=1 ORDER BY created_at DESC LIMIT '.$from.', '.$C->PAGING_NUM_USERS);
		}
		$res_new = $db2->query('SELECT * FROM event_settings LIMIT 1');
		$setting = $db2->fetch_object($res_new);
		
		if( $db2->num_rows($res) > 0 )
		{
			while($obj = $db2->fetch_object($res))
			{
				$tpl->layout->useBlock('calander_list', 'events');
				if(!empty($this->params->group)){
					//$url = "<div style='float:left; width:100%;'><a class='view' title='view event' data='$obj->id' href='".$C->SITE_URL."plugin/events/view/id:$obj->id/group:".$this->params->group."'>View</a></div>";				
					$title = '';
					if($obj->event_type=='major'){
						$title = '<img style="vertical-align: top;margin-top: 3px;" width="10px" src="'.$C->SITE_URL.'apps/events/static/images/major-event.png" />&nbsp';
					}
					$title_link = "<div style='float:left; width:100%;'>$title<a class='view' title='view event' data='$obj->id' href='".$C->SITE_URL."plugin/events/view/id:$obj->id/group:".$this->params->group."'>".$obj->event_name."</a></div>";				
				}else{
					$title = '';
					if($obj->event_type=='major'){
						$title = '<img style="vertical-align: top;margin-top: 3px;" width="10px" src="'.$C->SITE_URL.'apps/events/static/images/major-event.png" />&nbsp';
					}
					if(!empty($this->params->username)){
					//	$url = "<div style='float:left; width:100%;'><a class='view' title='view event' data='$obj->id' href='".$C->SITE_URL."plugin/events/view/id:$obj->id/username:".$this->params->username."'>View</a></div>";
						$title_link = "<div style='float:left; width:100%;'>$title<a class='view' title='view event' data='$obj->id' href='".$C->SITE_URL."plugin/events/view/id:$obj->id/username:".$this->params->username."'>".$obj->event_name."</a></div>";
					}else{
					//	$url = "<div style='float:left; width:100%;'><a class='view' title='view event' data='$obj->id' href='".$C->SITE_URL."plugin/events/view/id:$obj->id'>View</a></div>";
						$title_link = "<div style='float:left; width:100%;'>$title<a class='view' title='view event' data='$obj->id' href='".$C->SITE_URL."plugin/events/view/id:$obj->id'>".$obj->event_name."</a></div>";
					}
				}
				$url = '';
				$url.='<a class="" title ="Outlook Export" href="' . $C->SITE_URL .'plugin/events/ical/event:'.$obj->id.'"><img width="35px" src="'.$C->SITE_URL.'apps/events/static/images/o-export.png" ></a>';
				if(!empty($setting->google_app_key) && !empty($setting->google_secret_key)){
					$url.='<a class="" title ="Google Export"  href="' . $C->SITE_URL .'plugin/events/google_sync/event:'.$obj->id.'" ><img width="35px" src="'.$C->SITE_URL.'apps/events/static/images/g-export.png" ></a>';
				}
				if(!empty($setting->facebook_app_key) && !empty($setting->facebook_secret_key)){
					$url.='<a class="" title ="Facebook Export"  href="' . $C->SITE_URL .'plugin/events/facebook_sync/event:'.$obj->id.'"><img width="35px" src="'.$C->SITE_URL.'apps/events/static/images/f-export.png" ></a>';
				}
				$tpl->layout->block->setVar('id', $obj->id);
				$tpl->layout->block->setVar('title_link', $title_link);
				$tpl->layout->block->setVar('address', $obj->address);
				$tpl->layout->block->setVar('start_date', date('M d, Y', strtotime($obj->start_date)));
				$tpl->layout->block->setVar('start_time', date('h:i A', strtotime($obj->start_time)));
				$tpl->layout->block->setVar('end_date',date('M d, Y', strtotime($obj->end_date)));
				$tpl->layout->block->setVar('end_time',date('h:i A', strtotime($obj->end_time)));
				$tpl->layout->block->setVar('view_link',$url);
				$status = ($obj->status == 1)?'Active':(($obj->status == 2)?'Cancelled':'Expired');
				$tpl->layout->block->setVar('status',$status);
				$tpl->layout->block->save('main_content');
			}
			$tpl->layout->setVar( 'main_content_bottom', $tpl->designer->pager( $num_results, $num_pages, $pg, $paging_url ) );
		}else{
			$tpl->layout->setVar( 'main_content_bottom', 'No Events Found');
		}
		
		*/
		
		
		
		
	





	
		

		$groups = $this->network->get_user_follows($this->user->id, '', 'hisgroups');
		$group_ids = 0;
		if(!empty($groups->follow_groups)){
			$follow_group = $groups->follow_groups;
			$groups = array_flip($follow_group);
			$group_ids = implode(',', $groups);
		}
		
		$pg	= $this->param('pg') ? intval($this->param('pg')) : 1;
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
					if(!empty($this->params->group)){
			
							$title = '';
							if($get_dates_obj->event_type=='major'){
								$title = '<img class="tBoxImg" src="'.$C->SITE_URL.'apps/events/static/images/major-event.png" />';
							}
							$title_link = "<div class='tBox'>$title<a class='view' title='view event' data='$get_dates_obj->id' href='".$C->SITE_URL . "plugin/events/view/id:$get_dates_obj->id/gr:".$this->params->group."'>".$get_dates_obj->event_name."</a></div>";				
					}else{
							$title = '';
							if($get_dates_obj->event_type=='major'){
								$title = '<img class="tBoxImg" src="'.$C->SITE_URL.'apps/events/static/images/major-event.png" />';
							}
							if(!empty($this->params->username)){
								$title_link = "<div class='tBox'>$title<a class='view' title='view event' data='$get_dates_obj->id' href='".$C->SITE_URL . "plugin/events/view/id:$get_dates_obj->id/username:".$this->params->username."'>".$get_dates_obj->event_name."</a></div>";
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
			

			
			$additional_sql = ( empty($remove_ids) ? '' : ' AND id NOT IN('.implode(',', $remove_ids).') ' );
			
			$num_results = $db2->fetch_field('SELECT count(*) FROM events WHERE (display_type="community" OR (display_type="group" AND group_id IN ('.$group_ids.'))) AND CONCAT(`start_date`," ",`start_time`) >= "'.$now_date.'" '.$additional_sql);
			$num_pages	= ceil($num_results / $C->PAGING_NUM_GROUPS);
			$pg	= min($pg, $num_pages);
			$pg	= max($pg, 1);
			$from	= ($pg - 1) * $C->PAGING_NUM_GROUPS;	
			// AND `start_date` >= CURDATE()
			$paging_url = $C->SITE_URL . $user->info->username.'/tab:events/action:list_events/pg:';		
			$res = $db2->query('SELECT * FROM events WHERE 
			(display_type="community" OR (display_type="group" AND group_id IN ('.$group_ids.'))) AND 
			CONCAT(`start_date`," ",`start_time`) >= "'.$now_date.'" '.$additional_sql.' ORDER BY start_date, start_time LIMIT '.$from.', '.$C->PAGING_NUM_USERS);

		}
		
		$res_new = $db2->query('SELECT * FROM event_settings LIMIT 1');
		$setting = $db2->fetch_object($res_new);
        $calander_list ='<div class="clear" ></div>';
		
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
				if(!empty($this->params->group)){
						//$url = "<div style='float:left; width:100%;'><a class='view' title='view event' data='$obj->id' href='".$C->SITE_URL."plugin/events/view/id:$obj->id/group:".$this->params->group."'>View</a></div>";				
						$title = '';
						if($obj->event_type=='major'){
								$title = '<img class="tBoxImg" src="'.$C->SITE_URL.'apps/events/static/images/major-event.png" />';
						}
						$title_link = "<div class='tBox'>$title<a class='view' title='view event' data='$obj->id' href='".$C->SITE_URL . "plugin/events/view/id:$obj->id/gr:".$this->params->group."'>".$obj->event_name."</a></div>";				
				}else{
						$title = '';
						if($obj->event_type=='major'){
								$title = '<img class="tBoxImg" src="'.$C->SITE_URL.'apps/events/static/images/major-event.png" />';
						}
						if(!empty($this->params->username)){
						//	$url = "<div style='float:left; width:100%;'><a class='view' title='view event' data='$obj->id' href='".$C->SITE_URL."plugin/events/view/id:$obj->id/username:".$this->params->username."'>View</a></div>";
							$title_link = "<div class='tBox'>$title<a class='view' title='view event' data='$obj->id' href='".$C->SITE_URL . "plugin/events/view/id:$obj->id/username:".$this->params->username."'>".$obj->event_name."</a></div>";
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

			$tpl->layout->setVar( 'main_content', $calander_list );
			$tpl->layout->setVar( 'main_content_bottom', $tpl->designer->pager( $num_results, $num_pages, $pg, $paging_url ) );
			
		}else{
			$tpl->layout->setVar( 'main_content_bottom', 'No Events Found');
		}	
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		$tpl->display();	
		
	}
	elseif(!empty($this->params->group)){
		
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
		
		$table = new tableCreator();
		$rows = array(
			$table->hiddenField( 'group_id', $this->params->group)
		);
		$add_link ='';
		$add_link .= '<a href="' . $C->SITE_URL .'plugin/events/home/tab:list_events/group:'.$this->params->group.'"  title="List View"  class="btn btn-primary calander-view"><span>List View</span></a>';
		if(!empty($i_am_admin)){
			//$add_link .= '<a href="' . $C->SITE_URL .'plugin/events/add_event/group:'.$this->params->group.'" title="Create Event"  class="btn blue calander-add calander-view"><span>Create Event</span></a>';
		}
		//$add_link .= '<a href="' . $C->SITE_URL .'plugin/events/facebook_sync" class="btn btn-social btn-facebook" style="margin-bottom: 20px; width: 135px;">Facebook</a>';		$tpl->layout->setVar('add_link', $add_link );
		
		$tpl->layout->setVar('add_link', $add_link );
		$tpl->layout->setVar('main_content', $table->createTableInput( $rows ) );
		
		$tpl->layout->useBlock('calander_template', 'events');
		$tpl->layout->block->save('main_content');
		
		$tpl->display();
	}
	else{
	
	
		// redirect 
		$this->redirect($C->SITE_URL . $user->info->username.'/tab:events');
	
	

		//TEMPLATE CODE START
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
			$tpl = new template( array('page_title' => $u->username. ' - ' .$C->SITE_TITLE, 'header_page_layout'=>'scs') );
			
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
			$add_link .= '<a href="' . $C->SITE_URL .'plugin/events/home/tab:list_events/username:'.$this->params->username.'" title="List View"  class="btn btn-primary  calander-view" ><span>List View</span></a>';
			$add_link.= '<input type="hidden" id="username" value="'.$this->params->username.'" /><a href="' . $C->SITE_URL .'plugin/events/add_event/username:'.$this->params->username.'" class="btn blue calander-add calander-view" title="Create Event" ><span>Create Event</span></a>';

		}else{		
			$tpl = new template( array('page_title' => 'Events - '.$C->SITE_TITLE, 'header_page_layout'=>'scs') );
			$tpl->initRoutine('DashboardLeftMenu', array(''), '', 'events');
			$tpl->routine->load();
			
			$add_link .= '<a href="' . $C->SITE_URL .'plugin/events/home/tab:list_events" title="List View"  class="btn btn-primary calander-view" > <span>List View</span></a>';
			$add_link.= '<a href="' . $C->SITE_URL .'plugin/events/add_event" class="btn blue calander-add calander-view" title="Create Event"><span>Create Event</span></a>';

			$event_content= 'Event Calendar';	
			
			$tpl->layout->setVar('main_content_top_placeholder', '<h1 style="margin:10px">'.$event_content.'</h1><div class="break"></div>');
		}
		
		
		$tpl->layout->useBlock('calander_template', 'events');
//		$add_link .= '<a href="' . $C->SITE_URL .'plugin/events/facebook_sync" style="margin-bottom: 20px; width: 135px;"><img width="120px" src="http://www.insidefacebook.com/wp-content/uploads/2009/08/facebook-connect-logo.jpg" ></a>';		
		$tpl->layout->setVar('add_link', $add_link );

		$tpl->layout->block->save('main_content');
		
		$tpl->display();	
	}
	//TEMPLATE CODE END
?>