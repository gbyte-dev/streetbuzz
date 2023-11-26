<script src="<?php echo $C->SITE_URL;?>static/js/jquery.js?v=3.6.0"></script>
<script src="<?php echo $C->SITE_URL;?>static/js/textareaeditor.js?v=3.6.0"></script>

<!-- START : Title for EVENTS -->
<div class="title-mobile-wrapper">
<span class="title-mob-main">WORKSPACE</span> 
&raquo;
<span class="title-mob-sub">EVENTS</span>
</div>
<!--/ END : Title for EVENTS -->

<div class="zeropadding">

<div  id="scroll-mob-nav">
    
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li class="active">
          <a href="#eveone" role="tab" data-toggle="tab">
               All 
          </a>
      </li>
      <li><a href="#evetwo" role="tab" data-toggle="tab" class="myeven">
          My
          </a>
      </li>
      <li>
          <a href="#evethree" role="tab" data-toggle="tab" class="acc">
              Accepted Invites
          </a>
      </li>
    </ul>
	</div>
	
	<!-- Sub tabs -->
	<!--<ul class="nav nav-tabs sub-tabs" role="tablist">
      <li class="active">
          <a href="#subeveone" role="tab" data-toggle="tab">
               All
          </a>
      </li>
      <li><a href="#subevetwo" role="tab" data-toggle="tab">
          People you follow
          </a>
      </li>
      <li>
          <a href="#subevethree" role="tab" data-toggle="tab">
              Groups you have joined
          </a>
      </li>
    </ul>-->
	
    
    <!-- Tab panes -->
    <div class="tab-content">
	
      <div class="tab-pane fade active in" id="eveone">
	  <?php if($D->alleventcount > 0){  ?>
	  
	  <div class="col-xs-12"><a  id="aleve" title="Calendar View" class="btn calander-view"><span class="glyphicon glyphicon-calendar"></span> Calendar View</a></div>
	  <?php } ?>
	  <div class="col-xs-12"><a  id="list" title="List View" class="btn btn-primary calander-view" style="display:none"><span class="glyphicon glyphicon-list"></span> List View</a></div>
    <div id="allevents">
	  {%allevents%}
	  </div>
	  <input type="hidden" id="all-show-count" value="10">
	   <?php if($D->alleventcount >= 10){  ?>
	  <div class="show-more-container">
	  <a id="all-show"class="show-more"><span>Show more</span></a>
	  </div>
	   <?php } ?>


      </div>
      <div class="tab-pane fade" id="evetwo">
	   <?php if($D->myeventcount > 0){  ?>
	   <div class="col-xs-12"><a  id="myevents" title="Calendar View" class="btn calander-view"><span class="glyphicon glyphicon-calendar"></span> Calendar View</a></a></div>
	   <?php } ?>
	   	  <div><a  id="list_myeventlist_myevent" title="Calendar View" class="btn btn-primary calander-view" style="display:none"><span class="glyphicon glyphicon-list"></span> List View</a></div>

   
		   <div id="myeven">{%myevents%}</div>
		    <input type="hidden" id="my-show-count" value="10">
			   <?php if($D->myeventcount >= 10){  ?>
		  <div class="show-more-container">

	  <a   id="my-show"class="show-more"><span>Show more</span></a>
	  </div>
			   <?php } ?>
      </div>
      <div class="tab-pane fade" id="evethree">
	   <?php if($D->acceptcount > 0){  ?>
	  	   <div class="col-xs-12"><a  id="acceptevents" title="Calendar View" class="btn calander-view"><span class="glyphicon glyphicon-calendar"></span> Calendar View</a></a></div>
	   <?php } ?>
		   	   	  <div><a  id="list_accept" title="Calendar View" class="btn btn-primary calander-view" style="display:none"><span class="glyphicon glyphicon-list"></span> List View</a></div>


	  <div id="accept">{%acceptevents%}</div>
	   <input type="hidden" id="accept-show-count" value="10">
	      <?php if($D->acceptcount >= 10){  ?>

	   	  <div class="show-more-container">
	  <a   id="accept-show"class="show-more"><span>Show more</span></a>
	  </div>
		  <?php } ?>
           

      </div>
    </div>
	
	
	<!-- Sub tab panes -->
  <!-- <div class="tab-content">
      <div class="tab-pane fade active in" id="subeveone">
          <p>All contents here</p>
      </div>
      <div class="tab-pane fade" id="subevetwo">
          <p>People you follow contents here</p>
      </div>
      <div class="tab-pane fade" id="subevethree">
          <p>Groups you have joined contents here</p>
      </div>
    </div> -->
    
