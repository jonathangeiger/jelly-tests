<?php

Jelly_Test::bootstrap();

/**
 * Tests various ways of changing model data
 *
 * @group jelly
 * @group jelly.create-and-update
 */
Class Jelly_CreateAndUpdate extends PHPUnit_Framework_TestCase
{	
	/**
	 * Tests that empty models can be created with nothing set on them
	 */
	public function testCreatingEmptyModel()
	{
		$model = Jelly::factory('author')->save();
		
		// The saving act itself used to create an SQL syntax error 
		// in Jelly, so that acts as an assertion as well.
		$this->assertTrue($model->saved());
		$this->assertGreaterThan(0, $model->id);
		
		// Cleanup
		$model->delete();
	}
	
	/**
	 * Tests that passwords change only when they are actually changed.
	 * Addresses issue #8
	 */
	public function testPassword()
	{
		$model = Jelly::factory('author')->save();
		
		// Set the password, which should be hashed on save()
		$model->password = 'abc';
		$model->save();
		
		// The password should match this sha1 hash
		$this->assertEquals('a9993e364706816aba3e25717850c26c9cd0d89d', $model->password);
		
		// Test updates now
		$model->password = '12345';
		$model->save();
		
		// The password should match this sha1 hash
		$this->assertEquals('8cb2237d0679ca88db6464eac60da96345513964', $model->password);
		
		// Cleanup
		$model->delete();
	}
	
	/**
	 * Tests that primary keys can be changed or set
	 */
	public function testPrimaryKey()
	{
		$model = Jelly::factory('post');
		$model->id = 9000;
		$model->save();
		
		// Verify data is as it should be
		$this->assertTrue($model->saved());
		$this->assertEquals(9000, $model->id);
		
		// Verify the record actually exists in the database
		$this->assertTrue(Jelly::select('post', 9000)->loaded());
		
		// Change it again so we can verify it works on UPDATE as well
		// This is key because Jelly got this wrong in the past
		$model->id = 9001;
		$model->save();
		
		// Verify we can't find the old record 9000
		$this->assertFalse(Jelly::select('post', 9000)->loaded());
		
		// And that we can find the new 9001
		$this->assertTrue(Jelly::select('post', 9001)->loaded());
		
		// Cleanup
		$model->delete();
	}
	
	/**
	 * Tests that belongs to relationships are properly set
	 */
	public function testBelongsTo()
	{
		$model = Jelly::factory('author');
		$foreign = Jelly::factory('role')->set('name', 'Test')->save();
		$model->role = $foreign;
		$model->save();
		
		// Verify we can retrieve the role object
		$this->assertEquals($model->role->id, Jelly::select('author', $model->id)->role->id);
		$this->assertGreaterThan(0, $model->role->id);
		
		// Change to a new foreign object and attempt an update
		$old_foreign = $foreign;
		$foreign = Jelly::factory('role')->set('name', 'Test')->save();
		
		$model->role = $foreign;
		$model->save();
		
		// Once again, verify we can retrieve the role object
		$this->assertEquals($model->role->id, Jelly::select('author', $model->id)->role->id);
		$this->assertGreaterThan(0, $model->role->id);
		
		// Cleanup
		$model->delete();
		$foreign->delete();
		$old_foreign->delete();
	}
	
	/**
	 * Test that HasOne relationships are properly set
	 */
	public function testHasOne()
	{
		$model = Jelly::factory('author');
		$foreign = Jelly::factory('post')->save();
		$model->post = $foreign;
		$model->save();
		
		// Verify we can retrieve the foreign object
		$this->assertEquals($model->post->id, Jelly::select('author', $model->id)->post->id);
		$this->assertGreaterThan(0, $model->post->id);
		
		// Change to a new foreign object and attempt an update
		$old_foreign = $foreign;
		$foreign = Jelly::factory('post')->save();
		
		$model->post = $foreign;
		$model->save();
		
		// Once again, verify we can retrieve the foreign object
		$this->assertEquals($model->post->id, Jelly::select('author', $model->id)->post->id);
		$this->assertGreaterThan(0, $model->post->id);
		
		// Cleanup
		$model->delete();
		$foreign->delete();
		$old_foreign->delete();
	}
	
	/**
	 * Tests that HasMany relationships are properly set
	 */
	public function testHasMany()
	{
		$model = Jelly::factory('author');
		$foreign = array(
			Jelly::factory('post')->save(), 
			Jelly::factory('post')->save(), 
			Jelly::factory('post')->save()
		);
		
		$model->posts = $foreign;
		$model->save();
		
		// Ensure we have the correct number of related models
		$this->assertEquals(count($foreign), count($model->posts));
		
		// Compare all found relationships
		foreach($foreign as $i => $related)
		{
			$this->assertEquals($model->posts[$i]->id, Jelly::select('author', $model->id)->posts[$i]->id);
			$this->assertGreaterThan(0, $model->posts[$i]->id);
		}
		
		// Change to a new foreign object and attempt an update
		$old_foreign = $foreign;
		$foreign = $foreign = array(
			Jelly::factory('post')->save(), 
			Jelly::factory('post')->save(), 
		);
		
		$model->posts = $foreign;
		$model->save();
		
		// Ensure we have the correct number of related models
		$this->assertEquals(count($foreign), count($model->posts));
		
		// Once again, verify we can retrieve the related object
		foreach($foreign as $i => $related)
		{
			$this->assertEquals($model->posts[$i]->id, Jelly::select('author', $model->id)->posts[$i]->id);
			$this->assertGreaterThan(0, $model->posts[$i]->id);
		}
		
		// Cleanup
		$model->delete();
		
		foreach($foreign as $row)
		{
			$row->delete();
		}
		
		foreach($old_foreign as $row)
		{
			$row->delete();
		}
	}
	
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
	
	
	/**
	 * Tests creating/updating a new model, verifying 
	 * that certain states are changed properly.
	 */
	public function testModelChangesStateAsExpected()
	{
		$model = Jelly::factory('post');
		$model->_name = 'Foo';
		
		// Keys should now report as changed
		$this->assertTrue($model->changed('name'));
		$this->assertTrue($model->changed('_name'));
		
		$model->save();
		
		// Verify data is as it should be
		$this->assertTrue($model->saved());
		$this->assertTrue($model->loaded());
		$this->assertEquals(array(), $model->changed());
		$this->assertEquals('Foo', $model->name);
		
		// ID should be set
		$this->assertGreaterThan(0, $model->id);
		
		// Set data again, nothing should report as changed
		$model->name = 'Foo';
		$this->assertTrue($model->saved());
		$this->assertEquals(array(), $model->changed());
		
		// Change and verify we can update
		$model->name = 'Bar';
		$this->assertFalse($model->saved());
		
		// Update now
		$model->save();
		
		// Verify data is as it should be
		$this->assertTrue($model->saved());
		$this->assertTrue($model->loaded());
		$this->assertEquals(array(), $model->changed());
		$this->assertEquals('Bar', $model->name);
		
		// Cleanup
		$model->delete();
	}
}