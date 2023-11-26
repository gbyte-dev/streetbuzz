<?php
	error_reporting(0);
	if( !$this->user->is_logged ) {
		$this->redirect('home');
	}
	
	$this->load_langfile('inside/global.php');
	$p = new post();
	$data =array();
		$obj=$data[0];
	$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);
	if($_POST['type'] =='all_intraday'){
		 $offset              =$_POST['allintrashowcnt'];
		 $allintradays   =$p->allintraday($this->user->id,$offset);
		 
		 /*intraday all Tab*/
		$allintrahtml='';
		if(!empty($allintradays)){
	    foreach($allintradays as $allintrkeys=>$allintravals){


 	

				/*reshare and like count logic apply here*/
                $res	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$allintravals->post_id.'" LIMIT 1');
		        $is_reshared       =$db2->fetch_object($res);
				$resharesres       =$db2->query('select count(id) as resharecnt  FROM post_reshares WHERE  post_id="'.$allintravals->post_id.'" ');
				 $resultres=$this->db2->fetch_object($resharesres);
				
				$resharecnt     = $resultres->resharecnt;
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
					$is_agree = $p->is_post_agree($this->user->id,$allintravals->post_id);
					$is_agree_cnt = $p->is_post_agree_cnt($allintravals->post_id);
					if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
           				/* end reshare and like count logic apply here*/
           				$time = ($allintravals->date);
						$res2  =date("Y-m-d h:i:s", $time);
						$pre   =date('Y-m-d h:i:s');
						$datetime1 = strtotime($pre);
						$datetime2 = strtotime($res2);
						$interval  = abs($datetime2 - $datetime1);
						$minutes   = round($interval / 60);
           				if(($this->user->id ==$allintravals->userid) && trim($minutes) < 5 ){
           				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
						}else{
           				$delete ='';
           				}
           				
                $is_spam  = $p->is_spam($allintravals->post_id,"public");
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
   							
										
 /********************* Start :  All Intraday  **************************/          						
 		                
		$allintrahtml .='
		
		 <div class="activity no-comments" id="allintrashow'.$allintravals->post_id.'">

<!-- start Parent -->
<div class="row start'.$allintravals->post_id.'" style="border:0px solid red; margin:0px; padding:0px;">
<div id="allintrashowjaneesh'.$allintravals->post_id.'"></div>

   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">

   <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden">

<a href="'.userlink($allintravals->username).'" class="avatar bizcard" data-userid="'.$allintravals->user_id.'"><img src="'.$src.'"  /></a>

  </div><!--/ end : col-md-1 -->

  <div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">	

	<div class="activity-container">
	<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<a href="'.userlink($allintravals->username).'" class="author bizcard" data-userid="'.$allintravals->user_id.'">'. ($allintravals->username) .'</a>
			<div class="meta-info">'.$individual.''.$grp.'
				
				
			</div>
			<div class="activity-options">'.$delete .''.$fav.'	
			</div>
		</div>




<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'view/post:'.$allintravals->post_id.'" class="permlink">'.post::parse_date($allintravals->postdate).' <span class="glyphicon glyphicon-link"></span></a>
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
			  <span class="reply icon-ftr icon-ftr-reply">
      '.$replayhtml.'
      </span>
				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>

				<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>
			<div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Linkedin</a></li>
								  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
          
           
              
        </div>

        </div><!--/ end : col-md-11 -->  
</div><!--/ end : col-md-12 -->
				';
										   //this for subreplay checking 
        $replies                =$buff->checkreplies($allintravals->post_id);
    if(empty($replies)){
		$even ="intraday";
		$chield  = $buff->is_notificationchield_replay($allintravals->post_id,$even);
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);

	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 7;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" style="border:0px solid grey;"><a style="cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$allintravals->post_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
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
				 $time = ($chield[$m]->date);
						$res2  =date("Y-m-d h:i:s", $time);
						$pre   =date('Y-m-d h:i:s');
						$datetime1 = strtotime($pre);
						$datetime2 = strtotime($res2);
						$interval  = abs($datetime2 - $datetime1);
						$minutes   = round($interval / 60);
           				if(($this->user->id ==$chield[$m]->userid) && trim($minutes) < 5 ){
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
					$childclass="allshowchild".$allintravals->post_id;
					
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


   
   
   	       $allintrahtml .='
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
                          <a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>

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
                           <div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12">
   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($chield[$m]).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$allintravals->post_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
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
	$allintrahtml .='</div><!--/ end : activity container --> 
</div><!--/ end : parent -->
</div><!-- end : activity no-comments-->
<script>
var main = $("#allintrashow'.$allintravals->post_id.'").height();
var child = $(".allshowchild'.$allintravals->post_id.'").height();

var final = Number(main)-Number(child);

$("#allintrashowjaneesh'.$allintravals->post_id.'").css("height",final);
</script>
<style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#allintrashowjaneesh'.$allintravals->post_id.'{
z-index:100;
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#allintrashowjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#allintrashowjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#allintrashowjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#allintrashowjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#allintrashowjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>

		';
		
	}
	
	echo $allintrahtml;exit;
	}
/********************* End :  All Intraday  **************************/
		
	}




	
