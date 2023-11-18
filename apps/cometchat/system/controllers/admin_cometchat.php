<?php
	
	if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	$db2->query('SELECT 1 FROM users WHERE id="'.$this->user->id.'" AND is_network_admin=1 LIMIT 1');
	if( 0 == $db2->num_rows() ) {
		$this->redirect('dashboard');
	}
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/admin.php');
	
	$tpl = new template( array('page_title' => 'CometChat', 'header_page_layout'=>'sc'));
	$tpl->initRoutine('AdminLeftMenu', array());
	$tpl->routine->load();

	$cometchat_dir = $C->PROJPATH."cometchat";
	$install = $C->SITE_URL."cometchat/install.php";
	$cometchat_admin_path = $C->SITE_URL."cometchat/admin/";	

	if(!file_exists($cometchat_dir."/license.php")) {
		$source = $C->PROJPATH."apps/cometchat/cometchat.zip";
		$dest = $C->PROJPATH;		
		
		$zip = new ZipArchive();
		if($zip->open($source) === TRUE){
			$zip->extractTo($dest);
			$zip->close();
		}
		$files = array('config.php','admin/index.php','install.php','cache/','temp/','lang/','plugins/handwrite/uploads/','plugins/filetransfer/uploads/');
        chmod($cometchat_dir,0755);
        foreach($files as $filepath){
            chmod($cometchat_dir. DS .$filepath,0777);
        }


			$cometchat_install = <<<EOD
	<iframe src="$install" style="width:1px;height:1px;border:0;"></iframe>
EOD;
		
		$tpl->layout->setVar('main_content', $cometchat_install);
	
	}

	if(file_exists($cometchat_dir."/license.php")) {
		$cometchat_admin = <<<EOD
		<iframe id="admin_iframe" src="$cometchat_admin_path" style=" width:100%;height:720px;margin-left: -84px;margin-top: -18px; border: 0; position: relative; left: 10%"  scrolling="no" onload= "iFrameSize();"></iframe>
EOD;
		$tpl->layout->setVar('main_content', $cometchat_admin);
	}
	$tpl->display();
	