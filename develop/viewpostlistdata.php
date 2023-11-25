<?php
//This query for getting results from post day feel table where result is null.
$dbname="sb_test";

//Connect to the database
$connection = mysqli_connect("localhost","sb_test","Wslu@697");
mysqli_select_db($connection,$dbname);
$id = $_POST['postid'];

$viewquery  = "SELECT count(pvl.ip_addr) as cnt  FROM post_views_list as pvl 
WHERE pvl.post_id='$id' ";

$viewcheck    = mysqli_query($connection,$viewquery);
$final = mysqli_fetch_assoc($viewcheck);
//print_r($final);
$html="<b>Total Views:</b>";
echo $html.''.$final['cnt'];

?>