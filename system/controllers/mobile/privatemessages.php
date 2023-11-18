<?php
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if(!$this->user->is_logged){
		$this->redirect('signin');
	}

	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/dashboard.php');
	
	$this->network->reset_dashboard_tabstate($this->user->id, $this->param('tab')? $this->param('tab') : 'private');
	
	//TEMPLATE CODE START
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
	$tpl->useStaticHTML();
	$tpl->staticHTML->useActivityContainer();

	$activity = activityFactory::select('private');
	$activity->setTemplate( $tpl );
	$result = $activity->loadPosts();

	if( isset($result[1]) && $result[1] > 0 ){
		$tpl->layout->useBlock('activity-show-more');
		$tpl->layout->setVar('activities_pager_value', htmlentities('{"activities_type":"private","activities_id":"'.$result[1].'"}'));
		$tpl->layout->block->save('activity_container_show_more');
	}
	
	if( $this->param('tab') !== 'group' && isset($result[0]) && $result[0] > 0 ){
		$table = new tableCreator();
		$tpl->layout->setVar('main_content_bottom', 
													$table->hiddenField( 'activities_type', 'private' ) .
													$table->hiddenField( 'last_activity', intval($result[0]) ) .
													$table->hiddenField( 'activities_tab', $this->param('tab')? $this->param('tab') : 'all' )
		);
	}
	
	$tpl->display(); 
	//TEMPLATE CODE END
?>