<?php
	
	global $db2,$C;
	 
	if( isset($_POST['activities_id']) && isset($_POST['activities_type']) ){
		$activity_id = intval($_POST['activities_id']);
		$post_type = $_POST['activities_type'] == 'private'? 'private' : 'public';
		$post_author_id = 0;
		$is_admin = 0;
		
		$res = $db2->query('SELECT u.id, u.is_network_admin FROM '.( $post_type == 'private'? 'posts_pr' : 'posts' ).' p, users u WHERE u.id = p.user_id AND p.id="'.$activity_id.'" LIMIT 1');
		if( $res = $db2->fetch_object($res) ){
			$post_author_id = intval($res->id);
			$is_admin = intval($res->is_network_admin);
		}

		if( $post_author_id > 0 && $is_admin === 0 && $post_author_id !== $this->user->id ){
			$res = $db2->fetch_field('SELECT 1 FROM posts_spamprotector WHERE post_id="'.$activity_id.'" AND marked_by_user_id="'.$this->user->id.'" LIMIT 1');
			
			if( !$res ){
				$db2->query('INSERT INTO posts_spamprotector(post_id, marked_by_user_id, post_author_id, post_type) VALUES("'.intval($activity_id).'", "'.intval($this->user->id).'", "'.intval($post_author_id).'", "'.$post_type.'")');
			}
			
			echo '<div class="like-list icon-ftr"><a href="" title="Undospam" data-role="services" data-namespace="spamprotector" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$activity_id.'"}').'"><em><img  src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a></div>';
			return;
		}
		
		echo 'ERROR: You couldn\'t mark this post as spam. Probably your own or administrator\'s post.';
		return;
	}
	
	echo 'ERROR: Invalid data provided.';
	return;