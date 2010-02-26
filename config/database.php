<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	'jelly-sqlite' => array
	(
		'type'       => 'pdo_sqlite',
		'connection' => array(
			/**
			 * The following options are available for PDO:
			 *
			 * string   dsn
			 * string   username
			 * string   password
			 * boolean  persistent
			 * string   identifier
			 */
			'dsn'        => 'sqlite::memory:',
			'username'   => NULL,
			'password'   => NULL,
			'persistent' => FALSE,
		),
		'table_prefix' => '',
		'charset'      => FALSE,
		'caching'      => FALSE,
		'profiling'    => TRUE,
		'dump_file'    => 'sqlite',
	),
	'jelly-mysql' => array
	(
		'type'       => 'mysql',
		'connection' => array(
			/**
			 * The following options are available for MySQL:
			 *
			 * string   hostname
			 * integer  port
			 * string   socket
			 * string   username
			 * string   password
			 * boolean  persistent
			 * string   database
			 */
			'hostname'   => 'localhost',
			'username'   => 'root',
			'password'   => '',
			'persistent' => FALSE,
			'database'   => 'jelly',
		),
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => FALSE,
		'profiling'    => TRUE,
		'dump_file'    => 'mysql',
	)
);