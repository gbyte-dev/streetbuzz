<?php
if(!empty($_POST)){
     $postid =$_POST['postid'];
     $adid = $_POST['adid'];
     $ipddr =$_SERVER['REMOTE_ADDR'];
     
      //day wise view count
     $today = date("Y-m-d");
     $res =  $db2->query('SELECT id from  ads_click_info WHERE post_id="'.$postid.'" and ad_id="'.$adid.'" and created_date="'.$today.'" ');
     $num_rows = $db2->num_rows($res);
     
     if($num_rows > 0 ) {
             $cnt = 1;
              $db2->query('update ads_click_info  SET cnt=cnt+'.$cnt.' where post_id="'.$postid.'" and ad_id="'.$adid.'" and created_date="'.$today.'" ');
          } else {
              $cnt = 1;
              
              $db2->query('INSERT INTO ads_click_info  SET post_id="'.$postid.'",ad_id="'.$adid.'", cnt="'.$cnt.'", created_date="'.$today.'" ');
          }
     
}
?>
