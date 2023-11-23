<div class="user-status-field htmlarea">
<div class='commentpoll'>
</div>
	<div class="textarea-wrap comment">
		<textarea  name="message" tabindex="1" data-placeholder="<?= $this->page->lang('comment_text_box_shate_txt') ?> {%in_group%}..."><?= $this->page->lang('activity_text_box_shate_txt') ?> {%in_group%}...dsdadds</textarea>
		<div class="textarea-highlighter"><span></span></div>
	</div>
</div>
<div class="htmlarea-ac">
	<div class="htmlarea-ac-container" ></div>
	
</div>



<!--<script src="<?php echo $C->SITE_URL;?>static/js/textareaeditor.js?v=3.6.0"></script>-->
<script>
 $(document).ready(function(){
	
	$('.footer0').css('display','none');
	$('.comments0').css('display','none');

	 $("#event").click(function(){
$(".characters-counter").css('display','none');
		 $("#poll").css("display","block");
		 		 $("#post").hide();

		 $("#poll").load("<?php echo $C->SITE_URL;?>/<?php echo $this->user->info->username;?>/tab:events/action:add_event");
	 
	 });

	 $("#careatepoll").click(function(){
	 	//alert('ok');
	 	$(".characters-counter").css('display','none');
	 	$(".status-btn").css('display','none');
		 $("#post").hide();
		 $("#poll").css("display","block");
		 $("#poll").load("<?php echo $C->SITE_URL;?>plugin/poll/admin?action=users");

	 });
	  $(".pollcreate").click(function(){
  		var id=$(this).attr('id');
  		alert(id);
	  	 $('.comment-post'+id).hide();
		 $('.comment'+id).hide();
		 $(".commentpoll"+id).css("display","block");
		 $(".commentpoll"+id).load("<?php echo $C->SITE_URL;?>plugin/poll/admin?action=users&option=commentpoll");

	 });


	 
 });

</script>
<script type="text/javascript">
		$(document).ready(function(){

			$(".st_text").hide();
			$(".user-status-field").show();
			var area1, area2;
 
  function toggleArea1() {
        if(!area1) {
                area1 = new nicEditor({fullPanel : true}).panelInstance('myArea1',{hasPanel : true});
        } else {
                area1.removeInstance('myArea1');
                area1 = null;
        }
  }
 
  function addArea2() {
        area2 = new nicEditor({fullPanel : true}).panelInstance('myArea2');
  }
  function removeArea2() {
        area2.removeInstance('myArea2');
  }
 
  bkLib.onDomLoaded(function() { toggleArea1(); });
			
		});
		
        //var a  =$(".nicEdit-main").html();

$('a.toggler').click(function(){

        $(this).toggleClass('off');

		var dfggfg  = $("#switch").val();
		if(dfggfg =='off'){
			var switch1 ="ON";
			$(".st_text").show();
			$(".user-status-field").hide();
			
		}else{
			var switch1 ="off";
			$(".st_text").hide();
			$(".user-status-field").show();
			
		}
		$("#switch").val(switch1);
		
    });

		</script>
<script>
	$('#grp').click(function(){
		 $('#grtxt').show();
		  $('#share').val('group');
		 
	 });
	$('#user').click(function(){
		 
		  $('#share').val('user');
		 $('#urtxt').show();
	 });
	 $("#location").click(function()
</script>

<script>
$(document).ready(function() {
	  $('[data-toggle="tooltip"]').tooltip();
  $("#location").click(function() {

    el = $(this);
    $.get("<?php echo $C->SITE_URL;?>location", function(response) {
      el.unbind('click').popover({
        content: response,
        title: 'Dynamic response!',
        placement: 'bottom', 
        html: true,
        delay: {show: 500, hide: 100}
      }).popover('show');
    });
  });
 });
</script>


