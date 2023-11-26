<?php
//This query for getting results from post day feel table where result is null.
$dbname="sb_test";

//Connect to the database
$connection = mysqli_connect("localhost","sb_test","Wslu@697");
mysqli_select_db($connection,$dbname);
$tickerselect  = "SELECT id FROM  post_dayfeel  where result is null order by id desc ";
$tickerquery    = mysqli_query($connection,$tickerselect);
while($tickerfinalres = mysqli_fetch_assoc($tickerquery)){
		  $id =  $tickerfinalres['id'];

    $result = 0;
  $status ="close";
	 $updateselect =" update  post_dayfeel set result ='".$result."',status='".$status."' where  id='".$id."'";
	mysqli_query($connection,$updateselect);
  


}



 
?>
