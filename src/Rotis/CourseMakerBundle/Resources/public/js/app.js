var coursemaker = angular.module('coursemaker', [
	'coursemakerControllers',
	]);

coursemaker.config(['$interpolateProvider', function($interpolateProvider){
            $interpolateProvider.startSymbol('{[{');
            $interpolateProvider.endSymbol('}]}');
    }]);