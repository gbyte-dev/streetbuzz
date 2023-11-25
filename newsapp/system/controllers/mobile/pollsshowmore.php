<?php
	error_reporting(0);
	if( !$this->user->is_logged ) {
		$this->redirect('home');
	}
	
	$this->load_langfile('inside/global.php');
	if($_POST['type'] =="poll_all"){
		$from    = $_POST['allshowcount'];
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
		 
		 
			
 
	   $poll_all_html .='<div class="activity no-comments">
<a href="'.userlink($pollallresfetch->username).'" class="avatar bizcard" data-userid="'.$pollallresfetch->id.'"><img src="'.$src.'"  /></a>	
	
	<div class="activity-container">
		<div class="activity-header">
			<a href="'.userlink($pollallresfetch->username).'" class="author bizcard" data-userid="'.$pollallresfetch->id.'">'. ($pollallresfetch->username) .'</a>
			<div class="meta-info">
				
				
			</div>
<div class="activity-options"><a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$pollallresfetch->posts_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" class="delete">delete</a>	
		</div>		</div>
		<div class="activity-content"></div>
		<div></div>
		<div class="activity-poll"><div class="attachments lightbox-enabled">
	<div class="images">
		<div class="list-link-container" style="margin-bottom:30px">
			<b><label style="color:#2665ca;">Poll: '.$pollallresfetch->poll_question.'</label></b>
			<!-- <a target="_blank" href="" class="lightbox-image image-thumb cboxElement"><img alt="filename" src=""></a> -->
			<!-- //this is placeholder for video player <div class="video-placeholder"></div>  -->
		</div>
	
	</div>
	<div class="links">';
	
	  
             if(!empty($checkres)){
				$answersres =  $db2->query('SELECT  pa.answer,pa.poll_answer_id FROM  polls_answers AS pa  WHERE pa.poll_id="'.$pollallresfetch->poll_id.'"  AND pa.answer IS NOT NULL');
				while($answersresw  =    $db2->fetch_object($answersres)){
					if($answersresw->answer !=''){
					$answercount = $db2->query('SELECT  count(ANSWER_ID) as count FROM   post_poll_votes AS ppv  WHERE ppv.ANSWER_ID="'.$answersresw->poll_answer_id.'" ');
					$answercountres =  $db2->fetch_object($answercount); 
					$poll_all_html.='<div ><table><tr><td style="margin: 0px;width:200px">'.$answersresw->answer.'</td><td style="margin: -15px 0px 0px 60px;">'.$answercountres->count.'</td>
	</div></td></tr></table></div>';
					
					}
					
				}

				            
			 
			 
		 }else{
			 		$answersres =  $db2->query('SELECT  pa.answer,pa.poll_answer_id FROM  polls_answers AS pa  WHERE pa.poll_id="'.$pollallresfetch->poll_id.'"  AND pa.answer IS NOT NULL');
									while($answersresw  =    $db2->fetch_object($answersres)){
														if($answersresw->answer !=''){

																$poll_all_html.= '<div><input onclick="changeurl(this.value,this.id)" id="'.$pollallresfetch->poll_id.'" class="option'.$pollallresfetch->poll_answer_id.' radio'.$pollallresfetch->poll_id.'" name="option" type="radio" value="'.$pollallresfetch->poll_answer_id.'"/>'.$answersresw->answer.'</div><br>';

														}
									}
									$poll_all_html.='<span id="optionerror'.$pollallresfetch->poll_id.'"></span><br><span><a onclick="checkoption('.$pollallresfetch->poll_id.')" id="suboption'.$pollallresfetch->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=answer&poll_id='.$pollallresfetch->poll_id.'&from='.$this->user->id.'"><button style="color:white;background-color:orange; padding:6px 8px; border:1px solid orange;">Submit</button></a>&nbsp;&nbsp;&nbsp;';

		 }
		 if($this->user->id==$pollallresfetch->id){ 
		      $poll_all_html .='<span><a id="suboption'.$pollallresfetch->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$pollallresfetch->poll_id.'"><button class="btn-download-results">Download Results</button></a></span>';
			 
			 
		 }


					
					
				
			

	
	$poll_all_html .='</div>
	<div class="activity-poll-option"><span></span></div>
</div></div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	<!-- <div class="comments-thread-container " data-value="{&quot;activities_type&quot;:&quot;public&quot;,&quot;activities_id&quot;:&quot;311&quot;}">
		
		<div class="comments-editor-field offset_comment"><a href="#" class="additionalcomment" data-action="activityAddComment" data-namespace="comments"  data-role="services"> <span class="glyphicon glyphicon-comment"></span> Write a comment ...</a></div>
	</div>  -->
	<div>
	<div class="activity-footer meta-info">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$pollallresfetch->posts_id.'" class="permlink">'.post::parse_date($pollallresfetch->postdate).'</a>
				<a style="cursor:pointer" onclick="activityAddCommentchield('.$pollallresfetch->posts_id.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>
				<a style="cursor:pointer"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png" title="Like"></a>
				<a style="cursor:pointer"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"></a>
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
				</div>
				<div class="comment-chield" style="display:none" id="chield'.$pollallresfetch->posts_id.'">				

				<div class="comments-editor data-content-placeholder" data-token="020e4e354">
				<div>
				
				<div class="activity-header commentpost'.$pollallresfetch->posts_id.'">

				<a href="'.$C->SITE_URL.''.$pollallresfetch->username.'" class="author bizcard" data-userid="'.$pollallresfetch->id.'">'.$pollallresfetch->username.'</a>

				
				</div>
				<form method="post"action="'.$C->SITE_URL.'plugin/poll/admin?action=comment&amp;posts_id='.$pollallresfetch->posts_id.'">
				<div class="comments-editor-content">
					<div class="user-status-field htmlarea">
					<div class="commentpoll commentpoll'.$pollallresfetch->posts_id.'">
					</div>
						<div class="textarea-wrap comment">
							<textarea name="message">@'.$pollallresfetch->username.' </textarea>
							<div class="textarea-highlighter"><span></span></div>
						</div>
					</div>			
							<div class="buttons">
								<button type="button" id="'.$pollallresfetch->posts_id.'" class="comment-post pollcreate left comment-post'.$pollallresfetch->posts_id.' post-btn btn blue"><span>POLL</span></button>
								
									<input type="submit" class="comment-post comment-post'.$pollallresfetch->posts_id.' post-btn btn  blue center" value="Buzz">
								
							</div>
						</div></form>
					</div>
				</div></div><br><br>
	</div>
	<div>
	
	</div>
</div>';
	   
	}
	echo $poll_all_html;
	}
		if($_POST['type'] =="mypoll_all"){
			$from    = $_POST['mypollshowcount'];
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
		 
		 
			
 
	   //print_r($pollallresfetch);
	   $poll_my_html .='<div class="activity no-comments">
<a href="'.userlink($pollmyresfetch->username).'" class="avatar bizcard" data-userid="'.$pollmyresfetch->id.'"><img src="'.$src.'"  /></a>	
	
	<div class="activity-container">
		<div class="activity-header">
			<a href="'.userlink($pollmyresfetch->username).'" class="author bizcard" data-userid="'.$pollmyresfetch->id.'">'. ($pollmyresfetch->username) .'</a>
			<div class="meta-info">
				
				
			</div>
<div class="activity-options"><a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$pollmyresfetch->posts_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" class="delete">delete</a>	
		</div>		</div>
		<div class="activity-content"></div>
		<div></div>
		<div class="activity-poll"><div class="attachments lightbox-enabled">
	<div class="images">
		<div class="list-link-container" style="margin-bottom:30px">
			<b><label style="color:#2665ca;">Poll: '.$pollmyresfetch->poll_question.'</label></b>
			<!-- <a target="_blank" href="" class="lightbox-image image-thumb cboxElement"><img alt="filename" src=""></a> -->
			<!-- //this is placeholder for video player <div class="video-placeholder"></div>  -->
		</div>
	
	</div>
	<div class="links">';
	
	  
             if(!empty($checkres)){
				$answersres =  $db2->query('SELECT  pa.answer,pa.poll_answer_id FROM  polls_answers AS pa  WHERE pa.poll_id="'.$pollmyresfetch->poll_id.'"  AND pa.answer IS NOT NULL');
				while($answersresw  =    $db2->fetch_object($answersres)){
					if($answersresw->answer !=''){
					$answercount = $db2->query('SELECT  count(ANSWER_ID) as count FROM   post_poll_votes AS ppv  WHERE ppv.ANSWER_ID="'.$answersresw->poll_answer_id.'" ');
					$answercountres =  $db2->fetch_object($answercount); 
					$poll_my_html.='<div ><table><tr><td style="margin: 0px;width:200px">'.$answersresw->answer.'</td><td style="margin: -15px 0px 0px 60px;">'.$answercountres->count.'</td>
	</div></td></tr></table></div>';
					
					}
					
				}

				            
			 
			 
		 }else{
			 		$answersres =  $db2->query('SELECT  pa.answer,pa.poll_answer_id FROM  polls_answers AS pa  WHERE pa.poll_id="'.$pollmyresfetch->poll_id.'"  AND pa.answer IS NOT NULL');
									while($answersresw  =    $db2->fetch_object($answersres)){
														if($answersresw->answer !=''){

																$poll_my_html.= '<div><input onclick="changeurl(this.value,this.id)" id="'.$pollmyresfetch->poll_id.'" class="option'.$pollmyresfetch->poll_answer_id.' radio'.$pollmyresfetch->poll_id.'" name="option" type="radio" value="'.$pollmyresfetch->poll_answer_id.'"/>'.$answersresw->answer.'</div><br>';

														}
									}
									$poll_my_html.='<span id="optionerror'.$pollmyresfetch->poll_id.'"></span><br><span><a onclick="checkoption('.$pollmyresfetch->poll_id.')" id="suboption'.$pollmyresfetch->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=answer&poll_id='.$pollmyresfetch->poll_id.'&from='.$this->user->id.'"><button style="color:white;background-color:orange; padding:6px 8px; border:1px solid orange;">Submit</button></a>&nbsp;&nbsp;&nbsp;';

		 }
		 if($this->user->id==$pollmyresfetch->id){ 
		      $poll_my_html .='<span><a id="suboption'.$pollmyresfetch->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$pollmyresfetch->poll_id.'"><button class="btn-download-results">Download Results</button></a></span>';
			 
			 
		 }


					
					
				
			

	
	$poll_my_html .='</div>
	<div class="activity-poll-option"><span></span></div>
</div></div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	<!-- <div class="comments-thread-container " data-value="{&quot;activities_type&quot;:&quot;public&quot;,&quot;activities_id&quot;:&quot;311&quot;}">
		
		<div class="comments-editor-field offset_comment"><a href="#" class="additionalcomment" data-action="activityAddComment" data-namespace="comments"  data-role="services"> <span class="glyphicon glyphicon-comment"></span> Write a comment ...</a></div>
	</div>  -->
	<div>
	<div class="activity-footer meta-info">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$pollmyresfetch->posts_id.'" class="permlink">'.post::parse_date($pollmyresfetch->postdate).'</a>
				<a style="cursor:pointer" onclick="activityAddCommentchield('.$pollmyresfetch->posts_id.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>
				<a style="cursor:pointer"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png" title="Like"></a>
				<a style="cursor:pointer"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"></a>
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
				</div>
				<div class="comment-chield" style="display:none" id="chield'.$pollmyresfetch->posts_id.'">				

				<div class="comments-editor data-content-placeholder" data-token="020e4e354">
				<div>
				
				<div class="activity-header commentpost'.$pollmyresfetch->posts_id.'">

				<a href="'.$C->SITE_URL.''.$pollmyresfetch->username.'" class="author bizcard" data-userid="'.$pollmyresfetch->id.'">'.$pollmyresfetch->username.'</a>

				
				</div>
				<form method="post"action="'.$C->SITE_URL.'plugin/poll/admin?action=comment&amp;posts_id='.$pollmyresfetch->posts_id.'">
				<div class="comments-editor-content">
					<div class="user-status-field htmlarea">
					<div class="commentpoll commentpoll311">
					</div>
						<div class="textarea-wrap comment">
							<textarea name="message">@'.$pollmyresfetch->username.' </textarea>
							<div class="textarea-highlighter"><span></span></div>
						</div>
					</div>			
							<div class="buttons">
								<button type="button" id="'.$pollmyresfetch->posts_id.'" class="comment-post pollcreate left comment-post'.$pollmyresfetch->posts_id.' post-btn btn blue"><span>POLL</span></button>
								
									<input type="submit" class="comment-post comment-post'.$pollmyresfetch->posts_id.' post-btn btn  blue center" value="Buzz">
								
							</div>
						</div></form>
					</div>
				</div></div><br><br>
	</div>
	<div>
	
	</div>
</div>';
	   
	}
	echo $poll_my_html;
		}
				if($_POST['type'] =="myresponsepoll_all"){
					$from    = $_POST['myresponseshowcount'];
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
		 
		 
			
 
	   //print_r($pollallresfetch);
	   $poll_myresponse_html .='<div class="activity no-comments">
<a href="'.userlink($pollmyresponsefetch->username).'" class="avatar bizcard" data-userid="'.$pollmyresponsefetch->id.'"><img src="'.$src.'"  /></a>	
	
	<div class="activity-container">
		<div class="activity-header">
			<a href="'.userlink($pollmyresponsefetch->username).'" class="author bizcard" data-userid="'.$pollmyresponsefetch->id.'">'. ($pollmyresponsefetch->username) .'</a>
			<div class="meta-info">
				
				
			</div>
<div class="activity-options"><a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$pollmyresponsefetch->posts_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" class="delete">delete</a>	
		</div>		</div>
		<div class="activity-content"></div>
		<div></div>
		<div class="activity-poll"><div class="attachments lightbox-enabled">
	<div class="images">
		<div class="list-link-container" style="margin-bottom:30px">
			<b><label style="color:#2665ca;">Poll: '.$pollmyresponsefetch->poll_question.'</label></b>
			<!-- <a target="_blank" href="" class="lightbox-image image-thumb cboxElement"><img alt="filename" src=""></a> -->
			<!-- //this is placeholder for video player <div class="video-placeholder"></div>  -->
		</div>
	
	</div>
	<div class="links">';
	
	  
             if(!empty($checkres)){
				$answersres =  $db2->query('SELECT  pa.answer,pa.poll_answer_id FROM  polls_answers AS pa  WHERE pa.poll_id="'.$pollmyresponsefetch->poll_id.'"  AND pa.answer IS NOT NULL');
				while($answersresw  =    $db2->fetch_object($answersres)){
					if($answersresw->answer !=''){
					$answercount = $db2->query('SELECT  count(ANSWER_ID) as count FROM   post_poll_votes AS ppv  WHERE ppv.ANSWER_ID="'.$answersresw->poll_answer_id.'" ');
					$answercountres =  $db2->fetch_object($answercount); 
					$poll_myresponse_html.='<div ><table><tr><td style="margin: 0px;width:200px">'.$answersresw->answer.'</td><td style="margin: -15px 0px 0px 60px;">'.$answercountres->count.'</td>
	</div></td></tr></table></div>';
					
					}
					
				}

				            
			 
			 
		 }else{
			 		$answersres =  $db2->query('SELECT  pa.answer,pa.poll_answer_id FROM  polls_answers AS pa  WHERE pa.poll_id="'.$pollmyresponsefetch->poll_id.'"  AND pa.answer IS NOT NULL');
									while($answersresw  =    $db2->fetch_object($answersres)){
														if($answersresw->answer !=''){

																$poll_myresponse_html.= '<div><input onclick="changeurl(this.value,this.id)" id="'.$pollmyresponsefetch->poll_id.'" class="option'.$pollmyresponsefetch->poll_answer_id.' radio'.$pollmyresponsefetch->poll_id.'" name="option" type="radio" value="'.$pollmyresponsefetch->poll_answer_id.'"/>'.$answersresw->answer.'</div><br>';

														}
									}
									$poll_myresponse_html.='<span id="optionerror'.$pollmyresponsefetch->poll_id.'"></span><br><span><a onclick="checkoption('.$pollmyresponsefetch->poll_id.')" id="suboption'.$pollmyresponsefetch->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=answer&poll_id='.$pollmyresponsefetch->poll_id.'&from='.$this->user->id.'"><button style="color:white;background-color:orange; padding:6px 8px; border:1px solid orange;">Submit</button></a>&nbsp;&nbsp;&nbsp;';

		 }
		 if($this->user->id==$pollmyresponsefetch->id){ 
		      $poll_myresponse_html .='<span><a id="suboption'.$pollmyresponsefetch->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$pollmyresponsefetch->poll_id.'"><button class="btn-download-results">Download Results</button></a></span>';
			 
			 
		 }


					
					
				
			

	
	$poll_myresponse_html .='</div>
	<div class="activity-poll-option"><span></span></div>
</div></div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	<!-- <div class="comments-thread-container " data-value="{&quot;activities_type&quot;:&quot;public&quot;,&quot;activities_id&quot;:&quot;311&quot;}">
		
		<div class="comments-editor-field offset_comment"><a href="#" class="additionalcomment" data-action="activityAddComment" data-namespace="comments"  data-role="services"> <span class="glyphicon glyphicon-comment"></span> Write a comment ...</a></div>
	</div>  -->
	<div>
	<div class="activity-footer meta-info">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$pollmyresponsefetch->posts_id.'" class="permlink">'.post::parse_date($pollmyresponsefetch->postdate).'</a>
				<a style="cursor:pointer" onclick="activityAddCommentchield('.$pollmyresponsefetch->posts_id.')"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REPLY.png" title="Reply"></a>
				<a style="cursor:pointer"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png" title="Like"></a>
				<a style="cursor:pointer"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"></a>
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
				</div>
				<div class="comment-chield" style="display:none" id="chield'.$pollmyresponsefetch->posts_id.'">				

				<div class="comments-editor data-content-placeholder" data-token="020e4e354">
				<div>
				
				<div class="activity-header commentpost'.$pollmyresponsefetch->posts_id.'">

				<a href="'.$C->SITE_URL.''.$pollmyresponsefetch->username.'" class="author bizcard" data-userid="'.$pollmyresponsefetch->id.'">'.$pollmyresponsefetch->username.'</a>

				
				</div>
				<form method="post"action="'.$C->SITE_URL.'plugin/poll/admin?action=comment&amp;posts_id='.$pollmyresponsefetch->posts_id.'">
				<div class="comments-editor-content">
					<div class="user-status-field htmlarea">
					<div class="commentpoll commentpoll311">
					</div>
						<div class="textarea-wrap comment">
							<textarea name="message">@'.$pollmyresponsefetch->username.' </textarea>
							<div class="textarea-highlighter"><span></span></div>
						</div>
					</div>			
							<div class="buttons">
								<button type="button" id="'.$pollmyresponsefetch->posts_id.'" class="comment-post pollcreate left comment-post'.$pollmyresponsefetch->posts_id.' post-btn btn blue"><span>POLL</span></button>
								
									<input type="submit" class="comment-post comment-post'.$pollmyresponsefetch->posts_id.' post-btn btn  blue center" value="Buzz">
								
							</div>
						</div></form>
					</div>
				</div></div><br><br>
	</div>
	<div>
	
	</div>
</div>';
	   
	}
	echo $poll_myresponse_html;
				}
	
		
	
?>