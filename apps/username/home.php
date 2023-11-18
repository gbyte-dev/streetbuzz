<?
if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/settings.php');
require_once $C->INCPATH.'helpers/func_cache-users.php';


$checkbg = $this->db2->query('select name from plugins where name = "bg"');
$checkbg2 = $db2->num_rows($checkbg);
$user = $this->user->id;

if($checkbg2 == 0){} else {
$bgg = $this->db2->query('select pbg from users where id = "'.$user.'"');
while($obj = $this->db2->fetch_object($bgg)){

				
	$bg = $obj->pbg;
					 } 


?>
<style type="text/css">#bg { background-color:#000;
background:url("<? echo $C->STORAGE_URL .'pbg/'.$bg; ?>");
background-attachment:fixed; height:100%; min-height:100%; font-family:arial, helvetica, tahoma, verdana, "lucida grande", sans-serif; font-size:13px; line-height:1.3; color:#000; background-repeat: repeat; background-color:#000; }</style>
 
<?
}
$result = $this->db2->query('select username from users where id = "'.$user.'"');
while($obj = $this->db2->fetch_object($result)){

				
	$user2 = $obj->username;
					 }  

$tempuser = $_POST['user'];
$result2 = $this->db2->query('select username from users where username = "'.$tempuser.'"');
$num = $db2->num_rows($result2);
if($tempuser == $user2){
$aviso = "Alterado com sucesso";
} else if($num >= 1){
$aviso = "Usuario ja existente";
} else if($tempuser == "" and isset($_POST["ok"] )) {
$aviso = "Usuario invalido, nao salvo";
}
else if((strlen($tempuser)) <= 5 and isset($_POST["ok"] )){
$aviso = "usuario muito curto, no minimo 6 caracteres";
}
else if((strlen($tempuser)) >= 19 and isset($_POST["ok"] )){
$aviso = "usuario muito longo, no maximo 18 caracteres";
}else if($num == 0 and isset($_POST["ok"] ))
{

$this->db2->query('UPDATE users SET username = "'.$tempuser.'" WHERE id = "'.$user.'"');
$aviso = "Alterado com sucesso";
$user2 = $tempuser;

$this->network->get_user_by_id($this->user->id, TRUE);
				
				$this->user->info->username	= $tempuser;
}


$title = "Change username";
$tpl = new template( array('page_title' => ($title), 'header_page_layout'=>'sc') );
	
	$tpl->initRoutine('SettingsLeftMenu', array());
	$tpl->routine->load();
	
	
	
	$table = new tableCreator();
	
	$rows = array(
			
			$table->inputField( $this->lang('st_profile_name'), 'user', $user2 ),
                        $table->textField( '', $aviso ),
			$table->submitButton( 'ok', Change )
	);

	$table->form_title = "Change your username";
	$table->form_enctype = 'enctype="multipart/form-data"';
	
	$tpl->layout->setVar('main_content', $table->createTableInput( $rows ));
	
	$tpl->display();

?>

