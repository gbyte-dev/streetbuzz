<!DOCTYPE html>
<html lang="{%html_lang_abbrv%}">

	<head>
		 <?php 
		 
global   $D;
$posttype="public";$objarr = array();
$tagbuzzs =new post($posttype, FALSE, $objarr);



//$userID = $_SESSION['NETWORKS_USR_DATA'][1]['LOGGED_USER']->id;
$userId = $this->user->sess['LOGGED_USER']->id;
//checking location id   
 $location_id=$tagbuzzs->check_location_id($userId);
if (empty($location_id)) {
//  header('Location: ' . $C->SITE_URL . 'setlocation');
}
$page_title_detail=$tagbuzzs->page_title_detail($D->postid);
$post_detail_new=$tagbuzzs->post_detail_new($D->postid);

$domain = $_SERVER['HTTP_HOST'];
$url = "https://".$domain.$_SERVER['REQUEST_URI'];
$urlsub= substr($url, strrpos($url, '/')+1);
$title = "Streetbuzz";
$description = "StreetBuzz is a real-time news platform of India. Newspersons share news directly from places where these news stories happen. All the newspersons publishing news on StreetBuzz follow a well-defined standard of journalism called StreetBuzz Principles of Journalism to share the truth with a clear sense of loyalty to the citizens.";
if(!empty($post_detail_new->title)){
    $description =strip_tags(substr(trim($post_detail_new->message),0,500));
    $title = $post_detail_new->title;
    $publish_date =  $post_detail_new->date;
}
if(empty($D->postid)){
$page_title_detail=$urlsub;
}
?>
		<title>StreetBuzz <?php echo $page_title_detail; ?></title> 
		<link data-rh="true" rel="canonical" href=<?php echo $url; ?>>
		{%header_data%}


		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		{%newmeta%}



		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5,
		maximum-scale=1.0, user-scalable=no"/>
		<meta name="mobile-web-app-capable" content="yes">

<?php

  if( preg_match( "#iPhone#i", $_SERVER['HTTP_USER_AGENT'] )  || preg_match( "#iPod#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Android#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Phone#i", $_SERVER['HTTP_USER_AGENT'] )) {
		print_r("<link rel='stylesheet' href='".$C->SITE_URL."/themes/FishingEnthusiastTheme/mobile/mobile.css' type='text/css' media='all' />");
		print_r("<script type='text/javascript' src='".$C->SITE_URL."/themes/FishingEnthusiastTheme/mobile/mobile.js'></script>");
		}


?>
<!--scheema.org-->
<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "NewsArticle",
    "url": "https://streetbuzz.co.in<?php echo $_SERVER['REQUEST_URI']; ?>",
    "name": "<?php echo $title; ?>",
    "description": "<?php echo $title; ?>",
    "mainEntityOfPage": "https://streetbuzz.co.in<?php echo $_SERVER['REQUEST_URI']; ?>",
    "headline": "<?php echo $title; ?>",
    "datePublished": "<?php echo date('Y-m-d H:i:s',$publish_date); ?>"
}
</script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-64E1N6H3KN"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-64E1N6H3KN');
</script>

<script>
 

//old code google analytics


(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-84017407-1', 'auto');
  ga('send', 'pageview');

</script>



<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-3281280003779605",
    enable_page_level_ads: true
  });
</script>

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
<a href="<?php echo $C->SITE_URL;?>dashboard"><span class="glyphicon glyphicon-home mob-header-menu-icon {%dashboardclass%}"></span></a>
</div>

<div class="col-xs-2 col-sm-2" style="display:{%mobileheadercss%}">
<div class="user-options dropdown">
			<a href="<?= $C->SITE_URL ?>janeeshk" class="arrow menu-btn"><span class="glyphicon glyphicon-briefcase mob-header-menu-icon {%notificationclass%}">{%mobile_notifications_cnt%}</span></a>

<ul class="menu-options menu-options-workspace">

	<a href="<?= $C->SITE_URL ?>notifications"><li class="workspace-left-main-menu-mob"><span><span class="glyphicon glyphicon-bell"></span> Notifications</span><span class="badge badge-workspace-left-menu-mob">{%mobile_notifications_notcnt%}</span></li></a>

				<a href="<?= $C->SITE_URL ?>notification/tab:@me"><li class="workspace-left-main-menu-mob"><span><span class="glyphicon glyphicon-bullhorn"></span> Mentions</span><span class="badge badge-workspace-left-menu-mob">{%mobile_notifications_mentcnt%}</span></li></a>

				<a href="<?= $C->SITE_URL ?>notification/tab:polls"><li class="workspace-left-main-menu-mob"><span><span class="glyphicon glyphicon-align-right"></span> Polls</span><span class="badge badge-workspace-left-menu-mob"></span></li></a>

				<a href="<?= $C->SITE_URL ?>notification/tab:event"><li class="workspace-left-main-menu-mob"><span><span class="glyphicon glyphicon-calendar"></span> Events</span><span class="badge badge-workspace-left-menu-mob"></span></li></a>

				<a href="<?= $C->SITE_URL ?>notification/tab:Intraday"><li class="workspace-left-main-menu-mob-last"><span><i class="fa fa-line-chart fa-profile-icons" aria-hidden="true"></i> Intraday</span><span class="badge badge-workspace-left-menu-mob"></span></li></a>

			</ul>
</div>
</div>

<div class="col-xs-2 col-sm-2" style="display:{%mobileheadercss%}">
<a href="<?php echo $C->SITE_URL;?>dashboard/tab:everybody"><span class="glyphicon glyphicon-star mob-header-menu-icon {%favourite%}"></span></a>
</div>
<!--
<div class="col-xs-2 col-sm-2">
<a href="<?php echo $C->SITE_URL;?>privatemessages"><span class="glyphicon glyphicon-comment mob-header-menu-icon"></span></a>
</div>
-->

<div class="col-xs-{%searchmobilelg%} col-sm-2">
    {%searchmobile%}
</div>


			{%mobileprofile%}


</div>


</div>
<!--/ END : Mobile Screen Header -->
<style>
	.mob-header-menu-icon.active {
		color: orange;
	}

	</style>





	<div id="page-container">
