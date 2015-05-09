<?php namespace App;

class Format extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'formats';

	protected $fillable = array('format');

	public static $rules = array(
		'format' => 'required'
	);

}