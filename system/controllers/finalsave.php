<?php
header('Access-Control-Allow-Origin: *'); 
if(isset($_POST['strret_useremail'])){
    $cats   = array(1,2,3,4,5,6,7,8,9,10,11,12);
	$catids = 	implode(",",$cats);
	$locationid = null;
	if(isset($_POST["userpreferlocation"]) && $_POST["userpreferlocation"]!=''){
	   $locationarr =  json_decode($_POST["userpreferlocation"],true);
	    $citygoogle = "";
	    $districtgoogle = "";
	    $stategoogle = "";
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
	    }
	    if($citygoogle !== "" && $districtgoogle !== "" ){
	        $locationres = $this->network->findsblocation($citygoogle,$districtgoogle);

	        
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
	

	if(!empty($_POST["locationid"])){
	    $locationid = $_POST["locationid"];
	}

	
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

		$db2->query('INSERT INTO users SET  email="'.$db2->e($email).'", username="'.$db2->e($username).'",referdby="'.$db2->e($referdby).'", refer_type="'.$db2->e($type).'", password="'.$db2->e($street_userpassword).'",phone_no="'.$db2->e($phone).'", gender="'.$db2->e($gender).'", birthdate="'.$db2->e($birthdate).'", fullname="'.$db2->e($fullname).'", language="'.$tmplang.'", timezone="'.$tmpzone.'", reg_date="'.$lastlogin_date.'", reg_ip="'.$lastlogin_ip.'", lastlogin_date="'.$lastlogin_date.'", lastlogin_ip="'.$lastlogin_ip.'" ,location_id="'.$locationid.'",active=1');
		
		
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
$local_id =$locationid;

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
		
				$post_data =  array(
					'user_id' => $user_id
				  );		
		call_api('api/newsperson/location/generate_timeline', $post_data);

    echo "1";

}else{
        echo "0";

}
}

?>
