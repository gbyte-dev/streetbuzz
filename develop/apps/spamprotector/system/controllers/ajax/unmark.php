<?php
	
	global $db2,$C;
	 
	if( isset($_POST['activities_id']) && isset($_POST['activities_type']) ){
		$activity_id = intval($_POST['activities_id']);
		$post_type = $_POST['activities_type'] == 'private'? 'private' : 'public';
		
		$db2->query('DELETE FROM posts_spamprotector WHERE post_type="'.$post_type.'" AND post_id="'.$activity_id.'" AND marked_by_user_id="'.$this->user->id.'"');
			
		echo '<div class="like-list icon-ftr"><a href="" data-role="services" title="Markspam" data-namespace="spamprotector" data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$activity_id.'"}').'"><img  class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a></div>';
		return;
	}
	
	echo 'ERROR: Invalid data provided.';
	return;