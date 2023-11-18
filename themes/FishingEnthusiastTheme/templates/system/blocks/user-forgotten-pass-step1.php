<html lang="en">

	<head>
		<title>StreetBuzz</title>
		<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo $C->SITE_URL;?>static/css/bootstrap.min.css">
    <script src="<?php echo $C->SITE_URL;?>assets/home/jquery3min.js"></script>
    <script src="<?php echo $C->SITE_URL;?>assets/home/bootstrap4min.js"></script>
    <link rel="stylesheet" href="<?php echo $C->SITE_URL;?>static/css/forgot_pass.css">
<style>
    /*.card{*/
    /*    box-shadow:1px 1px 1px 1px gray;*/
        
    /*}*/
</style>
  </head>

	<body class="fixed-header layout-c">

		<div class="flat_ui_wrap">

		   <div id="layout-container">

         <div id="page-container">  

           <div class="container-fluid">

<div class="row" >
    <div class="col-sm-3"></div>
    <div class="col-sm-6 " style="backgroun-color:#f4f7f8;font-size:18px;font-weight:bold">
        <div class="row" >
            <div class="card" style="margin-left:30px;margin-right:30px;">
                <div class="card-header" style="margin-top:28%;">
                    <h2 class="card-header" style="font-weight:bold">Don't worry please enter your Email </h2>
                </div>
                <div class="card-body" style="margin-top:15px">
                       <form action="" method="post">
                         
                         <?php if($D->error == TRUE){ ?> <div class="alert alert-danger" role="alert">
                                Please enter valid user data
                                     </div> <?php } ?>
													<span class="input_field">
														<input type="text" value="{%user_forgotten_email_value%}"  placeholder="Email User name" class="text_field form-control" name="email" id="email" style="font-size:15px"/>
													</span> 
													<span class="input_field">
														<input type="submit" value="Send OTP" class="btn submit_button " id="forgotsub" style="font-size:18px;color:white;background-color:#0096d1"/>
													</span>
							</form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3"></div>
</div>

             <!---- <div class="row">
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
								     
								        
                  <hr></hr>
                  <h2>Don't worry please enter your Email Id, your User name</h2>
                  
                     <form action="" method="post">
                          <h2>Don't worry please enter your Email Id, your User name</h2>
                         <?php if($D->error == TRUE){ ?> <div class="alert alert-danger" role="alert">
                                Please enter valid user data
                                     </div> <?php } ?>
													<span class="input_field">
														<input type="text" value="{%user_forgotten_email_value%}"  placeholder="Email User name" class="text_field" name="email" id="email" />
													</span>
													<span class="input_field">
														<input type="submit" value="Continue" class="btn btn-success submit_button" id="forgotsub" />
													</span>
											</form>
								     
								     
								     
                  
								 </div>

               </div>
             </div>--->

           </div>

         </div>

        </div>

     </div>
 <script>
 $("#email").keyup(function(){
      $("#email").css('border-color',"");
   $(".alert-danger").css("display","none");


 });
 $("#forgotsub").click(function(){
   var username =$("#email").val();
   if(username == "" ){
        $("#email").css('border',"1px solid");
        $("#email").css('border-color',"#FA8072");
        return false;

   }


 });
 </script>
