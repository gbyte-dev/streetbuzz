<?php 
if( $this->user->is_logged ) {
		$this->redirect('home');
	}

if(isset($_POST['strret_useremail'])){
	
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
			   
				$_SESSION['verification'] = $verification ;
				$to = $_POST['strret_useremail'];
				$subject = "Streetbuzz Verification code";
				$message = "Hello,<br />
							 YoUR VERIFICATION CODE:".$strret_code."
							 ";
				 // Always set content-type when sending HTML email
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				// More headers
				$headers .= 'From: <webmaster@example.com>' . "\r\n";

				if(mail($to,$subject,$message,$headers)){
				}
            }else{
				$url ="home3";
				
			}			

	
	}
	
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
    By signing up, you are agree to the <a href="#" class="link_blue_text">Terms of Service</a> and <a href="#" class="link_blue_text">Privacy Policy</a>,
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