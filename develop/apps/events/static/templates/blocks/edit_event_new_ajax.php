<div class="header"><h1>Edit Event</h1></div>
<form id="event_form" action="" method="POST" enctype="multipart/form-data">
<table class="form-container custom-form-container">
<input type="hidden" id="display_type" name="display_type" value="<?php echo $display_type; ?>"><tbody><tr>
<td class="field-title"><label for="title">Title:</label></td>
<td><input type="text" id="title" name="title" value="<?php echo $event->event_name; ?>"  data-validate="required"></td>
</tr><tr>
<td class="field-title"><label for="start_date">Start Time:</label></td>
<td><input type="text" id="start_date" name="start_date" value="<?php echo $start_date; ?>"  data-validate="required">
<input type="text" id="start_time" name="start_time" value="<?php echo $start_time; ?>" ></td>
</tr><tr>
<td class="field-title"><label for="end_date">End Date:</label></td>
<td><input type="text" id="end_date" name="end_date"  value="<?php echo $end_date; ?>"  data-validate="required">
<input type="text" id="end_time" name="end_time" value="<?php echo $end_time; ?>" >
</td>
</tr><tr>
<td class="field-title"><label for="address">Address:</label></td>
<td><input type="text" id="address" name="address" value="<?php echo $event->address; ?>"  placeholder="Enter a location"></td>
</tr><tr>
<tr>
<td class="field-title"><label for="address">Url:</label></td>
<td><input type="text" id="url" name="url" value="<?php echo $event->url; ?>"  placeholder="Enter a location"></td>
</tr>
<td class="field-title"><label for="description">Description:</label></td>
<td><textarea id="description" name="description"><?php echo $event->event_description; ?></textarea></td>
</tr>
<tr>
<tr>
			<td class="field-title"><label for="description" ></label></td>
			<td><input data-validate="required"  type="text" id="hastagedit" name="hastag" placeholder="hastag" value="<?php echo $event->tag_name; ?>" ></input></td>
		</tr>
		<tr id="grtxt">
			<td class="field-title"></td>
			<td><input type="text" class="htmlarea textarea group"id="grouptxtedit" placeholder="Group" value="<?php echo $event->street_group; ?>" name="street_group" /></td>
		</tr>
		<tr id="urtxt">
			<td class="field-title"></td>
			<td><input type="text" class="group" id="usertxtedit" value="<?php echo $event->street_user ; ?>" placeholder="Users" name="street_user"  /></td>
		</tr>
		<tr>
			<td class="field-title"><input type="button" id="grp" value="+Group" /></td>
			<td><input type="button" id="user" value="+Add Users" /></td>
		</tr>
		
		<input type="hidden" id="is_private" name="is_private" value="0" />
		<input type="hidden" id="group_id" name="group_id" value="<?php echo $group_id; ?>" />
		<input type="hidden" id="event_id" name="event_id" value="<?php echo $page->event; ?>" />

		
	<div class="htmlarea-ac">
			<div class="htmlarea-ac-container"></div>
			<div class="htmlarea-ac-container1" style="display:none"></div>
		</div>
		
	<div class="attachment_content"></div>
<?php if(!empty($event->group_id)){ ?>
<tr>
	<td></td><td></td>
</tr>

<tr>
	<td class="field-title">Assign roles and Resources:</td>
	<td>
<div class="role_section">
	<a class="event_roles" href="#" style="float:right; margin-bottom: 10px;"><span>Add Roles</span></a>
	<div class="event_role_view">
		<span id='error' class='error_resource' style='color:red;'>{%message%}</span>
		<input id="group_id" name="group_id" type="hidden" value="<?php echo $group_id; ?>" size="30">
		<div class="form_row" id='error' style='color:red;'></div>
		<div class="form_row"><span>Role:</span><br><input id="role_name_id" name="role_name12" type="text" value="" size="30"></div>
		<div class="form_row"><span>Description:<span><br><input id="role_description" name="role_description12" type="text" value="" size="30"></div>
		
		<div class="form_row">
			<input class="btn_submit btn green js-button-add-role" type="button" value="Add Roles" name="roles">
		</div>
	</div>
</br></br>
<?php echo $roles_list; ?>
</div>

<div class="resource_section">
	<a class="event_resource" href="#" style="float:right; margin-bottom: 10px;"><span>Assign roles</span></a>
	<div class="event_resource_view">
		<span id='error' class='error_role' style='color:red;'>{%message%}</span>
		<input id="group_id" name="group_id" type="hidden" value="<?php echo $group_id; ?>" size="30">

			<div class="form_row" id='error' style='color:red;'></div>
			<div class="form_row"><span>Select Role:</span><br>
				<?php echo empty($role_select) ? '' : $role_select; ?>
			</div>
			<div class="form_row"><span>Members List:<span>
				<br>
			</div>
			<?php echo empty($role_select_user) ? '' : $role_select_user; ?>
			<br/>
		<div class="form_row">
			<input class="btn_submit btn green js-button-add-resource" type="button" value="Add" name="Submit">
		</div>
	</div>
</div>

<?php 
echo empty($roles_resouce_list) ? '' : $roles_resouce_list; 

echo '	</td>
</tr>';
}
?>

