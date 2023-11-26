<?php
 if(!empty($_POST)){
$unusers	    =$_POST['allVals'];
	 $user_id =$_POST['userid'];
$username = 	$db2->fetch_field('SELECT username FROM users WHERE id='.$user_id.' LIMIT 1');
	          
 $name="newusercat";
 if(!empty($unusers )){
	 foreach($unusers  as $keys=>$vals){
	 $db2->query('INSERT INTO users_invitations SET user_id="'.$user_id.'", date="'.time().'", recp_name="'.$db2->e($name).'", recp_email="'.$db2->e($vals).'", recp_is_registered=0, recp_user_id=0'); 
		 
	 			$to = $vals;
	 			$url= $C->SITE_URL;
                $SmtpServer="webmail.streetbuzz.co.in";
                $SmtpPort="25";
                $SmtpUser="srinivasa@streetbuzz.co.in";
                $SmtpPass="SRI@12345678";
		       // $to ="a.srinu.it@gmail.com";
		 	 	//$to = $vals;

	 			$url= $C->SITE_URL;
				$subject = "Join StreetBuzz";
				$message = "Hello ,\n Your friend ".$username." has invited you to join StreetBuzz - an application to connect, share and predict world   events and financial markets.\n More than 10,000 users from your town are already on StreetBuzz. So join and have fun!. \n\n Use the following link:".$url."\n \n Cheers,
Team StreetBuzz";
		 			$from ="streetbuzz@streetbuzz.co.in";
             $SMTPMail = new SMTPClient ($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $subject, $message);
$SMTPChat = $SMTPMail->SendMail();

	 
	 }
	 		 echo "0";

 }
 }
 

?>