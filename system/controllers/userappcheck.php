<?php
 $postdata = file_get_contents("php://input");
 $userjson = json_decode($postdata);
$action        =($userjson->action);

  //$data['username'] ='855384913';
 //$data['password'] ='satyaprakash123';
//$userdata = json_encode($data);
//$userjson = json_decode($userdata);
//$username        =$userjson->username;
//$password        =md5($userjson->password);
//$username ='nara';
 //$pass ='12345';
if(!empty($userjson)){
$login	= $this->db2->escape($username);
$pass		= $this->db2->escape($password);

if($action=="L"){
$remember = "1";
$username        =$userjson->username;
$password        =md5($userjson->password);
$login	= $this->db2->escape($username);
$pass		= $this->db2->escape($password);
$res	= $this->user->login($login ,$pass,$remember);



if(!empty($this->user)){
	$userdata = array();
$userdata['username'] = $this->user->info->username;
$userdata['fullname'] = $this->user->info->fullname;
$userdata['password'] = $this->user->info->password;
$userdata['status'] = 'TRUE';
}else{
	$userdata =array();
	$userdata['status'] = 'FALSE';


}
echo  json_encode($userdata);
}else{
	if($action =="R"){
		$tmplang	= $db2->fetch_field('SELECT value FROM settings WHERE word="LANGUAGE" LIMIT 1');
	$tmpzone	= $db2->fetch_field('SELECT value FROM settings WHERE word="DEF_TIMEZONE" LIMIT 1');
	$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
	$lastlogin_date = time();
	$fullname        =($userjson->fullname);
	$username        =($userjson->street_username);
	$strret_useremail        =($userjson->strret_useremail);
	$street_userpassword        =md5($userjson->street_userpassword);

	//$fullname = $db2->escape($_POST['fullname']);
	//$username = $db2->escape($_POST['street_username']);

	//$strret_useremail = $_POST['strret_useremail'];
	//$street_userpassword = md5($_POST['street_userpassword']);
	if(is_numeric($strret_useremail)){

		$phone =  $strret_useremail;
		$email ='';

	}else{
		$phone =  '';
		$email =$strret_useremail;

	}
	$referdby ='camp';
	$type ='person';
	$bdate_d	= $userjson->profile_birth_day;
	$bdate_m	= $userjson->profile_birth_month;
	$bdate_y	= $userjson->profile_birth_year;
	//$bdate_d	= isset($_POST['profile_birth_day'])? intval($_POST['profile_birth_day']) : '';
  // $bdate_m	= isset($_POST['profile_birth_month'])? intval($_POST['profile_birth_month']) : '';
	//$bdate_y	= isset($_POST['profile_birth_year'])? intval($_POST['profile_birth_year']) : '';
	$birthdate	= $bdate_y.'-'.str_pad($bdate_m,2,0,STR_PAD_LEFT).'-'.str_pad($bdate_d,2,0,STR_PAD_LEFT);
		//$gender		= isset($_POST['profile_gender']) ? trim($_POST['profile_gender']) : '';
	$gender	= $userjson->profile_gender;

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
		$this->user->login($strret_useremail, $street_userpassword, FALSE);
$key	= md5(time().rand(0,999999));
				$_SESSION['reg_'.$key]	= (object) array (
						'network_id'	=> $this->network->id,
						'user_id'		=> $user_id,
				);
		
		

if(!empty($this->user)){
$userdata =array();

$userdata['username'] = $username;
$userdata['email'] = $email;
$userdata['phone_no'] = $phone;


$userdata['password'] = $street_userpassword;
$userdata['user_id'] = $user_id;

$userdata['status'] = 'TRUE';
}else{
$userdata =array();

$userdata['username'] = $username;
$userdata['password'] = $street_userpassword;
$userdata['status'] = 'FALSE';
}
echo  json_encode($userdata);

		
}
}
}else{
	$userdata =array();
	$userdata['status'] = 'FALSE';
    echo  json_encode($userdata);

}


?>