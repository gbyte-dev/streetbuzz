<?php 
function loadDefaultHeader( $tpl, $params )
{ 
global $C, $D, $network, $user, $page;

$pm 	= & $GLOBALS['plugins_manager'];

$page->load_langfile('inside/header.php');

$lang_abbrv = explode( '.', $C->PHP_LOCALE );

if( is_array($lang_abbrv) ){
$lang_abbrv = explode('_', $lang_abbrv[0]);
}
$lang_abbrv = (isset($lang_abbrv[0]))? strtolower($lang_abbrv[0]) : 'en';
$tpl->layout->setVar('html_lang_abbrv', $lang_abbrv);
if( $page->request[0] == 'dashboard' ){
$tab = ($page->param('tab') !== '')? $page->param('tab') : 'all';
$D->tabs_state	= $network->get_dashboard_tabstate($user->id, array('all','@me','private','commented', 'notifications'), $tab);
if( isset($D->tabs_state[$tab]) ) {
$D->tabs_state[$tab]	= 0;
}
}else{
$D->tabs_state	= $network->get_dashboard_tabstate($user->id, array('@me','private', 'notifications'));
}
if(!empty($D->tabs_state['private'])){
$privatecnt  = $D->tabs_state['private'];

}else{
$privatecnt ="";

}
if(!empty($D->tabs_state['notifications'])){
$notificationcnt  = $D->tabs_state['notifications'];

}else{
$notificationcnt ="";

}
$D->customuserlogged =$user->id;


$notifications_cnt = isset($D->tabs_state['notifications'])? $notificationcnt: 0;
$privmsgs_cnt = isset($D->tabs_state['private'])? ($privatecnt ) :0;
if($D->tabs_state['@me'] > 0){$mentions_cnt=$D->tabs_state['@me'];}else{$mentions_cnt='';};
$total_cnt = $notifications_cnt + $mentions_cnt;

if($total_cnt > 0){$total_newcnt=$total_cnt;}else{$total_newcnt='';};


$D->hdr_search = ($page->request[0]=='members' ? 'users' : ($page->request[0]=='groups' ? 'groups' : ($page->request[0]=='search' ? $D->tab : 'posts') ) );

$menu = array( 'dashboard' => array('item-btn', $page->lang('hdr_nav_home')) );

if( $user->is_logged ){
$menu = array(  array('url' => 'dashboard', 'css_class' => 'item-btn'.($page->request[0] == 'dashboard' && $tab==''? ' active' : ''), 'title' => '<span class="glyphicon glyphicon-home"></span> '.$page->lang('hdr_nav_home').'') );	
$menu[] = array('url' => 'notifications', 'css_class' => 'item-btn'.($page->request[0] == 'notification'? ' active' : ''), 'title' => '<span class="glyphicon glyphicon-briefcase"></span> '.$page->lang('hdr_nav_workspace').' <span class="badge badge-workspace">'.$total_newcnt.'</span>' );
$menu[] = array('url' => 'dashboard/tab:everybody', 	'css_class' => ($page->request[0] == 'dashboard' && $tab=='everybody' ? ' active' : ''), 'title' => '<span class="glyphicon glyphicon-star"></span>Popular');


/*$menu[] = array('url' => 'predictions', 	'css_class' => ($page->request[0] == 'predictions'? ' active' : ''), 'title' => '<span class="glyphicon glyphicon-signal"></span>  '.$page->lang('hdr_nav_prediction').'');

//$menu[] = array('url' => '#', 	'css_class' => 'item-btn'.($page->request[0] == 'groups'? ' active' : ''), 'title' => $page->lang('hdr_nav_groups') );
/*$menu[] = array('url' => 'privatemessages', 	'css_class' => 'item-btn'.($page->request[0] == 'privatemessages'? ' active' : '')',title' => 
'<span class="glyphicon glyphicon-comment"></span>'.$page->lang('hdr_nav_privatemessages').' <span class="badge badge-privatemessage">'.$privmsgs_cnt.'</span>' );*/
}else{
if( strpos($_SERVER["REQUEST_URI"], 'view/post:') !== false ){
$menu = array(  array('url' => 'home?view='.$D->postid, 'css_class' => 'item-btn'.($page->request[0] == 'home'? ' active' : ''), 'title' => '<span class="glyphicon glyphicon-home"></span> '.$page->lang('hdr_nav_home').'') );
//$menu[] = array('url' =>'home?view='.$D->postid, 'css_class' => 'item-btn'.($page->request[0] == 'notification'? ' active' : ''), 'title' => '<span class="glyphicon glyphicon-briefcase"></span> '.$page->lang('hdr_nav_workspace').' <span class="badge badge-workspace">'.$total_newcnt.'</span>' );
/*
$menu[] = array('url' =>'home?view='.$D->postid, 	'css_class' => ($page->request[0] == 'predictions'? ' active' : ''), 'title' => '<span class="glyphicon glyphicon-signal"></span>  '.$page->lang('hdr_nav_prediction').'');

//$menu[] = array('url' => '#', 	'css_class' => 'item-btn'.($page->request[0] == 'groups'? ' active' : ''), 'title' => $page->lang('hdr_nav_groups') );
$menu[] = array('url' => 'home?view='.$D->postid, 	'css_class' => 'item-btn'.($page->request[0] == 'privatemessages'? ' active' : ''), 'title' => 
'<span class="glyphicon glyphicon-comment"></span>'.$page->lang('hdr_nav_privatemessages').' <span class="badge badge-privatemessage">'.$privmsgs_cnt.'</span>' );
*/				
}else
{

$menu = array(  array('url' => 'home', 'css_class' => 'item-btn'.($page->request[0] == 'home'? ' active' : ''), 'title' => '<span class="glyphicon glyphicon-home"></span> '.$page->lang('hdr_nav_home').'') );
$menu[] = array('url' => 'notification/tab:@me', 'css_class' => 'item-btn'.($page->request[0] == 'notification'? ' active' : ''), 'title' => '<span class="glyphicon glyphicon-briefcase"></span> '.$page->lang('hdr_nav_workspace').' <span class="badge badge-workspace">'.$notifications_cnt.'</span>' );
/*
$menu[] = array('url' => 'predictions', 	'css_class' => ($page->request[0] == 'predictions'? ' active' : ''), 'title' => '<span class="glyphicon glyphicon-signal"></span>  '.$page->lang('hdr_nav_prediction').'');

//$menu[] = array('url' => '#', 	'css_class' => 'item-btn'.($page->request[0] == 'groups'? ' active' : ''), 'title' => $page->lang('hdr_nav_groups') );
$menu[] = array('url' => 'privatemessages', 	'css_class' => 'item-btn'.($page->request[0] == 'privatemessages'? ' active' : ''), 'title' => 
'<span class="glyphicon glyphicon-comment"></span>'.$page->lang('hdr_nav_privatemessages').' <span class="badge badge-privatemessage">'.$privmsgs_cnt.'</span>' );
*/			
}
}
if( ! $user->is_logged ){
if( (strpos($_SERVER["REQUEST_URI"], 'search') !== false)  ){
unset($menu);
}
}
if( $user->is_logged ){
$mobileprofile ='<div class="col-xs-2 col-sm-2 pull-right">
<div class="user-options dropdown" style="float:right;">
<a   href="'.$C->SITE_URL.''.$user->info->username.'" class="arrow menu-btn" ><span class="plain-avatar" ><img src="'.$C->STORAGE_URL.'avatars/thumbs3/'.$user->info->avatar.'" alt="" width="30" height="30" /></span></a>

<ul class="menu-options" >

<a href="'.$C->SITE_URL.'groups/tab:my"><li class="workspace-left-main-menu-mob"><span><i class="fa fa-users fa-profile-icons" aria-hidden="true"></i> Groups</span></li></a>

<a href="'.$C->SITE_URL.'members/tab:ifollow"><li class="workspace-left-main-menu-mob"><span><i class="fa fa-user-plus fa-profile-icons" aria-hidden="true"></i> Who to follow</span></li></a>

<a href="'.$C->SITE_URL.'settingsmobile"><li class="workspace-left-main-menu-mob"><span><span class="glyphicon glyphicon-wrench"></span> Settings</span></li></a>

<a href="'.$C->SITE_URL.'signout"><li class="workspace-left-main-menu-mob-last"><span><span class="glyphicon glyphicon-log-out"></span> Sign out</span></li></a>

</ul>
</div>
</div>';
}else{
$mobileprofile = '<div class="col-xs-2 col-sm-2 "><a href="#" class="btn btn-info btn-sm headerme"  onclick="sighn('.$D->postid.')">
<span class="glyphicon glyphicon-log-in"></span> Log in
</a></div>';

}
if($page->request[0] =='view'){
$posttype="public";$objarr = array();
$tagbuzzs =new post($posttype, FALSE, $objarr);

$attachdata =$tagbuzzs->attchmentreplaydisplayfortags($D->postid);


$postmessage = $originalmessage =$tagbuzzs->tagmessage($D->postid);

$descriptionmessage = $postmessage[0]->message;
$description = strip_tags(substr(trim($descriptionmessage),0,500));
if($postmessage[0]->posttype == 1){
if(!empty($postmessage[0]->images[0])){
    $description=trim(trim(strip_tags($postmessage[0]->images[0]->caption)));
 }
}else if($postmessage[0]->posttype == 5){
        $description=trim($postmessage[0]->PollQuestion);
}

if( empty($description)){
$description = $postmessage[0]->title;
}


if( is_string($descriptionmessage) && is_array(json_decode($descriptionmessage, true)) ){
$descriptionmessage = $postmessage[0]->title;
}
/* if(!empty($postmessage[0]->title)){
$descriptionmessage = $postmessage[0]->title;

}*/
// $postmessage =$tagbuzzs->parsetext($postmessage[0]->message);
$postmessage =trim(substr(trim(strip_tags($descriptionmessage)),0,500));
if(!empty($originalmessage[0]->title)){
$postmessage = $originalmessage[0]->title;
}
if($originalmessage[0]->posttype == 5){

$poll = $tagbuzzs->getpollquestion($D->postid);
if(!empty($poll[0]->poll_question)){
$postmessage = $poll[0]->poll_question;

}





}

$newmeta ='';


$post_detail =$tagbuzzs->post_detail($D->postid);
$meta_title_detail =$tagbuzzs->meta_title_detail($D->postid);
$page_title_detail=$tagbuzzs->page_title_detail($D->postid);
$tpl->layout->setVar('pagetitle', $page_title_detail);
$user_location=$tagbuzzs->user_location($D->postid);
$news_meta_tags=$tagbuzzs->news_meta_tags($D->postid);

//Photo, LongStory, Video, Event, Poll, Normal
// og:type - The type of your object. video.movie for video news, Article for News, Photo News, Polls and Events. Depending on the type you specify, other properties may also be required.
$pstype='';
if($originalmessage[0]->posttype==1){
   $pstype='Photo News'; 
}else if($originalmessage[0]->posttype==2){
    $pstype='Article';
}
else if($originalmessage[0]->posttype==3){
    $pstype='video.movie';
}
else if($originalmessage[0]->posttype==4){
    $pstype='Events';
}
else if($originalmessage[0]->posttype==5){
    $pstype='Polls';
}


$newmeta .='
<meta content="'.$post_detail.'" http-equiv="content-language" />';	
$newmeta .='<meta name="description" content="'.$description.'" />';
$newmeta .='
<meta name="og:title" content="'.$meta_title_detail.'" />
<meta name="twitter:card" content="summary" />
<meta property="og:title" content="'.$meta_title_detail.'" />
<meta name="keywords" content="real-time news, local news, breaking news, '.$user_location.'"/>

<meta name="twitter:site" content="@Streetbuzz">
<meta name="twitter:creator" content="'.$originalmessage[0]->username.'">
<meta name="twitter:description" content="'.$description.'">

<meta property="type" content="'.$pstype.'"/>
<meta property="articleid" content="'.$D->postid.'"/>
<meta data-rh="true" name="news_keywords" content="'.$news_meta_tags.'">
';



if($attachdata['type'] =='image'){
$imgurl = $attachdata['url'];


preg_match_all('/<img[^>]+>/i',$descriptionmessage, $parsehtml); 

if(!empty($parsehtml) && !empty($parsehtml[0])){
$imgurl =  $parsehtml[0][0];
$doc = new DOMDocument();
$doc->loadHTML($imgurl);
$xpath = new DOMXPath($doc);
$imgurl = $xpath->evaluate("string(//img/@src)");
if(strpos($imgurl,$C->SITE_URL) !== false){
$fileurlsreplace =str_replace($C->SITE_URL.'storage','',$imgurl);
if(strpos($fileurlsreplace,"png") !== false){
$imageexplode = explode(".png",$fileurlsreplace);
$imageexplodefirst = $imageexplode[0];
$thumburl = $imageexplodefirst."_thumb.png";

if(file_exists($C->STORAGE_DIR.$thumburl)){
$imgurl = $C->SITE_URL.'storage'.$thumburl;


}

}
if(strpos($fileurlsreplace,"jpeg") !== false){
$imageexplode = explode(".jpeg",$fileurlsreplace);
$imageexplodefirst = $imageexplode[0];
$thumburl = $imageexplodefirst."_thumb.jpeg";

if(file_exists($C->STORAGE_DIR.$thumburl)){
$imgurl = $C->SITE_URL.'storage'.$thumburl;


}

}
if(strpos($fileurlsreplace,"JPEG") !== false){
$imageexplode = explode(".JPEG",$fileurlsreplace);
$imageexplodefirst = $imageexplode[0];
$thumburl = $imageexplodefirst."_thumb.JPEG";

if(file_exists($C->STORAGE_DIR.$thumburl)){
$imgurl = $C->SITE_URL.'storage'.$thumburl;


}

}
if(strpos($fileurlsreplace,"jpg") !== false){
$imageexplode = explode(".jpg",$fileurlsreplace);
$imageexplodefirst = $imageexplode[0];
$thumburl = $imageexplodefirst."_thumb.jpg";

if(file_exists($C->STORAGE_DIR.$thumburl)){
$imgurl = $C->SITE_URL.'storage'.$thumburl;


}

}
if(strpos($fileurlsreplace,"JPG") !== false){
$imageexplode = explode(".JPG",$fileurlsreplace);
$imageexplodefirst = $imageexplode[0];
$thumburl = $imageexplodefirst."_thumb.JPG";

if(file_exists($C->STORAGE_DIR.$thumburl)){
$imgurl = $C->SITE_URL.'storage'.$thumburl;


}

}
if(strpos($fileurlsreplace,"gif") !== false){
$imageexplode = explode(".gif",$fileurlsreplace);
$imageexplodefirst = $imageexplode[0];
$thumburl = $imageexplodefirst."_thumb.gif";

if(file_exists($C->STORAGE_DIR.$thumburl)){
$imgurl = $C->SITE_URL.'storage'.$thumburl;


}

}
if(strpos($fileurlsreplace,"GIF") !== false){
$imageexplode = explode(".GIF",$fileurlsreplace);
$imageexplodefirst = $imageexplode[0];
$thumburl = $imageexplodefirst."_thumb.GIF";

if(file_exists($C->STORAGE_DIR.$thumburl)){
$imgurl = $C->SITE_URL.'storage'.$thumburl;


}

}

}


}



$newmeta .='<meta property="og:image" content="'.$imgurl.'" />';
$newmeta .='<meta property="twitter:image" content="'.$imgurl.'" />';
$newmeta .='<meta property="og:image:alt" content="'.$meta_title_detail.'" />';
}
if($attachdata['type'] =='video'){
$imgurl = $attachdata['url'];
$findthumb =   $network->findthumb($D->postid);
$thumb =$findthumb->thumb;
$thumbimg = $C->SITE_URL.'storage/attachments/1/'.$thumb;

$newmeta .='<meta property="og:image" content="'.$thumbimg.'" />'
;
$newmeta .='<meta property="twitter" content="'.$thumbimg.'" />'
;

}

}else{
$newmeta ='<meta name="description" content="'.$description.'" >
<meta name="description"  content="'.$description.'">
<meta name="author" content="Streetbuzz"/>
<meta name="page-topic" content="'.$description.'"   />
<meta name="copyright"  content="'.$description.'"/>
<meta name="robots" content="All"/>
<meta name="googlebot" content="Index, follow"/>
<meta name="msnbot" content="Index, follow"/>
<meta name="allow-search" content="yes"/>
<meta name="revisit-after" content="7 days"/>
<meta name="distribution" content="global"/>
<meta name="expires" content="never"/>
<meta name="language" content="English"/>';

}
$tpl->layout->setvar('newmeta',$newmeta);
if( $user->is_logged ){
$cssdis ='block';
}else{
$cssdis ='none';
}
$tpl->layout->setVar( 'mobileheadercss',$cssdis); 	
$tpl->layout->setVar( 'mobileprofile',$mobileprofile); 
$tpl->layout->setVar( 'mobile_notifications_notcnt',$notifications_cnt); 
$tpl->layout->setVar( 'mobile_notifications_mentcnt',$mentions_cnt); 
$totalcnthtml ='<span class="badge badge-workspace-mobile">'.$total_newcnt.'</span>';

$tpl->layout->setVar( 'mobile_notifications_cnt',$totalcnthtml); 
if($page->request[0] == 'dashboard' && $tab==''){
$dashboardclass='active';
}else{
$dashboardclass ='';
}
if($page->request[0] == 'notification'){
$notificationclass='active';
}else{
$notificationclass ='';
}
if($page->request[0] == 'dashboard' && $tab=='everybody'){
$favourite='active';
}else{
$favourite='';
}
if($page->request[0] == 'searchmob'){
$searchclass='active';
}else{
$searchclass='';
}	
if( $user->is_logged ){
$searchmobile ='<a href="'.$C->SITE_URL.'/searchmob"><span class="glyphicon glyphicon-search mob-header-menu-icon '.$searchclass.'"></span></a>
';
$searchmobilelg=2;
}else{
if($page->param('s') !='' ){
$searchstr  = $page->param('s');
}else{
$searchstr  ='';
}


$searchmobile ='<form id="searchForm" method="post" action="'.$C->SITE_URL.'/search">
<div class="input-group">
<input type="hidden" id="hserch" name="serchtab" value="">
<input type="hidden" name="defaultval" value="">
<input type="text" class="form-control search-field" style="position:relative;" name="lookfor" value="'.$searchstr.'"  x-webkit-speech="" autocomplete="off" onwebkitspeechchange="STX.searchReplace();" data-watermark="Search ..." placeholder="Search...">
<div class="input-group-btn">
<button class="btn btn-default btn-xs" type="submit"><i class="glyphicon glyphicon-search"></i></button>
</div>
</div>

</form>
';


$searchmobilelg=7;


}

$tpl->layout->setVar('searchmobile',$searchmobile);
$tpl->layout->setVar('searchmobilelg',$searchmobilelg);
$tpl->layout->setVar( 'dashboardclass',$dashboardclass);
$tpl->layout->setVar( 'notificationclass',$notificationclass);
$tpl->layout->setVar( 'favourite',$favourite);
$tpl->layout->setVar( 'searchclass',$searchclass);




$tpl->layout->setVar( 'main_navigation', $tpl->designer->createMenu( 'main-navigation', $menu, 'header_top_menu' ) ); unset($menu);

$tpl->layout->useBlock( 'header-content' );

$tpl->layout->useInnerBlock( 'header-content-searcharea' );

/* Start : SEARCH tab */
$tpl->layout->inner_block->saveInBlockPart('header_content_searcharea');
/* End : SEARCH tab */




$tpl->layout->block->save( 'header_content' );

if( FALSE === ($tmp = getCachedHTML('header_data')) ){
$tmp = $tpl->designer->getMetaData().$tpl->designer->getCSSData().$tpl->designer->getFaviconData();
setCachedHTML('header_data', $tmp);
}
//$tmp = $tpl->designer->getMetaData().$tpl->designer->getCSSData().$tpl->designer->getFaviconData();

$tpl->layout->setVar( 'header_data', $tmp );	
$tpl->layout->setVar( 'logo_data', $tpl->designer->loadNetworkLogo() );

}
?>
