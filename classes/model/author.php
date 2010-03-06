<?php

class Model_Author extends Jelly_Model
{
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->db = Jelly_Test::GROUP;
		$meta->fields += array(
			'id' => new Field_Primary,
			'name' => new Field_String,
			'password' => new Field_Password,
			'email' => new Field_Email,
			'posts' => new Field_HasMany,
			'post' => new Field_HasOne,
			'role' => new Field_BelongsTo(array(
				'default' => 0
			)),
			// Aliased fields
			'_id' => 'id',
			'_name' => 'name',
			'_password' => 'password',
			'_email' => 'email',
			'_posts' => 'posts',
			'_post' => 'post',
			'_role' => 'role'
		);
	}
}