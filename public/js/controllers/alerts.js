/* Fetched from : http://jsfiddle.net/uberspeck/j46Yh/light/ */
angular.module('MTGFinder')
	.controller('AlertsCtrl', function ($scope, alertsManager) {
		$scope.alerts = alertsManager.alerts;

		$scope.clearAlerts = function(){
			alertsManager.clearAlerts();
		};
	});