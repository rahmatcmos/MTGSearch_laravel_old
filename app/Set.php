<?php namespace App;

class Set extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sets';

	public $timestamps = false;

	protected $fillable = array('code', 'name', 'border', 'type');

	// Empty fields is there because of Repository method "insertData".
	public static $rules = array(
		'code' => 'required',
		'name'  => 'required',
		'type' => 'required',
		'border' => '',
		'description' => '',
		'block' => '',
		"common" => '',
		"uncommon" => '',
		"rare" => '',
		"mythicRare" => '',
		"basicLand" => '',
		"total" => '',
		'released' => ''
	);

}