<?php
global $db2, $C;

$page 		= & $GLOBALS['page'];
$user 		= & $GLOBALS['user'];
$network 	= & $GLOBALS['network'];
$pm 		= & $GLOBALS['plugins_manager'];

$page->load_langfile('inside/global.php');
$page->load_langfile('inside/dashboard.php');

switch( $ajax_action ){

case 'get':

break;

case 'checknew':
if(!empty($user->id)){
$activities_type 	= isset($_POST[ 'activities_type' ])? 	$_POST[ 'activities_type' ] 		: 		'';
$activities_id 		= isset($_POST[ 'last_activity' ])? 	intval($_POST[ 'last_activity' ]) 	: 		0;
$activities_group 	= isset($_POST[ 'activities_group' ])?	$_POST[ 'activities_group' ] 		: 		'';
$activities_tab 	= isset($_POST[ 'activities_tab' ])?	$_POST[ 'activities_tab' ] 		: 		'';
$last_activity_date 	= isset($_POST[ 'last_activity_date' ])?	$_POST[ 'last_activity_date' ] 		: 		'';


if( empty($activities_type) || empty($activities_id) ){
//echo 'ERROR';
return;
}

$activity = activityFactory::select($activities_type);
if( !empty($activities_group) ){
$g = $network->get_group_by_id($activities_group);
$activity->setGroup( $g );
}
if( !empty($activities_tab) ){
$activity->tab = $activities_tab;
}
$activity->setNewetsPost( $activities_id );
$activity->setNewetsPostlastdate($last_activity_date );


$new_activities = $activity->checkNewPosts();

$new_activities_text = ' new activity';
if($new_activities >1)$new_activities_text = ' new activities';
$new_activities_tab = ( $activities_type === 'dashboard' )? $network->get_dashboard_tabstate($user->id, array('all', 'commented', '@me', 'private','notifications')) : array('all'=>0, '@me'=>0, 'commented'=>0, 'private'=>0, 'notifications'=>0);
$answer = array(	'html'=>$new_activities. $new_activities_text, 
'new_activities_dashboard'=>$new_activities, 
'new_activities_tab_all'=>$new_activities_tab['all'], 
'new_activities_tab_at'=>$new_activities_tab['@me'],
'new_activities_tab_commented'=>$new_activities_tab['commented'],
'new_messages'=>$new_activities_tab['private'],
'new_notifications'=>$new_activities_tab['notifications']
);
}else{
$answer = array('html'=>"0", 
'new_activities_dashboard'=>"0", 
'new_activities_tab_all'=>"0", 
'new_activities_tab_at'=>"0",
'new_activities_tab_commented'=>"0",
'new_messages'=>"0",
'new_notifications'=>"0"
);
}

echo json_encode($answer);

break;

case 'getnew': 
$activities_type 	= isset($_POST[ 'activities_type' ])? 	$_POST[ 'activities_type' ] 		: 		'';
$activities_id 		= isset($_POST[ 'last_activity' ])? 	intval($_POST[ 'last_activity' ]) 	: 		0;
$activities_group 	= isset($_POST[ 'activities_group' ])?	$_POST[ 'activities_group' ] 		: 		'';
$activities_tab 	= isset($_POST[ 'activities_tab' ])? 	$_POST[ 'activities_tab' ] 			: 		'';
$last_activity_date 	= isset($_POST[ 'last_activity_date' ])?	$_POST[ 'last_activity_date' ] 		: 		'';


if( empty($activities_type) || empty($activities_id) ){
echo 'ERROR';
return;
}

$tpl = new template(array(), FALSE);

$tpl->useStaticHTML();
$tpl->staticHTML->useActivityContainer();

$activity = activityFactory::select($activities_type);
$activity->setTemplate( $tpl );
if( !empty($activities_group) ){
$g = $network->get_group_by_id($activities_group);
$activity->setGroup( $g );
}
if( !empty($activities_tab) ){
$activity->tab = $activities_tab;
}

if($activities_tab =="all"){
//$result = $activity->loadPosts($activities_id, TRUE);
$result = $activity->loadPostslastactivitydate($last_activity_date, TRUE);

$answer = array('html'=>$tpl->display(true), 'first_activities_id'=>$result[0],'last_activity_date'=>$result[2]);

}else{
$result = $activity->loadPosts($activities_id, TRUE);
//$result = $activity->loadPostslastactivitydate($last_activity_date, TRUE);

$answer = array('html'=>$tpl->display(true), 'first_activities_id'=>$result[0]);
}

echo json_encode($answer);

break;

case 'getall':
$activities_type 	= isset($_POST[ 'activities_type' ])? 	$_POST[ 'activities_type' ] 		: 		'';
$activities_id 		= isset($_POST[ 'activities_id' ])? 	intval($_POST[ 'activities_id' ]) 	: 		0;
$activities_tab 	= isset($_POST[ 'activities_tab' ])? 	$_POST[ 'activities_tab' ] 			: 		'';
$activities_group 	= isset($_POST[ 'activities_group' ])?	$_POST[ 'activities_group' ] 		: 		'';
$activities_user	= isset($_POST[ 'activities_user' ])? 	$_POST[ 'activities_user' ] 		: 		'';
$activities_search	= isset($_POST[ 'activities_search' ])? $_POST[ 'activities_search' ] 		: 		'';

if( !empty($activities_type) && $activities_id ){
$tpl = new template(array(), FALSE);


$tpl->useStaticHTML();
$tpl->staticHTML->useActivityContainer();

$activity = activityFactory::select($activities_type);
$activity->setTemplate( $tpl );
if( !empty($activities_group) ){
$g = $network->get_group_by_id($activities_group);
$activity->setGroup( $g );
}
if( !empty($activities_user) ){
$u = $network->get_user_by_id($activities_user);
$activity->setUser( $u );
}

if( !empty($activities_tab) ){
$activity->tab = $activities_tab;
}

if( !empty($activities_search) ){
$activity->search_string = $activities_search;
}

$result = $activity->loadPosts($activities_id); 
$_SESSION['ajax_html'] = $tpl->display(true);

/*	$answer = array('html'=>$tpl->display(true), 'last_activities_id'=>$result[1]);
$cookie_name = "lastactivity";
$cookie_value =$result[1];
setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day

echo json_encode($answer);*/
}

break;
case 'set':


$activity_text 	= isset($_POST[ 'activities_text' ])? $_POST[ 'activities_text' ] : '';
$activities_token 	= isset($_POST[ 'token' ])? $_POST[ 'token' ] : '';

$activity_title 	= isset($_POST[ 'activities_title' ])? $_POST[ 'activities_title' ] : '';




if( !empty($activity_text) && !empty($activities_token) ){

//$p = new newpost();

//
$sess = &$user->sess;
if( ! isset($sess['TEMP_ACTIVITY_POSTS']) ) {
$sess['TEMP_ACTIVITY_POSTS']	= array();
}
if( ! isset($sess['TEMP_ACTIVITY_POSTS'][$activities_token]) ) {
$sess['TEMP_ACTIVITY_POSTS'][$activities_token]	= new newpost();
}
$p	= & $sess['TEMP_ACTIVITY_POSTS'][$activities_token];
//
if( isset($sess['TEMP_ACTIVITY_POSTS_ATTACHMENTS'][$activities_token]) ){
$att	= & $sess['TEMP_ACTIVITY_POSTS_ATTACHMENTS'][$activities_token];
}

if( isset($_POST[ 'activities_type' ]) && $_POST[ 'activities_type' ] == 'profile' ){
if(isset($_POST[ 'activities_username' ]) && strpos($activity_text, '@'.$_POST[ 'activities_username' ]) === false ){
$activity_text = '@'.$_POST[ 'activities_username' ].' '.$activity_text;
}
}
//print_r($activity_text);exit;


if(isset($_POST[ 'activities_sharemarketdata' ])){

$sharedata             =$_POST[ 'activities_sharemarketdata' ];
$sgharedata_filter     = array_filter($sharedata);
$arr                   = array();
$sharedata_final       = array_merge($arr,$sgharedata_filter);




$p->sharemarketdata($sharedata_final);

}

$p->posttitle($activity_title);

$coverimage 	= isset($_POST[ 'coverimage' ])? $_POST[ 'coverimage' ] : '';

$p->coverimage($coverimage);

if(isset($_POST['googlecity'])){
$p->geolocation($_POST['googlecity']);


}
if(isset($_POST['pageurlpage'])){
$p->pageurlpage($_POST['pageurlpage']);


}
$starcnt =  substr_count($activity_text,'*');
if($starcnt >=2 ){
$message = preg_replace('/(?:\*)([^*]*)(?:\*)/', '<strong>$1</strong>', $activity_text);
$activity_text = preg_replace('/(?:_)([^_]*)(?:_)/', '<i>$1</i>', $message); 

}
$p->set_message($activity_text);

if(!empty($_POST['group_new'])){

if( isset($_POST[ 'activities_group' ]) && !empty($_POST[ 'activities_group' ]) ){
$p->set_group_id( intval( $_POST[ 'activities_group' ] ) );
}
elseif(isset($att['group']) && !empty($att['group'])){
$p->set_group_id( intval( $att['group'] ) );
}
}else{
if( isset($_POST[ 'activities_group' ]) && !empty($_POST[ 'activities_group' ]) ){
$p->set_group_id( intval( $_POST[ 'activities_group' ] ) );
}
elseif(isset($att['group']) && !empty($att['group'])){
$p->set_group_id( intval( $att['group'] ) );
}

}

if( isset($att['image']) ){
 foreach($att['image'] as $img){
if( $ii = $p->attach_image($C->STORAGE_TMP_DIR.$img->tempfile, $img->filename) ) {
rm($C->STORAGE_TMP_DIR.$img->tempfile);
}
}
unset($att['image']);
}

if( isset($att['file']) ){
foreach($att['file'] as $file){
if( $ff = $p->attach_file($C->STORAGE_TMP_DIR.$file->tempfile, $file->filename, $file->detected_type) ) {
rm($C->STORAGE_TMP_DIR.$file->tempfile);
}
}
unset($att['file']);
}

if( isset($att['link']) ){
foreach($att['link'] as $link){
$p->attach_link($link);
}
unset($att['link']);
}

if( isset($att['videoembed']) ){
foreach($att['videoembed'] as $vid){
$p->attach_videoembed($vid);
}
unset($att['videoembed']);
}
 
/*
 $p->post_level();
 print_r($p);
 die('===========');*/
 
$res	= $p->save();

$p->remove_post_cache();

if( $res ){
$activity_id = explode('_', $res);
$activity_id = intval($activity_id[0]);
$activity_type = $activity_id[1]; //delete private post

$obj = $db2->query( 'SELECT * FROM '.( $activity_type=='private'? 'posts_pr' : 'posts' ).' WHERE id="'. $activity_id .'" LIMIT 1' );
$obj = $db2->fetch_object($obj);
$obj->type = $activity_type=='private'? 'private' : 'public';

$tpl = new template(array(), FALSE);

$tpl->useStaticHTML();
$tpl->staticHTML->useActivityContainer();

$tpl->initRoutine('SingleActivity', array( &$obj, FALSE ));
$tpl->routine->load();
$_SESSION['ajax_html'] = $tpl->display(true);

$answer = array('html'=>$tpl->display(true), 'inserted_activities_id'=>$activity_id);

echo json_encode($answer);
return;
}

if( $errmsg = $pm->getEventCallErrorMessage() ){
echo 'ERROR:' . $errmsg;
return;
}

echo 'ERROR:'.$page->lang('global_ajax_post_error');
return;
}

break;

case 'delete':	
if( isset($_POST['activities_id']) && isset($_POST['activities_type']) )
{
$p	= new post($_POST['activities_type'], $_POST['activities_id']);
if( $p->error ) {
echo 'ERROR:'.$page->lang('global_ajax_post_error1');
return;
}
if( $p->delete_this_post() ) {
echo 'OK';
return;
}

if( $errmsg = $pm->getEventCallErrorMessage() ){
echo 'ERROR:' . $errmsg;
return;
}
}

echo 'ERROR:'.$page->lang('global_ajax_post_error');
return;

break;

case 'bookmark':

if( isset($_POST['activities_id']) && isset($_POST['activities_type']) )
{
$p	= new post($_POST['activities_type'], $_POST['activities_id']);
if( $p->error ) {
echo 'ERROR:'.$page->lang('global_ajax_post_error1');
return;
}

$type = $p->is_post_faved()? FALSE : TRUE;

if( $p->fave_post($type) ) {
echo 'OK';
return;
}
}

echo 'ERROR:'.$page->lang('global_ajax_post_error');
return;

break;

case 'like': 

if( isset($_POST['activities_id']) && isset($_POST['activities_type']) ) {
$p	= new post($_POST['activities_type'], $_POST['activities_id']);
if( $p->error ) {
echo 'ERROR:Invalid post data provided.';
return;
}

if( !$p->could_be_liked() && !$p->is_post_liked() ){
echo 'ERROR';
return;
}
$ownuserres          =$p->get_own_user($_POST['activities_id']);
$ownuserid           =$ownuserres->user_id;
$not_type='ntf_me_on_post_like';
$checkuserres =$p->checkemptyuser($ownuserid);
if($checkuserres->num_rows == "0"){
$ownnotification =1;
}else{
$ownnotification     =$p->checknotrules($ownuserid,$not_type);
if(!empty($ownnotification)){
$ownnotification = $ownnotification;
}else{
$ownnotification =1;
}

}
if($ownnotification ==1 || $ownnotification ==2 || $ownnotification ==3){

if($ownuserid != $user->id){
$posttype      =$p->typeofpostofevent($_POST['activities_id']);
if($posttype->num_rows > 0){
$type ="event";
}else{
$polltype      =$p->typeofpostofpoll($_POST['activities_id']);
if($posttype->num_rows > 0){
$type ="poll";
}else{
$activitiestype      =$p->typelinks($_POST['activities_id']);
if(!empty($activitiestype)){

if($activitiestype->type=="videoembed"){
$type="video link";

}elseif($activitiestype->type=="image"){
$type="image";

}elseif($activitiestype->type=="file"){
$str = (unserialize($activitiestype->data));
$ext = pathinfo($str->file_original, PATHINFO_EXTENSION);
if($ext =='wmv' || $ext =='mp4' || $ext =='avi' || $ext =='mov' || $ext =='qt'){
$type = "video";

}else{
$type = "file";

}
}
}else{
$type ="buzz";	
}

}				
}

$notifytype="like";
$standardnotifytype ="ntf_me_on_post_like";
$newisert =$p->insert_active_notifications($ownuserid,$_POST['activities_id'],$notifytype,$type,$standardnotifytype);
}
}

if( $p->like_post(TRUE) ){
$likes = $p->get_post_likes();
$likes_number = isset($likes['post'])? count($likes['post']) : 0;
if($likes_number > 0){
$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$_POST['activities_type'].'","activities_id":"'.$_POST['activities_id'].'"}').'">'.$likes_number.'</a>';
}else{
$showlikes_btn ='';
}
$likes_number = isset($likes['post'])? count($likes['post']) : '';



$like_content = '<a href="" data-role="services" data-namespace="activities" data-action="unlike" data-value="'.htmlentities('{"activities_type":"'.$_POST['activities_type'].'","activities_id":"'.$_POST['activities_id'].'"}').'"><img  src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/> '.$showlikes_btn.' </a>';

echo '<div class="like-list icon-ftr">'.$like_content.'</div>';
return;
}

}

