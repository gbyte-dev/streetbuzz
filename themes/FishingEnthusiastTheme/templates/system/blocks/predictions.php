<!-- START : Title for Predictions -->
<div class="title-mobile-wrapper">
<span class="title-mob-main">PREDICTIONS</span> 
<!--&raquo;
<span class="title-mob-sub"></span>-->
</div>
<!--/ END : Title for Predictions -->


<div class="input-group">
			<input type="text" class="form-control search-field" style="position:relative;" id="predict_search"  name="lookfor" value="" x-webkit-speech="" autocomplete="off" onwebkitspeechchange="STX.searchReplace();" placeholder="Search an asset to predict price(Start typing with $)">
			<div class="input-group-btn">
				<button class="btn btn-default btn-xs" type="submit" id="predict"><i class="glyphicon glyphicon-search"></i></button>
			</div>
		</div>
		
		
 <div class="htmlarea-ac">
	<div class="htmlarea-ac-container"></div>
	
</div>
 <div id="graph"  class="modal fade" data-backdrop="static" data-keyboard="false"></div>
 <input type="hidden" id="predict_val" value="">


<div class="tabbable">
	<div  id="scroll-mob-nav">
  <ul class="nav nav-tabs" id="interest_tabs">
      <!--top level tabs-->
    <li ><a href="#all" data-toggle="tab">All</a></li>
    <li><a href="#follow" data-toggle="tab">Whom I follow predictions</a></li>
	 <li class="active"><a href="#myprediction" data-toggle="tab">My Predictions</a></li>
  </ul>
</div>
  
  
  <!--top level tab content-->
  <div class="tab-content">
      <!--all tab menu-->
      <div id="all" class="tab-pane ">
         
          <!--all tab content-->
          <div class="tab-content">
              <div id="all_popular" class="tab-pane active">
			  <?php if($D->allpredictcnt == 0){ echo "No Predictions found";}else{?>
			  {%allpredicthtml%}<?php } ?>
              </div>
			  <input type="hidden" id="allprediction-count" value="0">
			   <?php if($D->allpredictcnt == 10){ ?>
			   <div class="show-more-container">
				<a id="allprediction-show" class="show-more"><span>Show more</span></a>
			  </div>
			   <?php } ?>
              
          </div>
      </div><!--all tab pane-->
      
      <!--follow tab menu-->
      <div id="follow" class="tab-pane">
         
          <!--brands tab content-->
          <div class="tab-content">
              <div id="brands_popular" class="tab-pane active">
			  <?php  if($D->followerscount ==0){ ECHO "No predictions found";}else{?>
			  {%youfollowhtml%}<?php } ?>
              </div>
			   <input type="hidden" id="follow-count" value="0">
			   <?php if($D->followerscount == 10){ ?>
			   <div class="show-more-container">
				<a id="follow-show" class="show-more"><span>Show more</span></a>
			  </div>
			   <?php } ?>
             
          </div>
          
      </div><!--brands tab pane-->
  
  
   <!--My prediction tab menu-->
      <div id="myprediction" class="sub-tab-top-border tab-pane active">
          <ul class="nav nav-tabs sub-tab-ul" id="brands_tabs">
              <li class="active"><a href="#my_open" data-toggle="tab">Open Predictions</a></li>
              <li><a href="#my_close" data-toggle="tab">Closed Predictions</a></li>
          </ul>
          
          <!--brands tab content-->
          <div class="tab-content">
              <div id="my_open" class="tab-pane active">
			  <div id="open-data">
			   <?php if($D->opencount == 0){
				   echo "No Predictions found";
			   }else{?>
                  {%mypredictionopenhtml%}
			   <?php } ?>
			   </div>
			   <input type="hidden" id="open-count" value="0">
			   <?php if($D->opencount == 10){ ?>
			   <div class="show-more-container">
				<a id="open-show" class="show-more"><span>Show more</span></a>
			  </div>
			   <?php } ?>

			  
              </div>
			  
			  <div id="my_close" class="tab-pane">
			 
              <div id="myopen_close" >
			  <?php if($D->closedcount == 0){
				  echo "No Predictions found";
			  }else{?>
                  {%myclosedhtml%}
			  <?php } ?>
			  </div>
			   <input type="hidden" id="close-count" value="0">
			   <?php if($D->closedcount == 10){ ?>
			   <div class="show-more-container">
				<a id="closeshow" class="show-more"><span>Show more</span></a>
			  </div>
			   <?php } ?>
              </div>
          </div>
          
      </div><!--my prediction tab pane-->
  
  </div> <!--top level tab content-->
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