/********************* Start :  People you follow Intraday  **************************/
	if($_POST['type'] =='follow_intraday'){

		$offset              =$_POST['followshowcnt'];

		$intrdata_followers   =$p->peopleyoufollowintradaydata($this->user->id,$offset);
		 $followintrahtml='';
		if(!empty($intrdata_followers)){	

	    foreach($intrdata_followers as $allintrkeys=>$allintravals){

 	

				/*reshare and like count logic apply here*/
                $res	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$allintravals->post_id.'" LIMIT 1');
		        $is_reshared       =$db2->fetch_object($res);
				$resharesres       =$db2->query('select count(id) as resharecnt  FROM post_reshares WHERE  post_id="'.$allintravals->post_id.'" ');
				 $resultres=$this->db2->fetch_object($resharesres);
				
				$resharecnt     =$resultres->resharecnt;
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
					$is_agree = $p->is_post_agree($this->user->id,$allintravals->post_id);
					$is_agree_cnt = $p->is_post_agree_cnt($allintravals->post_id);
					if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
           				/* end reshare and like count logic apply here*/
						 $time = ($allintravals->date);
						$res2  =date("Y-m-d h:i:s", $time);
						$pre   =date('Y-m-d h:i:s');
						$datetime1 = strtotime($pre);
						$datetime2 = strtotime($res2);
						$interval  = abs($datetime2 - $datetime1);
						$minutes   = round($interval / 60);
           				if(($this->user->id ==$allintravals->userid) && trim($minutes) < 5 ){
           				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
						}else{
           				$delete ='';
           				}
           				
           				
                $is_spam  = $p->is_spam($allintravals->post_id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Unmarkspam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Markspam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
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
										
           						
 
		                
		$followintrahtml .='
		
		 <div class="activity no-comments" id="followshow'.$allintravals->post_id.'">

<!-- start Parent -->
<div class="row start'.$allintravals->post_id.'" style="border:0px solid red; margin:0px; padding:0px;">
<div id="followshowjaneesh'.$allintravals->post_id.'"></div>

   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">

   <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden">

<a href="'.userlink($allintravals->username).'" class="avatar bizcard" data-userid="'.$allintravals->user_id.'"><img src="'.$src.'"  /></a>	

</div><!--/ end : col-md-1 -->

     <div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">

	<div class="activity-container">
	<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<a href="'.userlink($allintravals->username).'" class="author bizcard" data-userid="'.$allintravals->user_id.'">'. ($allintravals->username) .'</a>
			<div class="meta-info">'.$individual.''.$grp.'
				
				
			</div>
			<div class="activity-options">'.$delete .''.$fav.'	
			</div>
		</div>



<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'view/post:'.$allintravals->post_id.'" class="permlink">'.post::parse_date($allintravals->postdate).' <span class="glyphicon glyphicon-link"></span></a>
</div>




		<div class="activity-content"></div>
		<div><div class="attachments lightbox-enabled">
	
	
	
        </div></div>
		<div class="activity-poll">'.$peopledatahtml.'</div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div><div id="replaypopup8-'.$allintravals->post_id.'" class="modal fade" ></div>
	
	<div>
	<div class="activity-footer meta-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
  <span class="reply icon-ftr icon-ftr-reply">
      '.$replayhtml.'
      </span>
				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>

				<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>
			<div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Linkedin</a></li>
								  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
                     
           
              
        </div>

        </div><!--/ end : col-md-11 -->  
</div><!--/ end : col-md-12 -->';
										   //this for subreplay checking 
        $replies                =$buff->checkreplies($allintravals->post_id);
    if(empty($replies)){
		$even ="intraday";
		$chield  = $buff->is_notificationchield_replay($allintravals->post_id,$even);
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);

	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 8;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" style="border:0px solid grey;"><a style="cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$allintravals->post_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
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
				     $time = ($chield[$m]->date);
						$res2  =date("Y-m-d h:i:s", $time);
						$pre   =date('Y-m-d h:i:s');
						$datetime1 = strtotime($pre);
						$datetime2 = strtotime($res2);
						$interval  = abs($datetime2 - $datetime1);
						$minutes   = round($interval / 60);
           				if(($this->user->id ==$chield[$m]->userid) && trim($minutes) < 5 ){
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
					$childclass="followchild".$allintravals->post_id;
					
					
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


		 
	 

   
   
   	       $followintrahtml .='
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
   
			<a  style="cursor:pointer" onclick="childpopup('.$allintravals->post_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
   			<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_likedreplay? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_likedreplay? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
   			<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>
   		<div class="dropdown icon-ftr">
   						   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
   						   <ul class="menu-options">
   							  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'&source='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'"  target="_blank" >Linkedin</a></li>
   							 <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$chield[$m]->id.'').'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'  target="_blank" >FriendFeed</a></li>
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
				
	$followintrahtml .='</div><!--/ end : activity container --> 
</div><!--/ end : parent -->
</div><!-- end : activity no-comments-->
<script>
var main = $("#followshow'.$allintravals->post_id.'").height();
var child = $(".followchild'.$allintravals->post_id.'").height();
var final = Number(main)-Number(child);
$("#followshowjaneesh'.$allintravals->post_id.'").css("height",final);
</script>
<style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#followshowjaneesh'.$allintravals->post_id.'{
z-index:100;
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#followshowjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#followshowjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#followshowjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#followshowjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#followshowjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>
		';
		
	}
	}
	echo $followintrahtml;
	}


