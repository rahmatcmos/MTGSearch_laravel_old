<?php namespace App\Services;

/**
 * Class card
 */
class card{

	/**
	 * @var
	 */
	private $card;
	/**
	 * @var mixed
	 */
	private $dbCard;

	/**
	 * @param $cardName
	 * @throws Exception
	 * Sets properties to result from apis.
	 */
	public function __construct($cardName){
		$cardName = urlencode($cardName);
		$this->card = json_decode(@file_get_contents('https://api.deckbrew.com/mtg/cards?name='.$cardName), true);
		$this->card = $this->card[0];
		$this->dbCard = json_decode(@file_get_contents('http://api.mtgdb.info/cards/'.$cardName), true);
		// Checks if apis is up.
		$this->checkApis();
	}

	/**
	 * @throws Exception
	 */
	private function checkApis(){
		// Checks if both apis is down.
		if(is_null($this->card) && is_null($this->dbCard)){
			throw new \Exception('Both apis that mtgSearch use is down your are now in offline mode please reload this page in a few minutes to use this page in online mode again.', 3);
		}
		// Checks if one of the apis is down.
		if(is_null($this->card) || is_null($this->dbCard)){
			error_log('One of mtgSearch apis was down at:'.date('Y-m-d H:i:s'), 0);
		}
	}

	/**
	 * @return string
	 * Fetching the cards name.
	 */
	public function getName(){
		if(!is_null($this->card)) {
			return (isset($this->card['name'])) ? $this->card['name'] : '';
		}else{
			return (isset($this->dbCard[0]['name'])) ? $this->dbCard[0]['name'] : '';
		}
	}

	/**
	 * @return string
	 * Fetching the cards quote.
	 */
	public function getFlavor(){
		if(!is_null($this->card)) {
			return (isset($this->card['editions'][0]['flavor'])) ? $this->card['editions'][0]['flavor'] : '';
		}else{
			return (isset($this->dbCard[0]['flavor'])) ? $this->dbCard[0]['flavor'] : '';
		}
	}

	/**
	 * @return array
	 * Fetching all images of the card.
	 */
	public function getImages(){
		$return = array();
		$i = 0;
		if(!is_null($this->dbCard)) {
			foreach($this->dbCard as $image) {
				$id = (isset($image['id'])) ? $image['id'] : 0;
				if($id > 0) {
					$return[$i] = array();
					$return[$i]['image'] = $id;
					$return[$i]['artist'] = (isset($image['artist'])) ? $image['artist'] : '';
					$return[$i]['releasedAt'] = (isset($image['releasedAt'])) ? $image['releasedAt'] : '';
					$i++;
				}
			}
		}else{
			foreach($this->card['editions'] as $image) {
				$id = (isset($image['multiverse_id'])) ? $image['multiverse_id'] : 0;
				if($id > 0) {
					$return[$i] = array();
					$return[$i]['image'] = $id;
					$return[$i]['artist'] = (isset($image['artist'])) ? $image['artist'] : '';
					$return[$i]['releasedAt'] = '';
					$i++;
				}
			}
		}
		return $return;
	}

	/**
	 * @return array
	 * Fetching all format that card can be played in and its legality.
	 */
	public function getFormats(){
		$return = array();
		if(!is_null($this->dbCard)) {
			$formats = (isset($this->dbCard[0]['formats'])) ? $this->dbCard[0]['formats'] : [];
			foreach($formats as $format) {
				$return[strtolower($format['name'])] = strtolower($format['legality']);
			}
		}else{
			$formats = (isset($this->card['formats'])) ? $this->card['formats'] : [];
			foreach($formats as $name =>$format) {
				$return[strtolower($name)] = strtolower($format);
			}
		}
		// If card is not legal in anu format return empty array.
		if(array_key_exists('this card is not playable in any formats.', $return)){
			$return = array();
		}
		return $return;
	}

	/**
	 * @return int
	 * Fetching power for the card.
	 */
	public function getPower(){
		if(!is_null($this->card)) {
			return (isset($this->card['power'])) ? $this->card['power'] : 0;
		}else{
			return (isset($this->dbCard[0]['power'])) ? $this->dbCard[0]['power'] : 0;
		}
	}

	/**
	 * @return int
	 * Fetching toughness for the card.
	 */
	public function getToughness(){
		if(!is_null($this->card)) {
			return (isset($this->card['toughness'])) ? $this->card['toughness'] : 0;
		}else{
			return (isset($this->dbCard[0]['toughness'])) ? $this->dbCard[0]['toughness'] : 0;
		}
	}

	/**
	 * @return string
	 * Fetching type of the card.
	 */
	public function getType(){
		if(!is_null($this->card)) {
			$type = (isset($this->card['types'])) ? $this->card['types'] : [];
			return ucwords(implode(' ', $type));
		}else{
			return (isset($this->dbCard[0]['type'])) ? $this->dbCard[0]['type'] : '';
		}
	}

