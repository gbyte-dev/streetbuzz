   <!-- Main content -->
      <div class="container contentbox">
      <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
      <h1>Welcome to Street Buzz</h1>
      <h3>Connect, Share and Predict market events as you see</h3>
<p>&nbsp;</p>
<h4>Follow experts-in-action on shares, bonds, commodities, currencies, and real estate</h4>
<p>&nbsp;</p>
<h4>Buzz on market events and their impact on prices</h4>
<p>&nbsp;</p>
<h4>Predict price movements, become experts and earn plus more...</h4>
      </div>
      
<!--/ Right Box -->
<div class="col-xs-12 col-sm-8 col-md-4 col-lg-4 login-signup pull-right">
<!--/ Login -->
<div class="box-white row">
<form action="{%home_form_action%}" method="post">
<input type="text" class="form-control form" placeholder="Phone email or Username" id="email" name="email" data-status="focus" value="<?php if(isset($_COOKIE['username'])){ echo $_COOKIE['username'];} ?>">
<div class="col-xs-8 col-sm-10 col-md-9 col-lg-9 login_pwd_split">
<input type="password" class="form-control form" placeholder="Password" id="password" name="password" value="<?php if(isset($_COOKIE['userpassword'])){ echo $_COOKIE['userpassword'];} ?>"></div>
<div class="btn_login_right"><input type="submit" class="btn btn-info" value="Log in"></div>
<div class="pull-left"><input type="checkbox" name="rememberme"  id="rememberme"  value="1" <?php if(isset($_COOKIE['username'])){  echo "checked";} ?> >
<span class="grey_text">Remember me <a href="<?= $C->SITE_URL ?>signin/forgotten" class="link_blue_text"><?= $this->page->lang('signin_form_forgotten'); ?></a></div>
</form>
</div>
<!--/ Signup -->
<div class="box-white row">
<p class="signup_title_bold">New to StreetBuzz? <a href="#" class="link_blue_text">Sign Up</a></p>
<form action="<?php echo $C->SITE_URL?>/home1" method="POST">
<input type="text" class="form-control form" placeholder="Full name" name="fullname" required>
<input type="email" class="form-control form" placeholder="Email" required name="strret_useremail">
<input type="password" class="form-control form" placeholder="Password" required name="street_userpassword">
<p class="pull-right"><input type="submit" class="btn btn-warning" value="Signup for SB"></p>
</form>
</div>      
</div>  


</div>

    </div> <!-- /container main -->



<!-- start - footer -->
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer-box" align="center">
<ul>
<li><a href="#">About</a></li>
<li><a href="#">Help</a></li>
<li><a href="#">Blog</a></li>
<li><a href="#">Status</a></li>
<li><a href="#">Job</a></li>
<li><a href="#">Privacy</a></li>
<li><a href="#">Cookies</a></li>
<li><a href="#">Adsinfo</a></li>
<li><a href="#">Brand</a></li>
<li><a href="#">Advertise</a></li>
<li><a href="#">Business</a></li>
<li><a href="#">Media</a></li>
<li><a href="#">Developers</a></li>
<li><a href="#">Directory</a></li>
</ul>
</div> 
<!-- end - footer -->

</div>
</body>
</html>