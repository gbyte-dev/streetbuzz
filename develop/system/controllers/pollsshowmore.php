<?php
	error_reporting(0);
	if( !$this->user->is_logged ) {
		$this->redirect('home');
	}
	
	$this->load_langfile('inside/global.php');
	$data =array();
		$obj=$data[0];
	$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);

	if($_POST['type'] =="poll_all"){
		$from    = $_POST['allshowcount']+1;
		$upto = 10;
		

		$pollallres            =$db2->query('SELECT p.*,u.* ,ps.date as postdate FROM `post_userbox` as pu inner join polls as p ON pu.post_id = p.posts_id 
		inner join posts as ps ON ps.id=p.posts_id
		inner join users as u ON u.id = ps.user_id
		left join post_poll_votes as ppv ON ppv.poll_id = p.poll_id
		left join polls_answers as pa ON pa.poll_id=ppv.poll_id
		

          WHERE pu.user_id = "'.$this->user->id.'" group by p.poll_id order by p.poll_id desc LIMIT '.$from.' ,'.$upto.' ' );;
		  	   	$poll_all_html='';

	   while($pollallresfetch = $db2->fetch_object($pollallres)){
		$checkanswer = $db2->query('select * from post_poll_votes where poll_id="'.$pollallresfetch->poll_id.'" LIMIT 1');
		 $checkres             =$db2->fetch_object($checkanswer);
 if($pollallresfetch->avatar !=''){
	 $src=getAvatarUrl($pollallresfetch->avatar, 'thumbs1');
	 
 }else{
	 $src = $C->SITE_URL.'storage/avatars/thumbs1/_noavatar_user.gif';
 }
   if(($this->user->id == $pollallresfetch->id )){
   
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$pollallresfetch->posts_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" > <img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				}
   		   $is_favres       =$buff->isfav($this->user->id,$pollallresfetch->posts_id);
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
		//Like content
				$is_likedreplay     = $buff->new_liked($pollallresfetch->posts_id);
			    $likes_numbers       =$buff->new_liked_count($pollallresfetch->posts_id);
				$likes_number        =$likes_numbers->likecount;
				
               	$post_type='public';
                if($likes_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'">'.$likes_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
		//agree content
				$is_agree = $buff->is_post_agree($this->user->id,$pollallresfetch->posts_id);
				$is_agree_cnt = $buff->is_post_agree_cnt($pollallresfetch->posts_id);
					if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   //markcontent
				    $is_spam  = $buff->is_spam($pollallresfetch->posts_id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				$is_replayreshares     = $buff->new_reshared($pollallresfetch->posts_id);
			    $likes_reshares       =$buff->new_reshare_count($pollallresfetch->posts_id);
				$sharecount        =$likes_reshares->sharecount;
				$post_type ="public";
				$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_replayreshares? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'">'.($is_replayreshares? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                    if($sharecount > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'">'.$sharecount.'</a>';
                    }else{
						$resharecnt ='';
						
					}
					//markcontent
				    $is_spam  = $buff->is_spam($pollallresfetch->posts_id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
		 
		 
/*********************** Start : All Polls Showmore ****************************/	

 
	   $poll_all_html .='
	   <div class="activity no-comments" id="allpollshow'.$pollallresfetch->posts_id.'">

<!-- start Parent -->
<div class="row start'.$pollallresfetch->posts_id.'" style="border:0px solid red; margin:0px; ">
 <div id="allpollshowjaneesh'.$pollallresfetch->posts_id.'"></div>
   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">

   <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden">

<a href="'.userlink($pollallresfetch->username).'" class="avatar bizcard" data-userid="'.$pollallresfetch->id.'"><img src="'.$src.'"  /></a>

 </div><!--/ end : col-md-1 -->

<div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">

<!--/ start : activity container --> 	
	<div class="activity-container">
		<div class="activity-header">
			<a href="'.userlink($pollallresfetch->username).'" class="author bizcard" data-userid="'.$pollallresfetch->id.'">'. ($pollallresfetch->username) .'</a>
			<div class="meta-info">'.$individual.''.$grp.'
				
				
			</div>
  <div class="activity-options">'.$delete.''.$fav.'
		</div>		</div>




<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'view/post:'.$pollallresfetch->post_id.'" class="permlink">'.post::parse_date($pollallresfetch->postdate).' <span class="glyphicon glyphicon-link"></span></a>
</div>




		<div class="activity-content"></div>
		<div class="activity-poll"><div class="attachments lightbox-enabled">';
		$url =$C->SITE_URL;
		$eventtype = 1;
		$poll_all_html .=$buff->notificationpollhtml($pollallresfetch->posts_id,$url,$eventtype);
		
	
	
	$poll_all_html .='<div class="activity-poll-option"><span></span></div>
</div></div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	<div id="replaypopup4-'.$pollallresfetch->posts_id.'" class="modal fade" ></div>

	<!-- <div class="comments-thread-container " data-value="{&quot;activities_type&quot;:&quot;public&quot;,&quot;activities_id&quot;:&quot;311&quot;}">
		
		<div class="comments-editor-field offset_comment"><a href="#" class="additionalcomment" data-action="activityAddComment" data-namespace="comments"  data-role="services"> <span class="glyphicon glyphicon-comment"></span> Write a comment ...</a></div>
	</div>  -->
	<div>
	<div class="activity-footer meta-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
				'.$replayhtml.'
				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_likedreplay? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'">'.($is_likedreplay? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollallresfetch->posts_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
              			<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>

				<div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$pollallresfetch->posts_id.'').'&title='.urlencode(htmlspecialchars($pollallresfetch->poll_question)).'&source='.urlencode($C->SITE_URL.'view/post:'.$pollallresfetch->posts_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$pollallresfetch->posts_id.'').'"  target="_blank" >Linkedin</a></li>
								  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$pollallresfetch->posts_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$pollallresfetch->posts_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$pollallresfetch->poll_question)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$pollallresfetch->posts_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$pollallresfetch->poll_question)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
      							
				</div>

</div><!--/ end : col-md-11 -->  
</div><!--/ end : col-md-12 -->
				';
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
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" style="border:0px solid grey;"><a style="cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$pollallresfetch->posts_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
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
				$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_replayreshares? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_replayreshares? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Buzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Buzz"/>').'</a>';
                    if($sharecount > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.$sharecount.'</a>';
                    }else{
						$resharecnt ='';
						
					}
				//agree content
				$is_agree = $buff->is_post_agree($this->user->id,$chield[$m]->id);
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
				if(($j-1)==$m){
					$css="tree1";
					$childclass="allpollchild".$pollallresfetch->posts_id;
					
					
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
					$poll  = $buff->replay_is_poll($chield[$m]->id);
					$pollanswer=$buff->is_pollanswer($user->id,$poll[0]->poll_id);
				$url =$C->SITE_URL;
               $eventtype = 1; 				
            $mes = $buff->notificationpollchildhtml($chield[$m]->id,$url,$eventtype);
					
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
                 <div class="row" style="padding:0px 4px;">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">
                     <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden"><a  href="'.userlink($chield[$m]->username).'"  class="avatar bizcard" data-userid="'.$chield[$m]->userid.'"><img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" /></a>
                     </div>
                     <div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">
                        <div class="activity-container">
                           <div class="activity-header col-md-12"><a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>

                           	<div class="activity-options">'.$delete.''.$fav.'</div>

                           </div>




<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'view/post:'.$chield[$m]->id.'" class="permlink">'.post::parse_date($chield[$m]->date).' <span class="glyphicon glyphicon-link"></span></a>
</div>




						   <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">

						   <div class="meta-info"> '.$grp.'
						   </div>
						   

   			
   			
   		                       </div>
                           <div class="activity-content">'.$mes.'</div>
                        </div>
                        <div></div>
                       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                           <div class="activity-footer meta-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($chield[$m]).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$pollallresfetch->posts_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
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
	$poll_all_html .='</div><!--/ end : activity container --> 
</div><!--/ end : parent -->
</div><!-- end : activity no-comments-->
<script>
var main = $("#allpollshow'.$pollallresfetch->posts_id.'").height();
var child = $(".allpollchild'.$pollallresfetch->posts_id.'").height();
var final = Number(main)-Number(child);
$("#allpollshowjaneesh'.$pollallresfetch->posts_id.'").css("height",final);
</script>
<style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#allpollshowjaneesh'.$pollallresfetch->posts_id.'{
z-index:100;
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#allpollshowjaneesh'.$pollallresfetch->posts_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#allpollshowjaneesh'.$pollallresfetch->posts_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#allpollshowjaneesh'.$pollallresfetch->posts_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#allpollshowjaneesh'.$pollallresfetch->posts_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#allpollshowjaneesh'.$pollallresfetch->posts_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>


';
	   
	}
	echo $poll_all_html;
	}

/*********************** End : All Polls Showmore ****************************/






/*********************** Start : My Polls Showmore ****************************/

		if($_POST['type'] =="mypoll_all"){
			$from    = $_POST['mypollshowcount']+1;
		$upto = 10;
		
			$pollmyres           = $db2->query('SELECT ps.*,u.*,p.date as postdate FROM `posts` as p inner join polls as ps ON p.id = ps.posts_id 
		inner join users as u ON u.id=p.user_id
		

          WHERE p.user_id = "'.$this->user->id.'" group by ps.poll_id order by ps.poll_id desc LIMIT '.$from.' ,'.$upto.' '
				   

	   );
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
  if(($this->user->id == $pollmyresfetch->id )){
   
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$pollmyresfetch->posts_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" > <img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				}
   		   $is_favres       =$buff->isfav($this->user->id,$pollmyresfetch->posts_id);
 		if(!empty($is_favres)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$pollmyresfetch->posts_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   									$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$pollmyresfetch->posts_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
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
		//Like content
				$is_likedreplay     = $buff->new_liked($pollmyresfetch->posts_id);
			    $likes_numbers       =$buff->new_liked_count($pollmyresfetch->posts_id);
				$likes_number        =$likes_numbers->likecount;
				
               	$post_type='public';
                if($likes_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$pollmyresfetch->posts_id.'"}').'">'.$likes_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
		//agree content
				$is_agree = $buff->is_post_agree($this->user->id,$pollmyresfetch->posts_id);
				$is_agree_cnt = $buff->is_post_agree_cnt($pollmyresfetch->posts_id);
					if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresfetch->posts_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   //markcontent
				    $is_spam  = $buff->is_spam($pollmyresfetch->posts_id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresfetch->posts_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresfetch->posts_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				$is_replayreshares     = $buff->new_reshared($pollmyresfetch->posts_id);
			    $likes_reshares       =$buff->new_reshare_count($pollmyresfetch->posts_id);
				$sharecount        =$likes_reshares->sharecount;
				$post_type ="public";
				$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_replayreshares? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$pollmyresfetch->posts_id.'"}').'">'.($is_replayreshares? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                    if($sharecount > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$pollmyresfetch->posts_id.'"}').'">'.$sharecount.'</a>';
                    }else{
						$resharecnt ='';
						
					}
					//markcontent
				    $is_spam  = $buff->is_spam($pollmyresfetch->posts_id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresfetch->posts_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresfetch->posts_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
		 
		 
			
 
	   //print_r($pollallresfetch);
	   $poll_my_html .='<div class="activity no-comments" id="mypollshow'.$pollmyresfetch->posts_id.'">

<!-- start Parent -->
<div class="row start'.$pollmyresfetch->id.'" style="border:0px solid red; margin:0px; ">
<div id="mypollshowjanessh'.$pollmyresfetch->posts_id.'"></div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">

   <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden">


<a href="'.userlink($pollmyresfetch->username).'" class="avatar bizcard" data-userid="'.$pollmyresfetch->id.'"><img src="'.$src.'"  /></a>	

</div><!--/ end : col-md-1 -->
	
<div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">

<!--/ start : activity container --> <div class="activity-container">

		<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<a href="'.userlink($pollmyresfetch->username).'" class="author bizcard" data-userid="'.$pollmyresfetch->id.'">'. ($pollmyresfetch->username) .'</a>
			<div class="meta-info">'.$individual.''.$grp.'
				
				
			</div>
<div class="activity-options">'.$delete.''.$fav.'
		</div>		</div>





<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'view/post:'.$pollmyresfetch->post_id.'" class="permlink">'.post::parse_date($pollmyresfetch->postdate).' <span class="glyphicon glyphicon-link"></span></a>
</div>





		<div class="activity-content"></div>
		

		<div class="activity-poll"><div class="attachments lightbox-enabled">';
	$url =$C->SITE_URL;
	$eventtype = 2;
	$poll_my_html .=$buff->notificationpollhtml($pollmyresfetch->posts_id,$url,$eventtype);
	   
	
	$poll_my_html .='<div class="activity-poll-option"><span></span></div>
</div></div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	   <div class="clear"></div>

	   <div id="replaypopup5-'.$pollmyresfetch->posts_id.'" class="modal fade" ></div>

	<!-- <div class="comments-thread-container " data-value="{&quot;activities_type&quot;:&quot;public&quot;,&quot;activities_id&quot;:&quot;311&quot;}">
		
		<div class="comments-editor-field offset_comment"><a href="#" class="additionalcomment" data-action="activityAddComment" data-namespace="comments"  data-role="services"> <span class="glyphicon glyphicon-comment"></span> Write a comment ...</a></div>
	</div>  -->
	<div>
	<div class="activity-footer meta-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
				'.$replayhtml.'
				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_likedreplay? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresfetch->posts_id.'"}').'">'.($is_likedreplay? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresfetch->posts_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>
				<div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$pollmyresfetch->posts_id.'').'&title='.urlencode(htmlspecialchars($pollmyresfetch->poll_question)).'&source='.urlencode($C->SITE_URL.'view/post:'.$pollmyresfetch->posts_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$pollmyresfetch->posts_id.'').'"  target="_blank" >Linkedin</a></li>
								 <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$pollmyresfetch->posts_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$pollmyresfetch->posts_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$pollmyresfetch->poll_question)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$pollmyresfetch->posts_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$pollmyresfetch->poll_question)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>	
			


   			</div>

        </div><!--/ end : col-md-11 -->  
</div><!--/ end : col-md-12 -->
			';
										   //this for subreplay checking 
        $replies                =$buff->checkreplies($pollmyresfetch->posts_id);
    if(empty($replies)){
		$even ="poll";

		$chield  = $buff->is_notificationchield_replay($pollmyresfetch->posts_id,$even);
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);

	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 5;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" style="border:0px solid grey;"><a style="cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$pollmyresfetch->posts_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
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
				$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_replayreshares? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_replayreshares? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                    if($sharecount > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.$sharecount.'</a>';
                    }else{
						$resharecnt ='';
						
					}
				//agree content
				$is_agree = $buff->is_post_agree($this->user->id,$chield[$m]->id);
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
				if(($j-1)==$m){
					$css="tree1";
					$childclass="mypollshowchild".$pollmyresfetch->posts_id;
					
					
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
		        $url =$C->SITE_URL;
				$eventtype = 2;
                $mes = $buff->notificationpollchildhtml($chield[$m]->id,$url,$eventtype);
					
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
            <div class="activity no-comments commentcontainer '.$childclass.'" style="border: 0px solid #E1E8ED;">
               <!-- start Parent -->
			   <ul class="'.$css.'">
			<li>
              <div class="row" style="padding:0px 4px;">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">
                   <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden"><a  href="'.userlink($chield[$m]->username).'"  class="avatar bizcard" data-userid="'.$chield[$m]->userid.'"><img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" /></a>
                     </div>
                   <div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">
                        <div class="activity-container">
                        <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>

                        	<div class="activity-options">'.$delete.''.$fav.'</div>

                           </div>




<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'view/post:'.$chield[$m]->id.'" class="permlink">'.post::parse_date($chield[$m]->date).' <span class="glyphicon glyphicon-link"></span></a>
</div>




						   	<div class="activity-header">

						   <div class="meta-info"> '.$grp.'
						   </div>
						   

   			
   			
   		                       </div>
                           <div class="activity-content">'.$mes.'</div>
                        </div>
                       
                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                           <div class="activity-footer meta-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($chield[$m]).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$pollmyresfetch->posts_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
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
            </div></div>
         ';
	 }
	 }
  }
	$poll_my_html .='</div><!--/ end : activity container --> 
</div><!--/ end : parent -->
</div><!-- end : activity no-comments-->
<script>
var main = $("#mypollshow'.$pollmyresfetch->posts_id.'").height();
var child = $(".mypollshowchild'.$pollmyresfetch->posts_id.'").height();
var final = Number(main)-Number(child);
$("#mypollshowjanessh'.$pollmyresfetch->posts_id.'").css("height",final);
</script>
<style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#mypollshowjanessh'.$pollmyresfetch->posts_id.'{
z-index:100;
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#mypollshowjanessh'.$pollmyresfetch->posts_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#mypollshowjanessh'.$pollmyresfetch->posts_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#mypollshowjanessh'.$pollmyresfetch->posts_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#mypollshowjanessh'.$pollmyresfetch->posts_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#mypollshowjanessh'.$pollmyresfetch->posts_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>


';
	   
	}
	echo $poll_my_html;
		}


/*********************** End : My Polls Showmore ****************************/



/*********************** Start : My Responses Polls Showmore ************************/



				if($_POST['type'] =="myresponsepoll_all"){
					$from    = $_POST['myresponseshowcount']+1;
		$upto = 10;
		
					$pollmyresponse           = $db2->query('SELECT ps.*,u.*,p.date as postdate FROM `posts` as p inner join polls as ps ON p.id = ps.posts_id 
		inner join  post_poll_votes as ppu ON ppu.POLL_ID=ps.poll_id
		inner join users as u on u.id = ppu.VOTER_USER_ID

          WHERE ppu.VOTER_USER_ID	 = "'.$this->user->id.'" group by ps.poll_id order by ps.poll_id desc LIMIT  '.$from.' ,'.$upto.' 
				   

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
  if(($this->user->id == $pollmyresponsefetch->id )){
   
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" > <img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				}
   		   $is_favres       =$buff->isfav($this->user->id,$pollmyresponsefetch->posts_id);
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
		//Like content
				$is_likedreplay     = $buff->new_liked($pollmyresponsefetch->posts_id);
			    $likes_numbers       =$buff->new_liked_count($pollmyresponsefetch->posts_id);
				$likes_number        =$likes_numbers->likecount;
				
               	$post_type='public';
                if($likes_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'">'.$likes_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
		//agree content
				$is_agree = $buff->is_post_agree($this->user->id,$pollmyresponsefetch->posts_id);
				$is_agree_cnt = $buff->is_post_agree_cnt($pollmyresponsefetch->posts_id);
					if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
				   //markcontent
				    $is_spam  = $buff->is_spam($pollmyresponsefetch->posts_id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				$is_replayreshares     = $buff->new_reshared($pollmyresponsefetch->posts_id);
			    $likes_reshares       =$buff->new_reshare_count($pollmyresponsefetch->posts_id);
				$sharecount        =$likes_reshares->sharecount;
				$post_type ="public";
				$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_replayreshares? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'">'.($is_replayreshares? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                    if($sharecount > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'">'.$sharecount.'</a>';
                    }else{
						$resharecnt ='';
						
					}
		 
		 
			
 
	   //print_r($pollallresfetch);
	   $poll_myresponse_html .='

	   <div class="activity no-comments" id="myresponse'.$pollmyresponsefetch->posts_id.'">

<!-- start Parent -->
<div class="row start'.$pollmyresponsefetch->id.'" style="border:0px solid red; margin:0px; padding:0px;">

<div id="myresponsejaneesh'.$pollmyresponsefetch->posts_id.'"></div>

   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">

   <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden">

<a href="'.userlink($pollmyresponsefetch->username).'" class="avatar bizcard" data-userid="'.$pollmyresponsefetch->id.'"><img src="'.$src.'"  /></a>

  </div><!--/ end : col-md-1 -->

     <div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">	
	
	<div class="activity-container">
	<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<a href="'.userlink($pollmyresponsefetch->username).'" class="author bizcard" data-userid="'.$pollmyresponsefetch->id.'">'. ($pollmyresponsefetch->username) .'</a>
			<div class="meta-info">'.$individual.''.$grp.'
				
				
			</div>
<div class="activity-options">'.$delete.''.$fav.'
		</div>		</div>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'view/post:'.$pollmyresponsefetch->post_id.'" class="permlink">'.post::parse_date($pollmyresponsefetch->postdate).' <span class="glyphicon glyphicon-link"></span></a>
</div>





		<div class="activity-content"></div>
	

		<div class="activity-poll"><div class="attachments lightbox-enabled">';
		$url = $C->SITE_URL;
		$eventtype =  3;
		$poll_myresponse_html .=$buff->notificationpollhtml($pollmyresponsefetch->posts_id,$url,$eventtype);
	  
	
	$poll_myresponse_html .='<div class="activity-poll-option"><span></span></div>
</div></div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>

	   <div id="replaypopup6-'.$pollmyresponsefetch->posts_id.'" class="modal fade" ></div>

	<!-- <div class="comments-thread-container " data-value="{&quot;activities_type&quot;:&quot;public&quot;,&quot;activities_id&quot;:&quot;311&quot;}">
		
		<div class="comments-editor-field offset_comment"><a href="#" class="additionalcomment" data-action="activityAddComment" data-namespace="comments"  data-role="services"> <span class="glyphicon glyphicon-comment"></span> Write a comment ...</a></div>
	</div>  -->
	<div>

	<div class="activity-footer meta-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
				'.$replayhtml.'
				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_likedreplay? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'">'.($is_likedreplay? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>
				<div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$pollmyresponsefetch->posts_id.'').'&title='.urlencode(htmlspecialchars($pollmyresponsefetch->poll_question)).'&source='.urlencode($C->SITE_URL.'view/post:'.$pollmyresponsefetch->posts_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$pollmyresponsefetch->posts_id.'').'"  target="_blank" >Linkedin</a></li>
								<li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$pollmyresponsefetch->posts_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$pollmyresponsefetch->posts_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$pollmyresponsefetch->poll_question)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$pollmyresponsefetch->posts_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$pollmyresponsefetch->poll_question)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
							
           
              
        </div>

        </div><!--/ end : col-md-11 -->  
</div><!--/ end : col-md-12 -->';
										   //this for subreplay checking 
     $replies                =$buff->checkreplies($pollmyresponsefetch->posts_id);
    if(empty($replies)){
		$event ="poll";
		$chield  = $buff->is_notificationchield_replay($pollmyresponsefetch->posts_id,$even);
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);

	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 6;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" style="border:0px solid grey;"><a style="cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$pollmyresponsefetch->posts_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
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
				if(($j-1)==$m){
					$css="tree1";
					$childclass="myresponsechild".$pollmyresponsefetch->posts_id;
					
					
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
	$url=$C->SITE_URL;
	$eventtype = 3;
$mes = $buff->notificationpollchildhtml($chield[$m]->id,$url,$eventtype);
					
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


		   
   
   	       $poll_myresponse_html .='
            <div class="activity no-comments commentcontainer '.$childclass.'" style="border: 0px solid #E1E8ED;">

               <!-- start Parent -->
			   <ul class="'.$css.'">
			<li>
            <div class="row" style="padding:0px 4px;">

                 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">
             
              <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden">
              <a  href="'.userlink($chield[$m]->username).'"  class="avatar bizcard" data-userid="'.$chield[$m]->userid.'"><img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" /></a>
                     </div>

                   <div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">
                        <div class="activity-container">
                       <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
                       <a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'
                       </a>

                       	<div class="activity-options">'.$delete.''.$fav.'</div>
                           </div>

						   	<div class="activity-header">

						   <div class="meta-info"> '.$grp.'
						   </div>
						   </div>



<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'view/post:'.$chield[$m]->id.'" class="permlink">'.post::parse_date($chield[$m]->date).' <span class="glyphicon glyphicon-link"></span></a>
</div>




                           <div class="activity-content">'.$mes.'</div>
                        </div>
                      
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                           <div class="activity-footer meta-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($chield[$m]->date).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$pollmyresponsefetch->posts_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
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
            </div>';
	 }
	 }
  }
	$poll_myresponse_html .='</div><!--/ end : activity container --> 
</div><!--/ end : parent -->
</div><!-- end : activity no-comments-->



<script>
var main = $("#myresponse'.$pollmyresponsefetch->posts_id.'").height();
var child = $(".myresponsechild'.$pollmyresponsefetch->posts_id.'").height();
var final = Number(main)-Number(child);
$("#myresponsejaneesh'.$pollmyresponsefetch->posts_id.'").css("height",final);
</script>
<style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#myresponsejaneesh'.$pollmyresponsefetch->posts_id.'{
z-index:100;
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#myresponsejaneesh'.$pollmyresponsefetch->posts_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#myresponsejaneesh'.$pollmyresponsefetch->posts_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#myresponsejaneesh'.$pollmyresponsefetch->posts_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#myresponsejaneesh'.$pollmyresponsefetch->posts_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#myresponsejaneesh'.$pollmyresponsefetch->posts_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>

';
	   
	}
	echo $poll_myresponse_html;
				}
	
/*********************** End : My Responses Polls Showmore ************************/		
	
?>