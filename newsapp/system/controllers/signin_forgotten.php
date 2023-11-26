<?php
 
	if( $this->network->id && $this->user->is_logged ) {
		$this->redirect('dashboard');
	}
	
	$this->load_langfile('outside/signin.php');
	$this->load_langfile('outside/global.php');
	$this->load_langfile('outside/signup.php');
	
	//require_once( $C->INCPATH.'helpers/func_images.php' );
	require_once( $C->INCPATH.'helpers/func_captcha.php' );
	require_once( $C->INCPATH.'helpers/func_recaptchalib.php');
	require_once( $C->INCPATH.'libraries/securimage/securimage.php');

	$page_title	= $this->lang('signinforg_page_title', array('#SITE_TITLE#'=>$C->SITE_TITLE));
	
	$securi = new Securimage();
	$securi->setNamespace('forgotten_pass');
	
	$have_key	= FALSE;

 	if( $this->param('newupdatedkey') )
	{
	  
	    $have_key	= TRUE;
		$error_key	= FALSE;
		$key	= $this->db2->e(trim($this->param('newupdatedkey')));
		$this->db2->query('SELECT id,email FROM users WHERE active=1 AND pass_reset_key="'.$key.'" AND pass_reset_valid>="'.time().'" LIMIT 1');
		$userdata =  $this->db2->fetch_object();
		$D->final_user_id = $userdata->id; 
		 if( empty($userdata->id)){
		   	$this->redirect('signin/forgotten');
		    
		}

		if( isset($_POST['pass1'], $_POST['pass2']) ) {
		    $pass	= md5($_POST['pass1']);
		    $userid = 	$D->final_user_id;
		    $email  = $userdata->email;
		    $rememberme =0;
			$this->db2->query('UPDATE users SET password="'.$this->db2->e($pass).'", pass_reset_key="", pass_reset_valid="" WHERE id="'.$userid.'" LIMIT 1');
		    $userLogin	= $this->user->login($email, $pass, $rememberme);
		    $this->redirect($C->SITE_URL.'dashboard');

		//	$this->redirect('signin/pass:changed');
		}

	
	}
	
	if( $this->param('newupdatekey') )
	{
		$have_key	= TRUE;
		$error_key	= FALSE;
		
		$key	= $this->db2->e(trim($this->param('newupdatekey')));
		$this->db2->query('SELECT id,otp_code FROM users WHERE active=1 AND pass_reset_key="'.$key.'" AND pass_reset_valid>="'.time().'" LIMIT 1');

	    $newres=  $this->db2->fetch_object();
	    $D->final_user_id = $newres->id;
	    $D->otp_code = $newres->otp_code;
	    if( empty($newres->id)){
		   	$this->redirect('signin/forgotten');
		    
		}

	
		if(isset($_POST['otpcheck'])){
		  	  $this->redirect($C->SITE_URL.'/signin/forgotten/newupdatedkey:'.$key);

		}else{
		  $D->otpchecked=FALSE;

		    
		}
		if( ! $u = $this->db2->fetch_object() ) {
			$error_key	= TRUE;
		}
		else {

			$submit	= FALSE;
			$error	= FALSE;
			$errmsg	= '';
			
			if( isset($_POST['pass1'], $_POST['pass2']) ) {
				$submit = TRUE;
				
				$pass	= trim($_POST['pass1']);
				if( strlen($pass)<5 ) {
					$error	= TRUE;
					$errmsg	= 'signinforg_err_passwdlen';
				}
				elseif( $pass != trim($_POST['pass2']) ) {
					$error	= TRUE;
					$errmsg	= 'signinforg_err_passdiff';
				}
				else {
					$pass	= md5($pass);
					$this->db2->query('UPDATE users SET password="'.$this->db2->e($pass).'", pass_reset_key="", pass_reset_valid="" WHERE id="'.$u->id.'" LIMIT 1');
					$u	= $this->network->get_user_by_id($u->id, TRUE);
					$this->redirect('signin/pass:changed');
				}
			}
		}
	}
	else
	{
		$submit	= FALSE;
		$error	= FALSE;
		$errmsg	= '';
		$email	= '';
		//Here we updated the key value of users and sent emailsto users 
		if(isset($_POST['updateemail'])){
			$submit	= TRUE;
			$userid =$_POST['updateuserid'];
		    $email	= (trim($_POST['updateemail']));
		   
		    require_once( $C->INCPATH.'libraries/smtp/PHPMailerAutoload.php');

			if( ! is_valid_email($email) ) {
				$error	= TRUE;
				$errmsg	= 'signinforg_err_email';
			}
				$key		= md5('akey_'.$userid.'_'.(rand().time().rand()));
				$valid	= time() + 48*60*60;
				$otp_code = mt_rand(1000, 9999);
				$this->db2->query('UPDATE users SET pass_reset_key="'.$key.'",pass_reset_valid="'.$valid.'",otp_code="'.$otp_code.'" WHERE id="'.$userid.'" LIMIT 1');
				$D->recover_link	= $C->SITE_URL.'signin/forgotten/newupdatekey:'.$key;
				//$this->redirect($D->recover_link);

				$subject	="Streetbuzz forgot password";
			//$message =" <div>google</div>Please click the  following recovery password  link \n".$D->recover_link."\n Regards, \n StreetBuzz";
			//$message ='Please click the following Reset password link '.$D->recover_link.' \n Regards, \n StreetBuzz';
			$message  = "Please use  the following OTP Code  \n".$otp_code."\n Regards, \n StreetBuzz";
			//$message ="<div>hiii</div>";
				//$subject	= $C->OUTSIDE_SITE_TITLE.' - '.$this->lang('cnt_frm_sbj');
			//$message	="HIII";
			//$message	.=$D->recover_link;
		//	$SmtpServer="120.138.9.201";
         //   $SmtpPort="25";
          //  $SmtpUser="administrator@streetbuzz.co.in";
          //  $SmtpPass="Hanuman3#";
		//	$sender	="administrator@streetbuzz.co.in";
			$recipient	= $_POST['updateemail'];
		//	$SMTPMail = new SMTPClient ($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $sender, $recipient, $subject, $message);
        //     $SMTPChat = $SMTPMail->SendMail();
             	//Create a new PHPMailer instance
	$mail = new PHPMailer();
	//Tell PHPMailer to use SMTP
	$mail->isSMTP();
	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 0;

	//Ask for HTML-friendly debug output
//	$mail->Debugoutput = 'html';
	
	//Set the hostname of the mail server
	$mail->Host = "120.138.9.201";
	
	//Set the SMTP port number - likely to be 25, 465 or 587
	$mail->Port = 25;
	
	//Whether to use SMTP authentication
	$mail->SMTPAuth = TRUE;
	$mail->SMTPSecure = '';
	
	//Username to use for SMTP authentication
	$mail->Username = "administrator@streetbuzz.co.in";
	
	//Password to use for SMTP authentication
//	$mail->isHTML(true);
	
	$mail->Password = "Hanuman3#";
	
	//Set who the message is to be sent from
	$mail->setFrom('administrator@streetbuzz.co.in', 'streetbuzz');
	
	//Set an alternative reply-to address
	//Set who the message is to be sent to
	
	$mail->addAddress($recipient, 'Streetbuzz');
	//Set the subject line
	$mail->Subject = $subject;
	

	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
   //	$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
	//Replace the plain text body with one created manually
	$mail->Body = $message;

	//$mail->AltBody = 'This is a plain-text message body';
	//Attach an image file
	// $mail->addAttachment('images/phpmailer_mini.png');

	//send the message, check for errors
	if (!$mail->send()) {
	     $D->error =TRUE;
	  	$this->redirect($C->SITE_URL.'/signin/forgotten/errorkey:'.$key);
	} else {
	   	$this->redirect($C->SITE_URL.'/signin/forgotten/newupdatekey:'.$key);
	}
		
//
		
			
			

				
			



			
		}

		
		if( isset($_POST['email']) ) {
			$submit	= TRUE;
			$email	= (trim($_POST['email']));
			if( empty($email) ) {
				$error	= TRUE;
				$errmsg	= 'signinforg_err_emailphone';
			}
			$u	= FALSE;
			if(is_numeric($_POST['email'])){
				$phoneid ='OR phone_no="'.$_POST['email'].'" ';
				
			}else{
				$phoneid ='';
			}

				$this->db2->query('SELECT id, active FROM users WHERE email="'.$this->db2->e($email).'" OR username="'.$this->db2->e($email).'" '.$phoneid.'  LIMIT 1');

				if( ! $u = $this->db2->fetch_object() ) {
					$error	= TRUE;
					$errmsg	= 'signinforg_err_email2';
				}

				elseif( $u->active == "0" ) {
					$error	= TRUE;
					$errmsg	= 'signinforg_err_banned';
				}
				elseif( ! $u = $this->network->get_user_by_id($u->id) ) {
					$error	= TRUE;
					$errmsg	= 'signinforg_err_email2';
				}
				$D->error =$error;

		
			
			
			
			if( ! $error ) {
				if( $this->user->is_logged ) {
					$this->user->logout();
				}
				$key		= md5('akey_'.$u->id.'_'.(rand().time().rand()));
				$valid	= time() + 48*60*60;
			

				$this->db2->query('UPDATE users SET pass_reset_key="'.$key.'", pass_reset_valid="'.$valid.'"  WHERE id="'.$u->id.'" LIMIT 1');
				$D->recover_link	= $C->SITE_URL.'signin/forgotten/newkey:'.$key;
				$this->redirect($D->recover_link);
				//$this->load_langfile('email/signin.php');
				//$subject	= $this->lang('signinforg_email_subject', array('#SITE_TITLE#'=>$C->SITE_TITLE));
				//$msgtxt	= $this->load_single_block('email/signinforg_txt', FALSE);
				//$msghtml	= $this->load_single_block('email/signinforg_html', FALSE);
				//do_send_mail_html($email, $subject, $msgtxt, $msghtml);
			}
		}
	}
	$tpl = new template( array('page_title' => $this->lang('signinforg_page_title', array('#SITE_TITLE#'=>$C->SITE_TITLE))) );
	$mail->Body = "<i>Mail body in HTML</i>";

	$show_step1_form = TRUE;
	
	if( $submit && $error ){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->errorMessageforgot($this->lang('signinforg_err'), $this->lang($errmsg) ) );
	}else if( $submit && !$error ){
		if( $have_key ){
			$tpl->layout->setVar('main_content_placeholder', $tpl->designer->okMessage($this->lang('signinforg_alldone_ttl'), $this->lang('signinforg_alldone_txt') ) );
		}else{
			$show_step1_form = FALSE;
			$tpl->layout->setVar('main_content_placeholder', $tpl->designer->okMessage($this->lang('signinforg_sentmail_ttl'), $this->lang('signinforg_sentmail_txt', array('#EMAIL#'=>$email)) ) );
		}
	}

	if( !$have_key ){
		
		if( $this->param('newkey') )
	{
		$nekey = $this->param('newkey');
		$this->db2->query('SELECT id,email,username FROM users WHERE active=1 AND pass_reset_key="'.$nekey.'" AND pass_reset_valid>="'.time().'" LIMIT 1');
		$newuser = $this->db2->fetch_object();
		if( empty($newuser->id)){
		   	$this->redirect('signin/forgotten');
		    
		}
		$D->userid = $newuser->id;
		$D->email = $newuser->email;
		$D->username = $newuser->username;
			
        	if(isset($newuser->email)){
			$submit	= TRUE;
			$userid = $newuser->id;
		    $email	=  $newuser->email;
		    $username	=  $newuser->username;
		   
		    require_once( $C->INCPATH.'libraries/smtp/PHPMailerAutoload.php');

			if( ! is_valid_email($email) ) {
				$error	= TRUE;
				$errmsg	= 'signinforg_err_email';
			}
				$key		= md5('akey_'.$userid.'_'.(rand().time().rand()));
				$valid	= time() + 48*60*60;
				$otp_code = mt_rand(100000, 999999);
				$this->db2->query('UPDATE users SET pass_reset_key="'.$key.'",pass_reset_valid="'.$valid.'",otp_code="'.$otp_code.'" WHERE id="'.$userid.'" LIMIT 1');
				//print_r($data);
				//die('------');
				$D->recover_link	= $C->SITE_URL.'signin/forgotten/newupdatekey:'.$key;
				//$this->redirect($D->recover_link);
                $url= $C->SITE_URL;

				$subject	="Streetbuzz forgot password";
			//$message =" <div>google</div>Please click the  following recovery password  link \n".$D->recover_link."\n Regards, \n StreetBuzz";
			//$message ='Please click the following Reset password link '.$D->recover_link.' \n Regards, \n StreetBuzz';
			$message  = "<!DOCTYPE html PUBLIC '
<html xmlns='http://www.w3.org/1999/xhtml'>
  <head>
    <meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=no'>  
    <meta http-equiv='content-type' content='text/html; charset=utf-8'>
    <meta http-equiv='content-type' content='text/html; charset=ISO-8859-15'>
    <title>Streetbuzz</title>
    
    <style>
      body{font-family:Arial, sans-serif;font-size:14px;background-color: #f2f2f2}
      .main-table{width: 600px;background-color: #ffffff;margin: 0 auto;border-bottom: 5px solid #0CBFEB!important;border: 1px solid #e8e8e8;}

      td{
        border:none;
      }
      tr.logo-tr {height: 44px;}
      tr.logo-tr a{color: #000000;font-size: 13px;  line-height: 12px;text-decoration: none;}
      .logo-td{width: 25%;padding: 0 30px;border-bottom:1px solid  #e8e8e8 !important;}  
      .middle-table{width: 90%;margin: 0 auto;}
      .middle-table h5{ font-size: 15px;} 
      .text-center{text-align: center;}
      .text-left{text-align: left;}
      .footer-tr{background-color: #F2F2F2;}
      .bootom{padding: 25px 15px;}
      .copyright-p{color: #555555; font-size: 13px;line-height: 19px;}
      .copyright-p span{font-size: 13px;font-weight: 600;}
      .copyright-p img{vertical-align: middle;margin-left: 7px;}
      .text1 {color: black;font-weight: bold;font-size: 14px;font-weight: 800;line-height: 45px;text-align: center;}
      .text2 {color: black;font-size: 14px;font-weight: 800;line-height: 45px;text-align: center;}
      .text4 {text-align:center;color: #555555;font-size: 10px;  line-height: 21px;}      
    
      .padding-5{padding:  5px 10px ;}
      .green-color{color: #5DBE7D;}
      }

        @media (max-width: 767px) {
        .main-table{  width: 100%;}
      }
    </style>
  </head>
  <body  style='font-family:Arial, sans-serif'>
    <table cellpadding='0' cellspacing='0' class='main-table'>
        <!-- Start header section-->
        <tr class='logo-tr'>
            <td colspan='3' class='logo-td'>
                <a><img src=' ".$url."/static/images/logo.png' alt='dfdgjh'></img></a>
            </td>
        </tr>
        
<tr> 
    <td style='background-color:#ffffff;'></td>
</tr>
<tr>
    <td style='padding:0 30px 0 30px;background-color:#ffffff;'>
        <p style='font-size:18px;font-family:Calibri;margin:0px;padding:0;font-weight:bold;line-height:50px;'>Hi ".$username."</p>
    </td>
</tr>
<tr>
    <td style='background-color:#ffffff;'></td>
</tr>

<tr>
    <td style='padding:0 30px 10px 30px;background-color:#ffffff;font-family:Arial, Helvetica, sans-serif;font-size:14px;'>
       Please use this OTP code <span class='green-color' ><b>".$otp_code."</b></span> to set a new password of your choice.</td>
</tr>

<tr>
    <td style='padding:0 30px 10px 30px;background-color:#ffffff;font-family:Arial, Helvetica, sans-serif;font-size:14px;'>
        Cheers,<br>
        StreetBuzz Team
    </td>
</tr>
<tr>
    <td style='background-color:#ffffff;'></td>
</tr>
<tr>
    <td style='background-color:#ffffff;'></td>
</tr>
<tr>
    <td style='background-color:#ffffff;'></td>
</tr>
<tr>
    <td></td>
</tr>
<!--Start footer section-->
        <tr class='footer-tr'>
            <td colspan='3' class='text-center'>
                <div class='bootom'>                    
                    <p class='copyright-p'>&copy; <?php echo date('Y'); ?> <span><span>streetbuzz.co.in.</span></span> All Rights Reserved </p>
                </div>
            </td>
        </tr>
        <!--End footer section-->
	</table>
</body>
</html>";
			//$message ="<div>hiii</div>";
				//$subject	= $C->OUTSIDE_SITE_TITLE.' - '.$this->lang('cnt_frm_sbj');
			//$message	="HIII";
			//$message	.=$D->recover_link;
		//	$SmtpServer="120.138.9.201";
         //   $SmtpPort="25";
          //  $SmtpUser="administrator@streetbuzz.co.in";
          //  $SmtpPass="Hanuman3#";
		//	$sender	="administrator@streetbuzz.co.in";
			$recipient	= $email;
		//	$SMTPMail = new SMTPClient ($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $sender, $recipient, $subject, $message);
        //     $SMTPChat = $SMTPMail->SendMail();
             	//Create a new PHPMailer instance
	$mail = new PHPMailer();
	//Tell PHPMailer to use SMTP
	$mail->isSMTP();
	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 0;

	//Ask for HTML-friendly debug output
//	$mail->Debugoutput = 'html';
	
	//Set the hostname of the mail server
	$mail->Host = "smtp.gmail.com";
	
	//Set the SMTP port number - likely to be 25, 465 or 587
	$mail->Port = 25;
	
	 //$mail->isHTML(true); // Set
	//Whether to use SMTP authentication
	$mail->SMTPAuth = TRUE;
	//$mail->mailtype = 'html';
	$mail->isHTML(true);
	$mail->SMTPSecure = 'tls';
	
	//Username to use for SMTP authentication
	$mail->Username = "shubham18822@gmail.com";
	
	//Password to use for SMTP authentication
//	$mail->isHTML(true);
	
	$mail->Password = "szeivapldlliexyj";
	
	//Set who the message is to be sent from
	$mail->setFrom('shubham18822@gmail.com', 'streetbuzz');
	
	//Set an alternative reply-to address
	//Set who the message is to be sent to
//	$recipient="vivekkumarpatel462626@gmail.com";
	$mail->addAddress($recipient, 'Streetbuzz');
	//Set the subject line
	$mail->Subject = $subject;
	

	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
   //	$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
	//Replace the plain text body with one created manually
	$mail->Body = $message;

	//$mail->AltBody = 'This is a plain-text message body';
	//Attach an image file
	// $mail->addAttachment('images/phpmailer_mini.png');

	//send the message, check for errors
	if (!$mail->send()) {
	  //  die('11111');
	    $D->error =TRUE;
	   	$this->redirect($C->SITE_URL.'/signin/forgotten/errorkey:'.$key);
	} else {
	      // die('22222');
	   	$this->redirect($C->SITE_URL.'signin/forgotten/newupdatekey:'.$key);
	}
        	}
		
		$tpl->layout->useBlock('user-forgotten-pass-step3');

		$tpl->layout->block->save('main_content');
		
	}else{
		

		
		if( $show_step1_form ){
		    if( $this->param('errorkey')){
		        $D->error =TRUE;
		        
		    }
			$tpl->layout->useBlock('user-forgotten-pass-step1');
			$tpl->layout->block->setVar('user_forgotten_email_value', isset($email)? htmlspecialchars($email) : '');
			$tpl->layout->block->setVar('captcha_image', $captcha_html);
			$tpl->layout->block->setVar('captcha_key', $captcha_key);
			
				
			$tpl->layout->block->setVar('autofocus','');
			if(isset($wrong_captcha)){
				$tpl->layout->block->setVar('autofocus','data-status="focus"');
			}
			
			$tpl->layout->block->save('main_content');
		}
	}
	
	}else{
	    if($this->param('newupdatedkey')){
	       $tpl->layout->useBlock('user-forgotten-pass-step4');

	      $tpl->layout->block->save('main_content');
	        
	    }else{
		$tpl->layout->useBlock('user-forgotten-pass-step2');

		$tpl->layout->block->save('main_content');
	    }
	}
	
	$tpl->display();
	function sentmail(){
	    
	    
	}
?>