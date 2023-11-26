<?php
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
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

	$event_src = $db2->query('SELECT events.*, groups.groupname FROM events LEFT JOIN groups ON (events.group_id = groups.id) WHERE events.id="'.$this->params->id.'" LIMIT 1');
	$event = $db2->fetch_object($event_src);
	$event_edit = $db2->query('SELECT edit_status FROM event_posts WHERE post_id="'.$this->params->postid.'" LIMIT 1');
	$event_edit_status = $db2->fetch_object($event_edit);
	
		if(!empty($this->params->group) || !empty($event->group_id)){
			$group_id = empty($this->params->group)?$event->group_id:$this->params->group;
			$g	= $this->network->get_group_by_id($group_id);
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
		}		
		if(empty($this->params->group)){
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
				
				$tpl->layout->setVar( 'subheader_placeholder', $tpl->designer->createMenu( 'navigation', $menu, 'group_navigation_top_menu' ) ); 
				unset($menu);		
				$tpl->layout->setVar( 'main_content_placeholder', $tpl->designer->createMenu('tabs-navigation', $menu, 'groups_top_tab_menu') );
				
			}		
		}
	$tpl->layout->useBlock('view_event', 'events');
			if($event->event_type=='major'){
				$title= '<span class="new-event-title"><img style="vertical-align: top;" width="16px" src="'.$C->SITE_URL.'apps/events/static/images/major-event.png" />&nbsp;'.htmlentities($event->event_name, ENT_COMPAT, 'UTF-8').'</span>';
			}else{
				$title= '<span class="new-event-title">'.htmlentities($event->event_name, ENT_COMPAT, 'UTF-8').'</span>';
			}
	
			$data = $title;			
			//Links for add/edit
			$cancel = "return confirm('Are you sure you want to cancel this event?')";
			
			$us_data = $db2->query('SELECT 1 FROM users WHERE id="'.$this->user->id.'" AND is_network_admin=1 LIMIT 1');

			//Links for add/edit
			if(($this->user->id == $event->admin_id || $db2->num_rows($us_data) > 0 )&& $event_edit_status->edit_status !=4 ){
               if($event->display_type == 'group'){
					
					if($event->status == 1){
						$data .='<div class="col-md-12 links"><a  title="Edit" class="btn btn-xs btn-edit-event pull-right" href="'.$C->SITE_URL . $user->info->username.'/tab:events/action:edit_event/event:'.$event->id.'/gr:'.$event->group_id.'"><span><span class="glyphicon glyphicon-edit"></span>  Edit</span></a>';
						
						$data .='<a class="cancel-event pull-right" href="'.$C->SITE_URL.'plugin/events/cancel_event/event:'.$event->id.'/group:'.$event->group_id.'" title="Cancel Event"  onclick="'.$cancel.'" ><span>Cancel Event</span></a></div>';
					}
				}else{
					if(empty($this->params->username)){
						if($event->status == 1){
						$data .='<div class="col-md-12"><a  title="Edit" class="btn btn-xs btn-edit-event pull-right" href="'.$C->SITE_URL . $user->info->username.'/tab:events/action:edit_event/event:'.$event->id.'"><span><span class="glyphicon glyphicon-edit"></span> Edit</span></a>';
						
						$data .='<a class="cancel-event pull-right" href="'.$C->SITE_URL.'plugin/events/cancel_event/event:'.$event->id.'" title="Cancel Event" onclick="'.$cancel.'" ><span>Cancel Event</span></a></div>';
						}
					}else{
						if($event->status == 1){

						    $data .='<div class="col-md-12 links"><a  title="Edit" class="btn btn-xs btn-edit-event pull-right" href="'.$C->SITE_URL . $user->info->username.'/tab:events/action:edit_event/event:'.$event->id.'/username:'.$this->params->username.'"><span><span class="glyphicon glyphicon-edit"></span>  Edit</span></a>';
							$data .='<a class="cancel-event pull-right" href="'.$C->SITE_URL.'plugin/events/cancel_event/event:'.$event->id.'/username:'.$this->params->username.'" title="Cancel Event" onclick="'.$cancel.'" ><span>Cancel Event</span></a></div>';
						}
					}
				}
			}
			$user_info= $this->network->get_user_by_id($event->admin_id);



  $data .= '</div><div class="popup_event col-md-12">


   <div class = "table-responsive">
   <table class = "table table-responsive table-condensed">
       
      <tbody>

         <tr>
             <th class="col-md-3">Author</th>
            <td>'.htmlentities($user_info->username, ENT_COMPAT, 'UTF-8').'</td>
         </tr>';


