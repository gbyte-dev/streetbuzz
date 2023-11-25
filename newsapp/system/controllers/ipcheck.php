<?php
$ip =$_SERVER['REMOTE_ADDR'];
echo $ip.'ip address';
$ipgetdate = file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip);

$ipdat = @json_decode($ipgetdate);
print_r($ipdat->geoplugin_region);

