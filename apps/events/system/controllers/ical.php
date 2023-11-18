<?php
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if(!$this->user->is_logged){
		$this->redirect('signin');
	}
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/dashboard.php');
	$this->load_langfile('inside/group.php');
	$this->load_langfile('inside/dashboard.php');
	$this->load_langfile('inside/groups_new.php');
	
	$event_id = $this->param('event'); 

	if($event_id > 0 )
	{
		
		//TEMPLATE CODE START

		$url = $C->SITE_URL.'plugin/events/home';
		$res = $db2->query("SELECT * FROM events WHERE id=$event_id AND status=1 LIMIT 1");
		if( $db2->num_rows($res) > 0 )
		{
			while($obj = $db2->fetch_object($res))
			{
				if($event_id)
				{
					header("Content-Type: text/Calendar");
					header("Content-Disposition: inline; filename=calendar.ics");
					echo "BEGIN:VCALENDAR\n";
					echo "VERSION:2.0\n";
					echo "PRODID:-//Sharetronix//NONSGML Event//EN\n";
					echo "METHOD:REQUEST\n"; // requied by Outlook
					echo "BEGIN:VEVENT\n";
					echo "UID:".date('Ymd').'T'.date('His')."-".rand()."-sharetronix.com\n"; // required by Outlok
					echo "DTSTAMP:".date('Ymd').'T'.date('His')."\n"; // required by Outlook
					echo "DTSTART:".date('c', strtotime($obj->start_date.' '.$obj->start_time))."\n"; 
					echo "DTEND:".date('c', strtotime($obj->end_date.' '.$obj->end_time))."\n"; 
					echo "SUMMARY;ENCODING=QUOTED-PRINTABLE:".ucwords($obj->event_name)."\n";
					echo "DESCRIPTION:".$obj->event_description."\n";
					echo "LOCATION;ENCODING=QUOTED-PRINTABLE:".($obj->address)."\n";
					echo "END:VEVENT\n";
					echo "END:VCALENDAR\n";
					exit;

				}
				else {
					
					$this->redirect('plugin/events/home/tab:list_events');
				}
			}
		}
	}
	
	//TEMPLATE CODE END
	function dateToCal($time) {
		return date('Ymd\This', $time) . 'Z';
	}
?>