<tr><td></td><td>
	<div style="float:left; margin-top:40px;"><button type="button" id="buzz"name="submit132" class="btn blue" value="Buzz"><span>Buzz</span></button></div>
</td></tr>
</tbody></table>
<input type="hidden" id="ha" value="1">
<input type="hidden" id="gro" value="1">
<input type="hidden" id="us" value="1">


</form>

<script src="<?php echo $C->SITE_URL;?>static/js/htmlarea_hash_edit_ajax.js?v=3.6.0"></script>
<script src="<?php echo $C->SITE_URL;?>static/js/htmlarea_event_edit_ajax.js?v=3.6.0"></script>
<script src="<?php echo $C->SITE_URL;?>static/js/htmlarea_user_edit.js?v=3.6.0"></script>





<style>
.htmlarea-ac-container1{
    margin-top: 573px;
    margin-left: 160px;
    width: 290px;
    position: absolute!important;
    z-index: 10000;
    background-color: #fff;
    border: 1px solid #dadada;
}
.htmlarea-ac-container1 ul {
	list-style-type:none;
}
.htmlarea-ac-container1 ul li {
	border-bottom:1px solid #DADADA;
}
.htmlarea-ac-container1 ul li:hover {
	border-bottom:1px solid #DADADA;
	background-color: #DADADA;
}
.htmlarea-ac-container{
margin-top:333px;margin-left:200px;width:290px;position:absolute!important;
}


</style>
<script type="text/javascript">
$("#buzz").click(function(){
		var is_private = $("#is_private").val();
		var group_id = $("#group_id").val();

	var title = $("#title").val();
	var start_date = $("#start_date").val();
	var start_time = $("#start_time").val();
	var end_date = $("#end_date").val();
	var end_time = $("#end_time").val();
	var address = $("#address").val();
	var url = $("#url").val();
	var description = $("#description").val();
	var hastagedit = $("#hastagedit").val();
	var grouptxtedit = $("#grouptxtedit").val();
	var usertxtedit = $("#usertxtedit").val();
	var display_type = $("#display_type").val();
	var event_id = $("#event_id").val();




	$.ajax({
					url:siteurl + 'services/events/editevents',
					type: 'POST',
					data: {
						event_id:event_id,
						display_type:display_type,
						is_private:is_private,
						group_id:group_id,
						title:title,
						start_date:start_date,
						start_time:start_time,
						end_date:end_date,
						end_time:end_time,
						address:address,
						url:url,
						description:description,
						hastag:hastagedit,
						street_group:grouptxtedit,
						street_user:usertxtedit
					},
					success: function(response) {
												//$("#content_all").hide();
$("#allevents").load("<?php echo $C->SITE_URL;?>notification_list",{id:'list_all'});
		$("#list").css("display","none");
							$("#aleve").css("display","block");

						//content = response.data.html;
						
						/*$.colorbox({
							width: '700px',
							html: content,
							open: true
						});*/
					}
				});
});

var abc     ="<?php echo $event->street_group; ?>";
	var abcd     ="<?php echo $event->street_user; ?>";
	 if(abc ==''){
		 $("#grtxt").hide();
		 
	 }else{
		  $("#grtxt").show();
		 
	 }
	  if(abcd.trim() ==''){
		 $("#urtxt").hide();
		 
	 }else{
		  $("#urtxt").show();
		 
	 }
	 $("#grp").click(function(){
		 $("#grtxt").show();
		  $("#share").val("group");
		 $("#urtxt").hide();
		 		 		 $(".htmlarea-ac-container").css("margin-top",165);

		 		 $(".htmlarea-ac-container").css("margin-top",630);

	 });
	 $("#user").click(function(){
		 $("#grtxt").hide();
		  $("#share").val("user");
		 $("#urtxt").show();
		 		 		 		 $(".htmlarea-ac-container").css("margin-top",165);

		 $(".htmlarea-ac-container").css("margin-top",630);
	 });
