<?php

	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('notification_api.php');


  $data = json_decode(file_get_contents('php://input'),true);
    $statuscode=0;

    //Parse data from JSON
    
    $userid = $data["user"]["userid"];
    
    $message='Success';
    
    $api = new API();
    
    $output1 = $api->get_lastFollow_count($userid);
    $output2 = $api->get_liked_count($userid);
    $output3 = $api->get_rebuzz_count($userid);
    $output4 = $api->get_replied_count($userid);
    $output5 = $api->get_group_count($userid);
    $output6 = $api->get_joined_count($userid);
    $output7 = $api->get_lastChangeProfileInfo_count($userid);
    $output8 = $api->get_lastProfileLiked_count($userid);
    $output9 = $api->get_lastInvitesMeToJoinGroup_count($userid);
    $output10 = $api->get_editprfilepic_count($userid);
    $output11 = $api->get_followerFollowedSomeone_count($userid);
    
    // get_editprfilepic_count
    
      $oauth_access_token = '';
    
 
    
    $oauth_access_token = $api->generateToken($userid);
    
    $temp= array();
    if(!empty($output1))
      array_push($temp,$output1);
    if(!empty($output2))
      array_push($temp,$output2);
    if(!empty($output3))
      array_push($temp,$output3);
    if(!empty($output4))
      array_push($temp,$output4);
    if(!empty($output5))
      array_push($temp,$output5);
    if(!empty($output6))
      array_push($temp,$output6);
    if(!empty($output7))
      array_push($temp,$output7);
    if(!empty($output8))
      array_push($temp,$output8);
    if(!empty($output9))
      array_push($temp,$output9);
    if(!empty($output10))
      array_push($temp,$output10);
    if(!empty($output11))
      array_push($temp,$output11);
    // array_push($temp,$output1);

    $user =array(
                "userid" => $userid,
                'token' =>$oauth_access_token,
                'data' => $temp
            );
    // array_push($user,$output1);
    
    
	// $status =array(
  //           "message" => $output,
  //       );
        
   
        $response["user"] = $user;
        // $response["status"] = $status;
    
    http_response_code(200);
  
    // show response data in json format
    echo json_encode($response);


?>