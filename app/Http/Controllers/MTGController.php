<?php namespace App\Http\Controllers;

use App\Services\MTG;

class MTGController extends Controller
{
	private $deck;

	public function __construct(){
		$this->deck = new MTG();
	}

	public function index(){
		return json_encode($this->deck->getAll());
	}

	public function names(){
		return json_encode($this->deck->getList());
	}

	public function show($name){
		return json_encode($this->deck->getCard(strip_tags(urldecode($name))));
	}
}
