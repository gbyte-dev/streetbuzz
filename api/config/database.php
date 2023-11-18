<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['db_user'] = array(
					'dsn'	=> '',
					'hostname' => getenv('DB_HOST'),
					'username' => getenv('DB_USER_NAME'),
					'password' => getenv('DB_PASSWORD'),
					'database' => getenv('DB_NAME'),
					'dbdriver' => 'mysqli',
					'dbprefix' => '',
					'pconnect' => FALSE,
					'db_debug' => (ENVIRONMENT !== 'production'),
					'cache_on' => FALSE,
					'cachedir' => '',
					'char_set' => 'utf8',
					'dbcollat' => 'utf8_general_ci',
					'swap_pre' => '',
					'encrypt' => FALSE,
					'compress' => FALSE,
					'stricton' => FALSE,
					'failover' => array(),
					'save_queries' => FALSE
				);		



