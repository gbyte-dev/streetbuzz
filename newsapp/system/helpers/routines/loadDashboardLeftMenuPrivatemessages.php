<?php
error_reporting(0);

	function loadDashboardLeftMenuPrivatemessages( $tpl, $params )
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
			</div

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
				<a href="#"><li>'.$event.'</li></a> 				
				</ul>
				</div>

				<div class="col-lg-12 col-md-12 box-footer">
				<div class="col-lg-12 col-md-12 box-footer-left"><a href="'.$C->SITE_URL.'notification/tab:event">View All</a></div>
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
			<div class="col-md-4 box-footer-right"><a href="#" id="buzzpoll">Buzz an Poll</a></div>
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


/* Start : LEFT Section */
	if($page->param('tab')=="Intraday"){
	$css4="active";
}else{
	$css4="";
	
}
if($page->param('tab')=="polls"){
	$css3="active";
}else{
	$css3="";
	
}
if($page->param('tab')=="event"){
	$css2="active";
}else{
		$css2="";
	
}
if($page->param('tab')=="@me"){
	$css1="active";
}else{
	$css1="";
}
if($page->param('tab')==""){
	$css5="active";
}else{
	$css5="";
}
$chathtml ='';
$pre   =date('Y-m-d h:i:s');
$datetime1 = strtotime($pre);
for($m=0;$m<count($D->users);$m++){
	 if($D->users[$m]->avatar !=''){
	 $src=$C->SITE_URL.'storage/avatars/thumbs3/'.$D->users[$m]->avatar;
	$img ='<div class="pm-row-1 circle-private-messages " data-userid="'.$D->users[$m]->id.'"><img src="'.$src .'" class="img-circle " data-userid="'.$D->users[$m]->id.'" width="30" ></div>';

	 
 }else{
	 $img ='<div class="pm-row-1 circle-private-messages " data-userid="'.$D->users[$m]->id.'">'.ucfirst(substr($D->users[$m]->username,0,1)).'</div>';
 }
 $time =$network->getuusserstat($D->users[$m]->id);
 $res2  =date("Y-m-d h:i:s", $time);
 $datetime2 = strtotime($res2);
 $interval  = abs($datetime2 - $datetime1);
 $minutes   = round($interval / 60); 
  if($minutes == 0){
	 $statushtml ='<div id="status-'.$D->users[$m]->id.'"><div class="pm-row-3 private-messages-active"></div></div>';
	 
 }else{
	 	 $statushtml ='<div id="status-'.$D->users[$m]->id.'"></div>';

	 
 }
 $user_message_data = '{"users_id": '.$D->users[$m]->id.', "users_name":"'.$D->users[$m]->fullname.'", "users_username":"'.$D->users[$m]->username.'"}';

 
 
$chathtml .='<!-- Start : Users -->
	<div class="private-messages-bor bizcard" data-userid="'.$D->users[$m]->id.'">

          '.$img.'		
			<div class="pm-row-2">
			<a data-role="services" data-namespace="users" data-value="'.htmlentities($user_message_data, ENT_COMPAT, 'UTF-8').'" data-action="sendMessagePopup"><p class="private-messages-name " data-userid="'.$D->users[$m]->id.'">'.ucfirst($D->users[$m]->username).'</p></a> 
			</div>
			'.$statushtml.'

</div>
<!--/ End : Users -->';
}
/* Start : Private Messages */
		    $tpl->layout->setVar( 'left_content_placeholder', $tpl->designer->createInfoBlock('
<style>
.demo-wrapper::-webkit-scrollbar {
    width: 8px;
}
.demo-wrapper::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.1); 
    border-radius: 10px;
}
.demo-wrapper::-webkit-scrollbar-thumb {
    border-radius: 10px;
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.1); 
}

.demo-wrapper:hover::-webkit-scrollbar {
    width: 8px;
}
.demo-wrapper:hover::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.2); 
    border-radius: 10px;
}
.demo-wrapper:hover::-webkit-scrollbar-thumb {
    border-radius: 10px;
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.4); 
}
</style>


			<div class="box demo-wrapper" style="border:0px solid green; background:none; height:92%; position:fixed; width:23%; top:75px; overflow: scroll;">

			

			<div class="box-inner">

			<div class="box-title">
			Private Messages
			</div>

			<div class="col-lg-12 col-md-12 workspace-left-menu notifications active zeropadding">
				<div class="lessoncup" id="show">
			
             '.$chathtml.'
	




			
			</div>
			


			</div>
			</div>
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
		//$tpl->layout->setVar( 'left_content',$tpl->designer->createTagLinks( $network->get_recent_posttags() ) ) ;
		//$tpl->layout->setVar( 'left_content', $tpl->designer->whatToDoBlock() );
        $tpl->layout->setVar( 'right_content', $tpl->designer->createInfoBlock( $page->lang('dbrd_right_lastonline'), $tpl->designer->createUserLinks( $network->get_online_users(), 'thumbs3' ) ) );
		$tpl->layout->setVar( 'right_content', $tpl->designer->createInfoBlock( $page->lang('dbrd_right_posttags'), $tpl->designer->createTagLinks( $network->get_recent_posttags() ) ) );
		$tpl->layout->setVar( 'right_content', $tpl->designer->whatToDoBlock() );		
		//$tpl->layout->saveVars(); //there is a saveVar to set the placeholders
	
	
	?>
	<script type="text/javascript"
    src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
 <script>
    $(document).ready(
	
            function() {
				var arrayFromPHP = <?php echo json_encode($D->jsonusers); ?>;

                setInterval(function() {
						$.ajax({
					async: true, 
		           cache: false,
					dataType : "html",
					type:"POST",
					data:{userid:1},
					url:"<?php echo $C->SITE_URL.'autocomplete'?>",
                     success:function(msg){
						 
						 var arrayFromPHP1 = msg;
						 var obj = jQuery.parseJSON(msg);

						 

						 
						 for(var m=0;m<(arrayFromPHP.length);m++){
							 if(jQuery.inArray(arrayFromPHP[m],obj)!='-1'){
								 $("#status-"+arrayFromPHP[m]).html('<div class="pm-row-3 private-messages-active"></div>');
								 
							 }else{
								 	$("#status-"+arrayFromPHP[m]).html('');

								

							 }
						 }
		
					}
				});
                    
                }, 300);
            });
</script>
   
	<?php } ?>