$( "#start_date, #start_time" ).click(function(){
	$("#ui-datepicker-div").fadeIn();
});
$( "#end_date, #end_time" ).click(function(){
	$("#ui-datepicker-div").fadeIn();
});
	$("#address").geocomplete();



	var dt = new Date();
	var dd = dt.getDate();
	var mm = dt.getMonth()+1; //January is 0!
	var yyyy = dt.getFullYear();
	
	//Ui Tab intiate
	$( "#tabs" ).tabs();
	$('a.gallery').colorbox({height:"100%"});
	
	//Datetime picker
    $( "#start_date, #start_time" ).datetimepicker({
		altField: "#start_time",
		minDateTime:dt,
		dateFormat: 'M d, yy', 
		timeFormat: 'hh:mm TT',
		altTimeFormat: 'hh:mm TT',
		onSelect: function( selectedDate ) {
			
			if( selectedDate != '' ){
				
				var date = new Date(selectedDate);
				var yr = date.getFullYear();
				var mo = date.getMonth() + 1;
				var day = date.getDate();

				var hours 	= $('#start_time').val().substr(0, 2);
				var minutes	= $('#start_time').val().substr(3, 2);
				var pm_am	= $('#start_time').val().substr(6, 2);
				var sec		= '00';
				
				var selectedCurrentDate =new Date(yr + ',' + mo  + ',' + day + ' ' + hours + ':' + minutes + ':' + sec + ' ' + pm_am);
				
				if ( !isNaN( selectedCurrentDate.getTime() ) ) {
						
					var sEndD = new Date( $('#end_date').datetimepicker('getDate') ); 

					if( $('#end_date').val() == '' ){ 
						
						hours = parseInt(hours) + 2;

						if( hours >= 12 ){
							if( pm_am == 'PM' ){
								day = day + 1;
							}
							hours = hours - 12;
							pm_am = ( pm_am == 'PM' ? 'AM' : 'PM' );
						}

						var newDateString = yr + ',' + mo  + ',' + day;
						var newTimeString = hours + ':' + minutes + ':' + sec;

						var excelDateString =new Date(newDateString + ' ' + newTimeString + ' ' + pm_am);
					
						$( "#end_date" ).datetimepicker('setDate', excelDateString);
                       // $("#ui-datepicker-div").fadeOut();

						
					}else{

						
						var testEndDate		= new Date(sEndD.getFullYear() + ',' + (sEndD.getMonth() + 1)  + ',' + sEndD.getDate() + ' ' + $('#end_time').val().substr(0, 2) + ':' + $('#end_time').val().substr(3, 2) + ':00 ' + $('#end_time').val().substr(6, 2));

						if( selectedCurrentDate > testEndDate ){

							hours = parseInt(hours) + 2;
							
							if( hours >= 12 ){				
								if( pm_am == 'PM' ){
									day = day + 1;
								}
								hours = hours - 12;
								pm_am = ( pm_am == 'PM' ? 'AM' : 'PM' );
							}
							var newDateString = yr + ',' + mo  + ',' + day;
							var newTimeString = hours + ':' + minutes + ':' + sec;
							var excelDateString =new Date(newDateString + ' ' + newTimeString + ' ' + pm_am);
							$( "#end_date" ).datetimepicker('setDate', excelDateString);

							 $("#ui-datepicker-div").fadeOut();

							
						}else{
							 $("#ui-datepicker-div").fadeOut();
						}
						
					}
					
				}

			}

		}
		
	
    }).attr('readonly','readonly');
	    $( "#end_date, #end_time" ).datetimepicker({
		altField: "#end_time",
		minDateTime:dt,
		dateFormat: 'M d, yy', 
		altTimeFormat: 'hh:mm TT',
		timeFormat: 'hh:mm TT',		
		onSelect: function( selectedDate ) {
			
			if( selectedDate != '' ){
				
				var date = new Date(selectedDate);
				var yr = date.getFullYear();
				var mo = date.getMonth() + 1;
				var day = date.getDate();

				var hours 	= $('#end_time').val().substr(0, 2);
				var minutes	= $('#end_time').val().substr(3, 2);
				var pm_am	= $('#end_time').val().substr(6, 2);
				var sec		= '00';
				
				var selectedCurrentDate =new Date(yr + ',' + mo  + ',' + day + ' ' + hours + ':' + minutes + ':' + sec + ' ' + pm_am);
				
				if ( !isNaN( selectedCurrentDate.getTime() ) ) {
						
					var sEndD = new Date( $('#start_date').datetimepicker('getDate') ); 

					if( $('#start_date').val() == '' ){ 

						$( "#start_date" ).datetimepicker('setDate', selectedCurrentDate);
						$("#ui-datepicker-div").fadeOut();

						
					}else{
						
						var testEndDate		= new Date(sEndD.getFullYear() + ',' + (sEndD.getMonth() + 1)  + ',' + sEndD.getDate() + ' ' + $('#start_time').val().substr(0, 2) + ':' + $('#start_time').val().substr(3, 2) + ':00 ' + $('#start_time').val().substr(6, 2));

						if( testEndDate > selectedCurrentDate ){

							$( "#end_date" ).datetimepicker('setDate', testEndDate);
							 $("#ui-datepicker-div").fadeOut();
							
						}else{
								$("#ui-datepicker-div").fadeOut();

						}
						
					}
					
				}else{
					$("#ui-datepicker-div").fadeOut();
					
				}

			}

		}	
    }).attr('readonly','readonly');
	
	
</script>
