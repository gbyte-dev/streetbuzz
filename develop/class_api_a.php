<?php
class API
{
	public function __construct()
	{
	}

	public function getPostComments($postid)
	{
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
		//$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);
		$ip_address = ip2long($_SERVER['REMOTE_ADDR']);
		$res = $db2->query('Select p.id as commentid,user_id, message,p.date,u.username,u.avatar,u.fullname from posts_comments p LEFT JOIN users u ON u.id=p.user_id where post_id  =  ' . $postid . ' order by p.date desc');

		return $res->fetch_all(MYSQLI_ASSOC);
	}

	public function getCommentsCount($postid)
	{
		global $db2, $C;

		//$db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
		//SELECT * FROM `posts` ORDER BY `posts`.`id` DESC

		$query = 'SELECT Count(id) as cnt from posts_comments where post_id=' . $postid;
		if (!empty($query)) {
			$res = $db2->query($query);
			if ($db2->num_rows($res) > 0) {
				$obj = $db2->fetch_object($res);
				return intval($obj->cnt);
			}
		}
		return 0;
	}

	public function getLikes($postid)
	{
		global $db2, $C;

		//$db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);

		$query = 'SELECT Count(post_id) as cnt from post_likes where post_id=' . $postid;
		if (!empty($query)) {
			$res = $db2->query($query);
			if ($db2->num_rows($res) > 0) {
				$obj = $db2->fetch_object($res);
				return intval($obj->cnt);
			}
		}
		return 0;
	}

	public function generateToken($who)
	{
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
		$oauth_access_token = $this->generate_request_token();

		//echo 'SELECT id FROM oauth_access_token WHERE user_id="'.$userid.'"  LIMIT 1';

		$res = $db2->query('SELECT id FROM oauth_access_token WHERE user_id=' . $who . '  LIMIT 1');

		if ($db2->num_rows($res) > 0) {
			$obj = $db2->fetch_object($res);

			$res = $db2->query('Update oauth_access_token SET access_token = "' . $oauth_access_token . '" WHERE  id="' . intval($obj->id) . '"');

			return $oauth_access_token;
		} else {
			$res = $db2->query('Insert into oauth_access_token (access_token,user_id) VALUES ("' . $oauth_access_token . '", "' . $who . '")');
			return $oauth_access_token;
		}
	}


	function generate_request_token()
	{

		$request_token = '';
		$request_token = substr(md5(rand() . time() . rand()), 0, 22);
		return $request_token;
	}

	public function validateToken($userid, $access_token)
	{
		global $db2, $C;

		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
		$sql = 'SELECT id FROM oauth_access_token WHERE user_id=' . $userid . ' and access_token="' . $access_token . '"  LIMIT 1';


		$res = $db2->query($sql);
		if ($db2->num_rows($res) > 0) {
			$obj = $db2->fetch_object($res);
			return true;
		}

		return true;
	}



	public function follow($who, $whom, $status)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$sql_user = 'SELECT * FROM users WHERE  id="' . $who . '" AND active=1  LIMIT 1';
		$res_user = $db2->query($sql_user);
		$obj_user = $db2->fetch_object($res_user);

		if ($obj_user->id == "") {
			return "User Not Available";
		}

		$sql_user1 = 'SELECT * FROM users WHERE  id="' . $whom . '" AND active=1  LIMIT 1';
		$res_user1 = $db2->query($sql_user1);
		$obj_user1 = $db2->fetch_object($res_user1);

