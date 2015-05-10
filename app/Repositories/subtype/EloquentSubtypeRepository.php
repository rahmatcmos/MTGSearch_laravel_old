<?php namespace App\Repositories\Subtype;

use App\Subtype;
use App\Repositories\CRepository;

class EloquentSubtypeRepository extends CRepository implements SubtypeRepository {

	public function get($id = null)
	{
		if(is_null($id)){
			return Subtype::all();
		}else{
			return Subtype::find($id);
		}
	}

	public function createOrUpdate($input, $id = null)
	{
		if(!is_numeric($id)) {
			$Subtype = new Subtype;
		} else {
			$Subtype = $this->get($id);
		}

		$keys = array_keys(Subtype::$rules);
		foreach($keys as $key){
			if(array_key_exists($key, $input)){
				$Subtype->$key = $input[$key];
			}
		}

		if($Subtype->save()){
			return true;
		}else{
			$this->errors = $Subtype::$errors;
			return false;
		}
	}

	public function delete($id){
		$Subtype = $this->get($id);
		if($Subtype == null){
			return false;
		}
		return $Subtype->delete();
	}

	public function insertData(){
		$data = $this->getJson(Subtype::getTableName(), false);
		$createArray = array();

		foreach($data as $Subtype){
			$createArray[] = array('subtype' => $Subtype);
		}

		foreach($createArray as $Subtype){
			$this->errors = null;
			$this->createOrUpdate($Subtype);
			if(!is_null($this->errors)){
				return false;
			}
		}
		return true;
	}


}