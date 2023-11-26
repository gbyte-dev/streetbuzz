<?php 
if( $this->user->is_logged ) {
		$this->redirect('home');
	}
$home3 = ($_POST);
$fullname = $_POST['fullname'];
$username = $_POST['street_username'];

$strret_useremail = $_POST['strret_useremail'];
$street_userpassword = md5($_POST['street_userpassword']);
$verificationid = $_POST['verificationid'];
$type = $_POST['businesstype'];
$referdby = $_POST['refered'];

if(is_numeric($strret_useremail)){
	
		$phone =  $strret_useremail;
		$email ='';
		$checkre   =$db2->query('select id from users where  phone_no="'.$db2->e($phone).'" ');
                $checkres  = $db2->fetch_object($checkre);
	 }else{
		 $phone =  '';
		$email =$strret_useremail;
		$checkre   =$db2->query('select id from users where  email="'.$db2->e($email).'" ');
                $checkres  = $db2->fetch_object($checkre);
		
		 
}
if($checkres->id ==''){
$tmplang	= $db2->fetch_field('SELECT value FROM settings WHERE word="LANGUAGE" LIMIT 1');
				$tmpzone	= $db2->fetch_field('SELECT value FROM settings WHERE word="DEF_TIMEZONE" LIMIT 1');
				$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
				$lastlogin_date = time();
				
				
$db2->query('INSERT INTO users SET  email="'.$db2->e($email).'", username="'.$db2->e($username).'",referdby="'.$db2->e($referdby).'", refer_type="'.$db2->e($type).'", password="'.$db2->e($street_userpassword).'",phone_no="'.$db2->e($phone).'", fullname="'.$db2->e($fullname).'", language="'.$tmplang.'", timezone="'.$tmpzone.'", reg_date="'.$lastlogin_date.'", reg_ip="'.$lastlogin_ip.'", lastlogin_date="'.$lastlogin_date.'", lastlogin_ip="'.$lastlogin_ip.'" ,active=1');
$user_id	= (int) $db2->insert_id();
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
  background: url(<?php echo $C->SITE_URL;?>static/images/bg5.jpg) no-repeat center center fixed;
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

<div class="reg-title">We’re glad you’re here, <?php echo $_POST['fullname'] ?>.</div>

<div class="col-md-7 col-lg-7 col-xs-12 reg-desc-big"><p>StreetBuzz is constantly updating most important stock market conversations and more.
</p>
<p>Tell us about your predictions on market and we’ll help you to get best.</p>

</div>

<div class="col-md-5 col-lg-5 col-xs-12 reg-desc"><img src="<?php echo $C->SITE_URL; ?>static/images/lets-go.jpg" class="img-responsive"></div>

<div class="col-md-12 col-lg-12 reg-desc-big">
<form action="<?php echo $C->SITE_URL?>home4" method="post" >
<?php 
foreach($home3 as $keys=>$vals){ ?>
<input type="hidden" name="<?php echo $keys ?>" value="<?php echo $vals;?>">
	
<?php }

?>
<input type="hidden" name="userid" value="<?php echo $user_id;?>">

<input type="submit" class="btn btn-default btn-blue" value="Let's go!">
</form>
</div>  

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

	$("#verify").keyup(function(){
		$("#verify").css("border-color","#66afe9");
		
	});	
		
	$("#homestep3").click(function(){
		var verify = $("#verify").val();
		if(verify ==""){
			$("#verify").css("border-color","red");
			return false;
			
		}
			 $.ajax({
		 
		  type:"POST",
		  data:{verify:verify,useremail:<?php echo $_POST['strret_useremail'];?>},
		   url:"<?php echo $C->SITE_URL;?>signup",

		  success:function(response){
			  if(response =="CORRECT"){
				  $("#home3").submit();
				  
			  }else{
				  $("#verifyerror").css("color","red").html("Please enter valid code.");
				  
			  }
			  
			  
			 
			  
			  
		  }
		  
	  });
		

		
	});
	
	

</script>

</html>