<?php

/**
 * Tests basic loading of data
 *
 * @group jelly
 * @group jelly.insert-and-delete
 */
Class Jelly_InsertAndDelete extends PHPUnit_Framework_TestCase
{
	public static function setUpBeforeClass()
	{
		Jelly_Test::bootstrap();
	}
	
	public function testBasic()
	{
		$total_posts = Model::factory('post')->count();
		
		$model = Model::factory('post');
		$model->id = 12345; // Verify manually specifying ID works
		$model->name = 'Inserted';
		$model->save();
		$this->assertEquals(TRUE, $model->saved());
		
		// See if we can retrieve the record
		$model = Model::factory('post', 12345);
		$this->assertEquals(TRUE, $model->loaded());
		$this->assertEquals("Inserted", $model->name);
		
		// Delete it
		$model->delete();
		$this->assertEquals(FALSE, $model->loaded());
		
		// Verify we have the same number of posts as we started with
		$this->assertEquals($total_posts, Model::factory('post')->count());
	}
	
	public function testBelongsTo()
	{		
		$model = Model::factory('post', 1);
		$original_author = $model->author;
		$model->author = 0;
		$model->save();
		$this->assertEquals(TRUE, $model->saved());
		
		// Ensure no author is associated with the record anymore
		$model = Model::factory('post', 1);
		$this->assertEquals(FALSE, Model::factory('post', 1)->author->loaded());
		
		// Set it back to the origin author
		$model = Model::factory('post', 1);
		$model->author = $original_author;
		$model->save();
		
		$this->assertEquals(TRUE, Model::factory('post', 1)->author->loaded());
	}
	
	public function testFieldAlias()
	{
		$author = Model::factory('author');
		$role = Model::factory('role')->set('name', 'Test')->save();
		$posts = array(
			Model::factory('post')->save(),
			Model::factory('post')->save(),
			Model::factory('post')->save(),
		);
		
		$author->set(array(
			'id' => 5,
			'_name' => 'Jon',
			'_post' => $posts[0],
			'role' => $role,
			'_posts' => $posts
		))->save();
		
		// Fields should remain the same
		$this->assertEquals($author->id, $author->_id);
		$this->assertEquals($author->name, $author->_name);
		$this->assertEquals($author->post, $author->_post);
		
		// Cleanup
		$author->delete();
		$role->delete();
		foreach($posts as $post)
		{
			$post->delete();
		}
	}
}