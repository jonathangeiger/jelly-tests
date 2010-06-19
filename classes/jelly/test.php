<?php

/**
 * Minimal class for setting up a database to use throughout 
 * the tests' life cycle
 *
 * @package Jelly
 */
class Jelly_Test
{
	const GROUP = 'jelly-sqlite';
	
	/**
	 * Bootstraps the test environment by creating a 
	 * default set of data in the DB group specified.
	 *
	 * @return void
	 */
	public static function bootstrap()
	{
		static $init;
		
		if ( ! $init)
		{
			// Determine our dump file, which is located in the db config
			$file = Kohana::config('database.'.Jelly_Test::GROUP.'.dump_file');
			
			// The file should return an array of queries to get the DB up to speed
			$queries = require Kohana::find_file('data', $file);
			
			// Import the data. Probably not the cleanest way, but it works.
			foreach($queries as $query)
			{
				DB::query(NULL, $query)->execute(Jelly_Test::GROUP);
			}
			
			$init = TRUE;
		}
	}
}