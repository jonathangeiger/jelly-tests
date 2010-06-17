<?php defined('SYSPATH') or die ('No direct script access.');
/**
 * This class is abstract so can't be instantiated directly
 */
abstract class Model_File extends Jelly_Model
{
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->db = Jelly_Test::GROUP;
		
		$meta->table = 'files';
		
		$meta->fields(array(
			'id' => new Field_Primary,
			'name' => new Field_String,
			'type' => new Field_ModelType(array(
				'in_db'  => FALSE,
				'column' => DB::expr('"file_" || files.type'),
				'label'  => 'File Type'
			)),
		));		
	}
	
} // End  Model_File