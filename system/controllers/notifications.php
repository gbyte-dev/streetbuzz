<?php
	
	if( !$this->user->is_logged ) {
		$this->redirect('home');
	}

	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/dashboard.php');
	$this->load_langfile('inside/notifications.php');
	
	$this->network->reset_dashboard_tabstate($this->user->id, 'notifications');
	$userdata =$this->network->get_username($this->user->id);
	
	$tpl = new template( array('page_title' => $this->lang('dashboard_page_title', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );
	$folow_res           = $db2->query('SELECT whom FROM  users_followed as uf where who="'.$this->user->id.'"' );
	 	
	while($fetchres = $db2->fetch_object($folow_res)){
		$res[] = $fetchres->whom;
	}
	if(!empty($res)){
	$fetchres   =implode(',',$res);
	}else{
		$fetchres ="' '";
		
		
	}	
	$fetch            =$db2->query('SELECT u.id,u.username,u.fullname,u.avatar,u.about_me FROM  street_suggestion as st
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
					WHERE pu.user_id = "'.$this->user->id.'"  and (pu.event_status = 1 ) order by pu.post_id desc LIMIT 3' );
	while($eventacceptfetchre[] = $db2->fetch_object($eventacceptres)){
	}
	$D->eventacceptfetchre  = $eventacceptfetchre;
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

	$tpl->initRoutine('DashboardLeftMenuNotification');
		$tpl->routine->load();
		$tpl->layout->useBlock('notification_head');
				
		$tpl->layout->block->save('main_content');

	
	$notifications = array();


	
	$db2->query( 'SELECT id,notif_type, in_group_id, from_user_id, notif_object_type, notif_object_id, date,post_id,noti_postid FROM notifications WHERE to_user_id="'.intval($this->user->id).'"   ORDER BY id DESC LIMIT 50' );

$stack = array();
	while( $n = $db2->fetch_object() ){
		
		//this could be removed
		if( $n->in_group_id > 0 ){
			$g = $this->network->get_group_by_id( $n->in_group_id );
			if( !$g ){
				continue;
			}
		}

	
		//end this could be removed
		if($n->post_id ==''){
		if($n->noti_postid ==''){
		


		if( !isset($notifications[ $n->notif_type ]) ){
			$notifications[ $n->notif_type ] = array();
		}
	
		$ndx = ( $n->notif_object_id > 0 )? $n->notif_object_type.'_'.$n->notif_object_id : 'none_0';

		if( !isset($notifications[ $n->notif_type ][ $ndx ]) ){
			$notifications[ $n->notif_type ][ $ndx ] = array();
		}
       		
		if(!isset($notifications[ $n->notif_type ][$ndx][$n->from_user_id.'-'.$n->in_group_id])){
			$notifications[ $n->notif_type ][$ndx][$n->from_user_id.'-'.$n->in_group_id] = array( 'gid' => $n->in_group_id, 'from_uid' => $n->from_user_id, 'date' => $n->date, 'nobj' => array( 'type' => $n->notif_object_type, 'id' => $n->notif_object_id,'postid'=>$n->noti_postid ));
		}
		}else{
			  
			
		 //check notification type and post id priority based data-action
		//$res =$this->network->checknotificationtypedata($n->noti_postid,$n->notif_type);
        // $checkpriority = $this->network->checkpriroty($res);
		if(in_array($n->notif_type.'-'.$n->noti_postid.'-'.$n->notif_object_type,$notifications)){
		    
		}else{
		 $notifications[] =$n->notif_type.'-'.$n->noti_postid.'-'.$n->notif_object_type;
		 
		}

	
		
		
		
		
			
		}

		
		}else{
			$notifications['event'] =$n->post_id;
			
		}

	
        		
	}
	$notifications = (($notifications));

	$D->hide_notification = FALSE;
	$D->ntf_group = '';

	if( count($notifications) > 0 ){
       $m=0;		

		foreach( $notifications as $notif_type => $notif_array ){
         $D->mvalue = $m++;	
   		if(strpos($notif_array,'ntf_me_on_post_profileloved') !== false){
				$notifyres   =explode("-",$notif_array);
				if($notifyres[1] !=''){
				$notifytype = $notifyres[0]; 
				$postid     = $notifyres[1];
				$totalnotifications            = $this->network->notificationpostres($notifytype,$postid);
			    $totalnotificationscnt = array_filter($totalnotifications);
				//$totalnoticnt =count($totalnotificationscnt);
				$totalnoticnt =$this->network->totallovedprofile($this->user->id);



				$noti            = $this->network->usersnotificationprofiledata($notifytype,$postid);
				$notificationslike = array_filter($noti);
				$noticnt =count($notificationslike);
				$tmp = array(
				
				'ntf_me_on_post_profileloved' => ' profile'
				);
				
		       $stxt = str_replace(array_keys($tmp), array_values($tmp), $notifytype);
				
				
				$usr = ' <a href="'.$C->SITE_URL.$notificationslike[0]->username.'" class="bizcard" data-userid="'.$notificationslike[0]->username.'">'.$notificationslike[0]->username.'</a> ';
				if($notificationslike[0]->avatar !=""){
					$avatar = $notificationslike[0]->avatar;
				}else{
					$avatar = "_noavatar_user.gif";
				}

				$D->usr_avatar = ' <a href="'.$C->SITE_URL.$notificationslike[0]->username.'" class="avatar bizcard" data-userid="'.$notificationslike[0]->id.'"><img src="'.$C->STORAGE_URL.'avatars/thumbs1/'.$avatar.'" title="'.htmlspecialchars(getThisUserCommunityName($u)).'" width="50" height="50" /></a> ';
                if($totalnoticnt == 1){
				
				$D->notif_text = ' <a href="'.$C->SITE_URL.$notificationslike[0]->username.'" class="bizcard" data-userid="'.$notificationslike[0]->id.'">'.$notificationslike[0]->username.'</a> loved your <a href="'.$C->SITE_URL.$userdata->username.'"> '.$stxt.'</a>';
                }else{
					if($totalnoticnt > 2){
						$totalmembers = $totalnoticnt-2;
						$totalhtml =' and <a  href="" data-role="services" data-namespace="activities" data-action="showloves" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$this->user->id.'"}').'">'.$totalmembers.' others members</a>  loved your  ';
						
					}else{
					   $totalhtml ='loved your';
					}
				$D->notif_text = '<a href="'.$C->SITE_URL.$notificationslike[0]->username.'" class="bizcard" data-userid="'.$notificationslike[0]->id.'">'.$notificationslike[0]->username.'</a> and <a href="'.$C->SITE_URL.$notificationslike[1]->username.'" class="bizcard" data-userid="'.$notificationslike[1]->id.'">'.$notificationslike[1]->username.'</a> '.$totalhtml.' <a href="'.$C->SITE_URL.$userdata->username.'">'.$stxt.'</a>';

				}
                $tpl->layout->useBlock('notification');
				
				$tpl->layout->block->setVar('activity_user_avatar', $D->usr_avatar);
				$tpl->layout->block->setVar('activity_user_username', $usr);
				$tpl->layout->block->setVar('activity_text', $D->notif_text);
				$tpl->layout->block->setVar('activity_footer', post::parse_date($notificationslike[0]->date));
				
				$tpl->layout->block->save('main_content');
				
				
 
				}
				

			
		}else{

		if(strpos($notif_array,'ntf_me_on_post') !== false){
              
				$notifyres   =explode("-",$notif_array);

				if($notifyres[1] !=''){
				$notifytype = $notifyres[0]; 
				$postid     = $notifyres[1];
				$totalnotifications            = $this->network->notificationpostres($notifytype,$postid);
			    $totalnotificationscnt = array_filter($totalnotifications);
				$totalnoticnt =count($totalnotificationscnt);


				$noti            = $this->network->usersnotificationdata($notifytype,$postid);
				$notificationslike = array_filter($noti);
				$noticnt =count($notificationslike);
				$tmp = array(
				'ntf_me_on_post_like' =>' likes your ',
				'ntf_me_on_post_agree' => ' Agree-To-Disagree(A2D) with your ',
				'ntf_me_on_post_replay' =>' replied to your ',
				'ntf_me_on_post_rebuzz' => ' rebuzzed your ',
				'ntf_me_if_u_follow_buzz'=> 'buzz posted'
				
				);
				$posttype     = $this->network->typeofpostofevent($postid);
				if($posttype->num_rows > 0){
					$type ="event";
				}else{
				$polltype      =$this->network->typeofpostofpoll($postid);
					if($posttype->num_rows > 0){
						$type =" poll";
					}else{
					$activitiestype      =$this->network->typelinks($postid);
					if(!empty($activitiestype)){
						
					 if($activitiestype->type=="videoembed"){
						 $type=" video(s)";
						 
					 }elseif($activitiestype->type=="image"){
						  $type=" pic(s)";
						 
					 }elseif($activitiestype->type=="file"){
						  $str = (unserialize($activitiestype->data));
						 $ext = pathinfo($str->file_original, PATHINFO_EXTENSION);
						 if($ext =='wmv' || $ext =='mp4' || $ext =='avi' || $ext =='mov' || $ext =='qt'){
							 $type = " video";
							 
						 }else{
							  $type = " file";
							 
						 }
					}else{
						 $type =" buzz";	
					 }
					}else{
					$type =" buzz";	
					}

					}				
				}
			 
				if($type ==' buzz')
				{
				$type="buzz:";
					
				if(strlen($notificationslike[0]->message) > 50){	
				$mes	    =substr($notificationslike[0]->message,0,50).'....show more';
				}else{
					$mes	    =$notificationslike[0]->message;
				}
					$post =$mes; 
				}else{
				   $post ='';	
				}
				
		       $stxt = str_replace(array_keys($tmp), array_values($tmp), $notifytype);
		       
		       
			   if($notifytype =="ntf_me_on_post_like"){
				   $action ="showlikes";
				   
			   }
			    if($notifytype =="ntf_me_on_post_agree"){
				   $action ="showreshares";
				   
			   }
			   if($notifytype =="ntf_me_on_post_replay"){
				   $action ="showreplay";
				   
			   }
			    if($notifytype =="ntf_me_on_post_rebuzz"){
				   $action ="showreshares";
				   
			   }
			     if($notifytype ==""){
				   $action ="showpost";
				   
			   }
			
				
				$usr = ' <a href="'.$C->SITE_URL.$notificationslike[0]->username.'" class="bizcard" data-userid="'.$notificationslike[0]->username.'">'.$notificationslike[0]->username.'</a> ';
				if($notificationslike[0]->avatar !=""){
					$avatar = $notificationslike[0]->avatar;
				}else{
					$avatar = "_noavatar_user.gif";
				}


				$D->usr_avatar = ' <a href="'.$C->SITE_URL.$notificationslike[0]->username.'" class="avatar bizcard" data-userid="'.$notificationslike[0]->id.'"><img src="'.$C->STORAGE_URL.'avatars/thumbs1/'.$avatar.'" title="'.htmlspecialchars(getThisUserCommunityName($u)).'" width="50" height="50" /></a> ';
                if($totalnoticnt == 1){
				
				$D->notif_text = ' <a href="'.$C->SITE_URL.$notificationslike[0]->username.'" class="bizcard" data-userid="'.$notificationslike[0]->id.'">'.$notificationslike[0]->username.'</a> '.$stxt.'<a href="'.$C->SITE_URL.'view/post:'.$postid.'">'.$type.'<br />'.$post.'</a>';
                }else{
					if($totalnoticnt > 2){
						$totalmembers = $totalnoticnt-2;
						$totalhtml =' and <a  href="" data-role="services" data-namespace="activities" data-action="'.$action.'" data-value="'.htmlentities('{"activities_type":"public","activities_id":"'.$postid.'"}').'">'.$totalmembers.'other members</a>';
						
					}else{
					   $totalhtml ='';
					}
				$D->notif_text = '<a href="'.$C->SITE_URL.$notificationslike[0]->username.'" class="bizcard" data-userid="'.$notificationslike[0]->id.'">'.$notificationslike[0]->username.'</a> and <a href="'.$C->SITE_URL.$notificationslike[1]->username.'" class="bizcard" data-userid="'.$notificationslike[1]->id.'">'.$notificationslike[1]->username.'</a>'.$totalhtml.$stxt.'<a href="'.$C->SITE_URL.'view/post:'.$postid.'">'.$type.'<br />'.$post.'</a>';

				}
                $tpl->layout->useBlock('notification');
				
				$tpl->layout->block->setVar('activity_user_avatar', $D->usr_avatar);
				$tpl->layout->block->setVar('activity_user_username', $usr);
				$tpl->layout->block->setVar('activity_text', $D->notif_text);
				$tpl->layout->block->setVar('activity_footer', post::parse_date($notificationslike[0]->date));
				
				$tpl->layout->block->save('main_content');
				
				

				}
				
				
				
			}
		}


		
			$notification_group_count = count($notif_array); 
			$D->ntf_group = $notif_type;
			if($notif_type =='event'){
				$notquery = 	$db2->query( 'SELECT  n.from_user_id, n.date,n.post_id,u.username FROM notifications as n
				inner join users as u ON n.from_user_id = u.id
				WHERE n.post_id="'.intval($notif_array).'" ' );
				$nres          =$db2->fetch_object($notquery);

				$notif_array_event = $nres->post_id.'-'.$nres->from_user_id.'-'.$nres->date;
			 $notif_array_eventarr = explode("-",$notif_array_event);
		 $date = $notif_array_eventarr[2];
		  $u = $this->network->get_user_by_id( $notif_array_eventarr[1] );
				if( !$u ){
					continue;
				}
		       $res = $db2->query( 'SELECT event_status  FROM post_userbox WHERE user_id="'.intval($this->user->id).'"  AND post_id ="'.$notif_array_eventarr[0].'" ' );
				$res1     =$db2->fetch_object($res);
				if(($res1->event_status ==4) || ($res1->event_status =='') || ($res1->event_status == 1) ){
					$status ="update";
					
				}
				if($res1->event_status ==2){
					$status ="cancel";
					
				}
				$usr = ' <a href="'.$C->SITE_URL.$nres->username.'" class="bizcard" data-userid="'.$nres->from_user_id.'">'.$nres->username.'</a> ';

				$D->usr_avatar = ' <a href="'.$C->SITE_URL.$u->username.'" class="avatar bizcard" data-userid="'.$u->id.'"><img src="'.$C->STORAGE_URL.'avatars/thumbs1/'.$u->avatar.'" title="'.htmlspecialchars(getThisUserCommunityName($u)).'" width="50" height="50" /></a> ';
                $D->notif_text = ' <a href="'.$C->SITE_URL.$u->username.'" class="bizcard" data-userid="'.$u->id.'">'.$u->username.'</a> Send '.$status.' event invitation to You.';
                $tpl->layout->useBlock('notification');
				
				$tpl->layout->block->setVar('activity_user_avatar', $D->usr_avatar);
				$tpl->layout->block->setVar('activity_user_username', $usr);
				$tpl->layout->block->setVar('activity_text', $D->notif_text);
				$tpl->layout->block->setVar('activity_footer', post::parse_date($date));
				
				$tpl->layout->block->save('main_content');
			}else{
			
          				
			foreach( $notif_array as $nobj_name => $notif_objects ){

				$numb = count($notif_objects);
				$notif_objects = reset($notif_objects);

				$date = $notif_objects['date'];
				$u = $this->network->get_user_by_id( $notif_objects['from_uid'] );
				if( !$u ){
					continue;
				}
				$is_in_group = $notif_objects['gid'];

				if($u->avatar !=""){
					$avatar = $u->avatar;
				}else{
					$avatar = "_noavatar_user.gif";
				}
				//the info for the langiage file
				$D->usr_avatar = ' <a href="'.$C->SITE_URL.$u->username.'" class="avatar bizcard" data-userid="'.$u->id.'"><img src="'.$C->STORAGE_URL.'avatars/thumbs1/'.$avatar.'" title="'.htmlspecialchars(getThisUserCommunityName($u)).'" width="50" height="50" /></a> ';
				$usr = ' <a href="'.$C->SITE_URL.$u->username.'" class="bizcard" data-userid="'.$u->id.'">'.getThisUserCommunityName($u).'</a> ';
				$a1 = '<a href="" data-value="'.htmlentities('{"notifications_type":"'.trim($notif_type).'","notifications_object":"'.trim($nobj_name).'","notifications_ingroup":"'.$is_in_group.'"}').'" data-role="services" data-namespace="notifications" data-action="showNotificationDetails">';
				$a2 = '</a>';
				$a3 = '';
				$notifobj = '';
				$error_continue = FALSE;
				//end

				$about = explode('_', $nobj_name);
				
				if( $about[0] != '0' || $about[1] != '0' ){
					switch( $about[0] ){
						case 'post': 
										$a3 = '<a href="'.$C->SITE_URL.'view/post:'.intval($about[1]).'">';
										break;
						case 'network': 
										$notifobj = '<a href="'.$C->SITE_URL.'">'.mb_substr($C->SITE_TITLE, 0, 20).'</a>';
										break;
						case 'group': 
										$g1 = $this->network->get_group_by_id( $about[1] ); 
										$g_name = $g1? $g1->groupname : '';
										$g1 = $g1? $g1->title : '';  
										$notifobj = '<a href="'.$C->SITE_URL.$g_name.'">'.$g1.'</a>';
										if( empty($g1) ){
											$error_continue = TRUE;
										}
										break;
						case 'user': 
										if( !$is_in_group ){
											$u1 = $this->network->get_user_by_id( $about[1] );
											$u1_id = $u1? $u1->id : ''; 
											$u1 = $u1? getThisUserCommunityName($u1) : ''; 
											$notifobj = '<a href="'.$C->SITE_URL.$u1.'" class="bizcard" data-userid="'.$u1_id.'">'.$u1.'</a>';
											
											if( empty($u1) ){
												$error_continue = TRUE;
											}
										}else{
											$g1 = $this->network->get_group_by_id( $is_in_group ); 
											$g_name = $g1? $g1->groupname : '';
											$g1 = $g1? $g1->title : ''; 
											$notifobj = '<a href="'.$C->SITE_URL.$g_name.'">'.$g1.'</a>';
											
											if( empty($g1) ){
												$error_continue = TRUE;
											}
										}
										break;
					}
				}
				
				if( $error_continue ){
					continue;
				}
				
				$D->error = FALSE;
				
				$D->notif_text = '';
				
				if( $numb == 1 ){
					$D->notif_text = $this->lang('single_msg_'.$notif_type, array('#USER#'=>$usr, '#A3#'=>$a3, '#A2#'=>$a2, '#NOTIFOBJ#'=>$notifobj));
					
				}elseif( $numb > 1 ){
					$D->notif_text = $this->lang('grouped_msg_'.$notif_type, array('#USER#'=>$usr, '#NUM#'=>($numb-1), '#A1#'=>$a1, '#A2#'=>$a2, '#A3#'=>$a3, '#NOTIFOBJ#'=>$notifobj));
				}
				
				
				
				$tpl->layout->useBlock('notification');
				
				$tpl->layout->block->setVar('activity_user_avatar', $D->usr_avatar);
				$tpl->layout->block->setVar('activity_user_username', $usr);
				$tpl->layout->block->setVar('activity_text', $D->notif_text);
				$tpl->layout->block->setVar('activity_footer', post::parse_date($date));
				
				$tpl->layout->block->save('main_content');
			}
				
		}
			

		}
	}
	
	$tpl->display();
	
	
?>