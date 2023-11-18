<script src="<?php echo $C->SITE_URL;?>static/js/jquery.js?v=3.6.0"></script>

<!-- START : Title for POLLS -->
<div class="title-mobile-wrapper">
<span class="title-mob-main">WORKSPACE</span> 
&raquo;
<span class="title-mob-sub">POLLS</span>
</div>
<!--/ END : Title for POLLS -->

<div class="zeropadding">

	<!-- START : Scroll -->
	<div  id="scroll-mob-nav">
		
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li class="active">
          <a href="#eveone" role="tab" data-toggle="tab">
               All
          </a>
      </li>
      <li><a href="#evetwo" role="tab" data-toggle="tab" class="mypolls">
          My
          </a>
      </li>
      <li>
          <a href="#evethree" role="tab" data-toggle="tab" class="myresponse">
             My Responses
          </a>
      </li>
		
    </ul>
	</div><!--/ END : Scroll -->
	
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
	  
    <div id="allpolls">
	  {%poll_all_html%}
	  </div>
	  <input type="hidden" id="allpoll-show-count" value="10">
	  <?php if($D->allpollscount == 10){?>
	  
	  <div class="show-more-container">
	  <a id="all-poll-show"class="show-more"><span>Show more</span></a>
	  </div>
	  	  <?php }  ?>



      </div>
      <div class="tab-pane fade" id="evetwo">

   
		   <div id="mypolls">{%poll_my_html%}</div>
		    <input type="hidden" id="mypoll-show-count" value="10">
				  <?php if($D->mypollscount == 10){?>

		  <div class="show-more-container">

	  <a   id="my-poll-show"class="show-more"><span>Show more</span></a>
	  </div>
				  <?php } ?>
      </div>
      <div class="tab-pane fade" id="evethree">


	  <div id="mypollresponsresponse">{%poll_myresponse_html%}</div>
	   <input type="hidden" id="myresponse-show-count" value="10">
	   	  <?php   if($D->myresponsepollscount == 10){?>

	   	  <div class="show-more-container">

	  <a   id="myresponse-show"class="show-more"><span>Show more</span></a>
	  </div>
		  <?php } ?>
           

      </div>
    </div>
	
	
	
    
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
background-color: none!important;
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
    padding: 0px;
}

</style>

	<script>
	
		$(".mypolls").click(function(){
	 running = true;
        var end = setInterval(function () {
            if (current >= limit || running == false) {
                running = false;
                clearInterval(end);
            }
			var strVale = "<?php echo  $D->mypollstr;?>";
			arr = strVale.split(',');
			for(i=0; i < arr.length; i++){
				if(arr[i] !=''){
					var a = $("#mypoll"+arr[i]).height();
					var b = $(".mypollchild"+arr[i]).height();
					var c =Number(a)-Number(b);
					$("#myeventplljaneesh"+arr[i]).css("height",c);


				}
				
			}


	

            current++;
        }, 500);
		});
		$(".myresponse").click(function(){
	 running = true;
        var end = setInterval(function () {
            if (current >= limit || running == false) {
                running = false;
                clearInterval(end);
            }
			var strVale = "<?php echo  $D->myresponsestr;?>";
			arr = strVale.split(',');
			for(i=0; i < arr.length; i++){
				if(arr[i] !=''){
					var a = $("#response"+arr[i]).height();
					var b = $(".responsechild"+arr[i]).height();
					var c =Number(a)-Number(b);
					$("#responsejaneesh"+arr[i]).css("height",c);


				}
				
			}


	

            current++;
        }, 500);
		});
