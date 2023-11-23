<?php
	
	
	global $page;
	$page->event = $_POST['event_id'];
	require_once($C->PLUGINS_DIR.'events/system/controllers/edit_eventaccept_ajax.php');
	
	
	
?>