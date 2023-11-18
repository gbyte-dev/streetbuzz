<?php 

class MyInstaller extends Installer {
	
	public function MyInstaller(){
		parent::Installer();
	}
	
	public function up(){
		
		$this->db->create_table('posts_spamprotector');
		$this->db->add_field('posts_spamprotector', 'post_id', ColumnType::INTEGER, array(new FieldOptionLimit(10), new FieldOptionNull(true)));
		$this->db->add_field('posts_spamprotector', 'post_type', ColumnType::STRING, array(new FieldOptionLimit(10), new FieldOptionDefault('public'), new FieldOptionNull(true)));
		$this->db->add_field('posts_spamprotector', 'marked_by_user_id', ColumnType::INTEGER, array(new FieldOptionLimit(10), new FieldOptionNull(true)));
		$this->db->add_field('posts_spamprotector', 'post_author_id', ColumnType::INTEGER, array(new FieldOptionLimit(10), new FieldOptionNull(true)));

	}
	
	public function down(){
			
		$this->db->drop_table('posts_spamprotector');
		
	}
	
}