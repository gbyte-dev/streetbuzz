<?php
class whosawmyprofile extends plugin{
	
	public function onPageLoad(){	
		GLOBAL $C;
		$maxnumber = 10;
		
		if($this->getCurrentController() == 'user'){
			if($this->user->is_logged){

				if($this->user->id == $this->page->params->user ){
					
					$res = $this->db2->query('
							SELECT 
								u.id,username, avatar, uv.date as date 
							FROM 
								users_whosawmyprofile AS uv 
							LEFT JOIN 
								users u 
							ON u.id=visitor_id 
							WHERE user_id = "' . $this->user->id . '" 
							ORDER BY date DESC'
					);
					
					if($this->db2->num_rows($res)){					
						$msg = '
						<div class="sawprofile_box">
							<div class="sawprofile_title">
								Last visitors of your profile:
							</div>';
							while($obj = $this->db2->fetch_object($res)){
								if(!$obj->avatar) $obj->avatar = '_noavatar_user.gif';							
								$msg .= '
								<a class="slimuser" title="'. $obj->username . ' visited on: ' . date('M d Y, H:m', $obj->date) . '" href="' . $C->SITE_URL . $obj->username . '">
									<img alt="" src="' . $C->SITE_URL . 'storage/avatars/thumbs3/' . $obj->avatar . '">						
								</a>';
							}
						$msg .= '	
						</div>
						';
						$this->setVar( 'left_content_bottom',$msg);
					}
										
				} else {			

					$this->db2->query('DELETE FROM users_whosawmyprofile WHERE user_id = "' . $this->page->params->user . '" AND visitor_id = "' . $this->user->id . '"');
					$this->db2->query('INSERT INTO users_whosawmyprofile (user_id,visitor_id, date) VALUES ("' . $this->page->params->user . '", "' . $this->user->id . '","' . time() . '")');
					
					$res = $this->db2->query('SELECT count(*) as c FROM users_whosawmyprofile where user_id = "' . $this->page->params->user . '"');
					$obj = $this->db2->fetch_object($res);
					if($obj->c > $maxnumber){
						$this->db2->query('
								DELETE FROM users_whosawmyprofile
								WHERE 
									user_id = "' . $this->page->params->user . '" AND 
									DATE = (
										SELECT date
										FROM (
												SELECT MIN(date) date
												FROM users_whosawmyprofile
												WHERE user_id = "' . $this->page->params->user . '"
										) foo
									)
						');
					}					
				}
			}			
		}
	}
}