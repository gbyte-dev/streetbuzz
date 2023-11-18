<?php
	error_reporting(0);
	if( !$this->user->is_logged ) {
		$this->redirect('home');
	}
	$post = new post();
	$if_can_delete= $post->if_can_delete_notification();
	
	$this->load_langfile('inside/global.php');
	$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post($obj->type, FALSE, $obj);
    if(($_POST['show_type'] =="all")){
	/*all predictions tab */
	$offset        = isset($_POST['totalcnt'])? $_POST['totalcnt']:0 ;
	$allpredictons   =$post->allpredictions($this->user->id,$offset);
	$D->allpredictcnt = count($allpredictons);
	$allpredicthtml ='';
	if($D->allpredictcnt !=0){
	    foreach($allpredictons as $allintrkeys=>$allpredictval){

         if(!empty($allpredictons[$allintrkeys])){	
 	

				/*reshare and like count logic apply here*/
                $res	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$allpredictval->post_id.'" LIMIT 1');
		        $is_reshared       =$db2->fetch_object($res);
				$resharesres       =$db2->query('select id  FROM post_reshares WHERE  post_id="'.$allpredictval->post_id.'" ');
				 while($resultres=$this->db2->fetch_object($resharesres))
				{
					$wew[]=$resultres;
				}
				$resharecnt     =count($wew);
				
				$is_likedres  = $db2->query('select *  FROM  post_likes WHERE user_id="'.$this->user->id.'" AND post_id="'.$allpredictval->post_id.'" LIMIT 1');;
			    $is_liked       =$db2->fetch_object($is_likedres);
				$likesnumberres		 = $db2->query('select count(*) as likecnt  FROM  post_likes WHERE  post_id="'.$allpredictval->post_id.'" ');;
				$likesnumberresdata    =$this->db2->fetch_object($likesnumberres);
				

				$likes_number     = $likesnumberresdata->likecnt;
				$is_fav  = $db2->query('select *  FROM  post_favs WHERE user_id="'.$this->user->id.'" AND post_id="'.$allpredictval->post_id.'" LIMIT 1');;
			    $is_favres       =$db2->fetch_object($is_fav);
				if(!empty($is_favres)){
					$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allpredictval->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
					
				}else{
										$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allpredictval->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';

					
				}
				if($allpredictval->avatar !=''){
					 $src=getAvatarUrl($allpredictval->avatar, 'thumbs1');
                }else{
					$src = $C->SITE_URL.'storage/avatars/thumbs1/_noavatar_user.gif';

				}
				$myincorrectintradatahtml ='';

				if($allpredictval->status =="OPEN"){
				//calculations for up rate
				$predict_value = $allpredictval->predict_value;
				$prediction_base_price = $allpredictval->prediction_base_price;
				$percentage             =(($predict_value-$prediction_base_price)/($prediction_base_price))*100;
				$percentage = number_format((float)$percentage, 2, '.', '');
				
				if (strpos($percentage, '-') !== false) {
					$con ='down';
	               $imag ='down-arrow-prediction.png';
				}else{
					$con ='up';
					$imag ='up-arrow-prediction.png';
				}
				                       
				$myincorrectintradatahtml .='<div style="background-color:#e3f8fe;font-size:12px;height:auto;padding:10px;">'.$allpredictval->asset_name.'($'.$allpredictval->ticker.')<img src="'.$C->SITE_URL.'/static/images/icons/'.$imag.'"> to be '.$con.' by '.$percentage.'% from '.$allpredictval->prediction_base_price.' in '.substr($allpredictval->end_date,0,10).' because of '.$allpredictval->predict_reason.'.</div>';
				}else{
					//calculations for up rate
				$predict_result = $allpredictval->predict_result;
				if($predict_result =='CORRECT'){
					 $imag ='hit.png';
					 $type ="Hit";
					 $percentage='';
					
				}else{
					 $imag ='miss.png';
					 
					 
					  $predict_value = $allpredictval->predict_value;
					  $prediction_base_price = $allpredictval->prediction_base_price;
					  $percentage             =(($predict_value-$prediction_base_price)/($prediction_base_price))*100;
					  $percentage = substr(number_format((float)$percentage, 2, '.', ''),1);
					  $type =" Mis by ".$percentage."%";
					
				}
				$myincorrectintradatahtml .='<div style="background-color:#e3f8fe;font-size:12px;height:auto;padding: 10px;"> Your prediction on '.$allpredictval->asset_name.'($'.$allpredictval->ticker.') done  on '.substr($allpredictval->end_date,0,10).' was a '.$type.' <img src="'.$C->SITE_URL.'static/images/icons/'.$imag.'"> </div>';
				}	



				  $buff->post_type='public';
				    if($likes_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allpredictval->post_id.'"}').'">'.$likes_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allpredictval->post_id.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="UnRebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                    if($resharecnt > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allpredictval->post_id.'"}').'">'.$resharecnt.'</a>';
                    }else{
						$resharecnt ='';
						
					}
					$is_agree = $buff->is_post_agree($this->user->id,$allpredictval->post_id);
					$is_agree_cnt = $buff->is_post_agree_cnt($allpredictval->post_id);
					if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allpredictval->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
           				/* end reshare and like count logic apply here*/
           				
           				if($if_can_delete =='1' ||($this->user->id ==$allpredictval->user_id) ){
           				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allpredictval->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
           				}else{
           				$delete ='';
           				}
                $is_spam  = $post->is_spam($allpredictval->post_id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Unmarkspam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allpredictval->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Markspam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allpredictval->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				$eventtypeqweqw = 11;
			$replayhtml ='<a style="cursor:pointer" onclick="parentreplay('.$allpredictval->post_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';
				
				

										
           						
 
		                
		$allpredicthtml .='
		
		 <div class="activity no-comments">
<a href="'.userlink($allpredictval->username).'" class="avatar bizcard" data-userid="'.$allpredictval->user_id.'"><img src="'.$src.'"  /></a>	
	<div class="activity-container">
		<div class="activity-header">
			<a href="'.userlink($allpredictval->username).'" class="author bizcard" data-userid="'.$allpredictval->user_id.'">'. ($allpredictval->username) .'</a>
			<div class="meta-info">
				
				
			</div>
			<div class="activity-options">'.$delete .''.$fav.'	
			</div>
		</div>
		<div class="activity-content"></div>
		<div><div class="attachments lightbox-enabled">
	
	
	
        </div></div>
		<div id="replaypopup11-'.$allpredictval->post_id.'" class="modal fade" ></div>

		<div class="activity-poll">'.$myincorrectintradatahtml.'</div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	
	<div>
	<div class="activity-footer meta-info">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$allpredictval->post_id.'" class="permlink">'.post::parse_date($allpredictval->date).'</a>
								'.$replayhtml.'

				<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allpredictval->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allpredictval->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>

				<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
			<div class="dropdown">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$allpredictval->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$allpredictval->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$allpredictval->post_id.'').'"  target="_blank" >Linkedin</a></li>
								  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$allpredictval->post_id.'').'  target="_blank" >StumbleUpon</a></li>
								  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$allpredictval->post_id.'').'  target="_blank" >MySpace</a></li>
								  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$allpredictval->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'  target="_blank" >FriendFeed</a></li>
								  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$allpredictval->post_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$allpredictval->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$allpredictval->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
                      <div class="like-list">'.$mark_content.'</div>							
				</div>
				<br><br>
	</div>
	<div>
	
	</div>
