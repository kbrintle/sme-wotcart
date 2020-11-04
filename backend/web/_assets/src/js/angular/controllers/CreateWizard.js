app.controller(
    'CreateWizardController',
    ['$scope', '$http', '$filter',
    function($scope, $http, $filter){
        $scope.wizard_id = "#createStoreModal";

        $scope.reset = function(){
            $scope.data = {
                name    : '',
                url     : '',
                brands  : []
            };
        };


        $scope.brands = [];
        $scope.getBrands = function(){
            $http.get("/admin/ajax/brands")
                .then(function(response){
                    //console.log(response);
                    $scope.brands = response.data;
                },function(error){
                    console.error(error);
                });
        };


        $scope.validateUrl = function(){
            $scope.data.url = $scope.data.url.replace(/[^\w\s]|_/g, "-")
                .replace(/\s+/g, "-");
            // $http.post("/admin/ajax/validate-url",
            //     {
            //         test_url : $scope.data.url
            //     })
            //     .then(function(response){
            //         console.log(response);
            //     },function(error){
            //         console.error(error);
            //     });
        };


        $scope.step = {
            current : 1,
            max     : 3,
            back    : function(){
                if( $scope.step.current > 1 ){
                    $scope.step.current--;
                }
            },
            forward : function(){
                if( $scope.step.current < $scope.step.max ){
                    $scope.step.current++;
                }
            }
        };


        $scope.cancel = function(){
            $scope.reset();
            $($scope.wizard_id).modal('hide');
        };

        $scope.validate = function(){

        };


        $scope.create = function(){
            $scope.validateUrl();
            var brands = $filter('filter')($scope.brands, {selected:true});
            var brand_ids = [];
            angular.forEach(brands, function(brand){
                brand_ids.push(brand.id)
            });

            $http.post("/admin/ajax/create-store",
                {
                    name    : $scope.data.name,
                    url     : $scope.data.url,
                    brands  : brand_ids
                })
                .then(function(response){
                    console.log(response);
                    $scope.cancel();
                },function(error){
                    console.error(error);
                    $scope.cancel();
                });
        };


        $scope.init = function(){
            $scope.reset();
            $scope.getBrands();
        };
        $scope.init();

    }]
);