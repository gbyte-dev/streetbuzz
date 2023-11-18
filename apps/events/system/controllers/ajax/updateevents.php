<?php
	error_reporting(0);

	global $user, $db2;
	if( isset($_POST['start']) ){
		$event_id = mysql_real_escape_string( addslashes($_POST['id']));
		$start = mysql_real_escape_string( addslashes($_POST['start']));
		$end = mysql_real_escape_string( addslashes($_POST['end']));
		$start_date = date('Y-m-d', $start);
		$start_time = date('H:i:s', $start);
		$end_date = date('Y-m-d', $end);
		$end_time = date('H:i:s', $end);
		$events = $db2->query("UPDATE events SET modified_at = now(), start_date='$start_date', start_time='$start_time', end_date='$end_date', end_time='$end_time' WHERE id=$event_id");
		if($events){
			// Send JSON to the client.
			$answer = array('html'=>false, 'events'=>'success');
			echo json_encode($answer);
			return;
		}else{
			$answer = array('html'=>false, 'events'=>'error');
			echo json_encode($answer);
			return;
		}
	}
	
	echo 'ERROR: Event not updated.';
	return;