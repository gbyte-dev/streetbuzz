<?php
  
  if( !$this->network->id ) {
    $this->redirect('home');
  }
  if( !$this->user->is_logged ) {
    $this->redirect('signin');
  }
  $db2->query('SELECT 1 FROM users WHERE id="'.$this->user->id.'" AND is_network_admin=1 LIMIT 1');
  if( 0 == $db2->num_rows() ) {
    $this->redirect('dashboard');
  }
  
  $this->load_langfile('inside/global.php');
  $this->load_langfile('inside/admin.php');
  if(!empty($_POST)){

  }
  //require_once( $C->INCPATH.'helpers/func_images.php' );
  

  
  $tpl = new template( array('page_title' => $this->lang('admpgtitle_networkbranding', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );
  
  $tpl->initRoutine('AdminLeftMenu', array());
  $tpl->routine->load();

  if(isset($_POST['sub'])){

    $state_id = $_GET['language'];
    $db2->query('DELETE FROM `homenews` where state_id="'.$state_id.'"');

  $count = count($_POST['postid']);
  for($i=0;$i<$count;$i++){
 //   echo $_POST['postid'][$i];
//echo 'INSERT INTO `homenews`(`post_id`,`main_or_not`,`state_id`) VALUES ("'.$_POST['postid'][$i].'","'.$_POST['mainpost'][$i].'","'.$state_id.'")'; die;
  $db2->query('INSERT INTO `homenews`(`post_id`,`main_or_not`,`state_id`,`sequence`) VALUES ("'.$_POST['postid'][$i].'","'.$_POST['mainpost'][$i].'","'.$state_id.'","'.$_POST['sequence'][$i].'")');


  }

}

  if(isset($_GET['submit'])){
    $state=$_GET['language'];

//echo 'select * from homenews where state_id="'.$state.'" order by id desc '; die;

  $check        = $db2->query('select * from homenews where state_id="'.$state.'" order by id desc ');
 
$count = ($check->num_rows);
if($count > 0){
    $datares='';
    while($result    = $db2->fetch_object($check)){ 

        $sequence =$result->sequence;
        $postid =$result->post_id;
        if($result->language =='ENGLISH'){
            $englishselect='selected';
            
        }else{
             $englishselect='';
            
        }
        if($result->language =='ap'){
            $teluguselected='selected';
            
        }else{
             $teluguselected ='';
            
        }
        if($result->language =='jha'){
            $jharkhandselected='selected';
            
        }else{
             $hindiselected ='';
            
        }
        if($result->language =='tel'){
            $telanganaselected ='selected';
            
        }else{
             $telanganaselected  ='';
            
        }
        if($result->language =='KANNADA'){
            $kannadaselected='selected';
            
        }else{
             $kannadaselected ='';
            
        }
        if($result->language =='bhi'){
            $bhiharselected='selected';
            
        }else{
             $bhiharselected ='';
            
        }
        if($result->main_or_not =='YES'){
            $yesselected='selected';
            
        }else{
            $yesselected='';
            
        }
        if($result->main_or_not =='NO'){
            $noselected='selected';
        }else{
            $noselected='';
            
        }

        
    $datares .='<div class="row">

 <div class="col-md-3"><input type="text" placeholder="Sequence" name="sequence[]" value="'.$sequence.'"></div>

  <div class="col-md-3"><input type="text" placeholder="Post id" name="postid[]" value="'.$postid.'"></div>
  
   <div class="col-md-3"><select name="mainpost[]"><option>Main Post ?</option>
  <option value="YES" '.$yesselected.'>YES</option><option value="NO" '.$noselected.'>NO</option>
  </select></div>

  <div class="col-md-3">
  <input type="button" value="Delete" class="delete" onclick="deletee34()"style="color: red;
    border: 1px solid red;
    border-radius: 5px;"></button>
  </div>
</div>';
}
}
else {

  $datares .='<br><h3>No Home News Find</h3><br>';
}

}

$check  = $db2->query('SELECT * FROM state');
$newscontent ='

<div class="row"> 
<div class="col-md-6">  
Select State
</div>
</div>
<div class="row"> 
<form method="GET"> 
<div class="col-md-6">  
<Select name="language" id="Language">
<option >-- Select State--</option>
';
while($result    = $db2->fetch_object($check)){ 
 $selected = ($result->id == $_GET['language']) ? 'selected="selected"' : '';
$newscontent .='<option   value="'.$result->id.'"   '.$selected.' >' .$result->name. '</option>' ;     }
      
  $newscontent .='</Select>
</div>
<div class="col-md-6">  

<input type="submit" value="submit" name="submit">
</form>
</div>

</div>
<br>';

if(isset($_GET['language'])){
  $state_id=$_GET['language'];
  $check        = $db2->query('select * from state where id="'.$state_id.'" order by id desc ');
while($result1    = $db2->fetch_object($check)){ 
  $State=$result1->name;
}
  //echo $check ['name']; 
}
 $newscontent .='
<h3>Home News Related To '.$State.'</h3><br>

<div class="row">
<div class="col-md-3">
 <b>Sequence</b>
</div>

<div class="col-md-3">
<b>Add News</b></div>


</div>

  <form method="POST" action="">
'.$datares.'


<div class="addrow">
</div>
   <div class="col-md-6"><input type="button" value="Add" id="addnews"></button></div>

<div class="row">
<input type="submit" value="Submit" name="sub"></submit>
</div>
</form>
<script type="text/javascript" src="'.$C->SITE_URL.'/static/js/jquery.js?v=3.6.0"></script>';

$newscontent .='<script type="text/javascript">
$("#addnews").click(function(){
  var url = "'.$C->SITE_URL.'/addinputs"; 
      jQuery.ajax({
        method: "POST",
        url: url
        }).done(function (response) {
      $(".addrow").append(response);
      
      });
});


</script>

<script>
$(".delete").click(function(){
 $(this).closest(".row").remove()});


</script>

';

    $tpl->layout->setVar('main_content',$newscontent);


  
$tpl->display();
?>