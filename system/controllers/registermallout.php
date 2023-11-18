<?php 
if( $this->user->is_logged ) {
		$this->redirect('home');
	}
$user 		= & $GLOBALS['user'];
	$network 	= & $GLOBALS['network'];
	$pm 		= & $GLOBALS['plugins_manager'];
           $locationid = "";
	if(isset($_POST["userlocationres"]) && $_POST["userlocationres"] != ""){
	   $locationarr =  json_decode($_POST["userlocationres"],true);
	    $citygoogle = "";
	    $districtgoogle = "";
	    $stategoogle = "";
	    $country  = "";
	    foreach($locationarr as $keys=>$vals){
	        if($vals["city"] !== undefined && isset($vals["city"])){
	            $citygoogle = $vals["city"];
	       }
	        if($vals["district"] !== undefined && isset($vals["district"])){
	            $districtgoogle = $vals["district"];
	       }
	       if($vals["state"] !== undefined && isset($vals["state"])){
	            $stategoogle = $vals["state"];
	       }
	        if($vals["country"] !== undefined && isset($vals["country"])){
	            $country = $vals["country"];
	       }
	    }
	    if($citygoogle !== "" && $districtgoogle !== "" ){
	        $locationres = $network->findsblocation(strtoupper($citygoogle),strtoupper($districtgoogle));
	        
	        if(!empty($locationres[0]->id)){
	            $locationid = $locationres[0]->id;
	        }else{
	            $stateres = $this->network->findsbstate($stategoogle);
	              $countryid=1;
	              $countryname = "India";

	              if(!empty($stateres[0]->id)){
	               $stateid = $stateres[0]->id;
	             }else{
	                   
	                   
	                	$db2->query('INSERT INTO state SET  name="'.$db2->e($stategoogle).'", coutry_id="'.$db2->e($countryid).'",capital="'.$db2->e($districtgoogle).'" '); 
	                	 $stateid	= (int) $db2->insert_id();
	             }
	             	$db2->query('INSERT INTO  sb_location_master SET  location="'.$db2->e($citygoogle).'", location_district="'.$db2->e($districtgoogle).'",location_capital="'.$db2->e($districtgoogle).'" ,location_state="'.$db2->e($stategoogle).'",location_country="'.$db2->e($countryname).'",state_id="'.$db2->e($stateid).'",country_id="'.$db2->e($countryid).'"  '); 
	                	 $locationid	= (int) $db2->insert_id();

	        }
	    }
	    
	}
	if(isset($_POST["userlocationid"])){
	    $locationid = $_POST["userlocationid"];
	}

