<?php
header('Access-Control-Allow-Origin: *'); 
/*header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With'); header('Access-Control-Allow-Credentials: true');*/

if(!empty($_POST['email']) && !empty($_POST['password']) ){
    $email  =$_POST['email'];
    $pass   =($_POST['password']);
   


 $res = $db2->query('SELECT id FROM users WHERE (email="'.$db2->e($email).'" OR username="'.$db2->e($email).'" OR phone_no="'.$email.'") AND password="'.$db2->e(md5($pass)).'" AND active=1 LIMIT 1');
		$result    = $db2->fetch_object($res);

$numrows = $db2->num_rows();
if($numrows > 0){
    $pass   =md5($_POST['password']);
      if($_POST['rechecked'] == 1){
       $res=	$this->user->login($email, $pass, TRUE);
      }
      if($_POST['rechecked'] == 0){
       $res=	$this->user->login($email, $pass, FALSE);
      }
        if($_POST['rechecked'] == 1){
    		$username = "username";
            $usernamevalue = $email;
			$userpassword = "userpassword";
            $password = $pass;
		   setcookie($username, $usernamevalue, time() + (86400 * 30), "/"); // 86400 = 1 day
			setcookie($userpassword, $password, time() + (86400 * 30), "/"); // 86400 = 1 day
}
$findata['success'] = 1;

}else{
$findata['success'] = 1;
}
echo json_encode($findata);
}	
	


?>