<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Tests for Jelly_Collection.
 *
 * @package Jelly
 * @group   jelly
 * @group   jelly.collection
 */
class Jelly_CollectionTest extends PHPUnit_Framework_TestCase
{	
	/**
	 * Provider for test type
	 */
	public function provider_construction()
	{
		$result = DB::select()->from('posts');
		
		return array(
			array(new Jelly_Collection($result->execute(Jelly_Test::GROUP), 'Model_Post'), 'Model_Post'),
			array(new Jelly_Collection($result->execute(Jelly_Test::GROUP), Jelly::factory('post')), 'Model_Post'),
			array(new Jelly_Collection($result->execute(Jelly_Test::GROUP)), FALSE),
			array(new Jelly_Collection($result->as_object()->execute(Jelly_Test::GROUP)), 'stdClass'),
			array(new Jelly_Collection($result->execute(Jelly_Test::GROUP), 'Model_Post'), 'Model_Post'),
		);
	}
	
	/**
	 * Tests Jelly_Collections properly handle database results and 
	 * different types of return values.
	 *
	 * @dataProvider  provider_construction
	 */
	public function test_construction($result, $class)
	{
		if (is_string($class))
		{
			$this->assertTrue($result->current() instanceof $class);
		}
		else
		{
			$this->assertTrue(is_array($result->current()));
		}
	}
}