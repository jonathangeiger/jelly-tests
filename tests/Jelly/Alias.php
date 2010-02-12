<?php

/**
 * Tests model and column aliasing
 *
 * @group jelly
 * @group jelly.alias
 */
class Jelly_Alias extends PHPUnit_Framework_TestCase
{	
	public function providerModel()
	{
		return array(
			array('aliases', 'alias'),
			array('aliases', new Model_Alias),
			array('non-existent-model', 'non-existent-model'),
		);
	}

	/**
	 * Tests aliasing models to their table.
	 * 
	 * @dataProvider providerModel
	 */
	public function testModel($expected, $field)
	{
		$this->assertEquals($expected, Jelly_Meta::table($field));
	}
		
	public function providerFields()
	{
		return array(
			array('alias', FALSE, 'alias'),
			array('alias.id', FALSE, 'id-alias'),
			array('alias.id', TRUE, 'aliases.id-alias'),
			array('alias.non-existent-column', FALSE, 'non-existent-column'),
			array('alias.non-existent-column', TRUE, 'aliases.non-existent-column'),
			array('blah.blah', TRUE, 'blah.blah'),
			array('one.two', FALSE, 'two'),
		);
	}

	/**
	 * Tests aliasing fields to their column using the normal syntax
	 * 
	 * @dataProvider providerFields
	 */
	public function testFields($field, $join, $expected)
	{
		$this->assertEquals($expected, Jelly_Meta::column($field, $join));
	}
	
	public function providerAlternateSyntaxFields()
	{
		return array(
			array('alias', 'id', FALSE, 'id-alias'),
			array('alias', 'id', TRUE, 'aliases.id-alias'),
			array(new Model_Alias, 'id', FALSE, 'id-alias'),
			array(new Model_Alias, 'id', TRUE, 'aliases.id-alias'),
			array(new Model_Alias, 'non-existent-column', FALSE, 'non-existent-column'),
			array(new Model_Alias, 'non-existent-column', TRUE, 'aliases.non-existent-column'),
			array(new Model_Alias, 'id', TRUE, 'aliases.id-alias'),
			array('blah', 'blah', TRUE, 'blah.blah'),
			array('one', 'two', FALSE, 'two'),
		);
	}

	/**
	 * Tests aliasing fields to their column using the normal syntax
	 * 
	 * @dataProvider providerAlternateSyntaxFields
	 */
	public function testAlternateSyntaxFields($model, $field, $join, $expected)
	{
		$this->assertEquals($expected, Jelly_Meta::column($model, $field, $join));
	}
	
	public function providerInsideModel()
	{
		return array(
			array(NULL, FALSE, 'aliases'),
			array('id', FALSE, 'id-alias'),
			array('id', TRUE, 'aliases.id-alias'),
			array('alias.id', FALSE, 'id-alias'),
			array('alias.id', TRUE, 'aliases.id-alias'),
			array('non-existent-column', FALSE, 'non-existent-column'),
			array('non-existent-column', TRUE, 'aliases.non-existent-column'),
			array('alias.non-existent-column', FALSE, 'non-existent-column'),
			array('alias.non-existent-column', TRUE, 'aliases.non-existent-column'),
			array('blah.blah', TRUE, 'aliases.blah'),
			array('one.two', FALSE, 'two'),
		);
	}
	
	/**
	 * Tests aliasing fields to their column from "inside" the model
	 * 
	 * @dataProvider providerInsideModel
	 */
	public function testInsideModel($field, $join, $expected)
	{
		$model = Jelly::factory('alias');
		
		$this->assertEquals($expected, $model->alias($field, $join));
	}
}