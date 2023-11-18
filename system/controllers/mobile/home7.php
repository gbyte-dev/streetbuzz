<?php
if( $this->user->is_logged ) {
		$this->redirect('home');
	}
require_once( $C->INCPATH.'libraries/globals.php');
require_once( $C->INCPATH.'libraries/oauth_helper.php');
$callback    = "http://purpledot.co/works/streetbuzz/yahoo_callback";
$retarr = get_request_token(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET, $callback, false, true, true);
if (! empty($retarr)){
list($info, $headers, $body, $body_parsed) = $retarr;

if ($info['http_code'] == 200 && !empty($body)) {
$_SESSION['request_token']  = $body_parsed['oauth_token'];
$_SESSION['request_token_secret']  = $body_parsed['oauth_token_secret'];
$_SESSION['oauth_verifier'] = $body_parsed['oauth_token'];
$url = urldecode($body_parsed['xoauth_request_auth_url']);
}
}
$home6 =$_POST;
$user_id = $_POST['userid'];
$strret_useremail    = $_POST['strret_useremail'];
$street_userpassword= $_POST['street_userpassword'];

$_SESSION['id'] =  $user_id ;
$_SESSION['strret_useremail'] =  $strret_useremail    ;
$_SESSION['street_userpassword'] =  $street_userpassword;


if(!empty($_FILES['file']['name'])){
$file  =$_FILES['file'];
$target     = $C->STORAGE_DIR.'avatars/thumbs1/'.$_FILES['file']['name'];
move_uploaded_file($_FILES['file']['tmp_name'],$target);
$target1     = $C->STORAGE_DIR.'avatars/thumbs2/'.$_FILES['file']['name'];
$target2     = $C->STORAGE_DIR.'avatars/thumbs3/'.$_FILES['file']['name'];
$target3     = $C->STORAGE_DIR.'avatars/thumbs4/'.$_FILES['file']['name'];
$target4     = $C->STORAGE_DIR.'avatars/thumbs5/'.$_FILES['file']['name'];


if(!copy ( $target , $target1 ))
        {  
          throw new Exception('Could not move 2nd file');
        }
if(!copy ( $target , $target2 ))
        {  
          throw new Exception('Could not move 3rd file');
        }
if(!copy ( $target , $target3 ))
{  
  throw new Exception('Could not move 4th file');
}
if(!copy ( $target , $target4 ))
{  
  throw new Exception('Could not move 5th file');
}



	



$db2->query('UPDATE users SET avatar="'.$_FILES['file']['name'].'" WHERE id="'.$user_id.'" ');


}
if($_POST['imagetype'] == 'takephoto'){
$query	    =$db2->query('select img_url from coverphotos order by id desc limit 1');
$result            =$db2->fetch_object($query);
	
	
	$db2->query('UPDATE users SET avatar="'.$result->img_url.'" WHERE id="'.$user_id.'" ');

	
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
  background: url(<?php echo $C->SITE_URL;?>static/images/bg7.jpg) no-repeat center center fixed;
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

<div class="reg-title">Find people you know</div>

<div class="col-md-12 col-lg-12 col-xs-12 reg-desc-big">
<p>Find people you know so you can see their stock updates. Your contacts are safe with us!</p>
</div>


<div class="col-md-12 col-lg-12 prof-main-box" style="padding:0">

<div class="col-md-6 col-lg-6 col-xs-12">
<div class="col-md-6 col-lg-6 col-xs-12 table-bordered" style="padding:0">
<img src="<?php echo $C->SITE_URL; ?>static/images/find-people.jpg" class="table-bordered">
</div>
<div class="col-md-6 col-lg-6 col-xs-12 prof-name-box">
<img src="<?php echo $C->SITE_URL; ?>static/images/in-out-arrow-social.jpg" class="center-block">
</div>
</div>

<div class="col-md-6 col-lg-6 col-xs-12">
<form action="<?php echo $C->SITE_URL; ?>home8" method="POST">
<input type="hidden" name="contact" value="gmail">

<?php 
foreach($home6 as $keys=>$vals){ ?>
<input type="hidden" name="<?php echo $keys ?>" value="<?php echo $vals;?>">
	
<?php } ?>

<p><img  name="submit" src="<?php echo $C->SITE_URL; ?>static/images/gmail.png" class="btn-photo" border="0" alt="Submit" onclick="auth();" />
</p>
</form>

<p><a href=""><img src="<?php echo $C->SITE_URL; ?>static/images/outlook.png" class="btn-photo" id="import"></a></p>
<!--<p><a href="<?php echo $url; ?>"><img src="<?php echo $C->SITE_URL; ?>static/images/yahoo.png" class="btn-photo"></a></p>-->
</div>

</div>

<form action="<?php echo $C->SITE_URL; ?>register" method="POST">
<input type="hidden" name="contact" value="gmail">
<input type="hidden" name="contactoutlook" value="outlook">


<?php 
foreach($home6 as $keys=>$vals){ ?>
<input type="hidden" name="<?php echo $keys ?>" value="<?php echo $vals;?>">
	
<?php } ?>


<div class="col-md-6 col-lg-6 pull-right">
<input type="submit" name="submit" value="SubmitSkip this step"></input>
</div> 
</form>
<div id="gmail">

</div>
<div id="outlook">

</div>

  <div class="col-md-12 col-lg-12 notification">
Choosing a service will open a window for you to log in security and import your contacts to StreetBuzz. You’ll only find users who have allowed their accounts to be found by email address. We won’t email anyone without your consent, but we may use contact information to make Who to follow suggestions. You can remove your contacts from StreetBuzz at any time..</a>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  	
<script src="https://apis.google.com/js/client.js"></script>
<script src="//js.live.net/v5.0/wl.js"></script>
<script type="text/javascript">
WL.init({
    client_id:'000000004C17C3AF',
    redirect_uri:'http://purpledot.co/works/streetbuzz/home7',
    scope: ["wl.basic", "wl.contacts_emails"],
    response_type: "token"
});
</script>
<script>
jQuery( document ).ready(function() {

	//live.com api
	jQuery('#import').click(function(e) {
	   var userid = <?php echo $user_id;  ?>;
    var password= <?php  echo  $street_userpassword;?>;
	    e.preventDefault();
	    WL.login({
	        scope: ["wl.basic", "wl.contacts_emails"]
	    }).then(function (response) 
	    {
			WL.api({
	            path: "me/contacts",
	            method: "GET"
	        }).then(
	            function (response) {
	                 $("#outlook").load("<?php echo $C->SITE_URL; ?>outlookparse",{data:response.data,userid:userid,password:password});;

	           
                        //your response data with contacts 
	            	//console.log(response.data);
	            },
	            function (responseFailed) {
	            	//console.log(responseFailed);
	            }
	        );
	        
	    },
	    function (responseFailed) 
	    {
	        //console.log("Error signing in: " + responseFailed.error_description);
	    });
	});    

});
</script>

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
    var password= <?php  echo  $street_userpassword;?>;
     $.ajax({
     url: "https://www.google.com/m8/feeds/contacts/default/full?access_token=" + token.access_token + "&alt=json",
     dataType: "jsonp",
     success:function(data) {
     
     
     $("#gmail").load("<?php echo $C->SITE_URL; ?>gmailregisterparse",{data:data,userid:userid,password:password});;
     
    $("#gmailform").submit();
    
     
                              // display all your data in console
              // console.log(JSON.stringify(data));
     }
 });
 } 
</script>


</body>

</html>