<?php namespace App\Repositories\Supertype;

use App\Supertype;
use App\Repositories\CRepository;

class EloquentSupertypeRepository extends CRepository implements SupertypeRepository {

	public function get($id = null)
	{
		if(is_null($id)){
			return Supertype::all();
		}else{
			return Supertype::find($id);
		}
	}

	public function createOrUpdate($input, $id = null)
	{
		if(!is_numeric($id)) {
			$Supertype = new Supertype;
		} else {
			$Supertype = $this->get($id);
		}

		$keys = array_keys(Supertype::$rules);
		foreach($keys as $key){
			if(array_key_exists($key, $input)){
				$Supertype->$key = $input[$key];
			}
		}

		if($Supertype->save()){
			return true;
		}else{
			$this->errors = $Supertype::$errors;
			return false;
		}
	}

	public function delete($id){
		$Supertype = $this->get($id);
		if($Supertype == null){
			return false;
		}
		return $Supertype->delete();
	}

	public function insertData(){
		$data = $this->getJson(Supertype::getTableName(), false);
		$createArray = array();

		foreach($data as $Supertype){
			$createArray[] = array('supertype' => $Supertype);
		}

		foreach($createArray as $Supertype){
			$this->errors = null;
			$this->createOrUpdate($Supertype);
			if(!is_null($this->errors)){
				return false;
			}
		}
		return true;
	}


}