<?php
class stickymessage extends plugin{
	
	public function onPageLoad(){
		GLOBAL $page;
		GLOBAL $C;
		GLOBAL $D;
		
		if($this->getCurrentController() != 'home' && $this->getCurrentController() !='signin'){
			if($this->user->is_logged){
				
				$designer = pageDesignerFactory::select();
				$this->setVar( 'administration_left_menu', $designer->createMenuLink( array('url'=>'plugin/stickymessage/stickymessage_controller',  'title'=>'<span style="color: #3da03a">Set Stickymessage</span>') ) );

					
				$res = $this->db2->query("SELECT * FROM settings WHERE word='STICKYMESSAGE'");
				$obj = $this->db2->fetch_object($res);
			
				if($obj){
					$status = "<div style='color: #ff0000; font-size: 20px; font-weight: bold'>Sticky message is set and visible for your users</div>";
					$value = json_decode($obj->value);				
					
					$msg = "<div id='stickymessage_bck' class='stickymessage' style='background: " .  $value->backcolor . "'>
								<div style='color: #de5bed'>		
									<div id='stickymessage_preview' class='stickymessage_text' style='color: " . $value->textcolor . "'>" . $value->stickymsg . "</div>
								</div>
							</div>";
					
					if( substr($this->getCurrentController(), 0, 9) == 'dashboard' ){
						$this->setVar( 'left_content_bottom',$msg);
					}
				}
			}			
						
		}
	}
}