echo 'ERROR:Invalid post.';

break;

case 'share': 
$share_id= $_POST['id'];  
$p	= new post($_POST['activities_type'], 
$_POST['id']);	
$share = $p->get_post_share($share_id);

echo $share;

return;

break;
/*post_profile*/
case 'postprofile':
$post_profile_who= $_POST['who']; 
$post_profile_whom= $_POST['whom'];

$p = new post($_POST['activities_type'], 
$_POST['who']);
$post_profiles = $p->get_post_profile($post_profile_who,$post_profile_whom);

echo $post_profiles;

return;

break;
/*end*/
case 'unlike': 

if( isset($_POST['activities_id']) && isset($_POST['activities_type']) ) {
$p	= new post($_POST['activities_type'], $_POST['activities_id']);
if( $p->error ) {
echo 'ERROR:Invalid post data provided.';
return;
}

if( !$p->could_be_liked() && !$p->is_post_liked() ){
echo 'ERROR';
return;
}

if( $p->like_post(FALSE) ){
$likes = $p->get_post_likes();
$likes_number = isset($likes['post'])? count($likes['post']) : 0;
if($likes_number > 0){

$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$_POST['activities_type'].'","activities_id":"'.$_POST['activities_id'].'"}').'">'.$likes_number.'</a>';
}else{
$showlikes_btn ='';
}
if($likes_number > 0){
$likes_number = $likes_number;
}else{
$likes_number ='';
}


$like_content = '<a href="" data-role="services" data-namespace="activities" data-action="like" data-value="'.htmlentities('{"activities_type":"'.$_POST['activities_type'].'","activities_id":"'.$_POST['activities_id'].'"}').'"><img class="icons" src="'.$C->SITE_URL.'static/images/icons/LIKE.png"  title="Like"/>'.$showlikes_btn.'</a>';

echo '<div class="like-list icon-ftr">'.$like_content.'</div>';

return;
}

}