</div>
		';
		
		
	}
	}
	echo $allpredicthtml;exit;
}
	}
	
	
	/*end all predictions tab */
	if($_POST['show_type'] =="follow"){
	
	/*people you follow predictions tab */
	$offset =isset($_POST['totalcnt'])? $_POST['totalcnt']:0;
	$youfollowers   =$post->peopleyoufollowpredictions($this->user->id,$offset);
	$D->followerscount =count($youfollowers);
	$youfollowhtml ='';
	
	
		if($D->followerscount !=0){
	    foreach($youfollowers as $allintrkeys=>$mypeopllepredictval){

         if(!empty($youfollowers[$allintrkeys])){	
 	

				/*reshare and like count logic apply here*/
                $res	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$mypeopllepredictval->post_id.'" LIMIT 1');
		        $is_reshared       =$db2->fetch_object($res);
				$resharesres       =$db2->query('select id  FROM post_reshares WHERE  post_id="'.$mypeopllepredictval->post_id.'" ');
				 while($resultres=$this->db2->fetch_object($resharesres))
				{
					$wew[]=$resultres;
				}
				$resharecnt     =count($wew);
				
				$is_likedres  = $db2->query('select *  FROM  post_likes WHERE user_id="'.$this->user->id.'" AND post_id="'.$mypeopllepredictval->post_id.'" LIMIT 1');;
			    $is_liked       =$db2->fetch_object($is_likedres);
				$likesnumberres		 = $db2->query('select count(*) as likecnt  FROM  post_likes WHERE  post_id="'.$mypeopllepredictval->post_id.'" ');;
				$likesnumberresdata    =$this->db2->fetch_object($likesnumberres);
				

				$likes_number     = $likesnumberresdata->likecnt;
				$is_fav  = $db2->query('select *  FROM  post_favs WHERE user_id="'.$this->user->id.'" AND post_id="'.$mypeopllepredictval->post_id.'" LIMIT 1');;
			    $is_favres       =$db2->fetch_object($is_fav);
				if(!empty($is_favres)){
					$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$mypeopllepredictval->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
					
				}else{
										$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$mypeopllepredictval->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';

					
				}
				if($mypeopllepredictval->avatar !=''){
					 $src=getAvatarUrl($mypeopllepredictval->avatar, 'thumbs1');
                }else{
					$src = $C->SITE_URL.'storage/avatars/thumbs1/_noavatar_user.gif';

				}
				$mypredictionshtml ='';
				if($mypeopllepredictval->status =="OPEN"){
				//calculations for up rate
				$predict_value = $mypeopllepredictval->predict_value;
				$prediction_base_price = $mypeopllepredictval->prediction_base_price;
				$percentage             =(($predict_value-$prediction_base_price)/($prediction_base_price))*100;
				$percentage = number_format((float)$percentage, 2, '.', '');
				
				if (strpos($percentage, '-') !== false) {
					$con ='down';
	               $imag ='down-arrow-prediction.png';
				}else{
					$con ='up';
					$imag ='up-arrow-prediction.png';
				}
				                       
				
				$mypredictionshtml .='<div style="background-color:#e3f8fe;font-size:12px;height:auto;padding: 10px;">'.$mypeopllepredictval->asset_name.'($'.$mypeopllepredictval->ticker.')<img src="'.$C->SITE_URL.'/static/images/icons/'.$imag.'"> to be '.$con.' by '.$percentage.'% from '.$mypeopllepredictval->prediction_base_price.' in '.substr($mypeopllepredictval->end_date,0,10).' because of '.$mypeopllepredictval->predict_reason.'.</div>';
				}else{
					//calculations for up rate
				$predict_result = $mypeopllepredictval->predict_result;
				if($predict_result =='CORRECT'){
					 $imag ='hit.png';
					 $type ="Hit";
					 $percentage='';
					
				}else{
					 $imag ='miss.png';
					 
					 
					  $predict_value = $mypeopllepredictval->predict_value;
					  $prediction_base_price = $mypeopllepredictval->prediction_base_price;
					  $percentage             =(($predict_value-$prediction_base_price)/($prediction_base_price))*100;
					  $percentage = substr(number_format((float)$percentage, 2, '.', ''),1);
					  $type =" Mis by ".$percentage."%";
					
				}
				$mypredictionshtml .='<div style="background-color:#e3f8fe;font-size:12px;height:auto;padding: 10px;"> Your prediction on '.$mypeopllepredictval->asset_name.'($'.$mypeopllepredictval->ticker.') done  on '.substr($mypeopllepredictval->end_date,0,10).' was a '.$type.' <img src="'.$C->SITE_URL.'static/images/icons/'.$imag.'"> </div>';

				}



				  $buff->post_type='public';
				    if($likes_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$mypeopllepredictval->post_id.'"}').'">'.$likes_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$mypeopllepredictval->post_id.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="UnRebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                    if($resharecnt > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$mypeopllepredictval->post_id.'"}').'">'.$resharecnt.'</a>';
                    }else{
						$resharecnt ='';
						
					}
					$is_agree = $buff->is_post_agree($this->user->id,$mypeopllepredictval->post_id);
					$is_agree_cnt = $buff->is_post_agree_cnt($mypeopllepredictval->post_id);
					if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$mypeopllepredictval->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
           				/* end reshare and like count logic apply here*/
           				
           				if($if_can_delete =='1' ||($this->user->id ==$mypeopllepredictval->user_id) ){
           				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$mypeopllepredictval->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
           				}else{
           				$delete ='';
           				}
                $is_spam  = $post->is_spam($mypeopllepredictval->post_id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Unmarkspam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$mypeopllepredictval->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Markspam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$mypeopllepredictval->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				$eventtypeqweqw = 12;
			$replayhtml ='<a style="cursor:pointer" onclick="parentreplay('.$mypeopllepredictval->post_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';
				
										
           						
 
		                
		$youfollowhtml .='
		
		 <div class="activity no-comments">
<a href="'.userlink($mypeopllepredictval->username).'" class="avatar bizcard" data-userid="'.$mypeopllepredictval->user_id.'"><img src="'.$src.'"  /></a>	
	<div class="activity-container">
		<div class="activity-header">
			<a href="'.userlink($mypeopllepredictval->username).'" class="author bizcard" data-userid="'.$mypeopllepredictval->user_id.'">'. ($mypeopllepredictval->username) .'</a>
			<div class="meta-info">
				
				
			</div>
			<div class="activity-options">'.$delete .''.$fav.'	
			</div>
		</div>
		<div class="activity-content"></div>
		<div><div class="attachments lightbox-enabled">
	
	
	
        </div></div>
		<div id="replaypopup12-'.$mypeopllepredictval->post_id.'" class="modal fade" ></div>


		<div class="activity-poll">'.$mypredictionshtml.'</div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	
	<div>
	<div class="activity-footer meta-info">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$mypeopllepredictval->post_id.'" class="permlink">'.post::parse_date($mypeopllepredictval->date).'</a>
										'.$replayhtml.'

				<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$mypeopllepredictval->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$mypeopllepredictval->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>

				<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
			<div class="dropdown">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$mypeopllepredictval->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$mypeopllepredictval->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$mypeopllepredictval->post_id.'').'"  target="_blank" >Linkedin</a></li>
								  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$mypeopllepredictval->post_id.'').'  target="_blank" >StumbleUpon</a></li>
								  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$mypeopllepredictval->post_id.'').'  target="_blank" >MySpace</a></li>
								  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$mypeopllepredictval->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'  target="_blank" >FriendFeed</a></li>
								  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$mypeopllepredictval->post_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$mypeopllepredictval->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$mypeopllepredictval->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
                      <div class="like-list">'.$mark_content.'</div>							
				</div>
				<div class="comment-chield" style="display:none" id="chield'.$mypeopllepredictval->post_id.'">
				<div class="comments-editor data-content-placeholder" data-token="069fc5555">
				<div>
				
				<div class="activity-header commentpost'.$mypeopllepredictval->post_id.'">
				<a href="'.$C->SITE_URL.''.$mypeopllepredictval->username.'" class="author bizcard" data-userid="'.$mypeopllepredictval->user_id.'">'.$mypeopllepredictval->username.'</a>
				
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
	echo $youfollowhtml;exit;
}
}

	
	
	/*end people you follow data */

	 if($_POST['show_type'] == "open"){
	 
		/*myprediction open  tab*/
		$status ="OPEN";
		$offset =isset($_POST['totalcnt']) ? $_POST['totalcnt']:0 ;
		$openres = $post->mypredictionopenresults($this->user->id,$status,$offset);
		$D->opencount  =count($openres);
		//print_r($openres);exit;
		
	    $mypredictionopenhtml='';
		if($D->opencount !=0){
	    foreach($openres as $allintrkeys=>$allintravals){

         if(!empty($openres[$allintrkeys])){	
 	

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
				//calculations for up rate
				$predict_value = $allintravals->predict_value;
				$prediction_base_price = $allintravals->prediction_base_price;
				$percentage             =(($predict_value-$prediction_base_price)/($prediction_base_price))*100;
				$percentage = number_format((float)$percentage, 2, '.', '');
				
				if (strpos($percentage, '-') !== false) {
					$con ='down';
	               $imag ='down-arrow-prediction.png';
				}else{
					$con ='up';
					$imag ='up-arrow-prediction.png';
				}
				                       
				$myincorrectintradatahtml ='';
				$myincorrectintradatahtml .='<div style="background-color:#e3f8fe;font-size:12px;height:auto;padding: 10px;">'.$allintravals->asset_name.'($'.$allintravals->ticker.')<img src="'.$C->SITE_URL.'/static/images/icons/'.$imag.'"> to be '.$con.' by '.$percentage.'% from '.$allintravals->prediction_base_price.' in '.substr($allintravals->end_date,0,10).' because of '.$allintravals->predict_reason.'.</div>';
					



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
				$eventtypeqweqw = 13;
			$replayhtml ='<a style="cursor:pointer" onclick="parentreplay('.$allintravals->post_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';
				
										
           						
 
		                
		$mypredictionopenhtml .='
		
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
		<div id="replaypopup13-'.$allintravals->post_id.'" class="modal fade" ></div>

		<div class="activity-poll">'.$myincorrectintradatahtml.'</div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	
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
				<br><br>
	</div>
	<div>
	
	</div>
</div>
		';
		
	}
	}
	echo $mypredictionopenhtml;exit;
}
}
	/*end myprediction open  tab */
		if($_POST['show_type'] == "close"){

	/*myprediction closed  tab*/
	$offset =isset($_POST['totalcnt']) ? $_POST['totalcnt']:0 ;

		$status ="CLOSE";
		$closed = $post->mypredictionopenresults($this->user->id,$status,$offset);
		$D->closedcount  =count($closed);
		//print_r($openres);exit;
		
	    $myclosedhtml='';
		if($D->closedcount !=0){
	    foreach($closed as $allintrkeys=>$allclosedval){

         if(!empty($closed[$allintrkeys])){	
 	

				/*reshare and like count logic apply here*/
                $res	=$db2->query('select *  FROM post_reshares WHERE user_id="'.$this->user->id.'" AND post_id="'.$allclosedval->post_id.'" LIMIT 1');
		        $is_reshared       =$db2->fetch_object($res);
				$resharesres       =$db2->query('select id  FROM post_reshares WHERE  post_id="'.$allclosedval->post_id.'" ');
				 while($resultres=$this->db2->fetch_object($resharesres))
				{
					$wew[]=$resultres;
				}
				$resharecnt     =count($wew);
				
				$is_likedres  = $db2->query('select *  FROM  post_likes WHERE user_id="'.$this->user->id.'" AND post_id="'.$allclosedval->post_id.'" LIMIT 1');;
			    $is_liked       =$db2->fetch_object($is_likedres);
				$likesnumberres		 = $db2->query('select count(*) as likecnt  FROM  post_likes WHERE  post_id="'.$allclosedval->post_id.'" ');;
				$likesnumberresdata    =$this->db2->fetch_object($likesnumberres);
				

				$likes_number     = $likesnumberresdata->likecnt;
				$is_fav  = $db2->query('select *  FROM  post_favs WHERE user_id="'.$this->user->id.'" AND post_id="'.$allclosedval->post_id.'" LIMIT 1');;
			    $is_favres       =$db2->fetch_object($is_fav);
				if(!empty($is_favres)){
					$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allclosedval->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
					
				}else{
										$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allclosedval->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';

					
				}
				if($allclosedval->avatar !=''){
					 $src=getAvatarUrl($allclosedval->avatar, 'thumbs1');
                }else{
					$src = $C->SITE_URL.'storage/avatars/thumbs1/_noavatar_user.gif';

				}
				//calculations for up rate
				$predict_result = $allclosedval->predict_result;
				if($predict_result =='CORRECT'){
					 $imag ='hit.png';
					 $type ="Hit";
					 $percentage='';
					
				}else{
					 $imag ='miss.png';
					 
					 
					  $predict_value = $allintravals->predict_value;
						$prediction_base_price = $allintravals->prediction_base_price;
						$percentage             =(($predict_value-$prediction_base_price)/($prediction_base_price))*100;
						$percentage = substr(number_format((float)$percentage, 2, '.', ''),1);
						$type =" Mis by ".$percentage."%";
					
				}
				
				
				                       
				$myincorrectintradatahtml ='';
				$myincorrectintradatahtml .='<div style="background-color:#e3f8fe;font-size:12px;height:auto;padding: 10px;"> Your prediction on '.$allclosedval->asset_name.'($'.$allclosedval->ticker.') done  on '.substr($allclosedval->end_date,0,10).' was a '.$type.' <img src="'.$C->SITE_URL.'static/images/icons/'.$imag.'"> </div>';
					



				  $buff->post_type='public';
				    if($likes_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allclosedval->post_id.'"}').'">'.$likes_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allclosedval->post_id.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="UnRebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                    if($resharecnt > 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allclosedval->post_id.'"}').'">'.$resharecnt.'</a>';
                    }else{
						$resharecnt ='';
						
					}
					$is_agree = $buff->is_post_agree($this->user->id,$allclosedval->post_id);
					$is_agree_cnt = $buff->is_post_agree_cnt($allclosedval->post_id);
					if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allclosedval->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }
           				/* end reshare and like count logic apply here*/
           				
           				if($if_can_delete =='1' ||($this->user->id ==$allclosedval->user_id) ){
           				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allclosedval->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
           				}else{
           				$delete ='';
           				}
                $is_spam  = $post->is_spam($allclosedval->post_id,"public");
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Unmarkspam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allclosedval->post_id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Markspam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allclosedval->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				$eventtypeqweqw = 14;
			$replayhtml ='<a style="cursor:pointer" onclick="parentreplay('.$allclosedval->post_id.','.$eventtypeqweqw.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>';
				
				

										
           						
 
		                
		$myclosedhtml .='
		
		 <div class="activity no-comments">
<a href="'.userlink($allclosedval->username).'" class="avatar bizcard" data-userid="'.$allclosedval->user_id.'"><img src="'.$src.'"  /></a>	
	<div class="activity-container">
		<div class="activity-header">
			<a href="'.userlink($allclosedval->username).'" class="author bizcard" data-userid="'.$allclosedval->user_id.'">'. ($allclosedval->username) .'</a>
			<div class="meta-info">
				
				
			</div>
			<div class="activity-options">'.$delete .''.$fav.'	
			</div>
		</div>
		<div class="activity-content"></div>
		<div><div class="attachments lightbox-enabled">
	
	
	
        </div></div>
		<div id="replaypopup14-'.$allclosedval->post_id.'" class="modal fade" ></div>

		<div class="activity-poll">'.$myincorrectintradatahtml.'</div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	
	<div>
	<div class="activity-footer meta-info">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$allclosedval->post_id.'" class="permlink">'.post::parse_date($allclosedval->date).'</a>
								'.$replayhtml.'

				<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allclosedval->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allclosedval->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>

				<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
			<div class="dropdown">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$allclosedval->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$allclosedval->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$allclosedval->post_id.'').'"  target="_blank" >Linkedin</a></li>
								  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'view/post:'.$allclosedval->post_id.'').'  target="_blank" >StumbleUpon</a></li>
								  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'view/post:'.$allclosedval->post_id.'').'  target="_blank" >MySpace</a></li>
								  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'view/post:'.$allclosedval->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'  target="_blank" >FriendFeed</a></li>
								  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$allclosedval->post_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$allclosedval->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$allclosedval->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
                      <div class="like-list">'.$mark_content.'</div>							
				</div>
				<br><br>
	</div>
	<div>
	
	</div>
</div>
		';
		
	}
	}
	echo $myclosedhtml;exit;
}
		 }
	/*end myprediction closed tab */
	
	
	
	
	
?>