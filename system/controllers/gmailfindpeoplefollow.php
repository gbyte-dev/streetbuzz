<?php
$followers = ($_POST['followers']);

	     $mes ='ntf_me_if_u_follows_me';
     foreach($followers  as $keys=>$vals){
      $folow_res           = $db2->query('SELECT whom FROM  users_followed as uf where who="'.$this->user->id.'" AND whom ="'.$vals.'" ' );
      $followvals                     =$db2->fetch_object($folow_res);
        if( $followvals->whom ==''){
            $followcount[]              =$followvals->whom;
          $db2->query('INSERT INTO users_followed SET who="'.$this->user->id.'", whom="'.$vals.'", date="'.time().'", whom_from_postid="'.$this->network->get_last_post_id().'" ');
	  $db2->query('INSERT INTO notifications SET notif_type="'.$mes.'", to_user_id="'.$vals.'",from_user_id="'.$user_id.'", date="'.time().'" ');
	  $notifi ="notifications";
         $userres            = $db2->query('SELECT user_id,newposts FROM  users_dashboard_tabs  where user_id="'.$vals.'" AND tab="'.$notifi.'" ' );
		 $useridfollow = $db2->fetch_object($userres);
		 if($useridfollow->user_id ==""){
			 		$db2->query('INSERT INTO users_dashboard_tabs SET user_id="'.$vals.'", tab="notifications",state=1,newposts=1  ');
       }else{
		   $postcnt = $useridfollow->newposts+1;
		   	$db2->query('UPDATE users_dashboard_tabs SET  newposts='.$postcnt.' WHERE  user_id='.$vals.' AND tab="'.$notifi.'" ');

		   
	   }
	    $userdata           =   $db2->query('SELECT num_followers FROM users WHERE id="'.$vals.'" LIMIT 1');
	  $userres            =$db2->fetch_object($userdata);
	  if($userres->num_followers !=''){
	  	$usercount  = $userres->num_followers +1;
	  
	  }else{
	 	 $usercount  = 0+$usercount;
	  }
	$db2->query('UPDATE users SET num_followers='.$usercount.' WHERE id='.$vals.' ');

        }
}
	  $usercount =count($followcount);

    if($usercount  > 0){
      $userdata           =   $db2->query('SELECT num_followers FROM users WHERE id="'.$db2->e($this->user->id).'" LIMIT 1');
	  $userres            =$db2->fetch_object($userdata);
	  if($userres->num_followers !=''){
	  	$usercount  = $userres->num_followers + $usercount;
	  
	  }else{
	 	 $usercount  = 0+$usercount;
	  }
	$db2->query('UPDATE users SET num_followers='.$usercount.' WHERE id='.$this->user->id.' ');
	}
	$this->redirect("'.$C->SITE_URL.'/members/tab:ifollow");

            

?>