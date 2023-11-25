<?php
require_once( $C->INCPATH.'libraries/globals.php');
require_once( $C->INCPATH.'libraries/oauth_helper.php');
 
    

	  // Grab the MIME type and the data with a regex for convenience
    if (!preg_match('/data:([^;]*);base64,(.*)/', $_POST['filedata'], $matches)) {
        die("error");
    }
    
    // Decode the data
    $data = $matches[2];
    $data = str_replace(' ','+',$data);
	
	
    $profileimage = ($data);

		$profileimagebase24 = base64_decode($profileimage); // base64 decoded image data

		$profileimagesource_img = imagecreatefromstring($profileimagebase24); //exit;

				//$profileimagerotated_img = imagerotate($profileimagesource_img, 0, 0); // rotate with angle 90 here
				if ($profileimagesource_img != false)
				{

				    $name = md5(date("Y-m-d H:i:s")) . '.jpg';
				//	imagejpeg($profileimagesource_img, 'assets/' . md5(date("Y-m-d H:i:s")) . '.jpg');

					$filename4 = $C->STORAGE_DIR.'thumbs/'.$name;

 



 imagejpeg($profileimagesource_img,$filename4);
					 $activityid = $_POST['activities_id'];
				   $db2->query('UPDATE posts SET thumb="'.$name.'" WHERE id="'.$activityid.'" ');




					exit;
				}

?>