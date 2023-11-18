<?php

if( !$this->network->id ) {
	$this->redirect('home');
}
if( !$this->user->is_logged ) {
	$this->redirect('signin');
}
if($_GET['action']=="comment")
{
	
	$db2		= & $this->network->db2;
	$db_user_id		= intval($this->user->id);
	$db_message		= $db2->escape($_POST['message']);
	$db_date		= time();
	$db_ip_addr		= ip2long($_SERVER['REMOTE_ADDR']);
	$db_attached	= '0';
	$db_date		= time();
	$db_ip_addr		= ip2long($_SERVER['REMOTE_ADDR']);
	$parentid=$_GET['posts_id'];
	$message=$_POST['message'];
	$db_value=0;
	$db2->query('INSERT INTO posts SET api_id="'.$db_value.'", user_id="'.$db_user_id.'",  message="'.$db_message.'",mentioned="'.$db_value.'", posttags="'.$db_value.'", attached="'.$db_value.'", date="'.$db_date.'", date_lastcomment="'.$db_date.'", ip_addr="'.$db_ip_addr.'",parent_id="'.$parentid.'" ');
	$pid = $db2->insert_id();
	$db2->query('update posts set date="'.$db_date.'" where id="'.$parentid.'"');
	$this->redirect($C->SITE_URL);
}
else
{
$db2->query('SELECT 1 FROM users WHERE id="'.$this->user->id.'"  LIMIT 1');//AND is_network_admin=1
if( 0 == $db2->num_rows() ) {
	$this->redirect('dashboard');
}

if($_GET['action']=="users")
{

	$tpl = new template( array('page_title' => $this->lang('admpgtitle_administrators', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'c'),false);
}
else if($_GET['action']<>"users" && $_GET['action']<>"answer")
{
	$tpl = new template( array('page_title' => $this->lang('admpgtitle_administrators', array('#SITE_TITLE#'=>$C->SITE_TITLE)), 'header_page_layout'=>'sc') );
}



$this->load_langfile('inside/global.php');
$this->load_langfile('inside/admin.php');
//print_r($C);
$plugin_name = 'poll';	
$main_content = "";
if($_GET['action']=="answer")
	{
		
		$db2 = & $this->network->db2;
		$userid=$user->id;
		$answerid=$_GET['answerid'];
		$pollid=$_GET['poll_id'];
		$this->db2->query("INSERT INTO post_poll_votes SET 
							POLL_ID = '".$pollid."', 
							ANSWER_ID = '".$answerid."', 
							VOTER_USER_ID = '".$userid."'", FALSE);
		//echo $query="delete from post_poll_votes";
		//$this->db2->query($query,true);
		$poll_id = $this->db2->insert_id();
		$this->redirect($C->SITE_URL);


	}
else if($_GET['action']=="download")
{

		$pollid=$_GET['poll_id'];
		$host="localhost";
		$uname="purpleco_sb";
		$pass="sb_123";
		$database = "purpleco_sb"; 

		$connection=mysql_connect($host,$uname,$pass); 

		echo mysql_error();

		//or die("Database Connection Failed");
		$selectdb=mysql_select_db($database) or 
		die("Database could not be selected"); 
		$result=mysql_select_db($database)
		or die("database cannot be selected <br>");
		$output = "";
		$table = ""; // Enter Your Table Name 
		$sql = mysql_query("select * from post_poll_votes where POLL_ID='".$pollid."'");
		$columns_total = mysql_num_fields($sql);

		// Get The Field Name

		for ($i = 0; $i < $columns_total; $i++) {
		$heading = mysql_field_name($sql, $i);
		$output .= '"'.$heading.'",';
		}
		$output .="\n";

		// Get Records from the table

		while ($row = mysql_fetch_array($sql)) {
		for ($i = 0; $i < $columns_total; $i++) {
		$output .='"'.$row["$i"].'",';
		}
		$output .="\n";
		}

		// Download the file

		$filename = "POLL-".$pollid.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);

		echo $output;
		exit;

}
else
{
if(isset($_GET['action']))
{	

	$action = trim($_GET['action']);
	$poll_id = isset($_GET['poll_id']) ? (int)($_GET['poll_id']) : 0;
	
	if ($action == "delete" && $poll_id > 0)
	{
		$this->db2->query("DELETE FROM polls_user_votes WHERE poll_id='".$poll_id."'");
		$this->db2->query("DELETE FROM polls_answers WHERE poll_id='".$poll_id."'");
		$this->db2->query("DELETE FROM polls WHERE poll_id='".$poll_id."'");
		
		$this->redirect($C->SITE_URL."plugin/".$plugin_name."/admin");
	}
	
	if ($action == "activate" && $poll_id > 0)
	{
		//$this->db2->query("UPDATE polls SET poll_is_active='0'");
		$this->db2->query("UPDATE polls SET poll_is_active='1' WHERE poll_id='".$poll_id."'");
		
		$this->redirect($C->SITE_URL."plugin/".$plugin_name."/admin");
	}
	
	if ($action == "deactivate" && $poll_id > 0)
	{
		$this->db2->query("UPDATE polls SET poll_is_active=0 WHERE poll_id='".$poll_id."'");
		
		$this->redirect($C->SITE_URL."plugin/".$plugin_name."/admin");	
	}
	
	if ($action == "add" || $action == "edit" || $action == "users") // || $action == "add_answer"
	{
		$question = "";
		$answers = array(0 => "", 1 => "", 2 => "", 3 => "", 4 => "");
		$allowUserAnswer = false;
	
		if (isset($_POST['save'])) 
		{
			//print_r($_POST); exit;

			$errorMsg = array();			
			$question = isset($_POST['question']) ? htmlspecialchars( trim($_POST['question']) ) : "";
			$answers = isset($_POST['answer']) ? $_POST['answer'] : array();
			$allowUserAnswer = isset($_POST['allowUserAnswer']) ? $_POST['allowUserAnswer'] : false;
			
			if ($question == "")
			{
				$errorMsg[] = "Please enter a valid 'Qiestion'.";
			}
			if (empty($answers) || count($answers) < 2) 
			{
				$errorMsg[] = "Please enter at least two answers.";
			}
			else
			{
				$notEmpty = 0;
				foreach($answers as $key => $val) 
				{
					if (!empty($val)) $notEmpty++;
					if ($notEmpty == 2) break;
				}
				if ($notEmpty < 2)
					$errorMsg[] = "Please enter at least two answers.";					
				
				// $i = 0;
				// foreach($answers as $key => $val) 
				// {
					// $i++;
					// if (empty(trim($val))) 
						// $errorMsg[] = "Please enter a valid 'Answer ".($i)."'.";
				// }
			}
			
			if (empty($errorMsg))
			{
				if ($poll_id > 0)  
				{
					// update poll
					$query = "UPDATE polls SET 
								poll_question = '".$this->db2->e($question)."', 
								poll_allow_user_answer = '".($allowUserAnswer ? 1 : 0)."'
							WHERE poll_id='".$poll_id."'";
					$this->db2->query($query, FALSE);
					
					$query2 = "SELECT poll_answer_id FROM polls_answers WHERE poll_id='".$poll_id."'";					
					$res2 = $this->db2->query($query2);
					$num2 = $res2->num_rows;						
					if ($num2 > 0)
					{
						// update old answers
						while($obj2 = $this->db2->fetch_object($res2))
						{
							$key = $obj2->poll_answer_id;
							if (array_key_exists($key, $answers))
							{
								$answer = trim($answers[$key]);
								if (!empty($answer))
								{
									$query3 = "UPDATE polls_answers 
									SET answer = '".$this->db2->e($answer)."'
									WHERE poll_answer_id = '".$key."' AND poll_id='".$poll_id."'";								
									$this->db2->query($query3, FALSE);
								}
								else
								{
									// delete empty answers
									$this->db2->query("DELETE FROM polls_user_votes WHERE poll_answer_id='".$key."' AND poll_id='".$poll_id."'");									
									$this->db2->query("DELETE FROM polls_answers WHERE poll_answer_id='".$key."' AND poll_id='".$poll_id."'");
								}								
								unset($answers[$key]);
							}
							else
							{							
								$this->db2->query("DELETE FROM polls_user_votes WHERE poll_answer_id='".$key."' AND poll_id='".$poll_id."'", FALSE);
								$this->db2->query("DELETE FROM polls_answers WHERE poll_answer_id='".$key."' AND poll_id='".$poll_id."'", FALSE);
							}
						}
					}
					// insert new answers
					foreach($answers as $key => $val)
					{
						$answer = trim($val);
						if (!empty($answer))
						{
							$this->db2->query("INSERT INTO polls_answers SET 
							poll_id = '".$poll_id."', 
							answer = '".$this->db2->e($answer)."', 
							votes = '0'", FALSE);	
						}
					}
				}
				else
				{					
					// insert
					
					$db2		= & $this->network->db2;
					$is_private	= 'TRUE';
					$db_api_id		= '0';
					$db_user_id		= intval($this->user->id);
					$db_group_id	= '0';
					$db_to_user		= '0';
					$db_mentioned	= '0';
					$db_attached	= '0'; //change here
					$db_posttags	= '0';
					$db_date		= time();
					$db_ip_addr		= ip2long($_SERVER['REMOTE_ADDR']);
					$question=$_POST['question'];
					$name=$_POST['grouptxt'];
					$data['group']=explode("@",$name);
					//print_r($data['group']);exit;
					$count=count($data['group']);
					if($count=="2")
					{

						$name1=$data['group'][1];
						$query = "SELECT id from groups WHERE groupname = '".$name1."'";
						$r = $this->db2->query($query);
						while($result=$this->db2->fetch_object($r))
						{
							$data['id']=$result;
						}	
						$groupid=$data['id']->id;
						$db2->query('INSERT INTO posts SET api_id="'.$db_api_id.'", user_id="'.$db_user_id.'", group_id="'.$groupid.'", mentioned="'.$db_mentioned.'", posttags="'.$db_posttags.'", attached="'.$db_attached.'", date="'.$db_date.'", date_lastcomment="'.$db_date.'", ip_addr="'.$db_ip_addr.'" ,group_name="'.$name.'" ');
						
					}
					else
					{
						$db2->query('INSERT INTO posts SET api_id="'.$db_api_id.'", user_id="'.$db_user_id.'", group_id="'.$db_group_id.'", mentioned="'.$db_mentioned.'", posttags="'.$db_posttags.'", attached="'.$db_attached.'", date="'.$db_date.'", date_lastcomment="'.$db_date.'", ip_addr="'.$db_ip_addr.'" ,group_name="'.$name.'" ');
					}
					
					$pid = $db2->insert_id();
					$query = "INSERT INTO polls SET 
											poll_date = '".time()."',  
											poll_question = '".$this->db2->e($question)."', 
											poll_is_active = '0',
											poll_allow_user_answer = '0'";
					$this->db2->query($query, FALSE);
					
					//$db2->query('INSERT INTO event_posts SET event_id="'.$id.'", post_id="'.$pid.'", created = "'.date('Y-m-d H:i:s').'"');

					$db2->query('INSERT INTO post_userbox SET user_id="'.$this->user->id.'", post_id="'.$pid.'"');
					//$db2->query('INSERT INTO posts_attachments SET post_id="'.$pid.'", type="link",data="'.$db2->escape(serialize($answer)).'"');
					
					$query = "INSERT INTO polls SET 
							poll_date = '".time()."',  
							poll_question = '".$this->db2->e($question)."', 
							poll_is_active = '0',
							poll_allow_user_answer = '".($allowUserAnswer ? 1 : 0)."',
							posts_id = '".$pid."'";
					$this->db2->query($query, FALSE);
					$poll_id = $this->db2->insert_id();
					foreach($answers as $key => $val) 
					{
						$query2 = "INSERT INTO polls_answers SET 
								poll_id = '".$poll_id."',
								answer = '".$this->db2->e($val)."', 
								votes = '0'";
						$this->db2->query($query2, FALSE);
					}
					$q =array();

					//insert to followers data
					if($this->user->info->is_posts_protected == 0){
						$u	= $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers;
					}else{
						$u	= array_intersect_key($this->network->get_user_follows($this->user->id, FALSE, 'hefollows')->follow_users, $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers);
					}
							
					$u	= $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers;
					foreach($u as $k=>$v) {
						$q[]	= '("'.$k.'", "'.$pid.'")';
					}
					
					if( $group_id ) {
						$u	= $this->network->get_group_members($group_id);
						if($u) {
							foreach($u as $k=>$v) {
								$z[]	= '("'.$k.'", "'.$pid.'")';
							}
						}
						$q	= array_unique($q);
						$q = array_intersect($q,$z);					
					}
					
					if( count($q) > 0 ) { 
						$q	= implode(', ', $q);
						$db2->query('INSERT INTO post_userbox (user_id, post_id) VALUES '.$q);
					}

				}
			if(isset($_GET['from']))
			{
				$this->redirect($C->SITE_URL);
			}	
			else
			{
				$this->redirect($C->SITE_URL."plugin/".$plugin_name."/admin");
			}
			}
		}
		
		// Get data
		if (empty($errorMsg) && $poll_id > 0)
		{
			$query = "	SELECT poll_id, poll_date, poll_question, poll_is_active, poll_allow_user_answer						
						FROM polls p					
						WHERE p.poll_id = '$poll_id'";
			$res = $this->db2->query($query);
			$num = $res->num_rows;
			if ($num > 0)
			{
				$obj = $this->db2->fetch_object($res);
				$question = $obj->poll_question;				
				$allowUserAnswer = $obj->poll_allow_user_answer;
				$answers = array();
				
				$query2 = "	SELECT poll_answer_id, poll_id, answer, votes				
							FROM polls_answers
							WHERE poll_id = '$obj->poll_id' ORDER BY poll_answer_id";
				$res2 = $this->db2->query($query2);
				$num2 = $res2->num_rows;						
				if ($num2 > 0)
				{					
					while($obj2 = $this->db2->fetch_object($res2))
					{
						$answers[$obj2->poll_answer_id] = $obj2->answer;
					}					
				}
			}
		}
		if($_GET['action']=="add")
		{
		$header = "<div class='header'>
					<h1>".($action == "add" ? "ADD":$action == "users" ? "ADD" : "EDIT") ." POLL</h1>
					<a href='".$C->SITE_URL."plugin/".$plugin_name."/admin"."' class='button right'>Back</a>
					<div class='clear'></div>
				</div>";
		}
		else
		{
			if(isset($_GET['option']))
			{
				$header = "<div class='header'>
					<h1>".($action == "add" ? "ADD":$action == "users" ? "ADD" : "EDIT") ." POLL</h1>
					<button type='button' id='removecommentpoll' class='button right' style='color:white;background:red; padding:6px 8px; border:1px solid white;'>Remove Poll</button>
					<div class='clear'></div>
					</div>";
			}
			else
			{
				$header = "
					<div class='header'>
					<h1>".($action == "add" ? "ADD":$action == "users" ? "ADD" : "EDIT") ." POLL</h1>
					<button type='button' id='removepoll' class='button right' style='margin:10px 10px;'>Remove Poll</button>
					<div class='clear'></div>
				</div>";
			}
			$header.="
				<script>
						$('#removepoll').click(function(){
								$('.characters-counter').css('display','block');
						  		$('#poll').css('display','none');
						  		$('#post').show();
						  		$('.status-btn').css('display','block');
						
					  	});
						$('#removecommentpoll').click(function(){
								$('.comment-post').show();
						  		$('.comment').show();
		 						$('.commentpoll').css('display','none');
						 
					  	});
				</script>

					";
		}		
		
		// Errors
		$error = "";		
		if (!empty($errorMsg))
		{
			$error = "<div class='system-message error'><ul class='poll_error'>";
			foreach($errorMsg as $key => $val)
			{
				$error .= "<li>".$val."</li>";
			}
			$error .= "</ul></div>";
		}		
		
		$form = "<div>
					<div class='form-group'>
						<label>Question:</label>
						<input type='text' ID='question' name='question' value='".$question."' class='form-control' required='required'>
					</div>
					<div class='answers'>";
		
					$i = 1;
					foreach($answers as $key => $val) 
					{			
						$form .= "<div class='form-group'>
									<label>Answer ".($i++).":</label>
									<input type='text' name='answer[".$key."]' id='answer".$key."' value='".$val."' class='form-control' >
								</div>";
					}
		$form.="<div id='grtxt' style='display:none'>
		<div class='field-title'></div>
			<div><input type='text' class='htmlarea textarea group' name='grouptxt' id='grouptxt' placeholder='Group' name='street_group' /></div>
		</div>
		<div id='urtxt' style='display:none'>
			<div class='field-title'></div>
			<div><input type='text' class='group' id='usertxt' name='usertxt' placeholder='Users' name='street_user'  /></div>
		</div>
		<div>
			<div class='field-title'><input type='button' id='grp' value='+Group' />
			<input type='button' id='user' value='+Add Users' />
		</div>
		<script>
			$('#grp').click(function(){
				 $('#grtxt').show();
				  $('#share').val('group');
				 
			 });
			$('#user').click(function(){
				 
				  $('#share').val('user');
				 $('#urtxt').show();
			 });
		</script>
		<script src='http://localhost/sharetronix/static/js/htmlarea_event.js?v=3.6.0'></script>
		<script src='http://localhost/sharetronix/static/js/htmlarea_user.js?v=3.6.0'></script>
		<script src='http://localhost/sharetronix/static/js/htmlarea_hash.js?v=3.6.0'></script>
		";

		if($_GET['action']=="add")
		{
		$form .= "	</div>
					<div class='form-group pt10'>
						<div class='left'>
							<input type='checkbox' name='allowUserAnswer' ".($allowUserAnswer ? "checked='checked'" : "")." value='1'> Users are be able to add their own answer
						</div>
						<div class='right'>
							<a href='?action=add_answer' data-name='add-new-answer'>Add new answer</a>
						</div>
						<div class='clear'></div>
					</div>
				</div>";
		}	
		// else if($_GET['action']=="users")
		// {
		// 	$form .= "	</div>
		// 			<div class='form-group pt10'>
		// 				<div class='right'>
		// 					<a id='addmore'>Add new answer</a>
		// 				</div>
		// 				<div class='clear'></div>
		// 			</div>
		// 		</div>
		// 		<script>
		// 		$('#addmore').click(function(){
		// 			var data='<div class='form-group'><label>Answer:</label><input type='text' name='answer[]' class='form-control' ></div>';
		// 			$('.answers').html(data);
		// 		});
		// 		</script>
		// 		";
		// }

		$actions = "<div class='actions'>
						<input type='submit' id='save' name='save' value='Buzz' class='status-btn post-btn btn blue small' style='padding: 0px 10px;color:white;' disabled='disabled'/>
						<div class='clear'></div>
						
					</div>
					<script>
					$('#answer1').keyup(function(){
						if($('#question').val()=='' || $('#answer0').val()=='' || $('#answer1').val()=='' )
						{
							$('#save').css('disabled','disabled');
						}
						else
						{
							$('#save').removeAttr('disabled');
							$('#save').css('enabled','enabled');
						}
					});
					$('#answer0').keyup(function(){
						if($('#question').val()=='' || $('#answer0').val()=='' || $('#answer1').val()=='' )
						{
							$('#save').css('disabled','disabled');
						}
						else
						{
							$('#save').removeAttr('disabled');
							$('#save').css('enabled','enabled');
						}
					});	
					$('#question').keyup(function(){
						if($('#question').val()=='' || $('#answer0').val()=='' || $('#answer1').val()=='' )
						{
							$('#save').css('disabled','disabled');
						}
						else
						{
							$('#save').removeAttr('disabled');
							$('#save').css('enabled','enabled');
						}
					});
					</script>
					";

				
		if($_GET['action']=="users")
		{

			echo $main_content = "<div class='poll-admin'>
							<form method='post' action='".$C->SITE_URL."plugin/poll/admin?action=$action&poll_id=$poll_id&from=users' >" . //novalidate
								$header . $error . "<div class='edit'>" . $form . $actions . "</div>
							</form>
						</div>";
		}
		else
		{
			$main_content = "<div class='poll-admin'>
								<form method='post' action='?action=$action&poll_id=$poll_id' >" . //novalidate
									$header . $error . "<div class='edit'>" . $form . $actions . "</div>
								</form>

							</div>";	
		}

	}
	
	// Details
	if ($action == "details" && $poll_id > 0)
	{
		$query = "	SELECT poll_id, poll_date, poll_question, poll_is_active, poll_allow_user_answer						
					FROM polls p					
					WHERE p.poll_id = '$poll_id'";
		$res = $this->db2->query($query);
		$num = $res->num_rows;
		if ($num > 0)
		{
			$obj = $this->db2->fetch_object($res);
			
			$header = "<div class='header'>
							<h1>".$obj->poll_question."</h1>
							<a href='".$C->SITE_URL."plugin/".$plugin_name."/admin"."' class='button right'>Back</a>
							<div class='clear'></div>
						</div>";					
		
			$query = "	SELECT puv.poll_id, puv.user_id, puv.vote_date, puv.poll_answer_id, pa.answer,
							u.email, u.username, u.fullname
						FROM polls_user_votes puv
							INNER JOIN polls_answers pa ON pa.poll_answer_id = puv.poll_answer_id
							INNER JOIN users u ON u.id = puv.user_id
						WHERE puv.poll_id = '$poll_id'
						ORDER BY puv.vote_date";
			$res = $this->db2->query($query);
			$num = $res->num_rows;
			$userList = "";
			if ($num > 0)
			{
				$userList = "<table class='table'>
								<tr>
									<th>User</th>
									<th>Email</th>
									<th>Answer</th>
									<th>Date</th>
								</tr>";
			
				while($obj = $this->db2->fetch_object($res))
				{	
					$userList .= "<tr>
									<td>".$obj->username."</td>
									<td>".$obj->email."</td>
									<td>".$obj->answer."</td>
									<td>".date("d F Y", $obj->vote_date)."</td>
								</tr>";
				}
				
				$userList .= "</table>";
			}
			else
			{
				$userList = "<div>There is no items.</div>";
			}
			
			$main_content = "<div class='poll-admin'>" . $header . $userList . "</div>";		
		}
	}	
}
else
{
	// View
	$header = "<div class='header'>
					<h1>MANAGE POLLS</h1>
					<div class='clear'></div>
				</div>";
				
	$query = "	SELECT poll_id, poll_date, poll_question, poll_is_active, poll_allow_user_answer,
					(SELECT SUM(votes) FROM polls_answers WHERE poll_id = p.poll_id) as voted_count
				FROM polls p					
				ORDER BY poll_date";
	$res = $this->db2->query($query);
	$num = $res->num_rows;
	$pollList = "";
	if ($num > 0)
	{
		$pollList = "<table class='table'>";
		
		while($obj = $this->db2->fetch_object($res))
		{	
			$pollList .= "<tr>
							<td>
								<div data-name='poll-question'>
									<a href='' class='left icon icon-right'></a>
									<a href=''>".$obj->poll_question."</a>
								</div>"; // ".($obj->poll_is_active ? "icon-down" : "icon-right")."
								
								$query2 = "	SELECT poll_answer_id, poll_id, answer, votes				
											FROM polls_answers
											WHERE poll_id = '$obj->poll_id' ORDER BY poll_answer_id";
									
								$res2 = $this->db2->query($query2);
								$num2 = $res2->num_rows;
										
								if ($num2 > 0)
								{
									$pollList .= "<div data-name='poll-votes' class='poll-votes display-none'>"; // ($obj->poll_is_active ? "" : "display-none")
									while($obj2 = $this->db2->fetch_object($res2))
									{
										$percent = ($obj2->votes > 0 && $obj->voted_count > 0) ? 
											(int)($obj2->votes / $obj->voted_count * 100) : 0;
										$percentFormat = number_format($percent); //, 2, ',', ' ');
										$pollList .= "	<div class='answer'>
															<div>".$obj2->answer."</div>
															<div>
																<div class='percent'>".$percentFormat."%</div>
																<div class='bar-wrap'>
																	<div class='bar' style='width:".$percentFormat."%;'>&nbsp;</div>
																</div>
																<div class='clear'></div>
															</div>
														</div>";
									}
									$pollList .= "<div>".$obj->voted_count." Votes</div>";
									$pollList .= "</div>";
								}
								
			$pollList .= "	</td>
							<td><a href='?action=details&poll_id=".$obj->poll_id."'>View poll details</a></td>
							<td><a href='?action=edit&poll_id=".$obj->poll_id."'>Edit</a></td>
							<td><a href='?action=".($obj->poll_is_active ? "deactivate" : "activate")."&poll_id=".$obj->poll_id."'>".
								($obj->poll_is_active ? "Deactivate" : "Activate")."</a></td>
							<td><a href='?action=delete&poll_id=".$obj->poll_id."' data-name='poll-delete'>
									<img src='".$C->SITE_URL."apps/".$plugin_name."/static/images/icon-delete.png' /></a>
							</td>
						</tr>";
		}
		
		$pollList .= "</table>";
	}
	else
	{
		$pollList = "<div>There is no items.</div>";
	}
			
	$actions = "<div class='actions'>
					<a href='".$C->SITE_URL."plugin/".$plugin_name."/admin?action=add"."' class='button right'>+ Add</a>
					<div class='clear'></div>
				</div>";
	
	$main_content = "<div class='poll-admin'><form method='post' action='?'>" . $header . $pollList . $actions . "</form></div>";
}

$tpl->layout->setVar('main_content', $main_content);

$tpl->initRoutine('AdminLeftMenu', array());
$tpl->routine->load();
$tpl->display();
}
}