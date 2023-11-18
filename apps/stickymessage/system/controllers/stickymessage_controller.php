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

$plugin_name = 'stickymessage';
$bck_color = '#535353';
$text_color = '#F7F7F7';
if(!empty($_POST) && isset($_POST['activate'])){
	$bck_color = (!empty($_POST['mycolor2'])?$_POST['mycolor2']:'#535353');
	$text_color = (!empty($_POST['mycolor1'])?$_POST['mycolor1']:'#F7F7F7');
	$val = json_encode(
				array('stickymsg'=>htmlspecialchars($_POST['stickymsg_text'], ENT_QUOTES),
						'backcolor' =>$bck_color,
						'textcolor' =>$text_color
				)
			);
	
	$this->db2->query("REPLACE settings set word='STICKYMESSAGE', value='" . $val . "'");
}

if(!empty($_POST) && isset($_POST['deactivate'])){
	$this->db2->query("DELETE FROM settings WHERE word='STICKYMESSAGE'");
}


$this->load_langfile('inside/global.php');
$this->load_langfile('inside/admin.php');

$tpl = new template( array('page_title' => $this->lang('admpgtitle_administrators', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );

$res = $this->db2->query("SELECT * FROM settings WHERE word='STICKYMESSAGE'");
$obj = $this->db2->fetch_object($res);

$value='';
$status = "<div style='color: #3db015; font-size: 20px;'>Sticky message is not set.</div>";
if($obj){
	$status = "<div style='color: #ff0000; font-size: 20px; font-weight: bold'>Sticky message is set and visible for your users</div>";
	$value = json_decode($obj->value);
	$bck_color = $value->backcolor;
	$text_color = $value->textcolor;
}

$content = $status. "
<div style='border: 1px solid #efefef; padding: 10px; margin-top: 10px'>
<table>
	<tr>
		<td>
			<form method='post' action='?'>
				<div style='color: #666666; margin-bottom: 12px;'>Write the text for your sticky message</div>
				<input id='stickymessage_text' type='text' name='stickymsg_text' value='" . (($obj)?$value->stickymsg:'') . "' style='width: 400px;'><br><br>
				<table style='border: 0px'>
					<tr>
						<td style='color: #666666;'>Choose text color</td><td><input id='mycolor1' name='mycolor1' type='hidden' value='" . $text_color . "' class='iColorPicker' /></td>
					</tr>
					<tr>
						<td style='color: #666666; width: 150px;'>Choose background color</td><td><input id='mycolor2' name='mycolor2' type='hidden' value='" . $bck_color . "' class='iColorPicker' /></td>
					</tr>
				</table><br>
			
				<input type='submit' name='activate' value='Set sticky message'>
				<input type='submit' name='deactivate' value='Unset sticky message'>
			</form>
		</td>
		<td style='padding-left: 50px;'>
			<div style='color: #666666'>See how your sticky message will look like:</div>
			<div id='stickymessage_bck' class='stickymessage' style='background: " .  $bck_color . "'>
				<div style='color: " . $text_color . "'>		
					<div id='stickymessage_preview' class='stickymessage_text' color='" . (($obj)?$value->textcolor:'') . "'>" . (($obj)?$value->stickymsg:'') . "</div>
				</div>
			</div>
		</td>
	</tr>
</table>
</div>";

$content .= '<script type="text/javascript">stkmsg_site_url="'.$C->SITE_URL.'"</script>';
$tpl->layout->setVar('main_content', $content);

$tpl->initRoutine('AdminLeftMenu', array());
$tpl->routine->load();
$html = $tpl->display();