if(!empty($event->group_id)){
				$data .= '<tr>
             <th>Group</th>
            <td>'.$event->groupname.'</td>
         </tr>';
			}

        $data .= '<tr>
             <th>Title</th>
            <td>'. strip_tags(htmlentities($event->event_name, ENT_COMPAT, 'UTF-8')) .'</td>
         </tr>
         
         <tr>
             <th>Start Time</th>
            <td>'.date('M d, Y', strtotime($event->start_date)).' '.date('h:i A', strtotime($event->start_time)).'</td>
         </tr>
         
         <tr>
             <th>End Time</th>
            <td>'.date('M d, Y', strtotime($event->end_date)).' '.date('h:i A', strtotime($event->end_time)).'</td>
         </tr>

         <tr>
             <th>Address</th>
            <td>'.htmlentities($event->address, ENT_COMPAT, 'UTF-8').'</td>
         </tr>
         
         <tr>
             <th>Description</th>
            <td>'.htmlentities($event->event_description, ENT_COMPAT, 'UTF-8').'</td>
         </tr>
         
         <tr>
             <th>Url</th>
            <td><a href="https://'.$event->url.'" target="_blank"><span>'.htmlentities($event->url, ENT_COMPAT, 'UTF-8').'</a></td>
         </tr>
         
         <tr>
             <th>Type</th>
            <td>'.htmlentities($event->event_type, ENT_COMPAT, 'UTF-8').'</td>
         </tr>
         <tr>
            <th>Status</th>
            <td>';

         if($event->status == 1){
				$data .= '<strong>Active</strong>';
			}elseif($event->status == 2){
				$data .= '<strong>Cancelled</strong>';
			}else{
				$data .= '<strong>Expired</strong>';
			}
 
        $data .= '</td>
         </tr>
         <tr>
             <th>Attachments</th>
            <td>';

			$data_attach = '';
			$evt_attach= $db2->query('SELECT * FROM event_attachemnts WHERE event_id="'.$event->id.'"');
			while ($attchement = $db2->fetch_object($evt_attach)) {
				if($attchement->file_type == 'image'){
					$window_pop = "MyWindow_new=window.open('$attchement->link','Event','width=600,height=500'); return false;";
					$class="gallery";
					$extension="";
				}else{
					$window_pop = '';
					$class = '';
					$extension = end(explode('.', $attchement->filename));
				}
				if($extension != 'pdf'){
					$data_attach.='<a  href="'.$attchement->link.'" class="'.$class.'">'.$attchement->filename.'</a> <br /> ';
				}else{
					$data_attach.='<a target="_blank" href="'.$C->SITE_URL.'apps/events/static/ViewerJS/#'.$attchement->link.'" >'.$attchement->filename.'</a> <br /> ';
				}
			}
			if(!empty($data_attach)){
				$data.=$data_attach.' ';
			}else{
				$data.='No attachment found.';
			}

            $data .= '</td>
         </tr>

      </tbody>
      
   </table>
