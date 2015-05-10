<?php namespace App\Repositories\Set;

use App\Set;
use App\Repositories\CRepository;

class EloquentSetRepository extends CRepository implements SetRepository {

	public function get($id = null)
	{
		if(is_null($id)){
			return Set::all();
		}else{
			return Set::find($id);
		}
	}

	public function createOrUpdate($input, $id = null)
	{
		if(!is_numeric($id)) {
			$Set = new Set;
		} else {
			$Set = $this->get($id);
		}

		$keys = array_keys(Set::$rules);
		foreach($keys as $key){
			if(array_key_exists($key, $input)){
				if($key == 'code') {
					if(is_null($Set->key)) {
						$Set->$key = $input[$key];
					}
				}else {
					$Set->$key = $input[$key];
				}
			}
		}

		if($Set->save()){
			return true;
		}else{
			$this->errors = $Set::$errors;
			return false;
		}
	}

	public function delete($id){
		$Set = $this->get($id);
		if($Set == null){
			return false;
		}
		return $Set->delete();
	}

	private function getApiData(){
		$table = Set::getTableName();
		$mtgdb = $this->getJson($table);
		$deckbrew = $this->getJson($table, false);
		return array($deckbrew, $mtgdb);
	}

	public function insertData(){
		$data = $this->getApiData();
		$createArray = array();

		foreach($data[0] as $set){
			$set['code'] = $set['id'];
			$createArray[$set['id']] = $set;
		}

		foreach($data[1] as $set){
			$set['released'] = $set['releasedAt'];
			if(array_key_exists($set['id'], $createArray)) {
				$createArray[$set['id']] = array_merge($set, $createArray[$set['id']]);
			}else{
				$set['code'] = $set['id'];
				$createArray[$set['id']] = $set;
			}
		}

		foreach($createArray as $set){
			$this->errors = null;
			$this->createOrUpdate($set);
			if(!is_null($this->errors)){
				return false;
			}
		}
		return true;
	}


}