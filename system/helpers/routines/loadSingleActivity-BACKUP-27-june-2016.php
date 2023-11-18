<?php
	function loadSingleActivity( $tpl, $params )
	{
                global $C, $D;
		$page 	= & $GLOBALS['page'];
		$network 	= & $GLOBALS['network'];
		$user 	= & $GLOBALS['user'];
		$pm 	= & $GLOBALS['plugins_manager'];
		$obj= $params[0];
		//echo '<pre>';print_r($obj);exit;
		 //the resource object from the DB
		$all_comments = isset($params[1]); //show all comments, usefull for View Post
		$tpl->layout->useBlock('activity');
		$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post($obj->type, FALSE, $obj);
		//echo '<pre>';print_r($buff);exit;
		
		// if( $buff->error ) {
		// 	return;
		// }
		//echo '<pre>';print_r($buff);exit;
		$pm->onPostLoad( $buff ); //cache results
		
		$onViewPage = $page->request[0] == 'view';
		$comments = (! $onViewPage)? $buff->get_comments() : $buff->get_all_comments();
		$comments_num = count($comments);
		
		if( !empty($buff->post_user->username) ){
		 $is_reshared    =$buff->is_post_reshared($buff->post_id);
				$reshares       =$buff->loaded_posts_reshares($buff->post_id);
				$resharecnt     =count($reshares);
$is_agree = $buff->is_post_agree($user->id,$buff->post_id);
				$is_agree_cnt = $buff->is_post_agree_cnt($buff->post_id);
				 if($is_agree_cnt->cnt > 0){					
					$showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
				   }else{
					 $showagreebtn_btn ='';  
				   }


			$like_content ='';
					$is_liked  = $buff->is_post_liked();
					$likes_number = isset($buff->post_likes['post'])? count($buff->post_likes['post']) : 0;
					$css="icons";
							$is_spam  = $buff->is_spam($buff->post_id,$buff->post_type);
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'"><em><img  src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                }
				
			//exit;
			//return;
				if( $user->is_logged ){
				 if($likes_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'">'.$likes_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Unrebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                    if($resharecnt> 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'">'.$resharecnt.'</a>';
                    }else{
						$resharecnt ='';
						
					}
					$replies                =$buff->checkreplies($buff->post_id);
					if(!empty($replies)){
						$popup ='<a  style="cursor:pointer" onclick="childpopuptimeline('.$replies->parent_id.','.$replies->replay_id.')" ><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>';

						
					}else{
						$popup ='<a  style="cursor:pointer" onclick="parentreplay('.$buff->post_id.')" ><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>';
						
					}
					
								  
								   $fsfs ='<li><a href="http://twitter.com/intent/tweet?text='.urlencode($buff->permalink).': '.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($buff->permalink).'&t='.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Facebook</a></li>';
								  }
			
			$tpl->layout->block->setVar('comment_footer','

               <div class="col-md-11 col-xs-11 col-md-offset-1 col-sm-offset-1 col-xs-offset-1 event-11">
               <input type="hidden" id="time-'.$buff->post_id.'" value="'.post::parse_date($obj->date).'" />
				
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$buff->permalink.'" class="permlink">'.post::parse_date($obj->date).'</a>
				'.$popup.'
				<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'">'.($is_agree? '<img  width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>

				<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
<div class="dropdown">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($buff->permalink).'&title='.urlencode(htmlspecialchars($buff->post_message)).'&source='.urlencode($buff->permalink).'&summary='.urlencode($buff->permalink).'"  target="_blank" >Linkedin</a></li>
								  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($buff->permalink).'  target="_blank" >StumbleUpon</a></li>
								  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($buff->permalink).'  target="_blank" >MySpace</a></li>
								  <li><a href="http://friendfeed.com/?url='.urlencode($buff->permalink).'&title='.urlencode(htmlspecialchars($buff->post_message)).'  target="_blank" >FriendFeed</a></li>
								  <li><a href="http://plus.google.com/share?url='.urlencode($buff->permalink).'"  target="_blank" >Google Plus</a></li>
								 '.$fsfs.'
							   </ul>
							</div>
							 <div class="like-list">'.$mark_content.'</div>						
	
				</div>



				<div class="comment-chield" style="display:none" id="chield'.$obj->id.'">
				<div class="comments-editor data-content-placeholder">
				<div>
				<div class="activity-header commentpost'.$obj->id.'">
				
				</div>
				
				<div class="comments-editor-content commentcontainer'.$obj->id.'">
					<div class="user-status-field htmlarea">
					<div class="commentpoll commentpoll'.$obj->id.'">
					</div>
						<div class="textarea-wrap comment">
							<textarea  id="message'.$obj->id.'" name="message" >@'.$buff->post_user->username.' '.'</textarea>
							<div class="textarea-highlighter"><span></span></div>
						</div>
					</div>			
							<div class="buttons">
								<button type="button" id="'.$obj->id.'" class="comment-post pollcreate left comment-post'.$obj->id.' post-btn btn blue"><span>POLL</span></button>
								
									<button onclick="comment('.$obj->id.','.$obj->id.')" type="button" id="'.$obj->id.'" class="comment-post comment-post'.$obj->id.' post-btn btn  blue center">Buzz</button>
								
							</div>
						</div>
					</div>
				</div></div>');
		}
		else
		{
		
			$tpl->layout->block->setVar('activity_delete','0');
		}
		if(isset($buff->post_user->username) ){
		
		//echo '<pre>';print_r($buff->post_user);

		$tpl->layout->block->setVar('activity_user_avatar', '<a href="'.userlink($buff->post_user->username).'" class="avatar bizcard" data-userid="'.$buff->post_user->id.'"><img src="'.getAvatarUrl($buff->post_user->avatar, 'thumbs1').'" alt="'.getThisUserCommunityName($buff->post_user).'" /></a>');
		$tpl->layout->block->setVar('activity_mobile_user_avatar', '<a href="'.userlink($buff->post_user->username).'" class="avatar bizcard" data-userid="'.$buff->post_user->id.'"><img src="'.getAvatarUrl($buff->post_user->avatar, 'thumbs4').'" alt="'.getThisUserCommunityName($buff->post_user).'" /></a>');
		$tpl->layout->block->setVar('activity_user_avatar_bkg', getAvatarUrl($buff->post_user->avatar, 'thumbs5'));
		$tpl->layout->block->setVar('activity_user_username', '<a href="'.userlink($buff->post_user->username).'" class="author bizcard" data-userid="'.$buff->post_user->id.'">'. getThisUserCommunityName($buff->post_user) .'</a>');
		$tpl->layout->block->setVar('activity_permlink', '<a href="'.$buff->permalink.'" class="permlink">'.post::parse_date($buff->post_date).'</a>');
		if( $buff->post_type == 'public' ){
			$tpl->layout->block->setVar('activity_user_activity_group', ($buff->post_group? $page->lang('postgroup_in').' <a href="'.$buff->post_group->group_link.'">'.$buff->post_group->title.'</a>' : '') );
		}else if( $buff->post_type == 'private' ){
			//@TODO: remove this when we add different private message template
			$tpl->layout->block->setVar('activity_user_activity_group', ((isset($buff->post_to_user->username) && $buff->post_to_user->username!=$user->info->username)? '>> <a href="'.userlink($buff->post_to_user->username).'">'.getThisUserCommunityName($buff->post_to_user).'</a>' : '') );
		}
		//check post have replies or not 

		$replies                =$buff->checkreplies($buff->post_id);
		if(!empty($replies)){
			$tpl->layout->block->setVar('replies','<a style="cursor:pointer;" class="author bizcard" onclick="replaycontent('.$replies->alternate_parent_id.','.$buff->post_id.')" data-userid="'.$replies->userid.'">Replies to @'. $replies->handler .'</a>');
			
		}		$prediction_data =$buff->predictiondata($buff->post_id);
		if(!empty($prediction_data)){
				if($prediction_data[0]->status =="OPEN"){
				//calculations for up rate
				$predict_value = $prediction_data[0]->predict_value;
				$prediction_base_price = $prediction_data[0]->prediction_base_price;
				$percentage             =(($predict_value-$prediction_base_price)/($prediction_base_price))*100;
				$percentage = number_format((float)$percentage, 2, '.', '');
				
				if (strpos($percentage, '-') !== false) {
					$con ='down';
	               $imag ='down-arrow-prediction.png';
				}else{
					$con ='up';
					$imag ='up-arrow-prediction.png';
				}
				                       
				$myincorrectintradatahtml .='<div style="background-color:#e3f8fe;font-size:12px;height:auto;padding:10px;">'.$prediction_data[0]->asset_name.'($'.$prediction_data[0]->ticker.')<img src="'.$C->SITE_URL.'/static/images/icons/'.$imag.'"> to be '.$con.' by '.$percentage.'% from '.$prediction_data[0]->prediction_base_price.' in '.substr($prediction_data[0]->end_date,0,10).' because of '.$prediction_data[0]->predict_reason.'.</div>';
				}else{
					//calculations for up rate
				$predict_result = $prediction_data[0]->predict_result;
				if($predict_result =='CORRECT'){
					 $imag ='hit.png';
					 $type ="Hit";
					 $percentage='';
					
				}else{
					 $imag ='miss.png';
					 
					 
					  $predict_value = $prediction_data[0]->predict_value;
					  $prediction_base_price = $prediction_data[0]->prediction_base_price;
					  $percentage             =(($predict_value-$prediction_base_price)/($prediction_base_price))*100;
					  $percentage = substr(number_format((float)$percentage, 2, '.', ''),1);
					  $type =" Mis by ".$percentage."%";
					
				}
				if($buff->post_user->id == $user->id){
					$handset ='If you want to change Hindsight reason please <a  class="mymodal" data-toggle="modal" data-target="#myModal-'.$prediction_data[0]->post_id.'"  >click here </a> 

  
  <!-- Modal -->
  <div class="modal fade-'.$prediction_data[0]->post_id.'" id="myModal-'.$prediction_data[0]->post_id.'" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Handset reason </h4>
        </div>
        <div class="modal-body">
		<div class="row">
		 <div>Reason :<input type="text" value="'.$prediction_data[0]->hindsight_reason.'" id="hindsight-'.$prediction_data[0]->post_id.'" onkeyup="validate(this,'.$prediction_data[0]->post_id.')">
		 </div>
		  <div id="handsetreason-error-'.$prediction_data[0]->post_id.'"class="notifyjs-container" style="top: 37px; left: 168px; overflow: hidden; display: hidden;"><div class="notifyjs-bootstrap-base notifyjs-bootstrap-error">
            <span data-notify-text="" class="notifyjs-text">This field is required</span>
         </div></div>
		 		   <button type="button" class="btn btn-default btn-primary"  data-toggle="modal"  onclick="changehandset('.$prediction_data[0]->post_id.')">Change</button>

		</div>


          
        </div>
		<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      
      </div>
      
    </div>
  </div>
  
					
					';
				}else{
					$handset ='';
					
				}
				$myincorrectintradatahtml .='<div style="background-color:#e3f8fe;font-size:12px;height:auto;padding: 10px;"> Your prediction on '.$prediction_data[0]->asset_name.'($'.$prediction_data[0]->ticker.') done  on '.substr($prediction_data[0]->end_date,0,10).' was a '.$type.' <img src="'.$C->SITE_URL.'static/images/icons/'.$imag.'">.'.$handset.' </div>';
				}
			
						$tpl->layout->block->setVar('activity_text', $myincorrectintradatahtml);

			
		}else{
				if($page->params->tab =="intraday" && $page->params->subtab ==""){
			$assetdata   =$buff->assetdata($buff->post_id);
			
		}elseif($page->params->tab =="intraday" && $page->params->subtab =="correct"){
		    $assetdata   =$buff->assetdatacorrect($buff->post_id);

		}elseif($page->params->tab =="intraday" && $page->params->subtab =="incorrect"){
			$assetdata   =$buff->assetdataincoorect($buff->post_id);

		}else{
			$assetdata   =$buff->assetdata($buff->post_id);
			
		}
		if($assetdata[0]->ticker !=''){
			$str =  $buff->parse_text();
			

			
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

			$tpl->layout->block->setVar('activity_text',$assetdatahtml);

			
			
		}else{
			$tpl->layout->block->setVar('activity_text', $buff->parse_text());
			$geolocation      =$buff->geolocation($buff->post_id);
			if(!empty($geolocation)){
				$geolocationhtml ="<div><a href=".$C->SITE_URL."search/tab:location/s:".$buff->post_id."><img src=".$C->SITE_URL."apps/events/static/images/icon-location-event.png> ".$geolocation."</a></div>";
			$tpl->layout->block->setVar('geolocation', $geolocationhtml);
			}

		}
		}

		if( $comments_num === 0 && !$page->is_mobile){ 
			$tpl->layout->block->setVar('activity_nocomments', 'no-comments');
		}
        $time = $obj->date;
		$res2  =date("Y-m-d h:i:s", $time);
		$pre   =date('Y-m-d h:i:s');
		$datetime1 = strtotime($pre);
               $datetime2 = strtotime($res2);
               $interval  = abs($datetime2 - $datetime1);
               $minutes   = round($interval / 60); 
               if(!empty($prediction_data) || !empty($assetdata) ){
			if($minutes > 5){
            $delete ='';
			}else{
			$delete = (($user->is_logged && $buff->if_can_delete())? '<a href="" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'" data-role="services" data-namespace="activities" title="Delete" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>' : '');
           }
		}else{
		$delete = (($user->is_logged && $buff->if_can_delete())? '<a href="" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'" data-role="services" data-namespace="activities" title="Delete" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>' : '');

		}
                           
		
		
		$tpl->layout->block->setVar('activity_options',$delete
				.
				(($user->is_logged && $buff->post_type == 'public')? ('<a href="" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'" data-role="services" title="Bookmark" data-namespace="activities" data-action="bookmark" class="'.($buff->is_post_faved()? '' : 'icons').'"> <img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>') : '' )
		);		
		
		if( $user->is_logged ){
			
			if( !$page->is_mobile ){
				$tpl->layout->block->setVar('activity_footer','<a href="" class="add-comment action" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'" data-role="services" data-namespace="comments" data-action="activityAddComment">'.$page->lang('activity_comment_txt').'</a>');
				$tpl->layout->block->setVar('comment_editor_user_avatar', '<a href="'.userlink($user->info->username).'" class="avatar"><img src="'.getAvatarUrl($user->info->avatar, 'thumbs3').'" alt="'.$user->info->fullname.'" /></a>');				
				
			} elseif ($page->is_mobile && $onViewPage) {				
				$tpl->layout->block->setVar('activity_footer','<a href="" class="add-comment action" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'" data-role="services" data-namespace="comments" data-action="activityAddComment">'.($buff->post_commentsnum == 0? ' ' : $buff->post_commentsnum).'</a>');
				$tpl->layout->block->setVar('comment_editor_user_avatar', '<a href="'.userlink($user->info->username).'" class="avatar"><img src="'.getAvatarUrl($user->info->avatar, 'thumbs3').'" alt="'.$user->info->fullname.'" /></a>');
			}else{
				$tpl->layout->block->setVar('comment_editor_user_avatar', '<a href="'.userlink($user->info->username).'" class="avatar"><img src="'.getAvatarUrl($user->info->avatar, 'thumbs3').'" alt="'.$user->info->fullname.'" /></a>');
				$tpl->layout->block->setVar('activity_footer', '<a href="#" class="add-comment action"  data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'" data-role="services" data-namespace="comments" data-action="showAll">'.($buff->post_commentsnum == 0? ' ' : $buff->post_commentsnum).'</a>');				
				
			}
			
			// if($buff->if_can_edit() ){
			// 	$edit_post_link = ' <a href="" class="edit_post" data-role="services" data-namespace="activities" data-action="show_edit_box" data-posttype="'.$buff->post_type.'" data-postid="'.$buff->post_id.'">Edit post</a>';
			// 	$tpl->layout->block->setVar( 'activity_footer', $edit_post_link );
			// }
			
			if($buff->post_type == 'public' && !$page->is_mobile ){
				$is_liked  = $buff->is_post_liked();
				
				$likes_number = isset($buff->post_likes['post'])? count($buff->post_likes['post']) : 0;
				$like_content = '<a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'">'.($is_liked? 'Unlike' : 'Like').'</a>';
					
				if ($likes_number > 0) {
					$like_users = $is_liked? ' (You' : '';
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'">';
						
					if( !$is_liked ){
						foreach ($buff->post_likes['post'] as $usr) {
							if( $usr[0] != $user->info->username ){
								$like_users = ' (<a href="'.userlink( $usr[0] ).'">'.$usr[0].'</a>';
								break;
							}
						}
						$like_content .= $like_users . (($likes_number>1)? ' and '.$showlikes_btn. ($likes_number-1).' other'.($likes_number-1>1? 's' : '').'</a>' : '') . ' like'.($likes_number==1? 's' : '').' this )';
					}else{
						$like_content .= $like_users . (($likes_number>1)? ' and '.$showlikes_btn. ($likes_number-1).' other'.($likes_number-1>1? 's' : '').'</a>' : '') . ' like this )';
					}
						
				}
				
				$tpl->layout->block->setVar( 'activity_footer', '<div class="like-list">'.$like_content.'</div>' );
			}

		}
	}
	$tpl->layout->block->setVar('activity_chield_text1',
				'<div id="replaypopup-'.$buff->post_id.'" class="modal fade" ></div>
  
			');
				$tpl->layout->block->setVar('hei',
				$buff->post_id);
			
			$tpl->layout->block->setVar('activity_chield_text1',
				'<div class="newdsdt-'.$buff->post_id.'"></div>	

  
			');
	$buff->is_chield_replay($buff->post_id);
	//this for subreplay checking 
	 $replies                =$buff->checkreplies($buff->post_id);
	 if(!empty($replies)){
		 $tpl->layout->block->setVar('heii',
				'0');
		 
	 }
	      if(empty($replies)){
		//start chield post
		$chield  = $buff->is_chield();
		if(!empty($chield )){
			$tpl->layout->block->setVar('heii',
				$buff->post_id);
			
			
		}else{
			$tpl->layout->block->setVar('heii',
				'0');
		}
		if(count($chield)>0)
		{
			$j=count($chield);
			
		}else{
			$j =0;
		}
		
		//echo count($chield);
		//echo '<pre>';print_r($chield);exit;
		if(!empty($chield) && count($chield)>0)
		{
			for($m=0;$m<count($chield);$m++){
			$replaycnt  = $buff->replaycount($chield[$m]->id);
			if($replaycnt > 0){
				$replycont ='<a style="color:blue;cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$buff->post_id.','.$chield[$m]->id.')">View Replies</a>';
				
			}else{
				$replycont ='';
				
			}
			//Like content
				$is_likedreplay     = $buff->new_liked($chield[$m]->id);
			    $likes_numbers       =$buff->new_liked_count($chield[$m]->id);
				$likes_number        =$likes_numbers->likecount;
				$groups = $buff->getgroupname($chield[$m]->group_id);
			   if(!empty($groups)){
			   $grp = 'in <a href="'.$C->SITE_URL.$groups->groupname.'">'.$groups->title.'</a>' ;
			
				}else{
					$grp ='';
				}
				
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
					$chi ="child".$buff->post_id;
					
					
				}else{
					
					
					$css="tree";
					$chi='';
					
				}
				$buzztype = $buff->getbuzztype($chield[$m]->id);
				if($buzztype =="buzz" || $buzztype =="" ){
					$mes = $buff->parsetext($chield[$m]->message);	
                }elseif($buzztype =="event"){
					$mes	    =$buff->eventhtml($chield[$m]->id);
						
				}elseif($buzztype =="poll"){
					$mes	    =$buff->pollchildhtml($chield[$m]->id);
					
				}elseif($buzztype =="intraday"){
					$mes	    =$buff->assethtml($chield[$m]->id);
					
				}

	   
		$delete = (($user->is_logged && $buff->if_can_delete())? '<a href="" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'" data-role="services" data-namespace="activities" title="Delete" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>' : '');
       $is_fav  = $buff->isfav($user->id,$chield[$m]->id);
	 
   			if(!empty($is_fav)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$chield[$m]->id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$chield[$m]->id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}

				
			$tpl->layout->block->setVar('activity_chield_text1','
			<ul class="'.$css.'">
			<li>
			
		


<!-- start Child -->
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box replay'.$buff->post_id.' single'.$chield[$m]->id.'"  style="border:0px solid red;" id="'.$chi.'">

 <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1 image-div" style="padding:0; overflow:hidden"><a href="'.userlink($chield[$m]->username).'" class="avatar bizcard" data-userid="'.$chield[$m]->userid.'"><img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" /></a>
            </div>

<div class="col-md-11 col-lg-11 col-sm-11 col-xs-11" style="padding:0px 3px 0px 8px;">

<div class="activity-container">
<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12"><a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>
				
<div class="meta-info">'.$grp.'
</div>
				
<div class="activity-options">'.$delete.''.$fav.'</div>
</div>
<div class="activity-content">'.$mes.'</div>
</div>		



		
		
		
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="activity-footer meta-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'/view/post:'.$chield[$m]->id.'" class="permlink">'.post::parse_date($chield[$m]->date).'</a>
				<a  style="cursor:pointer" onclick="childpopup('.$buff->post_id.','.$chield[$m]->id.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
				<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($chield[$m]->date).'" />

				<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_likedreplay? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_likedreplay? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_agree? '<img  width="30px" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Disagree"/>' : '<img width="30px" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree"/>').'</a>'.$showagreebtn_btn.'</div>
               	<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>

                  <div class="dropdown">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'/view/post:'.$chield[$m]->id).'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'&source='.urlencode($C->SITE_URL.'/view/post:'.$chield[$m]->id).'&summary='.urlencode($C->SITE_URL.'/view/post:'.$chield[$m]->id).'"  target="_blank" >Linkedin</a></li>
								  <li><a href="http://www.stumbleupon.com/submit?url='.urlencode($C->SITE_URL.'/view/post:'.$chield[$m]->id).'  target="_blank" >StumbleUpon</a></li>
								  <li><a href="http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($C->SITE_URL.'/view/post:'.$chield[$m]->id).'  target="_blank" >MySpace</a></li>
								  <li><a href="http://friendfeed.com/?url='.urlencode($C->SITE_URL.'/view/post:'.$chield[$m]->id).'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'  target="_blank" >FriendFeed</a></li>
								  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'/view/post:'.$chield[$m]->id).'"  target="_blank" >Google Plus</a></li>
								 '.$fsfs.'
							   </ul>
							</div>
							
                           <div class="like-list">'.$mark_content.'</div>						
							</div>
							<div id="replydis-'.$chield[$m]->id.'">'.$replycont.'</div>

				 </div>

<div></div>
</div>

</div>
<!-- end Child -->


</li>
</ul>

			'

			);
		}



		}
	  }


		//end chield post
	
	
	
		//start of poll
		$poll  = $buff->is_poll();
		//print_r($poll);exit;
		if(count($poll)>0)
		{
			$pollanswer=$buff->is_pollanswer($user->id,$poll[0]->poll_id);
			$tpl->layout->useInnerBlock('activity-poll');
			//$tpl->layout->inner_block->setVar('activity_poll_question', '<b><label style="color:#2665ca;"> Poll: '.$poll[0]->poll_question.'</label></b>');
			$pollhtml ='';
			$pollhtml .='<!-- start - 1st vote poll -->

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 poll-list-orange-bg">
    
    <!-- start : poll title -->
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-title">
    <ul class="list-inline">
    <li><img src="'.$C->SITE_URL.'static/images/icon-poll-24.png" class="img-responsive"></li>
    <li>'.$poll[0]->poll_question.'
    </li>
    </ul>  
    </div>    
    <!-- end : poll title -->';
	if(count($pollanswer) <=0){

			foreach($poll as $keys=>$row)
			{

				if($row->answer!="" && count($pollanswer)<=0)
				{

     $pollhtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">

    <!-- start : poll results -->

     <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content poll-parent-radio-margin">
    <ul class="list-unstyled poll-radio">
    <li>
<input onclick="changeurl('.$row->poll_id.','.$row->poll_answer_id.')" id="'.$keys.$row->poll_id.'" type="radio" name="radio11"  class="radios option'.$row->poll_answer_id.' radio'.$row->poll_id.' "/>

<label for="'.$keys.$row->poll_id.'">'.$row->answer.'</label></li>
 
    </ul> 

    </div>

    <!-- end : poll results -->

    </div>';

     

     }
				else if($row->answer!="")
				{
					$countpollanswer=$buff->is_countpollanswer($row->poll_id,$row->poll_answer_id);
					
				}

			}
	}else{
	$pollper =$buff->getpercentagesofpollanswers($poll[0]->poll_id);
	$totalpollcnt =$buff->totalpollcnt($poll[0]->poll_id);
	$pollhtml .='
<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content">';

 foreach($pollper as $keys=>$vals){
	 $percentage = ($vals->cnt/$totalpollcnt->totalcnt)*100;
	 if($percentage <10){
		 $width= '10';
		 
	 }else{
		  $width= $percentage;
		 
	 }
	 if($keys == 0){
		 $clor ='success';
	 }elseif($keys == 1){
		  $clor ='info';
		 
	 }elseif($keys == 2){
		 $clor ='warning';
	 }
	 elseif($keys == 3){
          $clor ='danger';
		 
	 }
	 elseif($keys == 4){
		$clor ='least'; 
	 }
    $pollhtml .='<strong>'.$vals->answer.'</strong>
    <div class="progress">
    <div class="progress-bar progress-bar-'.$clor.'" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width:'.$width.'%">
      <strong>('.round($percentage,2).'%)</strong>  
    </div>
  </div>';
 }




    $pollhtml .='</div>
	';
		
	
	}
	$pollhtml .='<!-- start : poll button download results -->
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">';
	if(count($pollanswer) <=0){
		$pollhtml .='<a type="submit" class="button-vote" onclick="checkoption('.$row->poll_id.')" id="suboption'.$row->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=answer&poll_id='.$row->poll_id.'&from='.$user->id.'" >Vote</a>';
		if($poll[0]->user_id == $user->id){
		$pollhtml .='<a type="submit"   id="suboption'.$row->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$row->poll_id.'" ><button type="submit" class="button-submit-results">Download Results</button></a>';
			
		}
	
   }else{
        if($poll[0]->user_id == $user->id){
		$pollhtml .='<a type="submit"  id="suboption'.$row->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$row->poll_id.'" ><button type="submit" class="button-submit-results">Download Results</button></a>';

			
		}
   }
     $pollhtml .='</div>
    <!-- end : poll button download results -->
   
    </div>
    <!-- end - 1st vote poll -->';
    
	$tpl->layout->inner_block->setVar('activity_answer',$pollhtml);		
		
	$tpl->layout->inner_block->saveInBlockPart('activity_poll');
		}
		//end of poll
		if( count( $buff->post_attached ) > 0 ){ 
			
			$tpl->layout->useInnerBlock('activity-attachments'); 
			
			if( isset($buff->post_attached['file']) ){
				foreach($buff->post_attached['file'] as $k => $file){
					$tpl->layout->inner_block->setVar('activity_attachments_files', '<a class="icon file '.(isset($file->filetype)? $file->filetype : '').'" href="'.$C->SITE_URL.'getfile/pid:'.$buff->post_tmp_id.'/attid:'.intval($k).'" title="'.$file->title.'">'.$file->title.'</a><span class="clear-right"></span>');
				}
			}
			
			if( isset($buff->post_attached['image']) ){
				foreach($buff->post_attached['image'] as $image){
					$tpl->layout->inner_block->setVar('activity_attachment_images', '<a target="_blank" href="'.($C->STORAGE_URL.'attachments/'.$network->id.'/'.$image->file_preview).'" class="lightbox-image image-thumb cboxElement"><img alt="Image" src="'.$C->STORAGE_URL.'attachments/'.$network->id.'/'.($image->file_thumbnail).'" /></a>');
					$tpl->layout->inner_block->setVar('activity_attachment_images_preview', '<a target="_blank" href="'.($C->STORAGE_URL.'attachments/'.$network->id.'/'.$image->file_preview).'" class="lightbox-image image-thumb cboxElement"><img alt="Image" src="'.$C->STORAGE_URL.'attachments/'.$network->id.'/'.($image->file_preview).'" /></a>');
				}
			}

			$tpl->layout->inner_block->saveInBlockPart( 'activity_attachments' );
					//echo '<pre>';print_r($buff->post_attached['link']);

			
						if( isset($buff->post_attached['link']) ){
				foreach($buff->post_attached['link'] as $link){
					$tpl->layout->useInnerBlock('activity-attachments-link');
					$tpl->layout->inner_block->setVar('activity_attachments_link', urldecode($link->link)); 
					$tpl->layout->inner_block->setVar('activity_attachments_link_title', isset($link->title)? $link->title : $link->link);
					$tpl->layout->inner_block->setVar('activity_attachments_link_description', isset($link->description)? $link->description : '');
					if($link->status ==1){
						$st  ="Active";
					}
					if($link->status ==2){
						$st  ="Cancelled";
					}
					if($link->status ==0){
						$st  ="Expired";
					}
					if($user->id != $buff->post_user->id){
					if($link->event_status !=2  ){
						
						if( ($link->event_status =='' &&  $link->edit_status =='')   ){
						$tpl->layout->inner_block->setVar('activity_attachments_link_description', '
							<div id="acc-'.$link->post_id.'"><strong>User Response:</strong><input type="radio"  class="accept" name="accept" onclick="myFunction('.$link->post_id.'1)" value="'.$link->post_id.'-1">Accept<input type="radio" class="accept" name="accept" onclick="myFunction('.$link->post_id.'3)"  value="'.$link->post_id.'-3">Reject</div>');
					    
					}
					if($link->event_status==1){
						$display ="block";
						
					}else{
						$display ="none";
					}
					if($link->event_status==3){
						$displayreject ="block";
						
					}else{
						$displayreject ="none";
					}
					if( ($link->event_status !=2 ||  $link->edit_status!=4) &&  ($link->event_status !=2 &&   $link->edit_status!=4) ){
					
					
					$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<div style="display:'.$display.';"id="accept-'.$link->post_id.'"><strong>User Response:</strong>Event Accepted</div>');
					$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<div style="display:'.$displayreject.';"id="reject-'.$link->post_id.'"><strong>User Response:</strong>Event Rejected</div>');
                  
					$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<input type="hidden" id="attach-'.$link->post_id.'"  value="'.$link->attachment_id.'">');
                    }else{
						if((($link->event_status!=4) &&   ($link->edit_status==4))){
							
					$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<div style="display:'.$display.';"id="accept-'.$link->post_id.'"><strong>User Response:</strong>Event Accepted</div>');
					$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<div style="display:'.$displayreject.';"id="reject-'.$link->post_id.'"><strong>User Response:</strong>Event Rejected</div>');
                  
							
						}else{
						$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<div>This event was no longer available.</div>');
						}
					}
					if($link->event_status == 5){
						$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<div>This event was modified.</div>');
						
					}
					$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<div>Status:'.$st.'</div>');

					
					
					
					}else{
						
						$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<div>Event Cancelled</div>');

						
					}
					
					}else{
						/*if((($link->event_status!=2) &&   ($link->edit_status!=4)) || (($link->event_status!=4) &&   ($link->edit_status==4))   ){ */
						if($link->event_status !=4){
						$tpl->layout->inner_block->setVar('activity_attachments_link_description', 
							
   '<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content event-list-blue-bg" style="padding:0px 0px 0px 10px;">
    <ul class="list-inline">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-status-event.png" class="img-responsive"></li>
    <li>Status - <span class="txt-accepted">'.$st.'</span></li>
    </ul>  
    </div>');

						$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<div class="btn-download-padding  event-list-blue-bg"><a href="'.$C->SITE_URL.'dashboard?pid='.$link->post_id.'"><input class="button-submit-results" type="button" name="download" value="Download Results"></a></div>');

						}else{
							if($link->status == 2 && $link->edit_status!=4 ){
								$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<div><strong>status:</strong>Cancel</div>');

								
							}else{
							$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<div><strong>Status:</strong>'.$st.'</div>');

							$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<div>This event was no longer available.</div>');

							}

							
						}
						
					}

					if(isset($link->image) && !empty($link->image)){
						$tpl->layout->inner_block->setVar('activity_attachments_link_image', '<a target="_blank" href="{%activity_attachments_link%}" class="thumb"><img src="'. $C->STORAGE_URL.'attachments/1/'.$link->image.'"></a>');		
					}
					
					$tpl->layout->inner_block->saveInBlockPart( 'activity_attachments_links' );
				}
			}


			
			
			if( isset($buff->post_attached['videoembed']) ){
				foreach($buff->post_attached['videoembed'] as $vid){

					$mobile_embed = str_replace('&autoplay=1','',$vid->embed_code);
					$mobile_embed = str_replace('width="460"','width="320"',$mobile_embed);
					$mobile_embed = str_replace('height="288"','height="180"',$mobile_embed);
					
					$tpl->layout->useInnerBlock('activity-attachments-videoembed');
					$tpl->layout->inner_block->setVar('activity_videoembed_html', htmlspecialchars($vid->embed_code));
					$tpl->layout->inner_block->setVar('activity_videoembed_mobile', $mobile_embed);
					
					
					
					$tpl->layout->inner_block->setVar('activity_videoembed_img_link', $C->STORAGE_URL.'attachments/1/'.$vid->file_thumbnail );
					$tpl->layout->inner_block->setVar('activity_videoembed_img_origin_link', $C->STORAGE_URL.'attachments/1/'.  str_replace('thumb.gif', "origin.jpg", $vid->file_thumbnail) );
					$tpl->layout->inner_block->setVar('activity_videoembed_link_href', $vid->orig_url);
					$tpl->layout->inner_block->setVar('activity_videoembed_link_title', isset($vid->title)? $vid->title : '');
					$tpl->layout->inner_block->setVar('activity_videoembed_link_description', isset($vid->description)? $vid->description : '');
					
						
					$tpl->layout->inner_block->saveInBlockPart( 'activity_attachments_links' );
				}
			}
		}
		if(isset($buff->poll_id) && $buff->poll_id!="" )
		{
			foreach($buff->posts_id as $val)
			{
				$tpl->layout->inner_block->setVar('activity_attachments_files', '<a href="www.google.com">test</a>');
			}
			
		}
		$tpl->layout->block->setVar('comments_thread_id', htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}'));

		if( $comments_num > 0 && (!$page->is_mobile || $onViewPage) ){
			
			$tpl->layout->useInnerBlock('activity-comments-container');

			if( ( $buff->post_commentsnum > $C->POST_LAST_COMMENTS ) && !$onViewPage ){
				$tpl->layout->inner_block->setVar('show_all_activity_comments', '<a href="'.$buff->permalink.'" class="show-all-comments"  data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'" data-role="services" data-namespace="comments" data-action="showAll"><span>'.$page->lang('activity_option_show_all_comments', array('#NUM_COMMENTS#'=>$buff->post_commentsnum)).'</span></a>');
			}
				
			$tpl->layout->inner_block->saveInBlockPart( 'activity_comments_container' );
			
			$newcomments = explode(',', $buff->newcomments);
			$new = FALSE;
			foreach( $comments as $c ){
				if( $page->param('tab') == 'commented' && in_array($c->comment_id, $newcomments) ){
					$new = TRUE;
				}
				$tpl->initRoutine('SingleActivityComment', array( & $c, FALSE, $new ));
				$tpl->routine->load();
			}		
		}elseif( !$comments_num && $page->is_mobile && $onViewPage ) {

			$tpl->layout->useInnerBlock('activity-comments-container');
			$tpl->layout->inner_block->saveInBlockPart( 'activity_comments_container' );
		} elseif( $page->is_mobile && !$onViewPage ) {
			//ECHO 'HIII';
			$tpl->layout->block->setVar('activity_nocomments', 'no-comments');
			$tpl->layout->useInnerBlock('activity-comments-container');
			$tpl->layout->inner_block->saveInBlockPart( 'activity_comments_container' );
		}
		$tpl->layout->block->save( 'activity-container-list', true ); 
	}
	

