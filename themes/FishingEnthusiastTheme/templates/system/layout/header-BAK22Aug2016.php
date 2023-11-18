<!DOCTYPE html>
<html lang="{%html_lang_abbrv%}">
	
	<head>
		<title>{%page_title%}</title>
		{%header_data%}

		
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, 
		maximum-scale=1.0, user-scalable=no"/>


	
<?php
   
  if( preg_match( "#iPhone#i", $_SERVER['HTTP_USER_AGENT'] )  || preg_match( "#iPod#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Android#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Phone#i", $_SERVER['HTTP_USER_AGENT'] )) {
		print_r("<link rel='stylesheet' href='".$C->SITE_URL."/themes/FishingEnthusiastTheme/mobile/mobile.css' type='text/css' media='all' />");
		print_r("<script type='text/javascript' src='".$C->SITE_URL."/themes/FishingEnthusiastTheme/mobile/mobile.js'></script>");
		}      
        
    	 
?>

	</head>
	<body class="fixed-header layout-{%header_page_layout%}">
		
		<div class="flat_ui_wrap">
		  
		
		<div id="layout-container">


	<!-- START : Desktop Header -->
	<div id="header" class="navbar navbar-default navbar-fixed-top zeropadding hidden-xs hidden-sm" role="navigation">

	 <div class="container-fluid">    
	   

   <div class="col-lg-12 col-md-12 zeropadding">
		  <div class="col-lg-3 col-md-3"> 
		  <div class="navbar-header">
          <div class="hidden-lg hidden-md second_menu_holder">
		  <span class="glyphicon glyphicon-th toggle_left_menu"></span>
		  </div>
		  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
         {%logo_data%}
        </div>
		</div>

		  <div class="col-lg-9 col-md-9"> 
			<div class="navbar-collapse collapse">
				{%header_content%}
			</div><!--/.nav-collapse -->
			</div>
		</div>
	


    </div>
    </div>
<!--/ END : Desktop Header -->




<!-- START : Mobile Screen Header -->
<div id="header" class="navbar navbar-default navbar-fixed-top hidden-md hidden-lg"  role="navigation">


<div class="col-xs-12 col-sm-12 mob-header zeropadding">

<div class="col-xs-2 col-sm-2">
<a href="<?php echo $C->SITE_URL;?>dashboard"><span class="glyphicon glyphicon-home mob-header-menu-icon"></span></a>
</div>
<div class="col-xs-2 col-sm-2">
<a href="<?php echo $C->SITE_URL;?>notification/tab:@me"><span class="glyphicon glyphicon-briefcase mob-header-menu-icon"></span></a>
</div>
<div class="col-xs-2 col-sm-2">
<a href="<?php echo $C->SITE_URL;?>predictions"><span class="glyphicon glyphicon-signal mob-header-menu-icon"></span></a>
</div>
<div class="col-xs-2 col-sm-2">
<a href="<?php echo $C->SITE_URL;?>privatemessages"><span class="glyphicon glyphicon-comment mob-header-menu-icon"></span></a>
</div>

<div class="col-xs-2 col-sm-2">
<a href="#"><span class="glyphicon glyphicon-search mob-header-menu-icon"></span></a>
</div>
<div class="col-xs-2 col-sm-2">
			
			<div class="user-options dropdown" style="float:right;">
			<a href="<?= $C->SITE_URL ?>janeeshk" class="arrow menu-btn"><span class="plain-avatar"><img src="<?= $C->STORAGE_URL ?>avatars/thumbs3/<?= $this->user->info->avatar ?>" alt="" width="30" height="30" /></a></span>
		
			<ul class="menu-options">
				<li><a href="<?= $C->SITE_URL ?>settings/profile"><span>Profile information</span></a></li>

				<li><a href="<?= $C->SITE_URL ?>settings/avatar"><span>Profile picture</span></a></li>

				<li><a href="<?= $C->SITE_URL ?>members/tab:ifollow"><span>Who to follow</span></a></li>

				<li><a href="<?= $C->SITE_URL ?>settings"><span>Settings</span></a></li>

				<li><a href="#"><span>Help</span></a></li>
					
				<li><a href="<?= $C->SITE_URL ?>ssignout"><span>Sign out</span></a></li>
			</ul>
		</div>

</div>

</div>


</div>
<!--/ END : Mobile Screen Header -->




				
	<div id="page-container">