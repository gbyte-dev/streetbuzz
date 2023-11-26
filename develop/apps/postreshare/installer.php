<?php 

class MyInstaller extends Installer {
	
	public function MyInstaller(){
		parent::Installer();
	}
	
	public function up(){
		
		$this->db->create_table('post_reshares');
		$this->db->add_field('post_reshares', 'id', ColumnType::INTEGER, array(new FieldOptionLimit(10), new FieldOptionNull(true)));
		$this->db->add_field('post_reshares', 'post_id', ColumnType::INTEGER, array(new FieldOptionLimit(10), new FieldOptionNull(true)));
		$this->db->add_field('post_reshares', 'user_id', ColumnType::INTEGER, array(new FieldOptionLimit(10), new FieldOptionNull(true)));
		$this->db->add_field('post_reshares', 'date', ColumnType::INTEGER, array(new FieldOptionLimit(10), new FieldOptionNull(true)));

	}
	
	public function down(){
		
		$this->db->drop_table('post_reshares');
		
	}
	
}