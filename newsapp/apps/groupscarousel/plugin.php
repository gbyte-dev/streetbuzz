<?php
	class groupscarousel extends plugin
	{
		public function onPageLoad()
		{
			global $C;
				
				if(!$this->page->is_mobile == 'false') {
				
					if( $this->getCurrentController() == 'home') {
				
						if (!($this->user->is_logged)){
						
							$carousel = $this->db2->query('SELECT groups.avatar, groups.groupname FROM groups WHERE groups.avatar <> "" AND is_public=1 order by groups.id desc limit 30');

							while($obj = $this->db2->fetch_object($carousel)){
								

								$imgs[] = "<li>
								
								<div class='full' onclick= \"window.location='".$C->SITE_URL.$obj->groupname."';\" style=' cursor: pointer; background: url(".$C->STORAGE_URL."avatars/".$obj->avatar.") center; width:60px; height:60px;  background-size: cover; margin:2px;  float:left; border='0' ></div>
								<div class='full'><div style='width: 92%; padding: 4%;'>".$obj->groupname."</div></div>
								</li>";

								
							}
							
							if(!empty($imgs)){
							
								$this->setVar( 'main_content', '
								<h4 style="  font-size: 14px;  color: #fff;  text-shadow: 2px 2px 2px #000;">Latest Groups</h4>
								
								<div class="list-tile mango">
								<ul class="flip-list four-wide" data-mode="flip-list" data-delay="2000">
								
								
								
								'. implode("", $imgs).'
								
								
								</ul>
								</div>
								<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
								<script src="'.$C->SITE_URL.'apps/groupscarousel/static/js/MetroJs.js"type="text/javascript"></script>
								<script type="text/javascript">
									$(".live-tile, .flip-list").not(".exclude").liveTile();
									/*$(".full:odd").each(function(){
										var randomColor = "#" + Math.floor(Math.random()*16777215).toString(16);
										$(this).css("background-color",randomColor);
									});*/
									
								</script>
								');	
							
							}
						}
				}
			}

	}
}


