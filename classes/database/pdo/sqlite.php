<?php defined('SYSPATH') or die('No direct script access.');

class Database_PDO_SQLite extends Kohana_Database_PDO 
{
	// with() queries break if this is not set for SQLite
	protected $_identifier = '`';
}
