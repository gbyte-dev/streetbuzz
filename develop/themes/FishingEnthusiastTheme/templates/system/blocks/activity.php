<div class="activity {%activity_nocomments%} replayhide-{%hei%}" id="main{%hei%}">	
<!-- start Parent -->	
<div class="row activity-parent">	
<div class="janeesh{%hei%}"></div>	
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 buzz-parent-box activity-inner">	
<div class="col-md-1 col-lg-1 col-sm-1 col-xs-2 image-div activity-image-div">	
{%activity_user_avatar%}	
</div>	
<div class="col-md-11 col-lg-11 col-sm-11 col-xs-10 activity-main-content">	
<!--/ start : activity container -->	
<div class="activity-container">	
<div class="activity-header col-xs-12 col-sm-12 col-md-12 col-lg-12">{%activity_user_username%}	
				 	
				<!-- START: For Desktop Screen -->	
				<div class="hidden-xs meta-info">	
				{%activity_user_activity_group%}	
					
				{%activity_top_placeholder%}	
					{%replies%}	
				</div>	
				<div class="gcse-search"></div>

				<!-- END: For Desktop Screen -->	
				<div class="activity-options">{%activity_options%}</div>	
</div>	
<!-- START: For Mobile Screen -->	
<div class="activity-header visible-xs meta-info">	
				{%activity_user_activity_group%}	
					
				{%activity_top_placeholder%}	
					{%replies%}	
</div>	

<!-- END: For Mobile Screen -->	
<div class="activity-content"><p>{%activity_permlink%}</p><pre>{%activity_text%}</pre>  <p>{%geolocation%}<p></div>	
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 activity-poll zeropadding">{%activity_poll%}</div>	

<div>{%activity_attachments%}{%replaycontainer%}</div>	
<div class="clear"></div>	
	<div class="comments{%activity_delete%} comments-thread-container" data-value="{%comments_thread_id%}">	
		{%activity_comments_container%}	
	    {%displaydefaultcommenteditor%}	
	</div> 	


<div class="footer{%activity_delete%} activity-footer meta-info"></div>	
<div class="comment-footer-mar">{%comment_footer%}</div>	
</div><!--/ end : activity container -->	
		
</div><!--/ col-md-11 -->	
</div><!--/ col-md-12 -->	
<!-- start Child post --><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 activity-child-post">{%activity_chield_text1%}</div><!--/ end Child post -->	
</div>	
<!-- end Parent -->	
</div><!--/ end activity comment -->	
<div id="agency_popup" class="modal " data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >	
        <div class="modal-dialog" >	
        <button class="close" data-dismiss="modal" style="z-index:100;color:white;background:black; padding:6px; border-radius:50%">X</button>	
        		
            <div class="modal-content" id="agency_popup_details">	
            </div>	
        </div>	
    </div>	
<input style="display:none;" name="" class="pro{%hei%} totalpro" value="{%hei%}" />		
		
