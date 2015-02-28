var coursemakerServices = angular.module('coursemakerServices', []);

coursemakerServices.directive('fileInput',['$parse',function($parse){
    return {
        restrict: 'A',
        link: function(scope,elm,attrs){
            elm.bind('change',function(){
                $parse(attrs.fileInput)
                    .assign(scope,elm[0].files[0]);
                scope.$apply();
            });
        }
    }
}]);