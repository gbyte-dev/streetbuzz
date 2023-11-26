<?php
   $this->load_langfile('inside/global.php');
	$this->load_langfile('inside/dashboard.php');
	$post_type	= '';
	$post_id	= '';
	if( $this->param('post') ) {
		$post_type	= 'public';
		$post_id	= intval($this->param('post'));
	}
	elseif( $this->param('priv') ) {
		$post_type	= 'private';
		$post_id	= intval($this->param('priv'));
	}
	else {
		$this->redirect('dashboard');
	}
	
	$D->post	= new post($post_type, $post_id);
	$D->postid  = $post_id;
	$userid     = $D->post->post_user->id;

	$this->network->reset_dashboard_tabstate($this->user->id, $this->param('tab')? $this->param('tab') : 'all');

	$tpl = new template( array('page_title' => $this->lang('viewpost_page_title'), 'header_page_layout'=>'sc') );


	$tpl->initRoutine('UserLeftColumn', array( &$D->post->post_user, &$he_follows ));
	$tpl->routine->load();


    $tpl->initRoutine('Ads', array());
	$tpl->routine->load();
    $activity = activityFactory::select('dashboard');
    $activity->setTemplate( $tpl );

    $result = $activity->loadPostsView($D->postid,$userid);

    if( isset($result[1]) && $result[1] > 0 ){
		$tpl->layout->useBlock('activity-show-more');
		$tpl->layout->setVar('activities_pager_value', htmlentities('{"activities_type":"dashboard","activities_id":"'.$result[1].'","activities_tab":"'.($this->param('tab')? $this->param('tab') : 'all').'"}'));
		$tpl->layout->block->save('activity_container_show_more');
	}
    $newdate = $this->network->lastactivitydate($result[0]);
    if( $this->param('tab') !== 'group' && isset($result[0]) && $result[0] > 0 ){
		$table = new tableCreator();
		$tpl->layout->setVar('main_content_bottom', 
													$table->hiddenField( 'activities_type', 'dashboard' ) .
													$table->hiddenField( 'last_activity', intval($result[0]) ) .
							 						$table->hiddenField( 'last_activity_date', $newdate->date_lastcomment ) .

													$table->hiddenField( 'activities_tab', $this->param('tab')? $this->param('tab') : 'all' )
		);
	} elseif( isset($g) && $g ) {
		$tpl->layout->setVar('in_group', 'in '.$g->title);
	}
	
	$tpl->display(); 
	//TEMPLATE CODE END
?>