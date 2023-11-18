<?php
	if( isset($_POST['activities_id']) && isset($_POST['activities_type']) ) {
		$p	= new post($_POST['activities_type'], $_POST['activities_id']);
		if( $p->error ) {
			echo 'ERROR:Invalid post data provided.';
			return;
		}
		
		global $C;
		$post_reshare = new reshareMyPost($p);
		$reshares = $post_reshare->get_post_reshares();
		$html = '';
		if( is_array($reshares) && count($reshares)>0 ){
			foreach( $reshares as $v ){
				$html .= '<div class="popup-count"><a href="'.userlink($v[0]).'"><img src="'.getAvatarUrl($v[1], 'thumbs3').'" /> '.$v[0].'</a>
				</div>';
			}
		}
		
		echo $html;
		return;
	
	}
	
	echo 'ERROR:Invalid post.';
	return;