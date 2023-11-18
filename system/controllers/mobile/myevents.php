<?php
	
	if( !$this->user->is_logged ) {
		$this->redirect('home');
	}
				require_once($C->PLUGINS_DIR.'events/system/controllers/home_new1.php');
	
	
	
?>