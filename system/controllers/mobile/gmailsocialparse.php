<?php
	
	if( !$this->user->is_logged ) {
		$this->redirect('home');
	}

	$this->load_langfile('inside/global.php');
	$D->ifollow	= $db2->fetch_field('SELECT COUNT(*) AS u FROM users_followed WHERE who="'.$this->user->id.'"');
	$D->followers	= $db2->fetch_field('SELECT COUNT(*) AS u FROM users_followed WHERE whom="'.$this->user->id.'"');
        $D->buzzes	= $db2->fetch_field('SELECT num_posts FROM users WHERE id="'.$this->user->id.'"');
	
	
	//TEMPLATE CODE START
	$tpl = new template( array('page_title' => $this->lang('dashboard_page_title', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );
	
	$tpl->initRoutine('DashboardLeftMenuFindpeople', array());
	$tpl->routine->load();
	$res  =explode(',',$_POST['simply']);
 foreach($res as $reskeys=>$resvals){ 
  $email= $resvals;
  $db2->query('SELECT u.id FROM users as u WHERE u.email="'.$db2->e($email).'" LIMIT 1');
  $obj = $db2->fetch_object();
  if(!empty($obj)){
     $streetuser[] = $obj->id;
  
  }

}
if(!empty($streetuser)){
$fetchres   =implode(',',$streetuser);


 $folow_res           = $db2->query('SELECT whom FROM  users_followed as uf where who="'.$this->user->id.'" AND whom IN('.$fetchres.')' );
while($fetchresass = $db2->fetch_object($folow_res)){
		$streetfetchres[] = $fetchresass->whom;
	}
}
if(!empty($streetuser)){
if(!empty($streetfetchres)){
 $finalarray = array_diff($streetuser,$streetfetchres);
 }else{
  $finalarray = $streetuser;

 }
 }
 $finarraycount = count($finalarray );

 $fetchresstreet   =implode(',',$finalarray);

 $fetch            =$db2->query('SELECT u.id,u.username,u.fullname,u.avatar,u.about_me FROM  street_suggestion as st
	                          INNER JOIN users AS u  ON st.user_id=u.id
                               where st.user_id IN('.$fetchresstreet.')							  
			       group by u.id ');
while($fetchresasw[] = $db2->fetch_object($fetch)){
	}
    $D->gmailcontacts= ($fetchresasw);
    $D->finarraycount = ($finarraycount );
			       
			   
    $tpl->layout->useBlock('gmailfindpeople');


	$tpl->layout->block->save('main_content');

	
	
	$tpl->display();
	
?>