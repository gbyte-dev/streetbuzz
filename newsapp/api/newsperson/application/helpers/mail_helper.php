<?php
/**
 * @Summary: send a email function
 */
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
 error_reporting(E_ALL); */
 
  
   require_once(APPPATH.'third_party/smtp/PHPMailerAutoload.php');
 
 
function send_email($to, $subject, $message)
{       
    

        $CI =& get_instance();
        $CI->load->library('email');
        //Set the hostname of the mail server
        $config['smtp_host'] = SMTP_HOST;
        
        //Username to use for SMTP authentication
        $config['smtp_user'] = SMTP_USER;
        
        //Password to use for SMTP authentication
        $config['smtp_pass'] = SMTP_PASS;
        
        //Set the SMTP port number - likely to be 25, 465 or 587
        $config['smtp_port'] = SMTP_PORT;
        
        //Set the SMTP PROTOCOL
        $config['protocol'] = PROTOCOL;
        
        //Set the other configuration for Mail
        $config['mailpath'] = MAILPATH;
        $config['mailtype'] = MAILTYPE;
        //$config['charset'] = CHARSET;
        //$config['wordwrap'] = WORDWRAP;
        //$config['smtp_crypto'] 	= 'ssl';
      //  $config['_smtp_auth'] = TRUE;
        
        //Create a new CIMailer instance
        $email = new CI_Email();
        $email->initialize($config);
        $email->set_newline("\r\n");
        $email->clear();
        
        //Set who the message is to be sent from
        $email->from(FROM_EMAIL, FROM_EMAIL_TITLE);
        $email->to(trim($to));
        $email->subject($subject);
      //  $email->reply_to(NO_REPLY_EMAIL, NO_REPLY_EMAIL_TITLE);
        $email->message($message);
        //Send Email
       // $email->send();
       if(!$email->send()){
         //die('11111111');
       }
       else {
          // die('222222');
       }
        //$email->print_debugger();die;
        return true;
}
function send_email_new($to, $subject, $message)
{
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
	$mail->Host = SMTP_HOST;
	
	//Set the SMTP port number - likely to be 25, 465 or 587
	$mail->Port = SMTP_PORT;
	
	 //$mail->isHTML(true); // Set
	//Whether to use SMTP authentication
	$mail->SMTPAuth = TRUE;
//	$mail->IsHTML = TRUE;
	//$mail->mailtype = 'html';	
 	$mail->SMTPSecure = 'tls';
	     $mail->isHTML(true); 
	//Username to use for SMTP authentication
	$mail->Username = SMTP_USER;
	
	//Password to use for SMTP authentication
//	$mail->isHTML(true);
	
	$mail->Password = SMTP_PASS;
	
	//Set who the message is to be sent from
	$mail->setFrom(FROM_EMAIL, FROM_EMAIL_TITLE);
	
	//Set an alternative reply-to address
	//Set who the message is to be sent to
	
	$mail->addAddress($to, FROM_EMAIL_TITLE);
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
	    // $D->error =TRUE;
	   return false;
	  	//$this->redirect($C->SITE_URL.'/signin/forgotten/errorkey:'.$key);
	} else {
	     return true;
	   	//$this->redirect($C->SITE_URL.'/signin/forgotten/newupdatekey:'.$key);
	}
		  
}