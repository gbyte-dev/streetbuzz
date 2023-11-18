<!-- Modal -->
    <div class="modal-dialog" id="graph-chat" >
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                   >
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
               <?php echo '<img src="'.$C->SITE_URL.'static/images/logo.png" class="img-responsive" width="150">'; ?>
                </h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
				<div class="row bor-padding" style="background: #f0fafd; padding-top: 15px; padding-bottom:10px; margin-right:-10px; margin-left:-10px;">
				<div  id="fbsuccess" style="color:green, font-weight: bold, font-size: 18px;"></div>
            <div class="col-md-4 col-xs-12 bor-login-icon">
              <fb:login-button   size="large" scope="email" onlogin="checkLoginState();">  Login with Facebook

</fb:login-button>
				

            </div>

           <!-- <div class="col-md-4 col-xs-12 bor-login-icon">
     <a href="#"><?php echo '<img src="'.$C->SITE_URL.'static/images/login-google.png" class="">'; ?>
     </a>
            </div>-->

            <div class="col-md-4 col-xs-12 bor-login-icon" id="streetbuzzlogin">
        <a href="#"><?php echo '<img src="'.$C->SITE_URL.'static/images/login-sb.png" class="">'; ?>
        </a>
            </div>
 </div>
				<html>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDArYN-93IBn3EBtCMXBoSoznKr3F1wPxE&libraries=places&v=2.exp"></script>
<script>

