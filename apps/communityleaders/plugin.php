<?php
	class communityleaders extends plugin
	{
		public function onPageLoad()
		{
			global $C;
			
			// if( $this->user->is_logged ){
			// 	$this->setVar( 'header_top_menu', '<li><a class="item-btn '.( $this->page->plugin_name === 'communityleaders'? ' active' : '').'" href="'. $C->SITE_URL .'plugin/communityleaders/table"><span>Leaders</span></li>' );
			// }
		}
	}