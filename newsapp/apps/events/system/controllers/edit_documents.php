<?php		
	if( !$this->network->id && !$this->params->event) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	$event_src = $db2->query('SELECT * FROM events WHERE id="'.$this->params->event.'" LIMIT 1');
	$event = $db2->fetch_object($event_src);
	if(empty($event)){
		$this->redirect('home');
	}
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/admin.php');
	$this->load_langfile('inside/dashboard.php');
	require $C->INCPATH.'helpers/func_additional.php';	
	
	$submit = FALSE;
	$error = FALSE;
	$errmsg = '';
	if(isset($_POST['submit'])){
		$submit = TRUE;
	}
	if(!empty($_FILES['attachment']['name'])){

		$maxsize    = 2097152;
		$allowedExts = array("pdf", "gif", "jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["attachment"]["name"]);
		$extension = end($temp);
		$file_type = $_FILES["attachment"]["type"]; 
		if ((($file_type == "application/pdf") || ($file_type == "image/gif") || ($file_type == "image/jpeg") || ($file_type== "image/jpg")  || ($file_type == "image/pjpeg")  || ($file_type == "image/x-png")  || ($file_type == "image/png"))  && in_array($extension, $allowedExts)) {
			if ($_FILES["attachment"]["error"] > 0) {
			  $errmsg.="Please upload a valid image or pdf file. <br />";
			  $error = TRUE;
			}
		 }else{
			  $errmsg.="Please upload a valid image or pdf file. <br />";
			  $error = TRUE;
		 }
		
		if(($_FILES['attachment']['size'] >= $maxsize) || ($_FILES["attachment"]["size"] == 0)) {
			$errors[] = 'Attachment too large. Attachment must be less than 2 megabytes.';
		}
		
		if($error === FALSE){
			$file	= (object) $_FILES['attachment'];
			
			$ext	= '';
			$pos	= mb_strpos($file->name, '.');
			if( FALSE !== $pos ) {
				$ext	= '.'.mb_strtolower(mb_substr($file->name,$pos+1));
			}
			$tempfile	= time().rand(1000000,9999999).$ext;
			move_uploaded_file($file->tmp_name, $C->STORAGE_TMP_DIR.$tempfile);
			if( ! file_exists($C->STORAGE_TMP_DIR.$tempfile) ) {
				$data	= FALSE;
				$error = true;
			}else{
				chmod($C->STORAGE_TMP_DIR.$tempfile, 0777);
				$data	= (object) array (
						'tempfile'	=> $tempfile,
						'filename'	=> $file->name,
						'filetype'	=> $file->type,
						'filesize'	=> filesize($C->STORAGE_TMP_DIR.$tempfile),
				);

				$file_type = detectUploadedFileType( $data->filetype );
				$data->detected_type = $file_type;
				
				$answer = array();
				if( $file_type === 'image' ){
					if( !function_exists('create_thumbnail_image') ){
						require $C->INCPATH.'helpers/func_images.php';
					}
					create_thumbnail_image($tempfile);
				}
				$attachment_id = 0;
				$org_file = $C->STORAGE_URL.'tmp/'.$tempfile;
				switch( $file_type ){
					case 'file':
					case 'acrobat':
					case 'word':
					case 'excell':
								$attachment_type = 'file';
								$attachment_url = $C->SITE_URL.'getfile/tmpid:'.$post_temp_id.'/attid:'.$attachment_id;
								break;
					case 'image':
								$attachment_type = 'image';
								$attachment_url = $C->STORAGE_TMP_URL.'thumb_'.$tempfile;
								break;
				}
				$this->db2->query('INSERT INTO `event_attachemnts` (event_id, user_id, attachment_type, filename, file_size, file_type, link, thumb_link) 
							VALUES ("'.$this->params->event.'","'.$this->user->id.'", "Admin", "'.$file->name.'", "'.$data->filesize.'","'.$file_type.'","'.$org_file.'","'.$attachment_url.'")');
			}
		}
	}else{
		$error = TRUE;
		$errmsg='Select a valid file to upload';
	}
		
	$tpl = new template( array('page_title' => $this->lang('admpgtitle_administrators', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );

	if( ($submit && !$error) || $this->param('msg') == 'grpsaved' ){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->okMessage($this->lang('admtrms_ok_ttl'), $this->lang('admadm_frm_ok_txt') ) );
	}else if( $submit && $error ){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->errorMessage('Error', $errmsg) );
	}

	
	if(empty($this->params->group)){
		$tpl->initRoutine('DashboardLeftMenu', array());
		$tpl->routine->load();
	}
	
	//$tpl->designer->createMenuLink( array('url'=>'plugin/blog/bpost',  'title'=>'Blog') ) );

	$tpl->layout->useBlock('edit_document', 'events');
	
	$data_attach = '<div>';
	$evt_attach= $db2->query('SELECT * FROM event_attachemnts WHERE event_id="'.$event->id.'"');
	while ($attchement = $db2->fetch_object($evt_attach)) {
		$window_pop = "MyWindow_new123=window.open('$attchement->link','MyWindowz','width=600,height=500'); return false;";
		$data_attach.='<div> <a href="'.$attchement->link.'" style="list-style:none; cursor:pointer; color:blue; float:left;" onclick="'.$window_pop.'" >'.$attchement->filename.'</li> <a class="event_attach_remove" rel="'.$attchement->id.'" href="'. $C->SITE_URL .'plugin/events/remove_event/attach_id:'.$attchement->id.'">&nbsp;&nbsp;&nbsp;&nbsp;Remove</a></div> <br />';
	}
	$data_attach .= '</div>';
	$tpl->layout->block->setVar('exiting_files', $data_attach);	

	$table = new tableCreator();
	$table->form_enctype = 'enctype="multipart/form-data"';
	$rows = array(
		$table->hiddenField( 'event_id', $event->id),
		$table->fileField( 'Attachement: ', 'attachment', '' ),
		$table->submitButton( 'submit', $this->lang('admgnrl_frm_sbm') )
	);
	
	$tpl->layout->block->setVar('form_data', $table->createTableInput( $rows ) );	
	$tpl->layout->block->save( 'main_content', true );	
	
	$tpl->display();
?>