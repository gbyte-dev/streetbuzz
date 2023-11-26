<?php
if( $this->user->is_logged ) {
		$this->redirect('home');
	}
$home6 =$_POST;
$str = var_export($home6, true);
 

$res = $db2->query('SELECT u.id,u.username,u.fullname,u.avatar,u.about_me FROM  street_suggestion as st
INNER JOIN users AS u  ON st.user_id=u.id

 WHERE st.suggestion_type="'.$db2->e($_POST['understand_financial']).'"  order by st.id desc LIMIT 10');
 


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
  background: url(<?php echo $C->SITE_URL;?>static/images/bg6.jpg) no-repeat center center fixed;
    background-size: cover;
    width: 100%;
    height: 100%;
}
</style>
</head>
<body data-spy="scroll" data-target="#myScrollspy" data-offset="20">
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

<div class="reg-title">Suggestions just for you</div>

<div class="col-md-12 col-lg-12 col-xs-12 reg-desc-big"><p>Based on your choices, here are some suggestions for you. We recommened 
  <a href="#" class="link_blue_text_big">following</a> all experts!</p>
</div>

<!-- start Scroll -->
<div class="col-md-12 col-lg-12 col-xs-12 scroll-bar">
<div class="scroll-title">Suggestions for you</div>
<div class="col-md-8">
<div class="panel panel-primary">
<div class="panel-body scroll-limit" id="Panel1">


<div class="checkbox">
<?php
while($result    = $db2->fetch_object($res)){ 
$users[] =$result->id;
$userpredict   = $db2->query('SELECT Level,Hit,Miss,NotionalAmount from `user_predict_details` WHERE  User_id = "'.$result->id.'" order by id desc LIMIT 1');
$userpredictres        =  $db2->fetch_object($userpredict);
if($userpredictres->Hit !=''){
	$hit = $userpredictres->Hit;
	
}else{
		$hit = 0;
}
if($userpredictres->Miss !=''){
	$Miss = $userpredictres->Miss;
	
}else{
		$Miss = 0;
}

?>
	

<!-- row 1 starts -->     
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sugg-outer" id="hide-<?php echo $result->id;?>">
<!-- start column 1 -->
<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 sugg">
<?php  if($result->avatar !="" ){
	$image = $result->avatar;
	$src = '<img src="'.$C->SITE_URL.'storage/avatars/thumbs3/'.$image.'" class="img-responsive">';
	
}else{
	 $src ='<div class="data-row-1 circle-who-to-follow" data-userid="'.$result->id.'">'.ucfirst(substr($result->username,0,1)).'</div>';
	
}
	?>
	<?php echo $src; ?>
</div>
<!-- end column 2 -->

<!-- start column 2 -->
<div class="col-xs-9 col-sm-9 col-md-8 col-lg-8">
<?php echo $result->fullname; ?>
<p class="link_blue_text">@<?php echo $result->username; ?></p> 
<div class="col-xs-12 col-sm-12 sugg-hit-miss visible-xs">
Hit - <?php echo $hit; ?> &nbsp;&nbsp;&nbsp; &nbsp; Miss - <?php echo $Miss; ?>
</div>
<span class="text-small-dark-blue">
<?php echo $result->about_me;?></span>
</div>
<!-- end column 2 -->

<!-- start column 3 -->
<div class="col-md-2 col-lg-2 sugg-hit-miss hidden-xs">
Hit - <?php echo $hit; ?> <br />
Miss - <?php echo $Miss; ?>
</div>
<!-- end column 3 -->

<!-- start column 4 -->
<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 sugg-close" rel="<?php echo $result->id ?>">
<img src="<?php echo $C->SITE_URL;?>static/images/close.png" rel="<?php echo $result->id ?>">
</div>
<!-- end column 4 -->

</div>
<!-- row 1 ends --> 

<form action="<?php echo $C->SITE_URL?>home6" method="post">

<?php 
foreach($home6 as $keys=>$vals){ ?>
<input type="hidden" name="<?php echo $keys ?>" value="<?php echo $vals;?>">
	
<?php }

?>
<?php } 
if(!empty($users)){
$res = implode(",",$users);
}

 ?>
 <input type="hidden" name="users" value="<?php echo $res;?>" id="users">

</div>

</div>
</div>
</div>


<div class="col-md-12 col-lg-12 reg-desc-big">
<input type="submit" class="btn btn-default btn-blue" value="Follow  & Continue">
</div> 
</form>


</div>
<!-- end Scroll -->


 

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
</html>