</div>



<style>
/* Tab Navigation */
.nav-tabs {
    margin: 0;
    padding: 0;
    border: 0;    
}
.nav-tabs > li > a {
	color: #0099d3;
	font-weight:bold;
    border-radius: 0;
}
.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
border:0;
border-color: #FFF;
color: #f7941d;
}
.nav-tabs > li > a:hover {
	background: none;
	border-color: #FFF;
}
 
 
 
 /*Sub Tabs*/
 .sub-tabs > li > a {
    background: #FFF;
	color: #0099d3;
	font-weight:normal;
    border-radius: 0;
}


/* Tab Content */
.tab-content > .tab-pane  {
    border-radius: 0;
    text-align: left;
}

</style>



	<script>
	$(".myeven").click(function(){
	 running = true;
        var end = setInterval(function () {
            if (current >= limit || running == false) {
                running = false;
                clearInterval(end);
            }
			var strVale = "<?php echo  $D->myeventstr;?>";
			arr = strVale.split(',');
			for(i=0; i < arr.length; i++){
				if(arr[i] !=''){
					var a = $("#myevent2-"+arr[i]).height();
					var b = $(".no-comments"+arr[i]).height();
					var c =Number(a)-Number(b);
					$("#alleventjaneesh2"+arr[i]).css("height",c);


				}
				
			}


	

            current++;
        }, 500);
		});
		$(".acc").click(function(){
	 running = true;
        var end = setInterval(function () {
            if (current >= limit || running == false) {
                running = false;
                clearInterval(end);
            }
			var strVale = "<?php echo  $D->allevents;?>";
			arr = strVale.split(',');
			for(i=0; i < arr.length; i++){
				if(arr[i] !=''){
					var a = $("#allevent-"+arr[i]).height();
					var b = $(".replayaccept"+arr[i]).height();
					var c =Number(a)-Number(b);
					$("#myeventjaneesh"+arr[i]).css("height",c);


				}
				
			}

	

            current++;
        }, 500);
		});
