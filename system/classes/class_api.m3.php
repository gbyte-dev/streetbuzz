<?php
error_reporting(0);

class API
{

public function event_details($userid,$postid){

global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

$pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);

$pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);


$db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
//                        SUBSTRING(p.message, 1, 75) as title, 

$query = 'SELECT
b.id AS pid,
p.id AS postid,
p.user_id AS postuserid,
u.username AS postusername,
u.avatar AS postuserimage,
if( (u.cover is null), "", u.cover) AS coverimage,
if( (p.title is null), "", p.title) AS title, 
p.posttype,
if( (posttype = 3), pa.content, p.message) AS message,
p.likes,
p.mentioned,
GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
p.posttags,
p.comments,
p.reshares,
p.date,
p.date_lastedit,
p.date_lastcomment,
p.group_name,
p.parent_id,
p.status,
p.post_level,
p.location,
p.thumb,
"" as `category`,
"public" AS `type`,
if( (pv.cnt is null), 0, pv.cnt) AS ViewCount,
(Select count(id) from posts_social_share where post_id =p.id ) AS sharecount,
if( ((Select count(id) from posts_social_share where post_id =p.id and user_id='.$userid.') > 0), true, false) AS isshared,
events.id,
events.admin_id,
events.address,
events.location,
events.event_name,
events.start_date,
events.start_time,
events.end_date,
events.end_time,
events.event_description,
events.status,
count(b.id) as totalvote 

FROM post_userbox b
LEFT JOIN posts p ON p.id=b.post_id
LEFT JOIN users u ON u.id=p.user_id
LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
LEFT OUTER JOIN post_views_list pv ON pv.post_id = p.id
LEFT OUTER JOIN event_posts ep ON ep.post_id = p.id
LEFT  OUTER JOIN  events  ON ep.event_id=events.id
WHERE
p.id='.$postid.'
Group By p.id
ORDER BY p.date_lastcomment DESC
LIMIT '.$pagenumber.','.$pagerecordcount;


$res =  $db2->query($query);

$array = $res->fetch_all(MYSQLI_ASSOC);
if($array['0']['location']=="null,null"){
$array['0']['location']=null;
}
//  echo $array['location']; die;
$returnarray = array();

foreach ($array as $i =>$array_expression) {
/* if($array[$i]["posttype"] != 2) {
$title = strip_tags($array[$i]["message"]); 
$array[$i]["title"] = mb_substr($title, 0, 144,'UTF-8');
}
*/
$array[$i]["title"] = strip_tags($array[$i]["title"]); 

$array[$i]["likes"] = $this->getLikes($array[$i]["postid"]);

$array[$i]["comments"] = $this->getCommentsCount($array[$i]["postid"]);
$array[$i]["commentdetails"] = $this->getPostComments($array[$i]["postid"]);
$query = 'SELECT id FROM post_likes WHERE  user_id="'.$userid.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
$res =  $db2->query($query);
$obj = $db2->fetch_object($res);


if(empty($obj->id)){
$array[$i]["isliked"] = "0";  
}

else {
$array[$i]["isliked"] = "1";  
}

$query1 = 'SELECT id FROM post_reshares WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
$res1 =  $db2->query($query1);
$obj1 = $db2->fetch_object($res1);


if(empty($obj1->id)){
$array[$i]["isbuzzed"] = "0";  
}

else {
$array[$i]["isbuzzed"] = "1";  
}


$query3 = 'SELECT id FROM users_followed WHERE  who="'.$user_id.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
$res3 =  $db2->query($query3);
$obj3 = $db2->fetch_object($res3);

if(empty($obj3->id)){
$array[$i]["isfollwed"] = "0";  
}

else {
$array[$i]["isfollwed"] = "1";  
}

$array[$i]["likes"]      =$this->get_like_count($array[$i]["postid"]);
$array[$i]["reshares"]       =$this->new_reshare_count($postid);
$videoquery = 'SELECT * FROM posts_attachments WHERE  type="'.'file'.'"  AND post_id = '.$array[$i]["postid"].' ';
$videores =  $db2->query($videoquery);
$videoattachment = $videores->fetch_all(MYSQLI_ASSOC);
if(!empty($videoattachment)){
$videos = $videoattachment[0]['data'];
$unserialize = unserialize($videos);
$videoattachmentdata = $unserialize->file_original;
$array[$i]['video_attachment'] = $videoattachmentdata;
}else{
$array[$i]['video_attachment']  = '';
}

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
$url = "https://";
else  
$url = "http://";   

$url.= $_SERVER['HTTP_HOST'];  
$array[$i]["profile_base_url"] =       $C->SITE_URL."storage/avatars/thumbs1/";
$array[$i]["attachment_base_url"] =       $C->SITE_URL."storage/attachments/1/";

$r = $db2->query('select count(id) as joincount  FROM post_userbox WHERE post_id="'.$array[$i]["postid"].'" AND event_status="'.'1'.'"', FALSE);
$result = $db2->fetch_object($r);
$sharecount = $result->joincount;  

$array[$i]['joincount'] = $sharecount;


$query = 'select event_status FROM post_userbox WHERE user_id="'.$userid.'" AND post_id="'.$array[$i]["postid"].'"';    
$res =  $db2->query($query);
$userResp = $res->fetch_all(MYSQLI_ASSOC);


$array[$i]['event_status'] = $userResp[0]['event_status'];


$query = 'SELECT * FROM posts_attachments WHERE  type="'.'image'.'"  AND post_id = '.$array[$i]["postid"].' ';
$res =  $db2->query($query);
$array[$i]["event_attachment"] = $res->fetch_all(MYSQLI_ASSOC);


$returnarray[] = $array[$i];
}
return $returnarray;
//////
/*
$query = 'SELECT event_id FROM event_posts WHERE  post_id="'.$postid.'" LIMIT 1';
$res =  $db2->query($query);
$obj = $db2->fetch_object($res);
$event_id = $obj->event_id;

$r = $db2->query('select count(id) as totalvote  FROM post_userbox WHERE user_id="'.$userid.'" AND post_id="'.$postid.'"  ');
$result = $db2->fetch_object($r);
$array["current_user_response"] = $result->totalvote;

$array["post_id"] = $postid;

$query = 'SELECT id as event_id,admin_id as user_id,address,location,event_name,start_date,start_time,end_date,end_time,status FROM events WHERE  id="'.$event_id.'" LIMIT 1';
$res =  $db2->query($query);
$array["event_detail"] = $db2->fetch_object($res);

$query = 'SELECT * FROM posts_attachments WHERE  type="'.'image'.'"  AND post_id = '.$postid.' ';
$res =  $db2->query($query);
$array["event_attachment"] = $res->fetch_all(MYSQLI_ASSOC);

return  $array;               */ 

}

public function loadSearchResultUser($userid,$pagenumber,$pagerecordcount,$string)
{
global $db2, $C;
$pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);
$pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);

$db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
$query = 'SELECT  `id`,`username`,`email`,`phone_no`,`fullname`,`avatar`,`about_me`,`tags`,`gender`,`birthdate`,`position` FROM `users` WHERE  `active` = 1 AND username LIKE "%'.$string.'%" OR fullname LIKE "%'.$string.'%" OR tags LIKE "%'.$string.'%"  ORDER BY username ASC  LIMIT '.$pagenumber.','.$pagerecordcount;                               

$res =  $db2->query($query);
return $res->fetch_all(MYSQLI_ASSOC);
}
public function loadSearchResultGroup($userid,$pagenumber,$pagerecordcount,$string)
{
global $db2, $C;
$pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);
$pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);

$db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
$query = 'SELECT * FROM groups WHERE groupname LIKE "%'.$string.'%" OR title LIKE "%'.$string.'%"   ORDER BY groupname ASC  LIMIT '.$pagenumber.','.$pagerecordcount;                               

$res =  $db2->query($query);
return $res->fetch_all(MYSQLI_ASSOC);
}
function deleteSearchKey($userid, $searchid)
{
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$db2->query('DELETE FROM searches WHERE  user_id='.$userid.' AND id='.$searchid);   
}
function fetchSavedSearch($userid)
{
global $db2, $C;
$db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
$query = 'SELECT id,search_key,search_string,search_url,added_date FROM searches where user_id='.$userid.' ORDER BY ID DESC';   
$res =  $db2->query($query);
return $res->fetch_all(MYSQLI_ASSOC);
}
function saveSearchKey($userid,$searchkey,$searchtype)
{//time()
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$db2->query('INSERT INTO searches SET  
user_id="'.$userid.'", 
search_key="'.md5('/tab:'.$searchtype.'/s:'.$searchkey).'",
search_string="'.$searchkey.'",
search_url="/tab:'.$searchtype.'/s:'.$searchkey.'",
added_date="'.time().'"
'); 

$search_id  = (int) $db2->insert_id();
return $search_id;
}

public function checkSearckey($userid,$searchkey,$searchtype)
{
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$sql = 'SELECT id FROM searches WHERE  user_id='.$userid.' and search_url="/tab:'.$searchtype.'/s:'.$searchkey.'"';
$res = $db2->query($sql);
if($db2->num_rows($res) > 0)
{
$obj = $db2->fetch_object($res);
return intval($obj->id);
}
return 0;

}       

public function loadSearchResult($userid,$pagenumber,$pagerecordcount,$searchkey)
{
global $db2, $C;
$pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);

$pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);


$db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);

if(empty($user_id))
{

/*$query = 'SELECT 
b.id AS pid,
p.id AS postid,
p.user_id AS postuserid,
u.username AS postusername,
u.avatar AS postuserimage,
if( (posttype = 2), p.title,  SUBSTRING(p.message, 1, 75)) AS title,
p.posttype,
if( (u.cover is null), "", u.cover) AS coverimage,
p.message AS message,
p.likes,
p.mentioned,
GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachments,
p.attached,
"" as attachment,
p.posttags,
p.comments,
p.reshares,
p.date,
p.date_lastedit,
p.date_lastcomment,
p.group_name,
p.parent_id,
p.status,
p.post_level,
p.location,
p.thumb,
"" as `category`,
"public" AS `type`,
if( (pv.cnt is null), 0, pv.cnt) AS ViewCount
FROM post_userbox b
LEFT JOIN posts p ON p.id=b.post_id
LEFT JOIN users u ON u.id=p.user_id
LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
LEFT OUTER JOIN post_views_list pv ON pv.post_id = p.id
WHERE
p.id in (Select id from posts where message LIKE "%'.$searchkey.'%" ORDER BY p.date_lastcomment DESC  LIMIT '.$pagenumber.','.$pagerecordcount.')
Group By p.id';   */

$query = 'SELECT 
b.id AS pid,
p.id AS postid,
p.user_id AS postuserid,
u.username AS postusername,
u.avatar AS postuserimage,
if( (u.cover is null), "", u.cover) AS coverimage,
if( (posttype = 2), p.title,  SUBSTRING(p.message, 1, 75)) AS title,
p.posttype,
p.message AS message,
p.likes,
p.mentioned,
GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachments,
p.attached,
"" as attachment,
p.posttags,
p.comments,
p.reshares,
p.date,
p.date_lastedit,
p.date_lastcomment,
p.group_name,
p.parent_id,
p.status,
p.post_level,
p.location,
p.thumb,
"" as `category`,
"public" AS `type`,
if( (pv.cnt is null), 0, pv.cnt) AS ViewCount
FROM post_userbox b
LEFT JOIN posts p ON p.id=b.post_id
LEFT JOIN users u ON u.id=p.user_id
LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
LEFT OUTER JOIN post_views_list pv ON pv.post_id = p.id  
INNER JOIN 
(Select id from posts where message LIKE  "%'.$searchkey.'%" OR MATCH(message) AGAINST( "%'.$searchkey.'%"  IN BOOLEAN MODE) 
ORDER BY date_lastcomment DESC LIMIT '.$pagenumber.','.$pagerecordcount.' 
) as p2
on p.id = p2.id
Group By p.id ORDER BY date_lastcomment DESC ';                               
}
// echo $query;//  AND p.user_id<>0 AND group_id NOT IN('.not_in_groups().')  AND (group_id>0 OR id NOT IN('.protected_users().'
// die();
$res =  $db2->query($query);
$array = $res->fetch_all(MYSQLI_ASSOC);
$returnarray = array();

