/* Fetched from : http://jsfiddle.net/uberspeck/j46Yh/light/ */
angular.module('MTGFinder')
	.service('alertsManager', function() {
		return {
			alerts: {},
			addAlert: function(message, type) {
				this.clearAlerts();
				this.alerts['alert-'+type] = message;
			},
			clearAlerts: function() {
				for(var x in this.alerts) {
					delete this.alerts[x];
				}
			}
		};
	});