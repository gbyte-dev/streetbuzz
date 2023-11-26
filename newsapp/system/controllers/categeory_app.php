<?php
 $userid = $_GET['user_id'];


$fetch = $db2->query('SELECT * FROM categeory_master ORDER BY sno ASC ');
	while($fetchresasw[] = $db2->fetch_object($fetch)){
	}
	 $finalres     =array_filter($fetchresasw);
	$finalarry =  array_chunk($finalres,3);

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>StreetBuzz</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="icon" href="images/favicon.jpg" type="image/jpeg" sizes="24x24"> 
  <link rel="stylesheet" href="<?php echo $C->SITE_URL;?>assets/bootstrap.css">
<link rel="stylesheet" href="<?php echo $C->SITE_URL;?>static/css/sb_ui.css">
 <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">


<!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<style>
body
{
  /*background: url(<?php echo $C->SITE_URL;?>static/images/bg9.jpg) no-repeat center center fixed;
    background-size: cover;*/
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
          <!-- <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button> -->
          <a href="<?php echo $C->SITE_URL; ?>"><img src="<?php echo $C->SITE_URL; ?>static/images/logo.png" class="img-responsive  center-block"></a>
        </div>
        <!--/.nav-collapse -->
      </div>
    </nav>
     

<!-- Main content -->
<div class="container-fluid contentbox-inner">

<div class="col-md-1 col-lg-1 hidden-xs">
<!-- for spacing adjustment -->
</div>        

      
<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 main-content-box">
<!--/ Signup -->
<div class="row box-white-inner">
	
<div class="reg-title">Select your areas of interest</div>
	
	
<div class="col-md-12 col-xs-12">
 <form action="<?php echo $C->SITE_URL?>invitefriends_app" method="POST">
            <input type="hidden" name="userid" value="<?php echo  $userid ?>">


	 
      <!-- Info boxes -->
	  <?php foreach($finalarry as $keys=>$vals){
		  
		  ?>
      <div class="row">
	  <?php foreach($vals as $childkeys=>$childval){
		?>
	  
        <div class="col-md-4 col-sm-12 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua">
            <i class="<?php echo $childval->image;?>" ></i>
            </span>

            <div class="info-box-content">
              <span class="info-box-text"><?php echo $childval->cat_name;?></span>
                 <span class="chkboxCategory info-box-checkbox">
	<input type="checkbox" value="<?php echo $childval->cat_id;?>" class="cat" name="cat[]" id="chkboxCategory<?php echo $childval->cat_id;?>" checked />
	<label for="chkboxCategory<?php echo $childval->cat_id;?>"></label>
</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
	  <?php } ?>
       

      
      </div>
      <!-- /.row -->
	  <?php } ?>
      
      
     
<div class="row">
  
  <div class="col-sm-12 col-xs-12 btn-wrapper">

      <button class="btn btn-primary btn-lg pull-right fin" name="action" value="finish" id=""> FINISH!</button>

       <button class="btn btn-warning btn-lg invite" name="action" value="invite" id=""> Invite Friends
      </button>
        </form>
  </div>
</div>
        <!-- /.row -->

       </div>
      <!-- /.container -->
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
<li>&copy; Copyright 2016.</li>
<li>&nbsp;</li>
<li><a href="<?php echo $C->SITE_URL ?>privacy-terms-rules/privacy/index.html" target="_blank">Privacy</a></li>
<li><a href="<?php echo $C->SITE_URL ?>privacy-terms-rules/terms/index.html" target="_blank">Terms</a></li>
</ul>
</div> 
<!-- end - footer -->


</body>
<script src="<?php echo $C->SITE_URL ?>static/js/jquery.js?v=3.6.0"></script>
<script type="text/javascript">
$(document).ready(function(){
	$(".cat").click(function(){
var a =	 $('.cat:checked').length;
if(a ==0){
	$(".invite").prop("disabled",true);
	$(".fin").prop("disabled",true);
	
}else{
	$(".invite").prop("disabled",false);
	$(".fin").prop("disabled",false);
	
	
}
});
	
});
</script>



<style>
.btn-wrapper {
  text-align: right;
}
.btn-wrapper .fin {
  margin-left: 25px;
}
.contentbox-inner {
    margin-top: 40px;
    margin-bottom: 40px;
    padding: 0;
}
.reg-title {
  padding-bottom: 15px;
}
.info-box {
  display: block;
  min-height: 90px;
  background: #fff;
  width: 100%;
  box-shadow: 0px 1px 1px 1px rgba(0, 0, 0, 0.2);
  border-radius: 2px;
  margin-bottom: 15px;
}
.info-box:hover {
background:#f9f9f9;
}
.info-box small {
  font-size: 14px;
}
.info-box .progress {
  background: rgba(0, 0, 0, 0.2);
  margin: 5px -10px 5px -10px;
  height: 2px;
}
.info-box .progress,
.info-box .progress .progress-bar {
  border-radius: 0;
}
.info-box .progress .progress-bar {
  background: #fff;
}
.info-box-icon {
  border-top-left-radius: 2px;
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 2px;
  display: block;
  float: left;
  height: 90px;
  width: 90px;
  text-align: center;
  font-size: 45px;
  line-height: 90px;
  background: rgba(0, 0, 0, 0.2);
}
.info-box-icon > img {
  max-width: 100%;
}
.info-box-content {
  padding: 34px 5px;
  margin-left: 100px;
  text-align: left;
}
.info-box-number {
  display: block;
  font-weight: normal;
  font-size: 14px;
  color:#333;
}
.info-box-checkbox {
float:right
}
.progress-description,
.info-box-text {
  display: block;
  font-size: 15px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  font-weight:bold;
  color: #0698d0;
}
.info-box-more {
  display: block;
}
.progress-description {
  margin: 0;
}
.bg-aqua {
  background-color: #0698d0 !important;
  color:#ffffff;
}



/* SQUARED THREE */
.chkboxCategory {
	width: 20px;	
	margin: 0px auto;
	position: relative;
}

.chkboxCategory label {
	cursor: pointer;
	position: absolute;
	width: 20px;
	height: 20px;
	top: 0;
	left: -4px;
	border-radius: 4px;

	-webkit-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,.4);
	-moz-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,.4);
	box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,.4);

	background: -webkit-linear-gradient(top, #ec971f 0%, #d58512 100%);
	background: -moz-linear-gradient(top, #ec971f 0%, #d58512 100%);
	background: -o-linear-gradient(top, #ec971f 0%, #d58512 100%);
	background: -ms-linear-gradient(top, #ec971f 0%, #d58512 100%);
	background: linear-gradient(top, #ec971f 0%, #d58512 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#222', endColorstr='#45484d',GradientType=0 );
}

.chkboxCategory label:after {
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
	filter: alpha(opacity=0);
	opacity: 0;
	content: '';
	position: absolute;
	width: 11px;
    height: 7px;
    background: transparent;
    top: 5px;
    left: 4px;
	border: 3px solid #fcfff4;
	border-top: none;
	border-right: none;

	-webkit-transform: rotate(-45deg);
	-moz-transform: rotate(-45deg);
	-o-transform: rotate(-45deg);
	-ms-transform: rotate(-45deg);
	transform: rotate(-45deg);
}

.chkboxCategory label:hover::after {
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=30)";
	filter: alpha(opacity=30);
	opacity: 0;
}

.chkboxCategory input[type=checkbox]:checked + label:after {
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
	filter: alpha(opacity=100);
	opacity: 1;
}
</style>



</html>

