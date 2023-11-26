<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MY_Model extends CI_Model {

    /**
     * Class constructor
     * load user db database.
     * @return	void
     */
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Class destructor
     * Closes the connection to user db if present
     * @return	void
     */
    function __destruct() {
        if (isset($this->db->conn_id)) {
            $this->db->close();
        }
    }
    /**
     * common function used to get all data from any table
     * @param string    $table
     * @param string    $select     
     * @param array/string $where
     * @return	array
     */
    function get_all_table_data($table, $select = '*', $where = "") {
        $this->db->select($select);
        $this->db->from($table);
        if ($where != "") {
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * common function used to get single record from any table
     * @param string    $select
     * @param string    $table
     * @param array/string $where
     * @return	array
     */
    function get_single_row($select = '*', $table, $where = "") {
        $this->db->select($select, FALSE);
        $this->db->from($table);
        if ($where != "") {
            $this->db->where($where);
        }
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * common function used to insert batch records into table
     * @param   array $data
     * @return	bool
     */
    function insert_batch($data) {
        $this->db->insert_batch($this->table_name, $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Replace into Batch statement
     * Generates a replace into string from the supplied data
     * @param    string    the table name
     * @param    array    the update data
     * @return   string
     */
    function replace_into_batch($table, $data) {
        $column_name = array();
        $update_fields = array();
        $append = array();
        foreach ($data as $i => $outer) {
            $column_name = array_keys($outer);
            $coloumn_data = array();
            foreach ($outer as $key => $val) {
                if ($i == 0) {
                    $update_fields[] = "`" . $key . "`" . '=VALUES(`' . $key . '`)';
                }

                if (is_numeric($val)) {
                    $coloumn_data[] = $val;
                } else {
                    $coloumn_data[] = "'" . replace_quotes($val) . "'";
                }
            }
            $append[] = " ( " . implode(', ', $coloumn_data) . " ) ";
        }

        $sql = "INSERT INTO " . $this->db->dbprefix($table) . " ( " . implode(", ", $column_name) . " ) VALUES " . implode(', ', $append) . " ON DUPLICATE KEY UPDATE " . implode(', ', $update_fields);
        $this->db->query($sql);
    }

    /**
     * Updates whole row [unlike update_field()]
     * @param array $data
     * @param int   $id
     */
    public function update($table = "", $data, $where = "") {
        $return_flag = FALSE;
        if (!is_array($data)) {
            log_message('error', 'Supposed to get an array!');
        } else if ($table == "") {
            log_message('error', 'Got empty table name');
        } else if ($where == "") {
            log_message('error', 'Got empty where condition');
        } else {
            $this->db->where($where);
            $this->db->update($table, $data);
            $return_flag = TRUE;
        }
        return $return_flag;
    }

    
    /**
     * [insert Used to insert data and retrun ID]
     * @param  [string] $table_name [table name]
     * @param  [array]  $data       [data]
     * @return [int]                [Inserted ID]
     */
    function insert($table_name, $data) {
        $this->db->insert($table_name, $data);
        $id = $this->db->insert_id();
        return $id;
    }

    function update_row($table, $where, $data) {
        $this->db->where($where);
        $this->db->update($table, $data);
    }

    /**
     * common function used to delete record from any table
     * @param string    $table
     * @param array/string $condition
     * @return	array
     */
    public function delete_row($table, $condition) {
        $this->db->where($condition);
        $this->db->delete($table);
    }  


    public function image_url() {
        $url = "http://"; 
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $url = "https://";
        }
        $url.= $_SERVER['HTTP_HOST'];
        return $url."/".PROJECT_FOLDER_NAME."/storage/attachments/1/";    
    }
    
    public function profile_image_url() {  
        $url = "http://"; 
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $url = "https://";
        }
        $url.= $_SERVER['HTTP_HOST'];   
        return $url."/".PROJECT_FOLDER_NAME."/storage/avatars/thumbs1/";
    }


    public function generate_login_auth_key($who) {
		$oauth_access_token = $this->generate_request_token();

		$this->db->select('id');
        $this->db->from('oauth_access_token');
        $this->db->where('user_id', $who);       
        $this->db->limit(1);
        $query = $this->db->get();
        

        if($query->num_rows() > 0) {
			$row  = $query->row_array();
            $this->db->where('id', $row['id']);
            $this->db->update('oauth_access_token', array('access_token' => $oauth_access_token));
			
		} else {
            $this->db->insert('oauth_access_token', array('access_token' => $oauth_access_token, 'user_id' => $who));
			
		}
        return $oauth_access_token;
	}


	function generate_request_token() {
		$request_token = '';
		$request_token = substr(md5(rand() . time() . rand()), 0, 22);
		return $request_token;
	}

    function random_unique_string($table, $unique_colomn, $extra_where = [], $type = 'alnum', $len = 8) {
        while (1) {
            $random_string = random_string($type, $len);
            $this->db->from($table);
            $this->db->where($unique_colomn, $random_string);
            if (!empty($extra_where)) {
                $this->db->where($extra_where);
            }
            $query = $this->db->get();
            if ($query->num_rows() == 0) {
                break;
            }
        }
        return $random_string;
    }

}

/* End of file MY_Model.php */
/* Location: application/core/MY_Model.php */