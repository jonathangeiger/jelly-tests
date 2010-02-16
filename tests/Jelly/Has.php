<?php

/**
 * Tests with autoloading
 *
 * @group jelly
 * @group jelly.has
 */
Class Jelly_Has extends PHPUnit_Framework_TestCase
{
	public static function setUpBeforeClass()
	{
		Jelly_Test::bootstrap();
	}
	
	public function providerHasOne()
	{
		Jelly_Test::bootstrap();
		
		$model = Model::factory('author', 1);
		$post1 = Model::factory('post', 1);
		$post2 = Model::factory('post', 2);
		
		return array(
			array($model->has('post', 1), TRUE),
			array($model->has('post', 2), TRUE),
			array($model->has('post', 3), FALSE),
			array($model->has('post', $post1), TRUE),
			array($model->has('post', $post2), TRUE),
			array($model->has('post', Model::factory('post')), FALSE),
			array($model->has('post', Model::factory('post')->load()), TRUE),
		);
	}
	
	/**
	 * @dataProvider providerHasOne
	 */
	public function testHasOne($result, $expected)
	{
		$this->assertEquals($expected, $result);
	}
	
	public function providerHasMany()
	{
		Jelly_Test::bootstrap();
		
		$model = Model::factory('author', 1);
		$post1 = Model::factory('post', 1);
		$post2 = Model::factory('post', 2);
		
		return array(
			array($model->has('posts', 1), TRUE),
			array($model->has('posts', 2), TRUE),
			array($model->has('posts', 3), FALSE),
			array($model->has('posts', $post1), TRUE),
			array($model->has('posts', $post2), TRUE),
			array($model->has('posts', Model::factory('post')), FALSE),
			array($model->has('posts', Model::factory('post')->load()), TRUE),
		);
	}
	
	/**
	 * @dataProvider providerHasMany
	 */
	public function testHasMany($result, $expected)
	{
		$this->assertEquals($expected, $result);
	}
	
	public function providerManyToMany()
	{
		Jelly_Test::bootstrap();
		
		$model = Model::factory('post', 1);
		$cat1 = Model::factory('category', 1);
		$cat2 = Model::factory('category', 2);
		
		return array(
			array($model->has('categories', Model::factory('category')->load()), TRUE),
			array($model->has('categories', Model::factory('category', array('name' => 'Does not exist'))), FALSE),
			array($model->has('categories', $cat1), TRUE),
			array($model->has('categories', $cat2), TRUE),
			array($model->has('categories', Model::factory('category')), FALSE),
		);
	}
	
	/**
	 * @dataProvider providerManyToMany
	 */
	public function testManyToMany($result, $expected)
	{
		$this->assertEquals($expected, $result);
	}
	
}