		if ($obj_user1->id == "") {
			return "User Not Available";
		}
		if ($obj_user1->id != "" && $obj_user->id != "") {

			$whom_from_postid = 0;
			$time = time();

			if ($status == "1") {

				$res = $db2->query('SELECT id FROM users_followed WHERE who=' . $who . ' AND whom=' . $whom . '   LIMIT 1');
				if ($db2->num_rows($res) == 0) {
					$db2->query('Insert into users_followed (who,whom,date,whom_from_postid) VALUES ("' . $who . '", "' . $whom . '", "' . $time . '", "' . $whom_from_postid . '")');
					
					$this->update_follower_count($whom);

					$data = array();
					$data['id'] = $who;
					$data['notification_type'] = 'follow';
					$data['username'] = $obj_user->username;
					send_push_notification($whom, $data);
				}


				$r      = $db2->query('select count(id) as sharecount  FROM users_followed WHERE  who="' . $who . '"', FALSE);
				$result = $db2->fetch_object($r);				

				return $result->sharecount;
			}

			if ($status == "0") {
				$db2->query('DELETE FROM users_followed WHERE who="' . $who . '" AND whom="' . $whom . '" LIMIT 1', FALSE);
				$this->update_follower_count($whom, -1);
				$r      = $db2->query('select count(id) as sharecount  FROM users_followed WHERE  who="' . $who . '"', FALSE);
				$result = $db2->fetch_object($r);
				return $result->sharecount;
			}
		}
	}


	public function typeofpostofpoll($postid)
	{
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$res	= $db2->query('select poll_id from polls where posts_id="' . $postid . '" ', FALSE);
		return $res;
	}

	public function typeofpostofevent($postid)
	{
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$res	= $db2->query('select id from event_posts where post_id="' . $postid . '" ', FALSE);
		return $res;
	}

	public function get_post_likes($force_refresh = FALSE)
	{
		if ($this->error) {
			return FALSE;
		}
		if ($this->is_system_post) {
			return FALSE;
		}

		$cachekey = 'n:' . $this->network->id . ',post_likes:' . $this->post_type . ':' . $this->post_id;
		$data     = $this->cache->get($cachekey);
		if (FALSE !== $data && TRUE != $force_refresh) {
			return $data;
		}
		$data = array(
			'post' => array()
		);
		$r    = $db2->query('SELECT u.id, u.avatar, u.username, pl.post_id, pl.comment_id FROM users u, post_likes pl WHERE pl.user_id=u.id AND post_id="' . $this->post_id . '" ', FALSE);
		while ($o = $db2->fetch_object($r)) {
			if ($o->comment_id == 0) {
				$data['post'][$o->id] = array(
					$o->username,
					(!empty($o->avatar) ? $o->avatar : $GLOBALS['C']->DEF_AVATAR_USER)
				);
			} elseif ($o->comment_id > 0) {
				$data['comment_' . $o->comment_id][$o->id] = array(
					$o->username,
					(!empty($o->avatar) ? $o->avatar : $GLOBALS['C']->DEF_AVATAR_USER)
				);
			}
		}
		$this->cache->set($cachekey, $data, $GLOBALS['C']->CACHE_EXPIRE);
		return $data;
	}


	public function like_post($userid, $postid, $action)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$sql_user1 = 'SELECT * FROM posts WHERE  id="' . $postid . '"  LIMIT 1';
		$res_user1 = $db2->query($sql_user1);
		$obj_user1 = $db2->fetch_object($res_user1);
		$postuser = $obj_user1->id;


		if ($action == "1") {
			$sql_user2 = 'SELECT * FROM posts WHERE  id="' . $postid . '"  LIMIT 1';
			$res_user2 = $db2->query($sql_user2);
			$posttype = $db2->fetch_object($res_user2);


			if ($posttype->id != "") {
				$type = "event";
			} else {

				$sql_user3 = 'SELECT * FROM posts WHERE  id="' . $postid . '"  LIMIT 1';
				$res_user3 = $db2->query($sql_user3);
				$posttype = $db2->fetch_object($res_user3);


				if ($posttype->id != "") {
					$type = "poll";
				} else {



					$sql_user3 = 'SELECT type,data from posts_attachments where post_id  ="' . $postid . '"  LIMIT 1';
					$res_user3 = $db2->query($sql_user3);
					$activitiestype = $db2->fetch_object($res_user3);

					if (!empty($activitiestype)) {

						if ($activitiestype->type == "videoembed") {
							$type = "video link";
						} elseif ($activitiestype->type == "image") {
							$type = "image";
						} elseif ($activitiestype->type == "file") {
							$str = (unserialize($activitiestype->data));
							$ext = pathinfo($str->file_original, PATHINFO_EXTENSION);
							if ($ext == 'wmv' || $ext == 'mp4' || $ext == 'avi' || $ext == 'mov' || $ext == 'qt') {
								$type = "video";
							} else {
								$type = "file";
							}
						}
					} else {
						$type = "buzz";
					}
				}
			}

			$notifytype = "like";
			$standardnotifytype = "ntf_me_on_post_like";


			$date = time();
			$db2->query('insert into active_notifications  values ("","","' . $userid . '","' . $postuser . '","' . $postid . '","' . $notifytype . '","' . $type . '","' . $date . '")');

			$db2->query('insert into post_likes  values ("","' . '0' . '","' . $userid . '","' . $postid . '","' . '0' . '","' . $date . '")');

			$groupid = 0;
			$notif_object_type = 'post';
			$notif_object_id = $userid;

			$db2->query('insert into notifications  (notif_type, to_user_id, in_group_id, from_user_id,notif_object_type,notif_object_id,date,noti_postid) values  ("' . $standrdtype . '","' . $ownuserid . '","' . $groupid . '","' . $userid . '","' . $notif_object_type . '","' . $notif_object_id . '","' . $date . '","' . $postid . '")');
			$postlike      = $this->get_like_count($postid);
			return $postlike;
		}

		if ($action == "0") {

			$db2->query('DELETE FROM post_likes WHERE user_id="' . $userid . '" AND post_id="' . $postid . '" AND comment_id=0 LIMIT 1', FALSE);
			$postlike      = $this->get_like_count($postid);
			return $postlike;
		}
	}


	public function delete_this_post($postid, $userid)
	{
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$sql_user1 = 'SELECT * FROM posts WHERE  id="' . $postid . '"  LIMIT 1';
		$res_user1 = $db2->query($sql_user1);
		$obj_user1 = $db2->fetch_object($res_user1);
		$postuser = $obj_user1->id;

		if ($postuser == "") {
			return "Post Not Available";
		} else {

			// echo 'DELETE FROM posts WHERE user_id="' . $userid . '" AND id="' . $postid . '" '; die;

			$db2->query('DELETE FROM posts WHERE user_id="' . $userid  . '" AND id="' . $postid . '" ');
			return "1";
		}
	}


	public function get_own_user($postid)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$r    = $db2->query('select user_id  from `posts` where id="' . $postid . '" ', FALSE);
		$result = $db2->fetch_object($r);
		return $result;
	}

	public function checkemptyuser($userid)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$res	= $db2->query('select user_id from users_notif_rules where user_id="' . $userid . '" ', FALSE);
		return $res;
	}


	public function get_like_count($postid)
	{
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$r      = $db2->query('select count(id) as likecount FROM post_likes WHERE  post_id="' . $postid . '"', FALSE);
		$result = $db2->fetch_object($r);
		return $result->likecount;
	}

	public function new_reshare_count($postid)
	{
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$r      = $db2->query('select count(id) as sharecount  FROM post_reshares WHERE  post_id="' . $postid . '"', FALSE);
		$result = $db2->fetch_object($r);
		return $result->sharecount;
	}

	public function checknotrules($user_id, $type)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
		$user =  $db2->fetch_field('SELECT ' . $type . '  FROM users_notif_rules WHERE user_id="' . $user_id . '" ');
		return $user;
	}


	public function userdashboardtabsuser($user_id)
	{


		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$notifytype = 'notifications';
		$user =  $db2->fetch_field('SELECT newposts  FROM users_dashboard_tabs WHERE user_id="' . $user_id . '" AND tab="' . $notifytype . '"  ');
		return $user;
	}


	public function insert_active_notifications($ownuserid, $postid, $notifytype, $type, $standrdtype, $userid)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$date = time();
		$db2->query('insert into active_notifications  values ("","","' . $userid . '","' . $ownuserid . '","' . $postid . '","' . $notifytype . '","' . $type . '","' . $date . '")');
		$groupid = 0;
		$notif_object_type = 'post';
		$notif_object_id = $userid;


		$db2->query('insert into notifications  (notif_type, to_user_id, in_group_id, from_user_id,notif_object_type,notif_object_id,date,noti_postid) values  ("' . $standrdtype . '","' . $ownuserid . '","' . $groupid . '","' . $userid . '","' . $notif_object_type . '","' . $notif_object_id . '","' . $date . '","' . $postid . '")');

		//user existing in user dashboard tabs
		$userdash = $this->userdashboardtabsuser($ownuserid);

		if (!empty($userdash)) {
			$newpost = $userdash + 1;
			$tab = 'notifications';
			$db2->query('update users_dashboard_tabs set 	newposts="' . $newpost . '" WHERE user_id="' . $ownuserid . '" AND tab="' . $tab . '" ');
		} else {
			$tab = "notifications";
			$state = 1;
			$db2->query('insert into users_dashboard_tabs  values ("' . $ownuserid . '","' . $tab . '","' . $state . '","' . $state . '")');
		}
	}

	public function typelinks($postid)
	{
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$res	= $db2->query('select type,data from posts_attachments where post_id="' . $postid . '" ', FALSE);
		$result = $db2->fetch_object($res);
		return $result;
	}


	public function post_reshare($userid, $postid)
	{
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$sql_user1 = 'SELECT * FROM post_reshares WHERE  post_id="' . $postid . '"  and  user_id="' . $userid . '"  LIMIT 1';
		$res_user1 = $db2->query($sql_user1);
		$obj_user1 = $db2->fetch_object($res_user1);
		$postuser = $obj_user1->id;

		if ($postuser != "") {

			$db2->query('DELETE FROM post_reshares WHERE  id="' . $postuser . '" ');
		} else {


			$ownuserres          = $this->get_own_user($postid);
			$ownuserid           = $ownuserres->user_id;
			$not_type = '	ntf_me_on_post_rebuzz';
			$checkuserres = $this->checkemptyuser($ownuserid);

			if ($checkuserres->num_rows == "0") {
				$ownnotification = 1;
			} else {
				$ownnotification     = $this->checknotrules($ownuserid, $not_type);
				if (!empty($ownnotification)) {
					$ownnotification = $ownnotification;
				} else {
					$ownnotification = 1;
				}
			}




			if ($ownnotification == 1 || $ownnotification == 2 || $ownnotification == 3) {

				if ($ownuserid != $user->id) {
					$posttype      = $this->typeofpostofevent($postid);
					if ($posttype->num_rows > 0) {
						$type = "event";
					} else {
						$polltype      = $this->typeofpostofpoll($postid);
						if ($posttype->num_rows > 0) {
							$type = "poll";
						} else {
							$activitiestype      = $this->typelinks($postid);
							if (!empty($activitiestype)) {

								if ($activitiestype->type == "videoembed") {
									$type = "video link";
								} elseif ($activitiestype->type == "image") {
									$type = "image";
								} elseif ($activitiestype->type == "file") {
									$str = (unserialize($activitiestype->data));
									$ext = pathinfo($str->file_original, PATHINFO_EXTENSION);
									if ($ext == 'wmv' || $ext == 'mp4' || $ext == 'avi' || $ext == 'mov' || $ext == 'qt') {
										$type = "video";
									} else {
										$type = "file";
									}
								}
							} else {
								$type = "buzz";
							}
						}
					}

					$notifytype = "rebuzz";
					$standardtype = "ntf_me_on_post_rebuzz";

					$time = time();

					$newisert = $this->insert_active_notifications($ownuserid, $postid, $notifytype, $type, $standardtype, $userid);

					$db2->query('insert into post_reshares  values ("","' . $postid . '","' . $userid . '","' . $time . '")');
				}
			}
		}

		$posttype      = $this->new_reshare_count($postid);
		return $posttype;
	}


	public function post_unreshare($userid, $postid)
	{
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$sql_user1 = 'SELECT * FROM posts WHERE  id="' . $postid . '"  LIMIT 1';
		$res_user1 = $db2->query($sql_user1);
		$obj_user1 = $db2->fetch_object($res_user1);
		$postuser = $obj_user1->id;

		if ($postuser == "") {
			return "Post Not Available";
		} else {

			// echo 'DELETE FROM posts WHERE user_id="' . $userid . '" AND id="' . $postid . '" '; die;

			$db2->query('DELETE FROM post_reshares WHERE user_id="' . $userid  . '" AND post_id="' . $postid . '" ');
			$posttype      = $this->new_reshare_count($postid);
			return $posttype;
		}
	}


	public function deleteprofile($userid, $password)
	{
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$sql_user1 = 'SELECT * FROM users WHERE  id="' . $userid . '"  AND password="' . $password . '" ';
		$res_user1 = $db2->query($sql_user1);
		$obj_user1 = $db2->fetch_object($res_user1);
		$postuser = $obj_user1->id;

		if ($postuser == "") {
			return "0";
		} else {
			$db2->query('Update users SET active = "' . '0' . '" WHERE  id="' . $userid . '"');
			return "1";
		}
	}


	public function post_detail($postid)
	{


		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$sql_user1 = 'SELECT * FROM posts WHERE  id="' . $postid . '"  LIMIT 1';
		$res_user1 = $db2->query($sql_user1);
		$obj_user1 = $db2->fetch_object($res_user1);
		$postuser = $obj_user1->id;


		if (empty($postuser)) {
			return 0;
		} else {

			return $obj_user1;
		}
	}


	public function update_password($userid, $old_password, $new_password, $confirm_password)
	{


		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$sql_user1 = 'SELECT * FROM  users WHERE  id="' . $userid . '"  LIMIT 1';
		$res_user1 = $db2->query($sql_user1);
		$obj_user1 = $db2->fetch_object($res_user1);
		$user_pass = $obj_user1->password;


		if ($user_pass != $old_password) {
			return "0";
		}

		if ($new_password != $confirm_password) {
			return "00";
		}

		if (($user_pass == $old_password) and ($new_password == $confirm_password)) {
			$db2->query('Update users SET password = "' . $confirm_password . '" WHERE  id="' . $userid . '"');
			return "1";
		}
	}


	public function social_share($userid, $postid, $media, $platform_id)
	{
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
		$query = $db2->query('SELECT id FROM posts WHERE id=' . $postid . '  LIMIT 1');
		if ($query->num_rows > 0) {
			$db2->query('Insert into posts_social_share (post_id,user_id,media,platform_id,date) VALUES (' . $postid . ', ' . $userid . ', ' . $media . ', ' . $platform_id . ', ' . time() . ')');
			$id = (int) $db2->insert_id();
			$this->update_social_share_count($postid);
			return $id;
		}
		return 0;
	}

	public function update_follower_count($user_id, $count = 1) {
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);		
		$db2->query('UPDATE users SET num_followers=num_followers+(' . $count . ') WHERE id="' . $user_id . '"', FALSE);
	}

	public function update_social_share_count($post_id, $count = 1)
	{
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
		$query = $db2->query('SELECT posts_detail_id FROM  posts_details WHERE post_id="' . $post_id . '" limit 1', FALSE);

		if ($query->num_rows > 0) {
			$db2->query('UPDATE posts_details SET shares=shares+(' . $count . ') WHERE post_id="' . $post_id . '"', FALSE);
		} else if ($count == 1) {
			$db2->query('INSERT INTO posts_details SET shares=1, post_id="' . $post_id . '"', FALSE);
		}
	}



	public function profileshare($who, $whom)
	{
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$db2->query('Insert into profile_share (who,whom,time) VALUES (' . $who . ', ' . $whom . ',' . time() . ')');

		$r      = $db2->query('select count(id) as sharecount  FROM profile_share WHERE  whom="' . $whom . '"', FALSE);
		$result = $db2->fetch_object($r);
		return $result->sharecount;
	}


	public function user_favourites($who, $whom)
	{
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$db2->query('Insert into user_favourites (who,whom,time) VALUES (' . $who . ', ' . $whom . ',' . time() . ')');
		return 1;
	}


	public function splitAttachements($data)
	{
		$file_originalSTART = strpos($data, 's:25:"', 0) + 6;
		$file_originalEND  = strpos($data, ';', $file_originalSTART) - 1;
		$file_original = substr($data, $file_originalSTART, $file_originalEND - $file_originalSTART);

		$file_previewSTART = strpos($data, '"file_preview";s:26:"', 0) + 21;
		$file_previewEND  = strpos($data, ';', $file_previewSTART) - 1;
		$file_preview = substr($data, $file_previewSTART, $file_previewEND - $file_previewSTART);

		$file_thumbnailSTART = strpos($data, '"file_thumbnail";s:26:"', 0) + 23;
		$file_thumbnailEND  = strpos($data, ';', $file_thumbnailSTART) - 1;
		$file_thumbnail = substr($data, $file_thumbnailSTART, $file_thumbnailEND - $file_thumbnailSTART);

		$file_original . "+" . $file_preview . "+" . $file_thumbnail;
		return array("file_original" => $file_original, "file_preview" => $file_preview, "file_thumbnail" => $file_thumbnail);
	}


	public function event_post($userid, $title, $start_date, $end_date, $start_time, $end_time, $venue, $geoloc, $describe, $hash_tag, $group, $user, $link, $images, $files, $parentid)
	{

		$post_level  = 1;
		if ((int)$parentid == 0) {
			$post_level = 0;
		}



		if ($link != '') {
			$link = $link;
			$urlcon = '<span class="address_view">
									<a href="' . $link . '" class="buzz-content"  target="_blank">' . $link . '</a></span>';
		} else {
			$link = '';
			$urlcon = '';
		}


		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$res = $db2->query('Insert into posts (user_id,group_id,date,date_lastedit,date_lastcomment,ip_addr,parent_id,post_level,location,posttype,attached) VALUES ("' . $userid . '","' . '21' . '", "' . time() . '", "' . '0' . '","' . time() . '", "' . '0' . '", "' . $parentid . '", "' . $post_level . '","' . $venue . '", 4, "' . '1' . '")');
		$post_id	= (int) $db2->insert_id();

		$tags = "";

		$count1 = count($hash_tag);

		if ($count1 > 0) {
			foreach ($hash_tag as $tag) {
				$tags = $tags . "#" . $tag['tag'];
			}
		}

		$latitude = NULL;
		$longitude = NULL;
		if(!empty($geoloc)) {
			$geoloc_arr = explode(',', $geoloc);
			$latitude = isset($geoloc_arr[0]) ? $db2->escape($geoloc_arr[0]) : NULL;
			$longitude = isset($geoloc_arr[1]) ? $db2->escape($geoloc_arr[1]) : NULL;
		}

		$res = $db2->query('Insert into events (created_at,modified_at,group_id,admin_id,event_type,display_type,address,event_name,location,event_description,start_date,start_time,end_date,end_time,publish_now,publish_date,is_private,status,street_group,street_user,tag_name,url, latitude, longitude) VALUES ("' . date("Y-m-d h:i") . '", "' . date("Y-m-d h:i") . '", "' . '21' . '", "' . $userid . '", "' . 'Normal' . '", "' . 'community' . '", "' . $venue . '", "' . $title . '", "' . $geoloc . '", "' . $describe . '", "' . $start_date . '", "' . $start_time . '", "' . $end_date . '", "' . $end_time . '", "' . '1' . '", "' . '0' . '", "' . '0' . '", "' . '1' . '", "' . $group . '", "' . $user . '", "' . $tags . '", "' . $link . '",'.$latitude.','.$longitude.')');

		$event_id	= (int) $db2->insert_id();

		$res = $db2->query('Insert into event_posts (post_id,event_id,created) VALUES ("' . $post_id . '", "' . $event_id . '", "' . date("Y-m-d h:i") . '")');


		if ($count1 > 0) {
			foreach ($hash_tag as $tag) {

				$res = $db2->query('Insert into post_tags (tag_name,user_id,group_id,post_id,date) VALUES ("' . $tag['tag'] . '","' . $userid . '", "' . '0' . '","' . $post_id . '", "' . time() . '")');
			}
		}



		$res = $db2->query('Insert into posts_comments_watch (user_id,post_id,newcomments) VALUES ("' . $userid . '","' . $event_id . '", "' . '0' . '")');



		$res = $db2->query('Insert into post_userbox (user_id,post_id) VALUES ("' . $userid . '","' . $post_id . '")');

		$query = 'SELECT * FROM users_followed WHERE whom = ' . $userid . ' ';
		$res =  $db2->query($query);
		$followers = $res->fetch_all(MYSQLI_ASSOC);

		foreach ($followers as $value) {
			$res = $db2->query('Insert into post_userbox (user_id,post_id) VALUES ("' . $value['who'] . '","' . $post_id . '")');
		}

		$date_time = date('M d, Y h:i A', strtotime($start_date . ' ' . $start_time));


		$content .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" style="padding:10px 10px 0px 10px;">
    <!-- start : event title -->
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz- zeropadding">
    <ul class="list-inline single-line">
    <li><img src="' . $C->SITE_URL . 'apps/events/static/images/icon-calendar-event.png" class="img-responsive">
    </li>
    <li>
    <a href="' . $C->SITE_URL . 'plugin/events/view/id:' . $event_id . $addition_url . '/postid:' . $post_id . '" class="buzz-title">
    ' . $title . '</a>
    </li>
    </ul>  
    </div>
    <!-- end : event title -->

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
    <!-- start : event location -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding"> 
    <li><img src="' . $C->SITE_URL . 'apps/events/static/images/icon-location-event.png" class="img-responsive"></li>
    <li><a href="' . $C->SITE_URL . 'search/tab:location/s:' . $post_id . '">' . $venue . '</a></li>
    </ul>  
    </div>
    <!-- end : event location -->
    <!-- start : event date & time -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="' . $C->SITE_URL . 'apps/events/static/images/icon-calendar-event.png" class="img-responsive"></li>
    <li>' . $date_time . '</li>  
    </ul>  
    </div>
    <!-- end : event date & time -->
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">';
		if ($link != '') {
			$content .= '<!-- start : event url -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="' . $C->SITE_URL . 'apps/events/static/images/icon-url-event.png" class="img-responsive"></li>
    <li>' . $link . '</li>
    </ul>  
    </div>
    <!-- end : event url -->';
		}
		if ($hash_tag != '') {
			$content .= '<!-- start : event hashtag -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line">
    <li><img src="' . $C->SITE_URL . 'apps/events/static/images/icon-hashtag-event.png" class="img-responsive"></li>
    <li>' . $tags . '	</li>
    </ul>  
    </div>
    <!-- end : event hashtag -->';
		}
		$content .= '</div>
    </div>
		';


		$answer = (object)array(
			'description'	=> $content,
			'hits'			=> 'link'
		);


		$db2->query('INSERT INTO `posts_attachments`(`post_id`, `type`, `data`,  `content`) VALUES (' . $post_id . ',\'link\',\'' . $db2->escape(serialize($answer)) . '\',\'' . "" . '\')');

		$count2 = count($images);


		$p = new newpost();
		if ($count2 > 0) {
			foreach ($images as $img) {
				//echo $C->STORAGE_TMP_DIR.$img["image"];
				//echo  $img["image"];

				if ($ii = $p->attach_image($C->STORAGE_TMP_DIR . $img["image"], $img["image"])) {
					$imagecaption = $img["caption"];
					$db2->query('INSERT INTO `posts_attachments`(`post_id`, `type`, `data`,  `content`) VALUES (' . $post_id . ',\'Image\',\'' . $db2->escape(serialize($ii)) . '\',\'' . $imagecaption . '\')');


					$newimg = $this->splitAttachements(serialize($ii));

					foreach ($newimg as $tmpimg) {
						rename($C->STORAGE_TMP_DIR . $tmpimg, $C->STORAGE_DIR . 'attachments/1/' . $tmpimg);
					}
				}
			}
			unset($images);
		}

		/* Uploading videos for events */
		if (isset($files) && !empty($files)) {

			if ($videofile = $this->videofilesmove($C->STORAGE_TMP_DIR . $files, $files, $C->STORAGE_DIR)) {

				$data	= (object) array(
					'in_tmpdir'	=> TRUE,
					'title'		=> $files,
					'filetype'		=> 'file',
					'file_original'	=> $files,
					'filesize'	=> 0,
					'hits'	=> 0
				);
				$videocaption = '';
				$db2->query('INSERT INTO `posts_attachments`(`post_id`, `type`, `data`,  `content`) VALUES (' . $post_id . ',\'file\',\'' . $db2->escape(serialize($data)) . '\',\'' . $videocaption . '\')');
			}
		}
		return 1;
	}
	public function videofilesmove($tmpdirectory, $filename, $originaldirectory)
	{
		if (copy($tmpdirectory, $originaldirectory . '/attachments/1/' . $filename)) {
			unlink($tmpdirectory);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function poll_post($userid, $question, $option, $group, $user, $images, $parentid)
	{

		$post_level  = 1;
		if ((int)$parentid == 0) {
			$post_level = 0;
		}

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$res = $db2->query('Insert into posts (user_id,date,group_name,posttype,date_lastcomment,parent_id,post_level) VALUES ("' . $userid . '", "' . time() . '", "' . $group . '", "' . '5' . '", "' . time() . '","' . $parentid . '","' . $post_level . '")');
		$post_id	= (int) $db2->insert_id();


		$res = $db2->query('Insert into polls (poll_date,poll_question,poll_is_active,poll_allow_user_answer,posts_id) VALUES ("' . time() . '", "' . $question . '", "' . '0' . '", "' . '0' . '", "' . $post_id . '")');
		$poll_id	= (int) $db2->insert_id();

		$res = $db2->query('Insert into post_userbox (user_id,post_id) VALUES ("' . $userid . '","' . $post_id . '")');

		$query = 'SELECT * FROM users_followed WHERE whom = ' . $userid . ' ';
		$res =  $db2->query($query);
		$followers = $res->fetch_all(MYSQLI_ASSOC);

		// print_r($followers); die;

		foreach ($followers as $value) {
			$res = $db2->query('Insert into post_userbox (user_id,post_id) VALUES ("' . $value['who'] . '","' . $post_id . '")');
		}


		foreach ($option as $values) {
			$res = $db2->query('Insert into polls_answers (poll_id,answer,votes) VALUES ("' . $poll_id . '", "' . $values['opt'] . '", "' . '0' . '")');
		}

		$count2 = count($images);


		$p = new newpost();
		if ($count2 > 0) {
			foreach ($images as $img) {
				//echo $C->STORAGE_TMP_DIR.$img["image"];
				//echo  $img["image"];

				if ($ii = $p->attach_image($C->STORAGE_TMP_DIR . $img["image"], $img["image"])) {
					$imagecaption = $img["caption"];
					$db2->query('INSERT INTO `posts_attachments`(`post_id`, `type`, `data`,  `content`) VALUES (' . $post_id . ',\'Image\',\'' . $db2->escape(serialize($ii)) . '\',\'' . $imagecaption . '\')');


					$newimg = $this->splitAttachements(serialize($ii));

					foreach ($newimg as $tmpimg) {
						rename($C->STORAGE_TMP_DIR . $tmpimg, $C->STORAGE_DIR . 'attachments/1/' . $tmpimg);
					}
				}
			}
			unset($images);
		}

		return 1;
	}

	public function get_user($input)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);


		$count = strlen($input);

		if ($count > 2) {
			//echo "aa"; die; 

			$query = "SELECT * FROM `users` WHERE `username` LIKE '%" . $input . "%'";
			$res =  $db2->query($query);
			return $array = $res->fetch_all(MYSQLI_ASSOC);
		} else {
			return "Please Enter Atleast 3 Letter";
		}
	}

	public function get_group($input)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);


		$count = strlen($input);

		if ($count > 2) {
			//echo "aa"; die; 

			$query = "SELECT * FROM `groups` WHERE `title` LIKE '%" . $input . "%'";
			$res =  $db2->query($query);
			return $array = $res->fetch_all(MYSQLI_ASSOC);
		} else {
			return "Please Enter Atleast 3 Letter";
		}
	}

	public function get_tag($input)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);


		$count = strlen($input);

		if ($count > 2) {
			//echo "aa"; die; 

			$query = "SELECT * FROM `post_tags` WHERE `tag_name` LIKE '%" . $input . "%'";
			$res =  $db2->query($query);
			return $array = $res->fetch_all(MYSQLI_ASSOC);
		} else {
			return "Please Enter Atleast 3 Letter";
		}
	}


	public function event_edit($user_id, $post_id, $title, $start_date, $end_date, $start_time, $end_time, $describe, $hash_tag, $group, $user, $link, $images, $venue, $geoloc)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		if ($link != '') {
			$link = $link;
			$urlcon = '<span class="address_view">
									<a href="' . $link . '" class="buzz-content"  target="_blank">' . $link . '</a></span>';
		} else {
			$link = '';
			$urlcon = '';
		}

		// delete attachment
		// delete tag
		// update post
		// update event


		$sql_user1 = 'SELECT * FROM event_posts WHERE  post_id="' . $post_id . '" ';
		$res_user1 = $db2->query($sql_user1);
		$obj_user1 = $db2->fetch_object($res_user1);
		$event_id = $obj_user1->event_id;

		$tags = "";
		if (isset($hash_tag)) {
			foreach ($hash_tag as $tag) {
				$tags = $tags . " #" . $tag['tag'];
			}
		}

		$latitude = NULL;
		$longitude = NULL;
		if(!empty($geoloc)) {
			$geoloc_arr = explode(',', $geoloc);
			$latitude = isset($geoloc_arr[0]) ? $db2->escape($geoloc_arr[0]) : NULL;
			$longitude = isset($geoloc_arr[1]) ? $db2->escape($geoloc_arr[1]) : NULL;
		}


		$db2->query('Update events SET modified_at = "' . date("Y-m-d h:i") . '",event_name = "' . $title . '",event_name = "' . $title . '",event_description = "' . $describe . '" ,start_date = "' . $start_date . '",start_time = "' . $start_time . '",end_date = "' . $end_date . '",end_time = "' . $end_time . '",url = "' . $link . '",url = "' . $link . '",tag_name = "' . $tags . '",street_group = "' . $group . '",street_user = "' . $user . '", latitude="'.$latitude.'", longitude="'.$longitude.'", location="'.$geoloc.'", address="'.$venue.'" WHERE  id="' . $event_id . '"');



		$db2->query('DELETE FROM posts_attachments WHERE  post_id="' . $post_id . '" ');
		$db2->query('DELETE FROM post_tags WHERE  post_id="' . $post_id . '" ');


		$p = new newpost();
		if (isset($images)) {
			foreach ($images as $img) {

				if ($ii = $p->attach_image($C->STORAGE_TMP_DIR . $img["image"], $img["image"])) {
					$imagecaption = $img["caption"];
					$db2->query('INSERT INTO `posts_attachments`(`post_id`, `type`, `data`,  `content`) VALUES (' . $post_id . ',\'Image\',\'' . $db2->escape(serialize($ii)) . '\',\'' . $imagecaption . '\')');

					// print_r(serialize($ii)); die;

					$newimg = $this->splitAttachements(serialize($ii));

					foreach ($newimg as $tmpimg) {
						rename($C->STORAGE_TMP_DIR . $tmpimg, $C->STORAGE_DIR . 'attachments/1/' . $tmpimg);
					}
				}
			}
			unset($images);
		}





		$date_time = date('M d, Y h:i A', strtotime($start_date . ' ' . $start_time));


		$content .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" style="padding:10px 10px 0px 10px;">
    <!-- start : event title -->
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz- zeropadding">
    <ul class="list-inline single-line">
    <li><img src="' . $C->SITE_URL . 'apps/events/static/images/icon-calendar-event.png" class="img-responsive">
    </li>
    <li>
    <a href="' . $C->SITE_URL . 'plugin/events/view/id:' . $event_id . $addition_url . '/postid:' . $post_id . '" class="buzz-title">
    ' . $title . '</a>
    </li>
    </ul>  
    </div>
    <!-- end : event title -->

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">
    <!-- start : event location -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding"> 
    <li><img src="' . $C->SITE_URL . 'apps/events/static/images/icon-location-event.png" class="img-responsive"></li>
    <li><a href="' . $C->SITE_URL . 'search/tab:location/s:' . $post_id . '">' . $venue . '</a></li>
    </ul>  
    </div>
    <!-- end : event location -->
    <!-- start : event date & time -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="' . $C->SITE_URL . 'apps/events/static/images/icon-calendar-event.png" class="img-responsive"></li>
    <li>' . $date_time . '</li>  
    </ul>  
    </div>
    <!-- end : event date & time -->
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">';
		if ($link != '') {
			$content .= '<!-- start : event url -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line zeropadding">
    <li><img src="' . $C->SITE_URL . 'apps/events/static/images/icon-url-event.png" class="img-responsive"></li>
    <li>' . $link . '</li>
    </ul>  
    </div>
    <!-- end : event url -->';
		}
		if ($hash_tag != '') {
			$content .= '<!-- start : event hashtag -->
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">
    <ul class="list-inline single-line">
    <li><img src="' . $C->SITE_URL . 'apps/events/static/images/icon-hashtag-event.png" class="img-responsive"></li>
    <li>' . $tags . '	</li>
    </ul>  
    </div>
    <!-- end : event hashtag -->';
		}
		$content .= '</div>
    </div>
		';


		$answer = (object)array(
			'description'	=> $content,
			'hits'			=> 'link'
		);


		$db2->query('INSERT INTO `posts_attachments`(`post_id`, `type`, `data`,  `content`) VALUES (' . $post_id . ',\'link\',\'' . $db2->escape(serialize($answer)) . '\',\'' . "" . '\')');


		if (isset($hash_tag)) {
			foreach ($hash_tag as $tag) {

				$res = $db2->query('Insert into post_tags (tag_name,user_id,group_id,post_id,date) VALUES ("' . $tag['tag'] . '","' . $user_id . '", "' . '0' . '","' . $post_id . '", "' . time() . '")');
			}
		}

		$daata = "";


		return 1;
	}



	public function poll_edit($userid, $question, $option, $group, $user, $postid)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);



		$sql_user1 = 'SELECT * FROM polls WHERE  posts_id="' . $postid . '"  LIMIT 1';
		$res_user1 = $db2->query($sql_user1);
		$obj_user1 = $db2->fetch_object($res_user1);
		$poll_id = $obj_user1->poll_id;

		// echo $poll_id; die;

		$db2->query('Update posts SET group_name = "' . $group . '" WHERE  id="' . $postid . '"');

		$db2->query('Update polls SET poll_question = "' . $question . '" WHERE  poll_id="' . $poll_id . '"');


		$db2->query('DELETE FROM polls_answers WHERE  poll_id="' . $poll_id . '"');

		foreach ($option as $values) {
			$res = $db2->query('Insert into polls_answers (poll_id,answer,votes) VALUES ("' . $poll_id . '", "' . $values['opt'] . '", "' . '0' . '")');
		}


		return 1;
	}

	public function event_delete($post_id)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$sql_user1 = 'SELECT * FROM event_posts WHERE  post_id="' . $post_id . '" ';
		$res_user1 = $db2->query($sql_user1);
		$obj_user1 = $db2->fetch_object($res_user1);
		$event_id = $obj_user1->event_id;

		$db2->query('DELETE FROM posts_attachments WHERE  post_id="' . $post_id . '" ');
		$db2->query('DELETE FROM post_tags WHERE  post_id="' . $post_id . '" ');
		$db2->query('DELETE FROM events WHERE  id="' . $event_id . '" ');
		$db2->query('DELETE FROM event_posts WHERE  event_id="' . $event_id . '" ');
		$db2->query('DELETE FROM post_userbox WHERE  post_id="' . $post_id . '" ');
		return 1;
	}




	public function noti_someone_follow_me($userid, $option)
	{

		// both = 1
		//  Post = 2
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
		$db2->query('Update users_notif_rules SET ntf_me_if_u_follows_me = "' . $option  . '" WHERE  user_id="' . $userid . '"');
		clearstatcache();
		return 1;
	}


	public function poll_details($post_id, $userid)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
		$pagenumber = (intVal($pagenumber) == 0) ? "0" : (intVal($pagenumber) - 1);

		$pagerecordcount = (intVal($pagerecordcount) == 0) ? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);
		$query = 'SELECT 
                         b.id AS pid,
                        p.id AS postid,
                        p.user_id AS postuserid,
                        u.username AS postusername,
                        u.avatar AS postuserimage,
						if( (u.cover is null), "", u.cover) AS coverimage,
                        if( (posttype = 2), p.title,  SUBSTRING(p.message, 1, 75)) AS title,
                        p.posttype,
                        if( (posttype = 3), pa.content,  p.message) AS message,
                        p.likes,
                        p.mentioned,
						GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
                        p.posttags,
                        p.comments,
                        p.reshares,
                        p.date,
                        p.date_lastedit,
                        p.date_lastcomment,
                        p.group_name,
                        p.parent_id,
                        p.status,
                        p.post_level,
                        p.location,
                        p.thumb,
                        "" as `category`,
                        "public" AS `type`,
						if( (pv.cnt is null), 0, pv.cnt) AS ViewCount,
                        (Select count(id) from  posts_social_share where post_id =p.id ) AS sharecount,
                        if( ((Select count(id) from  posts_social_share where post_id =p.id and user_id=' . $userid . ') > 0), true,  false) AS isshared
							FROM post_userbox b
							LEFT JOIN posts p ON p.id=b.post_id
							LEFT JOIN users u ON u.id=p.user_id
							LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
							LEFT OUTER JOIN post_views_list pv ON pv.post_id = p.id
						WHERE
                         p.id=' . $post_id . ' AND (INSTR (pa.data, "<div") < 1 or INSTR (pa.data, "<div") is null)
                            Group By p.id
                            ORDER BY p.date_lastcomment DESC
                         LIMIT ' . $pagenumber . ',' . $pagerecordcount;

		$res =  $db2->query($query);
		$array = $res->fetch_all(MYSQLI_ASSOC);
		foreach ($array as $i => $array_expression) {

			$array[$i]["likes"] = $this->getLikes($array[$i]["postid"]);

			$array[$i]["comments"] = $this->getCommentsCount($array[$i]["postid"]);
			$array[$i]["commentdetails"] = $this->getPostComments($array[$i]["postid"]);
			$query = 'SELECT id FROM post_likes WHERE  user_id="' . $userid . '"  AND post_id = ' . $array[$i]["postid"] . ' LIMIT 1';
			$res =  $db2->query($query);
			$obj = $db2->fetch_object($res);


			if (empty($obj->id)) {
				$array[$i]["isliked"] = "0";
			} else {
				$array[$i]["isliked"] = "1";
			}

			$query1 = 'SELECT id FROM post_reshares WHERE  user_id="' . $userid . '"  AND post_id = ' . $array[$i]["postid"] . ' LIMIT 1';
			$res1 =  $db2->query($query1);
			$obj1 = $db2->fetch_object($res1);


			if (empty($obj1->id)) {
				$array[$i]["isbuzzed"] = "0";
			} else {
				$array[$i]["isbuzzed"] = "1";
			}


			$query3 = 'SELECT id FROM users_followed WHERE  who="' . $userid . '"  AND whom = ' . $array[$i]["postuserid"] . ' LIMIT 1';
			$res3 =  $db2->query($query3);
			$obj3 = $db2->fetch_object($res3);

			if (empty($obj3->id)) {
				$array[$i]["isfollwed"] = "0";
			} else {
				$array[$i]["isfollwed"] = "1";
			}

			$array[$i]["likes"]      = $this->get_like_count($array[$i]["postid"]);
			$array[$i]["reshares"]       = $this->new_reshare_count($postid);

			if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
				$url = "https://";
			else
				$url = "http://";

			$url .= $_SERVER['HTTP_HOST'];
			$array[$i]["profile_base_url"] =       $C->SITE_URL . "storage/avatars/thumbs1/";
			$array[$i]["attachment_base_url"] =      $C->SITE_URL . "storage/attachments/1/";


			$sql_user1 = 'SELECT * FROM polls WHERE  posts_id="' . $post_id . '" ';
			$res_user1 = $db2->query($sql_user1);
			$obj_user1 = $db2->fetch_object($res_user1);
			$poll_id = $obj_user1->poll_id;

			$array[$i]['question'] = $obj_user1->poll_question;
			$array[$i]['poll_id'] = $poll_id;



			$r = $db2->query('SELECT * FROM  post_poll_votes WHERE POLL_ID="' . $poll_id . '" AND VOTER_USER_ID="' . $userid . '" LIMIT 1');
			$result = $db2->fetch_object($r);
			$ANSWER_ID = $result->ANSWER_ID;

			if ($ANSWER_ID == null) {
				$ANSWER_ID = "0";
			}

			$array[$i]['is_vote'] = $ANSWER_ID = $ANSWER_ID;

			//  echo $poll_id; die;
			$r = $db2->query('select count(id) as totalvote  FROM post_poll_votes WHERE  POLL_ID="' . $poll_id . '"', FALSE);
			$result = $db2->fetch_object($r);
			$array[$i]['total_vote'] = $result->totalvote;

			$query = 'SELECT * FROM polls_answers WHERE  poll_id="' . $poll_id . '"';
			$res =  $db2->query($query);
			$answer = $res->fetch_all(MYSQLI_ASSOC);


			if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
				$url = "https://";
			else
				$url = "http://";

			$url .= $_SERVER['HTTP_HOST'];
			$array[$i]["profile_base_url"] =       $C->SITE_URL . "storage/avatars/thumbs1/";
			$array[$i]["attachment_base_url"] =      $C->SITE_URL . "storage/attachments/1/";


			$poll_value = array();

			foreach ($answer as $value) {

				$r = $db2->query('select count(id) as pollvote  FROM post_poll_votes WHERE  ANSWER_ID="' . $value['poll_answer_id'] . '" AND POLL_ID="' . $poll_id . '"', FALSE);
				$result = $db2->fetch_object($r);
				$result->pollvote;
				$value['votes'] =  $result->pollvote;
				$poll_value[] = $value;
			}


			$query = 'SELECT * FROM posts_attachments WHERE  type="' . 'image' . '"  AND post_id = ' . $post_id . ' ';
			$res =  $db2->query($query);
			$array[$i]["poll_attachment"] = $res->fetch_all(MYSQLI_ASSOC);

			$array[$i]['poll_option'] = $poll_value;
		}
		return $array;
	}




	public function poll_vote($userid, $postid, $ans)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$sql_user1 = 'SELECT * FROM polls WHERE  posts_id="' . $postid . '" ';
		$res_user1 = $db2->query($sql_user1);
		$obj_user1 = $db2->fetch_object($res_user1);
		$poll_id = $obj_user1->poll_id;

		$query = 'SELECT * FROM post_poll_votes WHERE POLL_ID="' . $poll_id . '" AND VOTER_USER_ID="' . $userid . '" LIMIT 1';
		$res =  $db2->query($query);
		$current = $res->fetch_all(MYSQLI_ASSOC);

		if (empty($current)) {

			$db2->query('Insert into post_poll_votes (`POLL_ID`, `ANSWER_ID`, `VOTER_USER_ID`) VALUES ("' . $poll_id . '", "' . $ans . '", "' . $userid . '")');
			$r = $db2->query('select count(id) as totalvote  FROM post_poll_votes WHERE  POLL_ID="' . $poll_id . '"', FALSE);
			$result = $db2->fetch_object($r);
			return $result->totalvote;
		} else {

			$db2->query('DELETE FROM post_poll_votes WHERE POLL_ID="' . $obj_user1->poll_id . '" AND VOTER_USER_ID="' . $userid . '" LIMIT 1', FALSE);

			$db2->query('Insert into post_poll_votes (`POLL_ID`, `ANSWER_ID`, `VOTER_USER_ID`) VALUES ("' . $poll_id . '", "' . $ans . '", "' . $userid . '")');

			$r = $db2->query('select count(id) as totalvote  FROM post_poll_votes WHERE  POLL_ID="' . $poll_id . '"', FALSE);
			$result = $db2->fetch_object($r);
			return $result->totalvote;
		}
	}



	public function chnagepoll_vote($userid, $postid, $ans)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$sql_user1 = 'SELECT * FROM polls WHERE  posts_id="' . $postid . '" ';
		$res_user1 = $db2->query($sql_user1);
		$obj_user1 = $db2->fetch_object($res_user1);
		$poll_id = $obj_user1->poll_id;

		$db2->query('DELETE FROM post_poll_votes WHERE POLL_ID="' . $poll_id . '" AND VOTER_USER_ID="' . $userid . '" LIMIT 1', FALSE);

		$db2->query('Insert into post_poll_votes (`POLL_ID`, `ANSWER_ID`, `VOTER_USER_ID`) VALUES ("' . $poll_id . '", "' . $ans . '", "' . $userid . '")');

		$r = $db2->query('select count(id) as totalvote  FROM post_poll_votes WHERE  POLL_ID="' . $poll_id . '"', FALSE);
		$result = $db2->fetch_object($r);
		return $result->totalvote;
	}

	public function event_response($userid, $postid, $ans)
	{


		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$query = 'select *  FROM post_userbox WHERE user_id="' . $userid . '" AND post_id="' . $postid . '"  ';
		$res =  $db2->query($query);
		$data = $res->fetch_all(MYSQLI_ASSOC);

		if (empty($data)) {

			$array[$i]["user_response"] = $res->fetch_all(MYSQLI_ASSOC);
			$db2->query('Insert into post_userbox (`user_id`, `post_id`, `event_status` , `status`) VALUES ("' . $userid . '", "' . $postid . '", "' . $ans . '", "' . $ans . '")');
		} else {

			$db2->query('update post_userbox set event_status="' . $ans . '" ,status="' . $ans . '" WHERE user_id="' . $userid . '" AND post_id="' . $postid . '" ');
		}


		$r = $db2->query('select count(id) as joincount  FROM post_userbox WHERE post_id="' . $postid . '" AND event_status="' . '1' . '"', FALSE);
		$result = $db2->fetch_object($r);
		$sharecount = $result->joincount;

		return $sharecount;
	}



	public function event_details1($userid, $postid)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
		//////
		$pagenumber = (intVal($pagenumber) == 0) ? "0" : (intVal($pagenumber) - 1);

		$pagerecordcount = (intVal($pagerecordcount) == 0) ? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);


		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
		//                        SUBSTRING(p.message, 1, 75) as title, 

		$query = 'SELECT 
                         b.id AS pid,
                        p.id AS postid,
                        p.user_id AS postuserid,
                        u.username AS postusername,
                        u.avatar AS postuserimage,
						if( (u.cover is null), "", u.cover) AS coverimage,
                        if( (posttype = 2), p.title,  SUBSTRING(p.message, 1, 75)) AS title,
                        p.posttype,
                        if( (posttype = 3), pa.content,  p.message) AS message,
                        p.likes,
                        p.mentioned,
						GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
                        p.posttags,
                        p.comments,
                        p.reshares,
                        p.date,
                        p.date_lastedit,
                        p.date_lastcomment,
                        p.group_name,
                        p.parent_id,
                        p.status,
                        p.post_level,
                        p.location,
                        p.thumb,
                        "" as `category`,
                        "public" AS `type`,
						if( (pv.cnt is null), 0, pv.cnt) AS ViewCount,
                        (Select count(id) from  posts_social_share where post_id =p.id ) AS sharecount,
                        if( ((Select count(id) from  posts_social_share where post_id =p.id and user_id=' . $userid . ') > 0), true,  false) AS isshared
							FROM post_userbox b
							LEFT JOIN posts p ON p.id=b.post_id
							LEFT JOIN users u ON u.id=p.user_id
							LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
							LEFT OUTER JOIN post_views_list pv ON pv.post_id = p.id
						WHERE
                         p.id=' . $postid . ' AND (INSTR (pa.data, "<div") < 1 or INSTR (pa.data, "<div") is null)
                            Group By p.id
                            ORDER BY p.date_lastcomment DESC
                         LIMIT ' . $pagenumber . ',' . $pagerecordcount;

		$res =  $db2->query($query);
		$array1 = $res->fetch_all(MYSQLI_ASSOC);
		//////

		$query = 'SELECT * FROM event_posts WHERE  post_id="' . $postid . '" LIMIT 1';
		$res =  $db2->query($query);
		$obj = $db2->fetch_object($res);
		$event_id = $obj->event_id;

		$r = $db2->query('select count(id) as totalvote  FROM post_userbox WHERE user_id="' . $userid . '"');
		$result = $db2->fetch_object($r);
		$array["current_user_response"] = $result->totalvote;

		$array["post_id"] = $postid;

		$query = 'SELECT id as event_id,admin_id as user_id,address,location,event_name,start_date,start_time,end_date,end_time,status FROM events WHERE  id="' . $event_id . '" LIMIT 1';
		$res =  $db2->query($query);
		$array["event_detail"] = $db2->fetch_object($res);

		$query = 'SELECT * FROM posts_attachments WHERE  type="' . 'image' . '"  AND post_id = ' . $postid . ' ';
		$res =  $db2->query($query);
		$array["event_attachment"] = $res->fetch_all(MYSQLI_ASSOC);
		$videoquery = 'SELECT * FROM posts_attachments WHERE  type="' . 'file' . '"  AND post_id = ' . $postid . ' ';
		$videores =  $db2->query($videoquery);
		$array["video_attachment"] = $videores->fetch_all(MYSQLI_ASSOC);

		return $array1 + $array;
	}


	public function ntf_them_if_i_edt_profl($userid, $respo)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$res = $db2->query('Update users_notif_rules SET ntf_them_if_i_edt_profl = "' . $respo . '" WHERE  user_id="' . $userid . '"');

		return $respo;
	}


	public function ntf_them_if_i_edt_pictr($userid, $respo)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$res = $db2->query('Update users_notif_rules SET ntf_them_if_i_edt_pictr = "' . $respo . '" WHERE  user_id="' . $userid . '"');

		return $respo;
	}

	public function ntf_them_if_i_create_grp($userid, $respo)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$res = $db2->query('Update users_notif_rules SET ntf_them_if_i_create_grp = "' . $respo . '" WHERE  user_id="' . $userid . '"');

		return $respo;
	}



	public function ntf_them_if_i_follow_usr($userid, $respo)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$res = $db2->query('Update users_notif_rules SET ntf_them_if_i_follow_usr = "' . $respo . '" WHERE  user_id="' . $userid . '"');

		return $respo;
	}
	/*
	* Displaying mypolls data
	*/
	public function 	mypolls_workspace($userId, $pagenumber, $pagerecordcount)
	{
		global $db2, $C;
		$pagenumber = $pagenumber - 1;
		$pagenumber = ((int)$pagenumber *  (int)$pagerecordcount);
		$pagerecordcount = (intVal($pagerecordcount) == 0) ? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);
		$user_id = $userId;

		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
		$pollmyquery           = $db2->query('SELECT  p.id as postid,p.user_id AS postuserid,
                        u.username AS postusername,
                        u.avatar AS postuserimage,
						if( (u.cover is null), "", u.cover) AS coverimage,
                        if( (posttype = 2), p.title,  SUBSTRING(p.message, 1, 75)) AS title,
                        p.posttype,
                        p.likes,
                        p.mentioned,
						p.attached,
						p.posttags,
                        p.comments,
                        p.reshares,
                        p.date,
                        p.date_lastedit,
                        p.date_lastcomment,
                        p.group_name,
                        p.parent_id,
                        p.status,
                        p.post_level,
                        p.location,
                        p.thumb,
                       if( (pv.cnt is null), 0, pv.cnt) AS ViewCount,
                        u.username AS commentdetails,
                        "" as category,
                        "public" AS type,
                        ps.poll_id,ps.poll_question
	  
	  FROM `posts` as p inner join polls as ps ON p.id = ps.posts_id 
		inner join users as u ON u.id=p.user_id
		LEFT OUTER JOIN posts_attachments pa ON pa.post_id = p.id
		 LEFT OUTER JOIN post_views_list pv ON pv.post_id = p.id
		WHERE p.user_id = "' . $userId . '" group by ps.poll_id order by ps.poll_id desc  LIMIT ' . $pagenumber . ',' . $pagerecordcount);
		$array = $pollmyquery->fetch_all(MYSQLI_ASSOC);
		$returnarray = array();
		foreach ($array as $i => $array_expression) {
			if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
				$url = "https://";
			else
				$url = "http://";

			$url .= $_SERVER['HTTP_HOST'];
			$array[$i]["profile_base_url"] =       $C->SITE_URL . "storage/avatars/thumbs1/";
			$array[$i]["attachment_base_url"] =       $C->SITE_URL . "storage/attachments/1/";


			$array[$i]["likes"] = $this->getLikes($array[$i]["postid"]);
			$array[$i]["comments"] = $this->getCommentsCount($array[$i]["postid"]);



			$query = 'SELECT * FROM post_likes WHERE  user_id="' . $user_id . '"  AND post_id = ' . $array[$i]["postid"] . ' LIMIT 1';
			$res =  $db2->query($query);
			$obj = $db2->fetch_object($res);


			if (empty($obj->id)) {
				$array[$i]["isliked"] = "0";
			} else {
				$array[$i]["isliked"] = "1";
			}

			$query1 = 'SELECT * FROM post_reshares WHERE  user_id="' . $user_id . '"  AND post_id = ' . $array[$i]["postid"] . ' LIMIT 1';
			$res1 =  $db2->query($query1);
			$obj1 = $db2->fetch_object($res1);


			if (empty($obj1->id)) {
				$array[$i]["isbuzzed"] = "0";
			} else {
				$array[$i]["isbuzzed"] = "1";
			}


			$query1 = 'SELECT * FROM profile_share WHERE  who="' . $user_id . '"  AND whom = ' . $array[$i]["postuserid"] . ' LIMIT 1';
			$res1 =  $db2->query($query1);
			$obj1 = $db2->fetch_object($res1);


			if (empty($obj1->id)) {
				$array[$i]["isshared"] = "0";
			} else {
				$array[$i]["isshared"] = "1";
			}


			$query3 = 'SELECT * FROM users_followed WHERE  who="' . $user_id . '"  AND whom = ' . $array[$i]["postuserid"] . ' LIMIT 1';
			$res3 =  $db2->query($query3);
			$obj3 = $db2->fetch_object($res3);

			if (empty($obj3->id)) {
				$array[$i]["isfollwed"] = "0";
			} else {
				$array[$i]["isfollwed"] = "1";
			}
			$query = 'SELECT * FROM posts_attachments WHERE  type="' . 'image' . '"  AND post_id = ' . $array[$i]["postid"] . ' ';
			$res =  $db2->query($query);
			$array[$i]["poll_attachment"] = $res->fetch_all(MYSQLI_ASSOC);

			$array[$i]["likes"]      = $this->get_like_count($array[$i]["postid"]);
			$array[$i]["reshares"]       = $this->new_reshare_count($array[$i]["postid"]);
			$r = $db2->query('select count(id) as joincount  FROM post_poll_votes WHERE POLL_ID="' . $array[$i]["poll_id"] . '"', FALSE);
			$result = $db2->fetch_object($r);
			$sharecount = $result->joincount;

			$array[$i]['total_vote'] = $sharecount;
			$r = $db2->query('SELECT * FROM  post_poll_votes WHERE POLL_ID="' . $array[$i]["poll_id"]  . '" AND VOTER_USER_ID="' . $user_id . '" LIMIT 1');
			$result = $db2->fetch_object($r);
			$ANSWER_ID = $result->ANSWER_ID;

			if ($ANSWER_ID == null) {
				$ANSWER_ID = "0";
			}

			$array[$i]['is_vote'] = $ANSWER_ID = $ANSWER_ID;
		}
		return $array;
	}
	/*
	* Displaying mypolls data
	*/
	public function 	trendpolls_workspace($userId, $pagenumber, $pagerecordcount)
	{
		global $db2, $C;
		$pagenumber = $pagenumber - 1;
		$pagenumber = ((int)$pagenumber *  (int)$pagerecordcount);
		$pagerecordcount = (intVal($pagerecordcount) == 0) ? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);
		$user_id = $userId;
		$now = time();
		$fifteendaysago = date_create('15 days ago');
		$fifteendays = date_format($fifteendaysago, 'Y-m-d');
		$todaydate     = date('Y-m-d');
		$enddate = strtotime($todaydate . '00:00:00');
		$startdate = strtotime($fifteendays . "23:59:59");




		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
		$pollmyquery           = $db2->query('SELECT  p.id as postid,p.user_id AS postuserid,
                        u.username AS postusername,
                        u.avatar AS postuserimage,
						if( (u.cover is null), "", u.cover) AS coverimage,
                        if( (posttype = 2), p.title,  SUBSTRING(p.message, 1, 75)) AS title,
                        p.posttype,
                        p.likes,
                        p.mentioned,
						p.attached,
						 p.posttags,
                        p.comments,
                        p.reshares,
                        p.date,
                        p.date_lastedit,
                        p.date_lastcomment,
                        p.group_name,
                        p.parent_id,
                        p.status,
                        p.post_level,
                        p.location,
                        p.thumb,
                        if( (pv.cnt is null), 0, pv.cnt) AS ViewCount,
                        u.username AS commentdetails,
                        "" as category,
                        "public" AS type,
                        ps.poll_id,ps.poll_question,count(ppv.ID) as total_vote
	  
	  FROM `posts` as p inner join polls as ps ON p.id = ps.posts_id 
	  LEFT  JOIN post_poll_votes ppv ON ppv.POLL_ID = ps.poll_id
		inner join users as u ON u.id=p.user_id
		LEFT  JOIN posts_attachments pa ON pa.post_id = p.id
		 LEFT  JOIN post_views_list pv ON pv.post_id = p.id
		WHERE p.date between "' . $startdate . '" AND "' . $enddate . '" group by ps.poll_id order by total_vote desc  LIMIT ' . $pagenumber . ',' . $pagerecordcount);

		$array = $pollmyquery->fetch_all(MYSQLI_ASSOC);
		$returnarray = array();
		foreach ($array as $i => $array_expression) {
			if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
				$url = "https://";
			else
				$url = "http://";

			$url .= $_SERVER['HTTP_HOST'];
			$array[$i]["profile_base_url"] =       $C->SITE_URL . "storage/avatars/thumbs1/";
			$array[$i]["attachment_base_url"] =      $C->SITE_URL . "storage/attachments/1/";


			$array[$i]["likes"] = $this->getLikes($array[$i]["postid"]);
			$array[$i]["comments"] = $this->getCommentsCount($array[$i]["postid"]);



			$query = 'SELECT * FROM post_likes WHERE  user_id="' . $user_id . '"  AND post_id = ' . $array[$i]["postid"] . ' LIMIT 1';
			$res =  $db2->query($query);
			$obj = $db2->fetch_object($res);


			if (empty($obj->id)) {
				$array[$i]["isliked"] = "0";
			} else {
				$array[$i]["isliked"] = "1";
			}
			$query = 'SELECT * FROM posts_attachments WHERE  type="' . 'image' . '"  AND post_id = ' . $array[$i]["postid"] . ' ';
			$res =  $db2->query($query);
			$array[$i]["poll_attachment"] = $res->fetch_all(MYSQLI_ASSOC);

			$query1 = 'SELECT * FROM post_reshares WHERE  user_id="' . $user_id . '"  AND post_id = ' . $array[$i]["postid"] . ' LIMIT 1';
			$res1 =  $db2->query($query1);
			$obj1 = $db2->fetch_object($res1);


			if (empty($obj1->id)) {
				$array[$i]["isbuzzed"] = "0";
			} else {
				$array[$i]["isbuzzed"] = "1";
			}


			$query1 = 'SELECT * FROM profile_share WHERE  who="' . $user_id . '"  AND whom = ' . $array[$i]["postuserid"] . ' LIMIT 1';
			$res1 =  $db2->query($query1);
			$obj1 = $db2->fetch_object($res1);


			if (empty($obj1->id)) {
				$array[$i]["isshared"] = "0";
			} else {
				$array[$i]["isshared"] = "1";
			}


			$query3 = 'SELECT * FROM users_followed WHERE  who="' . $user_id . '"  AND whom = ' . $array[$i]["postuserid"] . ' LIMIT 1';
			$res3 =  $db2->query($query3);
			$obj3 = $db2->fetch_object($res3);

			if (empty($obj3->id)) {
				$array[$i]["isfollwed"] = "0";
			} else {
				$array[$i]["isfollwed"] = "1";
			}

			$array[$i]["likes"]      = $this->get_like_count($array[$i]["postid"]);
			$array[$i]["reshares"]       = $this->new_reshare_count($array[$i]["postid"]);

			$r = $db2->query('SELECT * FROM  post_poll_votes WHERE POLL_ID="' . $array[$i]["poll_id"]  . '" AND VOTER_USER_ID="' . $user_id . '" LIMIT 1');
			$result = $db2->fetch_object($r);
			$ANSWER_ID = $result->ANSWER_ID;

			if ($ANSWER_ID == null) {
				$ANSWER_ID = "0";
			}

			$array[$i]['is_vote'] = $ANSWER_ID = $ANSWER_ID;
		}
		return $array;
	}

	public function ntf_me_if_u_follows_me($userid, $respo)
	{
		// echo "aa"; die;
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$res = $db2->query('Update users_notif_rules SET ntf_me_if_u_follows_me = "' . $respo . '" WHERE  user_id="' . $userid . '"');

		return $respo;
	}


	public function ntf_me_if_u_commments_me($userid, $respo)
	{
		// echo "aa"; die;
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$res = $db2->query('Update users_notif_rules SET ntf_me_if_u_commments_me = "' . $respo . '" WHERE  user_id="' . $userid . '"');

		return $respo;
	}


	public function ntf_me_if_u_like_buzz($userid, $respo)
	{
		// echo "aa"; die;
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$res = $db2->query('Update users_notif_rules SET ntf_me_if_u_like_buzz = "' . $respo . '" WHERE  user_id="' . $userid . '"');

		return $respo;
	}



	public function ntf_me_if_u_rebuzz_buzz($userid, $respo)
	{
		// echo "aa"; die;
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$res = $db2->query('Update users_notif_rules SET ntf_me_if_u_rebuzz_buzz = "' . $respo . '" WHERE  user_id="' . $userid . '"');

		return $respo;
	}


	public function ntf_me_if_u_share_buzz($userid, $respo)
	{
		// echo "aa"; die;
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$res = $db2->query('Update users_notif_rules SET ntf_me_if_u_share_buzz = "' . $respo . '" WHERE  user_id="' . $userid . '"');

		return $respo;
	}


	public function ntf_me_if_u_joins_grp($userid, $respo)
	{
		// echo "aa"; die;
		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$res = $db2->query('Update users_notif_rules SET ntf_me_if_u_joins_grp = "' . $respo . '" WHERE  user_id="' . $userid . '"');

		return $respo;
	}

	public function notification_page($userid)
	{

		global $db2, $C;
		$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

		$r  = $db2->query('select  ntf_them_if_i_edt_profl,
                                ntf_them_if_i_edt_pictr,
                                ntf_them_if_i_create_grp,
                                ntf_them_if_i_follow_usr,
                                ntf_me_if_u_follows_me,
                                ntf_me_if_u_commments_me,
                                ntf_me_if_u_like_buzz,
                                ntf_me_if_u_rebuzz_buzz,
                                ntf_me_if_u_share_buzz,
                                ntf_me_if_u_joins_grp
                                FROM users_notif_rules WHERE  user_id="' . $userid . '"', FALSE);

		$result = $db2->fetch_object($r);

		return $result;
	}
}
