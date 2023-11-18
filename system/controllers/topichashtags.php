<?php
	global $db2;
	$w =$_POST['search'];
	$w =str_replace('#','',$w);
	if(!empty($w)){
	$r	= $db2->query('SELECT id,tag_name FROM  post_tags WHERE (tag_name LIKE "%'.$w.'%") group by tag_name ORDER BY date  DESC LIMIT 5');
   //	$users = array('users'=>array());
   while($obj = $db2->fetch_object($r)) {
      $users[] = array("value"=>$obj->id,"label"=>'#'.$obj->tag_name);
    
   }
 echo json_encode($users);
	}

?>