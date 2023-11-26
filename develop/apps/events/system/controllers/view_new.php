<?php
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	$page->load_langfile('inside/global.php');
	$page->load_langfile('inside/dashboard.php');
	$page->load_langfile('inside/group.php');
	$page->load_langfile('inside/dashboard.php');
	$page->load_langfile('inside/groups_new.php');
	$page->load_langfile('inside/admin.php');
	$page->load_langfile('inside/user.php');

	
	$submit = FALSE;
	$error = FALSE;
	$errmsg = '';
	$event_src = $db2->query('SELECT events.*, groups.groupname FROM events LEFT JOIN groups ON (events.group_id = groups.id) WHERE events.id="'.$page->params->id.'" LIMIT 1');
	$event = $db2->fetch_object($event_src);
	$event_edit = $db2->query('SELECT edit_status FROM event_posts WHERE post_id="'.$page->params->postid.'" LIMIT 1');
	$event_edit_status = $db2->fetch_object($event_edit);
    if(!empty($page->params->gr) || !empty($event->group_id)){
		$group_id = empty($page->params->gr)?$event->group_id:$page->params->gr;
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
		

	if($event->event_type=='major'){
		$title= '<h2><img style="vertical-align: top;" width="16px" src="'.$C->SITE_URL.'apps/events/static/images/major-event.png" />&nbsp; <b>'.htmlentities($event->event_name, ENT_COMPAT, 'UTF-8').'</b></h2>';
	}else{
		$title= '<h2><b>'.htmlentities($event->event_name, ENT_COMPAT, 'UTF-8').'</b></h2>';
	}

	$data = $title;			
	//Links for add/edit
	$cancel = "return confirm('Are you sure you want to cancel this event?')";
	
	$us_data = $db2->query('SELECT 1 FROM users WHERE id="'.$this->user->id.'" AND is_network_admin=1 LIMIT 1');

	//Links for add/edit
	if( ($this->user->id == $event->admin_id || $db2->num_rows($us_data) > 0) && $event_edit_status->edit_status !=4 ){
		if($event->display_type == 'group'){
			$data .='<div class="row links"><a  title="Edit" class="btn btn-primary" href="'.$C->SITE_URL . $user->info->username.'/tab:events/action:edit_event/event:'.$event->id.'/gr:'.$event->group_id.'"><span>Edit</span></a>';
			if($event->status == 1){
				$data .='<a class="btn btn-primary" href="'.$C->SITE_URL.'plugin/events/cancel_event/event:'.$event->id.'/group:'.$event->group_id.'" title="Cancel Event"  onclick="'.$cancel.'" ><span>Cancel Event</span></a></div>';
			}
		}else{
			if(empty($page->params->username)){
				$data .='<div class="row links"><a  title="Edit" class="btn btn-primary" href="'.$C->SITE_URL . $user->info->username.'/tab:events/action:edit_event/event:'.$event->id.'"><span>Edit</span></a>';
				if($event->status == 1 ){
					$data .='<a class="btn btn-primary" href="'.$C->SITE_URL.'plugin/events/cancel_event/event:'.$event->id.'" title="Cancel Event" onclick="'.$cancel.'" ><span>Cancel Event</span></a></div>';
				}
			}else{
				$data .='<div class="row links"><a  title="Edit" class="btn btn-primary" href="'.$C->SITE_URL . $user->info->username.'/tab:events/action:edit_event/event:'.$event->id.'/username:'.$page->params->username.'"><span>Edit</span></a>';
				if($event->status == 1){
					$data .='<a class="btn btn-primary" href="'.$C->SITE_URL.'plugin/events/cancel_event/event:'.$event->id.'/username:'.$page->params->username.'" title="Cancel Event" onclick="'.$cancel.'" ><span>Cancel Event</span></a></div>';
				}
			}
		}
	}
	$user_info= $this->network->get_user_by_id($event->admin_id);
	$data .= '</div><div class="popup_event"><div class="row"><label>Author</label>:<span>'.htmlentities($user_info->username, ENT_COMPAT, 'UTF-8').'</span> </div>';
	if(!empty($event->group_id)){
		$data .= '<div class="row"><label>Group</label>:<span>'.$event->groupname.'</span> </div>';
	}
	$data .= '<div class="row"><label>Title</label>:<span>'.strip_tags(htmlentities($event->event_name, ENT_COMPAT, 'UTF-8')).'</span> </div>';
	$data .= '<div class="row"><label>Start Time</label>:<span>'.date('M d, Y', strtotime($event->start_date)).' '.date('h:i A', strtotime($event->start_time)).'</span> </div>';
	$data .= '<div class="row"><label>End Time</label>:<span>'.date('M d, Y', strtotime($event->end_date)).' '.date('h:i A', strtotime($event->end_time)).'</span> </div>';
	$location = urlencode($event->address);

	$data .= '<div class="row"><label>Address</label>:<span>'.htmlentities($event->address, ENT_COMPAT, 'UTF-8').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span> </div>';

	$data .= '<div class="row"><label>Description</label>:<span>'.htmlentities($event->event_description, ENT_COMPAT, 'UTF-8').'</span> </div>';
	$data .= '<div class="row"><label>Type</label>:<span>'.htmlentities($event->event_type, ENT_COMPAT, 'UTF-8').'</span> </div>';
	if($event->status == 1){
		$data .= '<div class="row"><label>Status</label>:<span><b>Active</b></span> </div>';
	}elseif($event->status == 2){
		$data .= '<div class="row"><label>Status</label>:<span><b>Cancelled</b></span> </div>';
	}else{
		$data .= '<div class="row"><label>Status</label>:<span><b>Expired</b></span> </div>';
	}
	$data.=' <table style="border:none;"><tr><td style="width: 115px; border:none;">Attachments</td><td style="border:none;">:</td><td style="border:none;">';
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
		$data.=$data_attach.'</td></tr></table>';
	}else{
		$data.='No attachment found.</td></tr></table>';
	}
	if(!empty($event->group_id)){
		$role_selct = role_select($event->group_id, $event->id); 
		if(!empty($role_selct)){
			$data.='<div class="row" style="float:left; width:100%;"><label>Volunteer </label></div>';
			$data.='<form id="searchForm" method="post"><input type="hidden" name="group_id" id="group_id" value="'.$event->group_id.'" />'.$role_selct;
			$data.='<button style="vertical-align:top" type="submit" name="submit" class="btn blue js-button-save-res"><span>Join</span></button></form><br />';
			$data.='<div class="row"><label>Event Resources </label><span>'.roles_resource_list($event->group_id, $event->id).'</span></div>';
		}
	}
	//else{
		//if(empty($page->params->username) && empty($page->params->gr))
			//$this->setVar('main_content_top_placeholder', '<h1 style="margin:10px">Event View</h1><div class="break"></div>');
	//}
	
	if(!empty($event->address)){
		$data .='<br />
		<iframe  width="750"  height="450"  frameborder="0" style="border:0"
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
					
	/*	
	$tpl->layout->block->setVar('login_user_id', $this->user->id);
	$tpl->layout->block->setVar('post_id', $event->id);
	$tpl->layout->block->setVar('user_name', $this->user->info->username);
	$tpl->layout->block->setVar('img_name', $this->user->info->avatar);
	$tpl->layout->block->setVar();
	$tpl->layout->block->save( 'main_content', true );
		*/
	$comments = mycmt($event->id);
	
	ob_start();
	require_once($C->PLUGINS_DIR.'events/static/templates/blocks/view_event_new.php');
	$view_event_new = ob_get_clean();

	$this->setVar( 'main_content', $view_event_new, 'replace');
	$this->setVar( 'main_content_placeholder', '', 'replace');
	$this->setVar( 'main_content_bottom', '', 'replace');
	
	
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
					$output.='<div class="comment" id="cmt'.$cmt_obj->comment_id.'">
							  <a data-userid="1" class="avatar bizcard" href="'.userlink($cmt_obj->username).'"><img alt="'.$cmt_obj->username.'" src="'.$img_url.'"></a>
							  <div class="comment-container">';
					if($cmt_obj->user_id==$user->info->id)
					{
					$output.='<div class="comment-options"><a class="delete" onclick="delete_comments('.$cmt_obj->comment_id.','.$cmt_id.');" >delete</a></div>';
					}
					$output.='<div class="comment-content"><span class="comment-author"><a data-userid="1" class="author bizcard" href="'.userlink($cmt_obj->username).'">'.$cmt_obj->username.'</a></span>'.$cmt_obj->comments.'</div>
								<div class="attachments lightbox-enabled"></div>
								<div class="meta-info">
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