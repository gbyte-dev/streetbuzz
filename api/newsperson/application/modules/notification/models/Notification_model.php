<?php

class Notification_model extends MY_Model {
    public $notification_type = array();
    public $notification_settting = array();
    public function __construct() {
        parent::__construct();

        $this->notification_type = array(
            'ntf_me_if_u_follows_me' => array('type' => 'followers', 'message' => 'started following you'),
            'ntf_me_on_post_like' => array('type' => 'likes', 'message' => 'have liked your post'),
            'ntf_me_on_post_rebuzz' => array('type' => 'rebuzz', 'message' => 'rebuzz your new/buzz'),
            'ntf_me_on_post_replay' => array('type' => 'replied on buzz', 'message' => 'has replied on your buzz'),
            'ntf_me_if_u_creates_grp' => array('type' => 'follower created groups', 'message' => 'has created a groups'),
            'ntf_me_if_u_joins_grp' => array('type' => 'follower joined a group', 'message' => 'has joins in a group'),
            'ntf_me_if_u_edt_profl' => array('type' => 'changed profile info', 'message' => 'changed there profile info'),
            'ntf_me_on_post_profileloved' => array('type' => 'liked profile pic', 'message' => 'liked your profile picture'),
            'ntf_me_if_u_invit_me_grp' => array('type' => 'invites to join group', 'message' => 'invites you to join group'),
            'ntf_me_if_u_edt_pictr' => array('type' => 'changed profile pic', 'message' => 'changed their profile picture'),
            'ntf_me_if_u_follows_u2' => array('type' => 'follower followed someone', 'message' => 'started following someone')
        );
        $this->notification_settting	= array (
			// 0 - off, 1 - on
			'ntf_them_if_i_follow_usr'	=> 1,
			'ntf_them_if_i_comment'		=> 1,
			'ntf_them_if_i_edt_profl'	=> 1,
			'ntf_them_if_i_edt_pictr'	=> 1,
			'ntf_them_if_i_create_grp'	=> 1,
			'ntf_them_if_i_join_grp'	=> 1,
			
			// 0 - off, 2 - post, 3 - email, 1 - both
			'ntf_me_if_u_follows_me'	=> 2,
			'ntf_me_if_u_follows_u2'	=> 0,
			'ntf_me_if_u_commments_me'	=> 0,
			'ntf_me_if_u_commments_m2'	=> 0,
			'ntf_me_if_u_edt_profl'		=> 0,
			'ntf_me_if_u_edt_pictr'		=> 0,
			'ntf_me_if_u_creates_grp'	=> 0,
			'ntf_me_if_u_joins_grp'		=> 2,
			'ntf_me_if_u_invit_me_grp'	=> 2,
			'ntf_me_if_u_posts_qme'		=> 0,
			'ntf_me_if_u_posts_prvmsg'	=> 0,
			'ntf_me_if_u_registers'		=> 0,
			'ntf_me_on_post_like'		=> 0,
		);
    }
    
    function list($data) {
        $notification_types = array_keys($this->notification_type);
        $page_no    = isset($data['page_no']) ? $data['page_no'] : 1;
        $page_size  = isset($data['page_size']) ? $data['page_size'] : 10;
        $offset     = pagination_offset($page_no, $page_size);
        $user_id    = $data['user_id']; 
        $count_flag    = isset($data['count_flag']) ? $data['count_flag'] : 0;

       // $this->db->select("u.username, u.fullname, u.id as user_id");
      //  $this->db->select('IFNULL(u.avatar,"") as avatar', FALSE);
        $this->db->select("GROUP_CONCAT(DISTINCT(n.id) ORDER BY n.id DESC) as notification_ids, GROUP_CONCAT(from_user_id ORDER BY id DESC) as from_user_ids, MAX(n.date) AS added_date", FALSE);
        $this->db->select("n.notif_type, n.in_group_id, n.from_user_id, n.to_user_id, n.notif_object_type, n.notif_object_id, n.post_id, n.noti_postid");
        $this->db->from("notifications n");
        //$this->db->join('users u', 'u.id = n.from_user_id', 'LEFT');
        $this->db->where('n.to_user_id', $user_id);
        $this->db->where('n.from_user_id !=', $user_id);
        $this->db->where('n.from_user_id >', 0);
        $this->db->where_in('n.notif_type', $notification_types);
        

        $this->db->group_by('n.notif_type');
        $this->db->_protect_identifiers = FALSE;
        $this->db->group_by('IF((n.notif_type="ntf_me_on_post_like" OR n.notif_type="ntf_me_on_post_rebuzz" OR n.notif_type="ntf_me_on_post_replay"),n.noti_postid,"")');
        $this->db->group_by('IF((n.notif_type="ntf_me_if_u_invit_me_grp" OR n.notif_type="ntf_me_if_u_joins_grp"),n.in_group_id,"")');        
        $this->db->group_by('IF((n.notif_type="ntf_me_if_u_edt_profl" OR n.notif_type="ntf_me_if_u_edt_pictr" OR n.notif_type="ntf_me_if_u_follows_u2" OR n.notif_type="ntf_me_if_u_creates_grp"),n.from_user_id,"")');        
        $this->db->_protect_identifiers = TRUE;
        $this->db->order_by('added_date', 'desc');
        if (!$count_flag) {
            $this->db->limit($page_size,$offset);
        }

        $query = $this->db->get();
        $total = $query->num_rows();
        if ($count_flag) {
            return $total;
        }
        $$notifications = array();

        if ($total > 0) {
            $results = $query->result_array();
            foreach ($results as $result) {
                $notif_type = $result['notif_type'];
                $from_user_ids = $result['from_user_ids'];
                
                $notification = $this->notification_type[$notif_type];
                $notification['notif_type'] = $notif_type;
                $notification['added_date'] = $result['added_date'];
                $notification['post_id'] = $result['noti_postid'];
                //$notification['notification_ids'] = $result['notification_ids'];
                //$notification['from_user_ids'] = $from_user_ids;
                $from_user_ids = array_unique(explode(',', $from_user_ids));

                $count = count($from_user_ids);
                if($notif_type == 'ntf_me_if_u_follows_me') {
                    $count = $this->num_followers;
                } else if($notif_type == 'ntf_me_on_post_profileloved') {
                    $count = $this->num_favourites;
                } else if(in_array($notif_type, array('ntf_me_on_post_like', 'ntf_me_on_post_rebuzz', 'ntf_me_on_post_replay'))) {
                    $post_details = $this->get_post_details($result['noti_postid']);
                    if($notif_type == 'ntf_me_on_post_like') {
                        $count = isset($post_details['likes']) ? $post_details['likes'] : $count;
                    } else if($notif_type == 'ntf_me_on_post_rebuzz') {
                        $count = isset($post_details['reshares']) ? $post_details['reshares'] : $count;
                    } else if($notif_type == 'ntf_me_on_post_replay') {
                        
                    }
                }
                $notification['count'] = $count;
                $count = ($count < 10) ? $count : 10;
                $from_user_ids = array_slice($from_user_ids, 0, $count);
                //$notification['from_users'] = $from_user_ids;
                $notification['users'] = $this->get_users($from_user_ids);
                $notifications[] = $notification;
            }
        }  
        return $notifications; 
    }

