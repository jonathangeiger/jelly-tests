<?php

/**
 * Tests basic loading of data and relationships.
 *
 * @group jelly
 * @group jelly.load
 */
Class Jelly_Load extends PHPUnit_Framework_TestCase
{
	public static function setUpBeforeClass()
	{
		Jelly_Test::bootstrap();
	}
	
	public function providerLoad()
	{
		// Ensure database is loaded
		Jelly_Test::bootstrap();
		
		return array(
			array(new Model_Post(1)),
			array(new Model_Post(array('id' => 1))),
			array(Jelly::factory('post')->load(1)),
			array(Jelly::factory('post')->load(1, 500)),
			array(Jelly::factory('post')->where('id', '=', 1)->load(NULL, 1)),
			array(Jelly::factory('post')->where('id', '=', 1)->limit(1, TRUE)->load()),
		);
	}
	
	/**
	 * @dataProvider providerLoad
	 */
	public function testLoad($model)
	{
		$this->assertEquals(TRUE, $model->loaded());
		$this->assertEquals(TRUE, $model->saved());
		$this->assertEquals(1, $model->id());
		$this->assertEquals(1, $model->id);
		$this->assertEquals('First Post', $model->name());
		$this->assertEquals('first-post', $model->slug);
		$this->assertEquals(1264985737, $model->created);
	}
	
	public function testBelongsTo()
	{
		$model = Model::factory('post', 1)->author;
		$this->assertEquals(TRUE, $model->id > 0);
		$this->assertEquals(TRUE, $model->loaded());
		$this->assertEquals(TRUE, $model->saved());
	}
	
	public function providerMany()
	{
		// Ensure database is loaded
		Jelly_Test::bootstrap();
		
		return array(
			array(Model::factory('author', 1)->posts), // Has Many
			array(Model::factory('post', 1)->categories), // Many To Many
		);
	}
	
	/**
	 * @dataProvider providerMany
	 */
	public function testMany($models)
	{
		$this->assertEquals(TRUE, $models instanceof Database_Result);
		
		foreach ($models as $model)
		{
			$this->assertEquals(TRUE, $model instanceof Jelly);
			$this->assertEquals(TRUE, $model->loaded());
			$this->assertEquals(TRUE, $model->saved());
		}
	}
}