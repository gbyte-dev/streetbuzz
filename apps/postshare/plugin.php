<?php
	class postshare extends plugin
	{
		public function onPostLoad( &$var )
		{
			if( $var[0]->post_type == 'private' ){
				return;
			}
			
			global $C;
			
			$this->setDelimiter('&nbsp&nbsp');
			
			$share_menu_items = array(
					array('url'=> 'http://www.linkedin.com/shareArticle?mini=true&url='.urlencode($var[0]->permalink).'&title='.urlencode(htmlspecialchars($var[0]->post_message)).'&source='.urlencode($var[0]->permalink).'&summary='.urlencode($var[0]->permalink), 'text'=> 'Linkedin'),
					array('url'=> 'http://www.stumbleupon.com/submit?url='.urlencode($var[0]->permalink), 'text'=> 'StumbleUpon'),
					array('url'=> 'http://www.myspace.com/Modules/PostTo/Pages/?u='.urlencode($var[0]->permalink), 'text'=> 'MySpace'),
					array('url'=> 'http://friendfeed.com/?url='.urlencode($var[0]->permalink).'&title='.urlencode(htmlspecialchars($var[0]->post_message)), 'text'=> 'FriendFeed'),
					array('url'=> 'http://plus.google.com/share?url='.urlencode($var[0]->permalink), 'text'=> 'Google Plus'),
			);
				
			if( $this->user->is_logged ){
				$share_menu_items[] = array('url'=> 'http://twitter.com/intent/tweet?text='.urlencode($var[0]->permalink).': '.urlencode(htmlspecialchars($this->user->info->username.': '.$var[0]->post_message)), 'text'=> 'Twitter');
				$share_menu_items[] = array('url'=>'http://www.facebook.com/sharer.php?u='.urlencode($var[0]->permalink).'&t='.urlencode(htmlspecialchars($this->user->info->username.': '.$var[0]->post_message)), 'text'=> 'Facebook');
			}
			
			$designer = pageDesignerFactory::select();
			$this->setVar( 'activity_footer', $designer->dropDownMenu('Share', $share_menu_items) );
				
			unset($share_menu_items);
		}
	}