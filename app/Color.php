<?php namespace App;

class Color extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'colors';

	protected $fillable = array('color');

	public $timestamps = false;

	public static $rules = array(
		'color' => 'required'
	);

}