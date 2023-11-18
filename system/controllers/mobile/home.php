<?php

	if( $this->user->is_logged ) {
		$this->redirect('dashboard');
	}

	$this->load_langfile('inside/global.php');
	$this->load_langfile('outside/home.php');
	$this->load_langfile('outside/signin.php');

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>StreetBuzz</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="icon" href="<?php echo $C->SITE_URL; ?>static/images/favicon.jpg" type="image/jpeg" sizes="24x24"> 
<link rel="stylesheet" href="<?php echo $C->SITE_URL;?>static/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $C->SITE_URL;?>static/css/sb_ui.css">
<!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<style>
body
{
  background: url(<?php echo $C->SITE_URL ?>static/images/bg1.jpg) no-repeat center center fixed;
    background-size: cover;
    width: 100%;
    height: 100%;
}
</style>
</head>
<body>
<div id="signup">
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
      <div class="container contentbox">
      <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
      <h1>Welcome to Street Buzz</h1>
      <h3>Connect, Share and Predict market events as you see</h3>
<p>&nbsp;</p>
<h4>Follow experts-in-action on shares, bonds, commodities, currencies, and real estate</h4>
<p>&nbsp;</p>
<h4>Buzz on market events and their impact on prices</h4>
<p>&nbsp;</p>
<h4>Predict price movements, become experts and earn plus more...</h4>
      </div>
      
<!--/ Right Box -->
<div class="col-xs-12 col-sm-8 col-md-4 col-lg-4 login-signup pull-right">
<!--/ Login -->
<div class="box-white row">

<form action="<?php echo $C->SITE_URL?>signin" method="post">
<input type="text" class="form-control form" placeholder="Phone email or Username" id="email" name="email" data-status="focus" value="<?php if(isset($_COOKIE['username'])){ echo $_COOKIE['username'];} ?>">
<div class="col-xs-8 col-sm-10 col-md-9 col-lg-9 login_pwd_split">
<input type="password" class="form-control form" placeholder="Password" id="password" name="password" value="<?php if(isset($_COOKIE['userpassword'])){ echo $_COOKIE['userpassword'];} ?>"></div>
<div class="btn_login_right"><input type="submit" class="btn btn-info" value="Log in"></div>
<div class="col-xs-8 col-sm-10 col-md-9 col-lg-9 login_pwd_split"  style="color:red;"><?php if(!empty($_GET)){if($_GET['message'] == 1 || $_GET['message'] == 2 ){ echo "Please enter input fields"; }
if($_GET['message'] == 3  ){ echo "Please enter valid data"; }

}  ?></div>

<div class="pull-left"><input type="checkbox" name="rememberme"  id="rememberme"  value="1" <?php if(isset($_COOKIE['username'])){  echo "checked";} ?> >
<span class="grey_text">Remember me <a href="<?= $C->SITE_URL ?>signin/forgotten" class="link_blue_text">Forgot password?</a></div>
</form>
</div>
<!--/ Signup -->
<div class="box-white row">
<p class="signup_title_bold">New to StreetBuzz? Sign Up</p>
<form action="<?php echo $C->SITE_URL?>home1"  method="POST" id="home">
<input type="text" id="fullname"class="form-control form" placeholder="Full name" name="fullname" required>

<input type="text" id="useremail" class="form-control form" placeholder="Email or Phone number" required name="strret_useremail">
<div id="emailerror"></div>

<input type="password" id="userpassword" class="form-control form" placeholder="Password" required name="street_userpassword" >

<input type="text" id="username"class="form-control form" placeholder="Username" required name="street_username"  >
<div id="usererror"></div>


<p class="pull-right"><input type="button" class="btn btn-warning" id="sub" value="Signup for SB"></p>
</form>
</div>      
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

</div>
</body>
<script src="<?php echo $C->SITE_URL ?>static/js/jquery.js?v=3.6.0"></script>

<script type="text/javascript">
$("#fullname").keyup(function(){
			$("#fullname").css("border-color","#66afe9");
		});
		$("#useremail").keyup(function(){
			$("#useremail").css("border-color","#66afe9");
			
		});
		$("#userpassword").keyup(function(){
			$("#userpassword").css("border-color","#66afe9");
			
		});
		$("#username").keyup(function(){
			$("#username").css("border-color","#66afe9");
			
		});
			
	$("#sub").click(function(){
		var full = $("#fullname").val();
		if(full ==""){
			$("#fullname").css("border-color","red");
			return false;
			
		}
		
		
		
		var email = $("#useremail").val();
		if(email ==""){
			$("#useremail").css("border-color","red");
			return false;
		}
				if($.isNumeric(email)){
		}else{
			if (!ValidateEmail(email)) {
             $("#emailerror").css("color","red").html("Please enter valid email");
			return false;
        }
        else {
        }
		}
		 function ValidateEmail(email) {
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(email);
    };

		
		var username = $("#username").val();
		$("#useremail").keyup(function(){
			 $("#emailerror").hide();
			
		});
		var userpassword = $("#userpassword").val();
		if(userpassword ==""){
			$("#userpassword").css("border-color","red");
			return false;
		}
		$("#userpassword").keyup(function(){
			$("#userpassword").css("border-color","green");
			
		});
		$("#useremail").keyup(function(){
			$("#useremail").css("border-color","green");
			 $("#emailerror").hide();
			
		});
		if(username ==''){
			$("#username").css("border-color","red");
			return false;
			
		}
		
		$("#username").keyup(function(){
			$("#username").css("border-color","green");
			 $("#usererror").hide();
			
		});
		if( full !='' && email !='' && username !=''){
			
		 $.ajax({
		 
		  type:"POST",
		  data:{email:email},
		   url:"<?php echo $C->SITE_URL;?>signup",

		  success:function(response){
			  if(response.trim() !="OK"){
				   $("#emailerror").show();
				  $("#emailerror").css("color","red").html(response);
				  return false;
				  
				  
			  }else{
				  $.ajax({
		 
		  type:"POST",
		  data:{username:username},
		   url:"<?php echo $C->SITE_URL;?>signup",

		  success:function(response){
			  if(response.trim() !="OK"){
				  $("#usererror").show();
				  $("#usererror").css("color","red").html(response);
				   return false;
				  
				  
			  }else{
				  $("#home").submit();
				  
				  
			  }
			 
			  
			  
		  }
		  
	  });
				  
				  
			  }
			 
			  
			  
		  }
		  
	  });
	}	

		
	});
	
	

</script>
</html>
     
   