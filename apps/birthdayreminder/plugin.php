<?php


class birthdayreminder extends plugin{

	public function onPageLoad(){
		GLOBAL $page;
		GLOBAL $C;
		$plugin_name = 'birthdayreminder';	
		if($this->user->is_logged){
			if(!isset($_SESSION['btd'])){
				$my_id = $this->user->sess['LOGGED_USER']->id;
		
				$i_follow = $this->network->get_user_follows($my_id, TRUE, 'hefollows');
				
				$id=array();
				foreach($i_follow->follow_users as $key =>$value){
					$id [] = $key;			
				}
				$num='';
				if(count($id)){
					$ids = implode(",",$id);
					$res = $this->db2->query('SELECT id,username,fullname,birthdate FROM users where id in('.$ids.')  and DATE_FORMAT(birthdate,"%c-%d") = "' . date("m-d") . '"');
					$num = $res->num_rows;
					$users ='';
					while($obj = $this->db2->fetch_object($res)){				
						$users .= '<a href="' . $C->SITE_URL . $obj->username . '">'.  $obj->fullname . " </a> / ";
					}
				 	
					$users = substr($users, 0, -2); 
					$users = '
					<div class="btd">
						<div style="color: #de5bed">
							<img style="float: left" src="' . $C->SITE_URL . 'apps/' . $plugin_name . '/static/templates/blocks/balloons.gif" alt="&raquo;" />
							<div class="btd_text">&nbspSomeone who you follow has a birthday!</div>
							<div style="clear: both"></div>
						</div>
						<div class="btd_users">' . $users . '</div>
					</div>';
					$_SESSION['btd'] = $users;
					$_SESSION['btd_today'] = date("Y-m-d");
					$_SESSION['btd_num'] = $num;
				}
			}else{			
				$users = $_SESSION['btd'];
				$num = $_SESSION['btd_num'];
				if($_SESSION['btd_today']!= date("Y-m-d")){
					unset($_SESSION['btd'],$_SESSION['btd_todate'],$_SESSION['btd_num']);
				}
			}
			if($num){
				if( substr($this->getCurrentController(), 0, 9) == 'dashboard' || ($page->plugin_name && $page->plugin_name==$plugin_name) ){			
					$this->setVar( 'left_content_bottom',$users);			
				}
			}
		}
	}
}