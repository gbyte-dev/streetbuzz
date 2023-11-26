
<?php

if(isset($_POST['userid'])){
	
	$user_id =  $_POST['userid'];
	$catids = 	implode(",",$_POST['cat']);
	
	$db2->query('INSERT INTO user_categeory SET user_id="'.$user_id.'",cat_ids="'.$catids.'" ');
	  $mes ='ntf_me_if_u_follows_me';

	$populareres=		$db2->query('SELECT user_id FROM  street_suggestion WHERE  popular_follow_type IN('.$catids.')');
	
while($result    = $db2->fetch_object($populareres)){
	$db2->query('INSERT INTO users_followed SET who="'.$user_id.'", whom="'.$result->user_id.'", date="'.time().'", whom_from_postid="'.$this->network->get_last_post_id().'" ');
	  	  $db2->query('INSERT INTO notifications SET notif_type="'.$mes.'", to_user_id="'.$result->user_id.'",from_user_id="'.$user_id.'", date="'.time().'" ');
		 $usernumcnt = $db2->query('SELECT num_followers FROM  users  WHERE id="'.$result->user_id.'" ');
		$userfollowerscnt              =$db2->fetch_object($usernumcnt);
		$userfollowcnt               = ($userfollowerscnt->num_followers) + 1;
		$db2->query('UPDATE users SET num_followers="'.$userfollowcnt.'" WHERE id="'.$result->user_id.'" ');
		  $notifi ="notifications";
         $userres            = $db2->query('SELECT user_id,newposts FROM  users_dashboard_tabs  where user_id="'.$result->user_id.'" AND tab="'.$notifi.'" ' );
		 $useridfollow = $db2->fetch_object($userres);
		 if($useridfollow->user_id ==""){
			 		$db2->query('INSERT INTO users_dashboard_tabs SET user_id="'.$result->user_id.'", tab="notifications",state=1,newposts=1  ');
       }else{
		   $postcnt = $useridfollow->newposts+1;
		   	$db2->query('UPDATE users_dashboard_tabs SET  newposts='.$postcnt.' WHERE  user_id='.$result->user_id.' AND tab="'.$notifi.'" ');

		   
	   }
}
	
			

if($_POST['action'] =="finish"){
	$this->redirect($C->SITE_URL.'dashboard');

	
}else{
	
$this->redirect($C->SITE_URL.'invitefriendscontacts');

 } } ?>

</html>