<?php
	abstract class activity
	{
		protected $user; 
		protected $network;
		protected $page;
		protected $filter;
		public  $tab;
		public  $subtab;
		protected $db2;
		protected $tpl;
		protected $onlygroup;
		protected $pm;
		protected $query;
		protected $query_order;
		protected $target_user;
		
		public function __construct()
		{
			$this->user 	= & $GLOBALS['user'];
			$this->network 	= & $GLOBALS['network'];
			$this->page		= & $GLOBALS['page'];
			$this->db2		= & $GLOBALS['db2'];
			$this->tpl 		= FALSE;
			$this->pm 		= & $GLOBALS['plugins_manager'];
			$this->target_user = FALSE;
			
			$this->tab 			= $this->page->param('tab')? $this->page->param('tab') : (isset($_POST['activities_tab'])? $this->db2->e($_POST['activities_tab']) : '');
			$this->subtab 		= $this->page->param('subtab')? $this->page->param('subtab') : '';
			$this->query_order 	= '';

			$this->filter = new postFilter();
			
			$this->onlygroup	= FALSE;
			if($this->tab == 'group' && $this->page->param('g')) {
				$this->onlygroup	= $this->network->get_group_by_name($this->page->param('g'));
				if( ! $this->onlygroup ) {
					$this->onlygroup	= FALSE;
				}
				elseif( ! isset( $this->network->get_user_follows($this->user->id, FALSE, 'hisgroups')->follow_groups[$this->onlygroup->id] ) ) {
					$this->onlygroup	= FALSE;
				}
			}
		}
		
		public function setTemplate( & $tpl )
		{
			$this->tpl = $tpl;
		}
		
		public function setUser( & $u )
		{
			$this->target_user = $u;
		}
		
		public function setNewetsPost( $id )
		{
			$this->newer_post_id = intval( $id );
		}
		
		public function loadPosts( $start_from = FALSE, $newer_posts = FALSE )
		{
			
			global $C;
			
			$error = array(0, 0);
			
			$this->setPostsQuery();
			if( empty($this->query) ){
				return $error;
			}else if( !$this->tpl ){
				return $error;
			}
			
			$direction = $newer_posts? '>' : '<';
			$start_from = ($start_from)? 'AND p.id' . $direction . intval($start_from) : '';
			$exclude_authors_post = $newer_posts? ' AND p.user_id<>'.$this->user->id.' ' : '';
			$last_id = 0;
			$first_id = 0;
			$post_ids = array();

			$res = $this->db2->query( $this->query. $start_from. $exclude_authors_post. $this->query_order .' LIMIT '.$C->PAGING_NUM_POSTS );

			$num_results = $this->db2->num_rows($res);
		
			if( $num_results ){
				while($obj = $this->db2->fetch_object($res)) {
					$post_ids[] = $obj->id;
					$first_id = ( $first_id == 0 )? $obj->id : $first_id;
					$last_id = $obj->id;
					$_SESSION['last_activity_id'] = 	$last_id;
						
					
					$this->tpl->initRoutine('SingleActivity', array( &$obj, FALSE ));
					$this->tpl->routine->load();
				}
									


				if( $this->tab == 'commented' && method_exists($this, 'reset_new_comment_watch') ){
					$this->reset_new_comment_watch($post_ids);
				}

				unset($post_ids);
				
			}else{
				$this->tpl->layout->setVar('main_content', $this->tpl->designer->createNoPostBox('<a href="'.$C->SITE_URL.'invitefriendscontacts">Invite</a> your friends and colleagues and make StreetBuzz a place you love to spend time. <br /><br />SB Introduction Video ', '<div class="files">
		 <video controls="" width="100%">
  <source src="'.$C->SITE_URL.'storage/attachments/1/1478332929524703.mp4" type="video/mp4">
</video>

	</div>'));
			}
					


			return ($num_results == $C->PAGING_NUM_POSTS)? array($first_id, $last_id) : array($first_id, 0);
		}
		public function loadPostsView( $postid,$userid,$start_from = FALSE, $newer_posts = FALSE )
		{
			global $C;
			
			$error = array(0, 0);
			
			$this->setPostsQueryView($postid,$userid);

			if( empty($this->query) ){
				return $error;
			}else if( !$this->tpl ){
				return $error;
			}
			
			$direction = $newer_posts? '>' : '<';
			$start_from = ($start_from)? 'AND p.id' . $direction . intval($start_from) : '';
			$exclude_authors_post = $newer_posts? ' AND p.user_id<>'.$this->user->id.' ' : '';
			$last_id = 0;
			$first_id = 0;
			$post_ids = array();
		//	echo $this->query. $start_from. $exclude_authors_post. $this->query_order .' LIMIT '.$C->PAGING_NUM_POSTS;exit;
            
            $str=$this->query. $start_from. $exclude_authors_post. $this->query_order .' LIMIT '.$C->PAGING_NUM_POSTS;
		
            $resquerymain = str_replace('AND p.post_level is null', ' ', $str);
            
		
			$res = $this->db2->query( $resquerymain );

			$num_results = $this->db2->num_rows($res);
		
			if( $num_results ){
				while($obj = $this->db2->fetch_object($res)) {

					$post_ids[] = $obj->id;
					$first_id = ( $first_id == 0 )? $obj->id : $first_id;
					$last_id = $obj->id;
					
					$this->tpl->initRoutine('SingleActivity', array( &$obj, FALSE ));
					$this->tpl->routine->load();
				}	

				if( $this->tab == 'commented' && method_exists($this, 'reset_new_comment_watch') ){
					$this->reset_new_comment_watch($post_ids);
				}

				unset($post_ids);
				
			}else{
				$this->tpl->layout->setVar('main_content', $this->tpl->designer->createNoPostBox('<a href="'.$C->SITE_URL.'invitefriendscontacts">Invite</a> your friends and colleagues and make StreetBuzz a place you love to spend time. <br /><br />SB Introduction Video ', '<div class="files">
		 <video controls="" width="100%">
  <source src="'.$C->SITE_URL.'storage/attachments/1/1478332929524703.mp4" type="video/mp4">
</video>

	</div>'));
			}


			return ($num_results == $C->PAGING_NUM_POSTS)? array($first_id, $last_id) : array($first_id, 0);
		}
		
		public function checkNewPosts()
		{
			$this->setCountNewQuery();
			
			$cnt = !empty($this->query)? $this->db2->fetch_field( $this->query ) : 0;			
			
			return $cnt;
		}
		public function setNewetsPostlastdate( $date )
		{
			$this->lastactitivitydate = ( $date );
		}
		public function loadPostslastactivitydate( $start_from = FALSE, $newer_posts = FALSE )
		{
			
			global $C;
			
			$error = array(0, 0);
			
			$this->setPostsQuery();
			if( empty($this->query) ){
				return $error;
			}else if( !$this->tpl ){
				return $error;
			}
			
			$direction = $newer_posts? '>' : '<';
			$start_from = ($start_from)? 'AND p.date_lastcomment' . $direction . intval($start_from) : '';
			$exclude_authors_post = $newer_posts? ' AND p.user_id<>'.$this->user->id.' ' : '';
			$last_id = 0;
			$first_id = 0;
			$post_ids = array();
		
			$res = $this->db2->query( $this->query. $start_from. $exclude_authors_post. $this->query_order .' LIMIT '.$C->PAGING_NUM_POSTS );
			$num_results = $this->db2->num_rows($res);
		
			if( $num_results ){
				while($obj = $this->db2->fetch_object($res)) {
					$post_ids[] = $obj->id;
					$first_id = ( $first_id == 0 )? $obj->id : $first_id;
					$last_id = $obj->id;
					$date_lastcomment[] = $obj->date_lastcomment;
					
					$this->tpl->initRoutine('SingleActivity', array( &$obj, FALSE ));
					$this->tpl->routine->load();
				}	

				if( $this->tab == 'commented' && method_exists($this, 'reset_new_comment_watch') ){
					$this->reset_new_comment_watch($post_ids);
				}

				unset($post_ids);
				
			}else{
				$this->tpl->layout->setVar('main_content', $this->tpl->designer->createNoPostBox('No Buzzes', 'No Buzzes Found'));
			}
			return ($num_results == $C->PAGING_NUM_POSTS)? array($first_id, $last_id,$date_lastcomment) : array($first_id, 0,$date_lastcomment[0]);
		}
	}