<?php
global $C,$db2;
$adid= $_POST['adid'];
$action= $_POST['action'];
if($action=="delete"){
    echo $db2->query('update ads_info  set big_image="" where id="'.$adid.'" ');
    
}else{
if(!empty($_FILES["adsmall"])){
$smallfilename   =time().'_small'; // 5dab1961e93a7_1571494241
$smallextension  = pathinfo( $_FILES["adsmall"]["name"], PATHINFO_EXTENSION ); // jpg
$smallbasename   = $smallfilename . '.' . $smallextension; // 5dab1961e93a7_1571494241.jpg
$dest = $C->STORAGE_DIR.'advs'.'/'.$smallbasename;

if(move_uploaded_file($_FILES['adsmall']['tmp_name'],$dest)){
	
    $db2->query('update ads_info  set sort_image="'. $smallbasename .'" where id="'.$adid.'" ');
}else{

}
}
if(!empty($_FILES["adbig"])){
$bigfilename   =time().'_big'; // 5dab1961e93a7_1571494241
$bigextension  = pathinfo( $_FILES["adbig"]["name"], PATHINFO_EXTENSION ); // jpg
$bigbasename   = $bigfilename . '.' . $bigextension; // 5dab1961e93a7_1571494241.jpg
$bigdest = $C->STORAGE_DIR.'advs'.'/'.$bigbasename;
if(move_uploaded_file($_FILES['adbig']['tmp_name'],$bigdest)){
        $db2->query('update ads_info  set big_image="'. $bigbasename .'" where id="'.$adid.'" ');

    
}else{

}
}
$customername    = $_POST['customer_name'];
$customer_number = $_POST['customer_number'];
$district       = $_POST['district'];
$salesperson       = $_POST['salesperson'];


$contact_email  = $_POST['contact_email'];
$display_url    = $_POST['display_url'];
$start_date     = $_POST['start_date'];
$end_date       = $_POST['end_date'];
$adsstatus       = $_POST['adsstatus'];
$ads_type       = $_POST['adstype'];
$ads_access_source       = $_POST['ads_access_source'];

$whatsapp_number       = $_POST['whatsapp_number'];
$callnow_number       = $_POST['callnow_number'];

if($start_date){
    $start_date=date_create($start_date);
  $start_date = date_format($start_date,"Y-m-d");
    $start_date    = strtotime($start_date.' 05:59:59');

}
if($end_date){
     $end_date=date_create($end_date);
    $end_date = date_format($end_date,"Y-m-d");
    $end_date    = strtotime($end_date.' 23:59:59');

}

$db2->query('update ads_info  set customer_name="'.$customername.'", contact_number="'.$customer_number.'",customer_district="'.$district.'",sales_person="'.$salesperson.'",contact_email="'.$contact_email.'", display_url="'.$display_url.'",start_date="'.$start_date.'",end_date="'.$end_date.'",ads_type="'.$ads_type.'",ads_access_source="'.$ads_access_source.'",whatsapp_number="'.$whatsapp_number.'",callnow_number="'.$callnow_number.'"  where id="'.$adid.'" ');
if(!empty($adsstatus)){
    $db2->query('update ads_info  set status="'.$adsstatus.'"  where id="'.$adid.'" ');
    
}
$tags    = $_POST['tags'];
if(!empty($tags)){
    $tags =str_replace(",",'',$tags);
    $expl = explode("@", $tags);
    $expl = array_filter($expl);
    $db2->query("delete  from ads_tags where ad_id=$adid ");

      foreach ($expl as $arrkeys => $arrvals) {
                $r      = $db2->query('select id  FROM  users  WHERE username="' . $arrvals . '"  LIMIT 1', FALSE);
                $result = $db2->fetch_object($r);
                if ($result->id != '') {
                 $res = $db2->query('INSERT INTO ads_tags SET  ad_id="'.$adid.'", user_id="'.$result->id.'" ');
  
                }
            }
    
    
}
    $_SESSION['ads_status'] = 1;
}

$this->redirect('admin_addsdisplay');




?>