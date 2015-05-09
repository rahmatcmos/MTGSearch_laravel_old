<?php namespace App;

class Edition extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'editions';

	protected $fillable = array('multiverse_id', 'rarity', 'artist', 'flavor', 'number', 'layout', 'released', 'code', 'card_id');

	public static $rules = array(
		'multiverse_id' => 'integer|required|min:1',
		'rarity' => 'required',
		'artist' => 'required',
		'flavor' => 'required',
		'number' => 'integer|required',
		'layout' => 'required',
		'released' => 'date|date_format:Y-m-d',
		'code' => 'required|size:3',
		'card_id' => 'integer|required'
	);

}