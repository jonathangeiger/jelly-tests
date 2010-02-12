<?php

class Model_Alias extends Jelly
{		
	public static function initialize($meta)
	{
		$meta->db = 'jelly';
		$meta->fields += array(
			'id' => new Jelly_Field_Primary('id-alias'),
			'name' => new Jelly_Field_Slug('name-alias'),
			'description' => new Jelly_Field_String(array(
				'column' => 'description-alias'
			))
		);
	}
}