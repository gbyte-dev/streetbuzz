<?php
	error_reporting(0);
	if( !$this->user->is_logged ) {
		$this->redirect('home');
	}
	
	$this->load_langfile('inside/global.php');
	$data =array();
		$obj=$data[0];
	$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);
	if($_POST['type'] =="list_myeven"){
		$from    = $_POST['myshowcount'];
		$upto = 10;
		

		$myevent            =$db2->query('SELECT id as eventid,event_name,address,location,event_description,start_date,start_time,end_date,end_time,url,status,tag_name
		FROM   events  where admin_id="'.$this->user->id.'"  order by id desc limit '.$from.' ,'.$upto.' ' );;
	while($myeventfetch[] = $db2->fetch_object($myevent)){
	}
    $D->myeventfetch = ($myeventfetch);
	}
	if($_POST['type'] =="list_accept"){
		//$from    = $_POST['acceptshowcount'];
		$from =1;
		$to=10;
		$eventnotifyaccept = $db2->query('SELECT e.id as eventid,e.event_name,e.address,e.location,e.event_description,e.start_date,e.start_time,e.end_date,e.end_time,e.url,e.status,e.event_type,e.tag_name,pu.event_status,ep.edit_status
   	FROM `post_userbox` as pu 
                   inner join event_posts as ep ON ep.post_id = pu.post_id 
				   inner join  posts as p ON ep.post_id = p.id		 

   				inner join events as e on ep.event_id = e.id 
   				WHERE pu.user_id = "'.$this->user->id.'" and (pu.event_status = 1 and pu.status is null)   and p.post_level is null  order by p.date_lastcomment desc,pu.id desc,ep.created desc LIMIT '.$from.' ,'.$to.' ' );
	
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
					if($vals->status ==0){
						$st  ="Expired";
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


						 $download = '<div class="btn-download-padding"><a href="'.$C->SITE_URL.'dashboard?pid='.$myeventres->post_id.'"><input class="button-submit-results" type="button" name="download" value="Download Results"></a></div>';

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
										$eventtypeqweqw =1;	
          $replies                =$buff->checkreplies($myeventres->post_id);
		 $groups                =$buff->getgroupname($myeventres->group_id);
		if(!empty($replies)){
			$individual = '<a style="cursor:pointer;" class="author bizcard" onclick="replaycontent('.$replies->alternate_parent_id.','.$myeventres->post_id.','.$eventtypeqweqw.')" data-userid="'.$replies->userid.'">Replies to @'. $replies->handler .'</a>' ;
		   $replayhtml ='<a style="cursor:pointer" onclick="childpopuptimeline('.$replies->parent_id.','.$replies->replay_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}else{
			$individual ='';
			$replayhtml ='<a style="cursor:pointer" onclick="parentreplay('.$myeventres->post_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}
		if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
		/*reshare and like count logic apply here*/
                $res	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$myeventres->post_id.'" LIMIT 1');
		        $is_reshared       =$db2->fetch_object($res);
				$resharesres       =$db2->query('select count(id) as resharecnt  FROM post_reshares WHERE  post_id="'.$myeventres->post_id.'" ');
				$resultres=$this->db2->fetch_object($resharesres);
				
				$resharecnt     = $resultres->resharecnt;
				$is_likedres  = $db2->query('select *  FROM  post_likes WHERE user_id="'.$this->user->id.'" AND post_id="'.$myeventres->post_id.'" LIMIT 1');;
			    $is_liked       =$db2->fetch_object($is_likedres);
				$likesnumberres		 = $db2->query('select count(id) as likecnt FROM  post_likes WHERE  post_id="'.$myeventres->post_id.'" ');;
				$likesnumberres=$this->db2->fetch_object($likesnumberres);
				
				$likes_number     = $likesnumberres->likecnt;
				$is_agree = $buff->is_post_agree($this->user->id,$myeventres->post_id);
   				$is_agree_cnt = $buff->is_post_agree_cnt($myeventres->post_id);
   				if($is_agree_cnt->cnt > 0){					
   				$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
   			   }else{
   				 $showagreebtn_btn ='';  
   			   }
				



				$buff->post_type='public';
				    if($likes_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$likes_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                    if($resharecnt > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$resharecnt.'</a>';
                    }else{
						$resharecnt ='';
						
					}
					$is_spam  = $buff->is_spam($myeventres->post_id,"public");
   			if($is_spam =="1"){
   					$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$is_spam->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                  }else{
   		          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$is_spam->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                  }
           				/* end reshare and like count logic apply here*/
 


/****************************** Start : All Event Showmore **************************/ 
		                
		$alleventhtml .='
		 <div class="activity no-comments" id="alleventshow-'.$myeventres->post_id.'">

<!-- start Parent -->
<div class="row start'.$myeventres->post_id.'" style="border:0px solid red; margin:0px; padding:0px;">

<div id="alleventjaneeshshow'.$myeventres->post_id.'"></div> 

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box " style="border:0px solid green" >


<div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden; padding:0">


<a href="'.userlink($myeventres->username).'" class="avatar bizcard" data-userid="'.$myeventres->id.'"><img src="'.$src.'"  /></a>	
</div><!--/ end : col-md-1 -->


<div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">

<!--/ start : activity container -->
<div class="activity-container">
	<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<a href="'.userlink($myeventres->username).'" class="author bizcard" data-userid="'.$myeventres->id.'">'. ($myeventres->username) .'</a>
			<div class="meta-info">'.$individual.''.$grp.'</div>


			<div class="activity-options"><a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>	
			<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>
			</div>
		</div>




<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'view/post:'.$myeventres->post_id.'" class="permlink">'.post::parse_date($myeventres->postdate).' <span class="glyphicon glyphicon-link"></span></a>
</div>





<div class="activity-content">      

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" 
style="padding:10px 10px 0px 10px;">
    <!-- start : event title -->
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz- zeropadding">
    <ul class="list-inline single-line">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-calendar-event.png" class="img-responsive">
    </li>
    <li>
    <a   href="'.$C->SITE_URL.'/plugin/events/view/id:'.$vals->eventid.'/postid:'.$myeventres->post_id.'"  class="buzz-title">
    '.$vals->event_name.'</a>
    </li>
    </ul>  
    </div>
    <!-- end : event title -->

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
    <!-- start : event location -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-location-event.png" class="img-responsive"></li>
    <li>'.$vals->address.'<a style="color:darkblue; font-weight:bold;" onclick="'.$window_pop.'" href="#">view map</a></li>
    </ul>  
    </div>
    <!-- end : event location -->
    <!-- start : event date & time -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-calendar-event.png" class="img-responsive"></li>
    <li>'.$date_time.'</li>
    </ul>  
    </div>
    <!-- end : event date & time -->
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">';
	if($vals->url !=''){
    $alleventhtml .='<!-- start : event url -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-url-event.png" class="img-responsive"></li>
    <li>'.$vals->url.'</li>
    </ul>  
    </div>
    <!-- end : event url -->';
	}
	if($con !=''){
     $alleventhtml  .='<!-- start : event hashtag -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-hashtag-event.png" class="img-responsive"></li>
    <li>'.$con.' </li>
    </ul>  
    </div>
    <!-- end : event hashtag -->';
	}
    $alleventhtml .='</div>
	'.$userresponse.'
	 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<ul class="list-inline">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-status-event.png" class="img-responsive"></li>
    <li>Status - <span class="txt-accepted">'.$st.'</span></li>
    </ul></div>
   	'.$download.'

  
</div> <!--/ event-list-blue-bg -->
</div> <!--/ activity-content -->


   	<div id="replaypopup1-'.$myeventres->post_id.'" class="modal fade" ></div>

    <div class="activity-poll"></div>
    <div class="footer1 activity-footer meta-info">  

	<div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12">
			
				 <span class="reply icon-ftr icon-ftr-reply">
       	'.$replayhtml.'
        </span>
				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_agree? '<img width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img  width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>

				<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>
				<div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							    <ul class="menu-options">
                  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >Linkedin</a></li>
                  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >Google Plus</a></li>
                   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
                  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
                 </ul>
							</div>		

						     

           </div> <!--/ end :  activity-footer meta-info -->

</div>
<!--/ end : activity container -->


  
</div><!--/ end : col-md-11 -->


</div><!--/ end : col-md-12 -->


</div><!-- end : activity-->';
	 //this for subreplay checking 
     $replies                =$buff->checkreplies($myeventres->post_id);
    if(empty($replies)){
		   $event ='event';

		$chield  = $buff->is_notificationchield_replay($myeventres->post_id,$event);
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);

	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 1;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" 
       style="border:0px solid grey;"><a style="cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$myeventres->post_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
			}else{
				$replycont ='';
				
			}
			$groups = $buff->getgroupname($chield[$m]->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
		  if(($this->user->id == $chield[$m]->userid )){
   
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$chield[$m]->id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" > <img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				}
   		   $is_favres       =$buff->isfav($this->user->id,$chield[$m]->id);
 		if(!empty($is_favres)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$chield[$m]->id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   									$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$chield[$m]->id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
				//Like content
				$is_likedreplay     = $buff->new_liked($chield[$m]->id);
			    $likes_numbers       =$buff->new_liked_count($chield[$m]->id);
				$likes_number        =$likes_numbers->likecount;
				
               	$post_type='public';
                if($likes_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.$likes_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				//Agree content
				$is_replayreshares     = $buff->new_reshared($chield[$m]->id);
			    $likes_reshares       =$buff->new_reshare_count($chield[$m]->id);
				$sharecount        =$likes_reshares->sharecount;
				$post_type ="public";
				$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_replayreshares? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_replayreshares? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>').'</a>';
                    if($sharecount > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.$sharecount.'</a>';
                    }else{
						$resharecnt ='';
						
					}
				//agree content
				$is_agree = $buff->is_post_agree($user->id,$chield[$m]->id);
				$is_agree_cnt = $buff->is_post_agree_cnt($chield[$m]->id);
					if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   //markcontent
				    $is_spam  = $buff->is_spam($chield[$m]->id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				if(($j-1)==$m){
					$css="tree1";
					$childclass="aleventchild".$myeventres->post_id;
					
					
				}else{
					
					
					$css="tree";
					$childclass ="";
					
				}
				$buzztype = $buff->getbuzztype($chield[$m]->id);
								if($buzztype =="buzz" || $buzztype =="" ){
					$mes = $buff->parsetext($chield[$m]->message);	
                }elseif($buzztype =="event"){
					$eventdetails	    =$buff->geteventdetails($chield[$m]->id);
						if($eventdetails->group_id !=''){
						$groupname               =$buff->getgroupname($eventdetails->group_id);
						}
			
                   $url =$C->SITE_URL;
					$mes = $buff->notificationeventhtml($chield[$m]->id,$url);
						
					
				}elseif($buzztype =="poll"){
					$poll  = $buff->replay_is_poll($chield[$m]->id);
					$pollanswer=$buff->is_pollanswer($user->id,$poll[0]->poll_id);
					$message ='';


					
					$message .='<div class="activity-poll"><div class="attachments lightbox-enabled">
	<div class="images">
		<div class="list-link-container" style="margin-bottom:30px">
			<b><label style="color:#2665ca;"> Poll: '.$poll[0]->poll_question.'</label></b>
			
		</div>
	
	</div>
	<div class="links">';
	foreach($poll as $keys=>$vals){
		if($vals->answer!="" && count($pollanswer)<=0)
				{
	
		$message .='<div><input onclick="changeurl(this.value,this.id)" id="'.$vals->poll_id.'" class="option'.$vals->poll_answer_id.' radio'.$vals->poll_id.'" name="option" type="radio" value="'.$vals->poll_answer_id.'"/>'.$vals->answer.'</div><br>';
	}else if($vals->answer!="")
	{
	$countpollanswer=$buff->is_countpollanswer($vals->poll_id,$vals->poll_answer_id);
	$message .='<div ><table><tr><td style="margin: 0px;width:200px">'.$vals->answer.'</td><td style="margin: -15px 0px 0px 60px;">'.count($countpollanswer).'</td>
<td style="width: 500px;padding-top: 18px;"><div style="background-color: green!important;width:'.count($countpollanswer).'%;height: 15px;margin: -15px 10px 25px 77px;"></div></td></tr></table></div>';

	}
	}
	$message .='</div>
	<div class="activity-poll-option"><span id="optionerror'.$poll[0]->poll_id.'"></span><br><span><a onclick="checkoption('.$poll[0]->poll_id.')" id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=answer&poll_id='.$poll[0]->poll_id.'&from='.$user->id.'"><button style="color:white;background-color:orange; padding:6px 8px; border:1px solid orange;">Submit</button></a>&nbsp;&nbsp;&nbsp;';
	if($user->id==$vals->user_id)
			{
	$message .='<a id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'"><input class="button-submit-results" type="button" name="download" value="Download Results"></a>';
			}
	$message .='</span></div>
</div></div>';
$mes = $message;
					
				}elseif($buzztype =="intraday"){
              			$assetdata   =$buff->assetdata($chield[$m]->id);
			if($assetdata[0]->ticker !=''){
			$str =  $buff->parsetext($chield[$m]->message);
			

			
			$assetdatahtml ='<div>'.$str.'</div>
			<table class="table table-bordered" width="100%">
    <thead>
      <tr class="box-sub-title" style="color:white;background-color:#69c6e2">
        <th>Asset</th>
        <th>Price @ Buzzing</th>
        <th>Stop Loss</th>
		<th>Target Price</th>
		<th>Current Price</th>
		<th>Result</th>
      </tr>
    </thead>
    <tbody>
			
			';
			foreach($assetdata as $assetkeys=>$assetvals){
				if($assetkeys/2 ==0){
					$css11 ="#f6fbfc";
					
				}else{
					$css11 ="#e3f8fe";
				}
				if($assetvals->result == "1"){
					$img ='<img  src="'.$C->SITE_URL.'static/images/tick.png" width="16"/>';
					
				}elseif($assetvals->result =="0"){
					$img ='<img  src="'.$C->SITE_URL.'static/images/wrong.png"/>';
					
				}else{
					$img ='Open';
					
				}
				$assetdatahtml .='
				
  
      <tr style="background-color:'.$css11.'; color: #66757F; font-size:12px;font-weight:normal;">
        <td>$'.$assetvals->ticker.'</td>
        <td>2130</td>
        <td>'.$assetvals->stoploss_price.'</td>
		<td>'.$assetvals->predicted_price.'</td>
		<td>'.$assetvals->current_price.'</td>

		<td>'.$img.'</td>
      </tr>
      
    
	
				
				';
				
			}
			$assetdatahtml .='</tbody>
  </table>';

   $mes = $assetdatahtml;
			
			
		}
				}


		 
	 

   
   
   	       $alleventhtml .='<div>
            <div class="activity no-comments commentcontainer '.$childclass.'" style="border: 0px solid #E1E8ED;">
               <!-- start Parent -->
			   <ul class="'.$css.'">
			<li>
               <div class="row" style="padding:0px 4px;">
                  <div class="col-md-12 col-xs-12 buzz-parent-box">
                     <div class="col-md-1" style="padding:0px; overflow:hidden"><a  href="'.userlink($chield[$m]->username).'"  class="avatar bizcard" data-userid="'.$chield[$m]->userid.'"><img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" /></a>
                     </div>
                     <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11" style="padding: 0px 0px 0px 8px;">


                        <div class="activity-container">
                           <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
                           <a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>
                         
                             <div class="activity-options">'.$delete.''.$fav.'</div>

                              </div>	

						  <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">

						   <div class="meta-info"> '.$grp.'</div>
                           </div>


                           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'view/post:'.$chield[$m]->id.'" class="permlink">'.post::parse_date($chield[$m]->date).' <span class="glyphicon glyphicon-link"></span></a>
       </div>



                        <div class="activity-content">'.$mes.'</div>
                        </div>
                     

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12">

   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($myeventres->postdate).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$myeventres->post_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
   			<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_likedreplay? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_likedreplay? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
   		<div class="dropdown">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						    <ul class="menu-options">
                  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'&source='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Linkedin</a></li>
                  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Google Plus</a></li>
                   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Twitter</a></li>
                  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Facebook</a></li>
                 </ul>
   						</div>
                        <div class="like-list icon-ftr">'.$mark_content.'</div>
                        <div id="replydis-'.$chield[$m]->id.'">'.$replycont.'</div>
						
   			</div>
                        </div>
                        <div></div>
                     </div>
                  </div>
               </div>
			   </li>
			   </ul>
               <!-- end Parent -->
            </div></div>';
	 }
	 }
  }
	 
$alleventhtml .='</div></div>
<script>
var main = $("#alleventshow-'.$myeventres->post_id.'").height();
var child = $(".aleventchild'.$myeventres->post_id.'").height();
var final = Number(main)-Number(child);
$("#alleventjaneeshshow'.$myeventres->post_id.'").css("height",final);
</script>
<style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#alleventjaneeshshow'.$myeventres->post_id.'{
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#alleventjaneeshshow'.$myeventres->post_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#alleventjaneeshshow'.$myeventres->post_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#alleventjaneeshshow'.$myeventres->post_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#alleventjaneeshshow'.$myeventres->post_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#alleventjaneeshshow'.$myeventres->post_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>
		';
		
	}
	}
		echo $alleventhtml;

}


/****************************** End : All Event Showmore **************************/ 




/****************************** Start : My Event Showmore **************************/ 

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
					if($vals->status ==0){
						$st  ="Expired";
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
$download = '<div class="btn-download-padding"><a href="'.$C->SITE_URL.'dashboard?pid='.$myeventres->post_id.'"><input class="button-submit-results" type="button" name="download" value="Download Results"></a></div>';
  $eventtypeqweqw =2;	
          $replies                =$buff->checkreplies($myeventres->post_id);
		 $groups                =$buff->getgroupname($myeventres->group_id);
		if(!empty($replies)){
			$individual = '<a style="cursor:pointer;" class="author bizcard" onclick="replaycontent('.$replies->alternate_parent_id.','.$myeventres->post_id.','.$eventtypeqweqw.')" data-userid="'.$replies->userid.'">Replies to @'. $replies->handler .'</a>' ;
		   $replayhtml ='<a style="cursor:pointer" onclick="childpopuptimeline('.$replies->parent_id.','.$replies->replay_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}else{
			$individual ='';
			$replayhtml ='<a style="cursor:pointer" onclick="parentreplay('.$myeventres->post_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}
		if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
		   $is_agree = $buff->is_post_agree($this->user->id,$myeventres->post_id);
   				$is_agree_cnt = $buff->is_post_agree_cnt($myeventres->post_id);
   				if($is_agree_cnt->cnt > 0){					
   				$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
   			   }else{
   				 $showagreebtn_btn ='';  
   			   }
			   /*reshare and like count logic apply here*/
                $res	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$myeventres->post_id.'" LIMIT 1');
		        $is_reshared       =$db2->fetch_object($res);
				$resharesres       =$db2->query('select count(id) as resharecnt  FROM post_reshares WHERE  post_id="'.$myeventres->post_id.'" ');
				 $resultres=$this->db2->fetch_object($resharesres);
				
				$resharecnt     =$resultres->resharecnt;
				$is_likedres  = $db2->query('select *  FROM  post_likes WHERE user_id="'.$this->user->id.'" AND post_id="'.$myeventres->post_id.'" LIMIT 1');;
			    $is_liked       =$db2->fetch_object($is_likedres);
				$likesnumberres		 = $db2->query('select count(id) as likecnt FROM  post_likes WHERE  post_id="'.$myeventres->post_id.'" ');;
				$likesnumberres=$this->db2->fetch_object($likesnumberres);
				
				$likes_number     = $likesnumberres->likecnt;
				$is_agree = $buff->is_post_agree($this->user->id,$myeventres->post_id);
   				$is_agree_cnt = $buff->is_post_agree_cnt($myeventres->post_id);
   				if($is_agree_cnt->cnt > 0){					
   				$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
   			   }else{
   				 $showagreebtn_btn ='';  
   			   }
				



				$buff->post_type='public';
				    if($likes_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$likes_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                    if($resharecnt > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$resharecnt.'</a>';
                    }else{
						$resharecnt ='';
						
					}
					$is_spam  = $buff->is_spam($myeventres->post_id,"public");
   			if($is_spam =="1"){
   					$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$is_spam->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                  }else{
   		          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$is_spam->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                  }
           				/* end reshare and like count logic apply here*/

	
                
		$myeventhtml .='
		 <div class="activity no-comments" id="myeventshow'.$myeventres->post_id.'">

<!-- start Parent -->
<div class="row start'.$myeventres->post_id.'" style="border:0px solid red; margin:0px; padding:0px;">

<div id="myeventshowjaneesh'.$myeventres->post_id.'"></div> 

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box " style="border:0px solid green" >


<div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden; padding:0">

<a href="'.userlink($myeventres->username).'" class="avatar bizcard" data-userid="'.$myeventres->id.'"><img src="'.$src.'"  /></a>
</div><!--/ end : col-md-1 -->


<div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">

<!--/ start : activity container -->
<div class="activity-container">
		<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<a href="'.userlink($myeventres->username).'" class="author bizcard" data-userid="'.$myeventres->id.'">'. ($myeventres->username) .'</a>
			<div class="meta-info">'.$individual.''.$grp.'</div>






			<div class="activity-options"><a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>	
			<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>
			</div>
		</div>




		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'view/post:'.$myeventres->post_id.'" class="permlink">'.post::parse_date($myeventres->postdate).' <span class="glyphicon glyphicon-link"></span></a>
     </div>





<div class="activity-content">
      

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" 
style="padding:10px 10px 0px 10px;">
    <!-- start : event title -->
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz- zeropadding">
    <ul class="list-inline single-line">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-calendar-event.png" class="img-responsive">
    </li>
    <li>
    <a   href="'.$C->SITE_URL.'/plugin/events/view/id:'.$vals->eventid.'/postid:'.$myeventres->post_id.'"  class="buzz-title">
    '.$vals->event_name.'</a>
    </li>
    </ul>  
    </div>
    <!-- end : event title -->

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
    <!-- start : event location -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-location-event.png" class="img-responsive"></li>
    <li>'.$vals->address.'<a style="color:darkblue; font-weight:bold;" onclick="'.$window_pop.'" href="#">view map</a></li>
    </ul>  
    </div>
    <!-- end : event location -->
    <!-- start : event date & time -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-calendar-event.png" class="img-responsive"></li>
    <li>'.$date_time.'</li>
    </ul>  
    </div>
    <!-- end : event date & time -->
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">';
	if($vals->url !=''){
    $myeventhtml .='<!-- start : event url -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-url-event.png" class="img-responsive"></li>
    <li>'.$vals->url.'</li>
    </ul>  
    </div>
    <!-- end : event url -->';
	}
	if($con !=''){
     $myeventhtml  .='<!-- start : event hashtag -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-hashtag-event.png" class="img-responsive"></li>
    <li>'.$con.' </li>
    </ul>  
    </div>
    <!-- end : event hashtag -->';
	}
    $myeventhtml .='</div>
	'.$userresponse.'
	 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<ul class="list-inline">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-status-event.png" class="img-responsive"></li>
    <li>Status - <span class="txt-accepted">'.$st.'</span></li>
    </ul></div>
   '.$download.'

   </div> <!--/ event-list-blue-bg -->
</div> <!--/ activity-content -->
  
		<div id="replaypopup2-'.$myeventres->post_id.'" class="modal fade" ></div>

		 <div class="activity-poll"></div>
         <div class="footer1 activity-footer meta-info"> 


	<div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12">
			
			  <span class="reply icon-ftr icon-ftr-reply">
      '.$replayhtml.'
      </span>
			<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_agree? '<img width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img  width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>

				<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>
<div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
                  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >Linkedin</a></li>
                  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >Google Plus</a></li>
                   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
                  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
                 </ul>
							</div>			

							 <div class="like-list icon-ftr">'.$mark_content.'</div>      

           </div> <!--/ end :  activity-footer meta-info -->

</div>
<!--/ end : activity container -->


  
</div><!--/ end : col-md-11 -->


</div><!--/ end : col-md-12 -->


</div><!-- end : activity-->';
	$replies                =$buff->checkreplies($myeventres->post_id);
    if(empty($replies)){
	$event="event";
		$chield  = $buff->is_notificationchield_replay($myeventres->post_id,$event);
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);

	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 2;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" 
       style="border:0px solid grey;"><a style="cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$myeventres->post_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
			}else{
				$replycont ='';
				
			}
			$groups = $buff->getgroupname($chield[$m]->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
		if(($this->user->id == $chield[$m]->userid)){
   
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$chield[$m]->id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" > <img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				}
   		   $is_favres       =$buff->isfav($this->user->id,$chield[$m]->id);
 		if(!empty($is_favres)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$chield[$m]->id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$chield[$m]->id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}		
		
				//Like content
				$is_likedreplay     = $buff->new_liked($chield[$m]->id);
			    $likes_numbers       =$buff->new_liked_count($chield[$m]->id);
				$likes_number        =$likes_numbers->likecount;
				
               	$post_type='public';
                if($likes_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.$likes_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				//Agree content
				$is_replayreshares     = $buff->new_reshared($chield[$m]->id);
			    $likes_reshares       =$buff->new_reshare_count($chield[$m]->id);
				$sharecount        =$likes_reshares->sharecount;
				$post_type ="public";
				$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_replayreshares? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_replayreshares? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>').'</a>';
                    if($sharecount > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.$sharecount.'</a>';
                    }else{
						$resharecnt ='';
						
					}
				//agree content
				$is_agree = $buff->is_post_agree($user->id,$chield[$m]->id);
				$is_agree_cnt = $buff->is_post_agree_cnt($chield[$m]->id);
					if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   //markcontent
				    $is_spam  = $buff->is_spam($chield[$m]->id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				if(($j-1)==$m){
					$css="tree1";
					$childclass="myeventshow".$myeventres->post_id;
					
				}else{
					
					
					$css="tree";
					$childclass ="";
					
				}
				$buzztype = $buff->getbuzztype($chield[$m]->id);
								if($buzztype =="buzz" || $buzztype =="" ){
					$mes = $buff->parsetext($chield[$m]->message);	
                }elseif($buzztype =="event"){
					$eventdetails	    =$buff->geteventdetails($chield[$m]->id);
						if($eventdetails->group_id !=''){
						$groupname               =$buff->getgroupname($eventdetails->group_id);
						}
				
					
                     $url =$C->SITE_URL;
					$mes =$buff->notificationeventhtml($chield[$m]->id,$url);
						
					
				}elseif($buzztype =="poll"){
					$poll  = $buff->replay_is_poll($chield[$m]->id);
					$pollanswer=$buff->is_pollanswer($user->id,$poll[0]->poll_id);
					$message ='';


					
					$message .='<div class="activity-poll"><div class="attachments lightbox-enabled">
	<div class="images">
		<div class="list-link-container" style="margin-bottom:30px">
			<b><label style="color:#2665ca;"> Poll: '.$poll[0]->poll_question.'</label></b>
			
		</div>
	
	</div>
	<div class="links">';
	foreach($poll as $keys=>$vals){
		if($vals->answer!="" && count($pollanswer)<=0)
				{
	
		$message .='<div><input onclick="changeurl(this.value,this.id)" id="'.$vals->poll_id.'" class="option'.$vals->poll_answer_id.' radio'.$vals->poll_id.'" name="option" type="radio" value="'.$vals->poll_answer_id.'"/>'.$vals->answer.'</div><br>';
	}else if($vals->answer!="")
	{
	$countpollanswer=$buff->is_countpollanswer($vals->poll_id,$vals->poll_answer_id);
	$message .='<div ><table><tr><td style="margin: 0px;width:200px">'.$vals->answer.'</td><td style="margin: -15px 0px 0px 60px;">'.count($countpollanswer).'</td>
<td style="width: 500px;padding-top: 18px;"><div style="background-color: green!important;width:'.count($countpollanswer).'%;height: 15px;margin: -15px 10px 25px 77px;"></div></td></tr></table></div>';

	}
	}
	$message .='</div>
	<div class="activity-poll-option"><span id="optionerror'.$poll[0]->poll_id.'"></span><br><span><a onclick="checkoption('.$poll[0]->poll_id.')" id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=answer&poll_id='.$poll[0]->poll_id.'&from='.$user->id.'"><button style="color:white;background-color:orange; padding:6px 8px; border:1px solid orange;">Submit</button></a>&nbsp;&nbsp;&nbsp;';
	if($user->id==$vals->user_id)
			{
	$message .='<a id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'"><input class="button-submit-results" type="button" name="download" value="Download Results"></a>';
			}
	$message .='</span></div>
</div></div>';
$mes = $message;
					
				}elseif($buzztype =="intraday"){
              			$assetdata   =$buff->assetdata($chield[$m]->id);
			if($assetdata[0]->ticker !=''){
			$str =  $buff->parsetext($chield[$m]->message);
			

			
			$assetdatahtml ='<div>'.$str.'</div>
			<table class="table table-bordered" width="100%">
    <thead>
      <tr class="box-sub-title" style="color:white;background-color:#69c6e2">
        <th>Asset</th>
        <th>Price @ Buzzing</th>
        <th>Stop Loss</th>
		<th>Target Price</th>
		<th>Current Price</th>
		<th>Result</th>
      </tr>
    </thead>
    <tbody>
			
			';
			foreach($assetdata as $assetkeys=>$assetvals){
				if($assetkeys/2 ==0){
					$css11 ="#f6fbfc";
					
				}else{
					$css11 ="#e3f8fe";
				}
				if($assetvals->result == "1"){
					$img ='<img  src="'.$C->SITE_URL.'static/images/tick.png" width="16"/>';
					
				}elseif($assetvals->result =="0"){
					$img ='<img  src="'.$C->SITE_URL.'static/images/wrong.png"/>';
					
				}else{
					$img ='Open';
					
				}
				$assetdatahtml .='
				
  
      <tr style="background-color:'.$css11.'; color: #66757F; font-size:12px;font-weight:normal;">
        <td>$'.$assetvals->ticker.'</td>
        <td>2130</td>
        <td>'.$assetvals->stoploss_price.'</td>
		<td>'.$assetvals->predicted_price.'</td>
		<td>'.$assetvals->current_price.'</td>

		<td>'.$img.'</td>
      </tr>
      
    
	
				
				';
				
			}
			$assetdatahtml .='</tbody>
  </table>';

   $mes = $assetdatahtml;
			
			
		}
				}


		 
	 

   
   
   	       $myeventhtml .='<div>
            <div class="activity no-comments commentcontainer '.$childclass.'" style="border: 0px solid #E1E8ED; */">
               <!-- start Parent -->
			   <ul class="'.$css.'">
			<li>
               <div class="row" style="padding:0px 4px;">
                  <div class="col-md-12 col-xs-12 buzz-parent-box">
                     <div class="col-md-1" style="padding:0px; overflow:hidden"><a  href="'.userlink($chield[$m]->username).'"  class="avatar bizcard" data-userid="'.$chield[$m]->userid.'"><img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" /></a>
                     </div>
                     <div class="col-md-11 col-xs-11" style="padding:0px 3px 0px 8px;">
                        <div class="activity-container">
                           <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12"><a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>

                           <div class="activity-options">'.$delete.''.$fav.'</div>

                           </div>
						   <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">

						   <div class="meta-info"> '.$grp.'</div>
						   </div>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'view/post:'.$chield[$m]->id.'" class="permlink">'.post::parse_date($chield[$m]->date).' <span class="glyphicon glyphicon-link"></span></a>
</div>




                           <div class="activity-content">'.$mes.'</div>
                        </div>
                        <div></div>
                        <div class="col-md-12">
                           <div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12">
   		
   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($myeventres->postdate).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$myeventres->post_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
   			<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_likedreplay? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_likedreplay? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>
   		<div class="dropdown icon-ftr">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
                  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'&source='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Linkedin</a></li>
                  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Google Plus</a></li>
                   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Twitter</a></li>
                  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Facebook</a></li>
                 </ul>
   						</div>
                        <div class="like-list icon-ftr">'.$mark_content.'</div>
                        <div id="replydis-'.$chield[$m]->id.'">'.$replycont.'</div>
						
   			</div>
                        </div>
                        <div></div>
                     </div>
                  </div>
               </div>
			   </li>
			   </ul>
               <!-- end Parent -->
            </div>
         </div>';
	 }
	 }
  }
	$myeventhtml .='</div>
	</div>
	<script>
var main = $("#myeventshow'.$myeventres->post_id.'").height();
var child = $(".myeventshow'.$myeventres->post_id.'").height();
var final = Number(main)-Number(child);
$("#myeventshowjaneesh'.$myeventres->post_id.'").css("height",final);
</script>
<style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#myeventshowjaneesh'.$myeventres->post_id.'{
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#myeventshowjaneesh'.$myeventres->post_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#myeventshowjaneesh'.$myeventres->post_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#myeventshowjaneesh'.$myeventres->post_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#myeventshowjaneesh'.$myeventres->post_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#myeventshowjaneesh'.$myeventres->post_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>
		';
		
	}
	}
	echo $myeventhtml;
			}

