<script type="text/javascript">
	function editdisplay(id){	
    var rel = $(".editcustdata"+id).attr('rel');	
    $("#message-"+id).val(rel);	
   	
}	

     function readURLcoverimage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                     document.getElementById("demoloadDoccoverimagedel").innerHTML = '<img onClick="reply_clickcoverimage(this.src)" src="'+e.target.result+'" width="100%" height="115px" style="border-radius:25px;"/>';
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        
function reply_clickcoverimage(videothumbsrc){
            document.getElementById("againpreview").innerHTML = '<img src="'+videothumbsrc+'" id="blah" width="90%" height="200px" style="border-radius:25px;" />';
        }
</script>



<script>


var slideIndex = 1;

function plusDivstime(n,m) {
  showDivstime(slideIndex += n,m);
}

function showDivstime(n,m) {
  var i;
  var x = document.getElementsByClassName("mySlides"+m);
  if (n > x.length) {slideIndex = 1}
  if (n < 1) {slideIndex = x.length}
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  x[slideIndex-1].style.display = "block";  
}



// aaaaaaaaaa
/*var slideIndex = 1;
showDivstime(slideIndex);

function plusDivstime(n,m) {
  showDivstime(slideIndex += n,m);
}

function showDivstime(n,m) {

  var i;
  var x = document.getElementsByClassName("mySlides"+m);
  if (n > x.length) {slideIndex = 1}
  if (n < 1) {slideIndex = x.length}
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  x[slideIndex-1].style.display = "block";  
}
*/
$(document).ready(function() {
  var buttonAdd = $(".add-button");
  var buttonRemove = $(".remove-button");
  var className = ".dynamic-field";
  var count = 0;
  var field = "";
  var maxFields =50;

  function totalFields() {
    return $(className).length;
  }

  function addNewField() {
    count = totalFields() + 1;
    field = $("#dynamic-field-1").clone();
    field.attr("id", "dynamic-field-" + count);
    field.children("label").text("Field " + count);
    field.find("input").val("");
    $(className + ":last").after($(field));
  }

  function removeLastField() {
    if (totalFields() > 1) {
      $(className + ":last").remove();
    }
  }

  function enableButtonRemove() {
    if (totalFields() === 2) {
      buttonRemove.removeAttr("disabled");
      buttonRemove.addClass("shadow-sm");
    }
  }

  function disableButtonRemove() {
    if (totalFields() === 1) {
      buttonRemove.attr("disabled", "disabled");
      buttonRemove.removeClass("shadow-sm");
    }
  }

  function disableButtonAdd() {
    if (totalFields() === maxFields) {
      buttonAdd.attr("disabled", "disabled");
      buttonAdd.removeClass("shadow-sm");
    }
  }

  function enableButtonAdd() {
    if (totalFields() === (maxFields - 1)) {
      buttonAdd.removeAttr("disabled");
      buttonAdd.addClass("shadow-sm");
    }
  }

  buttonAdd.click(function() {
    addNewField();
    enableButtonRemove();
    disableButtonAdd();
  });

  buttonRemove.click(function() {
    removeLastField();
    disableButtonRemove();
    enableButtonAdd();
  });
  
  
  

  
});


/*  function on_add() {
            alert('ggggg');

   addNewField();
    enableButtonRemove();
    disableButtonAdd();
}

function on_remove() {
    alert('hhhhhh');
     removeLastField();
    disableButtonRemove();
    enableButtonAdd();
}
  
*/
</script> 	

	{%footer_js_data%}
	
	
	<script type="text/javascript" src="<? print_r($C->SITE_URL); ?>themes/FishingEnthusiastTheme/js/parallax.js"></script>
	<script src="<?php echo $C->SITE_URL;?>static/js/textareaeditor.js?v=3.6.0"></script>

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script async src="https://click.nativclick.com/loading/?handle=7343" ></script>


