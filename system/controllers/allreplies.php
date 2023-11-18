<?php
   error_reporting(0);
      if(!empty($_POST['postid'])){
      	$postid = $_POST['postid'];
   	$childid = $_POST['childid'];
   
   	
      $data = array();
   	$obj=$data[0];
   	$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);
   	$replayidquery = $db2->query('select parent_id FROM  post_replay WHERE 	replay_id	="'.$postid.'" LIMIT 1');
   	$replayres          =$db2->fetch_object($replayidquery);
   	$replayid        = $replayres->parent_id;
   	if($replayid !=''){
   		$newparentid = $replayid;
   		
   	}else{
   		$newparentid = $postid;
   		
   	}
   	if($_POST['view_type'] =="individual"){
   		$seriesquery = $db2->query('select series FROM  post_replay WHERE replay_id	="'.$childid.'" LIMIT 1');
   	    $series          =$db2->fetch_object($seriesquery);

   	    $seriescon              =unserialize($series->series);
   		$sortarry           =array_unique($seriescon);
   		$firstval            = current($sortarry);
   		$endsval            = end($sortarry);
   
   
   		$sortseries                   =implode(",",$sortarry);
   		
   		
   		$aquerysset	= $db2->query('SELECT p.*,u.username,u.id as user_id,u.avatar,p.message,p.date,p.id FROM posts as p
                    INNER JOIN users AS u ON  u.id =p.user_id 	
   				 WHERE p.id IN ('.$sortseries.') ', FALSE);
   
   		
   
   		
   		
   	}elseif($_POST['view_type'] =="new_view"){
   		$seriesquery = $db2->query('select series FROM  post_replay WHERE parent_id	="'.$childid.'" AND alternate_parent_id ="'.$postid.'"  ORDER BY id desc LIMIT 1');
   	    $series          =$db2->fetch_object($seriesquery);
   	    $seriescon              =unserialize($series->series);
   		$sortarry           =array_unique($seriescon);
   		$firstval            = current($sortarry);
   		$endsval            = end($sortarry);
   		$sortseries                   =implode(",",$sortarry);
   		
   		$repliesquery = $db2->query('select alternate_parent_id FROM  post_replay as pr
   		
   		WHERE  pr.replay_id ="'.$endsval.'"  ORDER BY id desc LIMIT 1 ');
   		$replies          =$db2->fetch_object($repliesquery);
   		
   		
   		$repliesquerywe = $db2->query('select replay_id FROM  post_replay as pr
   		
   		WHERE  pr.alternate_parent_id ="'.$replies->alternate_parent_id.'"  ORDER BY id desc  ');
   		while($repliesid         =$db2->fetch_object($repliesquerywe)){
   			$repli[] =$repliesid->replay_id;
   			
   		}
   
   
   
   		
   		
   		$aquerysset	= $db2->query('SELECT p.*,u.username,u.id as user_id,u.avatar,p.message,p.date,p.id FROM posts as p
                    INNER JOIN users AS u ON  u.id =p.user_id 	
   				 WHERE p.id IN ('.$sortseries.') ', FALSE);
   	}elseif($_POST['view_type'] =="view"){
   		$firstval = $newparentid;
   
   	
   	
   		$aquerysset	= $db2->query('
   		SELECT p.*,u.username,u.id as user_id,u.avatar,p.message,p.date,p.id FROM posts as p
                    INNER JOIN users AS u ON  u.id =p.user_id 	
   				 WHERE p.id="'.$newparentid.'" 
   				UNION
   				SELECT p.*,u.username,u.id as user_id,u.avatar,p.message,p.date,p.id FROM posts as p
                    INNER JOIN users AS u ON  u.id =p.user_id 	
   				 WHERE p.id="'.$childid.'" 
   				UNION
   		
   		SELECT p.*,u.username,u.id as user_id,u.avatar,p.message,p.date,p.id FROM posts as p
                    INNER JOIN users AS u ON  u.id =p.user_id 	
                   INNER JOIN  post_replay AS pr ON pr.replay_id = p.id				 
   				 WHERE pr.parent_id="'.$childid.'" AND pr.alternate_parent_id="'.$postid.'" AND pr.parent_id !="'.$postid.'" ', FALSE);
   		
   	}
   	
   				while($res[]=$db2->fetch_object($aquerysset)){
   					
   				}
   				$res = array_filter($res);
   				$dcnt =count($res)-1;;
   				
   	$data =array();			
 	$obj=$data[0];
	$buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);
  
      	
      ?>
<div class="modal-dialog graph-<?php echo $childid;?>" >
   <!-- Modal content-->
   <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" rel="<?php echo $firstval;?>" data-dismiss="modal" >&times;</button>
         <h4 class="modal-title">View Replies </h4>
      </div>
      <div class="modal-body">
	  <div id="allmain<?php echo $childid;?>">
	 <div class="janeeshallchild<?php echo $childid;?>"></div>

	  
	  
         <?php foreach($res as $keys=>$result){
            $parentiddd =$buff->get_parent_id($result->id);
            
            
            if(!empty($parentiddd)){
            $parentid = $parentiddd->parent_id;
            $replaycnt  = $buff->replaycount($result->id);
            
            }else{
            $parentid	= $result->id;
            
            }
			$eventdetails = $buff->geteventdetails($result->id);
			$poll  = $buff->replay_is_poll($result->id);
	        $assetdata   =$buff->assetdata($result->id);
			$prediction_data =$buff->predictiondata($result->id);

			if(!empty($assetdata)){
				$parentmessage =$buff->assethtml($result->id);
	        }elseif(!empty($eventdetails)){
				$parentmessage = $buff->eventhtml($result->id);
			}elseif(!empty($poll)){
				$parentmessage = $buff->pollhtml($result->id);
				
			}elseif(!empty($prediction_data)){
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
			$parentmessage .='<div class="prediction-buzz-data">'.$prediction_data[0]->asset_name.'($'.$prediction_data[0]->ticker.')<img src="'.$C->SITE_URL.'/static/images/icons/'.$imag.'"> to be '.$con.' by '.$percentage.'% from '.$prediction_data[0]->prediction_base_price.' in '.substr($prediction_data[0]->end_date,0,10).' because of '.$prediction_data[0]->predict_reason.'.</div>';
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
				$parentmessage .='<div style="background-color:#e3f8fe;font-size:12px;height:auto;padding: 10px;"> Your prediction on '.$prediction_data[0]->asset_name.'($'.$prediction_data[0]->ticker.') done  on '.substr($prediction_data[0]->end_date,0,10).' was a '.$type.' <img src="'.$C->SITE_URL.'static/images/icons/'.$imag.'">.'.$handset.' 
				</div>';
						
					}
			}else{
				$parentmessage =$buff->parsetext($result->message);	
				$parentmessage .=$buff->attchmentreplaydisplay($result->id);					

			}
			$tmp = array(
            '##BEFORE##' =>'',
            '##HOUR##' => 'hour',
            '##HOURS##' =>'hours',
            '##MIN##' => 'min',
            '##MINS##' =>'min',
            '##SEC##' =>'sec',
            '##SECS##' =>'sec',
            '##AND##' =>'and',
            '##AGO##' =>'ago',
            '##NOW##' =>'just published'
        );
		$txt =post::replay_parse_date($result->date);
		$date = str_replace(array_keys($tmp), array_values($tmp), $txt);

            
            $is_likedreplay     = $buff->new_liked($result->id);
            $likes_numbers       =$buff->new_liked_count($result->id);
            $likes_number        =$likes_numbers->likecount;
            
                    	$post_type='public';
                     if($likes_number > 0){					
            $showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$result->id.'"}').'">'.$likes_number.'</a>';
            }else{
            $showlikes_btn ='';  
            }
            //Agree content
            $is_replayreshares     = $buff->new_reshared($result->id);
            $likes_reshares       =$buff->new_reshare_count($result->id);
            $sharecount        =$likes_reshares->sharecount;
            $post_type ="public";
            $reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_replayreshares? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$result->id.'"}').'">'.($is_replayreshares? '<img  src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>' : '<img class="icons" src="'.$C->SITE_URL.'static/images/icons/REBUZZ.png" title="Rebuzz"/>').'</a>';
                         if($sharecount > 0){					
            
            $resharecnt ='<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$result->id.'"}').'">'.$sharecount.'</a>';
                         }else{
            $resharecnt ='';
            
            }
            //agree content
            $is_agree = $buff->is_post_agree($user->id,$result->id);
            $is_agree_cnt = $buff->is_post_agree_cnt($result->id);
            if($is_agree_cnt->cnt > 0){					
            $showagreebtn_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$result->id.'"}').'">'.$is_agree_cnt->cnt.'</a>';
            }else{
            $showagreebtn_btn ='';  
            }
            //markcontent
             $is_spam  = $buff->is_spam($result->id,"public");
            if($is_spam =="1"){
            $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$result->id.'"}').'"><em><img src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></em></a>';
                     }else{
                  $mark_content = '<a href="" data-role="services" data-namespace="spamprotector" title="spam"  data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$result->id.'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SPAM.png"></a>';
                     }
			 if(($user->id == $result->user_id )){
					
   
             				$delete ='<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$result->id.'"}').'" data-role="services" data-namespace="activities" data-action="deleteActivity"> <img src="'.$C->SITE_URL.'static/images/icons/DELETE.png"></a>';
             				}else{
             				$delete ='';
             				}        $is_fav  = $buff->isfav($user->id,$result->id);
	 
   			if(!empty($is_fav)){
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$result->id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   				
   			}else{
   				$fav = '<a href="" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$result->id.'"}').'" data-role="services" data-namespace="activities" data-action="bookmark" class="icons" ><img src="'.$C->SITE_URL.'static/images/icons/BOOKMARK.png"></a>';
   
   				
   			}
            $dat = 'data-value="'.htmlentities('{"activities_type":"'.$post_type.'","activities_id":"'.$result->id.'"}').'" ';
            $sha= '<div class="dropdown share">
               <a style="cursor:pointer" href="" class="menu-btn"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/SHARE.png" title="Share"/></a>
              
               <ul class="menu-options">
            	  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($C->SITE_URL.'/view/post:'.$result->id).'&title='.urlencode(htmlspecialchars($row->message)).'&source='.urlencode($C->SITE_URL.'/view/post:'.$result->id).'&summary='.urlencode($C->SITE_URL.'/view/post:'.$result->id).'"  target="_blank" >Linkedin</a></li>

<li><a href="http://plus.google.com/share?url='.urlencode($C->SITE_URL.'/view/post:'.$result->id).'"  target="_blank" >Google Plus</a></li>

<li><a href="http://twitter.com/intent/tweet?text='.urlencode($C->SITE_URL.'/view/post:'.$result->id).'"  target="_blank" >Twitter</a></li>

<li><a href="http://www.facebook.com/sharer.php?u='.urlencode($C->SITE_URL.'/view/post:'.$result->id).'&title='.urlencode(htmlspecialchars($row->message)).'"  target="_blank" >Facebook</a></li>
               </ul>
            </div>';
            if($dcnt == $keys){
            $css="tree1";
			$chi ="allchild".$childid;
            }else{
            $css="tree";
			$chi ="";

            }
		   if($result->avatar !=''){
					$img ='<img src="'.getAvatarUrl($result->avatar, 'thumbs1').'" alt="'.$result->username.'" />';
					
				}else{
					$img ='<img src="'.$C->STORAGE_URL.'avatars/thumbs1/_noavatar_user.gif" alt="'.$result->username.'" />';
					
				}
	      $posttypeRes =$buff->checkposttype($result->id);
		
		     if(!empty($posttypeRes) && $posttypeRes->posttype == 2){
		          $decodemessages =json_decode($result->message,true);
		          
		          if(!empty($decodemessages['post'])){
		              $decodemessagespost = $decodemessages["post"];
		               foreach($decodemessagespost as $decodekeys=>$decodevals){
		                   $key = array_keys($decodevals);
		                   $deocdkey =  $key[0];
		                   $deocdevalue = $decodevals[$deocdkey];
		                   $createtext = $buff->createLongreadElements($deocdkey,$deocdevalue);
		                   $finalstr .= $createtext;
		               }
		               
		               $parentmessage = $finalstr;
		          }
		     }
            
            
            
            ?>
         <div id="replaypopup-<?php echo $postid;?>"></div>
         <div>
            <div class="activity no-comments commentcontainer <?php echo $chi ?> " style="border: 0px solid #E1E8ED;">
               <!-- start Parent -->
               <ul class="<?php echo $css;?>">
                  <li>
                      <div class="row" style="padding:0px 4px;">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                           <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2 image-div" style="padding:0; overflow:hidden">
                           <a href="<?php echo userlink($result->username); ?>" class="avatar bizcard" data-userid="<?php echo userlink($result->user_id); ?>"><?php echo $img;?></a>
                           </div>

						   

                            <div class="col-lg-11 col-md-11 col-sm-11 col-xs-10" style="padding:0px 3px 0px 8px;">
                              <div class="activity-container">
                                 <div class="activity-header col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                 <a href="<?php echo userlink($result->username); ?>" class="author bizcard" data-userid="<?php echo userlink($result->user_id); ?>"><?php echo ($result->username); ?></a>

                                 <div class="activity-options"><?php echo $delete;?><?php echo $fav;?></div>

                                 </div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 zeropadding">
<a href="<?php echo $C->SITE_URL?>/view/post:<?php echo $postid ;?>" class="permlink"><?php echo $date; ?> <span class="glyphicon glyphicon-link"></span></a>
</div>


                                 <div class="activity-content"><?php echo $parentmessage; ?></div>
                              </div>
                             
                                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                 <div class="activity-footer meta-info col-lg-12 col-md-12 col-sm-12 col-xs-12 admin-margin-footer-popup zeropadding">
                                    
                                   
<span class="reply icon-ftr icon-ftr-reply">
                                    <a  style="cursor:pointer" onclick="showtextarea(<?php echo $result->id;?>)" ><img class="icons" src="<?php echo $C->SITE_URL ?>static/images/icons/REPLY.png" title="Reply"/></a>
</span>

                                    <div class="like-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="<?php  if($is_likedreplay){ echo 'unlike'; }else{ echo 'like';}?>" <?php echo $dat;?>><?php if($is_likedreplay){ ?><img  src="<?php echo $C->SITE_URL?>static/images/icons/LIKE.png"  title="Like"/><?php }else{ ?> <img  src="<?php echo $C->SITE_URL?>static/images/icons/LIKE.png" class="icons"  title="Like"/><?php }?> </a><?php echo $showlikes_btn;?></div>
                                    <div class="agree-list icon-ftr"><a href="" data-role="services" data-namespace="activities" data-action="<?php  if($is_agree){ echo 'disagree'; }else{ echo 'agree';}?>" <?php echo $dat;?>> <?php if($is_agree){ ?><img width="" src="<?php echo $C->SITE_URL?>static/images/icons/a2d.png"  title="Like"/><?php }else{ ?> <img width="" src="<?php echo $C->SITE_URL?>static/images/icons/a2d.png" class="icons"  title="Like"/><?php }?></a><?php echo $showagreebtn_btn;?></div>
                                    <span class="reshare-list icon-ftr"><?php echo $reshare_content ?><?php echo $resharecnt ?></span>
                                    <?php echo $sha;?>
                                 </div>
                                 <?php if($_POST['view_type'] =="view"){if(!empty($parentiddd)){
                                    if($replaycnt > 0){	
                                    if($newparentid !=$result->id && $childid != $result->id ){							   
                                    ?>
                                 <div class="box-footer"><a style="cursor:pointer;" onclick="replaycontentalllevels(<?php echo $parentid; ?>,<?php echo $result->id?>,<?php echo $firstval;?>)" class="pull-right" >View Replies</a></div>
                                 <?php }} } }elseif($_POST['view_type'] =="new_view" || $_POST['view_type'] =="individual" ){
                                    if($replaycnt > 0){
                                    if(in_array($result->id,$repli)){	?>
                                 <div class="box-footer"><a style="cursor:pointer;" onclick="replaycontentalllevels(<?php echo $parentid; ?>,<?php echo $result->id?>,<?php echo $firstval;?>)" class="pull-right" >View Replies</a></div>
                                 <?php }}
                                    }?>
                              </div>
                              <div id="con-<?php echo $result->id; ?>" style="display:none;" class="cons" rel="<?php echo $result->id;?>">
                                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  user-status-field htmlarea userreplaybuzz-<?php echo $result->id;?>">
                                    <div class="textarea-wrap comment ">
                                       <textarea class="editpostreplay" id="message-<?php echo $result->id; ?>" rel="<?php echo $result->id; ?>"name="message">@<?php echo $result->username;?> </textarea>
                                    </div>
                                 </div>
                                 <div class="htmlarea-ac">
                                    <div class="htmlarea-ac-container-replay"></div>
                                 </div>
                                 <input type="hidden" id="asset_val" value="1"></input>
                                 <div class="replayslide-<?php echo $result->id;?>" ></div>
                                 <input type="hidden"  class="replayticker1-<?php echo $result->id;?>">
                                 <input type="hidden"  class="replayticker2">
                                 <!--poll creation -->
                                 <div id="poll-<?php echo $result->id;?>" class="create_poll-<?php echo $result->id;?>" style="display: none;">
                                    <div>
                                       <form method="post" action="http://purpledot.co/works/streetbuzz/plugin/poll/admin?action=users&amp;poll_id=0&amp;from=users">
                                          <div>
                                             <h1 class="title-pages-poll">Add Poll</h1>
                                             <a style="cursor:pointer;" class="pull-right removepoll" rel="<?php echo $result->id;?>">Remove Poll</a>
                                             <div class="clear"></div>
                                          </div>
                                          <div class="edit">
                                             <div class="row">
                                                <div class="col-md-3 hidden-xs hidden-sm">
                                                   <!-- for center alignment and spacing -->
                                                </div>
                                                <div class="col-md-8" style="padding:0">
                                                   <div class="col-md-12" style="padding:0">
                                                      <div class="col-md-11" style="padding:0">
                                                         <span class="add-more"><strong>Question</strong></span> <br>				
                                                         <input type="text" class="form-control title" id="question-<?php echo $result->id;  ?>" name="question" value=""  >
                                                         <div class="answers">
                                                         </div>
                                                         <div class="form-group">
                                                            <span class="add-more">Answer1:</span><br>
                                                            <input type="text" name="answer[0]" id="answer0-<?php echo $result->id;  ?>" value="" rel="<?php echo $result->id;?>" class="form-control ans1  answerspoll-<?php echo $result->id;  ?>">
                                                         </div>
                                                         <div class="form-group">
                                                            <span class="add-more">Answer2:</span><br>
                                                            <input type="text" name="answer[1]" id="answer1-<?php echo $result->id;  ?>" rel="<?php echo $result->id;?>" value="" class="form-control ans2  answerspoll-<?php echo $result->id;  ?>">
                                                         </div>
                                                      </div>
                                                      <div class="col-md-12" style="padding:0;">
                                                         <div class="form-group1-<?php echo $result->id;?>">
                                                         </div>
                                                      </div>
                                                      <div class="col-md-12" style="padding:0">
                                                         <a style="cursor: pointer;"class="add-more" rel="<?php echo $result->id;?>" id="addanswers">+ Add Answers (optional)</a>
                                                      </div>
                                                   </div>
                                                   <!--/ div 'col-md-11' -->
                                                   <div class="col-md-12 poll-replaygrp"  style="padding:0">
                                                      <div class="col-md-11" style="padding:0">
                                                         <div style="padding:0"><input type="text" class="form-control poll-grouptxt" class="htmlarea textarea grouptxt" id="pollgrouptxt-<?php echo $result->id;  ?>" autocomplete="off"  value="" placeholder="Group" name="street_group" /></div>
                                                         <!-- dropdown -->
                                                         <div class="col-md-12 grptype-dropdown grptype-dropdown" id="grptype-dropdown"></div>
                                                         <!--/ end dropdown -->
                                                      </div>
                                                      <div class="col-md-1">
                                                         <a href="#" id="closegrp" class="poll-closegrp"><span class="glyphicon glyphicon-remove"></span></a>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12 poll-replayuser" style="padding:0">
                                                      <div class="col-md-11" style="padding:0">
                                                         <div  style="padding:0" class="urtxt"><input type="text" class="form-control poll-usertxt" class="group" id="pollusertxt-<?php echo $result->id;  ?>" rel="<?php echo $result->id;  ?>" autocomplete="off"  value="" placeholder="Users" name="street_user"  /></div>
                                                         <!-- dropdown -->
                                                         <div class="col-md-12 usertype-dropdown" id="usertype-dropdown"></div>
                                                         <!--/ end dropdown -->
                                                      </div>
                                                      <div class="col-md-1">
                                                         <a href="#" id="closeuser" class="poll-closeuser"><span class="glyphicon glyphicon-remove "></span></a>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12" style="padding:0">
                                                      <input type="button" class="btn btn-xs btn-white active poll-grp" value="+Group" />
                                                      <input type="button" class="btn btn-xs btn-white active poll-user"  value="+Add Users" />
                                                   </div>
                                                </div>
                                                <!--/  'col-md-12' -->
                                             </div>
                                             <!--/  'col-md-8' -->
                                          </div>
                                          <!--/  'row' --> 
                                       </form>
                                    </div>




                                    <style>
									.janeeshallchild<?php echo $childid;?>{
											position: absolute; 
											 border-left: 4px solid orange;
										   float: left; 
											 margin-top: 49px!important; 
												 margin-left: 33px;
										}
								      .btn-white {
                                       border-color: #0084B4;
                                       border-color: rgba(0,132,180,.5);
                                       color: #0084B4;
                                       background: rgba(255,255,255,0.75);
                                       border-style: solid;
                                       border-width: 1px;
                                       box-shadow: none;
                                       opacity: .8;
                                       -ms-filter: "alpha(opacity=80)";
                                       }
                                       .btn-white:hover {
                                       background-color: #1b95e0;
                                       color: #fff;
                                       }
                                       /* Usertype */
                                       .usertype-dropdown { margin-top: -10px; width:100%; z-index:50; display:none; background:#fff;}
                                       .usertype-dropdown {font-weight:bold; font-style:italic; color:#6E6E6E; font-size:10px; border:1px solid #C2C2C2; border-top:none;}
                                       .usertype-dropdown ul {list-style:none; margin:0px; border:0px solid #C2C2C2; border-top:none;}
                                       .usertype-dropdown ul li {border-bottom:1px solid #F5F5F5; cursor:pointer; display:block; width:100%; margin-left: -54px; padding:1px;}
                                       .usertype-dropdown ul li.hover {background:#0076a3; color: #fff;}
                                       .usertype-dropdown ul li.selection {color: #6E6E6E;}
                                       .usertype-dropdown ul li.selection:hover {color: #fff;}
                                    </style>
                                 </div>
                                 <!-- poll end-->
                                 <div id="eventdata-<?php echo $result->id;?>" class="eventdata-<?php echo $result->id;?>" style="display:none;">
                                    <!--event creation -->
                                    <h1 class="title-pages-poll">Create Event</h1>
                                    <div id="rmevent" class="row" style="text-align:right">
                                       <a style="cursor:pointer;padding-right:12px" class="rmbt" rel="<?php echo $result->id?>" >Remove event</a>
                                    </div>
                                    <div class="content pad">
                                       <form id="eventform"method="POST" enctype="multipart/form-data">
                                          <input type="hidden" id="display_type" name="display_type" value="community">
                                          <!-- start form -->
                                          <div class="row">
                                             <div class="col-md-2 hidden-xs hidden-sm">
                                                <!-- for center alignment and spacing -->
                                             </div>
                                             <div class="col-md-8">
                                                <div class="col-md-12">
                                                   <div class="col-md-11" style="padding:0">
                                                      <input type="text" class="form-control title" id="title-<?php echo $result->id;  ?>"  name="title" placeholder="Title" value="" maxlength="50" autocomplete="off">
                                                   </div>
                                                   <div class="col-md-15" style="padding:0">
                                                      <ul class="list-inline">
                                                         <li style="padding-right: 0px; padding-left: 0px;" ><input type="text" class="form-date start_date " rel="<?php echo $result->id;  ?>" id="start_date-<?php echo $result->id; ?>" name="start_date" value="" placeholder="Start Date"></li>
                                                         <li  style="padding-right: 0px; padding-left: 0px;" ><input type="text" class="form-time start_time" rel="<?php echo $result->id;  ?>" id="start_time-<?php echo $result->id; ?>" name="start_time" value=""  placeholder="Start Time"></li>
                                                         <li  style="padding-right: 0px; padding-left: 0px;" ><input type="text" class="form-date end_date" rel="<?php echo $result->id;  ?>"  id="end_date-<?php echo $result->id;  ?>" name="end_date" value=""  placeholder="End Date"></li>
                                                         <li  style="padding-right: 0px; padding-left: 0px;" ><input type="text" class="form-time end_time" rel="<?php echo $result->id;  ?>"  id="end_time-<?php echo $result->id;  ?>" name="end_time" value=""  placeholder="End Time"></li>
                                                      </ul>
                                                   </div>
                                                   <div class="col-md-11" style="padding:0">
                                                      <input type="text" class="form-control city" id="city-<?php echo $result->id;  ?>"   name="address" placeholder="Where?" value="<?php echo isset($_POST['address'])?$_POST['address']:'';?>" maxlength="50" autocomplete="off" placeholder="Enter a location">
                                                   </div>
                                                   <div class="col-md-12" style="padding:0"><a  style="cursor: pointer;" class="add-more addurl" rel="<?php echo $result->id;?>" id="addurl">+ Add url (optional)</a></div>
                                                   <br />
                                                   <div class="col-md-12 urlopt" id="urlopt-<?php echo $result->id;?>" style="padding:0">
                                                      <div class="col-md-11" style="padding:0">
                                                         <input type="text" class="form-control url" id="url-<?php echo $result->id;  ?>"  name="url" placeholder="URL" value="<?php echo isset($_POST['address'])?$_POST['address']:'';?>" maxlength="50" autocomplete="off" placeholder="Enter a location"> 
                                                      </div>
                                                      <div class="col-md-1">
                                                         <a href="#" id="closeurl" rel="<?php echo $result->id;?>"class="closeurl"><span class="glyphicon glyphicon-remove"></span></a>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12" ="padding:0"><a style="cursor: pointer;" rel="<?php echo $result->id;?>"class="add-more adddescription" id="adddescription">+ Add description (optional)</a>
                                                </div>
                                                <br />
                                                <div class="col-md-12 descopt" id="descopt-<?php echo $result->id;?>" style="padding:0">
                                                   <div class="col-md-11" style="padding:0">
                                                      <textarea  class="form-control event-desc description" id="description-<?php echo $result->id;  ?>" name="description" placeholder="Description"></textarea>
                                                      <input type="hidden" id="is_private" name="is_private" value="0">
                                                   </div>
                                                   <div class="col-md-1">
                                                      <a href="#" id="closedesc" class="closedesc" rel="<?php echo $result->id;?>"><span class="glyphicon glyphicon-remove"></span></a>
                                                   </div>
                                                </div>
                                                <div class="col-md-12" style="padding:0"><a style="cursor: pointer;" rel="<?php echo $result->id;?>"class="add-more addhashtag" id="addhashtag">+ Add hashtag (optional)</a></div>
                                                <br />
                                                <div class="col-md-12 hashtagopt" id="hashtagopt-<?php echo $result->id;?>" style="padding:0">
                                                   <div class="col-md-11" style="padding:0; position:relative">
                                                      <input type="text" class="form-control hastag" autocomplete="off" id="hastag-<?php echo $result->id;  ?>" name="hastag" placeholder="hashtag" >
                                                      <!-- dropdown -->
                                                      <div class="col-md-12 hashtag-dropdown"></div>
                                                      <!--/ end dropdown -->
                                                   </div>
                                                   <div class="col-md-1">
                                                      <a href="#" id="closehashtag" rel="<?php echo $result->id;?>"><span class="glyphicon glyphicon-remove closehashtag" rel="<?php echo $result->id;?> " ></span></a>
                                                   </div>
                                                </div>
                                                <div class="col-md-12 replaygrp" id="replaygrp-<?php echo $result->id;?>" style="padding:0">
                                                   <div class="col-md-11" style="padding:0">
                                                      <div id="grtxt" style="padding:0"><input type="text" class="form-control grouptxt" class="htmlarea textarea grouptxt"id="grouptxt-<?php echo $result->id;  ?>" autocomplete="off"  value="" placeholder="Group" name="street_group" /></div>
                                                      <!-- dropdown -->
                                                      <div class="col-md-12 grptype-dropdown grptype-dropdown" id="grptype-dropdown"></div>
                                                      <!--/ end dropdown -->
                                                   </div>
                                                   <div class="col-md-1">
                                                      <a href="#" id="closegrp" class="closegrp" rel="<?php echo $result->id; ?>" ><span class="glyphicon glyphicon-remove"></span></a>
                                                   </div>
                                                </div>
                                                <div class="col-md-12 replayuser" id="replayuser-<?php echo $result->id;?>" style="padding:0">
                                                   <div class="col-md-11" style="padding:0">
                                                      <div id="urtxt" style="padding:0" class="urtxt"><input type="text" rel="<?php echo $result->id;?>"class="form-control usertxt" class="group" id="usertxt-<?php echo $result->id;  ?>" autocomplete="off"  value="" placeholder="Users" name="street_user"  /></div>
                                                      <!-- dropdown -->
                                                      <div class="col-md-12 usertype-dropdown" id="usertype-dropdown"></div>
                                                      <!--/ end dropdown -->
                                                   </div>
                                                   <div class="col-md-1">
                                                      <a href="#" id="closeuser" class="closeuser" rel="<?php echo $result->id; ?>"><span class="glyphicon glyphicon-remove "></span></a>
                                                   </div>
                                                </div>
                                                <div class="col-md-12" style="padding:0">
                                                   <input type="button" class="btn btn-xs btn-white active grp" rel="<?php echo $result->id; ?>" id="grp" value="+Group" />
                                                   <input type="button" class="btn btn-xs btn-white active user" rel="<?php echo $result->id; ?>"  id="user" value="+Add Users" />
                                                </div>
                                             </div>
                                             <!--/ div 'col-md-12' -->
                                          </div>
                                          <!--/ div 'col-md-8' -->
                                    </div>
                                    <!-- end form -->
                                    </form>
                                    <input type="hidden" id="act" class="act" value="">
                                 </div>
                                 <!--end event creation-->
                              </div>
                              <div class="htmlarea-ac-container1"></div>
							  	<input type="hidden" id="action-<?php echo $result->id;?>" class="action" value='buzz'>
								<input type="hidden" id="ans-<?php echo $result->id;?>" class="action" value='3'>
								<!--/ start : uploading data -->
                  <div class="comment attachments uploads lightbox-enabled" id="0f768e1c400608" style="display:block">
				<div class="images col-xs-12 col-sm-12 col-md-12 col-lg-12" id="imgdis<?php echo $result->id; ?>"></div>
				<div class="links"></div>
				<div class="files col-xs-12 col-sm-12 col-md-12 col-lg-12" id="linksdis<?php echo $result->id; ?>"></div>
			</div>
			<!--/ end : uploading data -->

                                     <div class="replay-editor<?php echo $result->id; ?> "></div>

                              <div class="buttons">
							 <div class="image-upload">
    <label for="sortpicture<?php echo $result->id; ?>">
        <img src="<?php echo $C->SITE_URL ?>/static/images/icons/FILEUPLOAD.png"  class="grayscale" id="profic" />
    </label>

<input id="sortpicture<?php echo $result->id; ?>" onchange="myimageload(<?php echo $result->id; ?>)" type="file" name="userfile" rel="<?php echo $postid;?>" style="display:none;" />
</div>
							  <span><img src="<?php echo $C->SITE_URL; ?>static/images/icons/EVENTS.png" class="grayscale replayevent" title="Events" id="replayevent" rel="<?php echo $result->id;?>">
</span>
<span>
<img src="<?php echo $C->SITE_URL; ?>static/images/icons/POLLS.png" class="grayscale replaypoll" title="Polls" id="careatepoll" rel="<?php echo $result->id;?>">

</span>

<span>
<img src="<?php echo $C->SITE_URL; ?>static/images/icons/intraday.png" class="grayscale replayintraday" title="Intraday" id="replayintraday" rel="<?php echo $result->id;?>">

</span>
<span data-value="999" class="post-cal">999</span> 

<span class="btn-right">
<button disabled="disabled" onclick="commentalllevvels(<?php echo $parentid; ?>,<?php echo $result->id; ?>,<?php echo $firstval; ?>)" type="button" id="<?php echo $postid; ?>" class="comment-post comment-post post-btn btn  blue center buzzpost-<?php echo $result->id;?>">Buzz</button>
 <button  style="display:none;" onclick="commenteventalllevels(<?php echo $parentid; ?>,<?php echo $result->id; ?>,<?php echo $firstval; ?>)" type="button" id="<?php echo $postid; ?>" class="eventpost-<?php echo $result->id;?> comment-post comment-post post-btn btn  blue center">Buzz</button>
 <button  style="display:none;" onclick="commentpollalllevels(<?php echo $parentid; ?>,<?php echo $result->id; ?>,<?php echo $firstval; ?>)" type="button" id="<?php echo $postid; ?>" class="pollpost-<?php echo $result->id;?> comment-post comment-post post-btn btn  blue center">Buzz</button>
  <button  style="display:none;" onclick="commentintradayalllevels(<?php echo $parentid; ?>,<?php echo $result->id; ?>,<?php echo $firstval; ?>)" type="button" id="<?php echo $postid; ?>" class="intrapost-<?php echo $result->id;?> comment-post comment-post post-btn btn  blue center">Buzz</button>
		 </span>  	
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
      </div>
      <?php } ?> 
   </div>
   </div>
</div>
</div>
<?php }
 function assethtml($parentid){
	 $data =array();
	 $obj =$data[0];
	 $buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);
     $assetdata   =$buff->assetdata($parentid);
	 if($assetdata[0]->ticker !=''){
	  $str =  $buff->parsetext($assetdata[0]->message);
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

   $parentmessage = $assetdatahtml;
   return $parentmessage;
			
			
		}else{
			 return '';
			
		}	
}
function eventhtml($parentid){
	$data =array();
	 $obj =$data[0];

	 $buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);
	 	 $user->id =$buff->presentuser();

		           $eventdetails = $buff->geteventdetails($parentid);
				   

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
					

					$parentmessage = $finalcon;
					return $parentmessage;
	
}
function pollhtml($parentid){
	  $data =array();
	  $obj =$data[0];
	  $buff = ( is_object($obj) && get_class($obj) == 'post' )? $obj :  new post('public', FALSE, $obj);
	  	$user->id =$buff->presentuser();

		$poll  = $buff->replay_is_poll($parentid);

		$pollanswer=$buff->is_pollanswer($user->id,$poll[0]->poll_id);

		$message ='';
      $message .='<div class="activity-poll"><div class="attachments lightbox-enabled">
	<div class="images">
		<div class="list-link-container" style="margin-bottom:30px">
			<b><label style="color:#2665ca;"> Poll: '.$poll[0]->poll_question.'</label></b>
			<!-- <a target="_blank" href="" class="lightbox-image image-thumb cboxElement"><img alt="filename" src=""></a> -->
			<!-- //this is placeholder for video player <div class="video-placeholder"></div>  -->
		</div>
	
	</div>
	<div class="links">';
	foreach($poll as $keys=>$vals){
				if($vals->answer!="" && count($pollanswer)<=0){

	
		$message .='<div><input onclick="changeurl(this.value,this.id)" id="'.$vals->poll_id.'" class="option'.$vals->poll_answer_id.' radio'.$vals->poll_id.'" name="option" type="radio" value="'.$vals->poll_answer_id.'"/>'.$vals->answer.'</div><br>';
	}else if($vals->answer!="")
	{
	$countpollanswer=$buff->is_countpollanswer($vals->poll_id,$vals->poll_answer_id);
	$message .='<div ><table><tr><td style="margin: 0px;width:200px">'.$vals->answer.'</td><td style="margin: -15px 0px 0px 60px;">'.count($countpollanswer).'</td>
<td style="width: 500px;padding-top: 18px;"><div style="background-color: green!important;width:'.count($countpollanswer).'%;height: 15px;margin: -15px 10px 25px 77px;"></div></td></tr></table></div>';

	}
	}
	$message .='</div>';
	if(count($pollanswer)==0){

	$message .='<div class="activity-poll-option"><span id="optionerror'.$poll[0]->poll_id.'"></span><br><span><a onclick="checkoption('.$poll[0]->poll_id.')" id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=answer&poll_id='.$poll[0]->poll_id.'&from='.$user->id.'"><button style="color:white;background-color:orange; padding:6px 8px; border:1px solid orange;">Submit</button></a>&nbsp;&nbsp;&nbsp;';
	
	}if($user->id==$vals->user_id)
    {
	$message .='<a id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'"><button  class="btn-download-results">Download Results</button></a>';
	}
	$message .='</span></div>
</div></div>';
$parentmessage = $message;
return $parentmessage;

	
}


?>





<style>
.image-upload {
  display: inline;
}
.btn-right {
  float: right;
  margin-top: -16px;
}
.activity-footer a {
  color: #fff;
}
.icon-ftr a {
     color: #00BFFF;
}
.buttons {
  text-align: left;
}
@media screen and (max-width: 600px) {
.btn-right {
  display: block!important;;
  width: 100%!important;
  margin-top: 0px;
}
.modal-body {
  padding-bottom: 115px;
}
}
</style>





<script type="text/javascript">
newToken = STX.generateToken();
$(".replayeditor").attr('data-token', newToken);

function myimageload(fileid){


var token =($(".replayeditor").attr("data-token"))+fileid;
$(".replay-editor"+fileid).attr('data-token', token);


var urlphp =  siteurl + 'services/commentattachments/setfile' + '/token:' + token +'/container:status';

    var file_data = $('#sortpicture'+fileid).prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('userfile', file_data);
	 $.ajax({
                url: urlphp, // point to server-side PHP script 
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
				async:false, 
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(php_script_response){
			$(".comment.attachments.uploads").css("display","block");
             var obj = jQuery.parseJSON(php_script_response);
			if(obj.data.att_type =="image"){
			newt = Math.floor(Math.random() * 6) + 1 ;

			    $("#imgdis"+fileid).append('<span class="image-thumb container close-'+newt+'"><img width="100" src="'+obj.data.url+'" alt="'+obj.data.file_name+'" title="'+obj.data.file_name+'"><a class="delete" onclick="deleteatta('+newt+')" ></a></span>');
               check(obj.data.att_type);

			}
			if(obj.data.att_type =="file"){
						newt = Math.floor(Math.random() * 6) + 1 ;

				$("#linksdis"+fileid).append('<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 close-'+newt+' " style="position: relative;"><a href="'+obj.data.url+'" title="query.php" class="icon file file" target="_blank">'+obj.data.file_name+'</a><a class="delete" onclick="deleteatta('+newt+')"></a></div>');

			}
			
		



                

				   // display response from the PHP script, if any
                }
     });
	 
			
}
function check(dfgh){
	        var end = setInterval(function () {
				if (current >= limit || running == false) {
                running = false;
                clearInterval(end);
            }
			var maintotal =$("#allmain<?php echo $childid;?>").height();
			var childtotal =$(".allchild<?php echo $childid;?>").height();
			var final = (maintotal-childtotal)+'px';
			$(".janeeshallchild<?php echo $childid;?>").css("height",final);
			}, 1000);

	/*var maintotal =$("#allmain<?php echo $childid;?>").height();
			var childtotal =$(".allchild<?php echo $childid;?>").height();
			var final = (maintotal-childtotal)+'px';
			$(".janeeshallchild<?php echo $childid;?>").css("height",final);*/
	
}
 running = true;
        var end = setInterval(function () {
            if (current >= limit || running == false) {
                running = false;
                clearInterval(end);
            }
			var maintotal =$("#allmain<?php echo $childid;?>").height();
			var childtotal =$(".allchild<?php echo $childid;?>").height();
			var final = (maintotal-childtotal)+'px';
			$(".janeeshallchild<?php echo $childid;?>").css("height",final);

			 


            current++;
        }, 1000);

   function showtextarea(postid){
	    running = true;
        var end = setInterval(function () {
            if (current >= limit || running == false) {
                running = false;
                clearInterval(end);
            }
			var maintotal =$("#allmain<?php echo $childid;?>").height();
			var childtotal =$(".allchild<?php echo $childid;?>").height();
			var final = (maintotal-childtotal)+'px';
			$(".janeeshallchild<?php echo $childid;?>").css("height",final);


            current++;
        }, 50);
	   
	   var idss =[];
	   $('.cons').each(function(){
		   var rel = $(this).attr('rel');
		   idss.push(rel);
		   
		   
	   });
	   
	   for(var i=0;i<idss.length;i++){
		   if(idss[i] == postid){
			     $("#con-"+postid).css("display","block");

			   
		   }else{
			    $("#con-"+idss[i]).css("display","none");
			   
		   }
		   
	   }
   	
   }
   function replaycontentalllevels(postid,childid,main){
   	var c ="<?php echo $childid;?>";
   		$(".graph-"+c).hide();
   
   		var view_type="new_view";
   
   	$.ajax({
   				async: true, 
   	           cache: false,
   				dataType : "html",
   				type:"POST",
   				data:{childid:childid,postid:postid,view_type:view_type},
   				url:"<?php  echo $C->SITE_URL;?>allreplies",
   
   				success:function(msg){
   					 $('#replaypopup-'+main).html('');
   					 $('#replaypopup-'+main).html(msg);
   					 $('#replaypopup-'+main).modal('show'); 
   					
   					
   				}
   			});
   
   	
   }
   
</script>
<script type="text/javascript">
var postid ="<?php echo $postid;?>";
$(".ans1").keyup(function(){
	var rel = $(this).attr('rel');
	 $("#answer0-"+rel).css('border-color','green');
	
	
});
$(".ans2").keyup(function(){
		var rel = $(this).attr('rel');

	 $("#answer1-"+rel).css('border-color','green');
	
	
});
$(".title").keyup(function(){
	  $(".title").css('border-color','green');
	
	
});
$(".city").keyup(function(){
	  $(".city").css('border-color','green');
	
	
});
 $(".start_date,start_time,end_date,end_time").click(function(){
	 $(".start_date").css('border-color','green');
		   $(".start_time").css('border-color','green');
		    $(".end_date").css('border-color','green');
			 $(".end_time").css('border-color','green');
	 
 });
 $(".poll-grouptxt").keyup(function(){
	 var group = $(this).val();
	$.ajax({
			type: "POST",
			url:"<?php  echo $C->SITE_URL;?>autocomplete",
			data:{poll_group:group},
			
			success: function(data){
				$(".grptype-dropdown").show();
				$(".grptype-dropdown").html(data);
			}
			});
	 
 });

$(".grouptxt").keyup(function(){
	var group = $(this).val();
	$.ajax({
			type: "POST",
			url:"<?php  echo $C->SITE_URL;?>autocomplete",
			data:{group:group},
			
			success: function(data){
				$(".grptype-dropdown").show();
				$(".grptype-dropdown").html(data);
			}
			});
	
});
$(".replayevent").click(function(){
	var rel             =$(this).attr('rel');

	$('.userreplaybuzz-'+rel).css("display","none");
	$(".create_poll-"+rel).css("display","none");
	$(".eventdata-"+rel).css("display","block");
	$("#action-"+rel).val("event");

	$(".buzzpost-"+rel).hide();
   $(".pollpost-"+rel).hide();
   $(".intrapost-"+rel).hide();

	$(".eventpost-"+rel).show();
	
});
$(".replayintraday").click(function(){
		var rel             =$(this).attr('rel');

	$(".create_poll-"+rel).css("display","none");
	$(".eventdata-"+rel).css("display","none");
	$("#action-"+rel).val("buzz");

	$(".buzzpost-"+rel).hide();
   $(".pollpost-"+rel).hide();
   $(".intrapost-"+rel).show();

	$(".eventpost-"+rel).hide();
	
});
$(".replaypoll").click(function(){
	var rel =$(this).attr('rel');
		$('.userreplaybuzz-'+rel).css("display","none");
		$(".eventdata-"+rel).css("display","none");
		$("#action-"+rel).val("poll");
		$(".buzzpost-"+rel).hide();
	   $(".eventpost-"+rel).hide();
	    $(".pollpost-"+rel).show();
		  $(".intrapost-"+rel).hide();


		

		$(".create_poll-"+rel).css("display","block");

	
});

$(".rmbt").click(function(){
	var rel = $(this).attr('rel');
	$('.userreplaybuzz-'+rel).css("display","block");
	$(".create_poll-"+rel).css("display","none");
	$(".eventdata-"+rel).css("display","none");
	$(".action").val("buzz");

	$(".buzzpost-"+rel).show();
	   $(".pollpost-"+rel).hide();

	$(".eventpost-"+rel).hide();

	
});
$(".removepoll").click(function(){
	var rel =$(this).attr('rel');
	$('.userreplaybuzz-'+rel).css("display","block");
	$(".create_poll-"+rel).css("display","none");
	$(".eventdata-"+rel).css("display","none");
	$(".action").val("buzz");

	$(".buzzpost-"+rel).show();
	$(".eventpost-"+rel).hide();
	 $(".pollpost-"+rel).hide();


	
});

$(".grp").click(function(){
	$(".act").val("group");
	
	
});
$(".user").click(function(){
	$(".act").val("user");
	
	
});
function selectgrp(val) {
$(".grouptxt").val(val);
$(".grptype-dropdown").hide();
}
function selectpollgrp(val) {
$(".poll-grouptxt").val(val);
$(".grptype-dropdown").hide();
}

//character count
$(".editpostreplay").keyup(function(e){
	var rel =$(this).attr('rel');
	$(".buzzpost-"+rel).prop("disabled",false);
	  var datavalue  =$(".post-cal").attr('data-value');
	  var preveious ="<?php echo $result->username;?>";
	  var pvlen     =parseInt(preveious.length)+2;
	  var editpost  =($(".editpostreplay").val().length)-pvlen;
	  var left     = parseInt(datavalue)-parseInt(editpost);
	  $(".post-cal").html(left);
	  if(left < 0){
		  $(".buzzpost-"+rel).prop("disabled",true);
		  $(".buzzpost-"+rel).css("opacity","0.5");
		 $(".post-cal").css("color","red");
		  
	  }
	
});
//chaaracter count end
// Sharetronix grouptxt namespace
var replayuser = function () {
    var autocompleteActive = false;
    var searchString = '';
    var startPos = 0;
    var endPos = 0;
    var currentPos = 0;
    var aliases = new Array();

    var tagsToReplace = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;'
        };

    function replaceTag(tag) {
        return tagsToReplace[tag] || tag;
    }
    
    function acMoveSelection(key) {
        acList = $('.usertype-dropdown');
        if (key == 38) { // up
            if ($('li.selection', acList).length > 0) {
                prev = $('li.selection', acList).prev();
                $('.selection').removeClass('selection');
                $(prev).addClass('selection');
            } else {
                $('li:last', acList).addClass('selection');
            }
        } else if (key == 40) { // down
            if ($('li.selection', acList).length > 0) {
                next = $('li.selection', acList).next();
                $('.selection').removeClass('selection');
                $(next).addClass('selection');
            } else {
                $('li:first', acList).addClass('selection');
            }
        }
    }

    function generateACList(result) { // build autocomplete list and attach events - click/hover
	//alert(result);
        if (result.users.length == 0) {
            //var users = $('<div />').addClass('grouptxt-ac-title').html('There are no users matching your query!');
            //var users = $('<span />');
        } else {
			var  userpostid = result.users[0].post_id;

            var users = $('<ul />');
            for (var i = 0; i < result.users.length; i++) {
                var userItem = $('<li />').data('alias', result.users[i].username);
                var userImage = $('<img width="30" style="margin-right:7px;" />').attr('src', result.users[i].avatar_url);

                searchStr = $('.ac-placeholder').text().replace(/@/gi, '');
                tmpName = result.users[i].fullname.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + searchStr.replace(/([\^\$\(\)\[\]\{\}\*\.\+\?\|\\])/gi, "\\$1") + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>");
				
				tmpName2 = ' (@' + result.users[i].username + ')';
				tmpName2 = tmpName2.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + searchStr.replace(/([\^\$\(\)\[\]\{\}\*\.\+\?\|\\])/gi, "\\$1") + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>");
                var userName = $('<span />').html(tmpName+tmpName2);

                var userClear = $('<div />').addClass('clear');
                $(userItem).append(userImage);
                $(userItem).append(userName);
                $(userItem).append(userClear);

                $(users).append($(userItem));
            }

            $('li:first', users).addClass('selection');

            $('li', users).click(function () {
                insertUserLink($(this),userpostid);
                stopAC();
            }).hover(
                function () { $(this).addClass('hover'); },
                function () { $(this).removeClass('hover'); }
            );
        }
        return $(users);
    }

    function grouptxtACSuccess(result, userContext) { $(userContext).html(generateACList(result)); } // append autocomplete list

    function grouptxtACFail() { }

    function startAC(grouptxtEl,rel) {
        autocompleteActive = true;
		//var rel = $(this).attr('rel');
		var act = $("#action-"+rel).val();
        if(act =="event" || act =="poll" ){
			accontainer = $('.usertype-dropdown');

		}else{
        accontainer = $('.htmlarea-ac-container-replay');
		}
		
        searchString = '';
        //var char = '';
        $(accontainer).html('<div class="htmlarea-ac-title">Please start typing user name ...</div>').show();
        startPos = currentPos;

        $(grouptxtEl).bind('keydown.ac', function (e) {
            if (e.which != 37 && e.which != 38 && e.which != 39 && e.which != 40) { // do not make ajax calls on arrow key press
                setTimeout(function () {
                    var text = $(grouptxtEl).val();
                    endPos = getCaretPosition($(grouptxtEl)[0]);
                    cnt = getSearchString($(grouptxtEl)[0], startPos, endPos);
                    //Users.autocomplete(cnt, 10, grouptxtACSuccess, grouptxtACFail, accontainer);
					var usertype="user";

                    
                    var args = {
        					//type: 'post',
        					module: 'users',
        					action: 'autocomplete',
        					data: { users_name: cnt,usertype:usertype,postid:rel }
        				}
        			Services.invoke(args, grouptxtACSuccess, grouptxtACFail, accontainer);
                    
                    
                }, 10);
            }
        });
    }

    function stopAC() {
        autocompleteActive = false;
        $('.usertxt').unbind('keydown.ac');
        $('.usertype-dropdown').hide();
    }

    function getLinks(text) {
        var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
        //var exp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/gi;
        return text.match(exp);
    }

    /*--------------------*/

    function getCaretPosition(el) {
        var caretPos = 0;
        // IE Support
        if (document.selection) {

            //el.focus();

            var r = document.selection.createRange();
            var range = el.createTextRange();
            var rc = range.duplicate();
            range.moveToBookmark(r.getBookmark());
            rc.setEndPoint('EndToStart', range);
            //return rc.text.length;
            caretPos = rc.text.length;

            /*
            el.focus();
            var range = el.createTextRange();
            var startCharMove = offsetToRangeCharacterMove(el, 0);
            range.moveStart("character", startCharMove);
            caretPos = range.text.length;
            */
        }
        // Firefox support
        else if (el.selectionStart || el.selectionStart == '0')
            caretPos = el.selectionStart;
        return (caretPos);
    }

    function setCaretPosition(el, pos) {
        if (el.setSelectionRange) {
            el.focus();
            el.setSelectionRange(pos, pos);
        } else if (el.createTextRange) {
            var range = el.createTextRange();
            range.collapse(true);
            range.moveEnd('character', pos);
            range.moveStart('character', pos);
            range.select();
        }
    }

    function getSearchString(el, startOffset, endOffset) {
        if (document.selection) { //ie
            var range = el.createTextRange();
            var startCharMove = offsetToRangeCharacterMove(el, startOffset);
            range.collapse(true);
            if (startOffset == endOffset) {
                range.move("character", startCharMove);
            } else {
                range.moveEnd("character", offsetToRangeCharacterMove(el, endOffset));
                range.moveStart("character", startCharMove);
            }
            var cnt = range.text;
        } else if (el.selectionStart || el.selectionStart == '0') { //ff
            var text = $(el).val();
            cnt = text.substr(startOffset, endOffset - startOffset);
        }
        searchStr = cnt.replace(/@/gi, '');
        return searchStr;
    }

    function insertUserLink(el,rel) {
        alias = $(el).data('alias');
		var act = $("#action-"+rel).val();
        if(act =="event" ){
			editor = $("#usertxt-"+rel);


		}else if(act =="poll" ){
			editor = $("#pollusertxt-"+rel);
		}else{
        editor = $('#message-'+rel);
		}
		

        if (aliases == null || aliases[alias] == null) {
            aliases[alias] = '@' + alias;
        }

        if (document.selection) {
            var range = $(editor)[0].createTextRange();
            var startCharMove = offsetToRangeCharacterMove($(editor)[0], startPos);
            range.collapse(true);
            if (startPos == endPos) {
                range.move("character", startCharMove);
            } else {
                range.moveEnd("character", offsetToRangeCharacterMove($(editor)[0], endPos));
                range.moveStart("character", startCharMove);
            }
            range.text = '@' + alias;
        } else {
            val = $(editor).val();
            alias = '@' + alias;
            replaced = val.substr(0, startPos) + alias + val.substr(endPos, val.length);
            $(editor).val(replaced);
            setTimeout(function () {
                setCaretPosition($(editor)[0], startPos + alias.length + 1)
            }, 10);

            //console.log(startPos);
            //console.log(endPos);
            //console.log(alias);
        }
        currentPos = getCaretPosition($(editor)[0]);
		$(".htmlarea-ac-container-replay").fadeOut();
        //highlighter($(editor));
        //alert('asd');

    }

    function offsetToRangeCharacterMove(el, offset) {
        return offset - (el.value.slice(0, offset).split("\r\n").length - 1);
    }

    function highlighter(el) {

        var cnt = $(el).val();
        cnt = cnt.replace(/[&<>]/g, replaceTag);
        cnt = cnt.replace(/\n/gi, '<br/>').replace(/\s/gi, '&nbsp;');
        cnt = cnt + '&nbsp;';


        if (aliases != null) {
            for (var a in aliases) {
                cnt = cnt.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + aliases[a].replace(/([\^\$\(\)\[\]\{\}\*\.\+\?\|\\])/gi, "\\$1") + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<span>$1</span>");
            };
        }

        if ($(el).parents('.editpostreplay').length > 0) {
            $(el).parents('.editpostreplay').find('.textarea-highlighter').html(cnt);
        } else {
            $(el).parents('.req.editor').find('.textarea-highlighter').html(cnt);
        }

    }





    function insertAtCursor(editor, str) {
        startPos = currentPos;

        if (document.selection) {
            var range = $(editor)[0].createTextRange();
            var startCharMove = offsetToRangeCharacterMove($(editor)[0], startPos);
            range.collapse(true);
            if (startPos == endPos) {
                range.move("character", startCharMove);
            } else {
                range.moveEnd("character", offsetToRangeCharacterMove($(editor)[0], endPos));
                range.moveStart("character", startCharMove);
            }
            range.text = str;
            //currentPos = getCaretPosition($(editor)[0]);
        } else {
            val = $(editor).val();
            replaced = val.substr(0, startPos) + str + val.substr(startPos + str.length, val.length);
            //alert(replaced);
            $(editor).val(replaced);
            setTimeout(function () {
                setCaretPosition($(editor)[0], startPos + str.length + 1);
                //currentPos = getCaretPosition($(editor)[0]);
            }, 10);
        }
    }


    function countCharacters(editor) {
    	counter = $(editor).parents('.status-editor-container').find('.characters-counter');
    	if (counter.length > 0) {
    		
    		counterValue = counter.data('value');
    		charactersCount = editor.val().length;
    		charactersLeft = counterValue - charactersCount;
    		//console.log(charactersCount);
    		counter.text(charactersLeft);
    		if (charactersCount > counterValue) {
    			//console.log('limit');
    			//return false;
    			editorString = editor.val();
    			editorString = editorString.substring(0,counterValue);
			
				 
				 
				
				$(".comment-post").prop("disabled",true);
				$(".comment-post").css("opacity","0.5");
				$(".characters-counter").css("color","red");
				       
		
				
    			
    			
    			
    		}else{
				if (charactersCount <= counterValue) {
					$(".comment-post").css("opacity","");
					$(".comment-post").prop("disabled",false);
					$(".characters-counter").css("color","");
			
				
    			
				}
				
			}
    		
    	}
    }

    // --- declare public methods --- //
    return {
        init: function (el) {
			var act = $("#act").val();
            grouptxtEl = ($(el).length > 0) ? $(el) : $('.usertxt');
            

            $(grouptxtEl).focus(function () {
            	//countCharacters($(this));
                if ($(this).val().trim() == $(this).data('placeholder')) { $(this).val(''); }
                $(this).parents('.usertxt').addClass('focus');
                currentPos = getCaretPosition($(this)[0]);
            }).blur(function () {
            	//countCharacters($(this));
                if ($(this).val().trim() == '') $(this).val($(this).data('placeholder'));
                $(this).parents('.usertxt').removeClass('focus');
            }).keypress(function (e) {
                //$(this).parents('.grouptxt').find('.textarea-highlighter span').text($(this).val());
                // start autocomplete on "@" press
                highlighter($(this));
                countCharacters($(this));
                if (e.which == 64/* && !autocompleteActive*/) {
var rel= $(this).attr('rel');
                    //e.preventDefault();
                    currentPos = getCaretPosition($(this)[0]);
                    stopAC();
                    startAC($(this),rel);
                }
            }).keyup(function () {
                //currentPos = getCaretPosition($(this)[0]);
               highlighter($(this));
                countCharacters($(this));
                //var content = $(this).val();
                //content = content.replace(/\n/gi, '<br />');
                //$(this).parents('.grouptxt').find('.textarea-highlighter span').html(content);
            }).keydown(function (e) {
                //$(this).parents('.grouptxt').find('.textarea-highlighter span').text($(this).val());
                /*
                // 8  - backspace
                // 13 - enter
                // 27 - escape
                // 32 - space
                // 37 - arrow left
                // 38 - arrow up
                // 39 - arrow right
                // 40 - arrow down
                // 46 - delete
                // 64 - @
                */

                //currentPos = getCaretPosition($(this)[0]);
                highlighter($(this));
                countCharacters($(this));
                txteditor = $(this);

                if (e.which == 13) { // stop autocomplete 
                    if (e.which == 13 && autocompleteActive) {
                        e.preventDefault();
                        endPos = getCaretPosition($(txteditor)[0]);
                        selected = $('li.selection', '.usertype-dropdown');
                        insertUserLink($(selected), txteditor,rel);
                    }
                    stopAC();
                }

                if ((e.which == 38 || e.which == 40) && autocompleteActive) { // up/down arrow keys
                    e.preventDefault();
                    acMoveSelection(e.which);

                }

                if (e.which == 27) { // esc key
                    e.preventDefault();
                    stopAC();
                }

                if (e.which == 32) {
                    el = $(this);
                    setTimeout(function () {
                        urls = getLinks($(el).val());
                        if (urls != null) {
                            for (var i = 0; i < urls.length; i++) {
                                Attachments.attachLink($(el), urls[i]);
                            }
                        }
                    }, 200);
                }



            }).bind('paste', function () { // on paste clean html
                el = $(this);
                setTimeout(function () {
                    urls = getLinks($(el).val());
                    if (urls != null) {
                        for (var i = 0; i < urls.length; i++) {
                            Attachments.attachLink($(el), urls[i]);
                        }
                    }
                }, 200);
            });

            $('body').click(function (event) {
                caller = event.target;
                if ($(caller).parents('.htmlarea-ac').length == 0 && !$(caller).hasClass('htmlarea-ac')) {
                    stopAC();
                }
            });











            //comment editor when user is not logged
            commentAreaEl = $('.req textarea');
            $(commentAreaEl).focus(function () {
                if ($(this).val().trim() == $(this).data('placeholder')) { $(this).val(''); }
                $(this).parents('.editor').addClass('focus');
                currentPos = getCaretPosition($(this)[0]);
            }).blur(function () {
                if ($(this).val().trim() == '') $(this).val($(this).data('placeholder'));
                $(this).parents('.editor').removeClass('focus');
            }).keypress(function (e) {
                highlighter($(this));
            }).keyup(function () {
                highlighter($(this));
            }).keydown(function (e) {
                highlighter($(this));
            });













            $('.ac-btn').live('click', function (e) {
                e.preventDefault();
                targetEditor = $(this).parents('.data-content-placeholder').find('textarea');
                $(targetEditor).focus();
                setCaretPosition($(targetEditor)[0], currentPos);
                //insertAtCursor($(targetEditor), '@');
                setTimeout(function () {
                    insertAtCursor($(targetEditor), '@');
                    startAC($(targetEditor));
                }, 20);

            });



        },

        reset: function (el, type) {
            editorContainer = $(el).parents('.data-content-placeholder');
            $(el).parents('.htmlarea').find('.textarea-highlighter').html('');
            $(el).val($(el).data('placeholder'));
            $(editorContainer).find('.attachments .images').html('');
            $(editorContainer).find('.attachments .links').html('');
            $(editorContainer).find('.attachments .files').html('');
            $(editorContainer).find('.uploads').hide();
            Attachments.reset(type);
        },

        highlightAlias: function (alias, el) {
            $(el).text('@' + alias);
            $(el).focus();

            aliases[alias] = '@' + alias;
            highlighter(el);
        }

    }
} ();

$(document).ready(function () {
    //HtmlAutocompleteService = new STXServices.BTSearchService();
    //grouptxt.init();
	
	 
    replayuser.init($('.editpostreplay,.usertxt,.poll-usertxt'));
    //grouptxt.init($('#comments-textarea'));


    
});

</script>
 <script type="text/javascript">
        window.onbeforeunload = function () {
            $("input[type=button], input[type=submit]").attr("disabled", "disabled");
        };
    </script>
	
<style>
htmlarea-ac-container-replay
/* Hashtag */
.hashtag-dropdown { margin-top: -10px; width:100%; z-index:50; display:none; background:#fff;}
.hashtag-dropdown {font-weight:bold; font-style:italic; color:#6E6E6E; font-size:10px; border:1px solid #C2C2C2; border-top:none;}
.hashtag-dropdown ul {list-style:none; margin:0px; border:0px solid #C2C2C2; border-top:none;}
.hashtag-dropdown ul li {border-bottom:1px solid #F5F5F5; cursor:pointer; display:block; width:100%; margin-left: -30px; padding:3px;}
.hashtag-dropdown ul li.hover {background:#0076a3; color: #fff;}
.hashtag-dropdown ul li.selection {color: #6E6E6E;}
/* Usertype */
.usertype-dropdown { margin-top: 0px; width:100%; z-index:50; display:none; background:#fff;}
.usertype-dropdown {font-weight:bold; font-style:italic; color:#6E6E6E; font-size:10px; border:1px solid #C2C2C2; border-top:none;}
.usertype-dropdown ul {list-style:none; margin:0px; border:0px solid #C2C2C2; border-top:none;}
.usertype-dropdown ul li {border-bottom:1px solid #F5F5F5; cursor:pointer; display:block; width:100%; margin-left: -54px; padding:1px;}
.usertype-dropdown ul li.hover {background:#0076a3; color: #fff;}
.usertype-dropdown ul li.selection {color: #6E6E6E;}
.usertype-dropdown ul li.selection:hover {color: #fff;}
/* Usertype */
.grptype-dropdown { margin-top: -10px; width:100%; z-index:50; display:none; background:#fff;}
.grptype-dropdown {font-weight:bold; font-style:italic; color:#6E6E6E; font-size:10px; border:1px solid #C2C2C2; border-top:none;}
.grptype-dropdown ul {list-style:none; margin:0px; border:0px solid #C2C2C2; border-top:none;}
.grptype-dropdown ul li {border-bottom:1px solid #F5F5F5; cursor:pointer; display:block; width:100%; margin-left: 0px; padding:1px;}
.grptype-dropdown ul li.hover {background:#0076a3; color: #fff;}
.grptype-dropdown ul li.selection {color: #6E6E6E;}
.grptype-dropdown ul li.selection:hover {color: #fff;}


.pac-container {
    /* put Google geocomplete list on top of Bootstrap modal */
    z-index: 9999;
}
/* Hashtag */
.htmlarea-ac-container-replay
 { margin-top: 0px; width:100%; z-index:50; display:none; background:#fff;}
.htmlarea-ac-container-replay
 {font-weight:bold; font-style:italic; color:#6E6E6E; font-size:10px; border:1px solid #C2C2C2; border-top:none;}
.htmlarea-ac-container-replay
 ul {list-style:none; margin:0px; border:0px solid #C2C2C2; border-top:none;}
.htmlarea-ac-container-replay
 ul li {border-bottom:1px solid #F5F5F5; cursor:pointer; display:block; width:100%; margin-left: -30px; padding:3px;}
.htmlarea-ac-container-replay
 ul li.hover {background:#0076a3; color: #fff;}
.htmlarea-ac-container-replay
 ul li.selection {color: #6E6E6E;}
</style>
<script type="text/javascript">
$(document).ready(function(){
		


	$("#grtxt").hide();
	$("#urtxt").hide();
	 $("#rmbt").click(function(){
		 $("#post").show();
		  $("#poll").hide();
		  $(".status-btn").show();
  $(".characters-counter").show();
		 
	 });
	 $("#grp").click(function(){
		 $("#grtxt").show();
		  $("#share").val("group");
		 //$("#urtxt").hide();
	 });
	 $("#user").click(function(){
		 //$("#grtxt").hide();
		  $("#share").val("user");
		 $("#urtxt").show();
	 });
	 $("#start_date").click(function(){
		  $("#ui-datepicker-div").fadeIn();
		   
		  					
	 });
	 $( "#end_date, #end_time" ).click(function(){
		  $("#ui-datepicker-div").fadeIn();
		   
		  					
	 });	 
	 
});

</script>


<!-- Add more script starts -->
<script>
$(document).ready(function() {
$(".urlopt").hide();
$(".descopt").hide();
$(".hashtagopt").hide();
$(".grpuser").hide();
$(".replaygrp").hide();
$(".poll-replaygrp").hide();
$(".poll-replayuser").hide();

$(".replayuser").hide();


$(".addurl").click(function() {
	var rel = $(this).attr('rel');
$("#urlopt-"+rel).show(500);
});

$(".closeurl").click(function() {
		var rel = $(this).attr('rel');

	$(".url").val('');

$("#urlopt-"+rel).hide(500);
});

$(".adddescription").click(function() {
	var rel = $(this).attr('rel');

$("#descopt-"+rel).show(500);
});

$(".closedesc").click(function() {
var rel = $(this).attr('rel');

$(".description").val('');

$("#descopt-"+rel).hide(500);

});

$(".addhashtag").click(function() {
	var rel = $(this).attr('rel');

$("#hashtagopt-"+rel).show(500);
});

$(".closehashtag").click(function() {
var rel = $(this).attr('rel');

	$(".hastag").val('');

$("#hashtagopt-"+rel).hide(500);


});

$(".grp").click(function() {
		var rel = $(this).attr('rel');

$("#replaygrp-"+rel).show(500);
});
$(".poll-grp").click(function() {
$(".poll-replaygrp").show(500);
});
$(".poll-user").click(function() {
$(".poll-replayuser").show(500);
});

$(".user").click(function() {

var rel = $(this).attr('rel');

$("#replayuser-"+rel).show(500);
});

$(".closegrp").click(function() {
			var rel = $(this).attr('rel');

	$(".grouptxt").val('');

$("#replaygrp-"+rel).hide(500);

$(".grp").css("background-color" , "rgba(255,255,255,0.75)");
$(".grp").css("color" , "#1b95e0");
});
$(".closeuser").click(function() {
				var rel = $(this).attr('rel');

	$(".usertxt").val('');

$("#replayuser-"+rel).hide(500);

$(".user").css("background-color" , "rgba(255,255,255,0.75)");
$(".user").css("color" , "#1b95e0");
});
$(".poll-closeuser").click(function() {
$(".poll-usertxt").val('');
$(".poll-replayuser").hide(500);

});
$(".poll-closegrp").click(function() {
	$(".poll-grouptxt").val('');

$(".poll-replaygrp").hide(500);

});




});
</script>
<!-- Add more script ends -->


<!-- statrt Group/Add Users 'active' color -->
<script>
$(document).ready(function() {
	$(".close").click(function(){
		//var postid =$(this).attr('rel');
		//$(".replayticker1").val('');
  // $('#replaypopup-'+postid).modal('toggle');
		
	});

$(".grp").click(function() {
$(".grp").css("background-color" , "#1b95e0");
$(".grp").css("color" , "#ffffff");
$(".user").css("background-color" , "rgba(255,255,255,0.75)");
$(".user").css("color" , "#1b95e0");
});

$(".user").click(function() {
$(".user").css("background-color" , "#1b95e0");
$(".user").css("color" , "#ffffff");
$(".grp").css("background-color" , "rgba(255,255,255,0.75)");
$("grp").css("color" , "#1b95e0");
});

});
</script>
<!-- end Group/Add Users 'active' color -->

<style>
.form-date {
	width: 85px!important;
	margin-bottom: -55px!important;
}
.form-time {
    width: 65px!important;
    margin-bottom: -55px!important;
}
.event-desc {
	min-height: 78px;
	margin-bottom: 10px;
}
.btn-white {
	border-color: #0084B4;
	border-color: rgba(0,132,180,.5);
    color: #0084B4;
    background: rgba(255,255,255,0.75);
    border-style: solid;
    border-width: 1px;
    box-shadow: none;
    opacity: .8;
    -ms-filter: "alpha(opacity=80)";
}
.btn-white:hover {
	background-color: #1b95e0;
	color: #fff;
}
.blue {
	margin-top: 15px;
}
@media screen and (max-width: 480px) {
.form-date {
	width: 100%!important;
	margin-bottom: -55px!important;
}
}
</style>
<script>
function removeew(x,rel){
	//e.preventDefault();
		$('#descopt-'+rel+'-'+x).remove(); 
		var rval= 	$("#ans-"+rel).val();
		var x        =parseInt(rval)-1;
		$("#ans-"+rel).val(x);
		//Remove field html
		//x--; //Decrement field counter
	}
var postid="<?php echo $postid;  ?>";
var maxField = 6; //Input fields increment limitation
	var addButton = $('.add-more'); //Add button selector
	var wrapper = $('.form-group1'); //Input field wrapper
	var x = 3; //Initial field counter is 1
	$(addButton).click(function(){
		var rel =$(this).attr('rel');
		var x = $("#ans-"+rel).val();
		
		//Once add button is clicked
		var fieldHTML = '<div class="col-md-12" id="descopt-'+rel+'-'+x+'" style="padding:0;"><div class="col-md-11" style="padding:0"><span class="add-more">Answer'+x+':</span><br /><input type="text" class="form-control replayas-'+rel+' answerspoll-'+rel+'" name="answer['+(x-1)+']" id="answer'+x+'" value=""/></div><div class="col-md-1" style="padding-top:25px;"><a onclick="removeew('+x+','+rel+');" class="remove_button" rel="'+x+'" title="Remove field"><span class="glyphicon glyphicon-remove"></span></a></div></div>'; //New input field html 
		if(x < maxField){ //Check maximum number of input fields
			x++; //Increment field counter
			$("#ans-"+rel).val(x);
			
			
			$('.form-group1-'+rel).append(fieldHTML); // Add field html
		}
	});
	
	
	/*$(wrapper).on('click', '.remove_button', function(e){ //Once remove button is clicked

		var id = $(this).attr('rel');
		e.preventDefault();
		$('#descopt'+id).remove(); //Remove field html
		x--; //Decrement field counter
	});*/
	$(".city").geocomplete();
	var pid ="<?php echo  $postid;  ?>";

/*
 * jQuery timepicker addon
 * By: Trent Richardson [http://trentrichardson.com]
 * Version 1.1.0
 * Last Modified: 11/03/2012
 *
 * Copyright 2012 Trent Richardson
 * You may use this project under MIT or GPL licenses.
 * http://trentrichardson.com/Impromptu/GPL-LICENSE.txt
 * http://trentrichardson.com/Impromptu/MIT-LICENSE.txt
 */

/*jslint evil: true, white: false, undef: false, nomen: false */

(function($) {

	/*
	* Lets not redefine timepicker, Prevent "Uncaught RangeError: Maximum call stack size exceeded"
	*/
	$.ui.timepicker = $.ui.timepicker || {};
	if ($.ui.timepicker.version) {
		return;
	}

	/*
	* Extend jQueryUI, get it started with our version number
	*/
	$.extend($.ui, {
		timepicker: {
			version: "1.1.0"
		}
	});

	/* 
	* Timepicker manager.
	* Use the singleton instance of this class, $.timepicker, to interact with the time picker.
	* Settings for (groups of) time pickers are maintained in an instance object,
	* allowing multiple different settings on the same page.
	*/
	function Timepicker() {
		this.regional = []; // Available regional settings, indexed by language code
		this.regional[''] = { // Default regional settings
			currentText: 'Now',
			closeText: 'Done',
			amNames: ['AM', 'A'],
			pmNames: ['PM', 'P'],
			timeFormat: 'HH:mm',
			timeSuffix: '',
			timeOnlyTitle: 'Choose Time',
			timeText: 'Time',
			hourText: 'Hour',
			minuteText: 'Minute',
			secondText: 'Second',
			millisecText: 'Millisecond',
			timezoneText: 'Time Zone',
			isRTL: false
		};
		this._defaults = { // Global defaults for all the datetime picker instances
			showButtonPanel: true,
			timeOnly: false,
			showHour: true,
			showMinute: true,
			showSecond: false,
			showMillisec: false,
			showTimezone: false,
			showTime: true,
			stepHour: 1,
			stepMinute: 1,
			stepSecond: 1,
			stepMillisec: 1,
			hour: 0,
			minute: 0,
			second: 0,
			millisec: 0,
			timezone: null,
			useLocalTimezone: false,
			defaultTimezone: "+0000",
			hourMin: 0,
			minuteMin: 0,
			secondMin: 0,
			millisecMin: 0,
			hourMax: 23,
			minuteMax: 59,
			secondMax: 59,
			millisecMax: 999,
			minDateTime: null,
			maxDateTime: null,
			onSelect: null,
			hourGrid: 0,
			minuteGrid: 0,
			secondGrid: 0,
			millisecGrid: 0,
			alwaysSetTime: true,
			separator: ' ',
			altFieldTimeOnly: true,
			altTimeFormat: null,
			altSeparator: null,
			altTimeSuffix: null,
			pickerTimeFormat: null,
			pickerTimeSuffix: null,
			showTimepicker: true,
			timezoneIso8601: false,
			timezoneList: null,
			addSliderAccess: false,
			sliderAccessArgs: null,
			controlType: 'slider',
			defaultValue: null,
			parse: 'strict'
		};
		$.extend(this._defaults, this.regional['']);
	}

	$.extend(Timepicker.prototype, {
		$input: null,
		$altInput: null,
		$timeObj: null,
		inst: null,
		hour_slider: null,
		minute_slider: null,
		second_slider: null,
		millisec_slider: null,
		timezone_select: null,
		hour: 0,
		minute: 0,
		second: 0,
		millisec: 0,
		timezone: null,
		defaultTimezone: "+0000",
		hourMinOriginal: null,
		minuteMinOriginal: null,
		secondMinOriginal: null,
		millisecMinOriginal: null,
		hourMaxOriginal: null,
		minuteMaxOriginal: null,
		secondMaxOriginal: null,
		millisecMaxOriginal: null,
		ampm: '',
		formattedDate: '',
		formattedTime: '',
		formattedDateTime: '',
		timezoneList: null,
		units: ['hour','minute','second','millisec'],
		control: null,

		/* 
		* Override the default settings for all instances of the time picker.
		* @param  settings  object - the new settings to use as defaults (anonymous object)
		* @return the manager object
		*/
		setDefaults: function(settings) {
			extendRemove(this._defaults, settings || {});
			return this;
		},

		/*
		* Create a new Timepicker instance
		*/
		_newInst: function($input, o) {
			var tp_inst = new Timepicker(),
				inlineSettings = {},
                fns = {},
		        overrides, i;

			for (var attrName in this._defaults) {
				if(this._defaults.hasOwnProperty(attrName)){
					var attrValue = $input.attr('time:' + attrName);
					if (attrValue) {
						try {
							inlineSettings[attrName] = eval(attrValue);
						} catch (err) {
							inlineSettings[attrName] = attrValue;
						}
					}
				}
			}
		    overrides = {
		        beforeShow: function (input, dp_inst) {
		            if ($.isFunction(tp_inst._defaults.evnts.beforeShow)) {
		                return tp_inst._defaults.evnts.beforeShow.call($input[0], input, dp_inst, tp_inst);
		            }
		        },
		        onChangeMonthYear: function (year, month, dp_inst) {
		            // Update the time as well : this prevents the time from disappearing from the $input field.
		            tp_inst._updateDateTime(dp_inst);
		            if ($.isFunction(tp_inst._defaults.evnts.onChangeMonthYear)) {
		                tp_inst._defaults.evnts.onChangeMonthYear.call($input[0], year, month, dp_inst, tp_inst);
		            }
		        },
		        onClose: function (dateText, dp_inst) {
		            if (tp_inst.timeDefined === true && $input.val() !== '') {
		                tp_inst._updateDateTime(dp_inst);
		            }
		            if ($.isFunction(tp_inst._defaults.evnts.onClose)) {
		                tp_inst._defaults.evnts.onClose.call($input[0], dateText, dp_inst, tp_inst);
		            }
		        }
		    };
		    for (i in overrides) {
		        if (overrides.hasOwnProperty(i)) {
		            fns[i] = o[i] || null;
		        }
		    }
		    tp_inst._defaults = $.extend({}, this._defaults, inlineSettings, o, overrides, {
		        evnts:fns,
		        timepicker: tp_inst // add timepicker as a property of datepicker: $.datepicker._get(dp_inst, 'timepicker');
		    });
			tp_inst.amNames = $.map(tp_inst._defaults.amNames, function(val) {
				return val.toUpperCase();
			});
			tp_inst.pmNames = $.map(tp_inst._defaults.pmNames, function(val) {
				return val.toUpperCase();
			});

			// controlType is string - key to our this._controls
			if(typeof(tp_inst._defaults.controlType) === 'string'){
				if($.fn[tp_inst._defaults.controlType] === undefined){
					tp_inst._defaults.controlType = 'select';
				}
				tp_inst.control = tp_inst._controls[tp_inst._defaults.controlType];
			}
			// controlType is an object and must implement create, options, value methods
			else{ 
				tp_inst.control = tp_inst._defaults.controlType;
			}

			if (tp_inst._defaults.timezoneList === null) {
				var timezoneList = ['-1200', '-1100', '-1000', '-0930', '-0900', '-0800', '-0700', '-0600', '-0500', '-0430', '-0400', '-0330', '-0300', '-0200', '-0100', '+0000', 
									'+0100', '+0200', '+0300', '+0330', '+0400', '+0430', '+0500', '+0530', '+0545', '+0600', '+0630', '+0700', '+0800', '+0845', '+0900', '+0930', 
									'+1000', '+1030', '+1100', '+1130', '+1200', '+1245', '+1300', '+1400'];

				if (tp_inst._defaults.timezoneIso8601) {
					timezoneList = $.map(timezoneList, function(val) {
						return val == '+0000' ? 'Z' : (val.substring(0, 3) + ':' + val.substring(3));
					});
				}
				tp_inst._defaults.timezoneList = timezoneList;
			}

			tp_inst.timezone = tp_inst._defaults.timezone;
			tp_inst.hour = tp_inst._defaults.hour;
			tp_inst.minute = tp_inst._defaults.minute;
			tp_inst.second = tp_inst._defaults.second;
			tp_inst.millisec = tp_inst._defaults.millisec;
			tp_inst.ampm = '';
			tp_inst.$input = $input;

			if (o.altField) {
				tp_inst.$altInput = $(o.altField).css({
					cursor: 'pointer'
				}).focus(function() {
					$input.trigger("focus");
				});
			}

			if (tp_inst._defaults.minDate === 0 || tp_inst._defaults.minDateTime === 0) {
				tp_inst._defaults.minDate = new Date();
			}
			if (tp_inst._defaults.maxDate === 0 || tp_inst._defaults.maxDateTime === 0) {
				tp_inst._defaults.maxDate = new Date();
			}

			// datepicker needs minDate/maxDate, timepicker needs minDateTime/maxDateTime..
			if (tp_inst._defaults.minDate !== undefined && tp_inst._defaults.minDate instanceof Date) {
				tp_inst._defaults.minDateTime = new Date(tp_inst._defaults.minDate.getTime());
			}
			if (tp_inst._defaults.minDateTime !== undefined && tp_inst._defaults.minDateTime instanceof Date) {
				tp_inst._defaults.minDate = new Date(tp_inst._defaults.minDateTime.getTime());
			}
			if (tp_inst._defaults.maxDate !== undefined && tp_inst._defaults.maxDate instanceof Date) {
				tp_inst._defaults.maxDateTime = new Date(tp_inst._defaults.maxDate.getTime());
			}
			if (tp_inst._defaults.maxDateTime !== undefined && tp_inst._defaults.maxDateTime instanceof Date) {
				tp_inst._defaults.maxDate = new Date(tp_inst._defaults.maxDateTime.getTime());
			}
			tp_inst.$input.bind('focus', function() {
				tp_inst._onFocus();
			});

			return tp_inst;
		},

		/*
		* add our sliders to the calendar
		*/
		_addTimePicker: function(dp_inst) {
			var currDT = (this.$altInput && this._defaults.altFieldTimeOnly) ? this.$input.val() + ' ' + this.$altInput.val() : this.$input.val();

			this.timeDefined = this._parseTime(currDT);
			this._limitMinMaxDateTime(dp_inst, false);
			this._injectTimePicker();
		},

		/*
		* parse the time string from input value or _setTime
		*/
		_parseTime: function(timeString, withDate) {
			if (!this.inst) {
				this.inst = $.datepicker._getInst(this.$input[0]);
			}

			if (withDate || !this._defaults.timeOnly) {
				var dp_dateFormat = $.datepicker._get(this.inst, 'dateFormat');
				try {
					var parseRes = parseDateTimeInternal(dp_dateFormat, this._defaults.timeFormat, timeString, $.datepicker._getFormatConfig(this.inst), this._defaults);
					if (!parseRes.timeObj) {
						return false;
					}
					$.extend(this, parseRes.timeObj);
				} catch (err) {
					$.datepicker.log("Error parsing the date/time string: " + err +
									"\ndate/time string = " + timeString +
									"\ntimeFormat = " + this._defaults.timeFormat +
									"\ndateFormat = " + dp_dateFormat);
					return false;
				}
				return true;
			} else {
				var timeObj = $.datepicker.parseTime(this._defaults.timeFormat, timeString, this._defaults);
				if (!timeObj) {
					return false;
				}
				$.extend(this, timeObj);
				return true;
			}
		},

		/*
		* generate and inject html for timepicker into ui datepicker
		*/
		_injectTimePicker: function() {
			var $dp = this.inst.dpDiv,
				o = this.inst.settings,
				tp_inst = this,
				litem = '',
				uitem = '',
				max = {},
				gridSize = {},
				size = null;

			// Prevent displaying twice
			if ($dp.find("div.ui-timepicker-div").length === 0 && o.showTimepicker) {
				var noDisplay = ' style="display:none;"',
					html = '<div class="ui-timepicker-div'+ (o.isRTL? ' ui-timepicker-rtl' : '') +'"><dl>' + '<dt class="ui_tpicker_time_label"' + ((o.showTime) ? '' : noDisplay) + '>' + o.timeText + '</dt>' + 
								'<dd class="ui_tpicker_time"' + ((o.showTime) ? '' : noDisplay) + '></dd>';

				// Create the markup
				for(var i=0,l=this.units.length; i<l; i++){
					litem = this.units[i];
					uitem = litem.substr(0,1).toUpperCase() + litem.substr(1);
					// Added by Peter Medeiros:
					// - Figure out what the hour/minute/second max should be based on the step values.
					// - Example: if stepMinute is 15, then minMax is 45.
					max[litem] = parseInt((o[litem+'Max'] - ((o[litem+'Max'] - o[litem+'Min']) % o['step'+uitem])), 10);
					gridSize[litem] = 0;

					html += '<dt class="ui_tpicker_'+ litem +'_label"' + ((o['show'+uitem]) ? '' : noDisplay) + '>' + o[litem +'Text'] + '</dt>' + 
								'<dd class="ui_tpicker_'+ litem +'"><div class="ui_tpicker_'+ litem +'_slider"' + ((o['show'+uitem]) ? '' : noDisplay) + '></div>';

					if (o['show'+uitem] && o[litem+'Grid'] > 0) {
						html += '<div style="padding-left: 1px"><table class="ui-tpicker-grid-label"><tr>';

						if(litem == 'hour'){
							for (var h = o[litem+'Min']; h <= max[litem]; h += parseInt(o[litem+'Grid'], 10)) {
								gridSize[litem]++;
								var tmph = $.datepicker.formatTime(useAmpm(o.pickerTimeFormat || o.timeFormat)? 'hht':'HH', {hour:h}, o);									
								html += '<td data-for="'+litem+'">' + tmph + '</td>';
							}
						}
						else{
							for (var m = o[litem+'Min']; m <= max[litem]; m += parseInt(o[litem+'Grid'], 10)) {
								gridSize[litem]++;
								html += '<td data-for="'+litem+'">' + ((m < 10) ? '0' : '') + m + '</td>';
							}
						}

						html += '</tr></table></div>';
					}
					html += '</dd>';
				}
				
				// Timezone
				html += '<dt class="ui_tpicker_timezone_label"' + ((o.showTimezone) ? '' : noDisplay) + '>' + o.timezoneText + '</dt>';
				html += '<dd class="ui_tpicker_timezone" ' + ((o.showTimezone) ? '' : noDisplay) + '></dd>';

				// Create the elements from string
				html += '</dl></div>';
				var $tp = $(html);

				// if we only want time picker...
				if (o.timeOnly === true) {
					$tp.prepend('<div class="ui-widget-header ui-helper-clearfix ui-corner-all">' + '<div class="ui-datepicker-title">' + o.timeOnlyTitle + '</div>' + '</div>');
					$dp.find('.ui-datepicker-header, .ui-datepicker-calendar').hide();
				}
				
				// add sliders, adjust grids, add events
				for(var i=0,l=tp_inst.units.length; i<l; i++){
					litem = tp_inst.units[i];
					uitem = litem.substr(0,1).toUpperCase() + litem.substr(1);
					
					// add the slider
					tp_inst[litem+'_slider'] = tp_inst.control.create(tp_inst, $tp.find('.ui_tpicker_'+litem+'_slider'), litem, tp_inst[litem], o[litem+'Min'], max[litem], o['step'+uitem]);

					// adjust the grid and add click event
					if (o['show'+uitem] && o[litem+'Grid'] > 0) {
						size = 100 * gridSize[litem] * o[litem+'Grid'] / (max[litem] - o[litem+'Min']);
						$tp.find('.ui_tpicker_'+litem+' table').css({
							width: size + "%",
							marginLeft: o.isRTL? '0' : ((size / (-2 * gridSize[litem])) + "%"),
							marginRight: o.isRTL? ((size / (-2 * gridSize[litem])) + "%") : '0',
							borderCollapse: 'collapse'
						}).find("td").click(function(e){
								var $t = $(this),
									h = $t.html(),
									n = parseInt(h.replace(/[^0-9]/g),10),
									ap = h.replace(/[^apm]/ig),
									f = $t.data('for'); // loses scope, so we use data-for

								if(f == 'hour'){
									if(ap.indexOf('p') !== -1 && n < 12){
										n += 12;
									}
									else{
										if(ap.indexOf('a') !== -1 && n === 12){
											n = 0;
										}
									}
								}
								
								tp_inst.control.value(tp_inst, tp_inst[f+'_slider'], litem, n);

								tp_inst._onTimeChange();
								tp_inst._onSelectHandler();
							})
						.css({
								cursor: 'pointer',
								width: (100 / gridSize[litem]) + '%',
								textAlign: 'center',
								overflow: 'hidden'
							});
					} // end if grid > 0
				} // end for loop

				// Add timezone options
				this.timezone_select = $tp.find('.ui_tpicker_timezone').append('<select></select>').find("select");
				$.fn.append.apply(this.timezone_select,
				$.map(o.timezoneList, function(val, idx) {
					return $("<option />").val(typeof val == "object" ? val.value : val).text(typeof val == "object" ? val.label : val);
				}));
				if (typeof(this.timezone) != "undefined" && this.timezone !== null && this.timezone !== "") {
					var local_date = new Date(this.inst.selectedYear, this.inst.selectedMonth, this.inst.selectedDay, 12);
					var local_timezone = $.timepicker.timeZoneOffsetString(local_date);
					if (local_timezone == this.timezone) {
						selectLocalTimeZone(tp_inst);
					} else {
						this.timezone_select.val(this.timezone);
					}
				} else {
					if (typeof(this.hour) != "undefined" && this.hour !== null && this.hour !== "") {
						this.timezone_select.val(o.defaultTimezone);
					} else {
						selectLocalTimeZone(tp_inst);
					}
				}
				this.timezone_select.change(function() {
					tp_inst._defaults.useLocalTimezone = false;
					tp_inst._onTimeChange();
				});
				// End timezone options
				
				// inject timepicker into datepicker
				var $buttonPanel = $dp.find('.ui-datepicker-buttonpane');
				if ($buttonPanel.length) {
					$buttonPanel.before($tp);
				} else {
					$dp.append($tp);
				}

				this.$timeObj = $tp.find('.ui_tpicker_time');

				if (this.inst !== null) {
					var timeDefined = this.timeDefined;
					this._onTimeChange();
					this.timeDefined = timeDefined;
				}

				// slideAccess integration: http://trentrichardson.com/2011/11/11/jquery-ui-sliders-and-touch-accessibility/
				if (this._defaults.addSliderAccess) {
					var sliderAccessArgs = this._defaults.sliderAccessArgs,
						rtl = this._defaults.isRTL;
					sliderAccessArgs.isRTL = rtl;
						
					setTimeout(function() { // fix for inline mode
						if ($tp.find('.ui-slider-access').length === 0) {
							$tp.find('.ui-slider:visible').sliderAccess(sliderAccessArgs);

							// fix any grids since sliders are shorter
							var sliderAccessWidth = $tp.find('.ui-slider-access:eq(0)').outerWidth(true);
							if (sliderAccessWidth) {
								$tp.find('table:visible').each(function() {
									var $g = $(this),
										oldWidth = $g.outerWidth(),
										oldMarginLeft = $g.css(rtl? 'marginRight':'marginLeft').toString().replace('%', ''),
										newWidth = oldWidth - sliderAccessWidth,
										newMarginLeft = ((oldMarginLeft * newWidth) / oldWidth) + '%',
										css = { width: newWidth, marginRight: 0, marginLeft: 0 };
									css[rtl? 'marginRight':'marginLeft'] = newMarginLeft;
									$g.css(css);
								});
							}
						}
					}, 10);
				}
				// end slideAccess integration

			}
		},

		/*
		* This function tries to limit the ability to go outside the
		* min/max date range
		*/
		_limitMinMaxDateTime: function(dp_inst, adjustSliders) {
			var o = this._defaults,
				dp_date = new Date(dp_inst.selectedYear, dp_inst.selectedMonth, dp_inst.selectedDay);

			if (!this._defaults.showTimepicker) {
				return;
			} // No time so nothing to check here

			if ($.datepicker._get(dp_inst, 'minDateTime') !== null && $.datepicker._get(dp_inst, 'minDateTime') !== undefined && dp_date) {
				var minDateTime = $.datepicker._get(dp_inst, 'minDateTime'),
					minDateTimeDate = new Date(minDateTime.getFullYear(), minDateTime.getMonth(), minDateTime.getDate(), 0, 0, 0, 0);

				if (this.hourMinOriginal === null || this.minuteMinOriginal === null || this.secondMinOriginal === null || this.millisecMinOriginal === null) {
					this.hourMinOriginal = o.hourMin;
					this.minuteMinOriginal = o.minuteMin;
					this.secondMinOriginal = o.secondMin;
					this.millisecMinOriginal = o.millisecMin;
				}

				if (dp_inst.settings.timeOnly || minDateTimeDate.getTime() == dp_date.getTime()) {
					this._defaults.hourMin = minDateTime.getHours();
					if (this.hour <= this._defaults.hourMin) {
						this.hour = this._defaults.hourMin;
						this._defaults.minuteMin = minDateTime.getMinutes();
						if (this.minute <= this._defaults.minuteMin) {
							this.minute = this._defaults.minuteMin;
							this._defaults.secondMin = minDateTime.getSeconds();
							if (this.second <= this._defaults.secondMin) {
								this.second = this._defaults.secondMin;
								this._defaults.millisecMin = minDateTime.getMilliseconds();
							} else {
								if (this.millisec < this._defaults.millisecMin) {
									this.millisec = this._defaults.millisecMin;
								}
								this._defaults.millisecMin = this.millisecMinOriginal;
							}
						} else {
							this._defaults.secondMin = this.secondMinOriginal;
							this._defaults.millisecMin = this.millisecMinOriginal;
						}
					} else {
						this._defaults.minuteMin = this.minuteMinOriginal;
						this._defaults.secondMin = this.secondMinOriginal;
						this._defaults.millisecMin = this.millisecMinOriginal;
					}
				} else {
					this._defaults.hourMin = this.hourMinOriginal;
					this._defaults.minuteMin = this.minuteMinOriginal;
					this._defaults.secondMin = this.secondMinOriginal;
					this._defaults.millisecMin = this.millisecMinOriginal;
				}
			}

			if ($.datepicker._get(dp_inst, 'maxDateTime') !== null && $.datepicker._get(dp_inst, 'maxDateTime') !== undefined && dp_date) {
				var maxDateTime = $.datepicker._get(dp_inst, 'maxDateTime'),
					maxDateTimeDate = new Date(maxDateTime.getFullYear(), maxDateTime.getMonth(), maxDateTime.getDate(), 0, 0, 0, 0);

				if (this.hourMaxOriginal === null || this.minuteMaxOriginal === null || this.secondMaxOriginal === null) {
					this.hourMaxOriginal = o.hourMax;
					this.minuteMaxOriginal = o.minuteMax;
					this.secondMaxOriginal = o.secondMax;
					this.millisecMaxOriginal = o.millisecMax;
				}

				if (dp_inst.settings.timeOnly || maxDateTimeDate.getTime() == dp_date.getTime()) {
					this._defaults.hourMax = maxDateTime.getHours();
					if (this.hour >= this._defaults.hourMax) {
						this.hour = this._defaults.hourMax;
						this._defaults.minuteMax = maxDateTime.getMinutes();
						if (this.minute >= this._defaults.minuteMax) {
							this.minute = this._defaults.minuteMax;
							this._defaults.secondMax = maxDateTime.getSeconds();
						} else if (this.second >= this._defaults.secondMax) {
							this.second = this._defaults.secondMax;
							this._defaults.millisecMax = maxDateTime.getMilliseconds();
						} else {
							if (this.millisec > this._defaults.millisecMax) {
								this.millisec = this._defaults.millisecMax;
							}
							this._defaults.millisecMax = this.millisecMaxOriginal;
						}
					} else {
						this._defaults.minuteMax = this.minuteMaxOriginal;
						this._defaults.secondMax = this.secondMaxOriginal;
						this._defaults.millisecMax = this.millisecMaxOriginal;
					}
				} else {
					this._defaults.hourMax = this.hourMaxOriginal;
					this._defaults.minuteMax = this.minuteMaxOriginal;
					this._defaults.secondMax = this.secondMaxOriginal;
					this._defaults.millisecMax = this.millisecMaxOriginal;
				}
			}

			if (adjustSliders !== undefined && adjustSliders === true) {
				var hourMax = parseInt((this._defaults.hourMax - ((this._defaults.hourMax - this._defaults.hourMin) % this._defaults.stepHour)), 10),
					minMax = parseInt((this._defaults.minuteMax - ((this._defaults.minuteMax - this._defaults.minuteMin) % this._defaults.stepMinute)), 10),
					secMax = parseInt((this._defaults.secondMax - ((this._defaults.secondMax - this._defaults.secondMin) % this._defaults.stepSecond)), 10),
					millisecMax = parseInt((this._defaults.millisecMax - ((this._defaults.millisecMax - this._defaults.millisecMin) % this._defaults.stepMillisec)), 10);

				if (this.hour_slider) {
					this.control.options(this, this.hour_slider, 'hour', { min: this._defaults.hourMin, max: hourMax });
					this.control.value(this, this.hour_slider, 'hour', this.hour);
				}
				if (this.minute_slider) {
					this.control.options(this, this.minute_slider, 'minute', { min: this._defaults.minuteMin, max: minMax });
					this.control.value(this, this.minute_slider, 'minute', this.minute);
				}
				if (this.second_slider) {
					this.control.options(this, this.second_slider, 'second', { min: this._defaults.secondMin, max: secMax });
					this.control.value(this, this.second_slider, 'second', this.second);
				}
				if (this.millisec_slider) {
					this.control.options(this, this.millisec_slider, 'millisec', { min: this._defaults.millisecMin, max: millisecMax });
					this.control.value(this, this.millisec_slider, 'millisec', this.millisec);
				}
			}

		},

		/*
		* when a slider moves, set the internal time...
		* on time change is also called when the time is updated in the text field
		*/
		_onTimeChange: function() {
			var hour = (this.hour_slider) ? this.control.value(this, this.hour_slider, 'hour') : false,
				minute = (this.minute_slider) ? this.control.value(this, this.minute_slider, 'minute') : false,
				second = (this.second_slider) ? this.control.value(this, this.second_slider, 'second') : false,
				millisec = (this.millisec_slider) ? this.control.value(this, this.millisec_slider, 'millisec') : false,
				timezone = (this.timezone_select) ? this.timezone_select.val() : false,
				o = this._defaults,
				pickerTimeFormat = o.pickerTimeFormat || o.timeFormat,
				pickerTimeSuffix = o.pickerTimeSuffix || o.timeSuffix;

			if (typeof(hour) == 'object') {
				hour = false;
			}
			if (typeof(minute) == 'object') {
				minute = false;
			}
			if (typeof(second) == 'object') {
				second = false;
			}
			if (typeof(millisec) == 'object') {
				millisec = false;
			}
			if (typeof(timezone) == 'object') {
				timezone = false;
			}

			if (hour !== false) {
				hour = parseInt(hour, 10);
			}
			if (minute !== false) {
				minute = parseInt(minute, 10);
			}
			if (second !== false) {
				second = parseInt(second, 10);
			}
			if (millisec !== false) {
				millisec = parseInt(millisec, 10);
			}

			var ampm = o[hour < 12 ? 'amNames' : 'pmNames'][0];

			// If the update was done in the input field, the input field should not be updated.
			// If the update was done using the sliders, update the input field.
			var hasChanged = (hour != this.hour || minute != this.minute || second != this.second || millisec != this.millisec 
								|| (this.ampm.length > 0 && (hour < 12) != ($.inArray(this.ampm.toUpperCase(), this.amNames) !== -1)) 
								|| ((this.timezone === null && timezone != this.defaultTimezone) || (this.timezone !== null && timezone != this.timezone)));

			if (hasChanged) {

				if (hour !== false) {
					this.hour = hour;
				}
				if (minute !== false) {
					this.minute = minute;
				}
				if (second !== false) {
					this.second = second;
				}
				if (millisec !== false) {
					this.millisec = millisec;
				}
				if (timezone !== false) {
					this.timezone = timezone;
				}

				if (!this.inst) {
					this.inst = $.datepicker._getInst(this.$input[0]);
				}

				this._limitMinMaxDateTime(this.inst, true);
			}
			if (useAmpm(o.timeFormat)) {
				this.ampm = ampm;
			}

			// Updates the time within the timepicker
			this.formattedTime = $.datepicker.formatTime(o.timeFormat, this, o);
			if (this.$timeObj) {
				if(pickerTimeFormat === o.timeFormat){
					this.$timeObj.text(this.formattedTime + pickerTimeSuffix);
				}
				else{
					this.$timeObj.text($.datepicker.formatTime(pickerTimeFormat, this, o) + pickerTimeSuffix);
				}
			}

			this.timeDefined = true;
			if (hasChanged) {
				this._updateDateTime();
			}
		},

		/*
		* call custom onSelect.
		* bind to sliders slidestop, and grid click.
		*/
		_onSelectHandler: function() {
			var onSelect = this._defaults.onSelect || this.inst.settings.onSelect;
			var inputEl = this.$input ? this.$input[0] : null;
			if (onSelect && inputEl) {
				onSelect.apply(inputEl, [this.formattedDateTime, this]);
			}
		},

		/*
		* update our input with the new date time..
		*/
		_updateDateTime: function(dp_inst) {
			dp_inst = this.inst || dp_inst;
			var dt = $.datepicker._daylightSavingAdjust(new Date(dp_inst.selectedYear, dp_inst.selectedMonth, dp_inst.selectedDay)),
				dateFmt = $.datepicker._get(dp_inst, 'dateFormat'),
				formatCfg = $.datepicker._getFormatConfig(dp_inst),
				timeAvailable = dt !== null && this.timeDefined;
			this.formattedDate = $.datepicker.formatDate(dateFmt, (dt === null ? new Date() : dt), formatCfg);
			var formattedDateTime = this.formattedDate;

			/*
			* remove following lines to force every changes in date picker to change the input value
			* Bug descriptions: when an input field has a default value, and click on the field to pop up the date picker. 
			* If the user manually empty the value in the input field, the date picker will never change selected value.
			*/
			//if (dp_inst.lastVal !== undefined && (dp_inst.lastVal.length > 0 && this.$input.val().length === 0)) {
			//	return;
			//}

			if (this._defaults.timeOnly === true) {
				formattedDateTime = this.formattedTime;
			} else if (this._defaults.timeOnly !== true && (this._defaults.alwaysSetTime || timeAvailable)) {
				formattedDateTime += this._defaults.separator + this.formattedTime + this._defaults.timeSuffix;
			}

			this.formattedDateTime = formattedDateTime;

			if (!this._defaults.showTimepicker) {
				this.$input.val(this.formattedDate);
			} else if (this.$altInput && this._defaults.altFieldTimeOnly === true) {
				this.$altInput.val(this.formattedTime);
				this.$input.val(this.formattedDate);
			} else if (this.$altInput) {
				this.$input.val(formattedDateTime);
				var altFormattedDateTime = '',
					altSeparator = this._defaults.altSeparator ? this._defaults.altSeparator : this._defaults.separator,
					altTimeSuffix = this._defaults.altTimeSuffix ? this._defaults.altTimeSuffix : this._defaults.timeSuffix;

				if (this._defaults.altFormat) altFormattedDateTime = $.datepicker.formatDate(this._defaults.altFormat, (dt === null ? new Date() : dt), formatCfg);
				else altFormattedDateTime = this.formattedDate;
				if (altFormattedDateTime) altFormattedDateTime += altSeparator;
				if (this._defaults.altTimeFormat) altFormattedDateTime += $.datepicker.formatTime(this._defaults.altTimeFormat, this, this._defaults) + altTimeSuffix;
				else altFormattedDateTime += this.formattedTime + altTimeSuffix;
				this.$altInput.val(altFormattedDateTime);
			} else {
				this.$input.val(formattedDateTime);
			}

			this.$input.trigger("change");
		},

		_onFocus: function() {
			if (!this.$input.val() && this._defaults.defaultValue) {
				this.$input.val(this._defaults.defaultValue);
				var inst = $.datepicker._getInst(this.$input.get(0)),
					tp_inst = $.datepicker._get(inst, 'timepicker');
				if (tp_inst) {
					if (tp_inst._defaults.timeOnly && (inst.input.val() != inst.lastVal)) {
						try {
							$.datepicker._updateDatepicker(inst);
						} catch (err) {
							$.datepicker.log(err);
						}
					}
				}
			}
		},

		/*
		* Small abstraction to control types
		* We can add more, just be sure to follow the pattern: create, options, value
		*/
		_controls: {
			// slider methods
			slider: {
				create: function(tp_inst, obj, unit, val, min, max, step){
					var rtl = tp_inst._defaults.isRTL; // if rtl go -60->0 instead of 0->60
					return obj.prop('slide', null).slider({
						orientation: "horizontal",
						value: rtl? val*-1 : val,
						min: rtl? max*-1 : min,
						max: rtl? min*-1 : max,
						step: step,
						slide: function(event, ui) {
							tp_inst.control.value(tp_inst, $(this), unit, rtl? ui.value*-1:ui.value);
							tp_inst._onTimeChange();
						},
						stop: function(event, ui) {
							tp_inst._onSelectHandler();
						}
					});	
				},
				options: function(tp_inst, obj, unit, opts, val){
					if(tp_inst._defaults.isRTL){
						if(typeof(opts) == 'string'){
							if(opts == 'min' || opts == 'max'){
								if(val !== undefined)
									return obj.slider(opts, val*-1);
								return Math.abs(obj.slider(opts));
							}
							return obj.slider(opts);
						}
						var min = opts.min, 
							max = opts.max;
						opts.min = opts.max = null;
						if(min !== undefined)
							opts.max = min * -1;
						if(max !== undefined)
							opts.min = max * -1;
						return obj.slider(opts);
					}
					if(typeof(opts) == 'string' && val !== undefined)
							return obj.slider(opts, val);
					return obj.slider(opts);
				},
				value: function(tp_inst, obj, unit, val){
					if(tp_inst._defaults.isRTL){
						if(val !== undefined)
							return obj.slider('value', val*-1);
						return Math.abs(obj.slider('value'));
					}
					if(val !== undefined)
						return obj.slider('value', val);
					return obj.slider('value');
				}
			},
			// select methods
			select: {
				create: function(tp_inst, obj, unit, val, min, max, step){
					var sel = '<select class="ui-timepicker-select" data-unit="'+ unit +'" data-min="'+ min +'" data-max="'+ max +'" data-step="'+ step +'">',
						ul = tp_inst._defaults.timeFormat.indexOf('t') !== -1? 'toLowerCase':'toUpperCase',
						m = 0;

					for(var i=min; i<=max; i+=step){						
						sel += '<option value="'+ i +'"'+ (i==val? ' selected':'') +'>';
						if(unit == 'hour' && useAmpm(tp_inst._defaults.pickerTimeFormat || tp_inst._defaults.timeFormat))
							sel += $.datepicker.formatTime("hh TT", {hour:i}, tp_inst._defaults);
						else if(unit == 'millisec' || i >= 10) sel += i;
						else sel += '0'+ i.toString();
						sel += '</option>';
					}
					sel += '</select>';

					obj.children('select').remove();

					$(sel).appendTo(obj).change(function(e){
						tp_inst._onTimeChange();
						tp_inst._onSelectHandler();
					});

					return obj;
				},
				options: function(tp_inst, obj, unit, opts, val){
					var o = {},
						$t = obj.children('select');
					if(typeof(opts) == 'string'){
						if(val === undefined)
							return $t.data(opts);
						o[opts] = val;	
					}
					else o = opts;
					return tp_inst.control.create(tp_inst, obj, $t.data('unit'), $t.val(), o.min || $t.data('min'), o.max || $t.data('max'), o.step || $t.data('step'));
				},
				value: function(tp_inst, obj, unit, val){
					var $t = obj.children('select');
					if(val !== undefined)
						return $t.val(val);
					return $t.val();
				}
			}
		} // end _controls

	});

	$.fn.extend({
		/*
		* shorthand just to use timepicker..
		*/
		timepicker: function(o) {
			o = o || {};
			var tmp_args = Array.prototype.slice.call(arguments);

			if (typeof o == 'object') {
				tmp_args[0] = $.extend(o, {
					timeOnly: true
				});
			}

			return $(this).each(function() {
				$.fn.datetimepicker.apply($(this), tmp_args);
			});
		},

		/*
		* extend timepicker to datepicker
		*/
		datetimepicker: function(o) {
			o = o || {};
			var tmp_args = arguments;

			if (typeof(o) == 'string') {
				if (o == 'getDate') {
					return $.fn.datepicker.apply($(this[0]), tmp_args);
				} else {
					return this.each(function() {
						var $t = $(this);
						$t.datepicker.apply($t, tmp_args);
					});
				}
			} else {
				return this.each(function() {
					var $t = $(this);
					$t.datepicker($.timepicker._newInst($t, o)._defaults);
				});
			}
		}
	});

	/*
	* Public Utility to parse date and time
	*/
	$.datepicker.parseDateTime = function(dateFormat, timeFormat, dateTimeString, dateSettings, timeSettings) {
		var parseRes = parseDateTimeInternal(dateFormat, timeFormat, dateTimeString, dateSettings, timeSettings);
		if (parseRes.timeObj) {
			var t = parseRes.timeObj;
			parseRes.date.setHours(t.hour, t.minute, t.second, t.millisec);
		}

		return parseRes.date;
	};

	/*
	* Public utility to parse time
	*/
	$.datepicker.parseTime = function(timeFormat, timeString, options) {		
		var o = extendRemove(extendRemove({}, $.timepicker._defaults), options || {});

		// Strict parse requires the timeString to match the timeFormat exactly
		var strictParse = function(f, s, o){

			// pattern for standard and localized AM/PM markers
			var getPatternAmpm = function(amNames, pmNames) {
				var markers = [];
				if (amNames) {
					$.merge(markers, amNames);
				}
				if (pmNames) {
					$.merge(markers, pmNames);
				}
				markers = $.map(markers, function(val) {
					return val.replace(/[.*+?|()\[\]{}\\]/g, '\\$&');
				});
				return '(' + markers.join('|') + ')?';
			};

			// figure out position of time elements.. cause js cant do named captures
			var getFormatPositions = function(timeFormat) {
				var finds = timeFormat.toLowerCase().match(/(h{1,2}|m{1,2}|s{1,2}|l{1}|t{1,2}|z|'.*?')/g),
					orders = {
						h: -1,
						m: -1,
						s: -1,
						l: -1,
						t: -1,
						z: -1
					};

				if (finds) {
					for (var i = 0; i < finds.length; i++) {
						if (orders[finds[i].toString().charAt(0)] == -1) {
							orders[finds[i].toString().charAt(0)] = i + 1;
						}
					}
				}
				return orders;
			};

			var regstr = '^' + f.toString()
					.replace(/([hH]{1,2}|mm?|ss?|[tT]{1,2}|[lz]|'.*?')/g, function (match) {
							switch (match.charAt(0).toLowerCase()) {
								case 'h': return '(\\d?\\d)';
								case 'm': return '(\\d?\\d)';
								case 's': return '(\\d?\\d)';
								case 'l': return '(\\d?\\d?\\d)';
								case 'z': return '(z|[-+]\\d\\d:?\\d\\d|\\S+)?';
								case 't': return getPatternAmpm(o.amNames, o.pmNames);
								default:    // literal escaped in quotes
									return '(' + match.replace(/\'/g, "").replace(/(\.|\$|\^|\\|\/|\(|\)|\[|\]|\?|\+|\*)/g, function (m) { return "\\" + m; }) + ')?';
							}
						})
					.replace(/\s/g, '\\s?') +
					o.timeSuffix + '$',
				order = getFormatPositions(f),
				ampm = '',
				treg;

			treg = s.match(new RegExp(regstr, 'i'));

			var resTime = {
				hour: 0,
				minute: 0,
				second: 0,
				millisec: 0
			};

			if (treg) {
				if (order.t !== -1) {
					if (treg[order.t] === undefined || treg[order.t].length === 0) {
						ampm = '';
						resTime.ampm = '';
					} else {
						ampm = $.inArray(treg[order.t].toUpperCase(), o.amNames) !== -1 ? 'AM' : 'PM';
						resTime.ampm = o[ampm == 'AM' ? 'amNames' : 'pmNames'][0];
					}
				}

				if (order.h !== -1) {
					if (ampm == 'AM' && treg[order.h] == '12') {
						resTime.hour = 0; // 12am = 0 hour
					} else {
						if (ampm == 'PM' && treg[order.h] != '12') {
							resTime.hour = parseInt(treg[order.h], 10) + 12; // 12pm = 12 hour, any other pm = hour + 12
						} else {
							resTime.hour = Number(treg[order.h]);
						}
					}
				}

				if (order.m !== -1) {
					resTime.minute = Number(treg[order.m]);
				}
				if (order.s !== -1) {
					resTime.second = Number(treg[order.s]);
				}
				if (order.l !== -1) {
					resTime.millisec = Number(treg[order.l]);
				}
				if (order.z !== -1 && treg[order.z] !== undefined) {
					var tz = treg[order.z].toUpperCase();
					switch (tz.length) {
					case 1:
						// Z
						tz = o.timezoneIso8601 ? 'Z' : '+0000';
						break;
					case 5:
						// +hhmm
						if (o.timezoneIso8601) {
							tz = tz.substring(1) == '0000' ? 'Z' : tz.substring(0, 3) + ':' + tz.substring(3);
						}
						break;
					case 6:
						// +hh:mm
						if (!o.timezoneIso8601) {
							tz = tz == 'Z' || tz.substring(1) == '00:00' ? '+0000' : tz.replace(/:/, '');
						} else {
							if (tz.substring(1) == '00:00') {
								tz = 'Z';
							}
						}
						break;
					}
					resTime.timezone = tz;
				}


				return resTime;
			}
			return false;
		};// end strictParse

		// First try JS Date, if that fails, use strictParse
		var looseParse = function(f,s,o){
			try{
				var d = new Date('2012-01-01 '+ s);
				return {
					hour: d.getHours(),
					minutes: d.getMinutes(),
					seconds: d.getSeconds(),
					millisec: d.getMilliseconds(),
					timezone: $.timepicker.timeZoneOffsetString(d)
				};
			}
			catch(err){
				try{
					return strictParse(f,s,o);
				}
				catch(err2){
					$.datepicker.log("Unable to parse \ntimeString: "+ s +"\ntimeFormat: "+ f);
				}				
			}
			return false;
		}; // end looseParse
		
		if(typeof o.parse === "function"){
			return o.parse(timeFormat, timeString, o)
		}
		if(o.parse === 'loose'){
			return looseParse(timeFormat, timeString, o);
		}
		return strictParse(timeFormat, timeString, o);
	};

	/*
	* Public utility to format the time
	* format = string format of the time
	* time = a {}, not a Date() for timezones
	* options = essentially the regional[].. amNames, pmNames, ampm
	*/
	$.datepicker.formatTime = function(format, time, options) {
		options = options || {};
		options = $.extend({}, $.timepicker._defaults, options);
		time = $.extend({
			hour: 0,
			minute: 0,
			second: 0,
			millisec: 0,
			timezone: '+0000'
		}, time);

		var tmptime = format,
			ampmName = options.amNames[0],
			hour = parseInt(time.hour, 10);

		if (hour > 11) {
			ampmName = options.pmNames[0];
		}

		tmptime = tmptime.replace(/(?:HH?|hh?|mm?|ss?|[tT]{1,2}|[lz]|('.*?'|".*?"))/g, function(match) {
		switch (match) {
			case 'HH':
				return ('0' + hour).slice(-2);
			case 'H':
				return hour;
			case 'hh':
				return convert24to12(hour).slice(-2);
			case 'h':
				return convert24to12(hour);
			case 'mm':
				return ('0' + time.minute).slice(-2);
			case 'm':
				return time.minute;
			case 'ss':
				return ('0' + time.second).slice(-2);
			case 's':
				return time.second;
			case 'l':
				return ('00' + time.millisec).slice(-3);
			case 'z':
				return time.timezone === null? options.defaultTimezone : time.timezone;
			case 'T': 
				return ampmName.charAt(0).toUpperCase();
			case 'TT': 
				return ampmName.toUpperCase();
			case 't':
				return ampmName.charAt(0).toLowerCase();
			case 'tt':
				return ampmName.toLowerCase();
			default:
				return match.replace(/\'/g, "") || "'";
			}
		});

		tmptime = $.trim(tmptime);
		return tmptime;
	};

	/*
	* the bad hack :/ override datepicker so it doesnt close on select
	// inspired: http://stackoverflow.com/questions/1252512/jquery-datepicker-prevent-closing-picker-when-clicking-a-date/1762378#1762378
	*/
	$.datepicker._base_selectDate = $.datepicker._selectDate;
	$.datepicker._selectDate = function(id, dateStr) {
		var inst = this._getInst($(id)[0]),
			tp_inst = this._get(inst, 'timepicker');

		if (tp_inst) {
			tp_inst._limitMinMaxDateTime(inst, true);
			inst.inline = inst.stay_open = true;
			//This way the onSelect handler called from calendarpicker get the full dateTime
			this._base_selectDate(id, dateStr);
			inst.inline = inst.stay_open = false;
			this._notifyChange(inst);
			this._updateDatepicker(inst);
		} else {
			this._base_selectDate(id, dateStr);
		}
	};

	/*
	* second bad hack :/ override datepicker so it triggers an event when changing the input field
	* and does not redraw the datepicker on every selectDate event
	*/
	$.datepicker._base_updateDatepicker = $.datepicker._updateDatepicker;
	$.datepicker._updateDatepicker = function(inst) {

		// don't popup the datepicker if there is another instance already opened
		var input = inst.input[0];
		if ($.datepicker._curInst && $.datepicker._curInst != inst && $.datepicker._datepickerShowing && $.datepicker._lastInput != input) {
			return;
		}

		if (typeof(inst.stay_open) !== 'boolean' || inst.stay_open === false) {

			this._base_updateDatepicker(inst);

			// Reload the time control when changing something in the input text field.
			var tp_inst = this._get(inst, 'timepicker');
			if (tp_inst) {
				tp_inst._addTimePicker(inst);

				if (tp_inst._defaults.useLocalTimezone) { //checks daylight saving with the new date.
					var date = new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay, 12);
					selectLocalTimeZone(tp_inst, date);
					tp_inst._onTimeChange();
				}
			}
		}
	};

	/*
	* third bad hack :/ override datepicker so it allows spaces and colon in the input field
	*/
	$.datepicker._base_doKeyPress = $.datepicker._doKeyPress;
	$.datepicker._doKeyPress = function(event) {
		var inst = $.datepicker._getInst(event.target),
			tp_inst = $.datepicker._get(inst, 'timepicker');

		if (tp_inst) {
			if ($.datepicker._get(inst, 'constrainInput')) {
				var ampm = useAmpm(tp_inst._defaults.timeFormat),
					dateChars = $.datepicker._possibleChars($.datepicker._get(inst, 'dateFormat')),
					datetimeChars = tp_inst._defaults.timeFormat.toString()
											.replace(/[hms]/g, '')
											.replace(/TT/g, ampm ? 'APM' : '')
											.replace(/Tt/g, ampm ? 'AaPpMm' : '')
											.replace(/tT/g, ampm ? 'AaPpMm' : '')
											.replace(/T/g, ampm ? 'AP' : '')
											.replace(/tt/g, ampm ? 'apm' : '')
											.replace(/t/g, ampm ? 'ap' : '') + 
											" " + tp_inst._defaults.separator + 
											tp_inst._defaults.timeSuffix + 
											(tp_inst._defaults.showTimezone ? tp_inst._defaults.timezoneList.join('') : '') + 
											(tp_inst._defaults.amNames.join('')) + (tp_inst._defaults.pmNames.join('')) + 
											dateChars,
					chr = String.fromCharCode(event.charCode === undefined ? event.keyCode : event.charCode);
				return event.ctrlKey || (chr < ' ' || !dateChars || datetimeChars.indexOf(chr) > -1);
			}
		}

		return $.datepicker._base_doKeyPress(event);
	};

	/*
	* Fourth bad hack :/ override _updateAlternate function used in inline mode to init altField
	*/
	$.datepicker._base_updateAlternate = $.datepicker._updateAlternate;
	/* Update any alternate field to synchronise with the main field. */
	$.datepicker._updateAlternate = function(inst) {
		var tp_inst = this._get(inst, 'timepicker');
		if(tp_inst){
			var altField = tp_inst._defaults.altField;
			if (altField) { // update alternate field too
				var altFormat = tp_inst._defaults.altFormat || tp_inst._defaults.dateFormat,
					date = this._getDate(inst),
					formatCfg = $.datepicker._getFormatConfig(inst),
					altFormattedDateTime = '', 
					altSeparator = tp_inst._defaults.altSeparator ? tp_inst._defaults.altSeparator : tp_inst._defaults.separator, 
					altTimeSuffix = tp_inst._defaults.altTimeSuffix ? tp_inst._defaults.altTimeSuffix : tp_inst._defaults.timeSuffix,
					altTimeFormat = tp_inst._defaults.altTimeFormat !== null ? tp_inst._defaults.altTimeFormat : tp_inst._defaults.timeFormat;
				
				altFormattedDateTime += $.datepicker.formatTime(altTimeFormat, tp_inst, tp_inst._defaults) + altTimeSuffix;
				if(!tp_inst._defaults.timeOnly && !tp_inst._defaults.altFieldTimeOnly){
					if(tp_inst._defaults.altFormat)
						altFormattedDateTime = $.datepicker.formatDate(tp_inst._defaults.altFormat, (date === null ? new Date() : date), formatCfg) + altSeparator + altFormattedDateTime;
					else altFormattedDateTime = tp_inst.formattedDate + altSeparator + altFormattedDateTime;
				}
				$(altField).val(altFormattedDateTime);
			}
		}
		else{
			$.datepicker._base_updateAlternate(inst);
		}
	};

	/*
	* Override key up event to sync manual input changes.
	*/
	$.datepicker._base_doKeyUp = $.datepicker._doKeyUp;
	$.datepicker._doKeyUp = function(event) {
		var inst = $.datepicker._getInst(event.target),
			tp_inst = $.datepicker._get(inst, 'timepicker');

		if (tp_inst) {
			if (tp_inst._defaults.timeOnly && (inst.input.val() != inst.lastVal)) {
				try {
					$.datepicker._updateDatepicker(inst);
				} catch (err) {
					$.datepicker.log(err);
				}
			}
		}

		return $.datepicker._base_doKeyUp(event);
	};

	/*
	* override "Today" button to also grab the time.
	*/
	$.datepicker._base_gotoToday = $.datepicker._gotoToday;
	$.datepicker._gotoToday = function(id) {
		var inst = this._getInst($(id)[0]),
			$dp = inst.dpDiv;
		this._base_gotoToday(id);
		var tp_inst = this._get(inst, 'timepicker');
		selectLocalTimeZone(tp_inst);
		var now = new Date();
		this._setTime(inst, now);
		$('.ui-datepicker-today', $dp).click();
	};

	/*
	* Disable & enable the Time in the datetimepicker
	*/
	$.datepicker._disableTimepickerDatepicker = function(target) {
		var inst = this._getInst(target);
		if (!inst) {
			return;
		}

		var tp_inst = this._get(inst, 'timepicker');
		$(target).datepicker('getDate'); // Init selected[Year|Month|Day]
		if (tp_inst) {
			tp_inst._defaults.showTimepicker = false;
			tp_inst._updateDateTime(inst);
		}
	};

	$.datepicker._enableTimepickerDatepicker = function(target) {
		var inst = this._getInst(target);
		if (!inst) {
			return;
		}

		var tp_inst = this._get(inst, 'timepicker');
		$(target).datepicker('getDate'); // Init selected[Year|Month|Day]
		if (tp_inst) {
			tp_inst._defaults.showTimepicker = true;
			tp_inst._addTimePicker(inst); // Could be disabled on page load
			tp_inst._updateDateTime(inst);
		}
	};

	/*
	* Create our own set time function
	*/
	$.datepicker._setTime = function(inst, date) {
		var tp_inst = this._get(inst, 'timepicker');
		if (tp_inst) {
			var defaults = tp_inst._defaults;

			// calling _setTime with no date sets time to defaults
			tp_inst.hour = date ? date.getHours() : defaults.hour;
			tp_inst.minute = date ? date.getMinutes() : defaults.minute;
			tp_inst.second = date ? date.getSeconds() : defaults.second;
			tp_inst.millisec = date ? date.getMilliseconds() : defaults.millisec;

			//check if within min/max times.. 
			tp_inst._limitMinMaxDateTime(inst, true);

			tp_inst._onTimeChange();
			tp_inst._updateDateTime(inst);
		}
	};

	/*
	* Create new public method to set only time, callable as $().datepicker('setTime', date)
	*/
	$.datepicker._setTimeDatepicker = function(target, date, withDate) {
		var inst = this._getInst(target);
		if (!inst) {
			return;
		}

		var tp_inst = this._get(inst, 'timepicker');

		if (tp_inst) {
			this._setDateFromField(inst);
			var tp_date;
			if (date) {
				if (typeof date == "string") {
					tp_inst._parseTime(date, withDate);
					tp_date = new Date();
					tp_date.setHours(tp_inst.hour, tp_inst.minute, tp_inst.second, tp_inst.millisec);
				} else {
					tp_date = new Date(date.getTime());
				}
				if (tp_date.toString() == 'Invalid Date') {
					tp_date = undefined;
				}
				this._setTime(inst, tp_date);
			}
		}

	};

	/*
	* override setDate() to allow setting time too within Date object
	*/
	$.datepicker._base_setDateDatepicker = $.datepicker._setDateDatepicker;
	$.datepicker._setDateDatepicker = function(target, date) {
		var inst = this._getInst(target);
		if (!inst) {
			return;
		}

		var tp_date = (date instanceof Date) ? new Date(date.getTime()) : date;

		this._updateDatepicker(inst);
		this._base_setDateDatepicker.apply(this, arguments);
		this._setTimeDatepicker(target, tp_date, true);
	};

	/*
	* override getDate() to allow getting time too within Date object
	*/
	$.datepicker._base_getDateDatepicker = $.datepicker._getDateDatepicker;
	$.datepicker._getDateDatepicker = function(target, noDefault) {
		var inst = this._getInst(target);
		if (!inst) {
			return;
		}

		var tp_inst = this._get(inst, 'timepicker');

		if (tp_inst) {
			// if it hasn't yet been defined, grab from field
			if(inst.lastVal === undefined){
				this._setDateFromField(inst, noDefault);
			}

			var date = this._getDate(inst);
			if (date && tp_inst._parseTime($(target).val(), tp_inst.timeOnly)) {
				date.setHours(tp_inst.hour, tp_inst.minute, tp_inst.second, tp_inst.millisec);
			}
			return date;
		}
		return this._base_getDateDatepicker(target, noDefault);
	};

	/*
	* override parseDate() because UI 1.8.14 throws an error about "Extra characters"
	* An option in datapicker to ignore extra format characters would be nicer.
	*/
	$.datepicker._base_parseDate = $.datepicker.parseDate;
	$.datepicker.parseDate = function(format, value, settings) {
		var date;
		try {
			date = this._base_parseDate(format, value, settings);
		} catch (err) {
			// Hack!  The error message ends with a colon, a space, and
			// the "extra" characters.  We rely on that instead of
			// attempting to perfectly reproduce the parsing algorithm.
			date = this._base_parseDate(format, value.substring(0,value.length-(err.length-err.indexOf(':')-2)), settings);
			$.datepicker.log("Error parsing the date string: " + err + "\ndate string = " + value + "\ndate format = " + format);
		}
		return date;
	};

	/*
	* override formatDate to set date with time to the input
	*/
	$.datepicker._base_formatDate = $.datepicker._formatDate;
	$.datepicker._formatDate = function(inst, day, month, year) {
		var tp_inst = this._get(inst, 'timepicker');
		if (tp_inst) {
			tp_inst._updateDateTime(inst);
			return tp_inst.$input.val();
		}
		return this._base_formatDate(inst);
	};

	/*
	* override options setter to add time to maxDate(Time) and minDate(Time). MaxDate
	*/
	$.datepicker._base_optionDatepicker = $.datepicker._optionDatepicker;
	$.datepicker._optionDatepicker = function(target, name, value) {
		var inst = this._getInst(target),
	        name_clone;
		if (!inst) {
			return null;
		}

		var tp_inst = this._get(inst, 'timepicker');
		if (tp_inst) {
			var min = null,
				max = null,
				onselect = null,
				overrides = tp_inst._defaults.evnts,
				fns = {},
				prop;
		    if (typeof name == 'string') { // if min/max was set with the string
		        if (name === 'minDate' || name === 'minDateTime') {
		            min = value;
		        } else if (name === 'maxDate' || name === 'maxDateTime') {
		            max = value;
		        } else if (name === 'onSelect') {
		            onselect = value;
		        } else if (overrides.hasOwnProperty(name)) {
		            if (typeof (value) === 'undefined') {
		                return overrides[name];
		            }
		            fns[name] = value;
		            name_clone = {}; //empty results in exiting function after overrides updated
		        }
		    } else if (typeof name == 'object') { //if min/max was set with the JSON
		        if (name.minDate) {
		            min = name.minDate;
		        } else if (name.minDateTime) {
		            min = name.minDateTime;
		        } else if (name.maxDate) {
		            max = name.maxDate;
		        } else if (name.maxDateTime) {
		            max = name.maxDateTime;
		        }
		        for (prop in overrides) {
		            if (overrides.hasOwnProperty(prop) && name[prop]) {
		                fns[prop] = name[prop];
		            }
		        }
		    }
		    for (prop in fns) {
		        if (fns.hasOwnProperty(prop)) {
		            overrides[prop] = fns[prop];
		            if (!name_clone) { name_clone = $.extend({}, name);}
		            delete name_clone[prop];
		        }
		    }
		    if (name_clone && isEmptyObject(name_clone)) { return; }
		    if (min) { //if min was set
		        if (min === 0) {
		            min = new Date();
		        } else {
		            min = new Date(min);
		        }
		        tp_inst._defaults.minDate = min;
		        tp_inst._defaults.minDateTime = min;
		    } else if (max) { //if max was set
		        if (max === 0) {
		            max = new Date();
		        } else {
		            max = new Date(max);
		        }
		        tp_inst._defaults.maxDate = max;
		        tp_inst._defaults.maxDateTime = max;
		    } else if (onselect) {
		        tp_inst._defaults.onSelect = onselect;
		    }
		}
		if (value === undefined) {
			return this._base_optionDatepicker.call($.datepicker, target, name);
		}
		return this._base_optionDatepicker.call($.datepicker, target, name_clone || name, value);
	};
	/*
	* jQuery isEmptyObject does not check hasOwnProperty - if someone has added to the object prototype,
	* it will return false for all objects
	*/
	var isEmptyObject = function(obj) {
		var prop;
		for (prop in obj) {
			if (obj.hasOwnProperty(obj)) {
				return false;
			}
		}
		return true;
	};

	/*
	* jQuery extend now ignores nulls!
	*/
	var extendRemove = function(target, props) {
		$.extend(target, props);
		for (var name in props) {
			if (props[name] === null || props[name] === undefined) {
				target[name] = props[name];
			}
		}
		return target;
	};

	/*
	* Determine by the time format if should use ampm
	* Returns true if should use ampm, false if not
	*/
	var useAmpm = function(timeFormat){
		return (timeFormat.indexOf('t') !== -1 && timeFormat.indexOf('h') !== -1);
	};

	/*
	* Converts 24 hour format into 12 hour
	* Returns 12 hour with leading 0
	*/
	var convert24to12 = function(hour) {
		if (hour > 12) {
			hour = hour - 12;
		}

		if (hour == 0) {
			hour = 12;
		}

		if (hour < 10) {
			hour = "0" + hour;
		}

		return String(hour);
	};

	/*
	* Splits datetime string into date ans time substrings.
	* Throws exception when date can't be parsed
	* Returns [dateString, timeString]
	*/
	var splitDateTime = function(dateFormat, dateTimeString, dateSettings, timeSettings) {
		try {
			// The idea is to get the number separator occurances in datetime and the time format requested (since time has 
			// fewer unknowns, mostly numbers and am/pm). We will use the time pattern to split.
			var separator = timeSettings && timeSettings.separator ? timeSettings.separator : $.timepicker._defaults.separator,
				format = timeSettings && timeSettings.timeFormat ? timeSettings.timeFormat : $.timepicker._defaults.timeFormat,
				timeParts = format.split(separator), // how many occurances of separator may be in our format?
				timePartsLen = timeParts.length,
				allParts = dateTimeString.split(separator),
				allPartsLen = allParts.length;

			if (allPartsLen > 1) {
				return [
						allParts.splice(0,allPartsLen-timePartsLen).join(separator),
						allParts.splice(0,timePartsLen).join(separator)
					];
			}

		} catch (err) {
			$.datepicker.log('Could not split the date from the time. Please check the following datetimepicker options' +
					"\nthrown error: " + err +
					"\ndateTimeString" + dateTimeString +
					"\ndateFormat = " + dateFormat +
					"\nseparator = " + timeSettings.separator +
					"\ntimeFormat = " + timeSettings.timeFormat);

			if (err.indexOf(":") >= 0) {
				// Hack!  The error message ends with a colon, a space, and
				// the "extra" characters.  We rely on that instead of
				// attempting to perfectly reproduce the parsing algorithm.
				var dateStringLength = dateTimeString.length - (err.length - err.indexOf(':') - 2),
					timeString = dateTimeString.substring(dateStringLength);

				return [$.trim(dateTimeString.substring(0, dateStringLength)), $.trim(dateTimeString.substring(dateStringLength))];

			} else {
				throw err;
			}
		}
		return [dateTimeString, ''];
	};

	/*
	* Internal function to parse datetime interval
	* Returns: {date: Date, timeObj: Object}, where
	*   date - parsed date without time (type Date)
	*   timeObj = {hour: , minute: , second: , millisec: } - parsed time. Optional
	*/
	var parseDateTimeInternal = function(dateFormat, timeFormat, dateTimeString, dateSettings, timeSettings) {
		var date;
		var splitRes = splitDateTime(dateFormat, dateTimeString, dateSettings, timeSettings);
		date = $.datepicker._base_parseDate(dateFormat, splitRes[0], dateSettings);
		if (splitRes[1] !== '') {
			var timeString = splitRes[1],
				parsedTime = $.datepicker.parseTime(timeFormat, timeString, timeSettings);

			if (parsedTime === null) {
				throw 'Wrong time format';
			}
			return {
				date: date,
				timeObj: parsedTime
			};
		} else {
			return {
				date: date
			};
		}
	};

	/*
	* Internal function to set timezone_select to the local timezone
	*/
	var selectLocalTimeZone = function(tp_inst, date) {
		if (tp_inst && tp_inst.timezone_select) {
			tp_inst._defaults.useLocalTimezone = true;
			var now = typeof date !== 'undefined' ? date : new Date();
			var tzoffset = $.timepicker.timeZoneOffsetString(now);
			if (tp_inst._defaults.timezoneIso8601) {
				tzoffset = tzoffset.substring(0, 3) + ':' + tzoffset.substring(3);
			}
			tp_inst.timezone_select.val(tzoffset);
		}
	};

	/*
	* Create a Singleton Insance
	*/
	$.timepicker = new Timepicker();

	/**
	 * Get the timezone offset as string from a date object (eg '+0530' for UTC+5.5)
	 * @param  date
	 * @return string
	 */
	$.timepicker.timeZoneOffsetString = function(date) {
		var off = date.getTimezoneOffset() * -1,
			minutes = off % 60,
			hours = (off - minutes) / 60;
		return (off >= 0 ? '+' : '-') + ('0' + (hours * 101).toString()).substr(-2) + ('0' + (minutes * 101).toString()).substr(-2);
	};

	/**
	 * Calls `timepicker()` on the `startTime` and `endTime` elements, and configures them to
	 * enforce date range limits.
	 * n.b. The input value must be correctly formatted (reformatting is not supported)
	 * @param  Element startTime
	 * @param  Element endTime
	 * @param  obj options Options for the timepicker() call
	 * @return jQuery
	 */
	$.timepicker.timeRange = function(startTime, endTime, options) {
		return $.timepicker.handleRange('timepicker', startTime, endTime, options);
	};

	/**
	 * Calls `datetimepicker` on the `startTime` and `endTime` elements, and configures them to
	 * enforce date range limits.
	 * @param  Element startTime
	 * @param  Element endTime
	 * @param  obj options Options for the `timepicker()` call. Also supports `reformat`,
	 *   a boolean value that can be used to reformat the input values to the `dateFormat`.
	 * @param  string method Can be used to specify the type of picker to be added
	 * @return jQuery
	 */
	$.timepicker.dateTimeRange = function(startTime, endTime, options) {
		$.timepicker.dateRange(startTime, endTime, options, 'datetimepicker');
	};

	/**
	 * Calls `method` on the `startTime` and `endTime` elements, and configures them to
	 * enforce date range limits.
	 * @param  Element startTime
	 * @param  Element endTime
	 * @param  obj options Options for the `timepicker()` call. Also supports `reformat`,
	 *   a boolean value that can be used to reformat the input values to the `dateFormat`.
	 * @param  string method Can be used to specify the type of picker to be added
	 * @return jQuery
	 */
	$.timepicker.dateRange = function(startTime, endTime, options, method) {
		method = method || 'datepicker';
		$.timepicker.handleRange(method, startTime, endTime, options);
	};

	/**
	 * Calls `method` on the `startTime` and `endTime` elements, and configures them to
	 * enforce date range limits.
	 * @param  string method Can be used to specify the type of picker to be added
	 * @param  Element startTime
	 * @param  Element endTime
	 * @param  obj options Options for the `timepicker()` call. Also supports `reformat`,
	 *   a boolean value that can be used to reformat the input values to the `dateFormat`.
	 * @return jQuery
	 */
	$.timepicker.handleRange = function(method, startTime, endTime, options) {
		$.fn[method].call(startTime, $.extend({
			onClose: function(dateText, inst) {
				checkDates(this, endTime, dateText);
			},
			onSelect: function(selectedDateTime) {
				selected(this, endTime, 'minDate');
			}
		}, options, options.start));
		$.fn[method].call(endTime, $.extend({
			onClose: function(dateText, inst) {
				checkDates(this, startTime, dateText);
			},
			onSelect: function(selectedDateTime) {
				selected(this, startTime, 'maxDate');
			}
		}, options, options.end));
		// timepicker doesn't provide access to its 'timeFormat' option, 
		// nor could I get datepicker.formatTime() to behave with times, so I
		// have disabled reformatting for timepicker
		if (method != 'timepicker' && options.reformat) {
			$([startTime, endTime]).each(function() {
				var format = $(this)[method].call($(this), 'option', 'dateFormat'),
					date = new Date($(this).val());
				if ($(this).val() && date) {
					$(this).val($.datepicker.formatDate(format, date));
				}
			});
		}
		checkDates(startTime, endTime, startTime.val());

		function checkDates(changed, other, dateText) {
			if (other.val() && (new Date(startTime.val()) > new Date(endTime.val()))) {
				other.val(dateText);
			}
		}
		selected(startTime, endTime, 'minDate');
		selected(endTime, startTime, 'maxDate');

		function selected(changed, other, option) {
			if (!$(changed).val()) {
				return;
			}
			var date = $(changed)[method].call($(changed), 'getDate');
			// timepicker doesn't implement 'getDate' and returns a jQuery
			if (date.getTime) {
				$(other)[method].call($(other), 'option', option, date);
			}
		}
		return $([startTime.get(0), endTime.get(0)]);
	};

	/*
	* Keep up with the version
	*/
	$.timepicker.version = "1.1.0";

})(jQuery);
/**
 * jQuery Geocoding and Places Autocomplete Plugin - V 1.6.1
 *
 * @author Martin Kleppe <kleppe@ubilabs.net>, 2014
 * @author Ubilabs http://ubilabs.net, 2014
 * @license MIT License <http://www.opensource.org/licenses/mit-license.php>
 */

// # $.geocomplete()
// ## jQuery Geocoding and Places Autocomplete Plugin
//
// * https://github.com/ubilabs/geocomplete/
// * by Martin Kleppe <kleppe@ubilabs.net>

(function($, window, document, undefined){

  // ## Options
  // The default options for this plugin.
  //
  // * `map` - Might be a selector, an jQuery object or a DOM element. Default is `false` which shows no map.
  // * `details` - The container that should be populated with data. Defaults to `false` which ignores the setting.
  // * `location` - Location to initialize the map on. Might be an address `string` or an `array` with [latitude, longitude] or a `google.maps.LatLng`object. Default is `false` which shows a blank map.
  // * `bounds` - Whether to snap geocode search to map bounds. Default: `true` if false search globally. Alternatively pass a custom `LatLngBounds object.
  // * `autoselect` - Automatically selects the highlighted item or the first item from the suggestions list on Enter.
  // * `detailsAttribute` - The attribute's name to use as an indicator. Default: `"name"`
  // * `mapOptions` - Options to pass to the `google.maps.Map` constructor. See the full list [here](http://code.google.com/apis/maps/documentation/javascript/reference.html#MapOptions).
  // * `mapOptions.zoom` - The inital zoom level. Default: `14`
  // * `mapOptions.scrollwheel` - Whether to enable the scrollwheel to zoom the map. Default: `false`
  // * `mapOptions.mapTypeId` - The map type. Default: `"roadmap"`
  // * `markerOptions` - The options to pass to the `google.maps.Marker` constructor. See the full list [here](http://code.google.com/apis/maps/documentation/javascript/reference.html#MarkerOptions).
  // * `markerOptions.draggable` - If the marker is draggable. Default: `false`. Set to true to enable dragging.
  // * `markerOptions.disabled` - Do not show marker. Default: `false`. Set to true to disable marker.
  // * `maxZoom` - The maximum zoom level too zoom in after a geocoding response. Default: `16`
  // * `types` - An array containing one or more of the supported types for the places request. Default: `['geocode']` See the full list [here](http://code.google.com/apis/maps/documentation/javascript/places.html#place_search_requests).

  var defaults = {
    bounds: true,
    country: null,
    map: false,
    details: false,
    detailsAttribute: "name",
    autoselect: true,
    location: false,

    mapOptions: {
      zoom: 14,
      scrollwheel: false,
      mapTypeId: "roadmap"
    },

    markerOptions: {
      draggable: false
    },

    maxZoom: 16,
    types: ['geocode'],
    blur: false
  };

  // See: [Geocoding Types](https://developers.google.com/maps/documentation/geocoding/#Types)
  // on Google Developers.
  var componentTypes = ("street_address route intersection political " +
    "country administrative_area_level_1 administrative_area_level_2 " +
    "administrative_area_level_3 colloquial_area locality sublocality " +
    "neighborhood premise subpremise postal_code natural_feature airport " +
    "park point_of_interest post_box street_number floor room " +
    "lat lng viewport location " +
    "formatted_address location_type bounds").split(" ");

  // See: [Places Details Responses](https://developers.google.com/maps/documentation/javascript/places#place_details_responses)
  // on Google Developers.
  var placesDetails = ("id place_id url website vicinity reference name rating " +
    "international_phone_number icon formatted_phone_number").split(" ");

  // The actual plugin constructor.
  function GeoComplete(input, options) {

    this.options = $.extend(true, {}, defaults, options);

    this.input = input;
    this.$input = $(input);

    this._defaults = defaults;
    this._name = 'geocomplete';

    this.init();
  }

  // Initialize all parts of the plugin.
  $.extend(GeoComplete.prototype, {
    init: function(){
      this.initMap();
      this.initMarker();
      this.initGeocoder();
      this.initDetails();
      this.initLocation();
    },

    // Initialize the map but only if the option `map` was set.
    // This will create a `map` within the given container
    // using the provided `mapOptions` or link to the existing map instance.
    initMap: function(){
      if (!this.options.map){ return; }

      if (typeof this.options.map.setCenter == "function"){
        this.map = this.options.map;
        return;
      }

      this.map = new google.maps.Map(
        $(this.options.map)[0],
        this.options.mapOptions
      );

      // add click event listener on the map
      google.maps.event.addListener(
        this.map,
        'click',
        $.proxy(this.mapClicked, this)
      );

      google.maps.event.addListener(
        this.map,
        'zoom_changed',
        $.proxy(this.mapZoomed, this)
      );
    },

    // Add a marker with the provided `markerOptions` but only
    // if the option was set. Additionally it listens for the `dragend` event
    // to notify the plugin about changes.
    initMarker: function(){
      if (!this.map){ return; }
      var options = $.extend(this.options.markerOptions, { map: this.map });

      if (options.disabled){ return; }

      this.marker = new google.maps.Marker(options);

      google.maps.event.addListener(
        this.marker,
        'dragend',
        $.proxy(this.markerDragged, this)
      );
    },

    // Associate the input with the autocompleter and create a geocoder
    // to fall back when the autocompleter does not return a value.
    initGeocoder: function(){

      var options = {
        types: this.options.types,
        bounds: this.options.bounds === true ? null : this.options.bounds,
        componentRestrictions: this.options.componentRestrictions
      };

      if (this.options.country){
        options.componentRestrictions = {country: this.options.country};
      }

      this.autocomplete = new google.maps.places.Autocomplete(
        this.input, options
      );

      this.geocoder = new google.maps.Geocoder();

      // Bind autocomplete to map bounds but only if there is a map
      // and `options.bindToMap` is set to true.
      if (this.map && this.options.bounds === true){
        this.autocomplete.bindTo('bounds', this.map);
      }

      // Watch `place_changed` events on the autocomplete input field.
      google.maps.event.addListener(
        this.autocomplete,
        'place_changed',
        $.proxy(this.placeChanged, this)
      );

      // Prevent parent form from being submitted if user hit enter.
      this.$input.keypress(function(event){
        if (event.keyCode === 13){ return false; }
      });

      // Listen for "geocode" events and trigger find action.
      this.$input.bind("geocode", $.proxy(function(){
        this.find();
      }, this));

      // Trigger find action when input element is blured out.
      // (Usefull for typing partial location and tabing to the next field
      // or clicking somewhere else.)
      if (this.options.blur === true){
        this.$input.blur($.proxy(function(){
          this.find();
        }, this));
      }
    },

    // Prepare a given DOM structure to be populated when we got some data.
    // This will cycle through the list of component types and map the
    // corresponding elements.
    initDetails: function(){
      if (!this.options.details){ return; }

      var $details = $(this.options.details),
        attribute = this.options.detailsAttribute,
        details = {};

      function setDetail(value){
        details[value] = $details.find("[" +  attribute + "=" + value + "]");
      }

      $.each(componentTypes, function(index, key){
        setDetail(key);
        setDetail(key + "_short");
      });

      $.each(placesDetails, function(index, key){
        setDetail(key);
      });

      this.$details = $details;
      this.details = details;
    },

    // Set the initial location of the plugin if the `location` options was set.
    // This method will care about converting the value into the right format.
    initLocation: function() {

      var location = this.options.location, latLng;

      if (!location) { return; }

      if (typeof location == 'string') {
        this.find(location);
        return;
      }

      if (location instanceof Array) {
        latLng = new google.maps.LatLng(location[0], location[1]);
      }

      if (location instanceof google.maps.LatLng){
        latLng = location;
      }

      if (latLng){
        if (this.map){ this.map.setCenter(latLng); }
        if (this.marker){ this.marker.setPosition(latLng); }
      }
    },

    // Look up a given address. If no `address` was specified it uses
    // the current value of the input.
    find: function(address){
      this.geocode({
        address: address || this.$input.val()
      });
    },

    // Requests details about a given location.
    // Additionally it will bias the requests to the provided bounds.
    geocode: function(request){
      if (this.options.bounds && !request.bounds){
        if (this.options.bounds === true){
          request.bounds = this.map && this.map.getBounds();
        } else {
          request.bounds = this.options.bounds;
        }
      }

      if (this.options.country){
        request.region = this.options.country;
      }

      this.geocoder.geocode(request, $.proxy(this.handleGeocode, this));
    },

    // Get the selected result. If no result is selected on the list, then get
    // the first result from the list.
    selectFirstResult: function() {
      //$(".pac-container").hide();

      var selected = '';
      // Check if any result is selected.
      if ($(".pac-item-selected")[0]) {
        selected = '-selected';
      }

      // Get the first suggestion's text.
      var $span1 = $(".pac-container .pac-item" + selected + ":first span:nth-child(2)").text();
      var $span2 = $(".pac-container .pac-item" + selected + ":first span:nth-child(3)").text();

      // Adds the additional information, if available.
      var firstResult = $span1;
      if ($span2) {
        firstResult += " - " + $span2;
      }

      this.$input.val(firstResult);

      return firstResult;
    },

    // Handles the geocode response. If more than one results was found
    // it triggers the "geocode:multiple" events. If there was an error
    // the "geocode:error" event is fired.
    handleGeocode: function(results, status){
      if (status === google.maps.GeocoderStatus.OK) {
        var result = results[0];
        this.$input.val(result.formatted_address);
        this.update(result);

        if (results.length > 1){
          this.trigger("geocode:multiple", results);
        }

      } else {
        this.trigger("geocode:error", status);
      }
    },

    // Triggers a given `event` with optional `arguments` on the input.
    trigger: function(event, argument){
      this.$input.trigger(event, [argument]);
    },

    // Set the map to a new center by passing a `geometry`.
    // If the geometry has a viewport, the map zooms out to fit the bounds.
    // Additionally it updates the marker position.
    center: function(geometry){

      if (geometry.viewport){
        this.map.fitBounds(geometry.viewport);
        if (this.map.getZoom() > this.options.maxZoom){
          this.map.setZoom(this.options.maxZoom);
        }
      } else {
        this.map.setZoom(this.options.maxZoom);
        this.map.setCenter(geometry.location);
      }

      if (this.marker){
        this.marker.setPosition(geometry.location);
        this.marker.setAnimation(this.options.markerOptions.animation);
      }
    },

    // Update the elements based on a single places or geoocoding response
    // and trigger the "geocode:result" event on the input.
    update: function(result){

      if (this.map){
        this.center(result.geometry);
      }

      if (this.$details){
        this.fillDetails(result);
      }

      this.trigger("geocode:result", result);
    },

    // Populate the provided elements with new `result` data.
    // This will lookup all elements that has an attribute with the given
    // component type.
    fillDetails: function(result){

      var data = {},
        geometry = result.geometry,
        viewport = geometry.viewport,
        bounds = geometry.bounds;

      // Create a simplified version of the address components.
      $.each(result.address_components, function(index, object){
        var name = object.types[0];

        $.each(object.types, function(index, name){
          data[name] = object.long_name;
          data[name + "_short"] = object.short_name;
        });
      });

      // Add properties of the places details.
      $.each(placesDetails, function(index, key){
        data[key] = result[key];
      });

      // Add infos about the address and geometry.
      $.extend(data, {
        formatted_address: result.formatted_address,
        location_type: geometry.location_type || "PLACES",
        viewport: viewport,
        bounds: bounds,
        location: geometry.location,
        lat: geometry.location.lat(),
        lng: geometry.location.lng()
      });

      // Set the values for all details.
      $.each(this.details, $.proxy(function(key, $detail){
        var value = data[key];
        this.setDetail($detail, value);
      }, this));

      this.data = data;
    },

    // Assign a given `value` to a single `$element`.
    // If the element is an input, the value is set, otherwise it updates
    // the text content.
    setDetail: function($element, value){

      if (value === undefined){
        value = "";
      } else if (typeof value.toUrlValue == "function"){
        value = value.toUrlValue();
      }

      if ($element.is(":input")){
        $element.val(value);
      } else {
        $element.text(value);
      }
    },

    // Fire the "geocode:dragged" event and pass the new position.
    markerDragged: function(event){
      this.trigger("geocode:dragged", event.latLng);
    },

    mapClicked: function(event) {
        this.trigger("geocode:click", event.latLng);
    },

    mapZoomed: function(event) {
      this.trigger("geocode:zoom", this.map.getZoom());
    },

    // Restore the old position of the marker to the last now location.
    resetMarker: function(){
      this.marker.setPosition(this.data.location);
      this.setDetail(this.details.lat, this.data.location.lat());
      this.setDetail(this.details.lng, this.data.location.lng());
    },

    // Update the plugin after the user has selected an autocomplete entry.
    // If the place has no geometry it passes it to the geocoder.
    placeChanged: function(){
      var place = this.autocomplete.getPlace();

      if (!place || !place.geometry){
        if (this.options.autoselect) {
          // Automatically selects the highlighted item or the first item from the
          // suggestions list.
          var autoSelection = this.selectFirstResult();
          this.find(autoSelection);
        }
      } else {
        // Use the input text if it already gives geometry.
        this.update(place);
      }
    }
  });

  // A plugin wrapper around the constructor.
  // Pass `options` with all settings that are different from the default.
  // The attribute is used to prevent multiple instantiations of the plugin.
  $.fn.geocomplete = function(options) {

    var attribute = 'plugin_geocomplete';

    // If you call `.geocomplete()` with a string as the first parameter
    // it returns the corresponding property or calls the method with the
    // following arguments.
    if (typeof options == "string"){

      var instance = $(this).data(attribute) || $(this).geocomplete().data(attribute),
        prop = instance[options];

      if (typeof prop == "function"){
        prop.apply(instance, Array.prototype.slice.call(arguments, 1));
        return $(this);
      } else {
        if (arguments.length == 2){
          prop = arguments[1];
        }
        return prop;
      }
    } else {
      return this.each(function() {
        // Prevent against multiple instantiations.
        var instance = $.data(this, attribute);
        if (!instance) {
          instance = new GeoComplete( this, options );
          $.data(this, attribute, instance);
        }
      });
    }
  };

})( jQuery, window, document );
/*
* MultiSelect v0.9.11
* Copyright (c) 2012 Louis Cuny
*
* This program is free software. It comes without any warranty, to
* the extent permitted by applicable law. You can redistribute it
* and/or modify it under the terms of the Do What The Fuck You Want
* To Public License, Version 2, as published by Sam Hocevar. See
* http://sam.zoy.org/wtfpl/COPYING for more details.
*/

!function ($) {

  "use strict";


 /* MULTISELECT CLASS DEFINITION
  * ====================== */

  var MultiSelect = function (element, options) {
    this.options = options;
    this.$element = $(element);
    this.$container = $('<div/>', { 'class': "ms-container" });
    this.$selectableContainer = $('<div/>', { 'class': 'ms-selectable' });
    this.$selectionContainer = $('<div/>', { 'class': 'ms-selection' });
    this.$selectableUl = $('<ul/>', { 'class': "ms-list", 'tabindex' : '-1' });
    this.$selectionUl = $('<ul/>', { 'class': "ms-list", 'tabindex' : '-1' });
    this.scrollTo = 0;
    this.elemsSelector = 'li:visible:not(.ms-optgroup-label,.ms-optgroup-container,.'+options.disabledClass+')';
  };

  MultiSelect.prototype = {
    constructor: MultiSelect,

    init: function(){
      var that = this,
          ms = this.$element;

      if (ms.next('.ms-container').length === 0){
        ms.css({ position: 'absolute', left: '-9999px' });
        ms.attr('id', ms.attr('id') ? ms.attr('id') : Math.ceil(Math.random()*1000)+'multiselect');
        this.$container.attr('id', 'ms-'+ms.attr('id'));
        this.$container.addClass(that.options.cssClass);
        ms.find('option').each(function(){
          that.generateLisFromOption(this);
        });

        this.$selectionUl.find('.ms-optgroup-label').hide();

        if (that.options.selectableHeader){
          that.$selectableContainer.append(that.options.selectableHeader);
        }
        that.$selectableContainer.append(that.$selectableUl);
        if (that.options.selectableFooter){
          that.$selectableContainer.append(that.options.selectableFooter);
        }

        if (that.options.selectionHeader){
          that.$selectionContainer.append(that.options.selectionHeader);
        }
        that.$selectionContainer.append(that.$selectionUl);
        if (that.options.selectionFooter){
          that.$selectionContainer.append(that.options.selectionFooter);
        }

        that.$container.append(that.$selectableContainer);
        that.$container.append(that.$selectionContainer);
        ms.after(that.$container);

        that.activeMouse(that.$selectableUl);
        that.activeKeyboard(that.$selectableUl);

        var action = that.options.dblClick ? 'dblclick' : 'click';

        that.$selectableUl.on(action, '.ms-elem-selectable', function(){
          that.select($(this).data('ms-value'));
        });
        that.$selectionUl.on(action, '.ms-elem-selection', function(){
          that.deselect($(this).data('ms-value'));
        });

        that.activeMouse(that.$selectionUl);
        that.activeKeyboard(that.$selectionUl);

        ms.on('focus', function(){
          that.$selectableUl.focus();
        })
      }

      var selectedValues = ms.find('option:selected').map(function(){ return $(this).val(); }).get();
      that.select(selectedValues, 'init');

      if (typeof that.options.afterInit === 'function') {
        that.options.afterInit.call(this, this.$container);
      }
    },

    'generateLisFromOption' : function(option, index, $container){
      var that = this,
          ms = that.$element,
          attributes = "",
          $option = $(option);

      for (var cpt = 0; cpt < option.attributes.length; cpt++){
        var attr = option.attributes[cpt];

        if(attr.name !== 'value' && attr.name !== 'disabled'){
          attributes += attr.name+'="'+attr.value+'" ';
        }
      }
      var selectableLi = $('<li '+attributes+'><span>'+that.escapeHTML($option.text())+'</span></li>'),
          selectedLi = selectableLi.clone(),
          value = $option.val(),
          elementId = that.sanitize(value);

      selectableLi
        .data('ms-value', value)
        .addClass('ms-elem-selectable')
        .attr('id', elementId+'-selectable');

      selectedLi
        .data('ms-value', value)
        .addClass('ms-elem-selection')
        .attr('id', elementId+'-selection')
        .hide();

      if ($option.prop('disabled') || ms.prop('disabled')){
        selectedLi.addClass(that.options.disabledClass);
        selectableLi.addClass(that.options.disabledClass);
      }

      var $optgroup = $option.parent('optgroup');

      if ($optgroup.length > 0){
        var optgroupLabel = $optgroup.attr('label'),
            optgroupId = that.sanitize(optgroupLabel),
            $selectableOptgroup = that.$selectableUl.find('#optgroup-selectable-'+optgroupId),
            $selectionOptgroup = that.$selectionUl.find('#optgroup-selection-'+optgroupId);

        if ($selectableOptgroup.length === 0){
          var optgroupContainerTpl = '<li class="ms-optgroup-container"></li>',
              optgroupTpl = '<ul class="ms-optgroup"><li class="ms-optgroup-label"><span>'+optgroupLabel+'</span></li></ul>';

          $selectableOptgroup = $(optgroupContainerTpl);
          $selectionOptgroup = $(optgroupContainerTpl);
          $selectableOptgroup.attr('id', 'optgroup-selectable-'+optgroupId);
          $selectionOptgroup.attr('id', 'optgroup-selection-'+optgroupId);
          $selectableOptgroup.append($(optgroupTpl));
          $selectionOptgroup.append($(optgroupTpl));
          if (that.options.selectableOptgroup){
            $selectableOptgroup.find('.ms-optgroup-label').on('click', function(){
              var values = $optgroup.children(':not(:selected, :disabled)').map(function(){ return $(this).val() }).get();
              that.select(values);
            });
            $selectionOptgroup.find('.ms-optgroup-label').on('click', function(){
              var values = $optgroup.children(':selected:not(:disabled)').map(function(){ return $(this).val() }).get();
              that.deselect(values);
            });
          }
          that.$selectableUl.append($selectableOptgroup);
          that.$selectionUl.append($selectionOptgroup);
        }
        index = index == undefined ? $selectableOptgroup.find('ul').children().length : index + 1;
        selectableLi.insertAt(index, $selectableOptgroup.children());
        selectedLi.insertAt(index, $selectionOptgroup.children());
      } else {
        index = index == undefined ? that.$selectableUl.children().length : index;

        selectableLi.insertAt(index, that.$selectableUl);
        selectedLi.insertAt(index, that.$selectionUl);
      }
    },

    'addOption' : function(options){
      var that = this;

      if (options.value) options = [options];
      $.each(options, function(index, option){
        if (option.value && that.$element.find("option[value='"+option.value+"']").length === 0){
          var $option = $('<option value="'+option.value+'">'+option.text+'</option>'),
              index = parseInt((typeof option.index === 'undefined' ? that.$element.children().length : option.index)),
              $container = option.nested == undefined ? that.$element : $("optgroup[label='"+option.nested+"']")

          $option.insertAt(index, $container);
          that.generateLisFromOption($option.get(0), index, option.nested);
        }
      })
    },

    'escapeHTML' : function(text){
      return $("<div>").text(text).html();
    },

    'activeKeyboard' : function($list){
      var that = this;

      $list.on('focus', function(){
        $(this).addClass('ms-focus');
      })
      .on('blur', function(){
        $(this).removeClass('ms-focus');
      })
      .on('keydown', function(e){
        switch (e.which) {
          case 40:
          case 38:
            e.preventDefault();
            e.stopPropagation();
            that.moveHighlight($(this), (e.which === 38) ? -1 : 1);
            return;
          case 37:
          case 39:
            e.preventDefault();
            e.stopPropagation();
            that.switchList($list);
            return;
          case 9:
            if(that.$element.is('[tabindex]')){
              e.preventDefault();
              var tabindex = parseInt(that.$element.attr('tabindex'), 10);
              tabindex = (e.shiftKey) ? tabindex-1 : tabindex+1;
              $('[tabindex="'+(tabindex)+'"]').focus();
              return;
            }else{
              if(e.shiftKey){
                that.$element.trigger('focus');
              }
            }
        }
        if($.inArray(e.which, that.options.keySelect) > -1){
          e.preventDefault();
          e.stopPropagation();
          that.selectHighlighted($list);
          return;
        }
      });
    },

    'moveHighlight': function($list, direction){
      var $elems = $list.find(this.elemsSelector),
          $currElem = $elems.filter('.ms-hover'),
          $nextElem = null,
          elemHeight = $elems.first().outerHeight(),
          containerHeight = $list.height(),
          containerSelector = '#'+this.$container.prop('id');

      $elems.removeClass('ms-hover');
      if (direction === 1){ // DOWN

        $nextElem = $currElem.nextAll(this.elemsSelector).first();
        if ($nextElem.length === 0){
          var $optgroupUl = $currElem.parent();

          if ($optgroupUl.hasClass('ms-optgroup')){
            var $optgroupLi = $optgroupUl.parent(),
                $nextOptgroupLi = $optgroupLi.next(':visible');

            if ($nextOptgroupLi.length > 0){
              $nextElem = $nextOptgroupLi.find(this.elemsSelector).first();
            } else {
              $nextElem = $elems.first();
            }
          } else {
            $nextElem = $elems.first();
          }
        }
      } else if (direction === -1){ // UP

        $nextElem = $currElem.prevAll(this.elemsSelector).first();
        if ($nextElem.length === 0){
          var $optgroupUl = $currElem.parent();

          if ($optgroupUl.hasClass('ms-optgroup')){
            var $optgroupLi = $optgroupUl.parent(),
                $prevOptgroupLi = $optgroupLi.prev(':visible');

            if ($prevOptgroupLi.length > 0){
              $nextElem = $prevOptgroupLi.find(this.elemsSelector).last();
            } else {
              $nextElem = $elems.last();
            }
          } else {
            $nextElem = $elems.last();
          }
        }
      }
      if ($nextElem.length > 0){
        $nextElem.addClass('ms-hover');
        var scrollTo = $list.scrollTop() + $nextElem.position().top - 
                       containerHeight / 2 + elemHeight / 2;

        $list.scrollTop(scrollTo);
      }
    },

    'selectHighlighted' : function($list){
      var $elems = $list.find(this.elemsSelector),
          $highlightedElem = $elems.filter('.ms-hover').first();

      if ($highlightedElem.length > 0){
        if ($list.parent().hasClass('ms-selectable')){
          this.select($highlightedElem.data('ms-value'));
        } else {
          this.deselect($highlightedElem.data('ms-value'));
        }
        $elems.removeClass('ms-hover');
      }
    },

    'switchList' : function($list){
      $list.blur();
      this.$container.find(this.elemsSelector).removeClass('ms-hover');
      if ($list.parent().hasClass('ms-selectable')){
        this.$selectionUl.focus();
      } else {
        this.$selectableUl.focus();
      }
    },

    'activeMouse' : function($list){
      var that = this;

      $('body').on('mouseenter', that.elemsSelector, function(){
        $(this).parents('.ms-container').find(that.elemsSelector).removeClass('ms-hover');
        $(this).addClass('ms-hover');
      });

      $('body').on('mouseleave', that.elemsSelector, function () {
          $(this).parents('.ms-container').find(that.elemsSelector).removeClass('ms-hover');;
      });
    },

    'refresh' : function() {
      this.destroy();
      this.$element.multiSelect(this.options);
    },

    'destroy' : function(){
      $("#ms-"+this.$element.attr("id")).remove();
      this.$element.css('position', '').css('left', '')
      this.$element.removeData('multiselect');
    },

    'select' : function(value, method){
      if (typeof value === 'string'){ value = [value]; }

      var that = this,
          ms = this.$element,
          msIds = $.map(value, function(val){ return(that.sanitize(val)); }),
          selectables = this.$selectableUl.find('#' + msIds.join('-selectable, #')+'-selectable').filter(':not(.'+that.options.disabledClass+')'),
          selections = this.$selectionUl.find('#' + msIds.join('-selection, #') + '-selection').filter(':not(.'+that.options.disabledClass+')'),
          options = ms.find('option:not(:disabled)').filter(function(){ return($.inArray(this.value, value) > -1); });

      if (method === 'init'){
        selectables = this.$selectableUl.find('#' + msIds.join('-selectable, #')+'-selectable'),
        selections = this.$selectionUl.find('#' + msIds.join('-selection, #') + '-selection');
      }

      if (selectables.length > 0){
        selectables.addClass('ms-selected').hide();
        selections.addClass('ms-selected').show();

        options.prop('selected', true);

        that.$container.find(that.elemsSelector).removeClass('ms-hover');

        var selectableOptgroups = that.$selectableUl.children('.ms-optgroup-container');
        if (selectableOptgroups.length > 0){
          selectableOptgroups.each(function(){
            var selectablesLi = $(this).find('.ms-elem-selectable');
            if (selectablesLi.length === selectablesLi.filter('.ms-selected').length){
              $(this).find('.ms-optgroup-label').hide();
            }
          });

          var selectionOptgroups = that.$selectionUl.children('.ms-optgroup-container');
          selectionOptgroups.each(function(){
            var selectionsLi = $(this).find('.ms-elem-selection');
            if (selectionsLi.filter('.ms-selected').length > 0){
              $(this).find('.ms-optgroup-label').show();
            }
          });
        } else {
          if (that.options.keepOrder && method !== 'init'){
            var selectionLiLast = that.$selectionUl.find('.ms-selected');
            if((selectionLiLast.length > 1) && (selectionLiLast.last().get(0) != selections.get(0))) {
              selections.insertAfter(selectionLiLast.last());
            }
          }
        }
        if (method !== 'init'){
          ms.trigger('change');
          if (typeof that.options.afterSelect === 'function') {
            that.options.afterSelect.call(this, value);
          }
        }
      }
    },

    'deselect' : function(value){
      if (typeof value === 'string'){ value = [value]; }

      var that = this,
          ms = this.$element,
          msIds = $.map(value, function(val){ return(that.sanitize(val)); }),
          selectables = this.$selectableUl.find('#' + msIds.join('-selectable, #')+'-selectable'),
          selections = this.$selectionUl.find('#' + msIds.join('-selection, #')+'-selection').filter('.ms-selected').filter(':not(.'+that.options.disabledClass+')'),
          options = ms.find('option').filter(function(){ return($.inArray(this.value, value) > -1); });

      if (selections.length > 0){
        selectables.removeClass('ms-selected').show();
        selections.removeClass('ms-selected').hide();
        options.prop('selected', false);

        that.$container.find(that.elemsSelector).removeClass('ms-hover');

        var selectableOptgroups = that.$selectableUl.children('.ms-optgroup-container');
        if (selectableOptgroups.length > 0){
          selectableOptgroups.each(function(){
            var selectablesLi = $(this).find('.ms-elem-selectable');
            if (selectablesLi.filter(':not(.ms-selected)').length > 0){
              $(this).find('.ms-optgroup-label').show();
            }
          });

          var selectionOptgroups = that.$selectionUl.children('.ms-optgroup-container');
          selectionOptgroups.each(function(){
            var selectionsLi = $(this).find('.ms-elem-selection');
            if (selectionsLi.filter('.ms-selected').length === 0){
              $(this).find('.ms-optgroup-label').hide();
            }
          });
        }
        ms.trigger('change');
        if (typeof that.options.afterDeselect === 'function') {
          that.options.afterDeselect.call(this, value);
        }
      }
    },

    'select_all' : function(){
      var ms = this.$element,
          values = ms.val();

      ms.find('option:not(":disabled")').prop('selected', true);
      this.$selectableUl.find('.ms-elem-selectable').filter(':not(.'+this.options.disabledClass+')').addClass('ms-selected').hide();
      this.$selectionUl.find('.ms-optgroup-label').show();
      this.$selectableUl.find('.ms-optgroup-label').hide();
      this.$selectionUl.find('.ms-elem-selection').filter(':not(.'+this.options.disabledClass+')').addClass('ms-selected').show();
      this.$selectionUl.focus();
      ms.trigger('change');
      if (typeof this.options.afterSelect === 'function') {
        var selectedValues = $.grep(ms.val(), function(item){
          return $.inArray(item, values) < 0;
        });
        this.options.afterSelect.call(this, selectedValues);
      }
    },

    'deselect_all' : function(){
      var ms = this.$element,
          values = ms.val();

      ms.find('option').prop('selected', false);
      this.$selectableUl.find('.ms-elem-selectable').removeClass('ms-selected').show();
      this.$selectionUl.find('.ms-optgroup-label').hide();
      this.$selectableUl.find('.ms-optgroup-label').show();
      this.$selectionUl.find('.ms-elem-selection').removeClass('ms-selected').hide();
      this.$selectableUl.focus();
      ms.trigger('change');
      if (typeof this.options.afterDeselect === 'function') {
        this.options.afterDeselect.call(this, values);
      }
    },

    sanitize: function(value){
      var hash = 0, i, character;
      if (value.length == 0) return hash;
      var ls = 0;
      for (i = 0, ls = value.length; i < ls; i++) {
        character  = value.charCodeAt(i);
        hash  = ((hash<<5)-hash)+character;
        hash |= 0; // Convert to 32bit integer
      }
      return hash;
    }
  };

  /* MULTISELECT PLUGIN DEFINITION
   * ======================= */

  $.fn.multiSelect = function () {
    var option = arguments[0],
        args = arguments;

    return this.each(function () {
      var $this = $(this),
          data = $this.data('multiselect'),
          options = $.extend({}, $.fn.multiSelect.defaults, $this.data(), typeof option === 'object' && option);

      if (!data){ $this.data('multiselect', (data = new MultiSelect(this, options))); }

      if (typeof option === 'string'){
        data[option](args[1]);
      } else {
        data.init();
      }
    });
  };

  $.fn.multiSelect.defaults = {
    keySelect: [32],
    selectableOptgroup: false,
    disabledClass : 'disabled',
    dblClick : false,
    keepOrder: false,
    cssClass: ''
  };

  $.fn.multiSelect.Constructor = MultiSelect;

  $.fn.insertAt = function(index, $parent) {
    return this.each(function() {
      if (index === 0) {
        $parent.prepend(this);
      } else {
        $parent.children().eq(index - 1).after(this);
      }
    });
}

}(window.jQuery);

//! moment.js
//! version : 2.7.0
//! authors : Tim Wood, Iskren Chernev, Moment.js contributors
//! license : MIT
//! momentjs.com
(function(a){function b(a,b,c){switch(arguments.length){case 2:return null!=a?a:b;case 3:return null!=a?a:null!=b?b:c;default:throw new Error("Implement me")}}function c(){return{empty:!1,unusedTokens:[],unusedInput:[],overflow:-2,charsLeftOver:0,nullInput:!1,invalidMonth:null,invalidFormat:!1,userInvalidated:!1,iso:!1}}function d(a,b){function c(){mb.suppressDeprecationWarnings===!1&&"undefined"!=typeof console&&console.warn&&console.warn("Deprecation warning: "+a)}var d=!0;return j(function(){return d&&(c(),d=!1),b.apply(this,arguments)},b)}function e(a,b){return function(c){return m(a.call(this,c),b)}}function f(a,b){return function(c){return this.lang().ordinal(a.call(this,c),b)}}function g(){}function h(a){z(a),j(this,a)}function i(a){var b=s(a),c=b.year||0,d=b.quarter||0,e=b.month||0,f=b.week||0,g=b.day||0,h=b.hour||0,i=b.minute||0,j=b.second||0,k=b.millisecond||0;this._milliseconds=+k+1e3*j+6e4*i+36e5*h,this._days=+g+7*f,this._months=+e+3*d+12*c,this._data={},this._bubble()}function j(a,b){for(var c in b)b.hasOwnProperty(c)&&(a[c]=b[c]);return b.hasOwnProperty("toString")&&(a.toString=b.toString),b.hasOwnProperty("valueOf")&&(a.valueOf=b.valueOf),a}function k(a){var b,c={};for(b in a)a.hasOwnProperty(b)&&Ab.hasOwnProperty(b)&&(c[b]=a[b]);return c}function l(a){return 0>a?Math.ceil(a):Math.floor(a)}function m(a,b,c){for(var d=""+Math.abs(a),e=a>=0;d.length<b;)d="0"+d;return(e?c?"+":"":"-")+d}function n(a,b,c,d){var e=b._milliseconds,f=b._days,g=b._months;d=null==d?!0:d,e&&a._d.setTime(+a._d+e*c),f&&hb(a,"Date",gb(a,"Date")+f*c),g&&fb(a,gb(a,"Month")+g*c),d&&mb.updateOffset(a,f||g)}function o(a){return"[object Array]"===Object.prototype.toString.call(a)}function p(a){return"[object Date]"===Object.prototype.toString.call(a)||a instanceof Date}function q(a,b,c){var d,e=Math.min(a.length,b.length),f=Math.abs(a.length-b.length),g=0;for(d=0;e>d;d++)(c&&a[d]!==b[d]||!c&&u(a[d])!==u(b[d]))&&g++;return g+f}function r(a){if(a){var b=a.toLowerCase().replace(/(.)s$/,"$1");a=bc[a]||cc[b]||b}return a}function s(a){var b,c,d={};for(c in a)a.hasOwnProperty(c)&&(b=r(c),b&&(d[b]=a[c]));return d}function t(b){var c,d;if(0===b.indexOf("week"))c=7,d="day";else{if(0!==b.indexOf("month"))return;c=12,d="month"}mb[b]=function(e,f){var g,h,i=mb.fn._lang[b],j=[];if("number"==typeof e&&(f=e,e=a),h=function(a){var b=mb().utc().set(d,a);return i.call(mb.fn._lang,b,e||"")},null!=f)return h(f);for(g=0;c>g;g++)j.push(h(g));return j}}function u(a){var b=+a,c=0;return 0!==b&&isFinite(b)&&(c=b>=0?Math.floor(b):Math.ceil(b)),c}function v(a,b){return new Date(Date.UTC(a,b+1,0)).getUTCDate()}function w(a,b,c){return bb(mb([a,11,31+b-c]),b,c).week}function x(a){return y(a)?366:365}function y(a){return a%4===0&&a%100!==0||a%400===0}function z(a){var b;a._a&&-2===a._pf.overflow&&(b=a._a[tb]<0||a._a[tb]>11?tb:a._a[ub]<1||a._a[ub]>v(a._a[sb],a._a[tb])?ub:a._a[vb]<0||a._a[vb]>23?vb:a._a[wb]<0||a._a[wb]>59?wb:a._a[xb]<0||a._a[xb]>59?xb:a._a[yb]<0||a._a[yb]>999?yb:-1,a._pf._overflowDayOfYear&&(sb>b||b>ub)&&(b=ub),a._pf.overflow=b)}function A(a){return null==a._isValid&&(a._isValid=!isNaN(a._d.getTime())&&a._pf.overflow<0&&!a._pf.empty&&!a._pf.invalidMonth&&!a._pf.nullInput&&!a._pf.invalidFormat&&!a._pf.userInvalidated,a._strict&&(a._isValid=a._isValid&&0===a._pf.charsLeftOver&&0===a._pf.unusedTokens.length)),a._isValid}function B(a){return a?a.toLowerCase().replace("_","-"):a}function C(a,b){return b._isUTC?mb(a).zone(b._offset||0):mb(a).local()}function D(a,b){return b.abbr=a,zb[a]||(zb[a]=new g),zb[a].set(b),zb[a]}function E(a){delete zb[a]}function F(a){var b,c,d,e,f=0,g=function(a){if(!zb[a]&&Bb)try{require("./lang/"+a)}catch(b){}return zb[a]};if(!a)return mb.fn._lang;if(!o(a)){if(c=g(a))return c;a=[a]}for(;f<a.length;){for(e=B(a[f]).split("-"),b=e.length,d=B(a[f+1]),d=d?d.split("-"):null;b>0;){if(c=g(e.slice(0,b).join("-")))return c;if(d&&d.length>=b&&q(e,d,!0)>=b-1)break;b--}f++}return mb.fn._lang}function G(a){return a.match(/\[[\s\S]/)?a.replace(/^\[|\]$/g,""):a.replace(/\\/g,"")}function H(a){var b,c,d=a.match(Fb);for(b=0,c=d.length;c>b;b++)d[b]=hc[d[b]]?hc[d[b]]:G(d[b]);return function(e){var f="";for(b=0;c>b;b++)f+=d[b]instanceof Function?d[b].call(e,a):d[b];return f}}function I(a,b){return a.isValid()?(b=J(b,a.lang()),dc[b]||(dc[b]=H(b)),dc[b](a)):a.lang().invalidDate()}function J(a,b){function c(a){return b.longDateFormat(a)||a}var d=5;for(Gb.lastIndex=0;d>=0&&Gb.test(a);)a=a.replace(Gb,c),Gb.lastIndex=0,d-=1;return a}function K(a,b){var c,d=b._strict;switch(a){case"Q":return Rb;case"DDDD":return Tb;case"YYYY":case"GGGG":case"gggg":return d?Ub:Jb;case"Y":case"G":case"g":return Wb;case"YYYYYY":case"YYYYY":case"GGGGG":case"ggggg":return d?Vb:Kb;case"S":if(d)return Rb;case"SS":if(d)return Sb;case"SSS":if(d)return Tb;case"DDD":return Ib;case"MMM":case"MMMM":case"dd":case"ddd":case"dddd":return Mb;case"a":case"A":return F(b._l)._meridiemParse;case"X":return Pb;case"Z":case"ZZ":return Nb;case"T":return Ob;case"SSSS":return Lb;case"MM":case"DD":case"YY":case"GG":case"gg":case"HH":case"hh":case"mm":case"ss":case"ww":case"WW":return d?Sb:Hb;case"M":case"D":case"d":case"H":case"h":case"m":case"s":case"w":case"W":case"e":case"E":return Hb;case"Do":return Qb;default:return c=new RegExp(T(S(a.replace("\\","")),"i"))}}function L(a){a=a||"";var b=a.match(Nb)||[],c=b[b.length-1]||[],d=(c+"").match(_b)||["-",0,0],e=+(60*d[1])+u(d[2]);return"+"===d[0]?-e:e}function M(a,b,c){var d,e=c._a;switch(a){case"Q":null!=b&&(e[tb]=3*(u(b)-1));break;case"M":case"MM":null!=b&&(e[tb]=u(b)-1);break;case"MMM":case"MMMM":d=F(c._l).monthsParse(b),null!=d?e[tb]=d:c._pf.invalidMonth=b;break;case"D":case"DD":null!=b&&(e[ub]=u(b));break;case"Do":null!=b&&(e[ub]=u(parseInt(b,10)));break;case"DDD":case"DDDD":null!=b&&(c._dayOfYear=u(b));break;case"YY":e[sb]=mb.parseTwoDigitYear(b);break;case"YYYY":case"YYYYY":case"YYYYYY":e[sb]=u(b);break;case"a":case"A":c._isPm=F(c._l).isPM(b);break;case"H":case"HH":case"h":case"hh":e[vb]=u(b);break;case"m":case"mm":e[wb]=u(b);break;case"s":case"ss":e[xb]=u(b);break;case"S":case"SS":case"SSS":case"SSSS":e[yb]=u(1e3*("0."+b));break;case"X":c._d=new Date(1e3*parseFloat(b));break;case"Z":case"ZZ":c._useUTC=!0,c._tzm=L(b);break;case"dd":case"ddd":case"dddd":d=F(c._l).weekdaysParse(b),null!=d?(c._w=c._w||{},c._w.d=d):c._pf.invalidWeekday=b;break;case"w":case"ww":case"W":case"WW":case"d":case"e":case"E":a=a.substr(0,1);case"gggg":case"GGGG":case"GGGGG":a=a.substr(0,2),b&&(c._w=c._w||{},c._w[a]=u(b));break;case"gg":case"GG":c._w=c._w||{},c._w[a]=mb.parseTwoDigitYear(b)}}function N(a){var c,d,e,f,g,h,i,j;c=a._w,null!=c.GG||null!=c.W||null!=c.E?(g=1,h=4,d=b(c.GG,a._a[sb],bb(mb(),1,4).year),e=b(c.W,1),f=b(c.E,1)):(j=F(a._l),g=j._week.dow,h=j._week.doy,d=b(c.gg,a._a[sb],bb(mb(),g,h).year),e=b(c.w,1),null!=c.d?(f=c.d,g>f&&++e):f=null!=c.e?c.e+g:g),i=cb(d,e,f,h,g),a._a[sb]=i.year,a._dayOfYear=i.dayOfYear}function O(a){var c,d,e,f,g=[];if(!a._d){for(e=Q(a),a._w&&null==a._a[ub]&&null==a._a[tb]&&N(a),a._dayOfYear&&(f=b(a._a[sb],e[sb]),a._dayOfYear>x(f)&&(a._pf._overflowDayOfYear=!0),d=Z(f,0,a._dayOfYear),a._a[tb]=d.getUTCMonth(),a._a[ub]=d.getUTCDate()),c=0;3>c&&null==a._a[c];++c)a._a[c]=g[c]=e[c];for(;7>c;c++)a._a[c]=g[c]=null==a._a[c]?2===c?1:0:a._a[c];a._d=(a._useUTC?Z:Y).apply(null,g),null!=a._tzm&&a._d.setUTCMinutes(a._d.getUTCMinutes()+a._tzm)}}function P(a){var b;a._d||(b=s(a._i),a._a=[b.year,b.month,b.day,b.hour,b.minute,b.second,b.millisecond],O(a))}function Q(a){var b=new Date;return a._useUTC?[b.getUTCFullYear(),b.getUTCMonth(),b.getUTCDate()]:[b.getFullYear(),b.getMonth(),b.getDate()]}function R(a){if(a._f===mb.ISO_8601)return void V(a);a._a=[],a._pf.empty=!0;var b,c,d,e,f,g=F(a._l),h=""+a._i,i=h.length,j=0;for(d=J(a._f,g).match(Fb)||[],b=0;b<d.length;b++)e=d[b],c=(h.match(K(e,a))||[])[0],c&&(f=h.substr(0,h.indexOf(c)),f.length>0&&a._pf.unusedInput.push(f),h=h.slice(h.indexOf(c)+c.length),j+=c.length),hc[e]?(c?a._pf.empty=!1:a._pf.unusedTokens.push(e),M(e,c,a)):a._strict&&!c&&a._pf.unusedTokens.push(e);a._pf.charsLeftOver=i-j,h.length>0&&a._pf.unusedInput.push(h),a._isPm&&a._a[vb]<12&&(a._a[vb]+=12),a._isPm===!1&&12===a._a[vb]&&(a._a[vb]=0),O(a),z(a)}function S(a){return a.replace(/\\(\[)|\\(\])|\[([^\]\[]*)\]|\\(.)/g,function(a,b,c,d,e){return b||c||d||e})}function T(a){return a.replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&")}function U(a){var b,d,e,f,g;if(0===a._f.length)return a._pf.invalidFormat=!0,void(a._d=new Date(0/0));for(f=0;f<a._f.length;f++)g=0,b=j({},a),b._pf=c(),b._f=a._f[f],R(b),A(b)&&(g+=b._pf.charsLeftOver,g+=10*b._pf.unusedTokens.length,b._pf.score=g,(null==e||e>g)&&(e=g,d=b));j(a,d||b)}function V(a){var b,c,d=a._i,e=Xb.exec(d);if(e){for(a._pf.iso=!0,b=0,c=Zb.length;c>b;b++)if(Zb[b][1].exec(d)){a._f=Zb[b][0]+(e[6]||" ");break}for(b=0,c=$b.length;c>b;b++)if($b[b][1].exec(d)){a._f+=$b[b][0];break}d.match(Nb)&&(a._f+="Z"),R(a)}else a._isValid=!1}function W(a){V(a),a._isValid===!1&&(delete a._isValid,mb.createFromInputFallback(a))}function X(b){var c=b._i,d=Cb.exec(c);c===a?b._d=new Date:d?b._d=new Date(+d[1]):"string"==typeof c?W(b):o(c)?(b._a=c.slice(0),O(b)):p(c)?b._d=new Date(+c):"object"==typeof c?P(b):"number"==typeof c?b._d=new Date(c):mb.createFromInputFallback(b)}function Y(a,b,c,d,e,f,g){var h=new Date(a,b,c,d,e,f,g);return 1970>a&&h.setFullYear(a),h}function Z(a){var b=new Date(Date.UTC.apply(null,arguments));return 1970>a&&b.setUTCFullYear(a),b}function $(a,b){if("string"==typeof a)if(isNaN(a)){if(a=b.weekdaysParse(a),"number"!=typeof a)return null}else a=parseInt(a,10);return a}function _(a,b,c,d,e){return e.relativeTime(b||1,!!c,a,d)}function ab(a,b,c){var d=rb(Math.abs(a)/1e3),e=rb(d/60),f=rb(e/60),g=rb(f/24),h=rb(g/365),i=d<ec.s&&["s",d]||1===e&&["m"]||e<ec.m&&["mm",e]||1===f&&["h"]||f<ec.h&&["hh",f]||1===g&&["d"]||g<=ec.dd&&["dd",g]||g<=ec.dm&&["M"]||g<ec.dy&&["MM",rb(g/30)]||1===h&&["y"]||["yy",h];return i[2]=b,i[3]=a>0,i[4]=c,_.apply({},i)}function bb(a,b,c){var d,e=c-b,f=c-a.day();return f>e&&(f-=7),e-7>f&&(f+=7),d=mb(a).add("d",f),{week:Math.ceil(d.dayOfYear()/7),year:d.year()}}function cb(a,b,c,d,e){var f,g,h=Z(a,0,1).getUTCDay();return h=0===h?7:h,c=null!=c?c:e,f=e-h+(h>d?7:0)-(e>h?7:0),g=7*(b-1)+(c-e)+f+1,{year:g>0?a:a-1,dayOfYear:g>0?g:x(a-1)+g}}function db(b){var c=b._i,d=b._f;return null===c||d===a&&""===c?mb.invalid({nullInput:!0}):("string"==typeof c&&(b._i=c=F().preparse(c)),mb.isMoment(c)?(b=k(c),b._d=new Date(+c._d)):d?o(d)?U(b):R(b):X(b),new h(b))}function eb(a,b){var c,d;if(1===b.length&&o(b[0])&&(b=b[0]),!b.length)return mb();for(c=b[0],d=1;d<b.length;++d)b[d][a](c)&&(c=b[d]);return c}function fb(a,b){var c;return"string"==typeof b&&(b=a.lang().monthsParse(b),"number"!=typeof b)?a:(c=Math.min(a.date(),v(a.year(),b)),a._d["set"+(a._isUTC?"UTC":"")+"Month"](b,c),a)}function gb(a,b){return a._d["get"+(a._isUTC?"UTC":"")+b]()}function hb(a,b,c){return"Month"===b?fb(a,c):a._d["set"+(a._isUTC?"UTC":"")+b](c)}function ib(a,b){return function(c){return null!=c?(hb(this,a,c),mb.updateOffset(this,b),this):gb(this,a)}}function jb(a){mb.duration.fn[a]=function(){return this._data[a]}}function kb(a,b){mb.duration.fn["as"+a]=function(){return+this/b}}function lb(a){"undefined"==typeof ender&&(nb=qb.moment,qb.moment=a?d("Accessing Moment through the global scope is deprecated, and will be removed in an upcoming release.",mb):mb)}for(var mb,nb,ob,pb="2.7.0",qb="undefined"!=typeof global?global:this,rb=Math.round,sb=0,tb=1,ub=2,vb=3,wb=4,xb=5,yb=6,zb={},Ab={_isAMomentObject:null,_i:null,_f:null,_l:null,_strict:null,_tzm:null,_isUTC:null,_offset:null,_pf:null,_lang:null},Bb="undefined"!=typeof module&&module.exports,Cb=/^\/?Date\((\-?\d+)/i,Db=/(\-)?(?:(\d*)\.)?(\d+)\:(\d+)(?:\:(\d+)\.?(\d{3})?)?/,Eb=/^(-)?P(?:(?:([0-9,.]*)Y)?(?:([0-9,.]*)M)?(?:([0-9,.]*)D)?(?:T(?:([0-9,.]*)H)?(?:([0-9,.]*)M)?(?:([0-9,.]*)S)?)?|([0-9,.]*)W)$/,Fb=/(\[[^\[]*\])|(\\)?(Mo|MM?M?M?|Do|DDDo|DD?D?D?|ddd?d?|do?|w[o|w]?|W[o|W]?|Q|YYYYYY|YYYYY|YYYY|YY|gg(ggg?)?|GG(GGG?)?|e|E|a|A|hh?|HH?|mm?|ss?|S{1,4}|X|zz?|ZZ?|.)/g,Gb=/(\[[^\[]*\])|(\\)?(LT|LL?L?L?|l{1,4})/g,Hb=/\d\d?/,Ib=/\d{1,3}/,Jb=/\d{1,4}/,Kb=/[+\-]?\d{1,6}/,Lb=/\d+/,Mb=/[0-9]*['a-z\u00A0-\u05FF\u0700-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+|[\u0600-\u06FF\/]+(\s*?[\u0600-\u06FF]+){1,2}/i,Nb=/Z|[\+\-]\d\d:?\d\d/gi,Ob=/T/i,Pb=/[\+\-]?\d+(\.\d{1,3})?/,Qb=/\d{1,2}/,Rb=/\d/,Sb=/\d\d/,Tb=/\d{3}/,Ub=/\d{4}/,Vb=/[+-]?\d{6}/,Wb=/[+-]?\d+/,Xb=/^\s*(?:[+-]\d{6}|\d{4})-(?:(\d\d-\d\d)|(W\d\d$)|(W\d\d-\d)|(\d\d\d))((T| )(\d\d(:\d\d(:\d\d(\.\d+)?)?)?)?([\+\-]\d\d(?::?\d\d)?|\s*Z)?)?$/,Yb="YYYY-MM-DDTHH:mm:ssZ",Zb=[["YYYYYY-MM-DD",/[+-]\d{6}-\d{2}-\d{2}/],["YYYY-MM-DD",/\d{4}-\d{2}-\d{2}/],["GGGG-[W]WW-E",/\d{4}-W\d{2}-\d/],["GGGG-[W]WW",/\d{4}-W\d{2}/],["YYYY-DDD",/\d{4}-\d{3}/]],$b=[["HH:mm:ss.SSSS",/(T| )\d\d:\d\d:\d\d\.\d+/],["HH:mm:ss",/(T| )\d\d:\d\d:\d\d/],["HH:mm",/(T| )\d\d:\d\d/],["HH",/(T| )\d\d/]],_b=/([\+\-]|\d\d)/gi,ac=("Date|Hours|Minutes|Seconds|Milliseconds".split("|"),{Milliseconds:1,Seconds:1e3,Minutes:6e4,Hours:36e5,Days:864e5,Months:2592e6,Years:31536e6}),bc={ms:"millisecond",s:"second",m:"minute",h:"hour",d:"day",D:"date",w:"week",W:"isoWeek",M:"month",Q:"quarter",y:"year",DDD:"dayOfYear",e:"weekday",E:"isoWeekday",gg:"weekYear",GG:"isoWeekYear"},cc={dayofyear:"dayOfYear",isoweekday:"isoWeekday",isoweek:"isoWeek",weekyear:"weekYear",isoweekyear:"isoWeekYear"},dc={},ec={s:45,m:45,h:22,dd:25,dm:45,dy:345},fc="DDD w W M D d".split(" "),gc="M D H h m s w W".split(" "),hc={M:function(){return this.month()+1},MMM:function(a){return this.lang().monthsShort(this,a)},MMMM:function(a){return this.lang().months(this,a)},D:function(){return this.date()},DDD:function(){return this.dayOfYear()},d:function(){return this.day()},dd:function(a){return this.lang().weekdaysMin(this,a)},ddd:function(a){return this.lang().weekdaysShort(this,a)},dddd:function(a){return this.lang().weekdays(this,a)},w:function(){return this.week()},W:function(){return this.isoWeek()},YY:function(){return m(this.year()%100,2)},YYYY:function(){return m(this.year(),4)},YYYYY:function(){return m(this.year(),5)},YYYYYY:function(){var a=this.year(),b=a>=0?"+":"-";return b+m(Math.abs(a),6)},gg:function(){return m(this.weekYear()%100,2)},gggg:function(){return m(this.weekYear(),4)},ggggg:function(){return m(this.weekYear(),5)},GG:function(){return m(this.isoWeekYear()%100,2)},GGGG:function(){return m(this.isoWeekYear(),4)},GGGGG:function(){return m(this.isoWeekYear(),5)},e:function(){return this.weekday()},E:function(){return this.isoWeekday()},a:function(){return this.lang().meridiem(this.hours(),this.minutes(),!0)},A:function(){return this.lang().meridiem(this.hours(),this.minutes(),!1)},H:function(){return this.hours()},h:function(){return this.hours()%12||12},m:function(){return this.minutes()},s:function(){return this.seconds()},S:function(){return u(this.milliseconds()/100)},SS:function(){return m(u(this.milliseconds()/10),2)},SSS:function(){return m(this.milliseconds(),3)},SSSS:function(){return m(this.milliseconds(),3)},Z:function(){var a=-this.zone(),b="+";return 0>a&&(a=-a,b="-"),b+m(u(a/60),2)+":"+m(u(a)%60,2)},ZZ:function(){var a=-this.zone(),b="+";return 0>a&&(a=-a,b="-"),b+m(u(a/60),2)+m(u(a)%60,2)},z:function(){return this.zoneAbbr()},zz:function(){return this.zoneName()},X:function(){return this.unix()},Q:function(){return this.quarter()}},ic=["months","monthsShort","weekdays","weekdaysShort","weekdaysMin"];fc.length;)ob=fc.pop(),hc[ob+"o"]=f(hc[ob],ob);for(;gc.length;)ob=gc.pop(),hc[ob+ob]=e(hc[ob],2);for(hc.DDDD=e(hc.DDD,3),j(g.prototype,{set:function(a){var b,c;for(c in a)b=a[c],"function"==typeof b?this[c]=b:this["_"+c]=b},_months:"January_February_March_April_May_June_July_August_September_October_November_December".split("_"),months:function(a){return this._months[a.month()]},_monthsShort:"Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec".split("_"),monthsShort:function(a){return this._monthsShort[a.month()]},monthsParse:function(a){var b,c,d;for(this._monthsParse||(this._monthsParse=[]),b=0;12>b;b++)if(this._monthsParse[b]||(c=mb.utc([2e3,b]),d="^"+this.months(c,"")+"|^"+this.monthsShort(c,""),this._monthsParse[b]=new RegExp(d.replace(".",""),"i")),this._monthsParse[b].test(a))return b},_weekdays:"Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),weekdays:function(a){return this._weekdays[a.day()]},_weekdaysShort:"Sun_Mon_Tue_Wed_Thu_Fri_Sat".split("_"),weekdaysShort:function(a){return this._weekdaysShort[a.day()]},_weekdaysMin:"Su_Mo_Tu_We_Th_Fr_Sa".split("_"),weekdaysMin:function(a){return this._weekdaysMin[a.day()]},weekdaysParse:function(a){var b,c,d;for(this._weekdaysParse||(this._weekdaysParse=[]),b=0;7>b;b++)if(this._weekdaysParse[b]||(c=mb([2e3,1]).day(b),d="^"+this.weekdays(c,"")+"|^"+this.weekdaysShort(c,"")+"|^"+this.weekdaysMin(c,""),this._weekdaysParse[b]=new RegExp(d.replace(".",""),"i")),this._weekdaysParse[b].test(a))return b},_longDateFormat:{LT:"h:mm A",L:"MM/DD/YYYY",LL:"MMMM D YYYY",LLL:"MMMM D YYYY LT",LLLL:"dddd, MMMM D YYYY LT"},longDateFormat:function(a){var b=this._longDateFormat[a];return!b&&this._longDateFormat[a.toUpperCase()]&&(b=this._longDateFormat[a.toUpperCase()].replace(/MMMM|MM|DD|dddd/g,function(a){return a.slice(1)}),this._longDateFormat[a]=b),b},isPM:function(a){return"p"===(a+"").toLowerCase().charAt(0)},_meridiemParse:/[ap]\.?m?\.?/i,meridiem:function(a,b,c){return a>11?c?"pm":"PM":c?"am":"AM"},_calendar:{sameDay:"[Today at] LT",nextDay:"[Tomorrow at] LT",nextWeek:"dddd [at] LT",lastDay:"[Yesterday at] LT",lastWeek:"[Last] dddd [at] LT",sameElse:"L"},calendar:function(a,b){var c=this._calendar[a];return"function"==typeof c?c.apply(b):c},_relativeTime:{future:"in %s",past:"%s ago",s:"a few seconds",m:"a minute",mm:"%d minutes",h:"an hour",hh:"%d hours",d:"a day",dd:"%d days",M:"a month",MM:"%d months",y:"a year",yy:"%d years"},relativeTime:function(a,b,c,d){var e=this._relativeTime[c];return"function"==typeof e?e(a,b,c,d):e.replace(/%d/i,a)},pastFuture:function(a,b){var c=this._relativeTime[a>0?"future":"past"];return"function"==typeof c?c(b):c.replace(/%s/i,b)},ordinal:function(a){return this._ordinal.replace("%d",a)},_ordinal:"%d",preparse:function(a){return a},postformat:function(a){return a},week:function(a){return bb(a,this._week.dow,this._week.doy).week},_week:{dow:0,doy:6},_invalidDate:"Invalid date",invalidDate:function(){return this._invalidDate}}),mb=function(b,d,e,f){var g;return"boolean"==typeof e&&(f=e,e=a),g={},g._isAMomentObject=!0,g._i=b,g._f=d,g._l=e,g._strict=f,g._isUTC=!1,g._pf=c(),db(g)},mb.suppressDeprecationWarnings=!1,mb.createFromInputFallback=d("moment construction falls back to js Date. This is discouraged and will be removed in upcoming major release. Please refer to https://github.com/moment/moment/issues/1407 for more info.",function(a){a._d=new Date(a._i)}),mb.min=function(){var a=[].slice.call(arguments,0);return eb("isBefore",a)},mb.max=function(){var a=[].slice.call(arguments,0);return eb("isAfter",a)},mb.utc=function(b,d,e,f){var g;return"boolean"==typeof e&&(f=e,e=a),g={},g._isAMomentObject=!0,g._useUTC=!0,g._isUTC=!0,g._l=e,g._i=b,g._f=d,g._strict=f,g._pf=c(),db(g).utc()},mb.unix=function(a){return mb(1e3*a)},mb.duration=function(a,b){var c,d,e,f=a,g=null;return mb.isDuration(a)?f={ms:a._milliseconds,d:a._days,M:a._months}:"number"==typeof a?(f={},b?f[b]=a:f.milliseconds=a):(g=Db.exec(a))?(c="-"===g[1]?-1:1,f={y:0,d:u(g[ub])*c,h:u(g[vb])*c,m:u(g[wb])*c,s:u(g[xb])*c,ms:u(g[yb])*c}):(g=Eb.exec(a))&&(c="-"===g[1]?-1:1,e=function(a){var b=a&&parseFloat(a.replace(",","."));return(isNaN(b)?0:b)*c},f={y:e(g[2]),M:e(g[3]),d:e(g[4]),h:e(g[5]),m:e(g[6]),s:e(g[7]),w:e(g[8])}),d=new i(f),mb.isDuration(a)&&a.hasOwnProperty("_lang")&&(d._lang=a._lang),d},mb.version=pb,mb.defaultFormat=Yb,mb.ISO_8601=function(){},mb.momentProperties=Ab,mb.updateOffset=function(){},mb.relativeTimeThreshold=function(b,c){return ec[b]===a?!1:(ec[b]=c,!0)},mb.lang=function(a,b){var c;return a?(b?D(B(a),b):null===b?(E(a),a="en"):zb[a]||F(a),c=mb.duration.fn._lang=mb.fn._lang=F(a),c._abbr):mb.fn._lang._abbr},mb.langData=function(a){return a&&a._lang&&a._lang._abbr&&(a=a._lang._abbr),F(a)},mb.isMoment=function(a){return a instanceof h||null!=a&&a.hasOwnProperty("_isAMomentObject")},mb.isDuration=function(a){return a instanceof i},ob=ic.length-1;ob>=0;--ob)t(ic[ob]);mb.normalizeUnits=function(a){return r(a)},mb.invalid=function(a){var b=mb.utc(0/0);return null!=a?j(b._pf,a):b._pf.userInvalidated=!0,b},mb.parseZone=function(){return mb.apply(null,arguments).parseZone()},mb.parseTwoDigitYear=function(a){return u(a)+(u(a)>68?1900:2e3)},j(mb.fn=h.prototype,{clone:function(){return mb(this)},valueOf:function(){return+this._d+6e4*(this._offset||0)},unix:function(){return Math.floor(+this/1e3)},toString:function(){return this.clone().lang("en").format("ddd MMM DD YYYY HH:mm:ss [GMT]ZZ")},toDate:function(){return this._offset?new Date(+this):this._d},toISOString:function(){var a=mb(this).utc();return 0<a.year()&&a.year()<=9999?I(a,"YYYY-MM-DD[T]HH:mm:ss.SSS[Z]"):I(a,"YYYYYY-MM-DD[T]HH:mm:ss.SSS[Z]")},toArray:function(){var a=this;return[a.year(),a.month(),a.date(),a.hours(),a.minutes(),a.seconds(),a.milliseconds()]},isValid:function(){return A(this)},isDSTShifted:function(){return this._a?this.isValid()&&q(this._a,(this._isUTC?mb.utc(this._a):mb(this._a)).toArray())>0:!1},parsingFlags:function(){return j({},this._pf)},invalidAt:function(){return this._pf.overflow},utc:function(){return this.zone(0)},local:function(){return this.zone(0),this._isUTC=!1,this},format:function(a){var b=I(this,a||mb.defaultFormat);return this.lang().postformat(b)},add:function(a,b){var c;return c="string"==typeof a&&"string"==typeof b?mb.duration(isNaN(+b)?+a:+b,isNaN(+b)?b:a):"string"==typeof a?mb.duration(+b,a):mb.duration(a,b),n(this,c,1),this},subtract:function(a,b){var c;return c="string"==typeof a&&"string"==typeof b?mb.duration(isNaN(+b)?+a:+b,isNaN(+b)?b:a):"string"==typeof a?mb.duration(+b,a):mb.duration(a,b),n(this,c,-1),this},diff:function(a,b,c){var d,e,f=C(a,this),g=6e4*(this.zone()-f.zone());return b=r(b),"year"===b||"month"===b?(d=432e5*(this.daysInMonth()+f.daysInMonth()),e=12*(this.year()-f.year())+(this.month()-f.month()),e+=(this-mb(this).startOf("month")-(f-mb(f).startOf("month")))/d,e-=6e4*(this.zone()-mb(this).startOf("month").zone()-(f.zone()-mb(f).startOf("month").zone()))/d,"year"===b&&(e/=12)):(d=this-f,e="second"===b?d/1e3:"minute"===b?d/6e4:"hour"===b?d/36e5:"day"===b?(d-g)/864e5:"week"===b?(d-g)/6048e5:d),c?e:l(e)},from:function(a,b){return mb.duration(this.diff(a)).lang(this.lang()._abbr).humanize(!b)},fromNow:function(a){return this.from(mb(),a)},calendar:function(a){var b=a||mb(),c=C(b,this).startOf("day"),d=this.diff(c,"days",!0),e=-6>d?"sameElse":-1>d?"lastWeek":0>d?"lastDay":1>d?"sameDay":2>d?"nextDay":7>d?"nextWeek":"sameElse";return this.format(this.lang().calendar(e,this))},isLeapYear:function(){return y(this.year())},isDST:function(){return this.zone()<this.clone().month(0).zone()||this.zone()<this.clone().month(5).zone()},day:function(a){var b=this._isUTC?this._d.getUTCDay():this._d.getDay();return null!=a?(a=$(a,this.lang()),this.add({d:a-b})):b},month:ib("Month",!0),startOf:function(a){switch(a=r(a)){case"year":this.month(0);case"quarter":case"month":this.date(1);case"week":case"isoWeek":case"day":this.hours(0);case"hour":this.minutes(0);case"minute":this.seconds(0);case"second":this.milliseconds(0)}return"week"===a?this.weekday(0):"isoWeek"===a&&this.isoWeekday(1),"quarter"===a&&this.month(3*Math.floor(this.month()/3)),this},endOf:function(a){return a=r(a),this.startOf(a).add("isoWeek"===a?"week":a,1).subtract("ms",1)},isAfter:function(a,b){return b="undefined"!=typeof b?b:"millisecond",+this.clone().startOf(b)>+mb(a).startOf(b)},isBefore:function(a,b){return b="undefined"!=typeof b?b:"millisecond",+this.clone().startOf(b)<+mb(a).startOf(b)},isSame:function(a,b){return b=b||"ms",+this.clone().startOf(b)===+C(a,this).startOf(b)},min:d("moment().min is deprecated, use moment.min instead. https://github.com/moment/moment/issues/1548",function(a){return a=mb.apply(null,arguments),this>a?this:a}),max:d("moment().max is deprecated, use moment.max instead. https://github.com/moment/moment/issues/1548",function(a){return a=mb.apply(null,arguments),a>this?this:a}),zone:function(a,b){var c=this._offset||0;return null==a?this._isUTC?c:this._d.getTimezoneOffset():("string"==typeof a&&(a=L(a)),Math.abs(a)<16&&(a=60*a),this._offset=a,this._isUTC=!0,c!==a&&(!b||this._changeInProgress?n(this,mb.duration(c-a,"m"),1,!1):this._changeInProgress||(this._changeInProgress=!0,mb.updateOffset(this,!0),this._changeInProgress=null)),this)},zoneAbbr:function(){return this._isUTC?"UTC":""},zoneName:function(){return this._isUTC?"Coordinated Universal Time":""},parseZone:function(){return this._tzm?this.zone(this._tzm):"string"==typeof this._i&&this.zone(this._i),this},hasAlignedHourOffset:function(a){return a=a?mb(a).zone():0,(this.zone()-a)%60===0},daysInMonth:function(){return v(this.year(),this.month())},dayOfYear:function(a){var b=rb((mb(this).startOf("day")-mb(this).startOf("year"))/864e5)+1;return null==a?b:this.add("d",a-b)},quarter:function(a){return null==a?Math.ceil((this.month()+1)/3):this.month(3*(a-1)+this.month()%3)},weekYear:function(a){var b=bb(this,this.lang()._week.dow,this.lang()._week.doy).year;return null==a?b:this.add("y",a-b)},isoWeekYear:function(a){var b=bb(this,1,4).year;return null==a?b:this.add("y",a-b)},week:function(a){var b=this.lang().week(this);return null==a?b:this.add("d",7*(a-b))},isoWeek:function(a){var b=bb(this,1,4).week;return null==a?b:this.add("d",7*(a-b))},weekday:function(a){var b=(this.day()+7-this.lang()._week.dow)%7;return null==a?b:this.add("d",a-b)},isoWeekday:function(a){return null==a?this.day()||7:this.day(this.day()%7?a:a-7)},isoWeeksInYear:function(){return w(this.year(),1,4)},weeksInYear:function(){var a=this._lang._week;return w(this.year(),a.dow,a.doy)},get:function(a){return a=r(a),this[a]()},set:function(a,b){return a=r(a),"function"==typeof this[a]&&this[a](b),this},lang:function(b){return b===a?this._lang:(this._lang=F(b),this)}}),mb.fn.millisecond=mb.fn.milliseconds=ib("Milliseconds",!1),mb.fn.second=mb.fn.seconds=ib("Seconds",!1),mb.fn.minute=mb.fn.minutes=ib("Minutes",!1),mb.fn.hour=mb.fn.hours=ib("Hours",!0),mb.fn.date=ib("Date",!0),mb.fn.dates=d("dates accessor is deprecated. Use date instead.",ib("Date",!0)),mb.fn.year=ib("FullYear",!0),mb.fn.years=d("years accessor is deprecated. Use year instead.",ib("FullYear",!0)),mb.fn.days=mb.fn.day,mb.fn.months=mb.fn.month,mb.fn.weeks=mb.fn.week,mb.fn.isoWeeks=mb.fn.isoWeek,mb.fn.quarters=mb.fn.quarter,mb.fn.toJSON=mb.fn.toISOString,j(mb.duration.fn=i.prototype,{_bubble:function(){var a,b,c,d,e=this._milliseconds,f=this._days,g=this._months,h=this._data;h.milliseconds=e%1e3,a=l(e/1e3),h.seconds=a%60,b=l(a/60),h.minutes=b%60,c=l(b/60),h.hours=c%24,f+=l(c/24),h.days=f%30,g+=l(f/30),h.months=g%12,d=l(g/12),h.years=d},weeks:function(){return l(this.days()/7)},valueOf:function(){return this._milliseconds+864e5*this._days+this._months%12*2592e6+31536e6*u(this._months/12)},humanize:function(a){var b=+this,c=ab(b,!a,this.lang());return a&&(c=this.lang().pastFuture(b,c)),this.lang().postformat(c)},add:function(a,b){var c=mb.duration(a,b);return this._milliseconds+=c._milliseconds,this._days+=c._days,this._months+=c._months,this._bubble(),this},subtract:function(a,b){var c=mb.duration(a,b);return this._milliseconds-=c._milliseconds,this._days-=c._days,this._months-=c._months,this._bubble(),this},get:function(a){return a=r(a),this[a.toLowerCase()+"s"]()},as:function(a){return a=r(a),this["as"+a.charAt(0).toUpperCase()+a.slice(1)+"s"]()},lang:mb.fn.lang,toIsoString:function(){var a=Math.abs(this.years()),b=Math.abs(this.months()),c=Math.abs(this.days()),d=Math.abs(this.hours()),e=Math.abs(this.minutes()),f=Math.abs(this.seconds()+this.milliseconds()/1e3);return this.asSeconds()?(this.asSeconds()<0?"-":"")+"P"+(a?a+"Y":"")+(b?b+"M":"")+(c?c+"D":"")+(d||e||f?"T":"")+(d?d+"H":"")+(e?e+"M":"")+(f?f+"S":""):"P0D"}});for(ob in ac)ac.hasOwnProperty(ob)&&(kb(ob,ac[ob]),jb(ob.toLowerCase()));kb("Weeks",6048e5),mb.duration.fn.asMonths=function(){return(+this-31536e6*this.years())/2592e6+12*this.years()},mb.lang("en",{ordinal:function(a){var b=a%10,c=1===u(a%100/10)?"th":1===b?"st":2===b?"nd":3===b?"rd":"th";return a+c}}),Bb?module.exports=mb:"function"==typeof define&&define.amd?(define("moment",function(a,b,c){return c.config&&c.config()&&c.config().noGlobal===!0&&(qb.moment=nb),mb}),lb(!0)):lb()}).call(this);
/** Verify.js - v0.0.1 - 2013/06/12
 * https://github.com/jpillora/verify
 * Copyright (c) 2013 Jaime Pillora - MIT
 */
(function(e,t,i){function n(e,t){function n(){f("ajax error"),t.callback("There has been an error")}function r(){d.prompt(u,!1);for(var e=s.loading[p];e.length;)e.pop().success.apply(t,arguments);s.loaded[p]=arguments}var o={method:"GET",timeout:15e3},l=t._exec,u="GroupRuleExecution"===l.type?l.element.domElem:t.field,h=e.success,c=e.error,d=l.element.options,p=JSON?JSON.stringify(e):a(),m={success:h,error:c||n};if(s.loaded[p]){var g=s.loaded[p],v=m.success;return v.apply(t,g),i}if(s.loading[p]||(s.loading[p]=[]),s.loading[p].push(m),1===s.loading[p].length){d.prompt(u,"Checking...","load");var y={success:r,error:r};l.ajax=$.ajax($.extend(o,e,y))}}function r(e){$.extend(!0,this,e)}(function(e,t,i){"use strict";var n,r,s,a,o,l,u,h,c,d,f,p,m,g,v,y,b,A,x,w,E,F,R,k=[].indexOf||function(e){for(var t=0,i=this.length;i>t;t++)if(t in this&&this[t]===e)return t;return-1};b="notify",y=b+"js",x={t:"top",m:"middle",b:"bottom",l:"left",c:"center",r:"right"},c=["l","c","r"],R=["t","m","b"],m=["t","b","l","r"],g={t:"b",m:null,b:"t",l:"r",c:null,r:"l"},v=function(e){var t;return t=[],$.each(e.split(/\W+/),function(e,n){var r;return r=n.toLowerCase().charAt(0),x[r]?t.push(r):i}),t},F={},s={name:"core",html:'<div class="'+y+'-wrapper">\n  <div class="'+y+'-arrow"></div>\n  <div class="'+y+'-container"></div>\n</div>',css:"."+y+"-corner {\n  position: fixed;\n  margin: 5px;\n  z-index: 1050;\n}\n\n."+y+"-corner ."+y+"-wrapper,\n."+y+"-corner ."+y+"-container {\n  position: relative;\n  display: block;\n  height: inherit;\n  width: inherit;\n  margin: 3px;\n}\n\n."+y+"-wrapper {\n  z-index: 1;\n  position: absolute;\n  display: inline-block;\n  height: 0;\n  width: 0;\n}\n\n."+y+"-container {\n  display: none;\n  z-index: 1;\n  position: absolute;\n  cursor: pointer;\n}\n\n."+y+"-text {\n  position: relative;\n}\n\n."+y+"-arrow {\n  position: absolute;\n  z-index: 2;\n  width: 0;\n  height: 0;\n}"},E={"border-radius":["-webkit-","-moz-"]},u=function(e){return F[e]},r=function(t,n){var r,s;if(!t)throw"Missing Style name";if(!n)throw"Missing Style definition";return(null!=(s=F[t])?s.cssElem:void 0)&&(e.console&&console.warn(""+b+": overwriting style '"+t+"'"),F[t].cssElem.remove()),n.name=t,F[t]=n,r="",n.classes&&$.each(n.classes,function(e,t){return r+="."+y+"-"+n.name+"-"+e+" {\n",$.each(t,function(e,t){return E[e]&&$.each(E[e],function(i,n){return r+="  "+n+e+": "+t+";\n"}),r+="  "+e+": "+t+";\n"}),r+="}\n"}),n.css&&(r+="/* styles for "+n.name+" */\n"+n.css),r?(n.cssElem=p(r),n.cssElem.attr("id","notify-"+n.name)):i},p=function(e){var t;t=a("style"),t.attr("type","text/css"),$("head").append(t);try{t.html(e)}catch(i){t[0].styleSheet.cssText=e}return t},A={clickToHide:!0,autoHide:!0,autoHideDelay:5e3,arrowShow:!0,arrowSize:5,elementPosition:"bottom",globalPosition:"top right",style:"bootstrap",className:"error",showAnimation:"slideDown",showDuration:400,hideAnimation:"slideUp",hideDuration:200,gap:5},f=function(e,t){var i;return i=function(){},i.prototype=e,$.extend(!0,new i,t)},o=function(e){return $.extend(A,e)},a=function(e){return $("<"+e+"></"+e+">")},h={},l=function(e){var t;return e.is("[type=radio]")&&(t=e.parents("form:first").find("[type=radio]").filter(function(t,i){return $(i).attr("name")===e.attr("name")}),e=t.first()),e},d=function(e,t,n){var r,s;if("string"==typeof n)n=parseInt(n,10);else if("number"!=typeof n)return;if(!isNaN(n))return r=x[g[t.charAt(0)]],s=t,e[r]!==i&&(t=x[r.charAt(0)],n=-n),e[t]===i?e[t]=n:e[t]+=n,null},w=function(e,t,i){if("l"===e||"t"===e)return 0;if("c"===e||"m"===e)return i/2-t/2;if("r"===e||"b"===e)return i-t;throw"Invalid alignment"},n=function(){function e(e,t,i){"string"==typeof i&&(i={className:i}),this.options=f(A,$.isPlainObject(i)?i:{}),this.loadHTML(),this.wrapper=$(s.html),this.wrapper.data(y,this),this.arrow=this.wrapper.find("."+y+"-arrow"),this.container=this.wrapper.find("."+y+"-container"),this.container.append(this.userContainer),e&&e.length&&(this.elementType=e.attr("type"),this.originalElement=e,this.elem=l(e),this.elem.data(y,this),this.elem.before(this.wrapper)),this.container.hide(),this.run(t)}return e.prototype.loadHTML=function(){var e;if(e=this.getStyle(),this.userContainer=$(e.html),this.text=this.userContainer.find("[data-notify-text]"),0===this.text.length&&(this.text=this.userContainer.find("[data-notify-html]"),this.rawHTML=!0),0===this.text.length)throw"style: '"+name+"' HTML is missing a: 'data-notify-text' or 'data-notify-html' attribute";return this.text.addClass(""+y+"-text")},e.prototype.show=function(e,t){var n,r,s,a,o,l=this;if(r=function(){return e||l.elem||l.destroy(),t?t():i},o=this.container.parent().parents(":hidden").length>0,s=this.container.add(this.arrow),n=[],o&&e)a="show";else if(o&&!e)a="hide";else if(!o&&e)a=this.options.showAnimation,n.push(this.options.showDuration);else{if(o||e)return r();a=this.options.hideAnimation,n.push(this.options.hideDuration)}return n.push(r),s[a].apply(s,n)},e.prototype.setGlobalPosition=function(){var e,t,i,n,r,s,o,l;return l=this.getPosition(),o=l[0],s=l[1],r=x[o],e=x[s],n=o+"|"+s,t=h[n],t||(t=h[n]=a("div"),i={},i[r]=0,"middle"===e?i.top="45%":"center"===e?i.left="45%":i[e]=0,t.css(i).addClass(""+y+"-corner"),$("body").append(t)),t.prepend(this.wrapper)},e.prototype.setElementPosition=function(){var e,t,n,r,s,a,o,l,u,h,f,p,v,y,b,A,E,F,j,T,I,C,O,S,D,M,z,N,P;for(O=this.getPosition(),T=O[0],F=O[1],j=O[2],f=this.elem.position(),l=this.elem.outerHeight(),p=this.elem.outerWidth(),u=this.elem.innerHeight(),h=this.elem.innerWidth(),S=this.wrapper.position(),s=this.container.height(),a=this.container.width(),y=x[T],A=g[T],E=x[A],o={},o[E]="b"===T?l:"r"===T?p:0,d(o,"top",f.top-S.top),d(o,"left",f.left-S.left),P=["top","left"],D=0,z=P.length;z>D;D++)I=P[D],b=parseInt(this.elem.css("margin-"+I),10),b&&d(o,I,b);if(v=Math.max(0,this.options.gap-(this.options.arrowShow?n:0)),d(o,E,v),this.options.arrowShow){for(n=this.options.arrowSize,t=$.extend({},o),e=this.userContainer.css("border-color")||this.userContainer.css("background-color")||"white",M=0,N=m.length;N>M;M++)I=m[M],C=x[I],I!==A&&(r=C===y?e:"transparent",t["border-"+C]=""+n+"px solid "+r);d(o,x[A],n),k.call(m,F)>=0&&d(t,x[F],2*n)}else this.arrow.hide();return k.call(R,T)>=0?(d(o,"left",w(F,a,p)),t&&d(t,"left",w(F,n,h))):k.call(c,T)>=0&&(d(o,"top",w(F,s,l)),t&&d(t,"top",w(F,n,u))),this.container.is(":visible")&&(o.display="block"),this.container.removeAttr("style").css(o),t?this.arrow.removeAttr("style").css(t):i},e.prototype.getPosition=function(){var e,t,i,n,r,s,a,o;if(t=this.options.position||(this.elem?this.options.elementPosition:this.options.globalPosition),e=v(t),0===e.length&&(e[0]="b"),i=e[0],0>k.call(m,i))throw"Must be one of ["+m+"]";return(1===e.length||(n=e[0],k.call(R,n)>=0&&(r=e[1],0>k.call(c,r)))||(s=e[0],k.call(c,s)>=0&&(a=e[1],0>k.call(R,a))))&&(e[1]=(o=e[0],k.call(c,o)>=0?"m":"l")),2===e.length&&(e[2]=e[1]),e},e.prototype.getStyle=function(e){var t;if(e||(e=this.options.style),e||(e="default"),t=F[e],!t)throw"Missing style: "+e;return t},e.prototype.updateClasses=function(){var e,t;return e=["base"],$.isArray(this.options.className)?e=e.concat(this.options.className):this.options.className&&e.push(this.options.className),t=this.getStyle(),e=$.map(e,function(e){return""+y+"-"+t.name+"-"+e}).join(" "),this.userContainer.attr("class",e)},e.prototype.run=function(e,t){var n=this;return $.isPlainObject(t)?$.extend(this.options,t):"string"===$.type(t)&&(this.options.color=t),this.container&&!e?(this.show(!1),i):this.container||e?(this.text[this.rawHTML?"html":"text"](e),this.updateClasses(),this.elem?this.setElementPosition():this.setGlobalPosition(),this.show(!0),this.options.autoHide?(clearTimeout(this.autohideTimer),this.autohideTimer=setTimeout(function(){return n.show(!1)},this.options.autoHideDelay)):i):i},e.prototype.destroy=function(){return this.wrapper.remove()},e}(),$[b]=function(e,t,i){return e&&e.nodeName||e.jquery?$(e)[b](t,i):(i=t,t=e,new n(null,t,i)),e},$.fn[b]=function(e,t){return $(this).each(function(){var i;return i=l($(this)).data(y),i?i.run(e,t):new n($(this),e,t)}),this},$.extend($[b],{defaults:o,addStyle:r,pluginOptions:A,getStyle:u,insertCSS:p}),$(function(){return p(s.css).attr("id","core-notify"),$(t).on("click notify-hide","."+y+"-wrapper",function(e){var t;return t=$(this).data(y),t&&(t.options.clickToHide||"notify-hide"===e.type)?t.show(!1):i})})})(e,t),$.notify.addStyle("bootstrap",{html:"<div>\n<span data-notify-text></span>\n</div>",classes:{base:{"font-weight":"bold",padding:"8px 15px 8px 14px","text-shadow":"0 1px 0 rgba(255, 255, 255, 0.5)","background-color":"#fcf8e3",border:"1px solid #fbeed5","border-radius":"4px","white-space":"nowrap","padding-left":"25px","background-repeat":"no-repeat","background-position":"3px 7px"},error:{color:"#B94A48","background-color":"#F2DEDE","border-color":"#EED3D7","background-image":"url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAtRJREFUeNqkVc1u00AQHq+dOD+0poIQfkIjalW0SEGqRMuRnHos3DjwAH0ArlyQeANOOSMeAA5VjyBxKBQhgSpVUKKQNGloFdw4cWw2jtfMOna6JOUArDTazXi/b3dm55socPqQhFka++aHBsI8GsopRJERNFlY88FCEk9Yiwf8RhgRyaHFQpPHCDmZG5oX2ui2yilkcTT1AcDsbYC1NMAyOi7zTX2Agx7A9luAl88BauiiQ/cJaZQfIpAlngDcvZZMrl8vFPK5+XktrWlx3/ehZ5r9+t6e+WVnp1pxnNIjgBe4/6dAysQc8dsmHwPcW9C0h3fW1hans1ltwJhy0GxK7XZbUlMp5Ww2eyan6+ft/f2FAqXGK4CvQk5HueFz7D6GOZtIrK+srupdx1GRBBqNBtzc2AiMr7nPplRdKhb1q6q6zjFhrklEFOUutoQ50xcX86ZlqaZpQrfbBdu2R6/G19zX6XSgh6RX5ubyHCM8nqSID6ICrGiZjGYYxojEsiw4PDwMSL5VKsC8Yf4VRYFzMzMaxwjlJSlCyAQ9l0CW44PBADzXhe7xMdi9HtTrdYjFYkDQL0cn4Xdq2/EAE+InCnvADTf2eah4Sx9vExQjkqXT6aAERICMewd/UAp/IeYANM2joxt+q5VI+ieq2i0Wg3l6DNzHwTERPgo1ko7XBXj3vdlsT2F+UuhIhYkp7u7CarkcrFOCtR3H5JiwbAIeImjT/YQKKBtGjRFCU5IUgFRe7fF4cCNVIPMYo3VKqxwjyNAXNepuopyqnld602qVsfRpEkkz+GFL1wPj6ySXBpJtWVa5xlhpcyhBNwpZHmtX8AGgfIExo0ZpzkWVTBGiXCSEaHh62/PoR0p/vHaczxXGnj4bSo+G78lELU80h1uogBwWLf5YlsPmgDEd4M236xjm+8nm4IuE/9u+/PH2JXZfbwz4zw1WbO+SQPpXfwG/BBgAhCNZiSb/pOQAAAAASUVORK5CYII=)"},success:{color:"#468847","background-color":"#DFF0D8","border-color":"#D6E9C6","background-image":"url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAutJREFUeNq0lctPE0Ecx38zu/RFS1EryqtgJFA08YCiMZIAQQ4eRG8eDGdPJiYeTIwHTfwPiAcvXIwXLwoXPaDxkWgQ6islKlJLSQWLUraPLTv7Gme32zoF9KSTfLO7v53vZ3d/M7/fIth+IO6INt2jjoA7bjHCJoAlzCRw59YwHYjBnfMPqAKWQYKjGkfCJqAF0xwZjipQtA3MxeSG87VhOOYegVrUCy7UZM9S6TLIdAamySTclZdYhFhRHloGYg7mgZv1Zzztvgud7V1tbQ2twYA34LJmF4p5dXF1KTufnE+SxeJtuCZNsLDCQU0+RyKTF27Unw101l8e6hns3u0PBalORVVVkcaEKBJDgV3+cGM4tKKmI+ohlIGnygKX00rSBfszz/n2uXv81wd6+rt1orsZCHRdr1Imk2F2Kob3hutSxW8thsd8AXNaln9D7CTfA6O+0UgkMuwVvEFFUbbAcrkcTA8+AtOk8E6KiQiDmMFSDqZItAzEVQviRkdDdaFgPp8HSZKAEAL5Qh7Sq2lIJBJwv2scUqkUnKoZgNhcDKhKg5aH+1IkcouCAdFGAQsuWZYhOjwFHQ96oagWgRoUov1T9kRBEODAwxM2QtEUl+Wp+Ln9VRo6BcMw4ErHRYjH4/B26AlQoQQTRdHWwcd9AH57+UAXddvDD37DmrBBV34WfqiXPl61g+vr6xA9zsGeM9gOdsNXkgpEtTwVvwOklXLKm6+/p5ezwk4B+j6droBs2CsGa/gNs6RIxazl4Tc25mpTgw/apPR1LYlNRFAzgsOxkyXYLIM1V8NMwyAkJSctD1eGVKiq5wWjSPdjmeTkiKvVW4f2YPHWl3GAVq6ymcyCTgovM3FzyRiDe2TaKcEKsLpJvNHjZgPNqEtyi6mZIm4SRFyLMUsONSSdkPeFtY1n0mczoY3BHTLhwPRy9/lzcziCw9ACI+yql0VLzcGAZbYSM5CCSZg1/9oc/nn7+i8N9p/8An4JMADxhH+xHfuiKwAAAABJRU5ErkJggg==)"},info:{color:"#3A87AD","background-color":"#D9EDF7","border-color":"#BCE8F1","background-image":"url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3QYFAhkSsdes/QAAA8dJREFUOMvVlGtMW2UYx//POaWHXg6lLaW0ypAtw1UCgbniNOLcVOLmAjHZolOYlxmTGXVZdAnRfXQm+7SoU4mXaOaiZsEpC9FkiQs6Z6bdCnNYruM6KNBw6YWewzl9z+sHImEWv+vz7XmT95f/+3/+7wP814v+efDOV3/SoX3lHAA+6ODeUFfMfjOWMADgdk+eEKz0pF7aQdMAcOKLLjrcVMVX3xdWN29/GhYP7SvnP0cWfS8caSkfHZsPE9Fgnt02JNutQ0QYHB2dDz9/pKX8QjjuO9xUxd/66HdxTeCHZ3rojQObGQBcuNjfplkD3b19Y/6MrimSaKgSMmpGU5WevmE/swa6Oy73tQHA0Rdr2Mmv/6A1n9w9suQ7097Z9lM4FlTgTDrzZTu4StXVfpiI48rVcUDM5cmEksrFnHxfpTtU/3BFQzCQF/2bYVoNbH7zmItbSoMj40JSzmMyX5qDvriA7QdrIIpA+3cdsMpu0nXI8cV0MtKXCPZev+gCEM1S2NHPvWfP/hL+7FSr3+0p5RBEyhEN5JCKYr8XnASMT0xBNyzQGQeI8fjsGD39RMPk7se2bd5ZtTyoFYXftF6y37gx7NeUtJJOTFlAHDZLDuILU3j3+H5oOrD3yWbIztugaAzgnBKJuBLpGfQrS8wO4FZgV+c1IxaLgWVU0tMLEETCos4xMzEIv9cJXQcyagIwigDGwJgOAtHAwAhisQUjy0ORGERiELgG4iakkzo4MYAxcM5hAMi1WWG1yYCJIcMUaBkVRLdGeSU2995TLWzcUAzONJ7J6FBVBYIggMzmFbvdBV44Corg8vjhzC+EJEl8U1kJtgYrhCzgc/vvTwXKSib1paRFVRVORDAJAsw5FuTaJEhWM2SHB3mOAlhkNxwuLzeJsGwqWzf5TFNdKgtY5qHp6ZFf67Y/sAVadCaVY5YACDDb3Oi4NIjLnWMw2QthCBIsVhsUTU9tvXsjeq9+X1d75/KEs4LNOfcdf/+HthMnvwxOD0wmHaXr7ZItn2wuH2SnBzbZAbPJwpPx+VQuzcm7dgRCB57a1uBzUDRL4bfnI0RE0eaXd9W89mpjqHZnUI5Hh2l2dkZZUhOqpi2qSmpOmZ64Tuu9qlz/SEXo6MEHa3wOip46F1n7633eekV8ds8Wxjn37Wl63VVa+ej5oeEZ/82ZBETJjpJ1Rbij2D3Z/1trXUvLsblCK0XfOx0SX2kMsn9dX+d+7Kf6h8o4AIykuffjT8L20LU+w4AZd5VvEPY+XpWqLV327HR7DzXuDnD8r+ovkBehJ8i+y8YAAAAASUVORK5CYII=)"},warn:{color:"#C09853","background-color":"#FCF8E3","border-color":"#FBEED5","background-image":"url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAABJlBMVEXr6eb/2oD/wi7/xjr/0mP/ykf/tQD/vBj/3o7/uQ//vyL/twebhgD/4pzX1K3z8e349vK6tHCilCWbiQymn0jGworr6dXQza3HxcKkn1vWvV/5uRfk4dXZ1bD18+/52YebiAmyr5S9mhCzrWq5t6ufjRH54aLs0oS+qD751XqPhAybhwXsujG3sm+Zk0PTwG6Shg+PhhObhwOPgQL4zV2nlyrf27uLfgCPhRHu7OmLgAafkyiWkD3l49ibiAfTs0C+lgCniwD4sgDJxqOilzDWowWFfAH08uebig6qpFHBvH/aw26FfQTQzsvy8OyEfz20r3jAvaKbhgG9q0nc2LbZxXanoUu/u5WSggCtp1anpJKdmFz/zlX/1nGJiYmuq5Dx7+sAAADoPUZSAAAAAXRSTlMAQObYZgAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxMAAAsTAQCanBgAAAAHdElNRQfdBgUBGhh4aah5AAAAlklEQVQY02NgoBIIE8EUcwn1FkIXM1Tj5dDUQhPU502Mi7XXQxGz5uVIjGOJUUUW81HnYEyMi2HVcUOICQZzMMYmxrEyMylJwgUt5BljWRLjmJm4pI1hYp5SQLGYxDgmLnZOVxuooClIDKgXKMbN5ggV1ACLJcaBxNgcoiGCBiZwdWxOETBDrTyEFey0jYJ4eHjMGWgEAIpRFRCUt08qAAAAAElFTkSuQmCC)"}}}),function(t){function n(){this.suppressLog||a("log",this,arguments)}function r(){a("warn",this,arguments)}function s(){a("info",this,arguments)}function a(n,r,s){if(e.console!==i&&e.console.isFake!==!0){var a=t.map(s,h);a[0]=[r.prefix,a[0],r.postfix].join("");var o="boolean"===t.type(a[a.length-1])?a.pop():null;o===!0&&e.console.group(a[0]),a[0]&&null===o&&(e.navigator.userAgent.indexOf("MSIE")>=0?e.console.log(a.join(",")):e.console[n].apply(e.console,a)),o===!1&&e.console.groupEnd()}}function o(e){return{log:function(){n.apply(e,arguments)},warn:function(){r.apply(e,arguments)},info:function(){s.apply(e,arguments)}}}e.console===i&&(e.console={isFake:!0});for(var l=["log","warn","info","group","groupCollapsed","groupEnd"],u=l.length-1;u>=0;u--)e.console[l[u]]===i&&(e.console[l[u]]=t.noop);if(t){var h=function(e){return e},c=function(e){return e=t.extend({},c.defaults,e),o(e)};c.defaults={suppressLog:!1,prefix:"",postfix:""},t.extend(c,o(c.defaults)),t.console===i&&(t.console=c),t.consoleNoConflict=c}}(jQuery);var s={loading:{},loaded:{}},a=function(){return a.curr++};a.curr=1,$.fn.verifyScrollView=function(e){var t=$(this).first();return 1!==t.length?$(this):$(this).verifyScrollTo(t,e)},$.fn.verifyScrollTo=function(e,t,i){"function"==typeof t&&2==arguments.length&&(i=t,t=e);var n=$.extend({scrollTarget:e,offsetTop:50,duration:500,easing:"swing"},t);return this.each(function(){var e=$(this),t="number"==typeof n.scrollTarget?n.scrollTarget:$(n.scrollTarget),r="number"==typeof t?t:t.offset().top+e.scrollTop()-parseInt(n.offsetTop,10);e.animate({scrollTop:r},parseInt(n.duration,10),n.easing,function(){"function"==typeof i&&i.call(this)})})},$.fn.equals=function(e){if($(this).length!==e.length)return!1;for(var t=0,i=$(this).length;i>t;++t)if($(this)[t]!==e[t])return!1;return!0};var o=null;(function(){var e=!1,t=/xyz/.test(function(){})?/\b_super\b/:/.*/;o=function(){},o.extend=function(i){function n(){!e&&this.init&&this.init.apply(this,arguments)}var r=this.prototype;e=!0;var s=new this;e=!1;for(var a in i)s[a]="function"==typeof i[a]&&"function"==typeof r[a]&&t.test(i[a])?function(e,t){return function(){var i=this._super;this._super=r[e];var n=t.apply(this,arguments);return this._super=i,n}}(a,i[a]):i[a];return n.prototype=s,n.prototype.constructor=n,n.extend=arguments.callee,n}})();var l=o.extend({init:function(e,t){this.name=t?t:"Set_"+a(),this.array=[],this.addAll(e)},indexOf:function(e){for(var t=0,i=this.array.length;i>t;++t)if($.isFunction(e)?e(this.get(t)):this.equals(this.get(t),e))return t;return-1},find:function(e){return this.get(this.indexOf(e))||null},get:function(e){return this.array[e]},has:function(e){return!!this.find(e)},add:function(e){return this.has(e)?!1:(this.array.push(e),!0)},addAll:function(e){if(!e)return 0;$.isArray(e)||(e=[e]);for(var t=0,i=0,n=e.length;n>i;++i)this.add(e[i])&&t++;return t},remove:function(e){for(var t=[],i=0,n=this.array.length;n>i;++i)this.equals(this.get(i),e)||t.push(this.get(i));return this.array=t,e},removeAll:function(){this.array=[]},equals:function(e,t){return e&&t&&e.equals!==i&&t.equals!==i?e.equals(t):e===t},each:function(e){for(var t=0,i=this.array.length;i>t;++t)if(e(this.get(t))===!1)return},map:function(e){return $.map(this.array,e)},filter:function(e){return $.grep(this.array,e)},size:function(){return this.array.length},getArray:function(){return this.array}}),u=l.extend({init:function(e,t,i){this.type=e,this._super(t,i)},add:function(e){e instanceof this.type?this._super(e):this.log("add failed - invalid type")}}),h={create:function(e){function t(){}return t.prototype=e,new t},bind:$.proxy,checkOptions:function(e){if(e)for(var t in e)g[t]===i&&p("Invalid option: '"+t+"'")},appendArg:function(e,t,i){i||(i=0);var n=[].slice.call(e,i);return n[i]=t+n[i],n},memoize:function(e){return function(){for(var t=Array.prototype.slice.call(arguments),i="",n=t.length,r=null;n--;)r=t[n],i+=r===Object(r)?JSON.stringify(r):r,e.memoize||(e.memoize={});return i in e.memoize?e.memoize[i]:e.memoize[i]=e.apply(this,t)}},dateToString:function(e){return e.getFullYear()+"-"+(e.getMonth()+1)+"-"+e.getDate()},parseDate:function(e){var t=e.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);if(!t)return null;var n;if($.datepicker!==i)try{var r=$.datepicker.parseDate("dd/mm/yy",e);n=new Date(r)}catch(s){return null}else n=new Date(parseInt(t[3],10),parseInt(t[2],10)-1,parseInt(t[1],10));return n},isRTL:function(e){var i=$(t),n=$("body"),r=e&&e.hasClass("rtl")||e&&"rtl"===(e.attr("dir")||"").toLowerCase()||i.hasClass("rtl")||"rtl"===(i.attr("dir")||"").toLowerCase()||n.hasClass("rtl")||"rtl"===(n.attr("dir")||"").toLowerCase();return Boolean(r)}},c="0.0.1",d=$.consoleNoConflict({prefix:"verify.js: "}),f=d.log,p=d.warn,m=d.info,g={debug:!1,autoInit:!0,validateAttribute:"data-validate",validationEventTrigger:"blur",scroll:!0,focusFirstField:!0,hideErrorOnChange:!1,skipHiddenFields:!0,skipDisabledFields:!0,errorClass:"error",errorContainer:function(e){return e},reskinContainer:function(e){return e},beforeSubmit:function(e,t){return t},track:$.noop,showPrompt:!0,prompt:function(e,t,i){"function"===$.type($.notify)&&(i||(i={color:"red"}),$.notify(e,t,i))}};r.prototype=g;var v=o.extend({name:"Class",toString:function(){return(this.type?this.type+": ":"")+(this.name?this.name+": ":"")},log:function(){g.debug&&f.apply(this,h.appendArg(arguments,""+this))},warn:function(){p.apply(this,h.appendArg(arguments,""+this))},info:function(){m.apply(this,h.appendArg(arguments,""+this))},bind:function(e){var t=this[e];t&&$.isFunction(t)&&(this[e]=h.bind(t,this))},bindAll:function(){for(var e in this)this.bind(e)},nextTick:function(t,i,n){var r=this;return e.setTimeout(function(){t.apply(r,i)},n||0)}}),y=v.extend({init:function(e,t){return this.name=e,$.isPlainObject(t)?(this.type=t.__ruleType,this.extendInterface(t.extend),this.userObj||(this.userObj={}),$.extend(this.userObj,t),this.buildFn(),this.ready=this.fn!==i,i):this.warn("rule definition must be a function or an object")},extendInterface:function(e){if(e&&"string"==typeof e){for(var t,i=e;i;){if(i===this.name)return this.error("Rule already extends '%s'",i);t=b.getRawRule(i),i=t?t.extend:null}var n=b.getRule(e);if(!n)return this.warn("Rule missing '%s'",i);if(this.parent=n,!(n instanceof y))return this.error("Cannot extend: '"+otherName+"' invalid type");this.userObj=h.create(n.userObj),this.userObj.parent=n.userObj}},buildFn:function(){if($.isFunction(this.userObj.fn))this.fn=this.userObj.fn;else{if("regexp"!==$.type(this.userObj.regex))return this.error("Rule has no function");this.fn=function(e){return function(t){var i=RegExp(e);return t.val().match(i)?!0:t.message||"Invalid Format"}}(this.userObj.regex)}},defaultInterface:{log:f,warn:p,ajax:function(e){n(e,this)}},defaultFieldInterface:{val:function(){return this.field.val.apply(this.field,arguments)}},defaultGroupInterface:{val:function(e,t){var n=this.field(e);return n?t===i?n.val():n.val(t):i},field:function(e){var t=$.grep(this._exec.members,function(t){return t.id===e}),i=t.length?t[0].element.domElem:null;return i||this.warn("Cannot find group element with id: '"+e+"'"),i},fields:function(){return $().add($.map(this._exec.members,function(e){return e.element.domElem}))}},buildInterface:function(e){var t=[];return t.push({}),t.push(this.userObj),t.push(this.defaultInterface),"field"===this.type&&(t.push(this.defaultFieldInterface),t.push({field:e.element.domElem})),"group"===this.type&&t.push(this.defaultGroupInterface),t.push({prompt:e.element.options.prompt,form:e.element.form.domElem,callback:e.callback,args:e.args,_exec:e}),$.extend.apply(this,t)}}),b=null;(function(){var e=function(e){for(var t,n,r=e.split(""),s=[],a=0,o=0,l=r.length;l>o;++o){if(t=r[o],"("===t&&a++,")"===t&&a--,a>1)return null;","===t&&1===a&&(r[o]=";")}return 0!==a?null:($.each(r.join("").split(","),function(t,r){return(n=r.match(/^(\w+)(\.(\w+))?(\#(\w+))?(\(([^;\)]+(\;[^;\)]+)*)\))?$/))?(r={},r.name=n[1],n[3]&&(r.scope=n[3]),n[5]&&(r.id=n[5]),r.args=n[7]?n[7].split(";"):[],s.push(r),i):p("Invalid validate attribute: "+e)}),s)},t=h.memoize(e),n={},r={},s=function(e,t){for(var i in t)n[i]&&p("validator '%s' already exists",i),$.isFunction(t[i])&&(t[i]={fn:t[i]}),t[i].__ruleType=e;$.extend(!0,n,t)},a=function(e){s("field",e)},o=function(e){s("group",e)},l=function(e){return n[e]},u=function(e){var t=r[e],i=n[e];return i?t||(t=r[e]=new y(e,i)):p("Missing rule: "+e),t},c=function(e){var i=e.form.options.validateAttribute,n=e.domElem.attr(i);return n?t(n):null},d=function(e){var t=!1,i=null,n=[];return"ValidationField"!==e.type?p("Cannot get rules from invalid type"):e.domElem?(i=this.parseAttribute(e),i&&i.length?($.each(i,function(e,i){/required/.test(i.name)&&(t=!0),i.rule=u(i.name),i.rule&&n.push(i)}),n.required=t,n):n):n};b={addFieldRules:a,addGroupRules:o,getRule:u,getRawRule:l,parseString:e,parseAttribute:c,parseElement:d}})();var A=null;(function(){var e=v.extend({type:"ValidationElement",init:function(e){if(!e||!e.length)throw"Missing Element";return this.domElem=e,this.bindAll(),this.name=this.domElem.attr("name")||this.domElem.attr("id")||a(),this.execution=null,e.data("verify")?!1:(e.data("verify",this),!0)},equals:function(t){var i,n;return this.domElem?(i=this.domElem,t.jquery?n=t:t instanceof e&&t.domElem&&(n=t.domElem),i&&n?i.equals(n):!1):!1}}),n=e.extend({type:"ValidationField",init:function(e,t){this._super(e),this.form=t,this.options=t.options,this.groups=t.groups,this.ruleNames=null,this.touched=!1},validate:function(e){e||(e=$.noop);var t=new w(this);t.execute().done(function(){e(!0)}).fail(function(){e(!1)})},update:function(){this.rules=b.parseElement(this);for(var e=0;this.rules.length>e;++e){var t=this.rules[e];if(t.rule&&"group"===t.rule.type){this.groups[t.name]||(this.groups[t.name]={});var i=t.scope||"default";this.groups[t.name][i]||(this.groups[t.name][i]=new u(n)),this.groups[t.name][i].add(this)}}},handleResult:function(e){var t=this.options,i=t.reskinContainer(this.domElem);if(!i||!i.length)return this.warn("No reskin element found. Check 'reskinContainer' option.");t.showPrompt&&t.prompt(i,e.response);var n=t.errorContainer(i);n&&n.length&&n.toggleClass(t.errorClass,!e.success),this.options.track("Validate",[this.form.name,this.name].join(" "),e.success?"Valid":e.response?'"'+e.response+'"':"Silent Fail")},scrollFocus:function(e){var t=$.noop;this.options.focusFirstField&&(t=function(){e.focus()}),this.options.scroll?e.verifyScrollView(t):this.options.focusFirstField&&field.focus()}});A=e.extend({type:"ValidationForm",init:function(e,s){if(this._super(e),!e.is("form"))throw"Must be a form";this.options=new r(s),this.fields=new u(n),this.groups={},this.fieldByName={},this.invalidFields={},this.fieldHistory={},this.submitResult=i,this.submitPending=!1,this.cache={ruleNames:{},ajax:{loading:{},loaded:{}}},$(t).ready(this.domReady)},extendOptions:function(e){$.extend(!0,this.options,e)},domReady:function(){this.bindEvents(),this.updateFields(),this.log("bound to "+this.fields.size()+" elems")},bindEvents:function(){this.domElem.on("keyup.jqv","input",this.onKeyup).on("blur.jqv","input[type=text]:not(.hasDatepicker),input:not([type].hasDatepicker)",this.onValidate).on("change.jqv","input[type=text].hasDatepicker,select,[type=checkbox],[type=radio]",this.onValidate).on("submit.jqv",this.onSubmit).trigger("initialised.jqv")},unbindEvents:function(){this.domElem.off(".jqv")},updateFields:function(){var e="["+this.options.validateAttribute+"]";this.domElem.find(e).each(this.updateField)},updateField:function(e,t){e.jquery!==i&&(t=e),t.jquery===i&&(t=$(t));var r,s,a="input:not([type=hidden]),select,textarea";return t.is(a)?(s=t,r=this.fields.find(s),r||(r=new n(s,this),this.fields.add(r)),r.update(),r):this.warn("Validators will not work on container elements ("+t.prop("tagName")+"). Please use INPUT, SELECT or TEXTAREA.")},onSubmit:function(e){var t=!1;return this.submitPending&&this.warn("pending..."),this.submitPending||this.submitResult!==i?this.submitResult!==i&&(t=this.options.beforeSubmit.call(this.domElem,e,this.submitResult)):(this.submitPending=!0,this.validate(this.doSubmit)),t||e.preventDefault(),t},doSubmit:function(e){this.log("doSubmit",e),this.submitPending=!1,this.submitResult=e,this.domElem.submit(),this.submitResult=i},onKeyup:function(e){this.options.hideErrorOnChange&&this.options.prompt($(e.currentTarget),null)},onValidate:function(e){var t=$(e.currentTarget),i=t.data("verify")||this.updateField(t);i.log("validate"),i.validate($.noop)},validate:function(e){e||(e=$.noop),this.updateFields();var t=new x(this);t.execute().done(function(){e(!0)}).fail(function(){e(!1)})}})})();var x=null,w=null;(function(){var e={NOT_STARTED:0,RUNNING:1,COMPLETE:2},t=v.extend({type:"Execution",init:function(t,i){this.element=t,t&&(this.prevExec=t.execution,t.execution=this,this.options=this.element.options,this.domElem=t.domElem),this.parent=i,this.name="#"+a(),this.status=e.NOT_STARTED,this.bindAll(),this.d=this.restrictDeferred(),this.d.done(this.executePassed),this.d.fail(this.executeFailed)},isPending:function(){return this.prevExec&&this.prevExec.status!==e.COMPLETE},toString:function(){return this._super()+"["+this.element.name+(this.rule?":"+this.rule.name:"")+"] "},serialize:function(e){var t=this.mapExecutables(e);if(!$.isArray(t)||0===t.length)return this.resolve();var i=t[0](),n=1,r=t.length;if(this.log("SERIALIZE",r),!i||!i.pipe)throw"Invalid Deferred Object";for(;r>n;n++)i=i.pipe(t[n]);return i.done(this.resolve).fail(this.reject),this.d.promise()},parallelize:function(e){function t(e){s++,s===o&&r.resolve(e)}function i(e){l||(l=!0,r.reject(e))}var n=this.mapExecutables(e),r=this,s=0,a=0,o=n.length,l=!1;if(this.log("PARALLELIZE",o),!$.isArray(n)||0===o)return this.resolve();for(;o>a;++a){var u=n[a]();if(!u||!u.done||!u.fail)throw"Invalid Deferred Object";u.done(t).fail(i)}return this.d.promise()},mapExecutables:function(e){return $.map(e,function(e){if($.isFunction(e))return e;if($.isFunction(e.execute))return e.execute;throw"Invalid executable"})},linkPass:function(e){e.d.done(this.resolve)},linkFail:function(e){e.d.fail(this.reject)},link:function(e){this.linkPass(e),this.linkFail(e)},execute:function(){for(var t=this.parent,i=[];t;)i.unshift(t.name),t=t.parent;var n="("+i.join(" < ")+")";return this.log(this.parent?n:"","executing..."),this.status=e.RUNNING,this.domElem&&this.domElem.triggerHandler("validating"),!0},executePassed:function(e){this.success=!0,this.response=this.filterResponse(e),this.executed()},executeFailed:function(e){this.success=!1,this.response=this.filterResponse(e),this.executed()},executed:function(){this.status=e.COMPLETE,this.log((this.success?"Passed":"Failed")+": "+this.response),this.domElem&&this.domElem.triggerHandler("validated",this.success)},resolve:function(e){return this.resolveOrReject(!0,e)},reject:function(e){return this.resolveOrReject(!1,e)},resolveOrReject:function(e,t){var i=e?"__resolve":"__reject";if(!this.d||!this.d[i])throw"Invalid Deferred Object";return this.nextTick(this.d[i],[t],0),this.d.promise()},filterResponse:function(e){return"string"==typeof e?e:null},restrictDeferred:function(e){return e||(e=$.Deferred()),e.__reject=e.reject,e.__resolve=e.resolve,e.reject=e.resolve=function(){console.error("Use execution.resolve|reject()")},e}});x=t.extend({type:"FormExecution",init:function(e){this._super(e),this.ajaxs=[],this.children=this.element.fields.map($.proxy(function(e){return new w(e,this)},this))},execute:function(){return this._super(),this.isPending()?(this.warn("pending... (waiting for %s)",this.prevExec.name),this.reject()):(this.log("exec fields #"+this.children.length),this.parallelize(this.children))}}),w=t.extend({type:"FieldExecution",init:function(e,t){this._super(e,t),t instanceof x&&(this.formExecution=t),e.touched=!0,this.children=[]},execute:function(){if(this._super(),this.isPending())return this.warn("pending... (waiting for %s)",this.prevExec.name),this.reject();var e=b.parseElement(this.element);return this.skip=this.skipValidations(e),this.skip?this.resolve():(this.children=$.map(e,$.proxy(function(e){return"group"===e.rule.type?new r(e,this):new n(e,this)},this)),this.serialize(this.children))},skipValidations:function(e){return 0===e.length?(this.log("skip (no validators)"),!0):e.required||$.trim(this.domElem.val())?this.options.skipHiddenFields&&this.options.reskinContainer(this.domElem).is(":hidden")?(this.log("skip (hidden)"),!0):this.options.skipDisabledFields&&this.domElem.is("[disabled]")?(this.log("skip (disabled)"),!0):!1:(this.warn("skip (not required)"),!0)},executed:function(){this._super();var e,t=this,i=this.children;for(e=0;i.length>e;++e)if(i[e].success===!1){t=i[e];break}this.element.handleResult(t)}});var n=t.extend({type:"RuleExecution",init:function(e,t){this._super(null,t),this.rule=e.rule,this.args=e.args,this.element=this.parent.element,this.options=this.element.options,this.rObj={}},callback:function(e){clearTimeout(this.t),this.callbackCount++,this.log(this.rule.name+" (cb:"+this.callbackCount+"): "+e),this.callbackCount>1||(e===i&&this.warn("Undefined result"),e===!0?this.resolve(e):this.reject(e))},timeout:function(){this.warn("timeout!"),this.callback("Timeout")},execute:function(){if(this._super(),this.callbackCount=0,!this.element||!this.rule.ready)return this.warn(this.element?"not  ready.":"invalid parent."),this.resolve();this.t=setTimeout(this.timeout,1e4),this.r=this.rule.buildInterface(this);var e;try{e=this.rule.fn(this.r)}catch(t){e=!0,console.error("Error caught in validation rule: '"+this.rule.name+"', skipping.\nERROR: "+(""+t)+"\nSTACK:"+t.stack)}return e!==i&&this.nextTick(this.callback,[e]),this.d.promise()}}),r=n.extend({type:"GroupRuleExecution",init:function(e,t){if(this._super(e,t),this.groupName=e.name,this.id=e.id,this.scope=e.scope||"default",this.group=this.element.groups[this.groupName][this.scope],!this.group)throw"Missing Group Set";1===this.group.size()&&this.warn("Group only has 1 field. Consider a field rule.")},execute:function(){var t,n,r,s=this.group.exec,a=this.parent,o=a&&a.parent,l=!o,u=o instanceof x,h=!1;if(s&&s.status!==e.COMPLETE){for(this.members=s.members,t=0;this.members.length>t;++t)this.element===this.members[t].element&&(h=!0);if(h)return this.log("ALREADY A MEMBER OF %s",s.name),this.reject(),i;
this.log("SLAVE TO %s",s.name),this.members.push(this),this.link(s),this.parent&&s.linkFail(this.parent)}else this.log("MASTER"),this.members=[this],this.executeGroup=this._super,s=this.group.exec=this,u&&s.linkFail(o);if(l)for(t=0;this.group.size()>t;++t)if(n=this.group.get(t),this.element!==n){if(this.log("CHECK:",n.name),!n.touched)return this.log("FIELD NOT READY: ",n.name),this.reject();r=n.execution,r&&r.status!==e.COMPLETE&&r.reject(),this.log("STARTING ",n.name),r=new w(n,this),r.execute()}var c=this.group.size(),d=s.members.length;return c===d&&s.status===e.NOT_STARTED?(s.log("RUN"),s.executeGroup()):this.log("WAIT ("+d+"/"+c+")"),this.d.promise()},filterResponse:function(e){if(!e||!this.members.length)return this._super(e);var t=$.isPlainObject(e),i="string"==typeof e;return i&&this===this.group.exec?e:t&&e[this.id]?e[this.id]:null}})})(),$.fn.validate=function(e){var t=$(this).data("verify");t?t.validate(e):p("element does not have verifyjs attached")},$.fn.validate.version=c,$.fn.verify=function(e){return this.each(function(){var t=$.verify.forms.find($(this));return e===!1||"destroy"===e?(t&&(t.unbindEvents(),$.verify.forms.remove(t)),i):(h.checkOptions(e),t?(t.extendOptions(e),t.updateFields()):(t=new A($(this),e),$.verify.forms.add(t)),i)})},$.verify=function(e){h.checkOptions(e),$.extend(g,e)},$.extend($.verify,{version:c,updateRules:b.updateRules,addRules:b.addFieldRules,addFieldRules:b.addFieldRules,addGroupRules:b.addGroupRules,log:m,warn:p,defaults:g,globals:g,utils:h,forms:new u(A,[],"FormSet"),_hidden:{ruleManager:b}}),$(function(){g.autoInit&&$("form").filter(function(){return $(this).find("["+g.validateAttribute+"]").length>0}).verify()}),f("plugin added."),function(t){return t.verify===i?(e.alert("Please include verify.js before each rule file"),i):(t.verify.addFieldRules({currency:{regex:/^\-?\$?\d{1,2}(,?\d{3})*(\.\d+)?$/,message:"Invalid monetary value"},email:{regex:/^(([^<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,message:"Invalid email address"},url:{regex:/^https?:\/\/[\-A-Za-z0-9+&@#\/%?=~_|!:,.;]*[\-A-Za-z0-9+&@#\/%=~_|]/,message:"Invalid URL"},alphanumeric:{regex:/^[0-9A-Za-z]+$/,message:"Use digits and letters only"},street_number:{regex:/^\d+[A-Za-z]?(-\d+)?[A-Za-z]?$/,message:"Street Number only"},number:{regex:/^\d+$/,message:"Use digits only"},numberSpace:{regex:/^[\d\ ]+$/,message:"Use digits and spaces only"},postcode:{regex:/^\d{4}$/,message:"Invalid postcode"},date:{fn:function(e){return t.verify.utils.parseDate(e.val())?!0:e.message},message:"Invalid date"},required:{fn:function(e){return e.requiredField(e,e.field)},requiredField:function(e,i){var n=i.val();switch(i.prop("type")){case"radio":case"checkbox":var r=i.attr("name"),s=i.data("fieldGroup");if(s||(s=e.form.find("input[name='"+r+"']"),i.data("fieldGroup",s)),s.is(":checked"))break;return 1===s.size()?e.messages.single:e.messages.multiple;default:if(!t.trim(n))return e.messages.all}return!0},messages:{all:"This field is required",multiple:"Please select an option",single:"This checkbox is required"}},regex:{fn:function(e){var t;try{var i=e.args[0];t=RegExp(i)}catch(n){return e.warn("Invalid regex: "+i),!0}return e.val().match(t)?!0:e.args[1]||e.message},message:"Invalid format"},pattern:{extend:"regex"},asyncTest:function(e){e.prompt(e.field,"Please wait..."),setTimeout(function(){e.callback()},2e3)},phone:function(e){e.val(e.val().replace(/\D/g,""));var t=e.val();return t.match(/^\+?[\d\s]+$/)?t.match(/^\+/)?!0:t.match(/^0/)?10!==t.replace(/\s/g,"").length?"Must be 10 digits long":!0:"Number must start with 0":"Use digits and spaces only"},size:function(e){var t=e.val(),n=e.args[0],r=e.args[1];if(n!==i&&r===i){var s=parseInt(n,10);if(e.val().length!==s)return"Must be "+s+" characters"}else if(n!==i&&r!==i){var a=parseInt(n,10);if(r=parseInt(r,10),a>t.length||t.length>r)return"Must be between "+a+" and "+r+" characters"}else e.warn("size validator parameter error on field: "+e.field.attr("name"));return!0},min:function(e){var t=e.val(),i=parseInt(e.args[0],10);return i>t.length?"Must be at least "+i+" characters":!0},max:function(e){var t=e.val(),i=parseInt(e.args[0],10);return t.length>i?"Must be at most "+i+" characters":!0},decimal:function(e){var t=e.val(),i=e.args[0]?parseInt(e.args[0],10):2;if(!t.match(/^\d+(,\d{3})*(\.\d+)?$/))return"Invalid decimal value";var n=parseFloat(t.replace(/[^\d\.]/g,"")),r=Math.pow(10,i);return n=Math.round(n*r)/r,e.field.val(n),!0},minVal:function(e){var t=parseFloat(e.val().replace(/[^\d\.]/g,"")),i=e.args[1]||"",n=parseFloat(e.args[0]);return n>t?"Must be greater than "+n+i:!0},maxVal:function(e){var t=parseFloat(e.val().replace(/[^\d\.]/g,"")),i=e.args[1]||"",n=parseFloat(e.args[0]);return t>n?"Must be less than "+n+i:!0},rangeVal:function(e){var t=parseFloat(e.val().replace(/[^\d\.]/g,"")),i=e.args[2]||"",n=e.args[3]||"",r=parseFloat(e.args[0]),s=parseFloat(e.args[1]);return t>s||r>t?"Must be between "+i+r+n+"\nand "+i+s+n:!0},agreement:function(e){return e.field.is(":checked")?!0:"You must agree to continue"},minAge:function(e){var i=parseInt(e.args[0],10);if(!i||isNaN(i))return console.log("WARNING: Invalid Age Param: "+i),!0;new Date;var n=new Date;n.setFullYear(n.getFullYear()-i);var r=t.verify.utils.parseDate(e.val());return"Invalid Date"===r?"Invalid Date":r>n?"You must be at least "+i:!0}}),t.verify.addGroupRules({dateRange:function(e){var i=e.field("start"),n=e.field("end");if(0===i.length||0===n.length)return e.warn("Missing dateRange fields, skipping..."),!0;var r=t.verify.utils.parseDate(i.val());if(!r)return"Invalid Start Date";var s=t.verify.utils.parseDate(n.val());return s?r>=s?"Start Date must come before End Date":!0:"Invalid End Date"},requiredAll:{extend:"required",fn:function(e){var i,n=(e.fields().length,[]),r=[];return e.fields().each(function(t,s){i=e.requiredField(e,s),i===!0?n.push(s):r.push({field:s,message:i})}),n.length>0&&r.length>0?(t.each(r,function(t,i){e.prompt(i.field,i.message)}),!1):!0}}}),i)}(jQuery)})(window,document);
/*!
 * FullCalendar v2.0.2
 * Docs & License: http://arshaw.com/fullcalendar/
 * (c) 2013 Adam Shaw
 */
(function(t){"function"==typeof define&&define.amd?define(["jquery","moment"],t):t(jQuery,moment)})(function(t,e){function n(t,e){return e.longDateFormat("LT").replace(":mm","(:mm)").replace(/(\Wmm)$/,"($1)").replace(/\s*a$/i,"t")}function r(t,e){var n=e.longDateFormat("L");return n=n.replace(/^Y+[^\w\s]*|[^\w\s]*Y+$/g,""),t.isRTL?n+=" ddd":n="ddd "+n,n}function a(t){o(xe,t)}function o(e){function n(n,r){t.isPlainObject(r)&&t.isPlainObject(e[n])&&!i(n)?e[n]=o({},e[n],r):void 0!==r&&(e[n]=r)}for(var r=1;arguments.length>r;r++)t.each(arguments[r],n);return e}function i(t){return/(Time|Duration)$/.test(t)}function s(n,r){function a(t){se?f()&&(b(),m(t)):i()}function i(){le=ne.theme?"ui":"fc",n.addClass("fc"),ne.isRTL?n.addClass("fc-rtl"):n.addClass("fc-ltr"),ne.theme&&n.addClass("ui-widget"),se=t("<div class='fc-content' />").prependTo(n),oe=new l(te,ne),ie=oe.render(),ie&&n.prepend(ie),h(ne.defaultView),ne.handleWindowResize&&t(window).resize(w),v()||s()}function s(){setTimeout(function(){!ce.start&&v()&&g()},0)}function d(){ce&&(Q("viewDestroy",ce,ce,ce.element),ce.triggerEventDestroy()),t(window).unbind("resize",w),ne.droppable&&t(document).off("dragstart",J).off("dragstop",K),ce.selectionManagerDestroy&&ce.selectionManagerDestroy(),oe.destroy(),se.remove(),n.removeClass("fc fc-ltr fc-rtl ui-widget")}function f(){return n.is(":visible")}function v(){return t("body").is(":visible")}function h(t){ce&&t==ce.name||p(t)}function p(e){ye++,ce&&(Q("viewDestroy",ce,ce,ce.element),N(),ce.triggerEventDestroy(),$(),ce.element.remove(),oe.deactivateButton(ce.name)),oe.activateButton(e),ce=new _e[e](t("<div class='fc-view fc-view-"+e+"' />").appendTo(se),te),g(),V(),ye--}function g(t){ce.start&&!t&&fe.isWithin(ce.intervalStart,ce.intervalEnd)||f()&&m(t)}function m(t){ye++,ce.start&&(Q("viewDestroy",ce,ce,ce.element),N(),x()),$(),t&&(fe=ce.incrementDate(fe,t)),ce.render(fe.clone()),D(),V(),(ce.afterRender||k)(),H(),F(),Q("viewRender",ce,ce,ce.element),ye--,M()}function y(){f()&&(N(),x(),b(),D(),S())}function b(){ue=ne.contentHeight?ne.contentHeight:ne.height?ne.height-(ie?ie.height():0)-T(se):Math.round(se.width()/Math.max(ne.aspectRatio,.5))}function D(){void 0===ue&&b(),ye++,ce.setHeight(ue),ce.setWidth(se.width()),ye--,de=n.outerWidth()}function w(t){if(!ye&&t.target===window)if(ce.start){var e=++me;setTimeout(function(){e==me&&!ye&&f()&&de!=(de=n.outerWidth())&&(ye++,y(),ce.trigger("windowResize",ge),ye--)},ne.windowResizeDelay)}else s()}function C(){x(),z()}function E(t){x(),S(t)}function S(t){f()&&(ce.renderEvents(be,t),ce.trigger("eventAfterAllRender"))}function x(){ce.triggerEventDestroy(),ce.clearEvents(),ce.clearEventData()}function M(){!ne.lazyFetching||he(ce.start,ce.end)?z():S()}function z(){pe(ce.start,ce.end)}function R(t){be=t,S()}function _(t){E(t)}function H(){oe.updateTitle(ce.title)}function F(){var t=te.getNow();t.isWithin(ce.intervalStart,ce.intervalEnd)?oe.disableButton("today"):oe.enableButton("today")}function A(t,e){ce.select(t,e)}function N(){ce&&ce.unselect()}function Y(){g(-1)}function O(){g(1)}function W(){fe.add("years",-1),g()}function L(){fe.add("years",1),g()}function Z(){fe=te.getNow(),g()}function P(t){fe=te.moment(t),g()}function j(t){fe.add(e.duration(t)),g()}function q(){return fe.clone()}function $(){se.css({width:"100%",height:se.height(),overflow:"hidden"})}function V(){se.css({width:"",height:"",overflow:""})}function X(){return te}function U(){return ce}function G(t,e){return void 0===e?ne[t]:(("height"==t||"contentHeight"==t||"aspectRatio"==t)&&(ne[t]=e,y()),void 0)}function Q(t,e){return ne[t]?ne[t].apply(e||ge,Array.prototype.slice.call(arguments,2)):void 0}function J(e,n){var r=e.target,a=t(r);if(!a.parents(".fc").length){var o=ne.dropAccept;(t.isFunction(o)?o.call(r,a):a.is(o))&&(ve=r,ce.dragStart(ve,e,n))}}function K(t,e){ve&&(ce.dragStop(ve,t,e),ve=null)}var te=this;r=r||{};var ee,ne=o({},xe,r);ee=ne.lang in Me?Me[ne.lang]:Me[xe.lang],ee&&(ne=o({},xe,ee,r)),ne.isRTL&&(ne=o({},xe,ze,ee||{},r)),te.options=ne,te.render=a,te.destroy=d,te.refetchEvents=C,te.reportEvents=R,te.reportEventChange=_,te.rerenderEvents=E,te.changeView=h,te.select=A,te.unselect=N,te.prev=Y,te.next=O,te.prevYear=W,te.nextYear=L,te.today=Z,te.gotoDate=P,te.incrementDate=j,te.getDate=q,te.getCalendar=X,te.getView=U,te.option=G,te.trigger=Q;var re=u(e.langData(ne.lang));if(ne.monthNames&&(re._months=ne.monthNames),ne.monthNamesShort&&(re._monthsShort=ne.monthNamesShort),ne.dayNames&&(re._weekdays=ne.dayNames),ne.dayNamesShort&&(re._weekdaysShort=ne.dayNamesShort),null!=ne.firstDay){var ae=u(re._week);ae.dow=ne.firstDay,re._week=ae}te.defaultAllDayEventDuration=e.duration(ne.defaultAllDayEventDuration),te.defaultTimedEventDuration=e.duration(ne.defaultTimedEventDuration),te.moment=function(){var t;return"local"===ne.timezone?(t=Re.moment.apply(null,arguments),t.hasTime()&&t.local()):t="UTC"===ne.timezone?Re.moment.utc.apply(null,arguments):Re.moment.parseZone.apply(null,arguments),t._lang=re,t},te.getIsAmbigTimezone=function(){return"local"!==ne.timezone&&"UTC"!==ne.timezone},te.rezoneDate=function(t){return te.moment(t.toArray())},te.getNow=function(){var t=ne.now;return"function"==typeof t&&(t=t()),te.moment(t)},te.calculateWeekNumber=function(t){var e=ne.weekNumberCalculation;return"function"==typeof e?e(t):"local"===e?t.week():"ISO"===e.toUpperCase()?t.isoWeek():void 0},te.getEventEnd=function(t){return t.end?t.end.clone():te.getDefaultEventEnd(t.allDay,t.start)},te.getDefaultEventEnd=function(t,e){var n=e.clone();return t?n.stripTime().add(te.defaultAllDayEventDuration):n.add(te.defaultTimedEventDuration),te.getIsAmbigTimezone()&&n.stripZone(),n},te.formatRange=function(t,e,n){return"function"==typeof n&&(n=n.call(te,ne,re)),I(t,e,n,null,ne.isRTL)},te.formatDate=function(t,e){return"function"==typeof e&&(e=e.call(te,ne,re)),B(t,e)},c.call(te,ne);var oe,ie,se,le,ce,de,ue,fe,ve,he=te.isFetchNeeded,pe=te.fetchEvents,ge=n[0],me=0,ye=0,be=[];fe=null!=ne.defaultDate?te.moment(ne.defaultDate):te.getNow(),ne.droppable&&t(document).on("dragstart",J).on("dragstop",K)}function l(e,n){function r(){f=n.theme?"ui":"fc";var e=n.header;return e?v=t("<table class='fc-header' style='width:100%'/>").append(t("<tr/>").append(o("left")).append(o("center")).append(o("right"))):void 0}function a(){v.remove()}function o(r){var a=t("<td class='fc-header-"+r+"'/>"),o=n.header[r];return o&&t.each(o.split(" "),function(r){r>0&&a.append("<span class='fc-header-space'/>");var o;t.each(this.split(","),function(r,i){if("title"==i)a.append("<span class='fc-header-title'><h2>&nbsp;</h2></span>"),o&&o.addClass(f+"-corner-right"),o=null;else{var s;if(e[i]?s=e[i]:_e[i]&&(s=function(){h.removeClass(f+"-state-hover"),e.changeView(i)}),s){var l,c=z(n.themeButtonIcons,i),d=z(n.buttonIcons,i),u=z(n.defaultButtonText,i),v=z(n.buttonText,i);l=v?R(v):c&&n.theme?"<span class='ui-icon ui-icon-"+c+"'></span>":d&&!n.theme?"<span class='fc-icon fc-icon-"+d+"'></span>":R(u||i);var h=t("<span class='fc-button fc-button-"+i+" "+f+"-state-default'>"+l+"</span>").click(function(){h.hasClass(f+"-state-disabled")||s()}).mousedown(function(){h.not("."+f+"-state-active").not("."+f+"-state-disabled").addClass(f+"-state-down")}).mouseup(function(){h.removeClass(f+"-state-down")}).hover(function(){h.not("."+f+"-state-active").not("."+f+"-state-disabled").addClass(f+"-state-hover")},function(){h.removeClass(f+"-state-hover").removeClass(f+"-state-down")}).appendTo(a);H(h),o||h.addClass(f+"-corner-left"),o=h}}}),o&&o.addClass(f+"-corner-right")}),a}function i(t){v.find("h2").html(t)}function s(t){v.find("span.fc-button-"+t).addClass(f+"-state-active")}function l(t){v.find("span.fc-button-"+t).removeClass(f+"-state-active")}function c(t){v.find("span.fc-button-"+t).addClass(f+"-state-disabled")}function d(t){v.find("span.fc-button-"+t).removeClass(f+"-state-disabled")}var u=this;u.render=r,u.destroy=a,u.updateTitle=i,u.activateButton=s,u.deactivateButton=l,u.disableButton=c,u.enableButton=d;var f,v=t([])}function c(e){function n(t,e){return!E||t.clone().stripZone()<E.clone().stripZone()||e.clone().stripZone()>S.clone().stripZone()}function r(t,e){E=t,S=e,O=[];var n=++H,r=_.length;F=r;for(var o=0;r>o;o++)a(_[o],n)}function a(e,n){o(e,function(r){var a,o,i=t.isArray(e.events);if(n==H){if(r)for(a=0;r.length>a;a++)o=r[a],i||(o=D(o,e)),o&&O.push(o);F--,F||M(O)}})}function o(n,r){var a,i,s=Re.sourceFetchers;for(a=0;s.length>a;a++){if(i=s[a].call(C,n,E.clone(),S.clone(),e.timezone,r),i===!0)return;if("object"==typeof i)return o(i,r),void 0}var l=n.events;if(l)t.isFunction(l)?(y(),l.call(C,E.clone(),S.clone(),e.timezone,function(t){r(t),b()})):t.isArray(l)?r(l):r();else{var c=n.url;if(c){var d,u=n.success,f=n.error,v=n.complete;d=t.isFunction(n.data)?n.data():n.data;var h=t.extend({},d||{}),p=Y(n.startParam,e.startParam),g=Y(n.endParam,e.endParam),m=Y(n.timezoneParam,e.timezoneParam);p&&(h[p]=E.format()),g&&(h[g]=S.format()),e.timezone&&"local"!=e.timezone&&(h[m]=e.timezone),y(),t.ajax(t.extend({},He,n,{data:h,success:function(e){e=e||[];var n=N(u,this,arguments);t.isArray(n)&&(e=n),r(e)},error:function(){N(f,this,arguments),r()},complete:function(){N(v,this,arguments),b()}}))}else r()}}function i(t){var e=s(t);e&&(_.push(e),F++,a(e,H))}function s(e){var n,r,a=Re.sourceNormalizers;if(t.isFunction(e)||t.isArray(e)?n={events:e}:"string"==typeof e?n={url:e}:"object"==typeof e&&(n=t.extend({},e),"string"==typeof n.className&&(n.className=n.className.split(/\s+/))),n){for(t.isArray(n.events)&&(n.events=t.map(n.events,function(t){return D(t,n)})),r=0;a.length>r;r++)a[r].call(C,n);return n}}function l(e){_=t.grep(_,function(t){return!c(t,e)}),O=t.grep(O,function(t){return!c(t.source,e)}),M(O)}function c(t,e){return t&&e&&u(t)==u(e)}function u(t){return("object"==typeof t?t.events||t.url:"")||t}function f(t){t.start=C.moment(t.start),t.end&&(t.end=C.moment(t.end)),w(t),h(t),M(O)}function h(t){var e,n,r,a;for(e=0;O.length>e;e++)if(n=O[e],n._id==t._id&&n!==t)for(r=0;W.length>r;r++)a=W[r],void 0!==t[a]&&(n[a]=t[a])}function p(t,e){var n=D(t);n&&(n.source||(e&&(R.events.push(n),n.source=R),O.push(n)),M(O))}function g(e){var n,r;for(null==e?e=function(){return!0}:t.isFunction(e)||(n=e+"",e=function(t){return t._id==n}),O=t.grep(O,e,!0),r=0;_.length>r;r++)t.isArray(_[r].events)&&(_[r].events=t.grep(_[r].events,e,!0));M(O)}function m(e){return t.isFunction(e)?t.grep(O,e):null!=e?(e+="",t.grep(O,function(t){return t._id==e})):O}function y(){A++||k("loading",null,!0,x())}function b(){--A||k("loading",null,!1,x())}function D(n,r){var a,o,i,s,l={};return e.eventDataTransform&&(n=e.eventDataTransform(n)),r&&r.eventDataTransform&&(n=r.eventDataTransform(n)),a=C.moment(n.start||n.date),a.isValid()&&(o=null,!n.end||(o=C.moment(n.end),o.isValid()))?(i=n.allDay,void 0===i&&(s=Y(r?r.allDayDefault:void 0,e.allDayDefault),i=void 0!==s?s:!(a.hasTime()||o&&o.hasTime())),i?(a.hasTime()&&a.stripTime(),o&&o.hasTime()&&o.stripTime()):(a.hasTime()||(a=C.rezoneDate(a)),o&&!o.hasTime()&&(o=C.rezoneDate(o))),t.extend(l,n),r&&(l.source=r),l._id=n._id||(void 0===n.id?"_fc"+Fe++:n.id+""),l.className=n.className?"string"==typeof n.className?n.className.split(/\s+/):n.className:[],l.allDay=i,l.start=a,l.end=o,e.forceEventDuration&&!l.end&&(l.end=z(l)),d(l),l):void 0}function w(t,e,n){var r,a,o,i,s=t._allDay,l=t._start,c=t._end,d=!1;return e||n||(e=t.start,n=t.end),r=t.allDay!=s?t.allDay:!(e||n).hasTime(),r&&(e&&(e=e.clone().stripTime()),n&&(n=n.clone().stripTime())),e&&(a=r?v(e,l.clone().stripTime()):v(e,l)),r!=s?d=!0:n&&(o=v(n||C.getDefaultEventEnd(r,e||l),e||l).subtract(v(c||C.getDefaultEventEnd(s,l),l))),i=T(m(t._id),d,r,a,o),{dateDelta:a,durationDelta:o,undo:i}}function T(n,r,a,o,i){var s=C.getIsAmbigTimezone(),l=[];return t.each(n,function(t,n){var c=n._allDay,u=n._start,f=n._end,v=null!=a?a:c,h=u.clone(),p=!r&&f?f.clone():null;v?(h.stripTime(),p&&p.stripTime()):(h.hasTime()||(h=C.rezoneDate(h)),p&&!p.hasTime()&&(p=C.rezoneDate(p))),p||!e.forceEventDuration&&!+i||(p=C.getDefaultEventEnd(v,h)),h.add(o),p&&p.add(o).add(i),s&&(+o||+i)&&(h.stripZone(),p&&p.stripZone()),n.allDay=v,n.start=h,n.end=p,d(n),l.push(function(){n.allDay=c,n.start=u,n.end=f,d(n)})}),function(){for(var t=0;l.length>t;t++)l[t]()}}var C=this;C.isFetchNeeded=n,C.fetchEvents=r,C.addEventSource=i,C.removeEventSource=l,C.updateEvent=f,C.renderEvent=p,C.removeEvents=g,C.clientEvents=m,C.mutateEvent=w;var E,S,k=C.trigger,x=C.getView,M=C.reportEvents,z=C.getEventEnd,R={events:[]},_=[R],H=0,F=0,A=0,O=[];t.each((e.events?[e.events]:[]).concat(e.eventSources||[]),function(t,e){var n=s(e);n&&_.push(n)});var W=["title","url","allDay","className","editable","color","backgroundColor","borderColor","textColor"]}function d(t){t._allDay=t.allDay,t._start=t.start.clone(),t._end=t.end?t.end.clone():null}function u(t){var e=function(){};return e.prototype=t,new e}function f(t,e){for(var n in e)e.hasOwnProperty(n)&&(t[n]=e[n])}function v(t,n){return e.duration({days:t.clone().stripTime().diff(n.clone().stripTime(),"days"),ms:t.time()-n.time()})}function h(t){return"[object Date]"===Object.prototype.toString.call(t)||t instanceof Date}function p(e,n,r){e.unbind("mouseover").mouseover(function(e){for(var a,o,i,s=e.target;s!=this;)a=s,s=s.parentNode;void 0!==(o=a._fci)&&(a._fci=void 0,i=n[o],r(i.event,i.element,i),t(e.target).trigger(e)),e.stopPropagation()})}function g(e,n,r){for(var a,o=0;e.length>o;o++)a=t(e[o]),a.width(Math.max(0,n-y(a,r)))}function m(e,n,r){for(var a,o=0;e.length>o;o++)a=t(e[o]),a.height(Math.max(0,n-T(a,r)))}function y(t,e){return b(t)+w(t)+(e?D(t):0)}function b(e){return(parseFloat(t.css(e[0],"paddingLeft",!0))||0)+(parseFloat(t.css(e[0],"paddingRight",!0))||0)}function D(e){return(parseFloat(t.css(e[0],"marginLeft",!0))||0)+(parseFloat(t.css(e[0],"marginRight",!0))||0)}function w(e){return(parseFloat(t.css(e[0],"borderLeftWidth",!0))||0)+(parseFloat(t.css(e[0],"borderRightWidth",!0))||0)}function T(t,e){return C(t)+S(t)+(e?E(t):0)}function C(e){return(parseFloat(t.css(e[0],"paddingTop",!0))||0)+(parseFloat(t.css(e[0],"paddingBottom",!0))||0)}function E(e){return(parseFloat(t.css(e[0],"marginTop",!0))||0)+(parseFloat(t.css(e[0],"marginBottom",!0))||0)}function S(e){return(parseFloat(t.css(e[0],"borderTopWidth",!0))||0)+(parseFloat(t.css(e[0],"borderBottomWidth",!0))||0)}function k(){}function x(t,e){return t-e}function M(t){return Math.max.apply(Math,t)}function z(t,e){if(t=t||{},void 0!==t[e])return t[e];for(var n,r=e.split(/(?=[A-Z])/),a=r.length-1;a>=0;a--)if(n=t[r[a].toLowerCase()],void 0!==n)return n;return t["default"]}function R(t){return(t+"").replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/'/g,"&#039;").replace(/"/g,"&quot;").replace(/\n/g,"<br />")}function _(t){return t.replace(/&.*?;/g,"")}function H(t){t.attr("unselectable","on").css("MozUserSelect","none").bind("selectstart.ui",function(){return!1})}function F(t){t.children().removeClass("fc-first fc-last").filter(":first-child").addClass("fc-first").end().filter(":last-child").addClass("fc-last")}function A(t,e){var n=t.source||{},r=t.color,a=n.color,o=e("eventColor"),i=t.backgroundColor||r||n.backgroundColor||a||e("eventBackgroundColor")||o,s=t.borderColor||r||n.borderColor||a||e("eventBorderColor")||o,l=t.textColor||n.textColor||e("eventTextColor"),c=[];return i&&c.push("background-color:"+i),s&&c.push("border-color:"+s),l&&c.push("color:"+l),c.join(";")}function N(e,n,r){if(t.isFunction(e)&&(e=[e]),e){var a,o;for(a=0;e.length>a;a++)o=e[a].apply(n,r)||o;return o}}function Y(){for(var t=0;arguments.length>t;t++)if(void 0!==arguments[t])return arguments[t]}function O(n,r,a){var o,i,s,l,c=n[0],d=1==n.length&&"string"==typeof c;return e.isMoment(c)?(l=e.apply(null,n),c._ambigTime&&(l._ambigTime=!0),c._ambigZone&&(l._ambigZone=!0)):h(c)||void 0===c?l=e.apply(null,n):(o=!1,i=!1,d?Ne.test(c)?(c+="-01",n=[c],o=!0,i=!0):(s=Ye.exec(c))&&(o=!s[5],i=!0):t.isArray(c)&&(i=!0),l=r?e.utc.apply(e,n):e.apply(null,n),o?(l._ambigTime=!0,l._ambigZone=!0):a&&(i?l._ambigZone=!0:d&&l.zone(c))),new W(l)}function W(t){f(this,t)}function L(t){var e,n=[],r=!1,a=!1;for(e=0;t.length>e;e++)n.push(Re.moment(t[e])),r=r||n[e]._ambigTime,a=a||n[e]._ambigZone;for(e=0;n.length>e;e++)r?n[e].stripTime():a&&n[e].stripZone();return n}function Z(t,n){return e.fn.format.call(t,n)}function B(t,e){return P(t,V(e))}function P(t,e){var n,r="";for(n=0;e.length>n;n++)r+=j(t,e[n]);return r}function j(t,e){var n,r;return"string"==typeof e?e:(n=e.token)?Oe[n]?Oe[n](t):Z(t,n):e.maybe&&(r=P(t,e.maybe),r.match(/[1-9]/))?r:""}function I(t,e,n,r,a){return t=Re.moment.parseZone(t),e=Re.moment.parseZone(e),n=t.lang().longDateFormat(n)||n,r=r||" - ",q(t,e,V(n),r,a)}function q(t,e,n,r,a){var o,i,s,l,c="",d="",u="",f="",v="";for(i=0;n.length>i&&(o=$(t,e,n[i]),o!==!1);i++)c+=o;for(s=n.length-1;s>i&&(o=$(t,e,n[s]),o!==!1);s--)d=o+d;for(l=i;s>=l;l++)u+=j(t,n[l]),f+=j(e,n[l]);return(u||f)&&(v=a?f+r+u:u+r+f),c+v+d}function $(t,e,n){var r,a;return"string"==typeof n?n:(r=n.token)&&(a=We[r.charAt(0)],a&&t.isSame(e,a))?Z(t,r):!1}function V(t){return t in Le?Le[t]:Le[t]=X(t)}function X(t){for(var e,n=[],r=/\[([^\]]*)\]|\(([^\)]*)\)|(LT|(\w)\4*o?)|([^\w\[\(]+)/g;e=r.exec(t);)e[1]?n.push(e[1]):e[2]?n.push({maybe:X(e[2])}):e[3]?n.push({token:e[3]}):e[5]&&n.push(e[5]);return n}function U(t,e){function n(t,e){return t.clone().stripTime().add("months",e).startOf("month")}function r(t){a.intervalStart=t.clone().stripTime().startOf("month"),a.intervalEnd=a.intervalStart.clone().add("months",1),a.start=a.intervalStart.clone(),a.start=a.skipHiddenDays(a.start),a.start.startOf("week"),a.start=a.skipHiddenDays(a.start),a.end=a.intervalEnd.clone(),a.end=a.skipHiddenDays(a.end,-1,!0),a.end.add("days",(7-a.end.weekday())%7),a.end=a.skipHiddenDays(a.end,-1,!0);var n=Math.ceil(a.end.diff(a.start,"weeks",!0));"fixed"==a.opt("weekMode")&&(a.end.add("weeks",6-n),n=6),a.title=e.formatDate(a.intervalStart,a.opt("titleFormat")),a.renderBasic(n,a.getCellsPerWeek(),!0)}var a=this;a.incrementDate=n,a.render=r,J.call(a,t,e,"month")}function G(t,e){function n(t,e){return t.clone().stripTime().add("weeks",e).startOf("week")}function r(t){a.intervalStart=t.clone().stripTime().startOf("week"),a.intervalEnd=a.intervalStart.clone().add("weeks",1),a.start=a.skipHiddenDays(a.intervalStart),a.end=a.skipHiddenDays(a.intervalEnd,-1,!0),a.title=e.formatRange(a.start,a.end.clone().subtract(1),a.opt("titleFormat")," — "),a.renderBasic(1,a.getCellsPerWeek(),!1)}var a=this;a.incrementDate=n,a.render=r,J.call(a,t,e,"basicWeek")}function Q(t,e){function n(t,e){var n=t.clone().stripTime().add("days",e);return n=a.skipHiddenDays(n,0>e?-1:1)}function r(t){a.start=a.intervalStart=t.clone().stripTime(),a.end=a.intervalEnd=a.start.clone().add("days",1),a.title=e.formatDate(a.start,a.opt("titleFormat")),a.renderBasic(1,1,!1)}var a=this;a.incrementDate=n,a.render=r,J.call(a,t,e,"basicDay")}function J(e,n,r){function a(t,e,n){U=t,G=e,Q=n,o(),W||i(),s()}function o(){re=ie("theme")?"ui":"fc",ae=ie("columnFormat"),oe=ie("weekNumbers")}function i(){I=t("<div class='fc-event-container' style='position:absolute;z-index:8;top:0;left:0'/>").appendTo(e)}function s(){var n=l();N&&N.remove(),N=t(n).appendTo(e),Y=N.find("thead"),O=Y.find(".fc-day-header"),W=N.find("tbody"),L=W.find("tr"),Z=W.find(".fc-day"),B=L.find("td:first-child"),P=L.eq(0).find(".fc-day > div"),j=L.eq(0).find(".fc-day-content > div"),F(Y.add(Y.find("tr"))),F(L),L.eq(0).addClass("fc-first"),L.filter(":last").addClass("fc-last"),Z.each(function(e,n){var r=ue(Math.floor(e/G),e%G);se("dayRender",A,r,t(n))}),h(Z)}function l(){var t="<table class='fc-border-separate' style='width:100%' cellspacing='0'>"+c()+d()+"</table>";return t}function c(){var t,e,n=re+"-widget-header",r="";for(r+="<thead><tr>",oe&&(r+="<th class='fc-week-number "+n+"'>"+R(ie("weekNumberTitle"))+"</th>"),t=0;G>t;t++)e=ue(0,t),r+="<th class='fc-day-header fc-"+Ae[e.day()]+" "+n+"'>"+R(he(e,ae))+"</th>";return r+="</tr></thead>"}function d(){var t,e,n,r=re+"-widget-content",a="";for(a+="<tbody>",t=0;U>t;t++){for(a+="<tr class='fc-week'>",oe&&(n=ue(t,0),a+="<td class='fc-week-number "+r+"'>"+"<div>"+R(pe(n))+"</div>"+"</td>"),e=0;G>e;e++)n=ue(t,e),a+=u(n);a+="</tr>"}return a+="</tbody>"}function u(t){var e=A.intervalStart.month(),r=n.getNow().stripTime(),a="",o=re+"-widget-content",i=["fc-day","fc-"+Ae[t.day()],o];return t.month()!=e&&i.push("fc-other-month"),t.isSame(r,"day")?i.push("fc-today",re+"-state-highlight"):r>t?i.push("fc-past"):i.push("fc-future"),a+="<td class='"+i.join(" ")+"'"+" data-date='"+t.format()+"'"+">"+"<div>",Q&&(a+="<div class='fc-day-number'>"+t.date()+"</div>"),a+="<div class='fc-day-content'><div style='position:relative'>&nbsp;</div></div></div></td>"}function f(e){$=e;var n,r,a,o=Math.max($-Y.height(),0);"variable"==ie("weekMode")?n=r=Math.floor(o/(1==U?2:6)):(n=Math.floor(o/U),r=o-n*(U-1)),B.each(function(e,o){U>e&&(a=t(o),a.find("> div").css("min-height",(e==U-1?r:n)-T(a)))})}function v(t){q=t,ee.clear(),ne.clear(),X=0,oe&&(X=Y.find("th.fc-week-number").outerWidth()),V=Math.floor((q-X)/G),g(O.slice(0,-1),V)}function h(t){t.click(p).mousedown(de)}function p(e){if(!ie("selectable")){var r=n.moment(t(this).data("date"));se("dayClick",this,r,e)}}function m(t,e,n){n&&J.build();for(var r=ve(t,e),a=0;r.length>a;a++){var o=r[a];h(y(o.row,o.leftCol,o.row,o.rightCol))}}function y(t,n,r,a){var o=J.rect(t,n,r,a,e);return le(o,e)}function b(t){return t.clone().stripTime().add("days",1)}function D(t,e){m(t,e,!0)}function w(){ce()}function C(t,e){var n=fe(t),r=Z[n.row*G+n.col];se("dayClick",r,t,e)}function E(t,e){te.start(function(t){if(ce(),t){var e=ue(t),r=e.clone().add(n.defaultAllDayEventDuration);m(e,r)}},e)}function S(t,e,n){var r=te.stop();ce(),r&&se("drop",t,ue(r),e,n)}function k(t){return ee.left(t)}function x(t){return ee.right(t)}function M(t){return ne.left(t)}function z(t){return ne.right(t)}function _(t){return L.eq(t)}var A=this;A.renderBasic=a,A.setHeight=f,A.setWidth=v,A.renderDayOverlay=m,A.defaultSelectionEnd=b,A.renderSelection=D,A.clearSelection=w,A.reportDayClick=C,A.dragStart=E,A.dragStop=S,A.getHoverListener=function(){return te},A.colLeft=k,A.colRight=x,A.colContentLeft=M,A.colContentRight=z,A.getIsCellAllDay=function(){return!0},A.allDayRow=_,A.getRowCnt=function(){return U},A.getColCnt=function(){return G},A.getColWidth=function(){return V},A.getDaySegmentContainer=function(){return I},ge.call(A,e,n,r),Te.call(A),we.call(A),K.call(A);var N,Y,O,W,L,Z,B,P,j,I,q,$,V,X,U,G,Q,J,te,ee,ne,re,ae,oe,ie=A.opt,se=A.trigger,le=A.renderOverlay,ce=A.clearOverlays,de=A.daySelectionMousedown,ue=A.cellToDate,fe=A.dateToCell,ve=A.rangeToSegments,he=n.formatDate,pe=n.calculateWeekNumber;H(e.addClass("fc-grid")),J=new Ce(function(e,n){var r,a,o;O.each(function(e,i){r=t(i),a=r.offset().left,e&&(o[1]=a),o=[a],n[e]=o}),o[1]=a+r.outerWidth(),L.each(function(n,i){U>n&&(r=t(i),a=r.offset().top,n&&(o[1]=a),o=[a],e[n]=o)}),o[1]=a+r.outerHeight()}),te=new Ee(J),ee=new ke(function(t){return P.eq(t)}),ne=new ke(function(t){return j.eq(t)})}function K(){function t(t,e){n.renderDayEvents(t,e)}function e(){n.getDaySegmentContainer().empty()}var n=this;n.renderEvents=t,n.clearEvents=e,me.call(n)}function te(t,e){function n(t,e){return t.clone().stripTime().add("weeks",e).startOf("week")}function r(t){a.intervalStart=t.clone().stripTime().startOf("week"),a.intervalEnd=a.intervalStart.clone().add("weeks",1),a.start=a.skipHiddenDays(a.intervalStart),a.end=a.skipHiddenDays(a.intervalEnd,-1,!0),a.title=e.formatRange(a.start,a.end.clone().subtract(1),a.opt("titleFormat")," — "),a.renderAgenda(a.getCellsPerWeek())}var a=this;a.incrementDate=n,a.render=r,ae.call(a,t,e,"agendaWeek")}function ee(t,e){function n(t,e){var n=t.clone().stripTime().add("days",e);return n=a.skipHiddenDays(n,0>e?-1:1)}function r(t){a.start=a.intervalStart=t.clone().stripTime(),a.end=a.intervalEnd=a.start.clone().add("days",1),a.title=e.formatDate(a.start,a.opt("titleFormat")),a.renderAgenda(1)}var a=this;a.incrementDate=n,a.render=r,ae.call(a,t,e,"agendaDay")}function ne(t,e){return e.longDateFormat("LT").replace(":mm","(:mm)").replace(/(\Wmm)$/,"($1)").replace(/\s*a$/i,"a")}function re(t,e){return e.longDateFormat("LT").replace(/\s*a$/i,"")}function ae(n,r,a){function o(t){xe=t,i(),$?l():s()}function i(){Fe=Le("theme")?"ui":"fc",Ne=Le("isRTL"),We=Le("columnFormat"),Ye=e.duration(Le("minTime")),Oe=e.duration(Le("maxTime")),me=e.duration(Le("slotDuration")),be=Le("snapDuration"),be=be?e.duration(be):me}function s(){var r,a,o,i,s=Fe+"-widget-header",c=Fe+"-widget-content",d=0===me.asMinutes()%15;for(l(),ee=t("<div style='position:absolute;z-index:2;left:0;width:100%'/>").appendTo(n),Le("allDaySlot")?(ne=t("<div class='fc-event-container' style='position:absolute;z-index:8;top:0;left:0'/>").appendTo(ee),r="<table style='width:100%' class='fc-agenda-allday' cellspacing='0'><tr><th class='"+s+" fc-agenda-axis'>"+(Le("allDayHTML")||R(Le("allDayText")))+"</th>"+"<td>"+"<div class='fc-day-content'><div style='position:relative'/></div>"+"</td>"+"<th class='"+s+" fc-agenda-gutter'>&nbsp;</th>"+"</tr>"+"</table>",re=t(r).appendTo(ee),ae=re.find("tr"),y(ae.find("td")),ee.append("<div class='fc-agenda-divider "+s+"'>"+"<div class='fc-agenda-divider-inner'/>"+"</div>")):ne=t([]),ie=t("<div style='position:absolute;width:100%;overflow-x:hidden;overflow-y:auto'/>").appendTo(ee),se=t("<div style='position:relative;width:100%;overflow:hidden'/>").appendTo(ie),le=t("<div class='fc-event-container' style='position:absolute;z-index:8;top:0;left:0'/>").appendTo(se),r="<table class='fc-agenda-slots' style='width:100%' cellspacing='0'><tbody>",a=e.duration(+Ye),Me=0;Oe>a;)o=q.start.clone().time(a),i=o.minutes(),r+="<tr class='fc-slot"+Me+" "+(i?"fc-minor":"")+"'>"+"<th class='fc-agenda-axis "+s+"'>"+(d&&i?"&nbsp;":R(Ge(o,Le("axisFormat"))))+"</th>"+"<td class='"+c+"'>"+"<div style='position:relative'>&nbsp;</div>"+"</td>"+"</tr>",a.add(me),Me++;r+="</tbody></table>",ce=t(r).appendTo(se),b(ce.find("td"))}function l(){var e=c();$&&$.remove(),$=t(e).appendTo(n),V=$.find("thead"),X=V.find("th").slice(1,-1),U=$.find("tbody"),G=U.find("td").slice(0,-1),Q=G.find("> div"),J=G.find(".fc-day-content > div"),K=G.eq(0),te=Q.eq(0),F(V.add(V.find("tr"))),F(U.add(U.find("tr")))}function c(){var t="<table style='width:100%' class='fc-agenda-days fc-border-separate' cellspacing='0'>"+d()+u()+"</table>";return t}function d(){var t,e,n,r=Fe+"-widget-header",a="";for(a+="<thead><tr>",Le("weekNumbers")?(t=Ve(0,0),e=Qe(t),Ne?e+=Le("weekNumberTitle"):e=Le("weekNumberTitle")+e,a+="<th class='fc-agenda-axis fc-week-number "+r+"'>"+R(e)+"</th>"):a+="<th class='fc-agenda-axis "+r+"'>&nbsp;</th>",n=0;xe>n;n++)t=Ve(0,n),a+="<th class='fc-"+Ae[t.day()]+" fc-col"+n+" "+r+"'>"+R(Ge(t,We))+"</th>";return a+="<th class='fc-agenda-gutter "+r+"'>&nbsp;</th>"+"</tr>"+"</thead>"}function u(){var t,e,n,a,o,i=Fe+"-widget-header",s=Fe+"-widget-content",l=r.getNow().stripTime(),c="";for(c+="<tbody><tr><th class='fc-agenda-axis "+i+"'>&nbsp;</th>",n="",e=0;xe>e;e++)t=Ve(0,e),o=["fc-col"+e,"fc-"+Ae[t.day()],s],t.isSame(l,"day")?o.push(Fe+"-state-highlight","fc-today"):l>t?o.push("fc-past"):o.push("fc-future"),a="<td class='"+o.join(" ")+"'>"+"<div>"+"<div class='fc-day-content'>"+"<div style='position:relative'>&nbsp;</div>"+"</div>"+"</div>"+"</td>",n+=a;return c+=n,c+="<td class='fc-agenda-gutter "+s+"'>&nbsp;</td>"+"</tr>"+"</tbody>"}function f(t){void 0===t&&(t=fe),fe=t,Je={};var e=U.position().top,n=ie.position().top,r=Math.min(t-e,ce.height()+n+1);te.height(r-T(K)),ee.css("top",e),ie.height(r-n-1);var a=ce.find("tr:first").height()+1,o=ce.find("tr:eq(1)").height();ye=(a+o)/2,De=me/be,Se=ye/De}function v(e){ue=e,_e.clear(),He.clear();var n=V.find("th:first");re&&(n=n.add(re.find("th:first"))),n=n.add(ce.find("th:first")),ve=0,g(n.width("").each(function(e,n){ve=Math.max(ve,t(n).outerWidth())}),ve);var r=$.find(".fc-agenda-gutter");re&&(r=r.add(re.find("th.fc-agenda-gutter")));var a=ie[0].clientWidth;pe=ie.width()-a,pe?(g(r,pe),r.show().prev().removeClass("fc-last")):r.hide().prev().addClass("fc-last"),he=Math.floor((a-ve)/xe),g(X.slice(0,-1),he)}function h(){function t(){ie.scrollTop(n)}var n=Y(e.duration(Le("scrollTime")))+1;t(),setTimeout(t,0)}function p(){h()}function y(t){t.click(D).mousedown(qe)}function b(t){t.click(D).mousedown(B)}function D(t){if(!Le("selectable")){var e=Math.min(xe-1,Math.floor((t.pageX-$.offset().left-ve)/he)),n=Ve(0,e),a=this.parentNode.className.match(/fc-slot(\d+)/);if(a){var o=parseInt(a[1],10);n.add(Ye+o*me),n=r.rezoneDate(n),Ze("dayClick",G[e],n,t)}else Ze("dayClick",G[e],n,t)}}function w(t,e,n){n&&ze.build();for(var r=Ue(t,e),a=0;r.length>a;a++){var o=r[a];y(C(o.row,o.leftCol,o.row,o.rightCol))}}function C(t,e,n,r){var a=ze.rect(t,e,n,r,ee);return Be(a,ee)}function E(t,e){t=t.clone().stripZone(),e=e.clone().stripZone();for(var n=0;xe>n;n++){var r=Ve(0,n),a=r.clone().add("days",1),o=t>r?t:r,i=e>a?a:e;if(i>o){var s=ze.rect(0,n,0,n,se),l=N(o,r),c=N(i,r);s.top=l,s.height=c-l,b(Be(s,se))}}}function S(t){return _e.left(t)}function k(t){return He.left(t)}function M(t){return _e.right(t)}function z(t){return He.right(t)}function _(t){return Le("allDaySlot")&&!t.row}function A(t){var n=Ve(0,t.col),a=t.row;return Le("allDaySlot")&&a--,a>=0&&(n.time(e.duration(Ye+a*be)),n=r.rezoneDate(n)),n}function N(t,n){return Y(e.duration(t.clone().stripZone()-n.clone().stripTime()))}function Y(t){if(Ye>t)return 0;if(t>=Oe)return ce.height();var e=(t-Ye)/me,n=Math.floor(e),r=e-n,a=Je[n];void 0===a&&(a=Je[n]=ce.find("tr").eq(n).find("td div")[0].offsetTop);var o=a-1+r*ye;return o=Math.max(o,0)}function O(t){return t.hasTime()?t.clone().add(me):t.clone().add("days",1)}function W(t,e){t.hasTime()||e.hasTime()?L(t,e):Le("allDaySlot")&&w(t,e,!0)}function L(e,n){var r=Le("selectHelper");if(ze.build(),r){var a=Xe(e).col;if(a>=0&&xe>a){var o=ze.rect(0,a,0,a,se),i=N(e,e),s=N(n,e);if(s>i){if(o.top=i,o.height=s-i,o.left+=2,o.width-=5,t.isFunction(r)){var l=r(e,n);l&&(o.position="absolute",de=t(l).css(o).appendTo(se))}else o.isStart=!0,o.isEnd=!0,de=t($e({title:"",start:e,end:n,className:["fc-select-helper"],editable:!1},o)),de.css("opacity",Le("dragOpacity"));de&&(b(de),se.append(de),g(de,o.width,!0),m(de,o.height,!0))}}}else E(e,n)}function Z(){Pe(),de&&(de.remove(),de=null)}function B(e){if(1==e.which&&Le("selectable")){Ie(e);var n;Re.start(function(t,e){if(Z(),t&&t.col==e.col&&!_(t)){var r=A(e),a=A(t);n=[r,r.clone().add(be),a,a.clone().add(be)].sort(x),L(n[0],n[3])}else n=null},e),t(document).one("mouseup",function(t){Re.stop(),n&&(+n[0]==+n[1]&&P(n[0],t),je(n[0],n[3],t))})}}function P(t,e){Ze("dayClick",G[Xe(t).col],t,e)}function j(t,e){Re.start(function(t){if(Pe(),t){var e=A(t),n=e.clone();e.hasTime()?(n.add(r.defaultTimedEventDuration),E(e,n)):(n.add(r.defaultAllDayEventDuration),w(e,n))}},e)}function I(t,e,n){var r=Re.stop();Pe(),r&&Ze("drop",t,A(r),e,n)}var q=this;q.renderAgenda=o,q.setWidth=v,q.setHeight=f,q.afterRender=p,q.computeDateTop=N,q.getIsCellAllDay=_,q.allDayRow=function(){return ae},q.getCoordinateGrid=function(){return ze},q.getHoverListener=function(){return Re},q.colLeft=S,q.colRight=M,q.colContentLeft=k,q.colContentRight=z,q.getDaySegmentContainer=function(){return ne},q.getSlotSegmentContainer=function(){return le},q.getSlotContainer=function(){return se},q.getRowCnt=function(){return 1},q.getColCnt=function(){return xe},q.getColWidth=function(){return he},q.getSnapHeight=function(){return Se},q.getSnapDuration=function(){return be},q.getSlotHeight=function(){return ye},q.getSlotDuration=function(){return me},q.getMinTime=function(){return Ye},q.getMaxTime=function(){return Oe},q.defaultSelectionEnd=O,q.renderDayOverlay=w,q.renderSelection=W,q.clearSelection=Z,q.reportDayClick=P,q.dragStart=j,q.dragStop=I,ge.call(q,n,r,a),Te.call(q),we.call(q),oe.call(q);var $,V,X,U,G,Q,J,K,te,ee,ne,re,ae,ie,se,le,ce,de,ue,fe,ve,he,pe,me,ye,be,De,Se,xe,Me,ze,Re,_e,He,Fe,Ne,Ye,Oe,We,Le=q.opt,Ze=q.trigger,Be=q.renderOverlay,Pe=q.clearOverlays,je=q.reportSelection,Ie=q.unselect,qe=q.daySelectionMousedown,$e=q.slotSegHtml,Ve=q.cellToDate,Xe=q.dateToCell,Ue=q.rangeToSegments,Ge=r.formatDate,Qe=r.calculateWeekNumber,Je={};
H(n.addClass("fc-agenda")),ze=new Ce(function(e,n){function r(t){return Math.max(l,Math.min(c,t))}var a,o,i;X.each(function(e,r){a=t(r),o=a.offset().left,e&&(i[1]=o),i=[o],n[e]=i}),i[1]=o+a.outerWidth(),Le("allDaySlot")&&(a=ae,o=a.offset().top,e[0]=[o,o+a.outerHeight()]);for(var s=se.offset().top,l=ie.offset().top,c=l+ie.outerHeight(),d=0;Me*De>d;d++)e.push([r(s+Se*d),r(s+Se*(d+1))])}),Re=new Ee(ze),_e=new ke(function(t){return Q.eq(t)}),He=new ke(function(t){return J.eq(t)})}function oe(){function n(t,e){var n,r=t.length,o=[],s=[];for(n=0;r>n;n++)t[n].allDay?o.push(t[n]):s.push(t[n]);v("allDaySlot")&&(V(o,e),w()),i(a(s),e)}function r(){C().empty(),E().empty()}function a(t){var e,n,r,a,i,s=H(),l=X(),c=U(),d=[];for(n=0;s>n;n++)for(e=_(0,n),i=o(t,e.clone().time(l),e.clone().time(c)),i=ie(i),r=0;i.length>r;r++)a=i[r],a.col=n,d.push(a);return d}function o(t,e,n){e=e.clone().stripZone(),n=n.clone().stripZone();var r,a,o,i,s,l,c,d,u=[],f=t.length;for(r=0;f>r;r++)a=t[r],o=a.start.clone().stripZone(),i=J(a).stripZone(),i>e&&n>o&&(e>o?(s=e.clone(),c=!1):(s=o,c=!0),i>n?(l=n.clone(),d=!1):(l=i,d=!0),u.push({event:a,start:s,end:l,isStart:c,isEnd:d}));return u.sort(pe)}function i(e,n){var r,a,o,i,c,d,u,f,g,m,b,D,w,C,S,x,R=e.length,_="",H=E(),F=v("isRTL");for(r=0;R>r;r++)a=e[r],o=a.event,i=k(a.start,a.start),c=k(a.end,a.start),d=M(a.col),u=z(a.col),f=u-d,u-=.025*f,f=u-d,g=f*(a.forwardCoord-a.backwardCoord),v("slotEventOverlap")&&(g=Math.max(2*(g-10),g)),F?(b=u-a.backwardCoord*f,m=b-g):(m=d+a.backwardCoord*f,b=m+g),m=Math.max(m,d),b=Math.min(b,u),g=b-m,a.top=i,a.left=m,a.outerWidth=g,a.outerHeight=c-i,_+=s(o,a);for(H[0].innerHTML=_,D=H.children(),r=0;R>r;r++)a=e[r],o=a.event,w=t(D[r]),C=h("eventRender",o,o,w),C===!1?w.remove():(C&&C!==!0&&(w.remove(),w=t(C).css({position:"absolute",top:a.top,left:a.left}).appendTo(H)),a.element=w,o._id===n?l(o,w,a):w[0]._fci=r,Z(o,w));for(p(H,e,l),r=0;R>r;r++)a=e[r],(w=a.element)&&(a.vsides=T(w,!0),a.hsides=y(w,!0),S=w.find(".fc-event-title"),S.length&&(a.contentTop=S[0].offsetTop));for(r=0;R>r;r++)a=e[r],(w=a.element)&&(w[0].style.width=Math.max(0,a.outerWidth-a.hsides)+"px",x=Math.max(0,a.outerHeight-a.vsides),w[0].style.height=x+"px",o=a.event,void 0!==a.contentTop&&10>x-a.contentTop&&(w.find("div.fc-event-time").text(Q(o.start,v("timeFormat"))+" - "+o.title),w.find("div.fc-event-title").remove()),h("eventAfterRender",o,o,w))}function s(t,e){var n="<",r=t.url,a=A(t,v),o=["fc-event","fc-event-vert"];return g(t)&&o.push("fc-event-draggable"),e.isStart&&o.push("fc-event-start"),e.isEnd&&o.push("fc-event-end"),o=o.concat(t.className),t.source&&(o=o.concat(t.source.className||[])),n+=r?"a href='"+R(t.url)+"'":"div",n+=" class='"+o.join(" ")+"'"+" style="+"'"+"position:absolute;"+"top:"+e.top+"px;"+"left:"+e.left+"px;"+a+"'"+">"+"<div class='fc-event-inner'>"+"<div class='fc-event-time'>"+R(f.getEventTimeText(t))+"</div>"+"<div class='fc-event-title'>"+R(t.title||"")+"</div>"+"</div>"+"<div class='fc-event-bg'></div>",e.isEnd&&b(t)&&(n+="<div class='ui-resizable-handle ui-resizable-s'>=</div>"),n+="</"+(r?"a":"div")+">"}function l(t,e,n){var r=e.find("div.fc-event-time");g(t)&&d(t,e,r),n.isEnd&&b(t)&&u(t,e,r),D(t,e)}function c(t,n,r){function a(){c||(n.width(o).height("").draggable("option","grid",null),c=!0)}var o,i,s,l=r.isStart,c=!0,d=S(),u=F(),f=X(),p=W(),g=O(),y=Y(),b=N();n.draggable({opacity:v("dragOpacity","month"),revertDuration:v("dragRevertDuration"),start:function(e,r){h("eventDragStart",n[0],t,e,r),P(t,n),o=n.width(),d.start(function(e,r){if($(),e){i=!1;var o=_(0,r.col),d=_(0,e.col);s=d.diff(o,"days"),e.row?l?c&&(n.width(u-10),m(n,G.defaultTimedEventDuration/p*g),n.draggable("option","grid",[u,1]),c=!1):i=!0:(q(t.start.clone().add("days",s),J(t).add("days",s)),a()),i=i||c&&!s}else a(),i=!0;n.draggable("option","revert",i)},e,"drag")},stop:function(r,o){if(d.stop(),$(),h("eventDragStop",n[0],t,r,o),i)a(),n.css("filter",""),B(t,n);else{var l,u,v=t.start.clone().add("days",s);c||(u=Math.round((n.offset().top-L().offset().top)/b),l=e.duration(f+u*y),v=G.rezoneDate(v.clone().time(l))),j(n[0],t,v,r,o)}}})}function d(t,e,n){function r(){$(),s&&(c?(n.hide(),e.draggable("option","grid",null),q(b,D)):(a(),n.css("display",""),e.draggable("option","grid",[C,E])))}function a(){b&&n.text(f.getEventTimeText(b,t.end?D:null))}var o,i,s,l,c,d,u,p,g,m,y,b,D,w=f.getCoordinateGrid(),T=H(),C=F(),E=N(),S=Y();e.draggable({scroll:!1,grid:[C,E],axis:1==T?"y":!1,opacity:v("dragOpacity"),revertDuration:v("dragRevertDuration"),start:function(n,r){h("eventDragStart",e[0],t,n,r),P(t,e),w.build(),o=e.position(),i=w.cell(n.pageX,n.pageY),s=l=!0,c=d=x(i),u=p=0,g=0,m=y=0,b=null,D=null},drag:function(n,a){var f=w.cell(n.pageX,n.pageY);if(s=!!f){if(c=x(f),u=Math.round((a.position.left-o.left)/C),u!=p){var v=_(0,i.col),h=i.col+u;h=Math.max(0,h),h=Math.min(T-1,h);var k=_(0,h);g=k.diff(v,"days")}c||(m=Math.round((a.position.top-o.top)/E))}(s!=l||c!=d||u!=p||m!=y)&&(c?(b=t.start.clone().stripTime().add("days",g),D=b.clone().add(G.defaultAllDayEventDuration)):(b=t.start.clone().add(m*S).add("days",g),D=J(t).add(m*S).add("days",g)),r(),l=s,d=c,p=u,y=m),e.draggable("option","revert",!s)},stop:function(n,a){$(),h("eventDragStop",e[0],t,n,a),s&&(c||g||m)?j(e[0],t,b,n,a):(s=!0,c=!1,u=0,g=0,m=0,r(),e.css("filter",""),e.css(o),B(t,e))}})}function u(t,e,n){var r,a,o,i=N(),s=Y();e.resizable({handles:{s:".ui-resizable-handle"},grid:i,start:function(n,o){r=a=0,P(t,e),h("eventResizeStart",e[0],t,n,o)},resize:function(l,c){if(r=Math.round((Math.max(i,e.height())-c.originalSize.height)/i),r!=a){o=J(t).add(s*r);var d;d=r?f.getEventTimeText(t.start,o):f.getEventTimeText(t),n.text(d),a=r}},stop:function(n,a){h("eventResizeStop",e[0],t,n,a),r?I(e[0],t,o,n,a):B(t,e)}})}var f=this;f.renderEvents=n,f.clearEvents=r,f.slotSegHtml=s,me.call(f);var v=f.opt,h=f.trigger,g=f.isEventDraggable,b=f.isEventResizable,D=f.eventElementHandlers,w=f.setHeight,C=f.getDaySegmentContainer,E=f.getSlotSegmentContainer,S=f.getHoverListener,k=f.computeDateTop,x=f.getIsCellAllDay,M=f.colContentLeft,z=f.colContentRight,_=f.cellToDate,H=f.getColCnt,F=f.getColWidth,N=f.getSnapHeight,Y=f.getSnapDuration,O=f.getSlotHeight,W=f.getSlotDuration,L=f.getSlotContainer,Z=f.reportEventElement,B=f.showEvents,P=f.hideEvents,j=f.eventDrop,I=f.eventResize,q=f.renderDayOverlay,$=f.clearOverlays,V=f.renderDayEvents,X=f.getMinTime,U=f.getMaxTime,G=f.calendar,Q=G.formatDate,J=G.getEventEnd;f.draggableDayEvent=c}function ie(t){var e,n=se(t),r=n[0];if(le(n),r){for(e=0;r.length>e;e++)ce(r[e]);for(e=0;r.length>e;e++)de(r[e],0,0)}return ue(n)}function se(t){var e,n,r,a=[];for(e=0;t.length>e;e++){for(n=t[e],r=0;a.length>r&&fe(n,a[r]).length;r++);(a[r]||(a[r]=[])).push(n)}return a}function le(t){var e,n,r,a,o;for(e=0;t.length>e;e++)for(n=t[e],r=0;n.length>r;r++)for(a=n[r],a.forwardSegs=[],o=e+1;t.length>o;o++)fe(a,t[o],a.forwardSegs)}function ce(t){var e,n,r=t.forwardSegs,a=0;if(void 0===t.forwardPressure){for(e=0;r.length>e;e++)n=r[e],ce(n),a=Math.max(a,1+n.forwardPressure);t.forwardPressure=a}}function de(t,e,n){var r,a=t.forwardSegs;if(void 0===t.forwardCoord)for(a.length?(a.sort(he),de(a[0],e+1,n),t.forwardCoord=a[0].backwardCoord):t.forwardCoord=1,t.backwardCoord=t.forwardCoord-(t.forwardCoord-n)/(e+1),r=0;a.length>r;r++)de(a[r],0,t.forwardCoord)}function ue(t){var e,n,r,a=[];for(e=0;t.length>e;e++)for(n=t[e],r=0;n.length>r;r++)a.push(n[r]);return a}function fe(t,e,n){n=n||[];for(var r=0;e.length>r;r++)ve(t,e[r])&&n.push(e[r]);return n}function ve(t,e){return t.end>e.start&&t.start<e.end}function he(t,e){return e.forwardPressure-t.forwardPressure||(t.backwardCoord||0)-(e.backwardCoord||0)||pe(t,e)}function pe(t,e){return t.start-e.start||e.end-e.start-(t.end-t.start)||(t.event.title||"").localeCompare(e.event.title)}function ge(n,r,a){function o(e,n){var r=O[e];return t.isPlainObject(r)&&!i(e)?z(r,n||a):r}function s(t,e){return r.trigger.apply(r,[t,e||H].concat(Array.prototype.slice.call(arguments,2),[H]))}function l(t){var e=t.source||{};return Y(t.startEditable,e.startEditable,o("eventStartEditable"),t.editable,e.editable,o("editable"))}function c(t){var e=t.source||{};return Y(t.durationEditable,e.durationEditable,o("eventDurationEditable"),t.editable,e.editable,o("editable"))}function d(){A={},N=[]}function u(t,e){N.push({event:t,element:e}),A[t._id]?A[t._id].push(e):A[t._id]=[e]}function f(){t.each(N,function(t,e){H.trigger("eventDestroy",e.event,e.event,e.element)})}function v(t,e){e.click(function(n){return e.hasClass("ui-draggable-dragging")||e.hasClass("ui-resizable-resizing")?void 0:s("eventClick",this,t,n)}).hover(function(e){s("eventMouseover",this,t,e)},function(e){s("eventMouseout",this,t,e)})}function h(t,e){g(t,e,"show")}function p(t,e){g(t,e,"hide")}function g(t,e,n){var r,a=A[t._id],o=a.length;for(r=0;o>r;r++)e&&a[r][0]==e[0]||a[r][n]()}function m(t,e,n,a,o){var i=r.mutateEvent(e,n,null);s("eventDrop",t,e,i.dateDelta,function(){i.undo(),F(e._id)},a,o),F(e._id)}function y(t,e,n,a,o){var i=r.mutateEvent(e,null,n);s("eventResize",t,e,i.durationDelta,function(){i.undo(),F(e._id)},a,o),F(e._id)}function b(t){return e.isMoment(t)&&(t=t.day()),B[t]}function D(){return L}function w(t,e,n){var r=t.clone();for(e=e||1;B[(r.day()+(n?e:0)+7)%7];)r.add("days",e);return r}function T(){var t=C.apply(null,arguments),e=E(t),n=S(e);return n}function C(t,e){var n=H.getColCnt(),r=I?-1:1,a=I?n-1:0;"object"==typeof t&&(e=t.col,t=t.row);var o=t*n+(e*r+a);return o}function E(t){var e=H.start.day();return t+=P[e],7*Math.floor(t/L)+j[(t%L+L)%L]-e}function S(t){return H.start.clone().add("days",t)}function k(t){var e=x(t),n=M(e),r=R(n);return r}function x(t){return t.clone().stripTime().diff(H.start,"days")}function M(t){var e=H.start.day();return t+=e,Math.floor(t/7)*L+P[(t%7+7)%7]-P[e]}function R(t){var e=H.getColCnt(),n=I?-1:1,r=I?e-1:0,a=Math.floor(t/e),o=(t%e+e)%e*n+r;return{row:a,col:o}}function _(t,e){var n=H.getRowCnt(),r=H.getColCnt(),a=[],o=x(t),i=x(e),s=+e.time();s&&s>=W&&i++,i=Math.max(i,o+1);for(var l=M(o),c=M(i)-1,d=0;n>d;d++){var u=d*r,f=u+r-1,v=Math.max(l,u),h=Math.min(c,f);if(h>=v){var p=R(v),g=R(h),m=[p.col,g.col].sort(),y=E(v)==o,b=E(h)+1==i;a.push({row:d,leftCol:m[0],rightCol:m[1],isStart:y,isEnd:b})}}return a}var H=this;H.element=n,H.calendar=r,H.name=a,H.opt=o,H.trigger=s,H.isEventDraggable=l,H.isEventResizable=c,H.clearEventData=d,H.reportEventElement=u,H.triggerEventDestroy=f,H.eventElementHandlers=v,H.showEvents=h,H.hideEvents=p,H.eventDrop=m,H.eventResize=y;var F=r.reportEventChange,A={},N=[],O=r.options,W=e.duration(O.nextDayThreshold);H.getEventTimeText=function(t){var e,n;return 2===arguments.length?(e=arguments[0],n=arguments[1]):(e=t.start,n=t.end),n&&o("displayEventEnd")?r.formatRange(e,n,o("timeFormat")):r.formatDate(e,o("timeFormat"))},H.isHiddenDay=b,H.skipHiddenDays=w,H.getCellsPerWeek=D,H.dateToCell=k,H.dateToDayOffset=x,H.dayOffsetToCellOffset=M,H.cellOffsetToCell=R,H.cellToDate=T,H.cellToCellOffset=C,H.cellOffsetToDayOffset=E,H.dayOffsetToDate=S,H.rangeToSegments=_;var L,Z=o("hiddenDays")||[],B=[],P=[],j=[],I=o("isRTL");(function(){o("weekends")===!1&&Z.push(0,6);for(var e=0,n=0;7>e;e++)P[e]=n,B[e]=-1!=t.inArray(e,Z),B[e]||(j[n]=e,n++);if(L=n,!L)throw"invalid hiddenDays"})()}function me(){function e(t,e){var n=r(t,!1,!0);be(n,function(t,e){x(t.event,e)}),m(n,e),be(n,function(t,e){E("eventAfterRender",t.event,t.event,e)})}function n(t,e,n){var a=r([t],!0,!1),o=[];return be(a,function(t,r){t.row===e&&r.css("top",n),o.push(r[0])}),o}function r(e,n,r){var o,l,u=I(),f=n?t("<div/>"):u,v=a(e);return i(v),o=s(v),f[0].innerHTML=o,l=f.children(),n&&u.append(l),c(v,l),be(v,function(t,e){t.hsides=y(e,!0)}),be(v,function(t,e){e.width(Math.max(0,t.outerWidth-t.hsides))}),be(v,function(t,e){t.outerHeight=e.outerHeight(!0)}),d(v,r),v}function a(t){for(var e=[],n=0;t.length>n;n++){var r=o(t[n]);e.push.apply(e,r)}return e}function o(t){for(var e=U(t.start,ne(t)),n=0;e.length>n;n++)e[n].event=t;return e}function i(t){for(var e=C("isRTL"),n=0;t.length>n;n++){var r=t[n],a=(e?r.isEnd:r.isStart)?P:Z,o=(e?r.isStart:r.isEnd)?j:B,i=a(r.leftCol),s=o(r.rightCol);r.left=i,r.outerWidth=s-i}}function s(t){for(var e="",n=0;t.length>n;n++)e+=l(t[n]);return e}function l(t){var e="",n=C("isRTL"),r=t.event,a=r.url,o=["fc-event","fc-event-hori"];S(r)&&o.push("fc-event-draggable"),t.isStart&&o.push("fc-event-start"),t.isEnd&&o.push("fc-event-end"),o=o.concat(r.className),r.source&&(o=o.concat(r.source.className||[]));var i=A(r,C);return e+=a?"<a href='"+R(a)+"'":"<div",e+=" class='"+o.join(" ")+"'"+" style="+"'"+"position:absolute;"+"left:"+t.left+"px;"+i+"'"+">"+"<div class='fc-event-inner'>",!r.allDay&&t.isStart&&(e+="<span class='fc-event-time'>"+R(T.getEventTimeText(r))+"</span>"),e+="<span class='fc-event-title'>"+R(r.title||"")+"</span>"+"</div>",r.allDay&&t.isEnd&&k(r)&&(e+="<div class='ui-resizable-handle ui-resizable-"+(n?"w":"e")+"'>"+"&nbsp;&nbsp;&nbsp;"+"</div>"),e+="</"+(a?"a":"div")+">"}function c(e,n){for(var r=0;e.length>r;r++){var a=e[r],o=a.event,i=n.eq(r),s=E("eventRender",o,o,i);s===!1?i.remove():(s&&s!==!0&&(s=t(s).css({position:"absolute",left:a.left}),i.replaceWith(s),i=s),a.element=i)}}function d(t,e){var n,r=u(t),a=g(),o=[];if(e)for(n=0;a.length>n;n++)a[n].height(r[n]);for(n=0;a.length>n;n++)o.push(a[n].position().top);be(t,function(t,e){e.css("top",o[t.row]+t.top)})}function u(t){for(var e,n=O(),r=W(),a=[],o=f(t),i=0;n>i;i++){var s=o[i],l=[];for(e=0;r>e;e++)l.push(0);for(var c=0;s.length>c;c++){var d=s[c];for(d.top=M(l.slice(d.leftCol,d.rightCol+1)),e=d.leftCol;d.rightCol>=e;e++)l[e]=d.top+d.outerHeight}a.push(M(l))}return a}function f(t){var e,n,r,a=O(),o=[];for(e=0;t.length>e;e++)n=t[e],r=n.row,n.element&&(o[r]?o[r].push(n):o[r]=[n]);for(r=0;a>r;r++)o[r]=v(o[r]||[]);return o}function v(t){for(var e=[],n=h(t),r=0;n.length>r;r++)e.push.apply(e,n[r]);return e}function h(t){t.sort(De);for(var e=[],n=0;t.length>n;n++){for(var r=t[n],a=0;e.length>a&&ye(r,e[a]);a++);e[a]?e[a].push(r):e[a]=[r]}return e}function g(){var t,e=O(),n=[];for(t=0;e>t;t++)n[t]=L(t).find("div.fc-day-content > div");return n}function m(t,e){var n=I();be(t,function(t,n,r){var a=t.event;a._id===e?b(a,n,t):n[0]._fci=r}),p(n,t,b)}function b(t,e,n){S(t)&&T.draggableDayEvent(t,e,n),t.allDay&&n.isEnd&&k(t)&&T.resizableDayEvent(t,e,n),z(t,e)}function D(t,e){var n,r,a=X();e.draggable({delay:50,opacity:C("dragOpacity"),revertDuration:C("dragRevertDuration"),start:function(o,i){E("eventDragStart",e[0],t,o,i),F(t,e),a.start(function(a,o,i,s){if(e.draggable("option","revert",!a||!i&&!s),$(),a){var l=G(o),c=G(a);n=c.diff(l,"days"),r=t.start.clone().add("days",n),q(r,ne(t).add("days",n))}else n=0},o,"drag")},stop:function(o,i){a.stop(),$(),E("eventDragStop",e[0],t,o,i),n?N(e[0],t,r,o,i):(e.css("filter",""),_(t,e))}})}function w(e,r,a){var o=C("isRTL"),i=o?"w":"e",s=r.find(".ui-resizable-"+i),l=!1;H(r),r.mousedown(function(t){t.preventDefault()}).click(function(t){l&&(t.preventDefault(),t.stopImmediatePropagation())}),s.mousedown(function(o){function s(n){E("eventResizeStop",r[0],e,n,{}),t("body").css("cursor",""),f.stop(),$(),c&&Y(r[0],e,d,n,{}),setTimeout(function(){l=!1},0)}if(1==o.which){l=!0;var c,d,u,f=X(),v=r.css("top"),h=t.extend({},e),p=te(K(e.start));V(),t("body").css("cursor",i+"-resize").one("mouseup",s),E("eventResizeStart",r[0],e,o,{}),f.start(function(r,o){if(r){var s=Q(o),l=Q(r);if(l=Math.max(l,p),c=J(l)-J(s),d=ne(e).add("days",c),c){h.end=d;var f=u;u=n(h,a.row,v),u=t(u),u.find("*").css("cursor",i+"-resize"),f&&f.remove(),F(e)}else u&&(_(e),u.remove(),u=null);$(),q(e.start,d)}},o)}})}var T=this;T.renderDayEvents=e,T.draggableDayEvent=D,T.resizableDayEvent=w;var C=T.opt,E=T.trigger,S=T.isEventDraggable,k=T.isEventResizable,x=T.reportEventElement,z=T.eventElementHandlers,_=T.showEvents,F=T.hideEvents,N=T.eventDrop,Y=T.eventResize,O=T.getRowCnt,W=T.getColCnt,L=T.allDayRow,Z=T.colLeft,B=T.colRight,P=T.colContentLeft,j=T.colContentRight,I=T.getDaySegmentContainer,q=T.renderDayOverlay,$=T.clearOverlays,V=T.clearSelection,X=T.getHoverListener,U=T.rangeToSegments,G=T.cellToDate,Q=T.cellToCellOffset,J=T.cellOffsetToDayOffset,K=T.dateToDayOffset,te=T.dayOffsetToCellOffset,ee=T.calendar,ne=ee.getEventEnd}function ye(t,e){for(var n=0;e.length>n;n++){var r=e[n];if(r.leftCol<=t.rightCol&&r.rightCol>=t.leftCol)return!0}return!1}function be(t,e){for(var n=0;t.length>n;n++){var r=t[n],a=r.element;a&&e(r,a,n)}}function De(t,e){return e.rightCol-e.leftCol-(t.rightCol-t.leftCol)||e.event.allDay-t.event.allDay||t.event.start-e.event.start||(t.event.title||"").localeCompare(e.event.title)}function we(){function e(e){var n=c("unselectCancel");n&&t(e.target).parents(n).length||r(e)}function n(t,e){r(),t=l.moment(t),e=e?l.moment(e):u(t),f(t,e),a(t,e)}function r(t){h&&(h=!1,v(),d("unselect",null,t))}function a(t,e,n){h=!0,d("select",null,t,e,n)}function o(e){var n=s.cellToDate,o=s.getIsCellAllDay,i=s.getHoverListener(),l=s.reportDayClick;if(1==e.which&&c("selectable")){r(e);var d;i.start(function(t,e){v(),t&&o(t)?(d=[n(e),n(t)].sort(x),f(d[0],d[1].clone().add("days",1))):d=null},e),t(document).one("mouseup",function(t){i.stop(),d&&(+d[0]==+d[1]&&l(d[0],t),a(d[0],d[1].clone().add("days",1),t))})}}function i(){t(document).off("mousedown",e)}var s=this;s.select=n,s.unselect=r,s.reportSelection=a,s.daySelectionMousedown=o,s.selectionManagerDestroy=i;var l=s.calendar,c=s.opt,d=s.trigger,u=s.defaultSelectionEnd,f=s.renderSelection,v=s.clearSelection,h=!1;c("selectable")&&c("unselectAuto")&&t(document).on("mousedown",e)}function Te(){function e(e,n){var r=o.shift();return r||(r=t("<div class='fc-cell-overlay' style='position:absolute;z-index:3'/>")),r[0].parentNode!=n[0]&&r.appendTo(n),a.push(r.css(e).show()),r}function n(){for(var t;t=a.shift();)o.push(t.hide().unbind())}var r=this;r.renderOverlay=e,r.clearOverlays=n;var a=[],o=[]}function Ce(t){var e,n,r=this;r.build=function(){e=[],n=[],t(e,n)},r.cell=function(t,r){var a,o=e.length,i=n.length,s=-1,l=-1;for(a=0;o>a;a++)if(r>=e[a][0]&&e[a][1]>r){s=a;break}for(a=0;i>a;a++)if(t>=n[a][0]&&n[a][1]>t){l=a;break}return s>=0&&l>=0?{row:s,col:l}:null},r.rect=function(t,r,a,o,i){var s=i.offset();return{top:e[t][0]-s.top,left:n[r][0]-s.left,width:n[o][1]-n[r][0],height:e[a][1]-e[t][0]}}}function Ee(e){function n(t){Se(t);var n=e.cell(t.pageX,t.pageY);(Boolean(n)!==Boolean(i)||n&&(n.row!=i.row||n.col!=i.col))&&(n?(o||(o=n),a(n,o,n.row-o.row,n.col-o.col)):a(n,o),i=n)}var r,a,o,i,s=this;s.start=function(s,l,c){a=s,o=i=null,e.build(),n(l),r=c||"mousemove",t(document).bind(r,n)},s.stop=function(){return t(document).unbind(r,n),i}}function Se(t){void 0===t.pageX&&(t.pageX=t.originalEvent.pageX,t.pageY=t.originalEvent.pageY)}function ke(t){function e(e){return r[e]=r[e]||t(e)}var n=this,r={},a={},o={};n.left=function(t){return a[t]=void 0===a[t]?e(t).position().left:a[t]},n.right=function(t){return o[t]=void 0===o[t]?n.left(t)+e(t).width():o[t]},n.clear=function(){r={},a={},o={}}}var xe={lang:"en",defaultTimedEventDuration:"02:00:00",defaultAllDayEventDuration:{days:1},forceEventDuration:!1,nextDayThreshold:"09:00:00",defaultView:"month",aspectRatio:1.35,header:{left:"title",center:"",right:"today prev,next"},weekends:!0,weekNumbers:!1,weekNumberTitle:"W",weekNumberCalculation:"local",lazyFetching:!0,startParam:"start",endParam:"end",timezoneParam:"timezone",timezone:!1,titleFormat:{month:"MMMM YYYY",week:"ll",day:"LL"},columnFormat:{month:"ddd",week:r,day:"dddd"},timeFormat:{"default":n},displayEventEnd:{month:!1,basicWeek:!1,"default":!0},isRTL:!1,defaultButtonText:{prev:"prev",next:"next",prevYear:"prev year",nextYear:"next year",today:"today",month:"month",week:"week",day:"day"},buttonIcons:{prev:"left-single-arrow",next:"right-single-arrow",prevYear:"left-double-arrow",nextYear:"right-double-arrow"},theme:!1,themeButtonIcons:{prev:"circle-triangle-w",next:"circle-triangle-e",prevYear:"seek-prev",nextYear:"seek-next"},unselectAuto:!0,dropAccept:"*",handleWindowResize:!0,windowResizeDelay:200},Me={en:{columnFormat:{week:"ddd M/D"}}},ze={header:{left:"next,prev today",center:"",right:"title"},buttonIcons:{prev:"right-single-arrow",next:"left-single-arrow",prevYear:"right-double-arrow",nextYear:"left-double-arrow"},themeButtonIcons:{prev:"circle-triangle-e",next:"circle-triangle-w",nextYear:"seek-prev",prevYear:"seek-next"}},Re=t.fullCalendar={version:"2.0.2"},_e=Re.views={};t.fn.fullCalendar=function(e){var n=Array.prototype.slice.call(arguments,1),r=this;return this.each(function(a,o){var i,l=t(o),c=l.data("fullCalendar");"string"==typeof e?c&&t.isFunction(c[e])&&(i=c[e].apply(c,n),a||(r=i),"destroy"===e&&l.removeData("fullCalendar")):c||(c=new s(l,e),l.data("fullCalendar",c),c.render())}),r},Re.langs=Me,Re.datepickerLang=function(e,n,r){var a=Me[e];a||(a=Me[e]={}),o(a,{isRTL:r.isRTL,weekNumberTitle:r.weekHeader,titleFormat:{month:r.showMonthAfterYear?"YYYY["+r.yearSuffix+"] MMMM":"MMMM YYYY["+r.yearSuffix+"]"},defaultButtonText:{prev:_(r.prevText),next:_(r.nextText),today:_(r.currentText)}}),t.datepicker&&(t.datepicker.regional[n]=t.datepicker.regional[e]=r,t.datepicker.regional.en=t.datepicker.regional[""],t.datepicker.setDefaults(r))},Re.lang=function(t,e){var n;e&&(n=Me[t],n||(n=Me[t]={}),o(n,e||{})),xe.lang=t},Re.sourceNormalizers=[],Re.sourceFetchers=[];var He={dataType:"json",cache:!1},Fe=1;Re.applyAll=N;var Ae=["sun","mon","tue","wed","thu","fri","sat"],Ne=/^\s*\d{4}-\d\d$/,Ye=/^\s*\d{4}-(?:(\d\d-\d\d)|(W\d\d$)|(W\d\d-\d)|(\d\d\d))((T| )(\d\d(:\d\d(:\d\d(\.\d+)?)?)?)?)?$/;Re.moment=function(){return O(arguments)},Re.moment.utc=function(){var t=O(arguments,!0);return t.hasTime()&&t.utc(),t},Re.moment.parseZone=function(){return O(arguments,!0,!0)},W.prototype=u(e.fn),W.prototype.clone=function(){return O([this])},W.prototype.time=function(t){if(null==t)return e.duration({hours:this.hours(),minutes:this.minutes(),seconds:this.seconds(),milliseconds:this.milliseconds()});delete this._ambigTime,e.isDuration(t)||e.isMoment(t)||(t=e.duration(t));var n=0;return e.isDuration(t)&&(n=24*Math.floor(t.asDays())),this.hours(n+t.hours()).minutes(t.minutes()).seconds(t.seconds()).milliseconds(t.milliseconds())},W.prototype.stripTime=function(){var t=this.toArray();return e.fn.utc.call(this),this.year(t[0]).month(t[1]).date(t[2]).hours(0).minutes(0).seconds(0).milliseconds(0),this._ambigTime=!0,this._ambigZone=!0,this},W.prototype.hasTime=function(){return!this._ambigTime},W.prototype.stripZone=function(){var t=this.toArray(),n=this._ambigTime;return e.fn.utc.call(this),this.year(t[0]).month(t[1]).date(t[2]).hours(t[3]).minutes(t[4]).seconds(t[5]).milliseconds(t[6]),n&&(this._ambigTime=!0),this._ambigZone=!0,this},W.prototype.hasZone=function(){return!this._ambigZone},W.prototype.zone=function(t){return null!=t&&(delete this._ambigTime,delete this._ambigZone),e.fn.zone.apply(this,arguments)},W.prototype.local=function(){var t=this.toArray(),n=this._ambigZone;return delete this._ambigTime,delete this._ambigZone,e.fn.local.apply(this,arguments),n&&this.year(t[0]).month(t[1]).date(t[2]).hours(t[3]).minutes(t[4]).seconds(t[5]).milliseconds(t[6]),this},W.prototype.utc=function(){return delete this._ambigTime,delete this._ambigZone,e.fn.utc.apply(this,arguments)},W.prototype.format=function(){return arguments[0]?B(this,arguments[0]):this._ambigTime?Z(this,"YYYY-MM-DD"):this._ambigZone?Z(this,"YYYY-MM-DD[T]HH:mm:ss"):Z(this)},W.prototype.toISOString=function(){return this._ambigTime?Z(this,"YYYY-MM-DD"):this._ambigZone?Z(this,"YYYY-MM-DD[T]HH:mm:ss"):e.fn.toISOString.apply(this,arguments)},W.prototype.isWithin=function(t,e){var n=L([this,t,e]);return n[0]>=n[1]&&n[0]<n[2]},t.each(["isBefore","isAfter","isSame"],function(t,n){W.prototype[n]=function(t,r){var a=L([this,t]);return e.fn[n].call(a[0],a[1],r)}});var Oe={t:function(t){return Z(t,"a").charAt(0)},T:function(t){return Z(t,"A").charAt(0)}};Re.formatRange=I;var We={Y:"year",M:"month",D:"day",d:"day",A:"second",a:"second",T:"second",t:"second",H:"second",h:"second",m:"second",s:"second"},Le={};_e.month=U,_e.basicWeek=G,_e.basicDay=Q,a({weekMode:"fixed"}),_e.agendaWeek=te,_e.agendaDay=ee,a({allDaySlot:!0,allDayText:"all-day",scrollTime:"06:00:00",slotDuration:"00:30:00",axisFormat:ne,timeFormat:{agenda:re},dragOpacity:{agenda:.5},minTime:"00:00:00",maxTime:"24:00:00",slotEventOverlap:!0})});/* date add hours function */
Date.prototype.addHours= function(h){
    this.setHours(this.getHours()+h);
    return this;
}
/* events related jquery coding */
jQuery(function ($) {
	var dt = new Date();
	var dd = dt.getDate();
	var mm = dt.getMonth()+1; //January is 0!
	var yyyy = dt.getFullYear();
	
	//Ui Tab intiate
	$( "#tabs" ).tabs();
	$('a.gallery').colorbox({height:"100%"});
	
	//Datetime picker
    $( ".start_date, .start_time").datetimepicker(
	{

		altField: ".start_time",
		minDateTime:dt,
		dateFormat: 'M d, yy', 
		timeFormat: 'hh:mm TT',
		altTimeFormat: 'hh:mm TT',
		onSelect: function( selectedDate ) {
			var rel = $(this).attr('rel');
			
			if( selectedDate != '' ){
				var date = new Date(selectedDate);
				var yr = date.getFullYear();
				var mo = date.getMonth() + 1;
				var day = date.getDate();

				var hours 	= $('#start_time-'+rel).val().substr(0, 2);
				var minutes	= $('#start_time-'+rel).val().substr(3, 2);
				var pm_am	= $('#start_time-'+rel).val().substr(6, 2);
				var sec		= '00';
				
				var selectedCurrentDate =new Date(yr + ',' + mo  + ',' + day + ' ' + hours + ':' + minutes + ':' + sec + ' ' + pm_am);
				
				if ( !isNaN( selectedCurrentDate.getTime() ) ) {
						
					var sEndD = new Date( $('#end_date-'+rel).datetimepicker('getDate') ); 

					if( $('#end_date-'+rel).val() == '' ){ 
						
						hours = parseInt(hours) + 2;

						if( hours >= 12 ){
							if( pm_am == 'PM' ){
								day = day + 1;
							}
							hours = hours - 12;
							pm_am = ( pm_am == 'PM' ? 'AM' : 'PM' );
						}

						var newDateString = yr + ',' + mo  + ',' + day;
						var newTimeString = hours + ':' + minutes + ':' + sec;

						var excelDateString =new Date(newDateString + ' ' + newTimeString + ' ' + pm_am);
					
						$( "#end_date-"+rel).datetimepicker('setDate', excelDateString);
                        $("#ui-datepicker-div").fadeOut();

						
					}else{

						
						var testEndDate		= new Date(sEndD.getFullYear() + ',' + (sEndD.getMonth() + 1)  + ',' + sEndD.getDate() + ' ' + $('#end_time-'+rel).val().substr(0, 2) + ':' + $('#end_time-'+rel).val().substr(3, 2) + ':00 ' + $('#end_time-'+rel).val().substr(6, 2));

						if( selectedCurrentDate > testEndDate ){

							hours = parseInt(hours) + 2;
							
							if( hours >= 12 ){				
								if( pm_am == 'PM' ){
									day = day + 1;
								}
								hours = hours - 12;
								pm_am = ( pm_am == 'PM' ? 'AM' : 'PM' );
							}
							var newDateString = yr + ',' + mo  + ',' + day;
							var newTimeString = hours + ':' + minutes + ':' + sec;
							var excelDateString =new Date(newDateString + ' ' + newTimeString + ' ' + pm_am);
							$( "#end_date-"+rel).datetimepicker('setDate', excelDateString);

							 $("#ui-datepicker-div").fadeOut();

							
						}else{
							 $("#ui-datepicker-div").fadeOut();
						}
						
					}
					
				}

			}

		}
		
	
    }).attr('readonly','readonly');
	

    $(".end_date, .end_time").datetimepicker(
	
	{
		altField: ".end_time",
		minDateTime:dt,
		dateFormat: 'M d, yy', 
		altTimeFormat: 'hh:mm TT',
		timeFormat: 'hh:mm TT',		
		onSelect: function( selectedDate ) {
						var rel = $(this).attr('rel');

			
			if( selectedDate != '' ){
				
				var date = new Date(selectedDate);
				var yr = date.getFullYear();
				var mo = date.getMonth() + 1;
				var day = date.getDate();

				var hours 	= $('#end_time-'+rel).val().substr(0, 2);
				var minutes	= $('#end_time-'+rel).val().substr(3, 2);
				var pm_am	= $('#end_time-'+rel).val().substr(6, 2);
				var sec		= '00';
				
				var selectedCurrentDate =new Date(yr + ',' + mo  + ',' + day + ' ' + hours + ':' + minutes + ':' + sec + ' ' + pm_am);
				
				if ( !isNaN( selectedCurrentDate.getTime() ) ) {
						
					var sEndD = new Date( $('#start_date-'+rel).datetimepicker('getDate') ); 

					if( $('#start_date-'+rel).val() == '' ){ 

						$("#start_date-"+rel).datetimepicker('setDate', selectedCurrentDate);
						$("#ui-datepicker-div").fadeOut();

						
					}else{
						
						var testEndDate		= new Date(sEndD.getFullYear() + ',' + (sEndD.getMonth() + 1)  + ',' + sEndD.getDate() + ' ' + $('#start_time-'+rel).val().substr(0, 2) + ':' + $('#start_time-'+rel).val().substr(3, 2) + ':00 ' + $('#start_time-'+rel).val().substr(6, 2));

						if( testEndDate > selectedCurrentDate ){

							$("#end_date-"+rel).datetimepicker('setDate', testEndDate);
							 $("#ui-datepicker-div").fadeOut();
							
						}else{
								$("#ui-datepicker-div").fadeOut();

						}
						
					}
					
				}else{
					$("#ui-datepicker-div").fadeOut();
					
				}

			}

		}	
    }).attr('readonly','readonly');
	
	//address load from google geo location

	

	if(dd<10) {
		dd='0'+dd
	} 

	if(mm<10) {
		mm='0'+mm
	} 

	var today = mm+'/'+dd+'/'+yyyy;


});

</script>
<script>
// Sharetronix grouptxt namespace
var replayhash = function () {
    var autocompleteActive = false;
    var searchString = '';
    var startPos = 0;
    var endPos = 0;
    var currentPos = 0;
    var aliases = new Array();

    var tagsToReplace = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;'
        };

    function replaceTag(tag) {
        return tagsToReplace[tag] || tag;
    }
    
    function acMoveSelection(key) {
        acList = $('.hashtag-dropdown');
        if (key == 38) { // up
            if ($('li.selection', acList).length > 0) {
                prev = $('li.selection', acList).prev();
                $('.selection').removeClass('selection');
                $(prev).addClass('selection');
            } else {
                $('li:last', acList).addClass('selection');
            }
        } else if (key == 40) { // down
            if ($('li.selection', acList).length > 0) {
                next = $('li.selection', acList).next();
                $('.selection').removeClass('selection');
                $(next).addClass('selection');
            } else {
                $('li:first', acList).addClass('selection');
            }
        }
    }

    function generateACList(result) { // build autocomplete list and attach events - click/hover
        if (result.users.length == 0) {
            //var users = $('<div />').addClass('grouptxt-ac-title').html('There are no users matching your query!');
            var users = $('<span />');
        } else {
            var users = $('<ul />');
            for (var i = 0; i < result.users.length; i++) {
                var userItem = $('<li />').data('alias', result.users[i].username);
                var userImage = $('<img />').attr('src', result.users[i].avatar_url);

                searchStr = $('.ac-placeholder').text().replace(/#/gi, '');
                tmpName = result.users[i].fullname.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + searchStr.replace(/([\^\$\(\)\[\]\{\}\*\.\+\?\|\\])/gi, "\\$1") + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>");
				
				tmpName2 = ' (#' + result.users[i].username + ')';
				tmpName2 = tmpName2.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + searchStr.replace(/([\^\$\(\)\[\]\{\}\*\.\+\?\|\\])/gi, "\\$1") + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>");
                var userName = $('<span />').html(tmpName+tmpName2);

                var userClear = $('<div />').addClass('clear');
                $(userItem).append(userImage);
                $(userItem).append(userName);
                $(userItem).append(userClear);
                $(users).append($(userItem));
            }

            $('li:first', users).addClass('selection');

            $('li', users).click(function () {
                insertUserLink($(this));
                stopAC();
            }).hover(
                function () { $(this).addClass('hover'); },
                function () { $(this).removeClass('hover'); }
            );
        }
        return $(users);
    }

    function grouptxtACSuccess(result, userContext) { $(userContext).html(generateACList(result)); } // append autocomplete list

    function grouptxtACFail() { }

    function startAC(grouptxtEl) {
        autocompleteActive = true;
        accontainer = $('.hashtag-dropdown');
        searchString = '';
        //var char = '';
        $(accontainer).html('<div id="htmlarea-ac-title">Please start typing has tags ...</div>').show();
        startPos = currentPos;


        $(grouptxtEl).bind('keydown.ac', function (e) {
            if (e.which != 37 && e.which != 38 && e.which != 39 && e.which != 40) { // do not make ajax calls on arrow key press
                setTimeout(function () {
                   var text = $(grouptxtEl).val();
                    endPos = getCaretPosition($(grouptxtEl)[0]);
                    cnt = getSearchString($(grouptxtEl)[0], startPos, endPos);
                    //Users.autocomplete(cnt, 10, grouptxtACSuccess, grouptxtACFail, accontainer);
					var usertype="hash";
                    
                    var args = {
        					//type: 'post',
        					module: 'users',
        					action: 'autocomplete',
        					data: { users_name: cnt,usertype:usertype}
        				}
        			Services.invoke(args, grouptxtACSuccess, grouptxtACFail, accontainer);
                    
                    
                }, 10);
            }
        });
    }

    function stopAC() {
        autocompleteActive = false;
        $('.hastag').unbind('keydown.ac');
        $('.hashtag-dropdown').hide();
    }

    function getLinks(text) {
        var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
        //var exp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/gi;
        return text.match(exp);
    }

    /*--------------------*/

    function getCaretPosition(el) {
        var caretPos = 0;
        // IE Support
        if (document.selection) {

            //el.focus();

            var r = document.selection.createRange();
            var range = el.createTextRange();
            var rc = range.duplicate();
            range.moveToBookmark(r.getBookmark());
            rc.setEndPoint('EndToStart', range);
            //return rc.text.length;
            caretPos = rc.text.length;

            /*
            el.focus();
            var range = el.createTextRange();
            var startCharMove = offsetToRangeCharacterMove(el, 0);
            range.moveStart("character", startCharMove);
            caretPos = range.text.length;
            */
        }
        // Firefox support
        else if (el.selectionStart || el.selectionStart == '0')
            caretPos = el.selectionStart;
        return (caretPos);
    }

    function setCaretPosition(el, pos) {
        if (el.setSelectionRange) {
            el.focus();
            el.setSelectionRange(pos, pos);
        } else if (el.createTextRange) {
            var range = el.createTextRange();
            range.collapse(true);
            range.moveEnd('character', pos);
            range.moveStart('character', pos);
            range.select();
        }
    }

    function getSearchString(el, startOffset, endOffset) {
        if (document.selection) { //ie
            var range = el.createTextRange();
            var startCharMove = offsetToRangeCharacterMove(el, startOffset);
            range.collapse(true);
            if (startOffset == endOffset) {
                range.move("character", startCharMove);
            } else {
                range.moveEnd("character", offsetToRangeCharacterMove(el, endOffset));
                range.moveStart("character", startCharMove);
            }
            var cnt = range.text;
        } else if (el.selectionStart || el.selectionStart == '0') { //ff
            var text = $(el).val();
            cnt = text.substr(startOffset, endOffset - startOffset);
        }
        searchStr = cnt.replace(/#/gi, '');
        return searchStr;
    }

    function insertUserLink(el, editor) {
        alias = $(el).data('alias');
        if (!editor) {
            editor = $(".hastag");
        }


        if (aliases == null || aliases[alias] == null) {
            aliases[alias] = '#' + alias;
        }

        if (document.selection) {
            var range = $(editor)[0].createTextRange();
            var startCharMove = offsetToRangeCharacterMove($(editor)[0], startPos);
            range.collapse(true);
            if (startPos == endPos) {
                range.move("character", startCharMove);
            } else {
                range.moveEnd("character", offsetToRangeCharacterMove($(editor)[0], endPos));
                range.moveStart("character", startCharMove);
            }
            range.text = '#' + alias;
        } else {
            val = $(editor).val();
            alias = '#' + alias;
            replaced = val.substr(0, startPos) + alias + val.substr(endPos, val.length);
            $(editor).val(replaced);
            setTimeout(function () {
                setCaretPosition($(editor)[0], startPos + alias.length + 1)
            }, 10);

            //console.log(startPos);
            //console.log(endPos);
            //console.log(alias);
        }
        currentPos = getCaretPosition($(editor)[0]);
        highlighter($(editor));
        //alert('asd');

    }

    function offsetToRangeCharacterMove(el, offset) {
        return offset - (el.value.slice(0, offset).split("\r\n").length - 1);
    }

    function highlighter(el) {

        var cnt = $(el).val();
        cnt = cnt.replace(/[&<>]/g, replaceTag);
        cnt = cnt.replace(/\n/gi, '<br/>').replace(/\s/gi, '&nbsp;');
        cnt = cnt + '&nbsp;';


        if (aliases != null) {
            for (var a in aliases) {
                cnt = cnt.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + aliases[a].replace(/([\^\$\(\)\[\]\{\}\*\.\+\?\|\\])/gi, "\\$1") + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<span>$1</span>");
            };
        }

        if ($(el).parents('.hastag').length > 0) {
            $(el).parents('.hastag').find('.textarea-highlighter').html(cnt);
        } else {
            $(el).parents('.req.editor').find('.textarea-highlighter').html(cnt);
        }

    }





    function insertAtCursor(editor, str) {
        startPos = currentPos;

        if (document.selection) {
            var range = $(editor)[0].createTextRange();
            var startCharMove = offsetToRangeCharacterMove($(editor)[0], startPos);
            range.collapse(true);
            if (startPos == endPos) {
                range.move("character", startCharMove);
            } else {
                range.moveEnd("character", offsetToRangeCharacterMove($(editor)[0], endPos));
                range.moveStart("character", startCharMove);
            }
            range.text = str;
            //currentPos = getCaretPosition($(editor)[0]);
        } else {
            val = $(editor).val();
            replaced = val.substr(0, startPos) + str + val.substr(startPos + str.length, val.length);
            //alert(replaced);
            $(editor).val(replaced);
            setTimeout(function () {
                setCaretPosition($(editor)[0], startPos + str.length + 1);
                //currentPos = getCaretPosition($(editor)[0]);
            }, 10);
        }
    }


    function countCharacters(editor) {
    	counter = $(editor).parents('.status-editor-container').find('.characters-counter');
    
    }

    // --- declare public methods --- //
    return {
        init: function (el) {
            grouptxtEl = ($(el).length > 0) ? $(el) : $('#hastag');
            

            $(grouptxtEl).focus(function () {
            	//countCharacters($(this));
                if ($(this).val().trim() == $(this).data('placeholder')) { $(this).val(''); }
                $(this).parents('.hastag').addClass('focus');
                currentPos = getCaretPosition($(this)[0]);
            }).blur(function () {
            	//countCharacters($(this));
                if ($(this).val().trim() == '') $(this).val($(this).data('placeholder'));
                $(this).parents('.hastag').removeClass('focus');
            }).keypress(function (e) {
                //$(this).parents('.grouptxt').find('.textarea-highlighter span').text($(this).val());
                // start autocomplete on "@" press
                highlighter($(this));
                countCharacters($(this));
                if (e.which == 35/* && !autocompleteActive*/) {

                    //e.preventDefault();
                    currentPos = getCaretPosition($(this)[0]);

                    stopAC();
                    startAC($(this));
                }
            }).keyup(function () {
                //currentPos = getCaretPosition($(this)[0]);
               highlighter($(this));
                countCharacters($(this));
                //var content = $(this).val();
                //content = content.replace(/\n/gi, '<br />');
                //$(this).parents('.grouptxt').find('.textarea-highlighter span').html(content);
            }).keydown(function (e) {
                //$(this).parents('.grouptxt').find('.textarea-highlighter span').text($(this).val());
                /*
                // 8  - backspace
                // 13 - enter
                // 27 - escape
                // 32 - space
                // 37 - arrow left
                // 38 - arrow up
                // 39 - arrow right
                // 40 - arrow down
                // 46 - delete
                // 64 - @
                */

                //currentPos = getCaretPosition($(this)[0]);
                highlighter($(this));
                countCharacters($(this));
                txteditor = $(this);

                if (e.which == 13) { // stop autocomplete 
                    if (e.which == 13 && autocompleteActive) {
                        e.preventDefault();
                        endPos = getCaretPosition($(txteditor)[0]);
                        selected = $('li.selection', '.hashtag-dropdown');
                        insertUserLink($(selected), txteditor);
                    }
                    stopAC();
                }

                if ((e.which == 38 || e.which == 40) && autocompleteActive) { // up/down arrow keys
                    e.preventDefault();
                    acMoveSelection(e.which);

                }

                if (e.which == 27) { // esc key
                    e.preventDefault();
                    stopAC();
                }

                if (e.which == 32) {
                    el = $(this);
                    setTimeout(function () {
                        urls = getLinks($(el).val());
                        if (urls != null) {
                            for (var i = 0; i < urls.length; i++) {
                                Attachments.attachLink($(el), urls[i]);
                            }
                        }
                    }, 200);
                }



            }).bind('paste', function () { // on paste clean html
                el = $(this);
                setTimeout(function () {
                    urls = getLinks($(el).val());
                    if (urls != null) {
                        for (var i = 0; i < urls.length; i++) {
                            Attachments.attachLink($(el), urls[i]);
                        }
                    }
                }, 200);
            });

            $('body').click(function (event) {
                caller = event.target;
                if ($(caller).parents('.htmlarea-ac').length == 0 && !$(caller).hasClass('htmlarea-ac')) {
                    stopAC();
                }
            });











            //comment editor when user is not logged
            commentAreaEl = $('.req textarea');
            $(commentAreaEl).focus(function () {
                if ($(this).val().trim() == $(this).data('placeholder')) { $(this).val(''); }
                $(this).parents('.editor').addClass('focus');
                currentPos = getCaretPosition($(this)[0]);
            }).blur(function () {
                if ($(this).val().trim() == '') $(this).val($(this).data('placeholder'));
                $(this).parents('.editor').removeClass('focus');
            }).keypress(function (e) {
                highlighter($(this));
            }).keyup(function () {
                highlighter($(this));
            }).keydown(function (e) {
                highlighter($(this));
            });













            $('.ac-btn').live('click', function (e) {
                e.preventDefault();
                targetEditor = $(this).parents('.data-content-placeholder').find('textarea');
                $(targetEditor).focus();
                setCaretPosition($(targetEditor)[0], currentPos);
                //insertAtCursor($(targetEditor), '@');
                setTimeout(function () {
                    insertAtCursor($(targetEditor), '#');
                    startAC($(targetEditor));
                }, 20);

            });



        },

        reset: function (el, type) {
            editorContainer = $(el).parents('.data-content-placeholder');
            $(el).parents('.htmlarea').find('.textarea-highlighter').html('');
            $(el).val($(el).data('placeholder'));
            $(editorContainer).find('.attachments .images').html('');
            $(editorContainer).find('.attachments .links').html('');
            $(editorContainer).find('.attachments .files').html('');
            $(editorContainer).find('.uploads').hide();
            Attachments.reset(type);
        },

        highlightAlias: function (alias, el) {
            $(el).text('#' + alias);
            $(el).focus();

            aliases[alias] = '#' + alias;
            highlighter(el);
        }

    }
} ();

$(document).ready(function () {
    //HtmlAutocompleteService = new STXServices.BTSearchService();
    //grouptxt.init();
	
	 
    replayhash.init($('.hastag'));
    //grouptxt.init($('#comments-textarea'));


    
});
</script>
<script>
// Sharetronix Htmlarea namespace
var Htmlareaasset = function () {
    var autocompleteActive = false;
    var searchString = '';
    var startPos = 0;
    var endPos = 0;
    var currentPos = 0;
    var aliases = new Array();

    var tagsToReplace = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;'
        };

    function replaceTag(tag) {
        return tagsToReplace[tag] || tag;
    }
    
    function acMoveSelection(key) {
        acList = $('.htmlarea-ac-container-replay');
        if (key == 38) { // up
            if ($('li.selection', acList).length > 0) {
                prev = $('li.selection', acList).prev();
                $('.selection').removeClass('selection');
                $(prev).addClass('selection');
            } else {
                $('li:last', acList).addClass('selection');
            }
        } else if (key == 40) { // down
            if ($('li.selection', acList).length > 0) {
                next = $('li.selection', acList).next();
                $('.selection').removeClass('selection');
                $(next).addClass('selection');
            } else {
                $('li:first', acList).addClass('selection');
            }
        }
    }

    function generateACList(result) { // build autocomplete list and attach events - click/hover
        if (result.users.length == 0) {
            //var users = $('<div />').addClass('htmlarea-ac-title').html('There are no users matching your query!');
            var users = $('<span />');
        } else {
			var postid = result.users[0].postid;
            var users = $('<ul />');
            for (var i = 0; i < result.users.length; i++) {
                var userItem = $('<li />').data('alias', result.users[i].fullname);

                searchStr = $('.ac-placeholder').text().replace(/$/gi, '');
				
				tmpName2 = ' $' + result.users[i].fullname +'';
				tmpName = ' (' + result.users[i].username +')';

                var userName = $('<span />').html(tmpName2+tmpName);

                var userClear = $('<div />').addClass('clear');
                $(userItem).append(userName);
                $(userItem).append(userClear);
                $(users).append($(userItem));

            }

            $('li:first', users).addClass('selection');

            $('li', users).click(function () {
                insertUserLink($(this),postid);
                stopAC();
            }).hover(
                function () { $(this).addClass('hover'); },
                function () { $(this).removeClass('hover'); }
            );
        }
        return $(users);
    }

    function HtmlAreaACSuccess(result, userContext) { $(userContext).html(generateACList(result)); } // append autocomplete list
	
	

    function HtmlAreaACFail() { }

    function startAC(htmlAreaEl,rel) {
        autocompleteActive = true;
        accontainer = $('.htmlarea-ac-container-replay');
        searchString = '';
        //var char = '';
        $(accontainer).html('<div class="htmlarea-ac-title">Please start typing asset name ...</div>').show();
        startPos = currentPos;

        $(htmlAreaEl).bind('keydown.ac', function (e) {
            if (e.which != 37 && e.which != 38 && e.which != 39 && e.which != 40) { // do not make ajax calls on arrow key press
                setTimeout(function () {
                    var text = $(htmlAreaEl).val();
                    endPos = getCaretPosition($(htmlAreaEl)[0]);
                    cnt = getSearchString($(htmlAreaEl)[0], startPos, endPos);
					var usertype="asset";
					var ticker1 = 	$("#ticker1").val();
                  
					
                            
                    var args = {
        					//type: 'post',
        					module: 'users',
        					action: 'autocomplete',
        					data: { users_name: cnt,usertype:usertype,ticker1:ticker1,finaldata:text,postid:rel}
        				}
        			Services.invoke(args, HtmlAreaACSuccess, HtmlAreaACFail, accontainer);
                    
                    
                }, 10);
            }
        });
    }

    function stopAC() {
        autocompleteActive = false;
        $('.htmlarea textarea').unbind('keydown.ac');
        $('.htmlarea-ac-container-replay').hide();
    }

    function getLinks(text) {
        var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&$#\/%?=~_|!:,.;]*[-A-Z0-9+&$#\/%=~_|])/ig;
        //var exp = /(ftp|http|https):\/\/(\w+:{0,1}\w*$)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%$!\-\/]))?/gi;
        return text.match(exp);
    }

    /*--------------------*/

    function getCaretPosition(el) {
        var caretPos = 0;
        // IE Support
        if (document.selection) {

            //el.focus();

            var r = document.selection.createRange();
            var range = el.createTextRange();
            var rc = range.duplicate();
            range.moveToBookmark(r.getBookmark());
            rc.setEndPoint('EndToStart', range);
            //return rc.text.length;
            caretPos = rc.text.length;

            /*
            el.focus();
            var range = el.createTextRange();
            var startCharMove = offsetToRangeCharacterMove(el, 0);
            range.moveStart("character", startCharMove);
            caretPos = range.text.length;
            */
        }
        // Firefox support
        else if (el.selectionStart || el.selectionStart == '0')
            caretPos = el.selectionStart;
        return (caretPos);
    }

    function setCaretPosition(el, pos) {
        if (el.setSelectionRange) {
            el.focus();
            el.setSelectionRange(pos, pos);
        } else if (el.createTextRange) {
            var range = el.createTextRange();
            range.collapse(true);
            range.moveEnd('character', pos);
            range.moveStart('character', pos);
            range.select();
        }
    }

    function getSearchString(el, startOffset, endOffset) {
        if (document.selection) { //ie
            var range = el.createTextRange();
            var startCharMove = offsetToRangeCharacterMove(el, startOffset);
            range.collapse(true);
            if (startOffset == endOffset) {
                range.move("character", startCharMove);
            } else {
                range.moveEnd("character", offsetToRangeCharacterMove(el, endOffset));
                range.moveStart("character", startCharMove);
            }
            var cnt = range.text;
        } else if (el.selectionStart || el.selectionStart == '0') { //ff
            var text = $(el).val();
            cnt = text.substr(startOffset, endOffset - startOffset);
        }
        searchStr = cnt.replace('$','');


        return searchStr;
    }

    function insertUserLink(el,postid) {
        alias = $(el).data('alias');
		alias_original = $(el).data('alias');
        if (postid !='') {
            editor = $('#message-'+postid);
        }

        if (aliases == null || aliases[alias] == null) {
            aliases[alias] = '$' + alias;
        }
		
          

        if (document.selection) {
            var range = $(editor)[0].createTextRange();
            var startCharMove = offsetToRangeCharacterMove($(editor)[0], startPos);
            range.collapse(true);
            if (startPos == endPos) {
                range.move("character", startCharMove);
            } else {
                range.moveEnd("character", offsetToRangeCharacterMove($(editor)[0], endPos));
                range.moveStart("character", startCharMove);
            }
            range.text = '$' + alias;
        } else {
            val = $(editor).val();
            alias = '' + alias+' ';
			
            replaced = val.substr(0, startPos) + alias + val.substr(endPos, val.length);
            $(editor).val(replaced);
            setTimeout(function () {
                setCaretPosition($(editor)[0], startPos + alias.length + 1)
            }, 10);

            //console.log(startPos);
            //console.log(endPos);
            //console.log(alias);
        }
		 var ticker = $(".replayticker1-"+postid).val();
		 if(ticker ==''){
			 var alisor =alias_original;
			 $(".replayticker1-"+postid).val(alisor);
			 
			 
		 }else{
			 var ticker = $(".replayticker1-"+postid).val();
			 var data     =ticker+','+alias_original;
			
			 
			 $(".replayticker1-"+postid).val(data);


			 
		 }
		
		
		
        highlighter($(editor));
        //alert('asd');

    }

    function offsetToRangeCharacterMove(el, offset) {
        return offset - (el.value.slice(0, offset).split("\r\n").length - 1);
    }

    function highlighter(el) {

        var cnt = $(el).val();
        cnt = cnt.replace(/[&<>]/g, replaceTag);
        cnt = cnt.replace(/\n/gi, '<br/>').replace(/\s/gi, '&nbsp;');
        cnt = cnt + '&nbsp;';


        if (aliases != null) {
            for (var a in aliases) {
                cnt = cnt.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + aliases[a].replace(/([\^\$\(\)\[\]\{\}\*\.\+\?\|\\])/gi, "\\$1") + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<span>$1</span>");
            };
        }

        if ($(el).parents('.htmlarea').length > 0) {
            $(el).parents('.htmlarea').find('.textarea-highlighter').html(cnt);
        } else {
            $(el).parents('.req.editor').find('.textarea-highlighter').html(cnt);
        }

    }





    function insertAtCursor(editor, str) {
        startPos = currentPos;

        if (document.selection) {
            var range = $(editor)[0].createTextRange();
            var startCharMove = offsetToRangeCharacterMove($(editor)[0], startPos);
            range.collapse(true);
            if (startPos == endPos) {
                range.move("character", startCharMove);
            } else {
                range.moveEnd("character", offsetToRangeCharacterMove($(editor)[0], endPos));
                range.moveStart("character", startCharMove);
            }
            range.text = str;
            //currentPos = getCaretPosition($(editor)[0]);
        } else {
            val = $(editor).val();
            replaced = val.substr(0, startPos) + str + val.substr(startPos + str.length, val.length);
            //alert(replaced);
            $(editor).val(replaced);
            setTimeout(function () {
                setCaretPosition($(editor)[0], startPos + str.length + 1);
                //currentPos = getCaretPosition($(editor)[0]);
            }, 10);
        }
    }
	function countCharacters(editor){
		   charactersCount = editor.val().length;
		   currentPos = getCaretPosition($(editor)[0]);

			
			
		    
	

		
	}


   

    // --- declare public methods --- //
    return {
        init: function (el) {
            htmlAreaEl = ($(el).length > 0) ? $(el) : $('.htmlarea textarea');
            

            $(htmlAreaEl).focus(function () {
            	//countCharacters($(this));
                if ($(this).val().trim() == $(this).data('placeholder')) { $(this).val(''); }
                $(this).parents('.htmlarea').addClass('focus');
                currentPos = getCaretPosition($(this)[0]);
            }).blur(function () {
            	//countCharacters($(this));
                if ($(this).val().trim() == '') $(this).val($(this).data('placeholder'));
                $(this).parents('.htmlarea').removeClass('focus');
            }).keypress(function (e) {
                //$(this).parents('.htmlarea').find('.textarea-highlighter span').text($(this).val());
                // start autocomplete on "$" press
                highlighter($(this));
                //countCharacters($(this));
                if (e.which == 36/* && !autocompleteActive*/) {
					var rel = $(this).attr('rel');
                    //e.preventDefault();
                    currentPos = getCaretPosition($(this)[0]);
                    stopAC();
                    startAC($(this),rel);
                }
            }).keyup(function () {
                //currentPos = getCaretPosition($(this)[0]);
               highlighter($(this));
                countCharacters($(this));
				var resstr =$(this).val();
                 var lastChar = resstr.substr(resstr.length - 1);
				if (lastChar =='$'/* && !autocompleteActive*/) {
                  currentPos = getCaretPosition($(this)[0]);
					var rel = $(this).attr('rel');

                    stopAC();
                         startAC($(this),rel);
				}
                //var content = $(this).val();
                //content = content.replace(/\n/gi, '<br />');
                //$(this).parents('.htmlarea').find('.textarea-highlighter span').html(content);
            }).keydown(function (e) {
                //$(this).parents('.htmlarea').find('.textarea-highlighter span').text($(this).val());
                /*
                // 8  - backspace
                // 13 - enter
                // 27 - escape
                // 32 - space
                // 37 - arrow left
                // 38 - arrow up
                // 39 - arrow right
                // 40 - arrow down
                // 46 - delete
                // 64 - $
                */

                //currentPos = getCaretPosition($(this)[0]);
                highlighter($(this));
                //countCharacters($(this));
                txteditor = $(this);

                if (e.which == 13) { // stop autocomplete 
                    if (e.which == 13 && autocompleteActive) {
                        e.preventDefault();
                        endPos = getCaretPosition($(txteditor)[0]);
                        selected = $('li.selection', '.htmlarea-ac-container-replay');
                        insertUserLink($(selected), txteditor);
                    }
                    stopAC();
                }

                if ((e.which == 38 || e.which == 40) && autocompleteActive) { // up/down arrow keys
                    e.preventDefault();
                    acMoveSelection(e.which);

                }

                if (e.which == 27) { // esc key
                    e.preventDefault();
                    stopAC();
                }

                if (e.which == 32) {
                    el = $(this);
                    setTimeout(function () {
                        urls = getLinks($(el).val());
                        if (urls != null) {
                            for (var i = 0; i < urls.length; i++) {
                                Attachments.attachLink($(el), urls[i]);
                            }
                        }
                    }, 200);
                }



            }).bind('paste', function () { // on paste clean html
                el = $(this);
                setTimeout(function () {
                    urls = getLinks($(el).val());
                    if (urls != null) {
                        for (var i = 0; i < urls.length; i++) {
                            Attachments.attachLink($(el), urls[i]);
                        }
                    }
                }, 200);
            });

            $('body').click(function (event) {
                caller = event.target;
                if ($(caller).parents('.htmlarea-ac').length == 0 && !$(caller).hasClass('htmlarea-ac')) {
                    stopAC();
                }
            });











            //comment editor when user is not logged
            commentAreaEl = $('.req textarea');
            $(commentAreaEl).focus(function () {
                if ($(this).val().trim() == $(this).data('placeholder')) { $(this).val(''); }
                $(this).parents('.editor').addClass('focus');
                currentPos = getCaretPosition($(this)[0]);
            }).blur(function () {
                if ($(this).val().trim() == '') $(this).val($(this).data('placeholder'));
                $(this).parents('.editor').removeClass('focus');
            }).keypress(function (e) {
                highlighter($(this));
            }).keyup(function () {
                highlighter($(this));
            }).keydown(function (e) {
                highlighter($(this));
            });













            $('.ac-btn').live('click', function (e) {
                e.preventDefault();
                targetEditor = $(this).parents('.data-content-placeholder').find('textarea');
                $(targetEditor).focus();
                setCaretPosition($(targetEditor)[0], currentPos);
                //insertAtCursor($(targetEditor), '$');
                setTimeout(function () {
                    insertAtCursor($(targetEditor), '$');
                    startAC($(targetEditor));
                }, 20);

            });



        },

        reset: function (el, type) {
            editorContainer = $(el).parents('.data-content-placeholder');
            $(el).parents('.htmlarea').find('.textarea-highlighter').html('');
            $(el).val($(el).data('placeholder'));
            $(editorContainer).find('.attachments .images').html('');
            $(editorContainer).find('.attachments .links').html('');
            $(editorContainer).find('.attachments .files').html('');
            $(editorContainer).find('.uploads').hide();
            Attachments.reset(type);
        },

        highlightAlias: function (alias, el) {
            $(el).text('$' + alias);
            $(el).focus();

            aliases[alias] = '$' + alias
            highlighter(el);
        }

    }
} ();

$(document).ready(function () {
    //HtmlAutocompleteService = new STXServices.BTSearchService();
    //Htmlarea.init();
	Htmlareaasset.init($('.htmlarea textarea'));
	
	
    

	
	$(".replayintraday").click(function(){
		var rel =$(this).attr('rel');
		
		var tickers   =$(".replayticker1-"+rel).val();
		if(tickers ==''){
			STX.showMessage("Please select asset", "error");
					return false;

			
		}
		
		var html_content      =$('#message-'+rel).val();
		$.ajax({
					 async: true, 
		           cache: false,
					type:"POST",
					data:{ticker:tickers,html_content:html_content},
					url:"<?php  echo $C->SITE_URL;?>assetsreplay",

					success:function(msg){
						$(".replayslide-"+rel).html(msg);
						
						
						
					}
				});
		 
				 

		
	});

    
});
</script>
