<?php

Jelly_Test::bootstrap();

/**
 * Tests various field related functionality
 *
 * @group jelly
 * @group jelly.fields
 */
class Jelly_Fields extends PHPUnit_Framework_TestCase
{
	/**
	 * Tests that timestamp auto create and auto update works
	 */
	public function testAutoTimestamp()
	{
		$post = Jelly::factory('post')
			->set(array(
				'name' => 'test post',
				'slug' => 'test-post',
			));
		
		// Save time so we can sanity check the created timestamp
		$time = time();
		
		$post->save();
		
		// Test timestamp has been set on create
		$this->assertType('integer', $post->created);
		$this->assertGreaterThanOrEqual($time, $post->created);
		$this->assertEquals('test post', $post->name);
		
		// Store created so we can prove it doesn't change on update
		$created = $post->created;
		
		sleep(1); // Wait one second to ensure the next tests are valid
		
		$post->name = 'changed';
		 
		$post->save();
		 
		$this->assertType('integer', $post->updated);
		$this->assertGreaterThan($post->created, $post->updated);
		$this->assertEquals($created, $post->created);
		$this->assertEquals('changed', $post->name);
		
		// Clean up to ensure other tests don't fail
		 $post->delete();
	}
}