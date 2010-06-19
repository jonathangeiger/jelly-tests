<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Used for testing aliasing. Has no real DB equivalent.
 *
 * @package  Jelly
 */
class Model_Alias extends Model_Test
{		
	public static function initialize(Jelly_Meta $meta)
	{
		parent::initialize($meta);
		
		// All fields are aliased to different columns
		$meta->fields(array(
			'id'           => new Jelly_Field_Primary('id-alias'),
			'name'         => new Jelly_Field_String('id-alias'),
			'description'  => new Jelly_Field_String('description-alias'),
			
			'_id'          => 'id',
			'_name'        => 'name',
			'_description' => 'description',
			
			// Non-existent alias shouldn't hurt anybody
			'_bar'         => 'foo',
		));
	}
}