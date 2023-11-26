<?php

error_reporting(0);


 	class API
	{
	    
	    
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
    
  
	  
	  public function setPostComments($userid,$postid,$message) {
	      	global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
			//$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);
			$ip_address = ip2long($_SERVER['REMOTE_ADDR']);
            $post_res = $db2->query('SELECT user_id, message FROM posts WHERE id=' . $postid . '  LIMIT 1');
            if ($db2->num_rows($post_res) > 0) {
                $obj = $db2->fetch_object($post_res);
                $notify_user_id = $obj->user_id;
                $pmessage = $obj->message;


                $res = $db2->query('Insert into posts_comments (user_id,post_id, message,date,ip_addr) VALUES ('.$userid.','.$postid.',"'.$message.'","'.time().'","'.$ip_address.'")');
        
                $data = array();
                $data['id'] = $userid;
                $data['postid'] = $postid;
                //$data['body'] = $message;
                $data['notification_type'] = 'comment';
                send_push_notification($notify_user_id, $data); 
                  
                if ((int)$db2->insert_id() > 0) {
                    $r = $db2->query('select count(id) as sharecount  FROM posts_comments WHERE  post_id="' . $postid . '"', FALSE);
                    $result = $db2->fetch_object($r);
                    return $result->sharecount;
                } 
            }  
			return false;
	  }
	  
	  public function getPostComments($postid)
	  {
	      	global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
			//$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);
			$ip_address = ip2long($_SERVER['REMOTE_ADDR']);
 			$res = $db2->query('Select p.id as commentid,user_id, message,p.date,u.username,u.avatar,u.fullname from posts_comments p LEFT JOIN users u ON u.id=p.user_id where post_id  =  '.$postid.' order by p.date desc');
 			
  			return $res->fetch_all(MYSQLI_ASSOC);

	  }
	  public function loadPostDetails1($pzostid,$pagenumber,$pagerecordcount)
	  {
	        global $db2, $C;
 	   	    $pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);
 	   	    
 	   	    $pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);
 	   	    
 	   	    
	        $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
//                        SUBSTRING(p.message, 1, 75) as title, 
	        
			$query = 'SELECT 
                        p.id AS postid,
                        p.user_id AS postuserid,
                        u.username AS postusername,
                        u.avatar AS postuserimage,
                        if( (u.cover is null), "", u.cover) AS coverimage,
                        if( (posttype = 2), p.title,  SUBSTRING(p.message, 1, 75)) AS title,
                        p.message,
                        p.posttype,
                        p.likes,
                        p.mentioned,
						GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
						GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachments,
						p.attached,
						"" as attachment,
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
                        u.username AS `commentdetails`,
                        "" as `category`,
                        "public" AS `type`,
						if( (pv.cnt is null), 0, pv.cnt) AS ViewCount
						(Select count(id) from  profile_share where whom =p.user_id ) AS profilesharecount,
							FROM post_userbox b
							LEFT JOIN posts p ON p.id=b.post_id
							LEFT JOIN users u ON u.id=p.user_id
							LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
							LEFT OUTER JOIN post_views_list pv ON pv.post_id = p.id
						WHERE
                         p.id='.$postid.' AND (INSTR (pa.data, "<div") < 1 or INSTR (pa.data, "<div") is null)
                            Group By p.id
                            ORDER BY p.date_lastcomment DESC
                         LIMIT '.$pagenumber.','.$pagerecordcount;
           // echo $query;
			
             $res =  $db2->query($query);
  			$array = $res->fetch_all(MYSQLI_ASSOC);
			$returnarray = array();
 							$dom = new DomDocument();

			foreach ($array as $i =>$array_expression)
            {
                $array[$i]["likes"] = $this->getLikes($array[$i]["postid"]);
                $array[$i]["attachement"] = $this->getAttachements($array[$i]["postid"]);
                $array[$i]["comments"] = $this->getCommentsCount($array[$i]["postid"]);
				$array[$i]["commentdetails"] = $this->getPostComments($array[$i]["postid"]);
				
				
				 $query = 'SELECT * FROM post_likes WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
                $res =  $db2->query($query);
                $obj = $db2->fetch_object($res);
                
                //echo $obj->id."-"; die;
                
                if(empty($obj->id)){
                  $array[$i]["isliked"] = "0";  
                }
                
                else {
                    $array[$i]["isliked"] = "1";  
                }
				
				
				
    			$query1 = 'SELECT * FROM post_reshares WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
                $res1 =  $db2->query($query1);
                $obj1 = $db2->fetch_object($res1);
			
			  
                if(empty($obj1->id)){
                  $array[$i]["isbuzzed"] = "0";  
                }
                
                else {
                    $array[$i]["isbuzzed"] = "1";  
                }
                
                
                
					$query1 = 'SELECT * FROM profile_share WHERE  who="'.$user_id.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
                $res1 =  $db2->query($query1);
                $obj1 = $db2->fetch_object($res1);
			
			  
                if(empty($obj1->id)){
                  $array[$i]["isshared"] = "0";  
                }
                
                else {
                    $array[$i]["isshared"] = "1";  
                }
                
                
                $query3 = 'SELECT * FROM users_followed WHERE  who="'.$array[$i]["postuserid"].'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
                $res3 =  $db2->query($query3);
                $obj3 = $db2->fetch_object($res3);
				
		          if(empty($obj3->id)){
                  $array[$i]["isfollwed"] = "0";  
                }
                
                else {
                    $array[$i]["isfollwed"] = "1";  
                }
                
                $array[$i]["likes"]      =$this->get_like_count($array[$i]["postid"]);
                $array[$i]["reshares"]       =$this->new_reshare_count($array[$i]["postid"]);

                
				
				 $strHTML = "";				
                if ($array[$i]["posttype"] == 6)//need to remove '\n'
                {
                    $strHTML = "<HTML><H3>".$array[$i]["title"]."</H3><span>".$array[$i]["message"]."<span>".$this->getAttachementsHTML($array[$i]["postid"])."</HTML>";
                    $array[$i]["message"] = $strHTML;
                }
                
                 if ($array[$i]["posttype"] == 4)//need to remove '\n'
                {
                    
                $query = 'SELECT * FROM event_posts WHERE  post_id="'.$array[$i]["postid"].'" LIMIT 1';
                $res =  $db2->query($query);
                $obj = $db2->fetch_object($res);
                $event_id = $obj->event_id;
                
                
                $query = 'SELECT * FROM events WHERE  id="'.$event_id.'" LIMIT 1';
                $res =  $db2->query($query);
                 $array[$i]["event_detail"] = $db2->fetch_object($res);
                
                }
                

                $returnarray[] = $array[$i];
            }
			
			return $returnarray;
			
		 
	  }
	  public function loadPostDetails($postid,$pagenumber,$pagerecordcount)
	  {
	        global $db2, $C;
 	   	    $pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);
 	   	    
 	   	    $pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);
 	   	    
 	   	    
	        $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
