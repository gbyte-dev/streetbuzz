<?php
global $db2;
$adid =$_POST['adid'];
$ipaddr =$_SERVER['REMOTE_ADDR'];
$created_date = date('Y-m-d h:i:s');
$res = $db2->query('INSERT INTO ads_links SET  ad_id="'.$adid.'", ip_addr="'.$ipaddr.'",created_date="'.$created_date.'" ');


?>