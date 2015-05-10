<?php namespace App;

class Supertype extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'supertypes';

	protected $fillable = array('supertype');

	public $timestamps = false;

	public static $rules = array(
		'supertype' => 'required'
	);

}