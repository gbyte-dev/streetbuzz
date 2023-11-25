	<li>
	<div class="row comment-ctrl comment {%activity_comment_new%}" style="margin-left:0px; margin-right:0px;">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 comment-container zeropadding">
<?php 	if( preg_match( "#iPhone#i", $_SERVER['HTTP_USER_AGENT'] )  || preg_match( "#iPod#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Android#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Phone#i", $_SERVER['HTTP_USER_AGENT'] )) { ?>
	<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 zeropadding">
	    <?php }else{ ?>
	    	<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 image-div zeropadding">
	  <?php  }?>
	
		{%activity_comment_user_avatar%}	
	</div><!--/ col-md-1 -->
	<div class="col-xs-11 col-sm-11 col-md-11 col-lg-11 ">
	<div class="comment-author pull-left">{%activity_comment_user_username%}</div>
			<div class="comment-options pull-right hidden-opt">
			<!--{%activity_comment_options%}-->
			</div>
			
	
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 activity-content zeropadding" style="margin-top:0">
    {%activity_comment_text%}
	</div>
	<div class="attachments lightbox-enabled">
		<div class="images">
		<div class="list-link-container">
			{%image_txt%}
			<!-- <a target="_blank" href="" class="lightbox-image image-thumb cboxElement"><img alt="filename" src="{%activity_attachment_image_src%}"></a> -->
			<!-- //this is placeholder for video player <div class="video-placeholder"></div>  -->
		</div>
	
	</div>
	<div class="links">
	</div>
	<div class="files col-xs-12 col-sm-12 col-md-12 col-lg-12">
		{%file_txt%}
	</div>
	
	</div>
			<div class="meta-info">
				<span class="permlink">{%activity_comment_date%}</span>
				<!--  <a class="reply" title="Reply">Reply</a> -->
				{%activity_comment_footer%}
	</div>
	
	</div><!--/ col-md-11 -->
	</div><!--/ col-md-12 -->
	</div><!--/ row -->
	
</li>
	<script type="text/javascript">
	$(document).ready(function(){
	});
	
	</script>
		
