<?php
if(empty($_POST['language'])){
                $yes ='YES';
                 $cnt =$_POST['cnt'];
                $state ='';
                
                $res =	$db2->query('SELECT p.id,p.message,pa.type,pa.data,u.username FROM homenews as h 
          	inner join posts as p ON p.id=h.post_id 
          	inner join users as u ON p.user_id=u.id
          	left join posts_attachments as pa ON pa.post_id=p.id WHERE h.main_or_not ="'.$yes.'" group by p.id order by p.id desc  LIMIT '.$cnt.',5');
          
          	
          	
                
                
            }else{
                $state =$_POST['language'];
                $cnt =$_POST['cnt'];
                if($_POST['search'] =='0'){
                    $res =	$db2->query('SELECT p.id,p.message,pa.type,pa.data,u.username FROM homenews as h 
          	inner join posts as p ON p.id=h.post_id 
          	inner join users as u ON p.user_id=u.id
          	left join posts_attachments as pa ON pa.post_id=p.id WHERE h.language="'.$state.'" group by p.id order by p.id desc LIMIT '.$cnt.',5');
                
                }else{
                     $res =	$db2->query('SELECT p.id,p.message,pa.type,pa.data,u.username FROM posts as p 
          	inner join users as u ON p.user_id=u.id
          	left join posts_attachments as pa ON pa.post_id=p.id WHERE p.message like "%'.$state.'%" group by p.id order by p.id desc LIMIT '.$cnt.',5');
                }
               
               
          	}
          	$numrows = $db2->num_rows($res);
          	if($numrows > 0 ){
          	    $str ='';
            while($result    = $db2->fetch_object($res)){ 
               $mes =  substr( $result->message,0,500);
               $siteurl =$C->SITE_URL.'view/post:'.$result->id;
               $username = $result->username;;
               $type = $result->type;
                 if($type =='image'){
         $a = unserialize($result->data);
         $image =$a->file_thumbnail;
         $thumbimg =$C->SITE_URL.'storage/attachments/1/'. $image;
                 }elseif($type =='file'){ 
                    $thumb =$result->thumb;
                       if(!empty($thumb)){
           $thumbimg = $C->SITE_URL.'storage/thumbs/'.$thumb;

            
        }else{
             $thumbimg = $C->SITE_URL.'storage/thumbs/no-preview.jpg';
            
        }

                 }

          	
$str .='<div class="bd-example newsrow">
<div class="row">
<div style="width:74%;">
<div style="font-weight:bold;" class="p-2">
'.$mes.'..<a target="_blank" href="'.$siteurl.'">Read More</a> </div>
 <div class="p-2">
 By <a target="_blank" href="'.$siteurl.'">@'.$username.'</a>
 </div>
 </div>
 
 <div style="width:25%">
           <img width="80%" style="border-radius:11px;" alt="Image" src="'.$thumbimg.'">

     
        
 </div>
</div>
</div>';
}
$res =1;
}else{
    $res =0;
}
echo $str;
?>