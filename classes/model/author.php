<?php

class Model_Author extends Model_Test
{
	public static function initialize(Jelly_Meta $meta)
	{
		parent::initialize($meta);
		
		$meta->fields(array(
			'id'       => Jelly::field('primary'),
			'name'     => Jelly::field('string'),
			'password' => Jelly::field('password'),
			'email'    => Jelly::field('email'),
			'posts'    => Jelly::field('hasmany'),
			'post'     => Jelly::field('hasone'),
			'role'     => Jelly::field('belongsto'),
		 ));
	}
}