foreach ($array as $i =>$array_expression)
{
$array[$i]["likes"] = $this->getLikes($array[$i]["postid"]);
$array[$i]["attachement"] = $this->getAttachements($array[$i]["postid"]);
$array[$i]["comments"] = $this->getCommentsCount($array[$i]["postid"]);


if(!empty($user_id))
{
$query = 'SELECT id,isliked FROM post_likes WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
$res =  $db2->query($query);
$obj = $db2->fetch_object($res);

//echo $obj->id."-"; die;

if(empty($obj->id)){
$array[$i]["isliked"] = "0";  
}

else {
$array[$i]["isliked"] = "1";  
}



$query1 = 'SELECT id,isbuzzed FROM post_reshares WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
$res1 =  $db2->query($query1);
$obj1 = $db2->fetch_object($res1);


if(empty($obj1->id)){
$array[$i]["isbuzzed"] = "0";  
}

else {
$array[$i]["isbuzzed"] = "1";  
}   


$query3 = 'SELECT id,isfollwed  FROM users_followed WHERE  who="'.$user_id.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
$res3 =  $db2->query($query3);
$obj3 = $db2->fetch_object($res3);

if(empty($obj3->id))
{
$array[$i]["isfollwed"] = "0";
}
else
{
$array[$i]["isfollwed"] = "1";
}
}
$array[$i]["likes"]      =$this->get_like_count($array[$i]["postid"]);
$array[$i]["reshares"]       =$this->new_reshare_count($postid);

$returnarray[] = $array[$i];
}

return $returnarray;
}
//public function loadLocationPosts($state,$district,$pagenumber,$pagerecordcount)
//
public function loadLocationPosts($location, $location_district, $location_capital, $location_state, $location_country)
{
global $db2, $C;
$pagenumber = $pagenumber -1;
$pagenumber = ((int)$pagenumber *  (int)$pagerecordcount)  ;
$pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);

$db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);

/*$query = 'SELECT  b.id AS pid, p.id AS postid, p.user_id AS postuserid,  u.username AS postusername, u.avatar AS postuserimage,
if( (posttype = 2), p.title,  SUBSTRING(p.message, 1, 75)) AS title, p.posttype,  if( (u.cover is null), "", u.cover) AS coverimage,  if( (posttype = 3), pa.content,  p.message) AS message,  p.likes, p.mentioned,
GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
p.posttags,  p.comments,  p.reshares, p.date, p.date_lastedit, p.date_lastcomment,  p.group_name, p.parent_id, p.status, p.post_level,
p.location, p.thumb,  "" as `category`, "public" AS `type`, if( (pv.cnt is null), 0, pv.cnt) AS ViewCount,
(Select count(id) from  posts_social_share where post_id =p.id ) AS sharecount,
(Select count(id) from  profile_share where whom =p.user_id ) AS profilesharecount,
if( ((Select count(id) from  posts_social_share where post_id =p.id and user_id=b.user_id) > 0), true,  false) AS isshared
FROM post_userbox b
LEFT JOIN posts p ON p.id=b.post_id
LEFT JOIN users u ON u.id=p.user_id
LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
LEFT OUTER JOIN post_views_list pv ON pv.post_id = p.id
WHERE
b.user_id  in (Select user_id from street_suggestion where state="'.$state.'" or district="'.$district.'")  AND p.post_level = 0  AND p.id is not null and (INSTR (pa.data, "<div") < 1 or INSTR (pa.data, "<div") is null)
Group By p.id ORDER BY p.date_lastcomment DESC LIMIT '.$pagenumber.','.$pagerecordcount;*/

$query = 'SELECT  b.id AS pid, p.id AS postid, p.user_id AS postuserid,  u.username AS postusername, u.avatar AS postuserimage, if( (u.cover is null), "", u.cover) AS coverimage,
if( (posttype = 2), p.title,  SUBSTRING(p.message, 1, 75)) AS title, p.posttype,  if( (posttype = 3), pa.content,  p.message) AS message,  p.likes, p.mentioned,
GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
p.posttags,  p.comments,  p.reshares, p.date, p.date_lastedit, p.date_lastcomment,  p.group_name, p.parent_id, p.status, p.post_level,
p.location, p.thumb,  "" as `category`, "public" AS `type`, if( (pv.cnt is null), 0, pv.cnt) AS ViewCount,
(Select count(id) from  posts_social_share where post_id =p.id ) AS sharecount,
(Select count(id) from  profile_share where whom =p.user_id ) AS profilesharecount,
if( ((Select count(id) from  posts_social_share where post_id =p.id and user_id=b.user_id) > 0), true,  false) AS isshared
FROM post_userbox b
LEFT JOIN posts p ON p.id=b.post_id
LEFT JOIN users u ON u.id=p.user_id
LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
LEFT OUTER JOIN post_views_list pv ON pv.post_id = p.id
WHERE
b.user_id  in (SELECT user_id from sb_reporter_coverage_location where location_id in 
(
Select id from sb_location_master where
location="'.$location.'" 
or
location_district="'.$location_district.'"
or
location_capital="'.$location_capital.'" 
or
location_state="'.$location_state.'"
or
location_country="'.$location_country.'"

)
)  AND p.post_level = 0  AND p.id is not null and (INSTR (pa.data, "<div") < 1 or INSTR (pa.data, "<div") is null)
Group By p.id ORDER BY p.date_lastcomment DESC LIMIT '.$pagenumber.','.$pagerecordcount;

// echo $query; //$location, $location_district, $location_capital, $location_state, $location_country
//die();

$res =  $db2->query($query);
$array = $res->fetch_all(MYSQLI_ASSOC);
$returnarray = array();

foreach ($array as $i =>$array_expression)
{
$array[$i]["likes"] = $this->getLikes($array[$i]["postid"]);
$array[$i]["comments"] = $this->getCommentsCount($array[$i]["postid"]);
$array[$i]["likes"]      =$this->get_like_count($array[$i]["postid"]);
$array[$i]["reshares"]       =$this->new_reshare_count($array[$i]["postid"]);

$returnarray[] = $array[$i];
}

return $returnarray;


}  
public function splitAttachementsVideo($data)
{
$file_originalSTART = strpos($data, 's:20:"', 0)+6;
$file_originalEND  = strpos($data, ';', $file_originalSTART)-1;
$file_original = substr($data,$file_originalSTART,$file_originalEND-$file_originalSTART);

return array("file_original"=>$file_original);
}

public function editsubmitVideoStoryPost($user_id,$title,$videos,$postid, $ff='', $message='',$video_url,$video_id) {

global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);

$source_dir = $C->SITE_URL.'storage/tmp/';
$destination_dir = $C->SITE_URL.'storage/tmp/';

$post_level  = 1;
if ((int)$parentid==0) {
$post_level = 0;
} 

/* $db2->query('Insert into posts (user_id,message,date,date_lastcomment,ip_addr,parent_id,post_level,attached,posttype) VALUES ('.$user_id.',"'.mysqli_real_escape_string($link,$postdata).'","'.time().'","'.time().'","'.ip2long($_SERVER['REMOTE_ADDR']).'",'.$parentid.','.$post_level.','.count($videos).',3)'); */

$db2->query('Update posts set message = "'.mysqli_real_escape_string($link,$message).'", title="'.mysqli_real_escape_string($link,$title).'"  where id='.$postid);

//print_r($videos); die;
$p = new newpost();
if(isset($videos) ){
if(!empty($ff['videothumbnail'])){
$videothumbnail = $ff['videothumbnail'];                            
$db2->query('Update posts set thumb = "'.$videothumbnail.'"  where id='.$postid);
}
if(!empty($ff['ff'])){                    
$db2->query('Update posts_attachments set data=\''.$db2->escape(serialize($ff)).'\',video_url="'.$video_url.'",video_id='.$video_id.' where post_id='.$postid);

$newimg = $this->splitAttachementsVideo(serialize($ff));

foreach ($newimg as $tmpimg) {
rename($C->STORAGE_TMP_DIR.$tmpimg,$C->STORAGE_DIR.'attachments/1/'.$tmpimg);
}  
} else {
foreach($videos as $file){
$videocaption   = $file["caption"];
$videoid = $file["id"];
$db2->query('Update posts_attachments set content = "'.$videocaption.'",video_url="'.$video_url.'",video_id='.$video_id.'  where id='.$videoid);                     
}
}
}
return $postid;
}

public function editsubmitVideoStoryPost_web($user_id,$title,$videos,$postid, $ff,$thubnail,$videocaption,$video_url) {

global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);

$source_dir = $C->SITE_URL.'storage/tmp/';
$destination_dir = $C->SITE_URL.'storage/tmp/';

$post_level  = 1;
if ((int)$parentid==0) {
$post_level = 0;
} 

/*  $db2->query('Insert into posts (user_id,message,date,date_lastcomment,ip_addr,parent_id,post_level,attached,posttype) VALUES ('.$user_id.',"'.mysqli_real_escape_string($link,$postdata).'","'.time().'","'.time().'","'.ip2long($_SERVER['REMOTE_ADDR']).'",'.$parentid.','.$post_level.','.count($videos).',3)'); */

$message="";
$db2->query('Update posts set message = "'.mysqli_real_escape_string($link,$videocaption).'", title="'.mysqli_real_escape_string($link,$title).'"  where id='.$postid);

//print_r($videos); die;
$p = new newpost();


$db2->query('Update posts_attachments set content="'.$videocaption.'"  where post_id='.$postid);

if(isset($video_url) ){
if(!empty($thubnail)){
$videothumbnail = $thubnail;                            
$db2->query('Update posts set thumb = "'.$videothumbnail.'"  where id='.$postid);



$db2->query('Update posts_attachments set video_url="'.$video_url.'"  where post_id='.$postid);


$newimg = $this->splitAttachementsVideo(serialize($ff));



}
if(!empty($ff)){      
/*  
$db2->query('Update posts_attachments set data=\''.$db2->escape(serialize($ff)).'\'  where post_id='.$postid);
$newimg = $this->splitAttachementsVideo(serialize($ff));
foreach ($newimg as $tmpimg) {
rename($C->STORAGE_TMP_DIR.$tmpimg,$C->STORAGE_DIR.'attachments/1/'.$tmpimg);
}  */
} 
else if($video_url!=""){

$db2->query('Update posts_attachments set video_url = "'.$video_url.'"  where post_id='.$postid); 
}
else {
foreach($videos as $file){
$videocaption   = $file["caption"];
$videoid = $file["id"];
$db2->query('Update posts_attachments set content = "'.$videocaption.'"  where id='.$videoid);                     
}
}
}
return $postid;
}


