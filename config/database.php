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
	),
	'jelly-postgres' => array
	(
		'type'       => 'postgresql',
		'connection' => array(
			/**
			 * There are two ways to define a connection for PostgreSQL:
			 *
			 * 1. Full connection string passed directly to pg_connect()
			 *
			 * string   info
			 *
			 * 2. Connection parameters:
			 *
			 * string   hostname    NULL to use default domain socket
			 * integer  port        NULL to use the default port
			 * string   username
			 * string   password
			 * string   database
			 * boolean  persistent
			 * mixed    ssl         TRUE to require, FALSE to disable, or 'allow' to negotiate
			 *
			 * @link http://www.postgresql.org/docs/current/static/libpq-connect.html
			 */
			'hostname'   => 'localhost',
			'username'   => 'postgres',
			'password'   => 'password',
			'persistent' => FALSE,
			'database'   => 'jelly',
		),
		'primary_key'  => '',   // Column to return from INSERT queries, see #2188 and #2273
		'schema'       => '',
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => FALSE,
		'profiling'    => TRUE,
		'dump_file'    => 'postgres',
	),
);