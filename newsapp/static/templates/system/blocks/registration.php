<div class="login-form">

	<h1 class="pagetitle"><?= $this->page->lang('signup_subtitle', array('#SITE_TITLE#' => $C->SITE_TITLE)) ?></h1> 
	
	{%login_form_additional_info%}
 	
	<form action="" method="post" autocomplete="off">
	
		<?php if($D->email_confirm){?>
			<label for="email"><?= $this->page->lang('signup_step2_form_email') ?></label>
			<input type="text" id="email" value="{%registration_email%}" disabled="disabled" data-status="focus" />
			<input type="hidden" name="email" value="{%registration_email%}" />
			<span class="hint"><!-- Please enter a valid email address. --></span>  
		<?php }else{ ?>
			<label for="email"><?= $this->page->lang('signup_step2_form_email') ?></label>
			<input type="text" id="email" name="email" value="{%registration_email%}" data-status="focus" />
			<span class="hint"><!-- Please enter a valid email address. --></span>  
		<?php } ?>
		
		<label for="fullname"><?= $this->page->lang('signup_step2_form_fullname') ?></label>
		<span data-tip="Limit is 20 alphanumeric characters.">
		<input type="text" id="fullname" name="fullname" value="{%registration_fullname%}" <?php if(isset($D->email_confirm)){?>  data-status="focus" <?php } ?> />
		</span>  
		<span class="hint"><!-- Limit is 20 alphanumeric characters. --></span>                  
		
		<label for="username"><?= $this->page->lang('signup_step2_form_username') ?></label>
		<span data-tip="Choose the name that will be part of your own URL">

		<input type="text" id="username" name="username" value="{%registration_username%}"  /></span>
		<span class="hint"> Choose the name that will be part of your own URL (…/username)	 </span>
		
		
		<label for="username"><?= $this->page->lang('phone_no') ?></label>
		<span data-tip="Please enter phone number.">

		<input type="text" id="phone" name="phone" value="{%phone_no%}"  /></span>
		<span class="hint"> Choose the name that will be part of your own URL (…/username)	</span>
		
		<label for="password"><?= $this->page->lang('signup_step2_form_password') ?></label>
		<span data-tip="Choose a password (minimum 6 characters).">
		<input type="password" id="password" name="password" value="{%registration_password%}" ></span>
		<span class="hint"><!-- Choose a password (minimum 6 characters). --></span>                         

		<label for="password2"><?= $this->page->lang('signup_step2_form_password2') ?></label>
		<span data-tip="Re-type your password..">
		<input type="password" id="password2" name="password2" value="{%registration_password2%}"  />
		</span>
		<span class="hint"><!-- Re-type your password. --></span>
		
		<label for="referdby"><?= $this->page->lang('Referred by') ?></label>
		<span data-tip="please enter Refered by.">
		<input type="text" id="referdby" name="referdby" value="{%referdby%}"  /></span>
		<span class="hint"><!-- Re-type your password. --></span>
		<label for="type"><?= $this->page->lang('type') ?></label>
		<select name="type_person" id="type_person" style="width: 256px;margin-bottom: 2px;">
		   <option value="person" <?php if(trim($D->type) == "person"){ echo "selected";} ?> >Person</option>
		   <option value="business" <?php if(trim($D->type)== "business"){ echo "selected";} ?> >Business</option>
		   <option value="brand" <?php if(trim($D->type)== "brand"){ echo "selected";} ?>>Brand</option>
		</select>


		<span class="hint"><!-- Re-type your password. --></span>
		<!--
		<label for="captcha_word"><?= $this->page->lang('signup_step2_form_captcha') ?></label>
		
		<?php if(!$D->use_google_recaptcha): ?>
		<div class="captcha-fields">
			<input type="text" id="captcha_word" name="captcha_word" {%autofocus%} />
			<input type="hidden" value="{%captcha_key%}" name="captcha_key">
		</div>
		<?php endif; ?>
		
		<div class="captcha-image">
		<?php if($D->use_google_recaptcha): ?>
			{%captcha_image%}
		<?php else: ?>
			<img id="captcha" src="<?= $C->SITE_URL ?>captcha" alt="Captcha" />
		<?php endif; ?>
		</div>-->
			
		{%registration_terms_of_use%}
		
		<div class="registration-buttons">
			<button class="login btn blue" type="submit" name="submit"><span><?= $this->page->lang('signup_step2_form_submit') ?></span></button>
			<a class="login btn blue"  href="<?= $C->SITE_URL ?>signin" ><span><?= $this->page->lang('login') ?></span></a>

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