public function submitVideoStoryPost($user_id, $title, $videos, $parentid, $ff='', $message='',$video_url,$video_id) {     




global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);

$post_level  = 1;
if ((int)$parentid==0) {
$post_level = 0;
} 

$db2->query('Insert into posts (user_id,message,title,date,date_lastcomment,ip_addr,parent_id,post_level,attached,posttype) VALUES ('.$user_id.',"'.mysqli_real_escape_string($link,$message).'","'.mysqli_real_escape_string($link,$title).'","'.time().'","'.time().'","'.ip2long($_SERVER['REMOTE_ADDR']).'",'.$parentid.','.$post_level.','.count($videos).',3)');


$post_id    = (int) $db2->insert_id();
if ((int)$parentid>0) {
$series = 'a:2:{i:0;s:5:"'.$parentid.'";i:1;i:'.$post_id.';}';
$db2->query('INSERT INTO `post_replay`(`parent_id`, `alternate_parent_id`, `replay_id`, `action_type`, `series`) VALUES ('.$parentid.','.$parentid.','.$post_id.',"buzz","'.$series.'")');
} 
$db2->query('Insert into posts_comments_watch (user_id,post_id) VALUES ('.$user_id.','.$post_id.')');

$followers = $this->getAllFollowersUserID($user_id);

$db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$user_id.','.$post_id.')');

foreach ($followers as $i =>$array_expression)
{
$db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$followers[$i]["who"].','.$post_id.')');
}

$p = new newpost();
if( isset($videos) ){ 

if(!empty($videos)){
$videothumbnail = $videos[0]['thumbnail'];
// $videocaption   = $file["caption"];
$db2->query('INSERT INTO `posts_attachments`(`post_id`,`video_url`,`video_id`, `type`,`content`) VALUES ('.$post_id.',"'.$video_url.'","'.$video_id.'",\'file\',\''.$videocaption.'\')');


$newimg = $this->splitAttachementsVideo(serialize($videos));

//$source_dir_thumb = $C->SITE_URL.'storage/attachments/1/'.$videothumbnail;

$db2->query('Update posts set thumb = "'.$videothumbnail.'"  where id='.$post_id);




if (copy($C->STORAGE_TMP_DIR.$videothumbnail,$C->STORAGE_DIR.'attachments/1/'.$videothumbnail)) {
   // echo "1";
} else {
  //  echo "1";
}
 
/*rename($C->STORAGE_TMP_DIR.$videothumbnail,$C->STORAGE_DIR.'attachments/1/'.$videothumbnail);

foreach ($newimg as $tmpimg) {
rename($C->STORAGE_TMP_DIR.$tmpimg,$C->STORAGE_DIR.'attachments/1/'.$tmpimg);
}*/



} else {

foreach($videos as $file){

//   echo $C->STORAGE_TMP_DIR; die('----------');



if( $ff = $p->attach_file($C->STORAGE_TMP_DIR.$file["video"],$file["video"], "file") ) { 

$videothumbnail = $file["thumbnail"];
$videocaption   = $file["caption"];


$db2->query('INSERT INTO `posts_attachments`(`post_id`,`video_url`,`video_id`,`type`, `data`,  `content`) VALUES ('.$post_id.',"'.$video_url.'","'.$video_id.'",\'file\',\''.$db2->escape(serialize($ff)).'\',\''.$videocaption.'\')');



$newimg = $this->splitAttachementsVideo(serialize($ff));

$db2->query('Update posts set thumb = "'.$videothumbnail.'"  where id='.$post_id);

rename($C->STORAGE_TMP_DIR.$file["thumbnail"],$C->STORAGE_DIR.'attachments/1/'.$file["thumbnail"]);

foreach ($newimg as $tmpimg) {
rename($C->STORAGE_TMP_DIR.$tmpimg,$C->STORAGE_DIR.'attachments/1/'.$tmpimg);
}  
} 

else {
$videothumbnail = $file["thumbnail"];
$videocaption   = $file["caption"];


$db2->query('INSERT INTO `posts_attachments`(`post_id`,`video_url`,`video_id`,`type`, `data`,  `content`) VALUES ('.$post_id.',"'.$video_url.'","'.$video_id.'",\'file\',\''.$db2->escape(serialize($ff)).'\',\''.$videocaption.'\')');



$newimg = $this->splitAttachementsVideo(serialize($ff));

$db2->query('Update posts set thumb = "'.$videothumbnail.'"  where id='.$post_id);
}
}
}
unset($images);
}


// notification start
$followers=$db2->query('select who FROM users_followed WHERE whom='.$user_id.'');
//	$vla=$this->db2->fetch_object($followers);
$vla=mysqli_fetch_all($followers);



foreach($vla as $vl){
$rules=$db2->query('select ntf_me_if_u_follow_buzz FROM users_notif_rules WHERE user_id='.$vl[0].'');



$vlt=mysqli_fetch_assoc($rules);



if($vlt['ntf_me_if_u_follow_buzz']==1 || $vlt['ntf_me_if_u_follow_buzz']==2 ){
$notifytype='buzz';
$type='buzz';
$standardnotifytype='ntf_me_if_u_follow_buzz_video';
$network = & $GLOBALS['network'];

/*$newisert =$network->insert_active_profilenotifications1($vl[0],$post_id,$notifytype,$type,$standardnotifytype);
print_r($vlt); die("=======");*/



$sql_user1 = 'SELECT * FROM users WHERE  id="' .$user_id. '"';
$res_user1 = $db2->query($sql_user1);
$obj_user1 = $db2->fetch_object($res_user1);

$data = array();
$data['id'] = $user_id;
$data['postid'] = $post_id;
$data['notification_type'] = 'video';
$data['username'] = $obj_user1->username;
send_push_notification($vl[0], $data);






$ownuserid=$vl[0];
$postid = $post_id;
$notifytype = $notifytype;
$type = $type;
$standrdtype=$standardnotifytype;


//$db2->query(

$date =time();
$db2->query('insert into active_notifications  values ("","","'.$user_id.'","'.$ownuserid.'","'.$postid.'","'.$notifytype.'","'.$type.'","'.$date.'")');

$groupid =0;
$notif_object_type ='post';
$notif_object_id =$user_id;


$db2->query('insert into notifications  (notif_type, to_user_id, in_group_id, from_user_id,notif_object_type,notif_object_id,date) values  ("'.$standrdtype.'","'. $ownuserid .'","'.$groupid.'","'.$user_id.'","'.$notif_object_type.'","'.$postid.'","'.$date.'")');





$notifytype='notifications';
$userdash =  $db2->fetch_field('SELECT newposts  FROM users_dashboard_tabs WHERE user_id="'.$ownuserid.'" AND tab="'.$notifytype.'"  ');

//	die('hhhhhhh');


if(!empty($userdash)){
$newpost = $userdash+1;
$db2->query('update users_dashboard_tabs set 	newposts="'.$newpost.'" WHERE user_id="'.$ownuserid.'" ');


}else{
$tab ="notifications";
$state = 1;
$db2->query('insert into users_dashboard_tabs  values ("'.$ownuserid.'","'. $tab .'","'.$state.'","'.$state.'")');
}









}



}







//notification end





return $post_id;
}






public function submitVideoStoryPost_web($user_id, $title, $videos, $parentid, $ff='', $thumbnail,$videocaption,$video_url) {          
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);

$post_level  = 1;
if ((int)$parentid==0) {
$post_level = 0;
} 

$message="";

$db2->query('Insert into posts (user_id,message,title,date,date_lastcomment,ip_addr,parent_id,post_level,attached,posttype) VALUES ('.$user_id.',"'.mysqli_real_escape_string($link,$videocaption).'","'.mysqli_real_escape_string($link,$title).'","'.time().'","'.time().'","'.ip2long($_SERVER['REMOTE_ADDR']).'",'.$parentid.','.$post_level.','.count($videos).',3)');


$post_id    = (int) $db2->insert_id();


// notification start
$followers=$db2->query('select who FROM users_followed WHERE whom='.$user_id.'');
//	$vla=$this->db2->fetch_object($followers);
$vla=mysqli_fetch_all($followers);



foreach($vla as $vl){

$rules=$db2->query('select ntf_me_if_u_follow_buzz FROM users_notif_rules WHERE user_id='.$vl[0].'');



$vlt=mysqli_fetch_assoc($rules);



if($vlt['ntf_me_if_u_follow_buzz']==1 || $vlt['ntf_me_if_u_follow_buzz']==2 ){
$notifytype='buzz';
$type='buzz';
$standardnotifytype='ntf_me_if_u_follow_buzz_video';
$network = & $GLOBALS['network'];

/*$newisert =$network->insert_active_profilenotifications1($vl[0],$post_id,$notifytype,$type,$standardnotifytype);
print_r($vlt); die("=======");*/


$sql_user1 = 'SELECT * FROM users WHERE  id="' .$user_id. '"';
$res_user1 = $db2->query($sql_user1);
$obj_user1 = $db2->fetch_object($res_user1);

$data = array();
$data['id'] = $user_id;
$data['postid'] = $post_id;
$data['notification_type'] = 'video';
$data['username'] = $obj_user1->username;
send_push_notification($vl[0], $data);



$ownuserid=$vl[0];
$postid = $post_id;
$notifytype = $notifytype;
$type = $type;
$standrdtype=$standardnotifytype;


//$db2->query(

$date =time();
$db2->query('insert into active_notifications  values ("","","'.$user_id.'","'.$ownuserid.'","'.$postid.'","'.$notifytype.'","'.$type.'","'.$date.'")');

$groupid =0;
$notif_object_type ='post';
$notif_object_id =$user_id;


$db2->query('insert into notifications  (notif_type, to_user_id, in_group_id, from_user_id,notif_object_type,notif_object_id,date) values  ("'.$standrdtype.'","'. $ownuserid .'","'.$groupid.'","'.$user_id.'","'.$notif_object_type.'","'.$postid.'","'.$date.'")');





$notifytype='notifications';
$userdash =  $db2->fetch_field('SELECT newposts  FROM users_dashboard_tabs WHERE user_id="'.$ownuserid.'" AND tab="'.$notifytype.'"  ');

//	die('hhhhhhh');


if(!empty($userdash)){
$newpost = $userdash+1;
$db2->query('update users_dashboard_tabs set 	newposts="'.$newpost.'" WHERE user_id="'.$ownuserid.'" ');


}else{
$tab ="notifications";
$state = 1;
$db2->query('insert into users_dashboard_tabs  values ("'.$ownuserid.'","'. $tab .'","'.$state.'","'.$state.'")');
}









}



}








//notification end

if ((int)$parentid>0) {
$series = 'a:2:{i:0;s:5:"'.$parentid.'";i:1;i:'.$post_id.';}';
$db2->query('INSERT INTO `post_replay`(`parent_id`, `alternate_parent_id`, `replay_id`, `action_type`, `series`) VALUES ('.$parentid.','.$parentid.','.$post_id.',"buzz","'.$series.'")');
} 
$db2->query('Insert into posts_comments_watch (user_id,post_id) VALUES ('.$user_id.','.$post_id.')');

$followers = $this->getAllFollowersUserID($user_id);

$db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$user_id.','.$post_id.')');

