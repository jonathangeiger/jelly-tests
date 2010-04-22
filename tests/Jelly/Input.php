<?php

Jelly_Test::bootstrap();

/**
 * Tests input generation
 *
 * @group jelly
 * @group jelly.input
 */
Class Jelly_Input extends PHPUnit_Framework_TestCase
{
	public function testBelongsTo()
	{
		// We'll use this for constructing the HTML
		$model = Jelly::select('post', 1);
		$expected = $model->input('author')->render();		
		
		// And mock construct another one to test against
		$view = View::factory('jelly/field/belongsto');
		$view->name			= 'author';
		$view->foreign		= array('model' => 'author');
		$view->value		= Jelly::select('author', 1);
		$view->options		= array(1 => 'Jonathan Geiger');
		$view->attributes	= array();
		
		// And compare
		$this->assertEquals($expected, $view->render());
	}
	
	public function testHasOne()
	{
		// We'll use this for constructing the HTML
		$model = Jelly::select('author', 1);
		$expected = $model->input('post')->render();
		
		// And mock construct another one to test against
		$view = View::factory('jelly/field/hasone');
		$view->name			= 'post';
		$view->foreign		= array('model' => 'post');
		$view->value		= Jelly::select('post', 1);
		$view->options		= array(
			1 => 'First Post',
			2 => 'Second Post',
		);
		$view->attributes	= array();
		
		// And compare
		$this->assertEquals($expected, $view->render());
	}
	
	public function testHasMany()
	{
		// We'll use this for constructing the HTML
		$model = Jelly::select('author', 1);
		$expected = $model->input('posts')->render();
		
		// And mock construct another one to test against
		$view = View::factory('jelly/field/hasmany');
		$view->name			= 'posts';
		$view->foreign		= array('model' => 'post');
		$view->ids			= Jelly::select('post')->execute()->as_array(NULL, 'id');
		$view->attributes	= array();
		
		// And compare
		$this->assertEquals($expected, $view->render());
	}
	
	public function testManyToMany()
	{
		// We'll use this for constructing the HTML
		$model = Jelly::select('post', 1);
		$expected = $model->input('categories')->render();
		
		// And mock construct another one to test against
		$view = View::factory('jelly/field/manytomany');
		$view->name			= 'categories';
		$view->foreign		= array('model' => 'category');
		$view->ids			= Jelly::select('category')->execute()->as_array(NULL, 'id');
		$view->attributes	= array();
		
		// And compare
		$this->assertEquals($expected, $view->render());
	}
	
	public function testEnum()
	{
		// We'll use this for constructing the HTML
		$model = Jelly::select('post', 1);
		
		// Also test adding extra attributes
		$expected = $model->input('status', array('attributes' => array('class' => 'test')))->render();
		
		// And mock construct another one to test against
		$view = View::factory('jelly/field/enum');
		$view->name			= 'status';
		$view->value		= 'draft';
		$view->attributes	= array('class' => 'test');
		$view->choices		= array('published' => 'published', 'draft' => 'draft', 'review' => 'review');
		
		// And compare
		$this->assertEquals($expected, $view->render());
	}
}