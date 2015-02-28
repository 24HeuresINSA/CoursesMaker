var coursemaker = angular.module('coursemaker', [
	'coursemakerControllers', 'coursemakerServices',
	]);

coursemaker.config(['$interpolateProvider', function($interpolateProvider){
            $interpolateProvider.startSymbol('{[{');
            $interpolateProvider.endSymbol('}]}');
    }]);