<div class="header"><h1 class="title-pages">Edit Event</h1></div>

<form id="event_form" action="" method="POST" enctype="multipart/form-data">
<!-- start form -->
	<div class="row">
	<div class="col-md-2 hidden-xs hidden-sm">
	<!-- for center alignment and spacing -->
	</div>
    <div class="col-md-8">
    <div class="col-md-12">

    <div class="col-md-11" style="padding:0">
    <span class="add-more">Title</span> <br />
    <input type="text" class="form-control" required id="title" name="title" value="<?php echo $event->event_name; ?>" placeholder="Title"  data-validate="required">
    	<input type="hidden" id="display_type" name="display_type" value="<?php echo $display_type; ?>">

    </div>

    <div class="col-md-11" style="padding:0">
    <ul class="list-inline">
    <li><span class="add-more">Start Date</span> <br /><input type="text"class="form-date" id="start_date" required name="start_date" value="<?php echo $start_date; ?>"  placeholder="Start Date"  data-validate="required"></li>
    <li><input type="text"class="form-date" id="start_time" name="start_time" value="<?php echo $start_time; ?>" placeholder="Start Time"></li>
    <li><span class="add-more">End Date</span> <input type="text"class="form-date" required id="end_date" name="end_date"  value="<?php echo $end_date; ?>"  data-validate="required"></li>
    <li><input type="text"class="form-date" id="end_time" required  name="end_time" value="<?php echo $end_time; ?>" ></li>
    </ul>
    </div>

    <div class="col-md-11" style="padding:0">
    <span class="add-more">Address</span><input type="text" required class="form-control" id="address" name="address" value="<?php echo $event->address; ?>"  placeholder="Enter a location">
    </div>
   	<?php  if($event->url !=''){?>

    <div class="col-md-11" style="padding:0">
    <span class="add-more">Url</span><input type="text" class="form-control" id="url" name="url" value="<?php echo $event->url; ?>"  placeholder="Enter a url">
    </div>
	<?php }else{?>
	 
    <div class="col-md-12" style="padding:0"><a href="#" class="add-more" id="addurl">+ Add url (optional)</a></div><br />
    
    <div class="col-md-12" id="urlopt" style="padding:0;display:none;">
    <div class="col-md-11" style="padding:0">
    <input type="text" class="form-control" id="url"  name="url" placeholder="URL" value="<?php echo $event->url; ?>" maxlength="50" autocomplete="off" placeholder="Enter a url"> 
    </div>
    <div class="col-md-1">
    <a href="#" id="closeurl"><span class="glyphicon glyphicon-remove"></span></a>
    </div>
    </div>
	<?php } ?>
	
	<?php if($event->event_description !=''){?>
	

    <div class="col-md-11" style="padding:0">
    <span class="add-more">Description</span><textarea class="form-control event-desc" id="description" name="description"  placeholder="Enter Description"><?php echo $event->event_description; ?></textarea>
    </div>
	<?php }else{ ?>
	
    <div class="col-md-12" style="padding:0"><a href="#" class="add-more" id="adddescription">+ Add description (optional)</a></div><br />

    <div class="col-md-12" id="descopt" style="padding:0;display:none">
    <div class="col-md-11" style="padding:0">
    <textarea  class="form-control event-desc" id="description" name="description" placeholder="Description"></textarea>
    <input type="hidden" id="is_private" name="is_private" value="0">
    </div>
    <div class="col-md-1">
    <a href="#" id="closedesc"><span class="glyphicon glyphicon-remove"></span></a>
    </div>
    </div>

		
	<?php } ?>
	 	<?php if($event->tag_name !=''){?>


    <div class="col-md-11" style="padding:0">
    <span class="add-more">Hashtag</span><input class="form-control" data-validate="required"  type="text" id="hastagedit" name="hastag" placeholder="Hashtag" value="<?php echo $event->tag_name; ?>" >
    </div>
	    <!-- dropdown --><div class="col-md-12 hashtag-dropdown" id="htmlarea-ac-container"></div><!--/ end dropdown -->

		<?php }else{ ?>
		<div class="col-md-12" style="padding:0"><a href="#" class="add-more" id="addhashtag">+ Add hashtag (optional)</a></div><br />

    <div class="col-md-12" id="hashtagopt" style="padding:0;display:none;" >
    <div class="col-md-11" style="padding:0; position:relative">
    <input type="text" class="form-control" autocomplete="off" id="hastagedit" name="hastag" placeholder="hashtag" >
    <!-- dropdown --><div class="col-md-12 hashtag-dropdown" id="htmlarea-ac-container"></div><!--/ end dropdown -->
    </div>

    <div class="col-md-1">
    <a href="#" id="closehashtag"><span class="glyphicon glyphicon-remove"></span></a>
    </div>
    </div>

			
	<?php 	} ?>

    <div class="col-md-12" id="grouptextarea" style="padding:0;">
    <div class="col-md-11" style="padding:0">
    <div id="grtxt" style="padding:0"><input type="text" class="htmlarea textarea group form-control"  autocomplete="off" id="grouptxtedit" placeholder="Group" value="<?php echo $event->street_group; ?>" name="street_group" /></div>
    		<div class="col-md-12 grptype-dropdown grptype-dropdown" id="grptype-dropdown"></div>

	</div>

    <div class="col-md-1">
    <a href="#" id="closegrp"><span class="glyphicon glyphicon-remove" ></span></a>
    </div>
    </div>
	
	  <div class="col-md-12" id="usertextarea" style="padding:0;">
    <div class="col-md-11" style="padding:0">
	 <div id="urtxt" style="padding:0"><input type="text" class="group form-control" id="usertxtedit" autocomplete="off" value="<?php echo $event->street_user ; ?>" placeholder="Users" name="street_user"  /></div>
           <!-- dropdown --><div class="col-md-12 usertype-dropdown" id="usertype-dropdown"></div><!--/ end dropdown -->

   </div>
	
    <div class="col-md-1">
    <a href="#" id="closeuser"><span class="glyphicon glyphicon-remove" ></span></a>
    </div>
    </div>
	 <div class="col-md-12" style="padding:0">
    <input type="button" class="btn btn-xs btn-white active" id="grp" value="+Group" />

    <input type="button" class="btn btn-xs btn-white active" id="user" value="+Add Users" />
    </div>
	

   

	


	    <div class="col-md-11" style="padding:0px 0px 50px 0px">
	    <button type="submit" name="submit132" class="btn blue"><span>Buzz</span></button>
	    </div>

    </div>
    </div>

    </div>
