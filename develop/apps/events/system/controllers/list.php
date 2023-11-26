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

	
	$res = $db2->query('SELECT * from b_posts');

	$tpl = new template( array('page_title' => $this->lang('admpgtitle_administrators', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );
	
	$tpl->initRoutine('DashboardLeftMenu', array());
	$tpl->routine->load();
	

	$i = 0;
	if( $db2->num_rows($res) > 0 ){
		while($obj = $db2->fetch_object($res)) {
			$i++;
			
			$tpl->layout->useBlock('single-post', 'events');
			
			$tpl->layout->block->setVar('id', $obj->id);
			
			$tpl->layout->block->save( 'main_content', true );	
		}
	}
	
	/*$table = new tableCreator();
	$rows = array(
			$table->inputField( 'Group Name:', 'group', '' ),
			$table->inputField( 'Group ttile:', 'group', '' ),
			$table->submitButton( 'submit', $this->lang('admgnrl_frm_sbm') )
	);*/
	
	//$tpl->layout->setVar('main_content', $table->createTableInput( $rows ) );
	
	$tpl->display();
?>