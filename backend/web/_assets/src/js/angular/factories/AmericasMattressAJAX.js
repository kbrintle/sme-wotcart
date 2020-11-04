app.factory(
    "AmericasMattressAJAX",
    ['$http', '$httpParamSerializerJQLike',
    function($http, $httpParamSerializerJQLike){
        return{

            getProducts: function(success, failure){
                $http.get('/admin/product/json').then(success, failure);
            },

            productGridUpdateAttribute: function(data, success, failure){
                $http({
                    method          : 'POST',
                    url             : '/admin/product/update-attribute',
                    headers         : {'Content-Type': 'application/x-www-form-urlencoded'},
                    data            : $httpParamSerializerJQLike(data)
                }).then(success, failure);
            },

            productGridUpdateBulk: function(data, success, failure){
                $http({
                    method          : 'POST',
                    url             : '/admin/product/update-bulk',
                    headers         : {'Content-Type': 'application/x-www-form-urlencoded'},
                    data            : $httpParamSerializerJQLike(data)
                }).then(success, failure);
            },

            productGridDeleteProduct: function(data, success, failure){
                $http({
                    method          : 'POST',
                    url             : '/admin/product/json-delete',
                    headers         : {'Content-Type': 'application/x-www-form-urlencoded'},
                    data            : $httpParamSerializerJQLike(data)
                }).then(success, failure);
            }

        }
    }]
);