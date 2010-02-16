<?php

/**
 * Tests add()ing relationships
 *
 * @group jelly
 * @group jelly.addremove
 */
Class Jelly_AddRemove extends PHPUnit_Framework_TestCase
{
	public static function setUpBeforeClass()
	{
		Jelly_Test::bootstrap();
	}
	
	public function providerHasMany()
	{
		Jelly_Test::bootstrap();
		
		return array(
			array(1, 1),
			array(Model::factory('post')->load(), 2),
			array(array(1, 2), 2),
			array('does not exist', 0),
			array(array('do', 'not', 'exist', 45), 0),
			array(array(Model::factory('post')), 0),
		);
	}
	
	/**
	 * @dataProvider providerHasMany
	 */
	public function testHasMany($change, $changed)
	{
		$author = Model::factory('author', 1);
		
		// Save the original count for testing
		$posts = count($author->posts);
		
		// Null out posts
		$author->remove('posts', $change);
		$author->save();

		$this->assertEquals($posts - $changed, count($author->posts));
		
		// Should return back to normal state
		$author->add('posts', $change);
		$author->save();
		
		$this->assertEquals($posts, count($author->posts));
	}
	
	public function providerManyToMany()
	{
		Jelly_Test::bootstrap();
		
		return array(
			array(1, 1),
			array(Model::factory('category')->load(), 3),
			array(array(1, 2), 2),
			array('does not exist', 0),
			array(array('do', 'not', 'exist', 45), 0),
			array(array(Model::factory('category')), 0),
		);
	}
	
	/**
	 * @dataProvider providerManyToMany
	 */
	public function testManyToMany($change, $changed)
	{
		$post = Model::factory('post', 1);
		
		// Save the original count for testing
		$categories = count($post->categories);
		
		// Null out categories
		$post->remove('categories', $change);
		$post->save();
		
		$this->assertEquals($categories - $changed, count($post->categories));
		
		// Should return back to normal state
		$post->add('categories', $change);
		$post->save();
		
		$this->assertEquals($categories, count($post->categories));
	}
}