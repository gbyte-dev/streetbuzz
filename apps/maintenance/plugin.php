<?php
class maintenance extends plugin{
	
	public function onPageLoad(){
		GLOBAL $page;
		GLOBAL $C;
		GLOBAL $D;
		
		if($this->getCurrentController() != 'home' && $this->getCurrentController() !='signin'){
			
			if($this->user->is_logged && !$this->user->info->is_network_admin){
				if(isset($C->MAINTENANCE)){
				
					$D->page_title = "Maintenance - service is temporary unavailable";
					if(isset($C->MAINTENANCE)){
						$D->MAINTENANCE = $C->MAINTENANCE;
					}
					$tpl = new template(array(),false);				
					$tpl->layout->useBlock('maintenance','maintenance');
					$tpl->layout->block->save('main_content');
					$html = $tpl->display(true);
					echo $html;
					exit;
				}
					
			}else{
				$designer = pageDesignerFactory::select();
				if( substr($this->getCurrentController(), 0, 6) == 'admin/' || ($page->plugin_name && $page->plugin_name=='maintenance') ){
					$this->setVar( 'administration_left_menu', $designer->createMenuLink( array('url'=>'plugin/maintenance/msg',  'title'=>'<span style="color: #ff0000">Set Maintenance</span>') ) );
				}
			}			
		}
	}
}