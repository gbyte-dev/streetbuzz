<?php
	class specialgroups extends plugin
	{
		public function onPageLoad()
		{ 
			global $page;
			
			$designer = pageDesignerFactory::select();
			
			if( substr($this->getCurrentController(), 0, 6) == 'admin/' || ($page->plugin_name && $page->plugin_name=='specialgroups') ){
				$this->setVar( 'administration_left_menu', $designer->createMenuLink( array('url'=>'plugin/specialgroups/list',  'title'=>'Special Groups') ) );
			}elseif( $this->getCurrentController() == 'groups' ){
				
				$this->setVar( 'groups_top_tab_menu', $designer->createMenuLink( array('url'=>'groups/tab:special', 'css_class'=>(($page->param('tab') == 'special' && $page->param('orderby') != 'posts' && $page->param('orderby') != 'name' && $page->param('orderby') != 'date')? 'active' : ''), 'title'=>'Special Groups') ) );
			}
		}
		
		public function onPageSetCountQuery()
		{
			if( $this->getCurrentController() == 'groups' && $this->getCurrentTab() == 'special' ){
				return 'SELECT COUNT(*) FROM groups g, groups_special gs WHERE gs.group_id = g.id';
			}
		}
		
		public function onPageSetQuery()
		{
			if( $this->getCurrentController() == 'groups' && $this->getCurrentTab() == 'special' ){
				return 'SELECT g.* FROM groups g, groups_special gs WHERE gs.group_id = g.id';
			}
		}
		
	}
?>