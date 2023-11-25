<?php
 $dbhost = "localhost"; 
 $dbuser = "streetbuzz1_guest"; 
 $dbpass = ")]GaLibeRkA7"; 
 $dbname = "streetbuzz1_sb_live_1";
 $connection= mysqli_connect ($dbhost, $dbuser, $dbpass,$dbname);
 if (!$connection)
 {
 die ("Could not connect:" . mysqli_error());
 }


echo "<center> <b> List All the tables from sb_live_1 </b> <br> " ;
echo "##################################################### <br>";
$showtables= mysqli_query($connection, "SHOW TABLES FROM $dbname");
 while($table = mysqli_fetch_array($showtables)) { 
  echo($table[0] . "<br>");  
 }
?>