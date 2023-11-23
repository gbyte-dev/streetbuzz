<?php
	function loadAds( $tpl, $params )
	{
		global $C;
		
		$page = & $GLOBALS['page'];
		$pm = & $GLOBALS['plugins_manager'];
				$network 	= & $GLOBALS['network'];

		
		$save_placeholder = isset($params[0])? $params[0] : 'main_content';
		$usr = isset($params[1])? $params[1] : new stdClass();



		if($page->params->group){
			$data_value = '{"activities_type": "group", "activities_group":"'.$page->params->group.'"}';
		}elseif(isset($usr->username)){
			$data_value = '{"activities_type": "profile", "activities_username":"'.$usr->username.'"}';
		}else{
			$data_value = '{"activities_type": "dashboard"}';
		}
		$C->POST_MAX_SYMBOLS	= 2000;

	$tpl->layout->setVar( 'left_content',$tpl->designer->createTagLinks( $network->get_recent_posttags() ) ) ;


		if( !$page->is_mobile && !isset($usr->username) ){
			$tpl->useStaticHTML();
			$tpl->staticHTML->useActivityContainer();
		}
	}