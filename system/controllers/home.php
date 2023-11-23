<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 'On');*/

//die("hello1");

  if( $this->user->is_logged ) {
    $this->redirect('dashboard');
  }

  $this->load_langfile('inside/global.php');
  $this->load_langfile('outside/home.php');
  $this->load_langfile('outside/signin.php');
  $ismobile=0;
  if( preg_match( "#iPhone#i", $_SERVER['HTTP_USER_AGENT'] )  || preg_match( "#iPod#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Android#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Phone#i", $_SERVER['HTTP_USER_AGENT'] )) {
	  $ismobile=1;
  }

?>

<?php
$locationid = "";
$location=$_COOKIE['location'];
if($location !== undefined && $location !== null  ){
    $locationarr =  json_decode($location,true);
    $citygoogle = "";
	    $districtgoogle = "";
	    $stategoogle = "";
	    foreach($locationarr as $keys=>$vals){
	        if($vals["city"] !== undefined && isset($vals["city"])){
	            $citygoogle = $vals["city"];
	       }
	        if($vals["district"] !== undefined && isset($vals["district"])){
	            $districtgoogle = $vals["district"];
	       }
	       if($vals["state"] !== undefined && isset($vals["state"])){
	            $stategoogle = $vals["state"];
	       }
	    }
	     if($citygoogle !== "" && $districtgoogle !== "" ){
	        $locationres = $this->network->findsblocation($citygoogle,$districtgoogle);


	        
	        if(!empty($locationres[0]->id)){
	            $locationid = $locationres[0]->id;
	            
	            	        

	        }
	     }

    
}

