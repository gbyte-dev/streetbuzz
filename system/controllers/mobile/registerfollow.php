<?php
if( $this->user->is_logged ) {
		$this->redirect('home');
	}
	if(isset($_SESSION['request_token'])){
		unset($_SESSION['request_token']);
		unset($_SESSION['request_token_secret']);
	}


$unusers = $_POST['unuser'];
$user_id= $_POST['userid'];
$users= $_POST['users'];
if(!empty($users)){
  $users           =explode(",",$_POST['users']);
  $usercount        =count($users);
  if($user_id !=''){
    $mes ='ntf_me_if_u_joins_grp';
	  foreach($users as $keys=>$vals){

	  $db2->query('INSERT INTO users_followed SET who="'.$user_id.'", whom="'.$vals.'", date="'.time().'", whom_from_postid="'.$this->network->get_last_post_id().'" ');
	  $db2->query('INSERT INTO notifications SET notif_type="'.$mes.'", to_user_id="'.$vals.'",from_user_id="'.$user_id.'", date="'.time().'" ');
	  $db2->query('INSERT INTO users_dashboard_tabs SET user_id="'.$vals.'", tab="notifications",state=1,newposts=1  ');


	  }
	  $userdata           =   $db2->query('SELECT num_followers FROM users WHERE id="'.$db2->e($user_id).'" LIMIT 1');
	  $userres            =$db2->fetch_object($userdata);
	  if($userres->num_followers !=''){
	  	$usercount  = $userres->num_followers + $usercount;
	  
	  }else{
	 	 $usercount  = 0+ $usercount;
	  }


	$db2->query('UPDATE users SET num_followers="'.$usercount.'" WHERE id="'.$user_id.'" ');
	  
  }
  }
 $userdata =   $db2->query('SELECT id, username, fullname,email,password,phone_no  FROM users WHERE id="'.$db2->e($user_id).'" LIMIT 1');
 $userres            =$db2->fetch_object($userdata );
 $name="newuser";
 if(!empty($unusers )){
	 foreach($unusers  as $keys=>$vals){
	 		$db2->query('INSERT INTO users_invitations SET user_id="'.$user_id.'", date="'.time().'", recp_name="'.$db2->e($name).'", recp_email="'.$db2->e($vals).'", recp_is_registered=0, recp_user_id=0');
	 			$to = $vals;
	 			$url= $C->SITE_URL;
				$subject = "Join StreetBuzz";
				$message = "Hello,<br />
		                          To sign up, use the following link:".$url."
		                          <br /><br />
		                          Regards,<br />
		                          StreetBuzz
							 ";
				 // Always set content-type when sending HTML email
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				// More headers
				$headers .= 'From: <webmaster@example.com>' . "\r\n";

				if(mail($to,$subject,$message,$headers)){
				}

	 
	 }
 }
 if($user_id !=''){
 
 $strret_useremail = $userres->username ;
 $street_userpassword= $userres->password;
 
 $res = $this->user->login($strret_useremail,$street_userpassword, FALSE);
 
 
  $key	= md5(time().rand(0,999999));
				$_SESSION['reg_'.$key]	= (object) array (
						'network_id'	=> $this->network->id,
						'user_id'		=> $user_id,
				);
		
				$notif = new notifier();
				$notif->set_notification_obj('network', 1);
				$notif->onJoinNetwork();
				//echo '<pre>';print_r($_SESSION);exit;
		
				//if($network_members < 1001 ){
				//	$this->redirect( $C->SITE_URL.'signup/follow/regid:'.$key);
				//}else{
				$this->redirect($C->SITE_URL.'dashboard');
				
		}

  


?>