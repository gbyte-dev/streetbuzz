  <?php 
  global $db2, $C;
  $id=$D->userid ;
  if(empty($id)){
     $user_id=0; 
   } else{
   $user_id=$id;
   }
   //echo $user_id; die('jiii');
  //print_r($D->userid);die('----');  ?>  <link rel="stylesheet" href="<?php echo $C->SITE_URL;?>themes/FishingEnthusiastTheme/css/glyphicon.css">
    <!-- Keep this CSS link to load GlyphIcons in profile page @janeesh -->
  
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
    <style>
 .box-profile-wrapper {
      margin-top: 20px;
 }
 .box-profile {
    background-color: #fff;
    border-radius: 8px;
    padding: 10px;
    border: 1px solid #ccc;
     }
 .box-profile ul {
        padding: 14px;
     }
.box-profile ul li {
        margin-bottom: 6px;
      }
 .box-inner-profile {
    border: 0px solid #ccc;
    padding: 10px 0px 0px 0px;
    line-height: 16px;
}
.box-profile-inner {
  border-bottom:1px solid #E1E8ED;
  padding-bottom: 20px;
}
.box-profile-inner p {
  font-size: 16px;
  line-height: 20px;
}
.btn-profile-love {
    background-color: #0698d0;
    color: #fff;
    margin: 0px;
    font-weight: bold;
}
.btn-profile-follow {
border: 1px solid #0698d0;
color: #0698d0;
font-weight: bold;
background: #fff;
}
.profile-buttons {
    margin: 0px 0px 20px 0px;
}
.box-inner-profile ul {
    margin: 0px;
    padding: 0px;
}
.box-inner-profile ul li {
    list-style-type: none;
}
.box-profile-user-title {
font-size: 16px;
    font-weight: bold;
    color: #0076a3;
    padding: 3px;
    border-bottom: 1px solid #E1E8ED;
    margin-bottom: 5px;
}
.box-profile-title {
    font-size: 18px;
    font-weight: bold;
    color: #0076a3;
    padding: 3px;
    margin-top: 20px;
}
.box-profile-inner .fa-icon-profile-blue {
width : 30px;    
}
.txt-small-profile {
  font-family: roboto;
  font-size: 16px;
  font-weight: normal!important;
}
.fa-icon-profile {
  color: #333;
}
.fa-icon-profile-blue {
  color: #22abdd;
}
ul.fa-icon-interest li {
  font-size: 16px;
  border-bottom:1px dotted #E1E8ED;
}
ul.fa-icon-interest li .fa-icon-profile-blue {
  width: 30px;
  height: 30px;
  font-size: 18px;
  line-height: 36px;
}
.box-profile-footer .count {
    color: #00BFFF;
    font-size: 16px;
    font-weight: bold;
}
.box-profile-footer .count-title {
    color: #66757F;
    font-size: 10px;
    font-weight: bold;
    text-transform: uppercase;
}
.box-profile-footer-center {
    padding: 0 !important;
    text-align: center;    
    line-height: 16px;
}
.profile-buttons {
  position: absolute;
  top: 30px;
  right:40px;
}
.profile-buttons-love {
  cursor: pointer;
  position: absolute;
  top: 150px;
  left: 100px;
}
.glyphicon-heart-love {
    font-size: 48px;
    padding:0;
    color: #EF597B;
  }
.glyphicon-heart-unlove {
    font-size: 48px;
    padding:0;
    color:#fff;
  }
.count-love {
  color:#fff;
  font-size: 32px;
}
a[data-action="follow"] .tooltip span {
    cursor: pointer;
    border: 1px solid #22abdd;
    font-weight: normal;
}
a[data-action="unfollow"] .tooltip span {
    cursor: pointer;
    border: 1px solid #22abdd;
    font-weight: normal;
}
a[data-action="follow"] .tooltip span:active, a[data-action="unfollow"] .tooltip span:active {
  box-shadow: 1px 2px 10px #22abdd;
  color : #f7941d !important;
  }

 /* START : Tags specialization */
  .tags-specialization {
    border: 1px solid #22abdd;
    border-radius: 4px;
    padding: 2px 5px;
    background: #22abdd;
    color: #fff;
    cursor: pointer;
    transition: .5s;
    margin: 5px;
    display: inline-block;
    font-size: 14px;
  }
  .tags-specialization:hover {
    background: #fff;
    color: #22abdd;
  }
  /* END : Tags specialization */


  /* START : Areas Of Interest */
