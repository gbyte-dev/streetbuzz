<?php

	function loadAdminLeftMenu( $tpl, $params )
	{
		global $C, $D;
		$page 	= & $GLOBALS['page'];
		$user 	= & $GLOBALS['user'];
		$pm 	= & $GLOBALS['plugins_manager'];
	
		$tab = isset($page->request[1])? $page->request[1] : '';
		
		try {
			$lang_updates = Langpack::getNumUpgradableLangs();
		} catch ( Exception $e ) {
			$lang_updates = 0;
		}
	
		$menu = array( 	array('url' => 'admin/general', 		'css_class' => (($tab === 'general')? ' selected' : ''), 		'title' => $page->lang('admmenu_general') ),
						array('url' => 'admin/networkbranding', 'css_class' => (($tab === 'networkbranding')? ' selected' : ''),'title' => $page->lang('admmenu_networkbranding') ),
						array('url' => 'admin/themes', 			'css_class' => (($tab === 'themes')? ' selected' : ''), 		'title' => $page->lang('admmenu_themes') ),
						array('url' => 'admin/termsofuse', 		'css_class' => (($tab === 'termsofuse')? ' selected' : ''), 	'title' => $page->lang('admmenu_termsofuse') ),
						array('url' => 'admin/administrators',	'css_class' => (($tab === 'administrators')? ' selected' : ''), 'title' => $page->lang('admmenu_administrators') ),
						array('url' => 'admin/deleteuser',		'css_class' => (($tab === 'deleteuser')? ' selected' : ''), 	'title' => $page->lang('admmenu_delete_user') ),
						//array('url' => 'admin/editusers', 		'css_class' => (($tab === 'editusers')? ' selected' : ''), 		'title' => $page->lang('admmenu_editusers') ),
						array('url' => 'admin/suspendusers',	'css_class' => (($tab === 'suspendusers')? ' selected' : ''), 	'title' => $page->lang('admmenu_suspendusers') ),
						array('url' => 'admin/plugins', 'css_class' => (($tab === 'plugins')? ' selected' : ''), 'title' => $page->lang('admmenu_apps') ),
						array('url' => 'admin/languages', 'css_class' => (($tab === 'languages')? ' selected' : ''), 'title' => $page->lang('admmenu_languages'), 'tab_state' => $lang_updates ),
						array('url' => 'admin/groupcategories', 'css_class' => (($tab === 'groupcategories')? ' selected' : ''), 'title' => $page->lang('admmenu_group_categories') ),
						array('url' => 'admin/homenews', 'css_class' => (($tab === 'homenews')? ' selected' : ''), 'title' =>'Home News' ),
						array('url' => 'admin/country', 'css_class' => (($tab === 'country')? ' selected' : ''), 'title' =>'Manage Country' ),
						array('url' => 'admin/state', 'css_class' => (($tab === 'state')? ' selected' : ''), 'title' =>'Manage State' ),
						array('url' => 'admin/city', 'css_class' => (($tab === 'city')? ' selected' : ''), 'title' =>'Manage District' ),
						array('url' => 'admin/addsdisplay', 'css_class' => (($tab === 'addsdisplay')? ' selected' : ''), 'title' =>'Advt.Mgmt' ),
						array('url' => 'admin/archiving',		'css_class' => (($tab === 'archiving')? ' selected' : ''), 	'title' => 'Archive' ),
						array('url' => 'admin/topics',		'css_class' => (($tab === 'topics')? ' selected' : ''), 	'title' => 'Manage Topics' ),
						array('url' => 'admin/assigntopics',		'css_class' => (($tab === 'assigntopics')? ' selected' : ''), 	'title' => 'Assign Topics' ),
						

						


		);
		$tpl->layout->setVar( 'left_content_placeholder', $tpl->designer->createInfoBlock($page->lang('adm_menu_title'), $tpl->designer->createMenu('feed-navigation', $menu, 'administration_left_menu')) );
	
	}
	