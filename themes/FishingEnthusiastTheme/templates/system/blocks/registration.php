<div class="row">
<div class="col-md-3">
</div>
<div class="col-md-6">
	<h1 class="pagetitle"><?= $this->page->lang('signup_subtitle', array('#SITE_TITLE#' => $C->SITE_TITLE)) ?></h1> 
	
	{%login_form_additional_info%}
	
	<form action="" method="post" autocomplete="off">
		
		
		<? if($D->email_confirm):?>
			<label for="email"><?= $this->page->lang('signup_step2_form_email') ?></label>
			<input type="text" id="email" class="form-control" value="{%registration_email%}" disabled="disabled" data-status="focus" />
			<input type="hidden" name="email" value="{%registration_email%}" />
			<span class="hint"><!-- Please enter a valid email address. --></span>  
		<? else: ?>
			<label for="email"><?= $this->page->lang('signup_step2_form_email') ?></label>
			<input type="text" id="email" class="form-control" name="email" value="{%registration_email%}" data-status="focus" />
			<span class="hint"><!-- Please enter a valid email address. --></span>  
		<? endif; ?>
		
		<label for="fullname"><?= $this->page->lang('signup_step2_form_fullname') ?></label>
		<input type="text" class="form-control" id="fullname" name="fullname" value="{%registration_fullname%}" <? if(isset($D->email_confirm)):?>  data-status="focus" <?endif;?> />
		<input type="text" id="hmmm" name="realname" data-status="focus" value="" style="  position: absolute;  height: 0px;  line-height: 0px;  z-index: -99;  margin-top: -20px;" />
		<span class="hint"><!-- Limit is 20 alphanumeric characters. --></span>                    
		
		<label for="username"><?= $this->page->lang('signup_step2_form_username') ?></label>
		<input type="text" class="form-control" id="username" name="username" value="{%registration_username%}"  />
		<span class="hint"><!-- Choose the name that will be part of your own URL (…/username)	 --></span>
		
		<label for="password"><?= $this->page->lang('signup_step2_form_password') ?></label>
		<input type="password" id="password" class="form-control" name="password" value="{%registration_password%}" >
		<span class="hint"><!-- Choose a password (minimum 6 characters). --></span>                         

		<label for="password2"><?= $this->page->lang('signup_step2_form_password2') ?></label>
		<input type="password" id="password2" class="form-control" name="password2" value="{%registration_password2%}"  />
		<span class="hint"><!-- Re-type your password. --></span>
		
		<label for="captcha_word"><?= $this->page->lang('signup_step2_form_captcha') ?></label>
		
		<?php if(!$D->use_google_recaptcha): ?>
		<div class="captcha-fields">
			<input type="text" class="form-control" id="captcha_word" name="captcha_word" {%autofocus%} />
			<input type="hidden" value="{%captcha_key%}" name="captcha_key">
		</div>
		<?php endif; ?>
		
		<div class="captcha-image">
		<?php if($D->use_google_recaptcha): ?>
			{%captcha_image%}
		<?php else: ?>
			<img id="captcha" src="<?= $C->SITE_URL ?>captcha" alt="Captcha" />
		<?php endif; ?>
		</div>
			
		{%registration_terms_of_use%}
		
		<div class="registration-buttons">
			<button class="btn btn-lg btn-primary" type="submit" name="submit"><span><?= $this->page->lang('signup_step2_form_submit') ?></span></button>
			<div class="clear"></div>
		</div>
	
	</form>
	
	<?php if((!empty($C->FACEBOOK_API_ID) && (!empty($C->FACEBOOK_API_SECRET))) || (!empty($C->TWITTER_CONSUMER_SECRET) && (!empty($C->TWITTER_CONSUMER_KEY))) ): ?>
		<div class="links">
			<?php if(!empty($C->FACEBOOK_API_ID) && (!empty($C->FACEBOOK_API_SECRET))): ?>
			<a href="<?= $C->SITE_URL ?>signup/using:facebook" class="facebook-button"></a>
			<?php endif; ?>
			
			<?php if(!empty($C->TWITTER_CONSUMER_SECRET) && (!empty($C->TWITTER_CONSUMER_KEY))): ?>
			<a href="<?= $C->SITE_URL ?>signup/using:twitter" class="twitter-button login-box"></a>
			<?php endif; ?>
		</div>
		<div class="clear"></div>
	<?php endif; ?>
	</div>
<div class="col-md-3">
</div>
</div>