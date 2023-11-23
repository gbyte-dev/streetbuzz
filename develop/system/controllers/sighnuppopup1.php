<?php
	  $fbid	= $db2->fetch_field('SELECT id FROM users WHERE facebook_uid="'.$_POST['fbid'].'" LIMIT 1');
   if(!empty($fbid)){
	   echo "OK";
   }else{
	    $str = $_POST['fbuser'];
	   $fbstr=preg_replace('/\s+/', '', $str);
    

?>
<!-- Modal -->
    <div class="modal-dialog" id="graph-chat" >
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
               <?php echo '<img src="'.$C->SITE_URL.'static/images/logo.png" class="img-responsive" width="150">'; ?>
                </h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
				             <div class="col-lg-12 col-md-12 col-xs-12">
            <h5>We didn't receive below information.Please fillup to proceed.. </h5>
            </div>
                
                <div class="bor-padding10">
                <form role="form" action="<?php echo $C->SITE_URL?>sighnmallout"  method="POST" id="misshome">
				<input type="hidden" name="postid" value="<?php echo $_POST['postid'];?>" />
				<input type="hidden" name="pollid" value="<?php echo $_POST['pollid'];?>" />
				<input type="hidden" name="answerid" value="<?php echo $_POST['fbanswerid'];?>" />
				<input type="hidden" name="fbid" value="<?php echo $_POST['fbid'];?>" />
				<input type="hidden" name="facebookfirstname" value="<?php echo $_POST['facebookfirstname'];?>" />
				<input type="hidden" name="fbuser" value="<?php echo $fbstr;?>" />

                <?php if(empty($_POST['fbe'])){ ?>
				  <div class="form-group">
                      <input type="text" class="form-control form" placeholder="Email or Mobile number" id="malemailhone" name="fbemail"/>
					   <div id="malemailhoneerror" class="notifyjs-container" style="overflow: hidden; display: none;margin-top:-40px;">
						   <div class="notifyjs-bootstrap-base notifyjs-bootstrap-error">
								<span data-notify-text="" class="notifyjs-text" id="malvalid">Email or Mobile number is required</span>
							 </div>
						 </div>
						 
                  </div>
				<?php }else{ ?>
					   <input type="hidden" class="form-control form" placeholder="Email or Mobile number" id="malemailhone" name="fbemail" value="<?php echo $_POST['fbe'];?>"/>

<?php }?>
				  
                
                  
                <?php if(empty($_POST['fbdateofbirth'])){?>
                  <div class="form-group">
                  <label><strong>Birthday</strong></label><br />
                  <div class="row bor-padding-birthday">
                   <div class="col-md-4 col-xs-4">
                    <select class="form-control form" name="profile_birth_day" id="birthdayday">
		<?php for($i=1; $i<=31; $i++) {?>
		<option  value="<?php echo $i;?>" <?php if($i == intval(date('d'))){ echo 'selected'; }?>><?php echo $i;?></option>
		<?php } ?>
				
				</select>
				
                  </div>
                  <div class="col-md-4 col-xs-4">
                  <select class="form-control form" name="profile_birth_month" id="birthdaymonth">
	
	 <?php	for($j=1; $j<=12; $j++) { ?>

	<option value="<?php echo $j;?>" <?php if($j == intval(date('m'))){ echo 'selected'; }?>><?php echo strftime('%B', mktime(0,0,1,$j,1,2009)); ?></option>
	<?php } ?>
		</select>
		
                  </div>
				  
                  <div class="col-md-4 col-xs-4">
                   <select class="form-control form"name="profile_birth_year"  id="birthdayyear">

		<?php for($k=intval(date('Y')); $k>=1900; $k--) { ?>
	<option value="<?php echo $k;?>" <?php if($k == (intval(date('Y'))-20)){ echo 'selected';}?>><?php echo $k;?></option>
	<?php } ?>
		</select>
		
                  </div>
                  </div>
                  </div>
	<?php }else{ ?>
		<input type="hidden" name="fbdateofbirth" value="<?php echo $_POST['fbdateofbirth']; ?>">
<?php } ?>
					<?php if(empty($_POST['fbgender'])) {?>

                  <div class="form-group">
                      <p><input type="radio" value="male" name="fbgender" checked /> <strong>Male</strong> <input type="radio" value="female"  name="profile_gender" /> <strong>Female</strong></p>
                  </div>
					<?php }else{ ?>
						<input type="hidden" name="fbgender" value="<?php echo $_POST['fbgender']; ?>">

<?php }?>
                </form>
                </div>


				</div>
				<!-- Modal Footer -->
            <div class="modal-footer">
               <!-- <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Cancel
                </button>-->
                <button type="button" class="btn btn-warning" id="malvote">
                    VOTE
                </button>
            </div>
				
			</div>
	</div>
	</div>
<style>
.form {
    border: 1px solid #BFE0EC;
    color: #34495e;
    font-family: Helvetica, Arial, sans-serif, Helvetica, Arial, sans-serif;
    font-size: 14px;
    line-height: 1.467;
    /* height: 42px; */
    -webkit-appearance: none;
    border-radius: 6px;
    -webkit-box-shadow: none;
    box-shadow: none;
    -webkit-transition: border .25s linear, color .25s linear, background-color .25s linear;
    transition: border .25s linear, color .25s linear, background-color .25s linear;
}
.bor-padding {
	padding:0px 15px;
}
.bor-login-icon {
	margin: 0px 8px 5px -3px;
}
.bor-padding10 {
	padding:0px 10px;
}
.bor-padding-birthday { 
	padding: 0px 6px;
}
.btn-warning {
	width: 40%;
	font-weight: bold;
}
h5 {
	color: #0099d3;
	font-size: 16px;
}
.modal-body {
	padding-top: 0px;
}
.modal-footer {
	margin-top: 0px;
	text-align: left;
}
.modal-header {
	padding-bottom: 0px;
}
.form-group {
	margin-bottom:10px!important;
}
.alert-label {
	color: #0099d3 !important;
	font-size: 12px;
}
@media screen and (max-width: 480px) {
.btn-warning {
	width: 70%;
	font-weight: bold;
}
}
</style>
<script>
	$(document).ready(function(){
			$("#malvote").on("click", function(){

		
		var malemailhone = $("#malemailhone").val();
		if(malemailhone ==''){
			$("#malemailhoneerror").css("display","block");
			$("#malemailhoneerror").delay(1000).fadeOut(100);
			return false;
		}
		if($.isNumeric(malemailhone)){
			var moblen = malemailhone.length;
			if(moblen ==10){
				
			}else{
			$("#malemailhoneerror").css("display","block");
		    $("#malvalid").html("Please enter valid mobile number");

			$("#malemailhoneerror").delay(1000).fadeOut(400);
			return false;
				
			}
		}else{
			if (!ValidateEmail(malemailhone)) {
           $("#malemailhoneerror").css("display","block");
		   $("#malvalid").html("Please enter valid email");

			$("#malemailhoneerror").delay(1000).fadeOut(100);
			return false;
        }
        else {
        }
		}
		 function ValidateEmail(malemailhone) {
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(malemailhone);
    };
		
		
				
	$("#misshome").submit();
		
	});
	});	
</script>
<?php }  ?>
 

 