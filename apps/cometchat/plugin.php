<?php
class cometchat extends plugin
{
	public function onPageLoad()
	{	
		/*
		global $C;
        $this->setVar('header_data', '<script type="text/javascript">function iFrameSize() {var f = document.getElementById("admin_iframe");f.style.width = f.contentDocument.body.scrollWidth+"px";f.style.height = f.contentDocument.body.scrollHeight+"px";}</script>');

		$cometchat_dir = $C->PROJPATH."apps/cometchat/cometchat";
		if(file_exists($cometchat_dir."/license.php")){				
			$this->setVar('header_data', "<link type=\"text/css\" href=\"".$C->SITE_URL."apps/cometchat/cometchat/cometchatcss.php?t=".time()."\" rel=\"stylesheet\" charset=\"utf-8\" />" );
	    	$this->setVar('header_data','<script type="text/javascript" src="'.$C->SITE_URL.'apps/cometchat/cometchat/cometchatjs.php?t='.time().'" charset="utf-8"></script>');	    	
    	}
		
		$designer = pageDesignerFactory::select();
		$this->setVar( 'administration_left_menu', $designer->createMenuLink( array('url'=>'plugin/cometchat/admin',  'title'=>'CometChat') ) );
		*/
	}
	
}
