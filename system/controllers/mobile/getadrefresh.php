<?php

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
	                          order by rand() limit 3 ');
							
	

	while($fetchres = $db2->fetch_object($fetch)){ 
		 if($fetchres->avatar !=''){
	 $src=$C->SITE_URL.'storage/avatars/thumbs3/'.$fetchres->avatar;
	 
 }else{
	 $src = $C->SITE_URL.'static/images/sb-greyscale.png';
 }
	?>
	
	<div class="follow-data-bor" id="follow<?php echo $fetchres->id ?>">
			<div class="data-row-1">
			<a href="<?php echo $C->SITE_URL;?><?php echo $fetchres->username;  ?> "><img src="<?php echo $src; ?>" width="30" class="img-circle bizcard" data-userid="<?php echo $fetchres->id; ?>"  id="<?php echo $fetchres->id ?>"></a>
			</div>
			<div class="data-row-2">
			<a href="<?php echo $C->SITE_URL;?><?php echo $fetchres->username;  ?> "><p class="follow-name bizcard1" data-userid="<?php echo $fetchres->id; ?>" ><?php echo $fetchres->fullname ?></p></a> 
			<a href="<?php echo $C->SITE_URL;?><?php echo $fetchres->username;  ?> "><p class="follow-by">@<?php echo $fetchres->username?></p> </a>
			<p class="btn-follow" id="<?php echo $fetchres->id ?>"  >Follow +</p>
			</div>
			<div class="data-row-3" id="<?php echo $fetchres->id ?>">
			<img src="<?php echo $C->SITE_URL ?>storage/attachments/1/close.png">
			</div>
			</div>
		
		
  <?php  }



?>