<script src="<?php echo $C->SITE_URL;?>static/js/textareaeditor.js?v=3.6.0"></script>	
<style>	
.image-div {	
    z-index:400!important;	
    border: 0px solid #fff;	
}	
.janeesh{%hei%}{	
position: absolute; 	
border-left: 4px solid orange; 	
float:left; 	
margin-top: 5px!important;	
}	
@media screen and (max-width: 320px) {	
.janeesh{%hei%} {	
margin: 0px 0px 0px 22px; 	
}	
}	
@media screen and (min-width: 320px) and (max-width: 480px) {	
.janeesh{%hei%} {	
margin: 0px 0px 0px 26px; 	
}	
}	
@media screen and (min-width: 480px) and (max-width: 768px) {	
.janeesh{%hei%} {	
margin: 0px 0px 0px 27px; 	
}	
}	
@media screen and (min-width: 768px) and (max-width: 992px) {	
.janeesh{%hei%} {	
margin: 0px 0px 0px 35px; 	
}	
}	
@media screen and (min-width: 992px) {	
.janeesh{%hei%} {	
margin: 0px 0px 0px 25px; 	
}	
}	
</style>	
<script type="text/javascript">	
 	
     var checkdata ={%heii%};	
	 var checkdata1 ={%hei%};	
     if(checkdata == checkdata1){	
    var ht_child_3 = document.getElementById('main{%hei%}').offsetHeight;	
	var da = document.getElementsByClassName('pro{%hei%}')[0].value={%hei%};	
    //alert(ht_child_3);	
      var x = document.getElementById("child{%hei%}").offsetHeight;	
     var  heightheig = ht_child_3-x;	
        //document.getElementsByClassName('janeesh{%hei%}')[0].style.height = heightheig+'px';	
	 //  document.getElementsByClassName('main{%hei%}')[0].style.height = ht_child_3+'px';	
	   //document.getElementsByClassName('child{%hei%}')[0].style.height = x+'px';	
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
	 	
	 function changeurl(pollid,answerid){	
		$("#pollvote"+pollid).attr("data-rel",pollid);	
	   $("#pollvote"+pollid).attr("answerid",answerid);	
		 	
	 }	
	 function vote(maintype,pollid){	
			 var sessionuser ="<?php echo $this->user->is_logged;?>";	
$(this).hide();	
var answerid           =$("#pollvote"+pollid).attr("answerid");	
if (typeof answerid === "undefined") {	
    $("#uservoteerror"+pollid).css("display","block");	
    return false;	
}	
$.ajax({	
					async: true, 	
		           cache: false,	
					dataType : "html",	
					type:"POST",	
					data:{pollid:pollid,answerid:answerid},	
		          url:"<?php echo $C->SITE_URL.'plugin/poll/admin'?>",	
					success:function(msg){	
						if(sessionuser !=''){	
						$("#pollvote"+pollid).hide();	
						if(maintype =='0'){	
							$("#replace"+pollid).replaceWith(msg);	
						}else{	
						$("#replace1"+pollid).replaceWith(msg);	
						$("#replace"+pollid).replaceWith(msg);	
						}	
						}else{	
						  var actpollid =$("#actpollid").val();	
						  var answerid           =$("#pollvote"+pollid).attr("answerid");	
						  var title='VOTE';	
	   	$.ajax({	
					async: true, 	
		           cache: false,	
					dataType : "html",	
					type:"POST",	
					data:{postid:actpollid,pollid:pollid,answerid:answerid,title:title},	
					url:"<?php  echo $C->SITE_URL;?>sighnuppopup",	
					success:function(msg){	
						 $('#replaypopup-'+actpollid).html('');	
						 $('#replaypopup-'+actpollid).html(msg);	
						 $('#replaypopup-'+actpollid).modal('show'); 	
							
							
					}	
				});	
			            //var url =siteurl+'home?view='+actpollid;	
		                 //window.location.href = url;	
						}	
							
							
							
							
					}	
				});	
		
}	
function comment(p,a)	
{	
	var token = ($(".replay-editor").attr('data-token'));	
	var postid=a;	
	var message=$("#message-"+a).val();	
		var pageurlpg = $("#replypageurlpage").val();	
	$.ajax({	
		type:'POST',	
		data:{postid:postid,message:message,chieldid:a,alterparentid:p,token:token,pageurlpg:pageurlpg},	
		url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=comment'?>",	
		success:function(response)	
		{	
			if(p == a){	
			STX.showMessage("Your Buzz to  has been sent!", "success");	
				
			$("#message-"+a).val('');	
			var user = $("#user-"+a).val();	
			$("#message-"+a).val(user);	
				
			$('#replaypopup-'+p).modal('toggle');;	
			$(".replayhide-"+p).hide();	
			$("body").animate({ scrollTop: 0 }, "slow");	
			$(".activity-feed-list").prepend(response);	
			} else{	
					
			STX.showMessage("Your Buzz to  has been sent!", "success");	
			$("#message-"+a).val('');	
			var user = $("#user-"+a).val();	
			$("#message-"+a).val(user);	
			$("#replydis-"+a).html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" style="border:0px solid grey;"><a style="cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('+p+','+a+')">View Replies</a></div>');	
				
			$('#replaypopup-'+p).modal('toggle');;	
			//$(".replayhide-"+p).hide();	
			$("body").animate({ scrollTop: 0 }, "slow");	
			//$(".replay-attach").prepend(response);	
			$(".activity-feed-list").prepend(response);	
		
			}	
				
				
		}	
	});	
}	
function changeopenion(userid,pollid,postid,maintype){	
		
	$.ajax({	
					async: true, 	
		           cache: false,	
					dataType : "html",	
					type:"POST",	
					data:{vote_pollid:pollid,vote_post_id:postid,vote_userid:userid,maintype:maintype},	
		          url:"<?php echo $C->SITE_URL.'plugin/poll/admin'?>",	
					success:function(msg){	
						$(".changevote"+maintype+pollid).hide();	
						$(".download"+maintype+pollid).hide()	
						if(maintype ==0){	
							$("#changevote"+maintype+pollid).html('<a onclick="backresponse('+userid+','+pollid+','+postid+','+maintype+')"><span class="glyphicon glyphicon-edit"></span> Back Response</a>');	
						}else{	
							$("#changevote"+maintype+pollid).html('<a onclick="backresponse('+userid+','+pollid+','+postid+','+maintype+')"><span class="glyphicon glyphicon-edit"></span> Back Response</a>');	
						}	
						if(maintype ==0){	
						$("#replace"+pollid).replaceWith(msg);	
						}else{	
						$("#replace1"+pollid).replaceWith(msg);	
						}	
							
							
							
							
					}	
				});	
}	
function voteopention(maintype,userid,postid,pollid,replaymaintype){	
	var answerid           =$("#pollvote"+pollid).attr("answerid");	
	$.ajax({	
					async: true, 	
		           cache: false,	
					dataType : "html",	
					type:"POST",	
					data:{vote_update_pollid:pollid,vote_update_answerid:answerid,maintype:replaymaintype},	
		            url:"<?php echo $C->SITE_URL.'plugin/poll/admin'?>",	
					success:function(msg){	
						$("#pollvote"+pollid).hide();	
						if(replaymaintype ==0){	
						 	$("#changevote"+replaymaintype+pollid).html('<a onclick="changeopenion('+userid+','+pollid+','+postid+','+replaymaintype+')"><span class="glyphicon glyphicon-edit"></span> Change Vote</a>');	
						}else{	
							 $("#changevote"+replaymaintype+pollid).html('<a onclick="changeopenion('+userid+','+pollid+','+postid+','+replaymaintype+')"><span class="glyphicon glyphicon-edit"></span> Change Vote</a>');	
						}	
                        if(replaymaintype ==0){	
							$("#replace"+pollid).replaceWith(msg);	
								
						}else{	
							$("#replace1"+pollid).replaceWith(msg);	
						}	
							
							
							
							
							
					}	
				});	
		
}	
function backresponse(userid,pollid,postid,maintype){	
	var answerid           =0;	
	$.ajax({	
					async: true, 	
		           cache: false,	
					dataType : "html",	
					type:"POST",	
					data:{vote_update_pollid:pollid,vote_update_answerid:answerid,maintype:maintype},	
		            url:"<?php echo $C->SITE_URL.'plugin/poll/admin'?>",	
					success:function(msg){	
						$("#pollvote"+pollid).hide();	
						$(".changevote"+maintype+pollid).hide();	
						if(maintype ==0){	
							$("#changevote"+maintype+pollid).html('<a onclick="changeopenion('+userid+','+pollid+','+postid+','+maintype+')"><span class="glyphicon glyphicon-edit"></span> Change Vote</a>');	
						}else{	
				           $("#changevote"+maintype+pollid).html('<a onclick="changeopenion('+userid+','+pollid+','+postid+','+maintype+')"><span class="glyphicon glyphicon-edit"></span> Change Vote</a>');	
						}	
                        if(maintype ==0){	
							$("#replace"+pollid).replaceWith(msg);	
						}else{	
							$("#replace"+maintype+pollid).replaceWith(msg);	
						}	
							
							
							
							
							
					}	
				});	
		
}	
function commentalllevvels(p,a,level)	
{	
	var postid=a;	
	var message=$("#message-"+a).val();	
  token =$(".replay-editor"+a).attr("data-token");	
	$.ajax({	
		type:'POST',	
		data:{postid:postid,message:message,chieldid:a,alterparentid:p,token:token},	
		url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=comment'?>",	
		success:function(response)	
		{	
				
			STX.showMessage("Your Buzz to  has been sent!", "success");	
            $("#message-"+a).val('');	
			var user = $("#user-"+a).val();	
			$("#message-"+a).val(user);	
				
			$('#replaypopup-'+level).modal('toggle');;	
						$(".replayhide-"+level).hide();	
			//$(".replayhide-"+p).hide();	
			$("body").animate({ scrollTop: 0 }, "slow");	
			//$(".replay-attach").prepend(response);	
			$(".activity-feed-list").prepend(response);	
         }	
	});	
}	
function commentintradayalllevels(p,a,level)	
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
				
			STX.showMessage("Your Buzz to  has been sent!", "success");	
            $("#message-"+a).val('');	
			var user = $("#user-"+a).val();	
			$("#message-"+a).val(user);	
				
			$('#replaypopup-'+level).modal('toggle');;	
			$(".replayhide-"+level).hide();	
			//$(".replayhide-"+p).hide();	
			$("body").animate({ scrollTop: 0 }, "slow");	
			//$(".replay-attach").prepend(response);	
			$(".activity-feed-list").prepend(response);	
         }	
	});	
}	
function commentpollalllevels(p,a,level)	
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
				
			STX.showMessage("Your Buzz to  has been sent!", "success");	
            $("#message-"+a).val('');	
			var user = $("#user-"+a).val();	
			$("#message-"+a).val(user);	
				
			$('#replaypopup-'+level).modal('toggle');;	
			$(".replayhide-"+level).hide();	
			//$(".replayhide-"+p).hide();	
			$("body").animate({ scrollTop: 0 }, "slow");	
			//$(".replay-attach").prepend(response);	
			$(".activity-feed-list").prepend(response);	
         }	
	});	
}	
function commenteventalllevels(p,a,level)	
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
				
			STX.showMessage("Your Buzz to  has been sent!", "success");	
            $("#message-"+a).val('');	
			var user = $("#user-"+a).val();	
			$("#message-"+a).val(user);	
				
			$('#replaypopup-'+level).modal('toggle');;	
			$(".replayhide-"+level).hide();	
			//$(".replayhide-"+p).hide();	
			$("body").animate({ scrollTop: 0 }, "slow");	
			//$(".replay-attach").prepend(response);	
			$(".activity-feed-list").prepend(response);	
         }	
	});	
}	
function commenttimelines(p,a)	
{	
	var token = ($(".replay-editor").attr('data-token'));	
	var postid=a;	
	var message=$("#message-"+a).val();	
	var pageurlpg = $("#replypageurlpage").val();	
	$.ajax({	
		type:'POST',	
		data:{postid:postid,message:message,chieldid:a,alterparentid:p,token:token,pageurlpg:pageurlpg},	
		url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=comment'?>",	
		success:function(response)	
		{	
		
			STX.showMessage("Your BUzz to  has been sent!", "success");	
			$("#message-"+a).val('');	
			var user = $("#user-"+a).val();	
			$("#message-"+a).val(user);	
			//$(".replayhide-"+a).hide();	
				
			$('#replaypopup-'+a).modal('toggle');;	
			$("body").animate({ scrollTop: 0 }, "slow");	
				
			//$(".replayhide-"+p).hide();	
			//$(".replay-attach").prepend(response);	
			$(".activity-feed-list").prepend(response);	
				
				
		}	
	});	
}	
function commenteventtimelines(p,a)	
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
		
			STX.showMessage("Your Buzz to  has been sent!", "success");	
			$("#message-"+a).val('');	
			var user = $("#user-"+a).val();	
			$("#message-"+a).val(user);	
			//$(".replayhide-"+a).hide();	
				
			$('#replaypopup-'+a).modal('toggle');;	
			$("body").animate({ scrollTop: 0 }, "slow");	
				
			//$(".replayhide-"+p).hide();	
			//$(".replay-attach").prepend(response);	
						$(".activity-feed-list").prepend(response);	
				
				
		}	
	});	
}	
function commentpolltimelines(p,a)	
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
		
			STX.showMessage("Your Buzz to  has been sent!", "success");	
			$("#message-"+a).val('');	
			var user = $("#user-"+a).val();	
			$("#message-"+a).val(user);	
			//$(".replayhide-"+a).hide();	
				
			$('#replaypopup-'+a).modal('toggle');;	
			$("body").animate({ scrollTop: 0 }, "slow");	
				
			//$(".replayhide-"+p).hide();	
			//$(".replay-attach").prepend(response);	
			$(".activity-feed-list").prepend(response);	
				
				
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
				
				
			$("#message-"+a).val('');	
			var user = $("#user-"+a).val();	
			$("#message-"+a).val(user);	
				
			$('#replaypopup-'+p).modal('toggle');;	
			$(".replayhide-"+p).hide();	
			$(".activity-feed-list").prepend(response);	
			//$(".replay-attach").prepend(response);	
			}else{	
			$("#replydis-"+a).html(response);	
			$("#message-"+a).val('');	
			var user = $("#user-"+a).val();	
			$("#message-"+a).val(user);	
			//$(".replayhide-"+p).hide();	
			$("#replydis-"+a).html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" style="border:0px solid grey;"><a style="cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('+p+','+a+')">View Replies</a></div>');	
				
			$('#replaypopup-'+p).modal('toggle');;	
			//$(".replay-attach").prepend(response);	
			$(".activity-feed-list").prepend(response);	
					
			}	
				
				
		}	
	});	
}	
// change1	
function editpoll(p,a,evnttype){
    
    
    
    
    		
					   var file_data = $('#file').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);
		
		
	
		 $.ajax({
      		url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=polledit&p_id='?>"+a,
        dataType: 'text',  // <-- what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(php_script_response){
            
           //      window.location.replace(window.location.href);

            //alert(php_script_response); // <-- display response from the PHP script, if any
        }
     });
		
    
    
    
    
    
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
	$(".answerspoll-"+a).each(function(){	
		     $(this).val('');	
				
			
	});	
	var group = $("#pollgrouptxt-"+a).val();	
	var users = $("#pollusertxt-"+a).val();	
	   setTimeout(function () {
                 	$.ajax({	
					type:"POST",	
					data:{postid:a,chieldid:a,alterparentid:p,question:question,answers:answe,group:group,users:users},	
					url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=polledit'?>",	
                     success:function(response){	
						STX.showMessage("Your Poll has been Edited !", "success");	
						$('#replaypopup-'+p).modal('toggle');	
						// $(".replayhide-"+p).hide();	
						$('.replayhide-'+p).find('.poll-list-orange-bg').hide();	
						// $('.replayhide-'+p).find('.activity-content').hide();	
						$('.replayhide-'+p).find('.activity-content').html(response);	
						// #$('.poll-list-orange-bg').html(response);	
					}	
				});	
				    
                 }, 500);
	

				
				
				$('.poll_'+p).css({"display":"none"});


		
		
		
}	
function edittext(p,a){	
	// console.log("Hey there!");	
	//var mesg = $('#message-'+p).val();	
	// alert(mesg);	
	
	
	   
	// console.log("Hey there!");	content
	var mesg = $('#message-'+p).val();
    var textariatitle = $('#textariatitle-'+p).val();
	var mesg = mesg ;
	var posttile = textariatitle ;
	
	
	$.ajax({	
					type:"POST",	
					data:{postid:a,message:mesg,posttile:posttile},	
					url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=textedit'?>",	
                     success:function(response){	
						STX.showMessage("Your Buzz has been Edited !", "success");	
						$('#replaypopup-'+p).modal('toggle');	
						// $(".replayhide-"+p).hide();	
						$('.replayhide-'+p).find('.activity-content').html(response);	
						// $('.poll-list-orange-bg').html(response);	
					}	
				});	
		
		
		
}	
// change2	
function editevent(p,a,evnttype)	
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
		url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=eventedit'?>",	
		success:function(response)	
		{	
			// alert(response);	
			// $('.replayhide-'+p).find("#title-"+a).html(response);	
				
			STX.showMessage("Your Event has been Edited !", "success");	
			$('#replaypopup-'+p).modal('toggle');	
			// $(".replayhide-"+p).hide();	
			$('.replayhide-'+p).find('.css-event-url').html((response));	
			// $('.replayhide-'+p).find('.activity-content').html((response));	
			// $('.event-list-blue-bg').html(response);	
		}	
	});	
}	
function childpopup(postid,childid){	
		var time = $("#time-"+childid).val();	
		var sessionuser ="<?php echo $this->user->is_logged;?>";	
		var title='Sign up';	
		if(sessionuser == ''){	
				
				
			$.ajax({	
					async: true, 	
		           cache: false,	
					dataType : "html",	
					type:"POST",	
					data:{postid:postid,title:title},	
					url:"<?php  echo $C->SITE_URL;?>sighnuppopup",	
					success:function(msg){	
						 $('#replaypopup-'+postid).html('');	
						 $('#replaypopup-'+postid).html(msg);	
						 $('#replaypopup-'+postid).modal('show'); 	
							
							
					}	
				});	
				return false;	
		}	
		$.ajax({	
					async: true, 	
		           cache: false,	
					dataType : "html",	
					type:"POST",	
					data:{postid:postid,childid:childid,time:time},	
					url:"<?php  echo $C->SITE_URL;?>childpopup",	
					success:function(msg){	
						 $('#replaypopup-'+postid).html('');	
						 $('#replaypopup-'+postid).html(msg);	
						 $('#replaypopup-'+postid).modal('show'); 	
						 		
							
							
					}	
				});	
		
}	
function childpopuptimeline(postid,childid){	
		var time = $("#time-"+childid).val();	
		var sessionuser ="<?php echo $this->user->is_logged;?>";	
		var title ="Sign up";	
		if(sessionuser ==''){	
			$.ajax({	
					async: true, 	
		           cache: false,	
					dataType : "html",	
					type:"POST",	
					data:{postid:postid,title:title},	
					url:"<?php  echo $C->SITE_URL;?>sighnuppopup",	
					success:function(msg){	
						 $('#replaypopup-'+postid).html('');	
						 $('#replaypopup-'+postid).html(msg);	
						 $('#replaypopup-'+postid).modal('show'); 	
							
							
					}	
				});	
					
				return false;	
		}	
		$.ajax({	
					async: true, 	
		           cache: false,	
					dataType : "html",	
					type:"POST",	
					data:{postid:postid,childid:childid,time:time},	
					url:"<?php  echo $C->SITE_URL;?>childpopuptimeline",	
					success:function(msg){	
						 $('#replaypopup-'+childid).html('');	
						 $('#replaypopup-'+childid).html(msg);	
						 $('#replaypopup-'+childid).modal('show'); 	
							
							
					}	
				});	
		
}	
function replaycontent(postid,childid){	
	var view_type="individual";	
	var sessionuser ="<?php echo $this->user->is_logged;?>";	
	var title ='Sign up';	
	if(sessionuser ==''){	
		$.ajax({	
					async: true, 	
		           cache: false,	
					dataType : "html",	
					type:"POST",	
					data:{postid:childid,title:title},	
					url:"<?php  echo $C->SITE_URL;?>sighnuppopup",	
					success:function(msg){	
						 $('#replaypopup-'+childid).html('');	
						 $('#replaypopup-'+childid).html(msg);	
						 $('#replaypopup-'+childid).modal('show'); 	
							
							
					}	
				});	
				return false;	
	}	
		$.ajax({	
					async: true, 	
		           cache: false,	
					dataType : "html",	
					type:"POST",	
					data:{childid:childid,postid:postid,view_type:view_type},	
					url:"<?php  echo $C->SITE_URL;?>allreplies",	
					success:function(msg){	
						 $('#replaypopup-'+childid).html('');	
						 $('#replaypopup-'+childid).html(msg);	
						 $('#replaypopup-'+childid).modal('show'); 	
							
							
					}	
				});	
		
		
}	
function replaycontenttimeline(postid,childid){	
		
	var view_type="view";	
		$.ajax({	
					async: true, 	
		           cache: false,	
					dataType : "html",	
					type:"POST",	
					data:{childid:childid,postid:postid,view_type:view_type},	
					url:"<?php  echo $C->SITE_URL;?>allreplies",	
					success:function(msg){	
						 $('#replaypopup-'+postid).html('');	
						 $('#replaypopup-'+postid).html(msg);	
						 $('#replaypopup-'+postid).modal('show'); 	
							
							
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
	$(".answerspoll-"+a).each(function(){	
		     $(this).val('');	
				
			
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
				
				
			$("#message-"+a).val('');	
			var user = $("#user-"+a).val();	
			$("#message-"+a).val(user);	
				
			$('#replaypopup-'+p).modal('toggle');;	
			$(".replayhide-"+p).hide();	
			//$(".replay-attach").prepend(msg);	
			$(".activity-feed-list").prepend(msg);	
			}else{	
			//$("#replydis-"+a).html(response);	
			$("#message-"+a).val('');	
			var user = $("#user-"+a).val();	
			$("#message-"+a).val(user);	
			$("#replydis-"+a).html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" style="border:0px solid grey;"><a style="cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('+p+','+a+')">View Replies</a></div>');	
				
			$('#replaypopup-'+p).modal('toggle');;	
			$(".activity-feed-list").prepend(msg);	
			//$(".replay-attach").prepend(msg);	
					
			}	
					}	
				});	
		
		
		
}	
		
function parentreplay(postid){	
	var time = $("#time-"+postid).val();	
		var sessionuser ="<?php echo $this->user->is_logged;?>";	
		if(sessionuser ==''){	
		var title='Sighn up';	
	   	$.ajax({	
					async: true, 	
		           cache: false,	
					dataType : "html",	
					type:"POST",	
					data:{postid:postid,title:title},	
					url:"<?php  echo $C->SITE_URL;?>sighnuppopup",	
					success:function(msg){	
						 $('#replaypopup-'+postid).html('');	
						 $('#replaypopup-'+postid).html(msg);	
						 $('#replaypopup-'+postid).modal('show'); 	
							
							
					}	
				});	
				return false;	
			
			
	}	
	   	$.ajax({	
					async: true, 	
		           cache: false,	
					dataType : "html",	
					type:"POST",	
					data:{postid:postid,time:time},	
					url:"<?php  echo $C->SITE_URL;?>replaypopup",	
					success:function(msg){	
						 $('#replaypopup-'+postid).html('');	
						 $('#replaypopup-'+postid).html(msg);	
						 $('#replaypopup-'+postid).modal('show'); 	
							
							
					}	
				});	
		
}	
function editpopupnew(postid){	
	var time = $("#time-"+postid).val();	
	   $.ajax({	
				async: true, 	
			   cache: false,	
				dataType : "html",	
				type:"POST",	
				data:{postid:postid,time:time},	
				url:"<?php  echo $C->SITE_URL;?>editpopup",	
				success:function(msg){	
					 $('#replaypopup-'+postid).html('');	
					 $('#replaypopup-'+postid).html(msg);	
					 $('#replaypopup-'+postid).modal('show'); 	
						
						
				}	
			});	
}	
function parentreplayforparent(postid){	
	var time = $("#time-"+postid).val();	
	   	$.ajax({	
					async: true, 	
		           cache: false,	
					dataType : "html",	
					type:"POST",	
					data:{postid:postid,time:time},	
					url:"<?php  echo $C->SITE_URL;?>replaypopup",	
					success:function(msg){	
						 $('#parentreplaypopup-'+postid).html('');	
						 $('#parentreplaypopup-'+postid).html(msg);	
						 $('#parentreplaypopup-'+postid).modal('show'); 	
							
							
					}	
				});	
		
}	
function showall(id)	
{	
		
    $('#agency_popup_details').html('');	
    $('#agency_popup_details').load("<?php echo $C->SITE_URL.'plugin/poll/admin?action=showall&parentid='?>"+id+"");	
    $('#agency_popup').modal('show');	
}	
function changehandset(postid){	
		var reason   =$("#hindsight-"+postid).val();	
		if(reason ==''){	
			$("#handsetreason-error-"+postid).css("display","block");	
			$("#handsetreason-error-"+postid).delay(5000).fadeOut(400);	
			return false;	
			
	}	
	$.ajax({	
				
			type:"POST",	
			data:{reason:reason,postids:postid},	
			url:"<?php  echo $C->SITE_URL;?>predictions",	
			cache:false,	
			success:function(msg){	
				if($.trim(msg) =="YES"){	
					$('.fade-'+postid).modal('toggle');;	
					STX.showMessage("Hindsight changed successfully!!", "success");	
						
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
				
				
			$("#message-"+a).val('');	
			var user = $("#user-"+a).val();	
			$("#message-"+a).val(user);	
				
			$('#replaypopup-'+p).modal('toggle');;	
			$(".replayhide-"+p).hide();	
			$(".activity-feed-list").prepend(msg);	
			//$(".replay-attach").prepend(msg);	
			}else{	
			$("#message-"+a).val('');	
			var user = $("#user-"+a).val();	
			$("#message-"+a).val(user);	
		    $("#replydis-"+a).html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box-footer" style="border:0px solid grey;"><a style="cursor:pointer;" class="pull-right" onclick="replaycontenttimeline('+p+','+a+')">View Replies</a></div>');	
				
			$('#replaypopup-'+p).modal('toggle');;	
			$(".activity-feed-list").prepend(response);	
			//$(".replay-attach").prepend(msg);	
					
			}	
					}	
				});	
		
		
		
}	
function commentintradaytimelines(p,a)	
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
		
			STX.showMessage("Your Buzz to  has been sent!", "success");	
			$("#message-"+a).val('');	
			var user = $("#user-"+a).val();	
			$("#message-"+a).val(user);	
			//$(".replayhide-"+a).hide();	
				
             $('#replaypopup-'+a).modal('toggle');;	
			$("body").animate({ scrollTop: 0 }, "slow");	
				
			//$(".replayhide-"+p).hide();	
			//$(".replay-attach").prepend(response);	
						$(".activity-feed-list").prepend(response);	
				
				
		}	
	});	
}	
function myimageload(fileid){	
	var token =($(".comments-editor").attr("data-token"))+(fileid);	
var urlphp =  siteurl + 'services/commentattachments/setfile' + '/token:' + token +'/container:status';	
    var file_data = $('#sortpicture'+fileid).prop('files')[0];   	
    var form_data = new FormData();                  	
    form_data.append('userfile', file_data);	
		
    $.ajax({	
                url: urlphp, // point to server-side PHP script 	
                dataType: 'text',  // what to expect back from the PHP script, if anything	
                cache: false,	
                contentType: false,	
                processData: false,	
                data: form_data,                         	
                type: 'post',	
                success: function(php_script_response){	
			$(".comment.attachments.uploads").css("display","block");	
             var obj = jQuery.parseJSON(php_script_response);	
			if(obj.data.att_type =="image"){	
						newt = Math.floor(Math.random() * 6) + 1 ;	
			    $("#imgdis"+fileid).append('<span class="image-thumb container close-'+newt+'"><img width="100" src="'+obj.data.url+'" alt="'+obj.data.file_name+'" title="'+obj.data.file_name+'"><a class="delete" onclick="deleteatta('+newt+')" ></a></span>');	
					
			}	
			if(obj.data.att_type =="file"){	
						newt = Math.floor(Math.random() * 6) + 1 ;	
				$("#linksdis"+fileid).append('<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 close-'+newt+' " style="position: relative;"><a href="'+obj.data.url+'" title="query.php" class="icon file file" target="_blank">'+obj.data.file_name+'</a><a class="delete" onclick="deleteatta('+newt+')"></a></div>');	
			}	
                	
				   // display response from the PHP script, if any	
                }	
     });	
	}	