foreach ($followers as $i =>$array_expression)
{
$db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$followers[$i]["who"].','.$post_id.')');
}

$p = new newpost();
if( isset($video_url) ){ 

// if(!empty($ff)){

$videothumbnail = $thumbnail;
$videocaption   = $videocaption;

$db2->query('INSERT INTO `posts_attachments`(`video_url`,`post_id`, `type`,  `content`) VALUES ("'.$video_url.'",'.$post_id.',"file","'.$videocaption.'")');

//echo 'INSERT INTO `posts_attachments`(`video_url`,`post_id`, `type`,  `content`) VALUES ("'.$video_url.'",'.$post_id.',"file","'.$videocaption.'")';
// die('ssssssssssssss');


$newimg = $this->splitAttachementsVideo(serialize($ff));
foreach ($newimg as $tmpimg) {
rename($C->STORAGE_TMP_DIR.$tmpimg,$C->STORAGE_DIR.'attachments/1/'.$tmpimg);
}




$db2->query('Update posts set thumb = "'.$videothumbnail.'"  where id='.$post_id);

rename($C->STORAGE_TMP_DIR.$file["thumbnail"],$C->STORAGE_DIR.'attachments/1/'.$file["thumbnail"]);


// } /*else {
/*      foreach($videos as $file){
if( $ff = $p->attach_file($C->STORAGE_TMP_DIR.$file["video"],$file["video"], "file") ) { 

$videothumbnail = $file["thumbnail"];
$videocaption   = $file["caption"];
$db2->query('INSERT INTO `posts_attachments`(`post_id`, `type`, `data`,  `content`) VALUES ('.$post_id.',\'file\',\''.$db2->escape(serialize($ff)).'\',\''.$videocaption.'\')');
$newimg = $this->splitAttachementsVideo(serialize($ff));

$db2->query('Update posts set thumb = "'.$videothumbnail.'"  where id='.$post_id);

rename($C->STORAGE_TMP_DIR.$file["thumbnail"],$C->STORAGE_DIR.'attachments/1/'.$file["thumbnail"]);

foreach ($newimg as $tmpimg) {
rename($C->STORAGE_TMP_DIR.$tmpimg,$C->STORAGE_DIR.'attachments/1/'.$tmpimg);
}  
} 
}*/
//  }
// unset($images);*/
}
else {
$videothumbnail = $thumbnail;
$videocaption   = $videocaption;
$videodata="";
$db2->query('INSERT INTO `posts_attachments`(`video_url`,`post_id`, `type`, `data`,  `content`) VALUES ('.$video_url.','.$post_id.',\'file\',\''.$videodata.'\',\''.$videocaption.'\')');
}
return $post_id;
}

public function get_like_count($postid)
{
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

$r      = $db2->query('select count(id) as likecount FROM post_likes WHERE  post_id="' . $postid . '"', FALSE);
$result = $db2->fetch_object($r);
return $result->likecount;

}

public function new_reshare_count($postid)
{
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

$r      = $db2->query('select count(id) as sharecount  FROM post_reshares WHERE  post_id="' . $postid . '"', FALSE);
$result = $db2->fetch_object($r);
return $result->sharecount;

}



public function setPostComments($userid,$postid,$message)
{
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
//$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);
$ip_address = ip2long($_SERVER['REMOTE_ADDR']);
$res = $db2->query('Insert into posts_comments (user_id,post_id, message,date,ip_addr) VALUES ('.$userid.','.$postid.',"'.$message.'","'.time().'","'.$ip_address.'")');

if ((int)$db2->insert_id() > 0)
{
return (int)$db2->insert_id();
}
return false;
}


public function postComments($postid,$pagenumber,$pagerecordcount)
{

global $db2, $C;
$pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);
$pagenumber = $pagenumber -1;
$pagenumber = ((int)$pagenumber *  (int)$pagerecordcount)  ;
//$pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);

$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

$res = $db2->query('Select p.id as commentid,user_id, message,p.date,u.username,u.avatar,u.fullname from posts_comments p LEFT JOIN users u ON u.id=p.user_id where post_id  =  '.$postid.' order by p.date desc LIMIT '.$pagenumber.','.$pagerecordcount);

return $res->fetch_all(MYSQLI_ASSOC);

}
public function getPostComments($postid)
{
global $db2, $C;
$arr = array();
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
//$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);
$ip_address = ip2long($_SERVER['REMOTE_ADDR']);
$res = $db2->query('Select p.id as commentid, user_id, p.message, p.date, u.username, u.avatar, u.fullname from posts_comments p LEFT JOIN users u ON u.id=p.user_id where post_id  =  '.$postid.' order by p.date desc');

return $res->fetch_all(MYSQLI_ASSOC);

}
public function loadPostDetails1($user_id,$postid,$pagenumber,$pagerecordcount)
{
global $db2, $C;
$pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);

$pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);


$db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
//                        SUBSTRING(p.message, 1, 75) as title, 
//if( (posttype = 3), pa.content,  p.message) AS message,
$query = 'SELECT 
b.id AS pid,
p.id AS postid,
p.user_id AS postuserid,
u.username AS postusername,
u.avatar AS postuserimage,
if( (u.cover is null), "", u.cover) AS coverimage,
if( (p.title is null), "", p.title) AS title, 
p.posttype,  
p.message AS message, 
p.likes,
p.mentioned,
GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
pa.id as attachements_id,
pa.video_url as video_url,
pa.video_id as video_id,
p.posttags,
p.comments,
p.reshares,
p.date,
p.date_lastedit,
p.date_lastcomment,
p.group_name,
p.parent_id,
p.status,
p.post_level,
p.location,
p.thumb,
"" as `category`,
"public" AS `type`,
if( (pd.views is null), 0, pd.views) AS ViewCount,
if( (pd.shares is null), 0, pd.shares) AS sharecount,
if( (pd.likes is null), 0, pd.likes) AS likes,
if( (pd.comments is null), 0, pd.comments) AS comments,
if( (pd.reshares is null), 0, pd.reshares) AS reshares
FROM post_userbox b
LEFT JOIN posts p ON p.id=b.post_id
LEFT JOIN users u ON u.id=p.user_id
LEFT JOIN posts_details pd ON pd.post_id = p.id
LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
LEFT OUTER JOIN post_views_list pv ON pv.post_id = p.id
WHERE
p.id='.$postid.' AND (INSTR (pa.data, "<div") < 1 or INSTR (pa.data, "<div") is null)
Group By p.id
ORDER BY p.date_lastcomment DESC
LIMIT '.$pagenumber.','.$pagerecordcount;


$res =  $db2->query($query);
$array = $res->fetch_all(MYSQLI_ASSOC);
$returnarray = array();
$dom = new DomDocument();

foreach ($array as $i =>$array_expression)
{
/* if($array[$i]["posttype"] != 2) {
$title = strip_tags($array[$i]["message"]); 
$array[$i]["title"] = mb_substr($title, 0, 144,'UTF-8');
}
*/
$array[$i]["title"] = strip_tags($array[$i]["title"]); 

if ($array[$i]["posttype"] == 1)
{
$array[$i]["images"] = $this->getAttachements($array[$i]["postid"]);
}
$array[$i]["commentdetails"] = $this->getPostComments($array[$i]["postid"]);


$query = 'SELECT id FROM post_likes WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
$res =  $db2->query($query);
$obj = $db2->fetch_object($res);


if(empty($obj->id)){
$array[$i]["isliked"] = "0";  
} else {
$array[$i]["isliked"] = "1";  
}

$query1 = 'SELECT id FROM post_reshares WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
$res1 =  $db2->query($query1);
$obj1 = $db2->fetch_object($res1);

if(empty($obj1->id)){
$array[$i]["isbuzzed"] = "0";  
} else {
$array[$i]["isbuzzed"] = "1";  
}

$query3 = 'SELECT id FROM users_followed WHERE  who="'.$user_id.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
$res3 =  $db2->query($query3);
$obj3 = $db2->fetch_object($res3);

if(empty($obj3->id)){
$array[$i]["isfollwed"] = "0";  
} else {
$array[$i]["isfollwed"] = "1";  
}

$strHTML = "";             
if ($array[$i]["posttype"] == 6)//need to remove '\n'
{
//$strHTML = "<HTML><H3>".$array[$i]["title"]."</H3><span>".$array[$i]["message"]."<span>".$this->getAttachementsHTML($array[$i]["postid"])."</HTML>";
$strHTML = "<HTML><span>".nl2br($array[$i]["message"])."</span>".$this->getAttachementsHTML($array[$i]["postid"])."</HTML>";
$array[$i]["message"] = $strHTML;
}

$returnarray[] = $array[$i];
}

return $returnarray;


}
public function loadPostDetails($postid,$pagenumber,$pagerecordcount)
{
global $db2, $C;
$pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);

$pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);

$db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
$query = 'SELECT 
p.id AS postid,
p.user_id AS postuserid,
u.username AS postusername,
u.avatar AS postuserimage,
if( (u.cover is null), "", u.cover) AS coverimage,
if( (posttype = 2), p.title,  SUBSTRING(p.message, 1, 75)) AS title,
p.posttype,
p.likes,
p.mentioned,
GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachments,
pa.video_url as video_url,
pa.video_id as video_id,
p.attached,
"" as attachment,
p.posttags,
p.comments,
p.reshares,
p.date,
p.date_lastedit,
p.date_lastcomment,
p.group_name,
p.parent_id,
p.status,
p.post_level,
p.location,
p.thumb,
"" as `category`,
"public" AS `type`,
if( (pv.cnt is null), 0, pv.cnt) AS ViewCount
FROM post_userbox b
LEFT JOIN posts p ON p.id=b.post_id
LEFT JOIN users u ON u.id=p.user_id
LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
LEFT OUTER JOIN post_views_list pv ON pv.post_id = p.id
WHERE
(p.id='.$postid.' OR p.parent_id='.$postid.')  AND p.id is not null and (INSTR (pa.data, "<div") < 1 or INSTR (pa.data, "<div") is null)
Group By p.id
ORDER BY p.date_lastcomment DESC
LIMIT '.$pagenumber.','.$pagerecordcount;
//echo $query;

$res =  $db2->query($query);
$array = $res->fetch_all(MYSQLI_ASSOC);
$returnarray = array();
$dom = new DomDocument();

foreach ($array as $i =>$array_expression)
{
$array[$i]["likes"] = $this->getLikes($array[$i]["postid"]);
$array[$i]["attachement"] = $this->getAttachements($array[$i]["postid"]);
$array[$i]["comments"] = $this->getCommentsCount($array[$i]["postid"]);

$query = 'SELECT * FROM post_likes WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
$res =  $db2->query($query);
$obj = $db2->fetch_object($res);

//echo $obj->id."-"; die;

if(empty($obj->id)){
$array[$i]["isliked"] = "0";  
}

else {
$array[$i]["isliked"] = "1";  
}



$query1 = 'SELECT * FROM post_reshares WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
$res1 =  $db2->query($query1);
$obj1 = $db2->fetch_object($res1);


if(empty($obj1->id)){
$array[$i]["isbuzzed"] = "0";  
}

else {
$array[$i]["isbuzzed"] = "1";  
}


$query3 = 'SELECT * FROM users_followed WHERE  who="'.$user_id.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
$res3 =  $db2->query($query3);
$obj3 = $db2->fetch_object($res3);

if(empty($obj3->id)){
$array[$i]["isfollwed"] = "0";  
}

else {
$array[$i]["isfollwed"] = "1";  
}


$array[$i]["likes"]      =$this->get_like_count($array[$i]["postid"]);

$array[$i]["reshares"]       =$this->new_reshare_count($postid);



$returnarray[] = $array[$i];
}



