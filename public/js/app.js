// Inits app.
var app = angular.module('MTGFinder', ['ngStorage']);

app.config(function($locationProvider) {
	// Removes # from url.
	$locationProvider.html5Mode(true).hashPrefix('!');
});

app.controller('HomeCtrl',
	['$scope', '$http', 'filterFilter', '$location', '$localStorage', '$timeout', 'alertsManager',
	function ($scope, $http, filterFilter, $location, $localStorage, $timeout, alertsManager) {
	// Inits all scope varible
	$scope.Cards = [];
	$scope.choosenCards = [];
	$scope.selectedCard = [];
	$scope.cardLoading = [];
	$scope.CardsCache = $localStorage.CardsCache || [];
	$scope.currentPage = 0;
	$scope.pageSize = 10;
	$scope.numberOfPages = Math.ceil($scope.Cards.length/$scope.pageSize);
	$scope.cardsToLoad = 0;
	$scope.offline = false;
	$scope.unusable = false;

	/* Change pagination on search term change */
	$scope.$watch('search', function(term) {
		$scope.currentPage = 0;
		$scope.filtered = filterFilter($scope.Cards, term);
		$scope.numberOfPages = Math.ceil($scope.filtered.length/$scope.pageSize);
	});

	/*
	* Sets offline satus and checks if offline cards exists
	* else shows an error message and hides websites functions.
	*/
	function setOffline() {
		if ($scope.offline !== true) {
			alertsManager.addAlert('You have no internet or the apis are down', 'danger');
		}
		$scope.offline = true;
		if($localStorage.CardsCache){
			$scope.CardsCache = $localStorage.CardsCache;
			$scope.Cards = Object.keys($scope.CardsCache);
		}else{
			alertsManager.addAlert('We could not find any cards, please try to connect to internet and reload MTGsearch', 'danger');
			$scope.unusable = true;
		}
	}

	/* Checks if any error was recived with ajax call and runs code depending on error code. */
	function hasError(data){
		if (data[0] && data[0].error) {
			// Outputs error message.
			alertsManager.addAlert(data[0].error, 'danger');
			var code = data[1];
			// Switch code depending on error code.
			switch(code){
				case 1:
					$scope.hideCard();
					$location.url($location.path());
					break;
				case 2:
					$scope.offline = true;
					setOffline();
					break;
				case 3:
					$scope.offline = true;
					setOffline();
					break;
			}
			return true;
		}
		return false;
	}

	/* Page load */
	$scope.$watch('$viewContentLoaded', function(){
		// Fetching choosen cards from storage.
		$scope.getCardsInStorage();
		// Checks if there is any parameters in url.
		var Params = $location.search();
		// Fetching all card names and cards from server cache.
		$http.get('app/names').success(function(data) {
			if(!hasError(data)) {
				$scope.Cards = data;
				$scope.offline = false;
				$http.get('app').success(function (data) {
					$scope.CardsCache = data;
					$localStorage.CardsCache = data;
				});
			}
		}).error(function(data, status, headers, config){
			setOffline();
		});
		// Displays card if it is in a parameter in url.
		if(Params.name !== undefined) {
			$scope.DisplayCard(Params.name);
		}
		$scope.getCardsInStorage();
	});

	/* Checks if a card is unselected. */
	$scope.$on('$locationChangeStart', function(){
		if($location.search().name === undefined) {
			$scope.selectedCard = [];
		}
	});

	/* Store card in local storage. */
	$scope.storeCardsInStorage = function(){
		if($scope.choosenCards.length > 0) {
				$localStorage.choosenCards = $scope.choosenCards;
			return;
		}else {
			if ($localStorage.choosenCards) {
				delete $localStorage.choosenCards;
			}
		}
	}

	/* Fetch card in local storage. */
	$scope.getCardsInStorage = function(){
		if ($localStorage.choosenCards) {
			$scope.choosenCards = $localStorage.choosenCards;
			return;
		} else {
			$scope.choosenCards = [];
		}
		return false;
	}

	/* Fetch card in from backend. */
	$scope.getCard = function(name){
		// Add card to loading array så loading text can be shown.
		$scope.cardLoading.push(name);
		// Checks if card is in local cache.
		if($scope.CardsCache[name] === undefined) {
			// Fetching card from server with ajax.
			$http.get('app/' + name).success(function (data) {
				$scope.offline = false;
				if (!hasError(data)) {
					// Adding card to choosen cards.
					$scope.choosenCards.push(data);
					$scope.storeCardsInStorage();
				}
				// Removes it from loading array and hides loading text.
				$scope.notLoading(name);
			}).error(function(data, status, headers, config){
				setOffline();
				$scope.storeCardsInStorage();
				$scope.notLoading(name);
			});
		}else{
			// Adding cached card to choosen cards.
			$scope.choosenCards.push($scope.CardsCache[name]);
			$scope.storeCardsInStorage();
			$scope.notLoading(name);
		}
	}

	/* Removes card from loading array. */
	$scope.notLoading = function(name){
		var loadingcards = [];
		for(var i = 0; i < $scope.cardLoading.length; i++){
			if($scope.cardLoading[i] !== name){
				loadingcards.push($scope.cardLoading[i]);
			}
		}
		$scope.cardLoading = loadingcards;
	}

	/* Remove card from choosen cards array. */
	$scope.removeCard = function(name){
		var cards = [];
		for(var i = 0; i < $scope.choosenCards.length; i++){
			if(name !== $scope.choosenCards[i].name){
				cards.push($scope.choosenCards[i]);
			}
		}
		$scope.choosenCards = cards;
		$scope.storeCardsInStorage();
	}

	/* Displaying card on page */
	$scope.DisplayCard = function(name){
		// Checks if cards is in choosen cards and is already loaded to client.
		for(var i = 0; i < $scope.choosenCards.length; i++){
			if(name === $scope.choosenCards[i].name){
				$location.search('name', name);
				$scope.selectedCard.push($scope.choosenCards[i]);
				break;
			}
		}
		// Checks if another card is choosen.
		if($scope.selectedCard.length == 0){
			// Checks if card is in local cache.
			if($scope.CardsCache[name] === undefined) {
				// Checks if name is not an empty string.
				if(name !== '') {
					// Fetching card from server.
					$http.get('app/' + name).success(function (data) {
						$scope.offline = false;
						if (!hasError(data)) {
							// Adding fetched card to selected card array.
							$scope.selectedCard.push(data);
						}
					}).error(function (data, status, headers, config) {
						setOffline();
					});
				}else{
					// Reload page to index page.
					$location.url($location.path());
				}
			}else{
				// Adding cached card to selected card array.
				$scope.selectedCard.push($scope.CardsCache[name]);
			}
		}
	}

	/* Hide the selected card. */
	$scope.hideCard = function(){
		// Checks if user are on card page or index.
		if($location.search().name !== undefined && $scope.selectedCard.length > 0 || $scope.selectedCard.length > 0) {
			// Remove card name from url.
			$location.search('name', null);
			$scope.selectedCard = [];
		}else{
			// Remove value in search field.
			$scope.search = '';
			if($scope.getCardsInStorage()) {
				$scope.choosenCards = [];
			}
		}
	}

	/* Adding card to loading array. */
	$scope.Loading = function(name){
		for(var i = 0; i < $scope.cardLoading.length; i++){
			if(name === $scope.cardLoading[i]){
				return true;
			}
		}
		return false;
	}

	/* Checks if card is in choosen array. */
	$scope.isChoosen = function(name){
		for(var i = 0; i < $scope.choosenCards.length; i++){
			if(name === $scope.choosenCards[i].name){
				return true;
			}
		}
		return false;
	}
}]);

