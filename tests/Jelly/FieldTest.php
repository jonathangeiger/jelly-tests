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
				'null_set' => ''
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
				'choices' => array(NULL, 'one', 'two', 'three')
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
			// allow_null must be true if convert_empty is
			$this->assertTrue($field->allow_null, 'allow_null must be TRUE since convert_empty is TRUE');
			
			// Test setting a few empty values
			foreach (array(NULL, FALSE, '', '0', 0) as $value)
			{
				$this->assertSame($field->set($value), NULL);
			}
		}
	}
}