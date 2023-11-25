<?php
	global $user, $db2, $C;
	if( isset($_POST['attach_id']) ){
		
		$event_src = $db2->query('SELECT * FROM event_attachemnts WHERE id="'.$_POST['attach_id'].'" LIMIT 1');
		$event_attach = $db2->fetch_object($event_src);
		if($event_attach){
			$db2->query('DELETE FROM event_attachemnts WHERE id="'.$event_attach->id.'"');
			//@unlink($event_attach->link);
			//@unlink($event_attach->thumb_link);
		}
		$answer = array('event'=>'success');
		echo json_encode($answer);
		return;
	}
	?>