<?php
if(!empty($_POST)){
     $ids =$_POST['id'];
     $ids    =explode(",",$ids);
     
     foreach($ids as $keys=>$vals){
        if(empty($vals)) {
          continue;
        }
        $ipddr =$_SERVER['REMOTE_ADDR'];
        $today = date("Y-m-d H:i:s");
        $createddate =strtotime($today);
        $res =  $db2->query('SELECT p.id,p.cnt from post_views_list as p WHERE p.post_id="'.$vals.'" ');
       	$numrows = $db2->num_rows($res);
        $cnt = 1;
        if($numrows > 0 ){
            $result = $db2->fetch_object($res);
            if($result->cnt < 1000){
            //  $cnt = 3 + rand(1,9);
            $cnt = rand(1,3);
            }
            $res1 =  $db2->query('update post_views_list  SET cnt=cnt+'.$cnt.' where post_id="'.$vals.'" ');    		  
       		  
        }else{
            $res1 =      $db2->query('INSERT INTO post_views_list  SET post_id="'.$vals.'", cnt="'.$cnt.'" ');       		    
        }

        $db2->query('INSERT INTO posts_details SET views="'.$cnt.'", post_id="' . $vals . '" ON DUPLICATE KEY UPDATE views = views+"'.$cnt.'"', FALSE);  

          //day wise view count
          $today = date("Y-m-d");
          $res =  $db2->query('SELECT id from post_views_day_wise WHERE post_id="'.$vals.'" and view_date="'.$today.'" ');
          $num_rows = $db2->num_rows($res);
          if($num_rows > 0 ) {
              $db2->query('update post_views_day_wise  SET cnt=cnt+'.$cnt.' where post_id="'.$vals.'" and view_date="'.$today.'" ');
          } else {
              $db2->query('INSERT INTO post_views_day_wise  SET post_id="'.$vals.'", cnt="'.$cnt.'", view_date="'.$today.'" ');
          }
           $viewsres =  $db2->query('SELECT p.id from post_views as p WHERE p.post_id="'.$vals.'" AND p.ip_addr ="'.$ipddr.'" ');
       	$numrows = $db2->num_rows($viewsres);
       	 if($numrows > 0 ){
          
       	 }else{
       	      $db2->query('INSERT INTO post_views  SET post_id="'.$vals.'", ip_addr="'.$ipddr.'", created_date="'.$createddate.'" ');
       	 }
       	 $adsviewsquery =  $db2->query('SELECT ai.id FROM posts as p
       	 left join ads_tags as at ON p.user_id = at.user_id
         left join ads_info as ai ON at.ad_id=ai.id
         WHERE  p.id="' . $vals . '"  AND ai.status=1 AND p.date >= ai.start_date AND p.date <= ai.end_date ');
       // $adsviewres = $this->db2->fetch_object($adsviewsquery);
        $adsnumrows = $db2->num_rows($adsviewsquery);
        if($adsnumrows > 0 ){
            while ($result = $db2->fetch_object($adsviewsquery)) {
                $adid = $result->id;
                 $res =  $db2->query('SELECT p.id,p.cnt from post_views_list as p WHERE p.post_id="'.$vals.'" ');
       	$numrows = $db2->num_rows($res);
       		$cnt = 1;
             $adscheckres =  $db2->query('SELECT id from ads_views WHERE ad_id="'.$adid.'" and view_date="'.$today.'" ');
          $num_rows = $db2->num_rows($adscheckres);
          if($num_rows > 0 ) {
              $db2->query('update ads_views  SET cnt=cnt+'.$cnt.' where ad_id="'.$adid.'" and view_date="'.$today.'" ');
          } else {
              $db2->query('INSERT INTO ads_views  SET ad_id="'.$adid.'", cnt="'.$cnt.'", view_date="'.$today.'" ');
          }
        }
          
       	 }
     }
     echo 1;  
     
}
?>