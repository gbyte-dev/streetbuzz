<?php

    class API
    {
        private $user;
		private $db2;
		private $page;
		private $network;
		private $fortype=array();
		// $dic[""]->;

		
        public function __construct()
		{
			$this->user 	= & $GLOBALS['user'];
			$this->network 	= & $GLOBALS['network'];
			$this->page		= & $GLOBALS['page'];
			$this->db2		= & $GLOBALS['db2'];
			// $this->fortype['follower']->"get_lastFollow_count";
			$this->fortype = array(
				'followers'=>"get_follower_list",
				"likes"=>"get_liked_list",
				"rebuzz"=>"get_rebuzz_list",
				// ""=>"",
				"replied on buzz"=>"get_replied_list",
				"follower created groups"=>"get_group_list",
				"follower joined a group"=>"get_joined_list",
				"changed profile info"=>"get_changeProfileInfo_list",
				"liked profile pic"=>"get_profileLiked_list",
				"invites to join group"=>"get_invitesMeToJoinGroup_list",
				"changed profile pic"=>"get_editprfilepic_list",
				"Follower Followed Someone"=>"get_followerFollowedSomeone_list"
				// ""=>"get_profileLiked_list",
				// ""=>"",
				// ""=>""
			);
        }

        public function generateToken($who) 
	    {
	        global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
    	    $oauth_access_token= $this->generate_request_token();
 
  	       //echo 'SELECT id FROM oauth_access_token WHERE user_id="'.$userid.'"  LIMIT 1';
	        
	        $res = $db2->query('SELECT id FROM oauth_access_token WHERE user_id='.$who.'  LIMIT 1');

		    if($db2->num_rows($res) > 0)
		    {
			    $obj = $db2->fetch_object($res);
			  
			    $res = $db2->query('Update oauth_access_token SET access_token = "'.$oauth_access_token.'" WHERE  id="'.intval($obj->id).'"');
			    
			    return $oauth_access_token;
		    }
		    else
		    {
		         $res = $db2->query('Insert into oauth_access_token (access_token,user_id) VALUES ("'.$oauth_access_token.'", "'.$who.'")');
                return $oauth_access_token;
    		}
	    }
	
	    
	  function generate_request_token()
	  {
	       
	      $request_token='';
	      $request_token = substr(md5(rand().time().rand()), 0, 22);
	      return $request_token;	
	  }
	  
	  	  public function validateToken($userid, $access_token)
	  {
 	   	    
        global $db2, $C;
        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
	        $sql = 'SELECT id FROM oauth_access_token WHERE user_id='.$userid.' and access_token="'.$access_token.'"  LIMIT 1';
	        
	
     	   $res = $db2->query($sql);
    	   if($db2->num_rows($res) > 0)
    	   {
    	        $obj = $db2->fetch_object($res);
    	        return true;
    	   }
	   
	       return true;
	  }



        public function get_follower_list($userid){
            global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
            // $qu='SELECT T.id,T.notif_type, T.in_group_id, T.from_user_id,T.to_user_id ,T.notif_object_type, T.notif_object_id, T.date,T.post_id,T.noti_postid,u.id,u.username,u.fullname,u.avatar,u.about_me from (SELECT id,notif_type, in_group_id, from_user_id,to_user_id, notif_object_type, notif_object_id, date,post_id,noti_postid FROM notifications WHERE to_user_id="'.$userid.'") as T INNER JOIN users as u on T.from_user_id=u.id WHERE notif_type="ntf_me_if_u_follows_me" ORDER BY `T`.`date` DESC LIMIT 50';
            // $qu='SELECT u.id,u.username,u.fullname,u.avatar,u.about_me from (SELECT id,notif_type, in_group_id, from_user_id,to_user_id, notif_object_type, notif_object_id, date,post_id,noti_postid FROM notifications WHERE to_user_id="'.$userid.'") as T INNER JOIN users as u on T.from_user_id=u.id WHERE notif_type="ntf_me_if_u_follows_me" ORDER BY `T`.`date` DESC LIMIT 50';
			
			$qu='SELECT DISTINCT
					*
				FROM
					(
					SELECT
						u.id,
						u.username,
						u.fullname,
						u.avatar,
						u.about_me,
						u.location,
						u.num_followers
					FROM
						(
						SELECT
							id,
							notif_type,
							in_group_id,
							from_user_id,
							to_user_id,
							notif_object_type,
							notif_object_id,
							DATE,
							post_id,
							noti_postid
						FROM
							notifications
						WHERE
							to_user_id = "'.$userid.'"
					) AS T
				INNER JOIN users AS u
				ON
					T.from_user_id = u.id
				WHERE
					notif_type = "ntf_me_if_u_follows_me"
				ORDER BY
					`T`.`date`
				DESC
				) AS R
				LIMIT 50';
			
            $qur = 	$db2->query($qu);
            while($row = $db2->fetch_object($qur)){
                $res[] = $row;
            }
            return $res;
		}
		// public function default_user_data(){
		// 	$arr=array("id"=>"","username"=>"","fullname"=>"","avatar"=>"","about_me"=>"");
		// 	return $arr;
		// }

        public function get_lastFollow_count($userid){
			// print_r("userid",$userid);
			global $C;
			$res = $this->get_follower_list($userid);
			// $arr["type"]="followers";
            // $arr["user"]=$this->default_user_data();
            // $arr["count"]=0;
            if(count($res)>=1){
				$arr["type"]="followers";
				$arr["user"]=$res[0];
				$avatar=$arr["user"]->avatar;
				// print_r($avatar);
				if($avatar!=""){
					$link = "$C->STORAGE_URL/avatars/thumbs1/$avatar";
					$arr["user"]->avatar= $link;
				}
				$arr["count"]=count($res)-1;
				$arr["message"]="started following you";
				
			}
			
			// print_r($arr);
			// $arr["message"]="started following you";
            return $arr;
		}

		public function get_liked_list($userid){
			global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
            // $qu='SELECT T.id,T.notif_type, T.in_group_id, T.from_user_id,T.to_user_id ,T.notif_object_type, T.notif_object_id, T.date,T.post_id,T.noti_postid,u.id,u.username,u.fullname,u.avatar,u.about_me from (SELECT id,notif_type, in_group_id, from_user_id,to_user_id, notif_object_type, notif_object_id, date,post_id,noti_postid FROM notifications WHERE to_user_id="'.$userid.'") as T INNER JOIN users as u on T.from_user_id=u.id WHERE notif_type="ntf_me_if_u_follows_me" ORDER BY `T`.`date` DESC LIMIT 50';
            $qu='SELECT DISTINCT
					*
				FROM
					(
					SELECT
						u.id,
						u.username,
						u.fullname,
						u.avatar,
						u.about_me,
						u.location,
						u.num_followers
					FROM
						(
						SELECT
							id,
							notif_type,
							in_group_id,
							from_user_id,
							to_user_id,
							notif_object_type,
							notif_object_id,
							DATE,
							post_id,
							noti_postid
						FROM
							notifications
						WHERE
							to_user_id = "'.$userid.'" AND from_user_id != "'.$userid.'"
					) AS T
				INNER JOIN users AS u
				ON
					T.from_user_id = u.id
				WHERE
					notif_type = "ntf_me_on_post_like"
				ORDER BY
					`T`.`date`
				DESC
				) AS R
				LIMIT 50';
				
            $qur = 	$db2->query($qu);
            while($row = $db2->fetch_object($qur)){
                $res[] = $row;
            }
            return $res;
		}

		public function get_liked_count($userid){
			// print_r("userid",$userid);
			global $C;
			$res = $this->get_liked_list($userid);
			// $arr["type"]="likes";
            // $arr["user"]=$this->default_user_data();
            // $arr["count"]=0;
            if(count($res)>=1){
				$arr["type"]="likes";

				$arr["user"]=$res[0];
				$avatar=$arr["user"]->avatar;
				// print_r($avatar);
				if($avatar!=""){
					$link = "$C->STORAGE_URL/avatars/thumbs1/$avatar";
					$arr["user"]->avatar= $link;
				}
				$arr["count"]=count($res)-1;
				$arr["message"]="have liked your post";
				
			}
			// "likes"=>"get_liked_list";
			// print_r($arr);
			// $arr["message"]="have liked your post";
            return $arr;
		}
		

		public function get_rebuzz_list($userid){
            global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
            // $qu='SELECT T.id,T.notif_type, T.in_group_id, T.from_user_id,T.to_user_id ,T.notif_object_type, T.notif_object_id, T.date,T.post_id,T.noti_postid,u.id,u.username,u.fullname,u.avatar,u.about_me from (SELECT id,notif_type, in_group_id, from_user_id,to_user_id, notif_object_type, notif_object_id, date,post_id,noti_postid FROM notifications WHERE to_user_id="'.$userid.'") as T INNER JOIN users as u on T.from_user_id=u.id WHERE notif_type="ntf_me_if_u_follows_me" ORDER BY `T`.`date` DESC LIMIT 50';
            $qu='SELECT DISTINCT
					*
				FROM
					(
					SELECT
						u.id,
						u.username,
						u.fullname,
						u.avatar,
						u.about_me,
						u.location,
						u.num_followers
					FROM
						(
						SELECT
							id,
							notif_type,
							in_group_id,
							from_user_id,
							to_user_id,
							notif_object_type,
							notif_object_id,
							DATE,
							post_id,
							noti_postid
						FROM
							notifications
						WHERE
							to_user_id = "'.$userid.'" AND from_user_id != "'.$userid.'"
					) AS T
				INNER JOIN users AS u
				ON
					T.from_user_id = u.id
				WHERE
					notif_type = "ntf_me_on_post_rebuzz"
				ORDER BY
					`T`.`date`
				DESC
				) AS R
				LIMIT 50';
            
            $qur = 	$db2->query($qu);
            while($row = $db2->fetch_object($qur)){
                $res[] = $row;
            }
            return $res;
        }

        public function get_rebuzz_count($userid){
			// print_r("userid",$userid);


			global $C;
			$res = $this->get_rebuzz_list($userid);
			
            // $arr["user"]=$this->default_user_data();
            // $arr["count"]=0;
            if(count($res)>=1){
				$arr["type"]="rebuzz";
				$arr["user"]=$res[0];
				$avatar=$arr["user"]->avatar;
				// print_r($avatar);
				if($avatar!=""){
					$link = "$C->STORAGE_URL/avatars/thumbs1/$avatar";
					$arr["user"]->avatar= $link;
				}
				$arr["count"]=count($res)-1;
				$arr["message"]="rebuzz your new/buzz";
				
            }
			// print_r($arr);
			// $arr["message"]="shared your new/buzz";
            return $arr;
		}

		public function get_replied_list($userid){
            global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
            // $qu='SELECT T.id,T.notif_type, T.in_group_id, T.from_user_id,T.to_user_id ,T.notif_object_type, T.notif_object_id, T.date,T.post_id,T.noti_postid,u.id,u.username,u.fullname,u.avatar,u.about_me from (SELECT id,notif_type, in_group_id, from_user_id,to_user_id, notif_object_type, notif_object_id, date,post_id,noti_postid FROM notifications WHERE to_user_id="'.$userid.'") as T INNER JOIN users as u on T.from_user_id=u.id WHERE notif_type="ntf_me_if_u_follows_me" ORDER BY `T`.`date` DESC LIMIT 50';
            $qu='SELECT DISTINCT
					*
				FROM
					(
					SELECT
						u.id,
						u.username,
						u.fullname,
						u.avatar,
						u.about_me,
						u.location,
						u.num_followers
					FROM
						(
						SELECT
							id,
							notif_type,
							in_group_id,
							from_user_id,
							to_user_id,
							notif_object_type,
							notif_object_id,
							DATE,
							post_id,
							noti_postid
						FROM
							notifications
						WHERE
							to_user_id = "'.$userid.'" AND from_user_id != "'.$userid.'"
					) AS T
				INNER JOIN users AS u
				ON
					T.from_user_id = u.id
				WHERE
					notif_type = "ntf_me_on_post_replay"
				ORDER BY
					`T`.`date`
				DESC
				) AS R
				LIMIT 50';
            
            $qur = 	$db2->query($qu);
            while($row = $db2->fetch_object($qur)){
                $res[] = $row;
            }
            return $res;
        }

        public function get_replied_count($userid){
			// print_r("userid",$userid);
			global $C;
			$res = $this->get_replied_list($userid);
			// $arr["type"]="replied";
            // $arr["user"]=$this->default_user_data();
			// $arr["count"]=0;
			// print_r($res[0]);
			
            if(count($res)>=1){
				$arr["type"]="replied on buzz";

				$arr["user"]=$res[0];
				$avatar=$arr["user"]->avatar;
				// print_r($avatar);
				if($avatar!=""){
					$link = "$C->STORAGE_URL/avatars/thumbs1/$avatar";
					$arr["user"]->avatar= $link;
				}
				$arr["count"]=count($res)-1;
				$arr["message"]="has replied on your buzz";
				
            }
			// // print_r($arr);
			// $arr["message"]="has replied on your buzz";
            return $arr;
		}

		public function get_group_list($userid){
            global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
            // $qu='SELECT T.id,T.notif_type, T.in_group_id, T.from_user_id,T.to_user_id ,T.notif_object_type, T.notif_object_id, T.date,T.post_id,T.noti_postid,u.id,u.username,u.fullname,u.avatar,u.about_me from (SELECT id,notif_type, in_group_id, from_user_id,to_user_id, notif_object_type, notif_object_id, date,post_id,noti_postid FROM notifications WHERE to_user_id="'.$userid.'") as T INNER JOIN users as u on T.from_user_id=u.id WHERE notif_type="ntf_me_if_u_follows_me" ORDER BY `T`.`date` DESC LIMIT 50';
            $qu='SELECT DISTINCT
					*
				FROM
					(
					SELECT
						u.id,
						u.username,
						u.fullname,
						u.avatar,
						u.about_me,
						u.location,
						u.num_followers
					FROM
						(
						SELECT
							id,
							notif_type,
							in_group_id,
							from_user_id,
							to_user_id,
							notif_object_type,
							notif_object_id,
							DATE,
							post_id,
							noti_postid
						FROM
							notifications
						WHERE
							to_user_id = "'.$userid.'"
					) AS T
				INNER JOIN users AS u
				ON
					T.from_user_id = u.id
				WHERE
					notif_type = "ntf_me_if_u_creates_grp"
				ORDER BY
					`T`.`date`
				DESC
				) AS R
				LIMIT 50';
            
            $qur = 	$db2->query($qu);
            while($row = $db2->fetch_object($qur)){
                $res[] = $row;
            }
            return $res;
        }

        public function get_group_count($userid){
			// print_r("userid",$userid);
			global $C;
			$res = $this->get_group_list($userid);
			// $arr["type"]="groups";
            // $arr["user"]=$this->default_user_data();
            // $arr["count"]=0;
            if(count($res)>=1){
				$arr["type"]="follower created groups";

				$arr["user"]=$res[0];
				$avatar=$arr["user"]->avatar;
				// print_r($avatar);
				if($avatar!=""){
					$link = "$C->STORAGE_URL/avatars/thumbs1/$avatar";
					$arr["user"]->avatar= $link;
				}
				$arr["count"]=count($res)-1;
				$arr["message"]="has created a groups";
				
            }
			// print_r($arr);
			// $arr["message"]="has created a groups";
            return $arr;
		}
		
		public function get_joined_list($userid){
            global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
            // $qu='SELECT T.id,T.notif_type, T.in_group_id, T.from_user_id,T.to_user_id ,T.notif_object_type, T.notif_object_id, T.date,T.post_id,T.noti_postid,u.id,u.username,u.fullname,u.avatar,u.about_me from (SELECT id,notif_type, in_group_id, from_user_id,to_user_id, notif_object_type, notif_object_id, date,post_id,noti_postid FROM notifications WHERE to_user_id="'.$userid.'") as T INNER JOIN users as u on T.from_user_id=u.id WHERE notif_type="ntf_me_if_u_follows_me" ORDER BY `T`.`date` DESC LIMIT 50';
            $qu='SELECT DISTINCT
					*
				FROM
					(
					SELECT
						u.id,
						u.username,
						u.fullname,
						u.avatar,
						u.about_me,
						u.location,
						u.num_followers
					FROM
						(
						SELECT
							id,
							notif_type,
							in_group_id,
							from_user_id,
							to_user_id,
							notif_object_type,
							notif_object_id,
							DATE,
							post_id,
							noti_postid
						FROM
							notifications
						WHERE
							to_user_id = "'.$userid.'" AND from_user_id != "'.$userid.'"
					) AS T
				INNER JOIN users AS u
				ON
					T.from_user_id = u.id
				WHERE
					notif_type = "ntf_me_if_u_joins_grp"
				ORDER BY
					`T`.`date`
				DESC
				) AS R
				LIMIT 50';
            
            $qur = 	$db2->query($qu);
            while($row = $db2->fetch_object($qur)){
                $res[] = $row;
            }
            return $res;
        }

        public function get_joined_count($userid){
			// print_r("userid",$userid);
			global $C;
			$res = $this->get_joined_list($userid);
			// $arr["type"]="joined";
            // $arr["user"]=$this->default_user_data();
			// $arr["count"]=0;
            if(count($res)>=1){
				$arr["type"]="follower joined a group";

				$arr["user"]=$res[0];
				$avatar=$arr["user"]->avatar;
				// print_r($avatar);
				if($avatar!=""){
					$link = "$C->STORAGE_URL/avatars/thumbs1/$avatar";
					$arr["user"]->avatar= $link;
				}
				$arr["count"]=count($res)-1;
				$arr["message"]="has joins in a group";
				
			}
			// $arr["message"]="has joined in a group";
            // print_r($arr);
            return $arr;
		}

		public function get_changeProfileInfo_list($userid){
            global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
            // $qu='SELECT T.id,T.notif_type, T.in_group_id, T.from_user_id,T.to_user_id ,T.notif_object_type, T.notif_object_id, T.date,T.post_id,T.noti_postid,u.id,u.username,u.fullname,u.avatar,u.about_me from (SELECT id,notif_type, in_group_id, from_user_id,to_user_id, notif_object_type, notif_object_id, date,post_id,noti_postid FROM notifications WHERE to_user_id="'.$userid.'") as T INNER JOIN users as u on T.from_user_id=u.id WHERE notif_type="ntf_me_if_u_follows_me" ORDER BY `T`.`date` DESC LIMIT 50';
            $qu='SELECT DISTINCT
					*
				FROM
					(
					SELECT
						u.id,
						u.username,
						u.fullname,
						u.avatar,
						u.about_me,
						u.location,
						u.num_followers
					FROM
						(
						SELECT
							id,
							notif_type,
							in_group_id,
							from_user_id,
							to_user_id,
							notif_object_type,
							notif_object_id,
							DATE,
							post_id,
							noti_postid
						FROM
							notifications
						WHERE
							to_user_id = "'.$userid.'" AND from_user_id != "'.$userid.'"
					) AS T
				INNER JOIN users AS u
				ON
					T.from_user_id = u.id
				WHERE
					notif_type = "ntf_me_if_u_edt_profl"
				ORDER BY
					`T`.`date`
				DESC
				) AS R
				LIMIT 50';
            $qur = 	$db2->query($qu);
            while($row = $db2->fetch_object($qur)){
                $res[] = $row;
            }
            return $res;
		}
		// public function default_user_data(){
		// 	$arr=array("id"=>"","username"=>"","fullname"=>"","avatar"=>"","about_me"=>"");
		// 	return $arr;
		// }

        public function get_lastChangeProfileInfo_count($userid){
			// print_r("userid",$userid);
			global $C;
			$res = $this->get_changeProfileInfo_list($userid);
			// $arr["type"]="followers";
            // $arr["user"]=$this->default_user_data();
            // $arr["count"]=0;
            if(count($res)>=1){
				$arr["type"]="changed profile info";
				$arr["user"]=$res[0];
				$avatar=$arr["user"]->avatar;
				// print_r($avatar);
				if($avatar!=""){
					$link = "$C->STORAGE_URL/avatars/thumbs1/$avatar";
					$arr["user"]->avatar= $link;
				}
				$arr["count"]=count($res)-1;
				$arr["message"]="changed there profile info.";
				
            }
			// print_r($arr);
			// $arr["message"]="started following you";
            return $arr;
		}

		public function get_profileLiked_list($userid){
            global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
            // $qu='SELECT T.id,T.notif_type, T.in_group_id, T.from_user_id,T.to_user_id ,T.notif_object_type, T.notif_object_id, T.date,T.post_id,T.noti_postid,u.id,u.username,u.fullname,u.avatar,u.about_me from (SELECT id,notif_type, in_group_id, from_user_id,to_user_id, notif_object_type, notif_object_id, date,post_id,noti_postid FROM notifications WHERE to_user_id="'.$userid.'") as T INNER JOIN users as u on T.from_user_id=u.id WHERE notif_type="ntf_me_if_u_follows_me" ORDER BY `T`.`date` DESC LIMIT 50';
            $qu='SELECT DISTINCT
					*
				FROM
					(
					SELECT
						u.id,
						u.username,
						u.fullname,
						u.avatar,
						u.about_me,
						u.location,
						u.num_followers
					FROM
						(
						SELECT
							id,
							notif_type,
							in_group_id,
							from_user_id,
							to_user_id,
							notif_object_type,
							notif_object_id,
							DATE,
							post_id,
							noti_postid
						FROM
							notifications
						WHERE
							to_user_id = "'.$userid.'" AND from_user_id != "'.$userid.'"
					) AS T
				INNER JOIN users AS u
				ON
					T.from_user_id = u.id
				WHERE
					notif_type = "ntf_me_on_post_profileloved"
				ORDER BY
					`T`.`date`
				DESC
				) AS R
				LIMIT 50';
            $qur = 	$db2->query($qu);
            while($row = $db2->fetch_object($qur)){
                $res[] = $row;
            }
            return $res;
		}
		// public function default_user_data(){
		// 	$arr=array("id"=>"","username"=>"","fullname"=>"","avatar"=>"","about_me"=>"");
		// 	return $arr;
		// }

        public function get_lastProfileLiked_count($userid){
			// print_r("userid",$userid);
			global $C;
			$res = $this->get_profileLiked_list($userid);
			// $arr["type"]="followers";
            // $arr["user"]=$this->default_user_data();
            // $arr["count"]=0;
            if(count($res)>=1){
				$arr["type"]="liked profile pic";
				$arr["user"]=$res[0];
				$avatar=$arr["user"]->avatar;
				// print_r($avatar);
				if($avatar!=""){
					$link = "$C->STORAGE_URL/avatars/thumbs1/$avatar";
					$arr["user"]->avatar= $link;
				}
				$arr["count"]=count($res)-1;
				$arr["message"]="liked your profile picture.";
				
            }
			// print_r($arr);
			// $arr["message"]="started following you";
            return $arr;
		}

		public function get_invitesMeToJoinGroup_list($userid){
            global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
            // $qu='SELECT T.id,T.notif_type, T.in_group_id, T.from_user_id,T.to_user_id ,T.notif_object_type, T.notif_object_id, T.date,T.post_id,T.noti_postid,u.id,u.username,u.fullname,u.avatar,u.about_me from (SELECT id,notif_type, in_group_id, from_user_id,to_user_id, notif_object_type, notif_object_id, date,post_id,noti_postid FROM notifications WHERE to_user_id="'.$userid.'") as T INNER JOIN users as u on T.from_user_id=u.id WHERE notif_type="ntf_me_if_u_follows_me" ORDER BY `T`.`date` DESC LIMIT 50';
            $qu='SELECT DISTINCT
					*
				FROM
					(
					SELECT
						u.id,
						u.username,
						u.fullname,
						u.avatar,
						u.about_me,
						u.location,
						u.num_followers
					FROM
						(
						SELECT
							id,
							notif_type,
							in_group_id,
							from_user_id,
							to_user_id,
							notif_object_type,
							notif_object_id,
							DATE,
							post_id,
							noti_postid
						FROM
							notifications
						WHERE
							to_user_id = "'.$userid.'" AND from_user_id != "'.$userid.'"
					) AS T
				INNER JOIN users AS u
				ON
					T.from_user_id = u.id
				WHERE
					notif_type = "ntf_me_if_u_invit_me_grp"
				ORDER BY
					`T`.`date`
				DESC
				) AS R
				LIMIT 50';
            $qur = 	$db2->query($qu);
            while($row = $db2->fetch_object($qur)){
                $res[] = $row;
            }
            return $res;
		}
		// public function default_user_data(){
		// 	$arr=array("id"=>"","username"=>"","fullname"=>"","avatar"=>"","about_me"=>"");
		// 	return $arr;
		// }

        public function get_lastInvitesMeToJoinGroup_count($userid){
			// print_r("userid",$userid);
			global $C;
			$res = $this->get_invitesMeToJoinGroup_list($userid);
			// $arr["type"]="followers";
            // $arr["user"]=$this->default_user_data();
            // $arr["count"]=0;
            if(count($res)>=1){
				$arr["type"]="invites to join group";
				$arr["user"]=$res[0];
				$avatar=$arr["user"]->avatar;
				// print_r($avatar);
				if($avatar!=""){
					$link = "$C->STORAGE_URL/avatars/thumbs1/$avatar";
					$arr["user"]->avatar= $link;
				}
				$arr["count"]=count($res)-1;
				$arr["message"]="invites you to join group";
				
            }
			// print_r($arr);
			// $arr["message"]="started following you";
            return $arr;
		}
		
		
		public function get_editprfilepic_list($userid){
            global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
            // $qu='SELECT T.id,T.notif_type, T.in_group_id, T.from_user_id,T.to_user_id ,T.notif_object_type, T.notif_object_id, T.date,T.post_id,T.noti_postid,u.id,u.username,u.fullname,u.avatar,u.about_me from (SELECT id,notif_type, in_group_id, from_user_id,to_user_id, notif_object_type, notif_object_id, date,post_id,noti_postid FROM notifications WHERE to_user_id="'.$userid.'") as T INNER JOIN users as u on T.from_user_id=u.id WHERE notif_type="ntf_me_if_u_follows_me" ORDER BY `T`.`date` DESC LIMIT 50';
            $qu='SELECT DISTINCT
					*
				FROM
					(
					SELECT
						u.id,
						u.username,
						u.fullname,
						u.avatar,
						u.about_me,
						u.location,
						u.num_followers
					FROM
						(
						SELECT
							id,
							notif_type,
							in_group_id,
							from_user_id,
							to_user_id,
							notif_object_type,
							notif_object_id,
							DATE,
							post_id,
							noti_postid
						FROM
							notifications
						WHERE
							to_user_id = "'.$userid.'" AND from_user_id != "'.$userid.'"
					) AS T
				INNER JOIN users AS u
				ON
					T.from_user_id = u.id
				WHERE
					notif_type = "ntf_me_if_u_edt_pictr"
				ORDER BY
					`T`.`date`
				DESC
				) AS R
				LIMIT 50';
            $qur = 	$db2->query($qu);
            while($row = $db2->fetch_object($qur)){
                $res[] = $row;
            }
            return $res;
		}
		// public function default_user_data(){
		// 	$arr=array("id"=>"","username"=>"","fullname"=>"","avatar"=>"","about_me"=>"");
		// 	return $arr;
		// }

        public function get_editprfilepic_count($userid){
			// print_r("userid",$userid);
			global $C;
			$res = $this->get_editprfilepic_list($userid);
			// $arr["type"]="followers";
            // $arr["user"]=$this->default_user_data();
            // $arr["count"]=0;
            if(count($res)>=1){
				$arr["type"]="changed profile pic";
				$arr["user"]=$res[0];
				$avatar=$arr["user"]->avatar;
				// print_r($avatar);
				if($avatar!=""){
					$link = "$C->STORAGE_URL/avatars/thumbs1/$avatar";
					$arr["user"]->avatar= $link;
				}
				$arr["count"]=count($res)-1;
				$arr["message"]="changed their profile picture.";
				
            }
			// print_r($arr);
			// $arr["message"]="started following you";
            return $arr;
		}


		public function get_followerFollowedSomeone_list($userid){
            global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
            // $qu='SELECT T.id,T.notif_type, T.in_group_id, T.from_user_id,T.to_user_id ,T.notif_object_type, T.notif_object_id, T.date,T.post_id,T.noti_postid,u.id,u.username,u.fullname,u.avatar,u.about_me from (SELECT id,notif_type, in_group_id, from_user_id,to_user_id, notif_object_type, notif_object_id, date,post_id,noti_postid FROM notifications WHERE to_user_id="'.$userid.'") as T INNER JOIN users as u on T.from_user_id=u.id WHERE notif_type="ntf_me_if_u_follows_me" ORDER BY `T`.`date` DESC LIMIT 50';
            $qu='SELECT DISTINCT
					*
				FROM
					(
					SELECT
						u.id,
						u.username,
						u.fullname,
						u.avatar,
						u.about_me,
						u.location,
						u.num_followers
					FROM
						(
						SELECT
							id,
							notif_type,
							in_group_id,
							from_user_id,
							to_user_id,
							notif_object_type,
							notif_object_id,
							DATE,
							post_id,
							noti_postid
						FROM
							notifications
						WHERE
							to_user_id = "'.$userid.'"
					) AS T
				INNER JOIN users AS u
				ON
					T.from_user_id = u.id
				WHERE
					notif_type = "ntf_me_if_u_follows_u2"
				ORDER BY
					`T`.`date`
				DESC
				) AS R
				LIMIT 50';
            $qur = 	$db2->query($qu);
            while($row = $db2->fetch_object($qur)){
                $res[] = $row;
            }
            return $res;
		}
		// public function default_user_data(){
		// 	$arr=array("id"=>"","username"=>"","fullname"=>"","avatar"=>"","about_me"=>"");
		// 	return $arr;
		// }

        public function get_followerFollowedSomeone_count($userid){
			// print_r("userid",$userid);
			global $C;
			$res = $this->get_followerFollowedSomeone_list($userid);
			// $arr["type"]="followers";
            // $arr["user"]=$this->default_user_data();
            // $arr["count"]=0;
            if(count($res)>=1){
				$arr["type"]="Follower Followed Someone";
				$arr["user"]=$res[0];
				$avatar=$arr["user"]->avatar;
				// print_r($avatar);
				if($avatar!=""){
					$link = "$C->STORAGE_URL/avatars/thumbs1/$avatar";
					$arr["user"]->avatar= $link;
				}
				$arr["count"]=count($res)-1;
				$arr["message"]="started following someone.";
				
			}
			



			// print_r($arr);
			// $arr["message"]="started following you";
            return $arr;
		}




		public function changeAvatar(&$arr){
			global $C;
	        // $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
			
			$n=sizeof($arr);
			for( $i=0; $i<$n; $i++){
				$temp = $arr[$i]->avatar;
				// $res = $db2->query('SELECT * FROM `users_followed` WHERE who= and whom = 2');
				if($temp!="")
				$arr[$i]->avatar = "$C->STORAGE_URL/avatars/thumbs1/$temp";
			}
		}
		public function isFollow(&$arr,$userid){
			global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
			
			$n=sizeof($arr);
			for( $i=0; $i<$n; $i++){
				$temp = $arr[$i]->id;
				$var = "isfollow";
				$res = $db2->query('SELECT * FROM `users_followed` WHERE who="'.$userid.'" and whom = "'.$temp.'"');
				$arr[$i]->$var=0;
				if($res->num_rows>=1){
					// print_r($res);
					$arr[$i]->$var=1;
				}
			}
		}

		public function userlist($userid,$type){
			$func = $this->fortype[$type];
			$arr = $this->$func($userid);
			$this->changeAvatar($arr);
			$this->isFollow($arr,$userid);
			return $arr;
		}

        
    }


?>