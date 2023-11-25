<?php
 	class API
	{
		public function loadPostDetails($postid,$pagenumber,$pagerecordcount)
	  {
	        global $db2, $C;
 	   	    $pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);
 	   	    
 	   	    $pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);
 	   	    
 	   	    
	        $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);

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
                        u.username AS `category`,
                        "public" AS `type`,
						if( (pv.cnt is null), 0, pv.cnt) AS viewcount
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

             $res =  $db2->query($query);
  			$array = $res->fetch_all(MYSQLI_ASSOC);
			$returnarray = array();
 							$dom = new DomDocument();

			foreach ($array as $i =>$array_expression)
            {
                $array[$i]["likes"] = $this->getLikes($array[$i]["postid"]);
                $array[$i]["attachement"] = $this->getAttachements($array[$i]["postid"]);
                $array[$i]["comments"] = $this->getCommentsCount($array[$i]["postid"]);
				
				$html_string = $array[$i]["message"];
				
             
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
//print_r($images);
//die();
									    
				if( $ii = $p->attach_image($C->STORAGE_TMP_DIR.$img->tempfile, $img->filename) ) {
		//if( $ii = $p->attach_image($source_dir.$img["image"],$img->filename) ) {
							$db2->query('INSERT INTO `posts_attachments`(`post_id`, `type`, `data`,  `content`) VALUES ('.$post_id.',\'Image\',\''.$db2->escape(serialize($ii)).'\',\''.$imagecaption.'\')');
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
			$query = 'SELECT who from users_followed where whom='.$userid.' AND who != '.$userid ;
			$res =  $db2->query($query);
  			return $res->fetch_all(MYSQLI_ASSOC);
		}
		public function submitPost($user_id,$postdata,$parentid,$title,$coverimage,$images,$posttype)
		{
            global $db2, $C;
	        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
			
			$link = mysqli_connect($C->DB_HOST,$C->DB_USER, $C->DB_PASS, $C->DB_NAME) or die($link);

            $post_level  = 1;
            if ((int)$parentid==0)
            {
                $post_level = 0;
            } 

            if($post_level == 1){
 			   $db2->query('Insert into posts (user_id,message,date,date_lastcomment,ip_addr,parent_id,post_level,posttype,title,coverimage) VALUES ('.$user_id.',"'.mysqli_real_escape_string($link,$postdata).'","'.time().'","'.time().'","'.ip2long($_SERVER['REMOTE_ADDR']).'",'.$parentid.','.$post_level.',2,"'.$title.'","'.$coverimage.'")');//$title,$imageur
			}else{
				$db2->query('Insert into posts (user_id,message,date,date_lastcomment,ip_addr,parent_id,posttype,title,coverimage) VALUES ('.$user_id.',"'.mysqli_real_escape_string($link,$postdata).'","'.time().'","'.time().'","'.ip2long($_SERVER['REMOTE_ADDR']).'",'.$parentid.',2,"'.$title.'","'.$coverimage.'")');//$title,$imageur

			}
 	
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
            if($posttype !== undefined && $posttype == 2){
                	if( !empty($images) ){
                	  

					
					    //echo $C->STORAGE_TMP_DIR.$img["image"];
					    //echo  $img["image"];
					    //print_r ($p->attach_image($C->STORAGE_TMP_DIR.$img["image"], $img["image"])); 
					    if(copy($C->STORAGE_TMP_DIR.$images["basename"],$C->STORAGE_DIR.'attachments/1/'.$images["basename"])){
            					        	$data	=  array (	
            				'in_tmpdir'	=> TRUE,	
            				'title'	=> $images["basename"],	
            				'file_original'	=> $images["basename"],	
            				'file_preview'	=>$images["basename"],	
            				'file_thumbnail'	=> $images["basename"],	
            				'size_original'	=> '',	
            				'size_preview'	=> '',	
            				'filesize'	=> 0,	
            				'hits'	=> 0,	
            			);	
            			$serializedata =  (serialize($data));
            			$serializedata =  str_replace("s:30","s:25",$serializedata);

            			 $imagecaption = $img["basename"];
     							$db2->query('INSERT INTO `posts_attachments`(`post_id`, `type`, `data`,  `content`) VALUES ('.$post_id.',\'Image\',\''.$db2->escape(($serializedata)).'\',\''.$imagecaption.'\')');
					         
					        
					    }
					  
					    
					   
					    
                	/*	if( $ii = $p->attach_image($C->STORAGE_TMP_DIR.$img["basename"], $img["basename"]) ) {
                		echo 2; 
                		    $imagecaption = $img["basename"];
     							$db2->query('INSERT INTO `posts_attachments`(`post_id`, `type`, `data`,  `content`) VALUES ('.$post_id.',\'Image\',\''.$db2->escape(serialize($ii)).'\',\''.$imagecaption.'\')');
     						
 
                                $newimg = $this->splitAttachements(serialize($ii));
                               
                                
                                foreach ($newimg as $tmpimg)
                                {
                                    rename($C->STORAGE_TMP_DIR.$tmpimg,$C->STORAGE_DIR.'attachments/1/'.$tmpimg);
                                }
                		} */
					
					unset($images);
				}
                
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
         function getUserDetails($userid)
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
                //$array[$i]["like"] = $this->getUserLikes($userid);
                
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
            //echo $array['cover']; die;
           
          // print_r($array['cover_image']); die;
          // https://streetbuzz.co/develop/storage/avatars/thumbs1
           
			$returnarray = array();
 			$image_url =  $this->profileImageUrl();
			foreach ($array as $i =>$array_expression)
            {
                $array[$i]["followers"] = $this->getUserFollowers($userid);
                $array[$i]["following"] = $this->getUserFollowing($userid);
                //$array[$i]["like"] = $this->getUserLikes($userid);
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
		
	    public function getUserLikes($userid)
       {	     
	      global $db2;
        //	$query = 'SELECT Count(id) as cnt from post_likes where user_id='.$userid;
 	      	$query = 'SELECT Count(id) as cnt from user_favourites where whom='.$userid;
 	      	$res = $db2->query($query);
	       if($db2->num_rows($res) > 0)
	       {
	           $obj = $db2->fetch_object($res);
	           return intval($obj->cnt);
	       }
	       return 0;
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
    	   
    	   else {
	       return false;
    	   }
	  }
	  public function loadPosts($user_id,$pagenumber,$pagerecordcount)
	  {
	        global $db2, $C;
 	   	    $pagenumber = (intVal($pagenumber) == 0)? "0" : (intVal($pagenumber)-1);
 	   	    
 	   	    $pagerecordcount = (intVal($pagerecordcount) == 0)? $C->PAGING_NUM_POSTS : intVal($pagerecordcount);
 	   	    
 	   	    
	        $db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
	        
			$query = 'SELECT 
                        b.id AS pid,
                        p.id AS postid,
                        p.user_id AS postuserid,
                        u.username AS postusername,
                        u.avatar AS postuserimage,
						if( (u.cover is null), "", u.cover) AS coverimage,
                        if( (posttype = 2), p.title,  SUBSTRING(p.message, 1, 75)) AS title,
                        p.posttype,
                        p.message AS message,
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
                        u.username AS `category`,
                        "public" AS `type`,
						if( (pv.cnt is null), 0, pv.cnt) AS ViewCount
							FROM post_userbox b
							LEFT JOIN posts p ON p.id=b.post_id
							LEFT JOIN users u ON u.id=p.user_id
							LEFT OUTER JOIN posts_attachments pa ON pa.post_id = b.post_id
							LEFT OUTER JOIN post_views_list pv ON pv.post_id = p.id
						WHERE
                        b.user_id='.$user_id.' AND p.post_level = 0  AND p.id is not null and (INSTR (pa.data, "<div") < 1 or INSTR (pa.data, "<div") is null)

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
				
				
				
			$query1 = 'SELECT * FROM post_reshares WHERE  user_id="'.$user_id.'"  AND post_id = '.$array[$i]["postid"].' LIMIT 1';
            $res1 =  $db2->query($query1);
            $obj1 = $db2->fetch_object($res1);
			
			  
                if(empty($obj1->id)){
                  $array[$i]["isbuzzed"] = "0";  
                }
                
                else {
                    $array[$i]["isbuzzed"] = "1";  
                }	
				
				
				$query3 = 'SELECT * FROM post_views_list WHERE  post_id = '.$array[$i]["postid"].' LIMIT 1';
                $res3 =  $db2->query($query3);
                $obj3 = $db2->fetch_object($res3);
				
				 $array[$i]["postview"] = $obj3->cnt; 
				
				$html_string = $array[$i]["message"];
				
                
                
                /* $charset = NULL;
                $searchInElemnt = function(&$item) use (&$searchInElemnt, &$charset)
                {
                    if($item->childNodes)
                    {
                        foreach($item->childNodes as $childItem)
                        {
                            //echo $childItem->nodeName;
                        }
                    }
                };
                */

                

 				//$html = str_get_html($html_string);
				//echo $html;
				/*
				foreach($html->find('h3') as $element) 
				{
					$array[$i]["title"] = $element->innertext;
				}
				foreach($html->find('body') as $element) 
				{
					$array[$i]["message"] = $element->innertext;
				}*/

                $returnarray[] = $array[$i];
            }
			
			return $returnarray;
			
		 
	  }
	   public function getLikes($postid)
	  {
	       global $db2, $C;
 	   	    
	        //$db2 = new mysql($C->DB_HOST,$C->DB_USER,$C->DB_PASS,$C->DB_NAME);
	        
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
 	  public function getCommentsCount($postid)
	  {
	        global $db2, $C;
 	   	    
			$query = 'SELECT Count(id) as cnt from posts where parent_id='.$postid;
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
    
        // Append the requested resource location to the URL   
        //$url.= $_SERVER['REQUEST_URI'];    
	    
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

			    $res = $db2->query('Update oauth_access_token SET access_token = "" WHERE  id="'.intval($obj->id).'"');
			    
			    return true;
		    }
            return false;
	    }        
	} 
?>
