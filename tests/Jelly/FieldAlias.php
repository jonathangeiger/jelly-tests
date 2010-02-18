<?php

/**
 * Tests aliasing fields
 *
 * @group jelly
 * @group jelly.field-alias
 */
Class Jelly_FieldAlias extends PHPUnit_Framework_TestCase
{
	public static function setUpBeforeClass()
	{
		Jelly_Test::bootstrap();
	}
	
	public function providerGet()
	{
		return array(
			array('id'),
			array('name'),
			array('password'),
			array('email'),
			array('posts'),
			array('post'),
			array('role'),
		);
	}
	
	/**
	 * @dataProvider providerGet
	 */
	public function testGet($field)
	{
		$author = Model::factory('author', 1);
		$this->assertEquals($author->$field, $author->{'_'.$field});
	}
	
	public function providerSet()
	{
		return array(
			array('id', 5),
			array('name', 'Jone'),
			array('password', 'password'),
			array('email', 'email@email.com'),
			array('posts', array(5, 9)),
			array('post', 5),
			array('role', Model::factory('role', 1)),
		);
	}
	
	/**
	 * @dataProvider providerSet
	 */
	public function testSet($field, $value)
	{
		$author = Model::factory('author', 1);
		
		// Save this for testing get()
		$original_value = $author->$field;
		
		// Fields should remain the same
		$author->$field = $value; 
		$this->assertEquals($author->$field, $author->{'_'.$field});
		
		// Test getting original values
		$this->assertEquals($author->get($field, FALSE), $author->get('_'.$field, FALSE));
	}
}