	/**
	 * @return string
	 * Fetching subtype of the card.
	 */
	public function getSubType(){
		if(!is_null($this->card)) {
			$type = (isset($this->card['subtypes'])) ? $this->card['subtypes'] : [];
			// Joins the array in to a string.
			return ucwords(implode(' ', array_reverse($type)));
		}else{
			return (isset($this->dbCard[0]['subType'])) ? $this->dbCard[0]['subType'] : '';
		}
	}

	/**
	 * @return int
	 * Fetching id of the card.
	 */
	public function getMultiverseId(){
		if(!is_null($this->card)) {
			return (isset($this->card['editions'][0]['multiverse_id'])) ? $this->card['editions'][0]['multiverse_id'] : 0;
		}else{
			return (isset($this->dbCard[0]['id'])) ? $this->dbCard[0]['id'] : 0;
		}
	}

	/**
	 * @return int
	 * Fetching loyalty of the card.
	 */
	public function getLoyalty(){
		if(!is_null($this->dbCard)) {
			return (isset($this->dbCard[0]['loyalty'])) ? $this->dbCard[0]['loyalty'] : 0;
		}
		else {
			return (isset($this->card['loyalty'])) ? $this->card['loyalty'] : 0;
		}
	}

	/**
	 * @return mixed|string
	 * Fetching text on the card.
	 */
	public function getText(){

		if(!is_null($this->card)) {
			return (isset($this->card['text'])) ? $this->card['text'] : '';
		}
		$text = (isset($this->dbCard[0]['description'])) ? $this->dbCard[0]['description'] : '';
		// finds all text with {} around it.
		preg_match_all('/\{(.*?)\}/', $text, $match);
		// Replace the text with first letter only.
		foreach($match[1] as $word){
			$text = str_replace('{'.$word.'}', '{'.$word[0].'}', $text);
		}
		return $text;
	}

	/**
	 * @return array
	 * Fetching cost for playing the card.
	 */
	public function getCostImages(){
		if(!is_null($this->card)) {
			$cost = (isset($this->card['cost'])) ? $this->card['cost'] : '';
			// Fetching all words with {} around and puts it in an array.
			preg_match_all('/\{(.*?)\}/', $cost, $costs);
			$costs = (isset($costs[1])) ? $costs[1] : [];
		}else{
			// Puts each letter in an array.
			$costs = (isset($this->dbCard[0]['manaCost'])) ? str_split($this->dbCard[0]['manaCost']) : [];
		}

		return $costs;
	}

	/**
	 * @param $file
	 * @return array|mixed
	 */
	private function getSets($file){
		$sets = array();
		if(file_exists($file)){
			$sets = json_decode(@file_get_contents($file), true);
		}
		if(is_null($sets) || count($sets) <= 0){
			$sets = json_decode(file_get_contents('http://mtgimage.com/SetSymbols.json'), true);
			@file_put_contents($file, json_encode($sets));
		}
		return $sets;
	}

	/**
	 * @param $setList
	 * @return array
	 */
	public function getEditonImages($setList){
		$return = array();
		$setsArray = $this->getSets($setList);
		if(!is_null($this->card)) {
			// Fetching all editions.
			$edtions = (isset($this->card['editions'])) ? $this->card['editions'] : [];
			foreach($edtions as $edtion) {
				// Sets rarity to empty if it does not exists.
				$rarity = (isset($edtion['rarity'][0])) ? $edtion['rarity'][0] : '';
				// Sets setid to 0 if it does not exists.
				$setid = (isset($edtion['set_id'])) ? $edtion['set_id'] : 0;
				// Checks if the setid is ok.
				if(array_key_exists($setid, $setsArray)) {
					// Checks if the setid has this rarity.
					if(in_array($rarity, $setsArray[$setid])) {
						$return[] = array('set_id' => $setid, 'rarity' => $rarity);
					} else {
						if(count($setsArray[$setid]) > 0) {
							$return[] = array('set_id' => $setid, 'rarity' => false);
						}
					}
				}
			}
		}else{
			foreach($this->dbCard as $edtion) {
				// Sets rarity to empty if it does not exists.
				$rarity = (isset($edtion['rarity'][0])) ? strtolower($edtion['rarity'][0]) : '';
				// Sets setid to 0 if it does not exists.
				$setid = (isset($edtion["cardSetId"])) ? $edtion["cardSetId"] : 0;
				// Checks if the setid is ok.
				if(array_key_exists($setid, $setsArray)) {
					// Checks if the setid has this rarity.
					if(in_array($rarity, $setsArray[$setid])) {
						$return[] = array('set_id' => $setid, 'rarity' => $rarity);
					} else {
						if(count($setsArray[$setid]) > 0) {
							$return[] = array('set_id' => $setid, 'rarity' => false);
						}
					}
				}
			}
		}
		return $return;
	}
}