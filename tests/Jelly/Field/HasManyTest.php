<?php defined('SYSPATH') or die('No direct script access.');

// Ensure the test environment has been created
Jelly_Test::bootstrap();

/**
 * Tests HasMany fields.
 *
 * @package Jelly
 * @group   jelly
 * @group   jelly.field
 * @group   jelly.field.has_many
 */
class Jelly_Field_HasManyTest extends PHPUnit_Framework_TestCase
{	
	/**
	 * Provider for test_get
	 */
	public function provider_get()
	{
		return array(
			array(Jelly::factory('author', 1)->get('posts'), 2),
			array(Jelly::factory('author', 555)->get('posts'), 0),
		);
	}
	
	/**
	 * Tests Jelly_Field_HasMany::get()
	 * 
	 * @dataProvider  provider_get
	 */
	public function test_get($builder, $count)
	{
		$this->assertTrue($builder instanceof Jelly_Builder);
		
		// Select the result
		$result = $builder->select();
		
		// Should now be a collection
		$this->assertEquals(TRUE, $result instanceof Jelly_Collection);
		$this->assertEquals($count, $result->count());
		
		foreach($result as $row)
		{
			$this->assertGreaterThan(0, $row->id());
			$this->assertTrue($row->loaded());
		}
	}
}