</div>'; 		








			if(!empty($event->group_id)){
				$role_selct = role_select($event->group_id, $event->id); 
				if(!empty($role_selct)){
					$data.='<div class="row" style="float:left; width:100%;"><label>Volunteer </label></div>';
					$data.='<form id="searchForm" method="post"><input type="hidden" name="group_id" id="group_id" value="'.$event->group_id.'" />'.$role_selct;
					$data.='<button style="vertical-align:top" type="submit" name="submit" class="btn blue js-button-save-res"><span>Join</span></button></form><br />';
					$data.='<div class="row"><label>Event Resources </label><span>'.roles_resource_list($event->group_id, $event->id).'</span></div>';
				}
			}else{
				if(empty($this->params->username) && empty($this->params->group))
					$tpl->layout->setVar('main_content_top_placeholder', '<div class="title-inner-pages">Event View</div><div class="break"></div>');
			}
			
			$location = urlencode($event->address);
			if(!empty($event->address)){
				$data .='<br />
				<iframe  width="100%"  height="450"  frameborder="0" style="border:0"
				  src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDPy5eSFaEefan3f1ClvREdFyaUeW6_V7Y&q='.$location.'">
				</iframe>
				';
			}
			
			$res = $db2->query('SELECT posts.id FROM posts LEFT JOIN event_posts ON (event_posts.post_id = posts.id) WHERE event_posts.event_id="'.$event->id.'"');
			if($db2->num_rows($res) > 0)
			{
				while($obj = $db2->fetch_object($res))
				{
					$post_id	=	($obj->id) ? $obj->id : "";
				}
			}
						
	$tpl->layout->block->setVar('content', $data);
	$tpl->layout->block->setVar('login_user_id', $this->user->id);
	$tpl->layout->block->setVar('post_id', $event->id);
	$tpl->layout->block->setVar('user_name', $this->user->info->username);
	$tpl->layout->block->setVar('img_name', $this->user->info->avatar);
	$tpl->layout->block->setVar('comments', mycmt($event->id));
	$tpl->layout->block->save( 'main_content', true );
		
	$tpl->display();
	function mycmt($cmt_id='')
	{
		global $user, $db2,$C;
		$output ='';
		$sql_comments = "select cmt.id as comment_id,cmt.message as comments,cmt.date as comment_post_date,
								usr.username as username,usr.avatar,cmt.user_id
								from posts_comments as cmt
								LEFT join posts as pst on pst.id=cmt.post_id
								LEFT join event_posts as evtpst on evtpst.post_id=cmt.post_id
								LEFT join users as usr on usr.id=cmt.user_id
								where evtpst.event_id=$cmt_id";
		$comments = $db2->query($sql_comments);
		$i = 0;
		$output='';
		$db2->num_rows($comments);
		if($db2->num_rows($comments) > 0)
		{
			while($cmt_obj = $db2->fetch_object($comments)) 
			{
				$i++;
				//$created = date("F j, Y, g:i a", strtotime($cmt_obj->comment_post_date));
				$created = post::parse_date($cmt_obj->comment_post_date);


				if($cmt_obj->avatar)
					$img_url = $C->STORAGE_URL.'avatars/thumbs1/'.$cmt_obj->avatar;
				else
					$img_url = $C->STORAGE_URL.'avatars/thumbs3/_noavatar_user.gif';


				



					//userlink($cmt_obj->username);
					$output.='<div class="comment" style="border-bottom:1px dotted #DADADA;" id="cmt'.$cmt_obj->comment_id.'">
							  <a data-userid="1" class="avatar bizcard" href="'.userlink($cmt_obj->username).'"><img alt="'.$cmt_obj->username.'" src="'.$img_url.'"></a>
							  <div class="comment-container">';
					if($cmt_obj->user_id==$user->info->id)
					{
					$output.='<div class="comment-options pull-right"><a class="delete" onclick="delete_comments('.$cmt_obj->comment_id.','.$cmt_id.');" ></a></div>';
					}
					$output.='<div class="comment-content"><span class="comment-author"><a data-userid="1" class="author bizcard" href="'.userlink($cmt_obj->username).'">'.$cmt_obj->username.'</a></span><span class="">'.$cmt_obj->comments.'</span></div>
							  <div class="attachments lightbox-enabled"></div>
							  <div>
							  <span class="permlink">'.$created.'</span>
							  </div>


							  </div>
							  <div class="clear"></div>
							  </div>';
			}
		}
		return $output;
	}
	function roles_resource_list($group_id, $event_id)
	{
		global $db2,$C, $user;
		$role_result = $db2->query('SELECT err.id,err.user_id,err.created_at,er.role_name,usr.username FROM event_role_resource as err
									inner join event_roles as er on er.id=err.role_id
									inner join users as usr on usr.id=err.user_id
									WHERE err.group_id="'.$group_id.'" AND er.event_id="'.$event_id.'" order by err.id desc;');
		$output="<table width='100%' class='resource_list'><th>S.No</th><th>Username</th><th>Role Name</th><th>Created</th><th>Action</th>";
		
		if($db2->num_rows($role_result) > 0)
		{
			$i=1;
			while($obj = $db2->fetch_object($role_result))
			{
				$created = date("F j, Y", strtotime($obj->created_at));
				$delete	= "<img height='14' width='14' src='".$C->SITE_URL."apps/events/static/images/delete.png'>";
				$url	= $C->SITE_URL."plugin/events/rosource/id:$obj->id/type:delete/group:$group_id";
				$output.="<tr><td>$i</td><td>".htmlentities($obj->username, ENT_COMPAT, 'UTF-8')."</td><td>".htmlentities($obj->role_name, ENT_COMPAT, 'UTF-8')."</td><td>$created</td>
						 ";
				if($user->id == $obj->user_id){
					$output.="<td><a href='".$url."' class='js-leave-group'  rel='$obj->id' title='Delete'>$delete</a>";
				}else{
					$output.="<td>&nbsp;</td>";
				}
				$output.='</tr>';
				$i++;
			}
		}
		else { $output.="<tr><td colspan='6'>No Roles Found!</td></tr>"; }
		$output.="</table>";
		return $output;
	}
	
	
	function role_select($group_id, $event_id)
	{
		global $db2,$C;
		$res 	= $db2->query('SELECT * from event_roles where group_id="'.$group_id.'" AND event_id="'.$event_id.'" order by role_name ASC');
		$output = false;
		if($db2->num_rows($res) > 0)
		{
			$output ="<select id='role_name_new' name='role_name_new'>";
			while($obj = $db2->fetch_object($res))
			{
				$output.="<option value='".$obj->id."'>".$obj->role_name."</option>";
			}
			$output.="</select>";
		}
		return $output;
	}
?>


<style>
.popup_event {

}
</style>