echo 'ERROR:Invalid post.';
break;
case 'agree':
if( isset($_POST['activities_id']) && isset($_POST['activities_type']) ) {
$p	= new post($_POST['activities_type'], $_POST['activities_id']);

}
if( $p->error ) {
echo 'ERROR:Invalid post data provided.';
return;
}
$ownuserres          =$p->get_own_user($_POST['activities_id']);
$ownuserid           =$ownuserres->user_id;
$not_type='ntf_me_on_post_agree';
$checkuserres =$p->checkemptyuser($ownuserid);
if($checkuserres->num_rows == "0"){
$ownnotification =1;
}else{
$ownnotification     =$p->checknotrules($ownuserid,$not_type);
if(!empty($ownnotification)){
$ownnotification= $ownnotification;
}else{
$ownnotification =1;
}

}
if($ownnotification ==1 || $ownnotification ==2 || $ownnotification ==3 ){

if($ownuserid != $user->id){
$posttype      =$p->typeofpostofevent($_POST['activities_id']);
if($posttype->num_rows > 0){
$type ="event";
}else{
$polltype      =$p->typeofpostofpoll($_POST['activities_id']);
if($posttype->num_rows > 0){
$type ="poll";
}else{
$activitiestype      =$p->typelinks($_POST['activities_id']);
if(!empty($activitiestype)){

if($activitiestype->type=="videoembed"){
$type="video link";

}elseif($activitiestype->type=="image"){
$type="image";

}elseif($activitiestype->type=="file"){
$str = (unserialize($activitiestype->data));
$ext = pathinfo($str->file_original, PATHINFO_EXTENSION);
if($ext =='wmv' || $ext =='mp4' || $ext =='avi' || $ext =='mov' || $ext =='qt'){
$type = "video";

}else{
$type = "file";

}
}
}else{
$type ="buzz";	
}

}				
}
$notifytype="agree";
$standardtype ="ntf_me_on_post_agree";

$newisert =$p->insert_active_notifications($ownuserid,$_POST['activities_id'],$notifytype,$type,$standardtype);
}
}
if( $p->agree_post(TRUE) ){
$agree = $p->get_post_agree();
$agree_number = isset($agree ['post'])? count($agree['post']) : 0;
if($agree_number > 0){
$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$_POST['activities_type'].'","activities_id":"'.$_POST['activities_id'].'"}').'">'.$agree_number.'</a>';
}else{
$showlikes_btn ='';
}
$agree_number = isset($likes['post'])? count($likes['post']) : '';



