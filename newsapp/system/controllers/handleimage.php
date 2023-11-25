<?php
if(empty($_POST)){
$userid	  =$_GET['id'];
$name = date('YmdHis');
$newname     = $C->STORAGE_DIR.'avatars/thumbs1/'.$name.'.jpg';
$newname1     = $C->STORAGE_DIR.'avatars/thumbs2/'.$name.'.jpg';
$newname2     = $C->STORAGE_DIR.'avatars/thumbs3/'.$name.'.jpg';
$newname3     = $C->STORAGE_DIR.'avatars/thumbs4/'.$name.'.jpg';
$newname4     = $C->STORAGE_DIR.'avatars/thumbs5/'.$name.'.jpg';
$photo =$name.'.jpg';




$file = file_put_contents( $newname, file_get_contents('php://input') );

$file1 = file_put_contents( $newname1, file_get_contents('php://input'));
$file2 = file_put_contents( $newname2, file_get_contents('php://input'));
$file3 = file_put_contents( $newname3, file_get_contents('php://input') );
$file4 = file_put_contents( $newname4, file_get_contents('php://input') );



$db2->query('INSERT INTO coverphotos SET  user_id="'.$userid.'",img_url="'.$db2->e($photo).'"');
$url = $newname;
print "$url\n";
EXIT;
}
if(isset($_POST['photo'])){
			$res_photos = $db2->query('SELECT id,img_url FROM coverphotos ORDER BY id DESC LIMIT 1');
			
			 $res_street = $db2->fetch_object($res_photos);
			 
				
			
			ECHO  $C->SITE_URL.'storage/avatars/thumbs1/'.$res_street->img_url;
			
			 exit;
			
		
	}

?>
