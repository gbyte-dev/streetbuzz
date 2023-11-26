<?php
/*$host="localhost";
	$uname="sb_test";
	$pass="Wslu@697";
	$database ="sb_test";
	$link=mysqli_connect($host,$uname,$pass,$database);
    $selectdb=mysqli_select_db($link,$database) or 
	die("Database could not be selected");
$date ='yues';
		 	$sql = mysqli_query($link,"INSERT INTO  feed VALUES ('','".$date."')");



echo date('Y-M-D h:i:s');exit;*/
 
//Sample Database Connection Script 
 
//Setup connection variables, such as database username
//and password

$dbname="sb_test";

//Connect to the database
$connection = mysqli_connect("localhost","sb_test","Wslu@697");
mysqli_select_db($connection,$dbname);
$tickerquery = "SELECT id,ticker,market_name FROM assets ";
$tickerres  = mysqli_query($connection,$tickerquery);
	//mysqli_query($connection,"TRUNCATE TABLE asset_marketetails");


while($tickerfinalres = mysqli_fetch_assoc($tickerres)){
	$ticker = $tickerfinalres['ticker'];
	$marketname = $tickerfinalres['market_name'];
	$tickerr = strtoupper($ticker);
	$ticker =$tickerr.'.'.$marketname;
	$reuters= "http://reuters.com/finance/stocks/overview?symbol=". $ticker;
    //$nasdaq= "http://www.nasdaq.com/symbol/". $ticker;
   $yahoo= "http://finance.yahoo.com/q?s=". $ticker;
   //Try to retrieve Reuters price first (most reliable of 3 possible sources)
$reutResult = file_get_contents($reuters);
$nyArr1 = explode( 'font-size: 23px;">', $reutResult);
if($nyArr1[1]){
$nyArr2 = explode( "</span>", $nyArr1[1]);
if($nyArr2[1]){
$nyPrice = $nyArr2[0];
}
}

if($nyPrice){
	$assetid = $tickerfinalres['id'];
	$nyPrice =trim($nyPrice);
	$nyPrice         =str_replace(',','',$nyPrice);
	$update ='UPDATE asset_marketetails set market_data="'.$nyPrice.'",updated_date="'.date('Y-m-d h:i:s').'" WHERE asset_id="'.$assetid.'" ';
//$tickerquery	= mysqli_query($connection,'INSERT INTO asset_marketetails VALUES("","'.$assetid.'","'.$tickerr.'","'.$nyPrice.'","'.date('Y-m-d h:i:s').'","") ');	
$tickerquery	= mysqli_query($connection,$update);	
	
		

    // We have Reuter's price data for this stock
}

/*
if($nyPrice){
    // We have Reuter's price data for this stock
     $jsonResponse = '{"price": "'.floatval($nyPrice).'"}';
     echo json_encode($jsonResponse);
    return;

}
*/



else{

//could not get Reuters, so trying Nasdaq
 $nasResult = file_get_contents($nasdaq);   
 //Try to retrieve Nasdaq price:
$nasArr1 = explode( "_LastSale1'>", $nasResult);
if($nasArr1[1]){
$nasArr2 = explode( "</label>", $nasArr1[1]);
if($nasArr2[1]){
$nasPrice = $nasArr2[0];
}
}


if($nasPrice){
    //we have Nasdaq's price
    $nasPrice = str_replace("$", "", $nasPrice);
    $nasPrice = str_replace(" ", "", $nasPrice);
     $jsonResponse = '{"price": "'. $nasPrice.'", "source": "Nasdaq"}';
     echo json_encode($jsonResponse);
    //return;

}



else{
    //could not get Nasdaq or Reutors, so trying Yahoo
    $yahResult = file_get_contents($yahoo);

$ticker = strtolower($ticker);
$yahArr1 = explode( 'id="yfs_l84_'.$ticker.'">', $yahResult);
if($yahArr1[1]){
   // echo $yahArr1[1];
$yahArr2 = explode( " ", $yahArr1[1]);
if($yahArr2[1]){
   
$yahPrice = $yahArr2[0];
}
}


if($yahPrice){
     $jsonResponse = '{"price": "'.floatval($yahPrice).'" , "source": "Yahoo"}';
     echo json_encode($jsonResponse);
    //return;

}

else{
      $jsonResponse = '{"error": "Y"Please make sure you passed a valid stock sticker symbol. (e.g. yoursite.com/?ticker=GOOG). If this error persists, please update this script with the latest version ( https://github.com/m140v/Real-time-Stock-Price-API/). The source site might have been reformatted."}';
     echo json_encode($jsonResponse);
    return;

}

}



  
	
	
}
	
}

 /*
//Setup our query
$query = "INSERT INTO test VALUES('','rahul')";
 
//Run the Query
$result = mysql_query($query);
print_R($result);
 */

 
?>