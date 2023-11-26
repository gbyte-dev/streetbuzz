<div style="text-align:center"><input type="text" id="postid" style="height:6%" placeholder="Enter Post Id" ></div>
<div style="text-align:center"><input type="button" id="serach" value="Search" style="margin-top:10px;width:13%;height:6%" ></div>
 <div id="mes" style="text-align:center;display:none;" ><b>Please wait...</b></div>
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
		<script>
		$(document).ready(function() {
		    $("#serach").click(function(){
				$("#mes").css("display","block");
				$("#mes").html("<b>Please wait...</b>");
		        var postid =$("#postid").val();
				$.ajax({
			type: "POST",
			url: "viewpostlistdata.php",
			data: {postid:postid},
					
			success: 
			function(result){
				$("#mes").html(result);
			}
		});
   
} );
});
		</script>

