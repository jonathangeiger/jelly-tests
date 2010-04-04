<?php

/**
 * Minimal class for setting up a database to use throughout 
 * the test's life-cylce
 *
 * @package Jelly
 */
class Jelly_Test
{
	const GROUP = 'jelly-sqlite';
	
	public static function bootstrap()
	{
		static $init;
		
		if ( ! $init)
		{
			$file = Kohana::config('database.'.Jelly_Test::GROUP.'.dump_file');
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