$network 	= & $GLOBALS['network'];
$tags       = $network->get_recent_posttags();
if(!empty($locationid)){
    $homeadsres =	$db2->query('SELECT ads.id,ads.big_image,hds.ads_image FROM ads_info as ads 
                INNER JOIN ads_home_locations as adl ON adl.ad_id = ads.id
                INNER JOIN  home_ads_images AS hds ON hds.ad_id=ads.id
                
          
           WHERE ads.ads_type="8" and status="1" and adl.location_id="'.$locationid.'" GROUP BY ads.id order by ads.id desc LIMIT 20');
           $homeaddscnt = $homeadsres->num_rows;
           if($homeaddscnt == 0){
               $homeadsres =	$db2->query('SELECT ads.id,ads.big_image,hds.ads_image FROM ads_info as ads 
                LEFT JOIN  home_ads_images AS hds ON hds.ad_id=ads.id
          
           WHERE ads.ads_type="8" and status="1" AND 	is_priority ="yes" order by ads.id desc LIMIT 20');
           }


         
}else{
$homeadsres =	$db2->query('SELECT ads.id,ads.big_image,hds.ads_image FROM ads_info as ads 
                LEFT JOIN  home_ads_images AS hds ON hds.ad_id=ads.id
          
           WHERE ads.ads_type="8" and status="1" AND 	is_priority ="yes" order by ads.id desc LIMIT 20');
}
$homeaddscnt = $homeadsres->num_rows;
if($homeaddscnt > 0){
 while($singleresult[]    = $db2->fetch_object($homeadsres)){ 
 }
}else{
    $singleresult = [];
}
?>
<?php


  function parse_date1($timestamp, $return_words = 'auto', $return_dt_format = '%d-%b-%Y, %H:%M')    
    {   
        if ($return_words == FALSE) {   
            return strftime($return_dt_format, $timestamp); 
        }   
        $time = time() - $timestamp;    
        $h    = floor($time / 3600);    
        $time -= $h * 3600; 
        $m = floor($time / 60); 
        $time -= $m * 60;   
        $s = $time; 
        if ($return_words === 'auto' && $h >= 12) { 
            return strftime($return_dt_format, $timestamp); 
        }   
        $txt = '##BEFORE## ';   
        if ($h > 0) {   
            $txt .= $h; 
            $txt .= $h == 1 ? ' ##HOUR##' : ' ##HOURS##';   
        }   
        if ($h >= 3) {  
            $txt .= ' ##AGO##'; 
            return post::_parse_date_replace_strings($txt); 
        }   
        if ($m > 0) {   
            if ($h > 0) {   
                $txt .= ' ##AND## ';    
            }   
            $txt .= $m; 
            $txt .= $m == 1 ? ' ##MIN##' : ' ##MINS##'; 
            if ($h > 0) {   
                $txt .= ' ##AGO##'; 
                return post::_parse_date_replace_strings($txt); 
            }   
        }
    }


  function getlikescount($table,$postid){
      global $db2;
  
 $r      = $db2->query('SELECT *  FROM  posts_details
          WHERE post_id="'.$postid.'"');

$rows=$r->num_rows;

if($rows>0){
while($result = $db2->fetch_object($r)){
    $data= $result->$table;
}
return $data;
}

else {
    return 0;

}
  }
  
  
  
        function is_countpollanswer($pollid, $pollanswerid)  
    {   
              global $db2;

     //   echo $pollid.'----------'.$pollanswerid; die('ddddddddddd');
        $data = array();    
        $r    = $db2->query('SELECT * FROM post_poll_votes WHERE POLL_ID="' . $pollid . '" and ANSWER_ID="' . $pollanswerid . '"', FALSE);  
        
        $rows=$r->num_rows;

if($rows>0){
    
        while ($result = $db2->fetch_object($r)) {    
            $data[] = $result;  
        }   
        return $data; 
      //  print_r($data); die;
    }  
    else {
        return;
    }
  
    }
  
  
    function is_poll($postid){
        
        
      global $db2;
      
      
       
 $r      = $db2->query('SELECT posts.*,pol.*,pola.* FROM polls as pol inner join polls_answers as pola on pol.poll_id=pola.poll_id inner join posts on posts.id=pol.posts_id  WHERE posts_id="' . $postid . '"');

   while ($result = $db2->fetch_object($r)) {    
            $data[] = $result;  
        }   
        
 return $data;
 
        
        
  }

  
  
  
  
  
  
  
  
  
  
  
  
  
  
  

 
  function get_polequestion($postid){
      
      global $db2;
  
 $r      = $db2->query('SELECT *  FROM  polls 
          WHERE posts_id="'.$postid.'"');
           
$rows=$r->num_rows;

if($rows>0){
while($result = $db2->fetch_object($r)){
    
     
    $data= $result;
}
return $data;
}

else {
    return 0;

}
  }
 
 
 
 
 
   
 
 
 


  function getattachments($postid){
       global $db2;
  
 $r      = $db2->query('SELECT *  FROM  posts_attachments
          WHERE post_id="'.$postid.'"');

$rows=$r->num_rows;


$all_attach = array();
if($rows>0){
while($result = $db2->fetch_object($r)){
    array_push($all_attach,$result);

}
return $all_attach;
}

else {
    return 0;

}
  }





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
 .card{
         box-shadow: -3px 4px 6px 2px #e9e8e8;
    margin: 10px;
 }
 .modal-backdrop {
    z-index: auto;
}



 </style>
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

<script>


 

    function getLocations() {
      
        if (navigator.geolocation) { 
          navigator.geolocation.getCurrentPosition(showPositions,showError);
           console.log("Getting location...");
        } else { 
           alert("Geolocation is not supported by this browser.");
        }
       
    }

    function showPositions(position) {
  
     getAddressFromCoordinates(position.coords.latitude,position.coords.longitude);
     
    }
    
function getAddressFromCoordinates(latitude, longitude) {
    // Use the Google Maps Geocoding API to get the address
    const apiKey = 'AIzaSyDArYN-93IBn3EBtCMXBoSoznKr3F1wPxE&libraries=places&v=2.exp';
    var x =document.getElementById('demos');
    const apiUrl = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key=${apiKey}`;

    // Make a request to the API
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            // Check if the response is successful
            if (data.status === 'OK') {
                const address = data.results[0].formatted_address;
               
                var administrativeAreaLevel3=data.results[0].address_components[4].long_name;
                
               // const administrativeAreaLevel3 = getAdministrativeAreaLevel3( data.results[0].address_components[4]);
               x.innerHTML = administrativeAreaLevel3;
        
            } else {
              
                console.error("Failed to retrieve address.");
            }
        })
        .catch(error => {
          
            console.error("Error occurred while fetching the address:", error);
        });
}    
    
    
    


  function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            console.log("User denied the request for Geolocation.");
            break;
        case error.POSITION_UNAVAILABLE:
           console.log("Location information is unavailable.");
            break;
        case error.TIMEOUT:
            console.log("The request to get user location timed out.");
            break;
        case error.UNKNOWN_ERROR:
           console.log("An unknown error occurred.");
            break;
    }
}



document.addEventListener("DOMContentLoaded",  
        function () { 

        window.onload = getLocations;
        
        const myTimeout = setTimeout(function(){
          var x =document.getElementById('demos').innerHTML;
           
          
          
         	$.ajax({
		type: "POST",
		url: "<?php  echo $C->SITE_URL;?>sb_location_masterid",
		data:'keyword='+x,
		success: function(data){
		   
	  var da=data.trim();
	document.getElementById('locationid').value=da; 

		}
		}); 
          
        }, 8000);
        
        
     //   sb_location_master
        });





function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}


function selectCountry(val,location) {
$("#userlocation").val(location);
$("#userlocation").attr("data-location-id",val);
$("#suggesstion-box").hide();
}
 function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else { 
   // x.innerHTML = "Geolocation is not supported by this browser.";
  }
}

function showPosition(position) {
    var google_map_position = new google.maps.LatLng( position.coords.latitude, position.coords.longitude );
    var google_maps_geocoder = new google.maps.Geocoder();
google_maps_geocoder.geocode(
    { 'latLng': google_map_position },
    function( results, status ) {
        let adcomponents = results ;
        console.log(results);
          var cityarr =[];
          for(var k=0;k<adcomponents.length ;k++){
              var cityobj = {};
              if(adcomponents[k]["types"][0] == "locality"){
                  cityobj["city"] = adcomponents[k]["address_components"][0]["long_name"];
              }
               if(adcomponents[k]["types"][0] == "administrative_area_level_3"){
                   cityobj["district"] = adcomponents[k]["address_components"][0]["long_name"];
              }
              if(adcomponents[k]["types"][0] == "administrative_area_level_2"){
                   cityobj["district"] = adcomponents[k]["address_components"][0]["long_name"];
              }
              if(adcomponents[k]["types"][0] == "administrative_area_level_1"){
                   cityobj["state"] = adcomponents[k]["address_components"][0]["long_name"];
              }
              if(adcomponents[k]["types"][0] == "country"){
                   cityobj["country"] = adcomponents[k]["address_components"][0]["long_name"];
              }
              cityarr.push(cityobj);
          }
          if(cityarr.length > 0){
            var citynewobj =  Object.assign({},cityarr); 
            const d = new Date();
            var exdays = 1
             d.setTime(d.getTime() + (exdays*24*60*60*1000));
            let expires = "expires="+ d.toUTCString();
            var cname = "location";
            var cvalue = JSON.stringify(citynewobj);
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            $("#userlocation").attr("data-rel", JSON.stringify(citynewobj));
	 var chkclick = "checkclick";
            var chkvalue=1;
            var cookievalue = getCookie(chkclick);
            if(cookievalue === null || cookievalue == ""){
                 document.cookie = chkclick + "=" + chkvalue + ";" + expires + ";path=/";
                 window.location.reload();
                
            }
          }
    }
);
$("#userlocation").css("display","none");

 /* x.innerHTML = "Latitude: " + position.coords.latitude + 
  "<br>Longitude: " + position.coords.longitude; */
}
(function($, window, document, undefined){
   getLocation();
  
})( jQuery, window, document );
</script>

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
$displaydatawidthimg1='200';
$wordcnt      =145;

     
  }else{
     $css ="navbar navbar-expand navbar-dark flex-column flex-md-row bd-navbar";
$btns ='';
$imgwidth ='';
$regularimagewidth ='width="80%"';
$regularimageheight ='';
$displaydatawidth ='74';
$displaydatawidthimg ='25';
$displaydatawidthimg1='375';
$wordcnt      =500;




}
?>


<header class="<?php echo $css;?>">
  <a class="navbar-brand mr-0 mr-md-2" href="/" aria-label="Bootstrap">
  <img width="<?php echo $imgwidth;?>" src="<?php echo $C->SITE_URL?>static/images/streetbuzz.jpg" class="img-responsive">
</a>
<?php  echo $btns;?>
<?php if($ismobile == 0){
if($homeaddscnt > 0){?>
    <div id="demo" class="carousel slide homeslide" data-ride="carousel">

  <!-- Indicators -->
  <ul class="carousel-indicators">
    <li data-target="#demo" data-slide-to="0" class="active"></li>
    <li data-target="#demo" data-slide-to="1"></li>
    <li data-target="#demo" data-slide-to="2"></li>
  </ul>
  
  <!-- The slideshow -->
  <div class="carousel-inner">
      <?php
      if(!empty($singleresult)){
        $singleresult =  array_filter($singleresult); 
      }
      foreach($singleresult as $keys=>$result){ ?>
      <?php if($keys == 0){ ?>
    <div class="carousel-item active"><?php }else{?>
     <div class="carousel-item">
    <?php }?>
      <img  width="100%" src="<?php echo  $C->SITE_URL?>/storage/advs/<?php echo $result->ads_image ?>"   >
    </div>
   
     <?php  } ?>
  </div>
  

</div>


  <?php } } ?>
<button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fa fa-arrow-up" aria-hidden="true"></i>
</button>

 

  <ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">
<li class="nav-item">
              <form class="bd-search d-flex align-items-center">
  <input type="search" class="form-control"  nabd-toc-itemme="search" id="search-input" placeholder="Search..." aria-label="Search for..." autocomplete="off" value="<?php echo $_GET['search'];?>">
  <button type="submit" class="searchButton">
    <i class="fa fa-search"></i>
    </button>


  <button class="btn btn-link bd-search-docs-toggle d-md-none p-0 ml-3" type="button" data-toggle="collapse" data-target="#bd-docs-nav" aria-controls="bd-docs-nav" aria-expanded="false" aria-label="Toggle docs navigation"><i class="fa fa-bars" aria-hidden="true"></i>

</button>
</form>
</a>
    </li>
  <!--<li onclick="return getLocations()"> test location </li>-->
    
<!--    <li id="demos"></li>-->
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
        <div class="col-12 col-md-3 col-xl-3">
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
      
        
      <!--<button type="button" class="btn btn-outline-primary ml-2" data-toggle="modal" data-target="#myModal_l">Change Location</button>-->
      <div class="card">
    <div class="card-body p-0">
        
        <a href="<?php echo $C->SITE_URL?>home" style="text-decoration:none;color:#007bff"><h4 class="text-center">Top News</h4></a>
        
       
        </div>
  </div>
    <div class="text-center mt-4">Top News In states</div>
  
  
 <?php 
    $querry=$db2->query('SELECT * FROM `state`');
    foreach ($querry as $value) {
     ?>
      
    <div class="card">
    <div class="card-body">
        
        <?php if($value['fileName']){ ?>
        
        <a href="<?php echo $C->SITE_URL?>home?st=<?php echo $value['id'].'&name='.$value['name']; ?>" >
        <img style="width:100%;height:150px;border-radius: 10px;" alt="Image3" src="<?php echo $C->SITE_URL?>assets/images/<?php echo $value['fileName']; ?>">
        </a>
        <?php }else{?>
        <a href="<?php echo $C->SITE_URL?>home?st=<?php echo $value['id'].'&name='.$value['name']; ?>" >
        <img style="width:100%;height:150px;border-radius: 10px;" alt="Image3" src="<?php echo $C->SITE_URL?>storage/attachments/1/627e45394271d.png">
        </a>
        <?php } ?>
        <a href="<?php echo $C->SITE_URL?>home?st=<?php echo $value['id'].'&name='.$value['name']; ?>" >
        <span class="mt-2" style="font-size: small; color: #2c6b82;"><?php echo $value['name']; ?> - <?php echo $value['native']; ?></span>
        </a>
        <!--<a href="<?php echo $C->SITE_URL?>home?st=<?php echo $value['id'].'&name='.$value['name']; ?>" class="btn btn-primary btn-sm" style="float: right;">Explore</a>-->
        </div>
  </div>
    
    
    <?php }  ?>
      
  
<div class="card">
    <div class="card-body">
        <img style="width:100%;height:150px;border-radius: 10px;" alt="Image1" src="<?php echo $C->SITE_URL?>storage/attachments/1/627e45394271d.png">
        <p class="mt-2" style="font-size: small; color: #2c6b82;">Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
        <button type="button" class="btn btn-primary btn-sm" style="float: right;">Respond</button>
    </div>
</div>
    </div>
  <div class="modal" id="myModal_l">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title"><a class="bd-toc-link <?php echo $mainactive;?>" href="<?php echo $C->SITE_URL?>home">
        Top News
      </a></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
         <div class="bd-toc-item">
      

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
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
          

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
      
 

            

</nav>


        </div>

        
          <div class="d-none d-xl-block col-xl-3 bd-toc">
               <div id="demos"></div>
               <div class="card">
    <div class="card-body p-0">
       
<a href="<?php echo $C->SITE_URL?>privacy-terms-rules/about/" style="text-decoration:none;color:#007bff">
        <h4 class="text-center">About StreetBuzz</h4>
        </a>
      <!-- <ul class="section-nav">
<li class="toc-entry toc-h2"><a href="<?php echo $C->SITE_URL?>privacy-terms-rules/privacy/index.html">Privacy Policy</a></li>
<li class="toc-entry toc-h2"><a href="<?php echo $C->SITE_URL?>privacy-terms-rules/terms/index.html">Terms & Conditions</a>

<li class="toc-entry toc-h2"><a href="<?php echo $C->SITE_URL?>principles/index.html">Principles</a></li>

</ul>-->
        </div>
  </div>
              <div class="text-center mt-4" style="font-size: 15px;">Top Trending</div>
              <div class="card">
    <div class="card-body" style="">
      <?php  
    
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,"http://ip-api.com/json");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
$result=curl_exec($ch);
$result=json_decode($result);

if($result->status=='success'){
 $city= $result->city;
}
$city="Lucknow";
$locationcheck= $db2->query('select * from sb_location_master where location_district like "%'.$city.'%"');
$row = $db2->fetch_object($locationcheck);
    $location_id=$row->id;  
    
      $r2= $db2->query('SELECT *  FROM  ads_info where customer_district='.$location_id.'');




$res = $db2->fetch_object($r2);
    
 $big_image=$res->big_image;

if($big_image){
?>

        <img style="width: 100%;border-radius: 10px;" alt="Image" src="<?php echo $C->SITE_URL?>storage/advs/<?=$big_image?>" onclick="showimage('<?php echo $C->SITE_URL?>storage/advs/<?=$big_image?>')">
   <?php }else{?>
       <img style="width: 100%;border-radius: 10px;" alt="Image" src="<?php echo $C->SITE_URL?>storage/attachments/1/627e45394271d.png">
       <?php } ?>
        </div>
  </div>
  
            
             
               
      <?php foreach($tags as  $keys=>$vals){ ?>
      <div class="card">
    <div class="card-body">
       <!-- <a class="bd-toc-link" href="<?php echo $C->SITE_URL?>search/tab:posts/s:<?php echo $vals;?>">
        <img style="width:100%;height:70px;border-radius: 10px;" alt="Image2" src="https://streetbuzz.co/develop/static/images/streetbuzz.jpg">
        </a>-->
        <a class="bd-toc-link" href="<?php echo $C->SITE_URL?>search/tab:posts/s:<?php echo $vals;?>" style="color: #007bff!important"><b>
        #<?php echo $vals;?>
      </b></a>
        
      <!--  <button type="button" class="btn btn-primary btn-sm" style="float: right;">Know More</button>-->
        </div>
  </div>
      <!--<div class="bd-toc-item">
      <a class="bd-toc-link" href="<?php echo $C->SITE_URL?>search/tab:posts/s:<?php echo $vals;?>">
        #<?php echo $vals;?>
      </a>

     
    </div>-->
    <?php }  ?>
      
      <hr />
            

          </div>
          
          
          <?php
            if(empty($_GET)){
                $yes ='YES';
                $state ='';
                 $searching =0;
                $res =  $db2->query('SELECT p.date,p.posttype,p.thumb,p.title,p.id,p.message,pa.type,pa.data,u.username,u.avatar FROM homenews as h 
            inner join posts as p ON p.id=h.post_id 
            inner join users as u ON p.user_id=u.id
            left join posts_attachments as pa ON pa.post_id=p.id WHERE h.main_or_not ="'.$yes.'" group by p.id order by h.id desc');
          
            
            
                
                
            }else{
               
                if(isset($_GET['st'])){
                 $state =$_GET['st'];
                 $searching =0;
               $res = $db2->query('SELECT p.date,p.posttype,p.thumb,p.title,p.id,p.message,pa.type,pa.data,u.username,u.avatar FROM homenews as h 
            inner join posts as p ON p.id=h.post_id 
            inner join users as u ON p.user_id=u.id
            left join posts_attachments as pa ON pa.post_id=p.id WHERE h.state_id="'.$state.'" group by p.id order by h.sequence asc');
                }elseif($_GET['search']){
                   $state=  $search =$_GET['search'];
                    $searching =1; 
               $res = $db2->query('SELECT p.date,p.posttype,p.thumb,p.id,p.message,pa.type,pa.data,u.username,u.avatar FROM posts as p 
            inner join users as u ON p.user_id=u.id
            left join posts_attachments as pa ON pa.post_id=p.id WHERE p.message like "%'.$search.'%" group by p.id order by h.sequence asc');
          
            

                }
            }
            
              
            

          ?>
        

        <main class="col-12 col-md-9 col-xl-6 py-md-3 bd-content" role="main"  style="border-right: 1px groove #00000024;
                    border-left: 1px groove #00000024;">



<!-- Start MObile slider
 -->


<style type="text/css">
input.radios:empty ~ label {
    position: relative;
    float: left;
    text-indent: 2.5em;
    padding: 0px;
    margin-top: -23px;
    margin-left: -6px;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
.image {
  opacity: 0.8;
  width: 200px;
  height: 160px;
  background-position: center center;
  background-color: $image-bg;
  display: inline-block;
  margin: 10px;

  &:hover {
    opacity: 1;
  }
}

header.navbar.navbar-expand.navbar-dark.flex-column.flex-md-row.bd-navbar {
    /*display: none;*/
 
    
}


      .bd-toc-link {
    display: block !important;
    padding: 0rem 1rem !important;
    font-weight: 500 !important;
    color: rgba(0,0,0,.65) !important;
}
  
@media screen and (min-width: 550px) {
    
  
    .owl-carousel.owl-theme.owl-loaded.owl-drag {
    display: none;
}
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
.txt-accepted {
    color: #5cb85c;
    font-weight: bold;
    border: 1px solid #5cb85c;
    border-radius: 3px;
    padding: 2px;
}
</style>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.theme.default.min.css">




<div class="owl-carousel owl-theme">



 <?php 
    $querry=$db2->query('SELECT * FROM `state`');
?>

<!-- <div class="item"><?php echo $newstype;?> News</div>
-->    <div class="item"> <a class="bd-toc-link <?php echo $mainactive;?>" href="<?php echo $C->SITE_URL?>home">
        Top News
      </a>
      </div>

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
//rtl:true,
  //  loop:true,
    margin:10,
   // autoplay:true,
    //nav:true,
    responsive:{
        0:{
            items:4
        },
        600:{
            items:5
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

<h4>Top News in <?php echo $_GET['name']; ?></h4>

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
<!-- <div class="form-group margin">
 <label class="radio-img">
       <div class="image" style="background-image: url(https://streetbuzz.co.in/newsapp/assets/images/9703972066062583.jpeg);width:130px;height:120px;"></div>
<p style="text-align:center;">hello</p>
  </label>
  </label>
<label class="radio-img">
        <div class="image" style="background-image: url(https://streetbuzz.co.in/newsapp/assets/images/9703972066062583.jpeg);width:130px;height:120px;"></div>
<p style="text-align:center;">hello</p>
  </label>
  </label>

  <label class="radio-img">
    <div class="image" style="background-image: url(https://streetbuzz.co.in/newsapp/assets/images/9703972066062583.jpeg);width:130px;height:120px;"></div>
<p style="text-align:center;">hello</p>
  </label>
</div> -->
<div class="row">
<div class="col-sm-12">
<h6 onclick="backtostate();" id="backbutton" style="display:none;float:right;margin-top:5px;color:white;cursor:pointer;" class="bg-danger">Back</h6>
</div> 
</div>
<div class="form-group margin" style="display:none">
    <h3 style="text-align: center;font-size:18px;" id="state-label">We dont yet serve real-time news at your location.<br>
Select the state where you want to enjoy real-time news.</h3>
    <div class="row" id="append-data-city">
</div>
    
    
    
<div class="row" id="append-data-state" style="display:none">
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
  <div class="form-group margin">
      <input type="hidden" class="form-control" name="locationid" id="locationid" placeholder="User Preference Location
" >
  <div id="suggesstion-box"></div>

    </div>
  <div class="margin10">
    
    <button  class="btn btn-default btncredit" id="sub" ><i class="fa fa-spinner fa-spin"></i>Signup for SB</button>
    </div>
        </div>
     </div>
    </div>
  </div>
  <!-- The Modal -->
  <div class="modal" id="myModal1">f
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
  
  
<?php $k=1;$s=0; while($result    = $db2->fetch_object($res)){ 
     if($k%3 ==0){
       $showads =1; 
    }else{
       $showads =0;  
    }
?>
  
  <style>
.checked {
  color: orange;
}
</style>
  
 <div class="bd-example-row ">

<div class="bd-example newsrow card" style="padding-top: 5px;"> 
<div class="row">
    <?php 
    
    
    if(!empty($result->avatar)){
      $avatar=$C->STORAGE_URL.'avatars/thumbs3/'.$result->avatar;
    }
    else {
        $avatar=$C->SITE_URL.'storage/thumbs/no-preview.jpg';   
    }
    
    ?>

<div class="col-sm-12" style="background:none;border:none;"><div style="background:none;border:none;float:left"><img style="height:60px;width:60px;border-radius:50%" src="<?= $avatar ?>"></div><div style="background:none;border:none;float:left;padding-left: 10px;">
<a target="_blank" style="text-decoration:none;color:black;font-size:19px;font-family: cursive;padding-left: 3px;" href="<?= $C->SITE_URL ?><?php echo $result->username;?>"><?php echo $result->username;?></a><br>

<span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star"></span>
<span class="fa fa-star"></span>
</span>
</div>

<div class="" style="text-align: end;background:none;border:none;">
    <button class="btn btn-primary"><a style="text-decoration:none;color:white" href="<?= $C->SITE_URL ?><?php echo $result->username;?>"><i class="fa fa-plus"></i>Follow</a></button><br>
<span style="font-size:10px"><?php echo  parse_date1($result->date);  ?>  <?php //echo getlikescount('views',$result->id); ?> </span>

</div>
</div>

<?php 
 ?>
<div class="col-sm-12" style="height:<?php echo $displaydatawidthimg;?>%;width:100%;background:none;border:none;"><?php
     $type = $result->posttype;
     if($type =='2' or $type =='6'){
         $a = unserialize($result->data);
         $image =$a->file_original;
         
         
         $html = $result->message;

$dom = new DOMDocument();
$dom->loadHTML($html);

$imgTags = $dom->getElementsByTagName('img');

foreach ($imgTags as $imgTag) {
    $src = $imgTag->getAttribute('src');
     $image=$src; // This will output the src attribute value
}
         
         
         ?>
        <?php  if(!empty($image)){ ?>
        
      <img   style="width:100%;height:<?php echo $displaydatawidthimg1;?>px;"  style="border-radius:11px;"  alt="Image" src="<?php echo $image;?>">
      <?php }else{ 
           $thumbimg = $C->SITE_URL.'storage/thumbs/no-preview.jpg';
      ?>
      <img style='display:none;' alt="Image"   style="width:100%; padding-left: 8%; height:<?php echo $displaydatawidthimg1;?>px;" style="border-radius:11px;" src="<?PHP echo $thumbimg;?>">
      
     <?php  } ?>

     
    <?php  }elseif($type =='3' ){ 
        $thumb =$result->thumb;
        //$video = file_original
        
         $a = unserialize($result->data);
         $video =$a->file_original;
        
        if(!empty($video)){ 
        ?>
 

 
                <video width="100%" height="auto" controls poster="<?php echo $C->SITE_URL;?>/storage/attachments/1/<?php echo $result->thumb; ?>">
                  <source src="<?PHP echo $C->SITE_URL;?>/storage/attachments/1/<?php echo $video;?>" type="video/mp4">
                 </video>
            
     <?php   }else{
             $thumbimg = $C->SITE_URL.'storage/thumbs/no-preview.jpg';
            
        }
    ?>
        
  <!--  <img alt="Image"   style="width:100%; padding-left: 8%; height:<?php //echo $displaydatawidthimg1;?>px;" src="<?PHP //echo $thumbimg;?>"> -->
  
    <?php }
   elseif($type =='1' ){
  
   ?>
       
   
   
 
<meta name="viewport" content="width=device-width, initial-scale=1">
 <style>
.mySlides {display:none;}
</style>

 
 

 
 
<div class="w3-content w3-display-container">
    
    <?php 
    $i=1;
    //file_original
        $data_attachment  =   getattachments($result->id);
      foreach($data_attachment as $values){
            $caption = $values->content; 
                   $a = unserialize($values->data);
                $slider_img = $a->file_original;
                
                if($i == 1){
                    $style="style=display:block;";
                }
                else {
                      $style="style=display:none;";
                }
                
                $i++;
                
                ?>
 
 
 <div class="w3-display-container mySlides<?php echo $result->id;  ?>" <?php echo $style; ?>>
  <img src="<?PHP echo $C->SITE_URL;?>/storage/attachments/1/<?php echo $slider_img;?>" style="width:100%">
  <div class="w3-display-bottommiddle w3-large w3-container w3-padding-16 w3-black">
     <?php echo $caption; ?>
  </div>
</div>


    <?php  } ?>
   
    
    
<!--    
  <img class="mySlides" src="https://t4.ftcdn.net/jpg/03/17/25/45/360_F_317254576_lKDALRrvGoBr7gQSa1k4kJBx7O2D15dc.jpg" style="width:100%">
  <img class="mySlides" src="https://thumbs.dreamstime.com/b/demo-text-businessman-dark-vintage-background-108609906.jpg" style="width:100%">-->
 
 
 
 
<button class="w3-button w3-display-left w3-black" onclick="plusDivs(-1,<?php echo $result->id;  ?>)">&#10094;</button>
<button class="w3-button w3-display-right w3-black" onclick="plusDivs(1,<?php echo $result->id;  ?>)">&#10095;</button>

</div>

<script>
var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n,id) {
  showDivs(slideIndex += n,id);
}

function showDivs(n,id) {
  var i;
  var x = document.getElementsByClassName("mySlides"+id);
  if (n > x.length) {slideIndex = 1}
  if (n < 1) {slideIndex = x.length}
  for (i = 0; i < x.length; i++) {
     x[i].style.display = "none";  
  }
  x[slideIndex-1].style.display = "block";  
}
</script>
   
       
   
       
  <?php  } 
  
  elseif($type =='4' ){ 
      $id=$result->id;
  $q= $db2->query('SELECT *  FROM  event_posts  WHERE post_id="'.$id.'"');

$rows=$q->num_rows;

if($rows>0){
$r= $db2->fetch_object($q);
    $event_id = $r->event_id ;
if($event_id){
  $q1= $db2->query('SELECT *  FROM  events  WHERE id="'.$event_id.'"');

$rows1=$q1->num_rows;

if($rows1>0){
$r1= $db2->fetch_object($q1);
    $status=$r1->status;
  ?>
						
    <ul class="list-inline single-line">
    <li><img src="<?PHP echo $C->SITE_URL;?>/apps/events/static/images/icon-calendar-event.png" class="img-responsive"> </li><li><?php echo $r1->event_name; ?></li></ul>
    
    <ul class="list-inline single-line">
    <li><img src="<?PHP echo $C->SITE_URL;?>/apps/events/static/images/icon-location-event.png" class="img-responsive"></li><li><?php echo $r1->location; ?></li></ul>
    <ul class="list-inline single-line">
    <li><img src="<?PHP echo $C->SITE_URL;?>/apps/events/static/images/icon-hashtag-event.png" class="img-responsive"></li><li><?php echo $r1->tag_name; ?></li></ul>
    <ul class="list-inline single-line">
    <li><img src="<?PHP echo $C->SITE_URL;?>/apps/events/static/images/icon-status-event.png" class="img-responsive"></li><li>Status - 
    <?php if($status==1){?>
    <span class="txt-accepted">Active</span>
    <?php }else{ ?>
    <span class="txt-accepted">Expired</span>
     <?php } ?></li></ul>
    <ul class="list-inline single-line">
    <li><img src="<?PHP echo $C->SITE_URL;?>/apps/events/static/images/icon-calendar-event.png" class="img-responsive"></li><li><?php echo  date("M d, Y", strtotime($r1->start_date))." ".date("g:i A", strtotime($r1->start_time))." - ".date("M d, Y", strtotime($r1->end_date ))." ".date("g:i A", strtotime($r1->end_time)); ?></li><br>
    </ul>  
    
<?php
				}	}}	}
  
       elseif($type =='5' ){
    
 $poll_ques =  get_polequestion($result->id);
      
                     $a = unserialize($result->data);
                $poll_img = $a->file_original;
             
           //  print_r($poll_img); die;
           
           
       //echo $C->SITE_URL;/storage/attachments/1/$poll_img;
     
    	$pollhtml ='';
			$pollhtml .='<!-- start - 1st vote poll -->
'.$imagedes.'';
 

if($poll_img){
	$pollhtml .='<img style="width:100%;height:400px;padding: 15px;" alt="Image" src="'.$C->SITE_URL.'/storage/attachments/1/'.$poll_img.'">';
}


   	$pollhtml .=' <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 poll-list-orange-bg ggggggggggg">
    
    <!-- start : poll title -->
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-title">
    <ul class="list-inline single-line">
    <li><img src="'.$C->SITE_URL.'static/images/icon-poll-24.png" class="img-responsive"></li>
    <li>'.$poll_ques->poll_question.'
    </li>
    </ul>  
    </div>    
    <!-- end : poll title -->';
    
 
 


$pollhtml .= '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 activity-poll zeropadding">
<div class="attachments lightbox-enabled">
<div class="images">
<div class="col-mlist-link-container test ">
</div>
</div>
</div>
</div>';

$poll = is_poll($result->id);
 $pollanswer = array();

 			foreach($poll as $keys=>$row)
			{

				if($row->answer!="" && count($pollanswer)<=0)
				{
 
     $pollhtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  zeropadding trtrt">
     


    <!-- start : poll results -->
    <style> 
    .button-vote {
    color: #fff!important;
    border: 1px solid #25729a;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    font-size: 12px;
    font-family: arial, helvetica, sans-serif;
    padding: 3px 8px;
    text-decoration: none;
    display: inline-block;
    text-shadow: -1px -1px 0 rgba(0,0,0,0.3);
    font-weight: bold;
 
    background-color: #3093c7;
  
    background-image: linear-gradient(to bottom, #3093c7, #1c5a85);
        
    }
    
    .button-vote {
      margin-right: 10px!important
        
    }
   ul.single-line li {
            /* display: table-cell; */
            /* border: 1px solid #ff0000; */
            vertical-align: top!important;
            line-height: 14px;

      }
       .list-inline > li {
                  display: inline-block;
                  padding-right: 5px;
                  padding-left: 5px;
              }
.buzz-title {
      font-size: 16px;
      color: #000!important;
      font-weight: bold;
  }
  
     .img-responsive {
      display: block;
      max-width: 100%;
      height: auto;
  }
   
   
 input.radios:empty ~ label:before {
      position: absolute;
      display: block;
      top: 0;
      bottom: 0;
      left: -15px;
      
      width: 1.7em;
      background: #D1D3D4;
      border-radius: 100%;
  }

  .poll-radio label {
      width: 100%;
      border-radius: 3px;
      border: 0px solid #D1D3D4;
      font-size: 13px;
      font-weight: normal;
      /* top: 0px; */
  }
   
    
    </style>
 <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content poll-parent-radio-margin">
    <ul class="list-unstyled poll-radio">
    <li>
<input onclick="changeurl('.$row->poll_id.','.$row->poll_answer_id.')" id="'.$keys.$row->poll_id.'" type="radio" name="radio11"  class="radios option"'.$row->poll_answer_id.' radio'.$row->poll_id.' "/>

<label for="'.$keys.$row->poll_id.'">'.$row->answer.'</label></li>
 
    </ul> 

    </div>

    <!-- end : poll results -->

    </div>';

     

     }
				else if($row->answer!="")
				{
					$countpollanswer=is_countpollanswer($row->poll_id,$row->poll_answer_id);
					
				}

			}
		$pollhtml .='
		<div style="padding-left:10px; color:red; font-size:12px;display:none" id="uservoteerror'.$poll_ques->poll_id.'">
		( ਇੱਕ ਉੱਤਰ ਚੁਣੋ ਅਤੇ ਵੋਟ ਕਲਿੱਕ ਕਰੋ / Select one answer and click vote )</div>
		</div>';		

 
 
 
 
 
 
 
 
  
 	$pollhtml .='<!-- start : poll button download results -->
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">';
	$mainpoll =0;
	if(count($pollanswer) <=0){
		
		$pollhtml .='<a href="'. $C->SITE_URL.'view/post:'.$result->id.'"  class="button-vote" style="cursor:default;margin: 0px 0px 0px 34px;"  onclick="vote('.$mainpoll.','.$row->poll_id.')"  id="pollvote'.$row->poll_id.'" >Vote</a>';
		if($poll[0]->user_id == $user->id){
		$pollhtml .='<a type="submit"  class="download'.$mainpoll.''.$poll[0]->poll_id.'"  id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'" ><button type="submit" class="button-submit-results">Download Results</button></a>';
			
		}
	
   }else{
        if($poll[0]->user_id == $user->id){
		$pollhtml .='<a type="submit"   class="download'.$mainpoll.''.$poll[0]->poll_id.'"  id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'" ><button type="submit" class="button-submit-results">Download Results</button></a>';

			
		}
   }
     $pollhtml .='</div>';
 
 
 
 
 
 
 
 
 
 
	


echo $pollhtml;


     } ?>
     </div>
<div class="col-sm-12" style="background:none;border:none"><?php
$str =strip_tags($result->message);
 $finalstr   =mb_substr($str,0,$wordcnt);



// echo '<pre>';
// print_r($result);

// die('============');

echo $result->title;?><a target="_blank" href="<?php echo $C->SITE_URL?>view/post:<?PHP ECHO $result->id;?>" > ..Read More</a> 
</div>

</div>
<div class="track2">
    <span class="tracks"><a style="color:gray" target="_blank" href="<?php echo $C->SITE_URL?>view/post:<?PHP ECHO $result->id;?>" ><i class="fa fa-thumbs-o-up"></i></a>&ensp;<span> <?= getlikescount('likes',$result->id)  ?></span></span>
 <span class="tracks"><a style="color:gray" target="_blank" href="<?php echo $C->SITE_URL?>view/post:<?PHP ECHO $result->id;?>" ><i class="fa fa-comment-o"></i></a>&ensp;<span> <?= getlikescount('comments',$result->id)  ?></span></span>
 <span class="tracks"><a style="color:gray" target="_blank" href="<?php echo $C->SITE_URL?>view/post:<?PHP ECHO $result->id;?>" ><i class="fa fa-refresh"></i></a>&ensp;<span> <?= getlikescount('reshares',$result->id)  ?></span></span>
 
 
 <span class="tracks">
     <a style="color:gray" data-toggle="collapse" href="#collapseExample<?= $result->id ?>" ><i class="fa fa-share-alt"></i></a>
     &ensp;<span> <?= getlikescount('shares',$result->id)  ?></span>
  

 <ul class="collapse" style="list-style: none;float: right;" id="collapseExample<?= $result->id ?>"> 
 
 
 <?php /* onclick="myFunctionpostshare(<?=$result->id ?>)"*/  /* for whatsapp  href="https://web.whatsapp.com/send?text=citeurl  " for facebook href="http://www.facebook.com/sharer.php?u=citeurl&t="*/ ?> 
 
 
 <li><a href="<?php echo $C->SITE_URL?>view/post:<?PHP ECHO $result->id;?>"><i class="fa fa-whatsapp" title="whatsapp"></i></a></li>
 <li><a href="<?php echo $C->SITE_URL?>view/post:<?PHP ECHO $result->id;?>"><i class="fa fa-facebook" title="facebook"></i></a></li>
 <li><a href="<?php echo $C->SITE_URL?>view/post:<?PHP ECHO $result->id;?>"><i class="fa fa-twitter"title="twitter"></i></a></li>
 <li><a href="<?php echo $C->SITE_URL?>view/post:<?PHP ECHO $result->id;?>"><i class="fa fa-linkedin" title="linkedin"></i></a></li>
  <li><a href="<?php echo $C->SITE_URL?>view/post:<?PHP ECHO $result->id;?>"><i class="fa fa-google-plus" title="google-plus"></i></a></li>
 </ul>
 

 </span>
 
 
 
</div>

</div>
</div>
 
  
  
  
  
  
  
  <?php /*
          
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

</div>  */   ?>
<?php

if($showads && !empty($singleresult) && $singleresult[$s] != undefined ){
?>
<div class="bd-example-row "><div class="row singlehomeads">
<?php 
$s=0;
     if($ismobile == 0 ){ ?>
     <img width="92%"  class="imgresponsive"src="<?php echo  $C->SITE_URL?>storage/advs/<?php echo $singleresult[$s]->ads_image ?>"  />
  <?php }else{ ?>
  <img width="92%" style="margin-top:10px" src="<?php echo  $C->SITE_URL?>/storage/advs/<?php echo $singleresult[$s]->ads_image ?>"  />
  <?php }?>
<?php $s;?></div></div>
<?php $s++;}
?>
<?php $k++;} ?>
<div id="mainapp" rel="10" data-lan="<?php echo $state;?>" data-serach="<?php echo $searching;?>"></div>

   <!-- <div class="sbload" style="display:none"><img src="<?PHP echo $C->SITE_URL;?>/static/images/ajax-loader.gif" /></div>-->

 </main>
      </div>
    </div>
 <?php if($ismobile == 1 && $homeaddscnt > 0){ ?>  
 <footer class="footer">
    <div id="demo" class="carousel slide" data-ride="carousel">

  <!-- Indicators -->
  <ul class="carousel-indicators">
    <li data-target="#demo" data-slide-to="0" class="active"></li>
    <li data-target="#demo" data-slide-to="1"></li>
    <li data-target="#demo" data-slide-to="2"></li>
  </ul>
  
  <!-- The slideshow -->
  <div class="carousel-inner">
      <?php foreach($singleresult as $keys=>$result){ ?>
      <?php if($keys == 0){ ?>
    <div class="carousel-item active"><?php }else{?>
     <div class="carousel-item">
    <?php }?>
      <img  width="100%"src="<?php echo  $C->SITE_URL?>/storage/advs/<?php echo $result->ads_image ?>"  class="11" >
    </div>
   
     <?php } ?>
  </div>
  

</div>
</footer>

<?php } ?>
      

   <!-- The Modal -->
  <div class="modal" id="show_image">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <img id="preview_image" height="100%" width="100%">
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
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
i.fa.fa-plus {
    color: white;
}

.tracks{
    margin-left:70px;
}

.track2{
    padding-left:0px;
    padding-right:60px;
    text-align:center;
}




	.imgresponsive{
    margin-left:14px;
    margin-right:14px;
    margin-top:10px;
}
	.homeads{
    padding-left:20px;padding-right:20px
}
.homeslide{
    width:500px;padding-left:40px;
}
.footer{
position: fixed; 
    bottom: 0;
    left: 0;
    right: 0;
    height: 50px;
}
.homeadsfooter{
    padding-left:10px;padding-right:10px
}
  .frmSearch {border: 1px solid #a8d4b1;background-color: #c6f7d0;margin: 2px 0px;padding:40px;border-radius:4px;}
#country-list{float:left;list-style:none;margin-top:-3px;padding:0;width:80%;position: absolute;}
#country-list li{padding: 10px; background:white; border-bottom: #bbb9b9 1px solid;}
#country-list li:hover{background:white;cursor: pointer;}
#userlocation{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
/*********************/
.form-control {
        border-radius: .25rem 0 0 .25rem!important;
}
.fa{
    margin:0px;
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
  /*  margin-left: -12px;
    margin-right: 8px;  */
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

@media (max-width: 480px){
     .tracks {margin-left:50px;}
     
 .track2{
    padding-left:25px;
    padding-right:25px;
}

    
}

@media (max-width: 767px) {
    .new-block{
    display:block;
      float: left;
    width: 50%;
}
  

 .track2{
    padding-left: 30px;
    padding-right: 50px;
 }
 
.section-nav{
    border-left:0px;
}
.top_news_wrap{
    width:5
}

</style>
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
    $("#userlocation").keyup(function(){
		$.ajax({
		type: "POST",
		url: "<?php  echo $C->SITE_URL;?>sb_location_master",
		data:'keyword='+$(this).val(),
		beforeSend: function(){
			$("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
		},
		success: function(data){
			$("#suggesstion-box").show();
			$("#suggesstion-box").html(data);
			$("#search-box").css("background","#FFF");
		}
		});
	});
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
       
    var locationids = new Array();
                $('input[name="locationids[]"]:checked').each(function(){
                    locationids.push($(this).val());
                });

                var dataString = locationids.join(',');
                //alert('Selected Locations: ' + dataString);
                
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
             var cityaddress = "";var locationid = "";
			      if( $("#userlocation").attr("data-rel") !== null &&  $("#userlocation").attr("data-rel") !== undefined){
			           cityaddress =  $("#userlocation").attr("data-rel");
			      }
			       if( $("#userlocation").attr("data-location-id") !== null &&  $("#userlocation").attr("data-location-id") !== undefined){
			           locationid =  $("#userlocation").attr("data-location-id");
			      }
			     
    locationid=$("#locationid").val();
             $.ajax({
     
      type:"POST",
      data:{fullname:full,strret_useremail:email,street_username:username,street_userpassword:userpassword,profile_birth_day:day,profile_birth_month:month,profile_birth_year:year,profile_gender:gender,userpreferlocation:cityaddress,locationid:locationid,locationids:dataString},
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

<script>
 function myFunctionpostshare(id) {
             $.ajax({
         //      url: 'services/activities/share_count',

        url: '<?php  echo $C->SITE_URL;?>services/activities/share',
            type: 'POST', 
            dataType: 'JSON',
            data: {id : id},
            success: function (data) {
                $.each(data,function(key,value){
                    
            var purpose = value.html;
            
            var qq1 =purpose.replace(/\.|,/g, '');
            var qq =qq1.replace(/\'|,/g, '');              
                        
                    
                 $( "."+id ).html(qq);   
                })
                
              //  $("#sssssssss").append("<b>Appended text</b>");
            }
        });
        }

</script>
<script>
// Get the button
let mybutton = document.getElementById("myBtn");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    mybutton.style.display = "block";
  } else {
    mybutton.style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}

function backtostate() {
    	 	 $('#backbutton').css('display','none'); 
		 	 	 $('#append-data-city').css('display','none');
		 	 	 	 $('#append-data-state').css('display','block');
                     $('#append-data-state').css('display','flex');
                     	 $('#state-label').css('display','block');
		 	 	 	 
}

function getlocationbystate(stateid) {
    
       $.ajax({
		type: "POST",
		url: "<?php  echo $C->SITE_URL;?>admin/loadcity",
        	data:{stateid:stateid},
		success: function(data){
		 	 $('#backbutton').css('display','block'); 
		 	 $('#append-data-state').css('display','none');
		 	 $('#state-label').css('display','none');
 
         $('#append-data-city').css('display','flex'); 
		 $('#append-data-city').html(data);
		}
		});
		
}
</script>
<script>
function showimage(data){
    document.getElementById('preview_image').src = '' + data + '';
  $('#show_image').modal('show');
}
</script>
