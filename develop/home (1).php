<?php

//die("hello1");

  if( $this->user->is_logged ) {
    $this->redirect('dashboard');
  }

  $this->load_langfile('inside/global.php');
  $this->load_langfile('outside/home.php');
  $this->load_langfile('outside/signin.php');

?>

<?php
$network  = & $GLOBALS['network'];
$tags       = $network->get_recent_posttags();
?>

<!doctype html>
<html lang="en">
  <head>
      <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Local | Real time | Interactive news :: लोकल | रियल टाइम | इंटरैक्टिव न्यूज़ ">
<meta name="description" content="Local | Real time | Interactive news :: लोकल | रियल टाइम | इंटरैक्टिव न्यूज़ ">
  <meta name="author" content="Streetbuzz"/>
  <meta name="page-topic" content="Local | Real time | Interactive news :: लोकल | रियल टाइम | इंटरैक्टिव न्यूज़ " />
  <meta name="copyright" content="Streetbuzz"/>
  <meta name="robots" content="All"/>
  <meta name="googlebot" content="Index, follow"/>
  <meta name="msnbot" content="Index, follow"/>
  <meta name="allow-search" content="yes"/>
  <meta name="revisit-after" content="7 days"/>
  <meta name="distribution" content="global"/>
  <meta name="expires" content="never"/>
  <meta name="language" content="English"/>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, 
    maximum-scale=1.0, user-scalable=no"/>
    <meta name="mobile-web-app-capable" content="yes">
<title>StreetBuzz</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="icon" href="<?php echo $C->SITE_URL;?>static/images/favicon.jpg" type="image/jpeg" sizes="24x24"> 


<!-- Bootstrap core CSS -->

<link href="<?php echo $C->SITE_URL;?>assets/home/bootstrap4.css" rel="stylesheet" async>
    <link  href="<?php echo $C->SITE_URL;?>assets/home/font-awesome.min.css" rel="stylesheet" async>
<link href="<?php echo $C->SITE_URL;?>assets/home/docs.css" rel="stylesheet" async>
<script src="<?php echo $C->SITE_URL ?>assets/home/jquery3min.js" ></script>

<script src="<?php echo $C->SITE_URL;?>assets/home/bootstrap4min.js"></script>

</head>
  <body>
    <a id="skippy" class="sr-only sr-only-focusable" href="#content">
   
 <?php
 if( preg_match( "#iPhone#i", $_SERVER['HTTP_USER_AGENT'] )  || preg_match( "#iPod#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Android#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Phone#i", $_SERVER['HTTP_USER_AGENT'] )) {
    $css ='';
$btns ='
       <a class="btn mbcredit" style="float:right;margin-top:13px;margin-right:10px" data-toggle="modal" data-target="#myModal">Sign Up</a> <a class="btn mbcredit"  style="float:right;margin-top:13px;margin-right:10px;" data-toggle="modal" data-target="#myModal1">Login</a> ';

$imgwidth ='166px';
$regularimagewidth ='width="70px"';
$regularimageheight ='height="100px"';
$displaydatawidth ='60';
$displaydatawidthimg ='38';
$wordcnt      =145;

     
  }else{
     $css ="navbar navbar-expand navbar-dark flex-column flex-md-row bd-navbar";
$btns ='';
$imgwidth ='';
$regularimagewidth ='width="80%"';
$regularimageheight ='';
$displaydatawidth ='74';
$displaydatawidthimg ='25';
$wordcnt      =500;




}
?>

<header class="<?php echo $css;?>">
  <a class="navbar-brand mr-0 mr-md-2" href="/" aria-label="Bootstrap">
  <img width="<?php echo $imgwidth;?>" src="<?php echo $C->SITE_URL?>static/images/streetbuzz.jpg" class="img-responsive">
</a>
<?php  echo $btns;?>

 

  <ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">
<li class="nav-item">
              <form class="bd-search d-flex align-items-center">
  <input type="search" class="form-control"  name="search" id="search-input" placeholder="Search..." aria-label="Search for..." autocomplete="off" value="<?php echo $_GET['search'];?>">
  <button type="submit" class="searchButton">
    <i class="fa fa-search"></i>
    </button>


  <button class="btn btn-link bd-search-docs-toggle d-md-none p-0 ml-3" type="button" data-toggle="collapse" data-target="#bd-docs-nav" aria-controls="bd-docs-nav" aria-expanded="false" aria-label="Toggle docs navigation"><i class="fa fa-bars" aria-hidden="true"></i>

