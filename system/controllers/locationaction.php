<?php 
error_reporting(E_ALL);
ini_set('display_errors', 'On');
$userId = $this->user->sess['LOGGED_USER']->id;
if (isset($_POST) && !empty($_POST)) {
    $locationids = $_POST['locationids'];
    $lo=explode(',',$locationids);
    $locationids= $lo[0];

    	$query = $db2->query('UPDATE users SET  location_id='.$locationids.' WHERE  id='.$userId);
    	
    	echo 1;	
    	
    $user_id=$userId;	
    $local_id =$locationids;
 $mes ='ntf_me_if_u_follows_me';
if($local_id!=null && $local_id!=''){
$followers = $db2->query('SELECT * FROM sb_location_handle where location_id='.$local_id); 
     $follower = $followers->fetch_assoc();
     $user_handles = $follower['user_handles'];
$state_handles = $follower['state_handles'];
$capital_handles = $follower['capital_handles'];
//$country_handles = $follower['country_handles'];
$national_handles = $follower['national_handles'];
$international_handles = $follower['international_handles'];

 $final_handles = $user_handles . ',' . $state_handles . ',' . $capital_handles . ',' . $national_handles . ',' . $international_handles;
if ($final_handles != "") {
    $array_final = explode(',', $final_handles);
}

	  
foreach($array_final as $uhandle){
  
	$db2->query('INSERT INTO users_followed SET who="'.$user_id.'", whom="'.$uhandle.'", date="'.time().'", whom_from_postid="'.$this->network->get_last_post_id().'" ');
	  	  $db2->query('INSERT INTO notifications SET notif_type="'.$mes.'", to_user_id="'.$uhandle.'",from_user_id="'.$user_id.'", date="'.time().'" ');
		 $usernumcnt = $db2->query('SELECT num_followers FROM  users  WHERE id="'.$uhandle.'" ');
		$userfollowerscnt              =$db2->fetch_object($usernumcnt);
		$userfollowcnt               = ($userfollowerscnt->num_followers) + 1;
		$db2->query('UPDATE users SET num_followers="'.$userfollowcnt.'" WHERE id="'.$uhandle.'" ');
		  $notifi ="notifications";
         $userres            = $db2->query('SELECT user_id,newposts FROM  users_dashboard_tabs  where user_id="'.$uhandle.'" AND tab="'.$notifi.'" ' );
		 $useridfollow = $db2->fetch_object($userres);
		 if($useridfollow->user_id ==""){
			 		$db2->query('INSERT INTO users_dashboard_tabs SET user_id="'.$uhandle.'", tab="notifications",state=1,newposts=1  ');
       }else{
		   $postcnt = $useridfollow->newposts+1;
		   	$db2->query('UPDATE users_dashboard_tabs SET  newposts='.$postcnt.' WHERE  user_id='.$uhandle.' AND tab="'.$notifi.'" ');

		   
	   }


	   $currentTime = time(); 
 $twentyFourHoursAgo = $currentTime - (48 * 60 * 60); 

 $post_id = $db2->query("SELECT * FROM posts WHERE user_id = $uhandle AND date >= $twentyFourHoursAgo");
 
	while($post = $post_id->fetch_assoc()){
// 	  print_r($post);die('=============');
         $post_id1 = $post['id'];
         

        $db2->query('INSERT INTO post_userbox SET user_id="'.$user_id.'",post_id="'.$post_id1.'" ');
}
}
}	
    	
   
    	
    	
    
}
?>