if(isset($_POST['strret_useremail'])){
		$tmplang	= $db2->fetch_field('SELECT value FROM settings WHERE word="LANGUAGE" LIMIT 1');
		$tmpzone	= $db2->fetch_field('SELECT value FROM settings WHERE word="DEF_TIMEZONE" LIMIT 1');
		$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
		$lastlogin_date = time();
		$fullname = $_POST['fullname'];
        $username = $_POST['street_username'];

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
	$gender		= isset($_POST['profile_gender']) ? trim($_POST['profile_gender']) : '';
	$birthdate	= $bdate_y.'-'.str_pad($bdate_m,2,0,STR_PAD_LEFT).'-'.str_pad($bdate_d,2,0,STR_PAD_LEFT);
	$db2->query('INSERT INTO users SET  email="'.$db2->e($email).'", username="'.$db2->e($username).'",referdby="'.$db2->e($referdby).'", refer_type="'.$db2->e($type).'", password="'.$db2->e($street_userpassword).'",phone_no="'.$db2->e($phone).'",gender="'.$db2->e($gender).'", birthdate="'.$db2->e($birthdate).'", fullname="'.$db2->e($fullname).'", language="'.$tmplang.'", timezone="'.$tmpzone.'", reg_date="'.$lastlogin_date.'", reg_ip="'.$lastlogin_ip.'", lastlogin_date="'.$lastlogin_date.'", lastlogin_ip="'.$lastlogin_ip.'" ,location_id="'.$locationid.'",active=1');
    $user_id	= (int) $db2->insert_id();
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
	   	$populareres=		$db2->query('SELECT user_id FROM  street_suggestion WHERE  popular_follow_type IS NOT NULL');
	      
	  }


	
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
	
		if(!empty($_POST['pollid'])){

	$answerid = $_POST['answerid'];
	$pollid = $_POST['pollid'];
	$db2->query("INSERT INTO post_poll_votes SET 
							POLL_ID = '".$pollid."', 
							ANSWER_ID = '".$answerid."', 
							VOTER_USER_ID = '".$user_id."'", FALSE);
		}
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
$this->user->login($strret_useremail, $street_userpassword, FALSE);
$key	= md5(time().rand(0,999999));
				$_SESSION['reg_'.$key]	= (object) array (
						'network_id'	=> $this->network->id,
						'user_id'		=> $user_id,
				);
		
				$notif = new notifier();
				$notif->set_notification_obj('network', 1);
				$notif->onJoinNetwork();
	if(!empty($_POST['groups_id'])){
		    $groups_id = intval($_POST['groups_id']);
			
			$g	= $network->get_group_by_id( $groups_id );
			if( ! $g ) {
				echo 'ERROR';
				return;
			}
			if( ! $user->follow_group($g->id, TRUE) ) {
				echo 'ERROR';
				return;
			}
			if( $errmsg = $pm->getEventCallErrorMessage() ){
				echo 'ERROR:' . $errmsg;
				return;
			}
			$this->redirect($C->SITE_URL.''.$g->groupname);

	
	}
				//echo '<pre>';print_r($_SESSION);exit;
		
				//if($network_members < 1001 ){
				//	$this->redirect( $C->SITE_URL.'signup/follow/regid:'.$key);
				//}else{
				//$this->redirect($C->SITE_URL.'dashboard');

$this->redirect($C->SITE_URL.'view/post:'.$_POST['postid']);


	
	}
if($_POST['loginuser']){
	
	$login	= ($_POST['loginuser']);
	$pass		= md5(($_POST['loginpassword']));
	if(is_numeric($login)){
		$res  = $db2->query('SELECT id FROM users WHERE (phone_no="'.$login.'") AND password="'.$pass.'" AND active=1 LIMIT 1');

	}else{
		if (!filter_var($login, FILTER_VALIDATE_EMAIL) === false) {
			$res  = $db2->query('SELECT id FROM users WHERE (email="'.$login.'") AND password="'.$pass.'" AND active=1 LIMIT 1');

		}else{
			$res = $db2->query('SELECT id FROM users WHERE (username="'.$login.'") AND password="'.$pass.'" AND active=1 LIMIT 1');

		}

		
	}
echo  $res->num_rows;	

}
if(isset($_POST['fbid'])){
	$tmplang	= $db2->fetch_field('SELECT value FROM settings WHERE word="LANGUAGE" LIMIT 1');
		$tmpzone	= $db2->fetch_field('SELECT value FROM settings WHERE word="DEF_TIMEZONE" LIMIT 1');
		$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
		$lastlogin_date = time();
		$fullname = $_POST['facebookfirstname'];
        $username = $_POST['fbuser'];

		$strret_useremail = $_POST['fbemail'];
		$street_userpassword = md5($_POST['street_userpassword']);
		if(is_numeric($strret_useremail)){
	
		$phone =  $strret_useremail;
		$email ='';
		
	 }else{
		 $phone =  '';
		$email =$strret_useremail;
		
    }
	if(!empty($_POST['fbdateofbirth'])){
		$db            =explode("/",$_POST['fbdateofbirth']);
		$birthdate	= $db[2].'-'.$db[0].'-'.$db[1];
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
	$user_id	= $this->user->checkfacebookuid($strret_useremail);

	print_r($user_id);

	
	
}
