<?php 
if( $this->user->is_logged ) {
		$this->redirect('home');
	}
 $home4 = ($_POST);
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

<div class="reg-title">How do you rate your understanding of Finanicial management ?</div>

<div class="col-md-12 col-lg-12 col-xs-12 reg-desc-big"><p>This helps us to serve contains specify to your needs </p>
</div>

<!-- start Scroll -->
<div class="col-md-12 col-lg-12 col-xs-12 scroll-bar">

<div class="col-md-8">
<div class="panel panel-primary">
<div class="panel-body scroll-limit" id="Panel1">
<form action="<?php echo $C->SITE_URL?>home5" method="post">
<?php 
foreach($home4 as $keys=>$vals){ ?>
<input type="hidden" name="<?php echo $keys ?>" value="<?php echo $vals;?>">
	
<?php }

?>

<div class="">
      <input id="check1" type="radio" name="understand_financial" value="beginner" checked>
      <label for="check1" >Beginner</label>
      <br>
      
      <input type="radio" name="understand_financial" value="intermediate">
      <label for="check3">Intermediate</label>
      <br>
      <input id="check4" type="radio" name="understand_financial" value="expert">
      <label for="check4">Expert</label>
      <br>
      <input id="check5" type="radio" name="understand_financial" value="maestro">
      <label for="check5">Maestro</label>
      <br>
      
    </div>

</div>
</div>
</div>


</div>
<!-- end Scroll -->


<div class="col-md-12 col-lg-12 reg-desc-big">
<input type="submit" class="btn btn-default btn-blue" value="Continue">
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
</html>