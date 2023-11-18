<!--<link href="<?php echo $C->SITE_URL ; ?>apps/events/static/cs/multiselect.css" media="screen" rel="stylesheet" type="text/css">-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!--<script type="text/javascript" src="<?php echo $C->SITE_URL ; ?>apps/events/static/js/jquery.multi-select.js"></script>-->

<h3>Add Roles and Resource</h3>
<hr>
<?php

?>

<form action="" id='add_roles_resource' name="add_roles_resource" method="post"  autocomplete="off">
<span id='error' style='color:#0099ff;'>{%message%}</span>
<input id="group_id" name="group_id" type="hidden" value="{%group_id%}" size="30">

	<div class="form_row" id='error' style='color:red;'></div>
	<div class="form_row"><span>Select Role:</span><br>
		{%role_select%}
	</div>
	<div class="form_row"><span>Add Resource List:<span>
	<br>
	</div>
	<a onclick='selectAll();'>Select All</a>&nbsp&nbsp&nbsp&nbsp&nbsp <a onclick='de_selectAll();'>De-select All</a>
	{%role_select_user%}
	<br>
<div class="form_row">
	<input class="btn_submit blue" type="submit" value="Submit" name="Submit">

</div>
</form>
</br></br>
{%roles_list%}

<script type="text/javascript">

$( document ).ready(function() {

//$('#my-select').multiSelect()
$('#my-select').multiSelect({ 
		keepOrder: true,
		selectableHeader: "<div class='custom-header'>Selectable items</div>",
		selectionHeader: "<div class='custom-header'>Selection items</div>",
		/* search */
			
		/* search */
	});
});
function selectAll()
{
	 $('#my-select').multiSelect('select_all');
	 return false;
}

function de_selectAll()
{
	 $('#my-select').multiSelect('deselect_all');
	 return false;
}

function add_role_validation()
{
	var role = document.getElementById("role_name").value;

	if(role=='')
	{
		var x=document.getElementById("error");
		x.innerHTML='';
		x.innerHTML='Please enter role name';
		x.style.color = "red";  
		return false;
	}
	return true;

}

</script>
