<?php
	
	require_once $C->INCPATH.'helpers/func_cache-users.php';

	$tab = 'users';
	if( $this->param('tab')){
		$tab = $this->param('tab');
	}

	$menu = array( 	array('url' => 'plugin/communityleaders/table/tab:users', 	'css_class' => (($tab === 'users')? 'active' : ''), 		'title' => 'Users' ),
					array('url' => 'plugin/communityleaders/table/tab:groups', 	'css_class' => (($tab === 'groups')? 'active' : ''), 		'title' => 'Groups' )
	);
	
	$tpl = new template( array('page_title' => 'Community Leaders', 'header_page_layout'=>'c') );
	$tpl->layout->setVar( 'main_content_placeholder', $tpl->designer->createMenu('tabs-navigation', $menu) );

	if($tab == 'users')
	{
		$statistics = array();
		
		$mostactive_users = get_mostactive_users();
		$mostcommenting_users = get_mostcommenting_users();
		$mostcommented_users = get_mostcommented_users();
		$mostfollowed_users = get_mostfollowed_users();
		$mostfollowing_users = get_mostfollowing_users();
		
		if( count($mostactive_users)>0 ){
			$statistics[] = array( 'Most Active Users', 'posts', get_mostactive_users() );
		}
		if( count($mostcommenting_users)>0 ){
			$statistics[] = array( 'Most Commenting Users', 'comments', get_mostcommenting_users() );
		}
		if( count($mostcommented_users)>0 ){
			$statistics[] = array( 'Most Commented Users', 'comments', get_mostcommented_users() );
		}
		if( count($mostfollowed_users)>0 ){
			$statistics[] = array( 'Most Followed Users', 'followers', get_mostfollowed_users() );
		}
		if( count($mostfollowing_users)>0 ){
			$statistics[] = array( 'Most Following Users', 'following', get_mostfollowing_users() );
		}
		
		if( count($statistics) > 0 ){
			foreach($statistics as $stat){
				$heading = $stat[0];
				$element = $stat[1];
				
				$tpl->layout->useBlock('empty');
				$tpl->layout->block->setVar('empty_block_content', '<div class="clear"></div><h3 style="margin: 10px 0 10px 0; font-weight: bold;">'.$heading.'</h3>');
				$tpl->layout->block->save( 'main_content' );
				
				$float = 'left-container';
				
				foreach( $stat[2] as $s ){
					
					$tpl->layout->useBlock('single-user');
					
					$tpl->layout->block->setVar( 'single_user_avatar', '<a href="'.userlink($s[0]).'"><img src="'.$C->STORAGE_URL.'avatars/thumbs1/'. $s[1] .'" alt="'.$s[0].'" /></a>');
					$tpl->layout->block->setVar( 'single_user_username', '<a href="'.userlink($s[0]).'">'.ucfirst($s[0]).'</a>' );
					$tpl->layout->block->setVar( 'single_user_activity', $s[2] .' '. $element );
					$tpl->layout->block->setVar( 'single_user_float', $float );
					
					$tpl->layout->block->save( 'main_content', true );
					
					$float = ($float == 'left-container')? 'right-container' : 'left-container';
				}
			}
		}else{
			$tpl->layout->setVar('main_content_placeholder', $tpl->designer->errorMessage('No statistics', 'No user statistics available. There is not enough data in your community.'));
		}

	}elseif($tab == 'groups')
	{
		$get_mostactive_groups		= get_mostactive_groups();
		$get_mostfollowed_groups	= get_mostfollowed_groups();
		
		$statistics = array();
		
		if( count($get_mostactive_groups) > 0 ){
			$statistics[] = array( 'Most Active Groups', 'posts', get_mostactive_groups() );
		}
		if( count($get_mostfollowed_groups) > 0 ){
			$statistics[] = array( 'Most Followed Groups', 'members', get_mostfollowed_groups() );
		}
		
		if( count($statistics) > 0 ){
			foreach($statistics as $stat){
				$heading = $stat[0];
				$element = $stat[1];
					
				$tpl->layout->useBlock('empty');
				$tpl->layout->block->setVar('empty_block_content', '<div class="clear"></div><h3 style="margin: 10px 0 10px 0; font-weight: bold;">'.$heading.'</h3>');
				$tpl->layout->block->save( 'main_content' );
				
				$float = 'left-container';
				
				foreach( $stat[2] as $s ){
					
					$tpl->layout->useBlock('single-user');
					
					$tpl->layout->block->setVar( 'single_user_avatar', '<a href="'.userlink($s[0]).'"><img src="'.$C->STORAGE_URL.'avatars/thumbs1/'. $s[1] .'" alt="'.$s[0].'" /></a>');
					$tpl->layout->block->setVar( 'single_user_username', '<a href="'.userlink($s[0]).'">'.ucfirst($s[0]).'</a>' );
					$tpl->layout->block->setVar( 'single_user_activity', $s[2] .' '. $element );
					$tpl->layout->block->setVar( 'single_user_float', $float );
					
					$tpl->layout->block->save( 'main_content', true );
					
					$float = ($float == 'left-container')? 'right-container' : 'left-container';
				}
			}
		}else{
			$tpl->layout->setVar('main_content_placeholder', $tpl->designer->errorMessage('No statistics', 'No group statistics available. There is not enough data in your community.'));
		}
	}
	
	$tpl->display();
	
	unset( $statistics ); 
	
	