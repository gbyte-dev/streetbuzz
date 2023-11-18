<?php
    $keyword = $_POST["keyword"];
    $result = array();
    if($keyword != ""){
	$sbres	= $db2->query("SELECT id,location,location_district,location_state FROM sb_location_master WHERE location like '" .$keyword. "%' or location_district like '" .$keyword. "%'  LIMIT 5");
    }
		while($result[] =$db2->fetch_object($sbres)){
				
			}
			if(!empty($result)) {
?>
<ul id="country-list">
<?php
foreach($result as $country) {
    if( $country->id != ""){
        $locationhtml = $country->location.",".$country->location_district.",".$country->location_state;
?>
<li onClick="selectCountry('<?php echo $country->id; ?>','<?php echo $locationhtml; ?>');"><?php echo $locationhtml; ?></li>
<?php } } }


?>
</ul>

