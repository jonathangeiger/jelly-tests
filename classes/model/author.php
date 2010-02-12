<?php

class Model_Author extends Jelly
{
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->db = 'jelly';
		$meta->fields += array(
			'id' => new Field_Primary,
			'name' => new Field_String,
			'email' => new Field_Email,
			'posts' => new Field_HasMany,
			'post' => new Field_HasOne,
			'role' => new Field_BelongsTo,
		);
	}
}