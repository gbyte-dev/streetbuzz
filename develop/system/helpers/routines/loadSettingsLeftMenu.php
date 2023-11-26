<?php
	function loadSettingsLeftMenu( $tpl, $params )
	{
		global $C, $D;
		$page 	= & $GLOBALS['page'];
		$user 	= & $GLOBALS['user'];
		$pm 	= & $GLOBALS['plugins_manager'];
		

		$html .= 'hi';

		$tab = isset($page->request[1])? $page->request[1] : '';
		
		$menuii = array( 	array('url' => 'settings/profile', 		'css_class' => (($tab === 'profile')? ' selected' : ''), 		'title' => $page->lang('settings_menu_profile')),
						array('url' => 'settings/contacts', 	'css_class' => (($tab === 'contacts')? ' selected' : ''), 		'title' => $page->lang('settings_menu_contacts') ),
						array('url' => 'settings/avatar', 		'css_class' => (($tab === 'avatar')? ' selected' : ''), 		'title' => $page->lang('settings_menu_avatar') ),
						array('url' => 'settings/cover', 		'css_class' => (($tab === 'cover')? ' selected' : ''), 		'title' => $page->lang('settings_menu_avatar') ),
						array('url' => 'settings/email', 		'css_class' => (($tab === 'email')? ' selected' : ''), 			'title' => $page->lang('settings_menu_email') ),
						array('url' => 'settings/password', 	'css_class' => (($tab === 'password')? ' selected' : ''), 		'title' => $page->lang('settings_menu_password') ),
						array('url' => 'settings/system', 		'css_class' => (($tab === 'system')? ' selected' : ''), 		'title' => $page->lang('settings_menu_system') ),
						array('url' => 'settings/deleteuser', 	'css_class' => (($tab === 'deleteuser')? ' selected' : ''), 	'title' => $page->lang('settings_menu_delaccount') ),
						array('url' => 'settings/notifications','css_class' => (($tab === 'notifications')? ' selected' : ''), 	'title' => $page->lang('settings_menu_notif') ),
						array('url' => 'settings/privacy', 		'css_class' => (($tab === 'privacy')? ' selected' : ''), 		'title' => $page->lang('settings_menu_privacy') )
		);
		$menu = array();
		$profilecss = (($tab == 'profile'))?'settings-nav-mousehover':'';
		$contactcss = (($tab == 'contacts'))?'settings-nav-mousehover':'';
		$avatarcss = (($tab === 'avatar'))?'settings-nav-mousehover':'';
		$covercss = (($tab === 'cover'))?'settings-nav-mousehover':'';
		$emailcss = (($tab == 'email'))?'settings-nav-mousehover':'';
		$passwordcss = (($tab == 'password'))?'settings-nav-mousehover':'';
        $systemcss = (($tab == 'system'))?'settings-nav-mousehover':'';
	    $deletecss = (($tab == 'deleteuser'))?'settings-nav-mousehover':'';
	    $notificationcss = (($tab == 'notifications'))?'settings-nav-mousehover':'';
		$userchanecss = (($tab == ''))?'settings-nav-mousehover':'';

  $privacycss = (($tab == 'privacy'))?'settings-nav-mousehover':'';

		$tpl->layout->setVar('left_content_placeholder', $tpl->designer->createInfoBlock('

    <!-- Start : Settings -->
    <div class="col-md-12 box" style="background-color:#fff; margin-bottom:30px; padding:0; margin-top:-15px;">    
    
    <div class="box-inner" style="padding:0">

        <div class="box-title" style="padding:10px; margin-bottom:0px;">
        Settings
        </div>

      <div class="box-content">


        <div class="box-sub-desc settings-nav">
        <ul>
        
 <a href="'.$C->SITE_URL.'settings/profile"><li class="'.$profilecss.'"><span class="glyphicon glyphicon-circle-arrow-right "></span> Profile Information</li>      </a>    
    
    <a href="'.$C->SITE_URL.'settings/contacts"><li class="'.$contactcss.'"><span class="glyphicon glyphicon-circle-arrow-right "></span> Contacts Details</li></a>

    <a href="'.$C->SITE_URL.'settings/avatar"><li class="'.$avatarcss.'"><span class="glyphicon glyphicon-circle-arrow-right "></span> Profile Picture</li></a>
    
    <a href="'.$C->SITE_URL.'settings/cover"><li class="'.$covercss.'"><span class="glyphicon glyphicon-circle-arrow-right "></span> Cover Picture</li></a>

    <a href="'.$C->SITE_URL.'settings/email"><li class="'.$emailcss.'"><span class="glyphicon glyphicon-circle-arrow-right "></span> Change E-mail</li></a>    
    
    <a href="'.$C->SITE_URL.'settings/password"><li class="'.$passwordcss.'"><span class="glyphicon glyphicon-circle-arrow-right "></span> Change Password</li></a>

    <a href="'.$C->SITE_URL.'settings/system"><li class="'.$systemcss.'"><span class="glyphicon glyphicon-circle-arrow-right "></span> Account Settings</li></a>

    <a href="'.$C->SITE_URL.'settings/deleteuser"><li class="'.$deletecss.'"><span class="glyphicon glyphicon-circle-arrow-right "></span> Delete Account</li></a>    
    
    <a href="'.$C->SITE_URL.'settings/notifications"><li class="'.$notificationcss.'"><span class="glyphicon glyphicon-circle-arrow-right "></span> Activity Notifications</li></a>

    <a href="'.$C->SITE_URL.'settings/privacy"><li class="'.$privacycss.'"><span class="glyphicon glyphicon-circle-arrow-right "></span> Account Privacy</li></a>  

    <a href="'.$C->SITE_URL.'plugin/username/changeuser"><li class="'.$userchanecss.'"><span class="glyphicon glyphicon-circle-arrow-right"></span> Change Username</li></a>  
    
    
        </ul>
        </div>

        </div>
   
    </div>
     <!-- End : Settings -->

			', 


			$tpl->designer->createMenu('feed-navigation', $menu)) );

		
	}


