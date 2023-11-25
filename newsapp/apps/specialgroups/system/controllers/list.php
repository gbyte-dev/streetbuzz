<?php		
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	$db2->query('SELECT 1 FROM users WHERE id="'.$this->user->id.'" AND is_network_admin=1 LIMIT 1');
	if( 0 == $db2->num_rows() ) {
		$this->redirect('dashboard');
	}
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/admin.php');
	
	$submit = FALSE;
	$error = FALSE;
	$errmsg = '';
	
	if( isset($_POST['group']) ) {
		$submit = TRUE;
		$group	= trim($_POST['group']);
		$a	= $this->network->get_group_by_name($group);
		
		if( !$a ){
			$error = TRUE;
			$errmsg = 'Invalid group name selected.';
		}else if( !$a->is_public ){
			$error = TRUE;
			$errmsg = 'You could not make a private group special.';
		}else{
			$res = $this->db2->query('SELECT 1 FROM groups_special WHERE group_id="'.$a->id.'" LIMIT 1');
			if( !$this->db2->num_rows($res) ){
				$this->db2->query('INSERT INTO `groups_special` (group_id) VALUES ("'.intval($a->id).'") ');			
				$this->network->get_group_by_id($a->id, TRUE);			
				$this->redirect( $C->SITE_URL.'plugin/specialgroups/list/msg:grpsaved' );
			}else{
				$error = TRUE;
				$errmsg = 'This group is special.';
			}
		}
	}
	
	$res = $db2->query('SELECT g.* FROM groups g, groups_special gs WHERE gs.group_id = g.id ORDER BY g.title ASC');

	$tpl = new template( array('page_title' => $this->lang('admpgtitle_administrators', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );
	
	$tpl->initRoutine('AdminLeftMenu', array());
	$tpl->routine->load();
	
	if( ($submit && !$error) || $this->param('msg') == 'grpsaved' ){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->okMessage($this->lang('admtrms_ok_ttl'), $this->lang('admadm_frm_ok_txt') ) );
	}else if( $submit && $error ){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->errorMessage('Error', $errmsg) );
	}
	
	$i = 0;
	if( $db2->num_rows($res) > 0 ){
		while($obj = $db2->fetch_object($res)) {
			$i++;
			
			$tpl->layout->useBlock('single-group');
			
			$group_type =  ($obj->is_public)? 'public' : 'private' ;
			$tpl->layout->block->setVar('single_group_type', $group_type);
			$tpl->layout->block->setVar( 'single_group_avatar', '<a href="'.$C->SITE_URL.$obj->groupname.'"><img src="'.$C->STORAGE_URL.'avatars/thumbs1/'.(empty($obj->avatar)? $C->DEF_AVATAR_GROUP : $obj->avatar).'" alt="'.$obj->groupname.'"/></a>');
			$tpl->layout->block->setVar( 'single_group_name', '<a href="'.$C->SITE_URL.$obj->groupname.'">'.ucfirst($obj->title).'</a>' );
			$tpl->layout->block->setVar( 'single_group_activity', '<a href="'.$C->SITE_URL.$obj->groupname. '/tab:members">'.$obj->num_followers.' members</a> &middot '. $obj->num_posts.' posts' );
			$tpl->layout->block->setVar( 'single_group_description', $obj->about_me );
			$tpl->layout->block->setVar( 'single_group_join_leave', '<a class="action-btn user-action remove-user" data-action="removespecialgroup" data-value="'.htmlentities('{"group_id":"'.$obj->id.'"}').'" data-namespace="specialgroups" data-role="services"><span class="tooltip"><span>Remove Special Group</span></span></a>' ); unset($menu_items);

			$tpl->layout->block->save( 'main_content', true );	
		}
	}
	
	if( $i == 0 && !$submit && !$this->param('msg') ){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->okMessage('No special groups', 'There are not special groups selected in your community.' ) );
	}
	
	$table = new tableCreator();
	$rows = array(
			$table->inputField( 'Group Name:', 'group', '' ),
			$table->submitButton( 'submit', $this->lang('admgnrl_frm_sbm') )
	);
	
	$tpl->layout->setVar('main_content', $table->createTableInput( $rows ) );
	
	$tpl->display();

?>