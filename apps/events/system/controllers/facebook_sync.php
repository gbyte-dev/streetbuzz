<?php
	@session_start();
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if(!$this->user->is_logged){
		$this->redirect('signin');
	}
	
	$res = $db2->query('SELECT * FROM event_settings LIMIT 1');

	$setting = $db2->fetch_object($res);
	
	if(empty($setting->facebook_app_key) || empty($setting->facebook_secret_key) ){
		$url = $C->SITE_URL.'plugin/events/home/tab:list_events';
	}
	//session_destroy();
	//include facebook library
	require $C->PLUGINS_DIR.'events/system/libraries/facebook/src/facebook.php';
	
	$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	//face app key include
	$facebook = new Facebook(array(
	  'appId'  => $setting->facebook_app_key,
	  'secret' => $setting->facebook_secret_key,
	  'cookie' => true,
	  'redirect_uri' => $current_url
	));
	

	//get user info
	$fb_user = $facebook->getUser();
	//Check whether user is code
	if ($fb_user) {
		$logoutUrl = $facebook->getLogoutUrl();
	} else {
		$loginUrl = $facebook->getLoginUrl(array('scope' => 'create_event'));
		echo "<script>window.location = '$loginUrl'</script>";
		exit;
		//header("Location:".$loginUrl);
	}	
	$res = $db2->query('SELECT * FROM events WHERE id="'.$this->params->event.'" AND status=1 ORDER BY created_at DESC');
	
	$group_id = '';
	
	if( $db2->num_rows($res) > 0 )
	{
		while($obj = $db2->fetch_object($res)){
			//check attachemnt any found
			//$file = "end300.jpg";
			
			//The event information array (timestamps are "Facebook time"...)
			$event_info = array(
				"privacy_type" => "SECRET",
				"name" => ucwords($obj->event_name),
				"start_time" => date("c", strtotime($obj->start_date.' '.$obj->start_time)),
				"end_time" => date("c", strtotime($obj->end_date.' '.$obj->end_time)),
				"location" => $obj->address,
				"description" => $obj->event_description
			);
			$group_id = $obj->group_id;
			//The key part - The path to the file with the CURL syntax
			//$event_info[basename($file)] = '@' . realpath($file);

			//Make the call and get back the event ID
			$result = $facebook->api('/me/events','POST',$event_info);
		}
	}
	if($group_id){
		$url = $C->SITE_URL.'plugin/events/home/tab:list_events/group:'.$group_id;
	}else{
		$url = $C->SITE_URL.'plugin/events/home/tab:list_events';
	}
	header('Location:'.$url);
?>