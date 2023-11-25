<?php

	function loadDashboardUserLeftMenu( $tpl, $params )
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
	 $img ='<div class="data-row-1" data-userid="'.$vals->id.'"><img src="'.$src .'" class="img-circle bizcard" data-userid="'.$vals->id.'" width="50"></div>';
	 
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
			<img height="22px" src="'.$C->SITE_URL.'storage/attachments/1/close.jpg" class="pull-right">
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
      <li></li>
      <li>Find people you know</li><li>
      </li></ul>
	  </div>
	  </a>
			',
			 $tpl->designer->createMenu('feed-navigation', $rightmenu)) );
/* End : Who To Follow */


		
/* End : RIGHT Section */		    


/* Start : LEFT Section */

//die('======');
 

//aqui começa o cod que identifica se vc é seguida
/*$num = $this->user->id;
$num2 = $this->page->params->user ;
$iffolow = $this->db2->query("SELECT * from users_followed where who = ".$num2." and whom = ".$num);
$iffollow2 = $this->db2->num_rows($iffolow);

if ($iffollow2 >= 1 ) {
$btn='<center> <div style="background: rgba(0,0,0,0.2); margin-bottom:10px; height:23px; border-radius:6px; padding:0px; color:#000 ;  font-size:14px; "> Follows You </div></center>';
}else{
    $btn='';
}*/
//aqui termina o cod
 
	


/* Start : Profile */

if($D->customuserlogged){
$num2=$D->customuserlogged;
$num=$D->uid;

$dbConnection = $GLOBALS['db2'];
$query = $dbConnection->query("SELECT * from users_followed where who = $num and whom = $num2 ");
$num_rows = mysqli_num_rows($query);
 if($num_rows>=1){

$btn='<center> <div style="background: rgba(0,0,0,0.2); margin-bottom:10px; height:23px; border-radius:6px; padding:4px; color:#000 ;  font-size:14px; "> Follows You </div></center>';

 }else{
     $btn='';
 }
}

//echo '<pre>';
//print_r(); 


if($D->userdetails->avatar !=''){
	$src='<img src="'.$C->STORAGE_URL.'avatars/thumbs4/'.$D->userdetails->avatar.'" class="img-circle img-responsive" width="90" >';
}else{
		 $src ='<div class="data-row-1 circle-who-to-follow" data-userid="'.$D->uid.'">'.ucfirst(substr($D->userdetails->username,0,1)).'</div>';

}
		    $tpl->layout->setVar( 'left_content_placeholder', $tpl->designer->createInfoBlock('
			<div class="box">

			<div class="profile-top">
			<a href="'.$C->SITE_URL.''.$D->userdetails->username.'">
			'.$src.'
			</a>
			</div>

			<div class="box-inner">

			<div class="box-title"><a href="'.$C->SITE_URL.''.$D->userdetails->username.'">
			'.$D->userdetails->fullname.'<br /> <span class="box-sub-desc">@'.$D->userdetails->username.'</span></a>
			</div>
			<input type="hidden" id="ifollow" value="'.$network->ifollow($D->uid).'">

			<div class="col-md-12 box-footer count">
			
			<a href="'.$C->SITE_URL.''.$D->userdetails->username.'"><div class="col-md-3 box-footer-center"><span class="count-title">BUZZES</span> <br /> '.$network->buzzes($D->uid).'</div></a>
			
			<a href="'.$C->SITE_URL.'members/tab:ifollow"><div class="col-md-3 box-footer-center"><span class="count-title">FOLLOWING</span> <br /> <span class="follow">'.$network->ifollow($D->uid).'</span></div></a>
			
			<a href="'.$C->SITE_URL.'members/tab:followers"><div class="col-md-3 box-footer-center"><span class="count-title">FOLLOWERS</span> <br /> '.$network->followers($D->uid).'</div></a>
			

			<a href="'.$C->SITE_URL.'groups//tab:my">
			<div class="col-md-3 box-footer-center"><span class="count-title">GROUPS</span> <br /> '.$network->groupcnt($D->uid).' </div></a>
			
			</div><!--/ End: box-footer -->

			</div><!--/ End: box-inner -->
			</div><br>'.$btn.'<!--/ End: box -->
			
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
		$tpl->layout->setVar( 'right_content_placeholder',$tpl->designer->createTagLinks( $network->get_recent_posttags() ) ) ;
		//$tpl->layout->setVar( 'left_content', $tpl->designer->whatToDoBlock() );
        $tpl->layout->setVar( 'right_content', $tpl->designer->createInfoBlock( $page->lang('dbrd_right_lastonline'), $tpl->designer->createUserLinks( $network->get_online_users(), 'thumbs3' ) ) );
		$tpl->layout->setVar( 'right_content', $tpl->designer->createInfoBlock( $page->lang('dbrd_right_posttags'), $tpl->designer->createTagLinks( $network->get_recent_posttags() ) ) );
		$tpl->layout->setVar( 'right_content', $tpl->designer->whatToDoBlock() );		
		//$tpl->layout->saveVars(); //there is a saveVar to set the placeholders
		
	
	}