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
		$post_view_cnt = $buff->is_post_view_cnt($buff->post_id);
        if($post_view_cnt->cnt > 0){
            $finalpostcnt      = $post_view_cnt->cnt;
			$posttviewbtn_btn = '<a class="" href="" data-role="services"  >'.$network->format_num($finalpostcnt).'</a>';
		}else{
			$posttviewbtn_btn =rand(1,9);  
		}

		$comments = (! $onViewPage)? $buff->get_comments() : $buff->get_all_comments();
		$comments_num = count($comments);
		if(!empty($buff->post_id)){
		
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
			       $like_number = $buff->new_liked_count($buff->post_id);
					$likes_number        =$like_number->likecount;
					//$likes_number = isset($buff->post_likes['post'])? count($buff->post_likes['post']) : 0;
					$css="icons";
							$is_spam  = $buff->is_spam($buff->post_id,$buff->post_type);
				if($is_spam =="1"){
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'"><em><img  src="'.$C->SITE_URL.'static/images/icons/SPAM.png" title="Undospam"></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png" title="Markspam"></a>';
                }
				
			//exit;
			//return;
			if( preg_match( "#iPhone#i", $_SERVER['HTTP_USER_AGENT'] )  || preg_match( "#iPod#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Android#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Phone#i", $_SERVER['HTTP_USER_AGENT'] )) {
					    $whats =' <li><a data-text="Streetbuzz" data-link="'.$C->SITE_URL.'/view/post:'.$buff->post_id.'" class="whatsapp w3_whatsapp_btn w3_whatsapp_btn_large">Whatsapp</a></li>';
					  }else{
			                          $whatsurl =$C->SITE_URL.'/view/post:'.$buff->post_id;
								    $whats =' 
								    <li><a onclick="myFunctionpostshare('.$buff->post_id.')" target="_blank" href="https://web.whatsapp.com/send?text='.$whatsurl.'" >Whatsapp</a>
</li>
								    ';
					  }
			
			 if(!$user->is_logged ){
							 $whats .='<li><a onclick="myFunctionpostshare('.$buff->post_id.')" href="http://www.facebook.com/sharer.php?u='.urlencode($buff->permalink).'"  target="_blank" >Facebook</a></li>';	    
								    
}
			
				if( $user->is_logged ){
				 if($likes_number > 0){					
					$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'">'.$likes_number.'</a>';
				   }else{
					 $showlikes_btn ='';  
				   }
				   	$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'">'.($is_reshared? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Undo-Rebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                    if($resharecnt> 0){					

					$resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'">'.$resharecnt.'</a>';
                    }else{
						$resharecnt ='';
						
					}
					$replies                =$buff->checkreplies($buff->post_id);
					if(!empty($replies)){
						$popup ='<span class="reply icon-ftr icon-ftr-reply"><a  style="cursor:pointer" onclick="childpopuptimeline('.$replies->parent_id.','.$replies->replay_id.')" ><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a></span>';

						
					}else{
						$popup ='<span class="reply icon-ftr icon-ftr-reply"><a  style="cursor:pointer" onclick="parentreplay('.$buff->post_id.')" ><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a></span>';
						
					}
					 
					   				//<li><a onclick="myFunctionpostshare('.$buff->post_id.')" href="http://twitter.com/intent/tweet?text='.urlencode($buff->permalink).': '.urlencode(htmlspecialchars($user->info->username.': '.$buff->post_message)).'"  target="_blank" >Twitter</a></li>';

					 	  
								   $fsfs ='
								  <li><a onclick="myFunctionpostshare('.$buff->post_id.')" href="http://www.facebook.com/sharer.php?u='.urlencode($buff->permalink).'"  target="_blank" >Facebook</a></li>
								  <li><a onclick="myFunctionpostshare('.$buff->post_id.')" href="http://twitter.com/intent/tweet?url='.urlencode($buff->permalink).'"  target="_blank" >Twitter</a></li>';
								  }
								  
		if($buff->post_type=="private"){
	   $comments       =$buff->postcomments($buff->post_id);


	   $commenthtml ='';
	   foreach($comments as $keys=>$vals){
		 //Here check type
		 $type_post =$buff->checktype_post($vals->id);
		 $imagedes ='';
			 $filedes ='';
		 if(!empty($type_post)){
			 
			 foreach($type_post as $imagekeys=>$imagevals){
				 
				 if($imagevals->type=="image"){
					  $tmp = @unserialize(stripslashes($imagevals->data));
                    if (!$tmp) {
                        $tmp = preg_replace_callback('!(?<=^|;)s:(\d+)(?=:"(.*?)";(?:}|a:|s:|b:|d:|i:|o:|N;))!s', 'serialize_fix_callback', stripslashes($imagevals->data));
                        $tmp = @unserialize(stripslashes($tmp));
                    }
				      $imagedes .= '<a target="_blank" href="'.($C->STORAGE_URL.'attachments/'.$network->id.'/'.$tmp->file_preview).'" class="lightbox-image image-thumb cboxElement"><img alt="Image" src="'.$C->STORAGE_URL.'attachments/'.$network->id.'/'.($tmp->file_thumbnail).'" /></a>';

					 
				 }if($imagevals->type=="file"){
					  $tmpfile = @unserialize(stripslashes($imagevals->data));
                    if (!$tmpfile) {
                        $tmpfile = preg_replace_callback('!(?<=^|;)s:(\d+)(?=:"(.*?)";(?:}|a:|s:|b:|d:|i:|o:|N;))!s', 'serialize_fix_callback', stripslashes($imagevals->data));
                        $tmpfile = @unserialize(stripslashes($tmp));
                    }
					$filedes .= '<a class="icon file '.(isset($tmpfile->filetype)? $tmpfile->filetype : '').'" href="'.$C->STORAGE_URL.'attachments/'.$network->id.'/'.$tmpfile->file_original.'" title="'.$tmpfile->title.'">'.$tmpfile->title.'</a><span class="clear-right"></span>';
                 $ext = pathinfo($tmpfile->file_original, PATHINFO_EXTENSION);


					 
				 }
			 }
		 }
		 
		 
		   
	   	$commenthtml .='<li>
	<div class="row comment-ctrl comment " style="margin-left:0px; margin-right:0px;">

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 comment-container zeropadding">
	
	<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 image-div zeropadding">
	
		<a href="'.userlink($vals->username).'" class="bizcard" data-userid="'.$vals->user_id.'" ><img src="'.getAvatarUrl($vals->avatar, 'thumbs1').'" alt="'.getThisUserCommunityName($vals->username).'" ></a>		

	</div><!--/ col-md-1 -->

	<div class="col-xs-11 col-sm-11 col-md-11 col-lg-11 ">

	<div class="comment-author pull-left"><a href="'.userlink($vals->username).'" class="author bizcard" data-userid="'.$vals->userid.'">'. $vals->username .'</a></div>

		
	
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 activity-content zeropadding" style="margin-top:0">
    '.$vals->message.'
	</div>
	<!--links start-->
	<div class="attachments lightbox-enabled">
	<div class="images">
	<div class="list-link-container test">

	'.$imagedes.'
	</div>
	</div>
	<div class="files col-xs-12 col-sm-12 col-md-12 col-lg-12">
	'.$filedes.'

	</div>

</div>
<!--links end-->

	<div class="attachments lightbox-enabled"></div>
			<div class="meta-info">
				<span class="permlink">'.post::parse_date($vals->date).'</span>
	</div>

	
	</div><!--/ col-md-11 -->
	</div><!--/ col-md-12 -->
	</div><!--/ row -->
	
</li>';
	   }

					$tpl->layout->block->setVar('comment_footer','
					
<div class="comment-chield" style="display:block" id="chield'.$obj->id.'">
				<div class="comments-editor data-content-placeholder comments-thread-container" data-value="'.$buff->post_id.'" style="display:block" >
				<div class="zeropadding" style="display:block;" >
		
<ol class="comments-thread " style="padding:0" >
	
'.$commenthtml.'





</ol>

</div>


				<div class="activity-header commentpost'.$obj->id.'">
				
				</div>
				
				<div class="comments-editor-content commentcontainer'.$obj->id.'">
					<div class="user-status-field htmlarea">
					<div class="commentpoll commentpoll'.$obj->id.'">
					</div>
						<div class="textarea-wrap comment">
							<textarea  id="message'.$obj->id.'" name="message" placeholder="Buzz something..."></textarea>
							<div class="textarea-highlighter"><span></span></div>
						</div>
					</div>

<style>
.image-upload > input
{
    display: none;
}

.image-upload img
{
    width: 100%;
    cursor: pointer;
    margin-top: 8px;
}
.image-upload .grayscale{ 
    filter: grayscale(100%) contrast(1%)!important;
    -webkit-filter: grayscale(100%) contrast(1%)!important;  /* For Webkit browsers */
    filter: gray;  /* For IE 6 - 9 */
    -webkit-transition: all .3s ease;  /* Transition for Webkit browsers */
}
.image-upload .grayscale:hover{ 
    filter: grayscale(0%);
    -webkit-filter: grayscale(0%);
    filter: none;
}



</style>


<!--/ start : uploading data -->
<div class="comment attachments uploads lightbox-enabled" id="0f768e1c400608" style="display:block">
				<div class="images col-xs-12 col-sm-12 col-md-12 col-lg-12" id="imgdis'.$buff->post_id.'"></div>
				<div class="links"></div>
				<div class="files col-xs-12 col-sm-12 col-md-12 col-lg-12" id="linksdis'.$buff->post_id.'"></div>
			</div>
			<!--/ end : uploading data -->


<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 zeropadding">
<div class="image-upload col-xs-6 col-sm-4 col-md-4 col-lg-4">
    <label for="sortpicture'.$buff->post_id.'">
        <img src="'.$C->SITE_URL.'/static/images/icons/FILEUPLOAD.png"  class="grayscale" id="profic" />
    </label>

<input id="sortpicture'.$buff->post_id.'" onchange="myimageload('.$buff->post_id.')" type="file" name="userfile" rel="'.$buff->post_id.'" />
</div>


			
<div class="buttons col-xs-6 col-sm-8 col-md-8 col-lg-8 zeropadding">		 
<button class="status-btn post-btn btn blue" onclick="mybuzz('.$buff->post_id.')"  data-role="services" data-namespace="comments" data-action="set"><span >Buzz</span></button>
</div>		


</div><!--/ col-md-12 col-lg-12 -->




						</div>
					</div>
				</div>');
		}
		
		
				 $share_count =$buff->share_count($buff->post_id);

		  if($buff->post_type=="public"){
			   $commentstr = "";
		       if($onViewPage){
		               $commentstr = '<div class="comment-list icon-ftr f1"><a href="" class="add-comment action" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'" data-role="services" data-namespace="comments" data-action="activityAddComment"><i class="fa fa-comments"></i></a>
	</div>';
		               
		           }
			  				if( $user->is_logged ){
								
				$footercnt ='
				'.$popup.'
				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
<!--
				<div class="agree-list icon-ftr f1"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'">'.($is_agree? '<img  width="" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>
-->
              '.$commentstr.'

				<span class="reshare-list  icon-ftr">'.$reshare_content.''.$resharecnt.'</span>
					
				';
				$markcnt ='<div class="like-list icon-ftr">'.$mark_content.'</div>';	
							}else{ 
					$footercnt ='<span class="reply icon-ftr icon-ftr-reply" id="aa"><a href="'.$C->SITE_URL.'home"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a></span> <div class="like-list icon-ftr">
					
					<a href="'.$C->SITE_URL.'home"> : <img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>'.'</a>
					
					</div>
	

                 <span class="reshare-list  icon-ftr">'.$reshare_content.'</span>';
				 $markcnt ='<div class="like-list icon-ftr">'.$mark_content.'</div>';	

								
							}
			
			$tpl->layout->block->setVar('comment_footer','
			

               <div class="11 activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12 footer-layout">
               <input type="hidden" id="time-'.$buff->post_id.'" value="'.post::parse_date($obj->date).'" />
			   
			   '.$footercnt.'
				
<div class="dropdown share icon-ftr ts" >
							   <a  style="cursor:pointer" href="" class="menu-btn"><img style="height:22px;width:22px;" class="icons" src="'.$C->SITE_URL.'themes/FishingEnthusiastTheme/images/social_share.png" title="Share"/></a>
							   <ul class="menu-options" id="'.$buff->post_id.'"  data_id1="'.$buff->post_id.'">
							     '.$whats.'
							       '.$fsfs.'
								  <li><a  onclick="myFunctionpostshare('.$buff->post_id.')" data_id="'.$buff->post_id.'" href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($buff->permalink).'&title='.urlencode(htmlspecialchars($buff->post_message)).'&source='.urlencode($buff->permalink).'&summary='.urlencode($buff->permalink).'"  target="_blank" >Linkedin</a></li>
								  <li><a onclick="myFunctionpostshare('.$buff->post_id.')" href="http://plus.google.com/share?url='.urlencode($buff->permalink).'"  target="_blank" >Google Plus</a></li>
								 
							   </ul>
							  <span class="'.$buff->post_id.'">
							        '.$share_count.'
							   <span>
							</div>
							 					
	
				</div>


				'
				);
		}
		  

		
		}
		else
		{
		
			$tpl->layout->block->setVar('activity_delete','0');
		}
		if(isset($buff->post_user->username) ){
		
		//echo '<pre>';print_r($buff->post_user);

		$tpl->layout->block->setVar('activity_user_avatar', '<a href="'.userlink($buff->post_user->username).'" class="avatar bizcard 111111" data-userid="'.$buff->post_user->id.'"><img src="'.getAvatarUrl($buff->post_user->avatar, 'thumbs1').'" alt="'.getThisUserCommunityName($buff->post_user).'" /></a>');

		$tpl->layout->block->setVar('activity_mobile_user_avatar', '<a href="'.userlink($buff->post_user->username).'" class="avatar bizcard 22222" data-userid="'.$buff->post_user->id.'"><img src="'.getAvatarUrl($buff->post_user->avatar, 'thumbs4').'" alt="'.getThisUserCommunityName($buff->post_user).'" /></a>');

		$tpl->layout->block->setVar('activity_user_avatar_bkg', getAvatarUrl($buff->post_user->avatar, 'thumbs5'));

		$tpl->layout->block->setVar('activity_user_username', '<a href="'.userlink($buff->post_user->username).'" class="author bizcard" data-userid="'.$buff->post_user->id.'">'. getThisUserCommunityName($buff->post_user) .'</a>');
		
		$tpl->layout->block->setVar('activity_permlink', '<a href="'.$buff->permalink.'?'.$buff->title.'" class="permlink">'.post::parse_date($buff->post_date).' <span class="glyphicon glyphicon-link"></span></a>');
// 		$tpl->layout->block->setVar('activity_permlink', '<a href="'.$buff->permalink.'" class="permlink">'.post::parse_date($buff->post_date).' <span class="glyphicon glyphicon-link"></span></a>');

		if( $buff->post_type == 'public' ){
			$tpl->layout->block->setVar('activity_user_activity_group', ($buff->post_group? $page->lang('postgroup_in').' <a href="'.$buff->post_group->group_link.'">'.$buff->post_group->title.'</a>' : '') );
		}else if( $buff->post_type == 'private' ){
			//@TODO: remove this when we add different private message template
			$tpl->layout->block->setVar('activity_user_activity_group', ((isset($buff->post_to_user->username) && $buff->post_to_user->username!=$user->info->username)? '>> <a href="'.userlink($buff->post_to_user->username).'">'.getThisUserCommunityName($buff->post_to_user).'</a>' : '') );
		}
		//check post have replies or not 

		$replies                =$buff->checkreplies($buff->post_id);
		if(!empty($replies)){
			$tpl->layout->block->setVar('replies','<a class="author bizcard replies-to" onclick="replaycontent('.$replies->alternate_parent_id.','.$buff->post_id.')" data-userid="'.$replies->userid.'">Replies to @'. $replies->handler .'</a>');
			
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
				                       
				$myincorrectintradatahtml .='<div class="prediction-buzz-data">'.$prediction_data[0]->asset_name.'($'.$prediction_data[0]->ticker.')<img src="'.$C->SITE_URL.'/static/images/icons/'.$imag.'"> to be '.$con.' by '.$percentage.'% from '.$prediction_data[0]->prediction_base_price.' in '.substr($prediction_data[0]->end_date,0,10).' because of '.$prediction_data[0]->predict_reason.'.</div>';
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
				$myincorrectintradatahtml .='<div style="background-color:#e3f8fe;font-size:12px;height:auto;padding: 10px;"> Your prediction on '.$prediction_data[0]->asset_name.'($'.$prediction_data[0]->ticker.') done  on '.substr($prediction_data[0]->end_date,0,10).' was a '.$type.' <img src="'.$C->SITE_URL.'static/images/icons/'.$imag.'">.'.$handset.' 
				</div>';
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
			


//START - For Desktop Screen		
$assetdatahtml ='<div class="hidden-xs intraday-box"><div>'.$str.'</div>
	<table class="table table-bordered intraday-table" width="100%">
    <thead>
      <tr class="box-sub-title intraday-title">
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
			$assetmarketpresentprice = $buff->assetmarketpresentprice($assetvals->ticker);

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
        <td>'.$assetvals->current_price.'</td>
        <td>'.$assetvals->stoploss_price.'</td>
		<td>'.$assetvals->predicted_price.'</td>
		<td>'.$assetmarketpresentprice.'</td>

		<td>'.$img.'</td>
      </tr>
      ';
				
			}
			$assetdatahtml .='</tbody>
  </table></div>';	
// END - For Desktop Screen




  //START - For Small Screens
  $assetdatahtml .='<div class="visible-xs intraday-box"><div>'.$str.'</div>
   <table class="table intraday-table" width="100%">
    <thead>
      <tr>
        <th class="intraday-title">Asset</th>';
        foreach($assetdata as $assetkeys=>$assetvals){
        
         $assetdatahtml .='<td class="intraday-content">$'.$assetvals->ticker.'</td>';
    }

         $assetdatahtml .='</tr>
         <tr>
       <th class="intraday-title">Price @ Buzzing</th>';
	    foreach($assetdata as $assetkeys=>$assetvals){
        $assetdatahtml .='<td class="intraday-content">'.$assetvals->current_price.'</td>';
		}
        $assetdatahtml .='</tr>
         <tr>
        <th class="intraday-title">Stop Loss</th>';
	    foreach($assetdata as $assetkeys=>$assetvals){

         $assetdatahtml .='<td class="intraday-content">'.$assetvals->stoploss_price.'</td>';
		}
         $assetdatahtml .='</tr>
         <tr>
        <th class="intraday-title">Target Price</th>';
		 foreach($assetdata as $assetkeys=>$assetvals){
        $assetdatahtml .='<td class="intraday-content">'.$assetvals->predicted_price.'</td>';
		 }
        $assetdatahtml .='</tr>
         <tr>
        <th class="intraday-title">Current Price</th>';
		 foreach($assetdata as $assetkeys=>$assetvals){
						 $assetmarketpresentpricemobile = $buff->assetmarketpresentprice($assetvals->ticker);

        $assetdatahtml .='<td class="intraday-content">'.$assetmarketpresentpricemobile.'</td>';
		 }
        $assetdatahtml .='</tr>
          <tr>
       <th class="intraday-title">Result</th>';
	   foreach($assetdata as $assetkeys=>$assetvals){
		   				if($assetvals->result == "1"){
					$img ='<img  src="'.$C->SITE_URL.'static/images/tick.png" width="16"/>';
					
				}elseif($assetvals->result =="0"){
					$img ='<img  src="'.$C->SITE_URL.'static/images/wrong.png"/>';
					
				}else{
					$img ='Open';
					
				}
        $assetdatahtml .='<td class="intraday-content">'.$img.'</td>';
	   }
        $assetdatahtml .='</tr>

   </thead>
   
  </table>
  </div>';
  //END - Small Screens
			






		//	$tpl->layout->block->setVar('activity_text',$assetdatahtml);
			$tpl->layout->block->setVar('activity_text', "<iframe id='a52a04d1' name='a52a04d1' src='https://streetbuzz.co/revive/www/delivery/afr.php?zoneid=2&amp;cb=INSERT_RANDOM_NUMBER_HERE' frameborder='0' scrolling='no' width='700' height='125'><a href='http://streetbuzz.co/revive/www/delivery/ck.php?n=a4c53157&amp;cb=INSERT_RANDOM_NUMBER_HERE' target='_blank'><img src='http://streetbuzz.co/revive/www/delivery/avw.php?zoneid=2&amp;cb=INSERT_RANDOM_NUMBER_HERE&amp;n=a4c53157' border='0' alt='' /></a></iframe>
");


			
			
		}else{
		    $parse_text = $buff->parse_text();
		    $checkadexist =$buff->checkadexist($buff->post_user->id);
		    
		   
		 if( preg_match( "#iPhone#i", $_SERVER['HTTP_USER_AGENT'] )  || preg_match( "#iPod#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Android#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Phone#i", $_SERVER['HTTP_USER_AGENT'] )) {
		             $is_mobile = 1;
		        }else{
		            $is_mobile = 0;
		        }
	     /* Long Read Logic
		      @posttype 2 is long read news
		 */
		$posttypeRes =$buff->checkposttype($buff->post_id);
		 if(!empty($posttypeRes) && $posttypeRes->posttype == 2){
		        
		        $decodemessages =json_decode($buff->post_message,true);
                if(!empty($decodemessages['post'])){
		        $decodemessagespost = $decodemessages["post"];

		        
		        if(count($decodemessagespost) > 0){
		            $finalstr = "";
		            $adsarr = array();
		            $decodecnt = count($decodemessagespost);
		            $paraads = [];

		             foreach($decodemessagespost as $decodekeys=>$decodevals){
		                  $key = array_keys($decodevals);
		                  $deocdkey =  $key[0];
		                  if($deocdkey == "h3"){
		                      $adsarr['official'] = $decodekeys+1;
		                  }
		                  if($deocdkey == "break"){
		                      $nextkey = $decodekeys+1;
		                      if(!empty($decodemessagespost[$nextkey])){
		                          $nextbrakrecords = $decodemessagespost[$nextkey];
		                   $key = array_keys($nextbrakrecords);
		                   $deocdkey =  $key[0];
		                   if($deocdkey == "break"){
		                       $nextnextkey = $nextkey+1;
		                       if(!empty($decodemessagespost[$nextnextkey])){
		                           for($m=$nextnextkey;$m<$decodecnt;$m++){
		                               $nextnextbrakrecords = $decodemessagespost[$nextnextkey];
		                               $nextkey = array_keys($nextnextbrakrecords);
		                   $deocdenextkey =  $nextkey[0];
		                    if($deocdenextkey == "break"){
		                        $nextnextkey = $m;
		                        
		                        
		                    }else{
		                        break;
		                    }
		                       }
		                           
		                       }
		                    }
		                    }
		                      array_push($paraads,$nextnextkey);
                           }
		             }
		             $newads = [];
		             if(!empty($paraads)){
		             $paraads = array_unique($paraads);
		             $paraempty = array();
		             $finalparaads = array_merge($paraempty,$paraads);
		              for($m=0;$m<=2;$m++){
		                  if(!empty($finalparaads[$m]) ){
		                      if($m == 0){
		                          $adsarr['para1'] = $finalparaads[$m];
		                          
		                      }
		                      if($m == 1){
		                          $adsarr['para2'] = $finalparaads[$m];
		                          
		                      }
		                      if($m == 2){
		                          $adsarr['para3'] = $finalparaads[$m];
		                          
		                      }
		                  }
		               }
		              }

		             foreach($decodemessagespost as $decodekeys=>$decodevals){
		                $key = array_keys($decodevals);
		               

		                $deocdkey =  $key[0];

		                $deocdevalue = $decodevals[$deocdkey];
		                $createtext = $buff->createLongreadElements($deocdkey,$deocdevalue);
		                $finalstr .= $createtext;
		                 if(!empty($adsarr)){
		                    $longadstype = array_search($decodekeys,$adsarr);
		                    if(!empty($longadstype)  && $longadstype == "official"){
		                             
		      	/* Official Adds Logic */
				$officalsourcetype = $checkadexist->ads_access_source;
				$sortimage = $C->STORAGE_URL.'advs/'.$checkadexist->big_image;
		        $bigimage =$C->STORAGE_URL.'advs/'.$checkadexist->big_image;
		        $officalsourcetype = $checkadexist->ads_access_source;
		           if(!empty($officalsourcetype) && $officalsourcetype == 1 && $is_mobile == 1 && $checkadexist->whatsapp_number != '' ){
		               /* whatsup sending */
		               if(strpos( $checkadexist->whatsapp_number,"+91") !== false){
    		            }else{
    		                 $checkadexist->whatsapp_number ="+91 ".$checkadexist->whatsapp_number; 
    		              } 
    		              $adsadvancehtml	    =$buff->whatsuphtml($checkadexist->whatsapp_number,$checkadexist->id,$buff->post_id);
    		              $display_url ="https://api.whatsapp.com/send?phone=".$checkadexist->whatsapp_number."&text=Hello";
    		              $displaycontent =$buff->displayadscontentmedia($checkadexist->ad_display_type,$display_url,$sortimage);
		                  $longnewstr   = $displaycontent.$adsadvancehtml;
    		          
		              
		           }else if($officalsourcetype == 2 && $is_mobile == 1 && $checkadexist->callnow_number !='' ){
		                /* call now in mobile*/
		                if(strpos( $checkadexist->callnow_number,"+91") !== false){
    		              }else{
    		                 $checkadexist->callnow_number ="+91 ".$checkadexist->callnow_number; 
    		              }
    		               $adsadvancehtml	    =$buff->dialphonehtml($checkadexist->callnow_number,$checkadexist->id,$buff->post_id);
    		              $display_url ="tel:".$checkadexist->callnow_number;
    		              $displaycontent =$buff->displayadscontentmedia($checkadexist->ad_display_type,$display_url,$sortimage);
		                 $longnewstr   = $displaycontent.$adsadvancehtml;
		           }else if($officalsourcetype == 3 && $checkadexist->display_url != ''){
		               /* URL Click */
		                $display_url =$checkadexist->display_url;
		                 $displaycontent =$buff->displayadscontentmedia($checkadexist->ad_display_type,$display_url,$sortimage);
    		              $longnewstr   = $displaycontent;
		           }else{
		                $displaycontent =$buff->displayadscontentmedia($checkadexist->ad_display_type,$sortimage,$sortimage);
    		              $longnewstr   = $displaycontent;
		               
		           }
		            $finalstr .=$longnewstr;

          
		                    }
		                    
		                    if(!empty($longadstype) && $longadstype == "para1"){
		                      $checkparagaphexist =$buff->checkparagaphexist1($buff->post_user->id);
		                      if(!empty($checkparagaphexist)){
		                    
		                     $para1data = $checkparagaphexist;
		                       /* Para1 Adds Logic */
                $parasortimage = $C->STORAGE_URL.'advs/'.$para1data->big_image;
		        $para1sourcetype = $para1data->ads_access_source;
		           if(!empty($para1sourcetype) && $para1sourcetype == 1 && $is_mobile == 1 && $para1data->whatsapp_number != '' ){
		               
		               /* whatsup sending */
		               if(strpos( $para1data->whatsapp_number,"+91") !== false){
    		            }else{
    		                 $para1data->whatsapp_number ="+91 ".$para1data->whatsapp_number; 
    		              } 
    		              $display_url ="https://api.whatsapp.com/send?phone=".$para1data->whatsapp_number."&text=Hello";
    		               
    		              
    		              $adsadvancehtml	    =$buff->whatsuphtml($para1data->whatsapp_number,$para1data->id,$buff->post_id);
    		              $displaycontent =$buff->displayadscontentmedia($para1data->ad_display_type,$display_url,$parasortimage);
                          $paraads   = $displaycontent.$adsadvancehtml;
                       }else if($para1sourcetype == 2 && $is_mobile == 1 && $para1data->callnow_number !='' ){
		                /* call now in mobile*/
		                if(strpos( $para1data->callnow_number,"+91") !== false){
    		              }else{
    		                 $para1data->callnow_number ="+91 ".$para1data->callnow_number; 
    		              }
    		              $adsadvancehtml	    =$buff->dialphonehtml($para1data->callnow_number,$para1data->id,$buff->post_id);
    		              $display_url ="tel:".$para1data->callnow_number;
    		              $displaycontent =$buff->displayadscontentmedia($para1data->ad_display_type,$display_url,$parasortimage);
		                  $paraads   = $displaycontent.$adsadvancehtml;
		           }else if($para1sourcetype == 3 && $para1data->display_url != ''){
		               /* URL Click */
		                $display_url =$para1data->display_url;
		                 $knowmorehtml	    =$buff->knowmorehtml($display_url,$para1data->id,$buff->post_id);
		                 $displaycontent =$buff->displayadscontentmedia($para1data->ad_display_type,$display_url,$parasortimage);
		                 $paraads   = $displaycontent.$knowmorehtml;
		           }else{
		               $displaycontent =$buff->displayadscontentmedia($para1data->ad_display_type,$parasortimage,$parasortimage);
		               $paraads   = $displaycontent;
		               
		           }
		           $finalstr .=$paraads;
		                   

		                    }
		                    }
		                    /* Paragraph 2 Ads Logic */
		                 if(!empty($longadstype) && $longadstype == "para2"){
		                 $checkparagaphexist2 =$buff->checkparagaphexist2($buff->post_user->id);
                     if(!empty($checkparagaphexist2))
                     {
		                       $para2data = $checkparagaphexist2;
		                      
		                        $parasortimage1 = $C->STORAGE_URL.'advs/'.$para2data->big_image;
		                         $para2sourcetype = $para2data->ads_access_source;

		                      if(!empty($para2sourcetype) && $para2sourcetype == 1 && $is_mobile == 1 && $para2data->whatsapp_number != '' ){
		               /* whatsup sending */
		               if(strpos( $para2data->whatsapp_number,"+91") !== false){
    		            }else{
    		                 $para2data->whatsapp_number ="+91 ".$para1data->whatsapp_number; 
    		              } 
    		              $display_url ="https://api.whatsapp.com/send?phone=".$para2data->whatsapp_number."&text=Hello";
    		               
    		              
    		              $adsadvancehtml	    =$buff->whatsuphtml($para2data->whatsapp_number,$para2data->id,$buff->post_id);
    		               $displaycontent =$buff->displayadscontentmedia($para2data->ad_display_type,$display_url,$parasortimage1);
                         $paraads1 = $displaycontent.$adsadvancehtml;
                       }else if($para1sourcetype == 2 && $is_mobile == 1 && $para2data->callnow_number !='' ){
		                /* call now in mobile*/
		                if(strpos( $para2data->callnow_number,"+91") !== false){
    		              }else{
    		                 $para2data->callnow_number ="+91 ".$para2data->callnow_number; 
    		            }
    		              $adsadvancehtml	    =$buff->dialphonehtml($para2data->callnow_number,$para2data->id,$buff->post_id);
    		              $display_url ="tel:".$para2data->callnow_number;
    		              $displaycontent =$buff->displayadscontentmedia($para2data->ad_display_type,$display_url,$parasortimage1);
		              $paraads1   = $displaycontent.$adsadvancehtml;;
		           }else if($para2sourcetype == 3 && $para2data->display_url != ''){
		               /* URL Click */
		                $display_url =$para2data->display_url;
		                 $knowmorehtml	    =$buff->knowmorehtml($display_url,$para2data->id,$buff->post_id);
		                 $displaycontent = $buff->displayadscontentmedia($para2data->ad_display_type,$display_url,$parasortimage1);
		                 $paraads1   = $displaycontent.$knowmorehtml;
		           }else{
		                $displaycontent =$buff->displayadscontentmedia($para2data->ad_display_type,$parasortimage1,$parasortimage1);
    		          $paraads1   = $displaycontent;
		               
		           }
		              $finalstr .=$paraads1;
		            
                             
		                      
		                   }
		                 }
		                  /* Paragraph 3 Ads Logic */
		                 if(!empty($longadstype) && $longadstype == "para3"){
		                 $checkparagaphexist3 =$buff->checkparagaphexist3($buff->post_user->id);
		                 if(!empty($checkparagaphexist3)){
                  $para3data = $checkparagaphexist3;
                               $parasortimage3 = $C->STORAGE_URL.'advs/'.$para3data->big_image;
		                         $para3sourcetype = $para3data->ads_access_source;

		           if(!empty($para3sourcetype) && $para3sourcetype == 1 && $is_mobile == 1 && $para3data->whatsapp_number != '' ){
		               /* whatsup sending */
		               if(strpos( $para3data->whatsapp_number,"+91") !== false){
    		            }else{
    		                 $para3data->whatsapp_number ="+91 ".$para1data->whatsapp_number; 
    		              } 
    		              $display_url ="https://api.whatsapp.com/send?phone=".$para3data->whatsapp_number."&text=Hello";
    		               
    		              
    		              $adsadvancehtml	    =$buff->whatsuphtml($para3data->whatsapp_number,$para3data->id,$buff->post_id);
    		               $displaycontent =$buff->displayadscontentmedia($para3data->ad_display_type,$display_url,$parasortimage);
                         $paraads3 = $displaycontent.$adsadvancehtml;
                       }else if($para3sourcetype == 2 && $is_mobile == 1 && $para3data->callnow_number !='' ){
		                /* call now in mobile*/
		                if(strpos( $para3data->callnow_number,"+91") !== false){
    		              }else{
    		                 $para3data->callnow_number ="+91 ".$para3data->callnow_number; 
    		              }
    		              $adsadvancehtml	    =$buff->dialphonehtml($para3data->callnow_number,$para3data->id,$buff->post_id);
    		              $display_url ="tel:".$para3data->callnow_number;
    		              $displaycontent =$buff->displayadscontentmedia($para3data->ad_display_type,$display_url,$parasortimage);
		              $paraads3   = $displaycontent.$adsadvancehtml;
		           }else if($para3sourcetype == 3 && $para3data->display_url != ''){
		               /* URL Click */
		                $display_url =$para3data->display_url;
		                 $knowmorehtml	    =$buff->knowmorehtml($display_url,$para3data->id,$buff->post_id);
		               $displaycontent =$buff->displayadscontentmedia($para3data->ad_display_type,$display_url,$parasortimage);
		               $paraads3   = $displaycontent.$knowmorehtml;
		           }else{
		              $displaycontent =$buff->displayadscontentmedia($para3data->ad_display_type,$parasortimage,$parasortimage);
                      $paraads3   = $displaycontent;
		               
		           }
		            $finalstr .=$paraads3;
		                 }
		                 }
		                }
		            }
		            $parse_text = $finalstr;
		            
		        }
		        
		        
		    }
		    }
		     if(strpos($parse_text, "</p><p><br>") !== FALSE ) {
		           $parseexplodestr = explode("</p><p><br>", $parse_text);
		           $parse_text = implode("\n\n",$parseexplodestr);
		          

		     }
		     
		    	  

		    

		           if(strpos($parse_text, "\n") !== FALSE ) {
		                  $array1 = 0;
		                  $explodestr  = explode("\n", $parse_text);
		                  $explodestr = array_map('trim', $explodestr);
		                  /* some times html string coming different*/
		                  $ishtmlparsewrong = false;
		                  $isparaalladsexist = false;
		                  if($explodestr[0] == "<p>" || $explodestr[0] == "</p>"|| $explodestr[0] == "<p></p>"){
		                      $explodestr[0] = "<p><br>";
		                      $ishtmlparsewrong = true;
		                      
		                  }

		                  $totalarrcnt = count($explodestr);
		                  $explodestrkeys =  array_keys($explodestr, "", true);
		                  
		                  $fileterarr = array_filter($explodestr);

		                 

		                  $checkparagaphexist =$buff->checkparagaphexist1($buff->post_user->id);
                          if(!empty($checkparagaphexist->id)){
                              $array1 = 0;
                              $isparaalladsexist = true;
                             
                            if(!empty($fileterarr) && count($fileterarr) >=2){
                                $arrkeys = array_keys($fileterarr);
                                
                                $nextelelement = $arrkeys[1];
                                $array1 = $nextelelement+1;
                                
                              }


		                   if( !empty($array1)  && empty($explodestr[$array1])){

		                      
		                       $para1data = $checkparagaphexist;
		                       $parasortimage = $C->STORAGE_URL.'advs/'.$para1data->big_image;
		                       $checkcnt = $array1+1;
                              if($totalarrcnt >= $checkcnt){
                                  
                 /* Para1 Adds Logic */
                $parasortimage = $C->STORAGE_URL.'advs/'.$para1data->big_image;
		        $para1sourcetype = $para1data->ads_access_source;
		           if(!empty($para1sourcetype) && $para1sourcetype == 1 && $is_mobile == 1 && $para1data->whatsapp_number != '' ){
		               
		               /* whatsup sending */
		               if(strpos( $para1data->whatsapp_number,"+91") !== false){
    		            }else{
    		                 $para1data->whatsapp_number ="+91 ".$para1data->whatsapp_number; 
    		              } 
    		              $display_url ="https://api.whatsapp.com/send?phone=".$para1data->whatsapp_number."&text=Hello";
    		               
    		              
    		              $adsadvancehtml	    =$buff->whatsuphtml($para1data->whatsapp_number,$para1data->id,$buff->post_id);
    		               $displaycontent =$buff->displayadscontentmedia($para1data->ad_display_type,$display_url,$parasortimage);
                         $paraads = $displaycontent.$adsadvancehtml;
                       }else if($para1sourcetype == 2 && $is_mobile == 1 && $para1data->callnow_number !='' ){
		                /* call now in mobile*/
		                if(strpos( $para1data->callnow_number,"+91") !== false){
    		              }else{
    		                 $para1data->callnow_number ="+91 ".$para1data->callnow_number; 
    		              }
    		              $adsadvancehtml	    =$buff->dialphonehtml($para1data->callnow_number,$para1data->id,$buff->post_id);
    		              $display_url ="tel:".$para1data->callnow_number;
    		              $displaycontent =$buff->displayadscontentmedia($para1data->ad_display_type,$display_url,$parasortimage);
		              $paraads   = $displaycontent.$adsadvancehtml;
		           }else if($para1sourcetype == 3 && $para1data->display_url != ''){
		               /* URL Click */
		                $display_url =$para1data->display_url;
		                 $knowmorehtml	    =$buff->knowmorehtml($display_url,$para1data->id,$buff->post_id);
		                 $displaycontent =$buff->displayadscontentmedia($para1data->ad_display_type,$display_url,$parasortimage);
		                $paraads   = $displaycontent.$knowmorehtml;
		           }else{
		               $displaycontent =$buff->displayadscontentmedia($para1data->ad_display_type,$parasortimage,$parasortimage);
                       $paraads   = $displaycontent;
		               
		           }

                                $explodestr[$array1] = $paraads;
                               $parse_text = implode("\n",$explodestr);
                             }
		                      
		                   }
		               }
		             
		                $checkparagaphexist2 =$buff->checkparagaphexist2($buff->post_user->id);

		               if(!empty($checkparagaphexist2->id)){
		                    $arrayvalue2 = 0;
		                    $isparaalladsexist = true;
		                    $arrkeys = array_keys($fileterarr);
                            if(!empty($fileterarr) && count($arrkeys) >=3){
                                
                                
                                $nextelelement = $arrkeys[2];
                                $arrayvalue2 = $nextelelement+1;
                                
                              }
                           
		                   if(!empty($arrayvalue2) && empty($explodestr[$arrayvalue2])){
		                       $para2data = $checkparagaphexist2;
		                      
		                        $parasortimage1 = $C->STORAGE_URL.'advs/'.$para2data->big_image;
		                         $para2sourcetype = $para2data->ads_access_source;
		                         $checkcnt = $arrayvalue2+1;
		                      
                              if($totalarrcnt >= $checkcnt){
		                      if(!empty($para2sourcetype) && $para2sourcetype == 1 && $is_mobile == 1 && $para2data->whatsapp_number != '' ){
		               /* whatsup sending */
		               if(strpos( $para2data->whatsapp_number,"+91") !== false){
    		            }else{
    		                 $para2data->whatsapp_number ="+91 ".$para1data->whatsapp_number; 
    		              } 
    		              $display_url ="https://api.whatsapp.com/send?phone=".$para2data->whatsapp_number."&text=Hello";
    		               
    		              
    		              $adsadvancehtml	    =$buff->whatsuphtml($para2data->whatsapp_number,$para2data->id,$buff->post_id);
    		              $displaycontent =$buff->displayadscontentmedia($para2data->ad_display_type,$display_url,$parasortimage1);
                         $paraads1 = $displaycontent.$adsadvancehtml;
                       }else if($para1sourcetype == 2 && $is_mobile == 1 && $para2data->callnow_number !='' ){
		                /* call now in mobile*/
		                if(strpos( $para2data->callnow_number,"+91") !== false){
    		              }else{
    		                 $para2data->callnow_number ="+91 ".$para2data->callnow_number; 
    		            }
    		              $adsadvancehtml	    =$buff->dialphonehtml($para2data->callnow_number,$para2data->id,$buff->post_id);
    		              $display_url ="tel:".$para2data->callnow_number;
    		              $displaycontent =$buff->displayadscontentmedia($para2data->ad_display_type,$display_url,$parasortimage1);
		              $paraads1   = $displaycontent.$adsadvancehtml;
		           }else if($para2sourcetype == 3 && $para2data->display_url != ''){
		               /* URL Click */
		                $display_url =$para2data->display_url;
		                 $knowmorehtml	    =$buff->knowmorehtml($display_url,$para2data->id,$buff->post_id);
		                  $displaycontent =$buff->displayadscontentmedia($para2data->ad_display_type,$display_url,$parasortimage1);
		                $paraads1   = $displaycontent.$knowmorehtml;
		           }else{
		                $displaycontent =$buff->displayadscontentmedia($para2data->ad_display_type,$parasortimage1,$parasortimage1);
		               $paraads1   = $displaycontent;
		               
		           }
		            
                                $explodestr[$arrayvalue2] = $paraads1;
                               $parse_text = implode("\n",$explodestr);
                             }
		                      
		                   }
		               }
		               $checkparagaphexist2 =$buff->checkparagaphexist3($buff->post_user->id);
		               if(!empty($checkparagaphexist2->id)){
		                   $arrayvalue3 = 0;
		                   $isparaalladsexist = true;
		                    $arrkeys = array_keys($fileterarr);
                            if(!empty($fileterarr) && count($arrkeys) >=4){
                               
                                
                                $nextelelement = $arrkeys[3];
                                $arrayvalue3 = $nextelelement+1;
                                
                              }
		                   
		                   if(!empty($arrayvalue3) && empty($explodestr[$arrayvalue3])){
		                       $para3data = $checkparagaphexist2;
                               $parasortimage3 = $C->STORAGE_URL.'advs/'.$para3data->big_image;
		                         $para3sourcetype = $para3data->ads_access_source;
		                      $checkcnt = $arrayvalue3+1;
		                      
                              if($totalarrcnt >= $checkcnt){

		           if(!empty($para3sourcetype) && $para3sourcetype == 1 && $is_mobile == 1 && $para3data->whatsapp_number != '' ){
		               /* whatsup sending */
		               if(strpos( $para3data->whatsapp_number,"+91") !== false){
    		            }else{
    		                 $para3data->whatsapp_number ="+91 ".$para1data->whatsapp_number; 
    		              } 
    		              $display_url ="https://api.whatsapp.com/send?phone=".$para3data->whatsapp_number."&text=Hello";
    		               
    		              
    		              $adsadvancehtml	    =$buff->whatsuphtml($para3data->whatsapp_number,$para3data->id,$buff->post_id);
    		              $displaycontent =$buff->displayadscontentmedia($para3data->ad_display_type,$parasortimage3,$parasortimage3);
    		              
                         $paraads3 = $displaycontent.$adsadvancehtml;
                       }else if($para3sourcetype == 2 && $is_mobile == 1 && $para3data->callnow_number !='' ){
		                /* call now in mobile*/
		                if(strpos( $para3data->callnow_number,"+91") !== false){
    		              }else{
    		                 $para3data->callnow_number ="+91 ".$para3data->callnow_number; 
    		              }
    		              $adsadvancehtml	    =$buff->dialphonehtml($para3data->callnow_number,$para3data->id,$buff->post_id);
    		              $display_url ="tel:".$para3data->callnow_number;
    		              $displaycontent =$buff->displayadscontentmedia($para3data->ad_display_type,$display_url,$parasortimage3);
		              $paraads3   = $displaycontent.$adsadvancehtml;
		           }else if($para3sourcetype == 3 && $para3data->display_url != ''){
		               /* URL Click */
		                $display_url =$para3data->display_url;
		                 $knowmorehtml	    =$buff->knowmorehtml($display_url,$para3data->id,$buff->post_id);
		                 $displaycontent =$buff->displayadscontentmedia($para3data->ad_display_type,$display_url,$parasortimage3);
		                $paraads3   = $displaycontent.$knowmorehtml;
		           }else{
		               $displaycontent =$buff->displayadscontentmedia($para3data->ad_display_type,$parasortimage3,$parasortimage3);
		               $paraads3   = $displaycontent;
		               
		           }
		  
                                $explodestr[$arrayvalue3] = $paraads3;
                              
                               $parse_text = implode("\n",$explodestr);
                             }
		                      
		                   }
		               } 
					    /* paragraph 4*/
		                $checkparagaphexist4 =$buff->checkparagaphexist4($buff->post_user->id);
		                if(!empty($checkparagaphexist4->id)){
		                   $arrayvalue4 = 0;
		                   $isparaalladsexist = true;
		                    $arrkeys = array_keys($fileterarr);
                            if(!empty($fileterarr) && count($arrkeys) >=5){
                               
                                
                                $nextelelement = $arrkeys[4];
                                $arrayvalue4 = $nextelelement+1;
                                
                              }
		                   
		                   if(!empty($arrayvalue4) && empty($explodestr[$arrayvalue4])){
		                       $para4data = $checkparagaphexist4;
                               $parasortimage4 = $C->STORAGE_URL.'advs/'.$para4data->big_image;
		                         $para4sourcetype = $para4data->ads_access_source;
		                      $checkcnt = $arrayvalue4+1;
		                      
                              if($totalarrcnt >= $checkcnt){

		           if(!empty($para4sourcetype) && $para4sourcetype == 1 && $is_mobile == 1 && $para4data->whatsapp_number != '' ){
		               /* whatsup sending */
		               if(strpos( $para4data->whatsapp_number,"+91") !== false){
    		            }else{
    		                 $para4data->whatsapp_number ="+91 ".$para4data->whatsapp_number; 
    		              } 
    		              $display_url ="https://api.whatsapp.com/send?phone=".$para4data->whatsapp_number."&text=Hello";
    		               
    		              
    		              $adsadvancehtml	    =$buff->whatsuphtml($para4data->whatsapp_number,$para4data->id,$buff->post_id);
                         $paraads4 = "\n<a class='adslink'  href='".$display_url."' target='_blank'><img width=100%' src='".$parasortimage4."'></a>\n".$adsadvancehtml;
                       }else if($para4sourcetype == 2 && $is_mobile == 1 && $para4data->callnow_number !='' ){
		                /* call now in mobile*/
		                if(strpos( $para4data->callnow_number,"+91") !== false){
    		              }else{
    		                 $para4data->callnow_number ="+91 ".$para4data->callnow_number; 
    		              }
    		              $adsadvancehtml	    =$buff->dialphonehtml($para4data->callnow_number,$para4data->id,$buff->post_id);
    		              $display_url ="tel:".$para4data->callnow_number;
    		               $displaycontent =$buff->displayadscontentmedia($para4data->ad_display_type,$display_url,$parasortimage4);
		              $paraads4   = $displaycontent.$adsadvancehtml;
		           }else if($para4sourcetype == 3 && $para4data->display_url != ''){
		               /* URL Click */
		                $display_url =$para4data->display_url;
		                 $knowmorehtml	    =$buff->knowmorehtml($display_url,$para4data->id,$buff->post_id);
		                  $displaycontent =$buff->displayadscontentmedia($para4data->ad_display_type,$parasortimage4,$parasortimage4);

		                $paraads4   =$displaycontent.$knowmorehtml;
		           }else{
		               $displaycontent =$buff->displayadscontentmedia($para4data->ad_display_type,$parasortimage4,$parasortimage4);
		               $paraads4   =$displaycontent;
		               
		           }
		  
                                $explodestr[$arrayvalue4] = $paraads4;
                              
                               $parse_text = implode("\n",$explodestr);
                             }
		                      
		                   }
		               }
		               /* paragraph 4 end */

		               /* paragraph 5*/
		               $checkparagaphexist5 =$buff->checkparagaphexist5($buff->post_user->id);
		                if(!empty($checkparagaphexist5->id)){
		                   $arrayvalue5 = 0;
		                   $isparaalladsexist = true;
		                    $arrkeys = array_keys($fileterarr);
                            if(!empty($fileterarr) && count($arrkeys) >=6){
                               
                                
                                $nextelelement = $arrkeys[5];
                                $arrayvalue5 = $nextelelement+1;
                                
                              }
		                   
		                   if(!empty($arrayvalue5) && empty($explodestr[$arrayvalue5])){
		                       $para5data = $checkparagaphexist5;
                               $parasortimage5 = $C->STORAGE_URL.'advs/'.$para5data->big_image;
		                         $para5sourcetype = $para5data->ads_access_source;
		                      $checkcnt = $arrayvalue5+1;
		                      
                              if($totalarrcnt >= $checkcnt){

		           if(!empty($para5sourcetype) && $para5sourcetype == 1 && $is_mobile == 1 && $para5data->whatsapp_number != '' ){
		               /* whatsup sending */
		               if(strpos( $para5data->whatsapp_number,"+91") !== false){
    		            }else{
    		                 $para5data->whatsapp_number ="+91 ".$para5data->whatsapp_number; 
    		              } 
    		              $display_url ="https://api.whatsapp.com/send?phone=".$para5data->whatsapp_number."&text=Hello";
    		               
    		              
    		              $adsadvancehtml	    =$buff->whatsuphtml($para4data->whatsapp_number,$para4data->id,$buff->post_id);
    		              $displaycontent =$buff->displayadscontentmedia($para5data->ad_display_type,$display_url,$parasortimage5);
                         $paraads5 = $displaycontent.$adsadvancehtml;
                       }else if($para5sourcetype == 2 && $is_mobile == 1 && $para5data->callnow_number !='' ){
		                /* call now in mobile*/
		                if(strpos( $para5data->callnow_number,"+91") !== false){
    		              }else{
    		                 $para5data->callnow_number ="+91 ".$para5data->callnow_number; 
    		              }
    		              $adsadvancehtml	    =$buff->dialphonehtml($para5data->callnow_number,$para5data->id,$buff->post_id);
    		              $display_url ="tel:".$para5data->callnow_number;
		              $paraads5   = "\n <a class='adslink'  href='".$display_url."'><img width=100%' src='".$parasortimage5."'></a> \n".$adsadvancehtml;
		           }else if($para5sourcetype == 3 && $para5data->display_url != ''){
		               /* URL Click */
		                $display_url =$para5data->display_url;
		                 $knowmorehtml	    =$buff->knowmorehtml($display_url,$para5data->id,$buff->post_id);
		                  $displaycontent =$buff->displayadscontentmedia($para5data->ad_display_type,$display_url,$parasortimage5);
		                $paraads5   =$displaycontent.$knowmorehtml;
		           }else{
		               $displaycontent =$buff->displayadscontentmedia($para5data->ad_display_type,$parasortimage5,$parasortimage5);
		               $paraads5   =$displaycontent;
		               
		           }
		  
                                $explodestr[$arrayvalue5] = $paraads5;
                              
                               $parse_text = implode("\n",$explodestr);
                             }
		                      
		                   }
		               }

		                /* paragraph 5 end*/
		             
		             
		                 
		                 
		             
		           }
		            if($isparaalladsexist == false && $ishtmlparsewrong){
		                $parse_text = implode("\n",$explodestr);
		               
		           }
		           
		        
		    if(!empty($checkadexist)){
		    if(strpos($parse_text, "\n") !== FALSE) {
		       
		        $firstLine = strtok($parse_text, "\n\n");
		         
		        $sortimage = $C->STORAGE_URL.'advs/'.$checkadexist->big_image;
		        $bigimage =$C->STORAGE_URL.'advs/'.$checkadexist->big_image;
		         $showwhatsup = 0;
		         
		           
		        if($sortimage){
		           
		      	/* Official Adds Logic */

		        $officalsourcetype = $checkadexist->ads_access_source;
		           if(!empty($officalsourcetype) && $officalsourcetype == 1 && $is_mobile == 1 && $checkadexist->whatsapp_number != '' ){
		               /* whatsup sending */
		               if(strpos( $checkadexist->whatsapp_number,"+91") !== false){
    		            }else{
    		                 $checkadexist->whatsapp_number ="+91 ".$checkadexist->whatsapp_number; 
    		              } 
    		              $adsadvancehtml	    =$buff->whatsuphtml($checkadexist->whatsapp_number,$checkadexist->id,$buff->post_id);
    		              $display_url ="https://api.whatsapp.com/send?phone=".$checkadexist->whatsapp_number."&text=Hello";
    		              $displaycontent =$buff->displayadscontentmedia($checkadexist->ad_display_type,$display_url,$sortimage);
		            $newstr   =$firstLine."\n".$displaycontent.$adsadvancehtml;
    		          
		              
		           }else if($officalsourcetype == 2 && $is_mobile == 1 && $checkadexist->callnow_number !='' ){
		                /* call now in mobile*/
		                if(strpos( $checkadexist->callnow_number,"+91") !== false){
    		              }else{
    		                 $checkadexist->callnow_number ="+91 ".$checkadexist->callnow_number; 
    		              }
    		               $adsadvancehtml	    =$buff->dialphonehtml($checkadexist->callnow_number,$checkadexist->id,$buff->post_id);
    		              $display_url ="tel:".$checkadexist->callnow_number;
    		               $displaycontent =$buff->displayadscontentmedia($checkadexist->ad_display_type,$display_url,$sortimage);
		            $newstr   =$firstLine."\n".$displaycontent.$adsadvancehtml;
		           }else if($officalsourcetype == 3 && $checkadexist->display_url != ''){
		               /* URL Click */
		                $display_url =$checkadexist->display_url;
		                 $displaycontent =$buff->displayadscontentmedia($checkadexist->ad_display_type,$display_url,$sortimage);
		                  $knowmorehtml	    =$buff->knowmorehtml($display_url,$checkadexist->id,$buff->post_id);
		            $newstr   =$firstLine."\n".$displaycontent.$knowmorehtml;
		           }else{
		                $displaycontent =$buff->displayadscontentmedia($checkadexist->ad_display_type,$sortimage,$sortimage);
		               $newstr   =$firstLine."\n".$displaycontent;
		               
		           }
		  
                   
                $parse_text =  str_replace($firstLine,$newstr,$parse_text);
                
                }
            
}
$adslink =1;
}
else {
    $adslink =0;

 
}
$nativeshow = false;
if(isset($buff->post_user->username) && ($buff->post_user->username == "lucknow" || $buff->post_user->username == "uttarpradesh") ){
    $nativeshow = false;
}
			
if($is_mobile == 0 && $nativeshow){
    $nativescriptstr = ' <br /><div id="f7ae58c7f1a1cc4abe9273a0f971ba2a"></div>';
    
}
if($is_mobile == 1 && $nativeshow){
    // $nativescriptstr = '<br />  <div id="14da15db887a4b50efe5c1bc66537089"></div>';
	 $nativescriptstr = ' <br /><div id="f7ae58c7f1a1cc4abe9273a0f971ba2a"></div>';
	
}
		$parse_text = $parse_text.$nativescriptstr;
	         $posttype =$buff->posttype($buff->post_id);
	         if($buff->title != ""){
	             $titlestr = "<h5>".$buff->title."</h5>";
	           $parse_text   =$titlestr."\n".$parse_text;   
	         }
	          


			$tpl->layout->block->setVar('activity_text', $parse_text);
		
			if(!empty($replies)){
			$tpl->layout->block->setVar('activity_text', $buff->attchmentreplaydisplay($buff->post_id));
				$link =$buff->findlink($buff->post_id);
					if(!empty($link)){
						$tpl->layout->block->setVar('replaycontainer',$buff->timelinelinkhtml($buff->post_id));
			      }

            }
			$geolocation      =$buff->geolocation($buff->post_id);
			
			if(!empty($geolocation)){
				if( isset($buff->post_attached['link']) ){
				}else{

				$geolocationhtml ="<div class='geo-location'><a href=".$C->SITE_URL."search/tab:location/s:".$buff->post_id."><img src=".$C->SITE_URL."apps/events/static/images/icon-location-event.png> ".$geolocation."</a></div>";
			$tpl->layout->block->setVar('geolocation', $geolocationhtml);
				}
			}

		}
		}

		if( $comments_num === 0 && !$page->is_mobile){ 
			$tpl->layout->block->setVar('activity_nocomments', 'no-comments');
		}
		if($buff->post_user->id != $user->id){
					  $follow =$network->ifollowcheckdata($user->id,$buff->post_user->id);
					  
					  if(!empty($follow)){
					
						  $Unfollow = '<a href="" data-action="unfollow" data-value="'.$buff->post_user->id.'" data-namespace="users" data-role="services">Unfollow</a>';
					  }else{
						
							$Unfollow = '<a href="" data-action="follow" data-value="'.$buff->post_user->id.'" data-namespace="users" data-role="services">follow</a>';
					  }

					  
				  }else{
					    // $followbutton ="";
					  	$Unfollow='';
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
			$delete = (($user->is_logged && $buff->if_can_delete())? '<a class="text-left" href="" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'" data-role="services" data-namespace="activities" title="Delete" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png">&nbsp&nbsp Delete</a>' : '');
           }
		}else{
		$delete = (($user->is_logged && $buff->if_can_delete())? '<a class="text-left" href="" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'" data-role="services" data-namespace="activities" title="Delete" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png">&nbsp&nbsp Delete</a>' : '');

		}
		// edit development
		
		if($buff->post_user->id!==$user->id){
			$editpng='';
		}
		else{
			$parentid=$buff->post_id;
			$editmessage=$buff->post_message;
			
						$editpng='<a class="text-left" style="cursor:pointer" onclick="editpopupnew('.$buff->post_id.')">
						<img src="' . $C->SITE_URL . 'static/images/icons/pencil.png" style="width:25px;" > Edit
					   </a>';


		}
		
		//


		


		if($user->is_logged && $buff->post_type=='public'){
				
		}
		// <i class="fa fa-ellipsis-v"></i>
		// <span class="caret"></span>
		// <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
$dropdown = '<div class="dropdown" >
<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v " ></i></a>
<ul class="dropdown-menu dropdown-menu-right">
  <li>'.$editpng.'</li>
  <li>'.$delete.'</li>
  '.(($user->is_logged && $buff->post_type == 'public')? ('<li><a  href="" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'" data-role="services" title="Bookmark" data-namespace="activities" data-action="bookmark" class="'.($buff->is_post_faved()? '' : 'icons').' text-left"> <img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png">&nbsp&nbsp Bookmark</a></li>') : '' ).'
</ul>
</div>';

$tpl->layout->block->setVar('activity_options',$dropdown);
		
		// $tpl->layout->block->setVar('activity_options',$editpng.$delete
		// 		.
		// 		(($user->is_logged && $buff->post_type == 'public')? ('<a href="" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$buff->post_id.'"}').'" data-role="services" title="Bookmark" data-namespace="activities" data-action="bookmark" class="'.($buff->is_post_faved()? '' : 'icons').'"> <img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>'.$followbutton.'') : '' )
		// );		
		
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
				
				$tpl->layout->block->setVar( 'activity_footer', '<div class="like-list icon-ftr">'.$like_content.'</div>' );
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
				$replycont ='
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" style="border:0px solid grey;">
        <a style=" cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('.$buff->post_id.','.$chield[$m]->id.')">View Replies</a></div>';
				
			}else{
				$replycont ='';
				
			}
			 
					     if( preg_match( "#iPhone#i", $_SERVER['HTTP_USER_AGENT'] )  || preg_match( "#iPod#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Android#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Phone#i", $_SERVER['HTTP_USER_AGENT'] )) {
					    $whatschild =' <li><a data-text="Streetbuzz" data-link="'.$C->SITE_URL.'/view/post:'.$chield[$m]->id.'" class="whatsapp w3_whatsapp_btn w3_whatsapp_btn_large">Whatsapp</a>
</li>';
					  }else{
					      $whatsurl =$C->SITE_URL.'/view/post:'.$chield[$m]->id;
								    $whatschild =' 
								    <li><a target="_blank" href="https://web.whatsapp.com/send?text='.$whatsurl.'" >Whatsapp</a>
</li>';
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
				$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_replayreshares? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_replayreshares? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Undo-Rebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
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
						$mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Undospam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png" ></em></a>';
                }else{
			          $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="Markspam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
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
					//$mes = $buff->parsetext($chield[$m]->message);
					$mes ="<pre yyyyyyyy>".$buff->parsetext($chield[$m]->message)."</pre>";

				    $mes .= $buff->attchmentreplaydisplay($chield[$m]->id);
					 $link = $buff->findlink($chield[$m]->id);
					if(!empty($link)){
					$mes  .=$buff->timelinelinkhtml($chield[$m]->id);
					}

					
                }elseif($buzztype =="event"){
					$mes	    =$buff->eventhtml($chield[$m]->id);
						
				}elseif($buzztype =="poll"){
					$mes	    =$buff->pollchildhtml($chield[$m]->id);
					
				}elseif($buzztype =="intraday"){
					$mes	    =$buff->assethtml($chield[$m]->id);
					
				}
				if($chield[$m]->pic !=''){
					$img ='<img src="'.getAvatarUrl($chield[$m]->pic, 'thumbs1').'" alt="'.$chield[$m]->username.'" />';
					
				}else{
					$img ='<img src="'.$C->STORAGE_URL.'avatars/thumbs1/_noavatar_user.gif" alt="'.$chield[$m]->username.'" />';
					
				}
               if($chield[$m]->userid != $user->id){
					  $follow =$network->ifollowcheckdata($user->id,$chield[$m]->userid);
					  
					  if(!empty($follow)){
						  $followbutton ='<span class="btn-follow-timeline"><a class="action-btn  user-action disconnect-user" data-action="unfollow" data-value="'.$chield[$m]->userid.'"  data-namespace="users" data-role="services"><span class="tooltip"><span class="glyphicon glyphicon-user user-icon-flw"></span></span></a></span>';
						  
					  }else{
						 $followbutton ='<span class="btn-follow-timeline"><a class="action-btn  user-action disconnect-user" data-action="follow" data-value="'.$chield[$m]->userid.'"  data-namespace="users" data-role="services"><span class="tooltip"><span class="glyphicon glyphicon-user user-icon-flw"></span></span></a></span>';

 
					  }

					  
				  }else{
					    $followbutton ="";
					  
				  }
				 $share_count =$buff->share_count($chield[$m]->id);

	   
		$delete = (($user->is_logged && $buff->if_can_delete())? '<a href="" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$chield[$m]->id.'"}').'" data-role="services" data-namespace="activities" title="Delete" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>' : '');
       $is_fav  = $buff->isfav($user->id,$chield[$m]->id);
	 
   			if(!empty($is_fav)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$chield[$m]->id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>'.$followbutton.'';
   				
   			}else{
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$chield[$m]->id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>'.$followbutton.'';
   
   				
   			}
			if($buff->post_type =="public"){
							  	if( $user->is_logged ){
									$childfootercnt='
						<span class="reply icon-ftr icon-ftr-reply">			
									<a  style="cursor:pointer" onclick="childpopup('.$buff->post_id.','.$chield[$m]->id.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
									</span>

				<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($chield[$m]->date).'" />

				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_likedreplay? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_likedreplay? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
<!--				
				<div class="agree-list icon-ftr f3"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_agree? '<img  width="" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Disagree"/>' : '<img width="" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree"/>').'</a>'.$showagreebtn_btn.'</div>
-->				
               	<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>
               	
               	';
								                          $childmark ='<div class="like-list icon-ftr">'.$mark_content.'</div>';

								}else{
						$childfootercnt='
<span class="reply icon-ftr icon-ftr-reply">
						<a  style="cursor:pointer" onclick="childpopup('.$buff->post_id.','.$chield[$m]->id.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"/></a>
						</span>

				<input type="hidden" id="time-'.$chield[$m]->id.'" value="'.post::parse_date($chield[$m]->date).'" />

				<div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_likedreplay? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$chield[$m]->id.'"}').'">'.($is_likedreplay? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
               

               	<span class="reshare-list icon-ftr">'.$reshare_content.''.$resharecnt.'</span>';

					}

				$footerhtml ='<div class="activity-footer meta-info col-xs-12 col-sm-12 col-md-12 col-lg-12 zeropadding">

				

				'.$childfootercnt.'

                  <div class="dropdown share icon-ftr">
							   <a 111 style="cursor:pointer" href="" class="menu-btn"><img style="height:25px;width:25px;" class="icons" src="'.$C->SITE_URL.'themes/FishingEnthusiastTheme/images/social_share.png" title="Share"/></a>
							   <ul class="menu-options"  id="'.$chield[$m]->id.'"  data_id1="'.$chield[$m]->id.'">
							   '.$whatschild.'
							   '.$fsfs.'
								  <li><a onclick="myFunctionpostshare('.$buff->post_id.')" href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'/view/post:'.$chield[$m]->id).'&title='.urlencode(htmlspecialchars($chield[$m]->message)).'&source='.urlencode($C->SITE_URL.'/view/post:'.$chield[$m]->id).'&summary='.urlencode($C->SITE_URL.'/view/post:'.$chield[$m]->id).'"  target="_blank" >Linkedin</a></li>
								  <li><a onclick="myFunctionpostshare('.$buff->post_id.')" href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'/view/post:'.$chield[$m]->id).'"  target="_blank" >Google Plus</a></li>
								 
							   </ul>
							   <span class="'.$chield[$m]->id.'">
							        '.$share_count.'
							   <span>
							</div>


							


							</div><!--/ End : Activity footer -->


							<div id="replydis-'.$chield[$m]->id.'">'.$replycont.'</div>';
				
			}else{
				$footerhtml ='';
				
			}

				
			$tpl->layout->block->setVar('activity_chield_text1','
			<ul class="'.$css.'">
			<li>
			
		


<!-- start Child -->
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box replay'.$buff->post_id.' single'.$chield[$m]->id.'"  style="border:0px solid red;" id="'.$chi.'">

 <div class="col-md-1 col-lg-1 col-sm-1 col-xs-2 image-div" style="padding:0; overflow:hidden"><a href="'.userlink($chield[$m]->username).'" class="avatar bizcard 3333333" data-userid="'.$chield[$m]->userid.'">'.$img.'</a>
            </div>

<div class="col-md-11 col-lg-11 col-sm-11 col-xs-10" style="padding:0px 3px 0px 8px;">

<div class="activity-container">
<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12"><a href="'.userlink($chield[$m]->username).'" class="author bizcard" data-userid="'.$chield[$m]->userid.'">'. $chield[$m]->username .'</a>
				
<div class="meta-info">'.$grp.'</div>
				
<div class="activity-options">'.$delete.''.$fav.'</div>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 zeropadding">
<a href="'.$C->SITE_URL.'/view/post:'.$chield[$m]->id.'" class="permlink">'.post::parse_date($chield[$m]->date).' <span class="glyphicon glyphicon-link"></span></a></div>

<div class="789 activity-content">'.$mes.'</div>
</div>		

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">'.$footerhtml.'</div>

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
	
	
			  if($buff->post_type=="public"){

		//start of poll
		$poll  = $buff->is_poll();
		//print_r($poll);exit;
		if(count($poll)>0)
		{
			$pollanswer=$buff->is_pollanswer($user->id,$poll[0]->poll_id);
			$tpl->layout->useInnerBlock('activity-poll');
			if(count($pollanswer)<=0){
				$changehtml ='';
			}else{
				$maintype=0;
				$changehtml ='<div id="changevote'.$maintype.''.$poll[0]->poll_id.'" class="pull-right zeropadding box-sub-desc"><a onclick="changeopenion('.$user->id.','.$poll[0]->poll_id.','.$buff->post_id.','.$maintype.')" ><span class="glyphicon glyphicon-edit"></span> Change Vote</a></div>';
			}
			//$tpl->layout->inner_block->setVar('activity_poll_question', '<b><label style="color:#2665ca;"> Poll: '.$poll[0]->poll_question.'</label></b>');
			
			
			
			
$post_id=$buff->post_id;

	global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$r= $db2->query("SELECT `data`,`type` FROM `posts_attachments` where post_id=".$post_id);

$result = mysqli_fetch_array($r); 
//echo $result['data'];
//echo $result['type'];
//$result = $db2->fetch_object($r);
	

//$posttype_image =$buff->posttype_image($post_id);

$imagevals->type='image';
$imagevals->data=$result['data'];

if($result['type']=="image"){
$tmp = @unserialize(stripslashes($imagevals->data));
if (!$tmp) {
$tmp = preg_replace_callback('!(?<=^|;)s:(\d+)(?=:"(.*?)";(?:}|a:|s:|b:|d:|i:|o:|N;))!s', 'serialize_fix_callback', stripslashes($imagevals->data));
$tmp = @unserialize(stripslashes($tmp));
}
$imagedes .= '<a  target="_blank" href="'.($C->STORAGE_URL.'attachments/1/'.$tmp->file_preview).'" class="lightbox-image image-thumb cboxElement poll_'.$buff->post_id.'"><img width="100%" alt="Image" src="'.$C->STORAGE_URL.'attachments/1/'.($tmp->file_preview).'" /></a>';



}
			
			
			$pollhtml ='';
			$pollhtml .='<!-- start - 1st vote poll -->
'.$imagedes.'
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 poll-list-orange-bg">
    
    <!-- start : poll title -->
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-title">
    <ul class="list-inline single-line">
    <li><img src="'.$C->SITE_URL.'static/images/icon-poll-24.png" class="img-responsive"></li>
    <li>'.$poll[0]->poll_question.'
    </li>
    </ul>  
    </div>    
    <!-- end : poll title -->';
	if(count($pollanswer) <=0){
		$pollhtml .='<div id="replace'.$poll[0]->poll_id.'">';



$pollhtml .= '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 activity-poll zeropadding">
<div class="attachments lightbox-enabled">
<div class="images">
<div class="col-mlist-link-container test ">
</div>
</div>
</div>
</div>';





/*$pollhtml .= '<div class="attachments lightbox-enabled">
<div class="images">
<div class="list-link-container">
<a target="_blank" href="'.($C->STORAGE_URL.'attachments/'.$network->id.'/1624711765506879_large.jpg').'" class="lightbox-image image-thumb cboxElement">
<img alt="Image" src="'.$C->STORAGE_URL.'attachments/'.$network->id.'/1624711765506879_large.jpg" />
</a></div></div></div>';
*/

/*
$data.='<div class="w3-display-container mySlides'.$buff->post_id.'">
<img  class="'.$image_caption.'" width="100%" alt="Image" src="'.$C->STORAGE_URL.'attachments/'.$network->id.'/'.($image->file_preview).'" />
  <div class="text-center">
<span class="w3-large  f  w3-black ">'.$image_caption.'</span>
  </div>
</div>';*/



  	

			foreach($poll as $keys=>$row)
			{

				if($row->answer!="" && count($pollanswer)<=0)
				{





     $pollhtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  zeropadding trtrt">
     


    <!-- start : poll results -->
 <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content poll-parent-radio-margin">
    <ul class="list-unstyled poll-radio">
    <li>
<input onclick="changeurl('.$row->poll_id.','.$row->poll_answer_id.')" id="'.$keys.$row->poll_id.'" type="radio" name="radio11"  class="radios option'.$row->poll_answer_id.' radio'.$row->poll_id.' "/>

<label for="'.$keys.$row->poll_id.'">&nbsp;</label>'.$row->answer.'</li>
 
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
		$pollhtml .='
		<div style="padding-left:10px; color:red; font-size:12px;display:none" id="uservoteerror'.$poll[0]->poll_id.'">
		( ਇੱਕ ਉੱਤਰ ਚੁਣੋ ਅਤੇ ਵੋਟ ਕਲਿੱਕ ਕਰੋ / Select one answer and click vote )</div>
		</div>';		

	}else{
	$pollper =$buff->getpercentagesofpollanswers($poll[0]->poll_id);
	$totalpollcnt =$buff->totalpollcnt($poll[0]->poll_id);
	$uservote  =$buff->userpollanswer($user->id,$poll[0]->poll_id);
	$pollhtml .='
<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content poll-result-box" id="replace'.$poll[0]->poll_id.'">';

 foreach($pollper as $keys=>$vals){
	 $percentage = ($vals->cnt/$totalpollcnt->totalcnt)*100;
	 if($vals->poll_answer_id == $uservote){
		 $userclass=' <span class="glyphicon glyphicon-ok"></span>';
	 }else{
		$userclass=''; 
	 }
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
      <strong>'.$network->format_num($vals->usercnt).' vote ('.round($percentage,2).'%)</strong>'.$userclass.' 
    </div>
  </div>';
 }




    $pollhtml .='</div>		

	';
		
	
	}
	$pollhtml .='<!-- start : poll button download results -->
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">';
	$mainpoll =0;
	if(count($pollanswer) <=0){
		
		$pollhtml .='<a  class="button-vote" style="cursor:default"  onclick="vote('.$mainpoll.','.$row->poll_id.')"  id="pollvote'.$row->poll_id.'" >Vote</a>';
		if($poll[0]->user_id == $user->id){
		$pollhtml .='<a type="submit"  class="download'.$mainpoll.''.$poll[0]->poll_id.'"  id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'" ><button type="submit" class="button-submit-results">Download Results</button></a>';
			
		}
	
   }else{
        if($poll[0]->user_id == $user->id){
		$pollhtml .='<a type="submit"   class="download'.$mainpoll.''.$poll[0]->poll_id.'"  id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'" ><button type="submit" class="button-submit-results">Download Results</button></a>';

			
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
			  }
			//$tpl->layout->inner_block->setVar('activity_attachment_images', '<a  class="adslink" rel="'.$checkadexist->id.'" href="'.$display_url.'" target="_blank"><img width="100%" src="'.$bigimage.'"></a>');

		if( count( $buff->post_attached ) > 0){ 
			
			$tpl->layout->useInnerBlock('activity-attachments'); 
			
			if( isset($buff->post_attached['file']) || $buff->video_url!=""){
			   // print_r($buff->video_url);
				foreach($buff->post_attached['file'] as $k => $file){
					$ext = pathinfo($file->file_original, PATHINFO_EXTENSION);
					if($buff->video_url!=""){
					$fileor	=$C->SITE_URL.'storage/attachments/'.$network->id.'/'.$file->file_original;
			
 	$video_id=$buff->getvideoid($buff->post_id);	
	$video_url=$buff->getvideourl($buff->post_id);
	

		
		$thumb =$buff->get_thumb($buff->post_id);
$image_caption = $buff->video_caption($buff->post_id);

$tpl->layout->inner_block->setVar('activity_attachments_files', ' <video poster="'.$C->STORAGE_URL.'attachments/1/'.($thumb->thumb).'" id="video'.$buff->post_id.'" controls width="100%" >

  <source src="'.$video_url.'" type="video/mp4">
</video>
  <h3 style="text-align: center;">'.$image_caption.'</h3>

');
				
						
					}else{

					$tpl->layout->inner_block->setVar('activity_attachments_files', '<a class="icon file '.(isset($file->filetype)? $file->filetype : '').'" href="'.$C->SITE_URL.'getfile/pid:'.$buff->post_tmp_id.'/attid:'.intval($k).'" title="'.$file->title.'">'.$file->title.'</a><span class="clear-right"></span>');
				}
				}
			}
			
			if( isset($buff->post_attached['image']) ){

/*$posttype =$buff->posttype($buff->post_id);

if($posttype!=1){
foreach($buff->post_attached['image'] as $image){
    
$data=$tpl->layout->inner_block->setVar('activity_attachment_images', '<a target="_blank"  href="'.($C->STORAGE_URL.'attachments/'.$network->id.'/'.$image->file_preview).'" class="lightbox-image image-thumb cboxElementtt"><img width="100%" alt="Image" src="'.$C->STORAGE_URL.'attachments/'.$network->id.'/'.($image->file_preview).'" /></a>');
$data=$tpl->layout->inner_block->setVar('activity_attachment_images_preview', '<a target="_blank" href="'.($C->STORAGE_URL.'attachments/'.$network->id.'/'.$image->file_preview).'" class="lightbox-image image-thumb cboxElement 22"><img alt="Image" src="'.$C->STORAGE_URL.'attachments/'.$network->id.'/'.($image->file_preview).'" /></a>');
   
   $tpl->layout->inner_block->setVar('activity_attachment_images',$data);
   
    }
  }else{*/
$data='
<link rel="stylesheet" href="'.$C->SITE_URL.'themes/FishingEnthusiastTheme/css/w3-slider.css">
<style>
.mySlides {display:none;}
/* Bottom right text */
.bottom-right {
    position: absolute;
    bottom: 20px;
    left: 50%;
    color: white;
    font-size: 22px;
    transform: translate(-50%, -50%);
  
}
</style>


<div class="w3-content w3-display-container">';
	$posttype =$buff->posttype($buff->post_id);
	$i=0;
	$count=0;
	$array_count = 1 ;
foreach($buff->post_attached['image'] as $image){

//

if($array_count==1){
   $css_slider ="display: block;";
}
else {
   $css_slider ="display: none;";
}

$count++;

$image_caption = $buff->image_caption($image->attachment_id);
 '<style>
 .f {
    padding: none;
}
 </style>';
$data.='<div class="w3-display-container mySlides'.$buff->post_id.'" style="'.$css_slider.'">
<img  class="'.$image_caption.'" width="100%" alt="Image" src="'.$C->STORAGE_URL.'attachments/'.$network->id.'/'.($image->file_preview).'" />
  <div class="text-center">
<span class="w3-large  f  w3-black ">'.$image_caption.'</span>
  </div>
</div>';

/*w3-display-bottomleft w3-large w3-container w3-padding-16 w3-black*/

if($posttype!=1){

$data=$tpl->layout->inner_block->setVar('activity_attachment_images', '<a target="_blank"  href="'.($C->STORAGE_URL.'attachments/'.$network->id.'/'.$image->file_preview).'" class="lightbox-image image-thumb cboxElement"><img width="100%" alt="Image" src="'.$C->STORAGE_URL.'attachments/'.$network->id.'/'.($image->file_preview).'" /></a>');
$data=$tpl->layout->inner_block->setVar('activity_attachment_images_preview', '<a target="_blank" href="'.($C->STORAGE_URL.'attachments/'.$network->id.'/'.$image->file_preview).'" class="lightbox-image image-thumb cboxElement"><img alt="Image" src="'.$C->STORAGE_URL.'attachments/'.$network->id.'/'.($image->file_preview).'" /></a>');
   
   $tpl->layout->inner_block->setVar('activity_attachment_images',$data);
   
    }

$array_count++;

}


		
		$data.='
<button class="w3-button w3-black w3-display-left" onclick="plusDivstime(-1,'.$buff->post_id.')">&#10094;</button>
  <button class="w3-button w3-black w3-display-right" onclick="plusDivstime(1,'.$buff->post_id.')">&#10095;</button>
</div>

<script>
var slideIndex = 1;
setTimeout(function(){

},1000);


</script>';
		
		
	//	echo $data; die;  yhs bhi to condition hoga na ki agar posttype 1 hua to ye chlega

if($posttype==1){
    
    if($count=='1'){
       $data=$tpl->layout->inner_block->setVar('activity_attachment_images', '<a target="_blank"  href="'.($C->STORAGE_URL.'attachments/'.$network->id.'/'.$image->file_preview).'" class="lightbox-image image-thumb cboxElement"><img width="100%" alt="Image" src="'.$C->STORAGE_URL.'attachments/'.$network->id.'/'.($image->file_preview).'" /></a>');
$data=$tpl->layout->inner_block->setVar('activity_attachment_images_preview', '<a target="_blank" href="'.($C->STORAGE_URL.'attachments/'.$network->id.'/'.$image->file_preview).'" class="lightbox-image image-thumb cboxElement"><img alt="Image" src="'.$C->STORAGE_URL.'attachments/'.$network->id.'/'.($image->file_preview).'" /></a>');
   
   $tpl->layout->inner_block->setVar('activity_attachment_images',$data);
    
    }
    else{
    
				$tpl->layout->inner_block->setVar('activity_attachment_images',$data);
    }
				
}
				
				

			
			}
		
			
		    
			
			

			$tpl->layout->inner_block->saveInBlockPart('activity_attachments');
					//echo '<pre>';print_r($buff->post_attached['link']);

			
						if( isset($buff->post_attached['link']) ){
				foreach($buff->post_attached['link'] as $link){
					$tpl->layout->useInnerBlock('activity-attachments-link');
					$tpl->layout->inner_block->setVar('activity_attachments_link', urldecode($link->link)); 
					$tpl->layout->inner_block->setVar('activity_attachments_link_title', isset($link->title)? $link->title : $link->link);
					$tpl->layout->inner_block->setVar('activity_attachments_link_description', isset($link->description)? $link->description : '');
					$is_event =$buff->is_event($link->post_id);
					if(!empty($is_event)){
					if( $user->is_logged ){

					if($link->status ==1){
						$st  ="Active";
					}
					if($link->status ==2){
						$st  ="Cancelled";
					}
					if($link->status ==0){
						$st  ="Expired";
					}
					}else{
						//user without login 
						$userwithout = $buff->usereventsttus($link->post_id);
						if($userwithout ==1){
						$st  ="Active";
					}
					if($userwithout ==2){
						$st  ="Cancelled";
					}
					if($userwithout ==0){
						$st  ="Expired";
					}
					}
					if($user->id != $buff->post_user->id){
					if($link->event_status !=2  ){
						if($link->status != 0){
						
						if( ($link->event_status =='' &&  $link->edit_status =='')   ){
						$tpl->layout->inner_block->setVar('activity_attachments_link_description', '

							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" style="padding:0px 10px 0px 6px; display:'.$display.';" id="acc-'.$link->post_id.'"><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/icon-user-response.png">&nbsp;  <strong>User Response:</strong>
							<input type="radio"  class="accept" name="accept" onclick="myFunction('.$link->post_id.'1)" value="'.$link->post_id.'-1">Accept
							&nbsp;&nbsp;
							<input type="radio" class="accept" name="accept" onclick="myFunction('.$link->post_id.'3)"  value="'.$link->post_id.'-3">Reject</div>');
					    
					}
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
					
					

					/* Start: TIMELINE Event User Response */

					$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" style="padding:0px 10px 0px 6px; display:'.$display.';"id="accept-'.$link->post_id.'"><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/icon-user-response.png">&nbsp; <strong>User Response: </strong> Event Accepted</div>');
					$tpl->layout->inner_block->setVar('activity_attachments_link_description', '
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" style="padding:0px 10px 0px 6px; display:'.$displayreject.';" id="acc-'.$link->post_id.'"><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/icon-user-response.png">&nbsp;  <strong>User Response: </strong>Event Rejected</div>');

                   /* End: TIMELINE Event User Response */


					
					$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<input type="hidden" id="attach-'.$link->post_id.'"  value="'.$link->attachment_id.'">');
                    } 
                    else{
						if((($link->event_status!=4) &&   ($link->edit_status==4))){
							
					$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<div style="display:'.$display.';" id="accept-'.$link->post_id.'"><strong>User Response:</strong>Event Accepted</div>');
					$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<div style="display:'.$displayreject.';"id="reject-'.$link->post_id.'"><strong>User Response:</strong>Event Rejected</div>');
                  
							
						} else{
						$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<div <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" style="padding:0px 10px 0px 6px;""><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/icon-event-expired.png">&nbsp; <span class="event-expired">This event was no longer available.</span></div>');
						}
					}
					if($link->event_status == 5){
						$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<div>This event was modified.</div>');
						
					}


					/* Start: TIMELINE Event Staus */

					$tpl->layout->inner_block->setVar('activity_attachments_link_description', '

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" style="padding:10px 10px 0px 6px;">

						<ul class="list-inline">
    <li><img src="'.$C->SITE_URL.'apps/events/static/images/icon-status-event.png" class="img-responsive"></li>
    <li>Status - <span class="txt-accepted">'.$st.'</span></li>
    </ul>
    </div>

');

					/* End: TIMELINE Event Staus */
					
					
					} else{
						
						$tpl->layout->inner_block->setVar('activity_attachments_link_description', '<div>Event Cancelled</div>');

						
					}
					
					}else{
						/*if((($link->event_status!=2) &&   ($link->edit_status!=4)) || (($link->event_status!=4) &&   ($link->edit_status==4))   ){ */
						if($link->event_status !=4){
						$tpl->layout->inner_block->setVar('activity_attachments_link_description', 
							
   '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content event-list-blue-bg" style="padding:0px 0px 0px 4px;">
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

							$tpl->layout->inner_block->setVar('activity_attachments_link_description','<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" style="padding:0px 10px 0px 6px;""><img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/icon-event-expired.png">&nbsp; <span class="event-expired">This event was no longer available.</span></div>');

							}

							
						}
						
					}
											$tpl->layout->inner_block->setVar('css-event-url','css-event-url');

					}else{
						$tpl->layout->inner_block->setVar('activyimagecss','col-md-3 col-lg-3');
						$tpl->layout->inner_block->setVar('activydesccss','col-md-9 col-lg-9');
						$tpl->layout->inner_block->setVar('activitymaintimeline','container-buzzurl-timeline col-md-12 col-lg-12');

					}

					if(isset($link->image) && !empty($link->image)){
						$tpl->layout->inner_block->setVar('activity_attachments_link_image', '<a target="_blank" href="'.$link->link.'"  class="thumb"><img src="'. $C->STORAGE_URL.'attachments/1/'.$link->image.'"></a>');		
					}
					if(isset($link->mainurl) && !empty($link->mainurl)){
						$tpl->layout->inner_block->setVar('activity_website','<a target="_blank" href="'.$link->link.'" class="thumb">'.$link->mainurl.'</a>');		
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
			$checkads =$buff->checkcommercialadds($buff->post_user->id,$buff->post_date);
			if( !empty($checkads) ){
			    $tpl->layout->useInnerBlock('activity-attachments'); 
			     $css ='lightbox-image image-thumb cboxElement';
			    foreach($checkads as $adskeys=>$adsvals){
			        if(!empty($adsvals) && !empty($adsvals->big_image) ){
			             if(!empty($adsvals) && $adsvals->ads_access_source == 1 && $is_mobile == 1 && $adsvals->whatsapp_number != '' ){
		               /* whatsup sending */
		               if(strpos( $adsvals->whatsapp_number,"+91") !== false){
    		            }else{
    		                 $adsvals->whatsapp_number ="+91 ".$adsvals->whatsapp_number; 
    		              } 
    		             
    		              $display_url ="https://api.whatsapp.com/send?phone=".$checkadexist->whatsapp_number."&text=Hello";
    		              $css ='';
		           
    		          
		              
		           }else if($adsvals->ads_access_source == 2 && $is_mobile == 1 && $adsvals->callnow_number !='' ){
		                /* call now in mobile*/
		                if(strpos( $adsvals->callnow_number,"+91") !== false){
    		              }else{
    		                 $adsvals->callnow_number ="+91 ".$adsvals->callnow_number; 
    		              }
    		             
    		              $display_url ="tel:".$checkadexist->callnow_number;
    		               $css ='';
		            
		           }else if($adsvals->ads_access_source == 3 && $adsvals->display_url != ''){
		               /* URL Click */
		                $display_url =$adsvals->display_url;
		                 $css ='';
		          
		           }else{
		               $display_url = $C->STORAGE_URL.'advs/'.$adsvals->big_image;
		               $css = 'lightbox-image image-thumb cboxElement';
		               
		           }
		  

					 if(isset($adsvals->ad_display_type) && $adsvals->ad_display_type == "video" ){
		                $tpl->layout->inner_block->setVar('activity_attachment_images', '<video controls muted  width="100%" autoplay="autoplay">
		                 <source src="'.$C->STORAGE_URL.'advs/'.$adsvals->big_image.'" type="video/mp4"></video>
		                ');
		            }else{
					$tpl->layout->inner_block->setVar('activity_attachment_images', '<a target="_blank"  href="'.($display_url).'" class="'.$css.'"><img width="100%" alt="Image" src="'.$C->STORAGE_URL.'advs/'.$adsvals->big_image.'" /></a>');
					$tpl->layout->inner_block->setVar('activity_attachment_images_preview', '<a target="_blank" href="'.($display_url).'" class="'.$css.' "><img alt="Image" src="'.$C->STORAGE_URL.'advs/'.$adsvals->big_image.'" /></a>');
			        }
			        }
			            
			        }
			     			$tpl->layout->inner_block->saveInBlockPart('activity_attachments');
   
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
			
			$tpl->layout->block->setVar('activity_nocomments', 'no-comments');
			$tpl->layout->useInnerBlock('activity-comments-container');
			$tpl->layout->inner_block->saveInBlockPart( 'activity_comments_container' );
		}
		$tpl->layout->block->save( 'activity-container-list', true );
		}		
	}

	?>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>

function myFunctionpostshare(id) {
             $.ajax({
         //      url: 'services/activities/share_count',

        url: '<?php  echo $C->SITE_URL;?>services/activities/share',
            type: 'POST', 
            dataType: 'JSON',
            data: {id : id},
            success: function (data) {
                $.each(data,function(key,value){
                    
            var purpose = value.html;
            
            var qq1 =purpose.replace(/\.|,/g, '');
            var qq =qq1.replace(/\'|,/g, '');              
                        
                    
                 $( "."+id ).html(qq);   
                })
                
              //  $("#sssssssss").append("<b>Appended text</b>");
            }
        });
        }

$(document).ready(function() 
 {
     
     
    // $('.menu-options a').click(function(e) 
    // { 
        
    //     var id = ($(this).closest("ul").attr("id")); 
    //  $.ajax({
    //      //      url: 'services/activities/share_count',

    //     url: '<?php  echo $C->SITE_URL;?>services/activities/share',
    //         type: 'POST', 
    //         dataType: 'JSON',
    //         data: {id : id},
    //         success: function (data) {
    //             $.each(data,function(key,value){
                    
    //         var purpose = value.html;
            
    //         var qq1 =purpose.replace(/\.|,/g, '');
    //         var qq =qq1.replace(/\'|,/g, '');              
                        
                    
    //              $( "."+id ).html(qq);   
    //             })
                
    //           //  $("#sssssssss").append("<b>Appended text</b>");
    //         }
    //     });
    //      });
     $('.adsaction').click(function(e) 
    { 
        var adid = $(this).attr("data-customadid");
        var postid = $(this).attr("data-custompostid");
        $.ajax({
            data:{postid:postid,adid:adid},	
			url:"<?php  echo $C->SITE_URL;?>adsclickaction",	
            type: 'POST',
            dataType: 'html',
           
            success: function (data) {
            }
        });
       
    });
    });
</script>

