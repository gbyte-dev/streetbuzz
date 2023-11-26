<?php
	global $db2;
	$w =$_POST['search'];
	$w =str_replace('@','',$w);
	if(!empty($w)){
	$r	= $db2->query('SELECT id, username, fullname, avatar FROM users WHERE active=1 AND (username LIKE "%'.$w.'%" OR fullname LIKE "%'.$w.'%") ORDER BY num_followers DESC, fullname ASC LIMIT 5');
   //	$users = array('users'=>array());
   while($obj = $db2->fetch_object($r)) {
      $users[] = array("value"=>$obj->id,"label"=>'@'.$obj->username);
    
   }
 echo json_encode($users);
	}

?>