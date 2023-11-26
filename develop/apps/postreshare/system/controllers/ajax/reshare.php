<?php
global $C;

	if( isset($_POST['activities_id']) && isset($_POST['activities_type']) ) {
		$p	= new post($_POST['activities_type'], $_POST['activities_id']);
		if( $p->error ) {
			echo 'ERROR:Invalid post data provided.';
			return;
		}
		$ownuserres          =$p->get_own_user($_POST['activities_id']);
		$ownuserid           =$ownuserres->user_id;
		$not_type='	ntf_me_on_post_rebuzz';
		$checkuserres =$p->checkemptyuser($ownuserid);

		if($checkuserres->num_rows == "0"){
			$ownnotification =1;
		}else{
			$ownnotification     =$p->checknotrules($ownuserid,$not_type);
			if(!empty($ownnotification)){
				$ownnotification = $ownnotification;
			}else{
				$ownnotification=1;
			}


		}

		if($ownnotification ==1 || $ownnotification ==2 || $ownnotification ==3  ){
				
		if($ownuserid != $user->id){
		$posttype      =$p->typeofpostofevent($_POST['activities_id']);
		if($posttype->num_rows > 0){
			$type ="event";
		}else{
		    $polltype      =$p->typeofpostofpoll($_POST['activities_id']);
			if($posttype->num_rows > 0){
				$type ="poll";
			}else{
			$activitiestype      =$p->typelinks($_POST['activities_id']);
			if(!empty($activitiestype)){
				
			 if($activitiestype->type=="videoembed"){
				 $type="video link";
				 
			 }elseif($activitiestype->type=="image"){
				  $type="image";
				 
			 }elseif($activitiestype->type=="file"){
				  $str = (unserialize($activitiestype->data));
				 $ext = pathinfo($str->file_original, PATHINFO_EXTENSION);
				 if($ext =='wmv' || $ext =='mp4' || $ext =='avi' || $ext =='mov' || $ext =='qt'){
					 $type = "video";
					 
				 }else{
					  $type = "file";
					 
				 }
			}
			}else{
			$type ="buzz";	
			}

			}				
		}
		$notifytype="rebuzz";
		$standardtype ="ntf_me_on_post_rebuzz";



		$newisert =$p->insert_active_notifications($ownuserid,$_POST['activities_id'],$notifytype,$type,$standardtype);
		}
		}

	
		$post_reshare = new reshareMyPost($p);
		if( !$post_reshare->could_be_reshared() || $post_reshare->is_post_reshared() ){
			echo 'ERROR: You could not reshare this post';
			return;
		}
	
		if( $post_reshare->reshare_post(TRUE) ){
			$reshares = $post_reshare->get_post_reshares();
			$reshares_number = is_array($reshares)? count($reshares) :'';
			if($reshares_number ==0){
				$reshares_number ='';
			}else{
				$reshares_number = $reshares_number;
				
			}
			
			$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="unreshare" data-value="'.htmlentities('{"activities_type":"'.$_POST['activities_type'].'","activities_id":"'.$_POST['activities_id'].'"}').'"><img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Undobuzz"/></a><a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$_POST['activities_type'].'","activities_id":"'.$_POST['activities_id'].'"}').'">'.$reshares_number.'</a>';
			
			echo '<span class="reshare-list">'.$reshare_content.'</span>';		
			return;
		}
	
	}
		
	echo 'ERROR:Invalid post.';
	return;
			
?>