function selectCountry(val,location) {
$("#userlocation").val(location);
$("#userlocationid").val(val);
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
              $("#userlocation").css("display","none");

            var citynewobj =  Object.assign({},cityarr); 
            $("#userlocationres").val(JSON.stringify(citynewobj));
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

<script>
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
	  $("#fbsuccess").html("We are receiving your information. Please wait !  ");

    FB.getLoginStatus(function(response) {
				var interval = setInterval(function(){

			 var fbcnt = 1;
			 var fbid =$("#fbid").val();
					    var pollid =$("#pollid").val();
					    var fbanswerid =$("#fbanswerid").val();
					    var facebookfirstname =$("#facebookfirstname").val();
					   var fbuser =$("#fbuser").val();
					   var fbgender = $("#fbgender").val();
					  var fbdateofbirth = $("#fbdateofbirth").val();
					  var fbe = $("#fbemail").val();
					 var groupids = $("#group_ids").val();
					  // var fbe ='a.srinu.it@gmail.com';
					   var actpollid =$("#postid").val();
			          
								   if(fbcnt == 1 && fbid !='' && fbgender !='' && fbdateofbirth !='' && fbe !="" ){
						   
						   $("#facebooksighned").submit();
						   return false;
					   }else{
					   if( fbcnt == 1 && fbid !='' && (fbgender == "" || fbdateofbirth == "" || fbe =="")){
						   	$.ajax({
					async: true, 
		           cache: false,
					dataType : "html",
					type:"POST",
					data:{postid:actpollid,fbid:fbid,pollid:pollid,fbanswerid:fbanswerid,facebookfirstname:facebookfirstname,fbuser:fbuser,fbgender:fbgender,fbdateofbirth:fbdateofbirth,fbe:fbe,},
					url:"<?php  echo $C->SITE_URL;?>sighnuppopup1",

					success:function(msg){
						if($.trim(msg) == "OK" ){
							$("#facebooksighned").submit();
							 return false;

						}else{
                         if(groupids ==""){
						 $('#replaypopup-'+actpollid).html('');
						 $('#replaypopup-'+actpollid).html(msg);
						 $('#replaypopup-'+actpollid).modal('show'); 
						 }else{
							  $('#group').html('');
						 $('#group').html(msg);
						 $('#group').modal('show'); 
						 }
						}
						
						
					}
				});
					   }
					   }
				}, 500); 


      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '237819566613317',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.5' // use graph api version 2.5
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me',
	        {fields: "id,about,age_range,picture,bio,birthday,context,email,first_name,gender,hometown,link,location,middle_name,name,timezone,website,work"}, 

	function(response) {
		$("#facebookfirstname").val(response.first_name);
          $("#fbuser").val(response.name);
          $("#fbid").val(response.id);
          $("#fbgender").val(response.gender);
          $("#fbdateofbirth").val(response.birthday);
		$("#fbemail").val(response.email);
    });
  }
</script>
<div id="login" style="display:none;">
 <div class="col-lg-12 col-md-12 col-xs-12">
 <h5>Login with Streetbuzz</h5>
 </div>

<form role="form"  action="<?php echo $C->SITE_URL?>sighnmallout"  method="POST" id="sighned">
<input type="hidden" name="postid" value="<?php echo $_POST['postid'];?>" />
				<input type="hidden" name="pollid" value="<?php echo $_POST['pollid'];?>" />
				<input type="hidden" name="answerid" value="<?php echo $_POST['answerid'];?>" />
									<input type="hidden" name="groups_id" value="<?php echo $_POST['groupid'];?>" />


<div class="row bor-padding10">

<div class="form-group col-md-5">
                      <input type="text" class="form-control form" placeholder="Username or Email or Mobile number" id="loginuser" name="email" value="<?php if(isset($_COOKIE['username'])){ echo $_COOKIE['username'];} ?>" />
					   <div id="userloginerror" class="notifyjs-container" style="overflow: hidden; display:none;margin-top:-40px;">
						   <div class="notifyjs-bootstrap-base notifyjs-bootstrap-error">
								<span data-notify-text="" class="notifyjs-text">Username or Email or Mobile number is required</span>
							 </div>
						 </div>
 </div>

 <div class="form-group col-md-5">
                      <input type="password" class="form-control form" placeholder="Password" id="loginpassword" name="password" value="<?php if(isset($_COOKIE['userpassword'])){ echo $_COOKIE['userpassword'];} ?>" />
					   <div id="userloginpassword" class="notifyjs-container" style="overflow: hidden; display:none;margin-top:-40px;">
						   <div class="notifyjs-bootstrap-base notifyjs-bootstrap-error">
								<span data-notify-text="" class="notifyjs-text">Password is required</span>
							 </div>
						 </div>
 </div>

<div class="form-group col-md-2">
<button type="button" class="btn btn-primary btn-sm" id="malvotelogin">Login</button>
 </div>
 
 <div class="form-group col-md-12 alert-label"><input type="checkbox" name="rememberme" id="rememberme" value="1"  checked <?php if(isset($_COOKIE['username'])){  echo "checked";} ?> /> Remember me</div>

</div>
</form>
</div>
             
             <div class="col-lg-12 col-md-12 col-xs-12 sighnuplabel">
            <h5>Signup</h5>
            </div>
                
                <div class="bor-padding10 sighnoutform">
                <form role="form" action="<?php echo $C->SITE_URL?>registermallout"  method="POST" id="home">
				<input type="hidden" name="postid" value="<?php echo $_POST['postid'];?>" />
				<input type="hidden" name="pollid" value="<?php echo $_POST['pollid'];?>" />
				<input type="hidden" name="answerid" value="<?php echo $_POST['answerid'];?>" />
			        <input type="hidden" id="userlocationres" name="userlocationres" />
					<input type="hidden" id="userlocationid" name="userlocationid" />

                  <div class="form-group">
                      <input type="text" class="form-control form" placeholder="Full Name" id="malfullname" name="fullname"/>
					   <div id="malfullnameerror" class="notifyjs-container" style="overflow: hidden; display:none;margin-top:-40px;">
						   <div class="notifyjs-bootstrap-base notifyjs-bootstrap-error">
								<span data-notify-text="" class="notifyjs-text">Full Name is required</span>
							 </div>
						 </div>
                  </div>
				   

				  
                  <div class="form-group">
                      <input type="text" class="form-control form" placeholder="Email or Mobile number" id="malemailhone" name="strret_useremail"/>
					   <div id="malemailhoneerror" class="notifyjs-container" style="overflow: hidden; display: none;margin-top:-40px;">
						   <div class="notifyjs-bootstrap-base notifyjs-bootstrap-error">
								<span data-notify-text="" class="notifyjs-text" id="malvalid">Email or Mobile number is required</span>
							 </div>
						 </div>
						 
                  </div>
				  
                  <div class="form-group">
				  <label><span class="alert-label">Username generated automatically</span></label>
                      <input type="text" class="form-control form" placeholder="Username" id="malusername" name="street_username" />
					   <div id="malusernameerror" class="notifyjs-container" style="overflow: hidden; display: none;margin-top:-40px;">
						   <div class="notifyjs-bootstrap-base notifyjs-bootstrap-error">
								<span id="mailusertext"data-notify-text="" class="notifyjs-text">Username is required</span>
							 </div>
						 </div>
                  </div>
                  <div class="form-group">
                      <input type="password" class="form-control form" placeholder="Password" id="malpassword" name="street_userpassword" />
					  <div  id="malpassworderror" class="notifyjs-container" style="overflow: hidden; display: none;margin-top:-40px;">
						   <div class="notifyjs-bootstrap-base notifyjs-bootstrap-error">
								<span data-notify-text="" class="notifyjs-text">Password is required</span>
							 </div>
						 </div>
                  </div>

                  <div class="form-group">
                  <label><strong>Birthday</strong></label><br />
                  <div class="row bor-padding-birthday">
                   <div class="col-md-4 col-xs-4">
                    <select class="form-control form" name="profile_birth_day" id="birthdayday">
		<?php for($i=1; $i<=31; $i++) {?>
		<option  value="<?php echo $i;?>" <?php if($i == intval(date('d'))){ echo 'selected'; }?>><?php echo $i;?></option>
		<?php } ?>
				
				</select>
				
                  </div>
                  <div class="col-md-4 col-xs-4">
                  <select class="form-control form" name="profile_birth_month" id="birthdaymonth">
	
	 <?php	for($j=1; $j<=12; $j++) { ?>

	<option value="<?php echo $j;?>" <?php if($j == intval(date('m'))){ echo 'selected'; }?>><?php echo strftime('%B', mktime(0,0,1,$j,1,2009)); ?></option>
	<?php } ?>
		</select>
		
                  </div>
				  
                  <div class="col-md-4 col-xs-4">
                   <select class="form-control form"name="profile_birth_year"  id="birthdayyear">

		<?php for($k=intval(date('Y')); $k>=1900; $k--) { ?>
	<option value="<?php echo $k;?>" <?php if($k == (intval(date('Y'))-20)){ echo 'selected';}?>><?php echo $k;?></option>
	<?php } ?>
		</select>
		
                  </div>
                  </div>
                  </div>

                  <div class="form-group">
                      <p><input type="radio" value="m" name="profile_gender" checked /> <strong>Male</strong> <input type="radio" value="f"  name="profile_gender" /> <strong>Female</strong></p>
                  </div>
		   <div class="form-group margin">
      <input type="text" class="form-control" id="userlocation" placeholder="User Preference Location
" >
  <div id="suggesstion-box"></div>

    </div>
                </form>
                </div>


				</div>
				<!-- Modal Footer -->
            <div class="modal-footer">
               <!-- <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Cancel
                </button>-->
                <button type="button" class="btn btn-warning" id="malvote">
                               <?php echo $_POST['title'];?>

                </button>
            </div>
				<div  style="display:block;">
					<form role="form"  action="<?php echo $C->SITE_URL?>sighnmallout"  method="POST" id="facebooksighned">
					<input type="hidden" name="postid" id="postid" value="<?php echo $_POST['postid'];?>" />
				<input type="hidden" name="pollid" id="pollid" value="<?php echo $_POST['pollid'];?>" />
				<input type="hidden" name="answerid" id="fbanswerid" value="<?php echo $_POST['answerid'];?>" />
					<input type="hidden" name="facebookfirstname" id="facebookfirstname" value="" />
						<input type="hidden" name="fbuser" id="fbuser" value="" />
					<input type="hidden" name="fbid" id="fbid" value="" />
					<input type="hidden" name="fbgender" id="fbgender" value="" />
					<input type="hidden" name="fbdateofbirth" id="fbdateofbirth" value="" />
					<input type="hidden" name="fbemail" id="fbemail" value="" />
						<input type="hidden" id="fbcount" value="0" />
						<input type="hidden" id="group_ids"name="groups_id" value="<?php echo $_POST['groupid'];?>" />

						</form>

					</div>

			
			</div>
		</div>
		<div id="replaypopupsighn-<?php echo $_POST['postid'];?>">
			<input type="hidden" id="logincount" value="0" />

		
<style>
.frmSearch {border: 1px solid #a8d4b1;background-color: #c6f7d0;margin: 2px 0px;padding:40px;border-radius:4px;}
#country-list{float:left;list-style:none;margin-top:-3px;padding:0;width:80%;position: absolute;}
#country-list li{padding: 10px; background:white; border-bottom: #bbb9b9 1px solid;}
#country-list li:hover{background:white;cursor: pointer;}
#userlocation{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
.form {
    border: 1px solid #BFE0EC;
    color: #34495e;
    font-family: Helvetica, Arial, sans-serif, Helvetica, Arial, sans-serif;
    font-size: 14px;
    line-height: 1.467;
    /* height: 42px; */
    -webkit-appearance: none;
    border-radius: 6px;
    -webkit-box-shadow: none;
    box-shadow: none;
    -webkit-transition: border .25s linear, color .25s linear, background-color .25s linear;
    transition: border .25s linear, color .25s linear, background-color .25s linear;
}
.bor-padding {
	padding:0px 15px;
}
.bor-login-icon {
	margin: 0px 8px 5px -3px;
}
.bor-padding10 {
	padding:0px 10px;
}
.bor-padding-birthday { 
	padding: 0px 6px;
}
.btn-warning {
	width: 40%;
	font-weight: bold;
}
h5 {
	color: #0099d3;
	font-size: 16px;
}
.modal-body {
	padding-top: 0px;
}
.modal-footer {
	margin-top: 0px;
	text-align: left;
}
.modal-header {
	padding-bottom: 0px;
}
.form-group {
	margin-bottom:10px!important;
}
.alert-label {
	color: #0099d3 !important;
	font-size: 12px;
}
@media screen and (max-width: 480px) {
.btn-warning {
	width: 70%;
	font-weight: bold;
}
}
	._4z_f fwb{
		font-size: 24px!important;
    line-height: 16px!important;
    padding: 0 6px!important;
	}
.qa-nav-user-facebook-login {width:200px; height:60px; display:inline-block;}
</style>
<script type="text/javascript">
$(document).ready(function(){
	 getLocation();
     $("#userlocation").keyup(function(){
		$.ajax({
		type: "POST",
		url: "<?php echo $C->SITE_URL;?>sb_location_master",
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
	$(".close").click(function(){
		window.location.reload();
		
	});
	
	
	$("#malvotelogin").click(function(){
		var loginuser = $("#loginuser").val();
		if(loginuser ==''){
			$("#userloginerror").css("display","block");
			$("#userloginerror").delay(1000).fadeOut(100);
			return false;
			
		}
		var loginpassword = $("#loginpassword").val();
		if(loginpassword ==''){
			$("#userloginpassword").css("display","block");
			$("#userloginpassword").delay(1000).fadeOut(100);
			return false;
			
		}
		if(loginuser !='' && loginpassword !=''){
			 $.ajax({
		 
		  type:"POST",
		  data:{loginuser:loginuser,loginpassword:loginpassword},
		   url:"<?php echo $C->SITE_URL;?>registermallout",

		  success:function(response){
			  if(response ==0){
				  STX.showMessage("Please enter valid data!", "error");

			  }else{
				  $("#sighned").submit();
				  
				  
			  }
			  
			 
			  
			  
		  }
		  
	  });
		}
		
		
		

		
	});
	$("#streetbuzzlogin").click(function(){
		var logcnt =$("#logincount").val();
		if(logcnt == 0 ){
			$("#logincount").val(1);
			$("#login").css("display","block");
	    $("#login").slideDown();
		$(".sighnuplabel").css("display","none");
		$(".sighnoutform").css("display","none");
		$("#malvote").css("display","none");
			
		}
		if(logcnt == 1 ){
			$("#logincount").val(0);
			$("#login").css("display","none");
		$(".sighnuplabel").css("display","block");
		$(".sighnoutform").css("display","block");
		$("#malvote").css("display","block");
			
		}
		

		
	});
	var num = parseInt((Math.random() * 100), 10);

	$("#malfullname").keyup(function(){
		var user =$(this).val();
			   var final = user.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '');
			  
			
			
			var num = parseInt((Math.random() * 100), 10);
			var finaluser =  final+num;
			$("#malusername").val(finaluser);
		
	});
	$("#malusername").keyup(function(){
		var regx = /^\S*$/; // a string consisting only of non-whitespaces

			if(regx.test($(this).val()) == false) {
			var finreg = 	($(this).val()).replace(/ /g,'');
			$("#malusername").val(finreg);
				
				return false;
           }
		var fullnamearr = ["!", "@","#", "$","%","^","&","*","(",")","-",":",";","<",">","?",";",",","+","=","{","}","."];
		var lastchrr = $(this).val().slice(-1);
		 if($.inArray(lastchrr, fullnamearr) !== -1){
			  var finregstr = 	$(this).val().slice(0,-1);
			$("#malusername").val(finregstr);
				
				return false;
		   }else{
		   }
	});
	$("#malvote").click(function(){
		var malfulname = $("#malfullname").val();

		if(malfulname ==''){
			$("#malfullnameerror").css("display","block");
			$("#malfullnameerror").delay(1000).fadeOut(100);
			return false;
			
		}
		var malemailhone = $("#malemailhone").val();
		if(malemailhone ==''){
			$("#malemailhoneerror").css("display","block");
			$("#malemailhoneerror").delay(1000).fadeOut(100);
			return false;
		}
		if($.isNumeric(malemailhone)){
			var moblen = malemailhone.length;
			if(moblen ==10){
				
			}else{
			$("#malemailhoneerror").css("display","block");
		    $("#malvalid").html("Please enter valid mobile number");

			$("#malemailhoneerror").delay(1000).fadeOut(400);
			return false;
				
			}
		}else{
			if (!ValidateEmail(malemailhone)) {
           $("#malemailhoneerror").css("display","block");
		   $("#malvalid").html("Please enter valid email");

			$("#malemailhoneerror").delay(1000).fadeOut(100);
			return false;
        }
        else {
        }
		}
		 function ValidateEmail(malemailhone) {
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(malemailhone);
    };
		var malusername = $("#malusername").val();
		if(malusername ==''){
			$("#malusernameerror").css("display","block");
			$("#malusernameerror").delay(1000).fadeOut(100);
			return false;
		}
		var malpassword = $("#malpassword").val();
		if(malpassword ==''){
			$("#malpassworderror").css("display","block");
			$("#malpassworderror").delay(1000).fadeOut(100);
			return false;
		}
				if( malfulname !='' && malemailhone !='' && malusername !=''){
			
		 $.ajax({
		 
		  type:"POST",
		  data:{email:malemailhone},
		   url:"<?php echo $C->SITE_URL;?>signup",

		  success:function(response){
			  if(response.trim() !="OK"){
				  $("#malemailhoneerror").css("display","block");
		         $("#malvalid").html(response);
				 $("#malemailhoneerror").delay(1000).fadeOut(100);

                 return false;
				  
				  
			  }else{
				  $.ajax({
		 
		  type:"POST",
		  data:{username:malusername},
		   url:"<?php echo $C->SITE_URL;?>signup",

		  success:function(response){
			  if(response.trim() !="OK"){
				   $("#malusernameerror").css("display","block");
		         $("#mailusertext").html(response);
				 	$("#malusernameerror").delay(1000).fadeOut(100);

                 return false;
				  
				  
				  
			  }else{
				  $("#home").submit();
				  
				  
			  }
			 
			  
			  
		  }
		  
	  });
				  
				  
			  }
			 
			  
			  
		  }
		  
	  });
	}
		
	});
});
</script>

 



				   
