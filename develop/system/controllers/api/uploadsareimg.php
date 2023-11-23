<?php
 require_once('../../helpers/func_main.php');
    require_once('../../conf_system.php');
      global $db2, $C;
 $returnarr = [];
  for($j=0; $j < count($_FILES["files"]['name']); $j++)
            { 
           
            $upload_dir = $C->STORAGE_DIR.'tmp/';
            $server_url = $C->STORAGE_URL.'tmp/';
            $avatar_name = $_FILES["files"]["name"]["$j"];
            $avatar_tmp_name = $_FILES["files"]["tmp_name"]["$j"];
            
            $temp = explode(".", $_FILES["files"]["name"]["$j"]);
            $digits = 17;
            $r_n = rand(pow(10, $digits-1), pow(10, $digits)-1);
            $newfilename1 = $r_n . '.' . end($temp);
            $upload_name = $upload_dir.strtolower($newfilename1);
            $upload_name = preg_replace('/\s+/', '-', $upload_name); 

            $imagecaption="Image";
            move_uploaded_file($avatar_tmp_name , $upload_name); 
            $returnarr[$avatar_name] = $newfilename1;
            
            }
            $arr["status"] = true;
            $arr["files"] = $returnarr;
            echo json_encode($arr);
?>