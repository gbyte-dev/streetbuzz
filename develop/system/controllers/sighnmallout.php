<?php
	if( $this->network->id && $this->user->is_logged ) {
		$this->redirect('dashboard');
	}
	
	$this->load_langfile('outside/global.php');
	$this->load_langfile('outside/signin.php');
	
	$D->submit	= FALSE;
	$D->error	= FALSE;
	$D->errmsg	= '';
	$D->email		= '';
	$D->password	= '';
	$D->rememberme	= FALSE;
if(isset($_POST['fbid'])){
	$tmplang	= $db2->fetch_field('SELECT value FROM settings WHERE word="LANGUAGE" LIMIT 1');
		$tmpzone	= $db2->fetch_field('SELECT value FROM settings WHERE word="DEF_TIMEZONE" LIMIT 1');
		$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
		$lastlogin_date = time();
		$fullname = $_POST['facebookfirstname'];
        $username = $_POST['fbuser'];
	    $strret_useremail = $_POST['fbemail'];
		if(is_numeric($strret_useremail)){
	
		$phone =  $strret_useremail;
		$email ='';
		
	 }else{
		 $phone =  '';
		$email =$strret_useremail;
		
    }
		if(!empty($_POST['fbdateofbirth'])){
		$dateofbirth            =explode("/",$_POST['fbdateofbirth']);
		$birthdate	= $dateofbirth[2].'-'.$dateofbirth[0].'-'.$dateofbirth[1];
     }else{
	$bdate_d	= isset($_POST['profile_birth_day'])? intval($_POST['profile_birth_day']) : '';
    $bdate_m	= isset($_POST['profile_birth_month'])? intval($_POST['profile_birth_month']) : '';
    $bdate_y	= isset($_POST['profile_birth_year'])? intval($_POST['profile_birth_year']) : '';
	$birthdate	= $bdate_y.'-'.str_pad($bdate_m,2,0,STR_PAD_LEFT).'-'.str_pad($bdate_d,2,0,STR_PAD_LEFT);
	}
	$referdby ='camp';
	if(strtoupper($_POST['fbgender']) =='MALE'){
		$gender ='m';
		
	}else{
		$gender ='f';
		
	}
	$facebookuid = $_POST['fbid'];
	$answerid = $_POST['answerid'];
	$pollid = $_POST['pollid'];
	if(is_numeric($strret_useremail)){
		$user_id	= $db2->fetch_field('SELECT id FROM users WHERE phone_no="'.$strret_useremail.'" LIMIT 1');

	}else{
		$user_id	= $db2->fetch_field('SELECT id FROM users WHERE email="'.$strret_useremail.'" LIMIT 1');

	}
	if(!empty($user_id)){
	  		$fbid	= $db2->fetch_field('SELECT id FROM users WHERE facebook_uid="'.$facebookuid.'" LIMIT 1');
		     if(!empty($fbid)){
				$pollres          =$this->user->userpollanswerforsighup($fbid,$pollid);
				if(empty($pollres)){	
		             $db2->query("INSERT INTO post_poll_votes SET 
							POLL_ID = '".$pollid."', 
							ANSWER_ID = '".$answerid."', 
							VOTER_USER_ID = '".$fbid."'", FALSE);
	                  }
				$r	= $db2->query('SELECT username,password FROM users WHERE id="'.$fbid.'"', FALSE);
				$userres = $this->db2->fetch_object($r);
		
	$this->user->login($userres->username, $userres->password, FALSE);
			$key	= md5(time().rand(0,999999));
				$_SESSION['reg_'.$key]	= (object) array (
						'network_id'	=> $this->network->id,
						'user_id'		=> $fbid,
				);
		
				$notif = new notifier();
				$notif->set_notification_obj('network', 1);
				$notif->onJoinNetwork();
				//echo '<pre>';print_r($_SESSION);exit;
		
				//if($network_members < 1001 ){
				//	$this->redirect( $C->SITE_URL.'signup/follow/regid:'.$key);
				//}else{
				//$this->redirect($C->SITE_URL.'dashboard');

$this->redirect($C->SITE_URL.'view/post:'.$_POST['postid']);
 		

			 }else{
						$db2->query('update  users SET  email="'.$db2->e($email).'",facebook_uid="'.$facebookuid.'", username="'.$db2->e($username).'",referdby="'.$db2->e($referdby).'", refer_type="'.$db2->e($type).'",phone_no="'.$db2->e($phone).'",gender="'.$db2->e($gender).'", birthdate="'.$db2->e($birthdate).'", fullname="'.$db2->e($fullname).'", language="'.$tmplang.'", timezone="'.$tmpzone.'", reg_date="'.$lastlogin_date.'", reg_ip="'.$lastlogin_ip.'", lastlogin_date="'.$lastlogin_date.'", lastlogin_ip="'.$lastlogin_ip.'"  WHERE id="'.$user_id.'" ');
	$pollres          =$this->user->userpollanswerforsighup($user_id,$pollid);
			if(empty($pollres)){	
	$db2->query("INSERT INTO post_poll_votes SET 
							POLL_ID = '".$pollid."', 
							ANSWER_ID = '".$answerid."', 
							VOTER_USER_ID = '".$user_id."'", FALSE);
	}
	$r	= $db2->query('SELECT username,password FROM users WHERE id="'.$user_id.'"', FALSE);
				$userres = $this->db2->fetch_object($r);
	$this->user->login($userres->username, $userres->password, FALSE);
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
				//$this->redirect($C->SITE_URL.'dashboard');

$this->redirect($C->SITE_URL.'view/post:'.$_POST['postid']);



			 }


	}else{
		$street_userpassword =md5('sb123');
		$db2->query('INSERT INTO users SET  email="'.$db2->e($email).'",facebook_uid="'.$facebookuid.'", username="'.$db2->e($username).'",referdby="'.$db2->e($referdby).'", refer_type="'.$db2->e($type).'", password="'.$db2->e($street_userpassword).'",phone_no="'.$db2->e($phone).'",gender="'.$db2->e($gender).'", birthdate="'.$db2->e($birthdate).'", fullname="'.$db2->e($fullname).'", language="'.$tmplang.'", timezone="'.$tmpzone.'", reg_date="'.$lastlogin_date.'", reg_ip="'.$lastlogin_ip.'", lastlogin_date="'.$lastlogin_date.'", lastlogin_ip="'.$lastlogin_ip.'" ,active=1');
		
    $user_id	= (int) $db2->insert_id();
	
	 $mes ='ntf_me_if_u_follows_me';

	$populareres=		$db2->query('SELECT user_id FROM  street_suggestion WHERE  popular_follow_type IS NOT NULL');
	
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
	$db2->query("INSERT INTO post_poll_votes SET 
							POLL_ID = '".$pollid."', 
							ANSWER_ID = '".$answerid."', 
							VOTER_USER_ID = '".$user_id."'", FALSE);
$level             = 'BEGINNER';
$currency          = 'INR';
$hit              = 0;
$miss              = 0;
$AvailableEarnings =0;
$withdrawalwarning =0;
$TotalPrediction =0;
$nationalamnt =0;
$date  =date('Y-m-d h:i:s');
$db2->query('INSERT INTO user_predict_details SET  User_id="'.$user_id.'", Level="'.$level.'",Currency="'.$currency.'", Hit="'.$hit.'", miss="'.$miss.'",AvailableEarnings="'.$AvailableEarnings.'", WithdrawnEarnings	="'.$withdrawalwarning.'",TotalPrediction="'.$TotalPrediction.'", NotionalAmount="'.$nationalamnt.'",CreatedDate="'.$date.'",UpdateDate="'.$date.'"');
$this->user->login($username, $street_userpassword, FALSE);
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
				//$this->redirect($C->SITE_URL.'dashboard');

$this->redirect($C->SITE_URL.'view/post:'.$_POST['postid']);
	}





	 
	

	//$user_id	= $this->user->getuseridfacebook($strret_useremail);
	

}



	
	if( isset($_POST['email'], $_POST['password']) ) {
		global $plugins_manager;
		$plugins_manager->onPageSubmit();
		if( !$plugins_manager->isValidEventCall() ){
			$error = TRUE;
			$errmsg = $plugins_manager->getEventCallErrorMessage();
		}
		
		$D->submit	= TRUE;
		$D->email		= trim($_POST['email']);
		$D->password	= trim($_POST['password']);
		$D->rememberme	= isset($_POST['rememberme']) && $_POST['rememberme']==1;
		if($D->rememberme !=''){
			$username = "username";
            $usernamevalue = $D->email;
			$userpassword = "userpassword";
            $password = $D->password;
		   setcookie($username, $usernamevalue, time() + (86400 * 30), "/"); // 86400 = 1 day
			setcookie($userpassword, $password, time() + (86400 * 30), "/"); // 86400 = 1 day
			
		}
		if( empty($D->email) || empty($D->password) ) {
			$D->error	= TRUE;
			if(empty($D->email)){
			
			$this->redirect($C->SITE_URL.'home?message=1');
			}
			if( empty($D->password)){
			
			$this->redirect($C->SITE_URL.'home?message=2');
			}
			
		}
		else {
			if( $this->user->is_logged ) {
				$this->user->logout();
			}
			$res	= $this->user->login($D->email, md5($D->password), $D->rememberme);
			if( ! $res ) {
				
			}
			else {
			$user_id	= $this->user->getuserid($D->email, md5($D->password));
			$answerid = $_POST['answerid'];
	       $pollid = $_POST['pollid'];
		   $pollres          =$this->user->userpollanswerforsighup($user_id,$pollid);
		   if(empty($pollres)){
	       $db2->query("INSERT INTO post_poll_votes SET 
							POLL_ID = '".$pollid."', 
							ANSWER_ID = '".$answerid."', 
							VOTER_USER_ID = '".$user_id."'", FALSE);
		   }
			$this->redirect($C->SITE_URL.'view/post:'.$_POST['postid']);



				
				//$this->redirect($C->SITE_URL.'dashboard');
			}
		}
	}



	
	
?>