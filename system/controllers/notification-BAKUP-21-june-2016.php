<?php
   error_reporting(0);
   if( !$this->user->is_logged ) {
   	$this->redirect('home');
   }
   $post = new post();
   $if_can_delete= $post->if_can_delete_notification();
   
   $this->load_langfile('inside/global.php');
				   

   		$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post($obj->type, FALSE, $obj);
   
   
    $folow_res           = $db2->query('SELECT whom FROM  users_followed as uf where who="'.$this->user->id.'"' );
    	
   while($fetchres = $db2->fetch_object($folow_res)){
   	$res[] = $fetchres->whom;
   }
   if(!empty($res)){
   $fetchres   =implode(',',$res);
   }else{
   	$fetchres ="' '";
   	
   	
   }
   
   
   $fetch = $db2->query('SELECT u.id,u.username,u.fullname,u.avatar,u.about_me FROM  street_suggestion as st
                             INNER JOIN users AS u  ON st.user_id=u.id
                                where st.user_id NOT IN('.$fetchres.')							  
   						  group by u.id
                             order by rand() limit 3 ');
   while($fetchresasw[] = $db2->fetch_object($fetch)){
   }

      $D->follow = ($fetchresasw);
   $eventres           = $db2->query('SELECT id,event_name FROM   events  where admin_id="'.$this->user->id.'"  order by id desc limit 3' );
   while($eventfetchre[] = $db2->fetch_object($eventres)){
   }

   $D->eventfetchre  = $eventfetchre;
   $eventacceptres           = $db2->query('SELECT e.id,e.event_name,ep.post_id FROM `post_userbox` as pu 
                   inner join event_posts as ep ON ep.post_id = pu.post_id 
   				inner join events as e on ep.event_id = e.id 
   				WHERE pu.user_id = "'.$this->user->id.'" and (pu.event_status = 1 and pu.status is null) order by pu.post_id desc LIMIT 3' );
   while($eventacceptfetchre[] = $db2->fetch_object($eventacceptres)){
   }

   $D->eventacceptfetchre  = $eventacceptfetchre;
   	$myevent            =$db2->query('SELECT e.id as eventid,ep.post_id,e.event_name,e.address,e.location,e.event_description,e.start_date,e.start_time,e.end_date,e.end_time,e.url,e.status,e.tag_name
   	FROM   events as e 
         inner join  event_posts as ep ON ep.event_id = e.id
inner join  posts as p ON ep.post_id = p.id		 
   	where e.admin_id="'.$this->user->id.'" AND ep.edit_status IS NULL  and p.post_level is null   order by p.date_lastcomment desc,ep.id desc limit 10' );;
   while($myeventfetch[] = $db2->fetch_object($myevent)){
   }
      $D->myeventfetch = ($myeventfetch);
	  $D->myeventcount =count($myeventfetch);
   $eventnotifyaccept           = $db2->query('SELECT e.id as eventid,e.event_name,e.address,e.location,e.event_description,e.start_date,e.start_time,e.end_date,e.end_time,e.url,e.status,e.event_type,e.tag_name,pu.event_status,ep.edit_status
   	FROM `post_userbox` as pu 
                   inner join event_posts as ep ON ep.post_id = pu.post_id 
				   inner join  posts as p ON ep.post_id = p.id		 

   				inner join events as e on ep.event_id = e.id 
   				WHERE pu.user_id = "'.$this->user->id.'" and (pu.event_status = 1 and pu.status is null)   and p.post_level is null  order by p.date_lastcomment desc,pu.id desc,ep.created desc LIMIT 10' );
   while($eventnotifyacceptfetch[] = $db2->fetch_object($eventnotifyaccept)){
   }

   $D->eventnotifyacceptfetch  = $eventnotifyacceptfetch;
   $D->acceptcount =count($eventnotifyacceptfetch);
   
   //$this->block->setVar();
   
      $eventallres           = $db2->query('SELECT e.id as eventid,ep.post_id,e.event_name,e.address,e.location,e.event_description,e.start_date,e.start_time,e.end_date,e.end_time,e.url,e.status,e.event_type,e.tag_name,pu.event_status,ep.edit_status 
                  FROM `post_userbox` as pu 
                   inner join event_posts as ep ON ep.post_id = pu.post_id
				   inner join posts as p ON ep.post_id = p.id
				   
				   
   				inner join events as e on ep.event_id = e.id 
   				WHERE pu.user_id = "'.$this->user->id.'" and ((pu.status =1 and pu.event_status is null) or pu.event_status = 1 or (pu.status =1 and pu.event_status = 5) ) and p.post_level is null   order by p.date_lastcomment desc,pu.id desc,ep.created desc LIMIT 10' );
     while($eventallresfetchres[] = $db2->fetch_object($eventallres)){
   }
   	$D->eventallresfetchres  = $eventallresfetchres;
	$D->alleventcount = count($eventallresfetchres);
   
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
   
         
   
   
   
   
   $myevent            =$db2->query('SELECT u.id,u.username,u.fullname,u.avatar,ep.post_id,p.date as postdate,p.user_id,p.group_id  FROM event_posts as ep
        inner join posts as p ON ep.post_id = p.id
      inner join users as u ON p.user_id = u.id
      where ep.event_id = "'.$vals->eventid.'"  AND ep.post_id ="'.$vals->post_id.'"' );
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
   				 $userresponse = '<div id="accept-'.$myeventres->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
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
   
   
   					 $download = '
             <div class="btn-download-padding"><a href="'.$C->SITE_URL.'dashboard?pid='.$myeventres->post_id.'"><input class="button-submit-results" type="button" name="download" value="Download Results"></a></div>';
   
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
   			 				/*reshare and like count logic apply here*/
                  $res	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$myeventres->post_id.'" LIMIT 1');
   	        $is_reshared       =$db2->fetch_object($res);
   			$resharesres       =$db2->query('select id  FROM post_reshares WHERE  post_id="'.$myeventres->post_id.'" ');
   			 while($resultres=$this->db2->fetch_object($resharesres))
   			{
   				$wew[]=$resultres;
   			}
   			$resharecnt     =count($wew);
   			$is_likedres  = $db2->query('select *  FROM  post_likes WHERE user_id="'.$this->user->id.'" AND post_id="'.$myeventres->post_id.'" LIMIT 1');;
   		    $is_liked       =$db2->fetch_object($is_likedres);
   			$likesnumberres		 = $db2->query('select *  FROM  post_likes WHERE  post_id="'.$myeventres->post_id.'" ');;
   			while($likesnumberres=$this->db2->fetch_object($likesnumberres))
   			{
   				$likesnumberresdata[]=$likesnumberres;
   			}
   			$likes_number     = count($likesnumberresdata);
   			$is_fav  = $db2->query('select *  FROM  post_favs WHERE user_id="'.$this->user->id.'" AND post_id="'.$myeventres->post_id.'" LIMIT 1');;
   		    $is_favres       =$db2->fetch_object($is_fav);
   			if(!empty($is_favres)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   									$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
   
   
   
   			$buff->post_type='public';
   			    if($likes_number > 0){					
   				$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$likes_number.'</a>';
   			   }else{
   				 $showlikes_btn ='';  
   			   }
   			   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="UnRebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                      if($resharecnt > 0){					
   
   				$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$resharecnt.'</a>';
                      }else{
   					$resharecnt ='';
   					
   				}
   				$is_agree = $buff->is_post_agree($this->user->id,$myeventres->post_id);
   				$is_agree_cnt = $buff->is_post_agree_cnt($myeventres->post_id);
   				if($is_agree_cnt->cnt > 0){					
   				$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
   			   }else{
   				 $showagreebtn_btn ='';  
   			   }
             				/* end reshare and like count logic apply here*/
             				
             				if($if_can_delete =='1' ||($this->user->id ==$myeventres->id) ){
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				}
   $is_spam  = $post->is_spam($myeventres->post_id,"public");
   			if($is_spam =="1"){
   					$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Unmarkspam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                  }else{
   		          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Markspam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                  }
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
						
             						
   
   	                
   	$alleventhtml .='
	<div id="sri'.$myeventres->post_id.'">
<div class="activity no-comments replayhide-'.$myeventres->post_id.' allthread1-'.$myeventres->post_id.'" style="border:0px solid blue;">
		 

<!-- start Parent -->
<div class="row start'.$myeventres->post_id.'" style="border:0px solid red; margin:0px; padding:0px;">

<div id="alleventjaneesh1'.$myeventres->post_id.'"></div> 

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green" >


<div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div"" style="padding:0; overflow:hidden">
<a href="'.userlink($myeventres->username).'" class="avatar bizcard" data-userid="'.$myeventres->id.'">
   <img src="'.$src.'"  /></a>
</div><!--/ end : col-md-1 -->


<div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">


<!--/ start : activity container -->
<div class="activity-container">
<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
  <a href="'.userlink($myeventres->username).'" class="author bizcard" data-userid="'.$myeventres->id.'">'. ($myeventres->username) .'</a>

    <div class="meta-info">'.$individual.' '.$grp.' </div>
    <div class="activity-options">'.$delete .''.$fav.'  </div>
        </div>



<div class="activity-content">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" 
style="padding:10px 10px 0px 10px;">
    <!-- start : event title -->
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-calendar-event.png" class="img-responsive">
    </li>
    <li>
    <a href="'.$C->SITE_URL.'/plugin/events/view/id:'.$vals->eventid.'/postid:'.$myeventres->post_id.'"  class="buzz-title">
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
    <li>'.$vals->address.' <a class="btn-view-map" onclick="'.$window_pop.'" href="#">view map</a></li>
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

<div class="activity-footer meta-info">
        <i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$myeventres->post_id.'" class="permlink">'.post::parse_date($myeventres->postdate).'</a>
        <input type="hidden" id="time-'.$myeventres->post_id.'" value="'.post::parse_date($myeventres->postdate).'" />
      '.$replayhtml.'
   
        <div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
        <div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
        <span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
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
            
            <div class="like-list">'.$mark_content.'</div>  

        </div> <!--/ end :  activity-footer meta-info -->

</div>
<!--/ end : activity container -->


  
</div><!--/ end : col-md-11 -->


</div><!--/ end : col-md-12 -->


</div><!-- end : activity no-comments-->
    ';
   //this for subreplay checking 
   $event ='event';
        $replies                =$buff->checkreplies($myeventres->post_id,$event);
    if(empty($replies)){
		$chield  = $buff->is_notificationchield_replay($myeventres->post_id,$event);
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);

	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 1;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" style="border:0px solid grey;">
        <a style=" cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$myeventres->post_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
			}else{
				$replycont ='';
				
			}
			$groups = $buff->getgroupname($chield[$m]->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
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
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   //markcontent
				    $is_spam  = $buff->is_spam($chield[$m]->id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				if(($j-1)==$m){
					$css="tree1";
					$childclass="child1".$myeventres->post_id;
					
					
				}else{
					
					
					$css="tree";
					$childclass ="";
					
				}
				$replypopeight ="replaychild".$myeventres->post_id;
				$buzztype = $buff->getbuzztype($chield[$m]->id);
					if($buzztype =="buzz" || $buzztype =="" ){
					$mes = $buff->parsetext($chield[$m]->message);	
                }elseif($buzztype =="event"){
					$eventdetails	    =$buff->geteventdetails($chield[$m]->id);
						if($eventdetails->group_id !=''){
						$groupname               =$buff->getgroupname($eventdetails->group_id);
						}
			
                     $url=$C->SITE_URL;
					$mes = $buff->notificationeventhtml($chield[$m]->id,$url);
						
					
				}elseif($buzztype =="poll"){
					$mes  = $buff->pollhtml($chield[$m]->id);
		
					
				}elseif($buzztype =="intraday"){

   $mes = $buff->assethtml($chield[$m]->id);
			
			
		}
				


		 
	 

   
   
   	       $alleventhtml .='
           <div class="'.$childclass.' '.$replypopeight.'  jan'.$myeventres->post_id.'" style="border: 0px solid yellow; padding:0;">

         <!-- start Parent -->
         <ul class="'.$css.'">
         <li>
               <div class="row" style="border: 0px solid red; padding:0; margin:0px;">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="border:0px solid green; margin:0px; padding:0px;">
                     <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1  image-div" style="padding:0; overflow:hidden"><a  href="'.userlink($chield[$m]->username).'"  class="avatar bizcard" data-userid="'.$chield[$m]->userid.'"><img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" /></a>
                     </div>
                    
                     <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11" style="padding:0px 0px 0px 0px;">
                       
                       


<div class="activity-container">
<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">

<a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>

       <div class="meta-info"> '.$grp.' </div>

    </div><!-- end : activity header -->


<!-- Start : activity content -->
 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 event-content-child">
'.$mes.'
 </div>
<!-- End : activity content -->


<div class="activity-footer meta-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$chield[$m]->id.'" class="permlink">'.post::parse_date($chield[$m]->date).'</a>
        <input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($myeventres->postdate).'" />
   
      <a  style="cursor:pointer" onclick="childpopup('.$myeventres->post_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
        <div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
        <div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
        <span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
      <div class="dropdown">
                 <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
                 <ul class="menu-options">
                  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'&source='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Linkedin</a></li>
                  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >StumbleUpon</a></li>
                  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >MySpace</a></li>
                  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'  target="_blank" >FriendFeed</a></li>
                  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Google Plus</a></li>
                   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Twitter</a></li>
                  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Facebook</a></li>
                 </ul>
              </div>
                        <div class="like-list">'.$mark_content.'</div>
                        <div id="replydis-'.$chield[$m]->id.'">'.$replycont.'</div>

</div><!-- end : activity footer - metainfo -->


</div>  <!-- end : activity container -->
                         
                     </div><!-- end : col-md-11 -->
                  </div><!-- end : col-md-12 -->
               </div><!-- end row -->
         </li>
         </ul>
               <!-- end Parent -->
            </div>      
           ';
	 }
	 }
  }
	 
  
   $alleventhtml .='</div></div>
   <script type="text/javascript">



   
   	var mainheight = $(".allthread1-'.$myeventres->post_id.'").height();

    
		var child = $(".child1'.$myeventres->post_id.'").height();
		var final = mainheight-child;
		$("#alleventjaneesh1'.$myeventres->post_id.'").css("height",final);

   
</script>

<style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#alleventjaneesh1'.$myeventres->post_id.'{
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#alleventjaneesh1'.$myeventres->post_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#alleventjaneesh1'.$myeventres->post_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#alleventjaneesh1'.$myeventres->post_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#alleventjaneesh1'.$myeventres->post_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#alleventjaneesh1'.$myeventres->post_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>
   
   ';
	 
   	
   }
   }
   //myevents tab
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
      where ep.event_id = "'.$vals->eventid.'"  AND ep.post_id="'.$vals->post_id.'"' );
   $myeventres                  =$db2->fetch_object($myevent);
   if($myeventres->avatar !=''){
    $src=getAvatarUrl($myeventres->avatar, 'thumbs1');
    
   }else{
    $src = $C->SITE_URL.'storage/avatars/thumbs1/_noavatar_user.gif';
   }

   $url = 'https://www.google.com/maps/search/'.urlencode(htmlentities($vals->address, ENT_COMPAT, 'UTF-8'));
   			$window_pop = "MyWindow=window.open('$url','Map','width=600,height=500'); return false;";
   				/*reshare and like count logic apply here*/
   $myeventreshare = array();
   
   $res	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$myeventres->post_id.'" LIMIT 1');
   	        $is_reshared       =$db2->fetch_object($res);
   			$resharesres       =$db2->query('select id  FROM post_reshares WHERE  post_id="'.$myeventres->post_id.'" ');
   			 while($resultres=$this->db2->fetch_object($resharesres))
   			{
   				$myeventreshare[]=$resultres;
   			}
   			$resharecnt     =count($myeventreshare);
   			$myeventlikesdata = array();
   
   			$is_likedres  = $db2->query('select *  FROM  post_likes WHERE user_id="'.$this->user->id.'" AND post_id="'.$myeventres->post_id.'" LIMIT 1');;
   		    $is_liked       =$db2->fetch_object($is_likedres);
   			$likesnumberresae		 = $db2->query('select *  FROM  post_likes WHERE  post_id="'.$myeventres->post_id.'" ');;
   			while($likes=$this->db2->fetch_object($likesnumberresae))
   			{
   				$myeventlikesdata[]=$likes;
   			}
   			$likes_number     = count($myeventlikesdata);
                 				$buff->post_type='public';
    if($likes_number > 0){					
   				$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$likes_number.'</a>';
   			   }else{
   				 $showlikes_btn ='';  
   			   }
   			   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>').'</a>';
                      if($resharecnt > 0){					
   
   				$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$resharecnt.'</a>';
                      }else{
   					$resharecnt ='';
   					
   				}
   				$is_agree = $buff->is_post_agree($this->user->id,$myeventres->post_id);
   				$is_agree_cnt = $buff->is_post_agree_cnt($myeventres->post_id);
   				if($is_agree_cnt->cnt > 0){					
   				$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
   			   }else{
   				 $showagreebtn_btn ='';  
   			   }
             				/* end reshare and like count logic apply here*/
             				if($if_can_delete =='1' ||($this->user->id ==$myeventres->id)){
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				}
                 $is_fav  = $db2->query('select *  FROM  post_favs WHERE user_id="'.$this->user->id.'" AND post_id="'.$myeventres->post_id.'" LIMIT 1');;
   		    $is_favres       =$db2->fetch_object($is_fav);
   			if(!empty($is_favres)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   									$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
   
                 $is_spam  = $post->is_spam($myeventres->post_id,"public");
   			if($is_spam =="1"){
   					$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                  }else{
   		          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                  }	
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
										  
   
   	                
   	$myeventhtml .='
    <div class="activity replayhide-'.$myeventres->post_id.'" id="myevent2-'.$myeventres->post_id.'" style="border:0px solid blue;">

<!-- start Parent -->
<div class="row start'.$myeventres->post_id.'" style="border:0px solid red; margin:0px; padding:0px;">

<div id="alleventjaneesh2'.$myeventres->post_id.'"></div> 

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
      <div class="activity-options">'.$delete.''.$fav.'</div>
    </div>

    <div class="activity-content">
      

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" 
style="padding:10px 10px 0px 10px;">
    <!-- start : event title -->
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content zeropadding">
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
    <li>'.$vals->address.' <a class="btn-view-map" onclick="'.$window_pop.'" href="#">view map</a></li>
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
   
 
    <div class="activity-footer meta-info">
        <i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$myeventres->post_id.'" class="permlink">'.post::parse_date($myeventres->postdate).'
        </a>
              <input type="hidden" id="time-'.$myeventres->post_id.'" value="'.post::parse_date($myeventres->postdate).'" />

      '.$replayhtml.'
        <div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
        <div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_agree? '<img width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
        <span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
        <div class="dropdown">
                 <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
                 <ul class="menu-options">

                  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >Linkedin</a></li>

                  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >StumbleUpon</a></li>

                  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >MySpace</a></li>
                  
                  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'"  target="_blank" >FriendFeed</a></li>

                  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >Google Plus</a></li>
                   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>

                  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>

                 </ul>
              </div>
             
              <div class="like-list">'.$mark_content.'</div>      

           </div> <!--/ end :  activity-footer meta-info -->

</div>
<!--/ end : activity container -->


  
</div><!--/ end : col-md-11 -->


</div><!--/ end : col-md-12 -->


</div><!-- end : activity-->
	 ';
			 //this for subreplay checking 
        $replies                =$buff->checkreplies($myeventres->post_id);
		$event ='event';
    if(empty($replies)){
		$chield  = $buff->is_notificationchield_replay($myeventres->post_id,$event);
		
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);
				$myeventstr .=$myeventres->post_id.',';


	for($m=0;$m<count($chield);$m++){
		
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 2;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" 
       style="border:0px solid grey;"> 
        <a style=" cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$myeventres->post_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>
        ';
				
			}else{
				$replycont ='';
				
			}
			$groups = $buff->getgroupname($chield[$m]->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
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
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   //markcontent
				    $is_spam  = $buff->is_spam($chield[$m]->id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				if(($j-1)==$m){
					$css="tree1";
				$childclass="no-comments".$myeventres->post_id;

					
					
				}else{
					
					
					$css="tree";
					$childclass='';
					
				}
				$buzztype = $buff->getbuzztype($chield[$m]->id);
								if($buzztype =="buzz" || $buzztype =="" ){
					$mes = $buff->parsetext($chield[$m]->message);	
                }elseif($buzztype =="event"){
					$eventdetails	    =$buff->geteventdetails($chield[$m]->id);
						if($eventdetails->group_id !=''){
						$groupname               =$buff->getgroupname($eventdetails->group_id);
						}
				
					$url = $C->SITE_URL;

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
	$message .='<a id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'"><button  class="btn-download-results">Download Results</button></a>';
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


		 
	 

   
   
   	       $myeventhtml .='
            <div class="'.$childclass.' commentcontainer">
               <!-- start Parent -->
			   <ul class="'.$css.'">
			<li>
               <div class="row" style="padding:0px 10px; border:0px solid blue">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box " style="border:0px solid green" >
                     <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden"><a  href="'.userlink($chield[$m]->username).'"  class="avatar bizcard" data-userid="'.$chield[$m]->userid.'"><img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" /></a>
                     </div>
                     <div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">
                        <div class="activity-container">
                             <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12"><a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>
                           </div>
						   	<div class="activity-header">

						   <div class="meta-info"> '.$grp.'
						   </div>
   			
   			                   </div>
                           <div class="activity-content">
                           <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 
                           event-content-child">
                           '.$mes.'
                           </div>
                           </div>
                        </div>
                        <div></div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                           <div class="activity-footer meta-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
   			<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$chield[$m]->id.'" class="permlink">'.post::parse_date($chield[$m]->date).'</a>
   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($myeventres->postdate).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$myeventres->post_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
   			<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
   		<div class="dropdown">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
   							  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'&source='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Linkedin</a></li>
   							  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >StumbleUpon</a></li>
   							  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >MySpace</a></li>
   							  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'  target="_blank" >FriendFeed</a></li>
   							  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Google Plus</a></li>
   							   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Twitter</a></li>
   							  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Facebook</a></li>
   						   </ul>
   						</div>
                        <div class="like-list">'.$mark_content.'</div>
                        <div id="replydis-'.$chield[$m]->id.'">'.$replycont.'</div>
						
   			</div>
                        </div>
                     </div>
                  </div>
               </div>
			   </li>
			   </ul>
               <!-- end Parent -->
         </div>';
	 }
	 }
  }
   $myeventhtml .='</div>
   <div>
   
   </div>
   </div>';
   $D->myeventstr = $myeventstr;
   if(!empty($chield)){
    $myeventhtml .='<script type="text/javascript">
	 
$(document).ready(function(){

	
   	var mainheight = $(".replayhide-'.$myeventres->post_id.'").height();
	   	var child = $(".no-comments'.$myeventres->post_id.'").height();

		

    
		

		var final = mainheight-child;
		$("#alleventjaneesh2'.$myeventres->post_id.'").css("height",final);
	
});

   

   
</script>

<style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#alleventjaneesh2'.$myeventres->post_id.'{
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#alleventjaneesh2'.$myeventres->post_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#alleventjaneesh2'.$myeventres->post_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#alleventjaneesh2'.$myeventres->post_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#alleventjaneesh2'.$myeventres->post_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#alleventjaneesh2'.$myeventres->post_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>
   	';
   }
   }
   }
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
   			/*reshare and like count logic apply here*/
          $acceptreshare =array();
   	$likesaccept   = array();
   
                $res	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$myeventres->post_id.'" LIMIT 1');
   	        $is_reshared       =$db2->fetch_object($res);
   			$resharesres       =$db2->query('select id  FROM post_reshares WHERE  post_id="'.$myeventres->post_id.'" ');
   			 while($resultres=$this->db2->fetch_object($resharesres))
   			{
   				$acceptreshare[]=$resultres;
   			}
   			$resharecnt     =count($acceptreshare);
   			$is_likedres  = $db2->query('select *  FROM  post_likes WHERE user_id="'.$this->user->id.'" AND post_id="'.$myeventres->post_id.'" LIMIT 1');;
   		    $is_liked       =$db2->fetch_object($is_likedres);
   			$likesnumberres		 = $db2->query('select *  FROM  post_likes WHERE  post_id="'.$myeventres->post_id.'" ');;
   			while($likesnumberres=$this->db2->fetch_object($likesnumberres))
   			{
   				$likesaccept[]=$likesnumberres;
   			}
   			$likes_number     = count($likesaccept);
                 				$buff->post_type='public';
                    if($likes_number > 0){					
   				$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$likes_number.'</a>';
   			   }else{
   				 $showlikes_btn ='';  
   			   }
   			   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>').'</a>';
                      if($resharecnt > 0){					
   
   				$resharecntyy ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$resharecnt.'</a>';
                      }else{
   					$resharecntyy ='';
   					
   				}
   				$is_agree = $buff->is_post_agree($this->user->id,$myeventres->post_id);
   				$is_agree_cnt = $buff->is_post_agree_cnt($myeventres->post_id);
   				if($is_agree_cnt->cnt > 0){					
   				$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
   			   }else{
   				 $showagreebtn_btn ='';  
   			   }
             				/* end reshare and like count logic apply here*/			
                       if($if_can_delete =='1' ||  ($this->user->id == $myeventres->id )){
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" > <img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				}
   					$is_fav  = $db2->query('select *  FROM  post_favs WHERE user_id="'.$this->user->id.'" AND post_id="'.$myeventres->post_id.'" LIMIT 1');;
   		    $is_favres       =$db2->fetch_object($is_fav);
   			if(!empty($is_favres)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   									$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$myeventres->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
   $is_spam  = $post->is_spam($myeventres->post_id,"public");
   			if($is_spam =="1"){
   					$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$is_spam->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                  }else{
   		          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$is_spam->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                  }
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
   
   	                
   	$acceptedhtml .='
   	 <div class="activity no-comments replayhide-'.$myeventres->post_id.'" id="allevent-'.$myeventres->post_id.'">

<!-- start Parent -->
<div class="row start'.$myeventres->post_id.'" style="border:0px solid red; margin:0px; padding:0px;">

	 <div id="myeventjaneesh'.$myeventres->post_id.'"></div> 

   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">

   <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden">
   <a href="'.userlink($myeventres->username).'" class="avatar bizcard" data-userid="'.$myeventres->id.'"><img src="'.$src.'"  /></a>	
</div><!--/ end : col-md-1 -->


<div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">

<!--/ start : activity container -->
<div class="activity-container">
<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
   		<a href="'.userlink($myeventres->username).'" class="author bizcard" data-userid="'.$myeventres->id.'">'. ($myeventres->username) .'</a>

   		  <div class="meta-info">'.$individual.' '.$grp.' </div>
        <div class="activity-options">'.$delete .''.$fav.'  </div>
        </div>


<div class="activity-content">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" style="padding:10px 10px 0px 10px;">
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
    <li>'.$vals->address.'<a class="btn-view-map" onclick="'.$window_pop.'" href="#">view map
    </a></li>
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
   	                           '.$download.'

  
</div> <!--/ event-list-blue-bg -->
</div> <!--/ activity-content -->



   <div id="replaypopup3-'.$myeventres->post_id.'" class="modal fade" ></div>
   <div class="activity-poll"></div>


   <div class="activity-footer meta-info">
   			<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$myeventres->post_id.'" class="permlink">'.post::parse_date($myeventres->postdate).'</a>
						   			<input type="hidden" id="time-'.$myeventres->post_id.'" value="'.post::parse_date($myeventres->postdate).'" />

			'.$replayhtml.'
   			<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_agree? '<img width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img  width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list">'.$reshare_content.''.$resharecntyy.'</span>
   			<div class="dropdown">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
   							  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >Linkedin</a></li>
   							  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >StumbleUpon</a></li>
   							  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >MySpace</a></li>
   							  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'"  target="_blank" >FriendFeed</a></li>
   							  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'"  target="_blank" >Google Plus</a></li>
   							   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
   							  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$myeventres->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
   						   </ul>
   						</div>
              
              <div class="like-list">'.$mark_content.'</div>							
   						
   			</div> <!--/ end :  activity-footer meta-info -->

</div>
<!--/ end : activity container -->
  
</div><!--/ end : col-md-11 -->


</div><!--/ end : col-md-12 -->


</div><!-- end : activity no-comments-->';

						 //this for subreplay checking 
        $replies                =$buff->checkreplies($myeventres->post_id);
		$event ='event';
    if(empty($replies)){
		$chield  = $buff->is_notificationchield_replay($myeventres->post_id,$event);
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);
		$allevents .=$myeventres->post_id.',';
		$D->allevents = $allevents;


	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 3;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" 
       style="border:0px solid grey;"> 
        <a style=" cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$myeventres->post_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
			}else{
				$replycont ='';
				
			}
			$groups = $buff->getgroupname($chield[$m]->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
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
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   //markcontent
				    $is_spam  = $buff->is_spam($chield[$m]->id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				if(($j-1)==$m){
					$css="tree1";
					$childclass ="replayaccept".$myeventres->post_id;
					
					
				}else{
					
					
					$css="tree";
					$childclass="";
					
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
	$message .='<a id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'"><button  class="btn-download-results">Download Results</button></a>';
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
            <div class="activity no-comments commentcontainer '.$childclass.'" style="border: 0px solid #E1E8ED;">
               <!-- start Parent -->
			   <ul class="'.$css.'">
			<li>
               <div class="row" style="padding:0px 10px;">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">
                   <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden"><a  href="'.userlink($chield[$m]->username).'"  class="avatar bizcard" data-userid="'.$chield[$m]->userid.'"><img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" /></a>
                     </div>
                     <div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">
                        <div class="activity-container">
                         <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12"><a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>
                           </div>
						   	<div class="activity-header">

						   <div class="meta-info"> '.$grp.'
						   </div>
   			
   			
   		                       </div>
                           <div class="activity-content">'.$mes.'</div>
                        </div>
                        <div></div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                           <div class="activity-footer meta-info">
   			<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$chield[$m]->id.'" class="permlink">'.post::parse_date($chield[$m]->date).'</a>
   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($myeventres->postdate).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$myeventres->post_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
   			<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
   		<div class="dropdown">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
   							  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'&source='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Linkedin</a></li>
   							  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >StumbleUpon</a></li>
   							  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >MySpace</a></li>
   							  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'  target="_blank" >FriendFeed</a></li>
   							  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Google Plus</a></li>
   							   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Twitter</a></li>
   							  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Facebook</a></li>
   						   </ul>
   						</div>
                        <div class="like-list">'.$mark_content.'</div>
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


<style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#myeventjaneesh'.$myeventres->post_id.'{
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#myeventjaneesh'.$myeventres->post_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#myeventjaneesh'.$myeventres->post_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#myeventjaneesh'.$myeventres->post_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#myeventjaneesh'.$myeventres->post_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#myeventjaneesh'.$myeventres->post_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>
   	';
   	
   }
   }
   
   
   	
   
   
   //TEMPLATE CODE START
   $tpl = new template( array('page_title' => $this->lang('dashboard_page_title', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );
   
   $tpl->initRoutine('DashboardLeftMenuNotification', array());
   $tpl->routine->load();
   if($this->param('tab') =="event"){
      $tpl->layout->useBlock('newnotification');
   $tpl->layout->setVar('acceptevents', $acceptedhtml);
   $tpl->layout->setVar('myevents', $myeventhtml);
    $D->css = "notifications active";
    $D->param = $this->param('req');
   
   
   $tpl->layout->setVar('allevents', $alleventhtml);	
   }
   if($this->param('tab') =="event"){
   	 $D->css = "notifications active";
      $tpl->layout->useBlock('newnotification');
   
   }
   if($this->param('tab') =="@me"  || $this->param('tab') ==""){
   	$tpl->useStaticHTML();
   					$tpl->staticHTML->useActivityContainer();
   	$activity = activityFactory::select('dashboard');
   					$activity->setTemplate( $tpl );
   					$activity->setUser( $u );
   					$result = $activity->loadPosts();
   					
   	 $D->css = "notifications active";
   	   if( $this->user->is_logged && isset($result[1]) && $result[1] > 0 ){
   						$tpl->layout->useBlock('activity-show-more');
   						$tpl->layout->setVar('activities_pager_value', htmlentities('{"activities_type":"user","activities_id":"'.$result[1].'","activities_user":"'.$u->id.'"}'));
   						$tpl->layout->block->save('activity_container_show_more');
   					
   				}else{
   				
   
   					$tpl->layout->setVar('main_content', $tpl->designer->createNoPostBox($this->lang('noposts_usrprofileprotected_ttl'), $this->lang('noposts_usrprofileprotected_txt', array('#USERNAME#'=>$u->username))));
   					$tpl->display();
   				}
   
   
   			//require_once($C->PLUGINS_DIR.'events/system/controllers/home_new1.php');
   
   		
   	
   }
   //Polls tab
   if($this->param('tab') =="polls"){
   	$pollallres           = $db2->query('SELECT p.*,u.* ,ps.date as postdate FROM `post_userbox` as pu inner join polls as p ON pu.post_id = p.posts_id 
   	inner join posts as ps ON ps.id=p.posts_id
   	inner join users as u ON u.id = ps.user_id
   	left join post_poll_votes as ppv ON ppv.poll_id = p.poll_id
   	left join polls_answers as pa ON pa.poll_id=ppv.poll_id
   	
   
            WHERE pu.user_id = "'.$this->user->id.'" AND ps.post_level is null  group by p.poll_id order by ps.date_lastcomment desc,p.poll_id desc LIMIT 10
   			   
   
      ');
    $pollallrowscount = $db2->num_rows($pollallres);
      	$poll_all_html ='';
   	
   	
     while($pollallresfetch = $db2->fetch_object($pollallres)){
   	$checkanswer = $db2->query('select * from post_poll_votes where poll_id="'.$pollallresfetch->poll_id.'" LIMIT 1');
   	 $checkres             =$db2->fetch_object($checkanswer);
   	  if($pollallresfetch->avatar !=''){
    $src=getAvatarUrl($pollallresfetch->avatar, 'thumbs1');
    
   }else{
    $src = $C->SITE_URL.'storage/avatars/thumbs1/_noavatar_user.gif';
   }
   	                      if($if_can_delete =='1' ||  ($this->user->id == $pollallresfetch->id )){
   
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$pollallresfetch->posts_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				} 
   					$buff->post_type ="public";
   		$is_spam  = $post->is_spam($pollallresfetch->posts_id,"public");
   			if($is_spam =="1"){
   					$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                  }else{
   		          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                  }
   			$is_fav  = $db2->query('select *  FROM  post_favs WHERE user_id="'.$this->user->id.'" AND post_id="'.$pollallresfetch->posts_id.'" LIMIT 1');;
   		    $is_favres       =$db2->fetch_object($is_fav);
   
   			if(!empty($is_favres)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$pollallresfetch->posts_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   									$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$pollallresfetch->posts_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
			$eventtypeqweqw =4;	
          $replies                =$buff->checkreplies($pollallresfetch->posts_id);
		 $groups                =$buff->getgroupname($pollallresfetch->group_id);
		if(!empty($replies)){
			$individual = '<a style="cursor:pointer;" class="author bizcard" onclick="replaycontent('.$replies->alternate_parent_id.','.$pollallresfetch->posts_id.','.$eventtypeqweqw.')" data-userid="'.$replies->userid.'">Replies to @'. $replies->handler .'</a>' ;
		   $replayhtml ='<a style="cursor:pointer" onclick="childpopuptimeline('.$replies->parent_id.','.$replies->replay_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}else{
			$individual ='';
			$replayhtml ='<a style="cursor:pointer" onclick="parentreplay('.$pollallresfetch->posts_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}
		if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
		 /*reshare and like count logic apply here*/
      $polllikes = array();
      $pollreshares = array();
   
                  $pollres	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$pollallresfetch->posts_id.'" LIMIT 1');
   	        $is_reshared       =$db2->fetch_object($pollres);
   			$pllallresharesres       =$db2->query('select id  FROM post_reshares WHERE  post_id="'.$pollallresfetch->posts_id.'" ');
   			 while($pollresultresultres=$this->db2->fetch_object($pllallresharesres))
   			{
   				$pollreshares[]=$pollresultresultres;
   			}
   			$resharecnt     =count($pollreshares);
   			$is_likedres  = $db2->query('select *  FROM  post_likes WHERE user_id="'.$this->user->id.'" AND post_id="'.$pollallresfetch->posts_id.'" LIMIT 1');;
   		    $is_liked       =$db2->fetch_object($is_likedres);
   			$likesnumberres		 = $db2->query('select *  FROM  post_likes WHERE  post_id="'.$pollallresfetch->posts_id.'" ');;
   			while($likesnumberres=$this->db2->fetch_object($likesnumberres))
   			{
   				$polllikes[]=$likesnumberres;
   			}
   			
   			$likes_number     = count($polllikes);
                 	$buff->post_type='public';
               if($likes_number > 0){					
   				$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'">'.$likes_number.'</a>';
   			   }else{
   				 $showlikes_btn ='';  
   			   }
   			   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>').'</a>';
                      if($resharecnt > 0){					
   
   				$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'">'.$resharecnt.'</a>';
                      }else{
   					$resharecnt ='';
   					
   				}
   				$is_agree = $buff->is_post_agree($this->user->id,$pollallresfetch->posts_id);
   				$is_agree_cnt = $buff->is_post_agree_cnt($pollallresfetch->posts_id);
   				if($is_agree_cnt->cnt > 0){					
   				$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
   			   }else{
   				 $showagreebtn_btn ='';  
   			   }
   			   $is_agree = $buff->is_post_agree($this->user->id,$pollallresfetch->posts_id);
   				$is_agree_cnt = $buff->is_post_agree_cnt($pollallresfetch->posts_id);
   				if($is_agree_cnt->cnt > 0){					
   				$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
   			   }else{
   				 $showagreebtn_btn ='';  
   			   }
             				/* end reshare and like count logic apply here*/	
   				
   		
   
      $poll_all_html .='<div class="activity no-comments replayhide-'.$pollallresfetch->posts_id.'">

	  <div id="allpolljaneesh1'.$pollallresfetch->posts_id.'"></div> 

   <a href="'.userlink($pollallresfetch->username).'" class="avatar bizcard" data-userid="'.$pollallresfetch->id.'"><img src="'.$src.'"  /></a>	
   
   <div class="activity-container">
   	<div class="activity-header">
   		<a href="'.userlink($pollallresfetch->username).'" class="author bizcard" data-userid="'.$pollallresfetch->id.'">'. ($pollallresfetch->username) .'</a>
   		<div class="meta-info">'.$individual.''.$grp.'
   			
   			
   		</div>
   <div class="activity-options">'.$delete.''.$fav.'
   		
   
   
   
   
   	</div>		</div>
   	<div class="activity-content"></div>
   	<div class="activity-poll"><div class="attachments lightbox-enabled">';
	$url =$C->SITE_URL;
	 $poll_all_html .=$buff->notificationpollhtml($pollallresfetch->posts_id,$url);
	
  
   
   $poll_all_html .= '<div class="activity-poll-option"><span></span></div>
   </div></div>
   	<div class="footer1 activity-footer meta-info">  </div>
   </div>
   
   
   <div class="clear"></div>
         			<div id="replaypopup4-'.$pollallresfetch->posts_id.'" class="modal fade" ></div>
   <!-- <div class="comments-thread-container " data-value="{&quot;activities_type&quot;:&quot;public&quot;,&quot;activities_id&quot;:&quot;311&quot;}">
   	
   	<div class="comments-editor-field offset_comment"><a href="#" class="additionalcomment" data-action="activityAddComment" data-namespace="comments"  data-role="services"> <span class="glyphicon glyphicon-comment"></span> Write a comment ...</a></div>
   </div>  -->
   <div>
   <div class="activity-footer meta-info">
   			<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$pollallresfetch->posts_id.'" class="permlink">'.post::parse_date($pollallresfetch->postdate).'</a>
		<input type="hidden" id="time-'.$pollallresfetch->posts_id.'" value="'.post::parse_date($pollallresfetch->postdate).'" />

			'.$replayhtml.'
   			<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   
   			<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
   
   			<div class="dropdown">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
   							  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$pollallresfetch->posts_id.'').'&title='.urlencode(htmlspecialchars($pollallresfetch->poll_question)).'&source='.urlencode($C->SITE_URL.'view/post:'.$pollallresfetch->posts_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$pollallresfetch->posts_id.'').'"  target="_blank" >Linkedin</a></li>
   							  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$pollallresfetch->posts_id.'').'  target="_blank" >StumbleUpon</a></li>
   							  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$pollallresfetch->posts_id.'').'  target="_blank" >MySpace</a></li>
   							  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$pollallresfetch->posts_id.'').'&title='.urlencode(htmlspecialchars($pollallresfetch->poll_question)).'  target="_blank" >FriendFeed</a></li>
   							  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$pollallresfetch->posts_id.'').'"  target="_blank" >Google Plus</a></li>
   							   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$pollallresfetch->posts_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$pollallresfetch->poll_question)).'"  target="_blank" >Twitter</a></li>
   							  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$pollallresfetch->posts_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$pollallresfetch->poll_question)).'"  target="_blank" >Facebook</a></li>
   						   </ul>
   						</div>
                        <div class="like-list">'.$mark_content.'</div>							
   						
   			</div>';
			   //this for subreplay checking 
        $replies                =$buff->checkreplies($pollallresfetch->posts_id);
    if(empty($replies)){
		$even ="poll";
		$chield  = $buff->is_notificationchield_replay($pollallresfetch->posts_id,$even);
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);

	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 4;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" 
       style="border:0px solid grey;"> 
        <a style=" cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$myeventres->post_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
			}else{
				$replycont ='';
				
			}
			$groups = $buff->getgroupname($chield[$m]->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
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
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   //markcontent
				    $is_spam  = $buff->is_spam($chield[$m]->id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				if(($j-1)==$m){
					$css="tree1";
					$childclass="allpollchild".$pollallresfetch->posts_id;
					
					
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
					$finalcon ='';
					$finalcon .= '<div class="title"><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/event.png"> <a href="'.$C->SITE_URL.'plugin/events/view/id:'.$eventdetails->id.'/postid:'.$eventdetails->post_id.'"  class="event-list-title"><strong>Event Name:</strong> '.$eventdetails->event_name.'</a></div>';
                    $finalcon .= '<span class="event-list-heading">Location:</span> <span class="event-list-txt">'.$eventdetails->address.'</span><br />';      
					if(!empty($eventdetails->url)){

					$finalcon .='<span class="event-list-heading">URL:</span> <span class="event-list-txt"><a href="'.$eventdetails->url.'"  target="_blank">'.$eventdetails->url.'</a></span><br />';						
                    }
					$time =$eventdetails->start_date.' '.$eventdetails->start_time;
					$date_time = date("M d,Y h:i:s A", strtotime($time));

					
                    $finalcon .='<span class="event-list-heading">Date and Time:</span> <span class="event-list-txt">'.$date_time.'</span><br />	
						';
					if(!empty($eventdetails->tag_name)){
						$hastagarr        =explode("#",trim($hastag));
			            $strret_arr       =array_filter($hastagarr);

						foreach($strret_arr as $keys=>$vals){
											if($keys ==1){
												$finalcon .='<span><a href="'.$C->SITE_URL.'/search/tab:tags/s:'.$vals.'"><strong>#'.$vals.'</strong></a>';
											}else{
												$finalcon .='<strong>#'.$vals.'</strong></span>';
											}
											
						}
						$finalcon .='<span class="event-list-heading">Hash Tags:</span> <span class="event-list-txt">'.$finalcon.'</span><br />
						';	
                    }
					if($eventdetails->status ==1){
						$st ="Active";
						
					}else{
						$st ="Cancelled";
						
					}
					if($user->id != $eventdetails->admin_id){
					if($eventdetails->event_status !=2  ){
						
						if( ($eventdetails->event_status =='' &&  $eventdetails->edit_status =='')   ){
						$finalcon .='<div id="acc-'.$eventdetails->post_id.'"><strong>User Response:</strong><input type="radio"  class="accept" name="accept" onclick="myFunction('.$eventdetails->post_id.'1)" value="'.$eventdetails->post_id.'-1">Accept<input type="radio" class="accept" name="accept" onclick="myFunction('.$eventdetails->post_id.'3)"  value="'.$eventdetails->post_id.'-3">Reject</div>';
					    
					}
					if($eventdetails->event_status==1){
						$display ="block";
						
					}else{
						$display ="none";
					}
					if($eventdetails->event_status==3){
						$displayreject ="block";
						
					}else{
						$displayreject ="none";
					}
					
					if( ($eventdetails->event_status !=2 ||  $eventdetails->edit_status!=4) &&  ($eventdetails->event_status !=2 &&   $eventdetails->edit_status!=4) ){
					
					
					$finalcon .='<div style="display:'.$display.';"id="accept-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					$finalcon .='<div style="display:'.$displayreject.';"id="reject-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
                  
					$finalcon .='<input type="hidden" id="attach-'.$eventdetails->post_id.'"  value="'.$eventdetails->attachment_id.'">';
                    }else{
						if((($eventdetails->event_status!=4) &&   ($eventdetails->edit_status==4))){
							
					$finalcon .='<div style="display:'.$display.';"id="accept-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					$finalcon .='<div style="display:'.$displayreject.';"id="reject-'.$link->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
                  
							
						}else{
						$finalcon .='<div>This event was no longer available.</div>';
						}
					}
					if($eventdetails->event_status == 5){
						$finalcon .='<div>This event was modified.</div>';
						
					}
						$finalcon .='<div>Status:'.$st.'</div>';

					
					
					
					}else{
						
						$finalcon .='<div>Event Cancelled</div>';

						
					}
					}else{
						if( $eventdetails->event_status!=2 || $eventdetails->event_status!=4 || $eventdetails->event_status!=5  ){
						$finalcon .='<div><strong>Status:</strong>'.$st.'</div>';

						$finalcon .='<div><a href="'.$C->SITE_URL.'dashboard?pid='.$link->post_id.'"><input class="btn-download-results" type="button" name="download" value="Download Results"></a></div>';

						}else{
							if($eventdetails->status == 2 && $eventdetails->edit_status!=4 ){
						$finalcon .='<div><strong>status:</strong>Cancel</div>';

								
							}else{
							$finalcon .='<div><strong>Status:</strong>'.$st.'</div>';

							$finalcon .='<div>This event was no longer available.</div>';

							}

							
						}
						
					}
					

					$mes = $finalcon;
						
					
				}elseif($buzztype =="poll"){
					$poll  = $buff->replay_is_poll($chield[$m]->id);
					$pollanswer=$buff->is_pollanswer($user->id,$poll[0]->poll_id);
					
           $url =$C->SITE_URL;	
            $mes = $buff->notificationpollhtml($chield[$m]->id,$url);
					
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


		 
	 

   
   
   	       $poll_all_html .='<div>
            <div class="activity no-comments commentcontainer '.$childclass.'" style="border: 0px solid #E1E8ED;">
               <!-- start Parent -->
			   <ul class="'.$css.'">
			<li>
               <div class="row" style="padding:0px 10px;">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green" >
                   <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden"><a  href="'.userlink($chield[$m]->username).'"  class="avatar bizcard" data-userid="'.$chield[$m]->userid.'"><img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" /></a>
                     </div>
                     <div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">
                        <div class="activity-container">
                        <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12"><a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>
                           </div>
						   	<div class="activity-header">

						   <div class="meta-info"> '.$grp.'
						   </div>
   			
   			
   		                       </div>
                           <div class="activity-content">'.$mes.'</div>
                        </div>
                        <div></div>
                        <div class=" col-xs-12 col-sm-12 col-md-12 col-lg-12">
                           <div class="activity-footer meta-info">
   			<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$chield[$m]->id.'" class="permlink">'.post::parse_date($chield[$m]->date).'</a>
   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($chield[$m]).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$pollallresfetch->posts_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
   			<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
   		<div class="dropdown">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
   							  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'&source='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Linkedin</a></li>
   							  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >StumbleUpon</a></li>
   							  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >MySpace</a></li>
   							  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'  target="_blank" >FriendFeed</a></li>
   							  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Google Plus</a></li>
   							   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Twitter</a></li>
   							  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Facebook</a></li>
   						   </ul>
   						</div>
                        <div class="like-list">'.$mark_content.'</div>
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
         </div></div>';
	 }
	 }
  }

   $poll_all_html .='</div></div></div>

   
   
   <script type="text/javascript">
   var height = $(".replayhide-'.$pollallresfetch->posts_id.'").height();
    var child = $(".allpollchild'.$pollallresfetch->posts_id.'").height();
	var final =Number(height)-Number(child);
	$("#allpolljaneesh1'.$pollallresfetch->posts_id.'").css("height",final);
   </script>

