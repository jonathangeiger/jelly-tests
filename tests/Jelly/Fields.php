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
			// Primary Keys
			array(new Field_Primary, 1, 1),
			array(new Field_Primary, 'primary-key-string', 'primary-key-string'),
			
			// Booleans
			array(new Field_Boolean, 1, TRUE),
			array(new Field_Boolean, '1', TRUE),
			array(new Field_Boolean, 'TRUE', TRUE),
			array(new Field_Boolean, 'yes', TRUE),
			
			// Integers
			array(new Field_Integer, 1.1, 1),
			array(new Field_Integer, '1', 1),
			array(new Field_Integer, NULL, NULL), // NULLs should be preserved
			
			// Floats
			array(new Field_Float, 1, 1.0),
			array(new Field_Float(array('places' => 2)), 3.14157, 3.14),
			array(new Field_Float, '3.14157', 3.14157),
			array(new Field_Float, NULL, NULL), // NULLs should be preserved
			
			// Strings
			array(new Field_String, 1, '1'),
			array(new Field_String, NULL, NULL), // NULLs should be preserved
			
			// Slugs
			array(new Field_Slug, 'Hello, World', 'hello-world'),
			array(new Field_Slug, NULL, NULL), // NULLs should be preserved
			
			// Serializable data
			array(new Field_Serialized, array(), array()),
			array(new Field_Serialized, 'a:1:{i:0;s:4:"test";}', array('test')),
			
			// Timestamps
			array(new Field_Timestamp, 'Some Unparseable Time', 'Some Unparseable Time'),
			array(new Field_Timestamp, '1264985682', 1264985682),
			array(new Field_Timestamp, '03/15/2010 12:56:32', 1268675792),
			array(new Field_Timestamp, NULL, NULL), // NULLs should be preserved
			
			// Enumerated lists
			array(new Field_Enum(array('choices' => array(1,2,3))), '1', 1),
			array(new Field_Enum(array('choices' => array(1,2,3))), '4', NULL),
			
			// Belongs To
			array(new Field_BelongsTo, 1, 1),
			array(new Field_BelongsTo, Jelly::select('post', 1), 1),
			array(new Field_BelongsTo, Jelly::factory('post'), NULL),
			
			// Has One
			array(new Field_HasOne, 1, 1),
			array(new Field_HasOne, Jelly::select('post', 1), 1),
			array(new Field_HasOne, Jelly::factory('post'), NULL),
			
			// Has Many
			array(new Field_HasMany, 1, array(1)),
			array(new Field_HasMany, Jelly::select('post', 1), array(1)),
			array(new Field_HasMany, Jelly::factory('post'), array()),
			array(new Field_HasMany, array(1, 2), array(1, 2)),
			array(new Field_HasMany, array(Jelly::select('post', 1), 2), array(1, 2)),
			array(new Field_HasMany, Jelly::select('category')->execute(), array(1, 2, 3)),
			
			// Many To Many
			array(new Field_ManyToMany, 1, array(1)),
			array(new Field_ManyToMany, Jelly::select('post', 1), array(1)),
			array(new Field_ManyToMany, Jelly::factory('post'), array()),
			array(new Field_ManyToMany, array(1, 2), array(1, 2)),
			array(new Field_ManyToMany, array(Jelly::select('post', 1), 2), array(1, 2)),
			array(new Field_ManyToMany, Jelly::select('category')->execute(), array(1, 2, 3)),
		);
	}
	
	/**
	 * @dataProvider providerBasicSet
	 */
	public function testBasicSet($field, $value, $expected)
	{
		$this->assertEquals($expected, $field->set($value));
	}
	
	public function providerNullSet()
	{
		return array(
			array(new Field_Integer(array('null' => TRUE))),
			array(new Field_Float(array('null' => TRUE))),
			array(new Field_String(array('null' => TRUE))),
			array(new Field_Slug(array('null' => TRUE))),
			array(new Field_Text(array('null' => TRUE))),
			array(new Field_Timestamp(array('null' => TRUE))),
			array(new Field_Enum(array('null' => TRUE, 'choices' => array('one', 'two')))),
		);
	}
	
	/**
	 * @dataProvider providerNullSet
	 */
	public function testNullSet($field)
	{
		$this->assertEquals(NULL, $field->set(''));
		$this->assertEquals(NULL, $field->set(NULL));
		$this->assertEquals(NULL, $field->set(FALSE));
	}
	
	/**
	 * Tests that timestamps specified with a format are converted properly.
	 */
	public function testIssue113()
	{
		$field = new Field_Timestamp(array('format' => 'Y-m-d H:i:s'));
		$this->assertEquals("2010-03-15 05:45:00", $field->save(NULL, "2010-03-15 05:45:00", FALSE));
		$this->assertEquals("2010-03-15 05:45:00", $field->save(NULL, 1268649900, FALSE));
	}
}