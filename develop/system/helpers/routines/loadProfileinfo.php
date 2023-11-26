<?php
	function loadProfileinfo( $tpl, $params )
	{
		global $C, $D,$db2;

		$page 	= & $GLOBALS['page'];
		$user 	= & $GLOBALS['user'];
		$pm 	= & $GLOBALS['plugins_manager'];
		$network 	= & $GLOBALS['network'];
		$D->buzzes = $network->buzzes($D->mytargetuserid);
		$D->following = $network->ifollow($D->mytargetuserid);
		$D->followers = $network->followers($D->mytargetuserid);
		$D->groupcnt =$network->groupcnt($D->mytargetuserid);
		
		$D->userdetails = $network->get_username($D->mytargetuserid);
		$D->userid = $user->id;
		$D->follow =$network->ifollowcheckdata($user->id,$D->mytargetuserid);
		$D->loved =$network->loveornot($user->id,$D->mytargetuserid);
        $D->lovedcnt = $network->lovecnt($D->mytargetuserid);
		$D->datavalue = htmlentities('{"activities_type":"public","activities_id":"'.$D->mytargetuserid.'"}').'' ;
		
        $u = $network->get_username(($D->mytargetuserid));

		if(!empty($u->birthdate)){
		$dob   =explode("-",$u->birthdate);
		$monthNum  = $dob[1];
		$dateObj   = DateTime::createFromFormat('!m', $monthNum);
		$number =$dob[2];
		$ends = array('th','st','nd','rd','th','th','th','th','th','th');
if (($number %100) >= 11 && ($number%100) <= 13)
   $abbreviation = $number. 'th';
else
   $abbreviation = $number. $ends[$number % 10];
	
		$monthName = $dateObj->format('F'); // March
		$D->birthdayday = $abbreviation.' '.$monthName.' is my birthday'; 
		}else{
			$D->birthdayday ='';
			
		}

		$D->tags = $u->tags;
		$catids         =$network->usersintrested($D->mytargetuserid);

		
		
		if(!empty($catids)){
		$areas   =$network->usersintrestedoncatids($catids);
		$D->areas              =array_filter($areas);
		}else{
			$D->areas = array();
		}



		
 
		$tpl->layout->useBlock('profile-info');

		$tpl->layout->block->save( 'main_content', true );
	}