/******************* Start : My Events Showmore **************************************/		



/******************* Start : Accepeted Events Showmore *******************************/	

		if($_POST['type'] =="list_accept"){

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
					if($vals->status ==0){
						$st  ="Expired";
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
				       $eventtypeqweqw =3;	
          $replies                =$buff->checkreplies($myeventres->post_id);
		 $groups                =$buff->getgroupname($myeventres->group_id);
		if(!empty($replies)){
			$individual = '<a style="cursor:pointer;" class="author bizcard" onclick="replaycontent('.$replies->alternate_parent_id.','.$myeventres->post_id.','.$eventtypeqweqw.')" data-userid="'.$replies->userid.'">Replies to @'. $replies->handler .'</a>' ;
		   $replayhtml ='<a style="cursor:pointer" onclick="childpopuptimeline('.$replies->parent_id.','.$replies->replay_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}else{
			$individual ='';
			$replayhtml ='<a style="cursor:pointer" onclick="parentreplay('.$myeventres->post_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}
		if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
		 $is_agree = $buff->is_post_agree($this->user->id,$myeventres->post_id);
   				$is_agree_cnt = $buff->is_post_agree_cnt($myeventres->post_id);
   				if($is_agree_cnt->cnt > 0){					
   				$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
   			   }else{
   				 $showagreebtn_btn ='';  
   			   }
			   /*reshare and like count logic apply here*/
                $res	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$myeventres->post_id.'" LIMIT 1');
		        $is_reshared       =$db2->fetch_object($res);
				$resharesres       =$db2->query('select count(id) as resharecnt  FROM post_reshares WHERE  post_id="'.$myeventres->post_id.'" ');
				 $resultres=$this->db2->fetch_object($resharesres);
				
				$resharecnt     = $resultres->resharecnt;
				$is_likedres  = $db2->query('select *  FROM  post_likes WHERE user_id="'.$this->user->id.'" AND post_id="'.$myeventres->post_id.'" LIMIT 1');;
			    $is_liked       =$db2->fetch_object($is_likedres);
				$likesnumberres		 = $db2->query('select count(id) as likecnt FROM  post_likes WHERE  post_id="'.$myeventres->post_id.'" ');;
				$likesnumberres=$this->db2->fetch_object($likesnumberres);
				
				$likes_number     = $likesnumberres->likecnt;
				$is_agree = $buff->is_post_agree($this->user->id,$myeventres->post_id);
   				$is_agree_cnt = $buff->is_post_agree_cnt($myeventres->post_id);
   				if($is_agree_cnt->cnt > 0){					
   				$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
   			   }else{
   				 $showagreebtn_btn ='';  
   			   }
				



				$buff->post_type='public';
				    if($likes_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$likes_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                    if($resharecnt > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$resharecnt.'</a>';
                    }else{
						$resharecnt ='';
						
					}
					$is_spam  = $buff->is_spam($myeventres->post_id,"public");
   			if($is_spam =="1"){
   					$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$is_spam->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                  }else{
   		          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$is_spam->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                  }
           				/* end reshare and like count logic apply here*/
		
 
		                
		$acceptedhtml .='
		 <div class="activity no-comments" id="acceptshow'.$myeventres->post_id.'">

<!-- start Parent -->
<div class="row start'.$myeventres->post_id.'" style="border:0px solid red; margin:0px; padding:0px;">
<div id="acceptjaneeshshowmore'.$myeventres->post_id.'"></div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box " style="border:0px solid green">

<div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden; padding:0">

<a href="'.userlink($myeventres->username).'" class="avatar bizcard" data-userid="'.$myeventres->id.'"><img src="'.$src.'"  /></a>	

</div><!--/ end : col-md-1 -->


<div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">

<!--/ start : activity container -->
<div class="activity-container">
		<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<a href="'.userlink($myeventres->username).'" class="author bizcard" data-userid="'.$myeventres->id.'">'. ($myeventres->username) .'</a>
			<div class="meta-info">'.$delete.''.$grp.'</div>


			<div class="activity-options"><a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>	
			<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>
			</div>
		</div>



		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'view/post:'.$myeventres->post_id.'" class="permlink">'.post::parse_date($myeventres->postdate).' <span class="glyphicon glyphicon-link"></span></a>
     </div>





<div class="activity-content">
      

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" 
style="padding:10px 10px 0px 10px;">
    <!-- start : event title -->
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz- zeropadding">
    <ul class="list-inline single-line">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-calendar-event.png" class="img-responsive">
    </li>
    <li>
    <a   href="'.$C->SITE_URL.'/plugin/events/view/id:'.$vals->eventid.'/postid:'.$myeventres->post_id.'"  class="buzz-title">
    '.$vals->event_name.'</a>
    </li>
    </ul>  
    </div>
    <!-- end : event title -->

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
    <!-- start : event location -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-location-event.png" class="img-responsive"></li>
    <li>'.$vals->address.'<a style="color:darkblue; font-weight:bold;" onclick="'.$window_pop.'" href="#">view map</a></li>
    </ul>  
    </div>
    <!-- end : event location -->
    <!-- start : event date & time -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-calendar-event.png" class="img-responsive"></li>
    <li>'.$date_time.'</li>
    </ul>  
    </div>
    <!-- end : event date & time -->
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">';
	if($vals->url !=''){
    $acceptedhtml .='<!-- start : event url -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-url-event.png" class="img-responsive"></li>
    <li>'.$vals->url.'</li>
    </ul>  
    </div>
    <!-- end : event url -->';
	}
	if($con !=''){
     $acceptedhtml  .='<!-- start : event hashtag -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-hashtag-event.png" class="img-responsive"></li>
    <li>'.$con.' </li>
    </ul>  
    </div>
    <!-- end : event hashtag -->';
	}
    $acceptedhtml .='</div>
	'.$userresponse.'
	 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<ul class="list-inline">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-status-event.png" class="img-responsive"></li>
    <li>Status - <span class="txt-accepted">'.$st.'</span></li>
    </ul></div>

  
      </div> <!--/ event-list-blue-bg -->
</div> <!--/ activity-content -->


		         			
		<div id="replaypopup3-'.$myeventres->post_id.'" class="modal fade" ></div>

 <div class="activity-poll"></div>
         <div class="footer1 activity-footer meta-info"> 


	<div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12">
				  <span class="reply icon-ftr icon-ftr-reply">
      '.$replayhtml.'
      </span>
				 <div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_agree? '<img width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img  width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>

				<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>
                 <div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							  <ul class="menu-options">
                  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >Linkedin</a></li>
                  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >Google Plus</a></li>
                   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
                  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
                 </ul>
							</div>

								 <div class="like-list icon-ftr">'.$mark_content.'</div>      

           </div> <!--/ end :  activity-footer meta-info -->

</div>
<!--/ end : activity container -->


  
</div><!--/ end : col-md-11 -->


</div><!--/ end : col-md-12 -->


</div><!-- end : activity-->
				';
				        $replies                =$buff->checkreplies($myeventres->post_id);
    if(empty($replies)){
		$event ="event";
		$chield  = $buff->is_notificationchield_replay($myeventres->post_id,$event);
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);

	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 3;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" 
       style="border:0px solid grey;"><a style="cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$myeventres->post_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
			}else{
				$replycont ='';
				
			}
			$groups = $buff->getgroupname($chield[$m]->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
		 if(($this->user->id == $chield[$m]->userid )){
   
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$chield[$m]->id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" > <img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				}
   		   $is_favres       =$buff->isfav($this->user->id,$chield[$m]->id);
 		if(!empty($is_favres)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$chield[$m]->id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   									$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$chield[$m]->id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
				//Like content
				$is_likedreplay     = $buff->new_liked($chield[$m]->id);
			    $likes_numbers       =$buff->new_liked_count($chield[$m]->id);
				$likes_number        =$likes_numbers->likecount;
				
               	$post_type='public';
                if($likes_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.$likes_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				//Agree content
				$is_replayreshares     = $buff->new_reshared($chield[$m]->id);
			    $likes_reshares       =$buff->new_reshare_count($chield[$m]->id);
				$sharecount        =$likes_reshares->sharecount;
				$post_type ="public";
				$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_replayreshares? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_replayreshares? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                    if($sharecount > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.$sharecount.'</a>';
                    }else{
						$resharecnt ='';
						
					}
				//agree content
				$is_agree = $buff->is_post_agree($user->id,$chield[$m]->id);
				$is_agree_cnt = $buff->is_post_agree_cnt($chield[$m]->id);
					if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   //markcontent
				    $is_spam  = $buff->is_spam($chield[$m]->id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				if(($j-1)==$m){
					$css="tree1";
					$childclass="acceptchildclass".$myeventres->post_id;
					
					
				}else{
					
					
					$css="tree";
					$childclass ="";
					
				}
				$buzztype = $buff->getbuzztype($chield[$m]->id);
								if($buzztype =="buzz" || $buzztype =="" ){
					$mes = $buff->parsetext($chield[$m]->message);	
                }elseif($buzztype =="event"){
					$eventdetails	    =$buff->geteventdetails($chield[$m]->id);
						if($eventdetails->group_id !=''){
						$groupname               =$buff->getgroupname($eventdetails->group_id);
						}
		
                       $url =$C->SITE_URL;

					$mes = $buff->notificationeventhtml($chield[$m]->id,$url);
						
					
				}elseif($buzztype =="poll"){
					$poll  = $buff->replay_is_poll($chield[$m]->id);
					$pollanswer=$buff->is_pollanswer($user->id,$poll[0]->poll_id);
					$message ='';


					
					$message .='<div class="activity-poll"><div class="attachments lightbox-enabled">
	<div class="images">
		<div class="list-link-container" style="margin-bottom:30px">
			<b><label style="color:#2665ca;"> Poll: '.$poll[0]->poll_question.'</label></b>
			
		</div>
	
	</div>
	<div class="links">';
	foreach($poll as $keys=>$vals){
		if($vals->answer!="" && count($pollanswer)<=0)
				{
	
		$message .='<div><input onclick="changeurl(this.value,this.id)" id="'.$vals->poll_id.'" class="option'.$vals->poll_answer_id.' radio'.$vals->poll_id.'" name="option" type="radio" value="'.$vals->poll_answer_id.'"/>'.$vals->answer.'</div><br>';
	}else if($vals->answer!="")
	{
	$countpollanswer=$buff->is_countpollanswer($vals->poll_id,$vals->poll_answer_id);
	$message .='<div ><table><tr><td style="margin: 0px;width:200px">'.$vals->answer.'</td><td style="margin: -15px 0px 0px 60px;">'.count($countpollanswer).'</td>
<td style="width: 500px;padding-top: 18px;"><div style="background-color: green!important;width:'.count($countpollanswer).'%;height: 15px;margin: -15px 10px 25px 77px;"></div></td></tr></table></div>';

	}
	}
	$message .='</div>
	<div class="activity-poll-option"><span id="optionerror'.$poll[0]->poll_id.'"></span><br><span><a onclick="checkoption('.$poll[0]->poll_id.')" id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=answer&poll_id='.$poll[0]->poll_id.'&from='.$user->id.'"><button style="color:white;background-color:orange; padding:6px 8px; border:1px solid orange;">Submit</button></a>&nbsp;&nbsp;&nbsp;';
	if($user->id==$vals->user_id)
			{
	$message .='<div class="btn-download-padding"><a id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'"><input class="button-submit-results" type="button" name="download" value="Download Results"></a></div>';
			}
	$message .='</span></div>
</div></div>';
$mes = $message;
					
				}elseif($buzztype =="intraday"){
              			$assetdata   =$buff->assetdata($chield[$m]->id);
			if($assetdata[0]->ticker !=''){
			$str =  $buff->parsetext($chield[$m]->message);
			

			
			$assetdatahtml ='<div>'.$str.'</div>
			<table class="table table-bordered" width="100%">
    <thead>
      <tr class="box-sub-title" style="color:white;background-color:#69c6e2">
        <th>Asset</th>
        <th>Price @ Buzzing</th>
        <th>Stop Loss</th>
		<th>Target Price</th>
		<th>Current Price</th>
		<th>Result</th>
      </tr>
    </thead>
    <tbody>
			
			';
			foreach($assetdata as $assetkeys=>$assetvals){
				if($assetkeys/2 ==0){
					$css11 ="#f6fbfc";
					
				}else{
					$css11 ="#e3f8fe";
				}
				if($assetvals->result == "1"){
					$img ='<img  src="'.$C->SITE_URL.'static/images/tick.png" width="16"/>';
					
				}elseif($assetvals->result =="0"){
					$img ='<img  src="'.$C->SITE_URL.'static/images/wrong.png"/>';
					
				}else{
					$img ='Open';
					
				}
				$assetdatahtml .='
				
  
      <tr style="background-color:'.$css11.'; color: #66757F; font-size:12px;font-weight:normal;">
        <td>$'.$assetvals->ticker.'</td>
        <td>2130</td>
        <td>'.$assetvals->stoploss_price.'</td>
		<td>'.$assetvals->predicted_price.'</td>
		<td>'.$assetvals->current_price.'</td>

		<td>'.$img.'</td>
      </tr>
      
    
	
				
				';
				
			}
			$assetdatahtml .='</tbody>
  </table>';

   $mes = $assetdatahtml;
			
			
		}
				}


		 
	 

   
   
   	       $acceptedhtml .='<div>
            <div class="activity no-comments commentcontainer" style="border: 0px solid #E1E8ED; ">
               <!-- start Parent -->
			   <ul class="'.$css.'">
			<li>
               <div class="row" style="padding:0px 4px">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-parent-box '.$childclass.'">
                     <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden"><a  href="'.userlink($chield[$m]->username).'"  class="avatar bizcard" data-userid="'.$chield[$m]->userid.'"><img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" /></a>
                     </div>
                     <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">
                        <div class="activity-container">
                          <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12"><a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>

                           <div class="activity-options">'.$delete.''.$fav.'</div>

                           </div>
                           
						   <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">

						   <div class="meta-info"> '.$grp.'</div>
						   </div>



<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'view/post:'.$chield[$m]->id.'" class="permlink">'.post::parse_date($chield[$m]->date).' <span class="glyphicon glyphicon-link"></span></a>
       </div>





                           <div class="activity-content">'.$mes.'</div>
                        </div>
                        <div></div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                           <div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12">
   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($myeventres->postdate).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$myeventres->post_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
   			<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_likedreplay? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_likedreplay? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>
   		<div class="dropdown icon-ftr">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
                  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'&source='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Linkedin</a></li>
                  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Google Plus</a></li>
                   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Twitter</a></li>
                  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Facebook</a></li>
                 </ul>
   						</div>
                        <div class="like-list icon-ftr">'.$mark_content.'</div>
                        <div id="replydis-'.$chield[$m]->id.'">'.$replycont.'</div>
						
   			</div>
                        </div>
                        <div></div>
                     </div>
                  </div>
               </div>
			   </li>
			   </ul>
               <!-- end Parent -->
            </div>
         </div>';
	 }
	 }
  }
	$acceptedhtml .='</div>
	<div>
	
	</div>
</div>
<script>
var main = $("#acceptshow'.$myeventres->post_id.'").height();
var child = $(".acceptchildclass'.$myeventres->post_id.'").height();
var final = Number(main)-Number(child);
$("#acceptjaneeshshowmore'.$myeventres->post_id.'").css("height",final);
</script>
<style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#acceptjaneeshshowmore'.$myeventres->post_id.'{
z-index:100;
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#acceptjaneeshshowmore'.$myeventres->post_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#acceptjaneeshshowmore'.$myeventres->post_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#acceptjaneeshshowmore'.$myeventres->post_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#acceptjaneeshshowmore'.$myeventres->post_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#acceptjaneeshshowmore'.$myeventres->post_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>

		';
		
	}
	}
	echo $acceptedhtml;exit;
		}
	
	
/******************* End : Accepeted Events Showmore *******************************/		
	
?>