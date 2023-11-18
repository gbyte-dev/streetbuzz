<?php

	require_once('../../helpers/func_main.php');
	require_once('../../helpers/func_api.php');
	require_once('../../conf_system.php');
	
	// Get the post data 
	$inputJSON = file_get_contents('php://input');

	// Convert data to json
    $data = json_decode($inputJSON,true); 
    
    //Parse ID from JSON
    $userid = $data["user"]["id"]; 

    //Get the IP from DB
	echo(getIPAddress($userid));
?>