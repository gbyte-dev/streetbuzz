<?php 
if( $this->user->is_logged ) {
		$this->redirect('home');
	}

if(isset($_POST['strret_useremail'])){
		if(!empty($_POST['viewpollid'])){
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
	$db2->query('INSERT INTO users SET  email="'.$db2->e($email).'", username="'.$db2->e($username).'",referdby="'.$db2->e($referdby).'", refer_type="'.$db2->e($type).'", password="'.$db2->e($street_userpassword).'",gender="'.$db2->e($gender).'", birthdate="'.$db2->e($birthdate).'",phone_no="'.$db2->e($phone).'", fullname="'.$db2->e($fullname).'", language="'.$tmplang.'", timezone="'.$tmpzone.'", reg_date="'.$lastlogin_date.'", reg_ip="'.$lastlogin_ip.'", lastlogin_date="'.$lastlogin_date.'", lastlogin_ip="'.$lastlogin_ip.'" ,active=1');
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
		
				$notif = new notifier();
				$notif->set_notification_obj('network', 1);
				$notif->onJoinNetwork();
				//echo '<pre>';print_r($_SESSION);exit;
		
				//if($network_members < 1001 ){
				//	$this->redirect( $C->SITE_URL.'signup/follow/regid:'.$key);
				//}else{
				//$this->redirect($C->SITE_URL.'dashboard');

$this->redirect($C->SITE_URL.'view/post:'.$_POST['viewpollid']);

}
	
	$home1 = $_POST;
	$userphone        = $db2->query('SELECT *  FROM settings WHERE word="'.$db2->e('USERS_PHONE_CONFIRMATION').'" ');
	$userphoneres     = $db2->fetch_object($userphone);
	
	
	if(is_numeric($_POST['strret_useremail'])){
		if($userphoneres->value == 1){
			$db2->query('INSERT INTO userverfication SET  cat="phone", sender="'.$db2->e($_POST['strret_useremail']).'",code="123" ');
			$verification = (int) $db2->insert_id();
			$_SESSION['verification'] = $verification ;
			$url ="home2";
		}else{
			$url ="home3";
		}
		
		}else{		
			$useremail        = $db2->query('SELECT *  FROM settings WHERE word="'.$db2->e('USERS_EMAIL_CONFIRMATION').'" ');
			$useremailres     = $db2->fetch_object($useremail);
			$url ="home2";
			if($useremailres->value == 1){

				$strret_code = rand();
				$db2->query('INSERT INTO userverfication SET  cat="email", sender="'.$db2->e($_POST['strret_useremail']).'",code="'.$db2->e($strret_code).'" ');
				$verification = (int) $db2->insert_id();
				$SmtpServer="webmail.streetbuzz.co.in";
                $SmtpPort="25";
                $SmtpUser="srinivasa@streetbuzz.co.in";
                $SmtpPass="SRI@12345678";
				
				
				$from ="srinivasa@streetbuzz.co.in";
				
			   
				$_SESSION['verification'] = $verification ;
				$to = $_POST['strret_useremail'];
				//$to = "srinivasa.r@purpledot.in";
				$subject = "Streetbuzz Verification code";
				$body = "Hello,
							 YoUR VERIFICATION CODE:".$strret_code."
							Regards
							Streetbuzz Team
							 ";
				$SMTPMail = new SMTPClient ($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $subject, $body);
				$SMTPChat = $SMTPMail->SendMail();

				 /* Always set content-type when sending HTML email
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				// More headers
				$headers .= 'From: <webmaster@example.com>' . "\r\n";

				if(mail($to,$subject,$message,$headers)){
				}*/
            }else{
				$url ="home3";
				
			}			

	
	}
	
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>StreetBuzz</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="icon" href="images/favicon.jpg" type="image/jpeg" sizes="24x24"> 
<link rel="stylesheet" href="<?php echo $C->SITE_URL;?>static/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $C->SITE_URL;?>static/css/sb_ui.css">

<!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<style>
body
{
  background: url(<?php echo $C->SITE_URL;?>static/images/bg2.jpg) no-repeat center center fixed;
    background-size: cover;
    width: 100%;
    height: 100%;
}
</style>
</head>
<body>
 <div class="container-fluid">

      <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="<?php echo $C->SITE_URL; ?>"><img src="<?php echo $C->SITE_URL; ?>static/images/logo.jpg" class="img-responsive"></a>
        </div>
        <!--/.nav-collapse -->
      </div>
    </nav>
     

<!-- Main content -->
<div class="container contentbox-inner">

<div class="col-md-1 col-lg-1 hidden-xs">
<!-- for spacing adjustment -->
</div>        

      
<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 main-content-box">
<!--/ Signup -->
<div class="row box-white-inner">
<form action="<?php echo $C->SITE_URL?><?php echo $url;?>" method="post" id="step2">
<?php 
foreach($home1 as $keys=>$vals){ ?>
<input type="hidden" name="<?php echo $keys?>" value="<?php echo $vals;?>">
	
<?php }

?>
<input type="hidden" name="verificationid" value="<?php echo $verification;?>">


<div class="form-group">
  <div class="col-md-7 col-xs-12">
    <select id="business" name="businesstype" class="form-control  form-inner" >
      <option value="Person">Person</option>
      <option value="Business">Business</option>
      <option value="Brand">Brand</option>
    </select>
  </div>
</div>

<div class="form-group">
  <div class="col-md-7">
  <input  name="refered" type="text" placeholder="Referred By" class="form-control form-inner" >
  </div>
    <label class="col-md-5 control-label" for="textinput">Share emailId or mobile no of the friend who invited you to 
Street Buzz. He deserves appreciation and a small gift </label>  
</div>



<div class="form-group">
  <div class="col-md-7"><input type="button" id="homestep2" class="btn btn-default btn-blue" value="Continue">
  </div>
    <label class="col-md-4 control-label" for="textinput"></label>  
</div>

<div class="form-group">
  <div class="col-md-12 notification">
 <input type="checkbox" name="ck" value="1"id="agree"  />
    By signing up, you are agree to the <a href="<?php echo $C->SITE_URL.'privacy-terms-rules/terms/'; ?>" target="_blank" class="link_blue_text">Terms of Service</a> and <a href="<?php echo $C->SITE_URL.'privacy-terms-rules/privacy/'; ?>" target="_blank" class="link_blue_text">Privacy Policy</a>,
     including <a href="#" class="link_blue_text">Cookie Use</a>. Others will be able to find you by email or mobile number when provided
  </div>

</div>



</form>
</div>      
</div>
<!--/ Start Main Content -->  



<div class="col-md-1 col-lg-1 hidden-xs">
<!-- for spacing adjustment -->
</div>  


</div>
</div> <!-- /container main -->

<!-- start - footer -->
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer-box" align="center">
<ul>
<li><a href="#">About</a></li>
<li><a href="#">Help</a></li>
<li><a href="#">Blog</a></li>
<li><a href="#">Status</a></li>
<li><a href="#">Job</a></li>
<li><a href="#">Privacy</a></li>
<li><a href="#">Cookies</a></li>
<li><a href="#">Adsinfo</a></li>
<li><a href="#">Brand</a></li>
<li><a href="#">Advertise</a></li>
<li><a href="#">Business</a></li>
<li><a href="#">Media</a></li>
<li><a href="#">Developers</a></li>
<li><a href="#">Directory</a></li>
</ul>
</div> 
<!-- end - footer -->


</body>
<script src="<?php echo $C->SITE_URL ?>static/js/jquery.js?v=3.6.0"></script>

<script type="text/javascript">
$("#business").change(function(){
			$("#business").css("border-color","#66afe9");
		});
		
		$('#agree').click(function(){
			$('#agree').css('outline-color', '');
			$('#agree').css('outline-style', '');
			$('#agree').css('outline-width', '');
			
		});
		
		
	$("#homestep2").click(function(){
		var business = $("#business").val();
		if(business ==""){
			$("#business").css("border-color","red");
			return false;
			
		}
		
		if($('#agree').attr('checked')) {
		}else{
			$('#agree').css('outline-color', 'red');
			$('#agree').css('outline-style', 'solid');
			$('#agree').css('outline-width', 'thin');
			return false;
		}
		
		if(business !=''){
			$("#step2").submit();
		}
		
		
		

		
	});
	
	

</script>

</html>
<?php } ?>