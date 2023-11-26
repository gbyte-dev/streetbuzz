<?php

	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if(!$this->user->is_logged){
		$this->redirect('signin');
	}

	if(!empty($this->params->group)){
		$this->load_langfile('inside/global.php');
		$this->load_langfile('inside/dashboard.php');
		$this->load_langfile('inside/group.php');
		$this->load_langfile('inside/dashboard.php');
		$this->load_langfile('inside/groups_new.php');
		
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
		$tpl = new template( array('page_title' => $g->groupname. ' - ' .$C->SITE_TITLE, 'header_page_layout'=>'sc') );
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

				//$tpl->layout->block->save('main_content_top_placeholder', true);		
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
		}
	}
	//$tpl = new template( array('page_title' => $this->lang('Event Role', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );

	if($this->param('type')=='delete' && $this->param('id') > 0)
	{
		$db2->query('DELETE FROM event_role_resource WHERE id="'.$this->param('id').'" and group_id="'.$this->param('group').'" LIMIT 1', FALSE);
		$this->redirect($C->SITE_URL . $user->info->username.'/tab:events/action:edit_event/event:'.$this->param('event').'/group:'.$this->param('group'));
	}
	/* group admin roles */

	$tpl->layout->useBlock('add_resource', 'events');
	$tpl->layout->block->setVar('role_select', role_select($this->params->group));
	$tpl->layout->block->setVar('role_select_user', role_user_select($this->params->group));

	if( isset($_POST['Submit'])) 
	{
		$role_id     = mysql_escape_string($_POST['role_name']);
		$group_id    = !empty($_POST['group_id'])? mysql_escape_string($_POST['group_id']):'0';
		$users       = $_POST['users'];
		$users_count = count($users);
		for($i=0; $i< $users_count;$i++)
		{
			$this->db2->query('INSERT INTO `event_role_resource` (user_id, group_id, role_id, created_at) 
								VALUES ("'.$users[$i].'","'.$group_id.'", "'.$role_id.'", "'.date('Y-m-d H:i:s').'")');
		
		}
		$tpl->layout->block->setVar('message', "Role and Resource Inserted sucessfully!");
	}
	$tpl->layout->block->setVar('roles_list', roles_resource_list($this->params->group));
	$tpl->layout->block->setVar('group_id', $this->params->group);
	$tpl->layout->block->save( 'main_content', true );
	$tpl->display(); 
	//TEMPLATE CODE END

	function roles_resource_list($group_id)
	{
		global $db2,$C;
		$role_result = $db2->query('SELECT err.id,err.user_id,err.created_at,er.role_name,usr.username FROM event_role_resource as err
									inner join event_roles as er on er.id=err.role_id
									inner join users as usr on usr.id=err.user_id
									WHERE err.group_id="'.$group_id.'" order by err.id desc;');
		$output="<table width='100%'><th>S.No</th><th>Username</th><th>Role Name</th><th>Created</th><th>Action</th>";
		if($db2->num_rows($role_result) > 0)
		{
			$i=1;
			while($obj = $db2->fetch_object($role_result))
			{
				$created = date("F j, Y", strtotime($obj->created_at));
				$delete	= "<img height='14' width='14' src='".$C->SITE_URL."apps/events/static/images/delete.png'>";
				$url	= $C->SITE_URL."plugin/events/rosource/id:$obj->id/type:delete/group:$group_id";
				$output.="<tr><td>$i</td><td>$obj->username</td><td>$obj->role_name</td><td>$created</td>
						 <td><a href='".$url."' onclick='return confirm(\"Are you sure want to delete?\");' title='Delete'>$delete</a></td>
						 </tr>";
				$i++;
			}
		}
		else { $output.="<tr><td colspan='6'>No Roles Found!</td></tr>"; }
		$output.="</table>";
		return $output;
	}
	function role_select($group_id)
	{
		global $db2,$C;
		$res 	= $db2->query('SELECT * from event_roles where group_id="'.$group_id.'" order by role_name ASC');
		$output ="<select id='role_name' name='role_name'>";
		if($db2->num_rows($res) > 0)
		{
			while($obj = $db2->fetch_object($res))
			{
				$output.="<option value='".$obj->id."'>".$obj->role_name."</option>";
			}
		}
		$output.="</select>";
		return $output;
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
				$output.="<option value='".$obj->user_id."'>".$obj->username."</option>";
			}
		}
		$output.="</select>";
		return $output;
	}
	function cutString($pmStr, $pmPosition = 15)
	{
		if(!trim($pmStr)) return false;
		return (strlen($pmStr) > (int)$pmPosition) ? substr($pmStr, 0, (int)$pmPosition).".." : trim($pmStr);
	}
?>