$agree_content = '<a href="" data-role="services" data-namespace="activities" data-action="disagree" data-value="'.htmlentities('{"activities_type":"'.$_POST['activities_type'].'","activities_id":"'.$_POST['activities_id'].'"}').'"><img  width="" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Agree"/> '.$showlikes_btn.' </a>';

echo '<div class="agree-list icon-ftr">'.$agree_content.'</div>';
return;
}



break;
case 'disagree': 

if( isset($_POST['activities_id']) && isset($_POST['activities_type']) ) {
$p	= new post($_POST['activities_type'], $_POST['activities_id']);

}
if( $p->error ) {
echo 'ERROR:Invalid post data provided.';
return;
}
if( $p->agree_post(FALSE) ){
$agree = $p->get_post_agree();
$agree_number = isset($agree ['post'])? count($agree ['post']) : 0;
if($agree_number > 0){
$showlikes_btn = '<a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showlikes" data-value="'.htmlentities('{"activities_type":"'.$_POST['activities_type'].'","activities_id":"'.$_POST['activities_id'].'"}').'">'.$agree_number.'</a>';
}else{
$showlikes_btn ='';
}
$agree_number = isset($likes['post'])? count($likes['post']) : '';



$agree_content = '<a href="" data-role="services" data-namespace="activities" data-action="agree" data-value="'.htmlentities('{"activities_type":"'.$_POST['activities_type'].'","activities_id":"'.$_POST['activities_id'].'"}').'"><img  width=" class="icons" src="'.$C->SITE_URL.'static/images/icons/a2d.png"  title="Disagree"/> '.$showlikes_btn.' </a>';

echo '<div class="agree-list icon-ftr">'.$agree_content.'</div>';
return;
}
break;


