<?php
$contact = $_POST['contact'];
$fullname = $_POST['fullname'];
$username = $_POST['street_username'];

$strret_useremail = $_POST['strret_useremail'];
$street_userpassword = md5($_POST['street_userpassword']);


$this->user->login($strret_useremail, $street_userpassword, FALSE);
 
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










?> 