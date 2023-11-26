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
	
	if( isset($_POST['user_id'], $_POST['suspend']) ){
		$uid = intval($_POST['user_id']);
		$a	= $this->network->get_user_by_id($uid);
		if( $a ) {
			$admin = new communityAdministration();
			$admin->suspendUser($a->id);
		}
	}
	
	$res = $db2->query('SELECT u.id, u.username, COUNT(*) AS numer_of_reports FROM users u, posts_spamprotector ps WHERE u.id = ps.post_author_id AND u.active=1 GROUP BY ps.post_author_id ');
	$num_results = 0;
	
	$tpl = new template( array('page_title' => 'Reported users', 'header_page_layout'=>'sc') );
	
	$tpl->initRoutine('AdminLeftMenu', array());
	$tpl->routine->load();
	
	
	while( $obj = $db2->fetch_object($res) ){
		$num_results++;
		//check for num reports
		$table = new tableCreator();
		$rows = array(
				$table->textField('Username:', '<strong>'.$obj->username.'</strong>'),
				$table->textField('Number of SPAM reports:', '<strong>'.$obj->numer_of_reports.'</strong>'),
				$table->hiddenField('user_id', $obj->id),
				$table->submitButton('suspend', 'Suspend')
		);
	
		$tpl->layout->setVar('main_content', '<hr style="margin: 10px; border: 0; border-top: 1px solid #ccc;"/>'.$table->createTableInput( $rows ) );
	}
	
	if( $num_results === 0 ){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->okMessage('Reported users', 'There are no reported users yet' ) );
	}
	
	$tpl->display();

?>