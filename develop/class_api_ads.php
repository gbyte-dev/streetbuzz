<?php

error_reporting(0);


 	class API
	{
	    
	 	
		public function __construct()
		{
   		}
	    
         
	  
	  public function validateToken($userid, $access_token)
	  {
	        global $db2, $C;
 	   	    
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
	        $sql = 'SELECT id FROM oauth_access_token WHERE user_id='.$userid.' and access_token="'.$access_token.'"  LIMIT 1';
	        
	
     	   $res = $db2->query($sql);
    	   if($db2->num_rows($res) > 0)
    	   {
    	        $obj = $db2->fetch_object($res);
    	        return true;
    	   }else{
    	       return true;
    	   }
	   
	       
	  }
	  public function loadAds($user_id,$postdate)
	  {
    	    /*Ads Logic */
    	    $array = array();
            $checkads =$this->checkcommercialadds($user_id,$postdate);
             if( !empty($checkads) ){
                $array["showcommercialadd"] = true;  
                $array["commercialadddata"] = $checkads;  
            }else{
                $array["showcommercialadd"] = false; 
                
            }
			$checkparagaphexist1 =$this->checkparagaphexist1($user_id);
		    if( !empty($checkparagaphexist1) ){
				$array["showparagraph1"] = true; 
				$array["showparagraph1data"] = $checkparagaphexist1; 
			}else{
				$array["showparagraph1"] = false; 
				
			}
			$checkparagaphexist2 =$this->checkparagaphexist2($user_id);
			if( !empty($checkparagaphexist2) ){
				$array["showparagraph2"] = true; 
				$array["showparagraph2data"] = $checkparagaphexist2; 
			}else{
				$array["showparagraph2"] = false; 
			}
            $checkparagaphexist3 =$this->checkparagaphexist3($user_id);
            if( !empty($checkparagaphexist3) ){
                $array["showparagraph3"] = true; 
                $array["showparagraph3data"] = $checkparagaphexist3; 
            }else{
            	$array["showparagraph3"] = false; 
            	    
            }
             $checkparagaphexist4 =$this->checkparagaphexist4($user_id);
            if( !empty($checkparagaphexist4) ){
                $array["showparagraph4"] = true; 
                $array["showparagraph4data"] = $checkparagaphexist4; 
            }else{
            	$array["showparagraph4"] = false; 
            	    
            }
             $checkparagaphexist5 =$this->checkparagaphexist5($user_id);
            if( !empty($checkparagaphexist5) ){
                $array["showparagraph5"] = true; 
                $array["showparagraph5data"] = $checkparagaphexist5; 
            }else{
            	$array["showparagraph5"] = false; 
            	    
            }
            $checkadexist =$this->checkadexist($user_id);
            if(!empty($checkadexist)){
            	 $array["officialadd"] = true;
            	 $array["officialadddata"] = $checkadexist; 
            }else{
            	 $array["officialadd"] = false; 
            	    
            }
            return $array;

			/* Ads end */
               
			
		 
	  }

	  	
	 /*
	   commerical add avalability checking
	   @param:post_user_id,date
	   @Author: Srinivasarao
	 */
	public function checkcommercialadds($post_userid,$postdate)
    {
         global $db2, $C;
 	   	 $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
         $adstype = 1;
       
         $r   = $db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number FROM ads_tags as at
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.start_date < "'.$postdate.'" AND ai.end_date > "'.$postdate.'"   AND ai.ads_type="'.$adstype.'" ');
        while( $res = $db2->fetch_object($r)){
			$fetchres[] = $res;
			   
		}
        return $fetchres;
     }
	  /*
	   paragraph1 add avalability checking
	   @param:post_user_id
	   @Author: Srinivasarao
	 */
      public function checkparagaphexist1($post_userid)
    {
         global $db2, $C;
 	   	 $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
         $adstype = 3;
         $r   = $db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_type,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number FROM ads_tags as at
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.ads_type="'.$adstype.'"  limit 1 ');
         $res = $db2->fetch_object($r);
         return $res;
      
        
    }
	 /*
	   paragraph2 add avalability checking
	   @param:post_user_id
	   @Author: Srinivasarao
	 */
     public function checkparagaphexist2($post_userid)
    {
         $adstype1=4;
         global $db2, $C;
 	   	 $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
         $r   = $db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_type,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number FROM ads_tags as at
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.ads_type="'.$adstype1.'"  limit 1 ');
        $res = $db2->fetch_object($r);
        return $res;
      
        
    }
	 /*
	   paragraph3 add avalability checking
	   @param:post_user_id
	   @Author: Srinivasarao
	 */
    public function checkparagaphexist3($post_userid)
    {
         $adstype1=5;
          global $db2, $C;
           $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
         $r   = $db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_type,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number FROM ads_tags as at
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.ads_type="'.$adstype1.'"  limit 1 ');
      $res = $db2->fetch_object($r);
        return $res;
      
        
    }
	 /*
	   Official add avalability checking
	   @param:post_user_id
	   @Author: Srinivasarao
	 */
    public function checkadexist($post_userid)
    {
       $adstype = 2;
        global $db2, $C;
         $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
         $r   = $db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number FROM ads_tags as at
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.ads_type="'.$adstype.'" AND ai.status=1 limit 1 ');
        
        $res = $db2->fetch_object($r);
        return $res;
    }
     /*
	   paragraph4 add avalability checking
	   @param:post_user_id
	   @Author: Srinivasarao
	 */
    public function checkparagaphexist4($post_userid)
    {
         $adstype1=6;
          global $db2, $C;
           $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
         $r   = $db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_type,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number FROM ads_tags as at
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.ads_type="'.$adstype1.'"  limit 1 ');
      $res = $db2->fetch_object($r);
        return $res;
      
        
    }
     /*
	   paragraph5 add avalability checking
	   @param:post_user_id
	   @Author: Srinivasarao
	 */
    public function checkparagaphexist5($post_userid)
    {
         $adstype1=7;
          global $db2, $C;
           $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
         $r   = $db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_type,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number FROM ads_tags as at
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.ads_type="'.$adstype1.'"  limit 1 ');
      $res = $db2->fetch_object($r);
        return $res;
      
        
    }
    /*
       paragraph5 add avalability checking
	   @param:post_user_id
	   @Author: Srinivasarao
	 */
	 public function addlinks($adid,$ipaddr,$created_date)
    {
          global $db2, $C;
           $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
           $res = $db2->query('INSERT INTO ads_links SET  ad_id="'.$adid.'", ip_addr="'.$ipaddr.'",created_date="'.$created_date.'" ');
           if($res){
                return true;
               
           }else{
               return false;
           }

      
      
        
    }
    /*
       paragraph5 add avalability checking
	   @param:post_user_id
	   @Author: Srinivasarao
	 */
	 public function addviews($postid,$ipddr,$createddate)
    {
          global $db2, $C;
           $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
           $res =  $db2->query('SELECT p.id,p.cnt from post_views_list as p WHERE p.post_id="'.$postid.'" ');
       	$numrows = $db2->num_rows($res);
       		$cnt = 1;
          if($numrows > 0 ){
              $result = $db2->fetch_object($res);
              if($result->cnt < 2000){
                  $cnt = 3 + rand(1,9);
              }
       		    $res1 =  $db2->query('update post_views_list  SET cnt=cnt+'.$cnt.' where post_id="'.$postid.'" ');  
       		   
       		  
       		}else{
       		    $res1 =      $db2->query('INSERT INTO post_views_list  SET post_id="'.$postid.'", cnt="'.$cnt.'" '); 
       		   
       		}
          //day wise view count
          $today = date("Y-m-d");
          $res =  $db2->query('SELECT id from post_views_day_wise WHERE post_id="'.$postid.'" and view_date="'.$today.'" ');
          $num_rows = $db2->num_rows($res);
          if($num_rows > 0 ) {
              $db2->query('update post_views_day_wise  SET cnt=cnt+'.$cnt.' where post_id="'.$postid.'" and view_date="'.$today.'" ');
          } else {
              $db2->query('INSERT INTO post_views_day_wise  SET post_id="'.$postid.'", cnt="'.$cnt.'", view_date="'.$today.'" ');
          }
           $viewsres =  $db2->query('SELECT p.id from post_views as p WHERE p.post_id="'.$postid.'" AND p.ip_addr ="'.$ipddr.'" ');
       	$numrows = $db2->num_rows($viewsres);
       	 if($numrows > 0 ){
          
       	 }else{
       	      $db2->query('INSERT INTO post_views  SET post_id="'.$postid.'", ip_addr="'.$ipddr.'", created_date="'.$createddate.'" ');
       	 }
       	 return true;
      
      
        
    }
    
    
	    
	} 
?>
