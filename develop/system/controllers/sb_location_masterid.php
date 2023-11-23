<?php
    $keyword = $_POST["keyword"];
    $result = array();
    if($keyword != ""){
	$sbres	= $db2->query("SELECT * FROM `sb_location_master` WHERE location='$keyword'");
    }
		while($result[] =$db2->fetch_object($sbres)){
				
			}
	if(!empty($result)) {
	   echo $result[0]->id;
?>

<?php  }


?>