<style>
    .image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#allpolljaneesh1'.$pollallresfetch->posts_id.'{
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#allpolljaneesh1'.$pollallresfetch->posts_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#allpolljaneesh1'.$pollallresfetch->posts_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#allpolljaneesh1'.$pollallresfetch->posts_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#allpolljaneesh1'.$pollallresfetch->posts_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#allpolljaneesh1'.$pollallresfetch->posts_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>
   ';
      
   }
   //my poll tab
   $pollmyres           = $db2->query('SELECT ps.*,u.*,p.date as postdate FROM `posts` as p inner join polls as ps ON p.id = ps.posts_id 
   	inner join users as u ON u.id=p.user_id
   	
   
            WHERE p.user_id = "'.$this->user->id.'" AND p.post_level is null  group by ps.poll_id order by p.date_lastcomment desc,ps.poll_id desc LIMIT 10
   			   
   
      ');
      	 $pollmyrowscount     =$db2->num_rows($pollmyres);
   
    	   	$poll_my_html='';
   	
   	
     while($pollmyresfetch = $db2->fetch_object($pollmyres)){
   	$checkanswer = $db2->query('select * from post_poll_votes where poll_id="'.$pollmyresfetch->poll_id.'" LIMIT 1');
   	 $checkres             =$db2->fetch_object($checkanswer);
   	  if($pollmyresfetch->avatar !=''){
    $src=getAvatarUrl($pollmyresfetch->avatar, 'thumbs1');
    
   }else{
    $src = $C->SITE_URL.'storage/avatars/thumbs1/_noavatar_user.gif';
   }
   	 
   	 
   		
     /*reshare and like count logic apply here*/
      $pollmylikes = array();
      $pollmyreshares = array();
   
                  $pollres	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$pollmyresfetch->posts_id.'" LIMIT 1');
   	        $is_reshared       =$db2->fetch_object($pollres);
   			$pllallresharesres       =$db2->query('select id  FROM post_reshares WHERE  post_id="'.$pollmyresfetch->posts_id.'" ');
   			 while($pollresultresultres=$this->db2->fetch_object($pllallresharesres))
   			{
   				$pollmyreshares[]=$pollresultresultres;
   			}
   			$resharecnt     =count($pollmyreshares);
   			$is_likedres  = $db2->query('select *  FROM  post_likes WHERE user_id="'.$this->user->id.'" AND post_id="'.$pollmyresfetch->posts_id.'" LIMIT 1');;
   		    $is_liked       =$db2->fetch_object($is_likedres);
   			$likesnumberres		 = $db2->query('select *  FROM  post_likes WHERE  post_id="'.$pollmyresfetch->posts_id.'" ');;
   			while($likesnumberres=$this->db2->fetch_object($likesnumberres))
   			{
   				$pollmylikes[]=$likesnumberres;
   			}
   			
   			$likes_number     = count($pollmylikes);
                 	$buff->post_type='public';
               if($likes_number > 0){					
   				$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresfetch->posts_id.'"}').'">'.$likes_number.'</a>';
   			   }else{
   				 $showlikes_btn ='';  
   			   }
   			   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresfetch->posts_id.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>').'</a>';
                      if($resharecnt > 0){					
   
   				$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresfetch->posts_id.'"}').'">'.$resharecnt.'</a>';
                      }else{
   					$resharecnt ='';
   					
   				}
   				$is_agree = $buff->is_post_agree($this->user->id,$pollmyresfetch->posts_id);
   				$is_agree_cnt = $buff->is_post_agree_cnt($pollmyresfetch->posts_id);
   				if($is_agree_cnt->cnt > 0){					
   				$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresfetch->posts_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
   			   }else{
   				 $showagreebtn_btn ='';  
   			   }
   				
             				/* end reshare and like count logic apply here*/
             		  if($if_can_delete =='1' ||  ($this->user->id == $pollmyresfetch->id )){
   
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$pollmyresfetch->posts_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" > <img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				}
   					$is_fav  = $db2->query('select *  FROM  post_favs WHERE user_id="'.$this->user->id.'" AND post_id="'.$pollmyresfetch->posts_id.'" LIMIT 1');;
   		    $is_favres       =$db2->fetch_object($is_fav);
   			if(!empty($is_favres)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$pollmyresfetch->posts_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   									$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$pollmyresfetch->posts_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
   			$is_spam  = $post->is_spam($pollmyresfetch->posts_id,"public");
   			if($is_spam =="1"){
   					$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresfetch->posts_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                  }else{
   		          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresfetch->posts_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                  }
		  $eventtypeqweqw =5;	
          $replies                =$buff->checkreplies($pollmyresfetch->posts_id);
		 $groups                =$buff->getgroupname($pollmyresfetch->group_id);
		if(!empty($replies)){
			$individual = '<a style="cursor:pointer;" class="author bizcard" onclick="replaycontent('.$replies->alternate_parent_id.','.$pollmyresfetch->posts_id.','.$eventtypeqweqw.')" data-userid="'.$replies->userid.'">Replies to @'. $replies->handler .'</a>' ;
		   $replayhtml ='<a style="cursor:pointer" onclick="childpopuptimeline('.$replies->parent_id.','.$replies->replay_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}else{
			$individual ='';
			$replayhtml ='<a style="cursor:pointer" onclick="parentreplay('.$pollmyresfetch->posts_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}
		if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
      //print_r($pollallresfetch);
      $poll_my_html .='<div class="activity no-comments replayhide-'.$pollmyresfetch->posts_id.'" id="mypoll'.$pollmyresfetch->posts_id.'">
	  <div id="myeventplljaneesh'.$pollmyresfetch->posts_id.'"></div>
   <a href="'.userlink($pollmyresfetch->username).'" class="avatar bizcard" data-userid="'.$pollmyresfetch->id.'"><img src="'.$src.'"  /></a>	
   
   <div class="activity-container">
   	<div class="activity-header">
   		<a href="'.userlink($pollmyresfetch->username).'" class="author bizcard" data-userid="'.$pollmyresfetch->id.'">'. ($pollmyresfetch->username) .'</a>
   		<div class="meta-info">'.$individual.''.$grp.'
   			
   			
   		</div>
   <div class="activity-options">'.$delete.''.$fav.'
   
   
   
   
   	</div>		</div>
   	<div class="activity-content"></div>
   	<div></div>
   	<div class="activity-poll"><div class="attachments lightbox-enabled">';
	$url = $C->SITE_URL;
	$poll_my_html .=$buff->notificationpollhtml($pollmyresfetch->posts_id,$url);
  
  
   $poll_my_html .='<div class="activity-poll-option"><span></span></div>
   </div></div>
   	<div class="footer1 activity-footer meta-info">  </div>
   </div>
   
   
   <div class="clear"></div><div id="replaypopup5-'.$pollmyresfetch->posts_id.'" class="modal fade" ></div>

   <!-- <div class="comments-thread-container " data-value="{&quot;activities_type&quot;:&quot;public&quot;,&quot;activities_id&quot;:&quot;311&quot;}">
   	
   	<div class="comments-editor-field offset_comment"><a href="#" class="additionalcomment" data-action="activityAddComment" data-namespace="comments"  data-role="services"> <span class="glyphicon glyphicon-comment"></span> Write a comment ...</a></div>
   </div>  -->
   <div>
   <div class="activity-footer meta-info">
   			<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$pollmyresfetch->posts_id.'" class="permlink">'.post::parse_date($pollmyresfetch->postdate).'</a>
			   			<input type="hidden" id="time-'.$pollmyresfetch->posts_id.'" value="'.post::parse_date($pollmyresfetch->postdate).'" />

   			'.$replayhtml.'
			<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresfetch->posts_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   		    <div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresfetch->posts_id.'"}').'">'.($is_agree? '<img width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img  width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
   			<div class="dropdown">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
   							  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$pollmyresfetch->posts_id.'').'&title='.urlencode(htmlspecialchars($pollmyresfetch->poll_question)).'&source='.urlencode($C->SITE_URL.'view/post:'.$pollmyresfetch->posts_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$pollmyresfetch->posts_id.'').'"  target="_blank" >Linkedin</a></li>
   							  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$pollmyresfetch->posts_id.'').'  target="_blank" >StumbleUpon</a></li>
   							  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$pollmyresfetch->posts_id.'').'  target="_blank" >MySpace</a></li>
   							  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$pollmyresfetch->posts_id.'').'&title='.urlencode(htmlspecialchars($pollmyresfetch->poll_question)).'  target="_blank" >FriendFeed</a></li>
   							  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$pollmyresfetch->posts_id.'').'"  target="_blank" >Google Plus</a></li>
   							   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$pollmyresfetch->posts_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$pollmyresfetch->poll_question)).'"  target="_blank" >Twitter</a></li>
   							  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$pollmyresfetch->posts_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$pollmyresfetch->poll_question)).'"  target="_blank" >Facebook</a></li>
   						   </ul>
   						</div>
                                                   <div class="like-list">'.$mark_content.'</div>							
   						
   						
   			</div>';
						   //this for subreplay checking 
        $replies                =$buff->checkreplies($pollmyresfetch->posts_id);
    if(empty($replies)){
		$even ="poll";
		$chield  = $buff->is_notificationchield_replay($pollmyresfetch->posts_id,$even);
	if(!empty($chield) && count($chield)>0)
	{
		$mypollstr .=$pollmyresfetch->posts_id.',';
		$D->mypollstr = $mypollstr;
		$j=count($chield);

	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 5;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" 
       style="border:0px solid grey;"> 
        <a style=" cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$myeventres->post_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
			}else{
				$replycont ='';
				
			}
			$groups = $buff->getgroupname($chield[$m]->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
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
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   //markcontent
				    $is_spam  = $buff->is_spam($chield[$m]->id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				if(($j-1)==$m){
					$css="tree1";
					$childclass="mypollchild".$pollmyresfetch->posts_id;
					
					
				}else{
					
					
					$css="tree";
					$childclass ='';
					
				}
				$buzztype = $buff->getbuzztype($chield[$m]->id);
								if($buzztype =="buzz" || $buzztype =="" ){
					$mes = $buff->parsetext($chield[$m]->message);	
                }elseif($buzztype =="event"){
					$eventdetails	    =$buff->geteventdetails($chield[$m]->id);
						if($eventdetails->group_id !=''){
						$groupname               =$buff->getgroupname($eventdetails->group_id);
						}
					$finalcon ='';
					$finalcon .= '<div class="title"><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/event.png"> <a href="'.$C->SITE_URL.'plugin/events/view/id:'.$eventdetails->id.'/postid:'.$eventdetails->post_id.'"  class="event-list-title"><strong>Event Name:</strong> '.$eventdetails->event_name.'</a></div>';
                    $finalcon .= '<span class="event-list-heading">Location:</span> <span class="event-list-txt">'.$eventdetails->address.'</span><br />';      
					if(!empty($eventdetails->url)){

					$finalcon .='<span class="event-list-heading">URL:</span> <span class="event-list-txt"><a href="'.$eventdetails->url.'"  target="_blank">'.$eventdetails->url.'</a></span><br />';						
                    }
					$time =$eventdetails->start_date.' '.$eventdetails->start_time;
					$date_time = date("M d,Y h:i:s A", strtotime($time));

					
                    $finalcon .='<span class="event-list-heading">Date and Time:</span> <span class="event-list-txt">'.$date_time.'</span><br />	
						';
					if(!empty($eventdetails->tag_name)){
						$hastagarr        =explode("#",trim($hastag));
			            $strret_arr       =array_filter($hastagarr);

						foreach($strret_arr as $keys=>$vals){
											if($keys ==1){
												$finalcon .='<span><a href="'.$C->SITE_URL.'/search/tab:tags/s:'.$vals.'"><strong>#'.$vals.'</strong></a>';
											}else{
												$finalcon .='<strong>#'.$vals.'</strong></span>';
											}
											
						}
						$finalcon .='<span class="event-list-heading">Hash Tags:</span> <span class="event-list-txt">'.$finalcon.'</span><br />
						';	
                    }
					if($eventdetails->status ==1){
						$st ="Active";
						
					}else{
						$st ="Cancelled";
						
					}
					if($user->id != $eventdetails->admin_id){
					if($eventdetails->event_status !=2  ){
						
						if( ($eventdetails->event_status =='' &&  $eventdetails->edit_status =='')   ){
						$finalcon .='<div id="acc-'.$eventdetails->post_id.'"><strong>User Response:</strong><input type="radio"  class="accept" name="accept" onclick="myFunction('.$eventdetails->post_id.'1)" value="'.$eventdetails->post_id.'-1">Accept<input type="radio" class="accept" name="accept" onclick="myFunction('.$eventdetails->post_id.'3)"  value="'.$eventdetails->post_id.'-3">Reject</div>';
					    
					}
					if($eventdetails->event_status==1){
						$display ="block";
						
					}else{
						$display ="none";
					}
					if($eventdetails->event_status==3){
						$displayreject ="block";
						
					}else{
						$displayreject ="none";
					}
					
					if( ($eventdetails->event_status !=2 ||  $eventdetails->edit_status!=4) &&  ($eventdetails->event_status !=2 &&   $eventdetails->edit_status!=4) ){
					
					
					$finalcon .='<div style="display:'.$display.';"id="accept-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					$finalcon .='<div style="display:'.$displayreject.';"id="reject-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
                  
					$finalcon .='<input type="hidden" id="attach-'.$eventdetails->post_id.'"  value="'.$eventdetails->attachment_id.'">';
                    }else{
						if((($eventdetails->event_status!=4) &&   ($eventdetails->edit_status==4))){
							
					$finalcon .='<div style="display:'.$display.';"id="accept-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					$finalcon .='<div style="display:'.$displayreject.';"id="reject-'.$link->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
                  
							
						}else{
						$finalcon .='<div>This event was no longer available.</div>';
						}
					}
					if($eventdetails->event_status == 5){
						$finalcon .='<div>This event was modified.</div>';
						
					}
						$finalcon .='<div>Status:'.$st.'</div>';

					
					
					
					}else{
						
						$finalcon .='<div>Event Cancelled</div>';

						
					}
					}else{
						if( $eventdetails->event_status!=2 || $eventdetails->event_status!=4 || $eventdetails->event_status!=5  ){
						$finalcon .='<div><strong>Status:</strong>'.$st.'</div>';

						$finalcon .='<div><a href="'.$C->SITE_URL.'dashboard?pid='.$link->post_id.'"><input class="btn-download-results" type="button" name="download" value="Download Results"></a></div>';

						}else{
							if($eventdetails->status == 2 && $eventdetails->edit_status!=4 ){
						$finalcon .='<div><strong>status:</strong>Cancel</div>';

								
							}else{
							$finalcon .='<div><strong>Status:</strong>'.$st.'</div>';

							$finalcon .='<div>This event was no longer available.</div>';

							}

							
						}
						
					}
					

					$mes = $finalcon;
						
					
				}elseif($buzztype =="poll"){
					$url =$C->SITE_URL;
		
               $mes = $buff->notificationpollhtml($chield[$m]->id,$url);
					
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


		 
	 

   
   
   	       $poll_my_html .='<div>
            <div class="activity no-comments commentcontainer '.$childclass.'" style="border: 0px solid #E1E8ED; */">
               <!-- start Parent -->
			   <ul class="'.$css.'">
			<li>
               <div class="row" style="padding:0px 10px;">
                  <d<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green" >
                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden"><a  href="'.userlink($chield[$m]->username).'"  class="avatar bizcard" data-userid="'.$chield[$m]->userid.'"><img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" /></a>
                     </div>
                     <div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">
                        <div class="activity-container">
                           <div class="activity-header col-md-12"><a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>
                           </div>
						   	<div class="activity-header">

						   <div class="meta-info"> '.$grp.'
						   </div>
   			
   			
   		                       </div>
                           <div class="activity-content">'.$mes.'</div>
                        </div>
                        <div></div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                           <div class="activity-footer meta-info">
   			<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$chield[$m]->id.'" class="permlink">'.post::parse_date($chield[$m]->date).'</a>
   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($chield[$m]).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$pollmyresfetch->posts_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
   			<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
   		<div class="dropdown">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
   							  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'&source='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Linkedin</a></li>
   							  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >StumbleUpon</a></li>
   							  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >MySpace</a></li>
   							  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'  target="_blank" >FriendFeed</a></li>
   							  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Google Plus</a></li>
   							   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Twitter</a></li>
   							  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Facebook</a></li>
   						   </ul>
   						</div>
                        <div class="like-list">'.$mark_content.'</div>
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
  
  $poll_my_html .= '</div>
   <div>
   
   </div>
   </div>
   <br />
   <style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#myeventplljaneesh'.$pollmyresfetch->posts_id.'{
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#myeventplljaneesh'.$pollmyresfetch->posts_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#myeventplljaneesh'.$pollmyresfetch->posts_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#myeventplljaneesh'.$pollmyresfetch->posts_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#myeventplljaneesh'.$pollmyresfetch->posts_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#myeventplljaneesh'.$pollmyresfetch->posts_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>
  
   ';
      
   }
   //my Response tab
   $pollmyresponse           = $db2->query('SELECT ps.*,u.*,p.date as postdate FROM `posts` as p inner join polls as ps ON p.id = ps.posts_id 
   	inner join  post_poll_votes as ppu ON ppu.POLL_ID=ps.poll_id
   	inner join users as u on u.id = ppu.VOTER_USER_ID
   
            WHERE ppu.VOTER_USER_ID	 = "'.$this->user->id.'"  AND p.post_level is null group by ps.poll_id order by p.date_lastcomment desc, ps.poll_id desc LIMIT 10
   			   
   
      ');
      	   	 $pollmyresponserowscount     =$db2->num_rows($pollmyresponse);
   
    	    	   	$poll_myresponse_html='';
   	
   	
     while($pollmyresponsefetch = $db2->fetch_object($pollmyresponse)){
   	$checkanswer = $db2->query('select * from post_poll_votes where poll_id="'.$pollmyresponsefetch->poll_id.'" LIMIT 1');
   	 $checkres             =$db2->fetch_object($checkanswer);
   	 if($pollmyresponsefetch->avatar !=''){
    $src=getAvatarUrl($pollmyresponsefetch->avatar, 'thumbs1');
    
   }else{
    $src = $C->SITE_URL.'storage/avatars/thumbs1/_noavatar_user.gif';
   }
   	           						                     if($if_can_delete =='1' ||  ($this->user->id == $pollmyresponsefetch->id )){
   
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity"> <img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				} 
   	     $is_spam  = $post->is_spam($pollmyresponsefetch->post_id,"public");
   			if($is_spam =="1"){
   					$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                  }else{
   		          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                  }
   		$is_fav  = $db2->query('select *  FROM  post_favs WHERE user_id="'.$this->user->id.'" AND post_id="'.$pollmyresponsefetch->posts_id.'" LIMIT 1');;
   		    $is_favres       =$db2->fetch_object($is_fav);
   			if(!empty($is_favres)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   									$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
   		  $eventtypeqweqw =6;	
          $replies                =$buff->checkreplies($pollmyresponsefetch->posts_id);
		 $groups                =$buff->getgroupname($pollmyresponsefetch->group_id);
		if(!empty($replies)){
			$individual = '<a style="cursor:pointer;" class="author bizcard" onclick="replaycontent('.$replies->alternate_parent_id.','.$pollmyresponsefetch->posts_id.','.$eventtypeqweqw.')" data-userid="'.$replies->userid.'">Replies to @'. $replies->handler .'</a>' ;
		   $replayhtml ='<a style="cursor:pointer" onclick="childpopuptimeline('.$replies->parent_id.','.$replies->replay_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}else{
			$individual ='';
			$replayhtml ='<a style="cursor:pointer" onclick="parentreplay('.$pollmyresponsefetch->posts_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}
		if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
		    
   /*reshare and like count logic apply here*/
      $polllikes = array();
      $pollreshares = array();
   
                  $pollres	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$pollmyresponsefetch->posts_id.'" LIMIT 1');
   	        $is_reshared       =$db2->fetch_object($pollres);
   			$pllallresharesres       =$db2->query('select id  FROM post_reshares WHERE  post_id="'.$pollmyresponsefetch->posts_id.'" ');
   			 while($pollresultresultres=$this->db2->fetch_object($pllallresharesres))
   			{
   				$pollreshares[]=$pollresultresultres;
   			}
   			$resharecnt     =count($pollreshares);
   			$is_likedres  = $db2->query('select *  FROM  post_likes WHERE user_id="'.$this->user->id.'" AND post_id="'.$pollmyresponsefetch->posts_id.'" LIMIT 1');;
   		    $is_liked       =$db2->fetch_object($is_likedres);
   			$likesnumberres		 = $db2->query('select *  FROM  post_likes WHERE  post_id="'.$pollmyresponsefetch->posts_id.'" ');;
   			while($likesnumberres=$this->db2->fetch_object($likesnumberres))
   			{
   				$polllikes[]=$likesnumberres;
   			}
   			
   			$likes_number     = count($polllikes);
                 	$buff->post_type='public';
               if($likes_number > 0){					
   				$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'">'.$likes_number.'</a>';
   			   }else{
   				 $showlikes_btn ='';  
   			   }
   			   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Share"/>').'</a>';
                      if($resharecnt > 0){					
   
   				$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'">'.$resharecnt.'</a>';
                      }else{
   					$resharecnt ='';
   					
   				}
   				$is_agree = $buff->is_post_agree($this->user->id,$pollmyresponsefetch->posts_id);
   				$is_agree_cnt = $buff->is_post_agree_cnt($pollmyresponsefetch->posts_id);
   				if($is_agree_cnt->cnt > 0){					
   				$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
   			   }else{
   				 $showagreebtn_btn ='';  
   			   }
             				/* end reshare and like count logic apply here*/
   
      //print_r($pollallresfetch);
      $poll_myresponse_html .='<div class="activity no-comments replayhide-'.$pollmyresponsefetch->posts_id.'" id="response'.$pollmyresponsefetch->posts_id.'">
	  <div id="responsejaneesh'.$pollmyresponsefetch->posts_id.'"></div>
   <a href="'.userlink($pollmyresponsefetch->username).'" class="avatar bizcard" data-userid="'.$pollmyresponsefetch->id.'"><img src="'.$src.'"  /></a>	
   
   <div class="activity-container">
   	<div class="activity-header">
   		<a href="'.userlink($pollmyresponsefetch->username).'" class="author bizcard" data-userid="'.$pollmyresponsefetch->id.'">'. ($pollmyresponsefetch->username) .'</a>
   		<div class="meta-info">
   			'.$individual.''.$grp.'
   			
   		</div>
   <div class="activity-options">'.$delete.''.$fav.'
   		
   	</div>		</div>
   	<div class="activity-content"></div>
   	<div></div>
   	<div class="activity-poll"><div class="attachments lightbox-enabled">';
	$url =$C->SITE_URL;
	$poll_myresponse_html .=$buff->notificationpollhtml($pollmyresponsefetch->posts_id,$url);
  
   
   $poll_myresponse_html .='<div class="activity-poll-option"><span></span></div>
   </div></div>
   	<div class="footer1 activity-footer meta-info">  </div>
   </div>
   
   
   <div class="clear"></div> <div id="replaypopup6-'.$pollmyresponsefetch->posts_id.'" class="modal fade" ></div>

   <!-- <div class="comments-thread-container " data-value="{&quot;activities_type&quot;:&quot;public&quot;,&quot;activities_id&quot;:&quot;311&quot;}">
   	
   	<div class="comments-editor-field offset_comment"><a href="#" class="additionalcomment" data-action="activityAddComment" data-namespace="comments"  data-role="services"> <span class="glyphicon glyphicon-comment"></span> Write a comment ...</a></div>
   </div>  -->
   <div>
   <div class="activity-footer meta-info">
   			<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$pollmyresponsefetch->posts_id.'" class="permlink">'.post::parse_date($pollmyresponsefetch->postdate).'</a>
   			<input type="hidden" id="time-'.$pollmyresponsefetch->posts_id.'" value="'.post::parse_date($pollmyresponsefetch->postdate).'" />

			'.$replayhtml.'
			<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   		 <div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'">'.($is_agree? '<img width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img  width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
   			<div class="dropdown">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
   							  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$pollmyresponsefetch->posts_id.'').'&title='.urlencode(htmlspecialchars($pollmyresponsefetch->poll_question)).'&source='.urlencode($C->SITE_URL.'view/post:'.$pollmyresponsefetch->posts_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$pollmyresponsefetch->posts_id.'').'"  target="_blank" >Linkedin</a></li>
   							  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$pollmyresponsefetch->posts_id.'').'  target="_blank" >StumbleUpon</a></li>
   							  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$pollmyresponsefetch->posts_id.'').'  target="_blank" >MySpace</a></li>
   							  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$pollmyresponsefetch->posts_id.'').'&title='.urlencode(htmlspecialchars($pollmyresponsefetch->poll_question)).'  target="_blank" >FriendFeed</a></li>
   							  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$pollmyresponsefetch->posts_id.'').'"  target="_blank" >Google Plus</a></li>
   							   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$pollmyresponsefetch->posts_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$pollmyresponsefetch->poll_question)).'"  target="_blank" >Twitter</a></li>
   							  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$pollmyresponsefetch->posts_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$pollmyresponsefetch->poll_question)).'"  target="_blank" >Facebook</a></li>
   						   </ul>
   						</div>
                        <div class="like-list">'.$mark_content.'</div>							
   			</div>';
						   //this for subreplay checking 
     $replies                =$buff->checkreplies($pollmyresponsefetch->posts_id);
    if(empty($replies)){
		$chield  = $buff->is_chield_replay($pollmyresponsefetch->posts_id);
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);
		$myresponse .=$pollmyresponsefetch->posts_id.',';
		$D->myresponsestr =$myresponse;

	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 6;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" 
       style="border:0px solid grey;"> 
        <a style=" cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$myeventres->post_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
			}else{
				$replycont ='';
				
			}
			$groups = $buff->getgroupname($chield[$m]->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
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
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   //markcontent
				    $is_spam  = $buff->is_spam($chield[$m]->id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				if(($j-1)==$m){
					$css="tree1";
					$childclass="responsechild".$pollmyresponsefetch->posts_id;
					
					
				}else{
					
					
					$css="tree";
					$childclass="";
					
				}
				$buzztype = $buff->getbuzztype($chield[$m]->id);
								if($buzztype =="buzz" || $buzztype =="" ){
					$mes = $buff->parsetext($chield[$m]->message);	
                }elseif($buzztype =="event"){
					$eventdetails	    =$buff->geteventdetails($chield[$m]->id);
						if($eventdetails->group_id !=''){
						$groupname               =$buff->getgroupname($eventdetails->group_id);
						}
					$finalcon ='';
					$finalcon .= '<div class="title"><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/event.png"> <a href="'.$C->SITE_URL.'plugin/events/view/id:'.$eventdetails->id.'/postid:'.$eventdetails->post_id.'"  class="event-list-title"><strong>Event Name:</strong> '.$eventdetails->event_name.'</a></div>';
                    $finalcon .= '<span class="event-list-heading">Location:</span> <span class="event-list-txt">'.$eventdetails->address.'</span><br />';      
					if(!empty($eventdetails->url)){

					$finalcon .='<span class="event-list-heading">URL:</span> <span class="event-list-txt"><a href="'.$eventdetails->url.'"  target="_blank">'.$eventdetails->url.'</a></span><br />';						
                    }
					$time =$eventdetails->start_date.' '.$eventdetails->start_time;
					$date_time = date("M d,Y h:i:s A", strtotime($time));

					
                    $finalcon .='<span class="event-list-heading">Date and Time:</span> <span class="event-list-txt">'.$date_time.'</span><br />	
						';
					if(!empty($eventdetails->tag_name)){
						$hastagarr        =explode("#",trim($hastag));
			            $strret_arr       =array_filter($hastagarr);

						foreach($strret_arr as $keys=>$vals){
											if($keys ==1){
												$finalcon .='<span><a href="'.$C->SITE_URL.'/search/tab:tags/s:'.$vals.'"><strong>#'.$vals.'</strong></a>';
											}else{
												$finalcon .='<strong>#'.$vals.'</strong></span>';
											}
											
						}
						$finalcon .='<span class="event-list-heading">Hash Tags:</span> <span class="event-list-txt">'.$finalcon.'</span><br />
						';	
                    }
					if($eventdetails->status ==1){
						$st ="Active";
						
					}else{
						$st ="Cancelled";
						
					}
					if($user->id != $eventdetails->admin_id){
					if($eventdetails->event_status !=2  ){
						
						if( ($eventdetails->event_status =='' &&  $eventdetails->edit_status =='')   ){
						$finalcon .='<div id="acc-'.$eventdetails->post_id.'"><strong>User Response:</strong><input type="radio"  class="accept" name="accept" onclick="myFunction('.$eventdetails->post_id.'1)" value="'.$eventdetails->post_id.'-1">Accept<input type="radio" class="accept" name="accept" onclick="myFunction('.$eventdetails->post_id.'3)"  value="'.$eventdetails->post_id.'-3">Reject</div>';
					    
					}
					if($eventdetails->event_status==1){
						$display ="block";
						
					}else{
						$display ="none";
					}
					if($eventdetails->event_status==3){
						$displayreject ="block";
						
					}else{
						$displayreject ="none";
					}
					
					if( ($eventdetails->event_status !=2 ||  $eventdetails->edit_status!=4) &&  ($eventdetails->event_status !=2 &&   $eventdetails->edit_status!=4) ){
					
					
					$finalcon .='<div style="display:'.$display.';"id="accept-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					$finalcon .='<div style="display:'.$displayreject.';"id="reject-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
                  
					$finalcon .='<input type="hidden" id="attach-'.$eventdetails->post_id.'"  value="'.$eventdetails->attachment_id.'">';
                    }else{
						if((($eventdetails->event_status!=4) &&   ($eventdetails->edit_status==4))){
							
					$finalcon .='<div style="display:'.$display.';"id="accept-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					$finalcon .='<div style="display:'.$displayreject.';"id="reject-'.$link->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
                  
							
						}else{
						$finalcon .='<div>This event was no longer available.</div>';
						}
					}
					if($eventdetails->event_status == 5){
						$finalcon .='<div>This event was modified.</div>';
						
					}
						$finalcon .='<div>Status:'.$st.'</div>';

					
					
					
					}else{
						
						$finalcon .='<div>Event Cancelled</div>';

						
					}
					}else{
						if( $eventdetails->event_status!=2 || $eventdetails->event_status!=4 || $eventdetails->event_status!=5  ){
						$finalcon .='<div><strong>Status:</strong>'.$st.'</div>';

						$finalcon .='<div><a href="'.$C->SITE_URL.'dashboard?pid='.$link->post_id.'"><input class="btn-download-results" type="button" name="download" value="Download Results"></a></div>';

						}else{
							if($eventdetails->status == 2 && $eventdetails->edit_status!=4 ){
						$finalcon .='<div><strong>status:</strong>Cancel</div>';

								
							}else{
							$finalcon .='<div><strong>Status:</strong>'.$st.'</div>';

							$finalcon .='<div>This event was no longer available.</div>';

							}

							
						}
						
					}
					

					$mes = $finalcon;
						
					
				}elseif($buzztype =="poll"){
					$url =$C->SITE_URL;
	
$mes = $buff->notificationpollhtml($chield[$m]->id,$url);
					
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


		 
	 

   
   
   	       $poll_myresponse_html .='<div>
            <div class="activity no-comments commentcontainer '.$childclass.'">
               <!-- start Parent -->
			   <ul class="'.$css.'">
			<li>
               <div class="row" style="padding:0px 10px;">
                  <div class="col-md-12 col-xs-12 buzz-parent-box">
                     <div class="col-md-1" style="overflow:hidden"><a  href="'.userlink($chield[$m]->username).'"  class="avatar bizcard" data-userid="'.$chield[$m]->userid.'"><img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" /></a>
                     </div>
                     <div class="col-md-11 col-xs-11" style="padding:0px 3px 0px 8px;">
                        <div class="activity-container">
                           <div class="activity-header col-md-12"><a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>
                           </div>
						   	<div class="activity-header">

						   <div class="meta-info"> '.$grp.'
						   </div>
   			
   			
   		                       </div>
                           <div class="activity-content">'.$mes.'</div>
                        </div>
                        <div></div>
                        <div class="col-md-12">
                           <div class="activity-footer meta-info">
   			<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$chield[$m]->id.'" class="permlink">'.post::parse_date($chield[$m]->date).'</a>
   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($chield[$m]->date).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$pollmyresponsefetch->posts_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
   			<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
   		<div class="dropdown">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
   							  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'&source='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Linkedin</a></li>
   							  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >StumbleUpon</a></li>
   							  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >MySpace</a></li>
   							  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'  target="_blank" >FriendFeed</a></li>
   							  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Google Plus</a></li>
   							   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Twitter</a></li>
   							  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Facebook</a></li>
   						   </ul>
   						</div>
                        <div class="like-list">'.$mark_content.'</div>
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
   $poll_myresponse_html .='</div>
   <div>
   
   </div>
   </div>
   <br />
      <style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#responsejaneesh'.$pollmyresponsefetch->posts_id.'{
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#responsejaneesh'.$pollmyresponsefetch->posts_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#responsejaneesh'.$pollmyresponsefetch->posts_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#responsejaneesh'.$pollmyresponsefetch->posts_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#responsejaneesh'.$pollmyresponsefetch->posts_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#responsejaneesh'.$pollmyresponsefetch->posts_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>
   ';
      
   }
   $D->allpollscount = $pollallrowscount;
   $D->mypollscount = $pollmyrowscount ;
   $D->myresponsepollscount = $pollmyresponserowscount;
       $D->param = $this->param('req');
   
   $D->css = "notifications active";
      $tpl->layout->useBlock('polls');
   	$tpl->layout->setVar('poll_all_html', $poll_all_html);
   	$tpl->layout->setVar('poll_my_html', $poll_my_html);
   	$tpl->layout->setVar('poll_myresponse_html', $poll_myresponse_html);
   
   
   
   }
   if($this->param('tab') =="Intraday"){
   	 $D->css = "notifications active";
   	 $p =new post();
   	 //displaying the all infraday data of me and from people you follow
   	 $offset =0;
   	 
   	 $myintrdatay   =$p->myintraday($this->user->id,$offset);
   	 $myintradaycount = count($myintrdatay);
   	 $D->myintradaycount = $myintradaycount;
   	 //displaying the  from people you follow infraday data .
   	 
   	 $intrdata_followers   =$p->peopleyoufollowintradaydata($this->user->id,$offset);
   	 $followcount = count($intrdata_followers);
   	 $D->followcount = $followcount;
   	// echo '<pre>';print_r($infrdata_followers);exit;
   	 //displaying the  my  .
   	 
   	 $allintraday   =$p->allintraday($this->user->id,$offset);
   	 $allintracount = count($allintraday);
   	 $D->allintracount = $allintracount;
   	 //displaying myintraday correct data//
   	 $myintradaycorrect  =$p->myintradaycorrectdata($this->user->id,$offset);
   	 $myintradaycal       = $p->myintradaycorrectdatacalculation($this->user->id);
   	 $totalintraday       = $p->totalintraday($this->user->id);
   	 $correctcnt          = $myintradaycal->correctcnt;
   	 $totalcnt            = $totalintraday->totalcnt;
   	 $correctper          = round(($correctcnt/$totalcnt)*100,2);
   	 $D->correctpercntage = $correctper;
   	 $D->totalcorrect     = count($myintradaycorrect);
   	 //displaying myintraday in correct data//
   	 $myintradayincorrect  =$p->myintradayincorrectdata($this->user->id,$offset);
   	 $myintradayincal       = $p->myintradayincorrectdatacalculation($this->user->id);
   	 $incorrectcnt          = $myintradayincal->correctcnt;
   	 $incorrectper          = round(($incorrectcnt/$totalcnt)*100,2);
   	 $D->incorrectpercntage = $incorrectper;
   	 $D->totalincorrect     = count($myintradayincorrect);
   
   	 
   	/*intraday all Tab*/
   		$allintrahtml='';
       foreach($allintraday as $allintrkeys=>$allintravals){
   
           if(!empty($allintraday[$allintrkeys])){	
   	
   
   			/*reshare and like count logic apply here*/
                  $res	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$allintravals->post_id.'" LIMIT 1');
   	        $is_reshared       =$db2->fetch_object($res);
   			$resharesres       =$db2->query('select id  FROM post_reshares WHERE  post_id="'.$allintravals->post_id.'" ');
   			 while($resultres=$this->db2->fetch_object($resharesres))
   			{
   				$wew[]=$resultres;
   			}
   			$resharecnt     =count($wew);
   			$is_likedres  = $db2->query('select *  FROM  post_likes WHERE user_id="'.$this->user->id.'" AND post_id="'.$allintravals->post_id.'" LIMIT 1');;
   		    $is_liked       =$db2->fetch_object($is_likedres);
   			$likesnumberres		 = $db2->query('select count(*) as likecnt  FROM  post_likes WHERE  post_id="'.$allintravals->post_id.'" ');;
   			$likesnumberresdata    =$this->db2->fetch_object($likesnumberres);
   			
   
   			$likes_number     = $likesnumberresdata->likecnt;
   			$is_fav  = $db2->query('select *  FROM  post_favs WHERE user_id="'.$this->user->id.'" AND post_id="'.$allintravals->post_id.'" LIMIT 1');;
   		    $is_favres       =$db2->fetch_object($is_fav);
   			if(!empty($is_favres)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   									$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
   			if($allintravals->avatar !=''){
   				 $src=getAvatarUrl($allintravals->avatar, 'thumbs1');
                  }else{
   				$src = $C->SITE_URL.'storage/avatars/thumbs1/_noavatar_user.gif';
   
   			}
   			$assetdata   =$p->assetdata($allintravals->post_id);
   			
   			$assetdatahtml ='';
   			$assetdatahtml .='<div>'.$allintravals->message.'</div>
   		<table class="table table-bordered" width="100%">
      <thead>
        <tr class="box-sub-title" style="color:white;background-color:#69c6e2;font-size:12px;">
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
   				$css ="#f6fbfc";
   				
   			}else{
   				$css ="#e3f8fe";
   			}
   			if($assetvals->result == "1"){
   				$img ='<img  src="'.$C->SITE_URL.'static/images/tick.png" width="16"/>';
   				
   			}elseif($assetvals->result =="0"){
   				$img ='<img  src="'.$C->SITE_URL.'static/images/wrong.png"/>';
   				
   			}else{
   				$img ='Open';
   				
   			}
   			$assetdatahtml .='
   			
    
        <tr style="background-color:'.$css.'; color: #66757F; font-size:12px;font-weight:normal;">
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
   
   
   
   			  $buff->post_type='public';
   			    if($likes_number > 0){					
   				$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.$likes_number.'</a>';
   			   }else{
   				 $showlikes_btn ='';  
   			   }
   			   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="UnRebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                      if($resharecnt > 0){					
   
   				$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.$resharecnt.'</a>';
                      }else{
   					$resharecnt ='';
   					
   				}
   				$is_agree = $buff->is_post_agree($this->user->id,$allintravals->post_id);
   				$is_agree_cnt = $buff->is_post_agree_cnt($allintravals->post_id);
   				if($is_agree_cnt->cnt > 0){					
   				$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
   			   }else{
   				 $showagreebtn_btn ='';  
   			   }
             				/* end reshare and like count logic apply here*/
             				
             				if($if_can_delete =='1' ||($this->user->id ==$allintravals->user_id) ){
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				}
                  $is_spam  = $post->is_spam($allintravals->post_id,"public");
   			if($is_spam =="1"){
   					$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Unmarkspam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                  }else{
   		          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Markspam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                  }
		$eventtypeqweqw =7;	
          $replies                =$buff->checkreplies($allintravals->post_id);
		 $groups                =$buff->getgroupname($allintravals->group_id);
		if(!empty($replies)){
			$individual = '<a style="cursor:pointer;" class="author bizcard" onclick="replaycontent('.$replies->alternate_parent_id.','.$allintravals->post_id.','.$eventtypeqweqw.')" data-userid="'.$replies->userid.'">Replies to @'. $replies->handler .'</a>' ;
		   $replayhtml ='<a style="cursor:pointer" onclick="childpopuptimeline('.$replies->parent_id.','.$replies->replay_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}else{
			$individual ='';
			$replayhtml ='<a style="cursor:pointer" onclick="parentreplay('.$allintravals->post_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}
		if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
   									
             						
   
   	                
   	$allintrahtml .='
   	
   	 <div class="activity no-comments replayhide-'.$allintravals->post_id.'">
	  <div id="allintrajaneesh-'.$allintravals->post_id.'" ></div>

	 
   <a href="'.userlink($allintravals->username).'" class="avatar bizcard" data-userid="'.$allintravals->user_id.'"><img src="'.$src.'"  /></a>	
   <div class="activity-container">
   	<div class="activity-header">
   		<a href="'.userlink($allintravals->username).'" class="author bizcard" data-userid="'.$allintravals->user_id.'">'. ($allintravals->username) .'</a>
   		<div class="meta-info">'.$individual.''.$grp.'
   			
   			
   		</div>
   		<div class="activity-options">'.$delete .''.$fav.'	
   		</div>
   	</div>
   	<div class="activity-content"></div>
   	<div><div class="attachments lightbox-enabled">
   
   
   
          </div></div>
   	<div class="activity-poll">'.$assetdatahtml.'</div>
   	<div class="footer1 activity-footer meta-info">  </div>
   </div>
   
   
   <div class="clear"></div>
   <div id="replaypopup7-'.$allintravals->post_id.'" class="modal fade" ></div>

   
   <div>
   <div class="activity-footer meta-info">
   			<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$allintravals->post_id.'" class="permlink">'.post::parse_date($allintravals->date).'</a>
			   			<input type="hidden" id="time-'.$allintravals->post_id.'" value="'.post::parse_date($allintravals->date).'" />

   			'.$replayhtml.'
			<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
   		<div class="dropdown">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
   							  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Linkedin</a></li>
   							  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'  target="_blank" >StumbleUpon</a></li>
   							  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'  target="_blank" >MySpace</a></li>
   							  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'  target="_blank" >FriendFeed</a></li>
   							  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Google Plus</a></li>
   							   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
   							  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
   						   </ul>
   						</div>
                        <div class="like-list">'.$mark_content.'</div>							
   			</div>
   			';
						   //this for subreplay checking 
        $replies                =$buff->checkreplies($allintravals->post_id);
    if(empty($replies)){
		$chield  = $buff->is_chield_replay($allintravals->post_id);
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);

	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 7;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" 
       style="border:0px solid grey;"> 
        <a style=" cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$myeventres->post_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
			}else{
				$replycont ='';
				
			}
			$groups = $buff->getgroupname($chield[$m]->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
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
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   //markcontent
				    $is_spam  = $buff->is_spam($chield[$m]->id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				if(($j-1)==$m){
					$css="tree1";
					$childclass="allintraday".$allintravals->post_id;
					
					
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
					$finalcon ='';
					$finalcon .= '<div class="title"><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/event.png"> <a href="'.$C->SITE_URL.'plugin/events/view/id:'.$eventdetails->id.'/postid:'.$eventdetails->post_id.'"  class="event-list-title"><strong>Event Name:</strong> '.$eventdetails->event_name.'</a></div>';
                    $finalcon .= '<span class="event-list-heading">Location:</span> <span class="event-list-txt">'.$eventdetails->address.'</span><br />';      
					if(!empty($eventdetails->url)){

					$finalcon .='<span class="event-list-heading">URL:</span> <span class="event-list-txt"><a href="'.$eventdetails->url.'"  target="_blank">'.$eventdetails->url.'</a></span><br />';						
                    }
					$time =$eventdetails->start_date.' '.$eventdetails->start_time;
					$date_time = date("M d,Y h:i:s A", strtotime($time));

					
                    $finalcon .='<span class="event-list-heading">Date and Time:</span> <span class="event-list-txt">'.$date_time.'</span><br />	
						';
					if(!empty($eventdetails->tag_name)){
						$hastagarr        =explode("#",trim($hastag));
			            $strret_arr       =array_filter($hastagarr);

						foreach($strret_arr as $keys=>$vals){
											if($keys ==1){
												$finalcon .='<span><a href="'.$C->SITE_URL.'/search/tab:tags/s:'.$vals.'"><strong>#'.$vals.'</strong></a>';
											}else{
												$finalcon .='<strong>#'.$vals.'</strong></span>';
											}
											
						}
						$finalcon .='<span class="event-list-heading">Hash Tags:</span> <span class="event-list-txt">'.$finalcon.'</span><br />
						';	
                    }
					if($eventdetails->status ==1){
						$st ="Active";
						
					}else{
						$st ="Cancelled";
						
					}
					if($user->id != $eventdetails->admin_id){
					if($eventdetails->event_status !=2  ){
						
						if( ($eventdetails->event_status =='' &&  $eventdetails->edit_status =='')   ){
						$finalcon .='<div id="acc-'.$eventdetails->post_id.'"><strong>User Response:</strong><input type="radio"  class="accept" name="accept" onclick="myFunction('.$eventdetails->post_id.'1)" value="'.$eventdetails->post_id.'-1">Accept<input type="radio" class="accept" name="accept" onclick="myFunction('.$eventdetails->post_id.'3)"  value="'.$eventdetails->post_id.'-3">Reject</div>';
					    
					}
					if($eventdetails->event_status==1){
						$display ="block";
						
					}else{
						$display ="none";
					}
					if($eventdetails->event_status==3){
						$displayreject ="block";
						
					}else{
						$displayreject ="none";
					}
					
					if( ($eventdetails->event_status !=2 ||  $eventdetails->edit_status!=4) &&  ($eventdetails->event_status !=2 &&   $eventdetails->edit_status!=4) ){
					
					
					$finalcon .='<div style="display:'.$display.';"id="accept-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					$finalcon .='<div style="display:'.$displayreject.';"id="reject-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
                  
					$finalcon .='<input type="hidden" id="attach-'.$eventdetails->post_id.'"  value="'.$eventdetails->attachment_id.'">';
                    }else{
						if((($eventdetails->event_status!=4) &&   ($eventdetails->edit_status==4))){
							
					$finalcon .='<div style="display:'.$display.';"id="accept-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					$finalcon .='<div style="display:'.$displayreject.';"id="reject-'.$link->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
                  
							
						}else{
						$finalcon .='<div>This event was no longer available.</div>';
						}
					}
					if($eventdetails->event_status == 5){
						$finalcon .='<div>This event was modified.</div>';
						
					}
						$finalcon .='<div>Status:'.$st.'</div>';

					
					
					
					}else{
						
						$finalcon .='<div>Event Cancelled</div>';

						
					}
					}else{
						if( $eventdetails->event_status!=2 || $eventdetails->event_status!=4 || $eventdetails->event_status!=5  ){
						$finalcon .='<div><strong>Status:</strong>'.$st.'</div>';

						$finalcon .='<div><a href="'.$C->SITE_URL.'dashboard?pid='.$link->post_id.'"><input class="btn-download-results" type="button" name="download" value="Download Results"></a></div>';

						}else{
							if($eventdetails->status == 2 && $eventdetails->edit_status!=4 ){
						$finalcon .='<div><strong>status:</strong>Cancel</div>';

								
							}else{
							$finalcon .='<div><strong>Status:</strong>'.$st.'</div>';

							$finalcon .='<div>This event was no longer available.</div>';

							}

							
						}
						
					}
					

					$mes = $finalcon;
						
					
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
	$message .='<a id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'"><button  class="btn-download-results">Download Results</button></a>';
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


		 
	 

   
   
   	       $allintrahtml .='<div>
            <div class="activity no-comments commentcontainer '.$childclass.'" style="border: 0px solid #E1E8ED; */">
               <!-- start Parent -->
			   <ul class="'.$css.'">
			<li>
               <div class="row" style="padding:0px 10px;">
               <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green" >
                 <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden"><a  href="'.userlink($chield[$m]->username).'"  class="avatar bizcard" data-userid="'.$chield[$m]->userid.'"><img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" /></a>
                     </div>
                     <div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">
                        <div class="activity-container">
                        <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12"><a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>
                           </div>
						   	<div class="activity-header">

						   <div class="meta-info"> '.$grp.'
						   </div>
   			
   			
   		                       </div>
                           <div class="activity-content">'.$mes.'</div>
                        </div>
                        <div></div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                           <div class="activity-footer meta-info">
   			<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$chield[$m]->id.'" class="permlink">'.post::parse_date($chield[$m]->date).'</a>
   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($chield[$m]).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$allintravals->post_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
   			<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
   		<div class="dropdown">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
   							  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'&source='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Linkedin</a></li>
   							  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >StumbleUpon</a></li>
   							  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >MySpace</a></li>
   							  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'  target="_blank" >FriendFeed</a></li>
   							  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Google Plus</a></li>
   							   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Twitter</a></li>
   							  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Facebook</a></li>
   						   </ul>
   						</div>
                        <div class="like-list">'.$mark_content.'</div>
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
   $allintrahtml .='</div>
   <div>
   
   </div>
   </div>
   <br />
   <script>
   var main = $(".replayhide-'.$allintravals->post_id.'").height();
      var child = $(".allintraday'.$allintravals->post_id.'").height();
	  var final = main-child;
	  $("#allintrajaneesh-'.$allintravals->post_id.'").css("height",final);

   </script>
   <style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#allintrajaneesh-'.$allintravals->post_id.'{
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#allintrajaneesh-'.$allintravals->post_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#allintrajaneesh-'.$allintravals->post_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#allintrajaneesh-'.$allintravals->post_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#allintrajaneesh-'.$allintravals->post_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#allintrajaneesh-'.$allintravals->post_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>
   	';
   	
   }
   }
   /*end all intraday tab  */
   /*intraday people you follow tab*/
       $followintrahtml='';
       foreach($intrdata_followers as $allintrkeys=>$allintravals){
   
           if(!empty($intrdata_followers[$allintrkeys])){	
   	
   
   			/*reshare and like count logic apply here*/
                  $res	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$allintravals->post_id.'" LIMIT 1');
   	        $is_reshared       =$db2->fetch_object($res);
   			$resharesres       =$db2->query('select id  FROM post_reshares WHERE  post_id="'.$allintravals->post_id.'" ');
   			 while($resultres=$this->db2->fetch_object($resharesres))
   			{
   				$wew[]=$resultres;
   			}
   			$resharecnt     =count($wew);
   			$is_likedres  = $db2->query('select *  FROM  post_likes WHERE user_id="'.$this->user->id.'" AND post_id="'.$allintravals->post_id.'" LIMIT 1');;
   		    $is_liked       =$db2->fetch_object($is_likedres);
   			$likesnumberres		 = $db2->query('select count(*) as likecnt  FROM  post_likes WHERE  post_id="'.$allintravals->post_id.'" ');;
   			$likesnumberresdata    =$this->db2->fetch_object($likesnumberres);
   			
   
   			$likes_number     = $likesnumberresdata->likecnt;
   			$is_fav  = $db2->query('select *  FROM  post_favs WHERE user_id="'.$this->user->id.'" AND post_id="'.$allintravals->post_id.'" LIMIT 1');;
   		    $is_favres       =$db2->fetch_object($is_fav);
   			if(!empty($is_favres)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   									$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
			$eventtypeqweqw =8;	
          $replies                =$buff->checkreplies($allintravals->post_id);
		 $groups                =$buff->getgroupname($allintravals->group_id);
		if(!empty($replies)){
			$individual = '<a style="cursor:pointer;" class="author bizcard" onclick="replaycontent('.$replies->alternate_parent_id.','.$allintravals->post_id.','.$eventtypeqweqw.')" data-userid="'.$replies->userid.'">Replies to @'. $replies->handler .'</a>' ;
		   $replayhtml ='<a style="cursor:pointer" onclick="childpopuptimeline('.$replies->parent_id.','.$replies->replay_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}else{
			$individual ='';
			$replayhtml ='<a style="cursor:pointer" onclick="parentreplay('.$allintravals->post_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}
		if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
   			if($allintravals->avatar !=''){
   				 $src=getAvatarUrl($allintravals->avatar, 'thumbs1');
                  }else{
   				$src = $C->SITE_URL.'storage/avatars/thumbs1/_noavatar_user.gif';
   
   			}
   			$assetdata   =$p->assetdata($allintravals->post_id);
   			
   			$peopledatahtml ='';
   			$peopledatahtml .='<div>'.$allintravals->message.'</div>
   		<table class="table table-bordered" width="100%">
      <thead>
        <tr class="box-sub-title" style="color:white;background-color:#69c6e2;font-size:12px;">
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
   				$css ="#f6fbfc";
   				
   			}else{
   				$css ="#e3f8fe";
   			}
   			if($assetvals->result == "1"){
   				$img ='<img  src="'.$C->SITE_URL.'static/images/tick.png" width="16"/>';
   				
   			}elseif($assetvals->result =="0"){
   				$img ='<img  src="'.$C->SITE_URL.'static/images/wrong.png"/>';
   				
   			}else{
   				$img ='Open';
   				
   			}
   			$peopledatahtml .='
   			
    
        <tr style="background-color:'.$css.'; color: #66757F; font-size:12px;font-weight:normal;">
          <td>$'.$assetvals->ticker.'</td>
          <td>2130</td>
          <td>'.$assetvals->stoploss_price.'</td>
   	<td>'.$assetvals->predicted_price.'</td>
   	<td>'.$assetvals->current_price.'</td>
   
   	<td>'.$img.'</td>
        </tr>
        
      
   
   			
   			';
   			
   		}
   		$peopledatahtml .='</tbody>
    </table>';		
   
   
   
   			  $buff->post_type='public';
   			    if($likes_number > 0){					
   				$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.$likes_number.'</a>';
   			   }else{
   				 $showlikes_btn ='';  
   			   }
   			   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="UnRebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                      if($resharecnt > 0){					
   
   				$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.$resharecnt.'</a>';
                      }else{
   					$resharecnt ='';
   					
   				}
   				$is_agree = $buff->is_post_agree($this->user->id,$allintravals->post_id);
   				$is_agree_cnt = $buff->is_post_agree_cnt($allintravals->post_id);
   				if($is_agree_cnt->cnt > 0){					
   				$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
   			   }else{
   				 $showagreebtn_btn ='';  
   			   }
             				/* end reshare and like count logic apply here*/
             				
             				if($if_can_delete =='1' ||($this->user->id ==$allintravals->user_id) ){
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				}
                  $is_spam  = $post->is_spam($allintravals->post_id,"public");
   			if($is_spam =="1"){
   					$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Unmarkspam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                  }else{
   		          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Markspam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                  }
   									
             						
   
   	                
   	$followintrahtml .='
   	
   	 <div class="activity no-comments replayhide-'.$allintravals->post_id.'" id="follow'.$allintravals->post_id.'">
	   <div  id="followjaneesh'.$allintravals->post_id.'"></div>

   <a href="'.userlink($allintravals->username).'" class="avatar bizcard" data-userid="'.$allintravals->user_id.'"><img src="'.$src.'"  /></a>	
   <div class="activity-container">
   	<div class="activity-header">
   		<a href="'.userlink($allintravals->username).'" class="author bizcard" data-userid="'.$allintravals->user_id.'">'. ($allintravals->username) .'</a>
   		<div class="meta-info">'.$individual.''.$grp.'
   			
   			
   		</div>
   		<div class="activity-options">'.$delete .''.$fav.'	
   		</div>
   	</div>
   	<div class="activity-content"></div>
   	<div><div class="attachments lightbox-enabled">
   
   
   
          </div></div>
   	<div class="activity-poll">'.$peopledatahtml.'</div>
   	<div class="footer1 activity-footer meta-info">  </div>
   </div>
   
   
   <div class="clear"></div>	<div id="replaypopup8-'.$allintravals->post_id.'" class="modal fade" ></div>

   
   <div>
   <div class="activity-footer meta-info">
   			<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$allintravals->post_id.'" class="permlink">'.post::parse_date($allintravals->date).'</a>
			'.$replayhtml.'
   			<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
   		<div class="dropdown">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
   							  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Linkedin</a></li>
   							  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'  target="_blank" >StumbleUpon</a></li>
   							  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'  target="_blank" >MySpace</a></li>
   							  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'  target="_blank" >FriendFeed</a></li>
   							  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Google Plus</a></li>
   							   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
   							  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
   						   </ul>
   						</div>
                        <div class="like-list">'.$mark_content.'</div>							
   			</div>';
						   //this for subreplay checking 
        $replies                =$buff->checkreplies($allintravals->post_id);
    if(empty($replies)){
		$chield  = $buff->is_chield_replay($allintravals->post_id);
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);
		$followstr .=$allintravals->post_id.',';
		$D->followstr =$followstr;

	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 8;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" 
       style="border:0px solid grey;"> 
        <a style=" cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$myeventres->post_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
			}else{
				$replycont ='';
				
			}
			$groups = $buff->getgroupname($chield[$m]->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
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
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   //markcontent
				    $is_spam  = $buff->is_spam($chield[$m]->id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				if(($j-1)==$m){
					$css="tree1";
					$childclass="followintra".$allintravals->post_id;
					
					
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
					$finalcon ='';
					$finalcon .= '<div class="title"><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/event.png"> <a href="'.$C->SITE_URL.'plugin/events/view/id:'.$eventdetails->id.'/postid:'.$eventdetails->post_id.'"  class="event-list-title"><strong>Event Name:</strong> '.$eventdetails->event_name.'</a></div>';
                    $finalcon .= '<span class="event-list-heading">Location:</span> <span class="event-list-txt">'.$eventdetails->address.'</span><br />';      
					if(!empty($eventdetails->url)){

					$finalcon .='<span class="event-list-heading">URL:</span> <span class="event-list-txt"><a href="'.$eventdetails->url.'"  target="_blank">'.$eventdetails->url.'</a></span><br />';						
                    }
					$time =$eventdetails->start_date.' '.$eventdetails->start_time;
					$date_time = date("M d,Y h:i:s A", strtotime($time));

					
                    $finalcon .='<span class="event-list-heading">Date and Time:</span> <span class="event-list-txt">'.$date_time.'</span><br />	
						';
					if(!empty($eventdetails->tag_name)){
						$hastagarr        =explode("#",trim($hastag));
			            $strret_arr       =array_filter($hastagarr);

						foreach($strret_arr as $keys=>$vals){
											if($keys ==1){
												$finalcon .='<span><a href="'.$C->SITE_URL.'/search/tab:tags/s:'.$vals.'"><strong>#'.$vals.'</strong></a>';
											}else{
												$finalcon .='<strong>#'.$vals.'</strong></span>';
											}
											
						}
						$finalcon .='<span class="event-list-heading">Hash Tags:</span> <span class="event-list-txt">'.$finalcon.'</span><br />
						';	
                    }
					if($eventdetails->status ==1){
						$st ="Active";
						
					}else{
						$st ="Cancelled";
						
					}
					if($user->id != $eventdetails->admin_id){
					if($eventdetails->event_status !=2  ){
						
						if( ($eventdetails->event_status =='' &&  $eventdetails->edit_status =='')   ){
						$finalcon .='<div id="acc-'.$eventdetails->post_id.'"><strong>User Response:</strong><input type="radio"  class="accept" name="accept" onclick="myFunction('.$eventdetails->post_id.'1)" value="'.$eventdetails->post_id.'-1">Accept<input type="radio" class="accept" name="accept" onclick="myFunction('.$eventdetails->post_id.'3)"  value="'.$eventdetails->post_id.'-3">Reject</div>';
					    
					}
					if($eventdetails->event_status==1){
						$display ="block";
						
					}else{
						$display ="none";
					}
					if($eventdetails->event_status==3){
						$displayreject ="block";
						
					}else{
						$displayreject ="none";
					}
					
					if( ($eventdetails->event_status !=2 ||  $eventdetails->edit_status!=4) &&  ($eventdetails->event_status !=2 &&   $eventdetails->edit_status!=4) ){
					
					
					$finalcon .='<div style="display:'.$display.';"id="accept-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					$finalcon .='<div style="display:'.$displayreject.';"id="reject-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
                  
					$finalcon .='<input type="hidden" id="attach-'.$eventdetails->post_id.'"  value="'.$eventdetails->attachment_id.'">';
                    }else{
						if((($eventdetails->event_status!=4) &&   ($eventdetails->edit_status==4))){
							
					$finalcon .='<div style="display:'.$display.';"id="accept-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					$finalcon .='<div style="display:'.$displayreject.';"id="reject-'.$link->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
                  
							
						}else{
						$finalcon .='<div>This event was no longer available.</div>';
						}
					}
					if($eventdetails->event_status == 5){
						$finalcon .='<div>This event was modified.</div>';
						
					}
						$finalcon .='<div>Status:'.$st.'</div>';

					
					
					
					}else{
						
						$finalcon .='<div>Event Cancelled</div>';

						
					}
					}else{
						if( $eventdetails->event_status!=2 || $eventdetails->event_status!=4 || $eventdetails->event_status!=5  ){
						$finalcon .='<div><strong>Status:</strong>'.$st.'</div>';

						$finalcon .='<div><a href="'.$C->SITE_URL.'dashboard?pid='.$link->post_id.'"><input class="btn-download-results" type="button" name="download" value="Download Results"></a></div>';

						}else{
							if($eventdetails->status == 2 && $eventdetails->edit_status!=4 ){
						$finalcon .='<div><strong>status:</strong>Cancel</div>';

								
							}else{
							$finalcon .='<div><strong>Status:</strong>'.$st.'</div>';

							$finalcon .='<div>This event was no longer available.</div>';

							}

							
						}
						
					}
					

					$mes = $finalcon;
						
					
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
	$message .='<a id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'"><button  class="btn-download-results">Download Results</button></a>';
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


		 
	 

   
   
   	       $followintrahtml .='<div>
            <div class="activity no-comments commentcontainer '.$childclass.'">
               <!-- start Parent -->
			   <ul class="'.$css.'">
			<li>
               <div class="row" style="padding:0px 10px;">
                  <div class="col-md-12 col-xs-12 buzz-parent-box">
                     <div class="col-md-1" style="overflow:hidden"><a  href="'.userlink($chield[$m]->username).'"  class="avatar bizcard" data-userid="'.$chield[$m]->userid.'"><img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" /></a>
                     </div>
                     <div class="col-md-11 col-xs-11" style="padding:0px 3px 0px 8px;">
                        <div class="activity-container">
                           <div class="activity-header col-md-12"><a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>
                           </div>
						   	<div class="activity-header">

						   <div class="meta-info"> '.$grp.'
						   </div>
   			
   			
   		                       </div>
                           <div class="activity-content">'.$mes.'</div>
                        </div>
                        <div></div>
                        <div class="col-md-12">
                           <div class="activity-footer meta-info">
   			<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$chield[$m]->id.'" class="permlink">'.post::parse_date($chield[$m]->date).'</a>
   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($chield[$m]).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$allintravals->post_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
   			<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
   		<div class="dropdown">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
   							  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'&source='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Linkedin</a></li>
   							  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >StumbleUpon</a></li>
   							  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >MySpace</a></li>
   							  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'  target="_blank" >FriendFeed</a></li>
   							  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Google Plus</a></li>
   							   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Twitter</a></li>
   							  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Facebook</a></li>
   						   </ul>
   						</div>
                        <div class="like-list">'.$mark_content.'</div>
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
   $followintrahtml .='</div>
   <div>
   
   </div>
   </div>
      <br />
	  <style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#followjaneesh'.$allintravals->post_id.' {
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#followjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#followjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#followjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#followjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#followjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>

   	';
   	
   }
   }
   /*end you people follow tab */

    /*myintraday correct tab*/
       $myintradaycorrectintrahtml='';
       foreach($myintradaycorrect as $allintrkeys=>$allintravals){
   
           if(!empty($myintradaycorrect[$allintrkeys])){	
   	
   
   			/*reshare and like count logic apply here*/
                  $res	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$allintravals->post_id.'" LIMIT 1');
   	        $is_reshared       =$db2->fetch_object($res);
   			$resharesres       =$db2->query('select id  FROM post_reshares WHERE  post_id="'.$allintravals->post_id.'" ');
   			 while($resultres=$this->db2->fetch_object($resharesres))
   			{
   				$wew[]=$resultres;
   			}
   			$resharecnt     =count($wew);
   			$is_likedres  = $db2->query('select *  FROM  post_likes WHERE user_id="'.$this->user->id.'" AND post_id="'.$allintravals->post_id.'" LIMIT 1');;
   		    $is_liked       =$db2->fetch_object($is_likedres);
   			$likesnumberres		 = $db2->query('select count(*) as likecnt  FROM  post_likes WHERE  post_id="'.$allintravals->post_id.'" ');;
   			$likesnumberresdata    =$this->db2->fetch_object($likesnumberres);
   			
   
   			$likes_number     = $likesnumberresdata->likecnt;
   			$is_fav  = $db2->query('select *  FROM  post_favs WHERE user_id="'.$this->user->id.'" AND post_id="'.$allintravals->post_id.'" LIMIT 1');;
   		    $is_favres       =$db2->fetch_object($is_fav);
   			if(!empty($is_favres)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   									$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
   			if($allintravals->avatar !=''){
   				 $src=getAvatarUrl($allintravals->avatar, 'thumbs1');
                  }else{
   				$src = $C->SITE_URL.'storage/avatars/thumbs1/_noavatar_user.gif';
   
   			}
   			$assetdata   =$p->assetdatacorrect($allintravals->post_id);
   			
   			$mycorrectintradatahtml ='';
   			$mycorrectintradatahtml .='<div>'.$allintravals->message.'</div>
   		<table class="table table-bordered" width="100%">
      <thead>
        <tr class="box-sub-title" style="color:white;background-color:#69c6e2;font-size:12px;">
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
   				$css ="#f6fbfc";
   				
   			}else{
   				$css ="#e3f8fe";
   			}
   			if($assetvals->result == "1"){
   				$img ='<img  src="'.$C->SITE_URL.'static/images/tick.png" width="16"/>';
   				
   			}elseif($assetvals->result =="0"){
   				$img ='<img  src="'.$C->SITE_URL.'static/images/wrong.png"/>';
   				
   			}else{
   				$img ='Open';
   				
   			}
   			$mycorrectintradatahtml .='
   			
    
        <tr style="background-color:'.$css.'; color: #66757F; font-size:12px;font-weight:normal;">
          <td>$'.$assetvals->ticker.'</td>
          <td>2130</td>
          <td>'.$assetvals->stoploss_price.'</td>
   	<td>'.$assetvals->predicted_price.'</td>
   	<td>'.$assetvals->current_price.'</td>
   
   	<td>'.$img.'</td>
        </tr>
        
      
   
   			
   			';
   			
   		}
   		$mycorrectintradatahtml .='</tbody>
    </table>';		
   
   
   
   			  $buff->post_type='public';
   			    if($likes_number > 0){					
   				$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.$likes_number.'</a>';
   			   }else{
   				 $showlikes_btn ='';  
   			   }
   			   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="UnRebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                      if($resharecnt > 0){					
   
   				$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.$resharecnt.'</a>';
                      }else{
   					$resharecnt ='';
   					
   				}
   				$is_agree = $buff->is_post_agree($this->user->id,$allintravals->post_id);
   				$is_agree_cnt = $buff->is_post_agree_cnt($allintravals->post_id);
   				if($is_agree_cnt->cnt > 0){					
   				$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
   			   }else{
   				 $showagreebtn_btn ='';  
   			   }
             				/* end reshare and like count logic apply here*/
             				
             				if($if_can_delete =='1' ||($this->user->id ==$allintravals->user_id) ){
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				}
                  $is_spam  = $post->is_spam($allintravals->post_id,"public");
   			if($is_spam =="1"){
   					$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Unmarkspam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                  }else{
   		          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Markspam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                  }
		$eventtypeqweqw = 9;	
          $replies                =$buff->checkreplies($allintravals->post_id);
		 $groups                =$buff->getgroupname($allintravals->post_id);
		if(!empty($replies)){
			$individual = '<a style="cursor:pointer;" class="author bizcard" onclick="replaycontent('.$replies->alternate_parent_id.','.$allintravals->post_id.','.$eventtypeqweqw.')" data-userid="'.$replies->userid.'">Replies to @'. $replies->handler .'</a>' ;
		   $replayhtml ='<a style="cursor:pointer" onclick="childpopuptimeline('.$replies->parent_id.','.$replies->replay_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}else{
			$individual ='';
			$replayhtml ='<a style="cursor:pointer" onclick="parentreplay('.$allintravals->post_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}
		if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
   									
             						
   
   	                
   	$myintradaycorrectintrahtml .='
   	
   	 <div class="activity no-comments" id="intracorrect'.$allintravals->post_id.'">
	    	 <div id="correctjaneesh'.$allintravals->post_id.'"></div>

   <a href="'.userlink($allintravals->username).'" class="avatar bizcard" data-userid="'.$allintravals->user_id.'"><img src="'.$src.'"  /></a>	
   <div class="activity-container">
   	<div class="activity-header">
   		<a href="'.userlink($allintravals->username).'" class="author bizcard" data-userid="'.$allintravals->user_id.'">'. ($allintravals->username) .'</a>
   		<div class="meta-info">'.$individual.''.$grp.'
   			
   			
   		</div>
   		<div class="activity-options">'.$delete .''.$fav.'	
   		</div>
   	</div>
   	<div class="activity-content"></div>
   	<div><div class="attachments lightbox-enabled">
   
   
   
          </div></div>
   	<div class="activity-poll">'.$mycorrectintradatahtml.'</div>
   	<div class="footer1 activity-footer meta-info">  </div>
   </div>
   
   
   <div class="clear"></div><div id="replaypopup9-'.$allintravals->post_id.'" class="modal fade" ></div>

   
   <div>
   <div class="activity-footer meta-info">
   			<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$allintravals->post_id.'" class="permlink">'.post::parse_date($allintravals->date).'</a>
   			'.$replayhtml.'
			<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
   		<div class="dropdown">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
   							  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Linkedin</a></li>
   							  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'  target="_blank" >StumbleUpon</a></li>
   							  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'  target="_blank" >MySpace</a></li>
   							  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'  target="_blank" >FriendFeed</a></li>
   							  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Google Plus</a></li>
   							   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
   							  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
   						   </ul>
   						</div>
                        <div class="like-list">'.$mark_content.'</div>							
   			</div>';
						   //this for subreplay checking 
        $replies                =$buff->checkreplies($allintravals->post_id);
    if(empty($replies)){
		$chield  = $buff->is_chield_replay($allintravals->post_id);
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);
		$myintrastr .=$allintravals->post_id.',';
		$D->myintrastr =$myintrastr;

	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 9;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" 
       style="border:0px solid grey;"> 
        <a style=" cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$myeventres->post_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
			}else{
				$replycont ='';
				
			}
			$groups = $buff->getgroupname($chield[$m]->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
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
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   //markcontent
				    $is_spam  = $buff->is_spam($chield[$m]->id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				if(($j-1)==$m){
					$css="tree1";
					$childclass="correctcild".$allintravals->post_id;
					
					
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
					$finalcon ='';
					$finalcon .= '<div class="title"><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/event.png"> <a href="'.$C->SITE_URL.'plugin/events/view/id:'.$eventdetails->id.'/postid:'.$eventdetails->post_id.'"  class="event-list-title"><strong>Event Name:</strong> '.$eventdetails->event_name.'</a></div>';
                    $finalcon .= '<span class="event-list-heading">Location:</span> <span class="event-list-txt">'.$eventdetails->address.'</span><br />';      
					if(!empty($eventdetails->url)){

					$finalcon .='<span class="event-list-heading">URL:</span> <span class="event-list-txt"><a href="'.$eventdetails->url.'"  target="_blank">'.$eventdetails->url.'</a></span><br />';						
                    }
					$time =$eventdetails->start_date.' '.$eventdetails->start_time;
					$date_time = date("M d,Y h:i:s A", strtotime($time));

					
                    $finalcon .='<span class="event-list-heading">Date and Time:</span> <span class="event-list-txt">'.$date_time.'</span><br />	
						';
					if(!empty($eventdetails->tag_name)){
						$hastagarr        =explode("#",trim($hastag));
			            $strret_arr       =array_filter($hastagarr);

						foreach($strret_arr as $keys=>$vals){
											if($keys ==1){
												$finalcon .='<span><a href="'.$C->SITE_URL.'/search/tab:tags/s:'.$vals.'"><strong>#'.$vals.'</strong></a>';
											}else{
												$finalcon .='<strong>#'.$vals.'</strong></span>';
											}
											
						}
						$finalcon .='<span class="event-list-heading">Hash Tags:</span> <span class="event-list-txt">'.$finalcon.'</span><br />
						';	
                    }
					if($eventdetails->status ==1){
						$st ="Active";
						
					}else{
						$st ="Cancelled";
						
					}
					if($user->id != $eventdetails->admin_id){
					if($eventdetails->event_status !=2  ){
						
						if( ($eventdetails->event_status =='' &&  $eventdetails->edit_status =='')   ){
						$finalcon .='<div id="acc-'.$eventdetails->post_id.'"><strong>User Response:</strong><input type="radio"  class="accept" name="accept" onclick="myFunction('.$eventdetails->post_id.'1)" value="'.$eventdetails->post_id.'-1">Accept<input type="radio" class="accept" name="accept" onclick="myFunction('.$eventdetails->post_id.'3)"  value="'.$eventdetails->post_id.'-3">Reject</div>';
					    
					}
					if($eventdetails->event_status==1){
						$display ="block";
						
					}else{
						$display ="none";
					}
					if($eventdetails->event_status==3){
						$displayreject ="block";
						
					}else{
						$displayreject ="none";
					}
					
					if( ($eventdetails->event_status !=2 ||  $eventdetails->edit_status!=4) &&  ($eventdetails->event_status !=2 &&   $eventdetails->edit_status!=4) ){
					
					
					$finalcon .='<div style="display:'.$display.';"id="accept-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					$finalcon .='<div style="display:'.$displayreject.';"id="reject-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
                  
					$finalcon .='<input type="hidden" id="attach-'.$eventdetails->post_id.'"  value="'.$eventdetails->attachment_id.'">';
                    }else{
						if((($eventdetails->event_status!=4) &&   ($eventdetails->edit_status==4))){
							
					$finalcon .='<div style="display:'.$display.';"id="accept-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					$finalcon .='<div style="display:'.$displayreject.';"id="reject-'.$link->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
                  
							
						}else{
						$finalcon .='<div>This event was no longer available.</div>';
						}
					}
					if($eventdetails->event_status == 5){
						$finalcon .='<div>This event was modified.</div>';
						
					}
						$finalcon .='<div>Status:'.$st.'</div>';

					
					
					
					}else{
						
						$finalcon .='<div>Event Cancelled</div>';

						
					}
					}else{
						if( $eventdetails->event_status!=2 || $eventdetails->event_status!=4 || $eventdetails->event_status!=5  ){
						$finalcon .='<div><strong>Status:</strong>'.$st.'</div>';

						$finalcon .='<div><a href="'.$C->SITE_URL.'dashboard?pid='.$link->post_id.'"><input class="btn-download-results" type="button" name="download" value="Download Results"></a></div>';

						}else{
							if($eventdetails->status == 2 && $eventdetails->edit_status!=4 ){
						$finalcon .='<div><strong>status:</strong>Cancel</div>';

								
							}else{
							$finalcon .='<div><strong>Status:</strong>'.$st.'</div>';

							$finalcon .='<div>This event was no longer available.</div>';

							}

							
						}
						
					}
					

					$mes = $finalcon;
						
					
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
	$message .='<a id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'"><button  class="btn-download-results">Download Results</button></a>';
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


		 
	 

   
   
   	       $myintradaycorrectintrahtml .='<div>
            <div class="activity no-comments commentcontainer '.$childclass.'" style="border: 0px solid #E1E8ED; */">
               <!-- start Parent -->
			   <ul class="'.$css.'">
			<li>
               <div class="row" style="padding:0px 10px;">
                 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">
                     <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden"><a  href="'.userlink($chield[$m]->username).'"  class="avatar bizcard" data-userid="'.$chield[$m]->userid.'"><img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" /></a>
                     </div>
                     <div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">
                        <div class="activity-container">
                        <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12"><a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>
                           </div>
						   	<div class="activity-header">

						   <div class="meta-info"> '.$grp.'
						   </div>
   			
   			
   		                       </div>
                           <div class="activity-content">'.$mes.'</div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                           <div class="activity-footer meta-info">
   			<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$chield[$m]->id.'" class="permlink">'.post::parse_date($chield[$m]->date).'</a>
   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($chield[$m]).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$allintravals->post_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
   			<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
   		<div class="dropdown">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
   							  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'&source='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Linkedin</a></li>
   							  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >StumbleUpon</a></li>
   							  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >MySpace</a></li>
   							  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'  target="_blank" >FriendFeed</a></li>
   							  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Google Plus</a></li>
   							   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Twitter</a></li>
   							  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Facebook</a></li>
   						   </ul>
   						</div>
                        <div class="like-list">'.$mark_content.'</div>
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
  
   $myintradaycorrectintrahtml .='</div>
   <div>
   
   </div>
   </div>
      <br />
	  <style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#correctjaneesh'.$allintravals->post_id.'{
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#correctjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#correctjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#correctjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#correctjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#correctjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>

   	';
   	
   }
   }
   /*end myintraday correct tab */
   
   /*myintraday incorrect tab*/
       $myintradayincorrectintrahtml='';
       foreach($myintradayincorrect as $allintrkeys=>$allintravals){
   
           if(!empty($myintradayincorrect[$allintrkeys])){	
   	
   
   			/*reshare and like count logic apply here*/
                  $res	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$allintravals->post_id.'" LIMIT 1');
   	        $is_reshared       =$db2->fetch_object($res);
   			$resharesres       =$db2->query('select id  FROM post_reshares WHERE  post_id="'.$allintravals->post_id.'" ');
   			 while($resultres=$this->db2->fetch_object($resharesres))
   			{
   				$wew[]=$resultres;
   			}
   			$resharecnt     =count($wew);
   			$is_likedres  = $db2->query('select *  FROM  post_likes WHERE user_id="'.$this->user->id.'" AND post_id="'.$allintravals->post_id.'" LIMIT 1');;
   		    $is_liked       =$db2->fetch_object($is_likedres);
   			$likesnumberres		 = $db2->query('select count(*) as likecnt  FROM  post_likes WHERE  post_id="'.$allintravals->post_id.'" ');;
   			$likesnumberresdata    =$this->db2->fetch_object($likesnumberres);
   			
   
   			$likes_number     = $likesnumberresdata->likecnt;
   			$is_fav  = $db2->query('select *  FROM  post_favs WHERE user_id="'.$this->user->id.'" AND post_id="'.$allintravals->post_id.'" LIMIT 1');;
   		    $is_favres       =$db2->fetch_object($is_fav);
   			if(!empty($is_favres)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   									$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
   			if($allintravals->avatar !=''){
   				 $src=getAvatarUrl($allintravals->avatar, 'thumbs1');
                  }else{
   				$src = $C->SITE_URL.'storage/avatars/thumbs1/_noavatar_user.gif';
   
   			}
   			$assetdata   =$p->assetdataincoorect($allintravals->post_id);
   			
   			$myincorrectintradatahtml ='';
   			$myincorrectintradatahtml .='<div>'.$allintravals->message.'</div>
   		<table class="table table-bordered" width="100%">
      <thead>
        <tr class="box-sub-title" style="color:white;background-color:#69c6e2;font-size:12px;">
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
   				$css ="#f6fbfc";
   				
   			}else{
   				$css ="#e3f8fe";
   			}
   			if($assetvals->result == "1"){
   				$img ='<img  src="'.$C->SITE_URL.'static/images/tick.png" width="16"/>';
   				
   			}elseif($assetvals->result =="0"){
   				$img ='<img  src="'.$C->SITE_URL.'static/images/wrong.png"/>';
   				
   			}else{
   				$img ='Open';
   				
   			}
   			$myincorrectintradatahtml .='
   			
    
        <tr style="background-color:'.$css.'; color: #66757F; font-size:12px;font-weight:normal;">
          <td>$'.$assetvals->ticker.'</td>
          <td>2130</td>
          <td>'.$assetvals->stoploss_price.'</td>
   	<td>'.$assetvals->predicted_price.'</td>
   	<td>'.$assetvals->current_price.'</td>
   
   	<td>'.$img.'</td>
        </tr>
        
      
   
   			
   			';
   			
   		}
   		$myincorrectintradatahtml .='</tbody>
    </table>';		
   
   
   
   			  $buff->post_type='public';
   			    if($likes_number > 0){					
   				$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.$likes_number.'</a>';
   			   }else{
   				 $showlikes_btn ='';  
   			   }
   			   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="UnRebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                      if($resharecnt > 0){					
   
   				$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.$resharecnt.'</a>';
                      }else{
   					$resharecnt ='';
   					
   				}
   				$is_agree = $buff->is_post_agree($this->user->id,$allintravals->post_id);
   				$is_agree_cnt = $buff->is_post_agree_cnt($allintravals->post_id);
   				if($is_agree_cnt->cnt > 0){					
   				$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
   			   }else{
   				 $showagreebtn_btn ='';  
   			   }
             				/* end reshare and like count logic apply here*/
             				
             				if($if_can_delete =='1' ||($this->user->id ==$allintravals->user_id) ){
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				}
                  $is_spam  = $post->is_spam($allintravals->post_id,"public");
   			if($is_spam =="1"){
   					$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Unmarkspam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                  }else{
   		          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Markspam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                  }
				  $eventtypeqweqw =10;	
          $replies                =$buff->checkreplies($allintravals->post_id);
		 $groups                =$buff->getgroupname($allintravals->group_id);
		if(!empty($replies)){
			$individual = '<a style="cursor:pointer;" class="author bizcard" onclick="replaycontent('.$replies->alternate_parent_id.','.$allintravals->post_id.','.$eventtypeqweqw.')" data-userid="'.$replies->userid.'">Replies to @'. $replies->handler .'</a>' ;
		   $replayhtml ='<a style="cursor:pointer" onclick="childpopuptimeline('.$replies->parent_id.','.$replies->replay_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}else{
			$individual ='';
			$replayhtml ='<a style="cursor:pointer" onclick="parentreplay('.$allintravals->post_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';

		}
		if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
		}
   									
             						
   
   	                
   	$myintradayincorrectintrahtml .='
   	
   	 <div class="activity no-comments 	replayhide-'.$allintravals->post_id.'" id="myintraincorrect'.$allintravals->post_id.'">
	    	 <div id="incorrectjannesh'.$allintravals->post_id.'"></div>

   <a href="'.userlink($allintravals->username).'" class="avatar bizcard" data-userid="'.$allintravals->user_id.'"><img src="'.$src.'"  /></a>	
   <div class="activity-container">
   	<div class="activity-header">
   		<a href="'.userlink($allintravals->username).'" class="author bizcard" data-userid="'.$allintravals->user_id.'">'. ($allintravals->username) .'</a>
   		<div class="meta-info">'.$individual.''.$grp.'
   			
   			
   		</div>
   		<div class="activity-options">'.$delete .''.$fav.'	
   		</div>
   	</div>
   	<div class="activity-content"></div>
   	<div><div class="attachments lightbox-enabled">
   
   
   
          </div></div>
   	<div class="activity-poll">'.$myincorrectintradatahtml.'</div>
   	<div class="footer1 activity-footer meta-info">  </div>
   </div>
   
   
   <div class="clear"></div><div id="replaypopup10-'.$allintravals->post_id.'" class="modal fade" ></div>

   
   <div>
   <div class="activity-footer meta-info">
   			<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$allintravals->post_id.'" class="permlink">'.post::parse_date($allintravals->date).'</a>
   			'.$replayhtml.'
			<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
   		<div class="dropdown">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
   							  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Linkedin</a></li>
   							  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'  target="_blank" >StumbleUpon</a></li>
   							  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'  target="_blank" >MySpace</a></li>
   							  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'  target="_blank" >FriendFeed</a></li>
   							  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Google Plus</a></li>
   							   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
   							  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
   						   </ul>
   						</div>
                        <div class="like-list">'.$mark_content.'</div>							
   			</div>
   			<div class="comment-chield" style="display:none" id="chield'.$allintravals->post_id.'">
   			<div class="comments-editor data-content-placeholder" data-token="069fc5555">
   			<div>
   			
   			<div class="activity-header commentpost'.$allintravals->post_id.'">
   			<a href="'.$C->SITE_URL.''.$allintravals->username.'" class="author bizcard" data-userid="'.$allintravals->user_id.'">'.$allintravals->username.'</a>
   			
   			</div>
   		
   				</div>
   			</div></div>';
						   //this for subreplay checking 
        $replies                =$buff->checkreplies($allintravals->post_id);
    if(empty($replies)){
		$chield  = $buff->is_chield_replay($allintravals->post_id);
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);
		$incorrectstr .=$allintravals->post_id;
		$D->incorrectstr =$incorrectstr;

	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 10;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" 
       style="border:0px solid grey;"> 
        <a style=" cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$myeventres->post_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
			}else{
				$replycont ='';
				
			}
			$groups = $buff->getgroupname($chield[$m]->group_id);
			if(!empty($groups)){
			$grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
		}else{
			$grp ='';
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
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   //markcontent
				    $is_spam  = $buff->is_spam($chield[$m]->id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				if(($j-1)==$m){
					$css="tree1";
					$childclass="incorrectchild".$allintravals->post_id;
					
					
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
					$finalcon ='';
					$finalcon .= '<div class="title"><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/event.png"> <a href="'.$C->SITE_URL.'plugin/events/view/id:'.$eventdetails->id.'/postid:'.$eventdetails->post_id.'"  class="event-list-title"><strong>Event Name:</strong> '.$eventdetails->event_name.'</a></div>';
                    $finalcon .= '<span class="event-list-heading">Location:</span> <span class="event-list-txt">'.$eventdetails->address.'</span><br />';      
					if(!empty($eventdetails->url)){

					$finalcon .='<span class="event-list-heading">URL:</span> <span class="event-list-txt"><a href="'.$eventdetails->url.'"  target="_blank">'.$eventdetails->url.'</a></span><br />';						
                    }
					$time =$eventdetails->start_date.' '.$eventdetails->start_time;
					$date_time = date("M d,Y h:i:s A", strtotime($time));

					
                    $finalcon .='<span class="event-list-heading">Date and Time:</span> <span class="event-list-txt">'.$date_time.'</span><br />	
						';
					if(!empty($eventdetails->tag_name)){
						$hastagarr        =explode("#",trim($hastag));
			            $strret_arr       =array_filter($hastagarr);

						foreach($strret_arr as $keys=>$vals){
											if($keys ==1){
												$finalcon .='<span><a href="'.$C->SITE_URL.'/search/tab:tags/s:'.$vals.'"><strong>#'.$vals.'</strong></a>';
											}else{
												$finalcon .='<strong>#'.$vals.'</strong></span>';
											}
											
						}
						$finalcon .='<span class="event-list-heading">Hash Tags:</span> <span class="event-list-txt">'.$finalcon.'</span><br />
						';	
                    }
					if($eventdetails->status ==1){
						$st ="Active";
						
					}else{
						$st ="Cancelled";
						
					}
					if($user->id != $eventdetails->admin_id){
					if($eventdetails->event_status !=2  ){
						
						if( ($eventdetails->event_status =='' &&  $eventdetails->edit_status =='')   ){
						$finalcon .='<div id="acc-'.$eventdetails->post_id.'"><strong>User Response:</strong><input type="radio"  class="accept" name="accept" onclick="myFunction('.$eventdetails->post_id.'1)" value="'.$eventdetails->post_id.'-1">Accept<input type="radio" class="accept" name="accept" onclick="myFunction('.$eventdetails->post_id.'3)"  value="'.$eventdetails->post_id.'-3">Reject</div>';
					    
					}
					if($eventdetails->event_status==1){
						$display ="block";
						
					}else{
						$display ="none";
					}
					if($eventdetails->event_status==3){
						$displayreject ="block";
						
					}else{
						$displayreject ="none";
					}
					
					if( ($eventdetails->event_status !=2 ||  $eventdetails->edit_status!=4) &&  ($eventdetails->event_status !=2 &&   $eventdetails->edit_status!=4) ){
					
					
					$finalcon .='<div style="display:'.$display.';"id="accept-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					$finalcon .='<div style="display:'.$displayreject.';"id="reject-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
                  
					$finalcon .='<input type="hidden" id="attach-'.$eventdetails->post_id.'"  value="'.$eventdetails->attachment_id.'">';
                    }else{
						if((($eventdetails->event_status!=4) &&   ($eventdetails->edit_status==4))){
							
					$finalcon .='<div style="display:'.$display.';"id="accept-'.$eventdetails->post_id.'"><strong>User Response:</strong>Event Accepted</div>';
					$finalcon .='<div style="display:'.$displayreject.';"id="reject-'.$link->post_id.'"><strong>User Response:</strong>Event Rejected</div>';
                  
							
						}else{
						$finalcon .='<div>This event was no longer available.</div>';
						}
					}
					if($eventdetails->event_status == 5){
						$finalcon .='<div>This event was modified.</div>';
						
					}
						$finalcon .='<div>Status:'.$st.'</div>';

					
					
					
					}else{
						
						$finalcon .='<div>Event Cancelled</div>';

						
					}
					}else{
						if( $eventdetails->event_status!=2 || $eventdetails->event_status!=4 || $eventdetails->event_status!=5  ){
						$finalcon .='<div><strong>Status:</strong>'.$st.'</div>';

						$finalcon .='<div><a href="'.$C->SITE_URL.'dashboard?pid='.$link->post_id.'"><input class="btn-download-results" type="button" name="download" value="Download Results"></a></div>';

						}else{
							if($eventdetails->status == 2 && $eventdetails->edit_status!=4 ){
						$finalcon .='<div><strong>status:</strong>Cancel</div>';

								
							}else{
							$finalcon .='<div><strong>Status:</strong>'.$st.'</div>';

							$finalcon .='<div>This event was no longer available.</div>';

							}

							
						}
						
					}
					

					$mes = $finalcon;
						
					
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
	$message .='<a id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'"><button  class="btn-download-results">Download Results</button></a>';
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


		 
	 

    $myintradayincorrectintrahtml .='<div>
            <div class="activity no-comments commentcontainer '.$childclass.'">
               <!-- start Parent -->
			   <ul class="'.$css.'">
			<li>
               <div class="row" style="padding:0px 10px;">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green" >
                     <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden"><a  href="'.userlink($chield[$m]->username).'"  class="avatar bizcard" data-userid="'.$chield[$m]->userid.'"><img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" /></a>
                     </div>
                    <div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">
                        <div class="activity-container">
                           <div class="activity-header col-md-12"><a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>
                           </div>
						   	<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">

						   <div class="meta-info"> '.$grp.'
						   </div>
   			
   			
   		                       </div>
                           <div class="activity-content">'.$mes.'</div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                           <div class="activity-footer meta-info">
   			<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$chield[$m]->id.'" class="permlink">'.post::parse_date($chield[$m]->date).'</a>
   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($chield[$m]).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$allintravals->post_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
   			<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$myeventres->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
   		<div class="dropdown">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
   							  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'&source='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Linkedin</a></li>
   							  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >StumbleUpon</a></li>
   							  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'  target="_blank" >MySpace</a></li>
   							  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'  target="_blank" >FriendFeed</a></li>
   							  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Google Plus</a></li>
   							   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Twitter</a></li>
   							  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$chield[$m]->message)).'"  target="_blank" >Facebook</a></li>
   						   </ul>
   						</div>
                        <div class="like-list">'.$mark_content.'</div>
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
  
   $myintradayincorrectintrahtml .='</div>
   <div>
   
   </div>
   </div>
      <br />
	  <style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#incorrectjannesh'.$allintravals->post_id.'{
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#incorrectjannesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#incorrectjannesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#incorrectjannesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#incorrectjannesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#incorrectjannesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>

   	';
   	
   }
   }
   /*end myintraday incorrect tab */
 

   
   
   
     $tpl->layout->setVar('myintradaycorrectintrahtml', $myintradaycorrectintrahtml);
     $tpl->layout->setVar('myintradayincorrectintrahtml', $myintradayincorrectintrahtml);
   
   
   	$tpl->layout->setVar('allintrahtml', $allintrahtml);
   	$tpl->layout->setVar('followintrahtml', $followintrahtml);
   
   $tpl->layout->useBlock('intradata');
   }
   
   
   $tpl->layout->block->save('main_content');
   
   
   
   $tpl->display();
   
   ?>