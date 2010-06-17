<?php

Jelly_Test::bootstrap();

/**
 * Tests model inheritance
 *
 * @group jelly
 * @group jelly.inheritance
 */
Class Jelly_Inheritance extends PHPUnit_Framework_TestCase
{
	function testPolymorphicListing()
	{
		$files = Jelly::select('file')->execute();
		
		$this->assertType('Model_File_Image', $files[0]);
		$this->assertType('Model_File_Text', $files[1]);
	}
	
	function testSubclassListing()
	{
		$files = Jelly::select('file_image')->compile(Database::instance(Jelly_Test::GROUP));
		
		$this->assertType('Model_File_Image', $files[0]);
		$this->assertEquals(1, count($files));
	}
}