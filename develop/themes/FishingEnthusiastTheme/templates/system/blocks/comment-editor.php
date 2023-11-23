		<div id="editor-placeholder">
    <div class="comments-editor data-content-placeholder">
	
	    <div class="loading-container">
	        <div class="loading-indicatior"></div>
	    </div>
    
    	{%comment_editor_user_avatar%}
		<div class="comments-editor-content offset_comment_container">
			{%editor_textarea1%}
				<!-- <div class="attachments-options">
				<!-- <a class="attachment-button ac-btn">User<span class="tooltip"><span></span></span></a> -->
			<!-- <button id="pollcreate" class="left comment-post post-btn btn blue"><span>POLL</span></button>
			</div>-->
			<div class="buttons comment-btns">
				<a data-action="set" data-namespace="comments" data-role="services" class="comment-post post-btn"><span>Comment</span></a>
				<a data-value="0" data-action="add" data-namespace="comments" data-role="ajax-click" class="comment-cancel post-btn hidden"><span><?= $this->page->lang('activity_comment_option_cancel') ?></span></a>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>