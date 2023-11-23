
<?php
//This query for getting results from post day feel table where result is null.
$dbname="streetbu_sb_test";
echo "dryrt";exit;


$connection = mysqli_connect("localhost","streetbu_sb_test","streetbu_sb_test");
print_r("cuc");exit;
mysqli_select_db($connection,$dbname);
$status ="OPEN";
$predictselect  = "SELECT id,predict_value,asset_id,prediction_base_price,considered_accuracy,user_id FROM  post_prediction  where status='".$status."' AND  end_date >=now() order by id desc ";

$predictselectquery    = mysqli_query($connection,$predictselect);



while($predictselectqueryres = mysqli_fetch_assoc($predictselectquery)){
//comparision of predicted_price and prediction_base_price. 
	$asset_id       = $predictselectqueryres['asset_id'];
	$user_id       = $predictselectqueryres['user_id'];
	$post_prediction_id       = $predictselectqueryres['id'];
	$userselect   = "SELECT Level,Hit,TotalPrediction,Miss,NotionalAmount FROM  user_predict_details  where User_id='".$user_id."' ";
	$userquery    = mysqli_query($connection,$userselect);
	$userdata    = mysqli_fetch_object($userquery);
	$hit            = $userdata->Hit;
    $miss            = $userdata->Miss;
	$level            = $userdata->Level;
	$NAmount            = $userdata->NotionalAmount;


	$predict_result = "WRONG";
	$status = "CLOSE";
	mysqli_query($connection,'UPDATE  post_prediction SET  predict_result="'.$predict_result.'",status="'.$status.'"  WHERE id="'.$post_prediction_id.'" LIMIT 1');
	mysqli_query($connection,'UPDATE  user_predict_details SET  	Miss=Miss+1   WHERE 	User_id="'.$user_id.'" LIMIT 1');
	$miss = $miss+1;
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
	mysqli_query($connection,'UPDATE  user_predict_details SET  Level="'.$level.'",NotionalAmount ="'.$notionalAmount.'"  WHERE  User_id="'.$user_id.'" LIMIT 1');

}
 
?>
