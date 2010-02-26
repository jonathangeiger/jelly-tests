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
			array(Jelly::select('post'),
				  'SELECT * FROM `posts`'),
			array(Jelly::select('post')->where(':primary_key', '=', 1),
				  'SELECT * FROM `posts` WHERE `posts`.`id` = 1'),
			array(Jelly::select('post')->order_by(':primary_key', 'ASC'),
				  'SELECT * FROM `posts` ORDER BY `posts`.`id` ASC'),
			array(Jelly::select('author')->order_by('_id', 'ASC'),
				  'SELECT * FROM `authors` ORDER BY `authors`.`id` ASC'),				
			array(Jelly::select('author')->from('post'),
				  'SELECT * FROM `authors`, `posts`'),				
			array(Jelly::select('author')->with('role'),
				  'SELECT `authors`.*, `roles`.`id` AS `:role:id`, `roles`.`name` AS `:role:name` FROM `authors` '.
				  'LEFT JOIN `roles` ON (`authors`.`role_id` = `roles`.`id`)'),
		);
	}
	
	/**
	 * @dataProvider providerQueryBuildingProducesCorrectSQL
	 */
	public function testQueryBuildingProducesCorrectSQL($query, $sql)
	{
		// Compile the SQL
		$this->assertEquals($query->compile(Database::instance(Jelly_Test::GROUP)), $sql);
		
		// Execute the query and make sure no errors are thrown
		$query->execute();
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
}