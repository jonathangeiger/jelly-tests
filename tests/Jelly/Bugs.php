<?php

/**
 * Tests for various bugs that have cropped up
 *
 * @group jelly
 * @group jelly.bugs
 */
Class Jelly_Bugs extends PHPUnit_Framework_TestCase
{
	public static function setUpBeforeClass()
	{
		Jelly_Test::bootstrap();
	}

	/**
	 * This fails only on MySQL because of the way Kohana handles MySQL results
	 */
	public function testSerializationWithIssue6()
	{
		$author = Model::factory('author', 1);
		// Retrieve posts, which is now cached
		$author->posts;
		
		$author = unserialize(serialize($author));
		
		// In MySQL results, this fails if __sleep is not implemented properly
		foreach ($author->posts as $post)
		{
			$post;
		}
		
		// Should always be a cached result
		$this->assertEquals(TRUE, $author->posts instanceof Database_Result_Cached);
	}
	
	/**
	 * Tests executing queries as different types
	 */
	public function testExecutingWithDifferentTypes()
	{
		$author = Model::factory('author');
		$jelly = $author->execute(Database::SELECT);
		$stdClass = $author->execute(Database::SELECT, TRUE);
		$array = $author->execute(Database::SELECT, FALSE);
		
		$this->assertEquals(TRUE, $jelly[0] instanceof Jelly);
		$this->assertEquals(TRUE, $stdClass[0] instanceof stdClass);
		$this->assertEquals(TRUE, is_array($array[0]));
	}
}