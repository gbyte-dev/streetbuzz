<?php
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/admin.php');
	$success= '';
	if(!empty($this->params->event)){
		
		$event_src = $db2->query('SELECT events.*, users.is_network_admin FROM ( events, users ) WHERE events.id="'.$this->params->event.'" AND users.id = "'.(int)$this->user->id.'" LIMIT 1');
		$event = $db2->fetch_object($event_src);
		$evebtdata = $db2->query('SELECT post_id  FROM event_posts WHERE event_id="'.$event->id.'" order by id desc LIMIT 1');
				while($ev = $this->db2->fetch_object($evebtdata)){
					$postidold[]      =$ev->post_id;

				}
				$userdatav = $db2->query('SELECT pu.user_id,pu.post_id,pu.status FROM event_posts as ep
				inner join post_userbox as pu ON ep.post_id = pu.post_id		

				WHERE ep.event_id="'.$event->id.'" AND ep.post_id="'.$postidold[0].'" ');
				$userdatavstatus = $db2->query('SELECT pu.user_id,pu.post_id FROM event_posts as ep
				inner join post_userbox as pu ON ep.post_id = pu.post_id		

				WHERE ep.event_id="'.$event->id.'" AND ep.post_id="'.$postidold[0].'" AND status=1  ');
				
		
		
		$postdata = $db2->query('SELECT user_id,group_id,attached,posttags FROM posts where id="'.$postidold[0].'"');
		$postattachmentdata = $db2->query('SELECT type,data  FROM posts_attachments where post_id="'.$postidold[0].'"');
		$posteventdata = $db2->query('SELECT edit_status FROM event_posts where event_id="'.$event->id.'" AND edit_status =4 ');
		while($ev = $this->db2->fetch_object($posteventdata)){
					$posteventdatawe[]      =$ev->edit_status;

			}

			if(!empty($posteventdatawe[0])){
				$edit = 4;
				$street_up = $this->db2->query('UPDATE `event_posts` SET edit_status="'.$edit.'" WHERE post_id="'.$postidold[0].'"');

				
			}
		
		
		$delete_access = true;
		if( empty($event->admin_id) ){
			$delete_access = false;
		}
		if( $this->user->id != $event->admin_id && empty($event->is_network_admin) ){
			$delete_access = false;
		}

		if( $delete_access ){

			$event_update = $this->db2->query('UPDATE `events` SET status=2 WHERE id="'.$this->params->event.'"');

			
			while($re = $this->db2->fetch_object($postdata)) {
				$db2->query('INSERT INTO posts SET user_id="'.$re->user_id.'",posttags="'.$re->posttags.'", group_id="'.$re->group_id.'", date="'.time().'", date_lastcomment="'.time().'", attached="1"'); 
				$pid = $db2->insert_id();
				$db2->query('INSERT INTO event_posts SET post_id="'.$pid.'", event_id="'.$this->params->event.'",created = "'.date('Y-m-d H:i:s').'" ');
				$postid[] = $pid;
			}
			while($pa = $this->db2->fetch_object($postattachmentdata)) {
				
				$db2->query('INSERT INTO posts_attachments SET post_id="'.$postid[0].'", type="link",data="'.$db2->escape($pa->data).'"');
				


       
		}
			while($o = $this->db2->fetch_object($userdatav)) {
				
				$db2->query('INSERT INTO post_userbox SET user_id="'.$o->user_id.'", post_id="'.$postid[0].'",event_status=2,status="'.$o->status.'" ');
				

       
		}
		while($o = $this->db2->fetch_object($userdatavstatus)) {
			if($o->user_id != $this->user->id ){
							$db2->query('INSERT INTO users_dashboard_tabs SET user_id="'.$o->user_id.'", tab="notifications",state=1,newposts=1  ');
							$db2->query('INSERT INTO notifications SET to_user_id="'.$o->user_id.'",notif_type="event",in_group_id="'.$group_id.'",from_user_id="'.$this->user->id.'", notif_object_type="user",date="'.time().'",post_id="'.$pid.'"  ');

				}
		}
		
			
			if($event->display_type == 'group' && !empty($event->group_id)){
				$user_list= $this->network->get_group_members($event->group_id, true);
				
				$subject = 'Event Cancelled : '.ucwords($event->event_name);
				$message_html = ucwords($event->event_name).' has been cancelled';
				$message_html .= '<br /><br /><b>Event Name: </b>'.ucwords($event->event_name);
				$message_html .= '<br /><b>When: </b>'.$event->start_date.' '.$event->start_time;
				$message_html .= '<br /><b>Address: </b>'.$event->location.' '.$event->address;
				$message_html .= '<br /> --------------------------------------------------------<br /><br />';
				$message_html .= 'This is an automatically generated email from an unattended inbox. <b>Please Do Not Reply</b>. Replies to this message will not be seen.';

					//Send mail
				foreach($user_list as $user_id=>$post){
					$user= $this->network->get_user_by_id($user_id);
					$message_html = '<br /> Hello '.$user->fullname.', <br />'.$message_html;
					do_send_mail_html($user->email, $subject, '', $message_html, '');
				}
			}
			if(!empty($event->group_id)){
				$this->redirect('plugin/events/home/group:'.$event->group_id);
			}else{
				$this->redirect('plugin/events/home');
			}
		}else{
			$this->redirect('plugin/events/home');
			$error = 'Event not updated';
		}
	}else{
		$this->redirect('plugin/events/home');
		$error = 'Invalid Event';
	}
?>