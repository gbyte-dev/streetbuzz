    <link rel="stylesheet" href="<?php echo $C->SITE_URL;?>themes/FishingEnthusiastTheme/css/glyphicon.css">
    <!-- Keep this CSS link to load GlyphIcons in profile page @janeesh -->

    
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
  width:730px;
  height: 280px;
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
/* END : Checkbox style */
</style>
  </head>

  <body>
  <?php
  ?>
  <div class="container-fluid">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-profile-wrapper">

    <!-- Start : Center Main Buzz Content  -->
    <div class="col-xs-12 box-profile-inner zeropadding">
  <?php

      
  if($D->userdetails->avatar !=''){
  $src='<img src="'.$C->STORAGE_URL.'avatars/thumbs4/'.$D->userdetails->avatar.'" class="img-circle img-responsive" width="120" >';
}else{
     $src ='<div class="data-row-1 circle-who-to-follow" data-userid="'.$D->uid.'">'.ucfirst(substr($D->userdetails->username,0,1)).'</div>';

}
//print_r($D);
  ?>
 <div class="profile-cover">
    <div class=circle>
          
 <?php if( !$this->user->is_logged ) { ?>
        <!--<a class="btn  d-none d-lg-inline-block mb-3 mb-md-0 ml-md-3 credit " data-toggle="modal" data-target="#myModal1">-->
            <a href="<?php echo $C->SITE_URL ; ?>home">
           <?php echo $src ?>
        </a>
 <?php }else{ ?>   
 
            <?php echo $src ?>
    
 <?php } ?>
        </div>
</div>

            <div class="box-inner-profile">
            <div class="box-profile-user-title">
             <?php echo $D->userdetails->fullname;?><br /> 
            <span class="box-sub-desc">@<?php echo $D->userdetails->username;?></span></a>
            </div>

            <div class="col-md-12 box-profile-footer">
            
            <a href="<?php echo $C->SITE_URL?><?php echo $D->userdetails->username; ?>/tab:all"><div class="col-xs-3 box-profile-footer-center"><span class="count-title">BUZZES</span> <br /><span class="count"><?php  echo $D->buzzes;?></span></div></a>
            
            <a href="<?php echo $C->SITE_URL?><?php echo $D->userdetails->username; ?>/tab:friends/subtab:ifollow"><div class="col-xs-3 box-profile-footer-center"><span class="count-title">FOLLOWING</span> <br /><span class="count"><?php  echo $D->following;?></span></div></a>
            
            <a href="<?php echo $C->SITE_URL?><?php echo $D->userdetails->username; ?>/tab:friends/subtab:followers"><div class="col-xs-3 box-profile-footer-center"><span class="count-title">FOLLOWERS</span> <br /><span class="count"><?php  echo $D->followers;?></span></div></a>

            <a href="<?php echo $C->SITE_URL?><?php echo $D->userdetails->username; ?>/tab:groups">
            <div class="col-xs-3 box-profile-footer-center"><span class="count-title">GROUPS</span> <br /><span class="count"><?php  echo $D->groupcnt;?></span></div></a>
            
            </div><!--/ End: box-footer -->

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
<div class="col-xs-12 box-profile-title">
      SPECIALIZATION
      </div>
      <div class="col-xs-12  box-profile-inner zeropadding">
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
   <span class="glyphicon glyphicon-heart glyphicon-heart-love"></span></a><a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showloves" data-value="<?php echo $D->datavalue;?>"><span class="count-love"><?php echo $D->lovedcnt;?></span>  </a>

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
   <span class="glyphicon glyphicon-heart glyphicon-heart-unlove"></span></a><a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showloves" data-value="<?php echo $D->datavalue;?>"><span class="count-love"><?php echo $D->lovedcnt;?></span> </a> 

    </div>
    <?php }else{ ?>
    
     <div class="love profile-buttons-love">
   <a class="action-btn user-action disconnect-user" data-action="love" data-value="<?php echo $D->mytargetuserid; ?>" data-namespace="users" data-role="services">
   <span class="glyphicon glyphicon-heart glyphicon-heart-unlove"></span></a><a class="showpostlikes_btn" href="" data-role="services" data-namespace="activities" data-action="showloves" data-value="<?php echo $D->datavalue;?>"><span class="count-love"><?php echo $D->lovedcnt;?></span> </a> 

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
</style>


