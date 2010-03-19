<?php

Jelly_Test::bootstrap();

/**
 * Tests various issues that have come up
 *
 * @group jelly
 * @group jelly.issues
 */
class Jelly_Issues extends PHPUnit_Framework_TestCase
{
	/**
	 * Tests that passwords aren't rehashed when pulled from the database
	 */
	public function testIssue8()
	{
		$author = Jelly::factory('author');
		$author->load_values(array('password' => '12345'));
		$this->assertEquals('12345', $author->password);
	}
	
	/**
	 * Tests that in_db values that are set to the same value as their
	 * original aren't registered as changed values.
	 */
	public function testIssue16()
	{
		$post = Jelly::select('post', 1);
		$post->id = 1;
		$this->assertEquals(FALSE, $post->changed('id'));
	}
	
	/**
	 * Issue #71
	 */
	public function testCollectionsAreSerializable()
	{
		$posts = Jelly::select('post')->execute();
		
		$this->assertType('Jelly_Collection', $posts);
		$this->assertEquals(2, count($posts));
		
		// Serialize and unserialize
		
		$posts = unserialize(serialize($posts));
		
		$this->assertType('Jelly_Collection', $posts);
		$this->assertEquals(2, count($posts));
		$this->assertEquals('First Post', $posts[0]->name);
	}
}