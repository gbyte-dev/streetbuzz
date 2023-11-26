<form  id="event_form" action="" method="POST" enctype="multipart/form-data">
<table class="form-container custom-form-container">
<input type="hidden" id="display_type" name="display_type" value="community" ><tbody><tr>
<td class="field-title"><label for="title">Title:</label></td>
<td><input type="text" id="title" name="title" data-validate="required" value="{%eventtitle%}" ></td>
</tr><tr>
<td class="field-title"><label for="start_date">Start Time:</label></td>
<td><input type="text" id="start_date" name="start_date" data-validate="required" value="{%start_date%}" >
<input type="text" id="start_time" name="start_time" value="{%start_time%}"></td>
</tr><tr>
<td class="field-title"><label for="end_date">End Time:</label></td>
<td><input type="text" id="end_date" name="end_date" data-validate="required" value="{%end_date%}">
<input type="text" id="end_time" name="end_time" value="{%end_time%}"></td>
</tr><tr>
<td class="field-title"><label for="address">Address:</label></td>
<td><input type="text" id="address" name="address" value="{%address%}" placeholder="Enter a location"></td>
</tr><tr>
<td class="field-title"><label for="description">Description </label></td>
<td><textarea id="description" name="description">{%description%}</textarea></td>
</tr>
		<input type="hidden" id="is_private" name="is_private" value="0" />
		<tr>
		<td class="field-title"><label for="event_type">Event Type:</label></td>
		<td>
			{%eventtype%}
		</td></tr>
		<tr>
			<td class="field-title event_label"><label for="activty_enable">Activity Feed Status:</label></td>
			<td class='event_activity'>
				{%publish_now%}
				<input name="pub_select_day" for="publish_date" id="pub_select_day" value="{%pub_select_day%}"  {%disabledval%} />
				<input name="publish_time" for="publish_time" id="publish_time" value="{%pub_time%}" {%disabledval%}  /><br>
			</td>
		</tr>	
		<tr>
			<td></td>
		</tr>
</tbody></table>


<div class="attachment_content">
<h2 class="section-title">Add Attachment</h2>
	{%attchmentlist%}
	<br />
	<div>
		<div class="form_row">
		<span>Attachment:</span><input type="file" class="upfile" data-validate="FileValidRule" id="attachment" name="attachment[]" value="">
		<!--br /><br /><span><b>NOTE:</b> File must be less than 2MB. File type should be jpg,gif,png,pdf format only</span-->
		</div>
	</div>
</br>
</div>
<table class="form-container ">
<tr>
		<td class="field-title"><label for="event_type">&nbsp;</label></td>
		<td>
			<button type="submit" name="submit132" class="btn blue"><span>Save</span></button>
		</td></tr><tr>
			<td></td>
		</tr>
</tbody></table>
</form>
</div>
</div>
</div>
