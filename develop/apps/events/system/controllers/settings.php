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
	if( isset($_POST['facebook_app_key']) ) {
		$submit = TRUE;
		if( empty($_POST['facebook_app_key']) ||  empty($_POST['facebook_secret_key']) ||  
		empty($_POST['google_app_key']) ||  empty($_POST['google_secret_key']) ) {
			$error = TRUE;
			$errmsg = 'No changes found.';
		}else{
			$facebook_app_key = $this->db2->escape($_POST['facebook_app_key']);
			$facebook_secret_key = $this->db2->escape($_POST['facebook_secret_key']);
			$google_app_key = $this->db2->escape($_POST['google_app_key']);
			$google_secret_key = $this->db2->escape($_POST['google_secret_key']);
			$time_zone = $this->db2->escape($_POST['time_zone']);
			$this->db2->query('REPLACE INTO `event_settings` (id, updated_at, facebook_app_key, facebook_secret_key, google_app_key, google_secret_key)
				VALUES (1, now(), "'.$facebook_app_key.'", "'.$facebook_secret_key.'", 
				"'.$google_app_key.'","'.$google_secret_key.'")');			
			$this->redirect( $C->SITE_URL.'plugin/events/settings/msg:grpsaved' );
		}
	}
	
	$res = $db2->query('SELECT * FROM event_settings LIMIT 1');

	$tpl = new template( array('page_title' => $this->lang('admpgtitle_administrators', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );
	
	$tpl->initRoutine('AdminLeftMenu', array());
	$tpl->routine->load();
	
	if( ($submit && !$error) || $this->param('msg') == 'grpsaved' ){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->okMessage($this->lang('admtrms_ok_ttl'), $this->lang('admadm_frm_ok_txt') ) );
	}else if( $submit && $error ){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->errorMessage('Error', $errmsg) );
	}
	
	$setting = $db2->fetch_object($res);
	if( empty($setting) && !$submit && !$this->param('msg') ){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->okMessage('No Setting Saved', 'There is no facebook or google settings saved yet.' ) );
	}

	$table = new tableCreator();
	$table->max_input_length=255;

	$rows = array(
			$table->inputField( 'Facebook App ID:', 'facebook_app_key', empty($setting->facebook_app_key)?'':$setting->facebook_app_key),
			$table->inputField( 'Facebook App Secret:', 'facebook_secret_key', empty($setting->facebook_secret_key)?'':$setting->facebook_secret_key),
			$table->inputField( 'Google Client ID:', 'google_app_key', empty($setting->google_app_key)?'':$setting->google_app_key),
			$table->inputField( 'Google Client Secret:', 'google_secret_key', empty($setting->google_secret_key)?'':$setting->google_secret_key),
			$table->submitButton( 'submit', $this->lang('admgnrl_frm_sbm') )
	);
	
	$tpl->layout->setVar('main_content', $table->createTableInput( $rows ) );
	
	$tpl->display();
?>