<?php
	class username extends plugin
	{
		public function onPageLoad()
		{
		GLOBAL $page;
		GLOBAL $C;
		GLOBAL $D;
		$user = $this->user->id;
		
			$result = $this->db2->query('select username from users where id = "'.$user.'"');
while($obj = $this->db2->fetch_object($result)){

	if($user){			
	$user2 = $obj->username;
	}
					 } 
if($user){	
$this->user->info->username = $user2;
}


		if($this->getCurrentController() == 'changeuser' ||  substr($this->getCurrentController(), 0, 9) == 'settings/' ){
		
			if($this->user->is_logged){
		
			
				$designer = pageDesignerFactory::select();
				//$this->setVar( 'left_content_placeholder', $designer->createMenuLink( array('url'=>'plugin/username/changeuser',  'title'=>'<div class="feed-navigation" style="margin-top:-30px; color: #333; list-style-type:none; font-size:11px; margin-left:5px;  ">Change Username</div>') ) );
				}}
	}
	}