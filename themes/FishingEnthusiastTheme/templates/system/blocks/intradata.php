<script src="<?php echo $C->SITE_URL;?>static/js/jquery.js?v=3.6.0"></script>

<!-- START : Title for INTRADAY -->
<div class="title-mobile-wrapper">
<span class="title-mob-main">WORKSPACE</span> 
&raquo;
<span class="title-mob-sub">INTRADAY</span>
</div>
<!--/ END : Title for INTRADAY -->

<div class="zeropadding">

<div  id="scroll-mob-nav"> 
 
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li class="active">
          <a href="#allintraday" role="tab" data-toggle="tab">
               All
          </a>
      </li>
      <li><a href="#peopleintraday" role="tab" data-toggle="tab" class="peoplefollow">
          People I Follow
          </a>
      </li>
      <li>
          <a href="#myintraday" role="tab" data-toggle="tab" class="myresponse">
             My
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
      <div class="tab-pane fade active in" id="allintraday">
	  
    <div id="allintradayshow">
	  {%allintrahtml%}
	  </div>
	  <input type="hidden" id="allintra-show-count" value="10">
	  <?php if($D->allintracount == 10){?>
	  
	  <div class="show-more-container">
	  <a id="all-intra-show" class="show-more"><span>Show more</span></a>
	  </div>
	  	  <?php }  ?>



      </div>
      <div class="tab-pane fade" id="peopleintraday">

   
		   <div id="followintra">{%followintrahtml%}</div>
		    <input type="hidden" id="follow-show-count" value="10">
				  <?php if($D->followcount == 10){?>

		  <div class="show-more-container">

	  <a   id="follow-intra-show"class="show-more"><span>Show more</span></a>
	  </div>
				  <?php } ?>
      </div>
     
	<?php if(!empty($D->incorrectpercntage)){
	$incorrect = $D->incorrectpercntage;
    }else{
	$incorrect =0;
}
if(!empty($D->correctpercntage)){
	$correct = $D->correctpercntage;
	if($correct ==NAN){
		$correct =0;
	}else{
		$correct =$correct;
	}
    }else{
	$correct =0;
}
			  ?>
		 <!--My Intraday tab menu-->
      <div id="myintraday" class="sub-tab-top-border tab-pane">
          <ul class="nav nav-tabs sub-tab-ul-intraday" id="brands_tabs">
			  
              <li class="active"><a href="#my_open" data-toggle="tab" class="correct">Correct(<?php echo $correct;?>%)</a></li>
			  
              <li><a href="#my_close" data-toggle="tab" class="myincorrect">Incorrect(<?php echo $incorrect;?>%)</a></li>
			  			   <li><a href="#my_openresults" data-toggle="tab" class="myoencorrect">Open(<?php echo $D->openper;?>%)</a>

          </ul>
          
          <!--brands tab content-->
          <div class="tab-content">
              <div id="my_open" class="tab-pane active">
			  <div id="myintradaycorrect">
			   <?php if($D->totalcorrect == 0){
				   echo "No intradays found";
			   }else{?>
                  {%myintradaycorrectintrahtml%}
			   <?php } ?>
			   </div>
			   <input type="hidden" id="myintradaycorrectshowcnt" value="10">
			   <?php if($D->totalcorrect == 10){ ?>
			   <div class="show-more-container">
				<a id="myintraday-correct-show" class="show-more"><span>Show more</span></a>
			  </div>
			   <?php } ?>

			  
              </div>
			  
			  <div id="my_close" class="tab-pane">
			 
              <div id="myopen_close" >
			  <?php if($D->totalincorrect == 0){
				  echo "No intradays found";
			  }else{?>
                  {%myintradayincorrectintrahtml%}
			  <?php } ?>
			  </div>
			   <input type="hidden" id="myintradayincorrectshowcnt" value="10">
			   <?php if($D->totalincorrect == 10){ ?>
			   <div class="show-more-container">
				<a id="myintraday-incorrect-show" class="show-more"><span>Show more</span></a>
			  </div>
			   <?php } ?>
              </div>
			   <div id="my_openresults" class="tab-pane">
			 
              <div id="myopenresults" >
			  <?php if($D->totalopencorrect == 0){
				  echo "No intradays found";
			  }else{?>
                  {%myintradayoenhtml%}
			  <?php } ?>
			  </div>
			   <input type="hidden" id="myintradayopencnt" value="10">
			   <?php if($D->totalopencorrect == 10){ ?>
			   <div class="show-more-container">
				<a id="myintraday-open-show" class="show-more"><span>Show more</span></a>
			  </div>
			   <?php } ?>
              </div>
          </div>
          
      </div><!--my prediction tab pane-->

	  
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
	$(".peoplefollow").click(function(){
	 running = true;
        var end = setInterval(function () {
            if (current >= limit || running == false) {
                running = false;
                clearInterval(end);
            }
			var strVale = "<?php echo  $D->followstr;?>";
			arr = strVale.split(',');
			for(i=0; i < arr.length; i++){
				if(arr[i] !=''){
					var a = $("#followintra"+arr[i]).height();
					var b = $(".followintrachild"+arr[i]).height();
					var c =Number(a)-Number(b);
					$("#followjaneesh"+arr[i]).css("height",c);


				}
				
			}


	

            current++;
        }, 1000);
		});
		$(".myresponse,correct").click(function(){
	 running = true;
        var end = setInterval(function () {
            if (current >= limit || running == false) {
                running = false;
                clearInterval(end);
            }
			var strVale = "<?php echo  $D->myintrastr;?>";
			arr = strVale.split(',');
			for(i=0; i < arr.length; i++){
				if(arr[i] !=''){
					var a = $("#intracorrect"+arr[i]).height();
					var b = $(".correctcild"+arr[i]).height();
					var c =Number(a)-Number(b);
					$("#correctjaneesh"+arr[i]).css("height",c);


				}
				
			}


	

            current++;
        }, 1000);
		});
		$(".myincorrect").click(function(){
	 running = true;
        var end = setInterval(function () {
            if (current >= limit || running == false) {
                running = false;
                clearInterval(end);
            }
			var strVale = "<?php echo  $D->incorrectstr;?>";
			arr = strVale.split(',');
			for(i=0; i < arr.length; i++){
				if(arr[i] !=''){
					var a = $("#myintraincorrect"+arr[i]).height();
					var b = $(".incorrectchild"+arr[i]).height();
					var c =Number(a)-Number(b);
					$("#incorrectjannesh"+arr[i]).css("height",c);


				}
				
			}


	

            current++;
        }, 1000);
		});
				$(".myoencorrect").click(function(){
	 running = true;
        var end = setInterval(function () {
            if (current >= limit || running == false) {
                running = false;
                clearInterval(end);
            }
			var strVale = "<?php echo  $D->openstr;?>";
			arr = strVale.split(',');
			for(i=0; i < arr.length; i++){
				if(arr[i] !=''){
					var a = $("#myopenintra"+arr[i]).height();
								alert(a);

					var b = $(".openchild"+arr[i]).height();
					var c =Number(a)-Number(b);
					$("#openjannesh"+arr[i]).css("height",c);


				}
				
			}


	

            current++;
        }, 1000);
		});
	
