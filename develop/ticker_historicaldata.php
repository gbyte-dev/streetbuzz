<?php

$dbname="sb_test";

$connection = mysqli_connect("localhost","sb_test","Wslu@697");
mysqli_select_db($connection,$dbname);
	
$tickerquery =mysqli_query($connection,"SELECT id,ticker,market_name,histrolical_symbol FROM assets");
while($tickerfinalres = mysqli_fetch_assoc($tickerquery)){
	$assetres =mysqli_query($connection,'SELECT asset_id,ticker,market_data,updated_date FROM asset_marketetails WHERE asset_id="'.$tickerfinalres['id'].'" ');
	$asset = mysqli_fetch_object($assetres);
    mysqli_query($connection,'INSERT INTO asset_historical_data VALUES("","'.$asset->asset_id.'","'.$asset->ticker.'","'.$asset->market_data.'","'.$asset->updated_date.'","'.date('Y-m-d h:i:s').'") ');

	                 

}



 
?>
