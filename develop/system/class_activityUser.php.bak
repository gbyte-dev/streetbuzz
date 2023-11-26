<?php
	class activityUser extends activity
	{
		public function setPostsQuery()
		{
			if( !$this->target_user )
			{
				return;
			}

			if( empty($this->tab) ){
				$this->tab = 'posts';
			}

			switch( $this->tab )
			{
										

				case 'posts':	//default: 
								$this->query = 'SELECT p.*, p.id AS pid, "public" AS `type` 
									FROM posts p 
									LEFT JOIN posts_mentioned pm ON pm.post_id = p.id
									WHERE (p.user_id="'.$this->target_user->id.'" OR pm.user_id = "'.$this->target_user->id.'") '. $this->filter->groups('group_id') .' AND p.api_id<>2 AND p.api_id<>6 ';
								$this->query_order = ' ORDER BY p.id DESC ';
								
								break;
				case 'all':	//default:
					$this->query = 'SELECT p.*, p.id AS pid, "public" AS `type` 
									FROM posts p 
									LEFT JOIN posts_mentioned pm ON pm.post_id = p.id
									WHERE (p.user_id="'.$this->target_user->id.'" OR pm.user_id = "'.$this->target_user->id.'") '. $this->filter->groups('group_id') .' AND p.api_id<>2 AND p.api_id<>6 ';
					$this->query_order = ' ORDER BY p.id DESC ';
					
					break;
					case 'bookmarks':	
								$this->query	= 'SELECT p.id, p.id AS pid, p.api_id, p.user_id, p.group_id, "0" AS to_user, p.message, p.mentioned, p.attached, p.posttags, p.comments, p.reshares, p.date, p.ip_addr, "0" AS is_recp_del, "public" AS `type`, f.id AS fid FROM post_favs f LEFT JOIN posts p ON p.id=f.post_id WHERE f.user_id="'.$this->target_user->id.'" AND f.post_type="public"';
					$this->query_order = ' ORDER BY fid DESC ';
					break;
					 case 'agree':
				    $this->query = 'SELECT p.*, p.id AS pid, "public" AS `type` FROM post_agree a, posts p WHERE a.post_id=p.id AND a.comment_id=0 AND a.user_id="'.$this->target_user->id.'" ';
					$this->query_order = ' ORDER BY a.id DESC';
				    break;
			}
			if($this->tab =='intraday' && $this->subtab =='open' ){
				$this->query	= 'SELECT p.*
		                               FROM  posts  as p
		                              inner join post_dayfeel as pd on p.id= pd.post_id

		                                WHERE 	p.user_id="'.$this->target_user->id.'" AND result is null group by pd.post_id ';
					$this->query_order = ' ORDER BY pd.id DESC ';
				
			}elseif($this->tab =='intraday' && $this->subtab =='correct' ){
				$this->query	= 'SELECT p.*
		                               FROM  posts  as p
		                              inner join post_dayfeel as pd on p.id= pd.post_id

		                                WHERE 	p.user_id="'.$this->target_user->id.'" AND result= 1 group by pd.post_id ';
					$this->query_order = ' ORDER BY pd.id DESC ';
			}elseif($this->tab =='intraday' && $this->subtab =='incorrect' ){
				$this->query	= 'SELECT p.*
		                               FROM  posts  as p
		                              inner join post_dayfeel as pd on p.id= pd.post_id

		                                WHERE 	p.user_id="'.$this->target_user->id.'" AND result= 0 group by pd.post_id ';
					$this->query_order = ' ORDER BY pd.id DESC ';
			}
			if($this->tab =='prediction'){
				$this->query	= 'SELECT p.*
		                               FROM  posts  as p
		                              inner join post_prediction as pd on p.id= pd.post_id

		                                WHERE 	p.user_id="'.$this->target_user->id.'"  group by pd.post_id ';
					$this->query_order = ' ORDER BY pd.id DESC ';
				
			}
			if( empty( $this->query ) ){
				$this->pm->onPageSetQuery();
			
				$tmp = $this->pm->getEventResult();
				if( is_string($tmp) ){
					$this->query = $tmp;
						
					$this->pm->onPageSetQueryOrder();
					$tmp = $this->pm->getEventResult();
					if( is_string($tmp) ){
						$this->query_order = $tmp;
					}
				}
			}
		}
		
		public function loadUsers()
		{
			if( !$this->target_user )
			{
				return;
			}
			
			global $C;
			
			$ifollow 	= $this->user->is_logged? array_keys( $this->network->get_user_follows($this->user->id, TRUE, 'hefollows')->follow_users ) : array();

			if( empty($this->subtab) ){
				$this->pm->onPageSetQuery();
					
				$tmp = $this->pm->getEventResult();
				if( is_string($tmp) ){
					$num_results = $this->db2->fetch_field($tmp);
					$num_pages		= ceil($num_results / $C->PAGING_NUM_USERS);
					$pg				= $this->page->param('pg') ? intval($this->page->param('pg')) : 1;
					$pg				= min($pg, $num_pages);
					$pg				= max($pg, 1);
					$from			= ($pg - 1) * $C->PAGING_NUM_USERS;
			
					$tmp = $this->pm->onPageSetCountQuery();
					if( is_string($tmp) ){
						$res = $this->db2->query($tmp);
					}
				}
				$this->subtab = 'ifollow';
			}
			/*
			if( !isset($res) ){
				$this->subtab = 'ifollow';
			}
			*/
			switch( $this->subtab )
			{
				case 'ifollow':  
									$num_results	= $this->db2->fetch_field('SELECT COUNT(*) AS u FROM users_followed WHERE who="'. intval($this->target_user->id) .'"');
									$num_pages	= ceil($num_results / $C->PAGING_NUM_USERS);
									$pg	= $this->page->param('pg') ? intval($this->page->param('pg')) : 1;
									$pg	= min($pg, $num_pages);
									$pg	= max($pg, 1);
									$from	= ($pg - 1) * $C->PAGING_NUM_USERS;
					
									$res = $this->db2->query('SELECT u.* FROM users u, users_followed uf WHERE u.id=uf.whom AND uf.who="'. intval($this->target_user->id) .'" LIMIT '.$from.', '.$C->PAGING_NUM_USERS );
									break;
				case 'incommon':	
									$num_results	= $this->db2->fetch_field('	SELECT COUNT(*) FROM users u, users_followed uf WHERE u.id=uf.whom AND uf.who="'. intval($this->target_user->id) .'"
																				AND uf.whom IN (SELECT whom FROM users_followed WHERE who="'. intval($this->user->id) .'")');
									
									$num_pages	= ceil($num_results / $C->PAGING_NUM_USERS);
									$pg	= $this->page->param('pg') ? intval($this->page->param('pg')) : 1;
									$pg	= min($pg, $num_pages);
									$pg	= max($pg, 1);
									$from	= ($pg - 1) * $C->PAGING_NUM_USERS;
									
									$res = $this->db2->query('SELECT u.* FROM users u, users_followed uf WHERE u.id=uf.whom AND uf.who="'. intval($this->target_user->id) .'"
																				AND uf.whom IN (SELECT whom FROM users_followed WHERE who="'. intval($this->user->id) .'")');
									break;
				case 'followers': //default:
									$num_results	= $this->db2->fetch_field('SELECT COUNT(*) AS u FROM users_followed WHERE whom="'. intval($this->target_user->id) .'"');
									$num_pages	= ceil($num_results / $C->PAGING_NUM_USERS);
									$pg	= $this->page->param('pg') ? intval($this->page->param('pg')) : 1;
									$pg	= min($pg, $num_pages);
									$pg	= max($pg, 1);
									$from	= ($pg - 1) * $C->PAGING_NUM_USERS;
					
									$res = $this->db2->query('SELECT u.* FROM users u, users_followed uf WHERE u.id=uf.who AND uf.whom="'. intval($this->target_user->id) .'" LIMIT '.$from.', '.$C->PAGING_NUM_USERS);
									break;
			}
			
			if( isset($res) ){
				
				$this->num_results = $num_results;
				$this->num_pages = $num_pages;
				$this->pg = $pg;
				
				if( $this->db2->num_rows($res) > 0 ){
					while($obj = $this->db2->fetch_object($res)) {
						$this->tpl->initRoutine('SingleUser', array( &$obj, &$ifollow ));
						$this->tpl->routine->load();
					}
				}else{
					$this->tpl->layout->setVar('main_content', $this->tpl->designer->createNoPostBox('No Users', 'No Users Found'));
				}
			}
		}
		
		public function loadGroups()
		{
			if( !$this->target_user )
			{
				return;
			}
				
			global $C;
				
			$ifollow 	= $this->user->is_logged? array_keys( $this->network->get_user_follows($this->user->id, FALSE, 'hisgroups')->follow_groups ) : array();
			
			$this->filter->groups('groups_followed');
			$num_results	= $this->db2->fetch_field('SELECT COUNT(*) AS u FROM groups_followed WHERE user_id="'. intval($this->target_user->id) .'"'. $this->filter->groups('groups_followed'));
			$num_pages	= ceil($num_results / $C->PAGING_NUM_GROUPS);
			$pg	= $this->page->param('pg') ? intval($this->page->param('pg')) : 1;
			$pg	= min($pg, $num_pages);
			$pg	= max($pg, 1);
			$from	= ($pg - 1) * $C->PAGING_NUM_GROUPS;
			
			$this->filter->setPrefix('g');
			$res = $this->db2->query('SELECT g.* FROM groups g, groups_followed gf WHERE g.id=gf.group_id AND gf.user_id="'. intval($this->target_user->id) .'" '.$this->filter->groups().' LIMIT '.$from.', '.$C->PAGING_NUM_GROUPS);
			
			$float = 'left-container';
			while($obj = $this->db2->fetch_object($res)) {
				$this->tpl->initRoutine('SingleGroup', array( &$obj, &$ifollow, $float ));
				$this->tpl->routine->load();
				$float = ($float == 'left-container')? 'right-container' : 'left-container';
			}
			
			$this->num_results = $num_results;
			$this->num_pages = $num_pages;
			$this->pg = $pg;
		}
		public function loadProfile(){
			
				$this->tpl->initRoutine('EditProfile');
				$this->tpl->routine->load();
		}
		public function profileinfo(){
			
				$this->tpl->initRoutine('Profileinfo');
				$this->tpl->routine->load();
		
	
		}
		
	}