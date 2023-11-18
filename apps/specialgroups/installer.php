<?php 

class MyInstaller extends Installer {
	
	public function MyInstaller(){
		parent::Installer();
	}
	
	public function up(){
		
		$this->db->create_table('groups_special');
		$this->db->add_field('groups_special', 'group_id', ColumnType::BOOLEAN, array(new FieldOptionLimit(1), new FieldOptionDefault(1), new FieldOptionNull(true)));
	
	}
	
	public function down(){
			
		$this->db->drop_table('groups_special');
		
	}
	
}