.info-box {
  display: block;
  width: 100%;
  box-shadow: 0px 1px 1px 1px rgba(0, 0, 0, 0.2);
  border-radius: 2px;
  margin-bottom: 15px;
}
.info-box small {
  font-size: 14px;
}
.info-box-content {
  padding: 5px 5px 5px 10px;
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
.info-box-text {
  font-size: 15px;
  font-weight:normal;
  color: #66757F;
}
  /* END : Areas Of Interest */


/* START : Checkbox style */
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
  top: 1px;
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
.contain{
    position:relative;
}
.profile-cover {
  background-image: url(<?php echo $C->STORAGE_URL.'avatars/'.$D->userdetails->cover; ?>);
  border-radius: 0px 0px 0 0;
/*  width:730px;
*/  height: 280px;
  background-size:cover;
  color: #fff;
  font-size: 11px;
  padding: 5px 5px 0px 5px;
}
.circle{
    position:absolute;
    bottom:120px;
    left:10px;
}
.dropdown.share.icon-ftr.profile {
    padding: 0px !important;
}
/* END : Checkbox style */
</style>
  </head>
  <body>
  <div class="container-fluid">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-profile-wrapper">

    <!-- Start : Center Main Buzz Content  -->
    <div class="box-profile-inner zeropadding">
  <?php

      
  if($D->userdetails->avatar !=''){
  $src='<img src="'.$C->STORAGE_URL.'avatars/thumbs4/'.$D->userdetails->avatar.'" class="img-circle img-responsive" width="120" >';
}else{
    // $src ='<div class="data-row-1 circle-who-to-follow" data-userid="'.$D->uid.'">'.ucfirst(substr($D->userdetails->username,0,1)).'</div>';

}
// print_r($D);die('-----');
  ?>
 <div class="profile-cover">
    <div class="circle">
          
 <?php 
 
                                 
 if( !$this->user->is_logged ) { ?>
        <!--<a class="btn  d-none d-lg-inline-block mb-3 mb-md-0 ml-md-3 credit " data-toggle="modal" data-target="#myModal1">-->
            <a href="<?php echo $C->SITE_URL ; ?>home">
           <?php echo $src ?>
        </a>
 <?php }else{ ?>   
 
            <?php echo $src ?>
    
    
 <?php } ?>
        </div>
</div>

<?php $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
 
$url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>

            <div class="box-inner-profile">
                 <div class="row">
            <div class="col-sm-10 box-profile-user-title">
             <?php echo $D->userdetails->fullname;?><br /> 
            <span class="box-sub-desc">@<?php echo $D->userdetails->username;?></span></a>
            </div>
            
             <div class="col-sm-2">
               
                <div class="dropdown share icon-ftr profile" >
                    
                      <span class="count" id="sharecount"><?php 
                         $whom=$D->userdetails->id;
                         $dbConnection = $GLOBALS['db2'];
                         $query = $dbConnection->query('SELECT * FROM `profile_share` WHERE whom ='.$whom);
                         $num_rows = mysqli_num_rows($query);
                         echo $num_rows;
                  
                          ?></span>
                 <a style="cursor:pointer;"  class="menu-btn"><img class="icons" style="background-color:blue;" src="<?php echo $C->SITE_URL.'themes/FishingEnthusiastTheme/images/social_share.png'; ?>" title="Share" width="30" height="30"/></a>
                 <ul style="text-align:left;padding: 10px;
                                " class="menu-options" id="<?php echo $user_id; ?>"  data_id1="<?php echo $D->userdetails->id ?>">
                     
                      <li><a href="https://web.whatsapp.com/send?text=<?= $url ?>" data-action="share/whatsapp/share"  target="_blank" >Whatsapp</a></li>
                      
                      <li><a href="http://www.facebook.com/sharer.php?u=<?= $url ?>"  target="_blank" >Facebook</a></li>
                      <li><a href="http://twitter.com/intent/tweet?url=<?= $url ?>"  target="_blank" >Twitter</a></li>
                      
                    <li><a  data_id="'.$buff->post_id.'" href="http://www.linkedin.com/shareArticle?mini=<?= $url ?>"  target="_blank" >Linkedin</a></li>
                  <li><a href="http://plus.google.com/share?url=<?= $url ?>"  target="_blank" >Google Plus</a></li>
                  
                 </ul>
              
              </div>
    
             </div> 
             
            <div class="col-md-12 box-profile-footer">
            
            <a href="<?php echo $C->SITE_URL?><?php echo $D->userdetails->username; ?>/tab:all"><div class="col-xs-3 box-profile-footer-center"><span class="count-title">BUZZES</span> <br /><span class="count"><?php  echo $D->buzzes;?></span></div></a>
            
            <a href="<?php echo $C->SITE_URL?><?php echo $D->userdetails->username; ?>/tab:friends/subtab:ifollow"><div class="col-xs-3 box-profile-footer-center"><span class="count-title">FOLLOWING</span> <br /><span class="count"><?php  echo $D->following;?></span></div></a>
            
            <a href="<?php echo $C->SITE_URL?><?php echo $D->userdetails->username; ?>/tab:friends/subtab:followers"><div class="col-xs-3 box-profile-footer-center"><span class="count-title">FOLLOWERS</span> <br /><span class="count"><?php  echo $D->followers;?></span></div></a>

            <a href="<?php echo $C->SITE_URL?><?php echo $D->userdetails->username; ?>/tab:groups">
            <div class="col-xs-3 box-profile-footer-center"><span class="count-title">GROUPS</span> <br /><span class="count"><?php  echo $D->groupcnt;?></span></div></a>
            <!--POST PROFILE-->
            
             <a href="<?php echo $C->SITE_URL?><?php echo $D->userdetails->username; ?>/tab:groups">
          <!--  <div id="postprofiles" class="col-xs-3 box-profile-footer-center"><span class="count-title">PROFILE SHARE</span> <br /></div>--></a>
            
            <!--end-->
            </div><!--/ End: box-footer -->
</div>
            </div><!--/ End: box-inner-profile -->

   
    </div><!-- End : Center Main Buzz Content  -->


      <div class="col-xs-12 box-profile-title">
      ABOUT
      </div>
      <div class="col-xs-12  box-profile-inner zeropadding">
      <p><?php echo $D->userdetails->about_me ?>.</p>
      <span class="glyphicon glyphicon-map-marker fa-icon-profile-blue"></span>
      <span class="txt-small-profile"><?php echo $D->userdetails->location; ?></span> 
     <!-- <br /><span class="glyphicon glyphicon-earphone"></span> +91 8884030271
      <br /><span class="glyphicon glyphicon-globe"></span> www.streetbuzz.co.in-->
    <br />
    <?
    if($D->userdetails->gender =="m"){
      $gender ="Male";
    }
     if($D->userdetails->gender =="f"){
      $gender ="Female";
    }
    
    ?>
    <i class="fa fa-venus-mars fa-icon-profile fa-icon-profile-blue" aria-hidden="true"></i> <span class="txt-small-profile"><?php echo $gender; ?></span> 
  <br />
  <i class="fa fa-birthday-cake  fa-icon-profile fa-icon-profile-blue" aria-hidden="true"></i> <span class="txt-small-profile"><?php echo $D->birthdayday;?></span> 
  <br />
 <?php  if($D->userdetails->email !== undefined && $D->userdetails->email != ""){ ?>
  <i class="fa fa-envelope  fa-icon-profile fa-icon-profile-blue" aria-hidden="true"></i> <span class="txt-small-profile"><?php echo $D->userdetails->email;?></span>   
  <?php } ?>  
  </div>
    
    
    
    
<!-- START : SPECIALIZATION -->
<div class="pull-right">
<div class="box-profile-title">
      SPECIALIZATION
      </div>
      <div class=" box-profile-inner zeropadding">
    <?php 
    if(!empty($D->tags)){
           $tags =explode("#",$D->tags);

      foreach($tags as $keys=>$vals)
      ?>
      <span class="tags-specialization">#<?php echo $vals;?></span>
    <?php }else{ ?>
           <span class="tags-specialization">No specialization</span>

  <?php   }?>
      </div>
      
      </div>
<!--/ END : SPECIALIZATION -->



<!-- START : AREAS OF INTEREST -->
<div class="col-xs-12 box-profile-title">
      AREAS OF INTEREST
      </div>
<div class="col-xs-12  box-profile-inner">
  
<ul class="fa-icon-interest list-unstyled"> 
<?php  if(!empty($D->areas)){
  foreach($D->areas as $keys=>$vals){
    ?>
<li><i class="<?php echo $vals->image?> fa-icon-profile-blue"></i> <?php echo $vals->cat_name?></li> 
<?php } }else{ ?> <span class="tags-specialization">No areas of interest</span>
<?php }          
  ?>
</ul>   
  
</div>
<!--/ END : AREAS OF INTEREST -->   
    
    
    

    <?php 
   if($D->userid !=$D->mytargetuserid){
  
  ?>
    <div class="col-xs-12 profile-buttons zeropadding">
  <?php
  if(!empty($D->follow)){ ?>
   <a class="action-btn user-action disconnect-user" data-action="unfollow" data-value="<?php echo $D->mytargetuserid; ?>" data-namespace="users" data-role="services"><span class="tooltip" style="top:-5px; right:0px"><span class="glyphicon glyphicon-user user-icon-flw"></span></span></a>  
    
<?php }else{ ?>
  <a class="action-btn user-action disconnect-user" data-action="follow" data-value="<?php echo $D->mytargetuserid; ?>" data-namespace="users" data-role="services"><span class="tooltip" style="top:-5px; right:0px"><span class="glyphicon glyphicon-user user-icon-flw"></span></span></a>  
<?php }
  
  ?>

    </div>
   <?php } ?>
    <?php if(!empty($D->loved)){ 
   if($D->lovedcnt !=0){
    $D->lovedcnt =$D->lovedcnt;
  }else{
    $D->lovedcnt ='';
  } 
   ?> 
   <div class="unlove profile-buttons-love">
   <a class='action-btn user-action disconnect-user' data-action="unlove" data-value="<?php echo $D->mytargetuserid; ?>"   data-namespace="users" data-role="services">
   <span class="glyphicon glyphicon-heart glyphicon-heart-love "></span></a><a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showloves" data-value="<?php echo $D->datavalue;?>"><span class="count-love" style="color:red;"><?php echo $D->lovedcnt;?></span>  </a>

    </div>
   <?php }else{
    if($D->lovedcnt !=0){
    $D->lovedcnt =$D->lovedcnt;
  }else{
    $D->lovedcnt ='';
  }    ?>
  
   <?php if( !$this->user->is_logged ) { ?>
        <!--<a class="btn  d-none d-lg-inline-block mb-3 mb-md-0 ml-md-3 credit " data-toggle="modal" data-target="#myModal1">-->
            
                
    <div class="love profile-buttons-love">
        
        <a href="<?php echo $C->SITE_URL ; ?>home">
            
   <!--<a class="action-btn user-action disconnect-user" data-action="love" data-value="<?php echo $D->mytargetuserid; ?>" data-namespace="users" data-role="services">-->
   <span class="glyphicon glyphicon-heart glyphicon-heart-unlove "></span></a><a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showloves" data-value="<?php echo $D->datavalue;?>"><span class="count-love" style="color: red;"><?php echo $D->lovedcnt;?></span> </a> 

    </div>
    <?php }else{ ?>
    
     <div class="love profile-buttons-love">
   <a class="action-btn user-action disconnect-user" data-action="love" data-value="<?php echo $D->mytargetuserid; ?>" data-namespace="users" data-role="services">
   <span class="glyphicon glyphicon-heart glyphicon-heart-unlove "></span></a>
   <a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showloves" data-value="<?php echo $D->datavalue;?>"><span class="count-love" style="color: red;"><?php echo $D->lovedcnt;?></span> </a> 

    </div>
     
  <?php } } ?>


   


    </div>

 
    </div>
<style>
.hero-image {
  background-image: url("<?php echo $C->STORAGE_URL.'avatars/'.$D->userdetails->cover;?>");
  background-color: #cccccc;
  height: 500px;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  position: relative;
}

.hero-text {
  text-align: center;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: white;
}
.imgcover{
    width: 800px;
    height: 280px;
    position: relative;
    background-size: cover
}
</style>


<!--
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

-->
<script>

$(document).ready(function() 
 {
    $('.menu-options').click(function(e) 
    { 
      var who = ($(this).closest("ul").attr("id"));
      var whom = ($(this).closest("ul").attr("data_id1"));  
    
          $.ajax({
                url: 'services/activities/postprofile',
                type: 'POST',
                data: {who: who, whom: whom},
                success: function (result) {
                    
                    
    $.each(result,function(key,value){
                  
var purpose = value.html;

var qq1 =purpose.replace(/\.|,/g, '');
var qq =qq1.replace(/\'|,/g, '');              
            console.log(qq);
          //alert(qq);           
                    
                 $(".count").html(qq);   
                })    
                    
                    
                //      alert(result);
                //   $("#postprofiles").append(result);
                }
            });
    

        });
    });

</script>

