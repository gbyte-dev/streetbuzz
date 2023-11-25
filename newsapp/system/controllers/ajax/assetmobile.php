 
  <?php
  global $C;global $db2;
if($_POST['usertype'] =='asset'){
$name		= isset($_POST['users_name']) ? trim($_POST['users_name']) : '';
$w	= $db2->e($name);


$data['user'][0]['id'] =$name;
echo json_encode($data);
}
 
 ?>