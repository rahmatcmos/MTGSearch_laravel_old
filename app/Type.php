<?php namespace App;

class Type extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'types';

	protected $fillable = array('type');

	public static $rules = array(
		'type' => 'required'
	);

}