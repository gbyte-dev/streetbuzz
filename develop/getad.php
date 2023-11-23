<?php
//error_reporting(E_ALL);
require_once $C->INCPATH.'helpers/func_main.php';
if(isset($_POST['followid'])){
	$followid = $_POST['followid'];
	$res = $db2->query('SELECT id FROM users_followed WHERE who=' . $this->user->id . ' AND whom=' . $followid . '   LIMIT 1');
	if ($db2->num_rows($res) == 0) {

		$mes ='ntf_me_if_u_follows_me';

		$db2->query('INSERT INTO users_followed SET who="'.$this->user->id.'", whom="'.$followid.'", date="'.time().'", whom_from_postid="'.$this->network->get_last_post_id().'" ');
		$db2->query('INSERT INTO notifications SET notif_type="'.$mes.'", to_user_id="'.$followid.'",from_user_id="'.$this->user->id.'", date="'.time().'" ');
		$notifi ="notifications";
		$userres            = $db2->query('SELECT user_id,newposts FROM  users_dashboard_tabs  where user_id="'.$followid.'" AND tab="'.$notifi.'" ' );
		$useridfollow = $db2->fetch_object($userres);
		if($useridfollow->user_id ==""){
			$db2->query('INSERT INTO users_dashboard_tabs SET user_id="'.$followid.'", tab="notifications",state=1,newposts=1  ');
		}else{
			$postcnt = $useridfollow->newposts+1;
			$db2->query('UPDATE users_dashboard_tabs SET  newposts='.$postcnt.' WHERE  user_id='.$followid.' AND tab="'.$notifi.'" ');
		}

		$userdata           = $db2->query('SELECT num_followers FROM users WHERE id="'.$db2->e($followid).'" LIMIT 1');
		$userres            = $db2->fetch_object($userdata);
		if($userres->num_followers !=''){
			$usercount  = $userres->num_followers +1;	
		}else{
			$usercount  = 0+$usercount;
		}
		$db2->query('UPDATE users SET num_followers='.$usercount.' WHERE id='.$followid.' ');

		$data = array();
		$data['id'] = $this->user->id;
		$data['notification_type'] = 'follow';
		send_push_notification($followid, $data);
		
	}
}
 	$folow_res           = $db2->query('SELECT GROUP_CONCAT(whom) AS whom_ids FROM  users_followed as uf where who="'.$this->user->id.'"' );
 	$obj_user = $db2->fetch_object($folow_res);
 	$fetchres ="' '";
	if ($obj_user->whom_ids != "") {
	    $fetchres = $obj_user->whom_ids;
	}
	
	$fetch            =$db2->query('SELECT u.id,u.username,u.fullname,u.avatar,u.about_me FROM  street_suggestion as st
	                          INNER JOIN users AS u  ON st.user_id=u.id
                              where st.user_id NOT IN('.$fetchres.')							  
							  group by u.id
	                          order by rand() limit 1 ');
							
	

	while($fetchres = $db2->fetch_object($fetch)){ 
		 if($fetchres->avatar !=''){
	 $src=$C->SITE_URL.'storage/avatars/thumbs1/'.$fetchres->avatar;
	$img ='<div class="data-row-1" data-userid="'.$fetchres->id.'"><img src="'.$src .'" class="img-circle bizcard" data-userid="'.$fetchres->id.'" width="50" ></div>';
	 
 }else{
	 $src = $C->SITE_URL.'static/images/sb-greyscale.png';
			 	 	$img ='<div class="data-row-1 circle-who-to-follow" data-userid="'.$fetchres->id.'">'.ucfirst(substr($fetchres->username,0,1)).'</div>';

 }
	?>
	<div class="follow-data-bor" id="follow<?php echo $fetchres->id ?>">
			<div class="data-row-1 circle-who-to-follow">
            <a href="<?php echo $C->SITE_URL;?><?php echo $fetchres->username; ?>"><?php echo $img; ?></a>
            </div>
			<div class="data-row-2">
			<a href="<?php echo $C->SITE_URL;?><?php echo $fetchres->username;  ?> "><p class="follow-name bizcard1" data-userid="<?php echo $fetchres->id; ?>" ><?php echo $fetchres->fullname ?></p></a> 
			<a href="<?php echo $C->SITE_URL;?><?php echo $fetchres->username;  ?> "><p class="follow-by">@<?php echo $fetchres->username?></p></a> 
			<button class="btn btn-default btn-xs btn-follow"  id="<?php echo $fetchres->id ?>">Follow +</button>
			</div>
			<div class="data-row-3" id="<?php echo $fetchres->id ?>">
			<img src="<?php echo $C->SITE_URL ?>storage/attachments/1/close.png" class="pull-right">
			</div>
			</div>
		
		
  <?php  }



?>