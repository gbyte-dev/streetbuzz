<?php 

class MyInstaller extends Installer {
	
	public function MyInstaller(){
		parent::Installer();
	}
	
	public function up(){
		
		$sql = "CREATE TABLE IF NOT EXISTS `polls` (
		  `poll_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `poll_date` int(11) unsigned NOT NULL,
		  `poll_question` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
		  `poll_is_active` tinyint(1) unsigned NOT NULL,
		  `poll_allow_user_answer` tinyint(1) unsigned NOT NULL,
		  PRIMARY KEY (`poll_id`),
		  KEY `poll_is_active` (`poll_is_active`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
		
		CREATE TABLE IF NOT EXISTS `polls_answers` (
		  `poll_answer_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `poll_id` int(11) NOT NULL,
		  `answer` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
		  `votes` int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`poll_answer_id`),
		  KEY `poll_id` (`poll_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
		
		CREATE TABLE IF NOT EXISTS `polls_user_votes` (
		  `poll_id` int(11) NOT NULL,
		  `user_id` int(11) unsigned NOT NULL,
		  `vote_date` int(11) unsigned NOT NULL,
		  `poll_answer_id` int(11) NOT NULL,
		  PRIMARY KEY (`poll_id`,`user_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" ;
				
		$this->db->execute($sql);		
	}
	
	public function down(){
			
		$this->db->drop_table('polls_user_votes');
		$this->db->drop_table('polls_answers');
		$this->db->drop_table('polls');
	}
	
}
