<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Tests timestamp fields.
 *
 * @package Jelly
 * @group   jelly
 * @group   jelly.field
 * @group   jelly.field.timestamp
 */
class Jelly_Field_TimestampTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Provider for test_format
	 */
	public function provider_format()
	{
		$field = new Jelly_Field_Timestamp(array('format' => 'Y-m-d H:i:s'));
		
		return array(
			array($field, "2010-03-15 05:45:00", "2010-03-15 05:45:00"),
			array($field, 1268649900, "2010-03-15 05:45:00"),
		);
	}
	
	/**
	 * Tests for issue #113 that ensures timestamps specified 
	 * with a format are converted properly.
	 * 
	 * @dataProvider  provider_format
	 * @link  http://github.com/jonathangeiger/kohana-jelly/issues/113
	 */
	public function test_format($field, $value, $expected)
	{
		$this->assertSame($field->save(NULL, $value, FALSE), $expected);
	}
}