<?php
   
  if( preg_match( "#iPhone#i", $_SERVER['HTTP_USER_AGENT'] )  || preg_match( "#iPod#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Android#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Phone#i", $_SERVER['HTTP_USER_AGENT'] )) {
		print_r("<script type='text/javascript' src='".$C->SITE_URL."/static/js/htmlarea_asset_mobile.js'></script>");
	  	print_r("<script type='text/javascript' src='".$C->SITE_URL."/static/js/htmlarea_predictionassetmobile.js'></script>");
	  	  print_r("<script type='text/javascript' src='".$C->SITE_URL."/static/js/whatsup.js'></script>");
	   print_r("<script type='text/javascript' src='".$C->SITE_URL."/static/js/htmlarea_mobile.js'></script>");
	  print_r("<script type='text/javascript' src='".$C->SITE_URL."/static/js/common_mobile.js'></script>");



		}      
        
    	 
?>


		<div class="paralax_holder">
	 <!--  <ul id="scene" class="scene">
			<li class="layer" data-depth="1.00"><img style="width:1000px;" src="<? print_r($C->SITE_URL); ?>themes/FishingEnthusiastTheme/images/p1.png"></li>
			
			<li class="layer" data-depth="0.90"><img style="margin-left:100px;"  class="brightness" src="<? print_r($C->SITE_URL); ?>themes/FishingEnthusiastTheme/images/p2.png"></li>
			<li class="layer" data-depth="0.80"><img style="width:1300px;margin-top:50px;margin-left:500px;filter: blur(5px);-webkit-filter: blur(5px);-moz-filter: blur(5px);-o-filter: blur(5px);-ms-filter: blur(5px);" src="<? print_r($C->SITE_URL); ?>themes/FishingEnthusiastTheme/images/p1.png"></li>
			<li class="layer" data-depth="0.70"><img style="width:1000px;margin-top:20px;margin-left:-100px;filter: blur(5px);-webkit-filter: blur(5px);-moz-filter: blur(5px);-o-filter: blur(5px);-ms-filter: blur(5px);" src="<? print_r($C->SITE_URL); ?>themes/FishingEnthusiastTheme/images/p1.png"></li>		
			<li class="layer" data-depth="0.60"><img style="width:1500px;margin-top:100px;margin-left:500px;filter: blur(10px);-webkit-filter: blur(10px);-moz-filter: blur(10px);-o-filter: blur(10px);-ms-filter: blur(10px);" src="<? print_r($C->SITE_URL); ?>themes/FishingEnthusiastTheme/images/p1.png"></li>
			<li class="layer" data-depth="0.50"><img style="width:1920px;margin-top:0px;margin-left:0px;filter: blur(20px);-webkit-filter: blur(20px);-moz-filter: blur(20px);-o-filter: blur(20px);-ms-filter: blur(20px);" src="<? print_r($C->SITE_URL); ?>themes/FishingEnthusiastTheme/images/p1.png"></li>
			
		 	<li class="layer" data-depth="0.38"><img class="waves11" style="width:2500px;margin-top:600px;margin-left:-300px;filter: blur(20px);-webkit-filter: blur(20px);-moz-filter: blur(20x);-o-filter: blur(20px);-ms-filter: blur(20px);" src="<? print_r($C->SITE_URL); ?>themes/FishingEnthusiastTheme/images/p3.png"></li>
		    <li class="layer" data-depth="0.39"><img class="waves21" style="margin-top:600px;margin-left:100px;" src="<? print_r($C->SITE_URL); ?>themes/FishingEnthusiastTheme/images/p4.png"></li>

		    <li class="layer" data-depth="0.40"><img class="waves13" style="width:2800px;margin-top:620px;margin-left:-400px;filter: blur(5px);-webkit-filter: blur(5px);-moz-filter: blur(5x);-o-filter: blur(5px);-ms-filter: blur(5px);" src="<? print_r($C->SITE_URL); ?>themes/FishingEnthusiastTheme/images/p3.png"></li>
			<li class="layer" data-depth="0.41"><img class="waves41" style="width:3200px;margin-top:680px;margin-left:-300px;filter: blur(0px);-webkit-filter: blur(0px);-moz-filter: blur(0x);-o-filter: blur(0px);-ms-filter: blur(0px);" src="<? print_r($C->SITE_URL); ?>themes/FishingEnthusiastTheme/images/p3.png"></li>
			
		</ul>-->
	</div>
	<script>
	var scene = document.getElementById('scene');
	var parallax = new Parallax(scene);
	
	

	</script>
	

	<div class="clear"></div>
	{%videoid%}
			</div>
			<div id="footer-spacer"></div>
		</div>
		<div class="container">
		<div class="row">
		<div class="col-md-12">
		<div class="footer-container">			
			<div id="footer">
				<div class="left">
					{%footer_placeholder%}
				</div>
					
				<div class="right">
					<span>
						
						<!-- "Powered by Sharetronix" backlink -->
							<!--
							You are required to keep the "Powered by Sharetronix" backlink
							as per the Sharetronix License: http://developer.sharetronix.com/license
							-->
							<!--{%stx_footer_link_abc%}-->
						<!-- "Powered by Sharetronix" backlink END -->
							
					</span> 
				</div>
			</div>
		</div>
		</div>
		</div>
		</div>
		<?php
			// 
			// Please read the file ./system/cronjobs/readme.txt
			// 	
			
			/*
			if( !isset($C->CRONJOB_IS_INSTALLED) || !$C->CRONJOB_IS_INSTALLED ) {
				$lastrun	= $GLOBALS['cache']->get('cron_last_run');
				if( ! $lastrun || $lastrun < time()-60 ) {
					echo '
						<script type="text/javascript">
							var tmpreq = ajax_init(false);
							if( tmpreq ) {
								tmpreq.onreadystatechange	= function() {  };
								tmpreq.open("HEAD", siteurl+"cron/r:"+Math.round(Math.random()*1000), true);
								tmpreq.setRequestHeader("connection", "close");
								tmpreq.send("");
								setTimeout( function() { tmpreq.abort(); }, 3000 );
							}			
						</script>';
				}
			}
			
			*/
		?>
	
	
	{%comment_editor%}
	
	</div>
	<script>
	$(document).ready(function () {
    //Disable cut copy paste
   /* $('.activity-content').bind('cut copy', function (e) {
        alert("For real time local news feed,please contact aravind:8310318503 ");
        e.preventDefault();
    });
     $(".activity-content").on("contextmenu",function(e){
        return false;
    });*/
   
   
});
$(window).bind('scroll', function() {
    var idss =[];
        $('.activity ').each(function() {
            var post = $(this);
            var position = post.position().top - $(window).scrollTop();
            
            if (position <= 0) {
			var str = $(this).attr('id');
			var replstr = str.replace("main","");
			var finalid = parseInt(replstr);
			if(finalid !=''){
			    idss.push(finalid);
			    
			}

            } else {
            }
        });   
        		 var uniqueArr = $.unique(idss.sort()).sort();
        		 $("#last_activity").attr("data-cust",uniqueArr);

    });
    setInterval(function() {
    var viewsdata = $("#last_activity").attr("data-cust");
    if(viewsdata[0] !='' ){
   	$.ajax({
			
			type:"POST",
			url:"<?php  echo $C->SITE_URL;?>addviews",
			data:{id:viewsdata},
			cache:false,
			success:function(msg){
			    if(msg == 1 ){
			        var arr = [];
			        $("#last_activity").attr("data-cust",arr)
			        
			    }
				
			
			}
			
			
		});
    }
}, 30000);
	$('.totalpro').each(function(){
		if($(this).val() !=''){
			var mainheight = $("#main"+$(this).val()).height();
			var childheight = $("#child"+$(this).val()).height();
			var final = mainheight-childheight;
			$(".janeesh"+$(this).val()).css("height",final);
			 
		}else{
		}
	});
	$('.show-more').click(function(){
		
	});
	$('body').on("click",".ref",function(){
		
		$('.lessoncup').load('<?php echo $C->SITE_URL?>getadrefresh');
		
	});
	
	
	$('body').on("click",".btn-follow",function(){
		
		var element = $(this);
		var fid = element.attr("id");
		
		$(this).text('Following');
		
		var datasend = "followid="+fid;

		$.ajax({
			
			type:"POST",
			url:"<?php  echo $C->SITE_URL;?>getad",
			data:datasend,
			cache:false,
			success:function(msg){
				
				$('#follow'+fid).delay(2000).fadeOut('slow',function(){
					
					$('#follow'+fid).replaceWith(msg);
					var ifollow   =($("#ifollow").val());
					var  ifollow =parseInt(ifollow)+parseInt(1);

					$("#ifollow").val(ifollow);
					$(".follow").html(ifollow);
					
					
				});
				
			}
			
			
		});
		
	});
	$('body').on("click",".data-row-3",function(){
		
		var element = $(this);
		var id = element.attr("id");
	
		$.ajax({
			
			type:"POST",
			url:"<?php  echo $C->SITE_URL;?>getad",
			cache:false,
			success:function(msg){
				
				$('#follow'+id).delay(2000).fadeOut('slow',function(){
					
					$('#follow'+id).replaceWith(msg);
					
				});
				
			}
			
			
		});

	});
	$("#buzzevent").click(function(){
		$(".characters-counter").css('display','none');
		 $(".status-btn").hide();
		 $("#poll").css("display","block");
		 		 $("#post").hide();

		 $("#poll").load("<?php echo $C->SITE_URL;?>/<?php echo $this->user->info->username;?>/tab:events/action:add_event");
		
	});

	var running = false,
        div = document.getElementById('response'),
        limit = 0,
        current = 0;

    $('#myevents').click(function () {
		$("#my-show").hide();
        if (running === true) {
            alert('Error: The cycle was running. Aborting.');
            running = false;
            return false;
        }
        running = true;
        var end = setInterval(function () {
            if (current >= limit || running == false) {
                running = false;
                clearInterval(end);
            }
					 $("#myeven").load("<?php echo $C->SITE_URL;?>myeventss");
					 $("#list_myeventlist_myevent").css("display","block");
					$("#myevents").css("display","none");

            current++;
        }, 500);

    });
	$('#acceptevents').click(function () {
		$("#accept-show").hide();
        if (running === true) {
            alert('Error: The cycle was running. Aborting.');
            running = false;
            return false;
        }
        running = true;
        var end = setInterval(function () {
            if (current >= limit || running == false) {
                running = false;
                clearInterval(end);
            }
					 $("#accept").load("<?php echo $C->SITE_URL;?>myevents");
					 $("#list_accept").css("display","block");
							$("#acceptevents").css("display","none");

            current++;
        }, 500);

    });
	$('#aleve').click(function () {
				$("#all-show").hide();

        if (running === true) {
            alert('Error: The cycle was running. Aborting.');
            running = false;
            return false;
        }
        running = true;
        var end = setInterval(function () {
            if (current >= limit || running == false) {
                running = false;
                clearInterval(end);
            }
					 $("#allevents").load("<?php echo $C->SITE_URL;?>allevents");
					 		$("#list").css("display","block");
							$("#aleve").css("display","none");


            current++;
        }, 500);

    });
	$('#list').click(function () {
		 $("#allevents").load("<?php echo $C->SITE_URL;?>notification_list",{id:'list_all'});
		 $("#list").css("display","none");
							$("#aleve").css("display","block");
								$("#all-show").show();
		
	});
	$('#list_myeventlist_myevent').click(function () {
		 $("#myeven").load("<?php echo $C->SITE_URL;?>notification_list",{id:'list_myeven'});
		  $("#list_myeventlist_myevent").css("display","none");
					$("#myevents").css("display","block");
					$("#my-show").show();
		
	});
	$('#list_accept').click(function () {
		 $("#accept").load("<?php echo $C->SITE_URL;?>notification_list",{id:'list_accept'});
		  $("#list_accept").css("display","none");
							$("#acceptevents").css("display","block");
							$("#accept-show").hide();
		
	});
	$("#all-show").click(function(){
		   var allshowcount =$("#all-show-count").val();
		   var to = parseInt(allshowcount)+parseInt("10");
		   var type="list_all";
		              
        $.ajax({
			
			type:"POST",
			data:{allshowcount:allshowcount,type:type},
			url:"<?php  echo $C->SITE_URL;?>notificationlistshowmore",
			cache:false,
			success:function(msg){
				$("#allevents").append(msg);
				$("#all-show-count").val(to);
				
				
			}
			
			
		});	
		});
		$("#my-show").click(function(){
		   var myshowcount =$("#my-show-count").val();
		   var to = parseInt(myshowcount)+parseInt("10");
		   var type="list_myeven";
		              
        $.ajax({
			
			type:"POST",
			data:{myshowcount:myshowcount,type:type},
			url:"<?php  echo $C->SITE_URL;?>notificationlistshowmore",
			cache:false,
			success:function(msg){
				$("#myeven").append(msg);
				$("#my-show-count").val(to);
				
				
			}
			
			
		});	
		});
		$("#accept-show").click(function(){
		   var acceptshowcount =$("#accept-show-count").val();
		   var to = parseInt(acceptshowcount)+parseInt("10");
		   var type="list_accept";
		              
        $.ajax({
			
			type:"POST",
			data:{acceptshowcount:acceptshowcount,type:type},
			url:"<?php  echo $C->SITE_URL;?>notificationlistshowmore",
			cache:false,
			success:function(msg){
				$("#accept").append(msg);
				$("#accept-show-count").val(to);
				
				
			}
			
			
		});	
		});
		$("#all-poll-show").click(function(){
		   var allshowcount =$("#allpoll-show-count").val();
		   var to = parseInt(allshowcount)+parseInt("10");
		   var type="poll_all";
		              
        $.ajax({
			
			type:"POST",
			data:{allshowcount:allshowcount,type:type},
			url:"<?php  echo $C->SITE_URL;?>pollsshowmore",
			cache:false,
			success:function(msg){
				$("#allpolls").append(msg);
				$("#allpoll-show-count").val(to);
				
				
			}
			
			
		});	
		});
		$("#my-poll-show").click(function(){
		   var mypollshowcount =$("#mypoll-show-count").val();
		   var to = parseInt(mypollshowcount)+parseInt("10");
		   var type="mypoll_all";
		              
        $.ajax({
			
			type:"POST",
			data:{mypollshowcount:mypollshowcount,type:type},
			url:"<?php  echo $C->SITE_URL;?>pollsshowmore",
			cache:false,
			success:function(msg){
				$("#mypolls").append(msg);
				$("#mypoll-show-count").val(to);
				
				
			}
			
			
		});	
		});
		$("#myresponse-show").click(function(){
		   var myresponseshowcount =$("#myresponse-show-count").val();
		   var to = parseInt(myresponseshowcount)+parseInt("10");
		   var type="myresponsepoll_all";
		              
        $.ajax({
			
			type:"POST",
			data:{myresponseshowcount:myresponseshowcount,type:type},
			url:"<?php  echo $C->SITE_URL;?>pollsshowmore",
			cache:false,
			success:function(msg){
				$("#mypollresponsresponse").append(msg);
				$("#myresponse-show-count").val(to);
				
				
			}
			
			
		});	
		});
		$("#all-intra-show").click(function(){
			var allintrashowcnt =$("#allintra-show-count").val();
			var to = parseInt(allintrashowcnt)+parseInt("10");
			var type="all_intraday";
		              
        $.ajax({
			
			type:"POST",
			data:{allintrashowcnt:allintrashowcnt,type:type},
			url:"<?php  echo $C->SITE_URL;?>intrashowmore",
			cache:false,
			success:function(msg){
				$("#allintradayshow").append(msg);
				$("#allintra-show-count").val(to);
				
				
			}
			
			
		});	


			
		});
		$("#follow-intra-show").click(function(){
			var followshowcnt =$("#follow-show-count").val();
			var to = parseInt(followshowcnt)+parseInt("10");
			var type="follow_intraday";
			 $.ajax({
			
			type:"POST",
			data:{followshowcnt:followshowcnt,type:type},
			url:"<?php  echo $C->SITE_URL;?>intrashowmore",
			cache:false,
			success:function(msg){
				$("#followintra").append(msg);
				$("#follow-show-count").val(to);
				
				
			}
			
			
		});	
			
		});
		$("#my-intra-show").click(function(){
			var myintrashowcnt =$("#myintra-show-count").val();
			var to = parseInt(myintrashowcnt)+parseInt("10");
			var type="my_intraday";
			 $.ajax({
			
			type:"POST",
			data:{myintrashowcnt:myintrashowcnt,type:type},
			url:"<?php  echo $C->SITE_URL;?>intrashowmore",
			cache:false,
			success:function(msg){
				$("#my-intraday-html").append(msg);
				$("#myintra-show-count").val(to);
				
				
			}
			
			
		});	
			
		});
                 		$("#myintraday-correct-show").click(function(){
		   var myintradaycorrectshowcnt =$("#myintradaycorrectshowcnt").val();
		   var to = parseInt(myintradaycorrectshowcnt)+parseInt("10");
		   var type="correct";
		              
        $.ajax({
			
			type:"POST",
			data:{myintradaycorrectshowcnt:myintradaycorrectshowcnt,type:type},
			url:"<?php  echo $C->SITE_URL;?>intrashowmore",
			cache:false,
			success:function(msg){
				$("#myintradaycorrect").append(msg);
				$("#myintradaycorrectshowcnt").val(to);
				
				
			}
			
			
		});	
		});
		
		$("#myintraday-incorrect-show").click(function(){
		   var myintradayincorrectshowcnt =$("#myintradayincorrectshowcnt").val();
		   var to = parseInt(myintradayincorrectshowcnt)+parseInt("10");
		   var type="incorrect";
		              
        $.ajax({
			
			type:"POST",
			data:{myintradayincorrectshowcnt:myintradayincorrectshowcnt,type:type},
			url:"<?php  echo $C->SITE_URL;?>intrashowmore",
			cache:false,
			success:function(msg){
				$("#myintradayincorrect").append(msg);
				$("#myintradayincorrectshowcnt").val(to);
				
				
			}
			
			
		});	
		});
		$("#myintraday-open-show").click(function(){
		   var openshowcnt =$("#myintradayopencnt").val();
		   var to = parseInt(openshowcnt)+parseInt("10");
		   var type="open";
		              
        $.ajax({
			
			type:"POST",
			data:{openshowcnt:openshowcnt,type:type},
			url:"<?php  echo $C->SITE_URL;?>intrashowmore",
			cache:false,
			success:function(msg){
				$("#myopenresults").append(msg);
				$("#myintradayopencnt").val(to);
				
				
			}
			
			
		});	
		});
	
	/* NOTIFICATIONS */
	//Edit 
	function myfunction(eventid){
	//var username	   ="<?php echo $this->user->info->username; ?>";
	//var url = "<?php echo $C->SITE_URL;?>";
	//var asd    =url+username+'/tab:events/action:edit_event/event:'+eventid;
	 $("#content_all").load("<?php echo $C->SITE_URL;?>edit_event",{event_id:eventid});
	 

	 
		
	}
		function joingrp(groupid){
		 var title='Join Group';
		 $.ajax({
					async: true, 
		           cache: false,
					dataType : "html",
					type:"POST",
					data:{groupid:groupid,title:title},
					url:"<?php  echo $C->SITE_URL;?>sighnuppopup",

					success:function(msg){

						 $('#group').html('');
						 $('#group').html(msg);
						 $('#group').modal('show'); 
						
						
					}
				});
	 }
			function geturl(a,b)
{
	$("#op-"+b).val(a);
}
function insertpoll(a)
{
var answerid	=$("#op-"+a).val();
var pollid	=$("#poll-"+a).val();
 $.ajax({
			
			type:"POST",
			data:{answerid:answerid,pollid:pollid},
			url:"<?php  echo $C->SITE_URL;?>plugin/poll/admin",
			cache:false,
			success:function(msg){
				$("#pollallid-"+a).replaceWith(msg);
				
				
			}
			
			
		});	
}
function insertmypoll(a)
{
var answerid	=$("#op-"+a).val();
var pollid	=$("#poll-"+a).val();
 $.ajax({
			
			type:"POST",
			data:{answerid:answerid,pollid:pollid},
			url:"<?php  echo $C->SITE_URL;?>plugin/poll/admin",
			cache:false,
			success:function(msg){
				$("#pollmyid-"+a).replaceWith(msg);
				
				
			}
			
			
		});	
}
function download_poll(pollid)
{
	var url="<?php echo $C->SITE_URL;?>plugin/poll/admin?action=download&poll_id="+pollid;
	$("#download-"+pollid).attr('action',url);
	$("#download-"+pollid).submit();

}
	function myfunction1(eventid){
	 $("#content1").load("<?php echo $C->SITE_URL;?>editmy_event",{event_id:eventid});
		}
		function myfunction2(eventid){
	 $("#content").load("<?php echo $C->SITE_URL;?>editaccept_event",{event_id:eventid});
		}
	//predictions show more button
	$("#allprediction-show").click(function(){
	var count = $("#allprediction-count").val();
	var totalcnt= parseInt(count)+parseInt(10);
	var show_type="all";
	$.ajax({
			
			type:"POST",
			data:{totalcnt:totalcnt,show_type:show_type},
			url:"<?php  echo $C->SITE_URL;?>predictionsshowmore",
			cache:false,
			success:function(msg){
				$("#all_popular").append(msg);
				$("#allprediction-count").val(totalcnt);
				}
			});	
	});
	$("#follow-show").click(function(){
	var count = $("#follow-count").val();
	var totalcnt= parseInt(count)+parseInt(10);
	var show_type="follow";
	$.ajax({
			
			type:"POST",
			data:{totalcnt:totalcnt,show_type:show_type},
			url:"<?php  echo $C->SITE_URL;?>predictionsshowmore",
			cache:false,
			success:function(msg){
				$("#brands_popular").append(msg);
				$("#follow-count").val(totalcnt);
				}
			});	
	});
	$("#open-show").click(function(){
		var count = $("#open-count").val();
	    var totalcnt= parseInt(count)+parseInt(10);
	    var show_type="open";
		$.ajax({
			
			type:"POST",
			data:{totalcnt:totalcnt,show_type:show_type},
			url:"<?php  echo $C->SITE_URL;?>predictionsshowmore",
			cache:false,
			success:function(msg){
				$("#open-data").append(msg);
				$("#open-count").val(totalcnt);
				}
			});	
		
	});
	$("#closeshow").click(function(){
		var count = $("#close-count").val();
	    var totalcnt= parseInt(count)+parseInt(10);
	    var show_type="close";
		$.ajax({
			
			type:"POST",
			data:{totalcnt:totalcnt,show_type:show_type},
			url:"<?php  echo $C->SITE_URL;?>predictionsshowmore",
			cache:false,
			success:function(msg){
				$("#myopen_close").append(msg);
				$("#close-count").val(totalcnt);
				}
			});	
		
	});
	function deletesearch(deleteid){
		$.ajax({
		type:'POST',
		 data:{deleteid:deleteid},
		url:"<?php echo $C->SITE_URL.'autocomplete'?>",
		success:function(response)
		{
			if(response ==1){
				$(".deletecls-"+deleteid).fadeOut();
				
			}
	
			//STX.showMessage("Your Buzz to  has been sent!", "success");


			
			
			
		}
	});
	}

  
