<?php

/**
 * Tests model and column aliasing
 *
 * @group jelly
 * @group jelly.alias
 */
class Jelly_Alias extends PHPUnit_Framework_TestCase
{
	public function providerFields()
	{
		return array(
			array('alias.id', 'aliases', 'id-alias'),
			array('alias.bar', 'aliases', 'bar'),
			
			// Test meta-aliasing
			array('alias.:foreign_key', 'aliases', 'alias_id'),
			array('alias.author:foreign_key', 'aliases', 'author_id'),
			array('author:foreign_key', NULL, 'author_id'),
			
			// Test aliased fields
			array('author._id', 'authors', 'id'),
			
			// _bar doesn't actually point to a field even though it is aliased to do so
			array('author._bar', 'authors', '_bar'),
			
			// These don't exist anywhere and can't be resolved to anything
			array('foo.bar', 'foo', 'bar'),
			array('foo', NULL, 'foo'),
		);
	}

	/**
	 * Tests aliasing fields to their column using the normal syntax
	 * 
	 * @dataProvider providerFields
	 */
	public function testFields($input, $table, $column)
	{
		$alias = Jelly::alias($input);
		
		$this->assertEquals($table, $alias['table']);
		$this->assertEquals($column, $alias['column']);
	}
}