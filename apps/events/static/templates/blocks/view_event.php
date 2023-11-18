<div class="content pad"> 
{%content%}
		<div class="comments-editor" style='float:left;width:99%; padding-right:0;' id="post_comments">
	    <div class="loading-container">
	        <div class="loading-indicatior"></div>
	    </div>
		<span id='msg' style='margin-left:10px;'></span>
    	

<!-- start post comment -->
<div class="row" style="padding:0px 10px;">
<div class="col-md-12 col-xs-12">

<div class="col-md-1 col-xs-1" style="padding:0; overflow:hidden">
<a class="avatar" href="<?php echo $C->SITE_URL ; ?>{%user_name%}">
<img alt="{%user_name%}" src="<?php echo $C->STORAGE_URL; ?>avatars/thumbs1/{%img_name%}"></a>
</div>

<div class="col-md-11 col-xs-11" style="padding:0px 3px 0px 8px;">

<!-- start post & comments -->
<div class="comments-editor-content events-comments">
			<div class="user-status-field htmlarea">
				<form action="" id='comment_post' method="post" autocomplete="off">
					<input type='hidden' id='login_user_id' value={%login_user_id%} />
					<input type='hidden' id='post_id' value={%post_id%} />
					<div class="textarea-wrap">
						<textarea  id='message' name='comment_post'></textarea>
						<div class="textarea-highlighter"></div>
					</div>
			</div>
			<div class="htmlarea-ac">
				<div class="htmlarea-ac-container" style="display: none;"></div>
			</div>

				<div class="buttons">
					<a class="comment-post post-btn blue" onclick='comment_post();'>Post comment</a>
				</div>
			</form>
			<span id='mycls'></span>
			{%comments%}

			<div class="clear"></div>

		</div>
<!--/ end post & comments -->	
		
</div>

</div>
</div>
<!--/ end post comment -->





    	
 </div><!--/ comments-editor -->

 </div><!--/ content pad -->
	
<script type='text/javascript'>

	function getnewSuccess_event1(response, context) 
	{
		$( "#mycls" ).after( response.html );
	}
	function getnewSuccess(response, context) 
	{
		//var result = $(response.html).html();
		var res = response.html;
		var parts = res.split("_");
		if(parts[0]=="SUCCESS")
		{
			$('#message').val('');
			//$('#msg').html('Sucessfully Posted your comments').css('color','#0099ff');

			//$('#msg').html('Sucessfully Posted your comments').css('color','#0099ff').fadeOut( "10000" );;
			var data = {
						post_id: parts[1],
						cmt_id: parts[2],
						type: 3
					}
			var args = {
							type: 'post',
							module: 'events',
							action: 'comment',
							data: data
	    				}
			Services.invoke(args, getnewSuccess_event1, commandFail, $(this));
		}
		if(parts[0]=='DELETE')
		{
			$('#cmt'+parts[1]).remove();
		}
    }
    function commandFail(response) {
        STX.showMessage(response.message, "error");
		//console.log(error);
    };

	function comment_post()
	{ 
		if($('#message').val()=='')
		{
			$('#msg').html('Comments Should not be an empty').css('color','red');
			return false;
		}
		var data = {
						message: $('#message').val(),
						post_id: $('#post_id').val(),
						user_id: $('#login_user_id').val(),
						type: 1
					}
		var args = {
						type: 'post',
						module: 'events',
						action: 'comment',
						data: data
	    			}
		Services.invoke(args, getnewSuccess, commandFail, $(this));
	}

	function delete_comments(id,post_id)
	{
		var data = { id: id, type: 2, post_id:post_id }
		var args = {
						type: 'post',
						module: 'events',
						action: 'comment',
						data: data
	    			}
		if (confirm('Are you sure you want to delete that comment?')) { 
			Services.invoke(args, getnewSuccess, commandFail, $(this));
		}
	}
</script>

