

<?php
$files = glob('system/cache/*'); // get all file names
foreach($files as $file){
if(is_file($file)){
    $file ='http://streetbuzz.co/test/'.$file;
        echo $file.'<br />';

  unlink($file); //delete file
}
}


?>
