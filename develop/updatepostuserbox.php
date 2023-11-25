<?php


/*
$dbname="sb_test";

$connection = mysqli_connect("120.138.9.201","sb_live","Hanuman321#");
mysqli_select_db($connection,$dbname);
	
$tickerquery =mysqli_query($connection,"SELECT * FROM `post_userbox` WHERE updatestatus is null order by id ASC LIMIT 100");
while($tickerfinalres = mysqli_fetch_assoc($tickerquery)){
    $id = $tickerfinalres["id"];
    $user_id = $tickerfinalres["user_id"];
    $post_id = $tickerfinalres["post_id"];
    $event_status = $tickerfinalres["event_status"];
     $status = $tickerfinalres["status"];
     $presentdbname = "streetbuzz1_sb_live_1";
     $connection1 = mysqli_connect("182.18.139.51","streetbuzz1_sb_live_1","Hanuman321#");
      mysqli_select_db($connection1,$presentdbname);
  
    $result = mysqli_query($connection1,'INSERT INTO post_userbox VALUES("'.$id.'","'.$user_id.'","'.$post_id.'","'.$event_status.'","'.$status.'") ');
    if($result === false){
       printf("error: %s\n", mysqli_error($con));
    }
     

	                 

}
*/
$dbname="streetbuzz1_sb_live_1";
try{
$connection = mysqli_connect("182.18.139.51","streetbuzz1_sb_live_1","Hanuman321#");
$mysqlres =mysqli_select_db($connection,$dbname);
	
$tickerquery =mysqli_query($connection,"SELECT * FROM `post_userbox` WHERE updatestatus is null order by id ASC LIMIT 1");
echo $tickerquery;
}catch(Exception $e) {
    print_r($e->getMessage());
}

while($tickerfinalres = mysqli_fetch_assoc($tickerquery)){
    
    print_r($tickerfinalres);exit;
    $id = $tickerfinalres["id"];
    $user_id = $tickerfinalres["user_id"];
    $post_id = $tickerfinalres["post_id"];
    $event_status = $tickerfinalres["event_status"];
     $status = $tickerfinalres["status"];
     $presentdbname = "streetbu_sb_live";
     $connection1 = mysqli_connect("182.18.139.51","streetbu_sb_live","Hanuman321#");
      mysqli_select_db($connection1,$presentdbname);
  
    $result = mysqli_query($connection1,'INSERT INTO post_userbox VALUES("'.$id.'","'.$user_id.'","'.$post_id.'","'.$event_status.'","'.$status.'") ');
    if($result === false){
       printf("error: %s\n", mysqli_error($con));
    }
     

	                 

}


 
?>