return $returnarray;


}

public function submitEditPhotoStoryPost($messagetitle,$images,$postid)
{
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

$sql ='UPDATE posts SET
title="'.mysqli_real_escape_string($link,$messagetitle).'",
date_lastedit="'.time().'"
Where ID='.$postid; 

//echo $sql;
//$db2->query($sql);


if( isset($images) ){ 
foreach($images as $img){
echo ('UPDATE posts_attachments SET  `content` =  "'.$img["caption"].'" where data like "%'.$img["image"].'%"');
}
}

return $postid;

}
public function submitInsrtInToPost($user_id,$postdata,$images,$parentid)
{

global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);

//$upload_dir = $_SERVER['DOCUMENT_ROOT'].'/develop/storage/attachments/1/';
$source_dir = $C->SITE_URL.'storage/tmp/';
$destination_dir = $C->SITE_URL.'storage/tmp/';


$post_level  = 1;
if ((int)$parentid==0)
{
$post_level = 0;
} 

$db2->query('Insert into posts (user_id,message,date,date_lastcomment,ip_addr,parent_id,post_level,attached,posttype) VALUES ('.$user_id.',"'.mysqli_real_escape_string($link,$postdata).'","'.time().'","'.time().'","'.ip2long($_SERVER['REMOTE_ADDR']).'",'.$parentid.','.$post_level.','.count($images).',1)');


$post_id    = (int) $db2->insert_id();
}
public function submitPhotoStoryPost($user_id,$postdata,$images,$parentid) {
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);            
$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);

//$upload_dir = $_SERVER['DOCUMENT_ROOT'].'/develop/storage/attachments/1/';
$post_level  = 1;
if ((int)$parentid==0) {
$post_level = 0;
} 

$db2->query('Insert into posts (user_id,title,date,date_lastcomment,ip_addr,parent_id,post_level,attached,posttype) VALUES ('.$user_id.',"'.mysqli_real_escape_string($link,$postdata).'","'.time().'","'.time().'","'.ip2long($_SERVER['REMOTE_ADDR']).'",'.$parentid.','.$post_level.','.count($images).',1)');

$post_id    = (int) $db2->insert_id();
if ((int)$parentid>0) {
$series = 'a:2:{i:0;s:5:"'.$parentid.'";i:1;i:'.$post_id.';}';
$db2->query('INSERT INTO `post_replay`(`parent_id`, `alternate_parent_id`, `replay_id`, `action_type`, `series`) VALUES ('.$parentid.','.$parentid.','.$post_id.',"buzz","'.$series.'")');
} 
$db2->query('Insert into posts_comments_watch (user_id,post_id) VALUES ('.$user_id.','.$post_id.')');

$followers = $this->getAllFollowersUserID($user_id);

$db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$user_id.','.$post_id.')');            
foreach ($followers as $i =>$array_expression) {
$db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$followers[$i]["who"].','.$post_id.')');
}

$p = new newpost();
if( isset($images) ){ 
foreach($images as $img){
$fullimage = $C->STORAGE_TMP_DIR.$img["image"];
if(strpos($img["image"],$C->SITE_URL) > -1){
$fullimage =  $img["image"];
}
if( $ii = $p->attach_image($fullimage, $img["image"]) ) { 
$imagecaption = $img["caption"];
$db2->query('INSERT INTO `posts_attachments`(`post_id`, `type`, `data`,  `content`) VALUES ('.$post_id.',\'Image\',\''.$db2->escape(serialize($ii)).'\',\''.$imagecaption.'\')');

$newimg = $this->splitAttachements(serialize($ii));


foreach ($newimg as $tmpimg) {
rename($C->STORAGE_TMP_DIR.$tmpimg,$C->STORAGE_DIR.'attachments/1/'.$tmpimg);
}
}
}
unset($images);
}

// notification start
$followers=$db2->query('select who FROM users_followed WHERE whom='.$user_id.'');
//	$vla=$this->db2->fetch_object($followers);
$vla=mysqli_fetch_all($followers);



foreach($vla as $vl){
$rules=$db2->query('select ntf_me_if_u_follow_buzz FROM users_notif_rules WHERE user_id='.$vl[0].'');



$vlt=mysqli_fetch_assoc($rules);



if($vlt['ntf_me_if_u_follow_buzz']==1 || $vlt['ntf_me_if_u_follow_buzz']==2 ){
$notifytype='buzz';
$type='buzz';
$standardnotifytype='ntf_me_if_u_follow_buzz_photo';
$network = & $GLOBALS['network'];

/*$newisert =$network->insert_active_profilenotifications1($vl[0],$post_id,$notifytype,$type,$standardnotifytype);
print_r($vlt); die("=======");*/

$sql_user1 = 'SELECT * FROM users WHERE  id="' .$user_id. '"';
$res_user1 = $db2->query($sql_user1);
$obj_user1 = $db2->fetch_object($res_user1);

$data = array();
$data['id'] = $user_id;
$data['postid'] = $post_id;
$data['notification_type'] = 'photostory';
$data['username'] = $obj_user1->username;
send_push_notification($vl[0], $data);



$ownuserid=$vl[0];
$postid = $post_id;
$notifytype = $notifytype;
$type = $type;
$standrdtype=$standardnotifytype;


//$db2->query(

$date =time();
$db2->query('insert into active_notifications  values ("","","'.$user_id.'","'.$ownuserid.'","'.$postid.'","'.$notifytype.'","'.$type.'","'.$date.'")');

$groupid =0;
$notif_object_type ='post';
$notif_object_id =$user_id;


$db2->query('insert into notifications  (notif_type, to_user_id, in_group_id, from_user_id,notif_object_type,notif_object_id,date) values  ("'.$standrdtype.'","'. $ownuserid .'","'.$groupid.'","'.$user_id.'","'.$notif_object_type.'","'.$postid.'","'.$date.'")');





$notifytype='notifications';
$userdash =  $db2->fetch_field('SELECT newposts  FROM users_dashboard_tabs WHERE user_id="'.$ownuserid.'" AND tab="'.$notifytype.'"  ');

//	die('hhhhhhh');


if(!empty($userdash)){
$newpost = $userdash+1;
$db2->query('update users_dashboard_tabs set 	newposts="'.$newpost.'" WHERE user_id="'.$ownuserid.'" ');


}else{
$tab ="notifications";
$state = 1;
$db2->query('insert into users_dashboard_tabs  values ("'.$ownuserid.'","'. $tab .'","'.$state.'","'.$state.'")');
}

}

} 




return $post_id;

}

////////////img caaption///////


public function submitPhotoWithCaption($user_id,$postdata,$images,$parentid,$ff='')
{
//print_r($ii); die('----------');
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);

//$upload_dir = $_SERVER['DOCUMENT_ROOT'].'/develop/storage/attachments/1/';
$source_dir = $C->SITE_URL.'storage/tmp/';
$destination_dir = $C->SITE_URL.'storage/tmp/';


// $post_level  = 1;
// if ((int)$parentid==0)
// {
//     $post_level = 0;
// } 

// $db2->query('Insert into posts (user_id,message,date,date_lastcomment,ip_addr,parent_id,post_level,attached,posttype) VALUES ('.$user_id.',"'.mysqli_real_escape_string($link,$postdata).'","'.time().'","'.time().'","'.ip2long($_SERVER['REMOTE_ADDR']).'",'.$parentid.','.$post_level.','.count($images).',1)');


$post_id    = $ff['postid'];

if ((int)$parentid>0)
{
$series = 'a:2:{i:0;s:5:"'.$parentid.'";i:1;i:'.$post_id.';}';
$db2->query('INSERT INTO `post_replay`(`parent_id`, `alternate_parent_id`, `replay_id`, `action_type`, `series`) VALUES ('.$parentid.','.$parentid.','.$post_id.',"buzz","'.$series.'")');
} 
$db2->query('Insert into posts_comments_watch (user_id,post_id) VALUES ('.$user_id.','.$post_id.')');

/*    $followers = $this->getAllFollowersUserID($user_id);

$db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$user_id.','.$post_id.')');

foreach ($followers as $i =>$array_expression)
{
$db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$followers[$i]["who"].','.$post_id.')');
}
*/
$p = new newpost();
if( isset($images) ){ 
//echo $C->STORAGE_TMP_DIR.$img["image"];
//echo  $img["image"];
//print_r ($p->attach_image($C->STORAGE_TMP_DIR.$img["image"], $img["image"])); 
if(!empty($ff)){
//echo 2; 
$imagecaption = $ff["caption"];
$ii=$ff['ff'];
$db2->query('INSERT INTO `posts_attachments`(`post_id`, `type`, `data`,  `content`) VALUES ('.$post_id.',\'Image\',\''.$db2->escape(serialize($ii)).'\',\''.$imagecaption.'\')');

$newimg = $this->splitAttachements(serialize($ii));

foreach ($newimg as $tmpimg)
{
rename($C->STORAGE_TMP_DIR.$tmpimg,$C->STORAGE_DIR.'attachments/1/'.$tmpimg);
}
}
// }
unset($images);
}
return $post_id;

}        

public function editPhotoWithCaption($user_id,$postdata,$images,$parentid,$ff='')
{
// print_r($ff['ff']); die('----------');
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);   


$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);

//$upload_dir = $_SERVER['DOCUMENT_ROOT'].'/develop/storage/attachments/1/';
$source_dir = $C->SITE_URL.'storage/tmp/';
$destination_dir = $C->SITE_URL.'storage/tmp/';

$post_id    = $ff['postid'];    
$idss    = $ff['idss'];

$p = new newpost();
if( isset($images) ){ 
//echo $C->STORAGE_TMP_DIR.$img["image"];
//echo  $img["image"];
//print_r ($p->attach_image($C->STORAGE_TMP_DIR.$img["image"], $img["image"])); 
if(!empty($ff)){
//echo 2; 
$imagecaption = $ff["caption"];
$ii=$ff['ff'];


$db2->query('UPDATE `posts_attachments` SET `type`=\'Image\',`data`=\''.$db2->escape(serialize($ii)).'\' WHERE post_id='.$post_id.' AND id='.$idss.'');

$newimg = $this->splitAttachements(serialize($ii));

foreach ($newimg as $tmpimg)
{
rename($C->STORAGE_TMP_DIR.$tmpimg,$C->STORAGE_DIR.'attachments/1/'.$tmpimg);
}
}
// }
unset($images);
}
return $post_id;

}




public function editPhotoStoryPost($user_id,$postdata,$parentid,$post_id,$images)
{
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);

$post_level  = 1;
if ((int)$parentid==0) {
$post_level = 0;
} 


$sql ='UPDATE posts SET
title="'.mysqli_real_escape_string($link,$postdata).'",
ip_addr="'.ip2long($_SERVER['REMOTE_ADDR']).'",
parent_id="'.$parentid.'",
post_level="'.$post_level.'"
Where id='.$post_id;
$db2->query($sql);

foreach($images as $img){
$res = $db2->query('Update posts_attachments SET content = "'.$img['caption'].'" WHERE  id="'.$img['id'].'"');
}
return $post_id;

}

