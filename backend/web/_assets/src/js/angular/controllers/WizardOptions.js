app.controller(
    'WizardOptionsController',
    ['$scope',
    function($scope){
        $scope.options = [];
        $scope.addOption = function(){
            $scope.options.push(
                {
                    icon    : '',
                    label   : '',
                    value   : ''
                }
            );
        };
        $scope.removeOption = function(index){
            var options = [];
            angular.forEach($scope.options, function(v, i){
                if(i != index){
                    options.push(v);
                }
            });
            $scope.options = options;
        };
    }]
);