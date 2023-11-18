<?php
$aviso="";
if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/settings.php');
require_once $C->INCPATH.'helpers/func_cache-users.php';



$user1 = $this->user->id;

$result = $this->db2->query('select username from users where id = "'.$user1.'"');
while($obj = $this->db2->fetch_object($result)){

				
	$user2 = $obj->username;
					 }  
$spacesarray = array("!", "@","#", "$","%","^","&","*","(",")","-",":",";","<",">","?",";",",","+","=","{","}",".","0");
$usersplit = str_split($_POST['user']);
$finalsplit     =array_intersect($spacesarray,$usersplit);
$finalspli                =array_filter($finalsplit);
if(isset($_POST['user'])){
$tempuser = $_POST['user'];

$result2 = $this->db2->query('select username from users where username = "'.$tempuser.'"');
$num = $this->db2->num_rows($result2);
if($tempuser == $user2){
$aviso = "Success";
} else if($num >= 1){
$aviso = "Existing User";
} else if($tempuser == "" and isset($_POST["ok"] )) {
$aviso = "Invalid User, unsaved";
}
else if((strlen($tempuser)) <= 5 and isset($_POST["ok"] )){
$aviso = "very short user at least 6 characters";
}
else if((strlen($tempuser)) >= 19 and isset($_POST["ok"] )){
$aviso = "Username too long, maximum 18 characters";
}elseif((preg_match('/\s/',$tempuser)) > 0){
	$aviso = "Sorry spaces not allowed!";

	
}elseif(count($finalspli) >0 ){
	$aviso = "Sorry special characters not allowed!";
}else if($num == 0 and isset($_POST["ok"] ))
{

$this->db2->query('UPDATE users SET username = "'.$tempuser.'" WHERE id = "'.$user1.'"');
$aviso = "Success";
$user2 = $tempuser;

$this->network->get_user_by_id($this->user->id, TRUE);
				
				$this->user->info->username	= $tempuser;
}
}

$title = "Change username";
$tpl = new template( array('page_title' => ($title), 'header_page_layout'=>'sc') );
	
	$tpl->initRoutine('SettingsLeftMenu', array()); 
	$tpl->routine->load();
	$tpl->layout->useBlock('empty');


	
	
	$table = new tableCreator();
	
	$rows = array(
			
			$table->inputField( 'Username', 'user', $user2 ),
                        $table->textField( '', $aviso ),
			$table->submitButton( 'ok', 'Change')
	);

	$table->form_title = "Change your username";
	$table->form_enctype = 'enctype="multipart/form-data"';
	
	$tpl->layout->block->setVar('empty_block_content', $table->createTableInput($rows));

	$tpl->layout->block->save('main_content');
	
	$tpl->display();

?>