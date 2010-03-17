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
	 * Tests that NULL values are preserved properly when setting, 
	 * and that empty strings, NULLs, etc can be changed freely
	 */
	public function testIssue51()
	{
		$author = Jelly::factory('author');
		$author->name = NULL;
		$author->save();
		
		// Re-find the author to ensure it comes back as NULL
		$this->assertEquals(NULL, Jelly::select('author', $author->id)->name);
		
		// Save to an empty string
		$author->name = '';
		$author->save();
		
		// Re-find the author to ensure it comes back as NULL
		$this->assertEquals('', Jelly::select('author', $author->id)->name);
		
		// Cleanup
		$author->delete();
	}
}