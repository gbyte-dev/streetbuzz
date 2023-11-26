<?php
	global $user, $db2, $C;
	$designer = pageDesignerFactory::select();
	if( isset($_POST['event']) ){
		$event_src = $db2->query('SELECT events.*, groups.groupname, groups.title as gtitle FROM events LEFT JOIN groups ON (events.group_id = groups.id) WHERE events.id="'.$_POST['event'].'" LIMIT 1');
		while ($event = $db2->fetch_object($event_src)) {
			if($event->event_type=='major'){
				$title= '<h2><img style="vertical-align: top;" width="16px" src="'.$C->SITE_URL.'apps/events/static/images/major-event.png" />&nbsp; <b>'.$event->event_name.'</b></h2>';
			}else{
				$title= '<h2><b>'.$event->event_name.'</b></h2>';
			}
			$user_info= $this->network->get_user_by_id($event->admin_id);
			$data = '<div class="popup_event">'.$title;
			$data .= '<div class="row"><label>Author</label>:<span>'.htmlentities($user_info->username, ENT_COMPAT, 'UTF-8').'</span> </div>';
			if(!empty($event->group_id)){
				$data .= '<div class="row"><label>Group</label>:<span>'.$event->gtitle.'</span> </div>';
			}
			//$data .= '<div class="row"><label>Title</label>:<span>'.strip_tags(htmlentities($event->event_name, ENT_COMPAT, 'UTF-8')).'</span> </div>';
			$data .= '<div class="row"><label>Start Time</label>:<span>'.date('M d, Y', strtotime($event->start_date)).' '.date('h:i A', strtotime($event->start_time)).'</span> </div>';
			$data .= '<div class="row"><label>End Time</label>:<span>'.date('M d, Y', strtotime($event->end_date)).' '.date('h:i A', strtotime($event->end_time)).'</span> </div>';
			if(!empty($event->address)){
				$url = 'https://www.google.com/maps/search/'.urlencode(htmlentities($event->address, ENT_COMPAT, 'UTF-8'));
				$window_pop = "MyWindow=window.open('$url','Map','width=600,height=500'); return false;";

				$data .= '<div class="row"><label>Address</label>:<span>'.htmlentities($event->address, ENT_COMPAT, 'UTF-8').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a style="color:darkblue; font-weight:bold;" onclick="'.$window_pop.'" href="#">view map</a></span> </div>';
			}
			$data .= '<div class="row"><label>Description</label>:<span>'.htmlentities($event->event_description, ENT_COMPAT, 'UTF-8').'</span> </div>';
			$data .= '<div class="row"><label>Type</label>:<span>'.htmlentities($event->event_type, ENT_COMPAT, 'UTF-8').'</span> </div>';
			if($event->status == 1){
				$data .= '<div class="row"><label>Status</label>:<span><b>Active</b></span> </div>';
			}elseif($event->status == 2){
				$data .= '<div class="row"><label>Status</label>:<span><b>Cancelled</b></span> </div>';
			}else{
				$data .= '<div class="row"><label>Status</label>:<span><b>Expired</b></span> </div>';
			}
			$data.=' <div class="row"><label><h3>Attachments:</h3></label>:<span>';
			$data_attach = '';
			$evt_attach= $db2->query('SELECT * FROM event_attachemnts WHERE event_id="'.$_POST['event'].'"');
			while ($attchement = $db2->fetch_object($evt_attach)) {
				if($attchement->file_type == 'image'){
					$window_pop = "MyWindow_new=window.open('$attchement->link','Event','width=600,height=500'); return false;";
					$extension = '';
				}else{
					$window_pop = '';
					$extension = end(explode('.', $attchement->filename));
				}
				if($extension != 'pdf'){
					$data_attach.='<a onclick="'.$window_pop.'" href="'.$attchement->link.'" >'.htmlentities($attchement->filename).'</a> <br />';
				}else{
					$data_attach.='<a target="_blank" href="'.$C->SITE_URL.'apps/events/static/ViewerJS/#'.$attchement->link.'" >'.$attchement->filename.'</a> <br /> ';
				}
			}
			if(!empty($data_attach)){
				$data.=$data_attach.'</span></div></div><div>';
			}else{
				$data.='No attachment found.</span></div> </div><div>';
			}


			$cancel = "return confirm('Are you sure you want to cancel this event?')";

			$us_data = $db2->query('SELECT 1 FROM users WHERE id="'.$user->id.'" AND is_network_admin=1 LIMIT 1');

			//Links for add/edit
			if($user->id == $event->admin_id || $db2->num_rows($us_data) > 0){
				if($event->display_type == 'group'){
					$data .='<div class="row links"><a  title="Edit"  class="btn btn-primary edev" rel="'.$C->SITE_URL . $user->info->username.'/tab:events/action:edit_event/event:'.$event->id.'/gr:'.$event->group_id.'"><span>Edit</span></a>';
					if($event->status == 1){
						$data .='<a class="btn btn-primary" href="'.$C->SITE_URL.'plugin/events/cancel_event/event:'.$event->id.'/group:'.$event->group_id.'" title="Cancel Event"  onclick="'.$cancel.'" ><span>Cancel Event</span></a></div>';
					}
				}else{
					if(empty($_POST['username'])){
						$data .='<div class="row links"><a  title="Edit"  onclick="myfunction('.$event->id.')" class="btn btn-primary edev" rel="'.$C->SITE_URL . $user->info->username.'/tab:events/action:edit_event/event:'.$event->id.'"><span>Edit</span></a>';
						if($event->status == 1){
							$data .='<a class="btn btn-primary" href="'.$C->SITE_URL.'plugin/events/cancel_event/event:'.$event->id.'" title="Cancel Event" onclick="'.$cancel.'" ><span>Cancel Event</span></a></div>';
						}
					}else{
						$data .='<div class="row links"><a  title="Edit"  class="btn btn-primary edev" rel="'.$C->SITE_URL . $user->info->username.'/tab:events/action:edit_event/event:'.$event->id.'/username:'.$_POST['username'].'"><span>Edit</span></a>';
						if($event->status == 1){
							$data .='<a class="btn btn-primary" href="'.$C->SITE_URL.'plugin/events/cancel_event/event:'.$event->id.'/username:'.$_POST['username'].'" title="Cancel Event" onclick="'.$cancel.'" ><span>Cancel Event</span></a></div>';
						}
					}
				}
			}
			$data .= '</div>';
			if(!empty($event->group_id)){
				$role_selct = role_select($event->group_id, $event->id); 
				if(!empty($role_selct)){
					$data.='<h3 style="margin:15px 0 10px 0;">Volunteer</h3><br />';
					$data.='<form id="searchForm" method="post">'.$role_selct;
					$data.='<button style="vertical-align:top" type="submit" name="submit" class="btn blue js-button-save-res"><span>Join</span></button></form><br />';
					$data.='<div class="row"><label>Event Resources </label>:<span>'.roles_resource_list($event->group_id, $event->id).'</span></div>';
				}
			}
		}
		$answer = array('html'=>$data);
		echo json_encode($answer);
		return;
	}
	
	function roles_resource_list($group_id, $event_id)
	{
		global $db2,$C, $user;
		$role_result = $db2->query('SELECT err.id,err.user_id,err.created_at,er.role_name,usr.username FROM event_role_resource as err
									inner join event_roles as er on er.id=err.role_id
									inner join users as usr on usr.id=err.user_id
									WHERE err.group_id="'.$group_id.'" AND er.event_id="'.$event_id.'" order by err.id desc;');
		//$output="<table class='resource_list' width='100%'><th>S.No</th><th>Username</th><th>Role Name</th><th>Created</th><th>Action</th>";
		$output="<table class='resource_list' width='100%'><th>Username</th><th>Role</th><th></th>";
		
		if($db2->num_rows($role_result) > 0)
		{
			$i=1;
			while($obj = $db2->fetch_object($role_result))
			{
				$created = date("F j, Y", strtotime($obj->created_at));
				$delete	= "<img height='14' width='14' src='".$C->SITE_URL."apps/events/static/images/delete.png'>";
				$url	= $C->SITE_URL."plugin/events/rosource/id:$obj->id/type:delete/group:$group_id";
				//$output.="<tr><td>$i</td><td>".htmlentities($obj->username, ENT_COMPAT, 'UTF-8')."</td><td>".htmlentities($obj->role_name, ENT_COMPAT, 'UTF-8')."</td><td>$created</td>";
				$output.="<tr><td>".htmlentities($obj->username, ENT_COMPAT, 'UTF-8')."</td><td>".htmlentities($obj->role_name, ENT_COMPAT, 'UTF-8')."</td>";
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
				$output.="<option value='".$obj->id."'>".htmlentities($obj->role_name, ENT_COMPAT, 'UTF-8')."</option>";
			}
			$output.="</select>";
		}
		return $output;
	}
