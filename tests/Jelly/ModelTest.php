<?php defined('SYSPATH') or die('No direct script access.');

// Ensure the test environment has been created
Jelly_Test::bootstrap();

/**
 * Tests for Jelly_Model functionality.
 *
 * @package Jelly
 * @group   jelly
 * @group   jelly.model
 */
class Jelly_ModelTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Provider for test_state
	 */
	public function provider_state()
	{
		return array(
			array(Jelly::factory('alias'), FALSE, FALSE, FALSE),
			array(Jelly::factory('alias')->set('name', 'Test'), FALSE, FALSE, TRUE),
			array(Jelly::factory('alias')->load_values(array('name' => 'Test')), TRUE, TRUE, FALSE),
			array(Jelly::factory('alias')->load_values(array('name' => 'Test'))->set('name', 'Test'), TRUE, TRUE, FALSE),
			array(Jelly::factory('alias')->load_values(array('name' => 'Test'))->set('name', 'Test2'), TRUE, FALSE, TRUE),
			array(Jelly::factory('alias')->set('name', 'Test')->clear(), FALSE, FALSE, FALSE),
			array(Jelly::factory('alias')->load_values(array('name' => 'Test'))->clear(), FALSE, FALSE, FALSE),
		);
	}
	
	/**
	 * Tests the various states a model may have are set properly.
	 * 
	 * The states are access with Jelly_Model::loaded(), 
	 * Jelly_Model::saved(), and Jelly_Model::changed().
	 * 
	 * @dataProvider  provider_state
	 */
	public function test_state($model, $loaded, $saved, $changed)
	{
		$this->assertSame($model->loaded(), $loaded);
		$this->assertSame($model->saved(), $saved);
		$this->assertSame($model->changed(), $changed);
	}
	
	/**
	 * Provider for test_original
	 */
	public function provider_original()
	{
		// Create a mock model for most of our tests
		$alias = Jelly::factory('alias')
			->load_values(array(
				'id'          => 1,
				'name'        => 'Test',
				'description' => 'Description',
			))->set(array(
				'id'          => 2,
				'name'        => 'Test2',
				'description' => 'Description2',
			));
			
		// Test without changes
		return array(
			array($alias, '_id', 1),
			array($alias, 'name', 'Test'),
			array($alias, 'description', 'Description'),
		);
	}
	
	/**
	 * Tests Jelly_Model::original()
	 * 
	 * @dataProvider provider_original
	 */
	public function test_original($model, $field, $expected)
	{
		$this->assertSame($model->original($field), $expected);
	}
	
	/**
	 * Tests Jelly_Model::clear()
	 */
	public function test_clear()
	{
		// Empty model to compare
		$one = Jelly::factory('alias');
		
		// Set and cleared model
		$two = Jelly::factory('alias')
			->load_values(array(
				'id'          => 1,
				'name'        => 'Test',
				'description' => 'Description',
			))->set(array(
				'id'          => 2,
				'name'        => 'Test2',
				'description' => 'Description2',
			))->clear();
			
		// They should match in a non-strict sense
		$this->assertEquals($one, $two);
	}
}