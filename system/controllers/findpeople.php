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
    $tpl->layout->useBlock('findpeople');


	$tpl->layout->block->save('main_content');

	
	
	$tpl->display();
	
?>