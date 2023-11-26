<?php
	function loadEditProfile( $tpl, $params )
	{
		global $C, $D,$db2;
		$page 	= & $GLOBALS['page'];
		$user 	= & $GLOBALS['user'];
		$pm 	= & $GLOBALS['plugins_manager'];
		
		
		$message = substr($_SERVER['REQUEST_URI'], -1); // returns "s"

		$tpl->layout->useBlock('edit_profile');
		  $res  =$db2->query('select * from users  where id="'.$user->id.'"');
		  $updateres      =$db2->fetch_object($res);
		  $D->fullname  = $updateres->fullname;
		   $D->location  = $updateres->location;
		  $D->avatar  = $updateres->avatar;
		  $D->refer_type  = $updateres->refer_type;
		  $D->bdate_d		= intval(substr($updateres->birthdate,8,2));
		  $D->bdate_m		= intval(substr($updateres->birthdate,5,2));
		  $D->bdate_y		= intval(substr($updateres->birthdate,0,4));
		  if($message =="0"){
			  $unsuccess='<div class="system-message error">
					<strong>Filesize</strong>00x200px or larger
               </div>';
			  
		  }else{
			   $unsuccess='';
			  
		  }
		  if($message =="1"){
			 $success = '<div class="system-message success">
					<strong>Done</strong>Information was saved.
               </div>';
			  
		  }else{
			   $success = '';
			  
		  }
		  $username ='<input type="text" class="form-control" id="profile_name" name="profile_name" value="'.$updateres->fullname.'" maxlength="50" autocomplete="off">';
		  $location ='<input type="text" class="form-control" id="profile_name" name="location_name" value="'.$updateres->location.'" maxlength="50" autocomplete="off">';
		  $birthdayday ='';
		  if($D->bdate_d ==0){
			  $birthdayday .='<option value=""></option>';
			  
		  }
		  for($i=1;$i<=31;$i++){
			   if($i==$D->bdate_d){
				   $selected ="selected";
			   }else{
				    $selected ="";
				   
			   }
			   $birthdayday .='<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
			  
		  }
		  if( $D->bdate_m ==1){
			  $selct1 = "selected";
			  
		  }else{
			  $selct1 = "";
			  
		  }
		  if( $D->bdate_m ==2){
			  $selct2 = "selected";
			  
		  }else{
			  $selct2 = "";
			  
		  }
		  if( $D->bdate_m ==3){
			  $selct3 = "selected";
			  
		  }else{
			  $selct3 = "";
			  
		  }
		  if( $D->bdate_m ==4){
			  $selct4 = "selected";
			  
		  }else{
			  $selct4 = "";
			  
		  }
		  if( $D->bdate_m ==5){
			  $selct5 = "selected";
			  
		  }else{
			  $selct5 = "";
			  
		  }
		   if( $D->bdate_m ==6){
			  $selct6 = "selected";
			  
		  }else{
			  $selct6 = "";
			  
		  }
		   if( $D->bdate_m ==7){
			  $selct7 = "selected";
			  
		  }else{
			  $selct7 = "";
			  
		  }
		   if( $D->bdate_m ==8){
			  $selct8 = "selected";
			  
		  }else{
			  $selct8 = "";
			  
		  }
		   if( $D->bdate_m ==9){
			  $selct9 = "selected";
			  
		  }else{
			  $selct9 = "";
			  
		  }
		   if( $D->bdate_m ==10){
			  $selct10 = "selected";
			  
		  }else{
			  $selct10 = "";
			  
		  }
		   if( $D->bdate_m ==11){
			  $selct11 = "selected";
			  
		  }else{
			  $selct11 = "";
			  
		  }
		   if( $D->bdate_m ==12){
			  $selct12 = "selected";
			  
		  }else{
			   $selct12 = "";
			  
		  }
		  
		  $birthdayyear ='';
		  if($D->bdate_y ==''){
			   $birthdayyear .='<option value=""></option>';
			  
		  }
		  $year =date("Y");
				for($j=$year;$j>=1950;$j--){
			   if($j==$D->bdate_y){
				   $selected ="selected";
			   }else{
				    $selected ="";
				   
			   }
			   $birthdayyear .='<option value="'.$j.'" '.$selected.'>'.$j.'</option>';
			  
		  }
		  $month='';
		   if($D->bdate_m ==''){
			   $month .='<option value=""></option>';
			  
		  }
		  $month .='
		  		<option value="1" '.$selct1.'>January</option><option value="2" '.$selct2.'>February</option><option value="3" '.$selct3.'>March</option><option value="4" '.$selct4.'>April</option><option value="5" '.$selct5.'>May</option><option value="6" '.$selct6.'>June</option><option value="7" '.$selct7.'>July</option><option value="8" '.$selct8.'>August</option><option value="9" '.$selct9.'>September</option><option value="10" '.$selct10.'>October</option><option value="11" '.$selct11.'>November</option><option value="12" '.$selct2.'>December</option>

		  ';
		  $img ='<img src="'.$C->SITE_URL.'storage/avatars/thumbs1/'.$updateres->avatar.' " alt="" border="0" id="blah"/>';
         if($updateres->refer_type =="Person"){
			 $selected ="selected";
		 }else{
			  $selected ='';
		 }
      if($updateres->refer_type =="Business"){
			 $selected ="selected";
		 }else{
			  $selected ='';
		 }
       if($updateres->refer_type =="Brand"){
			 $selected ="selected";
		 }else{
			 $selected ='';
			 
		 }	
       $refer ='';		 
        if($updateres->refer_type ==''){
			$refer .='<option value="" >Please select Referral type</option>';
			
		}
		$refer .='
	  <option value="Person"  '.$selected.'>Person</option>
      <option value="Business"  '.$selected.'>Business</option>
      <option value="Brand"  '.$selected.'>Brand</option>
	  ';


		$tpl->layout->block->setVar('fullname',$username);
		$tpl->layout->block->setVar('location',$location);
		$tpl->layout->block->setVar('birthdayday',$birthdayday);
		$tpl->layout->block->setVar('month',$month);
		$tpl->layout->block->setVar('birthdayyear',$birthdayyear);
	    $tpl->layout->block->setVar('img',$img);
	    $tpl->layout->block->setVar('refer',$refer);
		$tpl->layout->block->setVar('success',$success);
		$tpl->layout->block->setVar('unsuccess',$unsuccess);


		$tpl->layout->block->save( 'main_content', true );
	}