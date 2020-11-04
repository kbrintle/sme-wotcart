app.controller(
    'SettingsFormController',
    ['$scope',
    function($scope){
        $scope.paymentAlternatives = {
            stripe_enabled : 'paypal_enabled',
            paypal_enabled : 'stripe_enabled'
        };
        $scope.changePaymentMethod = function(payment_type){
            if( $scope[payment_type] )
                $scope[ $scope.paymentAlternatives[payment_type] ] = false;
        };
    }]
);