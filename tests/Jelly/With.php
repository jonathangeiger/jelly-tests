<?php

/**
 * Tests with autoloading
 *
 * @group jelly
 * @group jelly.with
 */
Class Jelly_With extends PHPUnit_Framework_TestCase
{
	public static function setUpBeforeClass()
	{
		Jelly_Test::bootstrap();
	}
	
	public function providerWith()
	{
		Jelly_Test::bootstrap();
		
		$model = Model::factory('post');
		return array(
			array($model->with('author')->load(1)->author),
			array($model->with('author:role')->load(1)->author),
			array($model->with('author:role')->load(1)->author->role),
		);
	}
	/**
	 * @dataProvider providerWith
	 */
	public function testWith($model)
	{
		// empty() id()s should not be loaded
		if ($model->id())
		{
			// Verify author is loaded and saved
			$this->assertEquals(TRUE, $model->loaded());
			$this->assertEquals(TRUE, $model->saved());
		}
		else
		{
			// Verify author is loaded and saved
			$this->assertEquals(FALSE, $model->loaded());
			$this->assertEquals(FALSE, $model->saved());
		}
	}
	
	public function providerWithNonExistentRecord()
	{
		Jelly_Test::bootstrap();
		
		$model = Model::factory('post');
		return array(
			array($model->with('author')->load(12345)->author),
			array($model->with('author:role')->load(12345)->author),
			array($model->with('author:role')->load(12345)->author->role),
		);
	}
	
	/**
	 * @dataProvider providerWithNonExistentRecord
	 */
	public function testWithNonExistentRecord($model)
	{
		// Relation shouldn't be loaded
		$this->assertEquals(FALSE, $model->loaded());
		$this->assertEquals(FALSE, $model->saved());
	}
	
	/**
	 * Ensures aliased come back properly named. Tests a mistake I noticed while working on Jelly.
	 */
	public function testWithAlias()
	{
		$model = Model::factory('post')->with('author')->load(1)->author;
		
		// Role should be set to something
		$this->assertEquals(TRUE, $model->role->loaded());
	}
}