/*
 * Fetched from: http://jsfiddle.net/2ZzZB/56/
 * Makes array start from inputs parameter number.
 */
app.filter('startFrom', [ function() {
	return function(input, start) {
		start = +start;
		return input.slice(start);
	}
}]);

/*
* Fetched from: http://stackoverflow.com/questions/13711735/angular-js-insert-html-with-scripts-that-should-run
* Reload html to be displayed as html and not text.
*/
app.directive('html', [ function () {
	return {
		restrict: 'A',
		link: function (scope, element, attrs) {
			element.html(attrs.html);
		}
	}
}]);

/*
* Fetched from: http://stackoverflow.com/questions/7467840/nl2br-equivalent-in-javascript
* All regexp are found on the internet.
* Makes all symbols in card text to images.
*/
app.directive('cardtext', [function(){
	return {
		restrict: 'A',
		link: function (scope, element, attrs) {
			var text = attrs.cardtext;
			// Finds all texts with {} around it.
			var matches = text.match(/\{(.*?)\}/g);
			// If there is any words.
			if(matches !== null) {
				for (var i = 0; i < matches.length; i++) {
					var type = matches[i].replace(/\{|\}/g, '');
					text = text.replace(matches[i], '<span class="mtg icon-'+type+'"></span>');
				}
			}
			// Adds br instead of new line code.
			text = text.replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + '<br />' + '$2');
			// Reload html to be displayed as html and not text.
			element.html(text);
		}
	}
}]);