<?php
class statistics extends plugin{

	public function onPageLoad(){
		GLOBAL $page;
		GLOBAL $C;
		GLOBAL $D;
		
		if($this->user->is_logged){
			$this->setVar( 'administration_left_menu', '<li><a class="' .( $this->page->plugin_name == 'statistics'? ' selected' : '').'" href="'. $C->SITE_URL .'plugin/statistics/admin_statistics"><span>Statistics</span></a></li>' );
		}
	}
}