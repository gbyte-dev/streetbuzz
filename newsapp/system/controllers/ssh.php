<?php
phpinfo();
require_once('classes/class_ssh.php');

/*class SSH {

    protected $conn;
	public $source_path;
    public  $destination_path;
    public function __construct($host, $username, $password, $port = 22) {
        $this->source_path = '/home/streetbuzz/public_html/test/storage/archival';
		//$this->source_path = $_SERVER['DOCUMENT_ROOT'].'/demo/archival';
        $this->destination_path = '/home/sreenivas/public_html/storage';
		
		$this->conn = ssh2_connect($host, $port);
	   //print_r($this->conn);
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
	//	echo $source_file;
		if(file_exists($source_file)){			
			echo "<br>".$destination_file;
			$return_flag = ssh2_scp_send($this->conn, $source_file, $destination_file, 0644);
			if($return_flag) {
				echo 'Source file '.$source_file.' move successfully.';
			} else {
				echo 'Source file '.$source_file.' not moved.';
			}
		} else {
			throw new Exception('Source file '.$source_file.' not exist.');
		}
	}	
}*/


 $host = '182.18.139.51';
 $username = 'root'; 
 $password = 'CE!6xqLSD#c4c$';
 $port = '2232';
 $current_date = date("d-m-Y");
try {
	$ssh = new SSH($host, $username, $password, $port);
	$ssh->move_files("1", '1419191519376882_orig.jpg');
} catch (Exception $ex) {
	echo 'Message: ' .$ex->getMessage();
}


?>