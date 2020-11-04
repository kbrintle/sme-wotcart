app.controller(
    'HoursSelectorController',
    ['$scope',
    function($scope){

        function pad(n){
            return (n < 10) ? ("0" + n) : n;
        }

        $scope.hours = function(){
            var h = [];
            for(i=1; i <= 12; i++){
                h.push(i);
            }
            return h;
        }();
        $scope.minutes = function(){
            var m = [];
            for(i=0; i < 4; i++){
                m.push( pad(i*15) );
            }
            return m;
        }();
        $scope.meridian = [
            'am',
            'pm'
        ];

        $scope.timeOptions = function(){
            var h = [];

            angular.forEach($scope.meridian, function(meridian){
                angular.forEach($scope.hours, function(hour){
                    angular.forEach($scope.minutes, function(minute){
                        h.push( hour+':'+minute+''+meridian );
                    });
                });
            });

            return h;
        }();

        $scope.setStoreHours = function(store_hours){
            if(store_hours){
                $scope.storeHours = store_hours;
            }
        };

        $scope.getStoreHours = function(){
            return JSON.stringify($scope.storeHours);
        };

        $scope.storeHours = {
            monday: {
                open    : "",
                closed  : ""
            },
            tuesday: {
                open    : "",
                closed  : ""
            },
            wednesday: {
                open    : "",
                closed  : ""
            },
            thursday: {
                open    : "",
                closed  : ""
            },
            friday: {
                open    : "",
                closed  : ""
            },
            saturday: {
                open    : "",
                closed  : ""
            },
            sunday: {
                open    : "",
                closed  : ""
            }
        };
    }]
);