public function updateProfileImage($userid,$avatar)
{

global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
$lastlogin_date = time();

$sql ='UPDATE users SET
avatar="'.$db2->e($avatar).'",
lastlogin_date="'.$lastlogin_date.'", 
lastlogin_ip="'.$lastlogin_ip.'" 
Where ID='.$userid;
//  echo $sql;
$db2->query($sql);
return true;
}
//$avatar_name,str_replace(' ', '-',strtolower($random_name)), str_replace(' ', '-',strtolower($server_url.$random_name))
public function insertTempFileDetails($filename,$generatedfilename,$url)
{

global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

$db2->query('Insert into tempfile (filename,generatedfilename,url) VALUES ("'.$filename.'","'.$generatedfilename.'","'.$url.'")');
}
private function getAllFollowersUserID($userid)
{
global $db2;
$query = 'SELECT who from users_followed where whom='.$userid.' AND who != '.$userid.' group by who' ;
$res =  $db2->query($query);
return $res->fetch_all(MYSQLI_ASSOC);
}
public function submitEditPost($userid,$postdata,$title,$postid)
{
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);            
$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);
//

//$db2->query('Insert into posts (user_id,message,date,date_lastcomment,ip_addr,parent_id,post_level,posttype,title,coverimage) VALUES ('.$user_id.',"'.mysqli_real_escape_string($link,$postdata).'","'.time().'","'.time().'","'.ip2long($_SERVER['REMOTE_ADDR']).'",'.$parentid.','.$post_level.',2,"'.$title.'","'.$coverimage.'")');//$title,$imageur

$sql ='UPDATE posts SET
message="'.mysqli_real_escape_string($link,$postdata).'",
title="'.$title.'",
date_lastedit="'.time().'"
Where ID='.$postid; 
$db2->query($sql);

return $postid;
}
public function submitPost($user_id,$postdata,$parentid,$title,$coverimage)
{
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);

$post_level  = 1;
if ((int)$parentid==0)
{
$post_level = 0;
} 


$db2->query('Insert into posts (user_id,message,date,date_lastcomment,ip_addr,parent_id,post_level,posttype,title,coverimage) VALUES ('.$user_id.',"'.mysqli_real_escape_string($link,$postdata).'","'.time().'","'.time().'","'.ip2long($_SERVER['REMOTE_ADDR']).'",'.$parentid.','.$post_level.',2,"'.$title.'","'.$coverimage.'")');//$title,$imageur


$post_id    = (int) $db2->insert_id();
if ((int)$parentid>0)
{

$series = 'a:2:{i:0;s:5:\"'.$parentid.'\";i:1;i:'.$post_id.';}';


$db2->query('INSERT INTO `post_replay`(`parent_id`, `alternate_parent_id`, `replay_id`, `action_type`, `series`) VALUES ('.$parentid.','.$parentid.','.$post_id.',"buzz","'.$series.'")');
} 
$db2->query('Insert into posts_comments_watch (user_id,post_id) VALUES ('.$user_id.','.$post_id.')');

$followers = $this->getAllFollowersUserID($user_id);
//echo $followers;
$db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$user_id.','.$post_id.')');

foreach ($followers as $i =>$array_expression)
{
$db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$followers[$i]["who"].','.$post_id.')');
}

return $post_id;
}

public function __construct()
{
}
public function checkUserForEdit($username,$userid)
{
global $db2, $C;

$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$sql = '';

if (!empty($username) && !empty($userid))
{
if (is_numeric($username))  
{
$sql = 'SELECT id FROM users WHERE  phone_no="'.$username.'"  AND id != '.$userid.' AND active=1  LIMIT 1';
}
else
{
$sql = 'SELECT id FROM users WHERE (username="'.$username.'" OR email="'.$username.'") AND id != '.$userid.' AND active=1  LIMIT 1';
}
}
if (!empty($sql))
{
$res = $db2->query($sql);
if($db2->num_rows($res) > 0)
{

$obj = $db2->fetch_object($res);
return intval($obj->id);
}
}
return 0;
}
public function checkUser($username,$password='')
{
global $db2, $C;

$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$sql = '';

if (!empty($username) && !empty($password))
{
if (is_numeric($username))
{
$sql = 'SELECT id FROM users WHERE  phone_no="'.$username.'" and password="'.$password.'" AND active=1  LIMIT 1';
}
else
{
$sql = 'SELECT id FROM users WHERE (username="'.$username.'" OR email="'.$username.'") and password="'.$password.'" AND active=1  LIMIT 1';
}
}
else if (!empty($username))
{
if (is_numeric($username))
{
$sql = 'SELECT id FROM users WHERE TRIM(IFNULL(phone_no,\'\')) <> \'\' AND  phone_no="'.$username.'"  AND active=1  LIMIT 1';
}
else
{
$sql = 'SELECT id FROM users WHERE (username="'.$username.'" OR (email="'.$username.'" AND  TRIM(IFNULL(email,\'\')) <> \'\')) LIMIT 1';
}

}
if (!empty($sql))
{
$res = $db2->query($sql);
if($db2->num_rows($res) > 0)
{
$obj = $db2->fetch_object($res);
return intval($obj->id);
}
}
return 0;
}

function getUserDetailsOld($userid)
{
global $db2, $C;

$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$sql = "Select users.id, email, username, phone_no, fullname, language, timezone, gender, birthdate, active, avatar as profile_image, access_token from users INNER JOIN oauth_access_token ON `users`.id = `oauth_access_token`.user_id where users.id=".$userid;

$userdata = array();

$records = $db2->query($sql, FALSE);


if($db2->num_rows($records) > 0)
{
$obj = $db2->fetch_object($records);
return $obj;
}

return $admins;
}
function getUserDetails($userid)
{
global $db2, $C;

$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$sql = "Select 
users.id,
email,
username,
phone_no,
fullname,
language,
timezone,
gender,
birthdate,
active,
avatar as profile_image,
access_token,
100 AS `followers`,
100 AS `following`,
num_favourites as `like`
from users
INNER JOIN oauth_access_token ON `users`.id = `oauth_access_token`.user_id
where users.id=".$userid;
/*Temp Code*/
/*
$sql->execute(); // Execute the statement.
$result = $sql->get_result(); // Binds the last executed statement as a result.
$result->getUserFollowers($userid);
$result->getUserFollowing($userid);
$result->getUserLikes($userid);

return  json_encode(($result->fetch_assoc())); // Parse to JSON and print.
*/
/*Temp code close*/
/**/
$res =  $db2->query($sql);
$array = $res->fetch_all(MYSQLI_ASSOC);


$returnarray = array();

foreach ($array as $i =>$array_expression)
{
$array[$i]["followers"] = $this->getUserFollowers($userid);
$array[$i]["following"] = $this->getUserFollowing($userid);
//$array[$i]["like"] = $this->getUserLikes($userid);

$returnarray[] = $array[$i];
}

return $returnarray;
/**/
}
function getUserProfileDetails($userid)
{
global $db2, $C;

$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$sql = "Select 
users.id,
email,
username,
phone_no,
fullname,
language,
timezone,
gender,
birthdate,
active,
about_me,
birthdate,
reg_date,
is_online,
active,
if( (cover is null), '', cover) AS cover_image,
if( (avatar is null), '', avatar) AS profile_image,
access_token,
100 AS `followers`,
100 AS `following`,
num_favourites as `like`
from users
INNER JOIN oauth_access_token ON `users`.id = `oauth_access_token`.user_id
where users.id=".$userid;
/*Temp Code*/
/*
$sql->execute(); // Execute the statement.
$result = $sql->get_result(); // Binds the last executed statement as a result.
$result->getUserFollowers($userid);
$result->getUserFollowing($userid);
$result->getUserLikes($userid);

return  json_encode(($result->fetch_assoc())); // Parse to JSON and print.
*/
/*Temp code close*/
/**/
$res =  $db2->query($sql);
$array = $res->fetch_all(MYSQLI_ASSOC);


$returnarray = array();
$image_url =  $this->profileImageUrl();
foreach ($array as $i =>$array_expression)
{
$array[$i]["followers"] = $this->getUserFollowers($userid);
$array[$i]["following"] = $this->getUserFollowing($userid);
//$array[$i]["like"] = $this->getUserLikes($userid);
//$array[$i]["profile_image"] = $this->profileImageUrl($userid).$array[$i]["profile_image"];
if($array[$i]["profile_image"]) {
$array[$i]["profile_image"] = $image_url.$array[$i]["profile_image"];
}
if($array[$i]["cover_image"]) {
$array[$i]["cover_image"] = $image_url.$array[$i]["cover_image"];
}
$returnarray[] = $array[$i];
}

return $returnarray;
/**/
}
public function getUserLikes($userid)
{         
global $db2;
//  $query = 'SELECT Count(id) as cnt from post_likes where user_id='.$userid;
$query = 'SELECT Count(id) as cnt from user_favourites where whom='.$userid;
$res = $db2->query($query);
if($db2->num_rows($res) > 0)
{
$obj = $db2->fetch_object($res);
return intval($obj->cnt);
}
return 0;
}
public function getUserFollowing($userid)
{ 
global $db2;
$query = 'SELECT Count(id) as cnt from users_followed where who='.$userid;
$res = $db2->query($query);
if($db2->num_rows($res) > 0)
{
$obj = $db2->fetch_object($res);
return intval($obj->cnt);
}
return 0;
}
public function getUserFollowers($userid)
{       global $db2;
$query = 'SELECT Count(id) as cnt from users_followed where whom='.$userid;
$res = $db2->query($query);
if($db2->num_rows($res) > 0)
{
$obj = $db2->fetch_object($res);
return intval($obj->cnt);
}
return 0;
}
function signUpUser($fullname,$email,$phone,$username,$password,$dob,$gender,$location)
{
global $db2, $C;

$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$tmplang    = $db2->fetch_field('SELECT value FROM settings WHERE word="LANGUAGE" LIMIT 1');

$tmpzone    = $db2->fetch_field('SELECT value FROM settings WHERE word="DEF_TIMEZONE" LIMIT 1');

$referdby   = isset($_POST['referdby'])?(trim($_POST['referdby'])):'';
$type       = isset($_POST['type_person'])?(trim($_POST['type_person'])):'';

$tmppass    =  $password;
$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
$lastlogin_date = time();
$is_fb_used = (isset($_POST['fb_user_id'])? 'facebook_uid="'.$db2->e($_POST['fb_user_id']).'", ' : '');
$is_tw_used = (isset($_POST['tw_user_id'])? 'twitter_uid="'.$db2->e($_POST['tw_user_id']).'", ' : '');

$db2->query('INSERT INTO users SET '.$is_fb_used.$is_tw_used.' email="'.$db2->e($email).'", username="'.$db2->e($username).'",referdby="'.$db2->e($referdby).'", refer_type="'.$db2->e($type).'", password="'.$db2->e($tmppass).'",phone_no="'.$db2->e($phone).'", fullname="'.$db2->e($fullname).'", language="'.$tmplang.'", timezone="'.$tmpzone.'", reg_date="'.$lastlogin_date.'", reg_ip="'.$lastlogin_ip.'", lastlogin_date="'.$lastlogin_date.'", lastlogin_ip="'.$lastlogin_ip.'", gender="'.$gender.'",  location="'.$location.'", birthdate="'.$dob.'", active=1');  

$user_id    = (int) $db2->insert_id();
return $user_id;
}

