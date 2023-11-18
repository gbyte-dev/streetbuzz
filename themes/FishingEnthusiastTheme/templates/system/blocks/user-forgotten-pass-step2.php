<html lang="en">

	<head>
		<title>StreetBuzz</title>
		<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="<?php echo $C->SITE_URL;?>static/css/bootstrap.min.css">
    <script src="<?php echo $C->SITE_URL;?>assets/home/jquery3min.js"></script>
    <script src="<?php echo $C->SITE_URL;?>assets/home/bootstrap4min.js"></script>
    <link rel="stylesheet" href="<?php echo $C->SITE_URL;?>static/css/forgot_pass.css">
		<script type="text/javascript">
		// OTP VALIDATION
     /* $(document).ready(function(){
		    $('.digit-group').find('input').each(function() {
          $(this).attr('maxlength', 6);
					$(".inputs").keyup(function () {
            if (this.value.length == this.maxLength) {
               $(this).next('.inputs').focus();
            }
          });
        });
     });*/
	</script>
  </head>

	<body class="fixed-header layout-c">

		<div class="flat_ui_wrap">

		   <div id="layout-container">

         <div id="page-container">

           <div class="container-fluid">
               
               
    <div class="row" style="margin-top:8%">
    <div class="col-sm-3"></div>
    <div class="col-sm-6 " style="backgroun-color:#f4f7f8;font-size:18px;font-weight:bold">
        <div class="row" >
            <div class="card " style="margin-left:30px;margin-right:30px;">
                <div class="card-header" style="margin-top:28%;">
                    <h2 class="card-header" style="font-weight:bold">Enter the OTP sent to your <br/> Email </h2>
                </div>
                <div class="card-body" style="margin-top:15px">
                       <div class="forgot_pass_form">
								      <div class="alert alert-danger" role="alert" style="display:none;">
                                Please Enter Valid Otp Code
                                     </div>
									 <form method="post" class="digit-group" data-group-name="digits" data-autosubmit="false" autocomplete="off" action="" id="forgotform">
									     <input type="hidden" style="font-size:15px" name="otpcheck" value="1">
											
											 <span class="input_field otp">
												 <input type="text" style="font-size:15px" id="digit-1" class="form-control inputs" placeholder="Enter OTP" maxlength="6" autofocus/>
												 <!--input type="text" class="inputs" id="digit-2" maxlength="1" />
												 <input type="text" class="inputs" id="digit-3"  maxlength="1" />
												 <input type="text" class="inputs" id="digit-4"  maxlength="1" />
											 </span>-->
											 
											 <!--<span class="input_field resend">-->
													<!--If you didn't receive a OTP!-->
													<?php
												//	echo $url= //$_SERVER['REQUEST_URI'];
												//	die();
													?>
													<!--<a href="" value="resend" >&nbsp; Resend</a>-->
											 <!--</span>-->
											 
											 <span class="input_field">
												 <input type="submit" style="font-size:18px;color:white;background-color:#0096d1;" value="Verify" class="btn submit_button" id="otpsubmit" />
											 </span>
											
										
									 </form>
								 </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3"></div>
</div>
               
               
               

         

           </div>

         </div>

        </div>

     </div>

   </body>
   <script>
$(document).ready(function() {

//	 $("body").css({ 'background-color': '#ffffff' });
     $(".inputs").keyup(function(){
     //$(".alert-danger").css("display","none");
     });
     $("body").css('background-image', 'none');
     $("#otpsubmit").click(function(){
         var digit1 =$("#digit-1").val();
        // var digit2 =$("#digit-2").val();
         //var digit3 =$("#digit-3").val();
         //var digit4 =$("#digit-4").val();
         if(digit1 == ""){
             $("#digit-1").focus(); return false;
         }
          /*if(digit2 == ""){
             $("#digit-2").focus(); return false;
         }
          if(digit3 == ""){
             $("#digit-3").focus(); return false;
         }
          if(digit4 == ""){
             $("#digit-4").focus(); return false;
         }*/
         var otp = digit1;//+digit2+digit3+digit4
       

         //var otp =$("#otp").val();
         if(otp == ""){
            $("#digit-1").focus(); return false;
             return false;
        }
        var otpcode ="<?php echo $D->otp_code; ?>";
        if(otpcode == otp ){

            $("#forgotform").submit();

        }else{
            $(".alert-danger").css("display","block");
             return false;

        }

     });

});
</script>
</html>
