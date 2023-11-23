
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

<!--<link href="<?php //echo $C->SITE_URL;?>themes/FishingEnthusiastTheme/css/flatuicss.css?v=3.6.0" type="text/css" rel="stylesheet" />
-->

<link href="<?php echo $C->SITE_URL;?>assets/home/bootstrap4.css" rel="stylesheet" async>
    <link  href="<?php echo $C->SITE_URL;?>assets/home/font-awesome.min.css" rel="stylesheet" async>
    
    
        <link  href="<?php echo $C->SITE_URL;?>assets/home/slider-home.css" rel="stylesheet" async>




<link href="<?php echo $C->SITE_URL;?>assets/home/docs.css" rel="stylesheet" async>
<script src="<?php echo $C->SITE_URL ?>assets/home/jquery3min.js" ></script>

<script src="<?php echo $C->SITE_URL;?>assets/home/bootstrap4min.js"></script>
 <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDArYN-93IBn3EBtCMXBoSoznKr3F1wPxE&libraries=places&v=2.exp"></script>
 <style>
/* width */
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey; 
  border-radius: 10px;
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #0088ffe6; 
  border-radius: 10px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #b30000; 
}
:checked + label {
    border :none;
}
</style>
<style>


#myBtn {
  display: none;
  position: fixed;
  bottom: 20px;
  right: 30px;
  z-index: 99;
  font-size: 18px;
  border: none;
  outline: none;
  background-color: #1792fd;
  color: white;
  cursor: pointer;
  padding: 15px;
  border-radius: 4px;
  right:5px;
}

#myBtn:hover {
  background-color: #555;
}
</style>
</head>
<body>
 <?php
 $css ="navbar navbar-expand navbar-dark flex-column flex-md-row bd-navbar";
$btns ='';
$imgwidth ='';
$regularimagewidth ='width="80%"';
$regularimageheight ='';
$displaydatawidth ='74';
$displaydatawidthimg ='25';
$displaydatawidthimg1='375';
$wordcnt      =500;
?>
<header class="<?php echo $css;?>">
  <a class="navbar-brand mr-0 mr-md-2" href="/" aria-label="Bootstrap">
  <img width="<?php echo $imgwidth;?>" src="<?php echo $C->SITE_URL?>static/images/streetbuzz.jpg" class="img-responsive">
</a>
  <ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">
<li class="nav-item">
<form class="bd-search d-flex align-items-center">
  <input type="search" class="form-control"  nabd-toc-itemme="search" id="search-input" placeholder="Search..." aria-label="Search for..." autocomplete="off" value="">
  <button type="submit" class="searchButton">
    <i class="fa fa-search"></i>
    </button>
</form>
</a>
    </li>

  </ul>

</header>
<div class="row">
    <div class="col-sm-3">
        </div>
    <div class="col-sm-6">
<h6 onclick="backtostate();" id="backbutton" style="display:none;float:right;margin-top:5px;color:white;cursor:pointer;" class="bg-danger">Back</h6>
</div> 
<div class="col-sm-3">
        </div>
    <div class="col-sm-3">
        </div>
<div class="col-sm-6">
<div class="form-group margin">
    <h3 style="text-align: center;font-size:18px;" id="state-label">We dont yet serve real-time news at your location.<br>
Select the state where you want to enjoy real-time news.</h3>
    <div class="row" id="append-data-city">
</div>
    
    
    
<div class="row" id="append-data-state">
<?php 
    $querry=$db2->query('SELECT * FROM `state`');
    foreach ($querry as $value) {
     ?>
    
<div class="col-sm-4 mb-2">
        
        <?php if($value['fileName']){ ?>
        
       <span onclick="getlocationbystate(<?php echo $value['id'] ?>)" href="<?php echo $C->SITE_URL?>home?st=<?php echo $value['id'].'&name='.$value['name']; ?>" >
        <img style="width:100%;height:150px;border-radius: 10px;cursor:pointer;" alt="Image3" src="<?php echo $C->SITE_URL?>assets/images/<?php echo $value['fileName']; ?>">
        </span>
        <?php }else{?>
        <span href="<?php echo $C->SITE_URL?>home?st=<?php echo $value['id'].'&name='.$value['name']; ?>" >
        <img style="width:100%;height:150px;border-radius: 10px;cursor:pointer;" alt="Image3" src="<?php echo $C->SITE_URL?>storage/attachments/1/627e45394271d.png">
        </span> 
        <?php } ?>
        <span onclick="getlocationbystate(<?php echo $value['id'] ?>)" href="<?php echo $C->SITE_URL?>home?st=<?php echo $value['id'].'&name='.$value['name']; ?>" >
        <span class="mt-2" style="font-size: small; color: #2c6b82;cursor:pointer;"><?php echo $value['name']; ?> - <?php echo $value['native']; ?></span>
        </span>
        </div>
    <?php }  ?>
</div>
</div>
 
  <div class="margin10">
    <button  class="btn btn-primary btncredit pull-right" id="sub" style="display:none;">Update Location</button>
    </div>
</div> 
<div class="col-sm-3">
        </div>
</div>

 <br><br>
    </div>
  </div>
  <script>

      function getlocationbystate(stateid) {
    
       $.ajax({
		type: "POST",
		url: "<?php  echo $C->SITE_URL;?>admin/loadcity",
        	data:{stateid:stateid},
		success: function(data){
		 	 $('#backbutton').css('display','block'); 
		 	 $('#append-data-state').css('display','none');
		 	 $('#state-label').css('display','none');
             $('#sub').css('display','block');
         $('#append-data-city').css('display','flex'); 
		 $('#append-data-city').html(data);
		}
		});
		
}

  </script>
  <script>
      $("#sub").click(function () {
    var locationids = [];
    $('input[name="locationids[]"]:checked').each(function () {
        locationids.push($(this).val());
    });
if (locationids.length === 0) {
        alert('Please select at least one location.');
        return;
    }

    var dataString = locationids.join(',');
   // alert('Selected Locations: ' + dataString);

$.ajax({
    type: "POST",
    data: { locationids: dataString },
    dataType: 'json',
    url: "<?php echo $C->SITE_URL; ?>locationaction",
    success: function (response) {
       // alert(response);
        window.location.href = "<?php echo $C->SITE_URL; ?>dashboard";
        console.log(response);
    } 
});


});
function backtostate() {
    	 	 $('#backbutton').css('display','none'); 
		 	 	 $('#append-data-city').css('display','none');
		 	 	 	 $('#append-data-state').css('display','block');
                     $('#append-data-state').css('display','flex');
                     	 $('#state-label').css('display','block');
                     	 $('#sub').css('display','none');
		 	 	 	 
}
  </script>
</body>
 </html>