function deleteatta(tokenid){	
	$(".close-"+tokenid).remove();	
}	
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
function sighn(postid){	
		var title='Sign up';	
	   	$.ajax({	
					async: true, 	
		           cache: false,	
					dataType : "html",	
					type:"POST",	
					data:{postid:postid,title:title},	
					url:"<?php  echo $C->SITE_URL;?>sighnuppopup",	
					success:function(msg){	
						 $('#replaypopup-'+postid).html('');	
						 $('#replaypopup-'+postid).html(msg);	
						 $('#replaypopup-'+postid).modal('show'); 	
							
							
					}	
				});	
			
	}	
/*	function editdisplay(id){	
    var rel = $(".editcustdata"+id).attr('rel');	
    $("#message-"+id).val(rel);	
   	
}	*/
function editcustomdata(a,b){	
    	var token = ($(".replay-editor").attr('data-token'));	
	   var postid=a;	
	   var message=$("#message-"+a).val();	
		var pageurlpg = $("#replypageurlpage").val();	
	$.ajax({	
		type:'POST',	
		data:{postid:postid,message:message,token:token,pageurlpg:pageurlpg},	
		url:"<?php echo $C->SITE_URL.'plugin/poll/admin?action=editpostcomment'?>",	
		success:function(response)	
		{	
				
			STX.showMessage("Your Buzz to  has been updated !", "success");	
            $("#message-"+a).val('');	
			var user = $("#user-"+a).val();	
			$("#message-"+a).val(user);	
				
           if(a == 0){	
			$('#replaypopup-'+a).modal('toggle');;	
           }else{	
               	$('#replaypopup-'+b).modal('toggle');;	
               	
           }	
			$("body").animate({ scrollTop: 0 }, "slow");	
			
				
				
		}	
	});	
    	
}	
</script>	
<style>	
.icons {	
  filter: gray; /* IE6-9 */	
  filter: grayscale(1); /* Firefox 35+ */	
  -webkit-filter: grayscale(1); /* Google Chrome, Safari 6+ & Opera 15+ */	
}	
/* Disable grayscale on hover */	
.icons:hover {	
    box-shadow: 10px 10px 100px black;	
    /*border:2px solid white;  */	
  filter: none;	
  -webkit-filter: grayscale(0);	
}	
.glyphicon-comment{	
  display:none;  	
}	
.comments-editor-field a {	
    text-align: left;	
}	
.comment-btns{	
    margin-top:5px;	
}	
.comments-thread{	
    padding-left:1px !important;	
    	
}
 .adimagehover{
 position: absolute !important;
  top: 50%!important;
  left: 50% !important;
  transform: translate(-50%, -50%) !important;
  -ms-transform: translate(-50%, -50%) !important;
  background-color: #555;
  color: white;
  font-size: 16px;
  padding: 12px 24px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  text-align: center;
 }
 .activity-container .activity-content img {
    width: 100% !important;
}
</style>