<?php defined('SYSPATH') or die ('No direct script access.');
/**
 * This class is abstract so can't be used directly
 */
abstract class Model_File extends Jelly_Model
{
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->db = Jelly_Test::GROUP;
		
		$meta->fields(array(
			'id' => new Field_Primary,
			'name' => new Field_String,
			'type' => new Field_ModelType,
		));
	}
	
} // End  Model_File