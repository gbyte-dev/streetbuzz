<?php 
ini_set('upload_max_filesize', '20000M');
ini_set('post_max_size', '20000M');                               
ini_set('max_input_time', 3000);                                
ini_set('max_execution_time', 3000);
require_once('../../helpers/func_main.php');
require_once('../../conf_system.php');
require_once('classes/class_api.m.php');

header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
define ("MAX_SIZE","10000");

$image =$_FILES['file']['name'];
$uploadedfile = $_FILES['file']['tmp_name'];

$userid    = $_POST["userid"]; 
$token     = $_POST["token"];    

//echo 'hello'; die;

if ($image) 
{
    

	$filename = stripslashes($_FILES['file']['name']);

	$extension = getExtension($filename);
	$extension = strtolower($extension);


	if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) 
	{
		//$change='<div class="msgdiv">Unknown Image extension </div> ';
		$errors=1;
		 $response = array(
					"status" => "error",
					"error" => true,
					"message" => "Unknown Image extension "
				);
	}
	else
	{
		$size=filesize($_FILES['file']['tmp_name']);

		if ($size > MAX_SIZE*1024)
		{
			//$change='<div class="msgdiv">You have exceeded the size limit!</div> ';
			$errors=1;
			 $response = array(
					"status" => "error",
					"error" => true,
					"message" => "You have exceeded the size limit!"
				);
		}

		if($extension=="jpg" || $extension=="jpeg" )
		{
			$uploadedfile = $_FILES['file']['tmp_name'];
			$src = imagecreatefromjpeg($uploadedfile);
		}
		else if($extension=="png")
		{
			$uploadedfile = $_FILES['file']['tmp_name'];
			$src = imagecreatefrompng($uploadedfile);
		}
		else 
		{
			$src = imagecreatefromgif($uploadedfile);
		}

		list($width,$height)=getimagesize($uploadedfile);

		$newwidth=50;
		$newheight=50;
		$tmp=imagecreatetruecolor($newwidth,$newheight);

		$newwidth1=16;
		$newheight1=16;
		$tmp1=imagecreatetruecolor($newwidth1,$newheight1);
		$newwidth2=30;
		$newheight2=30;
		$tmp2=imagecreatetruecolor($newwidth2,$newheight2);
		$newwidth3=100;
		$newheight3=100;
		$tmp3=imagecreatetruecolor($newwidth3,$newheight3);
		$newwidth4=420;
		$newheight4=60;
		$tmp4=imagecreatetruecolor($newwidth4,$newheight4);


		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
		imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);
		imagecopyresampled($tmp2,$src,0,0,0,0,$newwidth2,$newheight2,$width,$height);
		imagecopyresampled($tmp3,$src,0,0,0,0,$newwidth3,$newheight3,$width,$height);
		imagecopyresampled($tmp4,$src,0,0,0,0,$newwidth4,$newheight4,$width,$height);

		$avtr = time().rand(100000,999999).'.png';  

		$filename = $C->STORAGE_DIR.'avatars/thumbs1/'.$avtr;
		$filename1 = $C->STORAGE_DIR.'avatars/thumbs2/'.$avtr;
		$filename2 = $C->STORAGE_DIR.'avatars/thumbs3/'.$avtr;
		$filename3 = $C->STORAGE_DIR.'avatars/thumbs4/'.$avtr;
		$filename4 = $C->STORAGE_DIR.'avatars/thumbs5/'.$avtr;

		imagejpeg($tmp,$filename,100);
		imagejpeg($tmp1,$filename1,100);
		imagejpeg($tmp2,$filename2,100);
		imagejpeg($tmp3,$filename3,100);
		imagejpeg($tmp4,$filename4,100);

		imagedestroy($src);
		imagedestroy($tmp);
		imagedestroy($tmp1);
		imagedestroy($tmp2);
		imagedestroy($tmp3);
		imagedestroy($tmp4);

		 //echo "\n".$filename1;
		// echo "\n".$filename2;
		 //echo "\n".$filename3;
		 //echo "\n".$filename4;

		// //print_r($C);
		// exit;

		$userid    = $_POST["userid"]; 
		$token     = $_POST["token"]; 

		$response = array();

		$api = new API();
		
		$api->updateProfileImage($userid,$avtr);

		$status = array(
			"statuscode" => "0",
			"message" => "success"
		  );
		 $server_url = 'https://streetbuzz.co/develop/storage/avatars/thumbs1/';

		$file = array(
			"url" => str_replace(' ', '-',strtolower($server_url.$avtr)),
			"name" => $image,
			"generatedfilename" => $avtr
		  );

		
	}
	$response=array();
	$response["status"]=$status;
	$response["file"]= $file;
	echo json_encode($response);
}
?>