</button>
</form>
</a>
    </li>
    
    <li class="nav-item">
       <a class="btn  d-none d-lg-inline-block mb-3 mb-md-0 ml-md-3 credit"  data-toggle="modal" data-target="#myModal">Sign Up</a>
</a>
    </li>
    <li class="nav-item">
       <a class="btn  d-none d-lg-inline-block mb-3 mb-md-0 ml-md-3 credit " data-toggle="modal" data-target="#myModal1">Log in</a>

    </li>
  </ul>

</header>


    <div class="container-fluid">
      <div class="row flex-xl-nowrap">
        <div class="col-12 col-md-3 col-xl-2 bd-sidebar">
            <?php  if( preg_match( "#iPhone#i", $_SERVER['HTTP_USER_AGENT'] )  || preg_match( "#iPod#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Android#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Phone#i", $_SERVER['HTTP_USER_AGENT'] )) {
 ?>
          <form class="bd-search d-flex align-items-center">
  <input type="search" class="form-control"  name="search" id="search-input" placeholder="Search..." aria-label="Search for..." autocomplete="off" value="<?php echo $_GET['search'];?>">
  <button type="submit" class="searchButton">
      <i class="fa fa-search"></i>
    </button>
  <button class="btn btn-link bd-search-docs-toggle d-md-none p-0 ml-3" type="button" data-toggle="collapse" data-target="#bd-docs-nav" aria-controls="bd-docs-nav" aria-expanded="false" aria-label="Toggle docs navigation">
      <i class="fa fa-bars" aria-hidden="true"></i>

</button>
</form> <?php } ?>
<?php
if(empty($_GET)){
    $mainactive ='active';
        $newstype='Top';

    
}else{
    $mainactive ='';
}


if((isset($_GET['st']) && $_GET['st']) !='') {
$state_id = $_GET['st'];
$newstype = $_GET['name'];


}
else {
  $state_id ='';

}
?>

<nav class="collapse bd-links" id="bd-docs-nav">
 <div class="top_news_wrap">
             <!-- <div class="bd-toc-item">
     <a class="bd-toc-link stn">
         State News
        </a>
        </div> -->
          <div class="bd-toc-item">
      <a class="bd-toc-link <?php echo $mainactive;?>" href="<?php echo $C->SITE_URL?>home">
        Top News
      </a>

    </div>


    <?php 
    $querry=$db2->query('SELECT * FROM `state`');
    foreach ($querry as $value) {
     ?>
  <div class="bd-toc-item">
      <a class="bd-toc-link <?php if($state_id==$value['id']){ echo 'active'; } ?>" href="<?php echo $C->SITE_URL?>home?st=<?php echo $value['id'].'&name='.$value['name']; ?>" >
        <?php echo $value['name']; ?>
       </a>
    </div>
<?php } ?>

    </div>

     <div class="bd-toc-item col-xl-2 new-block">
               <a class="bd-toc-link1 stn active">
        Trending News
      </a>
      <?php foreach($tags as  $keys=>$vals){ ?>
      <div class="bd-toc-item">
      <a class="bd-toc-link1" href="<?php echo $C->SITE_URL?>search/tab:posts/s:<?php echo $vals;?>">
        #<?php echo $vals;?>
      </a>

     
    </div>
    <?php }  ?>
      
 

          </div>

  </nav>


        </div>

        
          <div class="d-none d-xl-block col-xl-2 bd-toc">
               <a class="bd-toc-link1 active">
        Trending News
      </a>
      <?php foreach($tags as  $keys=>$vals){ ?>
      <div class="bd-toc-item">
      <a class="bd-toc-link" href="<?php echo $C->SITE_URL?>search/tab:posts/s:<?php echo $vals;?>">
        #<?php echo $vals;?>
      </a>

     
    </div>
    <?php }  ?>
      
      <hr />
            <ul class="section-nav">
<li class="toc-entry toc-h2"><a href="<?php echo $C->SITE_URL?>privacy-terms-rules/privacy/index.html">Privacy Policy</a></li>
<li class="toc-entry toc-h2"><a href="<?php echo $C->SITE_URL?>privacy-terms-rules/terms/index.html">Terms & Conditions</a>

<li class="toc-entry toc-h2"><a href="<?php echo $C->SITE_URL?>principles/index.html">Principles</a></li>

