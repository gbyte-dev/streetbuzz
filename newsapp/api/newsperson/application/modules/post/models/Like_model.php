<?php

/**
 * Used for Like
 * @package     Post
 * @category    Like
 */
class Like_model extends My_Model {

    public function __construct() {
        parent::__construct();
    }

    function toggle_like($data) {
        $post_id    = $data['post_id'];
        $user_id    = $data['user_id'];
        $post_owner_id    = $data['post_owner_id'];
        $count = 1;

        $is_like = $this->is_liked($user_id, $post_id);
        if($is_like == 1) {
            $count = -1;
            $this->db->where('user_id', $user_id);
            $this->db->where('post_id', $post_id);
            $this->db->delete('post_likes');
        } else {
            $date  = time();
            $input = array(
                            'user_id'  => $user_id,
                            'post_id'  => $post_id,
                            'date'  => $date
                        );            
            $this->db->insert('post_likes', $input); 
            
            $this->load->model(array('notification/Notification_model'));
            $notify_data = array();
            $notify_data['from_user_id'] = $user_id;
            $notify_data['to_user_id'] = $post_owner_id;
            $notify_data['post_id'] = $post_id;
            $notify_data['noti_type'] = 'like';
            $notify_data['post_type'] = 'buzz';
            $notify_data['standard_notify_type'] = 'ntf_me_on_post_like';
            $this->Notification_model->insert_active_profile($notify_data);
        }
        $this->update_like_count($post_id, $count);
        return $count;
    }

    public function update_like_count($post_id, $count=1){
        $this->db->select('posts_detail_id');
        $this->db->from('posts_details');
        $this->db->where('post_id', $post_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            $set_field  = "likes";
            $this->db->where('post_id', $post_id);
            $this->db->set($set_field, "$set_field+($count)", FALSE);
            $this->db->update('posts_details');  
        } else {
            $input = array(
                'likes'  => 1,
                'post_id'  => $post_id
            );            
            $this->db->insert('posts_details', $input); 
        }
    }

    function is_liked($user_id, $post_id) {
        if($user_id) {
            $this->db->select('id');
            $this->db->from('post_likes');
            $this->db->where('user_id', $user_id);
            $this->db->where('post_id', $post_id);
            $this->db->limit(1);
            $query = $this->db->get();
            if($query->num_rows() > 0) {
                return 1;
            }
        }
        return 0;
    }

    function get_liked_count($post_id) {        
        $this->db->select('COUNT(id) as cnt');
        $this->db->from('post_likes');
        $this->db->where('post_id', $post_id);
        $result = $this->db->get();
        $count_data=$result->row_array();
        return $count_data['cnt'];
    }    

}