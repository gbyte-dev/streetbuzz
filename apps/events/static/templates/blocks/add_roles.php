
<h3>Add Roles</h3>

<form action="" id='add_roles' name="add_roles" method="post" onsubmit="return add_role_validation();" enctype="multipart/form-data" autocomplete="off">
<span id='error' style='color:#0099ff;'>{%message%}</span>
<input id="group_id" name="group_id" type="hidden" value="{%group_id%}" size="30">

	<div class="form_row" id='error' style='color:red;'></div>
	<div class="form_row"><span>Role Name:</span><br><input id="role_name" name="role_name" type="text" value="" size="30"></div>
	<div class="form_row"><span>Description:<span><br><input id="role_description" name="role_description" type="text" value="" size="30"></div>


<div class="htmlarea-ac">
	<div class="htmlarea-ac-container"></div>
	
</div>
<div class="form_row">
	<input class="btn_submit blue" type="submit" value="Submit" name="Submit">

</div>
</form>
</br></br>
{%roles_list%}

<script type="text/javascript">
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
