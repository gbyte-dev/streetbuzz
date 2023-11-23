
<?php
class SSH {

    protected $conn;
	public $source_path;
    public  $destination_path;
    public function __construct() {
        $this->source_path = $_SERVER['DOCUMENT_ROOT'].'/newsapp/storage/attachments';
		//$this->source_path = $_SERVER['DOCUMENT_ROOT'].'/demo/archival';
        $this->destination_path = '/home/sreenivas/public_html/storage/attachments/1';
	}
	
	public function connect($host, $username, $password, $port = 22) {
        $this->source_path = $_SERVER['DOCUMENT_ROOT'].'/newsapp/storage/attachments';
		//$this->source_path = $_SERVER['DOCUMENT_ROOT'].'/demo/archival';
        $this->destination_path = '/home/sreenivas/public_html/storage/attachments/1';
	
		$this->conn = ssh2_connect($host, $port);
	   //print_r($this->conn);die;
		if($this->conn) {
            if (ssh2_auth_password($this->conn, $username, $password) === false) {
    			throw new Exception('SSH2 login is invalid');
    		}
		} else {
		    throw new Exception('SSH2 Connection failed.');
		}
    }
	
	public function move_files($source_folder, $file_name) {
		$destination_file = $this->destination_path.'/'.$file_name;
        $source_file = $this->source_path.'/'.$source_folder.'/'.$file_name;
		if(file_exists($source_file)){			
			$return_flag = ssh2_scp_send($this->conn, $source_file, $destination_file, 0644);
			if($return_flag) {
			  //throw new Exception( 'Source file '.$source_file.' move successfully.');
               // echo 'Source file '.$source_file.' move successfully.';
			} else {
               throw new Exception('Source file '.$source_file.' not moved.');
				//echo 'Source file '.$source_file.' not moved.';
				//echo "noo";
			}
		} else {
			throw new Exception('Source file '.$source_file.' not exist.');
		}
	}	

	public function copy_files($source_folder, $file_name) {
		$destination_file = $this->destination_path.'/'.$file_name;
        $source_file = $this->source_path.'/'.$source_folder.'/'.$file_name;
		if(file_exists($source_file)){	
			if( !copy($source_file, $destination_file) ) {  
				throw new Exception('Source file '.$source_file.' not moved.');
			}
			//echo 'Source file => '.$source_file." Moved at => ".$destination_file;
		} else {
			throw new Exception('Source file '.$source_file.' not exist.');
		}
	}
}

?>