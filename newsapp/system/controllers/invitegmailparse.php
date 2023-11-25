<?PHP
 $res = $_POST['data']['feed']['entry'];
$userid =$_POST['userid'];


foreach($res as $reskeys=>$resvals){ 
 $result = $resvals['gd$email'][0]['address'];
$db2->query('SELECT u.id FROM users as u WHERE u.email="'.$db2->e($result).'" LIMIT 1');
  $obj = $db2->fetch_object();
  if(!empty($obj)){
     $streetuser[] = $result;
  
  }else{
    $unstreetuser[] = $result;
  
  }
$totalcnt = count($unstreetuser);


}
?>
<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
		       
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 main-content-box">
<!--/ Signup -->
<div class="row box-white-inner">

<div class="reg-title">Gmail Contacts</div>

<div class="col-md-12 col-lg-12 col-xs-12 reg-desc-big">
<p>Select your friends to invite.</p>
</div>



<div class="col-md-12 col-lg-12 col-xs-12 prof-main-box">



<!-- START : List gmail data -->
<div class="col-md-12 col-lg-12 col-xs-12 scroll-bar">
<div class="panel panel-primary">
<div class="panel-body scroll-limit">

<ul class="list-unstyled list-emails">

<?php
if(!empty($unstreetuser )){ ?>
	<li class="selactallcls"><span class="chkboxCategory"><input type="checkbox" class="all" id="chkboxCategorysball"  /><label for="chkboxCategorysball"></label>
</span><strong> <span id="selall" >Select All </span> </strong> </li>
		
	
	<?php 
foreach($unstreetuser as $keys=>$emails){ ?>
	
<li><span class="chkboxCategory"><input type="checkbox"class="myCheckbox" name="unuser[]" value="<?php echo $emails;?>" id="chkboxCategory<?php echo $keys;?>"  /><label for="chkboxCategory<?php echo $keys;?>"></label>
</span> <?php echo $emails;?></li>
	<?php } } ?>
	
</ul>

</div>
</div>
</div>
<!--/ END : List gmail data -->




<div id="errormsg" class="errormsg-invite" style="display:none;">Please select atleaset one email</div>

<div class="col-md-12 col-lg-12 col-xs-12">
<button class="btn btn-primary btn-lg" id="invitefrn"> Invite</button>
</div>

</div><!--/ prof-main-box -->




<div id="gmail">

</div>
<div id="outlook">

</div>


</div>      
</div>
<!--/ End Main Content -->  

      </div>
		 <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
      </div>
    </div>

  </div>
<input type="hidden" id="sel" value="0">
  
<style>
.modal-body {
	padding:0!important;
}
ul.list-emails {
width: 100%!important
}
ul.list-emails li {
padding: 10px 0px;
border-bottom: 1px solid #dadada;
text-align: left;
}

@media screen and (max-width: 480px) {
.main-content-box {
  margin-top:0px;
} 
ul.list-emails li {
padding: 10px 0px;
border-bottom: 1px solid #dadada;
text-align: left;
}
.chkboxCategory label {
  top: -3px!important;
}
}
	  
  /* SQUARED THREE */
.chkboxCategory {
  width: 20px;  
  margin: 0px auto;
  position: relative;
}

.chkboxCategory label {
  cursor: pointer;
  position: absolute;
  width: 20px;
  height: 20px;
  top: 0;
  left: -4px;
  border-radius: 4px;

  -webkit-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,.4);
  -moz-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,.4);
  box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,.4);

  background: -webkit-linear-gradient(top, #ec971f 0%, #d58512 100%);
  background: -moz-linear-gradient(top, #ec971f 0%, #d58512 100%);
  background: -o-linear-gradient(top, #ec971f 0%, #d58512 100%);
  background: -ms-linear-gradient(top, #ec971f 0%, #d58512 100%);
  background: linear-gradient(top, #ec971f 0%, #d58512 100%);
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#222', endColorstr='#45484d',GradientType=0 );
}

.chkboxCategory label:after {
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
  filter: alpha(opacity=0);
  opacity: 0;
  content: '';
  position: absolute;
  width: 11px;
    height: 7px;
    background: transparent;
    top: 5px;
    left: 4px;
  border: 3px solid #fcfff4;
  border-top: none;
  border-right: none;

  -webkit-transform: rotate(-45deg);
  -moz-transform: rotate(-45deg);
  -o-transform: rotate(-45deg);
  -ms-transform: rotate(-45deg);
  transform: rotate(-45deg);
}

.chkboxCategory label:hover::after {
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=30)";
  filter: alpha(opacity=30);
  opacity: 0;
}

.chkboxCategory input[type=checkbox]:checked + label:after {
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
  filter: alpha(opacity=100);
  opacity: 1;
}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		$(".myCheckbox").click(function(){
			var chelen = $('.myCheckbox:checked').length;
			var totalcnt ="<?php echo $totalcnt;?>";
			if(totalcnt == chelen){
				$("#selall").html("Deselect all");
				$(".all").prop("checked",true);
				 $("#sel").val(1);
			}else{
				$("#selall").html("Select all");
				$(".all").prop("checked",false);
				 $("#sel").val(0);
			}
			
		});
		$("#invitefrn").click(function(){

			var allVals = [];
			$('.myCheckbox:checked').each(function() {
       allVals.push($(this).val());
		//$("#errormsg").fadeOut();

				
     });
			if(allVals ==""){
				$("#errormsg").css("display","block");
			$("#errormsg").delay( 1200 ).fadeOut(500);
				return false;
			}
			var userid ="<?php echo $userid;?>";
				$.ajax({
					async: true, 
		           cache: false,
					dataType : "html",
					type:"POST",
					data:{userid:userid,allVals:allVals},
					url:"<?php  echo $C->SITE_URL;?>gmailregisterfollow",

					success:function(msg){
						$("#errormsg").css({"color" : "green", "display" : "block"}).html("Your invite has been sent!");
							setTimeout(function(){
								$(".sign-in-gmail").prop("disabled",true);
                               $("#gmail").modal('toggle');
                               }, 2000);
						
						
                      
						
						
						
					}
				});

			
				

		});
		$(".all").click(function(){
			$("#selall").html("Deselect all");
			
			var sel = $("#sel").val();
			if(sel ==0){
				$('.myCheckbox').prop('checked', true);
			  $("#sel").val(1);

			}else{
			$("#selall").html("Select all");
			$('.myCheckbox').prop('checked', false);
				  $("#sel").val(0);
			}

		});
	});
		
	</script>