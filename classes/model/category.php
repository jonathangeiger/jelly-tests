<?php

class Model_Category extends Jelly
{
	public static function initialize($meta)
	{
		$meta->db = 'jelly';
		$meta->fields += array(
			'id' => new Jelly_Field_Primary,
			'id_alias' => new Jelly_Field_Primary('id'),
			'name' => new Jelly_Field_String,
			'posts' => new Jelly_Field_HasMany,
			'parent' => new Jelly_Field_BelongsTo(array(
				'foreign_model' => 'category',
				'column' => 'parent_id'
			))
		);
	}
}