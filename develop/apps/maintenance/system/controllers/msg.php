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

if(!empty($_POST) && isset($_POST['activate'])){
	$this->db2->query("REPLACE settings set word='MAINTENANCE', value='" . $_POST['maint_text'] . "'");
}

if(!empty($_POST) && isset($_POST['deactivate'])){
	$this->db2->query("DELETE FROM settings WHERE word='MAINTENANCE'");
}


$this->load_langfile('inside/global.php');
$this->load_langfile('inside/admin.php');

$tpl = new template( array('page_title' => $this->lang('admpgtitle_administrators', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );

$res = $this->db2->query("SELECT * FROM settings WHERE word='MAINTENANCE'");
$obj = $db2->fetch_object($res);

$maint_value='';
$status = "<div style='color: #3db015; font-size: 20px;'>You are in a working mode ( maintenance mode is deactivated )</div>";
if($obj){
	$status = "<div style='color: #ff0000; font-size: 20px; font-weight: bold'>Maintenance mode is activated</div>";
	$maint_value = $obj->value;
}
$content = $status. "
<form method='post' action='?'>
Write the text which will informe your users that you set the networks in maintenance mode.<br><br>
<input type='text' name='maint_text' value='" . $maint_value . "' style='width: 500px'><br><br>
<input type='submit' name='activate' value='Activate maintenance mode'>
<input type='submit' name='deactivate' value='Deactivate maintenance mode'>
</form>
";

$tpl->layout->setVar('main_content', $content);

$tpl->initRoutine('AdminLeftMenu', array());
$tpl->routine->load();
$html = $tpl->display();