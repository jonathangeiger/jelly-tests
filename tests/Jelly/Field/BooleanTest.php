<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Tests primary fields.
 *
 * @package Jelly
 * @group   jelly
 * @group   jelly.field
 * @group   jelly.field.boolean
 */
class Jelly_Field_BooleanTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Boolean fields cannot have convert_empty set to TRUE.
	 * 
	 * @expectedException Kohana_Exception
	 */
	public function test_convert_empty_throws_exception()
	{
		$field = new Jelly_Field_Boolean(array('convert_empty' => TRUE));
	}
}