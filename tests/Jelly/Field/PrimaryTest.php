<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Tests primary fields.
 *
 * @package Jelly
 * @group   jelly
 * @group   jelly.field
 * @group   jelly.field.primary
 */
class Jelly_Field_PrimaryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Primary fields cannot have allow_null set to FALSE.
	 * 
	 * @expectedException Kohana_Exception
	 */
	public function test_allow_null_throws_exception()
	{
		$field = new Jelly_Field_Primary(array('allow_null' => FALSE));
	}
}