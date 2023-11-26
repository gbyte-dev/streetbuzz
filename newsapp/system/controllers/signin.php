<?php
	if( $this->network->id && $this->user->is_logged ) {
		$this->redirect('dashboard');
	}
	
	$this->load_langfile('outside/global.php');
	$this->load_langfile('outside/signin.php');
	
	$D->submit	= FALSE;
	$D->error	= FALSE;
	$D->errmsg	= '';
	$D->email		= '';
	$D->password	= '';
	$D->rememberme	= FALSE;
	
	if( isset($_POST['email'], $_POST['password']) ) {
		global $plugins_manager;
		$plugins_manager->onPageSubmit();
		if( !$plugins_manager->isValidEventCall() ){
			$error = TRUE;
			$errmsg = $plugins_manager->getEventCallErrorMessage();
		}
		
		$D->submit	= TRUE;
		$D->email		= trim($_POST['email']);
		$D->password	= trim($_POST['password']);
		$D->rememberme	= isset($_POST['rememberme']) && $_POST['rememberme']==1;
		if($D->rememberme !=''){
			$username = "username";
            $usernamevalue = $D->email;
			$userpassword = "userpassword";
            $password = $D->password;
		   setcookie($username, $usernamevalue, time() + (86400 * 30), "/"); // 86400 = 1 day
			setcookie($userpassword, $password, time() + (86400 * 30), "/"); // 86400 = 1 day
			
		}
		if( empty($D->email) || empty($D->password) ) {
			$D->error	= TRUE;
			if(empty($D->email)){
			
			$this->redirect($C->SITE_URL.'home?message=1');
			}
			if( empty($D->password)){
			
			$this->redirect($C->SITE_URL.'home?message=2');
			}
			
		}
		else {
			if( $this->user->is_logged ) {
				$this->user->logout();
			}
			$res	= $this->user->login($D->email, md5($D->password), $D->rememberme);
			if( ! $res ) {
				$D->error	= TRUE;
				if( $this->network->id ) {
					$db2->query('SELECT id FROM users WHERE (email="'.$db2->e($D->email).'" OR username="'.$db2->e($D->email).'") AND password="'.$db2->e(md5($D->password)).'" AND active=0 LIMIT 1');
					if( $db2->num_rows() > 0 ) {
						$D->errmsg	= $this->lang('signin_form_errmsgsusp');
						$this->redirect($C->SITE_URL.'home?message=3');
					}
				}
				if( empty($D->errmsg) ) {
					$D->errmsg	= $this->lang('signin_form_errmsg');
					$this->redirect($C->SITE_URL.'home?message=3');
				}
			}
			else {
							$db2->query('UPDATE users set is_online=1 WHERE  (phone_no ="'.$db2->e($D->email).'" OR email="'.$db2->e($D->email).'" OR username="'.$db2->e($D->email).'") AND password="'.$db2->e(md5($D->password)).'" ');

				 if(!empty($_POST['viewid'])){
				  $this->redirect($C->SITE_URL.'view/post:'.$_POST['viewid']);

			   }else{
					$this->redirect($C->SITE_URL.'dashboard');
  
			   }	
			}
		}
	}

	
	$tpl = new template( array('page_title' => $this->lang('signin_page_title', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'c') );
	if( $this->param('pass') == 'changed' ){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->okMessage($this->lang('signinforg_alldone_ttl'), $this->lang('signinforg_alldone_txt') ) );
	}
	
	
	if( $D->submit && $D->error ){
		$tpl->layout->setVar('main_content_placeholder', $tpl->designer->errorMessage($this->lang('signinforg_err'), $D->errmsg ) );
	}
	
	$tpl->layout->useBlock('login');
	//$tpl->layout->block->setVar('comments_thread_id', $val);
	$tpl->layout->block->save( 'main_content');
	
	
	$tpl->display();
?>