<?php

class Model_Post extends Jelly_Model
{
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->db = Jelly_Test::GROUP;
		$meta->load_with = array('author');
		$meta->fields += array(
			'id' => new Field_Primary,
			'name' => new Field_String,
			'slug' => new Field_Slug(array(
				'unique' => TRUE
			)),
			'author' => new Field_BelongsTo,
			'approved_by' => new Field_BelongsTo(array(
				'foreign' => 'author.id',
				'column'  => 'approved_by',
			)),
			'categories' => new Field_ManyToMany,
			'status' => new Field_Enum(array(
				'choices' => array('published', 'draft', 'review'),
				'default' => 'draft'
			)),
			'created' => new Field_Timestamp(array(
				'auto_now_create' => TRUE
			)),
			'updated' => new Field_Timestamp(array(
				'auto_now_update' => TRUE
			)),
			
			// Aliased fields, for testing
			'_id' => 'id',
			'_name' => 'name',
			'_categories' => 'categories',
		);
	}
}