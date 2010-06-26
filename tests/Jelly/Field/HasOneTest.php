<?php defined('SYSPATH') or die('No direct script access.');

// Ensure the test environment has been created
Jelly_Test::bootstrap();

/**
 * Tests HasOne fields.
 *
 * @package Jelly
 * @group   jelly
 * @group   jelly.field
 * @group   jelly.field.has_one
 */
class Jelly_Field_HasOneTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Provider for test_get
	 */
	public function provider_get()
	{
		return array(
			array(Jelly::factory('author', 1)->get('post'), TRUE),
			array(Jelly::factory('author', 2)->get('post'), FALSE),
			array(Jelly::factory('author', 555)->get('post'), FALSE),
		);
	}
	
	/**
	 * Tests Jelly_Field_HasOne::get()
	 * 
	 * @dataProvider  provider_get
	 */
	public function test_get($builder, $loaded)
	{
		$this->assertTrue($builder instanceof Jelly_Builder);
		
		// Load the model
		$model = $builder->select();
		
		// Ensure it's loaded if it should be
		$this->assertSame($loaded, $model->loaded());
	}
}

