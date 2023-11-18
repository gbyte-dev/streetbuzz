<!DOCTYPE html>
<html lang="{%html_lang_abbrv%}" class="login-page">
	<head>
		<title>{%page_title%}</title>
		{%header_data%}
		
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<?php
    if( preg_match( "#iPhone#i", $_SERVER['HTTP_USER_AGENT'] )  || preg_match( "#iPod#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Android#i", $_SERVER['HTTP_USER_AGENT'] ) || preg_match( "#Phone#i", $_SERVER['HTTP_USER_AGENT'] )) {
		print_r("<link rel='stylesheet' href='".$C->SITE_URL."/themes/flatUI/mobile/mobile.css' type='text/css' media='all' />");
		print_r("<script type='text/javascript' src='".$C->SITE_URL."/themes/flatUI/mobile/mobile.js'></script>");
		} 
?>
	
	</head>
	<!-- Matomo -->
<script type="text/javascript">
  var _paq = window._paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(["setDocumentTitle", document.domain + "/" + document.title]);
  _paq.push(["setCookieDomain", "*.streetbuzz.co"]);
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//streetbuzz.co/matomo/";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '1']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="//streetbuzz.co/matomo/matomo.php?idsite=1&amp;rec=1" style="border:0;" alt="" /></p></noscript>
<!-- End Matomo Code -->
	<body>
	
	<div id="layout-container">
		<div class="container">
		<div class="row">
		<div class="col-md-12">
		<div id="logo-login">
			{%logo_data%}
			<div class="clear"></div>
		</div>
		</div>
		</div>
		</div>
		<div class="clear"></div>
		
		<div id="page-container">