case 'showlikes': 

if( isset($_POST['activities_id']) && isset($_POST['activities_type']) ) {
$p	= new post($_POST['activities_type'], $_POST['activities_id']);
if( $p->error ) {
echo 'ERROR:Invalid post data provided.';
return;
}

global $C;

$likes = $p->get_post_likes();
$html = '';
if( isset($likes['post']) && count($likes['post'])>0 ){
foreach( $likes['post'] as $v ){
$html .= '<div class="popup-count"><a href="'.userlink($v[0]).'"><img src="'.getAvatarUrl($v[1], 'thumbs3').'" /> '.$v[0].'</a></div>';
}
}

echo $html;
return;

}

echo 'ERROR:Invalid post.';

break;
case 'showloves': 

if( isset($_POST['activities_id']) && isset($_POST['activities_type']) ) {
$p	= new post($_POST['activities_type'], $_POST['activities_id']);

global $C;

$loves = $p->get_profile_likes($_POST['activities_id']);
$html = '';
if(!empty($loves)){

foreach( $loves as $keys=>$v ){
if($v->avatar !=""){
$img = '<img src="'.getAvatarUrl($v->avatar, 'thumbs3').'" /> '; 

}else{
$avatar = "_noavatar_user.gif";
$img = '<img src="'.$C->STORAGE_URL.'avatars/thumbs1/'.$avatar.'" title="'.htmlspecialchars(getThisUserCommunityName($u)).'" width="50" height="50" /> '; 
}
$html .= '<div class="popup-count"><a href="'.userlink($v->username).'">'.$img.' '.$v->username.'</a> </div>';
}
}


echo $html;
return;

}

echo 'ERROR:Invalid post.';
break;

case 'edit': 

if( isset($_POST['activities_id'], $_POST['activities_type'], $_POST['message']) ) {
if( empty($_POST['message']) ){
echo 'ERROR:Empty message.';
return;
}

$p	= new post($_POST['activities_type'], $_POST['activities_id']);
if( $p->error ) {
echo 'ERROR:Invalid post data provided.';
return;
}

if( !$p->if_can_edit() ){
echo 'ERROR: You could not edit this post.';
return;
}

$message = strip_tags($_POST['message']);

if( $p->edit($message) ){
echo 'OK: Post edited';
return;
}

}

echo 'ERROR:Invalid post.';
break;
}