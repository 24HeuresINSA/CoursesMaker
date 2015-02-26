'use strict';

/* Controllers */

var coursemakerControllers = angular.module('coursemakerControllers', []);

coursemakerControllers.controller('TeamController', ['$scope','$http',
	function($scope,$http){

        $scope.count = {};
        $scope.count.joueurs = [];
        $scope.path = "";

        $scope.$watch('count.joueurs',function(){
            $scope.path = $scope.count.joueurs.map(function(elem){
                return elem.id;
            }).join('-');

            /* TODO: générer la route de paiement
            $scope.route = Routing.generate('inscrit_create', { sessionId: $scope.path });
            console.log($scope.route);*/

        },true);

    }]);

coursemakerControllers.controller('JoueurController', ['$scope','$http',
    function($scope,$http){
        $scope.joueur = {};
        $scope.fixed = {};


        $scope.$watch('joueur',function(){
            var found = -1;
            for(var i=0;i<$scope.count.joueurs.length;i++)
            {
                if($scope.count.joueurs[i].id == $scope.joueur.id)
                {
                    found = i;
                }
            }
            if(found == -1)
            {
                if($scope.joueur.checked == true)
                {
                    $scope.count.joueurs.push($scope.joueur);
                }
            }
            else
            {
                if($scope.joueur.checked == false)
                {
                    $scope.count.joueurs.splice(found,1);
                }
            }
        },true);

        $scope.toggleChecked = function()
        {
          $scope.joueur.checked = !$scope.joueur.checked;
        };

        $scope.send = function(id,idjoueur)
        {
            $scope.joueur.id = idjoueur;
            $http.post(Routing.generate('equipe_set_optional_data', { id: id }, true),$scope.joueur)
                .success(function(data){
                    var found = false;
                    for(var prop in data) {
                        if(data.hasOwnProperty(prop)) {
                            if(found) {
                                $scope.joueur[prop] = data[prop];
                                $scope.fixed[prop] = true;
                            }
                            else if(prop == 'id')
                            {
                                if($scope.joueur.hasOwnProperty(prop) && $scope.joueur[prop] == data[prop])
                                    {
                                        found = true;
                                    }
                            }
                        }
                    }
                })
                .error(function(){
                    console.log('error');
                });
        };
}]);