<?php

/**
 * Tests input generation
 *
 * @group jelly
 * @group jelly.input
 */
Class Jelly_Input extends PHPUnit_Framework_TestCase
{
	public static function setUpBeforeClass()
	{
		Jelly_Test::bootstrap();
	}
	
	public function providerCascade()
	{
		$model = Model::factory('alias');
		return array(
			array('string', new Field_Slug),
			array('text', new Field_HTML),
			array('string', new Field_String),
			array('integer', new Field_Integer),
		);
	}
	
	/**
	 * @dataProvider providerCascade
	 */
	public function testCascade($expected, $field)
	{
		$this->assertEquals($expected, $field->input('cascade')->render());
	}
	
	public function testBelongsTo()
	{
		// We'll use this for constructing the HTML
		$model = Model::factory('post', 1);
		$expected = $model->input('author')->render();
		
		// And mock construct another one to test against
		$view = View::factory('jelly/field/belongsto');
		$view->name = 'author';
		$view->foreign = array('model' => 'author');
		$view->value = Model::factory('author', 1);
		
		// And compare
		$this->assertEquals($expected, $view->render());
	}
	
	public function testHasOne()
	{
		// We'll use this for constructing the HTML
		$model = Model::factory('author', 1);
		$expected = $model->input('post')->render();
		
		// And mock construct another one to test against
		$view = View::factory('jelly/field/hasone');
		$view->name = 'post';
		$view->foreign = array('model' => 'post');
		$view->value = Model::factory('post', 1);
		
		// And compare
		$this->assertEquals($expected, $view->render());
	}
	
	public function testHasMany()
	{
		// We'll use this for constructing the HTML
		$model = Model::factory('author', 1);
		$expected = $model->input('posts')->render();
		
		// And mock construct another one to test against
		$view = View::factory('jelly/field/hasmany');
		$view->name = 'posts';
		$view->foreign = array('model' => 'post');
		$view->ids = Model::factory('post')->load()->as_array(NULL, 'id');
		
		// And compare
		$this->assertEquals($expected, $view->render());
	}
	
	public function testManyToMany()
	{
		// We'll use this for constructing the HTML
		$model = Model::factory('post', 1);
		$expected = $model->input('categories')->render();
		
		// And mock construct another one to test against
		$view = View::factory('jelly/field/manytomany');
		$view->name = 'categories';
		$view->foreign = array('model' => 'category');
		$view->ids = Model::factory('category')->load()->as_array(NULL, 'id');

		// And compare
		$this->assertEquals($expected, $view->render());
	}
}