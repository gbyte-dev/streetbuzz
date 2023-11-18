<?php
	
	/*if( !$this->network->id ) {
		$this->redirect('home');
	}elseif(!$this->user->is_logged){
		$this->redirect('signin');
	}*/
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/search.php');
	
	require($C->INCPATH.'helpers/func_cache-users.php');
	
	$D->tab = 'posts';
	if( $this->param('tab') ) {
		$D->tab	= $this->param('tab');
	}
if(!empty($_POST['serchtab'])){
		$D->tab = $_POST['serchtab'];
		
}elseif(!empty($_POST['defaultval'])){
	$D->tab = $_POST['defaultval'];

	
}
	$C->stype="posts";

	
	if(isset($_POST['lookin'])){
		$D->tab = $_POST['lookin'];
	}

	if( isset($_POST['lookfor']) && !empty($_POST['lookfor']) ){
		$redirect_url = $C->SITE_URL.'search/tab:'.$D->tab;
		if( isset($_POST['lookfor']) && !empty($_POST['lookfor']) ){
			$redirect_url .= '/s:'.(($_POST['lookfor']));	
		}
		if( isset($_POST['puser']) && !empty($_POST['puser']) ){
			$redirect_url .= '/puser:'.urlencode(trim($_POST['puser']));
		}
		if( isset($_POST['pgroup']) && !empty($_POST['pgroup']) ){
			$redirect_url .= '/pgroup:'.urlencode(trim($_POST['pgroup']));
		}
		if( isset($_POST['pcomments']) && $_POST['pcomments'] == 1 ){
			$redirect_url .= '/pcomments:true';
		}
		
		$this->redirect( $redirect_url );
	}
	if($this->param('tab') =="location"){
		$search_string = $this->param('s')? ($this->param('s')) : $this->lang('srch_get_string');
		/* $r      = $this->db2->query('SELECT p.location FROM posts as p
	                                     WHERE p.id="'. $search_string . '" ', FALSE);
        $result = $this->db2->fetch_object($r);*/
       // $D->search_string =  $result->location;
		$D->search_string =  urlencode($search_string);
		//$geolocation      =$buff->geolocation($search_string);

		
	}else{
	$D->search_string = $this->param('s')? urldecode($this->param('s')) : $this->lang('srch_get_string');
	}
	
	if(isset($_POST['save_search'])){
		$saved_search = $this->user->save_search($D->search_string, $D->tab, $this->param('puser'), $this->param('pgroup'));
	}
	
	$last_result = 0;
	
	$tpl = new template( array('page_title' => $this->lang('srch_page_title', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );
 $tpl->initRoutine('Ads', array());
	$tpl->routine->load();
	if($this->user->is_logged){
	
	$menu = array( 	array('url' => 'search/tab:posts'.($this->param('s')? '/s:'.$this->param('s') : ''), 	'css_class' => (($D->tab === 'posts')? ' active' : ''), 	'title' => 'Buzzes' ),
					array('url' => 'search/tab:users'.($this->param('s')? '/s:'.$this->param('s') : ''), 	'css_class' => (($D->tab === 'users')? ' active' : ''), 	'title' =>'Accounts'  ),
					array('url' => 'search/tab:groups'.($this->param('s')? '/s:'.$this->param('s') : ''), 	'css_class' => (($D->tab === 'groups')? ' active' : ''),  'title' => $this->lang('srch_tab_groups') ),
					array('url' => 'search/tab:tags'.($this->param('s')? '/s:'.$this->param('s') : ''), 	'css_class' => (($D->tab === 'tags')? ' active' : ''), 	'title' => $this->lang('srch_tab_tags') ),
					array('url' => 'search/tab:location'.($this->param('s')? '/s:'.$this->param('s') : ''), 	'css_class' => (($D->tab === 'location')? ' active' : ''), 	'title' =>'Location' ),

	);
	}
	if($D->tab =='posts'){
	
	$tpl->layout->setVar('tags','yes');
	}
	
	$tpl->layout->setVar( 'main_content_placeholder', $tpl->designer->createInfoBlock('<div class="title-mobile-wrapper">
<span class="title-mob-main">Search Results</span> 
»
<span class="title-mob-sub">'.$D->search_string.'</span>
</div>', $tpl->designer->createMenu('tabs-navigation', $menu)) );
	$tpl->useStaticHTML();
	$tpl->staticHTML->useActivityContainer();
	
	
	switch( $D->tab ){
		case 'posts':
			
			$activity = activityFactory::select('search');
			$activity->setTemplate( $tpl );
			$result = $activity->loadPosts();
			
			if( isset($result[1]) && $result[1] > 0 ){
				$tpl->layout->useBlock('activity-show-more');
				$tpl->layout->setVar('activities_pager_value', htmlentities('{"activities_type":"search","activities_id":"'.$result[1].'","activities_tab":"posts","activities_search":"'.urlencode($D->search_string).'"}'));
				$tpl->layout->block->save('activity_container_show_more');
			}
			break;
		
		case 'users':
			$activity = activityFactory::select('search');
			$activity->setTemplate( $tpl );
			$activity->loadUsers($this->param('pg'));
				
			break;
		
		case 'groups':
			$activity = activityFactory::select('search');
			
			$activity->setTemplate( $tpl );
			$activity->loadGroups($this->param('pg'));
		
			break;
			
		case 'tags':
			$activity = activityFactory::select('search');
			$activity->setTemplate( $tpl );		
			$result = $activity->loadPosts();
			
			if( isset($result[1]) && $result[1] > 0 ){
				$tpl->layout->useBlock('activity-show-more');
				$tpl->layout->setVar('activities_pager_value', htmlentities('{"activities_type":"search","activities_id":"'.$result[1].'","activities_tab":"tags","activities_search":"'.urlencode($D->search_string).'"}'));
				$tpl->layout->block->save('activity_container_show_more');
			}
			
			break;
			case 'location':
			$activity = activityFactory::select('search');
			$activity->setTemplate( $tpl );		
			$result = $activity->loadPosts();
			
			
			if( isset($result[1]) && $result[1] > 0 ){
				$tpl->layout->useBlock('activity-show-more');
				$tpl->layout->setVar('activities_pager_value', htmlentities('{"activities_type":"search","activities_id":"'.$result[1].'","activities_tab":"tags","activities_search":"'.urlencode($D->search_string).'"}'));
				$tpl->layout->block->save('activity_container_show_more');
			}
			
			break;
	}

	$tpl->layout->useBlock('empty');

	  if($this->user->is_logged){
	$table = new tableCreator();
	$rows = array(
			$table->textField('', $this->lang('srch_posts_string')),
			$table->inputField( '', 'lookfor', htmlspecialchars($D->search_string) ),
			$table->textField('', $this->lang('srch_posts_user')),
			$table->inputField( '', 'puser', htmlspecialchars($this->param('puser')) ),
			$table->textField('', $this->lang('srch_posts_group')),
			$table->inputField( '', 'pgroup', htmlspecialchars($this->param('pgroup')) ),
			//$table->checkBox( '', array( array('pcomments', 1, $this->lang('srch_posts_ptp_comments'), $this->param('pcomments')? 1 : 0 ) ) ),
			$table->submitButton( 'sbm', $this->lang('srch_posts_submit') ),
	);
	
	$tpl->layout->setVar( 'left_content',  $table->createTableInput( $rows ));
	
	$saved_searches = $this->user->get_saved_searches();
	$saved_searches_content = '';
	
	if(is_array($saved_searches) && !empty($saved_searches)){
		$weqe = array();
	
		foreach($saved_searches as $key => $value){
			$weqe[] = array('url' => $value->search_url,'id'=>$value->id,'css_class' => ($value->search_string == $D->search_string? 'active' : ''),'title' => $value->search_string );
		}
		$saved_searches_content .= $tpl->designer->createMenusearch('block-navigation', $weqe, 'groups_top_tab_menu');
	}
	
	

	


	$table = new tableCreator();
	$rows = array(
			$table->submitButton( 'save_search', $this->lang('srch_posts_save_add'), 'green' )
	);
	
	$saved_searches_content .= $table->createTableInput( $rows );
	  }
	

	
	$tpl->layout->setVar( 'left_content', $tpl->designer->createInfoBlock($this->lang('stch_posts_saved'), $saved_searches_content, true));

	
	
	
	$tpl->display();
?>