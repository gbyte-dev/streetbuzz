<?php
	global $user, $db2;
	
	if( isset($_POST['group_id']) ){
		$gid = intval($_POST['group_id']);
		
		if( $gid > 0 && $user->info->is_network_admin == 1 ){		
			$db2->query('DELETE FROM groups_special WHERE group_id="'.$gid.'" LIMIT 1');
		 	echo 'Done.'; 
		 	return;
		}
	}
	
	echo 'ERROR: No special group selected.';
	return;