<ul>

          </div>
          <?php
            if(empty($_GET)){
                $yes ='YES';
                $state ='';
                 $searching =0;
                $res =  $db2->query('SELECT p.id,p.message,pa.type,pa.data,u.username FROM homenews as h 
            inner join posts as p ON p.id=h.post_id 
            inner join users as u ON p.user_id=u.id
            left join posts_attachments as pa ON pa.post_id=p.id WHERE h.main_or_not ="'.$yes.'" group by p.id order by h.id desc');
          
            
            
                
                
            }else{
               
                if(isset($_GET['st'])){
                 $state =$_GET['st'];
                 $searching =0;
               $res = $db2->query('SELECT p.id,p.message,pa.type,pa.data,u.username FROM homenews as h 
            inner join posts as p ON p.id=h.post_id 
            inner join users as u ON p.user_id=u.id
            left join posts_attachments as pa ON pa.post_id=p.id WHERE h.state_id="'.$state.'" group by p.id order by h.sequence asc');
                }elseif($_GET['search']){
                   $state=  $search =$_GET['search'];
                    $searching =1; 
               $res = $db2->query('SELECT p.id,p.message,pa.type,pa.data,u.username FROM posts as p 
            inner join users as u ON p.user_id=u.id
            left join posts_attachments as pa ON pa.post_id=p.id WHERE p.message like "%'.$search.'%" group by p.id order by h.sequence asc');
          
            

                }
            }
            
              
            

          ?>
        

        <main class="col-12 col-md-9 col-xl-8 py-md-3 pl-md-5 bd-content" role="main">



<!-- Start MObile slider
 -->


<style type="text/css">
  
@media screen and (min-width: 550px) {
.owl-carousel.owl-theme.owl-rtl.owl-loaded.owl-drag {
    display: none;
}
}

.owl-carousel.owl-theme.owl-rtl.owl-loaded.owl-drag {
    font-size: 17px;
    margin-top: 10px;
}



@media screen and (max-width: 550px) {

.top_news_wrap {
    display: none;
}
}
</style>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.theme.default.min.css">




<div class="owl-carousel owl-theme">



 <?php 
    $querry=$db2->query('SELECT * FROM `state`');
?>

 <div class="item"><?php echo $newstype;?> News</div>
 
  <?php  foreach ($querry as $value) {
     ?>
         <div class="item"><a class="<?php if($state_id==$value['id']){ echo 'active'; } ?>" href="<?php echo $C->SITE_URL?>home?st=<?php echo $value['id'].'&name='.$value['name']; ?>" >
        <?php echo $value['name']; ?>
       </a></div>

      
<?php } ?>

</div>

<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js"></script>

<script>
  $('.owl-carousel').owlCarousel({
    rtl:true,
    loop:true,
    margin:10,
    autoplay:true,
    //nav:true,
    responsive:{
        0:{
            items:3
        },
        600:{
            items:3
        },
        1000:{
            items:5
        }
    }
});
</script>




<!-- End Mobile SLide   -->


<?php

if((isset($_GET['name']) && $_GET['name']) !='') {
?>
<br>
<h4>News Related To <?php echo $_GET['name']; ?></h4>

<?php
}
?>


  <!-- The Modal -->
  <div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Sign Up</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div id="finalerror" style="color:red;display:none;";>Please check enter data</div>
    <div class="form-group">
      <input type="text" class="form-control" id="fullname" placeholder="Full Name" name="email">
    </div>
    <div id="emailerror" style="display:none;"></div>

    <div class="form-group">
      <input type="text" class="form-control" id="useremail" placeholder="Email or Mobile">
    </div>
    <div id="usererror" style="display:none;"></div>

    <div class="form-group">
      <input type="text" class="form-control"  id="username" placeholder="User Name" >
    </div>
    <div class="form-group">
      <input type="password" class="form-control" id="userpassword" placeholder="Password" >
    </div>
    <div class="form-group"><strong>Birthday</strong></div>
      <div  class="form-inline" >
    <div >
    <span><select class="form-control" name="profile_birth_day" id="birthdayday">
    <option value="">Day</option>
    <?php for($i=1; $i<=31; $i++) {?>
    <option><?php echo $i;?></option>
    <?php } ?>
    </select>
    </span>
  </div>

    <div class="">
    <span><select class="form-control" name="profile_birth_month" id="birthdaymonth">
  <option value="">Month</option>
  <?php for($j=1; $j<=12; $j++) { ?>

  <option value="<?php echo $j;?>"><?php echo strftime('%B', mktime(0,0,1,$j,1,2009)); ?></option>
  <?php } ?>
  </select>
  </span>
  </div>
  <div>
  <span><select class="form-control" name="profile_birth_year" id="birthdayyear">
  <option value="">Year</option>

  <?php for($k=intval(date('Y')); $k>=1900; $k--) { ?>
  <option value="<?php echo $k;?>"><?php echo $k;?></option>
  <?php } ?>
  </select>
  </span>
  </div>
  </div>
  <div class="margin">
  <span><input name="profile_gender"  class="profile_gender" type="radio" value="f"> <strong>Female</strong></span>
  <span><input name="profile_gender" class="profile_gender" type="radio" checked="" value="m"> <strong>Male</strong></span>
  </div>
  <div class="margin10">
    
    <button  class="btn btn-default btncredit" id="sub" ><i class="fa fa-spinner fa-spin"></i>Signup for SB</button>
    </div>
        </div>
     </div>
    </div>
  </div>
  <!-- The Modal -->
  <div class="modal" id="myModal1">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Log in</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="form-group" style="color:red;display:none;" id="loginerror">
                Please enter valid data.
                </div>
        <div class="form-group">
      <input type="email" class="form-control"  placeholder="Mobile or User Name or Email" name="email" id="loginuser">
    </div>
    <div class="form-group">
      <input type="password" class="form-control"  placeholder="Password" name="pwd" id="loginpassword">
    </div>
    <div class="pull-left"><input type="checkbox"  id="rem" value="1">
<span class="grey_text">Remember me <a href="<?php echo $C->SITE_URL;?>signin/forgotten" class="link_blue_text">Forgot password?</a></span></div><br />
        <div class="margin10">
    
    <button type="submit" class="btn btn-default btncredit newlogin"><i class="fa fa-spinner fa-spin"></i>Log in</button>
    </div>
        
        
        </div>
        
        
        
      </div>
    </div>
  </div>
  
  
<?php  while($result    = $db2->fetch_object($res)){ 
?>
          
<div class="bd-example-row ">

<div class="bd-example newsrow">
<div class="row">
<div style="width:<?php echo $displaydatawidth;?>%;">
<div style="font-weight:bold;"class="p-2" >
<?php
$str =strip_tags($result->message);
 $finalstr   =mb_substr($str,0,$wordcnt);

echo $finalstr;?><a target="_blank" href="<?php echo $C->SITE_URL?>view/post:<?PHP ECHO $result->id;?>" > ..Read More</a> </div>
 <div class="p-2">
 By <a target="_blank" href="<?php echo $C->SITE_URL?>view/post:<?PHP ECHO $result->id;?>">@<?php echo $result->username;?></a>
 </div>
 </div>
 
 <div style="width:<?php echo $displaydatawidthimg;?>%">
     <?php
     $type = $result->type;
     if($type =='image'){
         $a = unserialize($result->data);
         $image =$a->file_thumbnail;
         ?>
        <?php  if(!empty($image)){ ?>
      <img width="80%"  style="border-radius:11px;" alt="Image" src="<?PHP echo $C->SITE_URL;?>/storage/attachments/1/<?php echo $image;?>">
      <?php }else{ 
           $thumbimg = $C->SITE_URL.'storage/thumbs/no-preview.jpg';
      ?>
      <img width="80%"  style="border-radius:11px;" alt="Image" src="<?PHP echo $thumbimg;?>">
      
     <?php  } ?>

     
    <?php  }elseif($type =='file'){ 
        $thumb =$result->thumb;
        if(!empty($thumb)){
           $thumbimg = $C->SITE_URL.'storage/thumbs/'.$thumb;

            
        }else{
             $thumbimg = $C->SITE_URL.'storage/thumbs/no-preview.jpg';
            
        }
    ?>
        
    <img width="80%"  style="border-radius:11px;" alt="Image" src="<?PHP echo $thumbimg;?>">
    <?php }
   
     
     ?>
    
 </div>
</div>
</div>

</div>
<?php } ?>
<div id="mainapp" rel="10" data-lan="<?php echo $state;?>" data-serach="<?php echo $searching;?>"></div>

   <!-- <div class="sbload" style="display:none"><img src="<?PHP echo $C->SITE_URL;?>/static/images/ajax-loader.gif" /></div>-->

 </main>
      </div>
    </div>

    

  <!--<footer class="footer">

<div class="container-fluid">
    <div id="carouselExample" class="carousel slide" data-ride="carousel" data-interval="9000">
        <div class="carousel-inner row w-100 mx-auto" role="listbox">
            <div class="carousel-item col-md-3  active">
               <div class="panel panel-default">
                  <div class="panel-thumbnail">
                    <a href="#" title="image 1" class="thumb">
                      <img height="50px" class="img-fluid mx-auto d-block" src="http://streetbuzz.in/wp-content/uploads/2018/10/JharkhandCraftMela.png" alt="slide 1">
                    </a>
                  </div>
                </div>
            </div>
            <div class="carousel-item col-md-3 ">
               <div class="panel panel-default">
                  <div class="panel-thumbnail">
                    <a href="#" title="image 3" class="thumb">
                     <img height="50px" class="img-fluid mx-auto d-block" src="http://streetbuzz.in/wp-content/uploads/2018/10/Divas_Bottom_Banner.png" alt="slide 2">
                    </a>
                  </div>
                </div>
            </div>
            <div class="carousel-item col-md-3 ">
               <div class="panel panel-default">
                  <div class="panel-thumbnail">
                    <a href="#" title="image 4" class="thumb">
                     <img height="50px" class="img-fluid mx-auto d-block" src="http://streetbuzz.in/wp-content/uploads/2018/10/WhatsApp-Image-2018-09-26-at-4.21.37-PM-1.jpeg" alt="slide 3">
                    </a>
                  </div>
                </div>
            </div>
            <div class="carousel-item col-md-3 ">
                <div class="panel panel-default">
                  <div class="panel-thumbnail">
                    <a href="#" title="image 5" class="thumb">
                     <img height="50px" class="img-fluid mx-auto d-block" src="http://streetbuzz.in/wp-content/uploads/2018/10/She-Line-Bottom-Banner-1.png" alt="slide 4">
                    </a>
                  </div>
                </div>
            </div>
            <div class="carousel-item col-md-3 ">
              <div class="panel panel-default">
                  <div class="panel-thumbnail">
                    <a href="#" title="image 6" class="thumb">
                      <img height="50px" class="img-fluid mx-auto d-block" src="http://streetbuzz.in/wp-content/uploads/2018/10/WhatsApp-Image-2018-09-26-at-4.21.37-PM-1.jpeg" alt="slide 5">
                    </a>
                  </div>
                </div>
            </div>
            <div class="carousel-item col-md-3 ">
               <div class="panel panel-default">
                  <div class="panel-thumbnail">
                    <a href="#" title="image 7" class="thumb">
                      <img height="50px" class="img-fluid mx-auto d-block" src="http://streetbuzz.in/wp-content/uploads/2018/10/She-Line-Bottom-Banner-1.png" alt="slide 6">
                    </a>
                  </div>
                </div>
            </div>
            <div class="carousel-item col-md-3 ">
               <div class="panel panel-default">
                  <div class="panel-thumbnail">
                    <a href="#" title="image 8" class="thumb">
                      <img height="50px" class="img-fluid mx-auto d-block" src="http://streetbuzz.in/wp-content/uploads/2018/10/She-Line-Bottom-Banner-1.png" alt="slide 7">
                    </a>
                  </div>
                </div>
            </div>
             <div class="carousel-item col-md-3  ">
                <div class="panel panel-default">
                  <div class="panel-thumbnail">
                    <a href="#" title="image 2" class="thumb">
                     <img height="50px"class="img-fluid mx-auto d-block" src="http://streetbuzz.in/wp-content/uploads/2018/10/She-Line-Bottom-Banner-1.png" alt="slide 8">
                    </a>
                  </div>
                  
                </div>
            </div>
        </div>
        <!--<a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next text-faded" href="#carouselExample" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
           <span class="sr-only">Next</span>
        </a>
    </div>
</div> 
      
</footer>-->
  

<style>
/*********************/
.form-control {
        border-radius: .25rem 0 0 .25rem!important;
}
.fa{
    margin:0px!important;
}
.search {
  width: 100%;
  position: relative;
  display: flex;
}

.searchTerm {
  width: 100%;
  border: 3px solid #00B4CC;
  border-right: none;
  padding: 5px;
  height: 20px;
  border-radius: 5px 0 0 5px;
  outline: none;
  color: #9DBFAF;
}

.searchTerm:focus{
  /*color: #00B4CC;*/
}

.searchButton {
  width: 40px;
  height: 38px;
  border: 1px solid #00B4CC;
  background: #00B4CC;
  text-align: center;
  color: #fff;
  border-radius: 0 5px 5px 0;
  cursor: pointer;
  font-size: 20px;
}

/*Resize the wrap to see the search bar change!*/
.wrap{
  width: 30%;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

/***********************/


.search-icon{
  position: relative;
  float: right;
  width: 75px;
  height: 75px;
  top: 0px;
  right: 55px;
  color:#007bff!important;
}

.new-block{
    display:none;
  
}
.bd-toc-link1 {
   display: block;
    padding: .25rem 0.5rem;
    font-weight: 500;
    color: rgba(0,0,0,.65);
}
.buttonload {
    background-color: #4CAF50; /* Green background */
    border: none; /* Remove borders */
    color: white; /* White text */
    padding: 12px 24px; /* Some padding */
    font-size: 16px; /* Set a font-size */
}

/* Add a right margin to each icon */
.fa {
    margin-left: -12px;
    margin-right: 8px;
}
.margin{
margin-top:5px;
}
.margin10{
margin-top:10px;
}
.bd-navbar{
background-color: white; !important}
.newsrow{
      display: -webkit-box;
    display: -moz-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    -webkit-flex-direction: column;
    flex-direction: column;
    border: 1px solid #dadce0;
    -webkit-border-radius: 8px;
    border-radius: 8px;
    position: relative;
}
.active{
  color:#22abdd !important;
}
.btncredit{
  border:1px solid #5bc0de;
  color:white !important;
  border-radius:25px;
  background: #5bc0de;
}
.credit{
  border:1px solid #5bc0de;
  color:#5bc0de !important;
  border-radius:25px;
  margin-top:15px !important;
}
.mbcredit{
  border:1px solid #5bc0de;
  color:#5bc0de !important;
  border-radius:20px;
}
 .footer {
   position:fixed;
   left:0px;
   bottom:0px;
   height:50px;
   width:100%;
   background:#fff;
}
.img-fluid{
height:50px !important;
}

@media (max-width: 767px) {
    .new-block{
    display:block;
      float: left;
    width: 50%;
}
.section-nav{
    border-left:0px;
}
.top_news_wrap{
    width:50%;
    float:left;
}
}

 @media (min-width: 768px) {

    /* show 3 items */
    .carousel-inner .active,
    .carousel-inner .active + .carousel-item,
    .carousel-inner .active + .carousel-item + .carousel-item,
    .carousel-inner .active + .carousel-item + .carousel-item + .carousel-item  {
        display: block;
    }
    
    .carousel-inner .carousel-item.active:not(.carousel-item-right):not(.carousel-item-left),
    .carousel-inner .carousel-item.active:not(.carousel-item-right):not(.carousel-item-left) + .carousel-item,
    .carousel-inner .carousel-item.active:not(.carousel-item-right):not(.carousel-item-left) + .carousel-item + .carousel-item,
    .carousel-inner .carousel-item.active:not(.carousel-item-right):not(.carousel-item-left) + .carousel-item + .carousel-item + .carousel-item {
        transition: none;
    }
    
    .carousel-inner .carousel-item-next,
    .carousel-inner .carousel-item-prev {
      position: relative;
      transform: translate3d(0, 0, 0);
    }
    
    .carousel-inner .active.carousel-item + .carousel-item + .carousel-item + .carousel-item + .carousel-item {
        position: absolute;
        top: 0;
        right: -25%;
        z-index: -1;
        display: block;
        visibility: visible;
    }
    
    /* left or forward direction */
    .active.carousel-item-left + .carousel-item-next.carousel-item-left,
    .carousel-item-next.carousel-item-left + .carousel-item,
    .carousel-item-next.carousel-item-left + .carousel-item + .carousel-item,
    .carousel-item-next.carousel-item-left + .carousel-item + .carousel-item + .carousel-item,
    .carousel-item-next.carousel-item-left + .carousel-item + .carousel-item + .carousel-item + .carousel-item {
        position: relative;
        transform: translate3d(-100%, 0, 0);
        visibility: visible;
    }
    
    /* farthest right hidden item must be abso position for animations */
    .carousel-inner .carousel-item-prev.carousel-item-right {
        position: absolute;
        top: 0;
        left: 0;
        z-index: -1;
        display: block;
        visibility: visible;
    }
    
    /* right or prev direction */
    .active.carousel-item-right + .carousel-item-prev.carousel-item-right,
    .carousel-item-prev.carousel-item-right + .carousel-item,
    .carousel-item-prev.carousel-item-right + .carousel-item + .carousel-item,
    .carousel-item-prev.carousel-item-right + .carousel-item + .carousel-item + .carousel-item,
    .carousel-item-prev.carousel-item-right + .carousel-item + .carousel-item + .carousel-item + .carousel-item {
        position: relative;
        transform: translate3d(100%, 0, 0);
        visibility: visible;
        display: block;
        visibility: visible;
    }

}

 /* Bootstrap Lightbox using Modal */

#profile-grid { overflow: auto; white-space: normal; } 
#profile-grid .profile { padding-bottom: 40px; }
#profile-grid .panel { padding: 0 }
#profile-grid .panel-body { padding: 15px }
#profile-grid .profile-name { font-weight: bold; }
#profile-grid .thumbnail {margin-bottom:6px;}
#profile-grid .panel-thumbnail { overflow: hidden; }
#profile-grid .img-rounded { border-radius: 4px 4px 0 0;}
</style>
  </body>
  <script>
  
$('#carouselExample').on('slide.bs.carousel', function (e) {

  
    var $e = $(e.relatedTarget);
    var idx = $e.index();
    var itemsPerSlide = 4;
    var totalItems = $('.carousel-item').length;
    
    if (idx >= totalItems-(itemsPerSlide-1)) {
        var it = itemsPerSlide - (totalItems - idx);
        for (var i=0; i<it; i++) {
            // append slides to end
            if (e.direction=="left") {
                $('.carousel-item').eq(i).appendTo('.carousel-inner');
            }
            else {
                $('.carousel-item').eq(0).appendTo('.carousel-inner');
            }
        }
    }
});


  $('#carouselExample').carousel({ 
                interval: 2000
        });


  $(document).ready(function() {
      $(".fa-spin").hide();
/* show lightbox when clicking a thumbnail */
    $('a.thumb').click(function(event){
      event.preventDefault();
      var content = $('.modal-body');
      content.empty();
        var title = $(this).attr("title");
        $('.modal-title').html(title);        
        content.html($(this).html());
        $(".modal-profile").modal({show:true});
    });

  });
  </script>
  <script type="text/javascript">
  $(window).on("scroll", function() {
  var scrollHeight = $(document).height();
  var scrollPosition = $(window).height() + $(window).scrollTop();
  if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
     var cnt =  $("#mainapp").attr("rel");
     var language =  $("#mainapp").attr("data-lan");
     var search =$$("#mainapp").attr("data-serach");
     if(cnt < 49 ){
         var inccnt =parseInt(cnt)+parseInt(5);
          $("#mainapp").attr("rel",inccnt);
          // $(".sbload").css("display","block");
     
      $.ajax({
     
      type:"POST",
      
      data:{cnt:cnt,language:language,search:search},
      
       url:"<?php echo $C->SITE_URL;?>addnewsfetch",
        

      success:function(response){
                     //$(".sbload").css("display","none");

          $("#mainapp").append(response);
          if(response == 1){
             
              
      }else{

          
      }
      }
      });
  }
  else{
      
  }
  }
});
$("#fullname").keyup(function(){
      $("#fullname").css("border-color","#66afe9");
            var user =$(this).val();
         var final = user.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '');
        
      
      
      var num = parseInt((Math.random() * 100), 10);
      var finaluser =  final+num;
      $("#username").val(finaluser);

    });
    $("#useremail").keyup(function(){
      $("#useremail").css("border-color","#66afe9");
      
    });
    $("#userpassword").keyup(function(){
      $("#userpassword").css("border-color","#66afe9");
      
    });
    $("#username").keyup(function(){
      $("#username").css("border-color","#66afe9");
      var regx = /^\S*$/; // a string consisting only of non-whitespaces

      if(regx.test($(this).val()) == false) {
      var finreg =  ($(this).val()).replace(/ /g,'');
      $("#username").val(finreg);
        
        return false;
           }
      
       var userarr = ["!", "@","#", "$","%","^","&","*","(",")","-",":",";","<",">","?",";",",","+","=","{","}","."];
       var lastchr = $(this).val().slice(-1);
       
       if($.inArray(lastchr, userarr) !== -1){
         var finregstr =  $(this).val().slice(0,-1);
      $("#username").val(finregstr);
        
        return false;
       }else{
         
       }
             
      
      
    });
      $("#birthdayday").change(function(){
    $("#birthdayday").css("border-color","#66afe9");

      
    });
    $("#birthdaymonth").change(function(){
    $("#birthdaymonth").css("border-color","#66afe9");

      
    });
    $("#birthdayyear").change(function(){
    $("#birthdayyear").css("border-color","#66afe9");

      
    });
    $("#loginuser").keyup(function(){
          $("#loginuser").css("border-color","#66afe9");
        
    });
    $("#loginpassword").keyup(function(){
          $("#loginpassword").css("border-color","#66afe9");
        
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
          var phoneno = /^\d{10}$/;  
  if(($("#useremail").val()).match(phoneno))  
  { 
  }  
  else  
  {  
    $("#useremail").css("border-color","red");
    return false;  
  }  
    }else{
      if (!ValidateEmail(email)) {

            $("#useremail").css("border-color","red");
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

    });
    var userpassword = $("#userpassword").val();
    if(userpassword ==""){
      $("#userpassword").css("border-color","red");
      return false;
    }
    $("#userpassword").keyup(function(){
      $("#userpassword").css("border-color","#66afe9");
      
    });
    $("#useremail").keyup(function(){
      $("#useremail").css("border-color","#66afe9");
       $("#emailerror").hide();
      
    });
    if(username ==''){
      $("#username").css("border-color","#66afe9");
      return false;
      
    }
    var day =$("#birthdayday").val();
    if(day ==''){
      $("#birthdayday").css("border-color","red");
      return false;
      
    }
    var month =$("#birthdaymonth").val();
    if(month ==''){
      $("#birthdaymonth").css("border-color","red");
      return false;
      
    }
    var year =$("#birthdayyear").val();
    if(year ==''){
      $("#birthdayyear").css("border-color","red");
      return false;
      
    }
    
    $("#username").keyup(function(){
      $("#username").css("border-color","#66afe9");
       $("#usererror").hide();
      
    });
          $(".fa-spin").show();
          $("#sub").attr("disabled","TRUE");

    if( full !='' && email !='' && username !=''){
      
     $.ajax({
     
      type:"POST",
      data:{email:email},
      crossDomain: true,
       url:"<?php echo $C->SITE_URL;?>signup",

      success:function(response){
        if(response.trim() !="OK"){
            alert("hii");
             $("#emailerror").css("display","block");
                     $("#emailerror").fadeIn();

          
          $("#emailerror").css("color","red").html(response);
          $("#emailerror").delay( 1200).fadeOut(500);
          $(".fa-spin").hide();
          $("#sub").attr("disabled",false);

              return false;
          
          
        }else{
          $.ajax({
     
      type:"POST",
      data:{username:username},
       url:"<?php echo $C->SITE_URL;?>signup",

      success:function(response){
        if(response.trim() !="OK"){
          if(response.trim() =="0"){
              $("#usererror").css("display","block");
                     $("#usererror").fadeIn();

          
          $("#usererror").css("color","red").html(response);
          $("#usererror").delay( 1200).fadeOut(500);
          $(".fa-spin").hide();
             $("#sub").attr("disabled",false);


           return false;
            
          }else{
              $("#usererror").css("display","block");
                     $("#usererror").fadeIn();

          
          $("#usererror").css("color","red").html(response);
          $("#usererror").delay( 1200).fadeOut(500);
          $(".fa-spin").hide();
          $("#sub").attr("disabled",false);

        
           return false;
          }
          
          
        }else{
            var gender =$(".profile_gender:checked").val();
             $.ajax({
     
      type:"POST",
      data:{fullname:full,strret_useremail:email,street_username:username,street_userpassword:userpassword,profile_birth_day:day,profile_birth_month:month,profile_birth_year:year,profile_gender:gender},
      crossDomain: true,
       url:"<?php echo $C->SITE_URL;?>finalsave",

      success:function(response){
          if(response == 1){
              
           var url = "<?php echo $C->SITE_URL;?>dashboard";
        $(location).attr('href',url);
      }else{
           if(response == 0){
              
           $("#finalerror").css("display","block");
      }
      }
      }
      });

        }
       
        
        
      }
      
    });
          
          
        }
       
        
        
      }
      
    });
  } 

    
  });
  
  

</script>
<script>
    $(".newlogin").click(function(){
        var loginuser =$("#loginuser").val();
        var loginpassword =$("#loginpassword").val();
     
        if(loginuser ==''){
              $("#loginuser").css("border-color","red");
      return false;
            
        }
         if(loginpassword ==''){
              $("#loginpassword").css("border-color","red");
      return false;
            
        }
        if($("#rem:checked")){
            var rechecked = 1;
            
        }else{
             var rechecked = 0;
            
        }
            $(".fa-spin").show();
          $(".newlogin").attr("disabled","TRUE");
        var rechecked =1;
           $.ajax({
     
      type : "post",
          async: true,
      dataType : "json",
      crossDomain: true,
      data:{email:loginuser,password:loginpassword,rechecked:rechecked},
       url:"<?php echo $C->SITE_URL;?>newsignin",

      success:function(response){
          if(response.success == 1){
              var url = "<?php echo $C->SITE_URL;?>dashboard";
             $(location).attr('href',url);
            
              
          }else{
              $("#loginerror").css("display","block");
                  $(".fa-spin").hide();
          $(".newlogin").attr("disabled",false);
                var refreshId = setInterval(function()
        {
            $("#loginerror").css("display","none");
        }, 500);
              
          }
      }
    });
    });
    
    
</script>
<style>
.stn{
    color:orange !important;
}
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 50px;
  height: 50px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
</html>

   