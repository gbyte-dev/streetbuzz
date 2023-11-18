<?php

function mail_followers(){
  require_once( $C->INCPATH.'libraries/smtp/PHPMailerAutoload.php');  
	$submit	= TRUE;
		//	$userid = $newuser->id;
		 //   $email	=  $newuser->email;
		 //   $username	=  $newuser->username;
		
		   $userid=1;
		   $email='yam123ji123@gmail.com';
		   $username="yam";

                $url= "https://streetbuzz.co.in";

				$subject	="Streetbuzz test";
		
			$message  = /*"<!DOCTYPE html PUBLIC '
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
</html>*/"Sussussefully buzzed";
			
			$recipient	= $email;
			
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
		try{
			$mail->Send();
		}catch ( phpmailerException $e){
			$err = $e->getMessage();
		}
		
		return TRUE;
	
	
        }







?>