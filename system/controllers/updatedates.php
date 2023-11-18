<?php
$start_date    = 1617241981;$end_date    = 1619833981;$userid=16352;
	$views = $db2->query('select p.id,p.user_id,p.date,(pv.cnt) as cnt from posts as p inner join post_views_list as pv ON p.id = pv.post_id where p.user_id='.$userid.' AND   p.date >= '.$start_date.' AND p.date <= '.$end_date.' order by p.id desc');
if($views->num_rows > 0){

    while($result    = $db2->fetch_object($views)){ 
        $divide = round($result->cnt/3);

        $db2->query('update post_views_list  set cnt="'.$divide.'"  where post_id="'.$result->id.'" ');
       
    }
}else{
    echo "No records";
}




?>