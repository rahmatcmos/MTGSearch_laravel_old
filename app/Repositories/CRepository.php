<?php namespace App\Repositories;

abstract class CRepository {

	public $errors;

	public function getErrors(){
		return $this->errors;
	}

	public function stripTrim($input){
		return trim(strip_tags($input));
	}

	public function is_assoc($array) {
		foreach(array_keys($array) as $key) {
			if(!is_int($key)) {
				return TRUE;
			}
		}
		return FALSE;
	}

	public function getJson($table, $param = '', $mtgdb = true){
		$url = 'http://api.deckbrew.com/mtg/';
		if(is_bool($param)){
			$mtgdb = $param;
			$param = '';
		}
		if($mtgdb){
			$url = 'http://api.mtgdb.info/';
		}
		return json_decode(file_get_contents($url.$table.$param), true);
	}
}