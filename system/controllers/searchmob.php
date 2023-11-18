<?php
	
/*	if( !$this->network->id ) {
		$this->redirect('home');
	}elseif(!$this->user->is_logged){
		$this->redirect('signin');
	}
	*/
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/search.php');
	
	require($C->INCPATH.'helpers/func_cache-users.php');
	
	
	
	$tpl = new template( array('page_title' => $this->lang('srch_page_title', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'c') );
   	$tpl->layout->setVar( 'left_content',$tpl->designer->createTagLinks( $network->get_recent_posttags() ) ) ;


	
	$tpl->layout->useBlock('searchmob');

	
	$table = new tableCreator();
	$tpl->layout->block->save('main_content');

	
	
	
	$tpl->display();
?>