<html lang="en">

	<head>
		<title>StreetBuzz</title>
		<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="<?php echo $C->SITE_URL;?>static/css/bootstrap.min.css">
    <script src="<?php echo $C->SITE_URL;?>assets/home/jquery3min.js"></script>
    <script src="<?php echo $C->SITE_URL;?>assets/home/bootstrap4min.js"></script>
    <link rel="stylesheet" href="<?php echo $C->SITE_URL;?>static/css/forgot_pass.css">
  </head>

	<body class="fixed-header layout-c">

		<div class="flat_ui_wrap">

		   <div id="layout-container">

         <div id="page-container">

           <div class="container-fluid">

             <!---<div class="row">
               <div class="col-md-6 col-lg-5 forgot_left">
                 <div class="new_logo">
                   <img src="<?php echo $C->SITE_URL ?>static/images/SbLogo.png" alt="" />
                 </div>
                 <div class="left_content">
                   <h1>Hello!</h1>
                   <h3>Your Just Few Click away to know your city News and create your city News</h3>
                   <p>Your Just Few Click away to know your city News and create your city News</p>
                 </div>

               </div>
               <div class="col-md-6 col-lg-7 forgot_right">
					<div class="forgot_pass_form">
					    <div class="alert alert-danger" role="alert" style="display:none">
         <div id="errhtml" >New Password and Confirm Password should be same</div> 

    </div>
                      <form action="" method="post">
                          <h2>Enter your New Password</h2>
													<span class="input_field">
														<input type="password" placeholder="New Password" class="text_field password"  value="{%user_forgotten_pass1_value%}" name="pass1" id="pass1" />
													</span>
													<span class="input_field">
														<input type="password" placeholder="Conform Password" class="text_field password"  value="{%user_forgotten_pass2_value%}" name="pass2" id="pass2"  />
													</span>
													<span class="input_field submit">
														<input type="submit" value="Submit" class="submit_button" id="changesub" />
													</span>
											</form>
								 </div>

               </div>
             </div>-->
             
             
              <div class="row" style="margin-top:8%">
    <div class="col-sm-3"></div>
    <div class="col-sm-6" style="backgroun-color:#f4f7f8;font-size:18px;font-weight:bold">
        <div class="row" >
            <div class="card " style="margin-left:30px;margin-right:30px;">
                <div class="card-header" style="margin-top:28%;">
                    <h2 class="card-header" style="font-weight:bold"></h2>
                </div>
                <div class="card-body" style="margin-top:15px">
                       <div class="forgot_pass_form">
                             <h2 style="font-weight:bold;text-align:center">Please Enter Your New Password</h2>
								      <div class="alert alert-danger" role="alert" style="display:none">
                                         <div id="errhtml" >New Password and Confirm Password should be same</div> 
                                
                                      </div>
									<form action="" method="post">
                        <br>
													<span class="input_field">
														<input type="password" placeholder="New Password" class="text_field password form-control" style="font-size:15px" value="{%user_forgotten_pass1_value%}" name="pass1" id="pass1" />
													</span><br>
													<span class="input_field">
														<input type="password" placeholder="Conform Password" style="font-size:15px" class="text_field password form-control"  value="{%user_forgotten_pass2_value%}" name="pass2" id="pass2"  />
													</span><br>
													<span class="input_field submit">
														<input type="submit" value="Submit" style="font-size:18px;color:white;background-color:#0096d1;" class="submit_button btn" id="changesub" />
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
     $(".inptctrl").keyup(function(){
    $(".inptctrl").css('border-color',"");
      $(".alert-danger").css("display","none");

         
     });
     $("body").css('background-image', 'none');
     $("#changesub").click(function(){
         
         var pass1 =$("#pass1").val();
         if(pass1 == ""){
              $("#pass1").css('border',"1px solid");
             $("#pass1").css('border-color',"#FA8072");
             return false;
        }
        if(pass1.length < 5 ){
             $("#pass1").css('border',"1px solid");
             $("#pass1").css('border-color',"#FA8072");
              $(".alert-danger").css("display","block");
             $("#errhtml").html("New password lenght more than 5 Characters.");
             return false;
            
        }
         var pass2 =$("#pass2").val();
         if(pass2 == ""){
           $("#pass1").css('border-color',"");
           $(".alert-danger").css("display","none");
            $("#pass2").css('border',"1px solid");
             $("#pass2").css('border-color',"#FA8072");
             return false;
        }
        if(pass1 != pass2){
              $("#errhtml").html("New Password and Confirm Password should be same");
              $(".alert-danger").css("display","block");
             return false;
            
        }
       

     });

});
</script>
</html>