/*********************** End : People you follow Showmore ************************/	



/*********************** Start : My Intraday Showmore ************************/	

		if($_POST['type'] =='my_intraday'){
			$offset              =$_POST['myintrashowcnt'];
			$myintrdatay   =$p->myintraday($this->user->id,$offset);

			$myintradayintrahtml='';
	    if(!empty($myintrdatay)){	

	    foreach($myintrdatay as $allintrkeys=>$allintravals){

 	

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
				
				$myintradatahtml ='';
				$myintradatahtml .='<div>'.$allintravals->message.'</div>
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
				$myintradatahtml .='
				
  
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
			$myintradatahtml .='</tbody>
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
					$is_agree = $p->is_post_agree($this->user->id,$allintravals->post_id);
					$is_agree_cnt = $p->is_post_agree_cnt($allintravals->post_id);
					if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
           			/* end reshare and like count logic apply here*/
					 $time = ($allintravals->date);
						$res2  =date("Y-m-d h:i:s", $time);
						$pre   =date('Y-m-d h:i:s');
						$datetime1 = strtotime($pre);
						$datetime2 = strtotime($res2);
						$interval  = abs($datetime2 - $datetime1);
						$minutes   = round($interval / 60);
           				if(($this->user->id ==$allintravals->userid) && trim($minutes) < 5 ){
           				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
						}else{
           				$delete ='';
           				}

           				
                $is_spam  = $p->is_spam($allintravals->post_id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Unmarkspam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Markspam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
										
           						
 
		                
		$myintradayintrahtml .='
		
		 <div class="activity no-comments">
<a href="'.userlink($allintravals->username).'" class="avatar bizcard" data-userid="'.$allintravals->user_id.'"><img src="'.$src.'"  /></a>	
	<div class="activity-container">
		<div class="activity-header">
			<a href="'.userlink($allintravals->username).'" class="author bizcard" data-userid="'.$allintravals->user_id.'">'. ($allintravals->username) .'</a>
			<div class="meta-info">
				
				
			</div>
			<div class="activity-options">'.$delete .''.$fav.'	
			</div>
		</div>




<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'view/post:'.$allintravals->post_id.'" class="permlink">'.post::parse_date($allintravals->postdate).' <span class="glyphicon glyphicon-link"></span></a>
</div>




		<div class="activity-content"></div>
		<div><div class="attachments lightbox-enabled">
	
	
	
        </div></div>
		<div class="activity-poll">'.$myintradatahtml.'</div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	
	<div>
	<div class="activity-footer meta-info">
				<a style="cursor:pointer" onclick="activityAddCommentchield('.$allintravals->post_id.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>
				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>

				<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>
			<div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Linkedin</a></li>
								  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
						
				</div>
				<div class="comment-chield" style="display:none" id="chield'.$allintravals->post_id.'">
				<div class="comments-editor data-content-placeholder" data-token="069fc5555">
				<div>
				
				<div class="activity-header commentpost'.$allintravals->post_id.'">
				<a href="'.$C->SITE_URL.''.$allintravals->username.'" class="author bizcard" data-userid="'.$allintravals->user_id.'">'.$allintravals->username.'</a>
				
				</div>
			
					</div>
				</div></div><br><br>
	</div>
	<div>
	
	</div>
</div>
		';
		
	}
	}
	echo $myintradayintrahtml;

		}

/*********************** End : My Intraday Showmore ************************/	



/*********************** Start : My Intraday Correct Showmore ************************/	

		if($_POST['type'] =='correct'){
			$offset              = $_POST['myintradaycorrectshowcnt'];
			$myintrdataycorrect   =$p->myintradaycorrectdata($this->user->id,$offset);

			$myintradayintracorrecthtml='';
	    if(!empty($myintrdataycorrect)){	

	    foreach($myintrdataycorrect as $allintrkeys=>$allintravals){

 	

				/*reshare and like count logic apply here*/
                $res	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$allintravals->post_id.'" LIMIT 1');
		        $is_reshared       =$db2->fetch_object($res);
				$resharesres       =$db2->query('select count(id) as resharecnt  FROM post_reshares WHERE  post_id="'.$allintravals->post_id.'" ');
				 $resultres=$this->db2->fetch_object($resharesres);
				
				$resharecnt     =$resultres->resharecnt;
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
				
				$myintradatacorrecthtml ='';
				$myintradatacorrecthtml .='<div>'.$allintravals->message.'</div>
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
					
				} else{
					$img ='Open';
					
				}
				$myintradatacorrecthtml .='
				
  
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

			$myintradatacorrecthtml .='</tbody>
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
					$is_agree = $p->is_post_agree($this->user->id,$allintravals->post_id);
					$is_agree_cnt = $p->is_post_agree_cnt($allintravals->post_id);
					if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
           				/* end reshare and like count logic apply here*/
						$time = ($allintravals->date);
						$res2  =date("Y-m-d h:i:s", $time);
						$pre   =date('Y-m-d h:i:s');
						$datetime1 = strtotime($pre);
						$datetime2 = strtotime($res2);
						$interval  = abs($datetime2 - $datetime1);
						$minutes   = round($interval / 60);
           				if(($this->user->id ==$allintravals->userid) && trim($minutes) < 5 ){
           				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
						}else{
           				$delete ='';
           				}
           				
           				
                $is_spam  = $p->is_spam($allintravals->post_id,"public");
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
										
           						
 
		                
		$myintradayintracorrecthtml .='
		
		 <div class="activity no-comments" id="mycorrectshow'.$allintravals->post_id.'">

		 <!-- start Parent -->
<div class="row start'.$allintravals->post_id.'" style="border:0px solid red; margin:0px; padding:0px;">
<div id="correctjaneesh'.$allintravals->post_id.'"></div>

   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">

   <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden">

<a href="'.userlink($allintravals->username).'" class="avatar bizcard" data-userid="'.$allintravals->user_id.'"><img src="'.$src.'"  /></a>	

</div><!--/ end : col-md-1 -->

   <div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">	

	<div class="activity-container">
	<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<a href="'.userlink($allintravals->username).'" class="author bizcard" data-userid="'.$allintravals->user_id.'">'. ($allintravals->username) .'</a>
			<div class="meta-info">'.$individual.''.$grp.'
				
				
			</div>
			<div class="activity-options">'.$delete .''.$fav.'	
			</div>
		</div>



<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'view/post:'.$allintravals->post_id.'" class="permlink">'.post::parse_date($allintravals->postdate).' <span class="glyphicon glyphicon-link"></span></a>
</div>




		<div class="activity-content"></div>
		<div><div class="attachments lightbox-enabled">
	
	
	
        </div></div>
		<div class="activity-poll">'.$myintradatacorrecthtml.'</div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div><div id="replaypopup9-'.$allintravals->post_id.'" class="modal fade" ></div>
	
	<div>
	<div class="activity-footer meta-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
				  <span class="reply icon-ftr icon-ftr-reply">
      '.$replayhtml.'
      </span>
				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>

				<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>
			<div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Linkedin</a></li>
								 <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
                      
          
              
        </div>

        </div><!--/ end : col-md-11 -->  
</div><!--/ end : col-md-12 -->';
										   //this for subreplay checking 
        $replies                =$buff->checkreplies($allintravals->post_id);
		$even ="intraday";
    if(empty($replies)){
		$chield  = $buff->is_notificationchield_replay($allintravals->post_id,$even);
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);

	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 9;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" style="border:0px solid grey;"><a style="cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$allintravals->post_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
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
				 $time = ($chield[$m]->date);
						$res2  =date("Y-m-d h:i:s", $time);
						$pre   =date('Y-m-d h:i:s');
						$datetime1 = strtotime($pre);
						$datetime2 = strtotime($res2);
						$interval  = abs($datetime2 - $datetime1);
						$minutes   = round($interval / 60);
           				if(($this->user->id ==$chield[$m]->userid) && trim($minutes) < 5 ){
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
					$childclass="mycorrectchild".$allintravals->post_id;
					
					
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


		 
	 

   
   
   	       $myintradaycorrectintrahtml .='
            <div class="activity no-comments commentcontainer '.$childclass.'" style="margin-top:43px;border: 0px solid #E1E8ED; */">
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
            <a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>

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
   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($chield[$m]).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$allintravals->post_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
   			<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_likedreplay? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_likedreplay? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list icon-ftr f1"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
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
	$myintradayintracorrecthtml .='</div><!--/ end : activity container --> 
</div><!--/ end : parent -->
</div><!-- end : activity no-comments-->

<script>
var main = $("#mycorrectshow'.$allintravals->post_id.'").height();
var child = $(".mycorrectchild'.$allintravals->post_id.'").height();
var final = Number(main)-Number(child);
$("#correctjaneesh'.$allintravals->post_id.'").css("height",final);
</script>
<style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#correctjaneesh'.$allintravals->post_id.'{
z-index:100;
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
	echo $myintradayintracorrecthtml;

		}

/*********************** End : My Intraday Correct Showmore ************************/	



/*********************** Start : My Intraday Incorrect Showmore ************************/	

		if($_POST['type'] =='incorrect'){
			$offset              = $_POST['myintradayincorrectshowcnt'];
			$myintrdatayincorrect   =$p->myintradayincorrectdata($this->user->id,$offset);

			$myintradayintraincorrecthtml='';
	    if(!empty($myintrdatayincorrect)){	

	    foreach($myintrdatayincorrect as $allintrkeys=>$allintravals){

 	

				/*reshare and like count logic apply here*/
                $res	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$allintravals->post_id.'" LIMIT 1');
		        $is_reshared       =$db2->fetch_object($res);
				$resharesres       =$db2->query('select count(id) as resharecnt  FROM post_reshares WHERE  post_id="'.$allintravals->post_id.'" ');
				 $resultres=$this->db2->fetch_object($resharesres);
				
				$resharecnt     =$resultres->resharecnt;
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
				
				$myintradataincorrecthtml ='';
				$myintradataincorrecthtml .='<div>'.$allintravals->message.'</div>
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
				$myintradataincorrecthtml .='
				
  
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
			$myintradataincorrecthtml .='</tbody>
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
					$is_agree = $p->is_post_agree($this->user->id,$allintravals->post_id);
					$is_agree_cnt = $p->is_post_agree_cnt($allintravals->post_id);
					if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
           				/* end reshare and like count logic apply here*/
           				 $time = ($allintravals->date);
						$res2  =date("Y-m-d h:i:s", $time);
						$pre   =date('Y-m-d h:i:s');
						$datetime1 = strtotime($pre);
						$datetime2 = strtotime($res2);
						$interval  = abs($datetime2 - $datetime1);
						$minutes   = round($interval / 60);
           				if(($this->user->id ==$allintravals->userid) && trim($minutes) < 5 ){
           				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
						}else{
           				$delete ='';
           				}
						
           				
                $is_spam  = $p->is_spam($allintravals->post_id,"public");
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
   							
										
           						
 
		                
		$myintradayintraincorrecthtml .='
		
		 <div class="activity no-comments" id="incorrect'.$allintravals->post_id.'">

<!-- start Parent -->
<div class="row start'.$allintravals->post_id.'" style="border:0px solid red; margin:0px; padding:0px;">
<div id="myintracorrectjaneesh'.$allintravals->post_id.'"></div>

   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">

   <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden">

<a href="'.userlink($allintravals->username).'" class="avatar bizcard" data-userid="'.$allintravals->user_id.'"><img src="'.$src.'"  /></a>	

  </div><!--/ end : col-md-1 -->

     <div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">	

	<div class="activity-container">
	<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<a href="'.userlink($allintravals->username).'" class="author bizcard" data-userid="'.$allintravals->user_id.'">'. ($allintravals->username) .'</a>
			<div class="meta-info">'.$individual.''.$grp.'
				
				
			</div>
			<div class="activity-options">'.$delete .''.$fav.'	
			</div>
		</div>




<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'view/post:'.$allintravals->post_id.'" class="permlink">'.post::parse_date($allintravals->postdate).' <span class="glyphicon glyphicon-link"></span></a>
</div>



		<div class="activity-content"></div>
		<div><div class="attachments lightbox-enabled">
	
	
	
        </div></div>
		<div class="activity-poll">'.$myintradataincorrecthtml.'</div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div><div id="replaypopup10-'.$allintravals->post_id.'" class="modal fade" ></div>
	
	<div>
	<div class="activity-footer meta-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
				  <span class="reply icon-ftr icon-ftr-reply">
      '.$replayhtml.'
      </span>
				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr f2"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>

				<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>
			<div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Linkedin</a></li>
								  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
                                 
              
        </div>

        </div><!--/ end : col-md-11 -->  
</div><!--/ end : col-md-12 -->';
										   //this for subreplay checking 
        $replies                =$buff->checkreplies($allintravals->post_id);
    if(empty($replies)){
		$even ="intraday";
		$chield  = $buff->is_notificationchield_replay($allintravals->post_id,$even);
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);

	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 10;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" style="border:0px solid grey;"><a style="cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$allintravals->post_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
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
				 $time = ($chield[$m]->date);
						$res2  =date("Y-m-d h:i:s", $time);
						$pre   =date('Y-m-d h:i:s');
						$datetime1 = strtotime($pre);
						$datetime2 = strtotime($res2);
						$interval  = abs($datetime2 - $datetime1);
						$minutes   = round($interval / 60);
           				if(($this->user->id ==$chield[$m]->userid) && trim($minutes) < 5 ){
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


		 
	 

   
   
   	       $myintradayincorrectintrahtml .='
            <div class="activity no-comments commentcontainer '.$childclass.'" style="margin-top:43px;border: 0px solid #E1E8ED; */">
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
            <a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>

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
   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($chield[$m]).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$allintravals->post_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
   			<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_likedreplay? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_likedreplay? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list icon-ftr f3"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
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
	$myintradayintraincorrecthtml .='</div><!--/ end : activity container --> 
</div><!--/ end : parent -->
</div><!-- end : activity no-comments-->
<script>
var main = $("#incorrect'.$allintravals->post_id.'").height();
var child = $(".incorrectchild'.$allintravals->post_id.'").height();
var final = Number(main)-Number(child);
$("#myintracorrectjaneesh'.$allintravals->post_id.'").css("height",final);
</script>
<style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#myintracorrectjaneesh'.$allintravals->post_id.'{
z-index:100;
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#myintracorrectjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#myintracorrectjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#myintracorrectjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#myintracorrectjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#myintracorrectjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>
		';
		
	}
	}
	echo $myintradayintraincorrecthtml;

		}
/*********************** End : My Intraday Incorrect Showmore ************************/

/*********************** Start : My Intraday open Showmore ************************/	

		if($_POST['type'] =='open'){
			$offset              = $_POST['openshowcnt'];
			$myopen   =$p->myintradayopendata($this->user->id,$offset);

			$myintradayintraincorrecthtml='';
	    if(!empty($myopen)){	

	    foreach($myopen as $allintrkeys=>$allintravals){

 	

				/*reshare and like count logic apply here*/
                $res	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$allintravals->post_id.'" LIMIT 1');
		        $is_reshared       =$db2->fetch_object($res);
				$resharesres       =$db2->query('select count(id) as resharecnt  FROM post_reshares WHERE  post_id="'.$allintravals->post_id.'" ');
				 $resultres=$this->db2->fetch_object($resharesres);
				
				$resharecnt     =$resultres->resharecnt;
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
				$assetdata   =$p->assetopen($allintravals->post_id);
				
				$myintradataincorrecthtml ='';
				$myintradataincorrecthtml .='<div>'.$allintravals->message.'</div>
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
				$myintradataincorrecthtml .='
				
  
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
			$myintradataincorrecthtml .='</tbody>
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
					$is_agree = $p->is_post_agree($this->user->id,$allintravals->post_id);
					$is_agree_cnt = $p->is_post_agree_cnt($allintravals->post_id);
					if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
           				/* end reshare and like count logic apply here*/
           				 $time = ($allintravals->date);
						$res2  =date("Y-m-d h:i:s", $time);
						$pre   =date('Y-m-d h:i:s');
						$datetime1 = strtotime($pre);
						$datetime2 = strtotime($res2);
						$interval  = abs($datetime2 - $datetime1);
						$minutes   = round($interval / 60);
           				if(($this->user->id ==$allintravals->userid) && trim($minutes) < 5 ){
           				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
						}else{
           				$delete ='';
           				}
						
           				
                $is_spam  = $p->is_spam($allintravals->post_id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Unmarkspam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Markspam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				$eventtypeqweqw =11;	
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
   							
										
           						
 
		                
		$myintradayintraincorrecthtml .='
		
		 <div class="activity no-comments" id="myopenintra'.$allintravals->post_id.'">

<!-- start Parent -->
<div class="row start'.$allintravals->post_id.'" style="border:0px solid red; margin:0px; padding:0px;">
<div id="openjannesh'.$allintravals->post_id.'"></div>

   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green">

   <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden">

<a href="'.userlink($allintravals->username).'" class="avatar bizcard" data-userid="'.$allintravals->user_id.'"><img src="'.$src.'"  /></a>	

  </div><!--/ end : col-md-1 -->

     <div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">	

	<div class="activity-container">
	<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<a href="'.userlink($allintravals->username).'" class="author bizcard" data-userid="'.$allintravals->user_id.'">'. ($allintravals->username) .'</a>
			<div class="meta-info">'.$individual.''.$grp.'
				
				
			</div>
			<div class="activity-options">'.$delete .''.$fav.'	
			</div>
		</div>



<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
<a href="'.$C->SITE_URL.'view/post:'.$allintravals->post_id.'" class="permlink">'.post::parse_date($allintravals->postdate).' <span class="glyphicon glyphicon-link"></span></a>
</div>




		<div class="activity-content"></div>
		<div><div class="attachments lightbox-enabled">
	
	
	
        </div></div>
		<div class="activity-poll">'.$myintradataincorrecthtml.'</div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div><div id="replaypopup11-'.$allintravals->post_id.'" class="modal fade" ></div>
	
	<div>
	<div class="activity-footer meta-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
				  <span class="reply icon-ftr icon-ftr-reply">
      '.$replayhtml.'
      </span>
				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list icon-ftr f5"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>

				<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>
			<div class="dropdown icon-ftr">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Linkedin</a></li>
								 <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
                     
           
              
        </div>

        </div><!--/ end : col-md-11 -->  
</div><!--/ end : col-md-12 -->';
										   //this for subreplay checking 
        $replies                =$buff->checkreplies($allintravals->post_id);
    if(empty($replies)){
		$even ="intraday";
		$chield  = $buff->is_notificationchield_replay($allintravals->post_id,$even);
	if(!empty($chield) && count($chield)>0)
	{
		$j=count($chield);

	for($m=0;$m<count($chield);$m++){
		$replaycnt  = $buff->replaycount($chield[$m]->id);
		$eventrew = 11;
			if($replaycnt > 0){
				$replycont ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" style="border:0px solid grey;"><a style="cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$allintravals->post_id.','.$chield[$m]->id.','.$eventrew.')">View Replies</a></div>';
				
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
				 $time = ($chield[$m]->date);
						$res2  =date("Y-m-d h:i:s", $time);
						$pre   =date('Y-m-d h:i:s');
						$datetime1 = strtotime($pre);
						$datetime2 = strtotime($res2);
						$interval  = abs($datetime2 - $datetime1);
						$minutes   = round($interval / 60);
           				if(($this->user->id ==$chield[$m]->userid) && trim($minutes) < 5 ){
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
					$childclass="openchild".$allintravals->post_id;
					
					
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


		 
	 

   
   
   	       $myintradayincorrectintrahtml .='
            <div class="activity no-comments commentcontainer" style="margin-top:43px;border: 0px solid #E1E8ED; */">
               <!-- start Parent -->
			   <ul class="'.$css.'">
			<li>
                <div class="row" style="padding:0px 4px;">
                 
                 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box  '.$childclass.'" style="border:0px solid green">

                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden">
                    <a  href="'.userlink($chield[$m]->username).'"  class="avatar bizcard" data-userid="'.$chield[$m]->userid.'"><img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" /></a>
                     </div>

       <div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">
            <div class="activity-container">
            <div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>

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
   			<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($chield[$m]).'" />
   
			<a  style="cursor:pointer" onclick="childpopup('.$allintravals->post_id.','.$chield[$m]->id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
   			<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_likedreplay? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_likedreplay? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
   			<div class="agree-list icon-ftr f6"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
   
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
	$myintradayintraincorrecthtml .='</div><!--/ end : activity container --> 
</div><!--/ end : parent -->
</div><!-- end : activity no-comments-->
<script>
var main = $("#incorrect'.$allintravals->post_id.'").height();
var child = $(".incorrectchild'.$allintravals->post_id.'").height();
var final = Number(main)-Number(child);
$("#myintracorrectjaneesh'.$allintravals->post_id.'").css("height",final);
</script>
<style>
.image-div {
    z-index:400!important;
    border: 0px solid #fff;
}
#myintracorrectjaneesh'.$allintravals->post_id.'{
z-index:100;
position: absolute; 
border-left: 4px solid orange; 
float:left; 
margin-top: 5px!important;
}
@media screen and (max-width: 320px) {
#myintracorrectjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 22px; 
}
}
@media screen and (min-width: 320px) and (max-width: 480px) {
#myintracorrectjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 23px; 
}
}
@media screen and (min-width: 480px) and (max-width: 768px) {
#myintracorrectjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 30px; 
}
}
@media screen and (min-width: 768px) and (max-width: 992px) {
#myintracorrectjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 35px; 
}
}
@media screen and (min-width: 992px) {
#myintracorrectjaneesh'.$allintravals->post_id.' {
margin: 0px 0px 0px 25px; 
}
}
</style>
		';
		
	}
	}
	echo $myintradayintraincorrecthtml;

		}
/*********************** End : My Intraday Incorrect Showmore ************************/
	
	
		
	
?>