//                        SUBSTRING(p.message, 1, 75) as title, 
	        
			$query = 'SELECT 
                        p.id AS postid,
                        p.user_id AS postuserid,
                        u.username AS postusername,
                        u.avatar AS postuserimage,
                        if( (u.cover is null), "", u.cover) AS coverimage,
                        if( (posttype = 2), p.title,  SUBSTRING(p.message, 1, 75)) AS title,
                        p.posttype,
                        p.likes,
                        p.mentioned,
						GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
						GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachments,
						p.attached,
						"" as attachment,
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
						if( (pv.cnt is null), 0, pv.cnt) AS ViewCount
					 (Select count(id) from  profile_share where whom =p.user_id ) AS profilesharecount,
							FROM post_userbox b
							LEFT JOIN posts p ON p.id=b.post_id
							LEFT JOIN users u ON u.id=p.user_id
							LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
							LEFT OUTER JOIN post_views_list pv ON pv.post_id = p.id
						WHERE
                        (p.id='.$postid.' OR p.parent_id='.$postid.')  AND p.id is not null and (INSTR (pa.data, "<div") < 1 or INSTR (pa.data, "<div") is null)
                            Group By p.id
                            ORDER BY p.date_lastcomment DESC
                         LIMIT '.$pagenumber.','.$pagerecordcount;
            //echo $query;
			
             $res =  $db2->query($query);
  			$array = $res->fetch_all(MYSQLI_ASSOC);
			$returnarray = array();
 							$dom = new DomDocument();

			foreach ($array as $i =>$array_expression)
            {
                if(empty($array[$i]["attachements"])) {
                    $array[$i]["attachements"] = '';
                }

                if(empty($array[$i]["coverimage"])) {
                    $array[$i]["coverimage"] = '';
                }

                $array[$i]["likes"] = $this->getLikes($array[$i]["postid"]);
                $array[$i]["attachement"] = $this->getAttachements($array[$i]["postid"]);
                $array[$i]["comments"] = $this->getCommentsCount($array[$i]["postid"]);
                
                 $query = 'SELECT * FROM post_likes WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
                $res =  $db2->query($query);
                $obj = $db2->fetch_object($res);
                
                //echo $obj->id."-"; die;
                
                if(empty($obj->id)){
                  $array[$i]["isliked"] = "0";  
                }
                
                else {
                    $array[$i]["isliked"] = "1";  
                }
                
                
                
					$query1 = 'SELECT * FROM profile_share WHERE  who="'.$user_id.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
                $res1 =  $db2->query($query1);
                $obj1 = $db2->fetch_object($res1);
			
			  
                if(empty($obj1->id)){
                  $array[$i]["isshared"] = "0";  
                }
                
                else {
                    $array[$i]["isshared"] = "1";  
                }
				
				
				
    			$query1 = 'SELECT * FROM post_reshares WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
                $res1 =  $db2->query($query1);
                $obj1 = $db2->fetch_object($res1);
			
			  
                if(empty($obj1->id)){
                  $array[$i]["isbuzzed"] = "0";  
                }
                
                else {
                    $array[$i]["isbuzzed"] = "1";  
                }
                
                
                	$query3 = 'SELECT * FROM users_followed WHERE  who="'.$user_id.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
         $res3 =  $db2->query($query3);
                $obj3 = $db2->fetch_object($res3);
				
		 if(empty($obj3->id)){
                  $array[$i]["isfollwed"] = "0";  
                }
                
                else {
                    $array[$i]["isfollwed"] = "1";  
                }
                
                
                 $array[$i]["likes"]      =$this->get_like_count($array[$i]["postid"]);
                 
                 	$array[$i]["reshares"]       =$this->new_reshare_count($array[$i]["postid"]);

				

                $returnarray[] = $array[$i];
            }
            
            
            
            
            
            
            
			
			return $returnarray;
			
		 
	  }
		public function submitPhotoStoryPost($user_id,$postdata,$images,$parentid)
		{
			 			global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
			
			$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);

			//$upload_dir = $_SERVER['DOCUMENT_ROOT'].'/develop/storage/attachments/1/';
            $source_dir = $C->SITE_URL.'storage/tmp/';
            $destination_dir = $C->SITE_URL.'storage/tmp/';
		

            $post_level  = 1;
            if ((int)$parentid==0)
            {
                $post_level = 0;
            } 
 			
 			$db2->query('Insert into posts (user_id,message,date,date_lastcomment,ip_addr,parent_id,post_level,attached,posttype) VALUES ('.$user_id.',"'.mysqli_real_escape_string($link,$postdata).'","'.time().'","'.time().'","'.ip2long($_SERVER['REMOTE_ADDR']).'",'.$parentid.','.$post_level.','.count($images).',1)');
			
 	
			$post_id	= (int) $db2->insert_id();

			if ((int)$parentid>0)
            {
				$series = 'a:2:{i:0;s:5:"'.$parentid.'";i:1;i:'.$post_id.';}';
                $db2->query('INSERT INTO `post_replay`(`parent_id`, `alternate_parent_id`, `replay_id`, `action_type`, `series`) VALUES ('.$parentid.','.$parentid.','.$post_id.',"buzz","'.$series.'")');
            } 
			$db2->query('Insert into posts_comments_watch (user_id,post_id) VALUES ('.$user_id.','.$post_id.')');
			
			$followers = $this->getAllFollowersUserID($user_id);

            $db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$user_id.','.$post_id.')');
			
			foreach ($followers as $i =>$array_expression)
            {
                $db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$followers[$i]["who"].','.$post_id.')');
            }
			
			$p = new newpost();
 			if( isset($images) ){ 
					foreach($images as $img){ 
                		if( $ii = $p->attach_image($C->STORAGE_TMP_DIR.$img["image"], $img["image"]) ) {
                		    
     							$db2->query('INSERT INTO `posts_attachments`(`post_id`, `type`, `data`,  `content`) VALUES ('.$post_id.',\'Image\',\''.$db2->escape(serialize($ii)).'\',\''.$imagecaption.'\')');
 
                                $newimg = $this->splitAttachements(serialize($ii));
                                
                                foreach ($newimg as $tmpimg)
                                {
                                    rename($C->STORAGE_TMP_DIR.$tmpimg,$C->STORAGE_DIR.'attachments/1/'.$tmpimg);
                                }
                		}
					}
					unset($images);
				}
			return $post_id;

		}
		
		public function updateProfileImage($userid,$avatar)
		{

			global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
            
			$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
			$lastlogin_date = time();

			$sql ='UPDATE users SET
 				avatar="'.$db2->e($avatar).'",
 				lastlogin_date="'.$lastlogin_date.'", 
 				lastlogin_ip="'.$lastlogin_ip.'" 
 				Where ID='.$userid;
 			//	echo $sql;
 				$db2->query($sql);
			return true;
		}
	 //$avatar_name,str_replace(' ', '-',strtolower($random_name)), str_replace(' ', '-',strtolower($server_url.$random_name))
		public function insertTempFileDetails($filename,$generatedfilename,$url)
		{

			global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

			$db2->query('Insert into tempfile (filename,generatedfilename,url) VALUES ("'.$filename.'","'.$generatedfilename.'","'.$url.'")');
		}
		private function getAllFollowersUserID($userid)
		{
			global $db2;
			$query = 'SELECT who from users_followed where whom='.$userid.' AND who != '.$userid.' group by who' ;
			$res =  $db2->query($query);
  			return $res->fetch_all(MYSQLI_ASSOC);
		}
 		public function submitPost($user_id,$postdata,$parentid,$title,$coverimage)
		{
            global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
			
			$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);

            $post_level  = 1;
            if ((int)$parentid==0)
            {
                $post_level = 0;
            } 


 			$db2->query('Insert into posts (user_id,message,date,date_lastcomment,ip_addr,parent_id,post_level,posttype,title,coverimage) VALUES ('.$user_id.',"'.mysqli_real_escape_string($link,$postdata).'","'.time().'","'.time().'","'.ip2long($_SERVER['REMOTE_ADDR']).'",'.$parentid.','.$post_level.',2,"'.$title.'","'.$coverimage.'")');//$title,$imageur
			
 	
			$post_id	= (int) $db2->insert_id();
			if ((int)$parentid>0)
            {

				$series = 'a:2:{i:0;s:5:\"'.$parentid.'\";i:1;i:'.$post_id.';}';
 
                      
                $db2->query('INSERT INTO `post_replay`(`parent_id`, `alternate_parent_id`, `replay_id`, `action_type`, `series`) VALUES ('.$parentid.','.$parentid.','.$post_id.',"buzz","'.$series.'")');
			 } 
			$db2->query('Insert into posts_comments_watch (user_id,post_id) VALUES ('.$user_id.','.$post_id.')');
			
			$followers = $this->getAllFollowersUserID($user_id);
			//echo $followers;
            $db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$user_id.','.$post_id.')');
			
			foreach ($followers as $i =>$array_expression)
            {
                $db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$followers[$i]["who"].','.$post_id.')');
            }

			return $post_id;
		}
		
		public function __construct()
		{
   		}
	    public function checkUserForEdit($username,$userid)
	    {
 	   	    global $db2, $C;
 	   	    
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
	        $sql = '';
 	        
	        if (!empty($username) && !empty($userid))
	        {
	            if (is_numeric($username))	
	            {
    	            $sql = 'SELECT id FROM users WHERE  phone_no="'.$username.'"  AND id != '.$userid.' AND active=1  LIMIT 1';
	            }
	            else
	            {
	                $sql = 'SELECT id FROM users WHERE (username="'.$username.'" OR email="'.$username.'") AND id != '.$userid.' AND active=1  LIMIT 1';
	            }
	        }
	         if (!empty($sql))
	       {
      	       $res = $db2->query($sql);
    	       if($db2->num_rows($res) > 0)
    	       {
                                
    	           $obj = $db2->fetch_object($res);
    	           return intval($obj->id);
    	       }
	       }
 	       return 0;
	    }
	    public function checkUser($username,$password='')
	    {
 	   	    global $db2, $C;
 	   	    
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
	        $sql = '';
 	        
	        if (!empty($username) && !empty($password))
	        {
	            if (is_numeric($username))
	            {
    	            $sql = 'SELECT id FROM users WHERE  phone_no="'.$username.'" and password="'.$password.'" AND active=1  LIMIT 1';
	            }
	            else
	            {
	                $sql = 'SELECT id FROM users WHERE (username="'.$username.'" OR email="'.$username.'") and password="'.$password.'" AND active=1  LIMIT 1';
	            }
	        }
	        else if (!empty($username))
	        {
	            if (is_numeric($username))
	            {
    	            $sql = 'SELECT id FROM users WHERE TRIM(IFNULL(phone_no,\'\')) <> \'\' AND  phone_no="'.$username.'"  AND active=1  LIMIT 1';
	            }
	            else
	            {
		            $sql = 'SELECT id FROM users WHERE (username="'.$username.'" OR (email="'.$username.'" AND  TRIM(IFNULL(email,\'\')) <> \'\')) LIMIT 1';
	            }

	       }
	       if (!empty($sql))
	       {
     	       $res = $db2->query($sql);
    	       if($db2->num_rows($res) > 0)
    	       {
    	           $obj = $db2->fetch_object($res);
    	           return intval($obj->id);
    	       }
	       }
	       return 0;
	    }
	
	    function getUserDetailsOld($userid)
	    {
	       global $db2, $C;

	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
	        $sql = "Select users.id, email, username, phone_no, fullname, language, timezone, gender, birthdate, active, avatar as profile_image, access_token from users INNER JOIN oauth_access_token ON `users`.id = `oauth_access_token`.user_id where users.id=".$userid;
	        
	        $userdata = array();
			
			$records = $db2->query($sql, FALSE);
		    
			
			if($db2->num_rows($records) > 0)
    	    {
    	           $obj = $db2->fetch_object($records);
    	           return $obj;
    	    }
			
			return $admins;
        }
         function getUserDetails($userid, $who=0)
	    {
	       global $db2, $C;

	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
	        $sql = "Select 
	        users.id,
	        email,
	        username,
	        phone_no,
	        fullname,
	        language,
	        timezone,
	        gender,
	        birthdate,
	        active,
	        avatar as profile_image,
	        access_token,
	       100 AS `followers`,
           100 AS `following`,
           num_favourites as `like`
         from users
	        INNER JOIN oauth_access_token ON `users`.id = `oauth_access_token`.user_id
	        where users.id=".$userid;
	        /*Temp Code*/
	     /*
	        $sql->execute(); // Execute the statement.
            $result = $sql->get_result(); // Binds the last executed statement as a result.
            $result->getUserFollowers($userid);
            $result->getUserFollowing($userid);
            $result->getUserLikes($userid);
                
            return  json_encode(($result->fetch_assoc())); // Parse to JSON and print.
        */
	        /*Temp code close*/
	       /**/
	       $res =  $db2->query($sql);
 			$array = $res->fetch_all(MYSQLI_ASSOC);

          
			$returnarray = array();
 			
			foreach ($array as $i =>$array_expression)
            {
                $array[$i]["followers"] = $this->getUserFollowers($userid);
                $array[$i]["following"] = $this->getUserFollowing($userid);
                $array[$i]["is_liked"] = $this->getUserLikes($userid, $who);
                $array[$i]["share_count"] = $this->getProfileShareCount($userid);
                
                $returnarray[] = $array[$i];
            }
			
			return $returnarray;
			/**/
        }
         function getUserProfileDetails($userid)
	    {
	       global $db2, $C;

	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
	        $sql = "Select 
	        users.id,
	        email,
	        username,
	        phone_no,
	        fullname,
	        language,
	        timezone,
	        gender,
	        birthdate,
	        active,
	        about_me,
	        birthdate,
	        reg_date,
	        is_online,
	        active,
	        if( (cover is null), '', cover) AS cover_image,
			if( (avatar is null), '', avatar) AS profile_image,
	        access_token,
	       100 AS `followers`,
           100 AS `following`,
           num_favourites as `like`
         from users
	        INNER JOIN oauth_access_token ON `users`.id = `oauth_access_token`.user_id
	        where users.id=".$userid;
	        /*Temp Code*/
	     /*
	        $sql->execute(); // Execute the statement.
            $result = $sql->get_result(); // Binds the last executed statement as a result.
            $result->getUserFollowers($userid);
            $result->getUserFollowing($userid);
            $result->getUserLikes($userid);
                
            return  json_encode(($result->fetch_assoc())); // Parse to JSON and print.
        */
	        /*Temp code close*/
	       /**/
	       $res =  $db2->query($sql);
 			$array = $res->fetch_all(MYSQLI_ASSOC);

          
			$returnarray = array();
            $image_url =  $this->profileImageUrl();
			foreach ($array as $i =>$array_expression)
            {
                $array[$i]["followers"] = $this->getUserFollowers($userid);
                $array[$i]["following"] = $this->getUserFollowing($userid);
                // $array[$i]["like"] = $this->getUserLikes($userid);
               // $array[$i]["profile_image"] = $this->profileImageUrl($userid).$array[$i]["profile_image"];
                if($array[$i]["profile_image"]) {
                    $array[$i]["profile_image"] = $image_url.$array[$i]["profile_image"];
                }
                if($array[$i]["cover_image"]) {
                    $array[$i]["cover_image"] = $image_url.$array[$i]["cover_image"];
                }
                $returnarray[] = $array[$i];
            }
			
			return $returnarray;
			/**/
        }

	    public function getUserLikes($userid, $who=0) {	     
            global $db2;
            $query = $db2->query('SELECT id FROM  user_favourites WHERE who="' . $who . '" AND whom="' . $userid . '" limit 1', FALSE);

		    if ($query->num_rows > 0) {
                return 1;
            }
             return 0;
        }

        public function getProfileShareCount($userid) {	     
            global $db2;

			$r      = $db2->query('select count(id) as sharecount  FROM profile_share WHERE  whom="' . $userid . '"', FALSE);
			$result = $db2->fetch_object($r);
			return $result->sharecount;
        }

	   public function getUserFollowing($userid)
	  { 
	      global $db2;
        	$query = 'SELECT Count(id) as cnt from users_followed where who='.$userid;
 	       $res = $db2->query($query);
	       if($db2->num_rows($res) > 0)
	       {
	           $obj = $db2->fetch_object($res);
	           return intval($obj->cnt);
	       }
	       return 0;
	  }
	   public function getUserFollowers($userid)
	  {	      global $db2;
        	$query = 'SELECT Count(id) as cnt from users_followed where whom='.$userid;
 	       $res = $db2->query($query);
	       if($db2->num_rows($res) > 0)
	       {
	           $obj = $db2->fetch_object($res);
	           return intval($obj->cnt);
	       }
	       return 0;
	  }
	    function signUpUser($fullname,$email,$phone,$username,$password,$dob,$gender,$location)
	    {
	   	    global $db2, $C;

	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
 	            $tmplang	= $db2->fetch_field('SELECT value FROM settings WHERE word="LANGUAGE" LIMIT 1');
 	            
				$tmpzone	= $db2->fetch_field('SELECT value FROM settings WHERE word="DEF_TIMEZONE" LIMIT 1');
				
				$referdby 	= isset($_POST['referdby'])?(trim($_POST['referdby'])):'';
            	$type 	    = isset($_POST['type_person'])?(trim($_POST['type_person'])):'';

				$tmppass	=  $password;
				$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
				$lastlogin_date = time();
				$is_fb_used = (isset($_POST['fb_user_id'])? 'facebook_uid="'.$db2->e($_POST['fb_user_id']).'", ' : '');
				$is_tw_used = (isset($_POST['tw_user_id'])? 'twitter_uid="'.$db2->e($_POST['tw_user_id']).'", ' : '');

 				$db2->query('INSERT INTO users SET '.$is_fb_used.$is_tw_used.' email="'.$db2->e($email).'", username="'.$db2->e($username).'",referdby="'.$db2->e($referdby).'", refer_type="'.$db2->e($type).'", password="'.$db2->e($tmppass).'",phone_no="'.$db2->e($phone).'", fullname="'.$db2->e($fullname).'", language="'.$tmplang.'", timezone="'.$tmpzone.'", reg_date="'.$lastlogin_date.'", reg_ip="'.$lastlogin_ip.'", lastlogin_date="'.$lastlogin_date.'", lastlogin_ip="'.$lastlogin_ip.'", gender="'.$gender.'",  location="'.$location.'", birthdate="'.$dob.'", active=1');	
 			
				$user_id	= (int) $db2->insert_id();
    		return $user_id;
	    }
	
	function editUser($userid,$fullname,$email,$phone,$username,$password,$dob,$gender,$location,$about_me)
	    {
	   	    global $db2, $C;
 	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
 	            $tmplang	= $db2->fetch_field('SELECT value FROM settings WHERE word="LANGUAGE" LIMIT 1');
 	            
				$tmpzone	= $db2->fetch_field('SELECT value FROM settings WHERE word="DEF_TIMEZONE" LIMIT 1');
				
				$referdby 	= isset($_POST['referdby'])?(trim($_POST['referdby'])):'';
            	$type 	    = isset($_POST['type_person'])?(trim($_POST['type_person'])):'';

				$tmppass	=  $password;
				$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
				$lastlogin_date = time();
				$is_fb_used = (isset($_POST['fb_user_id'])? 'facebook_uid="'.$db2->e($_POST['fb_user_id']).'", ' : '');
				$is_tw_used = (isset($_POST['tw_user_id'])? 'twitter_uid="'.$db2->e($_POST['tw_user_id']).'", ' : '');
                
                $sql ='UPDATE users SET
 				email="'.$db2->e($email).'",
 				username="'.$db2->e($username).'",
 				password="'.$db2->e($tmppass).'",
 				phone_no="'.$db2->e($phone).'", 
 				fullname="'.$db2->e($fullname).'", 
 				lastlogin_date="'.$lastlogin_date.'", 
 				lastlogin_ip="'.$lastlogin_ip.'", 
 				gender="'.$gender.'",  
 				about_me="'.$about_me.'",  
 				birthdate="'.$dob.'", active=1 
 				Where ID='.$userid;
 				
 				$db2->query($sql);
 				
    		return $userid;
	    }
		function editUserEmail($userid,$email)
	    {
	   	    global $db2, $C;
 	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
				$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
				$lastlogin_date = time();
				
                $sql ='UPDATE users SET
 				email="'.$db2->e($email).'",
 				lastlogin_date="'.$lastlogin_date.'", 
 				lastlogin_ip="'.$lastlogin_ip.'" 
 				Where ID='.$userid;

 				$db2->query($sql);
 				
    		return $userid;
	    }
		function editUserUserName($userid,$username)
	    {
	   	    global $db2, $C;
 	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
				$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
				$lastlogin_date = time();
				
                $sql ='UPDATE users SET
 				username="'.$db2->e($username).'",
 				lastlogin_date="'.$lastlogin_date.'", 
 				lastlogin_ip="'.$lastlogin_ip.'" 
 				Where ID='.$userid;

 				$db2->query($sql);
 				
    		return $userid;
	    }
	function editUserPhone($userid,$phone)
	    {
	   	    global $db2, $C;
 	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
				$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
				$lastlogin_date = time();
				
                $sql ='UPDATE users SET
 				phone_no="'.$db2->e($phone).'",
 				lastlogin_date="'.$lastlogin_date.'", 
 				lastlogin_ip="'.$lastlogin_ip.'" 
 				Where ID='.$userid;

 				$db2->query($sql);
 				
    		return $userid;
	    }
		function editUserPassword($userid,$password)
	    {
	   	    global $db2, $C;
 	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
				$lastlogin_ip = ip2long($_SERVER['REMOTE_ADDR']);
				$lastlogin_date = time();
				
                $sql ='UPDATE users SET
 				password="'.$db2->e($password).'",
 				lastlogin_date="'.$lastlogin_date.'", 
 				lastlogin_ip="'.$lastlogin_ip.'" 
 				Where ID='.$userid;

 				$db2->query($sql);
 				
    		return $userid;
	    }
	    public function getToken($userid)
	    {
 		    global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
    	    $oauth_access_token= $this->generate_request_token();
 
  	       //echo 'SELECT id FROM oauth_access_token WHERE user_id="'.$userid.'"  LIMIT 1';
	        
	        $res = $db2->query('SELECT oauth_access_token FROM oauth_access_token WHERE user_id='.$userid.'  LIMIT 1');

		    if($db2->num_rows($res) > 0)
		    {
			    $obj = $db2->fetch_object($res);
			    return $obj->oauth_access_token;
		    }
		  
	    }
	
	    public function generateToken($userid)
	    {
 		    global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
    	    $oauth_access_token= $this->generate_request_token();
 
  	       //echo 'SELECT id FROM oauth_access_token WHERE user_id="'.$userid.'"  LIMIT 1';
	        
	        $res = $db2->query('SELECT id FROM oauth_access_token WHERE user_id='.$userid.'  LIMIT 1');

		    if($db2->num_rows($res) > 0)
		    {
			    $obj = $db2->fetch_object($res);
			  
			    $res = $db2->query('Update oauth_access_token SET access_token = "'.$oauth_access_token.'" WHERE  id="'.intval($obj->id).'"');
			    
			    return $oauth_access_token;
		    }
		    else
		    {
		         $res = $db2->query('Insert into oauth_access_token (access_token,user_id) VALUES ("'.$oauth_access_token.'", "'.$userid.'")');
                return $oauth_access_token;
    		}
	    }
	
	
	    public function like_post($userid,$postid,$action)
		{
		    
            global $db2, $C;
            $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

            
		    //in case of 1
		    if($action=="1")
            {
     		     $res = $db2->query('SELECT id FROM post_likes WHERE  post_id="'.$postid.'" and  user_id="'.$userid.'"  LIMIT 1');
 
    		     //Check if the user liked the post, 
		        // if yes, ignore 
		        // if no, insert the value
    		    if($db2->num_rows($res) == 0) {
 
    		        $res = $db2->query('Insert into post_likes (post_id,user_id) VALUES ("'.$postid.'", "'.$userid.'")');
                    $this->update_like_count($postid);

                    $post	= new post('public', intval($postid));
                    $notif = new notifier();
                    $notif->set_post($post);
                    $notif->set_notification_obj('post', $postid);	
                    $notif->onLikePost();
    		    }
            }
            else if($action=="0")
            {
    		     $res = $db2->query('SELECT id FROM post_likes WHERE  post_id="'.$postid.'" and  user_id="'.$userid.'"  LIMIT 1');
    		    
    		     //Check if the user linked the post, 
		        // if no, ignore 
		        // if yes, delete the value
    		    if($db2->num_rows($res) > 0)
    		    {
    		        $res = $db2->query('DELETE from post_likes WHERE  post_id="'.$postid.'" and  user_id="'.$userid.'" ');
                    $this->update_like_count($postid, -1);
                }
            }
 
             return $this->get_like_count($postid);
		}

        public function update_like_count($post_id, $count = 1) { 
            global $db2, $C;
            $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

            $query = $db2->query('SELECT posts_detail_id FROM  posts_details WHERE post_id="' . $post_id . '" limit 1', FALSE);    
               
            if($query->num_rows > 0){ 
                $db2->query('UPDATE posts_details SET likes=likes+('.$count.') WHERE post_id="'.$post_id.'"', FALSE); 
            } else if($count == 1) {
                $db2->query('INSERT INTO posts_details SET likes=1, post_id="' . $post_id . '"', FALSE);  
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
    	   }else{
    	       return true;
    	   }
	   
	       
	  }

      
    public function is_profile_like($who, $whom) {
        global $db2, $C;
        $query = 'SELECT id from user_favourites where who='.$who.' AND whom='.$whom;
        $res = $db2->query($query);
        if($db2->num_rows($res) > 0) {
            return 1;
        }
        return 0;
    }

	  public function loadPosts($user_id,$pagenumber,$pagerecordcount)
	  {
	        global $db2, $C;
 	   	    $pagenumber = $pagenumber -1;
	        $pagenumber = ((int)$pagenumber *  (int)$pagerecordcount)  ;
 	   	    //$pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);
 	   	    $pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);
 	   	    
	        $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
	        //if( (posttype = 2), p.title,  SUBSTRING(p.message, 1, 144)) AS title,
            /* (Select count(id) from  profile_share where whom =p.user_id ) AS profilesharecount,
            if( ((Select count(id) from  posts_social_share where post_id =p.id and user_id=b.user_id) > 0), true,  false) AS isshared
            */
			/*$query = 'SELECT 
                        b.id AS pid,
                        p.id AS postid,
                        p.user_id AS postuserid,
                        u.username AS postusername,
                        u.avatar AS postuserimage,
                        if( (u.cover is null), "", u.cover) AS coverimage,
                        if( (p.title is null), "", p.title) AS title,                       
                        p.posttype,
                        p.message AS message,
                        p.likes,
                        p.mentioned,
						GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
                        pa.data,
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
                        if( (pd.views is null), 0, pd.views) AS ViewCount,
                        if( (pd.shares is null), 0, pd.shares) AS sharecount,
                        if( (pd.likes is null), 0, pd.likes) AS likes,
                        if( (pd.comments is null), 0, pd.comments) AS comments,
                        if( (pd.reshares is null), 0, pd.reshares) AS reshares                       
							FROM post_userbox b
							LEFT JOIN posts p ON p.id=b.post_id
							LEFT JOIN users u ON u.id=p.user_id
                            LEFT JOIN posts_details pd ON pd.post_id = p.id
							LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
						WHERE
                        b.user_id='.$user_id.' AND (p.post_level = 0 OR p.post_level is null) AND p.id is not null

                            Group By p.id
                            ORDER BY p.date_lastcomment DESC
                         LIMIT '.$pagenumber.','.$pagerecordcount; */
                         
                       /* 25 feb comment
                       $query = 'SELECT 
                        b.id AS pid,
                        p.id AS postid,
                        p.user_id AS postuserid,
                        u.username AS postusername,
                        u.avatar AS postuserimage,
                        if( (u.cover is null), "", u.cover) AS coverimage,
                        if( (p.title is null), "", p.title) AS title,                       
                        p.posttype,
                        p.message AS message,
                        p.likes,
                        p.mentioned,
                        GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
                        pa.data,
					
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
                         if( (pd.shares is null), 0, pd.shares) AS sharecount,
                        if( (pd.likes is null), 0, pd.likes) AS likes,
                        if( (pd.comments is null), 0, pd.comments) AS comments,
                        if( (pd.reshares is null), 0, pd.reshares) AS reshares 
                                           
							FROM post_userbox b
							LEFT JOIN posts p ON p.id=b.post_id
							LEFT JOIN users u ON u.id=p.user_id
							 LEFT JOIN posts_details pd ON pd.post_id = p.id
							LEFT  JOIN posts_attachments pa ON pa.post_id = p.id
							LEFT OUTER JOIN post_views_list pv ON pv.post_id = p.id
						
						WHERE
                        b.user_id='.$user_id.' AND (p.post_level = 0 OR p.post_level is null) AND p.id is not null

                            Group By p.id
                            ORDER BY p.date_lastcomment DESC
                         LIMIT '.$pagenumber.','.$pagerecordcount;*/
                         
                         
                     /*    //comment on 16 aug
                          $query = 'SELECT 
                        p.id AS postid,
                        p.user_id AS postuserid,
                        u.username AS postusername,
                        u.avatar AS postuserimage,
                        if( (p.title is null), "", p.title) AS title,       
                        pa.video_url as video_url,
                        pa.video_id as video_id,
                        p.posttype,
                        p.message AS message,
                        p.likes,
                        p.mentioned,
                        CONCAT("type=",pa.type,";",pa.data) as attachements,
                        pa.data,
                        pa.video_url as video_url,
                        pa.video_id as video_id,
                        p.comments,
                        p.reshares,
                        p.date,
                        p.post_level,
                        if( (pv.cnt is null), 0, pv.cnt) AS ViewCount,
                        if( (pd.shares is null), 0, pd.shares) AS sharecount,
                        if( (pd.likes is null), 0, pd.likes) AS likes,
                        if( (pd.comments is null), 0, pd.comments) AS comments,
                        if( (pd.reshares is null), 0, pd.reshares) AS reshares 
                                           
							FROM post_userbox b
							LEFT JOIN posts p ON p.id=b.post_id
							LEFT JOIN users u ON u.id=p.user_id
							 LEFT JOIN posts_details pd ON pd.post_id = p.id
							LEFT  JOIN posts_attachments pa ON pa.post_id = p.id
							LEFT OUTER JOIN post_views_list pv ON pv.post_id = p.id
						
						WHERE
                        b.user_id='.$user_id.' AND (p.post_level = 0 OR p.post_level is null) AND p.id is not null
                        
                        Group By p.id
                        ORDER BY p.date_lastcomment DESC
                      
                         LIMIT '.$pagenumber.','.$pagerecordcount;
                         
                         */
                         
                         
                         
                          $query = 'SELECT 
                        p.id AS postid,
                        p.user_id AS postuserid,
                        u.username AS postusername,
                        u.avatar AS postuserimage,
                        if( (p.title is null), "", p.title) AS title,       
                        pa.video_url as video_url,
                        pa.video_id as video_id,
                        p.posttype,
                         p.thumb,
                        p.message AS message,
                        p.likes,
                        p.mentioned,
                        CONCAT("type=",pa.type,";",pa.data) as attachements,
                        pa.data,
                        pa.video_url as video_url,
                        pa.video_id as video_id,
                        p.comments,
                        p.reshares,
                        p.date,
                        p.post_level,
                        if( (pv.cnt is null), 0, pv.cnt) AS ViewCount
                        
						FROM post_userbox b
						LEFT JOIN posts p ON p.id=b.post_id
						LEFT JOIN users u ON u.id=p.user_id
						LEFT  JOIN posts_attachments pa ON pa.post_id = p.id
						LEFT OUTER JOIN post_views_list pv ON pv.post_id = p.id
						
						WHERE
                        b.user_id='.$user_id.' AND (p.post_level = 0 OR p.post_level is null) AND p.id is not null
                        
                        Group By p.id
                        ORDER BY p.date_lastcomment DESC
                      
                         LIMIT '.$pagenumber.','.$pagerecordcount;
                         
                          
                         
                         
                         
            $res =  $db2->query($query);
  			$array = $res->fetch_all(MYSQLI_ASSOC);
			$returnarray = array(); 
			
			
			 
 
			foreach ($array as $i =>$array_expression) {
                /* if($array[$i]["posttype"] != 2) {
                    $title = strip_tags($array[$i]["message"]); 
                    $array[$i]["title"] = mb_substr($title, 0, 144,'UTF-8');
                }*/
                $array[$i]["title"] = strip_tags($array[$i]["title"]); 
                if( $array[$i]["title"] == ""){
                    $newtitle = strip_tags($array[$i]["message"]);
                     $array[$i]["title"] = mb_substr($newtitle, 0, 144,'UTF-8');
                    
                }

                if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                    $url = "https://";
                else  
                    $url = "http://";   
            
                $url.= $_SERVER['HTTP_HOST'];  
                    $array[$i]["profile_base_url"] =       $C->SITE_URL."storage/avatars/thumbs1/";
                    $array[$i]["attachment_base_url"] =       $C->SITE_URL."storage/attachments/1/";
			
                if(empty($array[$i]["attachements"])) {
                    $array[$i]["attachements"] = '';
                }

                $array[$i]["attach_data"] = NULL;
                if(!empty($array[$i]["data"])) {
                    $att_data = (array)unserialize($array[$i]["data"]);                    
                    $file_original = isset($att_data['file_original']) ? $att_data['file_original'] : '';
                    if(!empty($file_original)) {
                        $array[$i]["attach_data"]["file_original"] = $file_original;
                         $imgurl = $C->SITE_URL."storage/attachments/1/".$file_original;
                          $array[$i]["document_url"] = $imgurl;
                    }
                }
                unset($array[$i]["data"]);

                if(empty($array[$i]["coverimage"])) {
                    $array[$i]["coverimage"] = '';
                }

                $array[$i]["is_profile_liked"] = $this->is_profile_like($user_id, $array[$i]["postuserid"]);

                //$array[$i]["likes"] = $this->getLikes($array[$i]["postid"]);
                //$array[$i]["comments"] = $this->getCommentsCount($array[$i]["postid"]);
                                 
                $query = 'SELECT * FROM post_likes WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
                $res =  $db2->query($query);
                $obj = $db2->fetch_object($res);
                
                if(empty($obj->id)){
                    $array[$i]["isliked"] = "0";  
                } else {
                    $array[$i]["isliked"] = "1";  
                }
					
					
					
               	$query = 'SELECT * FROM posts_details WHERE  post_id = '.$array[$i]["postid"].' LIMIT 1';
                $res =  $db2->query($query);
                $obj_pd = $db2->fetch_object($res);

                if(empty($res)){
                    $array[$i]["sharecount"] = 0;   
                    $array[$i]["likes"] = 0;   
                    $array[$i]["comments"] =0;   
                    $array[$i]["reshares"] = 0;     
                } else {
                   
                    $array[$i]["sharecount"] = $obj_pd->shares;   
                    $array[$i]["likes"] = $obj_pd->likes; 
                    $array[$i]["comments"] =$obj_pd->comments;  
                    $array[$i]["reshares"] = $obj_pd->reshares;  
                }
					
					
		 
		
					
					
					
					
					
    			$query1 = 'SELECT * FROM post_reshares WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
                $res1 =  $db2->query($query1);
                $obj1 = $db2->fetch_object($res1);
						  
                if(empty($obj1->id)){
                    $array[$i]["isbuzzed"] = "0";  
                } else {
                    $array[$i]["isbuzzed"] = "1";  
                }	
								
                $query1 = 'SELECT * FROM profile_share WHERE  who="'.$user_id.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
                $res1 =  $db2->query($query1);
                $obj1 = $db2->fetch_object($res1);
						  
                if(empty($obj1->id)){
                    $array[$i]["isshared"] = "0";  
                } else {
                    $array[$i]["isshared"] = "1";  
                }			
				
	        	$query3 = 'SELECT * FROM users_followed WHERE  who="'.$user_id.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
                $res3 =  $db2->query($query3);
                $obj3 = $db2->fetch_object($res3);
				
		        if(empty($obj3->id)){
                    $array[$i]["isfollwed"] = "0";  
                } else {
                    $array[$i]["isfollwed"] = "1";  
                }		
				
                //$array[$i]["likes"]      = $this->get_like_count($array[$i]["postid"]);
                //$array[$i]["reshares"]   = $this->new_reshare_count($array[$i]["postid"]);
				 				 
                if ($array[$i]["posttype"] == 4) {
                    $query = 'SELECT * FROM event_posts WHERE  post_id="'.$array[$i]["postid"].'" LIMIT 1';
                    $res =  $db2->query($query);
                    $obj = $db2->fetch_object($res);
                    $event_id = $obj->event_id;
            
            
                    $query = 'select event_status  FROM post_userbox WHERE user_id="'.$user_id.'" AND post_id="'.$array[$i]["postid"].'"  ';   
                    $res =  $db2->query($query);
                    $userResp = $res->fetch_all(MYSQLI_ASSOC);
                    
                    
                    $query = 'SELECT id as event_id,admin_id as user_id,address,location,event_name,event_description,start_date,start_time,end_date,end_time,status FROM events WHERE  id="'.$event_id.'" LIMIT 1';
                    $res =  $db2->query($query);
                    $array[$i]["event_detail"] = $db2->fetch_object($res); 
                    
                    $query = 'SELECT * FROM posts_attachments WHERE  type="'.'image'.'"  AND post_id = '.$array[$i]["postid"].' ';
                    $res =  $db2->query($query);
                    $eventAttach = $res->fetch_all(MYSQLI_ASSOC);
                    $videoquery = 'SELECT * FROM posts_attachments WHERE  type="'.'file'.'"  AND post_id = '.$array[$i]["postid"].' ';
                    $videores =  $db2->query($videoquery);
                    $videoattachment = $videores->fetch_all(MYSQLI_ASSOC);
                
                    $r = $db2->query('select count(id) as joincount  FROM post_userbox WHERE post_id="'.$array[$i]["postid"].'" AND event_status="'.'1'.'"', FALSE);
                    $result = $db2->fetch_object($r);
                    $sharecount = $result->joincount; 
                    $array[$i]["event_detail"]->joincount = $sharecount;
                    
                    $array[$i]["event_detail"]->event_status = $userResp[0]['event_status'];
                    $array[$i]["event_detail"]->event_attachment = $eventAttach;
                    if(!empty($videoattachment)){
                        $videos = $videoattachment[0]['data'];
                        $unserialize = unserialize($videos);
                        $videoattachmentdata = $unserialize->file_original;
                        $array[$i]['event_detail']->video_attachment = $videoattachmentdata;
                    }
                }
                                
                if ($array[$i]["posttype"] == 5) {
                    
                    $query = 'SELECT * FROM polls WHERE  posts_id="'.$array[$i]["postid"].'" LIMIT 1';
                    $res =  $db2->query($query);
                    $obj = $db2->fetch_object($res);
                    $array[$i]["poll_question"] = $obj->poll_question;
                
                    $r = $db2->query('select count(id) as joincount  FROM post_poll_votes WHERE POLL_ID="' . $obj->poll_id . '"', FALSE);
                    $result = $db2->fetch_object($r);
                    $sharecount = $result->joincount;  
                    
                    $array[$i]['total_vote'] = $sharecount;
                    $array[$i]['poll_id'] = $obj->poll_id;
                                
                    $r = $db2->query('SELECT * FROM  post_poll_votes WHERE POLL_ID="' . $obj->poll_id . '" AND VOTER_USER_ID="' . $user_id . '" LIMIT 1');
                    $result = $db2->fetch_object($r);
                    $ANSWER_ID = $result->ANSWER_ID;  
	          
                    if($ANSWER_ID == null){
                        $ANSWER_ID = "0";
                    }
	           
                    $array[$i]['is_vote'] = $ANSWER_ID = $ANSWER_ID;  

                    $query = 'SELECT * FROM polls_answers WHERE  poll_id="'.$obj->poll_id.'"';
                    $res =  $db2->query($query);
                    // $array[$i]["poll_option"] = $res->fetch_all(MYSQLI_ASSOC);
                    $answer =  $res->fetch_all(MYSQLI_ASSOC);               
                      
                    $poll_value = array();
                    foreach($answer as $value) {                
                        $r = $db2->query('select count(id) as pollvote  FROM post_poll_votes WHERE  ANSWER_ID="' . $value['poll_answer_id'] . '"', FALSE);
                        $result = $db2->fetch_object($r);
                        $result->pollvote;
                        $value['votes'] =  $result->pollvote;
                        $poll_value[] = $value;                    
                    }                       
                    $array[$i]["poll_option"] = $poll_value;               
                
                    $query = 'SELECT data FROM posts_attachments WHERE  type="'.'image'.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1 ';
                    $res =  $db2->query($query);
                    $array[$i]["poll_attachment"] = $res->fetch_all(MYSQLI_ASSOC);                
                }
                $returnarray[] = $array[$i];
            }			
			return $returnarray;
	  }
	  
	  
	  
	   public function loadVideoNews_guest($pagenumber,$pagerecordcount)
	  {
	       global $db2, $C;
 	   	    $pagenumber = $pagenumber -1;
	        $pagenumber = ((int)$pagenumber *  (int)$pagerecordcount)  ;
 	   	    //$pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);
 	   	    $pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);
 	   	    
	        $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
 			$query = 'SELECT 
                        b.id AS pid,
                        p.id AS postid,
                        p.user_id AS postuserid,
                        u.username AS postusername,
                        u.avatar AS postuserimage,
                        if( (u.cover is null), "", u.cover) AS coverimage,
                        if( (p.title is null), "", p.title) AS title,
                        p.posttype,
                        p.message AS message,
                        p.likes,
                        p.mentioned,
						GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
                        pa.data,
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
                        if( (pd.views is null), 0, pd.views) AS ViewCount,
                        if( (pd.shares is null), 0, pd.shares) AS sharecount,
                        if( (pd.likes is null), 0, pd.likes) AS likes,
                        if( (pd.comments is null), 0, pd.comments) AS comments,
                        if( (pd.reshares is null), 0, pd.reshares) AS reshares						
							FROM post_userbox b
							LEFT JOIN posts p ON p.id=b.post_id
							LEFT JOIN users u ON u.id=p.user_id
                            LEFT JOIN posts_details pd ON pd.post_id = p.id
							LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
						WHERE
                         (p.post_level = 0 OR p.post_level is null) AND p.posttype = 3 AND p.id is not null

                            Group By p.id
                            ORDER BY p.date_lastcomment DESC
                         LIMIT '.$pagenumber.','.$pagerecordcount;
                         
                         

                         
                         
                         
            $res =  $db2->query($query);
  			$array = $res->fetch_all(MYSQLI_ASSOC);
			$returnarray = array(); 
			
			
			 
			foreach ($array as $i =>$array_expression) {
                 /* if($array[$i]["posttype"] != 2) {
                    $title = strip_tags($array[$i]["message"]); 
                    $array[$i]["title"] = mb_substr($title, 0, 144,'UTF-8');
                }*/
                $array[$i]["title"] = strip_tags($array[$i]["title"]); 
                if( $array[$i]["title"] == ""){
                    $newtitle = strip_tags($array[$i]["message"]);
                     $array[$i]["title"] = mb_substr($newtitle, 0, 144,'UTF-8');
                    
                }

                if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                    $url = "https://";
                else  
                    $url = "http://";   
            
                $url.= $_SERVER['HTTP_HOST'];  
                    $array[$i]["profile_base_url"] =       $C->SITE_URL."storage/avatars/thumbs1/";
                    $array[$i]["attachment_base_url"] =       $C->SITE_URL."storage/attachments/1/";
			
                if(empty($array[$i]["attachements"])) {
                    $array[$i]["attachements"] = '';
                }

                $array[$i]["attach_data"] = NULL;
                if(!empty($array[$i]["data"])) {
                    $att_data = (array)unserialize($array[$i]["data"]);                    
                    $file_original = isset($att_data['file_original']) ? $att_data['file_original'] : '';
                    if(!empty($file_original)) {
                        $array[$i]["attach_data"]["file_original"] = $file_original;
                         $imgurl = $C->SITE_URL."storage/attachments/1/".$file_original;
                          $array[$i]["document_url"] = $imgurl;
                    }
                }
                unset($array[$i]["data"]);

                if(empty($array[$i]["coverimage"])) {
                    $array[$i]["coverimage"] = '';
                }

                $array[$i]["is_profile_liked"] = "0";
 
                
                 $array[$i]["isliked"] = "0";
	 			
					
					  $array[$i]["isbuzzed"] = "0";  
					  
		 	
				
				
				 $array[$i]["isshared"] = "0";  
	 	
                
                 $array[$i]["isfollwed"] ="0";
				
               
                $returnarray[] = $array[$i];
            }		
            
            
        //    print_r($returnarray); die('xxxxxx');
            
			return $returnarray;
	  }
