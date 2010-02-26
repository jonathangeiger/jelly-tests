<?php

Jelly_Test::bootstrap();

/**
 * Tests various methods of reading records
 *
 * @group jelly
 * @group jelly.metadata
 */
Class Jelly_Read extends PHPUnit_Framework_TestCase
{
	public function providerMultipleRecords()
	{
		return array(
			array(2, Jelly::select('post')->execute()),
			array(1, Jelly::select('post')->where(':name_key', '=', 'First Post')->execute()),
			array(1, Jelly::select('author')->execute()),
			array(0, Jelly::select('author')->where('name', '=', 'Does not exist')->execute()),
		);
	}
	
	/**
	 * @dataProvider providerMultipleRecords
	 */
	public function testMultipleRecords($count, $result)
	{
		$this->assertEquals(TRUE, $result instanceof Jelly_Collection);
		$this->assertEquals($count, count($result));
		
		// Each model should be loaded
		foreach($result as $row)
		{
			$this->assertEquals(TRUE, $row->loaded());
		}
	}
	
	public function providerSingleRecords()
	{
		return array(
			array(2, Jelly::select('post', 2)),
			array(1, Jelly::select('post')->load(1)),
			array(1, Jelly::select('post')->limit(1)->where(':name_key', '=', 'First Post')->execute()),
			array(NULL, Jelly::select('post', 555)),
		);
	}
	
	/**
	 * @dataProvider providerSingleRecords
	 */
	public function testSingleRecords($pk, $model)
	{
		$this->assertEquals(TRUE, $model instanceof Jelly_Model);
		$this->assertEquals($pk,  $model->id());
	}
	
	public function providerBelongsTo()
	{
		return array(
			array(Jelly::select('post', 2)->author, TRUE),
			array(Jelly::select('post', 1)->author, TRUE),
			array(Jelly::select('post', 555)->author, FALSE),
		);
	}
	
	/**
	 * @dataProvider providerBelongsTo
	 */
	public function testBelongsTo($model, $loaded)
	{
		$this->assertEquals(TRUE, $model instanceof Jelly_Model);
		$this->assertEquals($loaded, $model->loaded());
	}
	
	public function providerHasMany()
	{
		return array(
			array(Jelly::select('author', 1)->posts, 2),
			array(Jelly::select('author', 555)->posts, 0),
		);
	}
	
	/**
	 * @dataProvider providerHasMany
	 */
	public function testHasMany($result, $count)
	{
		$this->assertEquals(TRUE, $result instanceof Jelly_Collection);
		$this->assertEquals($count, $result->count());
	}
}