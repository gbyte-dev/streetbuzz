<?php
	class reshareMyPost
	{
		private $post_resharesnum;
		private $post_reshares;
		private $network;
		private $user;
		private $post;
		private $db2;
		private $cache;
		
		public function __construct( &$post )
		{
			$this->post 	= $post;
			$this->db2 		= & $GLOBALS['db2'];
			$this->network 	= & $GLOBALS['network'];
			$this->cache 	= & $GLOBALS['cache'];
			$this->user 	= & $GLOBALS['user'];
			$this->post_reshares		= array();
			
			$this->post_reshares		= ( $post->post_type=='public' )? $this->get_post_reshares() : array();
			$this->post_resharesnum 	= ( is_array($this->post_reshares))? count( $this->post_reshares ) : 0;
		}
		
		public function could_be_reshared()
		{
			if($this->post->error){
				return FALSE;
			}
			if(!$this->user->is_logged){
				return FALSE;
			}
			if($this->post->post_type == 'private'){
				return FALSE;
			}
			if(!isset($this->post->post_user) || !$this->post->post_user){
				return FALSE;
			}
			if($this->user->id == $this->post->post_user->id){
				return FALSE; //could not reshare your own post
			}
			if( $this->is_post_reshared() ){
				return FALSE;
			}
					
			return TRUE;
		}
		public function is_post_reshared()
		{
			if( isset($this->post_reshares[$this->user->id]) ){
				return TRUE;
			}
				
			return FALSE;
		}
		
		public function get_post_reshares($force_refresh=FALSE)
		{			
			$cachekey	= 'n:'.$this->network->id.',post_reshares:'.$this->post->post_type.':'.$this->post->post_id;
			$data	= $this->cache->get($cachekey);
			if( FALSE!==$data && TRUE!=$force_refresh ) {
				return $data;
			}
			$data	= array();
			$r	= $this->db2->query('SELECT u.id, u.avatar, u.username, pl.post_id FROM users u, post_reshares pl WHERE pl.user_id=u.id AND pl.post_id="'.$this->post->post_id.'"', FALSE);
			while($o = $this->db2->fetch_object($r)) {
				$data[$o->id] = array($o->username, (!empty($o->avatar)? $o->avatar : $GLOBALS['C']->DEF_AVATAR_USER));
			}
			$this->cache->set($cachekey, $data, $GLOBALS['C']->CACHE_EXPIRE);
			return $data;
		}
		
		public function reshare_post()
		{
			if( ! $this->could_be_reshared() ) {
				return FALSE;
			}
			$this->db2->query('INSERT INTO post_reshares SET post_id="'.$this->post->post_id.'", user_id="'.$this->user->id.'", date="'.time().'" ');
			
			$this->update_reshare_count($this->post->post_id);

			$users	= array();
			foreach($this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers as $k=>$v) {
				$users[]	= intval($k);
			}
			if( $this->post->post_group ) {
				foreach($this->network->get_group_members($this->post->post_group->id) as $k=>$v) {
					$users[]	= intval($k);
				}
			}
			$users	= array_unique($users);
			$remove	= array();
			foreach($this->network->get_user_follows($this->post->post_user->id, FALSE, 'hisfollowers')->followers as $k=>$v) {
				$remove[]	= intval($k);
			}
			$users	= array_diff($users, $remove);
			$users[]	= intval($this->user->id);
			$users[]	= intval($this->post->post_user->id);
			$users	= array_unique($users);
			$this->db2->query('DELETE FROM post_userbox WHERE post_id="'.$this->post->post_id.'" AND user_id IN('.implode(', ',$users).') ');
			$insql	= array();
			foreach($users as $uid) {
				$insql[]	= '("'.$uid.'", "'.$this->post->post_id.'")';
			}
			$insql	= implode(', ', $insql);
			$this->db2->query('INSERT INTO post_userbox (user_id, post_id) VALUES '.$insql);
				
			$this->post_reshares = array();
			$this->post_reshares = $this->get_post_reshares(TRUE);
			return TRUE;
		}
		
		public function unshare_post()
		{
			if( ! $this->is_post_reshared() ) {
				return FALSE;
			}
				
			$this->db2->query('DELETE FROM post_reshares WHERE post_id="'.$this->post->post_id.'" AND user_id="'.$this->user->id.'"');
			
			$this->update_reshare_count($this->post->post_id, -1);

			$other_resharerers = array();
			$other_resharerers = array_diff(array_keys($this->post_reshares), array($this->user->id));
				
			$keep_reshared_for = array();
			foreach($this->network->get_user_follows($this->post->post_user->id, FALSE, 'hisfollowers')->followers as $k=>$v) {
				$keep_reshared_for[]	= intval($k);
			}
			foreach($other_resharerers as $resharer){
				foreach($this->network->get_user_follows($resharer, FALSE, 'hisfollowers')->followers as $k=>$v) {
					$keep_reshared_for[]	= (int) $k;
				}
			}
			if( $this->post->post_group ) {
				foreach($this->network->get_group_members($this->post_group->id) as $k=>$v) {
					$keep_reshared_for[]	= (int) $k;
				}
			}
			$keep_reshared_for	= array_unique($keep_reshared_for);
				
			$unsharerer_followers = array();
			foreach($this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers as $k=>$v) {
				$unsharerer_followers[]	= intval($k);
			}
				
			$unshare_for = array();
			$unshare_for = array_diff($unsharerer_followers, $keep_reshared_for);
				
			if(!in_array($this->user->id, $keep_reshared_for)){
				$unshare_for[] = $this->user->id;
			}
				
			if( count($unshare_for)>0 ){
				$this->db2->query('DELETE FROM post_userbox WHERE post_id="'.$this->post->post_id.'" AND user_id IN('.implode(', ',$unshare_for).') ');
			}
			unset($other_resharerers, $keep_reshared_for, $unsharerer_followers, $unshare_for);
			
			$this->post_reshares = array();
			$this->post_reshares = $this->get_post_reshares(TRUE);
			
			return TRUE;
		}

		public function update_reshare_count($post_id, $count = 1) { 
			$query = $this->db2->query('SELECT posts_detail_id FROM  posts_details WHERE post_id="' . $post_id . '" limit 1', FALSE);    
			   
			if($query->num_rows > 0){ 
				$this->db2->query('UPDATE posts_details SET reshares=reshares+('.$count.') WHERE post_id="'.$post_id.'"', FALSE); 
			} else if($count == 1) {
				$this->db2->query('INSERT INTO posts_details SET reshares=1, post_id="' . $post_id . '"', FALSE);  
			}
		}
	}