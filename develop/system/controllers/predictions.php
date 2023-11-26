<?php
	error_reporting(0);
	if( !$this->user->is_logged ) {
		$this->redirect('home');
	}
	if(!empty($_POST['postid'])){
		$postid = $_POST['postid'];
		$type = $_POST['type'];
		$hindsetreason  =$db2->query('SELECT hindsight_reason FROM post_prediction WHERE post_id="'.$postid.'" ');
		$hindsight_reason                =$db2->fetch_object($hindsetreason);
		
		
   $asdf = '<div class="modal-dialog" id="graph-chat" >
 
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Handset reason </h4>
        </div>
        <div class="modal-body">
		<div class="row">
		 <div>Reason :<input type="text" value="'.$hindsight_reason->hindsight_reason.'" id="hindsight-'.$type.$postid.'" onkeyup="validate(this,'.$postid.')">
		 </div>
		  <div id="handsetreason-error"class="notifyjs-container" style="top: 37px; left: 168px; overflow: hidden; display: hidden;"><div class="notifyjs-bootstrap-base notifyjs-bootstrap-error">
            <span data-notify-text="" class="notifyjs-text">This field is required</span>
         </div></div>
		 		   <button type="button" class="btn btn-default btn-primary"  data-toggle="modal"  onclick="myFunction('.$postid.','.$type.')">Change</button>

		</div>


          
        </div>
		<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      
      </div>
      
    </div>';
	echo $asdf;
	EXIT;
		
	}
	if(!empty($_POST['reason'])){
		$reason = $_POST['reason'];
		$postids = $_POST['postids'];
		$updateres = $db2->query('UPDATE post_prediction SET hindsight_reason="'.$reason.'" where post_id='.$postids.' ');
		if($updateres ==true){
			echo 'YES';
			
		}
		EXIT;
		
		
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
	//earnings
	//$earn   = $db2->query('SELECT SUM(`balance`) as totalearned from `user_earning_transactions` WHERE `transaction_type`="CPAM" OR `transaction_type`="CPAE" AND user_id = "'.$this->user->id.'" order by uet_id desc LIMIT 1');
	//$earnres        =  $db2->fetch_object($earn);
	//$totalearned    =$earnres->totalearned;
	//$D->totalearned = $totalearned;
	//print_r($D->totalearned);exit;
	$earn   = $db2->query('SELECT SUM(`transaction_amount`) as totalearned from `user_earning_transactions` WHERE  user_id = "'.$this->user->id.'" order by uet_id desc LIMIT 1');
	$earnres        =  $db2->fetch_object($earn);
	$totalearned    =$earnres->totalearned;
	
	if(!empty($totalearned)){
		$D->totalearned = $totalearned;
		
	}else{
		$D->totalearned =0;
		
	}
	//myprediction total amount
		$earnavail   = $db2->query('SELECT sum(predict_value) as predictcnt from post_prediction WHERE  user_id = "'.$this->user->id.'" ');
		$predictres        =  $db2->fetch_object($earnavail);
	$predictcnt    =$predictres->predictcnt;
	if(!empty($predictcnt)){
		$D->predictcnt = ($predictcnt);
	}else{
		$D->predictcnt = 0;
		
	}
	 //My polls tab
   $pollmyres           = $db2->query('SELECT ps.poll_id,ps.poll_question,p.id as post_id,p.date as postdate  FROM `posts` as p inner join polls as ps ON p.id = ps.posts_id 
		inner join users as u ON u.id=p.user_id
		

          WHERE p.user_id = "'.$this->user->id.'" group by ps.poll_id order by ps.poll_id desc LIMIT 3
				   

	   ');
     while($pollmyresfetch[] = $db2->fetch_object($pollmyres)){
	 }
	 	$D->pollmyresfetchresults  = $pollmyresfetch;
//my Response tab
	$pollmyresponse           = $db2->query('SELECT ps.poll_id,ps.poll_question,p.id as post_id,p.date as postdate  FROM `posts` as p inner join polls as ps ON p.id = ps.posts_id 
		inner join  post_poll_votes as ppu ON ppu.POLL_ID=ps.poll_id
		inner join users as u on u.id = ppu.VOTER_USER_ID

          WHERE ppu.VOTER_USER_ID	 = "'.$this->user->id.'" group by ps.poll_id order by ps.poll_id desc LIMIT 3
				   

	   ');
	    while($pollmyresponsefetch[] = $db2->fetch_object($pollmyresponse)){
		}
		$D->pollmyresponsefetchresults  = $pollmyresponsefetch;
	
	//user predict details
	$userpredict   = $db2->query('SELECT Level,Hit,Miss,NotionalAmount from `user_predict_details` WHERE  User_id = "'.$this->user->id.'" order by id desc LIMIT 1');
	$userpredictres        =  $db2->fetch_object($userpredict);
	$D->userpredictres = $userpredictres; 
	$fetch            =$db2->query('SELECT u.id,u.username,u.fullname,u.avatar,u.about_me FROM  street_suggestion as st
	                          INNER JOIN users AS u  ON st.user_id=u.id
                              where st.user_id NOT IN('.$fetchres.')							  
							  group by u.id
	                          order by rand() limit 3');
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
	/*all predictions tab */
	$offset        = 0;
	$allpredictons   =$post->allpredictions($this->user->id,$offset);
	$D->allpredictcnt = count($allpredictons);
	//myearnings chat
	$mypieearnings = $db2->query('SELECT sum(u.transaction_amount) as y,a.asset_name as indexLabel  FROM user_earning_transactions as u inner join post_prediction as pp on pp.id = u.reference_id inner join assets as a ON a.id=pp.asset_id where pp.user_id= "'.$this->user->id.'" group by pp.asset_id' );
	
	while($myearningsres[] = $db2->fetch_object($mypieearnings)){
		
	}
	$myearningsresjson  = array_filter($myearningsres);
	$D->myearningsresjson =  json_encode($myearningsresjson);
	//prediction amount chart
	$mypieearnings = $db2->query('SELECT sum(pp.predict_value) as y,a.asset_name as indexLabel  FROM post_prediction as pp inner join assets as a ON a.id=pp.asset_id where pp.user_id= "'.$this->user->id.'" group by pp.asset_id' );
	while($mypredictvalues[] = $db2->fetch_object($mypieearnings)){
		
	}
	$mypredictvaluesjson  = array_filter($mypredictvalues);
	$D->mypredictvaluesjson =  json_encode($mypredictvaluesjson);
	
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
				                       
				$myincorrectintradatahtml .='<div class="prediction-buzz-data prediction-layout">'.$allpredictval->asset_name.'($'.$allpredictval->ticker.')<img src="'.$C->SITE_URL.'/static/images/icons/'.$imag.'"> to be '.$con.' by '.$percentage.'% from '.$allpredictval->prediction_base_price.' in '.substr($allpredictval->end_date,0,10).' because of '.$allpredictval->predict_reason.'.</div>';
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
				if($allpredictval->user_id = $this->user->id){
					$type=1;
					$handset ='If you want to change Hindsight reason please <a  class="mymodal" data-toggle="modal" rel="'.$allpredictval->post_id.'" type="'.$type.'" >click here </a> 
                     <div id="handset-'.$type.$allpredictval->post_id.'"  class="modal fade-'.$type.'-'.$allpredictval->post_id.'" ></div>

					
					';
				}else{
					$handset ='';
					
				}
				$myincorrectintradatahtml .='<div class="prediction-buzz-data prediction-layout"> Your prediction on '.$allpredictval->asset_name.'($'.$allpredictval->ticker.') done  on '.substr($allpredictval->end_date,0,10).' was a '.$type.' <img src="'.$C->SITE_URL.'static/images/icons/'.$imag.'">.'.$handset.'</div>';
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
					$time = ($allpredictval->date);
					$res2  =date("Y-m-d h:i:s", $time);
					$pre   =date('Y-m-d h:i:s');
					$datetime1 = strtotime($pre);
					$datetime2 = strtotime($res2);
					$interval  = abs($datetime2 - $datetime1);
					$minutes   = round($interval / 60);
					
           				
           				if(($if_can_delete =='1' ||($this->user->id ==$allpredictval->user_id)) &&($minutes < 5)) {
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

		 <!-- start Parent -->
<div class="row" style="border:0px solid red; margin:0px; padding:0px;">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green" >


<div class="col-md-1 col-lg-1 col-sm-1 col-xs-2 image-div"" style="padding:0; overflow:hidden">

<a href="'.userlink($allpredictval->username).'" class="avatar bizcard" data-userid="'.$allpredictval->user_id.'"><img src="'.$src.'"  /></a>	

</div><!--/ end : col-md-1 -->
<div class="col-md-11 col-lg-11 col-sm-11 col-xs-10" style="padding:0px 3px 0px 8px;">


	<div class="activity-container">
		<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
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

		<div>'.$myincorrectintradatahtml.'</div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	
	
	<div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$allpredictval->post_id.'" class="permlink">'.post::parse_date($allpredictval->date).'</a>
				'.$replayhtml.'
				<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allpredictval->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allpredictval->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>

				<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
			<div class="dropdown">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$allpredictval->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$allpredictval->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$allpredictval->post_id.'').'"  target="_blank" >Linkedin</a></li>
								  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$allpredictval->post_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$allpredictval->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$allpredictval->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
                      <div class="like-list">'.$mark_content.'</div>							
						
	</div>
	<div>
	
	</div>
</div>

</div>
</div>
</div>

		';
		
	}
	}
}
	
	
	/*end all predictions tab */
	
	/*people you follow predictions tab */
	$offset =0;
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
				                       
				
				$mypredictionshtml .='<div class="prediction-buzz-data prediction-layout">'.$mypeopllepredictval->asset_name.'($'.$mypeopllepredictval->ticker.')<img src="'.$C->SITE_URL.'/static/images/icons/'.$imag.'"> to be '.$con.' by '.$percentage.'% from '.$mypeopllepredictval->prediction_base_price.' in '.substr($mypeopllepredictval->end_date,0,10).' because of '.$mypeopllepredictval->predict_reason.'.</div>';
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
				if($mypeopllepredictval->user_id = $this->user->id){
					$type=2;
					$handset ='If you want to change Hindsight reason please <a  class="mymodal" data-toggle="modal" rel="'.$mypeopllepredictval->post_id.'" type="'.$type.'" >click here </a> 
                     <div id="handset-'.$type.$mypeopllepredictval->post_id.'"  class="modal fade-'.$type.'-'.$mypeopllepredictval->post_id.'" ></div>

					
					';
				}else{
					$handset ='';
					
				}
				$mypredictionshtml .='<div class="prediction-buzz-data prediction-layout"> Your prediction on '.$mypeopllepredictval->asset_name.'($'.$mypeopllepredictval->ticker.') done  on '.substr($mypeopllepredictval->end_date,0,10).' was a '.$type.' <img src="'.$C->SITE_URL.'static/images/icons/'.$imag.'">.'.$handset.' </div>';

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

						$time = ($mypeopllepredictval->date);
						$res2  =date("Y-m-d h:i:s", $time);
						$pre   =date('Y-m-d h:i:s');
						$datetime1 = strtotime($pre);
						$datetime2 = strtotime($res2);
						$interval  = abs($datetime2 - $datetime1);
						$minutes   = round($interval / 60);
           				if(($if_can_delete =='1' ||($this->user->id ==$mypeopllepredictval->user_id)) && trim($minutes) < 5 ){
           				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$mypeopllepredictval->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
           				$delete ='';
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

<!-- start Parent -->
<div class="row" style="border:0px solid red; margin:0px; padding:0px;">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green" >

<div class="col-md-1 col-lg-1 col-sm-1 col-xs-2 image-div"" style="padding:0; overflow:hidden">

<a href="'.userlink($mypeopllepredictval->username).'" class="avatar bizcard" data-userid="'.$mypeopllepredictval->user_id.'"><img src="'.$src.'"  /></a>	

</div><!--/ end : col-md-1 -->
<div class="col-md-11 col-lg-11 col-sm-11 col-xs-10" style="padding:0px 3px 0px 8px;">


	<div class="activity-container">
		<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
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

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">'.$mypredictionshtml.'</div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	
	
	<div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$mypeopllepredictval->post_id.'" class="permlink">'.post::parse_date($mypeopllepredictval->date).'</a>
				'.$replayhtml.'

				<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$mypeopllepredictval->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$mypeopllepredictval->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>

				<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
			<div class="dropdown">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$mypeopllepredictval->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$mypeopllepredictval->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$mypeopllepredictval->post_id.'').'"  target="_blank" >Linkedin</a></li>
								  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$mypeopllepredictval->post_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$mypeopllepredictval->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$mypeopllepredictval->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
                      <div class="like-list">'.$mark_content.'</div>							
	</div>
	<div>
	
	</div>
</div>

</div>
</div>
</div>
		';
		
	}
	}
}

	
	
	/*end people you follow data */

	 
	 
		/*myprediction open  tab*/
		$status ="OPEN";
		$offset =0;
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
				$myincorrectintradatahtml .='
				<div class="prediction-buzz-data prediction-layout">'.$allintravals->asset_name.'($'.$allintravals->ticker.')<img src="'.$C->SITE_URL.'/static/images/icons/'.$imag.'"> to be '.$con.' by '.$percentage.'% from '.$allintravals->prediction_base_price.' in '.substr($allintravals->end_date,0,10).' because of '.$allintravals->predict_reason.'.</div>';
					



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
						$time = ($allintravals->date);
						$res2  =date("Y-m-d h:i:s", $time);
						$pre   =date('Y-m-d h:i:s');
						$datetime1 = strtotime($pre);
						$datetime2 = strtotime($res2);
						$interval  = abs($datetime2 - $datetime1);
						$minutes   = round($interval / 60);

           				if(($if_can_delete =='1' ||($this->user->id ==$allintravals->user_id) ) && trim($minutes) < 5){
           				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allintravals->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
           				//$delete ='';
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

		 <!-- start Parent -->
<div class="row" style="border:0px solid red; margin:0px; padding:0px;">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green" >


<div class="col-md-1 col-lg-1 col-sm-1 col-xs-2 image-div"" style="padding:0; overflow:hidden">

<a href="'.userlink($allintravals->username).'" class="avatar bizcard" data-userid="'.$allintravals->user_id.'"><img src="'.$src.'"  /></a>	

</div><!--/ end : col-md-1 -->
<div class="col-md-11 col-lg-11 col-sm-11 col-xs-10" style="padding:0px 3px 0px 8px;">

	<div class="activity-container">
		<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
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

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 zeropadding">'.$myincorrectintradatahtml.'</div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	
	<div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$allintravals->post_id.'" class="permlink">'.post::parse_date($allintravals->date).'</a>
				'.$replayhtml.'

				<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allintravals->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>

				<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
			<div class="dropdown">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Linkedin</a></li>
								  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$allintravals->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
                      <div class="like-list">'.$mark_content.'</div>							
	</div>
	<div>
	
	</div>
</div>

</div>
</div>
</div>
		';
		
	}
	}
}
	/*end myprediction open  tab */
	/*myprediction closed  tab*/
		$status ="CLOSE";
		$offset =0;
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
				if($allclosedval->user_id = $this->user->id){
					$type=3;
					$handset ='If you want to change Hindsight reason please <a  class="mymodal" data-toggle="modal" rel="'.$allclosedval->post_id.'" type="'.$type.'" >click here </a> 
                     <div id="handset-'.$type.$allclosedval->post_id.'"  class="modal fade-'.$type.'-'.$allclosedval->post_id.'" ></div>

					
					';
				}else{
					$handset ='';
					
				}
				
				
				                       
				$myincorrectintradatahtml ='';
				$myincorrectintradatahtml .='<div class="prediction-buzz-data prediction-layout"> Your prediction on '.$allclosedval->asset_name.'($'.$allclosedval->ticker.') done  on '.substr($allclosedval->end_date,0,10).' was a '.$type.' <img src="'.$C->SITE_URL.'static/images/icons/'.$imag.'">.'.$handset.' </div>';
					



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
						$time = ($allclosedval->date);
						$res2  =date("Y-m-d h:i:s", $time);
						$pre   =date('Y-m-d h:i:s');
						$datetime1 = strtotime($pre);
						$datetime2 = strtotime($res2);
						$interval  = abs($datetime2 - $datetime1);
						$minutes   = round($interval / 60);

           				
           				if(($if_can_delete =='1' ||($this->user->id ==$allclosedval->user_id)) && trim($minutes) < 5 ){
           				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$allclosedval->post_id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity" ><img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
           				//$delete ='';
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

<!-- start Parent -->
<div class="row" style="border:0px solid red; margin:0px; padding:0px;">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box" style="border:0px solid green" >


<div class="col-md-1 col-lg-1 col-sm-1 col-xs-2 image-div"" style="padding:0; overflow:hidden">

<a href="'.userlink($allclosedval->username).'" class="avatar bizcard" data-userid="'.$allclosedval->user_id.'"><img src="'.$src.'"  /></a>	

</div><!--/ end : col-md-1 -->
<div class="col-md-11 col-lg-11 col-sm-11 col-xs-10" style="padding:0px 3px 0px 8px;">

	<div class="activity-container">
		<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">
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

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 zeropadding">'.$myincorrectintradatahtml.'</div>
		<div class="footer1 activity-footer meta-info">  </div>
	</div>
	
	
	<div class="clear"></div>
	
	<div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<i class="fa fa-clock-o">&nbsp;&nbsp;</i><a href="'.$C->SITE_URL.'view/post:'.$allclosedval->post_id.'" class="permlink">'.post::parse_date($allclosedval->date).'</a>
				'.$replayhtml.'

				<div class="like-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_liked? 'unlike' : 'like').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allclosedval->post_id.'"}').'">'.($is_liked? '<img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>' : '<img class="icons"src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>').'</a>'.$showlikes_btn.'</div>
				<div class="agree-list"><a href="" data-role="services" data-namespace="activities" data-action="'.($is_agree? 'disagree' : 'agree').'" data-value="'.htmlentities('{"activities_type":"'.$buff->post_type.'","activities_id":"'.$allclosedval->post_id.'"}').'">'.($is_agree? '<img  width="30px"src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>' : '<img width="30px"class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree2Disagree"/>').'</a>'.$showagreebtn_btn.'</div>

				<span class="reshare-list">'.$reshare_content.''.$resharecnt.'</span>
			<div class="dropdown">
							   <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
							   <ul class="menu-options">
								  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'view/post:'.$allclosedval->post_id.'').'&title='.urlencode(htmlspecialchars($vals->event_name)).'&source='.urlencode($C->SITE_URL.'view/post:'.$allclosedval->post_id.'').'&summary='.urlencode($C->SITE_URL.'view/post:'.$allclosedval->post_id.'').'"  target="_blank" >Linkedin</a></li>
								  <li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'view/post:'.$allclosedval->post_id.'').'"  target="_blank" >Google Plus</a></li>
								   <li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'view/post:'.$allclosedval->post_id.'').': '.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Twitter</a></li>
								  <li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'view/post:'.$allclosedval->post_id.'').'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$vals->event_name)).'"  target="_blank" >Facebook</a></li>
							   </ul>
							</div>
                      <div class="like-list">'.$mark_content.'</div>							
	</div>
	<div>
	
	</div>
</div>

</div>
</div>
</div>
		';
		
	}
	}
}
	/*end myprediction closed tab */
	
	
	
	
	//TEMPLATE CODE START 
	$tpl = new template( array('page_title' => $this->lang('dashboard_page_title', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );

	
	$tpl->initRoutine('DashboardLeftMenuPrediction', array());
	$tpl->routine->load();
	$tpl->layout->setVar('allpredicthtml', $allpredicthtml);
 	$tpl->layout->setVar('mypredictionopenhtml', $mypredictionopenhtml);
	$tpl->layout->setVar('myclosedhtml', $myclosedhtml);
	$tpl->layout->setVar('youfollowhtml', $youfollowhtml);


	$tpl->layout->useBlock('predictions');




	$tpl->layout->block->save('main_content');

	
	
	$tpl->display();
	
	
?>
