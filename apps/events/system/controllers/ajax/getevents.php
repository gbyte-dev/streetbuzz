<?php
	global $user, $db2, $C;
	date_default_timezone_set("UTC");
	if( isset($_POST['start']) ){
		$group_found = false;
		if(!empty($_POST['group'])){

				// check if the request comes from dashboard or not 
				if( !empty($_POST['dashboard']) ){
					
					// Pavel => get group ids of all comunity that user is member
					$group_ids = array();
					$get_group_ids = $db2->query('SELECT group_id FROM groups_followed WHERE user_id = '.(int)$user->id);
					while ($objgr = $db2->fetch_object($get_group_ids)) {
						$group_ids[] = $objgr->group_id;
					}
					$group_ids = array_unique($group_ids);

				}else{
					 $group_ids[] = (int)$_POST['group'];
				}
			
				$group_ids      = @implode( '","', $group_ids );
				$group_found    = true;
				$events = $db2->query('SELECT * FROM events WHERE (display_type="community" OR (display_type="group" AND group_id IN ("'.$group_ids.'") ))');
			
        }elseif(!empty($_POST['username'])){

			$group_found = true;
			$groups = $this->network->get_user_follows($user->id, '', 'hisgroups');
			if(!empty($groups->follow_groups)){
				$follow_group = $groups->follow_groups;
				$groups = array_flip($follow_group);
				$group_ids = implode(',', $groups);
			}else{
				$group_ids = 0;
			}
			//$events = $db2->query('SELECT * FROM events WHERE (display_type="community" OR (display_type="group" AND group_id IN ('.$group_ids.'))) AND start_date>="'.(date('Y-m-d', $_POST['start'])).'" AND end_date<="'.(date('Y-m-d', $_POST['end'])).'" AND status=1');
			$events = $db2->query('SELECT * FROM events WHERE (display_type="community" OR (display_type="group") AND group_id IN ('.$group_ids.')))');
		}else{
			
			// Pavel => get group ids of all comunity that user is member
			$group_ids = array();
			$get_group_ids = $db2->query('SELECT group_id FROM groups_followed WHERE user_id = '.(int)$user->id);
			while ($objgr = $db2->fetch_object($get_group_ids)) {
				$group_ids[] = $objgr->group_id;
			}
			if( !empty($group_ids) ){
				
				$group_ids      = @implode( '","', $group_ids );
				$group_found    = true;
				if($_POST['tab'] =='all'){
					$events = $db2->query('SELECT e.id as id,e.event_name,e.address,e.location,e.event_description,e.start_date,e.start_time,e.end_date,e.end_time,e.url,e.status as estatus,e.event_type,e.tag_name,pu.event_status as status 
	               FROM `post_userbox` as pu 
	                inner join event_posts as ep ON ep.post_id = pu.post_id 
					inner join events as e on ep.event_id = e.id 
					WHERE pu.user_id = "'.$this->user->id.'" and ((pu.status =1 and pu.event_status is null) or pu.event_status = 1 or (pu.status =1 and pu.event_status = 5) )   order by id desc ');
				}elseif($_POST['tab'] =='myevent'){
					$events = $db2->query('SELECT e.id as id,e.event_name,e.event_type,e.address,e.location,e.event_description,e.start_date,e.start_time,e.end_date,e.end_time,e.url,e.status as estatus,e.tag_name,pu.event_status as status
		         FROM   events as e
                inner join event_posts as ep ON ep.event_id	=e.id
				inner join post_userbox as pu ON pu.post_id	=ep.post_id		
		       where e.admin_id="'.(int)$user->id.'" group by id  ');
				}elseif($_POST['tab'] =='accept'){
					$events = $db2->query('SELECT e.id as id,e.event_name,e.event_type,e.address,e.location,e.event_description,e.start_date,e.start_time,e.end_date,e.end_time,e.url,e.status as estatus,e.tag_name,pu.event_status as status
	               FROM `post_userbox` as pu 
	                inner join event_posts as ep ON ep.post_id = pu.post_id 
					inner join events as e on ep.event_id = e.id 
					WHERE pu.user_id = "'.$user->id.'" and (pu.event_status = 1 and pu.status is null)');
				}else{
				
				$events = $db2->query('SELECT e.id,e.event_type,e.event_name,e.event_description,e.address,e.start_date,e.end_date,e.start_time,e.end_time,pu.event_status as status,e.status as estatus

				FROM events  AS e
				inner join event_posts as ep ON ep.event_id	=e.id
				inner join post_userbox as pu ON pu.post_id	=ep.post_id
				
				WHERE pu.user_id="'.(int)$user->id.'" AND e.status=1 AND  ( (pu.status =1 and pu.event_status is null) or pu.event_status = 1 or (pu.status =1 and pu.event_status = 5) )AND (e.display_type="community" OR (e.display_type="group" AND e.group_id IN ("'.$group_ids.'") ))');
			
				}
			}else{
				$events = $db2->query(' SELECT * FROM events WHERE display_type="community" ');
			}
		
		}
		// Accumulate an output array of event data arrays.
		$output_arrays = array();
		while ($obj = $db2->fetch_object($events)) {
			if($group_found == true){
				if(!empty($obj->group_id)){
					$back_color = '#E8F5FF !important';
					$front_color = '#000';
				}else{
					$back_color = '#FFF';
					$front_color = '#000 !important';
				}
			}else{
				$back_color = '#FFF';
				$front_color = '#000';
			}
			if($obj->event_type=='Major'){
				$className = 'major-event';
			}else{
				$className = '';
			}
                        
                        if($obj->estatus==2){
                            $className = empty($className) ? 'fc-event-canceled' : $className.' fc-event-canceled';
                        }
						if($obj->estatus==3){
                            $className = empty($className) ? 'fc-event-canceled' : $className.' fc-event-canceled';
                        }
			
			$data = array();
			$data['id'] = $obj->id;
			$data['event_type'] = $obj->event_type;
			$data['event_name'] = $obj->event_name;
			$data['backgroundColor'] = $back_color;
			$data['className'] = $className;
			$data['textColor'] = $front_color;
			$data['event_description'] = $obj->event_description;
			$data['address'] = $obj->address;
			$cancel = ($obj->status == 2)?' - Cancelled Event':'';
			$data['title'] = $obj->event_name.$cancel;
			$start = date('M d, Y h:i A', strtotime($obj->start_date.' '.$obj->start_time));
			$end = date('M d, Y h:i A', strtotime($obj->end_date.' '.$obj->end_time));
			$data['start_disp'] = $start;
			$data['end_disp'] = $end;
			
			$data['start'] = $obj->start_date.' '.$obj->start_time;
			$data['end'] = $obj->end_date.' '.date('h:i:s', strtotime($obj->end_time));
			$output_arrays[]= $data;
		}
		// Send JSON to the client.
		$answer = array('html'=>false, 'events'=>$output_arrays);
		echo json_encode($answer);
		return;
	}
	
	echo 'ERROR: No event found.';
	return;
	
	
	
	function parseDateTime($string, $timezone=null) {
	$date = new DateTime(
		$string,
		$timezone ? $timezone : new DateTimeZone('UTC')
			// Used only when the string is ambiguous.
			// Ignored if string has a timezone offset in it.
	);
	if ($timezone) {
		// If our timezone was ignored above, force it.
		$date->setTimezone($timezone);
	}
	return $date;
}