<?php
class poll extends plugin
{
	public function onPageLoad()
	{
		GLOBAL $C, $page;
		$plugin_name = 'poll';	
		//print_r($C);
		
		if($this->getCurrentController() != 'home' && $this->getCurrentController() !='signin' && $this->user->is_logged)
		{
			if( substr($this->getCurrentController(), 0, 6) == 'admin/' || ($page->plugin_name && $page->plugin_name=='poll') )
			{
				$designer = pageDesignerFactory::select();
				$this->setVar( 'administration_left_menu', $designer->createMenuLink( array('url'=>'plugin/poll/admin',  'title'=>'Poll') ) );
			}			
		
			if( substr($this->getcurrentcontroller(), 0, 9) == 'dashboard')
			{
				$poll = "";
				
				if (isset($_POST['vote'])) 
				{
					$poll_id = isset($_GET['poll_id']) ? (int)($_GET['poll_id']) : 0;
					$poll_answer_id = isset($_POST['poll_answer_id']) ? (int)$_POST['poll_answer_id'] : false;					
					$user_answer = isset($_POST['user_answer']) ? htmlspecialchars( trim($_POST['user_answer']) ) : false;

					$query = "SELECT poll_answer_id
								FROM polls_user_votes 										
								WHERE poll_id = '".$poll_id."' AND user_id = '".$this->user->id."'";
					$res = $this->db2->query($query);
					$num = $res->num_rows;
					if ($num == 0)
					{
						$errorMsg = array();
						
						if (!isset($_POST['user_answer']) && empty($poll_answer_id))
						{
							$errorMsg[] = "Please select an answer.";
						}
						
						if (isset($_POST['user_answer']) && empty($poll_answer_id) && empty($user_answer))
						{
							$errorMsg[] = "Please select an answer or enter your own.";
						}
						
						if (empty($errorMsg))
						{
							if (!empty($poll_answer_id) && empty($user_answer))
							{
								$query = "UPDATE polls_answers 
											SET votes = votes + 1
											WHERE poll_answer_id = '".$poll_answer_id."' AND poll_id = '".$poll_id."'";
								$this->db2->query($query, FALSE);
							}
							
							if (!empty($user_answer))
							{
								$query = "SELECT poll_answer_id
											FROM polls_answers 										
											WHERE poll_id = '".$poll_id."' AND answer = '".$this->db2->e($user_answer)."'";
								$res = $this->db2->query($query);
								$num = $res->num_rows;
								if ($num > 0)
								{
									$obj = $this->db2->fetch_object($res);
									$poll_answer_id = $obj->poll_answer_id;
									
									$query = "UPDATE polls_answers 
												SET votes = votes + 1
												WHERE poll_answer_id = '".$poll_answer_id."' AND poll_id = '".$poll_id."'";
									$this->db2->query($query, FALSE);
								}
								else
								{
									$query = "INSERT INTO polls_answers SET 
											poll_id = '".$poll_id."',
											answer = '".$this->db2->e($user_answer)."',
											votes = '1'";
									$this->db2->query($query, FALSE);
									
									$poll_answer_id = $this->db2->insert_id();
								}
							}
										
							$query = "INSERT INTO polls_user_votes SET 
										poll_id = '".$poll_id."', 
										user_id = '".$this->user->id."', 
										vote_date = '".time()."',
										poll_answer_id = '".$poll_answer_id."'";
							$this->db2->query($query, FALSE); // date("Y-m-d H:i:s")						
							
							$successMsg[] = "You have voted successfully.";
						}
					}
				}
				
				$query = "	SELECT p.poll_id, p.poll_date, p.poll_question, p.poll_is_active, p.poll_allow_user_answer, puv.user_id,
								(SELECT SUM(votes) FROM polls_answers WHERE poll_id = p.poll_id) as voted_count
							FROM polls p 
								LEFT JOIN polls_user_votes puv ON puv.poll_id = p.poll_id AND puv.user_id = '".$this->user->id."'
							WHERE poll_is_active = '1'";
				$res = $this->db2->query($query);
				$num = $res->num_rows;
				$pollList = "";
				if ($num > 0)
				{	
					// Errors
					$message = "";
					if (!empty($errorMsg))
					{
						$message = "<div class='system-message error' data-name='poll-votes-error'><ul class='poll_error'>";
						foreach($errorMsg as $key => $val)
						{
							$message .= "<li>".$val."</li>";
						}
						$message .= "</ul></div>";
					}
					
					// Success
					if (!empty($successMsg))
					{
						$message = "<div class='system-message success' data-name='poll-votes-success'><ul class='poll_success'>";
						foreach($successMsg as $key => $val)
						{
							$message .= "<li>".$val."</li>";
						}
						$message .= "</ul></div>";
					}					
										
					$poll = "<div class='section-container'>
								<h3 class='section-title'>Poll</h3>";
								
					$isFirst = false;
					
					while($obj = $this->db2->fetch_object($res))
					{
						$poll .= "<div class='poll-app' data-poll-id='".$obj->poll_id."'>";
						
						$isFirst = !$isFirst && !isset($obj->user_id);
						$isOpen = (!empty($message) && $poll_id == $obj->poll_id) || ($isFirst && !isset($obj->user_id));
						
						$poll .= "<div class='poll-question-wrap'>
									<a href='' class='poll-question'>".$obj->poll_question."</a>
									<a href='' class='right icon ".($isOpen ? "icon-down" : "icon-right")."'></a>
									<div class='clear'></div>
								</div>";						
						
						$poll .= "<div class='".($isOpen ? "" : "display-none")."'>";
						
						$poll .= "<div class='message'>".(!empty($message) && $poll_id == $obj->poll_id ? $message : "")."</div>";
						
						$query2 = "	SELECT poll_answer_id, poll_id, answer, votes				
									FROM polls_answers
									WHERE poll_id = '$obj->poll_id' ORDER BY poll_answer_id";
									
						$arrResult = $this->db2->fetch_all($query2);
								
						if (count($arrResult) > 0)
						{
							if (!isset($obj->user_id))
							{
								// Vote 							
								$poll .= "<div class='poll-votes-wrap' data-name='poll-votes-wrap'>";							
								$poll .= "<form method='post' action='?poll_id=$obj->poll_id' data-id='".$obj->poll_id."'>";							
								$poll .= "<ul class='answers'>";
								foreach($arrResult as $key => $obj2)
								{
									$poll .= "<li><input type='radio' name='poll_answer_id' value='".$obj2->poll_answer_id."'>".$obj2->answer."</li>";
								}
								if ($obj->poll_allow_user_answer) 
								{
									$poll .=  "<li>
													<input type='radio' name='poll_answer_id' value='0' style='margin-right: 1px;'>
													<input type='text' id='user_answer' name='user_answer' placeholder='Other' disabled='disabled' class='txt'/>
												</li>";
								}
								$poll .= "</ul>";
								$poll .= "<div class='actions'>";								
								$poll .= "<div>
											<input type='submit' name='vote' value='Vote' class='button left'/>
											<a href='' class='button right' data-name='poll-btn-results' data-id='".$obj->poll_id."'>See the results</a>
											<div class='clear'></div>
										</div>						
									</div>";
								$poll .= "</form>";
								$poll .= "</div>";							
							}
							
							// Results
							$poll .= "<div class='poll-results-wrap ".(!isset($obj->user_id) ? "display-none" : "")."' data-name='poll-results-wrap'>";
							foreach($arrResult as $key => $obj2)
							{
								$percent = ($obj2->votes > 0 && $obj->voted_count > 0) ? 
									(int)($obj2->votes / $obj->voted_count * 100) : 0;
								$percentFormat = number_format($percent); //, 2, ',', ' ');
								$poll .= "<div class='answer'>
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
							$poll .= "<div>".$obj->voted_count." Votes</div>";								
								
							$poll .= "<div class='actions'>".
										(!isset($obj->user_id) ? "<a href='' class='button left' data-name='poll-btn-back' data-id='".$obj->poll_id."'>Back</a>" : "").									
										"<div class='clear'></div>
									</div>
								</div>";
						}
						$poll .= "</div>";						
						$poll .= "</div>";
					}
					
					$poll .= "</div>";
				}
				
				$this->setVar( 'left_content_bottom', $poll);		
			}
		}
	}	
}

?>