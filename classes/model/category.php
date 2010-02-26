<?php

class Model_Category extends Jelly_Model
{
	public static function initialize($meta)
	{
		$meta->db = Jelly_Test::GROUP;
		$meta->fields += array(
			'id' => new Field_Primary,
			'id_alias' => new Field_Primary('id'),
			'name' => new Field_String,
			'posts' => new Field_HasMany,
			'parent' => new Field_BelongsTo(array(
				'foreign_model' => 'category',
				'column' => 'parent_id'
			))
		);
	}
}