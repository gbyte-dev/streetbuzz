<?php
	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api.m1.php');

   	// Convert data to json
    $data = json_decode(file_get_contents('php://input'),true); 
 
    //Parse data from JSON
    $userid     = $data["user"]["userid"]; 
    $token      = $data["user"]["token"]; 
	$who     = isset($data["user"]["who"]) ? $data["user"]["who"] : 0; 


    //Set default values
    $message='Success';
    $statuscode=0;
    $oauth_access_token = '';

    // Create class object
    $api = new API();

    //Get the user id if exists
/*    if (!$api->validateToken($userid, $token))
    {
        $message='Invalid token';
        $statuscode=100;
        http_response_code(401);
    }
    else
	{*/
		$message='Success';
		$statuscode=0;
		$user = $api->getUserProfileDetails($userid, $who);
	/*}*/

	$settings =array(
		//"imageurl" => $api->imageUrl(),
		//"profileImageurl" => $api->profileImageUrl()
	);

	$response=array();
	$response["status"]=array();
	$response["user"]= $user;

	// $response["settings"] = $settings;
	$status =array(
		"message" => $message,
		"statuscode" => $statuscode
	);

	$response["status"] = $status;
	http_response_code(200);
	echo json_encode($response); 
?>