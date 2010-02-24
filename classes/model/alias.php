<?php

class Model_Alias extends Jelly_Model
{		
	public static function initialize($meta)
	{
		$meta->db = 'jelly';
		$meta->fields += array(
			'id' => new Field_Primary('id-alias'),
			'name' => new Field_Slug('name-alias'),
			'description' => new Field_String(array(
				'column' => 'description-alias'
			))
		);
	}
}