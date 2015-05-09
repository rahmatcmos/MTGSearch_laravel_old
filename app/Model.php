<?php namespace App;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel {

	public static $errors;

	public static function boot()
	{
		parent::boot();
		static::saving(function($object){
			return $object::isValid($object);
		});
	}

	public static function isValid($data)
	{
		$id = null;
		if(is_object($data)){
			$data = $data->toArray();
			if(isset($data['id'])){
				$id = $data['id'];
			}
		}

		$rules = static::$rules;

		if(is_numeric($id)){
			array_walk($rules, function(&$item) use ($id)
			{
				if(stripos($item, ':id:') !== false){
					$item = str_ireplace(':id:', $id, $item);
				}
			});
		}

		$v = Validator::make($data, $rules);
		if ($v->passes()) {
			return true;
		}else{
			static::$errors = $v->messages();
			return false;
		}
	}
}