<?php
class Auth_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }
   
    /**
     * used for validate user aut token
     * @param string $access_token
     * @return array
     */
    public function check_auth_token($access_token) {
        $this->db->select("o.user_id, u.is_network_admin");
        $this->db->from('oauth_access_token o');
        $this->db->join('users u', 'u.id = o.user_id AND u.active=1');
        $this->db->where("o.access_token", $access_token);
        $this->db->limit(1);
        $sql = $this->db->get();
        $result = $sql->row_array();
        return ($result) ? $result : array();
    }
    
}