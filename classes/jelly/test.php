<?php

/**
 * Minimal class for setting up a database to use throughout 
 * the test's life-cylce
 *
 * @package jelly.tests
 */
class Jelly_Test
{
	public static function bootstrap()
	{
		static $init;
		
		if (!$init)
		{
			$queries = require Kohana::find_file('data', Kohana::config('jelly-test')->dump_file);
			
			// Import the data. Probably not the cleanest way, but it works.
			foreach($queries as $query)
			{
				DB::query(NULL, $query)->execute('jelly');
			}
			
			$init = TRUE;
		}
	}
}