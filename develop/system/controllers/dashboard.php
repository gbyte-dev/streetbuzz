<?php

ERROR_REPORTING(0);


	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if(!$this->user->is_logged){
		$this->redirect('signin');
	}
  	 $folow_res= $db2->query('SELECT whom FROM  users_followed as uf where who="'.$this->user->id.'"' );
	 	
	while($fetchres = $db2->fetch_object($folow_res)){
		$res[] = $fetchres->whom;
	}
	if(!empty($res)){
	$fetchres   =implode(',',$res);
	}else{
		$fetchres ="' '";
		
		
	}
	
	
	$fetch  =$db2->query('SELECT u.id,u.username,u.fullname,u.avatar,u.about_me FROM  street_suggestion as st
	                          INNER JOIN users AS u  ON st.user_id=u.id
                              where st.user_id NOT IN('.$fetchres.')							  
							  group by u.id
	                          order by rand() limit 3 ');

	while($fetchresasw[] = $db2->fetch_object($fetch)){
	}
    $D->follow = ($fetchresasw);

	$eventres           = $db2->query('SELECT id,event_name FROM   events  where admin_id="'.$this->user->id.'"  order by id desc limit 3' );
	while($eventfetchre[] = $db2->fetch_object($eventres)){
	}
	$D->eventfetchre  = $eventfetchre;

	$eventacceptres           = $db2->query('SELECT e.id,e.event_name,ep.post_id FROM `post_userbox` as pu 
	                inner join event_posts as ep ON ep.post_id = pu.post_id 
					inner join events as e on ep.event_id = e.id 
					WHERE pu.user_id = "'.$this->user->id.'"  and (pu.event_status = 1 ) order by pu.post_id desc LIMIT 3' );
	while($eventacceptfetchre[] = $db2->fetch_object($eventacceptres)){
	}
	$D->eventacceptfetchre  = $eventacceptfetchre;



   //My polls tab
   $pollmyres           = $db2->query('SELECT ps.poll_id,ps.poll_question,p.id as post_id,p.date as postdate  FROM `posts` as p inner join polls as ps ON p.id = ps.posts_id 
		inner join users as u ON u.id=p.user_id
		

          WHERE p.user_id = "'.$this->user->id.'" group by ps.poll_id order by ps.poll_id desc LIMIT 3
				   

	   ');
     while($pollmyresfetch[] = $db2->fetch_object($pollmyres)){
	 }
	 	$D->pollmyresfetchresults  = $pollmyresfetch;

//my Response tab
	$pollmyresponse           = $db2->query('SELECT ps.poll_id,ps.poll_question,p.id as post_id,p.date as postdate  FROM `posts` as p inner join polls as ps ON p.id = ps.posts_id 
		inner join  post_poll_votes as ppu ON ppu.POLL_ID=ps.poll_id
		inner join users as u on u.id = ppu.VOTER_USER_ID

          WHERE ppu.VOTER_USER_ID	 = "'.$this->user->id.'" group by ps.poll_id order by ps.poll_id desc LIMIT 3
				   

	   ');
	    while($pollmyresponsefetch[] = $db2->fetch_object($pollmyresponse)){
		}
		$D->pollmyresponsefetchresults  = $pollmyresponsefetch;



	if(isset($_GET['pid']) && $_GET['pid']!=""){
	$host="localhost";
	$uname="sb_test";
	$pass="Wslu@697";
	$database = "sb_test"; 
    $connection=mysqli_connect($host,$uname,$pass,$database); 
   

	//or die("Database Connection Failed");
	$selectdb=mysqli_select_db($connection,$database) or 
	die("Database could not be selected"); 
	$output = "";
	$table = ""; // Enter Your Table Name 
	$sql = mysqli_query($connection,'SELECT u.username AS Username,ev.event_name AS Eventname,ev.start_date AS Startdate,ev.start_time AS Starttime,ev.end_date AS Enddate,ev.end_time AS Endtime,pu.event_status AS Status,ev.address AS Address,ev.event_description AS Description FROM  post_userbox AS pu
	                   LEFT JOIN users AS u ON u.id = pu.user_id
	                   LEFT JOIN event_posts AS ep ON ep.post_id = pu.post_id
					   INNER JOIN events AS ev ON ev.id = ep.event_id
	                  WHERE pu.post_id="'.$_GET['pid'].'" AND pu.user_id !="'.$this->user->id.'" AND ( pu.event_status = "1" OR pu.status = "2") ');
			
					 

	$columns_total = mysqli_num_fields($sql);


	// Get The Field Name
	$headingsd =array("Username","Eventname","Startdate","Starttime","Enddate","Endtime","Status","Address","Description");

	for ($i = 0; $i < $columns_total; $i++) {
	$heading = $headingsd[$i];
	$output .= '"'.$heading.'",';
	}
	$output .="\n";


	// Get Records from the table

	while ($row = mysqli_fetch_array($sql)) {
	
	for ($i = 0; $i < $columns_total; $i++) {
		if($i == 6){
			  if($row[$i] == "1"){
				$row[$i] = "Accepted";  
			  }
			  if($row[$i] == "3"){
				$row[$i] = "Rejected";  
			  }
		}
	$output .='"'.$row["$i"].'",';
	}
	$output .="\n";
	}
	// Download the file

	$filename = "event.csv";
	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename='.$filename);

	echo $output;
	exit;
	}



	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/dashboard.php');

	
	$this->network->reset_dashboard_tabstate($this->user->id, $this->param('tab')? $this->param('tab') : 'all');


	//TEMPLATE CODE START
	$tpl = new template( array('page_title' => $this->lang('dashboard_page_title', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'scs') );
	
	$tpl->initRoutine('DashboardLeftMenu', array());
	$tpl->routine->load();

	
	if(isset($_REQUEST['postid'])){
		  $postid      =$_REQUEST['postid'];
		  $status      =$_REQUEST['status'];
		  $attachid      =$_REQUEST['attachid'];
		  $db2->query('UPDATE posts_attachments SET event_status="'.$status.'"  WHERE id="'.$attachid.'" LIMIT 1');
		  $db2->query('UPDATE post_userbox SET event_status="'.$status.'"  WHERE post_id="'.$postid.'" AND user_id="'.$this->user->id.'" LIMIT 1');
		  

		  if($status ==3){
			 // $r = $db2->query('SELECT event_id FROM event_posts WHERE post_id="'.$postid.'" LIMIT 1');
			  //$o = $this->db2->fetch_object($r);
			  //$db2->query('UPDATE events SET status="'.$status.'"  WHERE id="'.$o->event_id.'" LIMIT 1');
			  $db2->query('UPDATE post_userbox SET event_status="'.$status.'",status="'.$status.'"  WHERE post_id="'.$postid.'" AND user_id="'.$this->user->id.'" LIMIT 1');
			  
			  $status  =$status;
		  }else{
			  $status  =$status;
			  
		  }
		  echo $status;
          
		  exit;

		
	}

	if(isset($this->params->g)){
		$g	= $this->network->get_group_by_name($this->params->g);
		
		if( ! $g ) {
			$this->redirect('dashboard');
		}
		if( $g->is_private && !$this->user->is_logged ) {
			$this->redirect('home');
		}
		if( $g->is_private && !$this->user->info->is_network_admin ) {
			$u	= $this->network->get_group_invited_members($g->id);
			if( !$u || !in_array(intval($this->user->id),$u) ) {
				$this->redirect('dashboard');
			}
		}
		
		$this->params->group = $g->id;
	}


	$tpl->initRoutine('Postform', array());

	$tpl->routine->load();
/*
echo '<pre>';
print_r($tpl);
die;
*/
	$activity = activityFactory::select('dashboard');
	$activity->setTemplate( $tpl );

	$result = $activity->loadPosts();
 
 //print_r($result[2]); die;

	if( isset($result[1]) && $result[1] > 0 ){
		$tpl->layout->useBlock('activity-show-more');
		$tpl->layout->setVar('activities_pager_value', htmlentities('{"activities_type":"dashboard","activities_id":"'.$result[1].'","activities_tab":"'.($this->param('tab')? $this->param('tab') : 'all').'"}'));
		$tpl->layout->block->save('activity_container_show_more');
	}
    	$newdate = $this->network->lastactivitydate($result[0]);

	
	if( $this->param('tab') !== 'group' && isset($result[0]) && $result[0] > 0 ){
		$table = new tableCreator();
		$tpl->layout->setVar('main_content_bottom', 
													$table->hiddenField( 'activities_type', 'dashboard' ) .
													$table->hiddenField( 'last_activity', intval($result[0]) ) .
							 						$table->hiddenField( 'last_activity_date', $newdate->date_lastcomment ) .

													$table->hiddenField( 'activities_tab', $this->param('tab')? $this->param('tab') : 'all' )
		);
	} elseif( isset($g) && $g ) {
		$tpl->layout->setVar('in_group', 'in '.$g->title);
	}
	
	$tpl->display(); 
	//TEMPLATE CODE END
?>