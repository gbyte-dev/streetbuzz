<?php
//This query for getting results from post day feel table where result is null.
$dbname="sb_test";

//Connect to the database
$connection = mysqli_connect("localhost","sb_test","Wslu@697");
mysqli_select_db($connection,$dbname);
$tickerselect  = "SELECT id,ticker,predicted_price,stoploss_price,current_price FROM  post_dayfeel  where result IS NULL order by id desc ";
$tickerquery    = mysqli_query($connection,$tickerselect);
while($tickerfinalres = mysqli_fetch_assoc($tickerquery)){
   //This query for present data of tickers.
     $ticker  = $tickerfinalres['ticker'];
	 $stoploss_price  = $tickerfinalres['stoploss_price'];
	 $target_price  = $tickerfinalres['predicted_price'];
	 $id  = $tickerfinalres['id'];

	 $tickerpriceselect   = "SELECT market_data FROM  asset_marketetails  where ticker='".$ticker."' ";
	  $tickerpricequery    = mysqli_query($connection,$tickerpriceselect);
	    $tickermarketdatares    = mysqli_fetch_object($tickerpricequery);
	 // $current_market_price  = 1269;
	  //$target_price = 1259;
	// $stoploss_price = 1247;
	
	//Here getting current market price
	  $current_market_price            = $tickermarketdatares->market_data;
	  if($stoploss_price <= $target_price ){

		   if($current_market_price >= $target_price  ){
			   $result = 1;
			   $status ="close";
			    $updateselect =" update  post_dayfeel set result ='".$result."',status='".$status."' where  id='".$id."'";
		  mysqli_query($connection,$updateselect);
		   }elseif($current_market_price <= $stoploss_price){
			    $result = 0;
				$status ="close";
			    $updateselect =" update  post_dayfeel set result ='".$result."',status='".$status."' where  id='".$id."'";
		  mysqli_query($connection,$updateselect);
			   
		   }
		
		 
		   
		  
	  }elseif($stoploss_price > $target_price){
		  if($current_market_price <= $target_price  ){
			   $result = 1;
			   $status ="close";
			  $updateselect =" update  post_dayfeel set result ='".$result."',status='".$status."' where  id='".$id."'";
		  mysqli_query($$connection,$updateselect);
			   
		   }elseif($current_market_price >= $stoploss_price){
			    $result = 0;
				$status ="close";
			  $updateselect =" update  post_dayfeel set result ='".$result."',status='".$status."' where  id='".$id."'";
		  mysqli_query($$connection,$updateselect);
			   
		   }

		  
	  }


	                  

}



 
?>
