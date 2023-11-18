<?php
	class postreshare extends plugin
	{	
		private $_loaded_posts_reshares;
		private $_isMobile;
		
		public function __construct()
		{
			parent::__construct();
			
			global $C;
			
			$this->_loaded_posts_likes = array();
			$this->_isMobile = $this->isMobileRegime();
			
			include $C->PLUGINS_DIR.'postreshare/system/classes/class_reshareMyPost.php';
			
		}
		public function onPageLoad()
		{ 
			global $C;
		
			if( $this->getCurrentController() == 'dashboard' ){
				global $C;
				$this->setVar( 'dashboard_main_left_menu', '<li><a href="'.$C->SITE_URL.'dashboard/tab:reshares" class="post-reshare'.($this->getCurrentTab() == 'reshares'? ' selected' : '').'" >Reshares</a></li>' );
			}
		
		}
		
		public function onPostLoad( &$var )
		{
			if( !$this->user->is_logged ){
				return;
			}else if( $var[0]->post_type == 'private' ){
				return;
			}else if( $this->_isMobile ){
				return;
			}else if( $this->user->id == $var[0]->post_user->id ){
				return;
			}
				
			$tmp = new ReshareMyPost( $var[0] );
			$this->_loaded_posts_reshares[ $var[0]->post_id ] = $tmp->get_post_reshares(); 

			$is_reshared  = $tmp->is_post_reshared();
			$reshares = & $this->_loaded_posts_reshares[ $var[0]->post_id ];
			
			$reshares_number = is_array($reshares)? count($reshares) : 0;
			$reshare_content = '<a href="" data-role="services" data-namespace="postreshare" data-action="'.($is_reshared? 'unreshare' : 'reshare').'" data-value="'.htmlentities('{"activities_type":"'.$var[0]->post_type.'","activities_id":"'.$var[0]->post_id.'"}').'">'.($is_reshared? 'Undo reshare' : 'Reshare').'</a>';
			
			if ($reshares_number > 0) {
				$reshare_users = $is_reshared? ' (You' : '';
				$showreshares_btn = '<a class="showpostreshares_btn" href="" data-role="services" data-namespace="postreshare" data-action="showreshares" data-value="'.htmlentities('{"activities_type":"'.$var[0]->post_type.'","activities_id":"'.$var[0]->post_id.'"}').'">';
			
				if( !$is_reshared && is_array($reshares) ){
					foreach ($reshares as $usr) {
						if( $usr[0] != $this->user->info->username ){
							$reshare_users = ' (<a href="'.userlink( $usr[0] ).'">'.$usr[0].'</a>';
							break;
						}
					}
					$reshare_content .= $reshare_users . (($reshares_number>1)? ' and '.$showreshares_btn. ($reshares_number-1).' other'.($reshares_number-1>1? 's' : '').'</a>' : '') . ' reshared this )'; 
				}else{
					$reshare_content .= $reshare_users . (($reshares_number>1)? ' and '.$showreshares_btn. ($reshares_number-1).' other'.($reshares_number-1>1? 's' : '').'</a>' : '') . ' reshared this )'; 
				}
				
			}
			
			$this->setVar( 'activity_footer', '<div class="reshare-list">'.$reshare_content.'</div>' );			
			
			unset($reshares, $tmp, $reshares_number);
		}
		
		public function onPageSetQuery()
		{
			if( $this->getCurrentController() == 'dashboard' && $this->getCurrentTab() == 'reshares' ){
				return 'SELECT p.*, p.id AS pid, "public" AS `type` FROM post_reshares a, posts p WHERE a.post_id=p.id AND a.user_id="'.$this->user->id.'" ORDER BY a.id DESC ';
			}
		}
		
		public function onPostDelete( &$var )
		{
			$this->db2->query('DELETE FROM post_reshares WHERE post_id="'.$var[0]->post_id.'" AND user_id="'.$this->user->id.'"');
			
			$tmp = new ReshareMyPost( $var[0] );
			$tmp->get_post_reshares(TRUE);
		}
	}
?>