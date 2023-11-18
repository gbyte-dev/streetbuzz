<?php
	error_reporting(0);
	if( !$this->user->is_logged ) {
		$this->redirect('home');
	}
	
	$this->load_langfile('inside/global.php');
	if($_POST['type'] =="list_myeven"){
		$from    = $_POST['myshowcount'];
		$upto = 10;
		

		$myevent            =$db2->query('SELECT id as eventid,event_name,address,location,event_description,start_date,start_time,end_date,end_time,url,status,tag_name
		FROM   events  where admin_id="'.$this->user->id.'"  order by id desc limit '.$from.' ,'.$upto.' ' );;
	while($myeventfetch[] = $db2->fetch_object($myevent)){
	}
    $D->myeventfetch = ($myeventfetch);
	}
	if($_POST['id'] =="list_accept"){

	$eventnotifyaccept           = $db2->query('SELECT e.id as eventid,e.event_name,e.address,e.location,e.event_description,e.start_date,e.start_time,e.end_date,e.end_time,e.url,e.status,e.event_type,e.tag_name,pu.event_status,ep.edit_status 
		FROM `post_userbox` as pu 
	                inner join event_posts as ep ON ep.post_id = pu.post_id 
					inner join events as e on ep.event_id = e.id 
					WHERE pu.user_id = "'.$this->user->id.'" and (pu.event_status = 1 and pu.status is null) order by eventid desc LIMIT 10' );
	while($eventnotifyacceptfetch[] = $db2->fetch_object($eventnotifyaccept)){
	}
	$D->eventnotifyacceptfetch  = $eventnotifyacceptfetch;
	//$this->block->setVar();
	}
		if($_POST['type'] =="list_all"){
			$from    = $_POST['allshowcount'];
			$to=10;
		
	
    $eventallres           = $db2->query('SELECT e.id as eventid,e.event_name,e.address,e.location,e.event_description,e.start_date,e.start_time,e.end_date,e.end_time,e.url,e.status,e.event_type,e.tag_name,pu.event_status,ep.edit_status 
	               FROM `post_userbox` as pu 
	                inner join event_posts as ep ON ep.post_id = pu.post_id 
					inner join events as e on ep.event_id = e.id 
					WHERE pu.user_id = "'.$this->user->id.'" and ((pu.status =1 and pu.event_status is null) or pu.event_status = 1)   order by eventid desc LIMIT '.$from.' ,'.$to.' ' );
   while($eventallresfetchres[] = $db2->fetch_object($eventallres)){
	}
		$D->eventallresfetchres  = $eventallresfetchres;
  }
  		if($_POST['type'] =="list_all"){

  
	$alleventhtml='';
	foreach($eventallresfetchres as $keys=>$vals){

 if(!empty($eventallresfetchres[$keys])){	
 	$date_time = date('M d, Y h:i A', strtotime($vals->start_date.' '.$vals->start_time));
	 $hasarr          =explode("#",$vals->tag_name);
	  $strret_arr       =array_filter($hasarr);
	  $con ='';
	  		foreach($strret_arr as $haskeys=>$hasvals){
				$con .='<span><a href="'.$C->SITE_URL.'/search/tab:tags/s:'.$hasvals.'"><strong>#'.$hasvals.'</strong></a>';

			}
			 if($vals->status ==1){
						$st  ="Active";
					}
					if($vals->status ==2){
						$st  ="Cancelled";
					}

	      



 
$myevent            =$db2->query('SELECT u.id,u.username,u.fullname,u.avatar,ep.post_id,p.date as postdate,p.user_id  FROM event_posts as ep
      inner join posts as p ON ep.post_id = p.id
	   inner join users as u ON p.user_id = u.id
    where ep.event_id = "'.$vals->eventid.'" ' );
 $myeventres                  =$db2->fetch_object($myevent);
 if($myeventres->avatar !=''){
	 $src=getAvatarUrl($myeventres->avatar, 'thumbs1');
	 
 }else{
	 $src = $C->SITE_URL.'storage/avatars/thumbs1/_noavatar_user.gif';
 }
 if($myeventres->user_id != $this->user->id){
	  				if($vals->event_status !=2  ){
						
						if( ($vals->event_status =='' &&  $vals->edit_status =='')   ){
						$userresponse = '<div id="acc-'.$myeventres->post_id.'"><strong>User Response:</strong><input type="radio"  class="accept" name="accept" onclick="myFunction('.$myeventres->post_id.'1)" value="'.$myeventres->post_id.'-1">Accept<input type="radio" class="accept" name="accept" onclick="myFunction('.$myeventres->post_id.'3)"  value="'.$myeventres->post_id.'-3">Reject</div>';
					    
					}
				
					
					if( ($vals->event_status !=2 ||  $vals->edit_status!=4) &&  ($vals->event_status !=2 &&   $vals->edit_status!=4) ){

					
					if($vals->event_status == 1){
					 $userresponse = '<div "id="accept-'.$myeventres->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					}
					if($vals->event_status == 3){
						$userresponse ='<div id="reject-'.$myeventres->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
					}
					 //$userresponse ='<div style="display:'.$displayreject.';"id="reject-'.$myeventres->post_id.'"><strong>User Response:</strong>Event Rejected</div>';

                  
                    }else{
						if((($vals->event_status!=4) &&   ($vals->edit_status==4))){
							
					$userresponse = '<div style="display:'.$display.';"id="accept-'.$myeventres->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					$userresponse = '<div style="display:'.$displayreject.';"id="reject-'.$myeventres->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
                  
							
						}else{
						$userresponse = '<div>This event was no longer available.</div>';
						}
					}
					if($vals->event_status == 5){
						 $userresponse =  '<div>This event was modified.</div>';
						
					}

					$status =  '<div>Status:'.$st.'</div>';

					
					
					
					}else{
						
						$status =  '<div>Event Cancelled</div>';

						
					}
	 
 }else{
						if((($vals->event_status!=2) &&   ($vals->edit_status!=4)) || (($vals->event_status!=4) &&   ($vals->edit_status==4))   ){
						 $status =  '<div><strong>Status:</strong>'.$st.'</div>';
						  $userresponse =  '';


						 $download = '<div><a href="'.$C->SITE_URL.'dashboard?pid='.$myeventres->post_id.'"><input class="btn-download-results" type="button" name="download" value="Download Results"></a></div>';

						}else{
							if($vals->status == 2 && $vals->edit_status!=4 ){
								$status =  '<div><strong>status:</strong>Cancel</div>';

								
							}else{
							$status = '<div><strong>Status:</strong>'.$st.'</div>';

							$status = '<div>This event was no longer available.</div>';

							}

							
						}
						
					}
					$url = 'https://www.google.com/maps/search/'.urlencode(htmlentities($vals->address, ENT_COMPAT, 'UTF-8'));
				$window_pop = "MyWindow=window.open('$url','Map','width=600,height=500'); return false;";
 
		                
		$alleventhtml .='
		 <div class="activity no-comments">
<a href="'.userlink($myeventres->username).'" class="avatar bizcard" data-userid="'.$myeventres->id.'"><img src="'.$src.'"  /></a>	
	<div class="activity-container">
		<div class="activity-header">
			<a href="'.userlink($myeventres->username).'" class="author bizcard" data-userid="'.$myeventres->id.'">'. ($myeventres->username) .'</a>
			<div class="meta-info">
				
				
			</div>
			<div class="activity-options"><a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" class="delete">delete</a>	
			<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="bookmark">bookmark</a>
			</div>
		</div>
		<div class="activity-content"></div>
		<div><div class="attachments lightbox-enabled">
	
	<div class="links">
		<div class="container">
	
	

	<div class="content text-info">
		<a target="_blank" href="'.$C->SITE_URL.''.$myeventres->username.'/tab:events/action:view/id:'.$vals->eventid.'" class="link-title"></a><div class="title"><a target="_blank" href="'.$C->SITE_URL.''.$myeventres->username.'/tab:events/action:view/id:'.$vals->eventid.'" class="link-title"><img class="icon-calander" src="'.$C->SITE_URL.'/apps/events/static/images/event.png"> </a><a href="'.$C->SITE_URL.''.$myeventres->username.'/tab:events/action:view/id:'.$vals->eventid.'/postid:'.$myeventres->post_id.'" "><strong>Event Name:</strong> '.$vals->event_name.'</a></div>
		<span>
			<p class="address_view">
										<span><strong>Location:</strong> '.$vals->address.' <a style="color:darkblue; font-weight:bold;" onclick="'.$window_pop.'" href="#">view map</a></span>
									</p>
									<p class="address_view">
										<span><strong>URL:</strong> <a href="http://www.'.$vals->url.'" target="_blank">'.$vals->url.'</a></span>
									</p>								
									<span class="time"><strong>Date and Time:</strong>'.$date_time.'</span>
									<span><stong>Hash Tags:</strong>'.$con.'</span>
									'.$userresponse.'
									<div><strong>Status:'.$st.'</strong></div>
		                           '.$download.'
	</span></div>
	<div class="clear"></div>
</div>			
	</div>
	
</div></div>
		<div class="activity-poll"></div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	
	<div>
	<div class="activity-footer meta-info">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$myeventres->post_id.'" class="permlink">'.post::parse_date($myeventres->postdate).'</a>
				<a style="cursor:pointer" onclick="activityAddCommentchield('.$myeventres->post_id.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>
				<a style="cursor:pointer"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png" title="Like"></a>
				<a style="cursor:pointer"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"></a>
				<div class="dropdown">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >Linkedin</a></li>
								  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'  target="_blank" >StumbleUpon</a></li>
								  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'  target="_blank" >MySpace</a></li>
								  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'  target="_blank" >FriendFeed</a></li>
								  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>		
				</div>
				<div class="comment-chield" style="display:none" id="chield'.$myeventres->post_id.'">
				<div class="comments-editor data-content-placeholder" data-token="069fc5555">
				<div>
				
				<div class="activity-header commentpost'.$myeventres->post_id.'">
				<a href="'.$C->SITE_URL.''.$myeventres->username.'" class="author bizcard" data-userid="'.$myeventres->id.'">'.$myeventres->username.'</a>
				
				</div>
				<form method="post"action="'.$C->SITE_URL.'plugin/poll/admin?action=comment&amp;posts_id='.$myeventres->post_id.'">
				<div class="comments-editor-content">
					<div class="user-status-field htmlarea">
					<div class="commentpoll commentpoll'.$myeventres->post_id.'">
					</div>
						<div class="textarea-wrap comment">
							<textarea name="message">@'.$myeventres->username.' </textarea>
							<div class="textarea-highlighter"><span></span></div>
						</div>
					</div>			
							<div class="buttons">
								<button type="button" id="'.$myeventres->post_id.'" class="comment-post pollcreate left comment-post'.$myeventres->post_id.' post-btn btn blue"><span>POLL</span></button>
								
									<input type="submit" class="comment-post comment-post'.$myeventres->post_id.' post-btn btn  blue center" value="Buzz">
								
							</div>
						</div></form>
					</div>
				</div></div><br><br>
	</div>
	<div>
	
	</div>
</div>
		';
		
	}
	}
		echo $alleventhtml;

}
	//myevents tab
			if($_POST['type'] =="list_myeven"){

	$myeventhtml ='';
	foreach($myeventfetch as $keys=>$vals){

 if(!empty($myeventfetch[$keys])){	
 	$date_time = date('M d, Y h:i A', strtotime($vals->start_date.' '.$vals->start_time));
	 $hasarr          =explode("#",$vals->tag_name);
	  $strret_arr       =array_filter($hasarr);
	  $con ='';
	  		foreach($strret_arr as $haskeys=>$hasvals){
				$con .='<span><a href="'.$C->SITE_URL.'/search/tab:tags/s:'.$hasvals.'"><strong>#'.$hasvals.'</strong></a>';

			}
			if($vals->status ==1){
						$st  ="Active";
					}
					if($vals->status ==2){
						$st  ="Cancelled";
					}
			

	      



 
$myevent            =$db2->query('SELECT u.id,u.username,u.fullname,u.avatar,ep.post_id,p.date as postdate  FROM event_posts as ep
      inner join posts as p ON ep.post_id = p.id
	   inner join users as u ON p.user_id = u.id
    where ep.event_id = "'.$vals->eventid.'" ' );
 $myeventres                  =$db2->fetch_object($myevent);
 if($myeventres->avatar !=''){
	 $src=getAvatarUrl($myeventres->avatar, 'thumbs1');
	 
 }else{
	 $src = $C->SITE_URL.'storage/avatars/thumbs1/_noavatar_user.gif';
 }
 $url = 'https://www.google.com/maps/search/'.urlencode(htmlentities($vals->address, ENT_COMPAT, 'UTF-8'));
				$window_pop = "MyWindow=window.open('$url','Map','width=600,height=500'); return false;";
	
		                
		$myeventhtml .='
		 <div class="activity no-comments">
<a href="'.userlink($myeventres->username).'" class="avatar bizcard" data-userid="'.$myeventres->id.'"><img src="'.$src.'"  /></a>	
	<div class="activity-container">
		<div class="activity-header">
			<a href="'.userlink($myeventres->username).'" class="author bizcard" data-userid="'.$myeventres->id.'">'. ($myeventres->username) .'</a>
			<div class="meta-info">
				
				
			</div>
			<div class="activity-options"><a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" class="delete">delete</a>	
			<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="bookmark">bookmark</a>
			</div>
		</div>
		<div class="activity-content"></div>
		<div><div class="attachments lightbox-enabled">
	
	<div class="links">
		<div class="container">
	
	

	<div class="content text-info">
		<a target="_blank" href="'.$C->SITE_URL.''.$myeventres->username.'/tab:events/action:view/id:'.$vals->eventid.'" class="link-title"></a><div class="title"><a target="_blank" href="'.$C->SITE_URL.''.$myeventres->username.'/tab:events/action:view/id:'.$vals->eventid.'" class="link-title"><img class="icon-calander" src="'.$C->SITE_URL.'/apps/events/static/images/event.png"> </a><a href="'.$C->SITE_URL.''.$myeventres->username.'/tab:events/action:view/id:'.$vals->eventid.'/postid:'.$myeventres->post_id.'" "><strong>Event Name:</strong> '.$vals->event_name.'</a></div>
		<span>
			<p class="address_view">
										<span><strong>Location:</strong> '.$vals->address.' <a style="color:darkblue; font-weight:bold;" onclick="'.$window_pop.'" href="#">view map</a>				
</span>
									</p>
									<p class="address_view">
										<span><strong>URL:</strong> <a href="http://www.'.$vals->url.'" target="_blank">'.$vals->url.'</a></span>
									</p>								
									<span class="time"><strong>Date and Time:</strong>'.$date_time.'</span>
									<span><stong>Hash Tags:</stong>'.$con.'</span>
									<div><strong>Status:'.$st.'</strong></div><div><a href="'.$C->SITE_URL.'dashboard?pid='.$myeventres->post_id.'"><input class="btn-download-results" type="button" name="download" value="Download Results"></a></div>
	</span></div>
	<div class="clear"></div>
</div>			
	</div>
	
</div></div>
		<div class="activity-poll"></div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	
	<div>
	<div class="activity-footer meta-info">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$myeventres->post_id.'" class="permlink">'.post::parse_date($myeventres->postdate).'</a>
				<a style="cursor:pointer" onclick="activityAddCommentchield('.$myeventres->post_id.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>
				<a style="cursor:pointer"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png" title="Like"></a>
				<a style="cursor:pointer"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"></a>
<div class="dropdown">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >Linkedin</a></li>
								  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'  target="_blank" >StumbleUpon</a></li>
								  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'  target="_blank" >MySpace</a></li>
								  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'  target="_blank" >FriendFeed</a></li>
								  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>						</div>
				<div class="comment-chield" style="display:none" id="chield'.$myeventres->post_id.'">
				<div class="comments-editor data-content-placeholder" data-token="069fc5555">
				<div>
				
				<div class="activity-header commentpost'.$myeventres->post_id.'">
				<a href="'.$C->SITE_URL.''.$myeventres->username.'" class="author bizcard" data-userid="'.$myeventres->id.'">'.$myeventres->username.'</a>
				
				</div>
				<form method="post"action="'.$C->SITE_URL.'plugin/poll/admin?action=comment&amp;posts_id='.$myeventres->post_id.'">
				<div class="comments-editor-content">
					<div class="user-status-field htmlarea">
					<div class="commentpoll commentpoll'.$myeventres->post_id.'">
					</div>
						<div class="textarea-wrap comment">
							<textarea name="message">@'.$myeventres->username.' </textarea>
							<div class="textarea-highlighter"><span></span></div>
						</div>
					</div>			
							<div class="buttons">
								<button type="button" id="'.$myeventres->post_id.'" class="comment-post pollcreate left comment-post'.$myeventres->post_id.' post-btn btn blue"><span>POLL</span></button>
								
									<input type="submit" class="comment-post comment-post'.$myeventres->post_id.' post-btn btn  blue center" value="Buzz">
								
							</div>
						</div></form>
					</div>
				</div></div><br><br>
	</div>
	<div>
	
	</div>
</div>
		';
		
	}
	}
	echo $myeventhtml;
			}
		if($_POST['id'] =="list_accept"){

	//accepted tabs
	$acceptedhtml ='';
	foreach($eventnotifyacceptfetch as $keys=>$vals){

 if(!empty($eventnotifyacceptfetch[$keys])){	
 	$date_time = date('M d, Y h:i A', strtotime($vals->start_date.' '.$vals->start_time));
	 $hasarr          =explode("#",$vals->tag_name);
	  $strret_arr       =array_filter($hasarr);
	  $con ='';
	  		foreach($strret_arr as $haskeys=>$hasvals){
				$con .='<span><a href="'.$C->SITE_URL.'/search/tab:tags/s:'.$hasvals.'"><strong>#'.$hasvals.'</strong></a>';

			}
           if($vals->status ==1){
						$st  ="Active";
					}
					if($vals->status ==2){
						$st  ="Cancelled";
					}
	      



 
$myevent            =$db2->query('SELECT u.id,u.username,u.fullname,u.avatar,ep.post_id,p.date as postdate  FROM event_posts as ep
      inner join posts as p ON ep.post_id = p.id
	   inner join users as u ON p.user_id = u.id
    where ep.event_id = "'.$vals->eventid.'" ' );
 $myeventres                  =$db2->fetch_object($myevent);
 if($myeventres->avatar !=''){
	 $src=getAvatarUrl($myeventres->avatar, 'thumbs1');
	 
 }else{
	 $src = $C->SITE_URL.'storage/avatars/thumbs1/_noavatar_user.gif';
 }
 								if( ($vals->event_status !=2 ||  $vals->edit_status!=4) &&  ($vals->event_status !=2 &&   $vals->edit_status!=4) ){

					
					if($vals->event_status == 1){
					 $userresponse = '<div "id="accept-'.$myeventres->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					}
					if($vals->event_status == 3){
						$userresponse ='<div id="reject-'.$myeventres->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
					}
					 //$userresponse ='<div style="display:'.$displayreject.';"id="reject-'.$myeventres->post_id.'"><strong>User Response:</strong>Event Rejected</div>';

                  
                    }else{
						if((($vals->event_status!=4) &&   ($vals->edit_status==4))){
							
				if($vals->event_status == 1){
					 $userresponse = '<div "id="accept-'.$myeventres->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					}
					if($vals->event_status == 3){
						$userresponse ='<div id="reject-'.$myeventres->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
					}
                  
							
						}else{
						$userresponse = '<div>This event was no longer available.</div>';
						}
					}
					if($vals->event_status == 5){
						 $userresponse =  '<div>This event was modified.</div>';
						
					}
					$url = 'https://www.google.com/maps/search/'.urlencode(htmlentities($vals->address, ENT_COMPAT, 'UTF-8'));
				$window_pop = "MyWindow=window.open('$url','Map','width=600,height=500'); return false;";
 
		                
		$acceptedhtml .='
		 <div class="activity no-comments">
<a href="'.userlink($myeventres->username).'" class="avatar bizcard" data-userid="'.$myeventres->id.'"><img src="'.$src.'"  /></a>	
	<div class="activity-container">
		<div class="activity-header">
			<a href="'.userlink($myeventres->username).'" class="author bizcard" data-userid="'.$myeventres->id.'">'. ($myeventres->username) .'</a>
			<div class="meta-info">
				
				
			</div>
			<div class="activity-options"><a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" class="delete">delete</a>	
			<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="bookmark">bookmark</a>
			</div>
		</div>
		<div class="activity-content"></div>
		<div><div class="attachments lightbox-enabled">
	
	<div class="links">
		<div class="container">
	
	

	<div class="content text-info">
		<a target="_blank" href="'.$C->SITE_URL.''.$myeventres->username.'/tab:events/action:view/id:'.$vals->eventid.'" class="link-title"></a><div class="title"><a target="_blank" href="'.$C->SITE_URL.''.$myeventres->username.'/tab:events/action:view/id:'.$vals->eventid.'" class="link-title"><img class="icon-calander" src="'.$C->SITE_URL.'/apps/events/static/images/event.png"> </a><a href="'.$C->SITE_URL.''.$myeventres->username.'/tab:events/action:view/id:'.$vals->eventid.'/postid:'.$myeventres->post_id.'" "><strong>Event Name:</strong> '.$vals->event_name.'</a></div>
		<span>
			<p class="address_view">
										<span><strong>Location:</strong> '.$vals->address.' <a style="color:darkblue; font-weight:bold;" onclick="'.$window_pop.'" href="#">view map</a>				
</span>
									</p>
									<p class="address_view">
										<span><strong>URL:</strong> <a href="http://www.'.$vals->url.'" target="_blank">'.$vals->url.'</a></span>
									</p>								
									<span class="time"><strong>Date and Time:</strong>'.$date_time.'</span>
									<span><stong>Hash Tags:</stong>'.$con.'</span>
									'.$userresponse.'
									<div><strong>Status:'.$st.'</strong></div>
	</span></div>
	<div class="clear"></div>
</div>			
	</div>
	
</div></div>
		<div class="activity-poll"></div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	
	<div>
	<div class="activity-footer meta-info">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$myeventres->post_id.'" class="permlink">'.post::parse_date($myeventres->postdate).'</a>
				<a style="cursor:pointer" onclick="activityAddCommentchield('.$myeventres->post_id.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>
				<a style="cursor:pointer"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png" title="Like"></a>
				<a style="cursor:pointer"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"></a>
                 <div class="dropdown">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >Linkedin</a></li>
								  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'  target="_blank" >StumbleUpon</a></li>
								  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'  target="_blank" >MySpace</a></li>
								  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'  target="_blank" >FriendFeed</a></li>
								  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>						</div>
				<div class="comment-chield" style="display:none" id="chield'.$myeventres->post_id.'">
				<div class="comments-editor data-content-placeholder" data-token="069fc5555">
				<div>
				
				<div class="activity-header commentpost'.$myeventres->post_id.'">
				<a href="'.$C->SITE_URL.''.$myeventres->username.'" class="author bizcard" data-userid="'.$myeventres->id.'">'.$myeventres->username.'</a>
				
				</div>
				<form method="post"action="'.$C->SITE_URL.'plugin/poll/admin?action=comment&amp;posts_id='.$myeventres->post_id.'">
				<div class="comments-editor-content">
					<div class="user-status-field htmlarea">
					<div class="commentpoll commentpoll'.$myeventres->post_id.'">
					</div>
						<div class="textarea-wrap comment">
							<textarea name="message">@'.$myeventres->username.' </textarea>
							<div class="textarea-highlighter"><span></span></div>
						</div>
					</div>			
							<div class="buttons">
								<button type="button" id="'.$myeventres->post_id.'" class="comment-post pollcreate left comment-post'.$myeventres->post_id.' post-btn btn blue"><span>POLL</span></button>
								
									<input type="submit" class="comment-post comment-post'.$myeventres->post_id.' post-btn btn  blue center" value="Buzz">
								
							</div>
						</div></form>
					</div>
				</div></div><br><br>
	</div>
	<div>
	
	</div>
</div>
		';
		
	}
	}
	echo $acceptedhtml;
		}
	
	
		
	
?>