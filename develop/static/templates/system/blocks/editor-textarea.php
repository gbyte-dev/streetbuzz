<div id="post">
<div class="off"><a href="#" class="toggler off">&nbsp;</a></div>
<div class="user-status-field htmlarea">
	<div class="textarea-wrap">
	<textarea id="test1"></textarea>

		<textarea class="editpost" name="message" tabindex="1"  data-placeholder="<?= $this->page->lang('activity_text_box_shate_txt') ?> {%in_group%}..."><?= $this->page->lang('activity_text_box_shate_txt') ?> {%in_group%}...</textarea>
		
		<div class="textarea-highlighter"><span></span></div>
	</div>
</div>
  <div class="st_text htmlarea" id="st_text">
    <textarea style="height: 100px; width:244px" id="myArea1"  >
</textarea><br />
  </div>
  <input type="hidden" value="off" id="switch">
  </div>
  <div id="poll" style="display:none;">
  </div>
  <div class="htmlarea-ac">
	<div class="htmlarea-ac-container"></div>
	
</div>

	
<style>

</style>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="<?php echo $C->SITE_URL;?>static/js/textareaeditor.js?v=3.6.0"></script>




<script type="text/javascript">
$("#careatepoll").click(function(){
	 	//alert('ok');
	 	
	 	$(".status-btn").css('display','none');
		 $("#post").hide();
		 $("#poll").css("display","block");
		 $("#poll").load("<?php echo $C->SITE_URL;?>plugin/poll/admin?action=users");

	 });
	  $("#pollcreate").click(function(){
	  	
	  	 $('.comment-post').hide();
		 $('.comment').hide();
		 $(".commentpoll").css("display","block");
		 $(".commentpoll").load("<?php echo $C->SITE_URL;?>plugin/poll/admin?action=users&option=commentpoll");

	 });

		$(document).ready(function(){
			$("#st_text").keyup(function(){
				var counter = $(".characters-counter").attr("data-value");
				   	if (counter.length > 0) {
						
    		
    		counterValue = $(".characters-counter").attr("data-value");
    		charactersCount = $(".nicEdit-main").html().length;
    		charactersLeft = counterValue - charactersCount;
    		//console.log(charactersCount);
    		$("#character").text(charactersLeft);
    		if (charactersCount > counterValue) {
    			//console.log('limit');
    			//return false;
    			editorString = $(".nicEdit-main").html();
    			editorString = editorString.substring(0,counterValue);
				var end =parseInt($(".nicEdit-main").html().length)+parseInt(1);
				editorString = $(".nicEdit-main").html();
				editorStringadditional = editorString.substring(counterValue,end);
				var abc     = editorString + '<span style="color:red">'+editorStringadditional+'</span>';
				 
			
				 
				 
				
				$(".post-btn").prop("disabled",true);
				$(".post-btn").css("opacity","0.5");
				$(".characters-counteref").css("color","red");
				       
		
				
    			
    			
    			
    		}else{
				if (charactersCount <= counterValue) {
					$(".post-btn").css("opacity","");
					$(".post-btn").prop("disabled",false);
					$(".characters-counteref").css("color","");
			
				
    			
				}
				
			}
    		
    	}

			});
			
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
			$("#characterff").css("display","none");
			$("#character").css("display","block");
			$(".st_text").show();
			$(".user-status-field").hide();
			
		}else{
			var switch1 ="off";
			$("#characterff").css("display","block");
			$("#character").css("display","none");
			$(".st_text").hide();
			$(".user-status-field").show();
			
		}
		$("#switch").val(switch1);
		
    });
		</script>
		
  <script type="text/javascript">
 $(document).ready(function(){
	 $("#event").click(function(){
		 $(".status-btn").hide();
		 $("#poll").css("display","block");
		 		 $("#post").hide();

		// $("#poll").load("<?php echo $C->SITE_URL;?>/<?php echo $this->user->info->username;?>/tab:events/action:add_event");
		 //$("#poll").load("http://localhost/sharetronix/add_event_new");



		 
	 });
	
 });
  </script>
<script type="text/javascript">
$(document).ready(function(){
	$(".accept").click(function(){
		var accept =$(this).val();
		var acceptarr =accept.split('-');
		var attach    ="attach-"+acceptarr[0];
		var attchmentid  =$("#"+attach).val();
		if(acceptarr[1] ==1){
		var str ="Are you sure want to accept the event !";
		}
		if(acceptarr[1] ==3){
			var str ="Are you sure want to   Reject the event !";
			
		}
		var r = confirm(str);

		if(r == true){
      $.ajax({
		 
		  method:"POST",
		  data:{postid:acceptarr[0],status:acceptarr[1],attachid:attchmentid},
		   url:"<?php echo $C->SITE_URL;?>/dashboard",

		  success:function(response){
			  $("#acc-"+acceptarr[0]).hide();
			  if(acceptarr[1] == 1){
				  $("#accept-"+acceptarr[0]).css("display","block");
				  
			  }
			  if(acceptarr[1] == 3){
				  $("#reject-"+acceptarr[0]).css("display","block");
				  
			  }
			  
		  }
		  
	  });	
		}	  
		
	});
	$(".download").click(function(){
		var pid = $(this).attr('rel');
		 $.ajax({
		 
		  method:"POST",
		  data:{pid:pid},
		   url:"<?php echo $C->SITE_URL;?>/dashboard",

		  success:function(response){
			  
			  
		  }
		  
	  });	
	});
});

</script>

<script type="text/javascript">
function changeurl(a,b)
{
	//alert(b);
	$('#optionerror'+b).hide();
	var id=$(".option"+a).attr("id");
	var iurl=$("#suboption"+id).attr("href");
	$("#suboption"+id).attr( 'href', '<?php echo $C->SITE_URL;?>plugin/poll/admin?action=answer&poll_id='+id+'&answerid='+a+'');   
	// alert(iurl);
	// alert(id);
}
</script>

		
		

