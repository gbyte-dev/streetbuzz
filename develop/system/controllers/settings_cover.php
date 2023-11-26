<?php

if (!$this->network->id) {
    $this->redirect('home');
}
if (!$this->user->is_logged) {
    $this->redirect('signin');
}
$C->DEF_COVER_USER='default_cover.png';
$this->load_langfile('inside/global.php');
$this->load_langfile('inside/settings.php');



global $plugins_manager;

$u    = &$this->user;

$submit    = FALSE;
$error    = FALSE;
$errmsg    = '';
$send_notif    = FALSE;
$width_bound = 100;//750;
$height_bound = 250;//400;
//print_r($C);
if (isset($_FILES['profile_cover']) && is_uploaded_file($_FILES['profile_cover']['tmp_name'])) {
    $submit    = TRUE;
    $plugins_manager->onUserSettingsSubmit();
    if (!$plugins_manager->isValidEventCall()) {
        $error = TRUE;
        $errmsg = $plugins_manager->getEventCallErrorMessage();
    }
    if (!$error) {
        $f    = (object) $_FILES['profile_cover'];
        list($w, $h, $tp) = getimagesize($f->tmp_name);
                          

        if ($w == 0 || $h == 0) {
            $error    = TRUE;
            $errmsg    = $this->lang('st_avatar_err_invalidfile');
        } elseif ($tp != IMAGETYPE_GIF && $tp != IMAGETYPE_JPEG && $tp != IMAGETYPE_PNG) {
            $error    = TRUE;
            $errmsg    = $this->lang('st_avatar_err_invalidformat');
        } elseif ($w < $width_bound || $h < $height_bound) {
            $error    = TRUE;
            $errmsg    = $this->lang('st_cover_err_toosmall');
        }
          

        if (!$error) {
           // $cvr    = $this->user->info->cover;
           /* if ($cvr == $C->DEF_COVER_USER) {
                
                //die('ddddddddd');
                
                rm($C->STORAGE_DIR . 'avatars/' . $cvr);
                rm($C->STORAGE_DIR . 'avatars/thumbs1/' . $cvr);
                rm($C->STORAGE_DIR . 'avatars/thumbs2/' . $cvr);
                rm($C->STORAGE_DIR . 'avatars/thumbs3/' . $cvr);
                rm($C->STORAGE_DIR . 'avatars/thumbs4/' . $cvr);
                rm($C->STORAGE_DIR . 'avatars/thumbs5/' . $cvr);
            } else { */
                 //die('tttttttttt');
                $cvr    = time() . rand(100000, 999999) . '.png';
           // }
            if ($cvr != $C->DEF_COVER_USER) {
                $res    = copy_cover($f->tmp_name, $cvr);
              // $res    = copy_avatar($f->tmp_name, $cvr);

                if (!$res) {
                    $error    = TRUE;
                    $errmsg    = $this->lang('st_avatar_err_cantcopy');
                }
            } else {
                $cvr = 'default_cover.png';
            }
            
            $db2->query('UPDATE users SET cover="' . $db2->e($cvr) . '" WHERE id="' . $this->user->id . '" LIMIT 1');
            $this->network->get_user_by_id($this->user->id, TRUE);
            $this->network->get_online_users(TRUE);
            $send_notif    = TRUE;
            $this->user->info->cover    = $cvr;
        }
    }
} elseif ($this->param('del') == 'current') {
    	    echo '<script>alert("Successfully Deleted")</script>'; 
    $old    = $this->user->info->cover;
    if ($old != $C->DEF_COVER_USER) {
        rm($C->STORAGE_DIR . 'avatars/' . $old);
        rm($C->STORAGE_DIR . 'avatars/thumbs1/' . $old);
        rm($C->STORAGE_DIR . 'avatars/thumbs2/' . $old);
        rm($C->STORAGE_DIR . 'avatars/thumbs3/' . $old);
        rm($C->STORAGE_DIR . 'avatars/thumbs4/' . $old);
        rm($C->STORAGE_DIR . 'avatars/thumbs5/' . $old);
        $db2->query('UPDATE users SET cover="default_cover.png" WHERE id="' . $this->user->id . '" LIMIT 1');
        $this->user->info->cover    = $C->DEF_COVER_USER;
        $this->network->get_user_by_id($this->user->id, TRUE);
        $msg    = 'deleted';
        $send_notif    = TRUE;
    }
} else if (isset($_POST['sbm'])) {
    $submit = TRUE;
}


if ($send_notif) {
    $notif = new notifier();
    $notif->set_notification_obj('user', $this->user->id);
    $notif->onChangeAvatar();
}
list($currw, $currh) = getimagesize($C->STORAGE_DIR . 'avatars/' . $u->info->cover);


$tpl = new template(array('page_title' => $this->lang('settings_avatar_pagetitle', array('#SITE_TITLE#' => $C->SITE_TITLE)), 'header_page_layout' => 'sc'));

$tpl->initRoutine('SettingsLeftMenu', array());
$tpl->routine->load();

if ($submit && $error) {
    $tpl->layout->setVar('main_content_placeholder', $tpl->designer->errorMessage($this->lang('st_avatat_err'), $errmsg));
} else if ($submit && !$error) {
    $tpl->layout->setVar('main_content_placeholder', $tpl->designer->okMessage($this->lang('st_cover_ok'), $this->lang('st_cover_okmsg')));
}
$tpl->layout->useBlock('empty');


$table = new tableCreator();

$rows = array(
    $table->textField($this->lang('st_avatar_current_picture'), '<img src="' . $C->STORAGE_URL . 'avatars/thumbs2/' . $u->info->cover . '" alt="" border="0" /><br /><br />'),
    $table->fileField($this->lang('st_avatar_change_picture'), 'profile_cover', ''),
    $table->textField('', '<span style="color:#CB4335; font-size:11px;">JPEG, GIF or PNG; 350x750px or larger.</span><br /><br />'),
    $table->textField('Remove Current Cover Photo', '<a href="' . $C->SITE_URL . 'settings/cover/del:current">' . $this->lang('st_avatar_upload_or_delete') . ' <span class="glyphicon glyphicon-trash"></span></a>'),
    $table->textField('', '<br />'),
    $table->submitButton('sbm', $this->lang('st_avatar_uploadbtn'))
);

$table->form_title = $this->lang('settings_avatar_ttl2');
$table->form_enctype = 'enctype="multipart/form-data"';

$tpl->layout->block->setVar('empty_block_content', $table->createTableInput($rows));
$tpl->layout->block->save('main_content');




$tpl->display();
