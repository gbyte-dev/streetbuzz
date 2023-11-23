<?php
	class follows extends plugin
	{
		public function onPageLoad()
		{
			global $C;



if($this->user->is_logged){
			 if( $this->getCurrentController() == 'user'){  

//aqui começa o cod que identifica se vc é seguida
$num = $this->user->id;
$num2 = $this->page->params->user ;
$iffolow = $this->db2->query("SELECT * from users_followed where who = ".$num2." and whom = ".$num);
$iffollow2 = $this->db2->num_rows($iffolow);

if ($iffollow2 >= 1 ) {
//$this->setVar( 'left_content_placeholder', '<center> <div style="background: rgba(0,0,0,0.2); margin-bottom:10px; height:23px; border-radius:6px; padding:0px; color:#000 ;  font-size:14px; "> Follows You </div></center>');
}
//aqui termina o cod
 
	

	}}
}}