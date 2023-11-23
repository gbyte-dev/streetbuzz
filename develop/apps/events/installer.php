<?php 

class MyInstaller extends Installer {
	
	public function MyInstaller(){
		parent::Installer();
	}
	
	public function up(){
		
		$this->db->create_table('events');
		$this->db->add_field('events', 'event_id', ColumnType::INTEGER, array(new FieldOptionLimit(1), new FieldOptionNull(true)));
		$this->db->add_field('events', 'created_at', ColumnType::DATETIME);
		$this->db->add_field('events', 'modified_at', ColumnType::DATETIME);
		$this->db->add_field('events', 'group_id', ColumnType::INTEGER);
		$this->db->add_field('events', 'admin_id', ColumnType::INTEGER);
		$this->db->add_field('events', 'event_type', ColumnType::STRING, array(new FieldOptionLimit(20), new FieldOptionNull(true)));
		$this->db->add_field('events', 'display_type', ColumnType::STRING, array(new FieldOptionLimit(20), new FieldOptionNull(true)));
		$this->db->add_field('events', 'address', ColumnType::STRING, array(new FieldOptionLimit(255), new FieldOptionNull(true)));
		$this->db->add_field('events', 'location', ColumnType::STRING, array(new FieldOptionLimit(255), new FieldOptionNull(true)));
		$this->db->add_field('events', 'event_name', ColumnType::STRING, array(new FieldOptionLimit(255), new FieldOptionNull(true)));
		$this->db->add_field('events', 'event_description', ColumnType::STRING, array(new FieldOptionLimit(1500), new FieldOptionNull(true)));
		$this->db->add_field('events', 'start_date', ColumnType::DATE);
		$this->db->add_field('events', 'start_time', ColumnType::TIME);
		$this->db->add_field('events', 'end_date', ColumnType::DATE);
		$this->db->add_field('events', 'end_time', ColumnType::TIME);
		$this->db->add_field('events', 'time_zone', ColumnType::STRING, array(new FieldOptionLimit(255), new FieldOptionNull(true)));
		$this->db->add_field('events', 'activity_pub_date', ColumnType::DATETIME);
		$this->db->add_field('events', 'publish_now',  ColumnType::INTEGER, array(new FieldOptionLimit(1), new FieldOptionNull(true)));
		$this->db->add_field('events', 'publish_date', ColumnType::INTEGER, array(new FieldOptionLimit(1), new FieldOptionNull(true)));
		$this->db->add_field('events', 'is_private',  ColumnType::INTEGER, array(new FieldOptionLimit(1), new FieldOptionNull(true)));
		$this->db->add_field('events', 'status',  ColumnType::INTEGER, array(new FieldOptionLimit(4), new FieldOptionNull(true)));
		
		$this->db->create_table('event_attachemnts');
		$this->db->add_field('event_attachemnts', 'event_id', ColumnType::INTEGER);
		$this->db->add_field('event_attachemnts', 'user_id', ColumnType::INTEGER);
		$this->db->add_field('event_attachemnts', 'attachment_type', ColumnType::STRING, array(new FieldOptionLimit(20), new FieldOptionNull(true)));
		$this->db->add_field('event_attachemnts', '	filename', ColumnType::STRING, array(new FieldOptionLimit(255), new FieldOptionNull(true)));
		$this->db->add_field('event_attachemnts', '	file_size', ColumnType::STRING, array(new FieldOptionLimit(20), new FieldOptionNull(true)));
		$this->db->add_field('event_attachemnts', '	file_type', ColumnType::STRING, array(new FieldOptionLimit(20), new FieldOptionNull(true)));
		$this->db->add_field('event_attachemnts', '	link', ColumnType::STRING, array(new FieldOptionLimit(500), new FieldOptionNull(true)));
		$this->db->add_field('event_attachemnts', '	thumb_link', ColumnType::STRING, array(new FieldOptionLimit(500), new FieldOptionNull(true)));
		
		$this->db->create_table('event_roles');
		$this->db->add_field('event_roles', 'role_name', ColumnType::STRING, array(new FieldOptionLimit(50), new FieldOptionNull(true)));
		$this->db->add_field('event_roles', 'role_desc', ColumnType::STRING, array(new FieldOptionLimit(50), new FieldOptionNull(true)));
		$this->db->add_field('event_roles', 'group_id', ColumnType::INTEGER);
		$this->db->add_field('event_roles', 'event_id', ColumnType::INTEGER);
		$this->db->add_field('event_roles', 'created_at', ColumnType::DATE);
		$this->db->add_field('event_roles', 'modified_at', ColumnType::DATE);
		$this->db->add_field('event_roles', 'status',  ColumnType::INTEGER, array(new FieldOptionLimit(4), new FieldOptionNull(true)));
		
		$this->db->create_table('event_role_resource');
		$this->db->add_field('event_role_resource', 'user_id', ColumnType::INTEGER);
		$this->db->add_field('event_role_resource', 'group_id', ColumnType::INTEGER);
		$this->db->add_field('event_role_resource', 'role_id', ColumnType::INTEGER);
		$this->db->add_field('event_role_resource', 'created_at', ColumnType::INTEGER, array(new FieldOptionLimit(4), new FieldOptionNull(true)));

		
		$this->db->create_table('event_posts');
		$this->db->add_field('event_posts', 'post_id', ColumnType::INTEGER);
		$this->db->add_field('event_posts', 'event_id', ColumnType::INTEGER);
		$this->db->add_field('event_posts', 'created', ColumnType::DATETIME);
		
		$this->db->create_table('event_settings');
		$this->db->add_field('event_settings', 'updated_at', ColumnType::DATETIME);
		$this->db->add_field('event_settings', 'facebook_app_key', ColumnType::STRING, array(new FieldOptionLimit(255), new FieldOptionNull(true)));
		$this->db->add_field('event_settings', 'facebook_secret_key', ColumnType::STRING, array(new FieldOptionLimit(255), new FieldOptionNull(true)));
		$this->db->add_field('event_settings', 'google_app_key', ColumnType::STRING, array(new FieldOptionLimit(255), new FieldOptionNull(true)));
		$this->db->add_field('event_settings', 'google_secret_key', ColumnType::STRING, array(new FieldOptionLimit(255), new FieldOptionNull(true)));

	}
	
	public function down(){
			
		$this->db->drop_table('events');
		$this->db->drop_table('event_attachemnts');
		$this->db->drop_table('event_roles');
		$this->db->drop_table('event_role_resource');
		$this->db->drop_table('event_posts');
		$this->db->drop_table('event_settings');
	}
	
}