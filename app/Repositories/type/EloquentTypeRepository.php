<?php namespace App\Repositories\Type;

use App\Type;
use App\Repositories\CRepository;

class EloquentTypeRepository extends CRepository implements TypeRepository {

	public function get($id = null)
	{
		if(is_null($id)){
			return Type::all();
		}else{
			return Type::find($id);
		}
	}

	public function createOrUpdate($input, $id = null)
	{
		if(!is_numeric($id)) {
			$Type = new Type;
		} else {
			$Type = $this->get($id);
		}

		$keys = array_keys(Type::$rules);
		foreach($keys as $key){
			if(array_key_exists($key, $input)){
				$Type->$key = $input[$key];
			}
		}

		if($Type->save()){
			return true;
		}else{
			$this->errors = $Type::$errors;
			return false;
		}
	}

	public function delete($id){
		$Type = $this->get($id);
		if($Type == null){
			return false;
		}
		return $Type->delete();
	}

	public function insertData(){
		$data = $this->getJson(Type::getTableName(), false);
		$createArray = array();

		foreach($data as $Type){
			$createArray[] = array('type' => $Type);
		}

		foreach($createArray as $Type){
			$this->errors = null;
			$this->createOrUpdate($Type);
			if(!is_null($this->errors)){
				return false;
			}
		}
		return true;
	}


}