$(document).ready(function(){
	
		
	var param  ="<?php  echo $D->param;?>";
	if(param =="myevent"){ 
	$(".myeven").click();
		
	}
	if(param =="accept"){ 
	$(".acc").click();
		
	}
	
});
</script>
<script type="text/javascript">
function parentreplay(postid,eventtype){

	var time = $("#time-"+postid).val();
	   	$.ajax({
					async: true, 
		           cache: false,
					dataType : "html",
					type:"POST",
					data:{postid:postid,time:time,eventtype:eventtype},
					url:"<?php  echo $C->SITE_URL;?>replaypopupnotification",

					success:function(msg){

						 $('#replaypopup'+eventtype+'-'+postid).html('');
						 $('#replaypopup'+eventtype+'-'+postid).html(msg);
						 $('#replaypopup'+eventtype+'-'+postid).modal('show'); 
						
						
						
					}
				});


	
}
function comment(p,a,eventtype)
{
	var postid=a;
	var message=$("#message-"+a).val();


	$.ajax({
		type:'POST',
		data:{postid:postid,message:message,chieldid:a,alterparentid:p},
		url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=comment'?>",
		success:function(response)
		{
			if(p == a){
			STX.showMessage("Your Tweet to  has been sent!", "success");


			

			
			$("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
			

			$('#replaypopup'+eventtype+'-'+p).modal('toggle');;
			$("body").animate({ scrollTop: 0 }, "slow");

			$(".replayhide-"+p).hide();
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:event";

			/*if(eventtype == 1){
			$("#allevents").prepend(response);
			}
			if(eventtype == 2){
			$("#myeven").prepend(response);
			}
			if(eventtype == 3){
			$("#accept").prepend(response);
			}*/
			


			}else{
				
			STX.showMessage("Your Tweet to  has been sent!", "success");


			/*$("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
			$("#replydis-"+a).html('<a style="color:blue;cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('+p+','+a+')">View Replies</a>');
			

			$('#replaypopup'+eventtype+'-'+p).modal('toggle');;
			//$(".replayhide-"+p).hide();
			$("body").animate({ scrollTop: 0 }, "slow");
			//$(".replay-attach").prepend(response);
           if(eventtype == 1){
			$("#allevents").prepend(response);
			}
			if(eventtype == 2){
			$("#myeven").prepend(response);
			}
			if(eventtype == 3){
			$("#accept").prepend(response);
			}*/
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:event";

			
			


				
			}
			


			
		}
	});
}
function commentevent(p,a,evnttype)
{

	   var title =$("#title-"+a).val();

	   if(title == ''){
		   $("#title-"+a).css('border-color','#B94A48');
		   return false;
		   
	   }
	   
	   var startdate      = $("#start_date-"+a).val();
	   var startime     =  $("#start_time-"+a).val();
	   var end_date     =  $("#end_date-"+a).val();
	   var end_time     =  $("#end_time-"+a).val();



	  if(startdate ==''){
		  $("#start_date-"+a).css('border-color','#B94A48');
		   $("#start_time-"+a).css('border-color','#B94A48');
		    $("#end_date-"+a).css('border-color','#B94A48');
			 $("#end_time-"+a).css('border-color','#B94A48');
		  return false;
		  
	  } 
	   
	    var city =$("#city-"+a).val();
	    if(city == ''){
		   $("#city-"+a).css('border-color','#B94A48');
		   return false;
		 }
		var url =$("#url-"+a).val();
		var description  =$("#description-"+a).val();
		var hastag      =$("#hastag-"+a).val();
		var grouptxt      =$("#grouptxt-"+a).val();
		var usertxt      =$("#usertxt-"+a).val();
	    var postid=a;
	    var message=$("#message-"+a).val();


	$.ajax({
		type:'POST',
		data:{postid:postid,message:message,chieldid:a,alterparentid:p,title:title,start_date:startdate,start_time:startime,end_date:end_date,end_time:end_time,address:city,url:url,description:description,hastag:hastag,street_group:grouptxt,street_user:usertxt},
		url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=eventcomment'?>",
		success:function(response)
		{
			if(p == a){

			
			$('#replaypopup'+evnttype+'-'+p).modal('toggle');;
			$(".replayhide-"+p).hide();
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:event";
			/*if(evnttype == 1){
			$("#allevents").prepend(response);
			}
			if(evnttype == 2){
			$("#myeven").prepend(response);
			}
			if(evnttype == 3){
			$("#accept").prepend(response);
			}
			*/
			
			

			//$(".replay-attach").prepend(response);

			}else{
			//$("#replydis-"+a).html(response);

			//$("#message-"+a).val('');
			//var user = $("#user-"+a).val();
			//$("#message-"+a).val(user);
			//$(".replayhide-"+p).hide();
			/*$("#replydis-"+a).html('<a style="color:blue;cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('+p+','+a+')">View Replies</a>');



			$('#replaypopup'+evnttype+'-'+p).modal('toggle');;
			$("body").animate({ scrollTop: 0 }, "slow");


			//$(".replay-attach").prepend(response);
			/* if(evnttype == 1){
			$("#allevents").prepend(response);
			}
			if(evnttype == 2){
			$("#myeven").prepend(response);
			}
			if(evnttype == 3){
			$("#accept").prepend(response);
			}
			*/
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:event";


				
			}
			


			
		}
	});

}
function commentpoll(p,a,evnttype){
	alert(evnttype);
	var question =$("#question-"+a).val();
	if(question == ""){
		  $("#question-"+a).css('border-color','#B94A48');
		return false;
		
		
	}
	var answer1 =$("#answer0-"+a).val();
	if(answer1 == ""){
		  $("#answer0-"+a).css('border-color','#B94A48');
		return false;
		
	}
	var answer2 =$("#answer1-"+a).val();
	if(answer2 == ""){
		  $("#answer1-"+a).css('border-color','#B94A48');
		return false;
		
	}


var answe =[];
	$(".answerspoll-"+a).each(function(){
		     var ans     =$(this).val();
			 answe.push(ans);
		
	});

	var group = $("#pollgrouptxt-"+a).val();
	var users = $("#pollusertxt-"+a).val();
	$.ajax({
					async: true, 
		           cache: false,
					dataType : "html",
					type:"POST",
					data:{postid:a,chieldid:a,alterparentid:p,question:question,answers:answe,group:group,users:users},
					url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=pollcomment'?>",
                     success:function(msg){
						 if(p == a){
			

			
			/*$("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
			

			$('#replaypopup'+eventtype+'-'+p).modal('toggle');;
			$(".replayhide-"+p).hide();*/
						window.location.href="<?php echo $C->SITE_URL?>notification/tab:event";


			//$(".replay-attach").prepend(msg);
			/*if(evnttype == 1){
			$("#allevents").prepend(msg);
			}
			if(eventtype == 2){
			$("#myeven").prepend(response);
			}
			if(eventtype == 3){
			$("#accept").prepend(response);
			}*/
			


			}else{
			//$("#replydis-"+a).html(response);

			/*$("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
			$("#replydis-"+a).html('<a style="color:blue;cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('+p+','+a+')">View Replies</a>');

			

			$('#replaypopup'+eventtype+'-'+p).modal('toggle');;
			$(".activity-feed-list").prepend(response);*/

			//$(".replay-attach").prepend(msg);
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:event";


				
			}
					}
				});
	
	
	
}
function commentintraday(p,a,evnttype){

	var sharemarketdataaAQWE = [];
	var message = $("#message-"+a).val();



	$('.assets').each(function(){
					   var valu  =$(this).val();
					   var stockdata         =$(".assetdata"+valu).val();

					    var ticker         =$(".tickers"+valu).val();

					   sharemarketdataaAQWE[valu] = [stockdata,ticker];
   });
	$.ajax({
					async: true, 
		           cache: false,
					dataType : "html",
					type:"POST",
					data:{postid:a,chieldid:a,alterparentid:p,sharemarketdataa:sharemarketdataaAQWE,message:message},
					url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=intradaycomment'?>",
                     success:function(msg){
						 if(p == a){
						window.location.href="<?php echo $C->SITE_URL?>notification/tab:event";

			

			
			/*$("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
			

			$('#replaypopup'+eventtype+'-'+p).modal('toggle');;
			$(".replayhide-"+p).hide();
			if(evnttype == 1){
			$("#allevents").prepend(msg);
			}*/


			//$(".replay-attach").prepend(msg);

			}else{

			/*$("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
		    $("#replydis-"+a).html('<a style="color:blue;cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('+p+','+a+')">View Replies</a>');

			

			$('#replaypopup'+eventtype+'-'+p).modal('toggle');;
			if(evnttype == 1){
			$("#allevents").prepend(msg);
			}
			if(eventtype == 2){
			$("#myeven").prepend(response);
			}*/
						window.location.href="<?php echo $C->SITE_URL?>notification/tab:event";

			


			//$(".replay-attach").prepend(msg);
				
			}
					}
				});
	
	
	
}
function childpopup(postid,childid,eventtype){
		var time = $("#time-"+childid).val();

		$.ajax({
					async: true, 
		           cache: false,
					dataType : "html",
					type:"POST",
					data:{postid:postid,childid:childid,time:time,eventtype:eventtype},
					url:"<?php  echo $C->SITE_URL;?>childpopupnotification",

					success:function(msg){
						 $('#replaypopup'+eventtype+'-'+postid).html('');
						 $('#replaypopup'+eventtype+'-'+postid).html(msg);
						 $('#replaypopup'+eventtype+'-'+postid).modal('show'); 
						
						
					}
				});

	
}
function childpopuptimeline(postid,childid,eventtype){
		var time = $("#time-"+childid).val();

		$.ajax({
					async: true, 
		           cache: false,
					dataType : "html",
					type:"POST",
					data:{postid:postid,childid:childid,time:time,eventtype:eventtype},
					url:"<?php  echo $C->SITE_URL;?>childpopuptimelinenotification",

					success:function(msg){
						 $('#replaypopup'+eventtype+'-'+childid).html('');
						 $('#replaypopup'+eventtype+'-'+childid).html(msg);
						 $('#replaypopup'+eventtype+'-'+childid).modal('show'); 
						
						
					}
				});

	
}
function commenteventtimelines(p,a,eventtype)
{
	var title =$("#title-"+a).val();

	   if(title == ''){
		   $("#title-"+a).css('border-color','#B94A48');
		   return false;
		   
	   }
	   
	   var startdate      = $("#start_date-"+a).val();
	   var startime     =  $("#start_time-"+a).val();
	   var end_date     =  $("#end_date-"+a).val();
	   var end_time     =  $("#end_time-"+a).val();



	  if(startdate ==''){
		  $("#start_date-"+a).css('border-color','#B94A48');
		   $("#start_time-"+a).css('border-color','#B94A48');
		    $("#end_date-"+a).css('border-color','#B94A48');
			 $("#end_time-"+a).css('border-color','#B94A48');
		  return false;
		  
	  } 
	   
	    var city =$("#city-"+a).val();
	    if(city == ''){
		   $("#city-"+a).css('border-color','#B94A48');
		   return false;
		 }
		var url =$("#url-"+a).val();
		var description  =$("#description-"+a).val();
		var hastag      =$("#hastag-"+a).val();
		var grouptxt      =$("#grouptxt-"+a).val();
		var usertxt      =$("#usertxt-"+a).val();
	    var postid=a;
	    var message=$("#message-"+a).val();



	$.ajax({
		type:'POST',
		data:{postid:postid,message:message,chieldid:a,alterparentid:p,title:title,start_date:startdate,start_time:startime,end_date:end_date,end_time:end_time,address:city,url:url,description:description,hastag:hastag,street_group:grouptxt,street_user:usertxt},
		url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=eventcomment'?>",
		success:function(response)
		{
	
			STX.showMessage("Your Tweet to  has been sent!", "success");
		   window.location.href="<?php echo $C->SITE_URL?>notification/tab:event";




			

			/*$('#replaypopup'+eventtype+'-'+a).modal('toggle');;
			$("body").animate({ scrollTop: 0 }, "slow");
			if(eventtype == 1 ){
				$("#allevents").prepend(response);

			}
			if(eventtype == 2){
			$("#myeven").prepend(response);
			}
			if(eventtype == 3){
			$("#accept").prepend(response);
			}*/
			
			

			//$(".replayhide-"+p).hide();
			//$(".replay-attach").prepend(response);



			


			
		}
	});
}
function replaycontenttimeline(postid,childid,eventtype){
	
	var view_type="view";
		$.ajax({
					async: true, 
		           cache: false,
					dataType : "html",
					type:"POST",
					data:{childid:childid,postid:postid,view_type:view_type,eventtype:eventtype},
					url:"<?php  echo $C->SITE_URL;?>allrepliesnotification",

					success:function(msg){
						 $('#replaypopup'+eventtype+'-'+postid).html('');
						 $('#replaypopup'+eventtype+'-'+postid).html(msg);
						 $('#replaypopup'+eventtype+'-'+postid).modal('show'); 
						
						
					}
				});
	
	
}
function commenteventalllevels(p,a,level,eventtype)
{
	var title =$("#title-"+a).val();

	   if(title == ''){
		   $("#title-"+a).css('border-color','#B94A48');
		   return false;
		   
	   }
	   
	   var startdate      = $("#start_date-"+a).val();
	   var startime     =  $("#start_time-"+a).val();
	   var end_date     =  $("#end_date-"+a).val();
	   var end_time     =  $("#end_time-"+a).val();



	  if(startdate ==''){
		  $("#start_date-"+a).css('border-color','#B94A48');
		   $("#start_time-"+a).css('border-color','#B94A48');
		    $("#end_date-"+a).css('border-color','#B94A48');
			 $("#end_time-"+a).css('border-color','#B94A48');
		  return false;
		  
	  } 
	   
	    var city =$("#city-"+a).val();
	    if(city == ''){
		   $("#city-"+a).css('border-color','#B94A48');
		   return false;
		 }
		var url =$("#url-"+a).val();
		var description  =$("#description-"+a).val();
		var hastag      =$("#hastag-"+a).val();
		var grouptxt      =$("#grouptxt-"+a).val();
		var usertxt      =$("#usertxt-"+a).val();
	    var postid=a;
	    var message=$("#message-"+a).val();


	$.ajax({
		type:'POST',
		data:{postid:postid,message:message,chieldid:a,alterparentid:p,title:title,start_date:startdate,start_time:startime,end_date:end_date,end_time:end_time,address:city,url:url,description:description,hastag:hastag,street_group:grouptxt,street_user:usertxt},
		url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=eventcomment'?>",
		success:function(response)
		{
			
			STX.showMessage("Your Tweet to  has been sent!", "success");
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:event";

			/*
            $("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
			

			$('#replaypopup'+eventtype+'-'+level).modal('toggle');;
			//$(".replayhide-"+level).hide();

			//$(".replayhide-"+p).hide();
			$("body").animate({ scrollTop: 0 }, "slow");
			//$(".replay-attach").prepend(response);
			if(eventtype == 1){
			$("#allevents").prepend(response);
			}
			if(eventtype == 2){
			$("#myeven").prepend(response);
			}
			if(eventtype == 3){
			$("#accept").prepend(response);
			}*/
			

         }
	});
}
function replaycontent(postid,childid,eventtype){
	var view_type="individual";
		$.ajax({
					async: true, 
		           cache: false,
					dataType : "html",
					type:"POST",
					data:{childid:childid,postid:postid,view_type:view_type,eventtype:eventtype},
					url:"<?php  echo $C->SITE_URL;?>allrepliesnotification",

					success:function(msg){
						 $('#replaypopup'+eventtype+'-'+childid).html('');
						 $('#replaypopup'+eventtype+'-'+childid).html(msg);
						 $('#replaypopup'+eventtype+'-'+childid).modal('show'); 
						
						
					}
				});
	
	
}
function commentalllevvels(p,a,level)
{
	var postid=a;
	var message=$("#message-"+a).val();


	$.ajax({
		type:'POST',
		data:{postid:postid,message:message,chieldid:a,alterparentid:p},
		url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=comment'?>",
		success:function(response)
		{
			
			STX.showMessage("Your Tweet to  has been sent!", "success");
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:event";

            

         }
	});
}
function commentpollalllevels(p,a,level,eventtype)
{
	var question =$("#question-"+a).val();
	if(question == ""){
		  $("#question-"+a).css('border-color','#B94A48');
		return false;
		
		
	}
	var answer1 =$("#answer0-"+a).val();
	if(answer1 == ""){
		  $("#answer0-"+a).css('border-color','#B94A48');
		return false;
		
	}
	var answer2 =$("#answer1-"+a).val();
	if(answer2 == ""){
		  $("#answer1-"+a).css('border-color','#B94A48');
		return false;
		
	}


var answe =[];
	$(".answerspoll-"+a).each(function(){
		     var ans     =$(this).val();
			 answe.push(ans);
		
	});

	var group = $("#pollgrouptxt-"+a).val();
	var users = $("#pollusertxt-"+a).val();


	$.ajax({
		type:'POST',
		data:{postid:a,chieldid:a,alterparentid:p,question:question,answers:answe,group:group,users:users},
					url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=pollcomment'?>",
		success:function(response)
		{
			
			STX.showMessage("Your Tweet to  has been sent!", "success");
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:event";

           /* $("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
			alert(level);

			$('#replaypopup'+eventtype+'-'+level).modal('toggle');;

			//$(".replayhide-"+level).hide();

			//$(".replayhide-"+p).hide();
			$("body").animate({ scrollTop: 0 }, "slow");
			if(eventtype == 4){
				alert(eventtype);
			$("#allpolls").prepend(response);
			}
			if(eventtype == 5){
			$("#mypolls").prepend(response);
			}
			if(eventtype == 6){
			$("#mypollresponsresponse").prepend(response);
			}*/
			//$(".replay-attach").prepend(response);

         }
	});
}
function commentintradayalllevels(p,a,level,eventtype)
{
	var sharemarketdataaAQWE = [];
	var message = $("#message-"+a).val();



	$('.assets').each(function(){
					   var valu  =$(this).val();
					   var stockdata         =$(".assetdata"+valu).val();

					    var ticker         =$(".tickers"+valu).val();

					   sharemarketdataaAQWE[valu] = [stockdata,ticker];
   });
   $.ajax({
		type:'POST',
		 data:{postid:a,chieldid:a,alterparentid:p,sharemarketdataa:sharemarketdataaAQWE,message:message},
		url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=intradaycomment'?>",
		success:function(response)
		{
			STX.showMessage("Your Tweet to  has been sent!", "success");
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:event";

            /*$("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
			

			$('#replaypopup'+eventtype+'-'+level).modal('toggle');;

			//$(".replayhide-"+p).hide();
			$("body").animate({ scrollTop: 0 }, "slow");
			//$(".replay-attach").prepend(response);
            if(evnttype == 7){
			$("#allintradayshow").prepend(response);
			}
			if(evnttype == 8){
			$("#followintra").prepend(response);
			}
			if(evnttype == 9){
			$("#open-data").prepend(response);
			}
			if(evnttype == 10){
			$("#myopen_close").prepend(response);
			}*/
         }
	});


}
function commenttimelines(p,a)
{
	var postid=a;
	var message=$("#message-"+a).val();


	$.ajax({
		type:'POST',
		data:{postid:postid,message:message,chieldid:a,alterparentid:p},
		url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=comment'?>",
		success:function(response)
		{
	
			STX.showMessage("Your Tweet to  has been sent!", "success");
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:event";



			/*$("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
			//$(".replayhide-"+a).hide();

			

			$('#replaypopup-'+a).modal('toggle');;
			$("body").animate({ scrollTop: 0 }, "slow");
			

			//$(".replayhide-"+p).hide();
			//$(".replay-attach").prepend(response);
			$(".activity-feed-list").prepend(response);*/



			


			
		}
	});
}
function commentpolltimelines(p,a,eventtype)
{
	var question =$("#question-"+a).val();
	if(question == ""){
		  $("#question-"+a).css('border-color','#B94A48');
		return false;
		
		
	}
	var answer1 =$("#answer0-"+a).val();
	if(answer1 == ""){
		  $("#answer0-"+a).css('border-color','#B94A48');
		return false;
		
	}
	var answer2 =$("#answer1-"+a).val();
	if(answer2 == ""){
		  $("#answer1-"+a).css('border-color','#B94A48');
		return false;
		
	}


var answe =[];
	$(".answerspoll-"+a).each(function(){
		     var ans     =$(this).val();
			 answe.push(ans);
		
	});

	var group = $("#pollgrouptxt-"+a).val();
	var users = $("#pollusertxt-"+a).val();




	$.ajax({
		type:'POST',
		data:{postid:a,chieldid:a,alterparentid:p,question:question,answers:answe,group:group,users:users},
		url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=pollcomment'?>",
		success:function(response)
		{
	
			STX.showMessage("Your Tweet to  has been sent!", "success");
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:event";



			/*$("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
			//$(".replayhide-"+a).hide();

			

			$('#replaypopup'+eventtype+'-'+a).modal('toggle');;
			$("body").animate({ scrollTop: 0 }, "slow");
			if(eventtype == 4){
			$("#allpolls").prepend(response);
			}
			if(eventtype == 5){
			$("#mypolls").prepend(response);
			}
			if(eventtype == 6){
			$("#mypollresponsresponse").prepend(response);
			}
			

			//$(".replayhide-"+p).hide();
			//$(".replay-attach").prepend(response);*/



			


			
		}
	});
}
function commentintradaytimelines(p,a,eventtype)
{
	var sharemarketdataaAQWE = [];
	var message = $("#message-"+a).val();



	$('.assets').each(function(){
					   var valu  =$(this).val();
					   var stockdata         =$(".assetdata"+valu).val();

					    var ticker         =$(".tickers"+valu).val();

					   sharemarketdataaAQWE[valu] = [stockdata,ticker];
   });




	$.ajax({
		type:'POST',
		 data:{postid:a,chieldid:a,alterparentid:p,sharemarketdataa:sharemarketdataaAQWE,message:message},
		url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=intradaycomment'?>",
		success:function(response)
		{
	
			STX.showMessage("Your Tweet to  has been sent!", "success");
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:event";



			/*$("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
			//$(".replayhide-"+a).hide();

			

			$('#replaypopup'+eventtype+'-'+a).modal('toggle');;
			$("body").animate({ scrollTop: 0 }, "slow");
			if(eventtype == 7){
			$("#allintradayshow").prepend(response);
			}
			if(eventtype == 8){
			$("#followintra").prepend(response);
			}
			if(evnttype == 9){
			$("#open-data").prepend(response);
			}
			if(evnttype == 10){
			$("#myopen_close").prepend(response);
			}*/
			
			
		}
	});
}
</script>