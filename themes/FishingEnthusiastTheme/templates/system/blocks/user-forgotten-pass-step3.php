<?php if(!empty($D->userid)){?>
<div class="margintopforgot">
	<h1 class="pagetitle"><?= $this->page->lang('how_do_you_text'); ?></h1>
		<h2><?= $this->page->lang('how_do_you_short'); 
		   
		?></h2>

	<form action="" method="POST">
		<input type="hidden" value="<?php echo $D->userid;?>" class="form-control" name="updateuserid"  data-status="focus">

		
	<?php 
	if(isset($D->email)){ 
	function mask_email($email) {
    /*
    Author: Fed
    Simple way of masking emails
    */

    /*$char_shown = 19;

    $mail_parts = explode("@", $email);
    $username = $mail_parts[0];
    $len = strlen( $username );

    if( $len <= $char_shown ){
        return implode("@", $mail_parts );  
    }

    //Logic: show asterisk in middle, but also show the last character before @
    $mail_parts[0] = substr( $username, 0 , $char_shown )
        . str_repeat("*", $len - $char_shown - 1 )
        . substr( $username, $len - $char_shown + 2 , 1  )
        ;
	

    return implode("@", $mail_parts
    */
    $em   = explode("@",$email);
    $name = implode(array_slice($em, 0, count($em)-1), '@');
    $len  = floor(strlen($name)/2);

    return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
}
$email= mask_email($D->email);

?>

	<input type="radio" name="userfinalemail" checked value="<?php echo $D->email;?>"> Email a link to <?php echo $email;?></input>
			<input type="hidden" value="<?php echo $D->email;?>" class="form-control" name="updateemail" id="email" data-status="focus">

		
	<?php }
	else{ ?>
	    $email= "sample@gmail.com
		<!--<label for="email">email</label>-->
		<!--<input type="text"  class="form-control" name="updateemail" id="email" data-status="focus">-->
		<input type="radio" name="userfinalemail" checked value="<?php echo $D->email;?>"> Email a link to <?php echo $email;?></input>
			<input type="hidden" value="<?php echo $D->email;?>" class="form-control" name="updateemail" id="email" data-status="focus">
	<?php }
	?>
		
		
		
		<div class="registration-buttons" style="margin-top: 8px;">
			<button type="submit" value="submit" name="submit" class="btn blue"><span><?= $this->page->lang('signinforg_form_submit'); ?></span></button>
			<div class="clear"></div>
		</div>
	</form>
</div>
<?php } ?>
<script src="<?php echo $C->SITE_URL;?>static/js/jquery.js?v=3.6.0"></script>

<style>
.margintopforgot {
	margin-top:225px;
}
</style>

<script>
$(document).ready(function() {
 $("body").css({ 'background-color': '#ffffff' });
     $("body").css('background-image', 'none');
$(".system-message error")
$(".margintopforgot").css("margin-top", "100px");
});
</script>
