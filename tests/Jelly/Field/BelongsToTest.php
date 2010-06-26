<?php defined('SYSPATH') or die('No direct script access.');

// Ensure the test environment has been created
Jelly_Test::bootstrap();

/**
 * Tests BelongsTo fields.
 *
 * @package Jelly
 * @group   jelly
 * @group   jelly.field
 * @group   jelly.field.belongs_to
 */
class Jelly_Field_BelongsToTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Provider for test_get
	 */
	public function provider_get()
	{
		return array(
			array(Jelly::factory('post', 1)->get('author'), TRUE),
			array(Jelly::factory('post', 2)->get('author'), TRUE),
			array(Jelly::factory('post', 2)->get('author')->where('name', 'IS', NULL), FALSE),
			array(Jelly::factory('post', 555)->get('author'), FALSE),
			array(Jelly::factory('post')->get('author'), FALSE),
		);
	}
	
	/**
	 * Tests Jelly_Field_BelongsTo::get()
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

