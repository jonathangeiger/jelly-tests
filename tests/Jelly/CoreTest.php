<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Tests for core Jelly methods.
 *
 * @package Jelly
 * @group   jelly
 * @group   jelly.core
 */
class Jelly_CoreTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Provider for test_register
	 */
	public function provider_register()
	{
		return array(
			array('alias', TRUE),
			array(new Model_Alias, TRUE),
			
			// Model_Invalid exists but does not extend Jelly_Model
			array('invalid', FALSE),
			
			// Model_Unknown does not exist
			array('unknown', FALSE),
			
			// Shouldn't throw any exceptions
			array(NULL, FALSE),
		);
	}

	/**
	 * Tests Jelly::register()
	 * 
	 * @dataProvider provider_register
	 */
	public function test_register($model, $expected)
	{
		$this->assertSame(Jelly::register($model), $expected);
	}
	
	/**
	 * Tests Jelly::meta() and that meta objects are correctly returned.
	 * 
	 * @dataProvider provider_register
	 */
	public function test_meta($model, $expected)
	{
		$result = Jelly::meta($model);
		
		// Should return a Jelly_Meta instance
		if ($expected === TRUE)
		{
			$this->assertTrue($result instanceof Jelly_Meta);
			$this->assertTrue($result->initialized());
		}
		else
		{
			$this->assertFalse($result);
		}
	}
	
	/**
	 * Provider for test_alias.
	 */
	public function provider_alias()
	{
		return array(
			// Test regular aliasing
			array('alias.id', 'aliases', 'id-alias'),
			array('alias.bar', 'aliases', 'bar'),
			
			// Test meta-aliasing
			array('alias.:foreign_key', 'aliases', 'alias_id'),
			array('alias.author:foreign_key', 'aliases', 'author_id'),
			array('author:foreign_key', NULL, 'author_id'),
			
			// Test aliased fields
			array('alias._id', 'aliases', 'id-alias'),
			
			// _bar doesn't actually point to a field even though it is aliased to do so
			array('alias._bar', 'aliases', '_bar'),
			
			// These don't exist anywhere and can't be resolved to anything
			array('foo.bar', 'foo', 'bar'),
			array('foo', NULL, 'foo'),
		);
	}

	/**
	 * Tests Jelly::alias() and Jelly::meta_alias() which are two core
	 * methods for aliasing in Jelly. 
	 * 
	 * @dataProvider provider_alias
	 */
	public function test_alias($input, $table, $column)
	{
		$alias = Jelly::alias($input);
		
		$this->assertSame($table, $alias['table']);
		$this->assertSame($column, $alias['column']);
	}
	
	/**
	 * Provider for test_model_name
	 */
	public function provider_model_name()
	{
		return array(
			array('model_alias', 'alias'),
			array(new Model_Alias, 'alias'),
			array('alias', 'alias'), // Should not chomp if there is no prefix
		);
	}
	
	/**
	 * Tests Jelly::model_name().
	 * 
	 * @dataProvider provider_model_name
	 */
	public function test_model_name($model, $expected)
	{
		$this->assertSame($expected, Jelly::model_name($model));
	}
	
	/**
	 * Provider for test_class_name
	 */
	public function provider_class_name()
	{
		return array(
			array('alias', 'model_alias'),
			array(new Model_Alias, 'model_alias'),
			array('model_alias', 'model_model_alias'), // Should add prefix even if it already exists
		);
	}
	
	/**
	 * Tests Jelly::class_name()
	 * 
	 * @dataProvider provider_class_name
	 */
	public function test_class_name($model, $expected)
	{
		$this->assertSame($expected, Jelly::class_name($model));
	}
}