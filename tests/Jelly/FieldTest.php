<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Provides a set of tests that apply to many different field types.
 *
 * @package Jelly
 * @group   jelly
 * @group   jelly.field
 */
class Jelly_FieldTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Provider for test_construction
	 */
	public function provider_construction()
	{
		return array(
			
			// Primary
			array(new Jelly_Field_Primary, array(
				'default' => NULL,
				'primary' => TRUE
			)),
			
			// Integers
			array(new Jelly_Field_Integer, array(
				'default' => NULL
			)),
			array(new Jelly_Field_Integer(array(
				'convert_empty' => TRUE,
			)), array(
				'null_set' => NULL,
				'default'  => NULL,
			)),
			array(new Jelly_Field_Integer(array(
				'allow_null' => FALSE,
			)), array(
				'default'  => 0,
				'null_set' => 0,
			)),
			
			// Floats
			array(new Jelly_Field_Float, array(
				'default' => NULL,
			)),
			array(new Jelly_Field_Float(array(
				'allow_null' => FALSE,
			)), array(
				'default'  => 0.0,
				'null_set' => 0.0,
			)),
			
			// Booleans
			array(new Jelly_Field_Boolean, array(
				'default'    => FALSE,
				'allow_null' => FALSE,
				'null_set'   => FALSE,
			)),
			array(new Jelly_Field_Boolean(array(
				'allow_null' => TRUE,
			)), array(
				'default' => NULL,
			)),
			
			// Strings: They default to allow_null as false
			array(new Jelly_Field_String, array(
				'default'  => '',
				'null_set' => '',
			)),
			array(new Jelly_Field_String(array(
				'convert_empty' => TRUE,
			)), array(
				'default'    => NULL,
				'allow_null' => TRUE,
			)),
			
			// Enum: They default to allow_null as false as well
			array(new Jelly_Field_Enum(array(
				'choices' => array('one', 'two', 'three')
			)), array(
				'default'  => 'one',
				'null_set' => '',
			)),
			// allow_null should be set since there is a NULL value here
			array(new Jelly_Field_Enum(array(
				'choices' => array(NULL, 'one', 'two', 'three')
			)), array(
				'allow_null' => TRUE,
				'default'    => NULL,
			)),
			// A NULL choice should be set since allow_null is TRUE
			array(new Jelly_Field_Enum(array(
				'allow_null' => TRUE,
				'choices' => array('one', 'two', 'three')
			)), array(
				'allow_null' => TRUE,
				'default'    => NULL,
				'choices' => array('' => NULL, 'one' => 'one', 'two' => 'two', 'three' => 'three')
			)),
			
			// BelongsTo
			array(new Jelly_Field_BelongsTo, array(
				'default'  => 0,
				'null_set' => 0,
			)),
			array(new Jelly_Field_BelongsTo(array(
				'convert_empty' => FALSE,
			)), array(
				'default'  => 0,
				'null_set' => 0,
			)),
			array(new Jelly_Field_BelongsTo(array(
				'allow_null' => TRUE,
			)), array(
				'default'  => NULL,
				'empty_value' => NULL,
			)),
		);
	}
	
	/**
	 * Tests various aspects of a newly constructed field.
	 * 
	 * @dataProvider provider_construction
	 */
	public function test_construction(Jelly_Field $field, $expected)
	{	
		// Ensure the following properties have been set
		foreach ($expected as $key => $value)
		{
			// NULL set is the expected value when allow_null is TRUE, skip it
			if ($key === 'null_set') continue;
			
			$this->assertSame($field->$key, $value, 'Field properties must match');
		}
		
		// Ensure that null values are handled properly
		if ($field->allow_null)
		{
			$this->assertSame($field->set(NULL), NULL, 
				'Field must return NULL when given NULL since `allow_null` is TRUE');
		}
		else
		{
			$this->assertSame($field->set(NULL), $expected['null_set'],
				'Since `allow_null` is FALSE, field must return expected value when given NULL');
		}
		
		// Ensure convert_empty works
		if ($field->convert_empty)
		{
			// allow_null must be true if convert_empty is TRUE and empty_value is NULL
			if ($field->empty_value === NULL)
			{
				$this->assertTrue($field->allow_null, 'allow_null must be TRUE since convert_empty is TRUE');
			}
			
			// Test setting a few empty values
			foreach (array(NULL, FALSE, '', '0', 0) as $value)
			{
				$this->assertSame($field->set($value), $field->empty_value);
			}
		}
	}
	
	/**
	 * Provider for test_set
	 */
	public function provider_set()
	{
		return array(
			// Primary Keys
			array(new Jelly_Field_Primary, 1, 1),
			array(new Jelly_Field_Primary, 'primary-key-string', 'primary-key-string'),
			
			// Booleans
			array(new Jelly_Field_Boolean, 1, TRUE),
			array(new Jelly_Field_Boolean, '1', TRUE),
			array(new Jelly_Field_Boolean, 'TRUE', TRUE),
			array(new Jelly_Field_Boolean, 'yes', TRUE),
			
			// Integers
			array(new Jelly_Field_Integer, 1.1, 1),
			array(new Jelly_Field_Integer, '1', 1),
			
			// Floats
			array(new Jelly_Field_Float, 1, 1.0),
			array(new Jelly_Field_Float(array('places' => 2)), 3.14157, 3.14),
			array(new Jelly_Field_Float, '3.14157', 3.14157),
			
			// Strings
			array(new Jelly_Field_String, 1, '1'),
			
			// Slugs
			array(new Jelly_Field_Slug, 'Hello, World', 'hello-world'),
			
			// Serializable data
			array(new Jelly_Field_Serialized, array(), array()),
			array(new Jelly_Field_Serialized, 'a:1:{i:0;s:4:"test";}', array('test')),
			array(new Jelly_Field_Serialized, 's:0:"";', ''),
			
			// Timestamps
			array(new Jelly_Field_Timestamp, 'Some Unparseable Time', 'Some Unparseable Time'),
			array(new Jelly_Field_Timestamp, '1264985682', 1264985682),
			array(new Jelly_Field_Timestamp, '03/15/2010 12:56:32', 1268675792),
			
			// Enumerated lists
			array(new Jelly_Field_Enum(array('choices' => array(1,2,3))), '1', '1'),
			array(new Jelly_Field_Enum(array('choices' => array(1,2,3))), '4', '4'),
			
			// BelongsTo
			array(new Jelly_Field_BelongsTo, '1', 1),
			array(new Jelly_Field_BelongsTo, 'string', 'string'),
			array(new Jelly_Field_BelongsTo, Model::factory('post', 1), 1),
		);
	}
	
	/**
	 * Tests Jelly_Field::set
	 * 
	 * @dataProvider provider_set
	 */
	public function test_set($field, $value, $expected)
	{
		$this->assertSame($expected, $field->set($value));
	}
	
	/**
	 * Data provider for test_supports
	 */
	public function provider_supports()
	{
		return array(
			array(new Jelly_Field_HasMany,    array(Jelly_Field::SAVE, Jelly_Field::ADD_REMOVE)),
			array(new Jelly_Field_BelongsTo,  array(Jelly_Field::WITH)),
			array(new Jelly_Field_ManyToMany, array(Jelly_Field::SAVE, Jelly_Field::HAS)),
			array(new Jelly_Field_Boolean,    array(FALSE))
		);
	}
	
	/**
	 * Tests Jelly_Field::supports()
	 *
	 * @dataProvider  provider_supports
	 */
	public function test_supports($field, array $supports)
	{
		foreach ($supports as $support)
		{
			if ($support)
			{
				$this->assertTrue($field->supports($support));
			}
			else
			{
				$this->assertFalse($field->supports(Jelly_Field::SAVE));
			}
		}
	}
}