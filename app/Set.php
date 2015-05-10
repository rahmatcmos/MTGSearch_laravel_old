<?php namespace App;

class Set extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sets';

	public $timestamps = false;

	protected $fillable = array(
		'code',
		'name',
		'border',
		'type',
		"common",
		"uncommon",
		"rare",
		"mythicRare",
		"basicLand",
		"total",
		'released',
		'description',
		'block'
	);

	// Empty fields is there because of Repository method "insertData".
	public static $rules = array(
		'code' => 'required',
		'name'  => 'required',
		'type' => 'required',
		"common" => 'integer',
		"uncommon" => 'integer',
		"rare" => 'integer',
		"mythicRare" => 'integer',
		"basicLand" => 'integer',
		"total" => 'integer',
		'released' => 'date|date_format:Y-m-d',
		'border' => '',
		'description' => '',
		'block' => '',
	);

}