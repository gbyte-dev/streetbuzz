<?php

/**
 * Used for Follow
 * @package     User
 * @category    User
 */
class Follow_model extends My_Model {

    public function __construct() {
        parent::__construct();
    }
   
    /**
     * [toggle_follow Used to  follow user.]
     * @param  [type] $data [Follow user data]
     */
    function toggle_follow($data) {
        $result = $this->get_single_row('id', 'users_followed', array('who' => $data['user_id'], 'whom' => $data['whom_id'])); //get details to check if this already exists
        if (empty($result)) {  
            $date  = time();
            $input = array(
                            'who'    => $data['user_id'],
                            'whom'  => $data['whom_id'],
                            'date'  => $date
                        ); 
            $this->db->insert('users_followed', $input);

            $this->db->set('num_followers', 'num_followers+1', FALSE);
            $this->db->where('id', $data['whom_id']);
            $this->db->update('users');


            $input = array(
                'from_user_id'  => $data['user_id'],
                'to_user_id'    => $data['whom_id'],
                'notif_type'     => 'ntf_me_if_u_follows_me',
                'date'          => $date
            ); 
            $this->load->model(array('notification/Notification_model'));
            $this->Notification_model->add_notification($input); 
        }
        return 1;
    }


    /**
     * [auto_follow Used to auto follow user.]
     * @param  [type] $data [Follow user data]
     */
    function auto_follow($who_id, $whom_id) {
        $result = $this->get_single_row('id', 'users_followed', array('who' => $who_id, 'whom' => $whom_id)); //get details to check if this already exists
        if (empty($result)) {  
            $date  = time();
            $input = array(
                            'who'    => $who_id,
                            'whom'  => $whom_id,
                            'date'  => $date
                        ); 
            $this->db->insert('users_followed', $input);

            $this->db->set('num_followers', 'num_followers+1', FALSE);
            $this->db->where('id', $whom_id);
            $this->db->update('users');


            $input = array(
                'from_user_id'  => $who_id,
                'to_user_id'    => $whom_id,
                'notif_type'     => 'ntf_me_if_u_follows_me',
                'date'          => $date
            ); 
            $this->load->model(array('notification/Notification_model'));
            $this->Notification_model->add_notification($input); 
        }
    }
    
}
