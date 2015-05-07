<?php namespace App\Services;

/**
 * Class MTG
 */
class MTG {

	/**
	 * @var
	 */
	private $cardList;
	/**
	 * @var string
	 */
	private $cache;
	/**
	 * @var string
	 */
	private $cardNameList;
	/**
	 * @var
	 */
	private $card;

	private $setList;

	/**
	 * @param string $addDir
	 * Setts path to cache files.
	 */
	public function __construct($addDir = '') {
		if($addDir == ''){
			$addDir = storage_path().'/';
		}
		$directory = $addDir.'mtg/';
		$this->cache = $directory.'cards.json';
		$this->cardNameList = $directory.'Cardname.json';
		$this->setList = 'setSymbols.json';
	}

	/**
	 * @param $name
	 * Sets property card to new instace of card.
	 */
	private function setCard($name){
		$this->card = new card($name);
	}

	/**
	 * @param bool $generate
	 * @return mixed
	 * @throws Exception
	 * Fetching a list with names of all cards.
	 */
	public function getList($generate = false) {
		if(file_exists($this->cardNameList)) {
			$this->cardList = json_decode(@file_get_contents($this->cardNameList), true);
			if(count($this->cardList) <= 0){
				$generate = true;
			}
		} else {
			$generate = true;
		}

		if($generate){
			$this->generateList();
		}

		return $this->cardList;
	}

	/**
	 * @throws Exception
	 * Fetching a list with names of all cards from one of the apis.
	 */
	private function generateList() {
		$this->cardList = array();
		$cards = json_decode(@file_get_contents('http://api.mtgdb.info/cards/?fields=name'), true);
		if(!is_null($cards)) {
			// Only fetch names and nothing else.
			foreach($cards as $card) {
				if(!in_array($card['name'], $this->cardList)) {
					$this->cardList[] = $card['name'];
				}
			}
			// Saves the list to cache.
			@file_put_contents($this->cardNameList, json_encode($this->cardList));
		}else{
			// Throws an expeption if no list can be generated.
			throw new  \Exception('We could not find any cards, please visit us soon again or contact admin if problems remains.', 2);
		}
	}

	/**
	 * @param $cardName
	 * @return array
	 * @throws Exception
	 * Fetching card from api if not exists in cache.
	 */
	public function getCard($cardName) {
		// Checks if card exists in list.
		if(in_array(ucwords($cardName), array_map('ucwords', $this->getList()))) {
			// Fetching card from cache.
			$cardArray = $this->fetchCard($cardName);
			// Checks if card did exist in cache.
			if(empty($cardArray)) {
				// Fetching all info from apis.
				$this->setCard($cardName);
				$cardArray['name'] = $this->card->getName();
				$cardArray['flavor'] = $this->card->getFlavor();
				$cardArray['images'] = $this->card->getImages();
				$cardArray['text'] = $this->card->getText();
				$cardArray['edtions'] = $this->card->getEditonImages($this->setList);
				$cardArray['cost'] = $this->card->getCostImages();
				$cardArray['formats'] = $this->card->getFormats();
				$cardArray['power'] = $this->card->getPower();
				$cardArray['toughness'] = $this->card->getToughness();
				$cardArray['multiverseid'] = $this->card->getMultiverseId();
				$cardArray['type'] = $this->card->getType();
				$cardArray['subtype'] = $this->card->getSubType();
				$cardArray['loyalty'] = $this->card->getLoyalty();
				// Storing card in cache.
				$this->storeCard($cardName, $cardArray);
			}
		}else{
			// Throws exception if card does not exists.
			throw new \Exception('This card does not exists', 1);
		}

		return $cardArray;
	}

	/**
	 * @param $name
	 * @param $card
	 * Stores card in cache.
	 */
	private function storeCard($name, $card) {
		$newCard = array($name => $card);
		// Prepend card in cache string.
		$cards = json_encode(array_merge($newCard, $this->getAll()));
		if(file_exists($this->cache)) {
			// Puts cache sting in file.
			@file_put_contents($this->cache, $cards);
		}
	}

	/**
	 * @return array|mixed
	 * Fetching all cards from cache.
	 */
	public function getAll() {
		$cards = array();
		if(file_exists($this->cache)) {
			$cards = json_decode(@file_get_contents($this->cache), true);
		}
		return $cards;
	}

	/**
	 * @param $name
	 * @return array
	 * Fetch card from cache.
	 */
	private function fetchCard($name) {
		$cards = $this->getAll();
		if(in_array($name, array_keys($cards))) {
			return $cards[$name];
		}
		return array();
	}
}