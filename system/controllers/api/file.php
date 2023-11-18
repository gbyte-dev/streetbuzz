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

$response = array();
$upload_dir = $_SERVER['DOCUMENT_ROOT'].'/develop/uploads/images/';
$server_url = 'https://streetbuzz.co/develop/uploads/images/';



if($_FILES['file'])
{
    $avatar_name = $_FILES["file"]["name"];
    $avatar_tmp_name = $_FILES["file"]["tmp_name"];
    $error = $_FILES["file"]["error"];

    if($error > 0){

//
    echo "upload_max_filesize=".ini_get('upload_max_filesize');  
    echo "post_max_size=".ini_get('post_max_size');  
//
	 $response = array(
				"status" => "error",
				"error" => true,
				"message" => "Error uploading the file!"
			);
    }
	else 
    {
		$random_name = rand(1000,1000000)."-".$avatar_name;
        $upload_name = $upload_dir.strtolower($random_name);
        $upload_name = preg_replace('/\s+/', '-', $upload_name);
        
        $api = new API();
        $api->insertTempFileDetails($avatar_name,str_replace(' ', '-',strtolower($random_name)), str_replace(' ', '-',strtolower($server_url.$random_name)));

        if(move_uploaded_file($avatar_tmp_name , $upload_name)) {
            $status = array(
                "statuscode" => "0",
                "message" => "success"
              );
            $file = array(
                "url" => str_replace(' ', '-',strtolower($server_url.$random_name)),
                "name" => $avatar_name,
                "generatedfilename" => str_replace(' ', '-',strtolower($random_name))
              );
            
        }
		else
        {
			$status = array(
                "statuscode" => "0",
                "message" => "error"
			);
        }
    }
}
else
{
    	$status = array(
                "statuscode" => "0",
                "message" => "error"
			);
}


    $response=array();
	$response["status"]=$status;
	$response["file"]= $file;
	echo json_encode($response);

?>
