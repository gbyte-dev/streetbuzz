<?php 
if( $this->user->is_logged ) {
		$this->redirect('home');	
}
 $home2 = ($_POST);
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
  background: url(<?php echo $C->SITE_URL;?>static/images/bg4.jpg) no-repeat center center fixed;
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

<div class="reg-title">Enter <?php if(is_numeric($_POST['strret_useremail'])){ ECHO "phone";}else{ echo "email";}?> verification code</div>

<div class="reg-desc">You should receive a text message from us with  a short verification code. 
Please enter it below.</div>

<form action="<?php echo $C->SITE_URL?>home3" method="post" id="home3">
<?php 
foreach($home2 as $keys=>$vals){ ?>
<input type="hidden" name="<?php echo $keys ?>" value="<?php echo $vals;?>">
	
<?php }

?>
<div class="form-group">
  <div class="col-md-7">
  <input id="verify" name="verify" type="text" placeholder="Verification code" class="form-control  form-inner">
  </div>
</div>


<div class="form-group">
  <div class="col-md-7"><input type="button" class="btn btn-default btn-blue" id="homestep3" value="Verify the code">
  </div>
    <label class="col-md-4 control-label" for="textinput"></label>  
</div>
<div id="verifyerror"></div>

<div class="form-group">
  <div class="col-md-12 notification">
Haven’t received the text message after a minute? <a href="#" class="link_blue_text">Enter your phone number or email again.</a>
  </div>

</div>
 <input type="hidden" name="user" id="st_user" value="<?php echo $_POST['strret_useremail'];?>">




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

	$("#verify").keyup(function(){
		$("#verify").css("border-color","#66afe9");
		
	});	
		
	$("#homestep3").click(function(){
		var verify = $("#verify").val();
		if(verify ==""){
			$("#verify").css("border-color","red");
			return false;
			
		}
		var userem = $("#st_user").val();
			 $.ajax({
		 
		  type:"POST",
		 data:{verify:verify,useremail:userem},

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
