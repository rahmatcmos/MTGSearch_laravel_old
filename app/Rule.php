<?php namespace App;

class Rule extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'rules';

	protected $fillable = array('rule', 'released');

	public static $rules = array(
		'rule' => 'required',
		'released' => 'date|date_format:Y-m-d'
	);

}