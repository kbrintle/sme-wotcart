app.controller(
    'ProductTableController',
    ['$scope', '$filter', 'Pagination', 'CheckAll', 'AmericasMattressAJAX',
        function($scope, $filter, Pagination, CheckAll, AmericasMattressAJAX){
            $scope.pagination = Pagination;

            $scope.loading  = true;             //show the loading div
            $scope.products = [];
            $scope.filters  = {                 //predefining these isn't necessary for this to work, I just like keeping my variables predefined if I can help it.
                set     : null,
                sku     : null,
                type    : null,
                name    : null,
                size    : null,
                brand   : null,
                active  : null
            };
            $scope.sort     = {
                type    : null,
                reverse : false
            };

            /**
             * Get Products
             */
            $scope.getProducts = function(){
                AmericasMattressAJAX.getProducts(function(response){
                    $scope.products = response.data;                //set the scope products as the response
                    $scope.pagination.init($scope.products, 50);     //initialize the Pagination factory based on the scope products (second param is the default `perPage`)

                    //get filterable sets by parsing the response data
                    $scope.getSizes();
                    $scope.getBrands();
                    $scope.getSets();

                    $scope.loading  = false;                        //hide the loading div
                }, function(error){
                    console.error(error);
                });
            };
            $scope.getProducts();                                   //trigger on load


            /**
             * Get Brands (compiled from response)
             */
            $scope.getBrands = function(){
                $scope.brands = [];
                angular.forEach($scope.products, function(product, i){
                    if( product.brand
                        && $scope.brands.indexOf(product.brand) == -1 ){
                        $scope.brands.push(product.brand);
                    }
                });
            };

            /**
             * Get Sizes (compiled from response)
             */
            $scope.getSizes = function(){
                $scope.sizes = [];
                angular.forEach($scope.products, function(product, i){
                    if( product.size
                        && $scope.sizes.indexOf(product.size) == -1 ){
                        $scope.sizes.push(product.size);
                    }
                });
            };

            /**
             * Get Sizes (compiled from response)
             */
            $scope.getSets = function(){
                $scope.sets = [];
                angular.forEach($scope.products, function(product, i){
                    if( product.set
                        && $scope.sets.indexOf(product.set) == -1 ){
                        $scope.sets.push(product.set);
                    }
                });
            };


            /**
             * Check and Uncheck (see CheckAll factory)
             * @type {CheckAll}
             */
            $scope.CheckAll = new CheckAll.init();
            $scope.checkAll = function(){
                $scope.CheckAll.checkAll(
                    $scope.pagination.data.currentData,
                    $scope.pagination.data.paginationSet);
            };
            $scope.checkAllVisible = function(){
                $scope.CheckAll.checkAllVisible(
                    $scope.pagination.data.currentData,
                    $scope.pagination.data.paginationSet);
            };
            $scope.checkItem = function(){
                $scope.CheckAll.checkItem(
                    $scope.pagination.data.currentData,
                    $scope.pagination.data.paginationSet);
            };

            /**
             *
             * @returns {*}
             */
            $scope.checkedProducts = function(){
                return $filter('filter')($scope.pagination.data.currentData, {selected:true});
            };

            /**
             * update Pagination scope with sort and filter params
             * @NOTE this must be run whenever a scope variable is altered
             */
            $scope.updateSort = function(){
                var filterOptions = {
                    'set'	: {
                        'type'          : 'filter',
                        'expression'    : $scope.filters.set
                    },
                    'sku'	: {
                        'type'          : 'filter',
                        'expression'    : $scope.filters.sku
                    },
                    'type'	: {
                        'type'          : 'filter',
                        'expression'    : $scope.filters.type
                    },
                    'name'	: {
                        'type'          : 'filter',
                        'expression'    : $scope.filters.name
                    },
                    'size'	: {
                        'type'          : 'filter',
                        'expression'    : $scope.filters.size,
                        'strict'        : true
                    },
                    'brand'	: {
                        'type'          : 'filter',
                        'expression'    : $scope.filters.brand
                    },
                    'price'	: {
                        'type'          : 'range',
                        'expression'    : {
                            key: 'price',
                            min: $scope.filters.price ? $scope.filters.price.min : null,
                            max: $scope.filters.price ? $scope.filters.price.max : null
                        }
                    },
                    'special-price'	: {
                        'type'          : 'range',
                        'expression'    : {
                            key: 'special-price',
                            min: $scope.filters.special_price ? $scope.filters.special_price.min : null,
                            max: $scope.filters.special_price ? $scope.filters.special_price.max : null
                        }
                    },
                    'active'	: {
                        'type'          : 'filter',
                        'expression'    : $scope.filters.active
                    }
                };

                console.log(filterOptions);

                $scope.pagination.updateSort($scope.sort, filterOptions);
            };

            /**
             * Update bulk
             */
            $scope.updateBulk = function(){
                var selected_products_ids = [];
                angular.forEach($scope.checkedProducts(), function(product){
                    selected_products_ids.push(product.id);
                });

                var value = $scope.bulk_update.value;
                if(value === true){
                    value = 1;
                }
                if(value === false){
                    value = 0;
                }

                var data = {
                    ProductGrid: {
                        selected_products   : selected_products_ids,
                        selected_key        : $scope.bulk_update.key,
                        selected_value      : value
                    }
                };

                $scope.loading = true;

                AmericasMattressAJAX.productGridUpdateBulk(
                    data,
                    function(response){
                        console.log(response);
                        if($scope.bulk_update.key == 'delete'){
                            $scope.success_message = response.data.success.length + " products were successfully deleted";
                            $scope.cullProducts(response.data.success);
                        }else{
                            angular.forEach($scope.checkedProducts(), function(product){
                                product[$scope.bulk_update.key] = $scope.bulk_update.value;
                            });
                        }
                        $scope.loading = false;
                    },
                    function(error){
                        console.error(error);
                    });
            };

            /**
             * Update individual attribute value
             * @NOTE use for inline fields
             *
             * @param record
             * @param key
             */
            $scope.updateAttribute = function(record, key){

                var value = record[key];
                if( value === true ){
                    value = 1;
                }
                if( value === false ){
                    value = 0;
                }

                var data = {
                    ProductGrid: {
                        switch_id       : record.id,
                        switch_key      : key,
                        switch_value    : value
                    }
                };

                AmericasMattressAJAX.productGridUpdateAttribute(
                    data,
                    function(response){
                        console.log(response);
                    },
                    function(error){
                        console.error(error);
                    });
            };


            /**
             * Set delete_product and open modal
             *
             * @param product
             */
            $scope.initiateDelete = function(product){
                $scope.delete_product = product;        //set delete_product var
                $('#deleteProductModal').modal('show'); //show modal
            };

            /**
             * Delete Single Product
             *
             * @param product
             */
            $scope.deleteProduct = function(product){
                var data = {    //set data object to be sent to server
                    ProductGrid: {
                        switch_id: product.id
                    }
                };

                AmericasMattressAJAX.productGridDeleteProduct(  //send data to server through AmericasMattressAJAX factory
                    data,   //set data
                    function(response){ //receive success response
                        $scope.success_message = "Product #"+product.id+": "+product.name+" was successfully deleted.";  //set success message
                        $scope.cullProducts([parseInt(response.data)]);
                        $scope.updateSort();                        //refresh Pagination to remove deleted row
                        $('#deleteProductModal').modal('hide');     //close modal
                        $scope.delete_product = null;               //reset delete_product var
                    },
                    function(error){    //receive error response
                        console.error(error);
                    });
            };


            /**
             * Cull Products from existing scope products object
             *
             * @param cull_products
             */
            $scope.cullProducts = function(cull_products){
                console.log('culling:', cull_products);

                var temp_arr = [];

                angular.forEach($scope.products, function(product){     //loop through each product
                    if( cull_products.indexOf(parseInt(product.id)) == -1 ){      //check if current product does not exist in the cull array
                        temp_arr.push(product);                         //push product into temp array if does not exist in cull array
                    }
                });

                $scope.products = temp_arr;                             //set the scope products as the culled array
                $scope.pagination.init($scope.products, 50);            //initialize the Pagination factory based on the culled scope products
            }

        }]
);