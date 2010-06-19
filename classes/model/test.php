<?php defined('SYSPATH') or die('No direct script access.');

/**
 * All test models extend this model, as it sets the proper
 * database group to use.
 *
 * @package  Jelly
 */
abstract class Model_Test extends Jelly_Model
{
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->db(Jelly_Test::GROUP);
	}
}