<?php 

class MyInstaller extends Installer {
	
	public function MyInstaller(){
		parent::Installer();
	}
	
	public function up(){
		
		$this->db->create_table('users_whosawmyprofile');
		$this->db->add_field('users_whosawmyprofile', 'user_id', ColumnType::INTEGER, array(new FieldOptionLimit(10), new FieldOptionNull(true)));
		$this->db->add_field('users_whosawmyprofile', 'visitor_id', ColumnType::INTEGER, array(new FieldOptionLimit(10), new FieldOptionNull(true)));
		$this->db->add_field('users_whosawmyprofile', 'date', ColumnType::INTEGER, array(new FieldOptionLimit(10), new FieldOptionNull(true)));
		
	}
	
	public function down(){
			
		$this->db->drop_table('users_whosawmyprofile');
		
	}
	
}
