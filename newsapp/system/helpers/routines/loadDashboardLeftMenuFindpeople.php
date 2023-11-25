<?php

	function loadDashboardLeftMenuFindpeople( $tpl, $params )
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
		    


 


		
/* End : RIGHT Section */		    


/* Start : LEFT Section */

/* Start : Profile */
		    $tpl->layout->setVar( 'left_content_placeholder', $tpl->designer->createInfoBlock('
			<div class="box">

			<div class="profile-top">
			<img src="'.$C->STORAGE_URL.'avatars/thumbs3/'.$user->info->avatar.'" class="img-circle img-responsive" width="92" >
			</div>

			<div class="box-inner">

			<div class="box-title">
			'.$user->info->fullname.'<br /> <span class="box-sub-desc"><a href="'.$C->SITE_URL.''.$user->info->username.'">@'.$user->info->username.'</a></span>
			</div>
			<input type="hidden" id="ifollow" value="'.$D->ifollow.'">

			<div class="col-md-12 box-footer count">
			
			<a href="'.$C->SITE_URL.''.$user->info->username.'"><div class="col-md-3 box-footer-center"><span class="count-title">BUZZES</span> <br /> '.$D->buzzes.'</div></a>
			
			<a href="'.$C->SITE_URL.'members/tab:ifollow"><div class="col-md-3 box-footer-center"><span class="count-title">FOLLOWING</span> <br /> <span class="follow">'.$D->ifollow.'</span></div></a>
			
			<a href="'.$C->SITE_URL.'members/tab:followers"><div class="col-md-3 box-footer-center"><span class="count-title">FOLLOWERS</span> <br /> '.$D->followers.'</div></a>
			

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
		
		//$tpl->layout->setVar( 'left_content', $tpl->designer->createInfoBlock( $page->lang('dbrd_right_lastonline'), $tpl->designer->createUserLinks( $network->get_online_users(), 'thumbs3' ) ) );
		$tpl->layout->setVar( 'left_content',$tpl->designer->createTagLinks( $network->get_recent_posttags() ) ) ;
		//$tpl->layout->setVar( 'left_content', $tpl->designer->whatToDoBlock() );
        $tpl->layout->setVar( 'right_content', $tpl->designer->createInfoBlock( $page->lang('dbrd_right_lastonline'), $tpl->designer->createUserLinks( $network->get_online_users(), 'thumbs3' ) ) );
		$tpl->layout->setVar( 'right_content', $tpl->designer->createInfoBlock( $page->lang('dbrd_right_posttags'), $tpl->designer->createTagLinks( $network->get_recent_posttags() ) ) );
		$tpl->layout->setVar( 'right_content', $tpl->designer->whatToDoBlock() );		
		//$tpl->layout->saveVars(); //there is a saveVar to set the placeholders
	
	}