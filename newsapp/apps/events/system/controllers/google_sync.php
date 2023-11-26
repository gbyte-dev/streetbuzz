<?php
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if(!$this->user->is_logged){
		$this->redirect('signin');
	}
	
	$res = $db2->query('SELECT * FROM event_settings LIMIT 1');

	$setting = $db2->fetch_object($res);	
	if(empty($setting->google_app_key) || empty($setting->google_secret_key) ){
		$url = $C->SITE_URL.'plugin/events/home/tab:list_events';
	}
	
	
	//include google library
	require_once $C->PLUGINS_DIR.'events/system/libraries/google/src/Google/Client.php';
	require_once $C->PLUGINS_DIR.'events/system/libraries/google/src/Google/Service/Calendar.php';

	$client_id = $setting->google_app_key;
	$client_secret = $setting->google_secret_key;
	$redirect_uri = $C->SITE_URL.'plugin/events/google_sync';

	$client = new Google_Client();
	$client->setClientId($client_id);
	$client->setClientSecret($client_secret);
	$client->setRedirectUri($redirect_uri);
	$client->setScopes("https://www.googleapis.com/auth/calendar");
	
	$service = new Google_Service_Calendar($client); 
	
	if($client->isAccessTokenExpired()) {
		$authUrl = $client->createAuthUrl();
		header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
	}

	if (isset($_GET['code'])) {
	 
	 $client->authenticate($_GET['code']);
	  $_SESSION['access_token'] = $client->getAccessToken();
	}

	
	if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
	  $client->setAccessToken($_SESSION['access_token']);
	}else{
		$authUrl = $client->createAuthUrl();
		 header('Location: ' .$authUrl);
	}

	$group_id = '';
	
	$res = $db2->query('SELECT * FROM events WHERE id="'.$this->params->event.'" AND status=1 ORDER BY created_at DESC');
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

				$event = new Google_Service_Calendar_Event();
				$event->setSummary($obj->event_name);
				$event->setDescription($obj->event_description);
				$event->setLocation($obj->location.' '.$obj->address);
				$start = new Google_Service_Calendar_EventDateTime();
				$start->setDateTime(date("c", strtotime($obj->start_date.' '.$obj->start_time)));
				$event->setStart($start);
				$end = new Google_Service_Calendar_EventDateTime();
				$end->setDateTime(date("c", strtotime($obj->end_date.' '.$obj->end_time)));
				$event->setEnd($end);
				
				/*$attendee1 = new EventAttendee();
				$attendee1->setEmail('attendeeEmail');
				// ...
				$attendees = array($attendee1,
								   // ...
								  );
				$event->attendees = $attendees;*/
				$group_id = $obj->group_id;			
				
				$createdEvent = $service->events->insert('primary', $event);

				$createdEvent->getId();	

		}
	}
	if($group_id){
		$url = $C->SITE_URL.'plugin/events/home/tab:list_events/group:'.$group_id;
	}else{
		$url = $C->SITE_URL.'plugin/events/home/tab:list_events';
	}

	header('Location:'.$url);
?>