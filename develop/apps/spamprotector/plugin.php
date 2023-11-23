<?php
	
	class spamprotector extends plugin
	{
		
		public function onPostLoad( &$var )
		{
			
			if( !$this->user->is_logged ){
				return;
			}else if( $var[0]->post_type == 'private' ){
				return;
			}
				
			$r = $this->db2->fetch_field('SELECT 1 FROM posts_spamprotector WHERE marked_by_user_id="'.$this->user->id.'" AND post_id ="'.$var[0]->post_id.'" AND post_type="'.$var[0]->post_type.'" LIMIT 1');
			
			$link_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="mark" data-value="'.htmlentities('{"activities_type":"'.$var[0]->post_type.'","activities_id":"'.$var[0]->post_id.'"}').'">Mark as spam</a>';
			if( $r ){
				$link_content = '<a href="" data-role="services" data-namespace="spamprotector" data-action="unmark" data-value="'.htmlentities('{"activities_type":"'.$var[0]->post_type.'","activities_id":"'.$var[0]->post_id.'"}').'"><em>Unmark as spam</em></a>';
			}
			
			$this->setVar( 'activity_footer', '<div class="like-list">'.$link_content.'</div>' );			
				
		}
		
		public function onPageLoad()
		{
			global $page;
				
			$designer = pageDesignerFactory::select();
				
			if( substr($this->getCurrentController(), 0, 6) == 'admin/' || ($page->plugin_name && $page->plugin_name=='spamprotector') ){
				$this->setVar( 'administration_left_menu', $designer->createMenuLink( array('url'=>'plugin/spamprotector/list',  'title'=>'SPAM Protector') ) );
			}
		}
		
		public function onPostDelete( &$var )
		{
			$this->db2->query('DELETE FROM posts_spamprotector WHERE post_id="'.$var[0]->post_id.'" AND marked_by_user_id="'.$this->user->id.'" AND post_type="'.$var[0]->post_type.'"');
		}
		
	}