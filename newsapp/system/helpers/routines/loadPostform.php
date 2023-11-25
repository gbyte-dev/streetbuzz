<?php
	function loadPostform( $tpl, $params )
	{
		global $C;
		
		$page = & $GLOBALS['page'];
		$pm = & $GLOBALS['plugins_manager'];	
		
		$save_placeholder = isset($params[0])? $params[0] : 'main_content';
		$usr = isset($params[1])? $params[1] : new stdClass();

		$tpl->layout->useBlock('activity-editor');
		
		$tpl->layout->useInnerBlock('editor-textarea');
		$tpl->layout->inner_block->saveInBlockPart('editor_textarea');
		
		$tpl->layout->useInnerBlock('editor-attachment-options');
		$tpl->layout->inner_block->saveInBlockPart('editor_attachment_options');

		if($page->params->group){
			$data_value = '{"activities_type": "group", "activities_group":"'.$page->params->group.'"}';
		}elseif(isset($usr->username)){
			$data_value = '{"activities_type": "profile", "activities_username":"'.$usr->username.'"}';
		}else{
			$data_value = '{"activities_type": "dashboard"}';
		}
		$C->POST_MAX_SYMBOLS	= 10000;

		$tpl->layout->block->setVar('editor_btn_placeholder', '<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2"><button id="show-newssection-close1" class="status-btn post-btn btn blue" data-value="'.htmlentities($data_value).'" data-role="services" data-namespace="activities" data-action="set"s>Buzz</button></div>');
		$tpl->layout->block->setVar('editor_character_limit', '<div class="characters-counter" style="display:block;" id="characterff" data-value="'.$C->POST_MAX_SYMBOLS.'">'.$C->POST_MAX_SYMBOLS.'</div>'); 
		$tpl->layout->block->setVar('editor_character_limit', '<div class="characters-counteref" style="display:none;"  id="character" data-value="'.$C->POST_MAX_SYMBOLS.'">'.$C->POST_MAX_SYMBOLS.'</div>'); 

		$tpl->layout->block->save( $save_placeholder );
		
		if( !$page->is_mobile && !isset($usr->username) ){
			$tpl->useStaticHTML();
			$tpl->staticHTML->useActivityContainer();
		}
	}