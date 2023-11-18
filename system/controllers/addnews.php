<?php
if(!empty($_POST)){
   $languages = $_POST['languages'];
   $mainpost = $_POST['mainpost'];
   $db2->query('TRUNCATE TABLE   homenews ');


    foreach($_POST['postid'] as $keys=>$vals){
        $postid = $vals;
        $lan   = $languages[$keys];
        $main   = $mainpost[$keys];
     //  echo 'INSERT INTO homenews SET post_id="'.$postid.'", language="'.$lan.'", main_or_not="'.$main.'"  ';
      $db2->query('INSERT INTO homenews SET post_id="'.$postid.'", language="'.$lan.'", main_or_not="'.$main.'"  ');

  }
            	$this->redirect('admin_homenews');



}
?>