function editUser($userid,$fullname,$email,$phone,$username,$password,$dob,$gender,$location,$about_me)
{
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$tmplang    = $db2->fetch_field('SELECT value FROM settings WHERE word="LANGUAGE" LIMIT 1');

$tmpzone    = $db2->fetch_field('SELECT value FROM settings WHERE word="DEF_TIMEZONE" LIMIT 1');

$referdby   = isset($_POST['referdby'])?(trim($_POST['referdby'])):'';
$type       = isset($_POST['type_person'])?(trim($_POST['type_person'])):'';

$tmppass    =  $password;
$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
$lastlogin_date = time();
$is_fb_used = (isset($_POST['fb_user_id'])? 'facebook_uid="'.$db2->e($_POST['fb_user_id']).'", ' : '');
$is_tw_used = (isset($_POST['tw_user_id'])? 'twitter_uid="'.$db2->e($_POST['tw_user_id']).'", ' : '');

$sql ='UPDATE users SET
email="'.$db2->e($email).'",
username="'.$db2->e($username).'",
password="'.$db2->e($tmppass).'",
phone_no="'.$db2->e($phone).'", 
fullname="'.$db2->e($fullname).'", 
lastlogin_date="'.$lastlogin_date.'", 
lastlogin_ip="'.$lastlogin_ip.'", 
gender="'.$gender.'",  
about_me="'.$about_me.'",  
birthdate="'.$dob.'", active=1 
Where ID='.$userid;

$db2->query($sql);

return $userid;
}
function editUserEmail($userid,$email)
{
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
$lastlogin_date = time();

$sql ='UPDATE users SET
email="'.$db2->e($email).'",
lastlogin_date="'.$lastlogin_date.'", 
lastlogin_ip="'.$lastlogin_ip.'" 
Where ID='.$userid;

$db2->query($sql);

return $userid;
}
function editUserUserName($userid,$username)
{
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
$lastlogin_date = time();

$sql ='UPDATE users SET
username="'.$db2->e($username).'",
lastlogin_date="'.$lastlogin_date.'", 
lastlogin_ip="'.$lastlogin_ip.'" 
Where ID='.$userid;

$db2->query($sql);

return $userid;
}
function editUserPhone($userid,$phone)
{
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
$lastlogin_date = time();

$sql ='UPDATE users SET
phone_no="'.$db2->e($phone).'",
lastlogin_date="'.$lastlogin_date.'", 
lastlogin_ip="'.$lastlogin_ip.'" 
Where ID='.$userid;

$db2->query($sql);

return $userid;
}
function editUserPassword($userid,$password)
{
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
$lastlogin_date = time();

$sql ='UPDATE users SET
password="'.$db2->e($password).'",
lastlogin_date="'.$lastlogin_date.'", 
lastlogin_ip="'.$lastlogin_ip.'" 
Where ID='.$userid;

$db2->query($sql);

return $userid;
}
public function getToken($userid)
{
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$oauth_access_token= $this->generate_request_token();

//echo 'SELECT id FROM oauth_access_token WHERE user_id="'.$userid.'"  LIMIT 1';

$res = $db2->query('SELECT oauth_access_token FROM oauth_access_token WHERE user_id='.$userid.'  LIMIT 1');

if($db2->num_rows($res) > 0)
{
$obj = $db2->fetch_object($res);
return $obj->oauth_access_token;
}

}

public function generateToken($userid)
{
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$oauth_access_token= $this->generate_request_token();

//echo 'SELECT id FROM oauth_access_token WHERE user_id="'.$userid.'"  LIMIT 1';

$res = $db2->query('SELECT id FROM oauth_access_token WHERE user_id='.$userid.'  LIMIT 1');

if($db2->num_rows($res) > 0)
{
$obj = $db2->fetch_object($res);

$res = $db2->query('Update oauth_access_token SET access_token = "'.$oauth_access_token.'" WHERE  id="'.intval($obj->id).'"');

return $oauth_access_token;
}
else
{
$res = $db2->query('Insert into oauth_access_token (access_token,user_id) VALUES ("'.$oauth_access_token.'", "'.$userid.'")');
return $oauth_access_token;
}
}

function generate_request_token()
{

$request_token='';
$request_token = substr(md5(rand().time().rand()), 0, 22);
return $request_token;    
}

public function validateToken($userid, $access_token)
{
global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$sql = 'SELECT id FROM oauth_access_token WHERE user_id='.$userid.' and access_token="'.$access_token.'"  LIMIT 1';

$res = $db2->query($sql);
if($db2->num_rows($res) > 0)
{
$obj = $db2->fetch_object($res);
return true;
}

else {
return false;

}
}

public function moreNewsPosts($userid,$postuserid,$postid,$pagenumber,$pagerecordcount) {
global $db2, $C;
$pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);
$pagenumber = $pagenumber -1;
$pagenumber = ((int)$pagenumber *  (int)$pagerecordcount)  ;
//$pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);

$db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
// if( (posttype = 2), p.title,  SUBSTRING(p.message, 1, 144)) AS title,           
$query = 'SELECT 
b.id AS pid,
p.id AS postid,
p.user_id AS postuserid,
u.username AS postusername,
u.avatar AS postuserimage,
if( (u.cover is null), "", u.cover) AS coverimage,
if( (p.title is null), "", p.title) AS title, 
p.posttype,
p.message AS message,
p.likes,
p.mentioned,
GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
pa.data,
pa.video_url as video_url,
pa.video_id as video_id,
p.posttags,
p.comments,
p.reshares,
p.date,
p.date_lastedit,
p.date_lastcomment,
p.group_name,
p.parent_id,
p.status,
p.post_level,
p.location,
p.thumb,
"" as `category`,
"public" AS `type`,
if( (pd.views is null), 0, pd.views) AS ViewCount,
if( (pd.shares is null), 0, pd.shares) AS sharecount,
if( (pd.likes is null), 0, pd.likes) AS likes,
if( (pd.comments is null), 0, pd.comments) AS comments,
if( (pd.reshares is null), 0, pd.reshares) AS reshares
FROM post_userbox b
JOIN posts p ON p.id=b.post_id AND p.user_id='.$postuserid.' AND (p.post_level = 0 OR p.post_level is null)  AND p.id is not null and p.id != '.$postid.' 
LEFT JOIN users u ON u.id=p.user_id
LEFT JOIN posts_details pd ON pd.post_id = p.id
LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
WHERE
b.user_id='.$userid.' 
Group By p.id
ORDER BY p.date_lastcomment DESC
LIMIT '.$pagenumber.','.$pagerecordcount;
//and (INSTR (pa.data, "<div") < 1 or INSTR (pa.data, "<div") is null)
$res =  $db2->query($query);
$array = $res->fetch_all(MYSQLI_ASSOC);
$returnarray = array();
// echo $query; die;
foreach ($array as $i =>$array_expression) {

/*if($array[$i]["posttype"] != 2) {
$title = strip_tags($array[$i]["message"]); 
$array[$i]["title"] = mb_substr($title, 0, 144,'UTF-8');
}*/
$array[$i]["title"] = strip_tags($array[$i]["title"]); 

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
$url = "https://";
else  
$url = "http://";   

$url.= $_SERVER['HTTP_HOST'];  
$array[$i]["profile_base_url"] =       $C->SITE_URL."storage/avatars/thumbs1/";
$array[$i]["attachment_base_url"] =       $C->SITE_URL."storage/attachments/1/";

if(empty($array[$i]["attachements"])) {
$array[$i]["attachements"] = '';
}

$array[$i]["attach_data"] = NULL;
if(!empty($array[$i]["data"])) {
$att_data = (array)unserialize($array[$i]["data"]);                    
$file_original = isset($att_data['file_original']) ? $att_data['file_original'] : '';
if(!empty($file_original)) {
$array[$i]["attach_data"]["file_original"] = $file_original;
}
}
unset($array[$i]["data"]);

if(empty($array[$i]["coverimage"])) {
$array[$i]["coverimage"] = '';
}          


$query = 'SELECT * FROM post_likes WHERE  user_id="'.$userid.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
$res =  $db2->query($query);
$obj = $db2->fetch_object($res);

if(empty($obj->id)){
$array[$i]["isliked"] = "0";  
} else {
$array[$i]["isliked"] = "1";  
}

$query1 = 'SELECT * FROM post_reshares WHERE  user_id="'.$userid.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
$res1 =  $db2->query($query1);
$obj1 = $db2->fetch_object($res1);           

if(empty($obj1->id)){
$array[$i]["isbuzzed"] = "0";  
} else {
$array[$i]["isbuzzed"] = "1";  
}   

$query1 = 'SELECT * FROM profile_share WHERE  who="'.$userid.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
$res1 =  $db2->query($query1);
$obj1 = $db2->fetch_object($res1);          

if(empty($obj1->id)){
$array[$i]["isshared"] = "0";  
} else {
$array[$i]["isshared"] = "1";  
}

$query3 = 'SELECT * FROM users_followed WHERE  who="'.$userid.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
$res3 =  $db2->query($query3);
$obj3 = $db2->fetch_object($res3);

if(empty($obj3->id)){
$array[$i]["isfollwed"] = "0";  
} else {
$array[$i]["isfollwed"] = "1";  
}       

$returnarray[] = $array[$i];
}            
return $returnarray;
}

public function loadPosts($user_id,$pagenumber,$pagerecordcount)
{
global $db2, $C;
$pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);

$pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);


$db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);

if(empty($user_id))
{
$query = 'SELECT 
b.id AS pid,
p.id AS postid,
p.user_id AS postuserid,
u.username AS postusername,
u.avatar AS postuserimage,
if( (u.cover is null), "", u.cover) AS coverimage,
if( (posttype = 2), p.title,  SUBSTRING(p.message, 1, 75)) AS title,
p.posttype,
p.message AS message,
p.likes,
p.mentioned,
GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachments,
p.attached,
"" as attachment,
p.posttags,
p.comments,
p.reshares,
p.date,
p.date_lastedit,
p.date_lastcomment,
p.group_name,
p.parent_id,
p.status,
p.post_level,
p.location,
p.thumb,
"" as `category`,
"public" AS `type`,
if( (pv.cnt is null), 0, pv.cnt) AS ViewCount
FROM post_userbox b
LEFT JOIN posts p ON p.id=b.post_id
LEFT JOIN users u ON u.id=p.user_id
LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
LEFT OUTER JOIN post_views_list pv ON pv.post_id = p.id
WHERE
p.post_level = 0  AND p.id is not null and (INSTR (pa.data, "<div") < 1 or INSTR (pa.data, "<div") is null)

Group By p.id
ORDER BY p.date_lastcomment DESC
LIMIT '.$pagenumber.','.$pagerecordcount;
}
else
{
$query = 'SELECT 
b.id AS pid,
p.id AS postid,
p.user_id AS postuserid,
u.username AS postusername,
u.avatar AS postuserimage,
if( (u.cover is null), "", u.cover) AS coverimage,
if( (posttype = 2), p.title,  SUBSTRING(p.message, 1, 75)) AS title,
p.posttype,
p.message AS message,
p.likes,
p.mentioned,
GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachments,
p.attached,
"" as attachment,
p.posttags,
p.comments,
p.reshares,
p.date,
p.date_lastedit,
p.date_lastcomment,
p.group_name,
p.parent_id,
p.status,
p.post_level,
p.location,
p.thumb,
"" as `category`,
"public" AS `type`,
if( (pv.cnt is null), 0, pv.cnt) AS ViewCount
FROM post_userbox b
LEFT JOIN posts p ON p.id=b.post_id
LEFT JOIN users u ON u.id=p.user_id
LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
LEFT OUTER JOIN post_views_list pv ON pv.post_id = p.id
WHERE
b.user_id='.$user_id.' AND p.post_level = 0  AND p.id is not null and (INSTR (pa.data, "<div") < 1 or INSTR (pa.data, "<div") is null)

Group By p.id
ORDER BY p.date_lastcomment DESC
LIMIT '.$pagenumber.','.$pagerecordcount;    



}


