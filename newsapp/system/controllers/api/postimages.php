<?php 
ini_set('upload_max_filesize', '20000M');
ini_set('post_max_size', '20000M');                               
ini_set('max_input_time', 3000);                                
ini_set('max_execution_time', 3000);
 	require_once('../../helpers/func_main.php');
	require_once('../../conf_system.php');
	require_once('classes/class_api.m3.php');
 
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");

$response = array();
$upload_dir = $C->STORAGE_DIR.'tmp/';
$server_url = $C->STORAGE_URL.'tmp/';
$response = array();
$file		 = array();
$status 	 = array();

	$api = new API();


	foreach($_FILES['file']['tmp_name'] as $key => $tmp_name)
	{		
		if($key.$_FILES['file'])
		{
			$avatar_name = $_FILES["file"]["name"][$key];
			$avatar_tmp_name = $_FILES["file"]["tmp_name"][$key];
			$error = $_FILES["file"]["error"][$key];

			if($error > 0){

	 
			array_push($response , array(
						"status" => "error1",
						"error" => true,
						"message" => "Error uploading the file!"
					));
			 
			}
			else 
			{
				$random_name = rand(1000,1000000)."_".$avatar_name;
				$upload_name = $upload_dir.strtolower($random_name);
				$upload_name = preg_replace('/\s+/', '_', $upload_name);
				
				$api->insertTempFileDetails($avatar_name,str_replace(' ', '_',strtolower($random_name)), str_replace(' ', '_',strtolower($server_url.$random_name)));
				if(move_uploaded_file($avatar_tmp_name , $upload_name)) {
					array_push($status, array(
						"statuscode" => "0",
						"message" => "success"
					  ));  
					 
					  
					   array_push($file, array(
						"url" => str_replace(' ', '-',strtolower($server_url.$random_name)),
						 "name" => $avatar_name,
						 "generatedfilename" => str_replace(' ', '-',strtolower($random_name))
					   ));
					
				}
				else
				{
					array_push($status, array(
						"statuscode" => "0",
						"message" => "error"
					));
				}
			}
		}
		else
		{
				array_push($status, array(
						"statuscode" => "0",
						"message" => "error3"
					));
		}

	}
    $response=array();
	$response["status"]=$status;
	$response["file"]= $file;
	echo json_encode($response);

?>
