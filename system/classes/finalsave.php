<?php
header('Access-Control-Allow-Origin: *'); 

if(isset($_POST['strret_useremail'])){
    $cats   = array(1,2,3,4,5,6,7,8,9,10,11,12);
	$catids = 	implode(",",$cats);

	
	$tmplang	= $db2->fetch_field('SELECT value FROM settings WHERE word="LANGUAGE" LIMIT 1');
	$tmpzone	= $db2->fetch_field('SELECT value FROM settings WHERE word="DEF_TIMEZONE" LIMIT 1');
	$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
	$lastlogin_date = time();
	$fullname = $db2->escape($_POST['fullname']);
	$username = $db2->escape($_POST['street_username']);

	$strret_useremail = $_POST['strret_useremail'];
	$street_userpassword = md5($_POST['street_userpassword']);
	if(is_numeric($strret_useremail)){

		$phone =  $strret_useremail;
		$email ='';

	}else{
		$phone =  '';
		$email =$strret_useremail;

	}
	$referdby ='camp';
	$type ='person';
	$bdate_d	= isset($_POST['profile_birth_day'])? intval($_POST['profile_birth_day']) : '';
   $bdate_m	= isset($_POST['profile_birth_month'])? intval($_POST['profile_birth_month']) : '';
			$bdate_y	= isset($_POST['profile_birth_year'])? intval($_POST['profile_birth_year']) : '';
	$birthdate	= $bdate_y.'-'.str_pad($bdate_m,2,0,STR_PAD_LEFT).'-'.str_pad($bdate_d,2,0,STR_PAD_LEFT);
		$gender		= isset($_POST['profile_gender']) ? trim($_POST['profile_gender']) : '';

		$db2->query('INSERT INTO users SET  email="'.$db2->e($email).'", username="'.$db2->e($username).'",referdby="'.$db2->e($referdby).'", refer_type="'.$db2->e($type).'", password="'.$db2->e($street_userpassword).'",phone_no="'.$db2->e($phone).'", gender="'.$db2->e($gender).'", birthdate="'.$db2->e($birthdate).'", fullname="'.$db2->e($fullname).'", language="'.$tmplang.'", timezone="'.$tmpzone.'", reg_date="'.$lastlogin_date.'", reg_ip="'.$lastlogin_ip.'", lastlogin_date="'.$lastlogin_date.'", lastlogin_ip="'.$lastlogin_ip.'" ,active=1');
	   $user_id	= (int) $db2->insert_id();
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
	$db2->query('INSERT INTO user_categeory SET user_id="'.$user_id.'",cat_ids="'.$catids.'" ');


	  $mes ='ntf_me_if_u_follows_me';
	  $ip =$_SERVER['REMOTE_ADDR'];
	  $ipgetdata = file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip);
	  if(!empty($ipgetdata)){
	      	  $ipdat      = @json_decode($ipgetdata);
	      	  $state     = $ipdat->geoplugin_region;
	      	  $state     =str_replace(' ', '', $state);
	      	  $state     = strtoupper($state);
	      	  if($state == "ANDHRAPRADESH" || $state == "TELANGANA" ){
	      	      $regionnews ='T';
	      	    $populareres=		$db2->query('SELECT user_id FROM  street_suggestion WHERE  region_news ="'.$regionnews.'" ');  
	      	  }else{
	      	       $regionnews ='H';
	      	    $populareres=		$db2->query('SELECT user_id FROM  street_suggestion WHERE  region_news ="'.$regionnews.'" ');  
	      	      
	      	  }
	  }else{
	     $populareres=		$db2->query('SELECT user_id FROM  street_suggestion WHERE  popular_follow_type IN('.$catids.')');
	      
	  }


	 
/*	$populareres=		$db2->query('SELECT user_id FROM  street_suggestion WHERE  popular_follow_type IN('.$catids.')');*/


	
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
if(!empty($user_id)){
    	$this->user->login($strret_useremail, $street_userpassword, FALSE);
	$key	= md5(time().rand(0,999999));
				$_SESSION['reg_'.$key]	= (object) array (
						'network_id'	=> $this->network->id,
						'user_id'		=> $user_id,
				);
		
				$notif = new notifier();
				$notif->set_notification_obj('network', 1);
				$notif->onJoinNetwork();
    echo "1";
}else{
        echo "0";
}
}

?>