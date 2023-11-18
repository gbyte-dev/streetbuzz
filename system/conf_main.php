<?php
	
	// Site Address Here:
	// 
		$C->DOMAIN		= 'streetbuzz.co.in';
		$C->SITE_URL	= 'https://streetbuzz.co.in/newsapp/';
	// 
	// 
	
	// Random identifier for this installation on this server
	// 
		$C->RNDKEY	= 'efda7';
	// 
	
	// MySQL SETTINGS
	// 
	//	$C->DB_HOST	= '120.138.9.201';
		//$C->DB_HOST	= 'localhost';
	//	$C->DB_USER	= 'sb_live';
	//	$C->DB_PASS	= 'Hanuman321#';
	//	$C->DB_NAME	= 'sb_test';
	//	$C->DB_MYEXT = 'mysqli'; // 'mysqli' or 'mysql'
	// 
	   //  $C->DB_HOST	= '182.18.139.51';
	   $C->DB_HOST	= 'localhost';
	    $C->DB_USER	= 'streetbuzz1_sb_live_1';
	    $C->DB_NAME	= 'streetbuzz1_sb_live_1';
	    $C->DB_PASS	= 'Hanuman321#';
		$C->DB_MYEXT = 'mysqli'; // 'mysqli' or 'mysql'
	
	// CACHE SETTINGS
	// 
		$C->CACHE_MECHANISM	= 'filesystem';	// 'apc' or 'memcached' or 'mysqlheap' or 'filesystem'
		$C->CACHE_EXPIRE		= 3600;
		$C->CACHE_KEYS_PREFIX	= 'efda7';
		
		// If 'memcached':
		$C->CACHE_MEMCACHE_HOST	= '';
		$C->CACHE_MEMCACHE_PORT	= '';
		
		// If 'filesystem':
		$C->CACHE_FILESYSTEM_PATH	= $C->INCPATH.'cache/';
	// 
	
	// IMAGE MANIPULATION SETTINGS
	// 
		$C->IMAGE_MANIPULATION	= 'gd';	// 'imagemagick_cli' or 'gd'
		
		// if 'imagemagick_cli' - /path/to/convert
		$C->IM_CONVERT	= 'convert';
	// 
	
	// DEFAULT LANGUAGE
	// 
		$C->LANGUAGE	= 'en';
	// 
	
	// USERS ACCOUNTS SETTINGS
	// 
		// if urls are user.site.com or site.com/user
		// this setting is still beta and it is not working properly
		$C->USERS_ARE_SUBDOMAINS	= FALSE;
	// 
	
	// RPC PING SETTINGS
	// 
		$C->RPC_PINGS_ON		= TRUE;
		$C->RPC_PINGS_SERVERS	= array('http://rpc.pingomatic.com');
	// 
	
	// TWITTER & FACEBOOK CONNECT SETTINGS
	//
		// To activate Facebook Connect, check out the README.txt file
		$C->FACEBOOK_API_KEY		= '';
		$C->FACEBOOK_API_ID		= '';
		$C->FACEBOOK_API_SECRET		= '';
		
		// To activate Twitter OAuth login, check out the README.txt file
		$C->TWITTER_CONSUMER_KEY	= '';
		$C->TWITTER_CONSUMER_SECRET	= '';
		
		// Bit.ly Integration - used for sharing posts to twitter
		$C->BITLY_LOGIN			= 'paraj';
		$C->BITLY_API_KEY			= 'R_9f2cba4f8ca34577b9930dcc27502360';
		
		// For inviting Yahoo contacts. Check out the README.txt file
		$C->YAHOO_CONSUMER_KEY		= '';
		$C->YAHOO_CONSUMER_SECRET	= '';
		
		// For Google reCaptcha. Check out the README.txt file
		$C->GOOGLE_CAPTCHA_PUBLIC_KEY  = '';
		$C->GOOGLE_CAPTCHA_PRIVATE_KEY = '';
	//
	
	// IF YOUR SERVER SUPPORTS CRONJOBS, READ THE FILE ./system/cronjobs/readme.txt 
	// 
		$C->CRONJOB_IS_INSTALLED	= FALSE;
	// 
	
	// DO NOT REMOVE THIS
	// 
		$C->INSTALLED	= TRUE;
		$C->VERSION		= '3.6.0';
		$C->DEBUG_USERS		= array();
	// 
	
	//SPAMMER CHECKS
	//go to https://www.projecthoneypot.org to get a access key
	$C->HONEYPOT_ACCESS_KEY = '';
    $C->FCM_API_KEY = 'AAAApFf3Kf8:APA91bFUL0xrisBv0qWMFGwQsTrYMsRmbtkm0dYDBkVBCm-FpLpQSEfY6GI40TMgO80kDJ2WBp3ZDwXVzVQ0SaNJrYQ6tDT1DFv1VvkK9KHKWrvtnILIwkFtzgt7BmlOH0iwPPOBzJx-';
	$C->FCM_API_URL = 'https://fcm.googleapis.com/fcm/send';
	
?>