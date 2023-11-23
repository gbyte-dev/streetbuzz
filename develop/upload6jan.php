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
    $image_parts = explode(";base64,", $_POST['image']);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
    $file = UPLOAD_DIR . uniqid() . '.png';
    file_put_contents($file, $image_base64);
  $var = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

  $baseurl = str_replace('upload.php', '', $var);
    echo $baseurl.$file;
    
?>