<?php
global $db2, $C;
$db2 = mysqli_connect($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
$path = "uploads/"; //Images upload folder 
