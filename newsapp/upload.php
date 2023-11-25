<?php
// print_r($_FILES);
// die('-----');
// //upload.php

// if(isset($_FILES['image']['name']))
// {
// // die('========');
//  $file = $_FILES['image']['tmp_name'];
//  $file_name = $_FILES['image']['name'];
//  $file_name_array = explode(".", $file_name);
//  $extension = end($file_name_array);
//  $new_image_name = rand() . '.' . $extension;
//  //chmod('upload', 0777);
//  $allowed_extension = array("jpg", "gif", "png", "jpeg");
//  if(in_array($extension, $allowed_extension))
//  {
//   move_uploaded_file($file, 'storage/attachments/1/' . $new_image_name);
//   $function_number = $_GET['CKEditorFuncNum'];
//   $url = 'storage/attachments/1/' . $new_image_name;
//   $message = '';
  
//   $var = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

//   $baseurl = str_replace('upload.php', '', $var);
  
//   echo $baseurl.$url;
   
//  }
// }

   define('UPLOAD_DIR', 'storage/attachments/1/');
//   define('UPLOAD_DIR', 'storage/tmp/');
    $image_parts = explode(";base64,", $_POST['image']);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
    
  //  echo $image_parts; die('======');
    
    
    $file = UPLOAD_DIR . uniqid() . '.png';
    $fileexplode = explode(".png",$file);
    $thumbfile = $fileexplode[0]. '_thumb.png';
    file_put_contents($file, $image_base64);
  $var = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

  $baseurl = str_replace('upload.php', '', $var);

  $serverbaseurl = $baseurl.$file;
    $thumbserverbaseurl = $baseurl.$thumbfile;
    $imgformat = true;

//Get the width, height and type values of the original image
list($width, $height, $type) = getimagesize($file);
if ($type == IMAGETYPE_JPEG)
   $img = imagecreatefromjpeg($file);
elseif ($type == IMAGETYPE_PNG)
   $img = imagecreatefrompng($file);
elseif ($type == IMAGETYPE_GIF)
   $img = imagecreatefromgif($file);
else
   $imgformat = false;
   // $img_crop = imagecrop($img, ['x' => 10, 'y' => 10, 'width' => $width-100, 'height' => $height-100]);
   
    //  imagejpeg($img_crop,$thumbfile);
      imagejpeg($img, $thumbfile, 60);







    echo $baseurl.$file;
    
    
?>