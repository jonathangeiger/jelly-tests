<?php

Jelly_Test::bootstrap();

/**
 * Tests select queries
 *
 * @group jelly
 * @group jelly.builder
 */
class Jelly_QueryBuilder extends PHPUnit_Framework_TestCase
{
	public function providerQueryBuildingProducesCorrectSQL()
	{
		return array(
			array(Jelly::select('post')),
			array(Jelly::select('post')->where(':primary_key', '=', 1)),
			array(Jelly::select('post')->order_by(':primary_key', 'ASC')),
			array(Jelly::select('author')->order_by('_id', 'ASC')),				
			array(Jelly::select('author')->from('post')),				
			array(Jelly::select('author')->with('role')),
			// This does not resolve to any model, but should still work
			array(Jelly::select('categories_posts')
			      ->where('post:foreign_key', '=', 1))
		);
	}
	
	/**
	 * @dataProvider providerQueryBuildingProducesCorrectSQL
	 */
	public function testQueryBuildingProducesCorrectSQL($query)
	{
		// Execute the query and make sure no errors are thrown
		// We have to manually specify the group because some
		// queries are not attached to a model and use the wrong group
		// I'm not sure if this is "correct"
		$query->execute(Jelly_Test::GROUP);
	}
	
	public function providerQueryBuildingReturnsCorrectResult()
	{
		return array(
			array(Jelly::select('post')->execute(), 
				  'Jelly_Collection'),
			array(Jelly::select('post')->where(':primary_key', '=', 1)->execute(),
				  'Jelly_Collection'),
			array(Jelly::select('post')->order_by(':primary_key', 'ASC')->execute(),
				  'Jelly_Collection'),
			array(Jelly::select('author')->limit(1)->execute(),
				  'Jelly_Model'),
			array(Jelly::select('author')->load(1),
				  'Jelly_Model'),
		);
	}
	
	/**
	 * @dataProvider providerQueryBuildingReturnsCorrectResult
	 */
	public function testQueryBuildingReturnsCorrectResult($result, $type)
	{
		$this->assertEquals(TRUE, is_a($result, $type));
	}
	
	/**
	 * Test for issue #58
	 */
	public function testCountUsesLoadsWith()
	{
		$count = Jelly::select('post')
			// Where condition includes a column from joined table
			// this will cause a SQL error if load_with hasn't been taken into account
			->where('author.name', '=', 'Jonathan Geiger')
			->count();
		
		$this->assertEquals(2, $count);
	}
}