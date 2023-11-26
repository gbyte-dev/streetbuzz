<?php 

class MyInstaller extends Installer {


	public function MyInstaller(){
		parent::Installer();
	}

    public function up(){
        $this->db->create_table('cometchat_sharetronix');
    }

	public function down(){

		global $C;		
		
		$this->db = ActiveRecord::getInstance();
		$sql = "SELECT CONCAT( 'DROP TABLE IF EXISTS ', GROUP_CONCAT(table_name) , '' ) AS statement FROM information_schema.tables WHERE table_schema = '$C->DB_NAME' and table_name like 'cometchat%'";	
		$result = $this->db->query($sql);
		$this->db->query($result[0]['statement']);   
	}
}