<?php
global $C;


	if( isset($_POST['activities_id']) && isset($_POST['activities_type']) ) {
		$p	= new post($_POST['activities_type'], $_POST['activities_id']);
		if( $p->error ) {
			echo 'ERROR:Invalid post data provided.';
			return;
		}
		
		$post_reshare = new reshareMyPost($p);
		if( !$post_reshare->is_post_reshared() ){
			echo 'ERROR: You could not undo reshare this post';
			return;
		}
	
		if( $post_reshare->unshare_post() ){
			$reshares = $post_reshare->get_post_reshares();
			$reshares_number = is_array($reshares)? count($reshares) : '';
			if($reshares_number ==0){
				$reshares_number ='';
			}else{
				$reshares_number = $reshares_number;
			}
			
			$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="reshare" data-value="'.htmlentities('{"activities_type":"'.$_POST['activities_type'].'","activities_id":"'.$_POST['activities_id'].'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/></a><a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$_POST['activities_type'].'","activities_id":"'.$_POST['activities_id'].'"}').'">'.$reshares_number.'</a>';
			
				
			echo '<span class="reshare-list">'.$reshare_content.'</span>';
			return;
		}
	
	}
		
	echo 'ERROR:Invalid post.';
	return;
		
?>