<?php
if(isset($_POST['simulatetype'])){
	$simulatetype                 =(isset($_POST['simulatetype']))?$_POST['simulatetype']:''; //simulation type
	$predicted_price              =(isset($_POST['predicted_price']))?$_POST['predicted_price']:''; //From graph predicted price
	$predicted_date               =(isset($_POST['predicted_graph_date']))?$_POST['predicted_graph_date']:'';// From graph predicted date
	$predicted_reason             =(isset($_POST['predicted_reason']))?$_POST['predicted_reason']:'';//Reason
	$predicted_bidding_amount     =(isset($_POST['predicted_bidding']))?$_POST['predicted_bidding']:'';//bidding amount
	$predicted_current_amount     =(isset($_POST['predicted_currentprice']))?$_POST['predicted_currentprice']:'';//current price
	$message                      =(isset($_POST['predicted_message']))?$_POST['predicted_message']:'';//messageuser_available_balance
	$db_message                   ='$'.$message;
	$available     =(isset($_POST['user_available_balance']))?$_POST['user_available_balance']:0;//current price
	$newavail      = $available-$predicted_bidding_amount;
	$current_query               =$db2->query('SELECT id FROM assets WHERE ticker="'.$message.'"  LIMIT 1');
	$currentdata                  =$db2->fetch_object($current_query);
	$assetid                     = $currentdata->id;
	$db_date		= time();
	$db_ip_addr		= ip2long($_SERVER['REMOTE_ADDR']);

	$created                      =date('Y-m-d h:i:s');
	$db_api_id		= 0;
	$db_group_id    =0;
	$db_mentioned  =0;
	$db_posttags =0;
	$db_attached =0;
	
	$db2->query('INSERT INTO posts SET api_id="'.$db_api_id.'", user_id="'.$this->user->id.'", group_id="'.$db_group_id.'", message="'.$db_message.'", mentioned="'.$db_mentioned.'", posttags="'.$db_posttags.'", attached="'.$db_attached.'", date="'.$db_date.'", date_lastcomment="'.$db_date.'", ip_addr="'.$db_ip_addr.'" ');
    $id = $db2->insert_id();   
   if(  $id !='') {
	   	$u	= $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers;
		$pid = $id;
		foreach($u as $k=>$v) {
						if($k !=$this->user->id){
						$q[]	= '("'.$k.'", "'.$pid.'")';
						}
	    }
	    if( count($q) > 0 ) { 

			$q	= implode(', ', $q);
			$db2->query('INSERT INTO post_userbox (user_id, post_id) VALUES '.$q);
		}
		$db2->query('INSERT INTO post_userbox SET user_id="'.$this->user->id.'", post_id="'.$pid.'" ');

		
		$status ="OPEN";

	    $db2->query('INSERT INTO post_prediction SET post_id="'.$id.'", user_id="'.$this->user->id.'",asset_id="'.$assetid.'",predict_value="'.$predicted_price.'",prediction_base_price ="'.$predicted_current_amount.'", predict_reason="'.$predicted_reason.'", end_date="'.$predicted_date.'", amount="'.$predicted_bidding_amount.'",considered_accuracy="'.$simulatetype.'", status="'.$status.'", create_date="'.$created.'",update_date="'.$created.'" ');
		$db2->query('UPDATE  user_predict_details SET  TotalPrediction= TotalPrediction+1   WHERE  User_id="'.$this->user->id.'" LIMIT 1');

		$db2->query('UPDATE users SET num_posts=num_posts+1, lastpost_date="'.time().'" WHERE id="'.$this->user->id.'" LIMIT 1');
		if($_POST['user_type'] !="BEGINNER" || $_POST['user_type'] !="INTERMEDIATE " ){
		$db2->query('UPDATE  user_predict_details SET NotionalAmount="'.$newavail.'", UpdateDate="'.$created.'" WHERE User_id="'.$this->user->id.'" LIMIT 1');
		}


    }
	$this->redirect($C->SITE_URL.'predictions');	

}
?>