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
	$successmessage = "";
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/admin.php');
	if(!empty($_POST)){
	    $sbversion="SB_APP_VERSION";
	    $sb_force_update = "SB_APP_FORCE_UPDATE";
	    $sbversionvalue=$_POST["version"];
	    $force_update=$_POST["force_update"];
	 
	 
	    $db2->query('REPLACE INTO settings SET word="SB_APP_VERSION", value="'.$db2->e($sbversionvalue).'" ');
	     $db2->query('REPLACE INTO settings SET word="SB_APP_FORCE_UPDATE", value="'.$db2->e($force_update).'" ');
	     	$successmessage = "1";
	   


	}

	
	//require_once( $C->INCPATH.'helpers/func_images.php' );
	  $sbversion = $db2->query('SELECT * FROM `settings` where word="SB_APP_VERSION" ');
	  $sb_version_displayvalue = "";
	  while($sbversionres =$db2->fetch_object($sbversion) ){
	       $sb_version_displayvalue = $sbversionres->value; 
	  }
       $sbversion = $db2->query('SELECT * FROM `settings` where word="SB_APP_FORCE_UPDATE" ');
	  $sb_force_displayvalue = "";
	  while($sbversionres =$db2->fetch_object($sbversion) ){
	       $sb_force_displayvalue = $sbversionres->value; 
	  }
	  $yesvalueselected = 'selected';
	  $novalueselected = "";
	  if($sb_force_displayvalue != "" && $sb_force_displayvalue == "no"){
	        $novalueselected = 'selected';
	       $yesvalueselected = "";
	      
	  }


	$tpl = new template( array('page_title' => $this->lang('admpgtitle_networkbranding', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );
	
	$tpl->initRoutine('AdminLeftMenu', array());
	$tpl->routine->load();
	$successhtml = "";
	if($successmessage == "1"){
	    $successhtml ='<div class="system-message success suc-mes" >
					<strong>Done</strong>Information was saved.
				</div>';
	    
	}

        
        
    $datares    	.='<div class="col-md-6 content-bg">	
	 <div id="content-container">
		
		<div id="subheader">
			
			
		</div>
		<div id="center-container">
		 '.$successhtml.'
	
			
			<h3>App Version</h3>(Update App Version)<form method="POST" action="'.$C->SITE_URL.'/admin/appversion" ><table class="form-container "><tbody>
				<tr>
					<td class="field-title"><label for="network_intro_title">App Version<span style="
    color: red;
">*</span>:</label></td>
					<td><input type="text" required id="version" name="version" autocomplete="off" value="'.$sb_version_displayvalue.'"> </td>
				</tr><tr>
					<td class="field-title"><label for="network_intro_txt">Force Update<span style="
    color: red;
">*</span>:</label></td>
					<td>
					<select name="force_update">
					<option value="yes" '.$yesvalueselected.' >Yes</option>
						<option value="no" '.$novalueselected.'>No</option>
					</select>
					
					</td>
				</tr>
		
				
				<tr>
				<td></td>
				<td><input type="submit"  class="btn blue cron" value="Save"></input></td>
				</tr></tbody></table></form>
			
	</div>
	</div>
	
	';
  $newscontent =''.$datares.'
<script type="text/javascript" src="'.$C->SITE_URL.'/static/js/jquery.js?v=3.6.0"></script>
<script type="text/javascript" src="'.$C->SITE_URL.'/static/js/jquery-ui.js?v=3.6.0"></script>';


$newscontent .='
<style>
.loader {
  border: 12px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 20px;
  height: 20px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

<script type="text/javascript">

    </script>';

		$tpl->layout->setVar('main_content',$newscontent);


	
$tpl->display();
?>