<script type="text/javascript" src="<?php echo $C->SITE_URL;?>static/js/jquery.js"></script>
<script src="<?php echo $C->SITE_URL;?>static/js/htmlarea_predictionasset.js?v=3.6.0"></script>
<script src="<?php echo $C->SITE_URL;?>static/js/canvas.js?v=3.6.0"></script>

<script type="text/javascript">
$(document).ready(function(){
	var chartss = new CanvasJS.Chart("chartContainer",
	{
		title:{
			text: ""
		},
		legend: {
			maxWidth: 350,
			itemWidth: 120
		},
		data: [
		{
			type: "pie",
			showInLegend: true,
			legendText: "{indexLabel}",
			dataPoints:<?php echo $D->myearningsresjson;?>
		}
		]
	});
	chartss.render();
	var chart1 = new CanvasJS.Chart("chartContainer1",
	{
		title:{
			text: ""
		},
		legend: {
			maxWidth: 350,
			itemWidth: 120
		},
		data: [
		{
			type: "pie",
			showInLegend: true,
			legendText: "{indexLabel}",
			dataPoints:<?php echo $D->mypredictvaluesjson;?>

		}
		]
	});
	chart1.render();
});
</script>
<script>
$("ul.nav-tabs a").click(function (e) {
  e.preventDefault();  
    $(this).tab('show');
});

$("#predict").click(function(){
			var predictval = $("#predict_val").val();

	    if(predictval ==''){
		 
			STX.showMessage("Please select asset", "error");
					return false;
		}

                 $.ajax({
					type:"POST",
					data:{ticker:predictval},
					url:"<?php  echo $C->SITE_URL;?>predictionsgraph",
                                         async : true,
                                        success:function(msg){
						$('#graph').html('');
						$('#graph').html(msg);
						$("#graph").modal('show'); 
						
						
					}
				});                                           
                    
});

$("#predict_search").keypress(function(e){
	
	if(e.which =='13'){
		var predictval = $("#predict_val").val();
		if(predictval ==''){
		 
			STX.showMessage("Please select asset", "error");
					return false;
		}
                  e.preventDefault();

	 
		 $.ajax({
					type:"POST",
					data:{ticker:predictval},
					url:"<?php  echo $C->SITE_URL;?>predictionsgraph",
                                        async : true,

					success:function(msg){
						$('#graph').html('hi');
						$('#graph').html(msg);
						$("#graph").modal('show'); 
						
						
					}
				});
	 				
		
		
	}
});
  $(".mymodal").click(function(){
	  var postid = $(this).attr('rel');
	   var type = $(this).attr('type');
	   $.ajax({
				    async: true, 
		            cache: false,
					dataType : "html",
					type:"POST",
					data:{postid:postid,type:type},
					url:"<?php  echo $C->SITE_URL;?>predictions",

					success:function(msg){
						$('#handset-'+type+postid).html('');
						$('#handset-'+type+postid).html(msg);
						$("#handset-"+type+postid).modal('show'); 
						
						
					}
				});
  });
function myFunction(postid,type) {
	var id = postid;
	var reason   =$("#hindsight-"+type+id).val();
	if(reason ==''){
			$("#handsetreason-error").css("display","block");
			$("#handsetreason-error").delay(5000).fadeOut(400);
			return false;
		
	}
	
	$.ajax({
			
			type:"POST",
			data:{reason:reason,postids:id},
			url:"<?php  echo $C->SITE_URL;?>predictions",
			cache:false,
			success:function(msg){
				if($.trim(msg) =="YES"){
					$('.fade-'+type+'-'+postid).modal('toggle');;
					STX.showMessage("Hindsight changed successfully!!", "success");
					
				}
				}
			});	
}
function validate(txt,postid){
	 txt.value = txt.value.replace(/[^a-zA-Z 0-9\n\r]+/g, '');
	 $("#hindsight-"+postid).val(txt.value);
	
	
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
				
			STX.showMessage("Your Buzz to  has been sent!", "success");
			window.location.href="<?php echo $C->SITE_URL?>predictions";



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
			
STX.showMessage("Your Buzz to  has been sent!", "success");
			window.location.href="<?php echo $C->SITE_URL?>predictions";
			
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
			window.location.href="<?php echo $C->SITE_URL?>predictions";
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
				STX.showMessage("Your Buzz to  has been sent!", "success");
			window.location.href="<?php echo $C->SITE_URL?>predictions";

			
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
				STX.showMessage("Your Buzz to  has been sent!", "success");
			window.location.href="<?php echo $C->SITE_URL?>predictions";
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
			window.location.href="<?php echo $C->SITE_URL?>predictions";
			

			
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
			window.location.href="<?php echo $C->SITE_URL?>predictions";

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


</script>


	
