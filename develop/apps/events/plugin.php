<?php
error_reporting(0);
class events extends plugin
{
	public function onPageLoad()
	{ 

		global $page,$C, $db2, $user, $network;
		
		if(!$this->user->is_logged){
			return;
		}
		
		$designer = pageDesignerFactory::select();
		
		//check selected menu class
		if($page->plugin_name=='events'){
			$class_name = 'event selected';
		}else{
			$class_name = 'event';
		}
                
		$this->plgn = & $GLOBALS['plugins_manager'];

		//set group and user navigation menus
		$this->setVar('group_navigation_top_menu', $designer->createMenuLink( array('url'=>'plugin/events/home/group:'.$page->params->group,  'title'=>'Events', 'css_class'=>$class_name)));
		$this->setVar('user_navigation_top_menu', $designer->createMenuLink( array('url'=>'plugin/events/home/group:'.$page->params->group,  'title'=>'Events', 'css_class'=>$class_name)));

		if( $user->is_logged && $page->params->user == $user->id && $page->params->tab=='events' ){

			$this->setVar('user_page_navigation_top_menu', '<li><a class="'.(!empty($page->params->tab) && $page->params->tab=='events' ? ' selected' : '') . '" href="' . $C->SITE_URL . $user->info->username.'/tab:events"><span>Events</span></a></li>');

			if( (!empty($page->params->tab) && $page->params->tab=='events') ){
				
				$this->setVar('header_data', '<style> .activity-feed-list, .show-more-container, .show-more { display:none; } </style>');
				$C->DISABLE_STATIC_HTML = TRUE;
				require_once($C->PLUGINS_DIR.'events/system/controllers/home_new.php');

			}
			
		}

		//set expire events query
		$db2->query('UPDATE events SET status=0 WHERE CONCAT(end_date, " ", end_time)<="'.date('Y-m-d H:i:s').'"');
/*
		$res = $db2->query('SELECT events.*, count(posts.id) as post_count FROM events LEFT JOIN event_posts on(event_posts.event_id = events.id) LEFT JOIN posts on(posts.id = event_posts.post_id) WHERE events.status =1 and events.publish_date = 1 AND DATE(events.activity_pub_date) = "'.date('Y-m-d').'" AND DATE(events.activity_pub_date) <= "'.date('Y-m-d H:i:s').'" group by event_posts.event_id having ((events.publish_now != 0 AND post_count<2) OR (events.publish_now != 1 AND post_count<1 ) )');
*/
		$res = $db2->query('
		SELECT events.*, count(posts.id) as post_count 
		FROM events 
		LEFT JOIN event_posts on(event_posts.event_id = events.id) 
		LEFT JOIN posts on(posts.id = event_posts.post_id) 
		WHERE 
		events.status = 1 and events.publish_date = 1 AND 
		events.activity_pub_date <= STR_TO_DATE("'.date('Y-m-d H:i:s').'", "%Y-%m-%d %H:%i:%s") 
		group by event_posts.event_id having ((events.publish_now != 0 AND post_count<2) OR (events.publish_now != 1 AND post_count<1 ) )');
		if($db2->num_rows($res) > 0){
			while($obj = $db2->fetch_object($res))
			{
				$is_private = 0;
				$title = $obj->event_name;
				$address = $obj->address;
				$admin_id = $obj->admin_id;
				$address = $obj->address;
				$activity_pub_date = $obj->activity_pub_date;
				$id =$obj->id;
				$group_id    = !empty($obj->group_id) ? $this->db2->escape($obj->group_id):'0';
				$date_time = date('M d, Y h:i A', strtotime($obj->start_date.' '.$obj->start_time));
				$user_name = $this->network->get_user_by_id($admin_id);
				$addition_url = empty($group_id) ? '' : '/group:'.$group_id;
				$content_title = '<div class="title"><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/event.png"> <a href="' . $C->SITE_URL . 'plugin/events/view/id:'.$id.$addition_url.'">'.$obj->event_name.'</a></div>';
				$content = '<p class="address_view">
								<span>'.$obj->address.'</span>
							</p>				
					<span class="time">'.$date_time.'</span>
				';
				//$left = ($this->params->group)?(strlen($this->user->info->username)+strlen($g->title)+8):(strlen($this->user->info->username)+4);
				$answer = (object)array(
						'link'=> $C->SITE_URL . 'plugin/events/view/id:'.$id.$addition_url, 
						'title'=>$content_title,
						'description' =>$content,
						'hits'=> 'link'
					);
				
				if( $user_name->id > 0 ) {
					$db2->query('UPDATE users SET num_posts=num_posts+1, lastpost_date="'.time().'" WHERE id="'.$this->user->id.'" LIMIT 1');
					$db2->query('INSERT INTO '.($is_private?'posts_pr_comments_watch':'posts_comments_watch').' SET user_id="'.$this->user->id.'", post_id="'.$id.'", newcomments=0');
				}		
				$time = strtotime($activity_pub_date);
				$db2->query('INSERT INTO posts SET user_id="'.$user_name->id.'", group_id="'.$group_id.'", date="'.$time.'", date_lastcomment="'.$time.'", attached="1"'); 
				$pid = $db2->insert_id();

				$db2->query('INSERT INTO event_posts SET event_id="'.$id.'", post_id="'.$pid.'", created = "'.time().'"');
				
				$db2->query('INSERT INTO post_userbox SET user_id="'.$user_name->id.'", post_id="'.$pid.'"');
				$db2->query('INSERT INTO posts_attachments SET post_id="'.$pid.'", type="link",data="'.$db2->escape(serialize($answer)).'"');
				
				$q =array();

				//insert to followers data
				if($user_name->is_posts_protected == 0){
					$u	= $this->network->get_user_follows($user_name->id, FALSE, 'hisfollowers')->followers;
				}else{
					$u	= array_intersect_key($this->network->get_user_follows($user_name->id, FALSE, 'hefollows')->follow_users, $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers);
				}
						
				$u	= $this->network->get_user_follows($user_name->id, FALSE, 'hisfollowers')->followers;
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
			}
		}		
		
		$hide_left_panel = true;
		if( !empty($page->request['1']) ){
			if( $page->request['1'] == 'invite' ){
				$hide_left_panel = false;
			}
		}
		if( !empty($page->request['0']) ){
			if( $page->request['0'] == 'groups' ){
				$hide_left_panel = false;
			}elseif( $page->request['0'] == 'members' ){
				$hide_left_panel = false;
			}
		}
		if( !empty($this->page->plugin_name) && $this->page->plugin_name != 'events' ){
			$hide_left_panel = false;
		}

		if( $hide_left_panel ){
			
			//set event calender link left side bar
			//if( substr($this->getCurrentController(), 0, 6) == 'admin/' || $this->getCurrentController() =='settings' ){
				//$this->setVar( 'administration_left_menu', $designer->createMenuLink( array('url'=>'plugin/events/settings',  'title'=>'Event Calendar Settings') ) );
			//}	
			//elseif (!empty($user->id) && $page->params->user == $user->id && substr($this->getCurrentController(), 0, 6) != 'admin/' && substr($this->getCurrentController(), 0, 9) != 'settings/' && $this->getCurrentController() !='settings' && $this->page->plugin_name!='ads')
			if (!empty($user->id) && $page->params->user == $user->id && substr($this->getCurrentController(), 0, 6) != 'admin/' && substr($this->getCurrentController(), 0, 9) != 'settings/' && $this->getCurrentController() !='settings' && $this->page->plugin_name!='ads')
			{ 
				if(empty($page->params->group) && $page->params->user == $user->id && $this->getCurrentController() != 'user' && empty($page->params->username)){
					$url_link = ( empty($this->user->info->username) ? 'plugin/events/home' : $this->user->info->username.'/tab:events');
									$link_class = '<div class="section-container ">
						<h3 class="section-title">Events</h3>
							<ul class="feed-navigation">
							'.$designer->createMenuLink( array('url'=>$url_link, 'title'=>'Event Calendar', 'css_class'=>'event_calendar_title '.$class_name) ).'</ul>
						';
					$this->setVar('left_content_bottom',$link_class);
					$this->setVar('right_content_bottom',$link_class);
				}elseif(empty($page->params->group) && ($page->params->user == $user->id || !empty($page->params->username))){
					$link_class = '<div class="section-container ">
						<h3 class="section-title">Events</h3>
							<ul class="feed-navigation">
							'.$designer->createMenuLink( array('url'=>$this->user->info->username.'/tab:events',  'title'=>'Event Calendar', 'css_class'=>'event_calendar_title '.$class_name) ).'</ul>
						';
					$this->setVar('left_content_bottom',$link_class);
					$this->setVar('right_content_bottom',$link_class);
				}else{
					$link_class = '<div class="section-container ">
						<h3 class="section-title">Events</h3>
							<ul class="feed-navigation">
							'.$designer->createMenuLink( array('url'=>'plugin/events/home/group:'.$page->params->group,  'title'=>'Event Calendar', 'css_class'=>'event_calendar_title '.$class_name) ).'</ul>
						';
					$this->setVar('left_content_bottom',$link_class);
					$this->setVar('right_content_bottom',$link_class);
				}
			}
			
			/*if($page->plugin_name && empty($page->params->group) && empty($page->params->user) && $page->plugin_name=='events'){
				$this->setVar('left_content_bottom',$link_class);
			}*/
			
			//$link= '<link href="'.$C->SITE_URL.'apps/events/static/css/jquery-ui.css" type="text/css" rel="stylesheet" />" />';
			//$this->setVar( 'header_data',$link );
			//$link= '<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.js"></script>';

			$link ='';
						//$link.= '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyALVs17BXOC6tpDgfczcRvDr1-qyjknVtY&libraries=places&v=2.exp"></script>';
						$link.= '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDArYN-93IBn3EBtCMXBoSoznKr3F1wPxE&libraries=places&v=2.exp"></script>';


						

						//$link.= '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&v=3.exp"></script>';

			


			$this->setVar( 'footer_js_data',$link );
		
			/* Group Admin */

			$is_group_admin = $this->db2->query('select group_id,user_id from groups_admins where 
												user_id="'.$user->id.'" 
												and group_id="'.$page->params->group.'"');
												
			$count			=	$this->db2->num_rows($is_group_admin);

			/* group admin end*/
			if(!empty($user->id) && $page->params->user == $user->id && substr($this->getCurrentController(), 0, 6) != 'admin/' && substr($this->getCurrentController(), 0, 9) != 'settings/' && $this->getCurrentController() !='settings' && $this->page->plugin_name!='ads'){
				if(!empty($page->params->group)){
					$res = $db2->query('SELECT * FROM events WHERE CONCAT(start_date, " ", start_time) >= "'.date("Y-m-d H:i:s").'"  AND (display_type="community" OR (display_type="group" AND group_id="'.$page->params->group.'")) AND status=1 ORDER BY start_date, start_time ASC LIMIT 5');
				}elseif(!empty($page->params->username)){
					$groups = $network->get_user_follows($user->id, '', 'hisgroups');
					if(!empty($groups->follow_groups)){
						
						$follow_group = $groups->follow_groups;
						$groups = array_flip($follow_group);
						$group_ids = implode(',', $groups);
					}else{
						$group_ids = 0;
					}
					$res = $db2->query('SELECT * FROM events WHERE  CONCAT(start_date, " ", start_time) >= "'.date("Y-m-d H:i:s").'"  AND (display_type="community" OR (display_type="group" AND group_id IN ('.$group_ids.'))) AND status=1 ORDER BY start_date, start_time ASC LIMIT 5');
				}else{
					
					$groups = $network->get_user_follows($user->id, '', 'hisgroups');
					if(!empty($groups->follow_groups)){
						$follow_group = $groups->follow_groups;
						$groups = array_flip($follow_group);
						$group_ids = implode(',', $groups);
					}else{
						$group_ids = 0;
					}
					
					//$res = $db2->query('SELECT * FROM events WHERE CONCAT(start_date, " ", start_time) >= "'.date("Y-m-d H:i:s").'" AND status=1 AND (display_type="community" OR (display_type="group" AND group_id IN ('.$group_ids.'))) ORDER BY start_date, start_time ASC LIMIT 5');	
                       $res = $db2->query('SELECT e.id,e.event_type,e.event_name,e.event_description,e.address,e.start_date,e.end_date,e.start_time,e.end_time,pu.event_status as status
                FROM events  AS e
				inner join event_posts as ep ON ep.event_id	=e.id
				inner join post_userbox as pu ON pu.post_id	=ep.post_id
				WHERE   pu.user_id="'.(int)$user->id.'" AND pu.event_status=1 AND CONCAT(e.start_date, " ", e.start_time) >= "'.date("Y-m-d H:i:s").'"  AND (e.display_type="community" OR (e.display_type="group" AND e.group_id IN ('.$group_ids.'))) ORDER BY e.start_date, e.start_time ASC LIMIT 5');						
				}
				if( $db2->num_rows($res) > 0 )
				{
								   
					$upcomming_events = '<div class="eventsList">
					<ul class="eList box">';			
					if(empty($page->params->group)){
						while($obj = $db2->fetch_object($res))
						{		
							$upcomming_events.='
								<li>
									<div class="TE-txt met-list">
										<div class="title"><a class="left_link"  title="'.strip_tags(ucwords($obj->event_name)).'"  href="' . $C->SITE_URL .'plugin/events/view/id:'.$obj->id.'">'.ucwords($obj->event_name).'</a></div>
											<span>
												<p>
													<span>'.substr($obj->address,0,100).'</span> <br>
												</p>
											</span>
										<span class="time"> <time>'.date('M d, Y', strtotime($obj->start_date)).'</time> &nbsp; <b>|</b> &nbsp;<span class="c">'.date('h:i A', strtotime($obj->start_time)).'</span></span>
									</div>
								</li>	
							';
						}
					
						//$upcomming_events.='</ul></div> <a class="viewall" title="View All" href="'.$C->SITE_URL .'plugin/events/home/tab:list_events">View All...</a>';
						$upcomming_events.='</ul></div> <a class="viewall" title="View All" href="' . $C->SITE_URL . $user->info->username.'/tab:events/action:list_events">View All...</a>';
						
						
					}else{ 

										$url_link = ( empty($this->user->info->username) ? $C->SITE_URL .'plugin/events/home/tab:list_events' : $this->user->info->username.'/tab:events');
										while($obj = $db2->fetch_object($res))
										{		
											$elist1_url = empty($obj->group_id) ? $C->SITE_URL .'plugin/events/view/id:'.$obj->id : $C->SITE_URL.'plugin/events/view/id:'.$obj->id.'/group:'.$obj->group_id;
											$upcomming_events.='
											<li>
												<div class="TE-txt met-list">
												<div class="title"><a class="left_link" title="'.strip_tags(ucwords($obj->event_name)).'" href="'.$elist1_url.'">'.ucwords($obj->event_name).'</a></div>
														<span>
																<p>
																		<span>'.substr($obj->address,0,100).'</span> <br>
																</p>
														</span>
												<span class="time"> <time>'.date('M d, Y', strtotime($obj->start_date)).'</time> &nbsp; <b>|</b> &nbsp;<span class="c">'.date('h:i A', strtotime($obj->start_time)).'</span></span>
												</div>
											</li>	
											';
										}
										$upcomming_events.='</ul></div><br /> <a  class="viewall" title="View All" href="'.$C->SITE_URL .'plugin/events/home/tab:list_events/group:'.$page->params->group.'">View All...</a>';
									}
				}else{
					$upcomming_events = '<p style="padding:10px;">No Events Found</p>';
				}
				//echo $page->params->username; exit;
				if(!empty($page->params->group)){
					$res = $db2->query('SELECT * FROM events WHERE CONCAT(start_date, " ", start_time) >= "'.date("Y-m-d H:i:s").'" AND  event_type="major"  AND (display_type="community" OR (display_type="group" AND group_id="'.$page->params->group.'")) AND status=1 ORDER BY start_date, start_time  ASC LIMIT 5');
				}elseif(!empty($page->params->user)){
					$groups = $network->get_user_follows($user->id, '', 'hisgroups');
					if(!empty($groups->follow_groups)){
						$follow_group = $groups->follow_groups;
						$groups = array_flip($follow_group);
						$group_ids = implode(',', $groups);
					}else{
						$group_ids = 0;
					}
					$res = $db2->query('SELECT * FROM events WHERE event_type="major" AND CONCAT(start_date, " ", start_time) >= "'.date("Y-m-d H:i:s").'" AND (display_type="community" OR (display_type="group" AND group_id IN ('.$group_ids.'))) AND status=1 ORDER BY start_date, start_time ASC LIMIT 5');
				}else{
					$res = $db2->query('SELECT * FROM events WHERE display_type="community" AND event_type="major" AND  CONCAT(start_date, " ", start_time) >= "'.date("Y-m-d H:i:s").'" AND status=1 ORDER BY start_date, start_time ASC LIMIT 5');		
				}
				if( $db2->num_rows($res) > 0 )
				{
					$new_events = '<div class="eventsList">
					<ul class="eList box">';			
					while($obj = $db2->fetch_object($res))
					{
						$elist_url = empty($obj->group_id) ? $C->SITE_URL.'plugin/events/view/id:'.$obj->id : $C->SITE_URL.'plugin/events/view/id:'.$obj->id.'/group:'.$obj->group_id;
						$new_events.='
							<li>
								<div class="TE-txt met-list">
									<div class="title"><a class="left_link" title="'.strip_tags(ucwords($obj->event_name)).'" href="'.$elist_url.'">'.ucwords($obj->event_name).'</a></div>
										<span>
											<p>
												<span>'.substr($obj->address,0,100).'</span> <br>
											</p>
										</span>
									<span class="time"> <time>'.date('M d, Y', strtotime($obj->start_date)).'</time> &nbsp; <b>|</b> &nbsp;<span class="c">'.date('h:i A', strtotime($obj->start_time)).'</span></span>
								</div>
							</li>	
						';
					}
					$new_events.='</ul></div>';
				}else{
					$new_events = '<p style="padding:10px;">No Events Found</p>';
				}
				if(!empty($upcomming_events) || !empty($new_events)){
				
					$event_list = '<br /><div id="tabs" class="event_tab ui-tabs ui-widget ui-widget-content ui-corner-all">
						  <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
							<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#eventnew">';
						$event_list .='Important';
							$event_list .='</a></li>
							<li class="ui-state-default ui-corner-top"><a href="#upcomming">Upcoming</a></li>
						  </ul>
						  <div id="eventnew">
							<p>'.$new_events.'</p>
						  </div>
						  <div id="upcomming" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
							'.$upcomming_events.'
						  </div>
						</div></div>';
					
									
					if(empty($page->params->group) && $page->params->user == $user->id && $this->getCurrentController() != 'user' && empty($page->params->username)){
						$this->setVar('left_content_bottom',$event_list);
						$this->setVar('right_content_bottom',$event_list);

					}elseif(empty($page->params->group) && ($page->params->user == $user->id || !empty($page->params->username))){
						$this->setVar('left_content_bottom',$event_list);
						$this->setVar('right_content_bottom',$event_list);

					}else{
						$this->setVar('left_content_bottom',$event_list);
						$this->setVar('right_content_bottom',$event_list);

					}
				}
			}
		}
	}
	
}
?>