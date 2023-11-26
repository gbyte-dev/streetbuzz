<?php
 	class API
	{
	    public function __construct()
		{
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

    public function textpost_edit($user_id, $post_id, $message)
    {

        global $db2, $C;
        $db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

        $time = time();
        $db2->query('UPDATE posts set message="' . $message . '", date="' . $time . '" Where id="' . $post_id . '"');
    }
	
	
    }
    
?>
