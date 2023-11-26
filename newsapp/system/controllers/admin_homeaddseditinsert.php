<?php
global $C,$db2;
if(!empty( $_POST['adid'])){
$custombasevalue = $_POST["custombasevalue"];
$bigimages = [];
if($custombasevalue > -1 ){
    
    for($i=0;$i<= $custombasevalue;$i++){
        $sampeval = "baseval".$i;
        
        $img = $_POST[$sampeval];
        $jpegext =  strpos($img,"data:image/jpeg;base64,");
        $pngext =  strpos($img,"data:image/png;base64,");
        if($jpegext > -1){
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        }
        if($pngext > -1){
        $img = str_replace('data:image/png;base64,', '', $img);
        }
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
          if($jpegext > -1){
             $filename = uniqid() . '.jpeg';
        $file = $C->STORAGE_DIR.'advs'.'/'.$filename;
          }
          if($pngext > -1){
               $filename = uniqid() . '.png';
               $file = $C->STORAGE_DIR.'advs'.'/'.$filename;
          }
          $bigimages[$i] = $filename;
        $success = file_put_contents($file, $data);


        
    }
    
}
$adid           = $_POST['adid'];
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
$bigextension  = pathinfo( $_FILES["adbig"]["name"][0], PATHINFO_EXTENSION ); // jpg
$bigbasename   = $bigfilename . '.' . $bigextension; // 5dab1961e93a7_1571494241.jpg
$bigdest = $C->STORAGE_DIR.'advs'.'/'.$bigbasename;
if(move_uploaded_file($_FILES['adbig']['tmp_name'][0],$bigdest)){
        $db2->query('update ads_info  set big_image="'. $bigbasename .'" where id="'.$adid.'" ');

    
}else{

}
}
$customername    = $_POST['customer_name'];
$customer_number = $_POST['customer_number'];
$district       = $_POST['district'];
$salesperson       = $_POST['salesperson'];


$contact_email  = $_POST['contact_email'];
$adsstatus       = $_POST['adsstatus'];
$is_priority       = $_POST['is_priority'];



$db2->query('update ads_info  set customer_name="'.$customername.'", contact_number="'.$customer_number.'",customer_district="'.$district.'",sales_person="'.$salesperson.'",contact_email="'.$contact_email.'",status="'.$adsstatus.'",is_priority="'.$is_priority.'" where id="'.$adid.'" ');
 if($adid){
     if(count($bigimages) > 0){
          $cnt = count($bigimages);
          for($k=0;$k<$cnt;$k++){
            
$bigbasename   = $bigimages[$k];
         
                $res = $db2->query('INSERT INTO  home_ads_images SET  ad_id="'.$adid.'",ads_image="'.$bigbasename.'" ');  
           
              
          }
          
      }
      
      $multiadsimages = $_FILES["adbig"];
      /*if(count($multiadsimages["name"]) > 0){
          $cnt = count($multiadsimages["name"]);
          for($k=0;$k<$cnt;$k++){
             
            $bigextension  = pathinfo( $_FILES["adbig"]["name"][$k], PATHINFO_EXTENSION ); // jpg
$bigbasename   = $bigfilename . '.' . $bigextension; // 5dab1961e93a7_1571494241.jpg
            $bigdest = $C->STORAGE_DIR.'advs'.'/'.$bigbasename;
            if(move_uploaded_file($_FILES['adbig']['tmp_name'][$k],$bigdest)){
                $res = $db2->query('INSERT INTO  home_ads_images SET  ad_id="'.$adid.'",ads_image="'.$bigbasename.'" ');  
            }else{
            }  
              
          }
          
      }*/
      
  }
  $publishedlocations    = $_POST['publishedlocations'];
if(!empty($publishedlocations)){
    foreach ($publishedlocations as $publishedkeys => $publishedvals) {
         $res = $db2->query('INSERT INTO  ads_home_locations SET  ad_id="'.$adid.'", location_id="'.$publishedvals.'" ON DUPLICATE KEY UPDATE    
ad_id="'.$adid.'", location_id="'.$publishedvals.'" ');
    }
    
}

    $_SESSION['ads_status'] = 1;


$this->redirect('admin_homeaddsdisplay');
}
if(!empty($_POST['action'] == "delete")){
   $adsname =  $C->STORAGE_DIR.'advs'.'/'.$_POST["imagename"];
   unlink($adsname);

   $bigbasename = '';
   $adid = $_POST['adsid'];
   $res = $db2->query('delete from  home_ads_images  where id="'.$adid.'" ');
   
   if($res){
       $arr =array('success'=>'true');
       echo 1;
       
   }else{
        $arr =array('success'=>'false');
       echo 0;
       
   }
   
    
}




?>
