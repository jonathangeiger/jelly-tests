<?php

/**
 * Tests various meta methods
 *
 * @group jelly
 * @group jelly.metadata
 */
Class Jelly_MetaData extends PHPUnit_Framework_TestCase
{
	public function providerClassNameConversion()
	{
		return array(
			array('alias', 'model_alias'),
			array(new Model_Alias, 'model_alias'),
			array('model_alias', 'model_model_alias'), // Should add prefix even if it already exists
		);
	}
	
	/**
	 * @dataProvider providerClassNameConversion
	 */
	public function testClassNameConversion($input, $expected)
	{
		$this->assertEquals($expected, Jelly::class_name($input));
	}
	
	public function providerModelNameConversion()
	{
		return array(
			array('model_alias', 'alias'),
			array(new Model_Alias, 'alias'),
			array('alias', 'alias'), // Should not chomp if there is no prefix
		);
	}
	
	/**
	 * @dataProvider providerModelNameConversion
	 */
	public function testModelNameConversion($input, $expected)
	{
		$this->assertEquals($expected, Jelly::model_name($input));
	}
	
	public function testInitialization()
	{
		$this->assertEquals(TRUE,  Jelly::meta('alias')->initialized());
		$this->assertEquals(TRUE,  Jelly::meta(new Model_Alias)->initialized());
		$this->assertEquals(FALSE, Jelly::meta('non-existent-model'));
	}
}