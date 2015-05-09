<?php namespace App;

class Set extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sets';

	protected $fillable = array('code', 'name', 'border', 'type');

	public static $rules = array(
		'code' => 'required|size:3',
		'name'  => 'required',
		'border' => 'required',
		'type' => 'required'
	);

}