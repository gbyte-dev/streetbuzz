<?php
global $C;
$base64image = "";
$addisplaytype="image";
$custombasevalue = $_POST["custombasevalue"];
$bigimages = [];
if($custombasevalue > -1 ){
    
    for($i=0;$i<= $custombasevalue;$i++){
        $sampeval = "baseval".$i;
        
        $img = $_POST[$sampeval];
        $jpegext =  strpos($img,"data:image/jpeg;base64,");
         $jpgext =  strpos($img,"data:image/jpg;base64,");
        $pngext =  strpos($img,"data:image/png;base64,");
       
        if($jpegext > -1 ){
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        }
        if($jpgext > -1 ){
        $img = str_replace('data:image/jpg;base64,', '', $img);
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
           if($jpgext > -1){
             $filename = uniqid() . '.jpg';
            $file = $C->STORAGE_DIR.'advs'.'/'.$filename;
          }
          if($pngext > -1){
               $filename = uniqid() . '.png';
               $file = $C->STORAGE_DIR.'advs'.'/'.$filename;
          }
         
          $bigimages[$i] = $filename;
        $success = file_put_contents($file, $data);


        
   


$smallfilename   =time().'_small'; // 5dab1961e93a7_1571494241
//$smallextension  = pathinfo( $_FILES["adsmall"]["name"], PATHINFO_EXTENSION ); // jpg
$smallextension  = pathinfo($_FILES["adbig"]["name"][$i], PATHINFO_EXTENSION ); // jpg
$smallbasename   = $smallfilename . '.' . $smallextension; // 5dab1961e93a7_1571494241.jpg

$dest = $C->STORAGE_DIR.'advs'.'/'.$smallbasename;


$bigfilename   =time().'_big'; // 5dab1961e93a7_1571494241
$bigextension  = pathinfo( $_FILES["adbig"]["name"][$i], PATHINFO_EXTENSION ); // jpg
$bigbasename   = $bigfilename . '.' . $bigextension; // 5dab1961e93a7_1571494241.jpg
if(!empty($bigimages)){
   $bigbasename =  $bigimages[$i];
}
if($bigextension == "mp4"){
   $addisplaytype = "video";

   
}
/*
echo $bigdest = $C->STORAGE_DIR.'advs'.'/'.$bigbasename;

if(move_uploaded_file($_FILES['adbig']['tmp_name'],$bigdest)){
    die('++++++++');
}else{

}*/
if(!empty($_FILES["adbig"])){
$bigfilename   =time().$i.'_big'; // 5dab1961e93a7_1571494241
$bigextension  = pathinfo( $_FILES["adbig"]["name"][$i], PATHINFO_EXTENSION ); // jpg
$bigbasename   = $bigfilename . '.' . $bigextension; // 5dab1961e93a7_1571494241.jpg
$bigdest = $C->STORAGE_DIR.'advs'.'/'.$bigbasename;
$bigimages[$i] = $bigbasename;
if(move_uploaded_file($_FILES['adbig']['tmp_name'][$i],$bigdest)){
       

    
}else{

}
}
 }
    
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
  $adid	= (int) $db2->insert_id();
   if($adid){
      if(count($bigimages) > 0){
          $cnt = count($bigimages);
          for($k=0;$k<$cnt;$k++){
            
$bigbasename   = $bigimages[$k];
         
                $res = $db2->query('INSERT INTO  home_ads_images SET  ad_id="'.$adid.'",ads_image="'.$bigbasename.'" ');  
           
              
          }
          
      }
      
  }

    $this->redirect('admin_homeaddsdisplay');






?>
