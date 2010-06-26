<?php defined('SYSPATH') or die('No direct script access.');

// Ensure the test environment has been created
Jelly_Test::bootstrap();

/**
 * Tests ManyToMany fields.
 *
 * @package Jelly
 * @group   jelly
 * @group   jelly.field
 * @group   jelly.field.many_to_many
 */
class Jelly_Field_ManyToManyTest extends PHPUnit_Framework_TestCase
{	
	/**
	 * Provider for test_get
	 */
	public function provider_get()
	{
		return array(
			array(Jelly::factory('post', 1)->get('categories'), 3),
			array(Jelly::factory('post', 2)->get('categories'), 1),
			array(Jelly::factory('post', 555)->get('categories'), 0),
		);
	}
	
	/**
	 * Tests Jelly_Field_ManyToMany::get()
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

