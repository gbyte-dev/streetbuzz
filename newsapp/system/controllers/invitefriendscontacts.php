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
    width: 100%;
    height: 100%;
    background: #f4f4f4;
}
</style>
</head>
<body>
 <div class="container-fluid">

      <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
  <!--         <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button> -->
                       <a href="<?php echo $C->SITE_URL; ?>"><img src="<?php echo $C->SITE_URL; ?>static/images/streetbuzz.jpg" class="img-responsive  center-block"></a>

        </div>
        <!--/.nav-collapse -->
      </div>
    </nav>
     

<!-- Main content -->
<div class="container contentbox-inner">

      

      
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 main-content-box">
<!--/ Signup -->
<div class="row box-white-inner">

<div class="reg-title">Find people you know</div>

<div class="col-md-12 col-lg-12 col-xs-12 reg-desc-big">
<p>Find people you know so you can see their updates. Your contacts are safe with us!</p>
</div>



<div class="col-md-12 col-lg-12 col-xs-12 prof-main-box">

<form action="<?php echo $C->SITE_URL; ?>home8" method="POST">
<input type="hidden" name="contact" value="gmail">

<?php 
 if( preg_match( "#iPhone#i", $_SERVER['HTTP_USER_AGENT'] )  || preg_match( "#iPod#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Android#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Phone#i", $_SERVER['HTTP_USER_AGENT'] )) { 
					    $whats =' <p><a data-text="Streetbuzz" data-link="'.$C->SITE_URL.'" class="whatsapp w3_whatsapp_btn w3_whatsapp_btn_large"><input type="button" value="Share on WhatsApp" class="btn btn-default sign-in-whatsapp whatsapp"></a></p>';
	     echo $whats;
					  }else{ ?>
	<p>
<input type="button" value="Invite Facebook Friends" onclick="FacebookInviteFriends();" class="btn btn-default sign-in-facebook">
</p>
	<?php } ?>
	 


<input type="button" value="Invite Gmail Friends" class="btn btn-default sign-in-gmail" onclick="auth();" >

</p>
</form>

<form action="<?php echo $C->SITE_URL; ?>dashboard" method="POST">

<button class="btn btn-primary btn-lg btn-done" id=""> Done! Go to Dashboard</button>
</form>

</div><!--/ prof-main-box -->




			<div id="gmail" class="modal fade" ></div>

<div id="outlook">

</div>

  <div class="col-md-12 col-lg-12 col-xs-12 notification">
Choosing a service will open a window for you to log in security and import your contacts to StreetBuzz. You’ll only find users who have allowed their accounts to be found by email address. We won’t email anyone without your consent, but we may use contact information to make Who to follow suggestions. You can remove your contacts from StreetBuzz at any time..</a>
  </div>

</div>      
</div>
<!--/ Start Main Content -->  


</div>
</div> <!-- /container main -->

<!-- start - footer -->
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer-box" align="center">
<ul>
<li>&copy; Copyright 2016.</li>
<li>&nbsp;</li>
<li><a href="<?php echo $C->SITE_URL ?>privacy-terms-rules/privacy/index.html" target="_blank">Privacy</a></li>
<li><a href="<?php echo $C->SITE_URL ?>privacy-terms-rules/terms/index.html" target="_blank">Terms</a></li>
</ul>
</div> 
<!-- end - footer -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    
<script src="https://apis.google.com/js/client.js"></script>
   <script src="http://connect.facebook.net/en_US/all.js">   </script>
   <script src="<?php echo $C->SITE_URL;?>static/js/whatsup.js">   </script>
  <script src="<?php echo $C->SITE_URL;?>themes/FishingEnthusiastTheme/js/bootstrap.min.js"></script>



 <script>
     FB.init({
 appId:'237819566613317', cookie:true, status:true, xfbml:true
 });
function FacebookInviteFriends()
{
FB.ui({ method: 'send',name: 'A blog about Customer Engagement & Business Collaboration software: use cases, best practices and tips.  Engagement/Inbound Marketing and Content Marketing.',link: 'http://streetbuzz.co.in/newsapp', description: 'A blog about Customer Engagement & Business Collaboration software: use cases, best practices and tips.  Engagement/Inbound Marketing and Content Marketing.', picture: 'http://streetbuzz.co.in/dev/streetbuzz_serverfinal/static/images/logo.jpg'});
}


     

   </script>
<?php
$user_id = $_SESSION['NETWORKS_USR_DATA'][1]['LOGGED_USER']->id;

?>


<script type="text/javascript">
   function auth() {
     var config = {
       'client_id': '751188755955-ht9r0e381893amidh53ecojf75kis3ls.apps.googleusercontent.com',
       'scope': 'https://www.google.com/m8/feeds'
     };
     gapi.auth.authorize(config, function() {
       fetch(gapi.auth.getToken());  
      
     });
   }
 
   function fetch(token) {
   var userid = <?php echo $user_id;  ?>;
  //  var password= "<?php  echo  $street_userpassword;?>";
     $.ajax({
     url: "https://www.google.com/m8/feeds/contacts/default/full?access_token=" + token.access_token + "&alt=json",
     dataType: "jsonp",
     success:function(data) {
     $.ajax({
					async: true, 
		           cache: false,
					dataType : "html",
					type:"POST",
					data:{data:data,userid:userid},
					url:"<?php  echo $C->SITE_URL;?>invitegmailparse",

					success:function(msg){

						 $('#gmail').html('');
						 $('#gmail').html(msg);
						 $('#gmail').modal('show'); 
						
						
					}
				});
     
     
    
     
                              // display all your data in console
              // console.log(JSON.stringify(data));
     }
 });
 } 
</script>

<style>
.btn-primary {
    color: #fff;
    background-color: #337ab7;
    border-color: #2e6da4;
}
.btn-primary:hover {
    color: #fff;
    background-color: #286090;
    border-color: #204d74;
}
@media screen and (max-width: 480px) {
.main-content-box {
  margin-top:0px;
} 
}
.sign-in-facebook
  {
    background-image: url(<?php echo $C->SITE_URL;?>static/images/fb-invite.png);
    background-position: -9px -7px;
    background-repeat: no-repeat;
    background-size: 39px 43px;
    padding-left: 41px;
    color: #005b7f;
    font-weight: bold;
	width: 220px;
  }
  .sign-in-facebook:hover
  {
    background-image: url(<?php echo $C->SITE_URL;?>static/images/fb-invite.png);
  }
  .sign-in-gmail
  {
    background-image: url(<?php echo $C->SITE_URL;?>static/images/gmail-invite.png);
    background-position: -1px 0px;
    background-repeat: no-repeat;
    background-size: 33px 35px;
    padding-left: 41px;
    color: #005b7f;
    font-weight: bold;	  
    margin-top: 20px;
	width: 220px;
  }
  .sign-in-gmail:hover
  {
    background-image: url(<?php echo $C->SITE_URL;?>static/images/gmail-invite.png);
  }
	.sign-in-whatsapp
  {
    background-image: url(<?php echo $C->SITE_URL;?>static/images/whatsapp-invite.png);
    background-position: -4px 0px 0px 0px;
    background-repeat: no-repeat;
    background-size: 35px 36px;
    padding-left: 41px;
    color: #005b7f;
    font-weight: bold;
	width: 220px;
  }
  .sign-in-whatsapp:hover
  {
    background-image: url(<?php echo $C->SITE_URL;?>static/images/whatsapp-invite.png);
  }
</style>


</body>

</html>