<?php defined('SYSPATH') or die('No direct script access.');

// Ensure the test environment has been created
Jelly_Test::bootstrap();

/**
 * Tests for Jelly_Builder SELECT functionality.
 *
 * @package Jelly
 * @group   jelly
 * @group   jelly.builder
 * @group   jelly.builder.select
 */
class Jelly_Builder_SelectTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Provider for test_multiple_select.
	 */
	public function provider_multiple_select()
	{
		return array(
			array(Jelly::query('post'), 2),
			array(Jelly::query('post')->where(':primary_key', '=', 1), 1),
			array(Jelly::query('post')->order_by(':primary_key', 'ASC'), 2),
			array(Jelly::query('post')->where(':primary_key', 'IS', 'NULL'), 0),
			
			// Test aliasing columns
			array(Jelly::query('author')->order_by('_id', 'ASC'), 3),
			
			// This does not resolve to any model, but should still work
			array(Jelly::query('categories_posts')->where('post:foreign_key', '=', 1), 3, FALSE),
			
			// This should join both author and approved by author.
			// Since they are both from the same model, we shouldn't
			// have any funny things happening
			array(Jelly::query('post')->with('approved_by'), 2),

			// Miscellaneous things
			array(Jelly::query('post')->select_column('TRIM("_slug")', 'trimmed_slug'), 2),
			array(Jelly::query('author')->with('role'), 3),
		);
	}
	
	/**
	 * Tests basic SELECT functionality and that collections are returned
	 * relatively sane.
	 *
	 * @dataProvider  provider_multiple_select
	 */
	public function test_multiple_select($result, $count, $is_model = TRUE)
	{
		// Ensure the count matches a count() query
		$this->assertEquals($result->count(Jelly_Test::GROUP), $count);
		
		// We can now get our collection
		$result = $result->select(Jelly_Test::GROUP);
		
		// Ensure we have a collection and our counts match
		$this->assertTrue($result instanceof Jelly_Collection);
		$this->assertEquals(count($result), $count);
		
		// Ensure we can loop through them and all models are loaded
		$verify = 0;
		
		foreach ($result as $model)
		{
			if ($is_model)
			{
				$this->assertTrue($model->loaded());
				$this->assertTrue($model->saved());
				$this->assertTrue($model->id > 0);
			}
			
			$verify++;
		}
		
		// Ensure the loop and result was the same
		$this->assertEquals($verify, $count);
	}
	
	/**
	 * Provider for test_single_select.
	 */
	public function provider_single_select()
	{
		return array(
			array(Jelly::query('post', 1)->select(), TRUE),
			array(Jelly::query('post', 0)->select(), FALSE),
			array(Jelly::query('post')->where(':primary_key', '=', 1)->limit(1)->select(), TRUE),
			array(Jelly::query('post', 1)->order_by(':primary_key', 'ASC')->select(), TRUE),
		);
	}
	
	/**
	 * Tests returning a model directly from a SELECT.
	 *
	 * @dataProvider  provider_single_select
	 */
	public function test_single_select($model, $exists)
	{
		$this->assertTrue($model instanceof Jelly_Model);
		
		if ($exists)
		{
			$this->assertTrue($model->loaded());
			$this->assertTrue($model->saved());
			$this->assertTrue($model->id > 0);
		}
		else
		{
			$this->assertFalse($model->loaded());
			$this->assertFalse($model->saved());
			$this->assertTrue($model->id === $model->meta()->field('id')->default);
		}
	}
	
	/**
	 * Provider for test_as_object
	 */
	public function provider_as_object()
	{
		return array(
			array(Jelly::query('post')->select(), 'Model_Post'),
			array(Jelly::query('post')->as_object('Model_Post')->select(), 'Model_Post'),
			array(Jelly::query('post')->as_object(TRUE)->select(), 'Model_Post'),
			array(Jelly::query('post')->as_object('stdClass')->select(), 'stdClass'),
			array(Jelly::query('post')->as_assoc()->select(), FALSE),
			array(Jelly::query('post')->as_object(FALSE)->select(), FALSE),
		);
	}
	
	/**
	 * Tests basic with() functionality
	 */
	public function test_with()
	{
		$query = Jelly::query('post')->with('approved_by')->select();
		
		// Ensure we find the proper columns in the result
		foreach ($query->as_array() as $array)
		{
			$this->assertTrue(array_key_exists(':author:id', $array));
			$this->assertTrue(array_key_exists(':approved_by:id', $array));
		}
		
		// Ensure we can actually access the models
		foreach ($query as $model)
		{
			$this->assertTrue($model->author instanceof Model_Author);
		}
	}
	
	/**
	 * Tests Jelly_Builder::as_object()
	 *
	 * @dataProvider  provider_as_object
	 */
	public function test_as_object($result, $class)
	{
		if ($class)
		{
			$this->assertTrue($result->current() instanceof $class);
		}
		else
		{
			$this->assertTrue(is_array($result->current()));
		}
	}
	
	/**
	 * Test for issue #58 that ensures count() uses any load_with
	 * conditions specified.
	 */
	public function test_count_uses_load_with()
	{
		$count = Jelly::query('post')
			// Where condition includes a column from joined table
			// this will cause a SQL error if load_with hasn't been taken into account
			->where(':author.name', '=', 'Jonathan Geiger')
			->count();
		
		$this->assertEquals(2, $count);
	}
	
	/**
	 * Test for Issue #95. This only fails when testing on Postgres.
	 */
	public function test_count_works_on_postgres()
	{
		// Should discard the select and order_by clauses
		Jelly::query('post')
			 ->select_column('foo')
			 ->order_by('foo')
			 ->count();
	}
}