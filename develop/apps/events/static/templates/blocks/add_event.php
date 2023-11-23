<div class="content pad"> 
	<form action="" method="POST" enctype="multipart/form-data"><table class="form-container custom-form-container">
		<input type="hidden" id="display_type" name="display_type" value="community"><tbody><tr>
			<td class="field-title"><label for="title">Title:</label></td>
			<td><input type="text" id="title" data-validate="required" name="title" value="<?php echo isset($_POST['title'])?$_POST['title']:'';?>" maxlength="50" autocomplete="off"></td>
		</tr><tr>
			<td class="field-title"><label for="start_date">Start Time:</label></td>
			<td><input type="text" data-validate="required" id="start_date" name="start_date" value="{%start%}" >
				<input type="text"  id="start_time" name="start_time" value="<?php echo isset($_POST['start_time'])?$_POST['start_time']:'';?>" >
			</td>
		</tr><tr>
			<td class="field-title"><label for="end_date">End Time:</label></td>
			<td><input type="text" data-validate="required" id="end_date" name="end_date" value="{%end%}" >
			<input type="text"  id="end_time" name="end_time" value="<?php echo isset($_POST['end_time'])?$_POST['end_time']:'';?>" ></td>
		</tr><tr>
			<td class="field-title"><label for="address">Address:</label></td>
			<td><input type="text" id="address" name="address" value="<?php echo isset($_POST['address'])?$_POST['address']:'';?>" maxlength="50" autocomplete="off" placeholder="Enter a location"></td>
		</tr><tr>
			<td class="field-title"><label for="description">Description:</label></td>
			<td><textarea id="description" name="description"><?php echo isset($_POST['description'])?$_POST['description']:'';?></textarea></td>
		</tr><input type="hidden" id="is_private" name="is_private" value="0"><tr>
		<td class="field-title"><label for="event_type">Type:</label></td>
		<td>
			<select id="event_type" name="event_type"><option value="normal">Regular</option><option value="major">Important</option></select>
		</td></tr><tr>
		<tr>
			<td class="field-title event_label"><label for="activty_enable">Create Activity:</label></td>
				<td  class='event_activity'>
				<input type="checkbox" name="publish_now" <?php echo isset($_POST['publish_now'])?'checked':'';?> id="publish_now" value="1"><label class="js-publish">Publish Now </label><br />
				<input type="checkbox" name="publish_date" <?php echo isset($_POST['publish_date'])?'checked':'';?> id="publish_date" value="1"><label  class="js-publish">Publish on date</label>
				<div class="clearfix"></div>
				<input type="text"  name="pub_select_day" for="publish_date" id="pub_select_day" value="<?php echo isset($_POST['pub_select_day'])?$_POST['pub_select_day']:date('M d, Y');?>" disabled />
				<input type="text" name="publish_time" for="publish_time" id="publish_time" value="<?php echo isset($_POST['publish_time'])?$_POST['publish_time']:date('h:i A');?>" disabled /><br>
			</td>
		</tr>	
		</tr>	<tr>
			<td class="field-title"><label for="attachment">Attachment: </label></td>
			<td><input type="file" class="upfile" data-validate="FileValidRule"  id="attachment" name="attachment[]" value=""></td>
		</tr><tr>
			<td></td>
			<td><button type="submit" name="submit4545" class="btn blue"><span>Save</span></button></td>
		</tr></tbody></table>
	</form>

</div> 

