<?php

/**
 * Tests field data conversion for basic types
 *
 * @group jelly
 * @group jelly.fields
 */
Class Jelly_Fields extends PHPUnit_Framework_TestCase
{
	public function providerBasicSet()
	{
		return array(
			array(new Field_Primary, 1, 1),
			array(new Field_Primary, 'primary-key-string', 'primary-key-string'),
			array(new Field_Boolean, 1, TRUE),
			array(new Field_Boolean, '1', TRUE),
			array(new Field_Boolean, 'TRUE', TRUE),
			array(new Field_Boolean, 'yes', TRUE),
			array(new Field_Integer, 1.1, 1),
			array(new Field_Integer, '1', 1),
			array(new Field_Float, 1, 1.0),
			array(new Field_Float(array('places' => 2)), 3.14157, 3.14),
			array(new Field_Float, '3.14157', 3.14157),
			array(new Field_String, 1, '1'),
			array(new Field_Slug, 'Hello, World', 'hello-world'),
			array(new Field_Serialized, array(), array()),
			array(new Field_Serialized, 'a:1:{i:0;s:4:"test";}', array('test')),
			array(new Field_Timestamp, 'Some Unparseable Time', 'Some Unparseable Time'),
			array(new Field_Timestamp, '1264985682', 1264985682),
			array(new Field_Timestamp, '03/15/2010 12:56:32', 1268675792),
			array(new Field_Enum(array('choices' => array(1,2,3))), '1', 1),
			array(new Field_Enum(array('choices' => array(1,2,3))), '4', NULL),
		);
	}
	
	/**
	 * @dataProvider providerBasicSet
	 */
	public function testBasicSet($field, $value, $expected)
	{
		$this->assertEquals($expected, $field->set($value));
	}
	
	public function providerDefaults()
	{
		$belongsto = new Field_BelongsTo;
		$hasone = new Field_HasOne;
		$hasmany = new Field_HasMany;
		$manytomany = new Field_ManyToMany;
		
		// Initialize all fields as if they were normally
		// The names here are arbitrary but based on the test
		// models just for clarity's sake
		$belongsto->initialize('post', 'author');
		$hasone->initialize('author', 'post');
		$hasmany->initialize('author', 'posts');
		$manytomany->initialize('post', 'categories');
		
		return array(
			array($belongsto->column, 'author_id'),
			array($belongsto->foreign['model'], 'author'),
			array($belongsto->foreign['column'], 'id'),
			
			array($hasone->foreign['model'], 'post'),
			array($hasone->foreign['column'], 'author_id'),
			
			array($hasone->foreign['model'], 'post'),
			array($hasone->foreign['column'], 'author_id'),
			
			array($manytomany->foreign['model'], 'category'),
			array($manytomany->foreign['column'], 'id'),
			array($manytomany->through['model'], 'categories_posts'),
			array($manytomany->through['columns'], array('post_id', 'category_id')),
		);
	}
	
	/**
	 * @dataProvider providerDefaults
	 */
	public function testDefaults($actual, $expected)
	{
		$this->assertEquals($expected, $actual);
	}
}