<?php
  global $db2,$C;

	require_once($C->INCPATH.'conf_embed.php');	
	require_once($C->INCPATH.'libraries/class_bitly.php');	
	require_once($C->INCPATH.'helpers/func_images.php');
	
//require('admin_city1.php');

  $this->load_langfile('inside/global.php');
  $this->load_langfile('inside/admin.php');

$id = $_GET['id'];



		
				$r	= $this->db2->query('SELECT * FROM posts_details WHERE post_id =19965');	
				
				die('pp');
//  $db2->query('SELECT * FROM `posts_details` WHERE post_id ='.$id);
  if( 0 == $db2->num_rows() ) {
  //  $this->redirect('dashboard');
      die('if');

  }
  
  else {
    die('else');
  
  }






// $servername = "localhost";
// $username = "streetbu_develop";
// $password = "streetbu_develop";
// $dbname = "streetbu_develop";
// // Create connection
// $connss = new mysqli($servername, $username, $password, $dbname);

// Check connection
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// }
// echo "Connected successfully";


/*	global $db2, $C;
//	print_r($db2); die("=======");
	$db2 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

$id = $_GET['id'];
//echo $id."hello sushil"; die("===");
$qq = "'".'SELECT * FROM `posts_details` WHERE post_id ='.$id."'";
$query=$this->db2->query($qq);
$res=mysqli_fetch_array($query);

// $ids=$this->db2->query('SELECT * FROM `posts_details` WHERE post_id =$id');
if(empty($res)){
    $qq="'".'INSERT INTO `posts_details`(`post_id`, `shares`) VALUES ("'.$id.'",1)'."'";
    $query=$this->db2->query($qq);
}else{
    $count = $res['shares'];
    $count = $count+1;
    $qq="'".'UPDATE `posts_details` SET `shares`="'.$count.'" WHERE post_id ='.$id."'";
    $query=$this->db2->query($qq);
}

*/
?>



















