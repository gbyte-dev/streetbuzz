<?php
 if(isset($_POST['followid'])){
	     $mes ='ntf_me_if_u_joins_grp';

		 $db2->query('INSERT INTO users_followed SET who="'.$this->user->id.'", whom="'.$_POST['followid'].'", date="'.time().'", whom_from_postid="'.$this->network->get_last_post_id().'" ');
	     $db2->query('INSERT INTO notifications SET notif_type="'.$mes.'", to_user_id="'.$_POST['followid'].'",from_user_id="'.$this->user->id.'", date="'.time().'" ');
		 $db2->query('INSERT INTO users_dashboard_tabs SET user_id="'.$_POST['followid'].'", tab="notifications",state=1,newposts=1  ');

	    $userdata           =   $db2->query('SELECT num_followers FROM users WHERE id="'.$db2->e($this->user->id).'" LIMIT 1');
	  $userres            =$db2->fetch_object($userdata);
	  if($userres->num_followers !=''){
	  	$usercount  = $userres->num_followers +1;
	  
	  }else{
	 	 $usercount  = 0+$usercount;
	  }
	$db2->query('UPDATE users SET num_followers='.$usercount.' WHERE id='.$this->user->id.' ');
	
	 
	 
 }
 $folow_res           = $db2->query('SELECT whom FROM  users_followed as uf where who="'.$this->user->id.'"' );
	 	
	while($fetchres = $db2->fetch_object($folow_res)){
		$res[] = $fetchres->whom;
	}
	if(!empty($res)){
	$fetchres   =implode(',',$res);
	}else{
		$fetchres ="' '";
		
		
	}
	
	
	$fetch            =$db2->query('SELECT u.id,u.username,u.fullname,u.avatar,u.about_me FROM  street_suggestion as st
	                          INNER JOIN users AS u  ON st.user_id=u.id
                              where st.user_id NOT IN('.$fetchres.')							  
							  group by u.id
	                          order by rand() limit 1 ');
							
	

	while($fetchres = $db2->fetch_object($fetch)){ 
		 if($fetchres->avatar !=''){
	 $src=$C->SITE_URL.'storage/avatars/thumbs3/'.$fetchres->avatar;
	 
 }else{
	 $src = $C->SITE_URL.'static/images/sb-greyscale.png';
 }
	?>
	<div class="follow-data-bor" id="follow<?php echo $fetchres->id ?>">
			<div class="data-row-1 circle-who-to-follow">
            <a href="<?php echo $C->SITE_URL;?><?php echo $fetchres->username; ?>">P</a>
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