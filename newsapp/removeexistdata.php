<?php
$max_post_id=409499;
//This query for getting results from post day feel table where result is null.
$dbname="streetbuzz1_sb_live_1";
$imgeurl ="/home/streetbuzz1/public_html/newsapp/storage/attachments/1/";

//Connect to the database
$connection = mysqli_connect("localhost","streetbuzz1_sb_live_1","Hanuman321#");
mysqli_select_db($connection,$dbname);

$viewquery  = "SELECT *  FROM posts_attachments  limit 5000  ";

$viewcheck    = mysqli_query($connection,$viewquery);

while($predictselectqueryres = mysqli_fetch_assoc($viewcheck)){
    $post_id = $predictselectqueryres["post_id"];
    if($max_post_id > $post_id){
        $unserialize = unserialize($predictselectqueryres["data"]);
        $unserialize  = (array) $unserialize;
        $file_original = "";
        if(isset($unserialize["file_original"])){
             $file_original = $imgeurl.$unserialize["file_original"];
        }
        if(isset($unserialize["file_preview"])){
                    $file_preview = $imgeurl.$unserialize["file_preview"];

        }
        if(isset($unserialize["file_thumbnail"])){
             $file_thumbnail = $imgeurl.$unserialize["file_thumbnail"];
        }

       
        if(file_exists($file_original)){
            unset($file_original);
        }else{
           
        }
       if(file_exists($file_preview)){
            unset($file_preview);
       }
        if(file_exists($file_thumbnail)){
            unset($file_thumbnail);
       }

        $deletepostattachmentquery  = "delete FROM posts_attachments where post_id='$post_id'  ";
        echo $deletepostattachmentquery;

        $viewcheckii    = mysqli_query($connection,$deletepostattachmentquery);
        $deletepostquery  = "delete FROM posts where id='$post_id'  ";
                $viewcheckii    = mysqli_query($connection,$deletepostquery);



        

     
        
    }
    
}

?>