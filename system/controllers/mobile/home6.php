<?php
if( $this->user->is_logged ) {
		$this->redirect('home');
	}
$home6 = $_POST;
$users = $_POST['users'];
$understand_financial = $_POST['understand_financial'];
$user_id = $_POST['userid'];
$db2->query('UPDATE users SET stratagic="'.$understand_financial.'" WHERE id="'.$user_id.'" ');
  $users           =explode(",",$_POST['users']);
  $usercount        =count($users);
  if($user_id !='' ){
     $check        = $db2->query(' select id from users_followed  where  who="'.$user_id.'" ');
     $checkres              =$db2->fetch_object($check);
     if($checkres->id ==''){
      $mes ='ntf_me_if_u_joins_grp';
	  foreach($users as $keys=>$vals){
	  $db2->query('INSERT INTO users_followed SET who="'.$user_id.'", whom="'.$vals.'", date="'.time().'", whom_from_postid="'.$this->network->get_last_post_id().'" ');
	  	  $db2->query('INSERT INTO notifications SET notif_type="'.$mes.'", to_user_id="'.$vals.'",from_user_id="'.$user_id.'", date="'.time().'" ');

	  }
	  }
	$db2->query('UPDATE users SET num_followers="'.$usercount.'" WHERE id="'.$user_id.'" ');
	  
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

<div class="reg-title">Customize your profile</div>

<div class="col-md-7 col-lg-7 col-xs-12 reg-desc-big">
<p>Add a photo to show your identity among members</p>
</div>


<div class="col-md-12 col-lg-12 prof-main-box" style="padding:0">

<div class="col-md-6 col-lg-6 col-xs-12"  id="profpic">
<!-- start of profile pic -->
<div class="col-md-4 col-lg-4 col-xs-12" style="padding:0">
<img id="blah"src="<?php echo $C->SITE_URL; ?>static/images/prof-pic.jpg" class="table-bordered">
</div>

<div class="col-md-8 col-lg-8 col-xs-12 prof-name-box">
<span class="prof-name"><?php echo $_POST['fullname']?></span> <br /> <span class="prof-at">@<?php echo $_POST['street_username']?></span>
</div>
</div>
<!-- end of profile pic -->

<!-- start of takepic pic -->
<div class="col-md-7 col-lg-7 col-xs-12" style="padding:0;display:none;" id="takephoto">

<!-- start settings  -->
<div id="main" style="height:300px; width:100%">
<div id="content" style="float:left; width:100%; padding:6px; border:1px solid #999999;" align="center">
<script src="<?php echo $C->SITE_URL;?>static/js/webcam.js?v=3.6.0"></script>

<script language="JavaScript">
		document.write( webcam.get_html(440, 240) );
</script>
<form>
<br />

		<input type=button value="Configure settings" onClick="webcam.configure()" class="shiva">
		&nbsp;&nbsp;
		<input type=button value="snap" onClick="take_snapshot()" class="shiva">
		<input type=button value="view" onClick="take_snapshots()" class="shiva" style="opacity: 0.4;" id="view">
	</form>


</div>

<script  type="text/javascript">

    webcam.set_api_url('<?php echo $C->SITE_URL?>handleimage');

		webcam.set_quality( 90 ); // JPEG quality (1 - 100)

		webcam.set_shutter_sound( true ); // play shutter click sound

		webcam.set_hook('onComplete', 'my_completion_handler' );


		function take_snapshot(){
			// take snapshot and upload to server
			document.getElementById('img').innerHTML = '<h1>Uploading...</h1>';
			
		 webcam.snap();
		 $("#view").css("opacity","");
		/* $.ajax({
		  type:"POST",
		 
		  data:{photo:'image'},
		  url:"<?php echo $C->SITE_URL;?>handleimage",

		  success:function(response){
			  alert(response);
			 document.getElementById('img').innerHTML ="<img src="+response+" class=\"img\">";


			  
			 
			  
			  
		  }
		  
	  });*/
		}
		function take_snapshots(){
			$.ajax({
		  type:"POST",
		 
		  data:{photo:'image'},
		  url:"<?php echo $C->SITE_URL;?>handleimage",

		  success:function(response){
			 document.getElementById('img').innerHTML ="<img src="+response+" class=\"img\">";


			  
			 
			  
			  
		  }
		  
	  });
		}

		/*function my_completion_handler(msg) {

			// extract URL out of PHP output
			if (msg.match(/(http\:\/\/\S+)/)) {
				// show JPEG image in page
				
				document.getElementById('img').innerHTML ='<h3>Upload Successfuly done</h3>'+msg;
			     
				document.getElementById('img').innerHTML ="<img src="+msg+" class=\"img\">";
				
			
				// reset camera for another shot
				webcam.reset();
			}
			else {alert("Error occured we are trying to fix now: " + msg); }
		}*/
		
		
	</script>


<div id="img" style=" height:auto; width:100%; float:left; margin-left:0px; margin-top:20px;">

</div>
</div>
<!-- end settings  -->

</div>
<!-- end of takepic pic -->

<form action="<?php echo $C->SITE_URL?>home7" method="post" enctype= "multipart/form-data">
<input type="hidden" name="imagetype" id="imagetype" value="profile">

<?php 
foreach($home6 as $keys=>$vals){ ?>
<input type="hidden" name="<?php echo $keys ?>" value="<?php echo $vals;?>">

	
<?php } ?>


<div class="col-md-5 col-lg-5 col-xs-12">
<p><div><img src="<?php echo $C->SITE_URL; ?>static/images/take-photo.png" class="btn-photo" id="takepic"></div></p>
<p><div class="image-upload" style="margin-left:-25px;">
    <label for="file-input">
        <img src="<?php echo $C->SITE_URL; ?>static/images/upload-photo.png"  class="btn-photo" id="profic" />
    </label>

<input id="file-input" type="file" name="file"/ onchange="readURL(this);">
</div></p>
</div>
<style>
.image-upload > input
{
    display: none;
}

.image-upload img
{
    width: 100%;
    cursor: pointer;
}
</style>


</div>



<div class="col-md-6 col-lg-6">
<input type="submit" class="btn btn-default btn-blue" value="Continue">
</form>
</div>  

<!--
<div class="col-md-6 col-lg-6">
<a href="#" class="link_blue_text">Skip this step for now</a>
</div> -->

</div>      
</div>
<!--/ Start Main Content -->  



<div class="col-md-1 col-lg-1 hidden-xs">
<!-- for spacing adjustment -->
</div>  


</div>
</div> <!-- /container main -->

<!-- start - footer -->
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer-box" align="center" style="display:none!important;>
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
$("#takepic").click(function(){
		$("#imagetype").val("takephoto");

	$("#profpic").hide();
	$("#takephoto").css("display","block");
	
	
});
$("#profic").click(function(){
	$("#profpic").show();
	$("#takephoto").css("display","none");
	$("#imagetype").val('profile');
	
	
});
$(".sugg-close").click(function(){
	var id = $(this).attr('rel');
	var users = $("#users").val();
	 $("#hide-"+id).fadeOut();
	 $.ajax({
		 
		  type:"POST",
		  data:{userid:id,users:users},
		   url:"<?php echo $C->SITE_URL;?>signup",

		  success:function(response){
			  $("#users").val(response);
			 
			  
			  
		  }
		  
	  });
	
	
});

</script>
<script>
     function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(130)
                        .height(180);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
</script>

<style type="text/css">

.img
    { background:#ffffff;
    padding:12px;
    border:1px solid #999999; }
.shiva{
 -moz-user-select: none;
    background: #2A49A5;
    border: 1px solid #082783;
    box-shadow: 0 1px #4C6BC7 inset;
    color: white;
    padding: 3px 5px;
    text-decoration: none;
    text-shadow: 0 -1px 0 #082783;
    font: 12px Verdana, sans-serif;}
</style>

</html>