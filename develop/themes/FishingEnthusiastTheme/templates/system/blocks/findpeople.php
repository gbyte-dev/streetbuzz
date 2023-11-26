<div id="social">
<div class="col-md-12" style="padding:20px;">
  <div class="row" style="border-bottom:1px solid #DADADA;">
   <span class="frnds-title">Find friends</span>
  </div>
  
  <div class="row sub-title-box">
   <span class="frnds-sub-title">Search your address book for friends</span>
  </div>
  
  <div class="row sub-title-box">
   <span class="frnds-text">Choosing a service will open a window for you to log in securely and import your contacts to StreetBuzz. We won't email anyone without your consent, but we may use contact information to improve Who To Follow suggestions.</span>
  </div>
  
  <div class="row email-box">
	<div class="col-md-9">
	 <ul class="list-inline">
	 <li><img  name="submit" src="<?php echo $C->SITE_URL; ?>static/images/gmail.png" class="btn-photo" border="0" alt="Submit" onclick="auth();" /></li>
	 <li class="title-mail">Gmail <br /> <span class="mail-id"</span></li>
	 </ul>
	</div>
	<div class="col-md-3"><button class="btn btn-primary" onclick="auth();">Search contacts</button></div>
  </div>
  
  
  
   <div class="row email-box">
	<div class="col-md-9">
	 <ul class="list-inline">
	 <li><img src="<?php echo $C->SITE_URL; ?>static/images/outlook.png" class="btn-photo import" ></li>
	 <li class="title-mail">Outlook <br /> <span class="mail-id"></span></li>
	 </ul>
	</div>
	<div class="col-md-3"><button class="btn btn-primary import">Search contacts</button></div>
  </div>
  
  
   <div class="row sub-title-box">
   <span class="frnds-text">You can manage the contacts you uploaded from your address book at anytime.</span>
  </div>
  <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">We are loading contact details</h4>
      </div>
      <div class="modal-body">
      <img src="<?php echo $C->SITE_URL;?>static/images/ajax-loader.gif">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


  
  
  
</div>
</div>

<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://apis.google.com/js/client.js"></script>
<script src="//js.live.net/v5.0/wl.js"></script>
<script type="text/javascript">
WL.init({
    client_id:'000000004417A2C3',
    redirect_uri:'http://streetbuzz.co.in/test/findpeople',
    scope: ["wl.basic", "wl.contacts_emails"],
    response_type: "token"
});
</script>
<script>
jQuery( document ).ready(function() {

	//live.com api
	jQuery('.import').click(function(e) {
	    e.preventDefault();
	    WL.login({
	        scope: ["wl.basic", "wl.contacts_emails"]
	    }).then(function (response) 
	    {
			WL.api({
	            path: "me/contacts",
	            method: "GET"
	        }).then(
	            function (response) {
	                 //$("#outlook").load("<?php echo $C->SITE_URL; ?>outlookparse",{data:response.data,userid:userid,password:password});;
	                      $("#social").load("<?php echo $C->SITE_URL; ?>outlooksocialparse",{data:response.data});;


	           
                        //your response data with contacts 
	            	//console.log(response.data);
	            },
	            function (responseFailed) {
	            	//console.log(responseFailed);
	            }
	        );
	        
	    },
	    function (responseFailed) 
	    {
	        //console.log("Error signing in: " + responseFailed.error_description);
	    });
	});    

});
</script>
<script type="text/javascript">
   function auth() {
     var config = {
       'client_id': '751188755955-ht9r0e381893amidh53ecojf75kis3ls.apps.googleusercontent.com',
       'scope': 'https://www.google.com/m8/feeds'
     };
     gapi.auth.authorize(config, function() {
       fetch(gapi.auth.getToken());  
      
     });
   }
 
   function fetch(token) {
     $.ajax({
     url: "https://www.google.com/m8/feeds/contacts/default/full?access_token=" + token.access_token + "&alt=json",
     dataType: "jsonp",
      beforeSend: function() { 
       $('#myModal').modal('show');

      },
     success:function(data) {
       $('#myModal').modal('hide');

     
     $("#social").load("<?php echo $C->SITE_URL; ?>socialparse",{data:data});;
     
   // $("#gmailform").submit();
    
     
                              // display all your data in console
              // console.log(JSON.stringify(data));
     }
 });
 } 
</script>



  

<style>
.frnds-title {
	font-size:22px;
	padding:10px;
}
.frnds-sub-title {
	font-size:16px;
	color: #8899a6;
}
.sub-title-box {
	margin-top:20px;
	padding:10px;
}
.email-box {
	margin-top:20px;
	border-bottom: 1px solid #DADADA;
}
.frnds-text {
	color: #8899a6;
}
.title-mail {
	font-weight: bold;
}
.mail-id {
	font-size: 12px;
	color: #8899a6;
	font-weight: normal;
}
</style>