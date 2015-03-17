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


        },true);

        $scope.pay = function(equipe){
            $scope.route = Routing.generate('pay', { equipe: equipe, joueurs: $scope.path });

            $http.post($scope.route)
                .success(function(data){
                    if(data){
                        if(data.indexOf('http') > -1){
                            window.location.href = data;
                        } else {
                            alert(data);
                        }
                    }
                })
                .error(function(){
                    console.log('error');
                });
        }
    }]);

coursemakerControllers.controller('JoueurController', ['$scope','$http',
    function($scope,$http){
        $scope.joueur = {};
        $scope.fixed = {};
        $scope.finished = {carte:false,certif:false};


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

        $scope.upload = function(idjoueur,type)
        {
            var fd =  new FormData();
            fd.append('file',$scope.file);
            $http.post(Routing.generate('upload', { joueur:idjoueur, type: type }),fd,
                {
                    transformRequest:angular.identity,
                    headers:{'Content-Type':undefined}
                })
                .success(function(d){
                    if(d.id == idjoueur){
                        $scope.finished[type] = true;

                    }
                })
        }
}]);