$(document).ready(function(){
	var param  ="<?php  echo $D->param;?>";
	if(param =="mypolls"){ 
	$(".mypolls").click();
		
	}
	if(param =="myresponse"){ 
	$(".myresponse").click();
		
	}
	
});
</script>
<script type="text/javascript">
function vote(eventtype,pollid){
$(this).hide();
var answerid           = $("#suboption"+eventtype+pollid).attr("answerid");
$.ajax({
					async: true, 
		           cache: false,
					dataType : "html",
					type:"POST",
					data:{pollid:pollid,answerid:answerid},
		          url:"<?php echo $C->SITE_URL.'plugin/poll/admin'?>",

					success:function(msg){
						$("#suboption"+eventtype+pollid).hide();
						$("#replace"+eventtype+pollid).replaceWith(msg);

						
						
						
						
					}
				});


	
}
 function changeurl(pollid,answerid,eventtype){
	   $("#suboption"+eventtype+pollid).attr("data-rel",pollid);
	   $("#suboption"+eventtype+pollid).attr("answerid",answerid);
}

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
           	window.location.href="<?php echo $C->SITE_URL?>notification/tab:polls";


			

			
			/*$("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
			

			$('#replaypopup'+eventtype+'-'+p).modal('toggle');;
			$("body").animate({ scrollTop: 0 }, "slow");

			$(".replayhide-"+p).hide();
			if(eventtype == 1){
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
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:polls";



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
				STX.showMessage("Your Tweet to  has been sent!", "success");
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:polls";

			
			/*$("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
			

			$('#replaypopup'+evnttype+'-'+p).modal('toggle');;
			$(".replayhide-"+p).hide();
			if($.trim(evnttype) == 1){
			$("#allevents").prepend(response);
			}
			if($.trim(evnttype) == 2){
			$("#myeven").prepend(response);
			}
			if($.trim(evnttype) == 3){
			$("#accept").prepend(response);
			}
			*/
			

			//$(".replay-attach").prepend(response);

			}else{
				STX.showMessage("Your Tweet to  has been sent!", "success");
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:polls";
			//$("#replydis-"+a).html(response);

			//$("#message-"+a).val('');
			//var user = $("#user-"+a).val();
			//$("#message-"+a).val(user);
			//$(".replayhide-"+p).hide();
			/*$("#replydis-"+a).html('<a style="color:blue;cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('+p+','+a+')">View Replies</a>');



			$('#replaypopup'+evnttype+'-'+p).modal('toggle');;
			$("body").animate({ scrollTop: 0 }, "slow");


			//$(".replay-attach").prepend(response);
			 if(evnttype == 1){
			$("#allevents").prepend(response);
			}
			if(evnttype == 2){
			$("#myeven").prepend(response);
			}
			if(evnttype == 3){
			$("#accept").prepend(response);
			}*/
			

				
			}
			


			
		}
	});

}
function commentpoll(p,a,evnttype){
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
			
STX.showMessage("Your Tweet to  has been sent!", "success");
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:polls";
			
			/*$("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
			

			$('#replaypopup'+evnttype+'-'+p).modal('toggle');;
			$(".replayhide-"+p).hide();

			//$(".replay-attach").prepend(msg);
			if(evnttype == 4){
			$("#allpolls").prepend(msg);
			}
			if(evnttype == 5){
			$("#mypolls").prepend(msg);
			}
			if(evnttype == 6){
			$("#mypollresponsresponse").prepend(msg);
			}*/
			


			}else{
			//$("#replydis-"+a).html(response);
         STX.showMessage("Your Tweet to  has been sent!", "success");
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:polls";
			/*$("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
			$("#replydis-"+a).html('<a style="color:blue;cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('+p+','+a+')">View Replies</a>');

			

			$('#replaypopup'+evnttype+'-'+p).modal('toggle');;
			if(evnttype == 4){
			$("#allpolls").prepend(msg);
			}
			if(evnttype == 5){
			$("#mypolls").prepend(msg);
			}
			if(evnttype == 6){
			$("#mypollresponsresponse").prepend(msg);
			}*/

			//$(".replay-attach").prepend(msg);

				
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
							 STX.showMessage("Your Tweet to  has been sent!", "success");
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:polls";
			

			
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
				STX.showMessage("Your Tweet to  has been sent!", "success");
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:polls";

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
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:polls";




			

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
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:polls";
           /* $("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
			

			$('#replaypopup'+eventtype+'-'+level).modal('toggle');;
			$(".replayhide-"+level).hide();

			//$(".replayhide-"+p).hide();
			$("body").animate({ scrollTop: 0 }, "slow");
			//$(".replay-attach").prepend(response);
			if(eventtype == 1){
			$("#allevents").prepend(response);
			}
			if(eventtype == 2){
			$("#myeven").prepend(response);
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
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:polls";

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
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:polls";



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
			}*/
			

			//$(".replayhide-"+p).hide();
			//$(".replay-attach").prepend(response);



			


			
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
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:polls";

            

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
function changeopenion(userid,eventtype,pollid,postid){
	
	
	$.ajax({
					async: true, 
		           cache: false,
					dataType : "html",
					type:"POST",
					data:{vote_pollid:pollid,vote_post_id:postid,vote_userid:userid,eventtype:eventtype},
		          url:"<?php echo $C->SITE_URL.'plugin/poll/admin'?>",

					success:function(msg){
						//$(".download"+eventtype+pollid).hide();
						$(".download"+eventtype+pollid).hide();
						$("#changevote"+eventtype+pollid).html('<a onclick="backresponse('+userid+','+eventtype+','+pollid+','+postid+')"><span class="glyphicon glyphicon-edit"></span> Back Response</a>');
						$("#replace"+eventtype+pollid).replaceWith(msg);

						
						
						
						
					}
				});
}
function voteopention(maintype,userid,postid,pollid,eventtype){
var answerid           = $("#suboption"+eventtype+pollid).attr("answerid");
	alert(answerid);
	$.ajax({
					async: true, 
		           cache: false,
					dataType : "html",
					type:"POST",
					data:{vote_update_pollid:pollid,vote_update_answerid:answerid,eventtype:eventtype},
		            url:"<?php echo $C->SITE_URL.'plugin/poll/admin'?>",

					success:function(msg){
						$("#suboption"+eventtype+pollid).hide();
						$("#changevote"+eventtype+pollid).html('<a onclick="changeopenion('+userid+','+eventtype+','+pollid+','+postid+')"><span class="glyphicon glyphicon-edit"></span> Change Vote</a>');

						$("#replace"+eventtype+pollid).replaceWith(msg);

						
						
						
						
					}
				});

	
}
function backresponse(userid,eventtype,pollid,postid){
	var answerid           =0;
	$.ajax({
					async: true, 
		           cache: false,
					dataType : "html",
					type:"POST",
					data:{vote_update_pollid:pollid,vote_update_answerid:answerid,eventtype:eventtype},
		            url:"<?php echo $C->SITE_URL.'plugin/poll/admin'?>",

					success:function(msg){
						$("#suboption"+eventtype+pollid).hide();

						$("#changevote"+eventtype+pollid).html('<a onclick="changeopenion('+userid+','+eventtype+','+pollid+','+postid+')"><span class="glyphicon glyphicon-edit"></span> Change Vote</a>');
                        
						$("#replace"+eventtype+pollid).replaceWith(msg);

						
						
						
						
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

</script>

