<?php

class Model_Category extends Jelly_Model
{
	public static function initialize($meta)
	{
		$meta->db(Jelly_Test::GROUP);
		$meta->fields(array(
			'id'     => Jelly::field('primary'),
			'name'   => Jelly::field('string'),
			'posts'  => Jelly::field('manytomany'),
			'parent' => Jelly::field('belongsto', array(
				'foreign' => 'category',
				'column' => 'parent_id'
			)),
		));
	}
}