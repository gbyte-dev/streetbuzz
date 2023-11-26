<style>
.search-drop{
    right:46px !important;
    left: auto !important;
}
</style>

<div class="row top30">

<!-- Start: Main Navigation -->
  <div class="col-lg-6 col-md-6 header_nav main_sb_menu">
		<div class="navbar-collapse collapse zeropadding">
		    <?php
		    	if( (strpos($_SERVER["REQUEST_URI"], 'view/post:') !== false) && (!$this->user->is_logged)  ){
		    	   
		    	    
		    	}else{
		    
		    ?>
		 {%main_navigation%} <?php }  ?>
		</div><!--/.nav-collapse -->
   </div>
<!--/ End: Main Navigation -->

<?php 

if( $this->user->is_logged ) { 
    $largenum =3;
    }else{
         $largenum =3;
    }
    ?>


<!-- Start: Search Box -->
<div class="col-lg-<?php echo $largenum;?> col-md-3 header_search">{%header_content_searcharea%}</div>
<!--/ End: Search Box -->



<!-- Start: User Profile -->
  <div class="col-lg-3 col-md-3 header_profile "><?php if( $this->user->is_logged ) { ?>
	<div id="user-navigation" class="userprofile">	
		
<!--		
<div class="user-notifications" style="float:right; margin: -2px 0px 0px 5px;">
<a href="#" data-toggle="tooltip" data-placement="right" title="Buzz">
<img src="<?= $C->SITE_URL ?>/static/images/icon-buzz.png" class="img-responsive">
</a>
</div>
-->

<!--	<div class="user-notifications" style="float:right;margin-right:5px;">
			<div class="dropdown">
				<a class="notifications-counter menu-btn {%header_notification_counter_full%}" id="ctl00_uxHeader_hlNotifications"><span class="bkg" id="ctl00_uxHeader_lblTotalCount">{%header_notification_counter%}</span></a>
				<ul class="menu-options">
					
                   	<li><a href="<?= $C->SITE_URL ?>privatemessages"><?= $this->page->lang('global_header_pm_name') ?><span class="items-counts" id="ctl00_uxHeader_lblPrivateCount" {%header_notification_privmsg_visibility%}>{%header_notification_privmsg_cnt%}</span></a></li>            
					<li><a href="<?= $C->SITE_URL ?>notifications"><?= $this->page->lang('global_header_notifs_name') ?><span class="items-counts" id="ctl00_uxHeader_lblNotifCount" {%header_notification_notifs_visibility%}>{%header_notification_notifs_cnt%}</span></a></li>
				</ul>
			</div>
        </div> -->


		<div class="user-options dropdown"  style="float:right; border:1px solid #ccc!important; border-radius:0">
			<a href="<?= $C->SITE_URL ?><?= $this->user->info->username ?>" class="arrow menu-btn"><span class="plain-avatar"><img style="border-radius:0;" src="<?= $C->STORAGE_URL ?>avatars/thumbs3/<?= $this->user->info->avatar ?>" alt="" width="30" height="30" />&#9660;</span></a>
		
			<ul class="menu-options">
				
				<li><a href="<?= $C->SITE_URL ?>settings"><span><?= $this->page->lang('hdr_nav_settings') ?></span></a></li>
				<?php if( $this->user->is_logged && $this->user->info->is_network_admin == 1 ) { ?>
			
				<li><a href="<?= $C->SITE_URL ?>admin" class="item-btn <?= $this->page->request[0]=='admin'?'active':'' ?>"><span><?= $this->page->lang('hdr_nav_admin') ?></span></a></li>
				<?php } ?>	
				<li><a href="<?= $C->SITE_URL ?>signout"><span><?= $this->page->lang('hdr_nav_signout') ?></span></a></li>
			</ul>
		</div>



		<a  style="float:right; margin-right:5px; margin-top: 5px; font-size: 12px;" href="<?= $C->SITE_URL ?><?= $this->user->info->username ?>" class="username"><?= $this->user->getCommunityName() ?></a>
		
        
        <div class="clear"></div>
		
	</div>		
<?php } else {
		if( strpos($_SERVER["REQUEST_URI"], 'view/post:') !== false ){ ?>
		<ul class="signup-navigation main-navigation">
		<li><a  onclick="sighn(<?php echo $D->postid ?>)" href="#"  ><?= $this->page->lang('hdr_nav_signin') ?></a></li>
		<li><a  onclick="sighn(<?php echo $D->postid ?>)" href="#" ><?= $this->page->lang('hdr_nav_signup') ?></a></li>
	</ul>
		<?php }else{

	?>
	<ul class="signup-navigation main-navigation">
		<li><a class="" href="<?= $C->SITE_URL ?>home"><?= $this->page->lang('hdr_nav_signin') ?></a></li>
		<li><a class="" href="<?= $C->SITE_URL ?>home"><?= $this->page->lang('hdr_nav_signup') ?></a></li>
	</ul>
		<?php } } ?>
</div>
<!--/ End: User Profile -->

</div>
<?php 

if( !$this->user->is_logged ) { ?>

<?php }?>




