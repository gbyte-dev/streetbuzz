<?php

	function loadDashboardLeftMenuPrediction( $tpl, $params )
	{
		global $C,$D;
		$page 		= & $GLOBALS['page'];
		$network 	= & $GLOBALS['network'];
		$user 		= & $GLOBALS['user'];
		$pm 		= & $GLOBALS['plugins_manager'];
		
		
		$tab	= 'all';
		if( $page->param('tab') ){
			$tab = htmlspecialchars( $page->param('tab') );
			
		}
		
		
		
		$my_groups	= $user->get_top_groups(5);
		//tab_state
		
		
		$new_activities = $network->get_dashboard_tabstate($user->id, array('all', 'commented', '@me'));
		
		$menu = array( 	array('url' => 'dashboard/tab:all', 		'css_class' => 'my-activities'.(($tab === 'all')? ' selected' : ''), 	'title' => $page->lang('dbrd_leftmenu_all'), 												'tab_state' => $new_activities['all']),
						array('url' => 'dashboard/tab:@me', 		'css_class' => 'at'.(($tab === '@me')? ' selected' : ''), 				'title' => $page->lang('dbrd_leftmenu_@me', array('#USERNAME#'=>$user->info->username) ), 	'tab_state' => $new_activities['@me'] ),
						array('url' => 'dashboard/tab:commented', 	'css_class' => 'comments'.(($tab === 'commented')? ' selected' : ''), 	'title' => $page->lang('dbrd_leftmenu_commented'), 											'tab_state' => $new_activities['commented'] ),
						array('url' => 'dashboard/tab:bookmarks', 	'css_class' => 'favourites'.(($tab === 'bookmarks')? ' selected' : ''), 'title' => $page->lang('dbrd_leftmenu_bookmarks') ),
						array('url' => 'dashboard/tab:likes', 		'css_class' => 'post-like'.(($tab === 'likes')? ' selected' : ''), 'title' => $page->lang('dbrd_leftmenu_likes') ),
						array('url' => 'dashboard/tab:everybody', 	'css_class' => 'filter-all'.(($tab === 'everybody')? ' selected' : ''), 'title' => $page->lang('dbrd_leftmenu_everybody', array('#COMPANY#'=>$C->COMPANY)) )
		);
	$rightmenu = array();
		//$tpl->layout->setVar( 'left_content_placeholder', $tpl->designer->createInfoBlock('Activity filter', $tpl->designer->createMenu('feed-navigation', $menu, 'dashboard_main_left_menu')) );
		    

/* Start : RIGHT Section */
$html ='';
if(!empty($D->follow)){
foreach($D->follow as $keys=>$vals ){
	if(!empty($D->follow[$keys])){
		 if($vals->avatar !=''){
	 $src=$C->SITE_URL.'storage/avatars/thumbs3/'.$vals->avatar;
	 $img ='<div class="data-row-1" data-userid="'.$vals->id.'"><img src="'.$src .'" class="img-circle bizcard" data-userid="'.$vals->id.'" width="50" ></div>';
	 
 }else{
	 $img ='<div class="data-row-1 circle-who-to-follow" data-userid="'.$vals->id.'">'.ucfirst(substr($vals->username,0,1)).'</div>';
 }
	$html .='

	<div class="follow-data-bor" id="follow'.$vals->id.'">
			
			<a href="'.$C->SITE_URL.''.$vals->username.'">'.$img.'</a>
			
			<div class="data-row-2">
			<a href="'.$C->SITE_URL.''.$vals->username.'"><p class="follow-name bizcard1" data-userid="'.$vals->id.'" >'.$vals->fullname.'</p></a> 
			<a href="'.$C->SITE_URL.''.$vals->username.'"><p class="follow-by">@'.$vals->username.'</p></a> 
			
			<button class="btn btn-default btn-xs btn-follow"  id="'.$vals->id.'" rel="'.$vals->id.'">Follow +</button>
			
			</div>
			<div class="data-row-3" id="'.$vals->id.'">
			<img src="'.$C->SITE_URL.'storage/attachments/1/close.png">
			</div>
			</div>
			
	
	';
	}
}
	}
 

/* Start : Who To Follow */
		    $tpl->layout->setVar( 'right_content_placeholder', $tpl->designer->createInfoBlock('
			<div class="box">
			<div class="box-inner">
			<div class="box-title">
			Who to Follow  <a class="ref txt-blue-small" href="#">Refresh</a>
			</div>
			<div class="lessoncup">
			'.$html.'
			</div>

			

			</div>
			

		<a href="'.$C->SITE_URL.'findpeople">
			<div class="follow-footer">
			<ul class="list-inline">
      <li><img src="'.$C->SITE_URL.'storage/attachments/1/follow-find-accounts.png"></li>
      <li>Find people you know</li><li>
      </li></ul>
			</div></a>
			',
			 $tpl->designer->createMenu('feed-navigation', $rightmenu)) );
/* End : Who To Follow */
$event =''; 
if(!empty($D->eventfetchre)){
foreach($D->eventfetchre as $keys=>$eventsval){
	if(!empty($D->eventfetchre[$keys])){
		$event .='
		<a href="'.$C->SITE_URL.'notification/tab:event/req:myevent" class="myevent"><li><span class="glyphicon glyphicon-calendar"></span> '.$eventsval->event_name.'</li></a>		

		
		';

		
	}
	
}
}
$acceptevent ='';
if(!empty($D->eventacceptfetchre)){
foreach($D->eventacceptfetchre as $keys=>$eventsval){
	if(!empty($D->eventfetchre[$keys])){
		$acceptevent .='
	<a href="'.$C->SITE_URL.'notification/tab:event/req:accept" class="myevent"><li><span class="glyphicon glyphicon-calendar"></span> '.$eventsval->event_name.'</li></a>		

		
		';

		
	}
	
}
}


/* Start : Upcoming Events */
		    $tpl->layout->setVar( 'right_content_placeholder', $tpl->designer->createInfoBlock('
			<div class="box">
			<div class="box-inner">

				<div class="box-title">
				Upcoming Events
				</div>

			<div class="box-content">

				<div class="box-sub-title">
				My Events
				</div>

				<div class="box-sub-desc">
				<ul>
				'.$event.'
				</ul>
				</div>

			</div>

			<div class="box-event-details">

				<div class="box-sub-title">
				Invite Accepted
				</div>
			
				<div class="box-sub-desc">
				<ul>
				'.$acceptevent.'
				</ul>
				</div>

			</div>

				<div class="col-md-12 box-footer">
				<div class="col-md-6 box-footer-left"><a href="'.$C->SITE_URL.'notification/tab:event">View All</a></div>
				</div>

			</div>
			</div>
			',
			 $tpl->designer->createMenu('feed-navigation', $rightmenu)) );
/* End : Upcoming Events */
//my polls html
$mypolls ='';
if(!empty($D->pollmyresfetchresults)){
foreach($D->pollmyresfetchresults as $keys=>$mypollsval){
	if(!empty($D->pollmyresfetchresults[$keys])){
		$mypolls .='
		<a href="'.$C->SITE_URL.'notification/tab:polls/req:mypolls"><li><span class="glyphicon glyphicon-signal"></span> '.$mypollsval->poll_question.'</li></a>

		
		';

		
	}
	
}
}
//my response html
$myresponseval ='';
if(!empty($D->pollmyresponsefetchresults)){
foreach($D->pollmyresponsefetchresults as $keys=>$pollmyresval){
	if(!empty($D->pollmyresponsefetchresults[$keys])){
		$myresponseval .='
				<a href="'.$C->SITE_URL.'notification/tab:polls/req:myresponse"><li><span class="glyphicon glyphicon-signal"></span> '.$pollmyresval->poll_question.'</li></a>

		
		';

		
	}
	
}
}
$stars ='';
if($D->userpredictres->Level =="BEGINNER"){
	for($i=0;$i<1;$i++){
		$stars .='<li><img src="'.$C->SITE_URL.'static/images/star-prediction.png" class="img-responsive"></li>	';
		
	}
	
	
}elseif($D->userpredictres->Level =="INTERMEDIATE"){
	for($i=0;$i<2;$i++){
		$stars .='<li><img src="'.$C->SITE_URL.'static/images/star-prediction.png" class="img-responsive"></li>	';
		
	}
	
	
}elseif($D->userpredictres->Level =="EXPERT"){
	for($i=0;$i<3;$i++){
		$stars .='<li><img src="'.$C->SITE_URL.'static/images/star-prediction.png" class="img-responsive"></li>	';
		
	}
		$amnt = $C->EXPERT;
		

}elseif($D->userpredictres->Level =="MAESTRO"){
	for($i=0;$i<4;$i++){
		$stars .='<li><img src="'.$C->SITE_URL.'static/images/star-prediction.png" class="img-responsive"></li>	';
		
	}
		$amnt = $C->MAESTRO;
		

}
/* Start : Polls */
		    $tpl->layout->setVar( 'right_content_placeholder', $tpl->designer->createInfoBlock('
			<div class="box">
			<div class="box-inner">

			<div class="box-title">
			Polls
			</div>

			<div class="box-content">

			<div class="box-sub-title">
			My Polls
			</div>

			<div class="box-sub-desc">
			<ul>
			'.$mypolls.'
			</ul>
			</div>

			</div>

			<div class="box-content">

			<div class="box-sub-title">
			My Responses
			</div>
			
			<div class="box-sub-desc">
			<ul>
			'.$myresponseval.'
			</ul>
			</div>

			</div>

			<div class="col-md-12 box-footer">
			<div class="col-md-4 box-footer-left"><a href="'.$C->SITE_URL.'notification/tab:polls/req:myresponse">View All</a></div>
			<div class="col-md-4 box-footer-center"><a href="#"></a></div>
			</div>

			</div>
			</div>
			',
			 $tpl->designer->createMenu('feed-navigation', $rightmenu)) );
/* End : Polls */

/* Start : Footer */
		    $tpl->layout->setVar( 'right_content_placeholder', $tpl->designer->createInfoBlock('
			<div class="box">
			<div class="box-inner">
			
			<div class="footer-text">
			<p>&copy; 2016 Street Buzz. <br />
			<ul class="list-inline">
			<li><a href="#">About</a></li>
			<li><a href="#">Help</a></li>
			<li><a href="#">Terms</a></li>
			<li><a href="#">Privacy</a></li>
			<li><a href="#">Cookies</a></li>
			<li><a href="#">AdsInfo</a></li>
			<li><a href="#">Brand</a></li>
			<li><a href="#">Blog</a></li>
			<li><a href="#">Status</a></li>
			<li><a href="#">Apps Jobs</a></li>
			<li><a href="#">Advertise</a></li>
			<li><a href="#">Business</a></li>
			<li><a href="#">Media</a></li>
			<li><a href="#">Developers</a></li>
			</ul>
			</p>
			</div>

			</div>
			</div>
			',
			 $tpl->designer->createMenu('feed-navigation', $rightmenu)) );
/* End : Footer */
		
/* End : RIGHT Section */		    


/********************* Start : LEFT Section *********************************/

/* Start : Profile */
		    $tpl->layout->setVar( 'left_content_placeholder', $tpl->designer->createInfoBlock('
		    <div class="box">

			<div class="profile-top">

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 zeropadding">
			<a href="'.$C->SITE_URL.''.$user->info->username.'">
			<img src="'.$C->STORAGE_URL.'avatars/thumbs4/'.$user->info->avatar.'" class="img-circle img-responsive" width="92" >
			</a>
			</div>


			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 zeropadding">

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 star zeropadding">
			<ul>
			'.$stars.'			
			</ul>
			</div>


			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 txt-hit-miss hit zeropadding">
			HIT - '.$D->userpredictres->Hit.'
			</div>

			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 txt-hit-miss zeropadding">
			MISS - '.$D->userpredictres->Miss.'
			</div>

			</div><!--/ End:  -->

			</div><!--/ End:  -->

			</div><!--/ End: profile-top -->




			<div class="box-inner">

			<div class="box-title">
			<a href="'.$C->SITE_URL.''.$user->info->username.'">'.$user->info->fullname.'
			<br /> <span class="box-sub-desc">@'.$user->info->username.'</span></a>
			</div>
			<input type="hidden" id="ifollow" value="'.$network->ifollow($user->id).'">
             '.$calhtml.'

			<div class="col-md-12 box-footer count">
			<a href="'.$C->SITE_URL.''.$user->info->username.'"><div class="col-md-3 box-footer-center"><span class="count-title">BUZZES</span> <br /> '.$network->buzzes($user->id).'</div></a>

			<a href="'.$C->SITE_URL.'members/tab:ifollow"><div class="col-md-3 box-footer-center"><span class="count-title">FOLLOWING</span> <br /> <span class="follow">'.$network->ifollow($user->id).'</span></div></a>

			<a href="'.$C->SITE_URL.'members/tab:followers"><div class="col-md-3 box-footer-center"><span class="count-title">FOLLOWERS</span> <br /> '.$network->followers($user->id).'</div></a>

			<a href="'.$C->SITE_URL.'groups//tab:my">
			<div class="col-md-3 box-footer-center"><span class="count-title">GROUPS</span> <br /> '.$network->groupcnt($user->id).' </div></a>

			</div><!--/ End: box-footer -->

			</div><!--/ End: box-inner -->
			</div><!--/ End: box -->

			',



			 $tpl->designer->createMenu('feed-navigation', $rightmenu)) );
			
/* End : Profile */



 //Get the posttags from database
  /*
 $posttags = $network->get_recent_posttags();
 $tags ='';
 $search_where ='tags';
  foreach($posttags as $keys=>$vals){
	 
	  $tags .= '<li><a href="'. $C->SITE_URL .'search/tab:'.$search_where.'/s:'. $vals .'" title="#'. htmlspecialchars($vals) .'"><small>#</small>'. htmlspecialchars(str_cut($vals,25)) .'</a></li> ';

}
*/

/* Start : Buzz */
		  /*  $tpl->layout->setVar( 'left_content_placeholder', $tpl->designer->createInfoBlock('
			<div class="box">
			<div class="box-inner">

			<div class="box-title">
			What is Buzzing in India
			</div>

			<div class="box-sub-desc">
			<ul>
			
			'.$tags.'
			</ul>
			</div>

			<div class="col-md-12 box-footer">
			<div class="col-md-4 box-footer-left"><a href="#">View All</a></div>
			</div>

			</div>
			</div>
			',
			 $tpl->designer->createMenu('feed-navigation', $rightmenu)) );*/
/* End : Buzz */


 /* End : LEFT Section */          


           

		/*
		if( count($menu) > 0 ){

			$tpl->layout->setVar( 'right_content_placeholder', $tpl->designer->createInfoBlock('', 
			$tpl->designer->createMenu('feed-navigation', $menu)) );

		
		}
		*/

		

		unset($menu, $my_groups);
		
				$tpl->layout->setVar( 'left_content','	


<!-- Start : My Earnings  -->
			
			<div class="box-prediction">
			<div class="box-inner">

				<div class="box-title">
				My Earnings
				</div>

			<div class="box-content">
<div id="chartContainer" style="min-height: 300px; width:100%;"></div>
			
				<div class="col-lg-12 col-md-12 box-footer">
				<div class="col-lg-12 col-md-12 box-footer-left"><a href="#">
				<h3>Total Amount : '.number_format($D->totalearned).' INR </h3>
				</a></div>
				</div>

				</div>

			</div>
			</div>

<!--/ End : My Earnings  -->



<!-- Start : Prediction Amount  -->

			<div class="box-prediction">
			<div class="box-inner">

				<div class="box-title">
				Prediction Amount
				</div>

			<div class="box-content">
<div id="chartContainer1" style="min-height: 300px; width:100%;"></div>

			
				<div class="col-lg-12 col-md-12 box-footer">
				<div class="col-lg-12 col-md-12 box-footer-left"><a href="#">
				<h3>Total Amount : '.number_format($D->predictcnt).' INR</h3>
				</a></div>
				</div>

				</div>

			</div>
			</div>
<!--/ End : Prediction Amount  -->
') ;
				


		
		//$tpl->layout->setVar( 'left_content', $tpl->designer->createInfoBlock( $page->lang('dbrd_right_lastonline'), $tpl->designer->createUserLinks( $network->get_online_users(), 'thumbs3' ) ) );
	//	$tpl->layout->setVar( 'left_content',$tpl->designer->createTagLinks( $network->get_recent_posttags() ) ) ;
		//$tpl->layout->setVar( 'left_content', $tpl->designer->whatToDoBlock() );
        $tpl->layout->setVar( 'right_content', $tpl->designer->createInfoBlock( $page->lang('dbrd_right_lastonline'), $tpl->designer->createUserLinks( $network->get_online_users(), 'thumbs3' ) ) );
		$tpl->layout->setVar( 'right_content', $tpl->designer->createInfoBlock( $page->lang('dbrd_right_posttags'), $tpl->designer->createTagLinks( $network->get_recent_posttags() ) ) );
		$tpl->layout->setVar( 'right_content', $tpl->designer->whatToDoBlock() );		
		//$tpl->layout->saveVars(); //there is a saveVar to set the placeholders
	
	}