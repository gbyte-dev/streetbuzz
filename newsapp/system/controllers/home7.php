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
$change="";
$abc="";


 define ("MAX_SIZE","400");
 function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }

 $errors=0;
  

  $image =$_FILES['file']['name'];
  $uploadedfile = $_FILES['file']['tmp_name'];
     
 
  if ($image) 
  {
  
    $filename = stripslashes($_FILES['file']['name']);
  
      $extension = getExtension($filename);
    $extension = strtolower($extension);
    
    
 if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) 
    {
    
      $change='<div class="msgdiv">Unknown Image extension </div> ';
      $errors=1;
    }
    else
    {

 $size=filesize($_FILES['file']['tmp_name']);


if ($size > MAX_SIZE*1024)
{
  $change='<div class="msgdiv">You have exceeded the size limit!</div> ';
  $errors=1;
}


if($extension=="jpg" || $extension=="jpeg" )
{
$uploadedfile = $_FILES['file']['tmp_name'];
$src = imagecreatefromjpeg($uploadedfile);

}
else if($extension=="png")
{
$uploadedfile = $_FILES['file']['tmp_name'];
$src = imagecreatefrompng($uploadedfile);

}
else 
{
$src = imagecreatefromgif($uploadedfile);
}


list($width,$height)=getimagesize($uploadedfile);


$newwidth=50;
$newheight=50;
$tmp=imagecreatetruecolor($newwidth,$newheight);


$newwidth1=16;
$newheight1=16;
$tmp1=imagecreatetruecolor($newwidth1,$newheight1);
$newwidth2=30;
$newheight2=30;
$tmp2=imagecreatetruecolor($newwidth2,$newheight2);
$newwidth3=100;
$newheight3=100;
$tmp3=imagecreatetruecolor($newwidth3,$newheight3);
$newwidth4=420;
$newheight4=60;
$tmp4=imagecreatetruecolor($newwidth4,$newheight4);


imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);

imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);
imagecopyresampled($tmp2,$src,0,0,0,0,$newwidth2,$newheight2,$width,$height);
imagecopyresampled($tmp3,$src,0,0,0,0,$newwidth3,$newheight3,$width,$height);
imagecopyresampled($tmp4,$src,0,0,0,0,$newwidth4,$newheight4,$width,$height);





$avtr = time().rand(100000,999999).'.png';  

$filename = $C->STORAGE_DIR.'avatars/thumbs1/'.$avtr;

$filename1 = $C->STORAGE_DIR.'avatars/thumbs2/'.$avtr;
$filename2 = $C->STORAGE_DIR.'avatars/thumbs3/'.$avtr;
$filename3 = $C->STORAGE_DIR.'avatars/thumbs4/'.$avtr;
$filename4 = $C->STORAGE_DIR.'avatars/thumbs5/'.$avtr;





imagejpeg($tmp,$filename,100);

imagejpeg($tmp1,$filename1,100);
imagejpeg($tmp2,$filename2,100);
imagejpeg($tmp3,$filename3,100);
imagejpeg($tmp4,$filename4,100);
$db2->query('UPDATE users SET avatar="'.$avtr.'" WHERE id="'.$user_id.'" ');






imagedestroy($src);
imagedestroy($tmp);
imagedestroy($tmp1);
imagedestroy($tmp2);
imagedestroy($tmp3);
imagedestroy($tmp4);



}}
}




/*if(!empty($_FILES['file']['name'])){
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
*/
if($_POST['imagetype'] == 'takephoto'){
$query      =$db2->query('select img_url from coverphotos where user_id="'.$user_id.'" order by id desc limit 1');
$result            =$db2->fetch_object($query);
  
  
  $db2->query('UPDATE users SET avatar="'.$result->img_url.'" WHERE id="'.$user_id.'" ');

  
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
<div class="col-md-6 col-lg-6 col-xs-12" style="padding:0">
<img src="<?php echo $C->SITE_URL; ?>static/images/find-people.jpg" class="table-bordered" width="150">
</div>
<div class="col-md-6 col-lg-6 col-xs-12 prof-name-box">
<img src="<?php echo $C->SITE_URL; ?>static/images/in-out-arrow-social.jpg">
</div>
</div>

<form action="<?php echo $C->SITE_URL; ?>home8" method="POST">
<input type="hidden" name="contact" value="gmail">

<?php 
foreach($home6 as $keys=>$vals){ ?>
<input type="hidden" name="<?php echo $keys ?>" value="<?php echo $vals;?>">
  
<?php } ?>

<p style="margin-left: 15px;"><img  name="submit" src="<?php echo $C->SITE_URL; ?>static/images/gmail.png" class="btn-photo" border="0" alt="Submit" onclick="auth();" />
</p>

<p style="margin-left: 15px;"><a href=""><img src="<?php echo $C->SITE_URL; ?>static/images/outlook.png" class="btn-photo" id="import"></a></p>
<!--<p><a href="<?php echo $url; ?>"><img src="<?php echo $C->SITE_URL; ?>static/images/yahoo.png" class="btn-photo"></a></p>-->
</form>

<form action="<?php echo $C->SITE_URL; ?>register" method="POST">
<input type="hidden" name="contact" value="gmail">
<input type="hidden" name="contactoutlook" value="outlook">


<?php 
foreach($home6 as $keys=>$vals){ ?>
<input type="hidden" name="<?php echo $keys ?>" value="<?php echo $vals;?>">
  
<?php } ?>


<div class="col-md-6 col-lg-6 pull-right">
<input type="submit" class="link_blue_text" name="submit" value="Skip this step" style="background: none; border:0"></input>
</div> 
</form>

</div><!--/ prof-main-box -->




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
    redirect_uri:'http://streetbuzz.co.in/test/home7',
    scope: ["wl.basic", "wl.contacts_emails"],
    response_type: "token"
});
</script>
<script>
jQuery( document ).ready(function() {

  //live.com api
  jQuery('#import').click(function(e) {
     var userid = <?php echo $user_id;  ?>;
    var password= "<?php  echo  $street_userpassword;?>";
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
    var password= "<?php  echo  $street_userpassword;?>";
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