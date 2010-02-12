<?php

class Model_Role extends Jelly
{
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->db = 'jelly';
		$meta->fields += array(
			'id' => new Field_Primary,
			'name' => new Field_String,
		);
	}
}