/// for video news only
	  public function loadVideoNews($user_id,$pagenumber,$pagerecordcount)
	  {
	        global $db2, $C;
 	   	    $pagenumber = $pagenumber -1;
	        $pagenumber = ((int)$pagenumber *  (int)$pagerecordcount)  ;
 	   	    //$pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);
 	   	    $pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);
 	   	    
	        $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
	        
			$query = 'SELECT 
                        b.id AS pid,
                        p.id AS postid,
                        p.user_id AS postuserid,
                        u.username AS postusername,
                        u.avatar AS postuserimage,
                        if( (u.cover is null), "", u.cover) AS coverimage,
                        if( (p.title is null), "", p.title) AS title,
                        p.posttype,
                        p.message AS message,
                        p.likes,
                        p.mentioned,
						GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
                        pa.data,
                        pa.video_url as video_url,
                        pa.video_id as video_id,
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
                        if( (pd.views is null), 0, pd.views) AS ViewCount,
                        if( (pd.shares is null), 0, pd.shares) AS sharecount,
                        if( (pd.likes is null), 0, pd.likes) AS likes,
                        if( (pd.comments is null), 0, pd.comments) AS comments,
                        if( (pd.reshares is null), 0, pd.reshares) AS reshares						
							FROM post_userbox b
							LEFT JOIN posts p ON p.id=b.post_id
							LEFT JOIN users u ON u.id=p.user_id
                            LEFT JOIN posts_details pd ON pd.post_id = p.id
							LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
						WHERE
                        b.user_id='.$user_id.' AND (p.post_level = 0 OR p.post_level is null) AND p.posttype = 3 AND p.id is not null

                            Group By p.id
                            ORDER BY p.date_lastcomment DESC
                         LIMIT '.$pagenumber.','.$pagerecordcount;
            $res =  $db2->query($query);
  			$array = $res->fetch_all(MYSQLI_ASSOC);
			$returnarray = array();
			
			foreach ($array as $i =>$array_expression)
            {
                $array[$i]["title"] = strip_tags($array[$i]["title"]);

                if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                    $url = "https://";
                else  
                    $url = "http://";   
        
                $url.= $_SERVER['HTTP_HOST'];  
                    $array[$i]["profile_base_url"] =      $C->SITE_URL."storage/avatars/thumbs1/";
                    $array[$i]["attachment_base_url"] =       $C->SITE_URL."storage/attachments/1/";
			
                if(empty($array[$i]["attachements"])) {
                    $array[$i]["attachements"] = '';
                }

                $array[$i]["attach_data"] = NULL;
                if(!empty($array[$i]["data"])) {
                    $att_data = (array)unserialize($array[$i]["data"]);                    
                    $file_original = isset($att_data['file_original']) ? $att_data['file_original'] : '';
                    if(!empty($file_original)) {
                        $array[$i]["attach_data"]["file_original"] = $file_original;
                          $array[$i]["document_url"] =  $C->SITE_URL."storage/attachments/1/".$file_original;
                    }
                }
                unset($array[$i]["data"]);

                if(empty($array[$i]["coverimage"])) {
                    $array[$i]["coverimage"] = '';
                }
                $array[$i]["is_profile_liked"] = $this->is_profile_like($user_id, $array[$i]["postuserid"]);
                //$array[$i]["likes"] = $this->getLikes($array[$i]["postid"]);
                //$array[$i]["comments"] = $this->getCommentsCount($array[$i]["postid"]);
                                                 
                $query = 'SELECT * FROM post_likes WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
                $res =  $db2->query($query);
                $obj = $db2->fetch_object($res);                

                if(empty($obj->id)){
                    $array[$i]["isliked"] = "0";  
                } else {
                    $array[$i]["isliked"] = "1";  
                }
					
    			$query1 = 'SELECT * FROM post_reshares WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
                $res1 =  $db2->query($query1);
                $obj1 = $db2->fetch_object($res1);
						  
                if(empty($obj1->id)){
                    $array[$i]["isbuzzed"] = "0";  
                } else {
                    $array[$i]["isbuzzed"] = "1";  
                }	
								
                $query1 = 'SELECT * FROM profile_share WHERE  who="'.$user_id.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
                $res1 =  $db2->query($query1);
                $obj1 = $db2->fetch_object($res1);
						  
                if(empty($obj1->id)){
                    $array[$i]["isshared"] = "0";  
                } else {
                    $array[$i]["isshared"] = "1";  
                }				
				
	        	$query3 = 'SELECT * FROM users_followed WHERE  who="'.$user_id.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
                $res3 =  $db2->query($query3);
                $obj3 = $db2->fetch_object($res3);
				
		        if(empty($obj3->id)){
                    $array[$i]["isfollwed"] = "0";  
                } else {
                    $array[$i]["isfollwed"] = "1";  
                }		
				
				// $array[$i]["likes"]      =$this->get_like_count($array[$i]["postid"]);
				// $array[$i]["reshares"]       =$this->new_reshare_count($array[$i]["postid"]);
				 
				 
                if ($array[$i]["posttype"] == 4) {
                    $query = 'SELECT * FROM event_posts WHERE  post_id="'.$array[$i]["postid"].'" LIMIT 1';
                    $res =  $db2->query($query);
                    $obj = $db2->fetch_object($res);
                    $event_id = $obj->event_id;
                        
                    $query = 'select event_status  FROM post_userbox WHERE user_id="'.$user_id.'" AND post_id="'.$array[$i]["postid"].'"  ';   
                    $res =  $db2->query($query);
                    $userResp = $res->fetch_all(MYSQLI_ASSOC);
                                        
                    $query = 'SELECT id as event_id,admin_id as user_id,address,location,event_name,start_date,start_time,end_date,end_time,status FROM events WHERE  id="'.$event_id.'" LIMIT 1';
                    $res =  $db2->query($query);
                    $array[$i]["event_detail"] = $db2->fetch_object($res); 
                    
                    $query = 'SELECT * FROM posts_attachments WHERE  type="'.'image'.'"  AND post_id = '.$array[$i]["postid"].' ';
                    $res =  $db2->query($query);
                    $eventAttach = $res->fetch_all(MYSQLI_ASSOC);
                    $videoquery = 'SELECT * FROM posts_attachments WHERE  type="'.'file'.'"  AND post_id = '.$array[$i]["postid"].' ';
                    $videores =  $db2->query($videoquery);
                    $videoattachment = $videores->fetch_all(MYSQLI_ASSOC);
                               
                
                    $r = $db2->query('select count(id) as joincount  FROM post_userbox WHERE post_id="'.$array[$i]["postid"].'" AND event_status="'.'1'.'"', FALSE);
                    $result = $db2->fetch_object($r);
                    $sharecount = $result->joincount; 
                    $array[$i]["event_detail"]->joincount = $sharecount;
                
                    $array[$i]["event_detail"]->event_status = $userResp[0]['event_status'];
                    $array[$i]["event_detail"]->event_attachment = $eventAttach;
                    if(!empty($videoattachment)){
                        $videos = $videoattachment[0]['data'];
                        $unserialize = unserialize($videos);
                        $videoattachmentdata = $unserialize->file_original;
                        $array[$i]['event_detail']->video_attachment = $videoattachmentdata;
                    }
                }
                
                
                if ($array[$i]["posttype"] == 5) {
                    
                    $query = 'SELECT * FROM polls WHERE  posts_id="'.$array[$i]["postid"].'" LIMIT 1';
                    $res =  $db2->query($query);
                    $obj = $db2->fetch_object($res);
                    $array[$i]["poll_question"] = $obj->poll_question;
                
                    $r = $db2->query('select count(id) as joincount  FROM post_poll_votes WHERE POLL_ID="' . $obj->poll_id . '"', FALSE);
                    $result = $db2->fetch_object($r);
                    $sharecount = $result->joincount;  
                    
                    $array[$i]['total_vote'] = $sharecount;
                    $array[$i]['poll_id'] = $obj->poll_id;
                                
                    $r = $db2->query('SELECT * FROM  post_poll_votes WHERE POLL_ID="' . $obj->poll_id . '" AND VOTER_USER_ID="' . $user_id . '" LIMIT 1');
                    $result = $db2->fetch_object($r);
                    $ANSWER_ID = $result->ANSWER_ID;  
                    
                    if($ANSWER_ID == null){
                        $ANSWER_ID = "0";
                    }
	           
                    $array[$i]['is_vote'] = $ANSWER_ID = $ANSWER_ID;  

                    $query = 'SELECT * FROM polls_answers WHERE  poll_id="'.$obj->poll_id.'"';
                    $res =  $db2->query($query);
                    // $array[$i]["poll_option"] = $res->fetch_all(MYSQLI_ASSOC);
                    $answer =  $res->fetch_all(MYSQLI_ASSOC);
                                      
                    $poll_value = array();

                    foreach($answer as $value){                
                        $r = $db2->query('select count(id) as pollvote  FROM post_poll_votes WHERE  ANSWER_ID="' . $value['poll_answer_id'] . '"', FALSE);
                        $result = $db2->fetch_object($r);
                        $result->pollvote;
                        $value['votes'] =  $result->pollvote;
                        $poll_value[] = $value;                    
                    }
                       
                    $array[$i]["poll_option"] = $poll_value;
                
                
                    $query = 'SELECT data FROM posts_attachments WHERE  type="'.'image'.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1 ';
                    $res =  $db2->query($query);
                    $array[$i]["poll_attachment"] = $res->fetch_all(MYSQLI_ASSOC);                
                }			 
                
                $returnarray[] = $array[$i];
            }			
			return $returnarray;
	    }

	   public function getLikes($postid)
	  {
	       global $db2, $C;
 	   	    
			$query = 'SELECT Count(post_id) as cnt from post_likes where post_id='.$postid;
 	       if (!empty($query))
	       {
     	       $res = $db2->query($query);
    	       if($db2->num_rows($res) > 0)
    	       {
    	           $obj = $db2->fetch_object($res);
    	           return intval($obj->cnt);
    	       }
	       }
	       return 0;
	  }
	  public function getAttachements($postid)
	  {
	       global $db2, $C;
 	   	    
	       // $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
	        
			$query = 'SELECT type, data, if(comment is null,\'\',comment) as comment FROM `posts_attachments`  where post_id='.$postid;
      	    $res = $db2->query($query);
      	       
      	    $array = $res->fetch_all(MYSQLI_ASSOC);
			$returnarray = array();
 			
			foreach ($array as $i =>$array_expression)
            {
                $array[$i]["data"] = $this->splitAttachements($array[$i]["data"]);
                $returnarray[] = $array[$i];
			}
			return $returnarray;
 	  }
 	  public function getAttachementsHTML($postid)
	  {
	       global $db2, $C;
 	   	    
	       // $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
	        
			$query = 'SELECT type, data, if(comment is null,\'\',comment) as comment FROM `posts_attachments`  where post_id='.$postid;
      	    $res = $db2->query($query);
      	       
      	    $array = $res->fetch_all(MYSQLI_ASSOC);
			$returnstr = "";
 			
			foreach ($array as $i =>$array_expression)
            {
                $returnstr = $returnstr."<BR>".$this->splitAttachementsHTML($array[$i]["data"]);
			}

			return $returnstr;
 	  }
 	  public function splitAttachementsHTML($data)
	  {
	     $file_originalSTART = strpos($data, 's:25:"', 0)+6;
	     $file_originalEND  = strpos($data, ';', $file_originalSTART)-1;
         $file_original = substr($data,$file_originalSTART,$file_originalEND-$file_originalSTART);
	  
	  	     $file_previewSTART = strpos($data, '"file_preview";s:26:"', 0)+21;
	     $file_previewEND  = strpos($data, ';', $file_previewSTART)-1;
         $file_preview = substr($data,$file_previewSTART,$file_previewEND-$file_previewSTART);
	  
	  	     $file_thumbnailSTART = strpos($data, '"file_thumbnail";s:26:"', 0)+23;
	     $file_thumbnailEND  = strpos($data, ';', $file_thumbnailSTART)-1;
         $file_thumbnail = substr($data,$file_thumbnailSTART,$file_thumbnailEND-$file_thumbnailSTART);
         
	     $file_original."+".$file_preview."+".$file_thumbnail;
	     return "<img alt='Image' src='".$this->imageUrl().$file_preview."' >";
	  }
 	  public function getCommentsCount($postid)
	  {
	        global $db2, $C;
 	   	    
	        //$db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
	        //SELECT * FROM `posts` ORDER BY `posts`.`id` DESC

			$query = 'SELECT Count(id) as cnt from posts_comments where post_id='.$postid;
 	       if (!empty($query))
	       {
     	       $res = $db2->query($query);
    	       if($db2->num_rows($res) > 0)
    	       {
    	           $obj = $db2->fetch_object($res);
    	           return intval($obj->cnt);
    	       }
	       }
	       return 0;
	  }
 	  public function splitAttachements($data)
	  {
	     $file_originalSTART = strpos($data, 's:25:"', 0)+6;
	     $file_originalEND  = strpos($data, ';', $file_originalSTART)-1;
         $file_original = substr($data,$file_originalSTART,$file_originalEND-$file_originalSTART);
	  
	  	     $file_previewSTART = strpos($data, '"file_preview";s:26:"', 0)+21;
	     $file_previewEND  = strpos($data, ';', $file_previewSTART)-1;
         $file_preview = substr($data,$file_previewSTART,$file_previewEND-$file_previewSTART);
	  
	  	     $file_thumbnailSTART = strpos($data, '"file_thumbnail";s:26:"', 0)+23;
	     $file_thumbnailEND  = strpos($data, ';', $file_thumbnailSTART)-1;
         $file_thumbnail = substr($data,$file_thumbnailSTART,$file_thumbnailEND-$file_thumbnailSTART);
         
	     $file_original."+".$file_preview."+".$file_thumbnail;
	     return array("file_original"=>$file_original,"file_preview"=>$file_preview,"file_thumbnail"=>$file_thumbnail);
	  }
	  public function loadPosts1($user_id,$pagenumber,$pagerecordcount)
	  {
	        global $db2, $C;
 	   	    $pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);
 	   	    
 	   	    $pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);
 	   	    
 	   	    //if ($this->getUserFollowing($user_id) > 0)
 	   	    if (1==1)
 	   	    {
        			$query = 'SELECT
                                b.id AS pid,
                                p.id AS postid,
                                p.user_id AS postuserid,
                                u.username AS postusername,
                                u.avatar AS postuserimage,
                                SUBSTRING(p.message, 1, 50) as title,
                                p.message AS message,
                                Count(pl.post_id) as "like",
                                p.mentioned,
                                GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
                                pa.video_url as video_url,
                                pa.video_id as video_id,
                                p.posttags,
                                Count(pc.id) as "comments",
                                Count(pr.id) as "reshares",
                                p.date,
                                p.thumb,
                                p.date_lastedit,
                                p.date_lastcomment,
                                p.group_name,
                                p.parent_id,
                                p.status,
                                p.post_level,
                                p.location,
                                p.thumb,
                                IF(count(uf.id) > 0,true,false) AS `following`,
                                "public" AS `type`
                                FROM post_userbox b
                                LEFT JOIN posts p ON p.id=b.post_id
                                LEFT JOIN users u ON u.id=p.user_id
                                LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
                                LEFT OUTER JOIN post_likes pl ON pl.post_id = pa.post_id
                                LEFT OUTER JOIN posts pc ON pc.parent_id = pa.post_id
                                LEFT OUTER JOIN post_reshares pr ON pr.post_id = pa.post_id
                                LEFT OUTER JOIN users_followed uf ON uf.whom = p.user_id
                                WHERE
                                b.user_id='.$user_id.' AND p.post_level is null AND uf.who = b.user_id  
                                    Group By p.id
                                    ORDER BY p.date_lastcomment DESC
                                 LIMIT '.$pagenumber.','.$pagerecordcount;
                                 //.$C->PAGING_NUM_POSTS;
                               // echo $query; 
 	   	    }
 	   	    else
 	   	    {
 	   	        $query = 'SELECT
                                b.id AS pid,
                                p.id AS postid,
                                p.user_id AS postuserid,
                                u.username AS postusername,
                                u.avatar AS postuserimage,
                                SUBSTRING(p.message, 1, 50) as title,
                                p.message AS message,
                                Count(pl.post_id) as "like",
                                p.mentioned,
                                GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
                                 pa.video_url as video_url,
                                 pa.video_id as video_id,
                                p.posttags,
                                Count(pc.id) as "comments",
                                Count(pr.id) as "reshares",
                                p.date,
                                p.date_lastedit,
                                p.date_lastcomment,
                                p.group_name,
                                p.parent_id,
                                p.status,
                                p.post_level,
                                p.location,
                                p.thumb,
                                IF(count(uf.id) > 0,true,false) AS `following`,
                                "public" AS `type`
                                FROM post_userbox b
                                LEFT JOIN posts p ON p.id=b.post_id
                                LEFT JOIN users u ON u.id=p.user_id
                                LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
                                LEFT OUTER JOIN post_likes pl ON pl.post_id = pa.post_id
                                LEFT OUTER JOIN posts pc ON pc.parent_id = pa.post_id
                                LEFT OUTER JOIN post_reshares pr ON pr.post_id = pa.post_id
                                LEFT OUTER JOIN users_followed uf ON uf.whom = p.user_id
                                WHERE
                                b.user_id='.$user_id.' AND p.post_level is null AND uf.who = b.user_id  
                                    Group By p.id
                                    ORDER BY p.date_lastcomment DESC
                                 LIMIT '.$pagenumber.','.$pagerecordcount;
                                 //.$C->PAGING_NUM_POSTS;
 	   	    }
 	   	    
 	   	    
             $res =  $db2->query($query);

 			return $res->fetch_all(MYSQLI_ASSOC);
 			
 			
	  }
	  public function imageUrl()
	  {
	      if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
	        $url = "https://";
	      else  
            $url = "http://";   
            
         $url.= $_SERVER['HTTP_HOST'];   
    
	    return $C->SITE_URL."storage/attachments/1/";
	      
	  }
	   public function profileImageUrl()
	  {
	      if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
	        $url = "https://";
	      else  
            $url = "http://";   
            
         $url.= $_SERVER['HTTP_HOST'];   
    
        // Append the requested resource location to the URL   
        //$url.= $_SERVER['REQUEST_URI'];    
	    
	    return $C->SITE_URL."storage/avatars/thumbs1/";
	      
	  }
	   public function postBaseUrl()
	  {
	      if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
	        $url = "https://";
	      else  
            $url = "http://";   
            
         $url.= $_SERVER['HTTP_HOST'];   
    
        // Append the requested resource location to the URL   
        //$url.= $_SERVER['REQUEST_URI'];    
	    
	    return $C->SITE_URL."view/post:";
	      
	  }
	  
	  public function refreshToken($userid,$token)
	  {
 	   	    
	  	    global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
 
  	       //echo 'SELECT id FROM oauth_access_token WHERE user_id="'.$userid.'"  LIMIT 1';
	        
	        $res = $db2->query('SELECT id FROM oauth_access_token WHERE user_id='.$userid.' and access_token=\''.$token.'\' LIMIT 1');

		    if($db2->num_rows($res) > 0)
		    {
			    $obj = $db2->fetch_object($res);
			     $oauth_access_token= $this->generate_request_token();

			    $res = $db2->query('Update oauth_access_token SET access_token = "'.$oauth_access_token.'" WHERE  id="'.intval($obj->id).'"');
			    
			    return $oauth_access_token;
		    }
            return null;
	    }
	  public function expiretoken($userid,$token)
	  {
 	   	    
	  	    global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
 
  	       //echo 'SELECT id FROM oauth_access_token WHERE user_id="'.$userid.'"  LIMIT 1';
	        
	        $res = $db2->query('SELECT id FROM oauth_access_token WHERE user_id='.$userid.' and access_token=\''.$token.'\' LIMIT 1');

		    if($db2->num_rows($res) > 0)
		    {
			    $obj = $db2->fetch_object($res);
			     //$oauth_access_token= $this->generate_request_token();

			    $res = $db2->query('insUpdate oauth_access_token SET access_token = "" WHERE  id="'.intval($obj->id).'"');
			    
			    return true;
		    }
            return false;
	    }    
	    
	    
	    		public function user_favourites($who,$whom)
		{
		    
		      global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
	             
	        $db2->query('Insert into user_favourites (who,whom,date) VALUES ('.$who.', '.$whom.','.time().')');
            return 1;
	                         //$db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$followers[$i]["who"].','.$post_id.')');

		   
		}
	    
	    
	    
	    		public function user_unfavourites($who,$whom)
		{
		    
		      global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
	             
    		        $res = $db2->query('DELETE from user_favourites WHERE  who="'.$who.'" and  whom="'.$whom.'" ');
            return 1;
	                         //$db2->query('Insert into post_userbox (user_id,post_id) VALUES ('.$followers[$i]["who"].','.$post_id.')');

		   
		}
		public function myevents_workspace($userId,$pagenumber,$pagerecordcount)
	    {
	        global $db2, $C;
 	   	    $pagenumber = $pagenumber -1;
	        $pagenumber = ((int)$pagenumber *  (int)$pagerecordcount)  ;
 	   	    $pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);
 	   	    $user_id = $userId;
 	   	     $posttype = 4;

 	   	    
	        $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);

			$query = 'SELECT  p.id as postid,p.user_id AS postuserid,
                        u.username AS postusername,
                        u.avatar AS postuserimage,
                        if( (u.cover is null), "", u.cover) AS coverimage,
                        if( (p.title is null), "", p.title) AS title,                       
                        p.posttype,
                        p.message AS message,                        
                        p.likes,
                        p.mentioned,
						pa.data as attachments,
                        pa.data,
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
                        u.username AS commentdetails,
                        "" as category,
                        "public" AS type,
                        if( (pd.views is null), 0, pd.views) AS ViewCount,
                        if( (pd.shares is null), 0, pd.shares) AS sharecount,
                        if( (pd.likes is null), 0, pd.likes) AS likes,
                        if( (pd.comments is null), 0, pd.comments) AS comments,
                        if( (pd.reshares is null), 0, pd.reshares) AS reshares,
                         e.id,
                            e.admin_id,
                            e.address,
                            e.location,
                            e.event_name,
                            e.start_date,
                            e.start_time,
                            e.end_date,
                            e.end_time,
                            e.event_description,
                            e.status
                        FROM   events as e 
                        inner join  event_posts as ep ON ep.event_id = e.id
                        inner join  posts as p ON ep.post_id = p.id	
                        LEFT JOIN users u ON u.id=p.user_id
                        LEFT JOIN posts_details pd ON pd.post_id = p.id
                        LEFT OUTER JOIN posts_attachments pa ON pa.post_id = p.id
                        where e.admin_id="'.$userId.'" AND 
                        ep.edit_status IS NULL   and (p.post_level is null or p.post_level ="0") order by p.date_lastcomment desc,ep.id desc LIMIT '.$pagenumber.','.$pagerecordcount;

            $res =  $db2->query($query);

            $array = $res->fetch_all(MYSQLI_ASSOC);
			$returnarray = array();
			foreach ($array as $i =>$array_expression)
            {
                /* if($array[$i]["posttype"] != 2) {
                    $title = strip_tags($array[$i]["message"]); 
                    $array[$i]["title"] = mb_substr($title, 0, 144,'UTF-8');

                    unset($array[$i]["message"]);
                }*/
                $array[$i]["title"] = strip_tags($array[$i]["title"]); 

                if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                    $url = "https://";
                else  
                    $url = "http://";   
    
                $url.= $_SERVER['HTTP_HOST'];  
                $array[$i]["profile_base_url"] =       $C->SITE_URL."storage/avatars/thumbs1/";
                $array[$i]["attachment_base_url"] =      $C->SITE_URL."storage/attachments/1/";
			
                $array[$i]["pid"] = '';
                 
                if(empty($array[$i]["attachements"])) {
                    $array[$i]["attachements"] = '';
                }

                $array[$i]["attach_data"] = NULL;
                if(!empty($array[$i]["data"])) {
                    $att_data = (array)unserialize($array[$i]["data"]);                    
                    $file_original = isset($att_data['file_original']) ? $att_data['file_original'] : '';
                    if(!empty($file_original)) {
                        $array[$i]["attach_data"]["file_original"] = $file_original;
                    }
                }
                unset($array[$i]["data"]);

                if(empty($array[$i]["coverimage"])) {
                    $array[$i]["coverimage"] = '';
                }
                 
                $array[$i]["is_profile_liked"] = $this->is_profile_like($user_id, $array[$i]["postuserid"]);

                $query = 'SELECT * FROM post_likes WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
                $res =  $db2->query($query);
                $obj = $db2->fetch_object($res);
                
                if(empty($obj->id)){
                    $array[$i]["isliked"] = "0";  
                } else {
                    $array[$i]["isliked"] = "1";  
                }
					
    			$query1 = 'SELECT * FROM post_reshares WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
                $res1 =  $db2->query($query1);
                $obj1 = $db2->fetch_object($res1);
						  
                if(empty($obj1->id)){
                  $array[$i]["isbuzzed"] = "0";  
                } else {
                    $array[$i]["isbuzzed"] = "1";  
                }	

				$query1 = 'SELECT * FROM profile_share WHERE  who="'.$user_id.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
                $res1 =  $db2->query($query1);
                $obj1 = $db2->fetch_object($res1);

                if(empty($obj1->id)){
                  $array[$i]["isshared"] = "0";  
                } else {
                    $array[$i]["isshared"] = "1";  
                }

	        	$query3 = 'SELECT * FROM users_followed WHERE  who="'.$user_id.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
                $res3 =  $db2->query($query3);
                 $obj3 = $db2->fetch_object($res3);
				
		        if(empty($obj3->id)){
                    $array[$i]["isfollowed"] = "0";  
                } else {
                    $array[$i]["isfollowed"] = "1";  
                }	

                $r = $db2->query('select count(id) as joincount  FROM post_userbox WHERE post_id="'.$array[$i]["postid"].'" AND event_status="'.'1'.'"', FALSE);
                $result = $db2->fetch_object($r);
                $sharecount = $result->joincount;
                $array[$i]['joincount'] = $sharecount;
								
                $query = 'SELECT * FROM posts_attachments WHERE  type="'.'image'.'"  AND post_id = '.$array[$i]["postid"].' ';
                $res =  $db2->query($query);
                $eventAttach = $res->fetch_all(MYSQLI_ASSOC);
                $array[$i]["event_attachment"] = $eventAttach;
                $videoquery = 'SELECT * FROM posts_attachments WHERE  type="'.'file'.'"  AND post_id = '.$array[$i]["postid"].' ';
                $videores =  $db2->query($videoquery);
                $videoattachment = $videores->fetch_all(MYSQLI_ASSOC);
                if(!empty($videoattachment)){
                    $videos = $videoattachment[0]['data'];
                    $unserialize = unserialize($videos);
                    $videoattachmentdata = $unserialize->file_original;
                    $array[$i]['video_attachment'] = $videoattachmentdata;
                }else{
                   $array[$i]['video_attachment'] = ''; 
                }
               $returnarray[] = $array[$i];
            }
			return $returnarray;
	  }

	  	public function acceptedevents_workspace($userId,$pagenumber,$pagerecordcount)
	    {
	        global $db2, $C;
 	   	    $pagenumber = $pagenumber -1;
	        $pagenumber = ((int)$pagenumber *  (int)$pagerecordcount)  ;
 	   	    $pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);
 	   	    $user_id = $userId;
 	   	    $posttype = 4;

 	   	    
	        $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);

			$query = 'SELECT  p.id as postid,p.user_id AS postuserid,
                        u.username AS postusername,
                        u.avatar AS postuserimage,
                        if( (u.cover is null), "", u.cover) AS coverimage,
                        if( (p.title is null), "", p.title) AS title,                       
                        p.posttype,
                        p.message AS message, 
                        p.likes,
                        p.mentioned,
						pa.data as attachments,
                        pa.data,
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
                        u.username AS commentdetails,
                        "" as category,
                        "public" AS type,
                        if( (pd.views is null), 0, pd.views) AS ViewCount,
                        if( (pd.shares is null), 0, pd.shares) AS sharecount,
                        if( (pd.likes is null), 0, pd.likes) AS likes,
                        if( (pd.comments is null), 0, pd.comments) AS comments,
                        if( (pd.reshares is null), 0, pd.reshares) AS reshares,
                         e.id,
                            e.admin_id,
                            e.address,
                            e.location,
                            e.event_name,
                            e.start_date,
                            e.start_time,
                            e.end_date,
                            e.end_time,
                            e.event_description,
                            e.status
                        FROM `event_posts` as ep 
                        inner join posts as p ON ep.post_id = p.id 
                        inner join post_userbox as pu ON pu.post_id=p.id                        
                        inner join events as e on ep.event_id = e.id
                        LEFT JOIN posts_details pd ON pd.post_id = p.id
                        LEFT JOIN users u ON u.id=p.user_id
                        LEFT  JOIN posts_attachments pa ON pa.post_id = p.id
                        WHERE p.user_id = "'.$user_id.'"  and  p.posttype = "'.$posttype.'" and (pu.event_status = 1) group by p.id  order by pu.post_id desc LIMIT '.$pagenumber.','.$pagerecordcount;

            $res =  $db2->query($query);
            $array = $res->fetch_all(MYSQLI_ASSOC);
			$returnarray = array();


			foreach ($array as $i =>$array_expression)
            {
               /* if($array[$i]["posttype"] != 2) {
                    $title = strip_tags($array[$i]["message"]); 
                    $array[$i]["title"] = mb_substr($title, 0, 144,'UTF-8');

                    unset($array[$i]["message"]);
                }*/
                $array[$i]["title"] = strip_tags($array[$i]["title"]); 
                
                if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                    $url = "https://";
                else  
                    $url = "http://";   
    
                $url.= $_SERVER['HTTP_HOST'];  
                $array[$i]["profile_base_url"] =       $C->SITE_URL."storage/avatars/thumbs1/";
                $array[$i]["attachment_base_url"] =       $C->SITE_URL."storage/attachments/1/";
                
                if(empty($array[$i]["attachements"])) {
                    $array[$i]["attachements"] = '';
                }

                $array[$i]["attach_data"] = NULL;
                if(!empty($array[$i]["data"])) {
                    $att_data = (array)unserialize($array[$i]["data"]);                    
                    $file_original = isset($att_data['file_original']) ? $att_data['file_original'] : '';
                    if(!empty($file_original)) {
                        $array[$i]["attach_data"]["file_original"] = $file_original;
                    }
                }
                unset($array[$i]["data"]);

                if(empty($array[$i]["coverimage"])) {
                    $array[$i]["coverimage"] = '';
                }

                $array[$i]["is_profile_liked"] = $this->is_profile_like($user_id, $array[$i]["postuserid"]);

                $query = 'SELECT * FROM post_likes WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
                $res =  $db2->query($query);
                $obj = $db2->fetch_object($res);                

                if(empty($obj->id)){
                    $array[$i]["isliked"] = "0";  
                } else {
                    $array[$i]["isliked"] = "1";  
                }                
					
    			$query1 = 'SELECT * FROM post_reshares WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
                $res1 =  $db2->query($query1);
                $obj1 = $db2->fetch_object($res1);			
			  
                if(empty($obj1->id)){
                    $array[$i]["isbuzzed"] = "0";  
                } else {
                    $array[$i]["isbuzzed"] = "1";  
                }	

				$query1 = 'SELECT * FROM profile_share WHERE  who="'.$user_id.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
                $res1 =  $db2->query($query1);
                $obj1 = $db2->fetch_object($res1);			  
                if(empty($obj1->id)){
                    $array[$i]["isshared"] = "0";  
                } else {
                    $array[$i]["isshared"] = "1";  
                }

	        	$query3 = 'SELECT * FROM users_followed WHERE  who="'.$user_id.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
                $res3 =  $db2->query($query3);
                $obj3 = $db2->fetch_object($res3);
				
		        if(empty($obj3->id)){
                    $array[$i]["isfollowed"] = "0";  
                } else {
                    $array[$i]["isfollowed"] = "1";  
                }	

                $r = $db2->query('select count(id) as joincount  FROM post_userbox WHERE post_id="'.$array[$i]["postid"].'" AND event_status="'.'1'.'"', FALSE);
                $result = $db2->fetch_object($r);
                $sharecount = $result->joincount;  	        
                $array[$i]['joincount'] = $sharecount;
				
                $query = 'SELECT * FROM posts_attachments WHERE  type="'.'image'.'"  AND post_id = '.$array[$i]["postid"].' ';
                $res =  $db2->query($query);
                $eventAttach = $res->fetch_all(MYSQLI_ASSOC);
                $array[$i]["event_attachment"] = $eventAttach;
                $videoquery = 'SELECT * FROM posts_attachments WHERE  type="'.'file'.'"  AND post_id = '.$array[$i]["postid"].' ';
                $videores =  $db2->query($videoquery);
                $videoattachment = $videores->fetch_all(MYSQLI_ASSOC);
                if(!empty($videoattachment)){
                    $videos = $videoattachment[0]['data'];
                    $unserialize = unserialize($videos);
                    $videoattachmentdata = $unserialize->file_original;
                    $array[$i]['video_attachment'] = $videoattachmentdata;
                } else {
                   $array[$i]['video_attachment'] = ''; 
                }
               $returnarray[] = $array[$i];
            }
			return $returnarray;		 
	  }
	  /*Loading posts for particular user*/
	   public function userLoadPosts($user_id,$pagenumber,$pagerecordcount)
	  {
	        global $db2, $C;
 	   	    $pagenumber = $pagenumber -1;
	        $pagenumber = ((int)$pagenumber *  (int)$pagerecordcount)  ;
 	   	    //$pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);
 	   	    $pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);
 	   	    
	        $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);

            /* (Select count(id) from  profile_share where whom =p.user_id ) AS profilesharecount,
                        if( ((Select count(id) from  posts_social_share where post_id =p.id and user_id=p.user_id) > 0), true,  false) AS isshared */
	        
			$query = 'SELECT 
                        p.id AS pid,
                        p.id AS postid,
                        p.user_id AS postuserid,
                        u.username AS postusername,
                        u.avatar AS postuserimage,
                        if( (u.cover is null), "", u.cover) AS coverimage,
                        if( (p.title is null), "", p.title) AS title,                       
                        p.posttype,
                        p.message AS message,
                        p.likes,
                        p.mentioned,
						GROUP_CONCAT(CONCAT("type=",pa.type,";"),pa.data) as attachements,
                        pa.data,
                        pa.video_url as video_url,
                        pa.video_id as video_id,
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
                        if( (pd.views is null), 0, pd.views) AS ViewCount,
                        if( (pd.shares is null), 0, pd.shares) AS sharecount,
                        if( (pd.likes is null), 0, pd.likes) AS likes,
                        if( (pd.comments is null), 0, pd.comments) AS comments,
                        if( (pd.reshares is null), 0, pd.reshares) AS reshares                        
							FROM posts p
							LEFT JOIN users u ON u.id=p.user_id
                            LEFT JOIN posts_details pd ON pd.post_id = p.id
							LEFT OUTER JOIN posts_attachments pa ON pa.post_id = p.id
						WHERE
                        p.user_id='.$user_id.' AND (p.post_level = 0 OR p.post_level is null)  AND p.id is not null

                            Group By p.id
                            ORDER BY p.date_lastcomment DESC
                         LIMIT '.$pagenumber.','.$pagerecordcount;
                        
            $res =  $db2->query($query);
  			$array = $res->fetch_all(MYSQLI_ASSOC);
			$returnarray = array();

            foreach ($array as $i =>$array_expression) {
                $array[$i]["title"] = strip_tags($array[$i]["title"]); 

                if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                    $url = "https://";
                else  
                    $url = "http://";   
    
                $url.= $_SERVER['HTTP_HOST'];  
                    $array[$i]["profile_base_url"] =      $C->SITE_URL."storage/avatars/thumbs1/";
                    $array[$i]["attachment_base_url"] =       $C->SITE_URL."storage/attachments/1/";
			
                if(empty($array[$i]["attachements"])) {
                    $array[$i]["attachements"] = '';
                }

                $array[$i]["attach_data"] = NULL;
                if(!empty($array[$i]["data"])) {
                    $att_data = (array)unserialize($array[$i]["data"]);                    
                    $file_original = isset($att_data['file_original']) ? $att_data['file_original'] : '';
                    if(!empty($file_original)) {
                        $array[$i]["attach_data"]["file_original"] = $file_original;
                    }
                }
                unset($array[$i]["data"]);

                if(empty($array[$i]["coverimage"])) {
                    $array[$i]["coverimage"] = '';
                }

               // $array[$i]["likes"] = $this->getLikes($array[$i]["postid"]);
              //  $array[$i]["comments"] = $this->getCommentsCount($array[$i]["postid"]);                
                
                $array[$i]["is_profile_liked"] = $this->is_profile_like($user_id, $array[$i]["postuserid"]);

                $query = 'SELECT * FROM post_likes WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
                $res =  $db2->query($query);
                $obj = $db2->fetch_object($res);                

                if(empty($obj->id)){
                  $array[$i]["isliked"] = "0";  
                } else {
                    $array[$i]["isliked"] = "1";  
                }
					
    			$query1 = 'SELECT * FROM post_reshares WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
                $res1 =  $db2->query($query1);
                $obj1 = $db2->fetch_object($res1);			
			  
                if(empty($obj1->id)) {
                    $array[$i]["isbuzzed"] = "0";  
                } else {
                    $array[$i]["isbuzzed"] = "1";  
                }	
								
				$query1 = 'SELECT * FROM profile_share WHERE  who="'.$user_id.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
                $res1 =  $db2->query($query1);
                $obj1 = $db2->fetch_object($res1);			
			  
                if(empty($obj1->id)){
                    $array[$i]["isshared"] = "0";  
                } else {
                    $array[$i]["isshared"] = "1";  
                }
								
	        	$query3 = 'SELECT * FROM users_followed WHERE  who="'.$user_id.'"  AND whom = '.$array[$i]["postuserid"].' LIMIT 1';
                $res3 =  $db2->query($query3);
                $obj3 = $db2->fetch_object($res3);
				
		        if(empty($obj3->id)) {
                    $array[$i]["isfollwed"] = "0";  
                } else {
                    $array[$i]["isfollwed"] = "1";  
                }		
				
               // $array[$i]["likes"]      =$this->get_like_count($array[$i]["postid"]);
              //  $array[$i]["reshares"]       =$this->new_reshare_count($array[$i]["postid"]);
                				 
                if ($array[$i]["posttype"] == 4) {
                    $query = 'SELECT * FROM event_posts WHERE  post_id="'.$array[$i]["postid"].'" LIMIT 1';
                    $res =  $db2->query($query);
                    $obj = $db2->fetch_object($res);
                    $event_id = $obj->event_id;
                                
                    $query = 'select event_status  FROM post_userbox WHERE user_id="'.$user_id.'" AND post_id="'.$array[$i]["postid"].'"  ';   
                    $res =  $db2->query($query);
                    $userResp = $res->fetch_all(MYSQLI_ASSOC);
                                        
                    $query = 'SELECT id as event_id,admin_id as user_id,address,location,event_name,event_description,start_date,start_time,end_date,end_time,status FROM events WHERE  id="'.$event_id.'" LIMIT 1';
                    $res =  $db2->query($query);
                    $array[$i]["event_detail"] = $db2->fetch_object($res); 
                    
                    $query = 'SELECT * FROM posts_attachments WHERE  type="'.'image'.'"  AND post_id = '.$array[$i]["postid"].' ';
                    $res =  $db2->query($query);
                    $eventAttach = $res->fetch_all(MYSQLI_ASSOC);
                    $videoquery = 'SELECT * FROM posts_attachments WHERE  type="'.'file'.'"  AND post_id = '.$array[$i]["postid"].' ';
                    $videores =  $db2->query($videoquery);
                    $videoattachment = $videores->fetch_all(MYSQLI_ASSOC);
                                                        
                    $r = $db2->query('select count(id) as joincount  FROM post_userbox WHERE post_id="'.$array[$i]["postid"].'" AND event_status="'.'1'.'"', FALSE);
                    $result = $db2->fetch_object($r);
                    $sharecount = $result->joincount; 
                    $array[$i]["event_detail"]->joincount = $sharecount;
                    
                    $array[$i]["event_detail"]->event_status = $userResp[0]['event_status'];
                    $array[$i]["event_detail"]->event_attachment = $eventAttach;
                    if(!empty($videoattachment)){
                        $videos = $videoattachment[0]['data'];
                        $unserialize = unserialize($videos);
                        $videoattachmentdata = $unserialize->file_original;
                        $array[$i]['event_detail']->video_attachment = $videoattachmentdata;
                    }
                }                
                
                if ($array[$i]["posttype"] == 5) {                    
                    $query = 'SELECT * FROM polls WHERE  posts_id="'.$array[$i]["postid"].'" LIMIT 1';
                    $res =  $db2->query($query);
                    $obj = $db2->fetch_object($res);
                    $array[$i]["poll_question"] = $obj->poll_question;
                    
                    $r = $db2->query('select count(id) as joincount  FROM post_poll_votes WHERE POLL_ID="' . $obj->poll_id . '"', FALSE);
                    $result = $db2->fetch_object($r);
                    $sharecount = $result->joincount;  
                            
                    $array[$i]['total_vote'] = $sharecount;
                    $array[$i]['poll_id'] = $obj->poll_id;
                                
                    $r = $db2->query('SELECT * FROM  post_poll_votes WHERE POLL_ID="' . $obj->poll_id . '" AND VOTER_USER_ID="' . $user_id . '" LIMIT 1');
                    $result = $db2->fetch_object($r);
                    $ANSWER_ID = $result->ANSWER_ID;  
                    
                    if($ANSWER_ID == null){
                        $ANSWER_ID = "0";
                    }
	           
                    $array[$i]['is_vote'] = $ANSWER_ID = $ANSWER_ID;  

                    $query = 'SELECT * FROM polls_answers WHERE  poll_id="'.$obj->poll_id.'"';
                    $res =  $db2->query($query);
                    // $array[$i]["poll_option"] = $res->fetch_all(MYSQLI_ASSOC);
                    $answer =  $res->fetch_all(MYSQLI_ASSOC);
                                      
                    $poll_value = array();

                    foreach($answer as $value){                
                        $r = $db2->query('select count(id) as pollvote  FROM post_poll_votes WHERE  ANSWER_ID="' . $value['poll_answer_id'] . '"', FALSE);
                        $result = $db2->fetch_object($r);
                        $result->pollvote;
                        $value['votes'] =  $result->pollvote;
                        $poll_value[] = $value;                        
                    }
                       
                    $array[$i]["poll_option"] = $poll_value;                
                
                    $query = 'SELECT data FROM posts_attachments WHERE  type="'.'image'.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1 ';
                    $res =  $db2->query($query);
                    $array[$i]["poll_attachment"] = $res->fetch_all(MYSQLI_ASSOC);                
                }
                $returnarray[] = $array[$i];
            }			
			return $returnarray;
	    }
	 /*
	   commerical add avalability checking
	   @param:post_user_id,date
	   @Author: Srinivasarao
	 */
	public function checkcommercialadds($post_userid,$postdate)
    {
         global $db2, $C;
 	   	 $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
         $adstype = 1;
       
         $r   = $db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number FROM ads_tags as at
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.start_date < "'.$postdate.'" AND ai.end_date > "'.$postdate.'"   AND ai.ads_type="'.$adstype.'" ');
        while( $res = $db2->fetch_object($r)){
			$fetchres[] = $res;
			   
		}
        return $fetchres;
     }
	  /*
	   paragraph1 add avalability checking
	   @param:post_user_id
	   @Author: Srinivasarao
	 */
      public function checkparagaphexist1($post_userid)
    {
         global $db2, $C;
 	   	 $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
         $adstype = 3;
         $r   = $db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_type,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number FROM ads_tags as at
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.ads_type="'.$adstype.'"  limit 1 ');
         $res = $db2->fetch_object($r);
         return $res;
      
        
    }
	 /*
	   paragraph2 add avalability checking
	   @param:post_user_id
	   @Author: Srinivasarao
	 */
     public function checkparagaphexist2($post_userid)
    {
         $adstype1=4;
         global $db2, $C;
 	   	 $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
         $r   = $db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_type,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number FROM ads_tags as at
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.ads_type="'.$adstype1.'"  limit 1 ');
        $res = $db2->fetch_object($r);
        return $res;
      
        
    }
	 /*
	   paragraph3 add avalability checking
	   @param:post_user_id
	   @Author: Srinivasarao
	 */
    public function checkparagaphexist3($post_userid)
    {
         $adstype1=5;
          global $db2, $C;
           $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
         $r   = $db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_type,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number FROM ads_tags as at
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.ads_type="'.$adstype1.'"  limit 1 ');
      $res = $db2->fetch_object($r);
        return $res;
      
        
    }
	 /*
	   Official add avalability checking
	   @param:post_user_id
	   @Author: Srinivasarao
	 */
    public function checkadexist($post_userid)
    {
       $adstype = 2;
        global $db2, $C;
         $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
         $r   = $db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number FROM ads_tags as at
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.ads_type="'.$adstype.'" AND ai.status=1 limit 1 ');
        
        $res = $db2->fetch_object($r);
        return $res;
    }
     /*
	   paragraph4 add avalability checking
	   @param:post_user_id
	   @Author: Srinivasarao
	 */
    public function checkparagaphexist4($post_userid)
    {
         $adstype1=6;
          global $db2, $C;
           $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
         $r   = $db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_type,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number FROM ads_tags as at
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.ads_type="'.$adstype1.'"  limit 1 ');
      $res = $db2->fetch_object($r);
        return $res;
      
        
    }
     /*
	   paragraph5 add avalability checking
	   @param:post_user_id
	   @Author: Srinivasarao
	 */
    public function checkparagaphexist5($post_userid)
    {
         $adstype1=7;
          global $db2, $C;
           $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
         $r   = $db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_type,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number FROM ads_tags as at
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.ads_type="'.$adstype1.'"  limit 1 ');
      $res = $db2->fetch_object($r);
        return $res;
      
        
    }
    
	    
	} 
?>
