<?php

global $C;
$addisplaytype = "image";
$smallfilename   =time().'_small'; // 5dab1961e93a7_1571494241
$smallextension  = pathinfo( $_FILES["adsmall"]["name"], PATHINFO_EXTENSION ); // jpg
$smallbasename   = $smallfilename . '.' . $smallextension; // 5dab1961e93a7_1571494241.jpg
$dest = $C->STORAGE_DIR.'advs'.'/'.$smallbasename;

if(move_uploaded_file($_FILES['adsmall']['tmp_name'],$dest)){
    
}else{

}
$bigfilename   =time().'_big'; // 5dab1961e93a7_1571494241
$bigextension  = pathinfo( $_FILES["adbig"]["name"], PATHINFO_EXTENSION ); // jpg
if($bigextension == "mp4"){
   $addisplaytype = "video";

   
}
$bigbasename   = $bigfilename . '.' . $bigextension; // 5dab1961e93a7_1571494241.jpg

$bigdest = $C->STORAGE_DIR.'advs'.'/'.$bigbasename;
if(move_uploaded_file($_FILES['adbig']['tmp_name'],$bigdest)){
    
}else{

}
$customername    = $_POST['customer_name'];
$district    = $_POST['district'];
$customer_number = $_POST['customer_number'];
$salesperson    = $_POST['salesperson'];
$contact_email  = $_POST['contact_email'];
$display_url    = $_POST['display_url'];
$home_ads    = $_POST['home_ads'];

if(!empty($_POST['start_date'])){
$start_date    = strtotime($_POST['start_date'].'00:00:00');
}else{
   $start_date =''; 
}
if(!empty($_POST['end_date'])){
$end_date    = strtotime($_POST['end_date'].'23:59:59');
}else{
 $end_date ='';   
}
$status = 3;
if($home_ads == 1){
    $ads_type=8;
    $status = 1;
   
    $res = $db2->query('INSERT INTO ads_info SET  customer_name="'.$customername.'",customer_district="'.$district.'", contact_number="'.$customer_number.'",sales_person="'.$salesperson.'",contact_email="'.$contact_email.'", display_url="'.$display_url.'",status="'.$status.'", sort_image="'.$smallbasename.'",big_image="'.$bigbasename.'",ad_display_type = "'.$addisplaytype.'",ads_type="'.$ads_type.'" ');
}else{


$res = $db2->query('INSERT INTO ads_info SET  customer_name="'.$customername.'",customer_district="'.$district.'", contact_number="'.$customer_number.'",sales_person="'.$salesperson.'",contact_email="'.$contact_email.'", display_url="'.$display_url.'",status="'.$status.'", sort_image="'.$smallbasename.'",big_image="'.$bigbasename.'",ad_display_type = "'.$addisplaytype.'" ');
}
if($res){
    $_SESSION['ads_status'] = 1;

if($home_ads == 1){
    $this->redirect('admin_homeaddsdisplay');

}else{
 $this->redirect('admin_addsdisplay');
   
}
}



?>
