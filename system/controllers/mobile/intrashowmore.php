<?php
	error_reporting(0);
	if( !$this->user->is_logged ) {
		$this->redirect('home');
	}
	
	$this->load_langfile('inside/global.php');
	$p = new post();
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
					$is_agree = $p->is_post_agree($this->user->id,$allintravals->post_id);
					$is_agree_cnt = $p->is_post_agree_cnt($allintravals->post_id);
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
                $is_spam  = $p->is_spam($allintravals->post_id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Unmarkspam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Markspam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
										
           						
 
		                
		$allintrahtml .='
		
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
		<div class="activity-content"></div>
		<div><div class="attachments lightbox-enabled">
	
	
	
        </div></div>
		<div class="activity-poll">'.$assetdatahtml.'</div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	
	<div>
	<div class="activity-footer meta-info">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$allintravals->post_id.'" class="permlink">'.post::parse_date($allintravals->date).'</a>
				<a style="cursor:pointer" onclick="activityAddCommentchield('.$allintravals->post_id.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>
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
				</div></div><br><br>
	</div>
	<div>
	
	</div>
</div>
		';
		
	}
	
	echo $allintrahtml;
	}
	/*end all intraday tab  */
		
	}
	
/*follow intraday tab */
	if($_POST['type'] =='follow_intraday'){
		$offset              =$_POST['follow-show-count'];
		$intrdata_followers   =$p->peopleyoufollowintradaydata($this->user->id,$offset);
		 $followintrahtml='';
		if(!empty($intrdata_followers)){	

	    foreach($intrdata_followers as $allintrkeys=>$allintravals){

 	

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
           				
           				if($if_can_delete =='1' ||($this->user->id ==$allintravals->user_id) ){
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
										
           						
 
		                
		$followintrahtml .='
		
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
		<div class="activity-content"></div>
		<div><div class="attachments lightbox-enabled">
	
	
	
        </div></div>
		<div class="activity-poll">'.$peopledatahtml.'</div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	
	<div>
	<div class="activity-footer meta-info">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$allintravals->post_id.'" class="permlink">'.post::parse_date($allintravals->date).'</a>
				<a style="cursor:pointer" onclick="activityAddCommentchield('.$allintravals->post_id.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>
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
				</div></div><br><br>
	</div>
	<div>
	
	</div>
</div>
		';
		
	}
	}
	echo $followintrahtml;
	}
	/*my intraday tab */
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
           				
           				if($if_can_delete =='1' ||($this->user->id ==$allintravals->user_id) ){
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
		<div class="activity-content"></div>
		<div><div class="attachments lightbox-enabled">
	
	
	
        </div></div>
		<div class="activity-poll">'.$myintradatahtml.'</div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	
	<div>
	<div class="activity-footer meta-info">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$allintravals->post_id.'" class="permlink">'.post::parse_date($allintravals->date).'</a>
				<a style="cursor:pointer" onclick="activityAddCommentchield('.$allintravals->post_id.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>
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
		/*my intraday correct tab */
		if($_POST['type'] =='correct'){
			$offset              = $_POST['myintradaycorrectshowcnt'];
			$myintrdataycorrect   =$p->myintradaycorrectdata($this->user->id,$offset);

			$myintradayintracorrecthtml='';
	    if(!empty($myintrdataycorrect)){	

	    foreach($myintrdataycorrect as $allintrkeys=>$allintravals){

 	

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
					
				}else{
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
           				
           				if($if_can_delete =='1' ||($this->user->id ==$allintravals->user_id) ){
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
										
           						
 
		                
		$myintradayintracorrecthtml .='
		
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
		<div class="activity-content"></div>
		<div><div class="attachments lightbox-enabled">
	
	
	
        </div></div>
		<div class="activity-poll">'.$myintradatacorrecthtml.'</div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	
	<div>
	<div class="activity-footer meta-info">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$allintravals->post_id.'" class="permlink">'.post::parse_date($allintravals->date).'</a>
				<a style="cursor:pointer" onclick="activityAddCommentchield('.$allintravals->post_id.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>
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
				</div></div><br><br>
	</div>
	<div>
	
	</div>
</div>
		';
		
	}
	}
	echo $myintradayintracorrecthtml;

		}
		/*my intraday incorrect tab */
		if($_POST['type'] =='incorrect'){
			$offset              = $_POST['myintradayincorrectshowcnt'];
			$myintrdatayincorrect   =$p->myintradayincorrectdata($this->user->id,$offset);

			$myintradayintraincorrecthtml='';
	    if(!empty($myintrdatayincorrect)){	

	    foreach($myintrdatayincorrect as $allintrkeys=>$allintravals){

 	

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
           				
           				if($if_can_delete =='1' ||($this->user->id ==$allintravals->user_id) ){
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
										
           						
 
		                
		$myintradayintraincorrecthtml .='
		
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
		<div class="activity-content"></div>
		<div><div class="attachments lightbox-enabled">
	
	
	
        </div></div>
		<div class="activity-poll">'.$myintradataincorrecthtml.'</div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	
	<div>
	<div class="activity-footer meta-info">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$allintravals->post_id.'" class="permlink">'.post::parse_date($allintravals->date).'</a>
				<a style="cursor:pointer" onclick="activityAddCommentchield('.$allintravals->post_id.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>
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
				</div></div><br><br>
	</div>
	<div>
	
	</div>
</div>
		';
		
	}
	}
	echo $myintradayintraincorrecthtml;

		}

	
	
		
	
?>