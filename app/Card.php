<?php namespace App;

class Card extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cards';

	protected $fillable = array('name', 'cms', 'cost', 'text', 'power', 'toughness', 'loyalty');

	public static $rules = array(
		'name' => 'required',
		'cms' => 'required|integer|min:0',
		'cost' => 'required',
		'text' => 'required',
		'power' => 'required|integer|min:0',
		'toughness' => 'required|integer|min:0',
		'loyalty' => 'required|integer|min:0',
	);

}