$res =  $db2->query($query);
$array = $res->fetch_all(MYSQLI_ASSOC);
$returnarray = array();

foreach ($array as $i =>$array_expression)
{
$array[$i]["likes"] = $this->getLikes($array[$i]["postid"]);
$array[$i]["attachement"] = $this->getAttachements($array[$i]["postid"]);
$array[$i]["comments"] = $this->getCommentsCount($array[$i]["postid"]);


if(!empty($user_id))
{
$query = 'SELECT * FROM post_likes WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
$res =  $db2->query($query);
$obj = $db2->fetch_object($res);

//echo $obj->id."-"; die;

if(empty($obj->id)){
$array[$i]["isliked"] = "0";  
}

else {
$array[$i]["isliked"] = "1";  
}



$query1 = 'SELECT * FROM post_reshares WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
$res1 =  $db2->query($query1);
$obj1 = $db2->fetch_object($res1);


if(empty($obj1->id)){
$array[$i]["isbuzzed"] = "0";  
}

else {
$array[$i]["isbuzzed"] = "1";  
}   


$query3 = 'SELECT * FROM users_followed WHERE  who="'.$user_id.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
$res3 =  $db2->query($query3);
$obj3 = $db2->fetch_object($res3);

if(empty($obj3->id))
{
$array[$i]["isfollwed"] = "0";
}
else
{
$array[$i]["isfollwed"] = "1";
}
}
$array[$i]["likes"]      =$this->get_like_count($array[$i]["postid"]);
$array[$i]["reshares"]       =$this->new_reshare_count($postid);

$returnarray[] = $array[$i];
}

return $returnarray;
}

public function getLikes($postid)
{
global $db2, $C;

//$db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);

$query = 'SELECT Count(post_id) as cnt from post_likes where post_id='.$postid;
if (!empty($query))
{
$res = $db2->query($query);
if($db2->num_rows($res) > 0)
{
$obj = $db2->fetch_object($res);
return intval($obj->cnt);
}
}
return 0;
}
public function getAttachements($postid)
{
global $db2, $C;

// $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);

$query = 'SELECT id,type, data, if(content is null,\'\',content) as caption FROM `posts_attachments`  where post_id='.$postid.' order by id';
$res = $db2->query($query);

$array = $res->fetch_all(MYSQLI_ASSOC);
$returnarray = array();

foreach ($array as $i =>$array_expression)
{
$array[$i]["data"] = $this->splitAttachements($array[$i]["data"]);
$returnarray[] = $array[$i];
}
return $returnarray;
}
public function getAttachementsHTML($postid)
{
global $db2, $C;

// $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);

$query = 'SELECT id,type, data, if(comment is null,\'\',comment) as comment FROM `posts_attachments`  where post_id='.$postid;
$res = $db2->query($query);

$array = $res->fetch_all(MYSQLI_ASSOC);
$returnstr = "";

foreach ($array as $i =>$array_expression)
{
$returnstr = $returnstr."<BR>".$this->splitAttachementsHTML($array[$i]["data"]);
}

return $returnstr;
}
public function splitAttachementsHTML($data)
{
$file_originalSTART = strpos($data, 's:25:"', 0)+6;
$file_originalEND  = strpos($data, ';', $file_originalSTART)-1;
$file_original = substr($data,$file_originalSTART,$file_originalEND-$file_originalSTART);

$file_previewSTART = strpos($data, '"file_preview";s:26:"', 0)+21;
$file_previewEND  = strpos($data, ';', $file_previewSTART)-1;
$file_preview = substr($data,$file_previewSTART,$file_previewEND-$file_previewSTART);

$file_thumbnailSTART = strpos($data, '"file_thumbnail";s:26:"', 0)+23;
$file_thumbnailEND  = strpos($data, ';', $file_thumbnailSTART)-1;
$file_thumbnail = substr($data,$file_thumbnailSTART,$file_thumbnailEND-$file_thumbnailSTART);

$file_original."+".$file_preview."+".$file_thumbnail;
return "<img alt='Image' src='".$this->imageUrl().$file_preview."' >";
}
public function getCommentsCount($postid)
{
global $db2, $C;

//$db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
//SELECT * FROM `posts` ORDER BY `posts`.`id` DESC

$query = 'SELECT Count(id) as cnt from posts_comments where post_id='.$postid;
if (!empty($query))
{
$res = $db2->query($query);
if($db2->num_rows($res) > 0)
{
$obj = $db2->fetch_object($res);
return intval($obj->cnt);
}
}
return 0;
}
public function splitAttachements($data)
{
$file_originalSTART = strpos($data, 's:25:"', 0)+6;
$file_originalEND  = strpos($data, ';', $file_originalSTART)-1;
$file_original = substr($data,$file_originalSTART,$file_originalEND-$file_originalSTART);

$file_previewSTART = strpos($data, '"file_preview";s:26:"', 0)+21;
$file_previewEND  = strpos($data, ';', $file_previewSTART)-1;
$file_preview = substr($data,$file_previewSTART,$file_previewEND-$file_previewSTART);

$file_thumbnailSTART = strpos($data, '"file_thumbnail";s:26:"', 0)+23;
$file_thumbnailEND  = strpos($data, ';', $file_thumbnailSTART)-1;
$file_thumbnail = substr($data,$file_thumbnailSTART,$file_thumbnailEND-$file_thumbnailSTART);

$file_original."+".$file_preview."+".$file_thumbnail;
return array("file_original"=>$file_original,"file_preview"=>$file_preview,"file_thumbnail"=>$file_thumbnail);
}
public function loadPosts1($user_id,$pagenumber,$pagerecordcount)
{
global $db2, $C;
$pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);

$pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);

//if ($this->getUserFollowing($user_id) > 0)
if (1==1)
{
$query = 'SELECT
b.id AS pid,
p.id AS postid,
p.user_id AS postuserid,
u.username AS postusername,
u.avatar AS postuserimage,
SUBSTRING(p.message, 1, 50) as title,
p.message AS message,
Count(pl.post_id) as "like",
p.mentioned,
GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
p.posttags,
Count(pc.id) as "comments",
Count(pr.id) as "reshares",
p.date,
p.date_lastedit,
p.date_lastcomment,
p.group_name,
p.parent_id,
p.status,
p.post_level,
p.location,
p.thumb,
IF(count(uf.id) > 0,true,false) AS `following`,
"public" AS `type`
FROM post_userbox b
LEFT JOIN posts p ON p.id=b.post_id
LEFT JOIN users u ON u.id=p.user_id
LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
LEFT OUTER JOIN post_likes pl ON pl.post_id = pa.post_id
LEFT OUTER JOIN posts pc ON pc.parent_id = pa.post_id
LEFT OUTER JOIN post_reshares pr ON pr.post_id = pa.post_id
LEFT OUTER JOIN users_followed uf ON uf.whom = p.user_id
WHERE
b.user_id='.$user_id.' AND p.post_level is null AND uf.who = b.user_id  
Group By p.id
ORDER BY p.date_lastcomment DESC
LIMIT '.$pagenumber.','.$pagerecordcount;
//.$C->PAGING_NUM_POSTS;
// echo $query; 
}
else
{
$query = 'SELECT
b.id AS pid,
p.id AS postid,
p.user_id AS postuserid,
u.username AS postusername,
u.avatar AS postuserimage,
SUBSTRING(p.message, 1, 50) as title,
p.message AS message,
Count(pl.post_id) as "like",
p.mentioned,
GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
p.posttags,
Count(pc.id) as "comments",
Count(pr.id) as "reshares",
p.date,
p.date_lastedit,
p.date_lastcomment,
p.group_name,
p.parent_id,
p.status,
p.post_level,
p.location,
p.thumb,
IF(count(uf.id) > 0,true,false) AS `following`,
"public" AS `type`
FROM post_userbox b
LEFT JOIN posts p ON p.id=b.post_id
LEFT JOIN users u ON u.id=p.user_id
LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
LEFT OUTER JOIN post_likes pl ON pl.post_id = pa.post_id
LEFT OUTER JOIN posts pc ON pc.parent_id = pa.post_id
LEFT OUTER JOIN post_reshares pr ON pr.post_id = pa.post_id
LEFT OUTER JOIN users_followed uf ON uf.whom = p.user_id
WHERE
b.user_id='.$user_id.' AND p.post_level is null AND uf.who = b.user_id  
Group By p.id
ORDER BY p.date_lastcomment DESC
LIMIT '.$pagenumber.','.$pagerecordcount;
//.$C->PAGING_NUM_POSTS;
}


$res =  $db2->query($query);

return $res->fetch_all(MYSQLI_ASSOC);


}
public function imageUrl()
{
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
$url = "https://";
else  
$url = "http://";   

$url.= $_SERVER['HTTP_HOST'];   

// Append the requested resource location to the URL   
//$url.= $_SERVER['REQUEST_URI'];    

return $C->SITE_URL."storage/attachments/1/";

}
public function profileImageUrl()
{
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
$url = "https://";
else  
$url = "http://";   

$url.= $_SERVER['HTTP_HOST'];   

// Append the requested resource location to the URL   
//$url.= $_SERVER['REQUEST_URI'];    

return $C->SITE_URL."storage/avatars/thumbs1/";

}
public function postBaseUrl()
{
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
$url = "https://";
else  
$url = "http://";   

$url.= $_SERVER['HTTP_HOST'];   

// Append the requested resource location to the URL   
//$url.= $_SERVER['REQUEST_URI'];    

return $C->SITE_URL."view/post:";

}
public function refreshToken($userid,$token)
{

global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

//echo 'SELECT id FROM oauth_access_token WHERE user_id="'.$userid.'"  LIMIT 1';

$res = $db2->query('SELECT id FROM oauth_access_token WHERE user_id='.$userid.' and access_token=\''.$token.'\' LIMIT 1');

if($db2->num_rows($res) > 0)
{
$obj = $db2->fetch_object($res);
$oauth_access_token= $this->generate_request_token();

$res = $db2->query('Update oauth_access_token SET access_token = "'.$oauth_access_token.'" WHERE  id="'.intval($obj->id).'"');

return $oauth_access_token;
}
return null;
}
public function expiretoken($userid,$token)
{

global $db2, $C;
$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

//echo 'SELECT id FROM oauth_access_token WHERE user_id="'.$userid.'"  LIMIT 1';

$res = $db2->query('SELECT id FROM oauth_access_token WHERE user_id='.$userid.' and access_token=\''.$token.'\' LIMIT 1');

if($db2->num_rows($res) > 0)
{
$obj = $db2->fetch_object($res);
//$oauth_access_token= $this->generate_request_token();

$res = $db2->query('Update oauth_access_token SET access_token = "" WHERE  id="'.intval($obj->id).'"');

return true;
}
return false;
}   



} 
?>