$(document).ready(function(){
	$("ul.nav-tabs a").click(function (e) {
  e.preventDefault();  
    $(this).tab('show');
});

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
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:Intraday";



			

			
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
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:Intraday";



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
				window.location.href="<?php echo $C->SITE_URL?>notification/tab:Intraday";


			
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
			}*/
			
			

			//$(".replay-attach").prepend(response);

			}else{
			//$("#replydis-"+a).html(response);

			//$("#message-"+a).val('');
			//var user = $("#user-"+a).val();
			//$("#message-"+a).val(user);
			//$(".replayhide-"+p).hide();
			$("#replydis-"+a).html('<a style="color:blue;cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('+p+','+a+')">View Replies</a>');



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
			}
			

				
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
						window.location.href="<?php echo $C->SITE_URL?>notification/tab:Intraday";


			

			
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

			$("#message-"+a).val('');
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
			}

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
						window.location.href="<?php echo $C->SITE_URL?>notification/tab:Intraday";



			
			/*$("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
			

			$('#replaypopup'+evnttype+'-'+p).modal('toggle');;
			$(".replayhide-"+p).hide();
			if(evnttype == 7){
			$("#allintradayshow").prepend(msg);
			}
			if(evnttype == 8){
			$("#followintra").prepend(msg);
			}
			if(evnttype == 9){
			$("#open-data").prepend(msg);
			}
			if(evnttype == 10){
			$("#myopen_close").prepend(msg);
			}*/



			//$(".replay-attach").prepend(msg);

			}else{
				STX.showMessage("Your Tweet to  has been sent!", "success");
				window.location.href="<?php echo $C->SITE_URL?>notification/tab:Intraday";



			/*$("#message-"+a).val('');
			var user = $("#user-"+a).val();
			$("#message-"+a).val(user);
		    $("#replydis-"+a).html('<a style="color:blue;cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('+p+','+a+')">View Replies</a>');

			

			$('#replaypopup'+evnttype+'-'+p).modal('toggle');;
			if(evnttype == 7){
			$("#allintradayshow").prepend(msg);
			}
			if(evnttype == 8){
			$("#followintra").prepend(msg);
			}
			if(evnttype == 9){
			$("#my_open").prepend(msg);
			}
			if(evnttype == 10){
			$("#myopen_close").prepend(msg);
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
            window.location.href="<?php echo $C->SITE_URL?>notification/tab:Intraday";



			

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
						window.location.href="<?php echo $C->SITE_URL?>notification/tab:Intraday";

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
						alert(msg);
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
						STX.showMessage("Your Tweet to  has been sent!", "success");

          /*  $("#message-"+a).val('');
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
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:Intraday";



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
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:Intraday";

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
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:Intraday";



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
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:Intraday";



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
			window.location.href="<?php echo $C->SITE_URL?>notification/tab:Intraday";

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
</script>