</script>
<style>
video::-internal-media-controls-download-button {
    display:none;
}

video::-webkit-media-controls-enclosure {
    overflow:hidden;
}

video::-webkit-media-controls-panel {
    width: calc(100% + 30px); /* Adjust as needed */
}
</style>
<script>
var videotype= $("#videotype").val();
if(videotype == 1 ){
    var videoid= $("#videoid").val();

var index = 0;
videofinalid='video'+videoid;
var video = document.getElementById(videofinalid);

video.addEventListener('canplay', function() {
   this.currentTime = 0;
}, false);
 
video.addEventListener('seeked', function() {
    getThumb();
}, false);
}
 
function nextVideo(){
    var videosrc = document.getElementById("videosrc").value;

    video.src = videosrc;
	video.load();
   
}
 
function getThumb(){
    var filename = video.src;
    var w = video.videoWidth;//video.videoWidth * scaleFactor;
    var h = video.videoHeight;//video.videoHeight * scaleFactor;
    var canvas = document.createElement('canvas');

    canvas.width = w;
    canvas.height = h;
    var ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, w, h);
 
    //document.body.appendChild(canvas);
    var data = canvas.toDataURL("image/jpg");
	if(index ==0){
    
    //send to php script
    var xmlhttp = new XMLHttpRequest;
    
    xmlhttp.onreadystatechange = function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            console.log('saved');
            //nextVideo();
        }
    }
   // var url="https://streetbuzz.co/test/thumbnail";
    //var url ="<?php  echo $C->SITE_URL;?>thumbnail",
    
     		$.ajax({
			
			type:"POST",
			url:"<?php  echo $C->SITE_URL;?>/thumbnail",
			data:{activities_id:videoid,filedata:data},
			cache:false,
			success:function(msg){
				
		
			}
			
		});
	}
		index++;

}
 
if(videotype == 1 ){
 
//let's go
nextVideo();
}
$(".adslink").click(function(){
  var id = $(this).attr('rel');
  	$.ajax({
			
			type:"POST",
			url:"<?php  echo $C->SITE_URL;?>/adslinkinsert",
			data:{adid:id},
			cache:false,
			success:function(msg){
				
		
			}
			
		});
  
});

</script>


	</body>
</html>
