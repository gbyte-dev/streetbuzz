<?php
	global $user, $db2, $C;
	if(isset($_POST['id']) && $_POST['action']=='delete')
	{
		$id=$db2->escape($_POST['id']);
		$res = $db2->query("SELECT * FROM event_role_resource WHERE id = '$id' AND user_id = '".$user->id."'");
		if($db2->num_rows($res) > 0)
		{
			$event_role_resource = $db2->fetch_object($res);
			if($event_role_resource->id){
				$db2->query('DELETE FROM event_role_resource WHERE id="'.$event_role_resource->id.'" LIMIT 1', FALSE);
				echo json_encode(array('status'=>'success'));
				exit;
			}
		}
	}elseif(isset($_POST['role_id']) && $_POST['action']=='add'){
		$group_id=$db2->escape($_POST['group_id']);
		$role_id=$db2->escape($_POST['role_id']);
		
		if( empty($group_id) && !empty($role_id) ){
			// Pavel check in event_roles has group
			$getroles = $db2->fetch("SELECT * FROM event_roles WHERE id = '$role_id' LIMIT 1");
			if( !empty($getroles->group_id) ){
				$group_id = (int)$getroles->group_id;
			}
		}
		
		
		$res = $db2->query("SELECT * FROM event_role_resource WHERE role_id = '$role_id' AND group_id = '$group_id' AND user_id = '".$user->id."'");
		if($db2->num_rows($res) < 1){
			$insert = $db2->query('INSERT INTO `event_role_resource` (user_id, group_id, role_id, created_at) 
						VALUES ("'.$user->id.'","'.$group_id.'", "'.$role_id.'", "'.date('Y-m-d H:i:s').'")');
			if($insert){
				$res1 = $db2->query("SELECT * FROM event_roles WHERE id = '$role_id'");
				$event_role = $db2->fetch_object($res1);
				echo json_encode(array('status'=>'success', 'html'=>roles_resource_list($group_id, $event_role->event_id)));
				exit;
			}
		}else{
			echo json_encode(array('status'=>'all'));
			exit;
		}
	}
	echo json_encode(array('status'=>'error'));
	
	
	function roles_resource_list($group_id, $event_id)
	{
		global $db2,$C, $user;
		$role_result = $db2->query('SELECT err.id,err.user_id,err.created_at,er.role_name,usr.username FROM event_role_resource as err
									inner join event_roles as er on er.id=err.role_id
									inner join users as usr on usr.id=err.user_id
									WHERE err.group_id="'.$group_id.'" AND er.event_id="'.$event_id.'" order by err.id desc;');
		//$output="<table class='resource_list' width='100%'><th>S.No</th><th>Username</th><th>Role Name</th><th>Created</th><th>Action</th>";
		$output="<table class='resource_list' width='100%'><th>Username</th><th>Role Name</th><th>Action</th>";
		if($db2->num_rows($role_result) > 0)
		{
			$i=1;
			while($obj = $db2->fetch_object($role_result))
			{
				//$created = date("F j, Y", strtotime($obj->created_at));
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
?>