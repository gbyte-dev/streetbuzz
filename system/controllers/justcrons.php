<?php
 $cur_date = date('Y-m-d');
    $start_date = strtotime($cur_date.'00:00:01');;
    $end_date = strtotime($cur_date.'23:59:59');;
    $post        = $db2->query(" select p.id,pt.tag_name from posts as p 
     left join post_tags as pt ON p.id=pt.post_id where  p.date between $start_date AND $end_date AND 	(p.topic_id IS NULL OR p.topic_id = 0 ) order by p.id desc LIMIT 20 ");
    if($post->num_rows > 0){
        while($post_results    = $db2->fetch_object($post)){ 
             $posttagstr = "#".$post_results->tag_name;
             $postid   = $post_results->id;
              $check        = $db2->query(" select sbt.id,sbt.topic_literal,sbt.topic_description,sbt.topic_gallery from sb_topics as sbt
	     where sbt.valid_till >= $start_date AND  (locate('$posttagstr',sbt.topic_tags)>0)
	     order by sbt.id desc LIMIT 1");
	     if($check->num_rows > 0){
	        $topicres =  $db2->fetch_object($check);
	        $topic_id = $topicres->id;
	        $check        = $db2->query(" update posts set topic_id=$topic_id where id=$postid AND (topic_id IS NULL OR topic_id = 0 )");
	        
	     }
       

        }

        
    }
    echo "hiii";
 
?>
