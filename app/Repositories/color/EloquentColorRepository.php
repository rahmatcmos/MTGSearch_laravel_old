<?php namespace App\Repositories\Color;

use App\Color;
use App\Repositories\CRepository;

class EloquentColorRepository extends CRepository implements ColorRepository {

	public function get($id = null)
	{
		if(is_null($id)){
			return Color::all();
		}else{
			return Color::find($id);
		}
	}

	public function createOrUpdate($input, $id = null)
	{
		if(!is_numeric($id)) {
			$Color = new Color;
		} else {
			$Color = $this->get($id);
		}

		$keys = array_keys(Color::$rules);
		foreach($keys as $key){
			if(array_key_exists($key, $input)){
				$Color->$key = $input[$key];
			}
		}

		if($Color->save()){
			return true;
		}else{
			$this->errors = $Color::$errors;
			return false;
		}
	}

	public function delete($id){
		$Color = $this->get($id);
		if($Color == null){
			return false;
		}
		return $Color->delete();
	}

	public function insertData(){
		$data = $this->getJson(Color::getTableName(), false);
		$createArray = array();

		foreach($data as $Color){
			$createArray[] = array('color' => $Color);
		}

		foreach($createArray as $Color){
			$this->errors = null;
			$this->createOrUpdate($Color);
			if(!is_null($this->errors)){
				return false;
			}
		}
		return true;
	}


}