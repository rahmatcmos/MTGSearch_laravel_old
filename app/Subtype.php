<?php namespace App;

class Subtype extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'subtypes';

	protected $fillable = array('subtype');

	public static $rules = array(
		'subtype' => 'required'
	);

}