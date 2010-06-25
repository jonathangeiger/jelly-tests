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
	 * Provider for test_save_empty_model
	 */
	public function provider_save_empty_model()
	{
		return array(
			array(Jelly::factory('author')),
			array(Jelly::factory('category')),
		);
	}
	
	/**
	 * Tests that empty models can be saved with nothing set on them.
	 * This should work for every model that has no rules that require
	 * data to be set on them, since Jelly properly manages NULLs and
	 * default values.
	 * 
	 * @dataProvider  provider_save_empty_model
	 */
	public function test_save_empty_model($model)
	{
		$model->save();
		
		// Model should be saved, loaded, and have an id
		$this->assertTrue($model->saved());
		$this->assertTrue($model->loaded());
		$this->assertGreaterThan(0, $model->id);
		
		// Cleanup
		$this->assertTrue($model->delete());
	}
	
	/**
	 * Tests that primary keys can be changed or set manually.
	 * 
	 * We don't put this in the PrimaryTest because it has more
	 * to do with how the model handles it than the field.
	 */
	public function test_save_primary_key()
	{
		$model = Jelly::factory('post');
		$model->id = 9000;
		$model->save();
		
		// Verify data is as it should be
		$this->assertTrue($model->saved());
		$this->assertEquals(9000, $model->id);
		
		// Verify the record actually exists in the database
		$this->assertTrue(Jelly::factory('post', 9000)->loaded());
		
		// Manually re-selecting so that Postgres doesn't cause errors down the line
		$model = Jelly::factory('post', 9000);
		
		// Change it again so we can verify it works on UPDATE as well
		// This is key because Jelly got this wrong in the past
		$model->id = 9001;
		$model->save();
		
		// Verify we can't find the old record 9000
		$this->assertFalse(Jelly::factory('post', 9000)->loaded());
		
		// And that we can find the new 9001
		$this->assertTrue(Jelly::factory('post', 9001)->loaded());
		
		// Cleanup
		Jelly::factory('post', 9001)->delete();
	}
	
	/**
	 * Provider for test_add_remove
	 */
	public function provider_add_remove()
	{
		// For has many
		$author = Jelly::factory('author', 1);
		
		// For many to many
		$post = Jelly::factory('post', 1);
		
		return array(
			array($author, 'posts', 1, 1),
			array($author, 'posts', Jelly::query('post')->select(), 2),
			array($author, 'posts', array(1, 2), 2),
			array($author, 'posts', 999, 0),
			array($author, 'posts', array(999, 998, 997, 45), 0),
			array($author, 'posts', array(Jelly::factory('post')), 0),
			array($post, 'categories', 1, 1),
			array($post, 'categories', Jelly::query('category')->select(), 3),
			array($post, 'categories', array(1, 2), 2),
			array($post, 'categories', 999, 0),
			array($post, 'categories', array(999, 998, 997, 45), 0),
			array($post, 'categories', array(Jelly::factory('category')), 0),
		);
	}
	
	/**
	 * Tests Jelly_Model::add() and Jelly_Model::remove()
	 * 
	 * @dataProvider provider_add_remove
	 */
	public function test_add_remove($model, $field, $change, $changed)
	{
		// Save the original count for testing
		$count = count($model->$field);
		
		// Null out posts
		$model->remove($field, $change);
		$model->save();

		$this->assertSame($count - $changed, count($model->$field));
		
		// Should return back to normal state
		$model->add($field, $change);
		$model->save();
		
		$this->assertEquals($count, count($model->$field));
	}
	
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
	 * Provider for test_changed
	 */
	public function provider_changed()
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
				'description' => 'Description',
			));
			
		// Test without changes
		return array(
			array($alias, '_id', TRUE),
			array($alias, 'name', TRUE),
			array($alias, 'description', FALSE),
		);
	}
	
	/**
	 * Tests Jelly_Model::changed()
	 * 
	 * @dataProvider provider_changed
	 */
	public function test_changed($model, $field, $expected)
	{
		$this->assertSame($model->changed($field), $expected);
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