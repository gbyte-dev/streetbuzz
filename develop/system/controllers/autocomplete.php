<?php
if(isset($_POST['input'])){
$reason =$_POST['input'];
$categeory =$_POST['predict'];
$historical_query            =$db2->query('SELECT reason FROM predict_reason WHERE reason like "%'.$reason.'%" AND  categeory like"%'.$categeory.'%"  order by id desc LIMIT 5');
	


?>
<ul id="country-list">
<?php while($res = $db2->fetch_object($historical_query)){ ?>

<li onClick="selectCountry('<?php echo $res->reason; ?>');"><?php echo $res->reason; ?></li>
<?php } ?>
</ul>
<?php }
if(isset($_POST['group'])){
	$grp = $_POST['group'];
	$group_query            =$db2->query('SELECT id,title FROM  groups WHERE groupname like "%'.$grp.'%" OR 	title like "%'.$grp.'%"    order by id desc LIMIT 5');
	?>
	<ul id="country-list">
<?php while($res = $db2->fetch_object($group_query)){ ?>

<li onClick="selectgrp('<?php echo $res->title; ?>');"><?php echo $res->title; ?></li>
<?php } ?>
</ul>

	
<?php }
if(isset($_POST['poll_group'])){
	$grp = $_POST['poll_group'];
	$group_query            =$db2->query('SELECT id,title FROM  groups WHERE groupname like "%'.$grp.'%" OR 	title like "%'.$grp.'%"    order by id desc LIMIT 5');

	?>
		<ul id="country-list">
<?php while($res = $db2->fetch_object($group_query)){ ?>

<li onClick="selectpollgrp('<?php echo $res->title; ?>');"><?php echo $res->title; ?></li>
<?php } ?>
</ul>
	
<?php }
if(isset($_POST['userid'])){
$q =array();

					//insert to followers data
					if($this->user->info->is_posts_protected == 0){
						$u	= $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers;
					}else{
						$u	= array_intersect_key($this->network->get_user_follows($this->user->id, FALSE, 'hefollows')->follow_users, $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers);
					}
							
					$u	= $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers;
					foreach($u as $k=>$v) {
						if($k !=$this->user->id){

						$q[]	= $k;
						}
					}
					
					if( $group_id ) {
						$u	= $this->network->get_group_members($group_id);
						if($u) {
							foreach($u as $k=>$v) {

								$z[]	= '("'.$k.'", "'.$pid.'")';
								}
							
						}
						$q	= array_unique($q);
						$q = array_intersect($q);					
					}
					
					if( count($q) > 0 ) { 
						$q	= $q;
						//$db2->query('INSERT INTO post_userbox (user_id, post_id) VALUES '.$q);
					}
					$s = array();
					$pre   =date('Y-m-d h:i:s');
                    $datetime1 = strtotime($pre);
					for($m=0;$m<count($q);$m++){
					$users[]	=$this->network->get_username($q[$m]);
					 $time =$network->getuusserstat($q[$m]);
					 $res2  =date("Y-m-d h:i:s", $time);
                     $datetime2 = strtotime($res2);
					 $interval  = abs($datetime2 - $datetime1);
					 $minutes   = round($interval / 60); 
										 
					 if($minutes ==0){
						$s[] =$q[$m];
					 }else{
						 
					 }



						
					}

 echo json_encode($s);
 
 

	
}
if(isset($_POST['deleteid'])){
		$group_query            =$db2->query('DELETE FROM searches WHERE id="'.$_POST['deleteid'].'"');
		echo $group_query;

}
if(isset($_POST['activenotification'])){

	
	$res            =$db2->query('SELECT an.id as notification_id,an.from_user_id,p.message,p.id,u.username,u.avatar,an.noti_type,an.post_type  FROM active_notifications AS an
    inner join 	users as u ON u.id =an.from_user_id
	inner join posts as p ON p.id = an.	post_id
	 WHERE an.to_user_id="'.$this->user->id.'"');
	$result            =$db2->fetch_object($res);
	$notifytype ="notifications";$notifytype1 ="@me";
	$rescnt            =$db2->query('SELECT SUM(an.newposts) as notcnt  FROM  users_dashboard_tabs AS an
	 WHERE user_id="'.$this->user->id.'" AND(tab ="'.$notifytype.'" OR tab ="'.$notifytype1.'" ) ');
	$resultcnt            =$db2->fetch_object($rescnt);
	if($resultcnt->notcnt > 0 ){
		$data['notcnt'] = $resultcnt->notcnt;
		
	}
	if(!empty($result)){
	if($result->message !='' ){
		$data['message'] = $result->message;
	}else{
	  $data['message'] ='st200';
	}
	$data['notification_id'] = $result->notification_id;
	$data['from_user_id'] = $result->from_user_id;
	$data['id'] = $result->id;
	$data['username'] = $result->username;
	$data['avatar'] = $result->avatar;
	$data['noti_type'] = $result->noti_type;
	$data['post_type'] = $result->post_type;
	$resss = $data;
	echo json_encode($resss);
	}else{
		echo '';
	}
	
}
if(isset($_POST['notificationid'])){
	$db2->query('DELETE FROM active_notifications WHERE id="'.$_POST['notificationid'].'"');
}


 ?>