    public function get_users($user_ids) {
        $user_id_str = implode(",", $user_ids);
        $this->db->select("u.username, u.fullname, u.id as user_id");
        $this->db->select('IFNULL(u.avatar,"") as avatar', FALSE);
        $this->db->from("users u");
        $this->db->where_in('u.id', $user_ids);
        $this->db->order_by("FIELD(u.id, $user_id_str)");
        $query = $this->db->get();
        $results = $query->result_array();
        return $results;
    }

    public function get_post_details($post_id) {
        $this->db->select('IFNULL(pd.likes,0) as likes', FALSE);
        $this->db->select('IFNULL(pd.comments,0) as comments', FALSE);
        $this->db->select('IFNULL(pd.reshares,0) as reshares', FALSE);
        $this->db->from("posts_details pd");
        $this->db->where('pd.post_id', $post_id);
        $query = $this->db->get();
        $row = $query->row_array();
        return $row;
    }


    public function get_user_notification_setting($user_id) {
		$this->db->select("*");
        $this->db->from("users_notif_rules");
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            $this->notification_settting = $query->row_array();
        }
        return $this->notification_settting;
	}

    public function is_notification_allow($key) {
        return isset($this->notification_settting[$key]) ? $this->notification_settting[$key] : 0;
    }


    function insert_active_profile($data){

        $this->get_user_notification_setting($data['to_user_id']);
        $is_allow = $this->is_notification_allow($data['standard_notify_type']);
        if($is_allow > 0) {
            $from_user_id   = $data['from_user_id'];
            $to_user_id     = $data['to_user_id'];
            $post_id        = $data['post_id'];
            $noti_type      = $data['noti_type'];
            $post_type      = $data['post_type'];
            $standard_notify_type      = $data['standard_notify_type'];

            $date  = time();
            $input = array(
                            'from_user_id'  => $from_user_id,
                            'to_user_id'    => $to_user_id,
                            'post_id'       => $post_id,
                            'noti_type'     => $noti_type,
                            'post_type'     => $post_type,
                            'date'          => $date
                        );            
            $this->db->insert('active_notifications', $input); 
            
            $input = array(
                'from_user_id'  => $from_user_id,
                'to_user_id'    => $to_user_id,
                'noti_postid'   => $post_id,
                'notif_type'     => $standard_notify_type,
                'notif_object_type'  => $post_type,
                'notif_object_id' => $from_user_id,
                'in_group_id' => 0,
                'date'          => $date
            ); 
            $this->db->insert('notifications', $input); 

        //user existing in user dashboard tabs
            $newpost = $this->user_dashboard_tabs($to_user_id);
            $count = 1;  
            if(!empty($newpost)) {
                $set_field  = "newposts";                   
                $this->db->where('user_id', $to_user_id);
                $this->db->set($set_field, "$set_field+($count)", FALSE);
                $this->db->update('users_dashboard_tabs'); 
            } else {
                $input = array(
                    'user_id'    => $to_user_id,
                    'tab'   => 'notifications',
                    'state'     => 1,
                    'newposts'  => $count
                ); 
                $this->db->insert('users_dashboard_tabs', $input); 			
            }
        }	
	}

	public function user_dashboard_tabs($user_id){
        $notify_type = 'notifications';
        $newposts = 0;
        $this->db->select('newposts');
        $this->db->where(array('user_id' => $user_id,'tab' => $notify_type));
        $this->db->limit(1);
        $query = $this->db->get('users_dashboard_tabs');
        if($query->num_rows() > 0) {
            $row = $query->row_array();
            $newposts = $row['newposts'];
        }		
        return $newposts;			
	}

    public function add_notification($data){
        $this->get_user_notification_setting($data['to_user_id']);
        $is_allow = $this->is_notification_allow($data['notif_type']);
        if($is_allow > 0) {
            $this->db->insert('notifications', $data); 
        }
    }
  
}