<!--/ end form -->
<style>
/* Hashtag */
.hashtag-dropdown { margin-top: -10px; width:100%; z-index:50; display:none; background:#fff;}
.hashtag-dropdown {font-weight:bold; font-style:italic; color:#6E6E6E; font-size:10px; border:1px solid #C2C2C2; border-top:none;}
.hashtag-dropdown ul {list-style:none; margin:0px; border:0px solid #C2C2C2; border-top:none;}
.hashtag-dropdown ul li {border-bottom:1px solid #F5F5F5; cursor:pointer; display:block; width:100%; margin-left: -30px; padding:3px;}
.hashtag-dropdown ul li.hover {background:#0076a3; color: #fff;}
.hashtag-dropdown ul li.selection {color: #6E6E6E;}

/* Usertype */
.usertype-dropdown { margin-top: -10px; width:100%; z-index:50; display:none; background:#fff;}
.usertype-dropdown {font-weight:bold; font-style:italic; color:#6E6E6E; font-size:10px; border:1px solid #C2C2C2; border-top:none;}
.usertype-dropdown ul {list-style:none; margin:0px; border:0px solid #C2C2C2; border-top:none;}
.usertype-dropdown ul li {border-bottom:1px solid #F5F5F5; cursor:pointer; display:block; width:100%; margin-left: -54px; padding:1px;}
.usertype-dropdown ul li.hover {background:#0076a3; color: #fff;}
.usertype-dropdown ul li.selection {color: #6E6E6E;}
.usertype-dropdown ul li.selection:hover {color: #fff;}
.grptype-dropdown { margin-top: -10px; width:100%; z-index:50; display:none; background:#fff;}
.grptype-dropdown {font-weight:bold; font-style:italic; color:#6E6E6E; font-size:10px; border:1px solid #C2C2C2; border-top:none;}
.grptype-dropdown ul {list-style:none; margin:0px; border:0px solid #C2C2C2; border-top:none;}
.grptype-dropdown ul li {border-bottom:1px solid #F5F5F5; cursor:pointer; display:block; width:100%; margin-left: 0px; padding:1px;}
.grptype-dropdown ul li.hover {background:#0076a3; color: #fff;}
.grptype-dropdown ul li.selection {color: #6E6E6E;}
.grptype-dropdown ul li.selection:hover {color: #fff;}
</style>

<style>
.form-date {
	width: 81px!important;
	margin-bottom: -55px!important;
}
.event-desc {
	min-height: 78px;
	margin-bottom: 10px;
}
.btn-white {
	border-color: #0084B4;
	border-color: rgba(0,132,180,.5);
    color: #0084B4;
    background: rgba(255,255,255,0.75);
    border-style: solid;
    border-width: 1px;
    box-shadow: none;
    opacity: .8;
    -ms-filter: "alpha(opacity=80)";
}
.btn-white:hover {
	background-color: #1b95e0;
	color: #fff;
}
.blue {
	margin-top: 15px;
}
@media screen and (max-width: 480px) {
.form-date {
	width: 100%!important;
	margin-bottom: -55px!important;
}
}
</style>
<!-- Add more script starts -->
<script>
$(document).ready(function() {


$("#grp").click(function() {
$("#grpuser").show(500);
});

$("#user").click(function() {
$("#grpuser").show(500);
});

$("#closegrpuser").click(function() {

$("#user").css("background-color" , "rgba(255,255,255,0.75)");
$("#user").css("color" , "#1b95e0");
$("#grp").css("background-color" , "rgba(255,255,255,0.75)");
$("#grp").css("color" , "#1b95e0");
});



});
</script>
<!-- Add more script ends -->
<!-- statrt Group/Add Users 'active' color -->
<script>
 function selectpollgrp(val) {
$("#grouptxtedit").val(val);
$(".grptype-dropdown").hide();
}

$(document).ready(function() {

$("#grp").click(function() {
$("#grp").css("background-color" , "#1b95e0");
$("#grp").css("color" , "#ffffff");
$("#user").css("background-color" , "rgba(255,255,255,0.75)");
$("#user").css("color" , "#1b95e0");
});

$("#user").click(function() {
$("#user").css("background-color" , "#1b95e0");
$("#user").css("color" , "#ffffff");
$("#grp").css("background-color" , "rgba(255,255,255,0.75)");
$("#grp").css("color" , "#1b95e0");
});





});
</script>
<!-- end Group/Add Users 'active' color -->




<script src="<?php echo $C->SITE_URL;?>static/js/jquery.js?v=3.6.0"></script>

<script src="<?php echo $C->SITE_URL;?>static/js/htmlarea_hash_edit.js?v=3.6.0"></script>
<script src="<?php echo $C->SITE_URL;?>static/js/htmlarea_event_edit.js?v=3.6.0"></script>
<script src="<?php echo $C->SITE_URL;?>static/js/htmlarea_user_edit.js?v=3.6.0"></script>



<style>
.htmlarea-ac-container1{
margin-top:270px;margin-left:200px;width:290px;position:absolute!important;
}
.htmlarea-ac-container{
margin-top:333px;margin-left:200px;width:290px;position:absolute!important;
}
</style>


<script >
$(document).ready(function(){
	$("#addhashtag").click(function() {
$("#hashtagopt").css("display","block");
});

$("#closehashtag").click(function() {
	$("#hastag").val('');

$("#hashtagopt").css("display","none");


});
$("#addurl").click(function() {
$("#urlopt").css("display","block");
});
 $("#grouptxtedit").keyup(function(){
	 var group = $(this).val();
		$.ajax({
			type: "POST",
			url:"<?php  echo $C->SITE_URL;?>autocomplete",
			data:{poll_group:group},
			
			success: function(data){
				$(".grptype-dropdown").show();
				$(".grptype-dropdown").html(data);
			}
			});
	 
 });

$("#closeurl").click(function() {
	$("#url").val('');

$("#urlopt").css("display","none");
});
$("#adddescription").click(function() {
$("#descopt").css("display","block");
});

$("#closedesc").click(function() {
$("#description").val('');

$("#descopt").css("display","none");

});



	    
	var abc     ="<?php echo $event->street_group; ?>";
	var abcd     ="<?php echo $event->street_user; ?>";
	 if(abc ==''){
		 $("#grouptextarea").css("display","none");
		 
	 }else{
		  $("#grouptextarea").css("display","block");

		 
	 }
	  if(abcd.trim() ==''){
		$("#usertextarea").css("display","none");


		 
	 }else{
		$("#usertextarea").css("display","block");

		 
	 }
	 
$("#closegrp").click(function(){
	$("#grouptxtedit").val("");
	$("#grouptextarea").css("display","none");

	
});
$("#closeuser").click(function(){
	$("#usertxtedit").val("");

	$("#usertextarea").css("display","none");

	
});
$("#grp").click(function(){
	$("#grouptextarea").css("display","block");

	
});
$("#user").click(function(){
	$("#usertextarea").css("display","block");

	
});
	
	 $("#rmbt").click(function(){
		 $("#post").show();
		  $("#poll").hide();
		 
	 });
	
	
	 
	 
	 
});

</script>




