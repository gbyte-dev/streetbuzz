<?php
	
ob_start();
                include($C->PLUGINS_DIR.'events/static/templates/blocks/calander_template_all.php');
                $calendar_template = ob_get_clean();
				echo $calendar_template;	
	
	
?>