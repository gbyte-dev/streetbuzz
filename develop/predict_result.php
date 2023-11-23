<?php
$DISTRUBUTION_RATIO =0.4;
//This query for getting results from post day feel table where result is null.
$dbname="sb_test";

$connection = mysqli_connect("localhost","sb_test","Wslu@697");
mysqli_select_db($connection,$dbname);
$status ="OPEN";
$predictselect  = "SELECT id,predict_value,asset_id,prediction_base_price,considered_accuracy,user_id,amount FROM  post_prediction  where 	status='".$status."' order by id desc ";

$predictselectquery    = mysqli_query($connection,$predictselect);
while($predictselectqueryres = mysqli_fetch_assoc($predictselectquery)){
//comparision of predicted_price and prediction_base_price. 
    $predicted_price            = $predictselectqueryres['predict_value'];
	$predicted_base_price       = $predictselectqueryres['prediction_base_price'];
	$asset_id       = $predictselectqueryres['asset_id'];
	$calculate_accuracy       = $predictselectqueryres['considered_accuracy'];
	$user_id       = $predictselectqueryres['user_id'];
	$post_prediction_id       = $predictselectqueryres['id'];
	$amount       = $predictselectqueryres['amount'];
	$tickerpriceselect   = "SELECT market_data FROM  asset_marketetails  where 	asset_id='".$asset_id."' ";
	    $tickerpricequery    = mysqli_query($connection,$tickerpriceselect);
	    $tickermarketdatares    = mysqli_fetch_object($tickerpricequery);

	   //Here getting current market price
	    $current_market_price            = $tickermarketdatares->market_data;
		 $userselect   = "SELECT Level,Hit,TotalPrediction,Miss,NotionalAmount FROM  user_predict_details  where User_id='".$user_id."' ";
	     $userquery    = mysqli_query($connection,$userselect);
	    $userdata    = mysqli_fetch_object($userquery);
	      $hit            = $userdata->Hit;
		$miss            = $userdata->Miss;
		$level            = $userdata->Level;
		$NAmount            = $userdata->NotionalAmount;

	
	if($predicted_price <= $predicted_base_price ){
		
		 
			if($current_market_price <= $predicted_price ){
				$predict_result = "CORRECT";
				$status = "CLOSE";
				mysqli_query($connection,'UPDATE  post_prediction SET  predict_result="'.$predict_result.'",status="'.$status.'"  WHERE id="'.$post_prediction_id.'" LIMIT 1');

				if($calculate_accuracy =="YES"){
					//increase value of hit
					mysqli_query($connection,'UPDATE  user_predict_details SET  	Hit=Hit+1   WHERE 	User_id="'.$user_id.'" LIMIT 1');
					$hit =$hit+1;
					$accuracy =($hit/($hit+$miss))*100; 

					if($accuracy < 30 && $level !="BEGINNER" ){
						$level ="BEGINNER";
						$notionalAmount =0;
						
						
					}elseif($accuracy >= 30 && $accuracy < 60 && $level !="INTERMEDIATE" ){
						$level ="INTERMEDIATE";
						$notionalAmount =0;
						
					}elseif($accuracy >= 60 && $accuracy < 80 && $level !="EXPERT" ){
						$level ="EXPERT";
						$notionalAmount =50000;
						
					}elseif($accuracy >= 80 && $level !="MAESTRO"){
						$level ="MAESTRO";
						$notionalAmount =100000+$NAmount;
					}
					mysqli_query($connection,'UPDATE  user_predict_details SET  Level="'.$level.'",NotionalAmount ="'.$notionalAmount.'"   WHERE  User_id="'.$user_id.'" LIMIT 1');
				$usertrans =  "SELECT Level FROM  user_predict_details  where User_id='".$user_id."' ";
	            $usertransquery    = mysqli_query($connection,$usertrans);
				$usertransdata    = mysqli_fetch_object($usertransquery);
				$level            = $usertransdata->Level;
				if($level =="EXPERT" || $level =="MAESTRO" ){
				$userearns =  "SELECT balance FROM  user_earning_transactions  where user_id='".$user_id."' order by uet_id desc LIMIT 1 ";
	            $userearnsquery    = mysqli_query($connection,$userearns);
				$userearndata    = mysqli_fetch_object($userearnsquery);
				$pbl            = $userearndata->balance;
				if($level =="EXPERT"){
					$trans_type ="CPAE";
					
				}elseif($level =="MAESTRO"){
					$trans_type ="CPAM";
					
				}
				
				if($pbl !=''){
					$pbl = $pbl;
					
				}else{
					$pbl =0;
				}
				$transaction_amount               =$amount*0.4;
				$balance       = $pbl+ $transaction_amount;
			    mysqli_query($connection,'INSERT INTO user_earning_transactions VALUES("","'.$user_id.'","'.$trans_type.'","'.$transaction_amount.'","'.date('Y-m-d h:i:s').'","'.$balance.'","'.$post_prediction_id.'") ');
				}
					

				}
				
			  }
		
		
	}elseif($predicted_price > $predicted_base_price){
		
		 	if($predicted_price <= $current_market_price){
				
				$predict_result = "CORRECT";
				$status = "CLOSE";
			   mysqli_query($connection,'UPDATE  post_prediction SET  predict_result="'.$predict_result.'",status="'.$status.'"  WHERE id="'.$post_prediction_id.'" LIMIT 1');

				if($calculate_accuracy =="YES"){
					//increase value of hit
				mysqli_query($connection,'UPDATE  user_predict_details SET  	Hit=Hit+1   WHERE 	User_id="'.$user_id.'" LIMIT 1');
				$hit =$hit+1;

					$accuracy =($hit/($hit+$miss))*100; 
					if($accuracy < 30 && $level !="BEGINNER" ){
						$level ="BEGINNER";
						$notionalAmount =0;
						
						
					}elseif($accuracy >= 30 && $accuracy < 60 && $level !="INTERMEDIATE" ){
						$level ="INTERMEDIATE";
						$notionalAmount =0;
						
					}elseif($accuracy >= 60 && $accuracy < 80 && $level !="EXPERT" ){
						$level ="EXPERT";
						$notionalAmount =50000;
						
						
					}elseif($accuracy >= 80 && $level !="MAESTRO"){
						$level ="MAESTRO";
						$notionalAmount =100000+$NAmount;
					}
				mysqli_query($connection,'UPDATE  user_predict_details SET  Level="'.$level.'",NotionalAmount ="'.$notionalAmount.'"   WHERE  User_id="'.$user_id.'" LIMIT 1');
				$usertrans =  "SELECT Level FROM  user_predict_details  where User_id='".$user_id."' ";
	            $usertransquery    = mysqli_query($connection,$usertrans);
				$usertransdata    = mysqli_fetch_object($usertransquery);
				$level            = $usertransdata->Level;
				if($level =="EXPERT" || $level =="MAESTRO" ){
				$userearns =  "SELECT balance FROM  user_earning_transactions  where user_id='".$user_id."' order by uet_id desc LIMIT 1 ";
	            $userearnsquery    = mysqli_query($connection,$userearns);
				$userearndata    = mysqli_fetch_object($userearnsquery);
				$pbl            = $userearndata->balance;
				if($level =="EXPERT"){
					$trans_type ="CPAE";
					
				}elseif($level =="MAESTRO"){
					$trans_type ="CPAM";
					
				}
				
				if($pbl !=''){
					$pbl = $pbl;
					
				}else{
					$pbl =0;
				}
				$transaction_amount               =$amount*0.4;
				$balance       = $pbl+ $transaction_amount;
			    mysqli_query($connection,'INSERT INTO user_earning_transactions VALUES("","'.$user_id.'","'.$trans_type.'","'.$transaction_amount.'","'.date('Y-m-d h:i:s').'","'.$balance.'","'.$post_prediction_id.'") ');
				}
				
					

					
				}
				
}

		
		
	}
	
   

	                  

}


 
?>
