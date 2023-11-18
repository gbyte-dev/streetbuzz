<div class="header"><h1>{%event_title%}</h1></div>
<form id="event_form" action="" method="POST" enctype="multipart/form-data">
<table class="form-container custom-form-container">
<input type="hidden" id="display_type" name="display_type" value="{%display_type%}"><tbody><tr>
<td class="field-title"><label for="title">Title:</label></td>
<td><input type="text" id="title" name="title" value="{%eventtitle%}"  data-validate="required"></td>
</tr><tr>
<td class="field-title"><label for="start_date">Start Time:</label></td>
<td><input type="text" id="start_date" name="start_date" value="{%start_date%}"  data-validate="required">
<input type="text" id="start_time" name="start_time" value="{%start_time%}" ></td>
</tr><tr>
<td class="field-title"><label for="end_date">End Date:</label></td>
<td><input type="text" id="end_date" name="end_date"  value="{%end_date%}"  data-validate="required">
<input type="text" id="end_time" name="end_time" value="{%end_time%}" >
</td>
</tr><tr>
<td class="field-title"><label for="address">Address:</label></td>
<td><input type="text" id="address" name="address" value="{%address%}"  placeholder="Enter a location"></td>
</tr><tr>
<td class="field-title"><label for="description">Description:</label></td>
<td><textarea id="description" name="description">{%description%}</textarea></td>
</tr>
		<input type="hidden" id="is_private" name="is_private" value="0" /><input type="hidden" id="group_id" name="group_id" value="{%group_id%}" />
		<tr>
		<td class="field-title"><label for="event_type">Type:</label></td>
		<td>
			{%eventtype%}
		</td>
		</tr>
		<tr>
			<td class="field-title event_label"><label for="activty_enable">Create Activity:</label></td>
			<td  class='event_activity'>
				{%publish_now%}
				<div class="clearfix"></div>
				<input type="text" name="pub_select_day" for="publish_date" id="pub_select_day" value="{%pub_select_day%}" {%disabledval%} />
				<input type="text" name="publish_time" for="publish_time" id="publish_time" value="{%pub_time%}" {%disabledval%}  /><br>
			</td>
		</tr>			
		<tr>
			<td class="field-title"><label for="attachment">Attachment: </label></td>
			<td>
				{%attchmentlist%}<br />
				<input type="file" class="upfile" data-validate="FileValidRule" id="attachment" name="attachment[]" value="">
			</td>
		</tr>
		<div class="attachment_content"></div>
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
					<input id="group_id" name="group_id" type="hidden" value="{%group_id%}" size="30">
					<div class="form_row" id='error' style='color:red;'></div>
					<div class="form_row"><span>Role:</span><br><input id="role_name_id" name="role_name12" type="text" value="" size="30"></div>
					<div class="form_row"><span>Description:<span><br><input id="role_description" name="role_description12" type="text" value="" size="30"></div>
					<div class="htmlarea-ac">
						<div class="htmlarea-ac-container"></div>
					</div>
					<div class="form_row">
						<input class="btn_submit btn green js-button-add-role" type="button" value="Add Roles" name="roles">
					</div>
				</div>
			</br></br>
			{%roles_list%}
			</div>
			
			<div class="resource_section">
			<a class="event_resource" href="#" style="float:right; margin-bottom: 10px;"><span>Assign roles</span></a>
			<div class="event_resource_view">
				<span id='error' class='error_role' style='color:red;'>{%message%}</span>
				<input id="group_id" name="group_id" type="hidden" value="{%group_id%}" size="30">

					<div class="form_row" id='error' style='color:red;'></div>
					<div class="form_row"><span>Select Role:</span><br>
						{%role_select%}
					</div>
					<div class="form_row"><span>Members List:<span>
						<br>
					</div>
					{%role_select_user%}
					<br>
				<div class="form_row">
					<input class="btn_submit btn green js-button-add-resource" type="button" value="Add" name="Submit">
				</div>
			</div>
			{%roles_resouce_list%}
			</div>
			</td>
		</tr>

		<tr><td></td><td>
			<div style="float:left; margin-top:40px;"><button type="submit" name="submit132" class="btn blue"><span>Save</span></button></div>
		</td></tr>
	</tbody></table>
</form>


