'use strict';

/* Controllers */

var coursemakerControllers = angular.module('coursemakerControllers', []);

coursemakerControllers.controller('joueurCtrl', ['$scope','$http',
	function($scope,$http){

		$scope.nbjoueurs = 0;
		$scope.joueurs = [];
		$scope.fixed = [];

		$scope.$watch('nbjoueurs', function(){
			if($scope.nbjoueurs > 0)
			{
				for(var i=0;i<$scope.nbjoueurs;i++)
				{
					$scope.joueurs[i] = {};
					$scope.fixed[i] = {};
				}
			}
		},true);

		$scope.send = function(id,idjoueur,index)
		{
			$scope.joueurs[index].id = idjoueur;
			$http.post(Routing.generate('equipe_set_optional_data', { id: id }, true),$scope.joueurs[index])
				.success(function(data){
					var index = -1;
					for(var prop in data)
					{
						if(data.hasOwnProperty(prop))
						{
							if(prop == 'id')
							{
								for(var i=0;i<$scope.nbjoueurs;i++)
								{
									if($scope.joueurs[i].hasOwnProperty(prop) && $scope.joueurs[i][prop] == data[prop])
									{
										index = i;
									}
								}
							}
							else if(index > -1)
							{
								$scope.joueurs[index][prop] = data[prop];
								$scope.fixed[index][prop] = true;
							}
						}
					}
				})
				.error(function(){
					console.log('error');
				});
		};
}]);