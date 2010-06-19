<?php

class Model_Post extends Jelly_Model
{
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->db(Jelly_Test::GROUP);
		$meta->load_with(array('author'));
		$meta->fields(array(
			'id'   => Jelly::field('primary'),
			
			'name' => Jelly::field('string'),
			
			'slug' => Jelly::field('slug', array(
				'unique' => TRUE
			)),
			
			'status'  => Jelly::field('enum', array(
				'choices' => array('published', 'draft', 'review'),
				'default' => 'draft'
			)),
			
			'created' => Jelly::field('timestamp', array(
				'auto_now_create' => TRUE
			)),	
			
			'updated' => Jelly::field('timestamp', array(
				'auto_now_update' => TRUE
			)),	
			
			'author' => Jelly::field('belongsto'),
			
			'approved_by' => Jelly::field('belongsto', array(
				'foreign' => 'author.id',
				'column'  => 'approved_by',
			)),
			
			'categories'  => Jelly::field('manytomany'),
			
			// Aliased fields, for testing
			'_id' => 'id',
			'_name' => 'name',
			'_categories' => 'categories',
		);
	}
}