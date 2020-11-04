/** Instantiate application */
angular.module('wot-cart', ['wot-cart.controllers']);

/** create controllers */
var app = angular.module('wot-cart.controllers', []);

/**
 * Get CSRF values
 */
app.value('$csrf', $('meta[name="csrf-token"]').attr("content"));
app.value('$csrf_param', $('meta[name="csrf-param"]').attr("content"));

/**
 * Force CSRF into all headers to satisfy Yii2 CSRF validation
 */
app.run(
    [
        '$http',
        function run($http){
        $http.defaults.headers.common['X-Requested-With']   = 'XMLHttpRequest';
        $http.defaults.headers.put['X-Requested-With']      = 'XMLHttpRequest';
        $http.defaults.headers.post['X-Requested-With']     = 'XMLHttpRequest';
        $http.defaults.headers.patch['X-Requested-With']    = 'XMLHttpRequest';
        $http.defaults.headers.common['X-CSRF-Token']       = $('meta[name="csrf-token"]').attr("content");
        $http.defaults.headers.put['X-CSRF-Token']          = $('meta[name="csrf-token"]').attr("content");
        $http.defaults.headers.post['X-CSRF-Token']         = $('meta[name="csrf-token"]').attr("content");
        $http.defaults.headers.patch['X-CSRF-Token']        = $('meta[name="csrf-token"]').attr("content");
    }
    ]
);

app.filter('categoryFilter', function(){
    return function(list, filter, element){
        if( angular.isArray(filter)
            && filter.length > 0 ){

            var filtered_items = [];

            angular.forEach(list, function(item){
                var add_item = false;

                angular.forEach(filter, function(keyword){
                    if( !add_item
                        && item.category == keyword ){
                        filtered_items.push(item);
                        add_item = true;
                    }
                });

            });
            return filtered_items;
        }
        return list;
    };
});
app.filter('brandFilter', function(){
    return function(list, filter, element){
        if( angular.isArray(filter)
            && filter.length > 0 ){

            var filtered_items = [];

            angular.forEach(list, function(item){
                var add_item = false;

                angular.forEach(filter, function(keyword){
                    if( !add_item
                        && item.brand == keyword ){
                        filtered_items.push(item);
                        add_item = true;
                    }
                });

            });
            return filtered_items;
        }
        return list;
    };
});
app.filter('attributeFilter', function(){
    return function(list, filter, element){
        var filtered_items = list;

        angular.forEach(filter, function(values, key){
            if( values.length > 0 ){
                var temp_arr = [];
                angular.forEach(filtered_items, function(item){

                    var add_item = false;
                    angular.forEach(values, function(value){
                        if( !add_item ){
                            if( item.attributes[key]
                                && item.attributes[key].value
                                && item.attributes[key].value == value ){
                                temp_arr.push(item);
                                add_item = true;
                            }
                        }
                    });

                });
                filtered_items = temp_arr;
            }
        });

        return filtered_items;
    }
});
app.filter('priceFilter', function(){
    return function(list, filter, element){
        if( angular.isArray(filter)
            && filter.length > 0 ){

            var filtered_items = [];

            angular.forEach(list, function(item){
                var price       = parseFloat(item.attributes.price.value);
                var add_item    = false;

                angular.forEach(filter, function(range){
                    if( !add_item ){
                        if( range.min && range.max ){
                            if( price >= range.min
                                && price <= range.max ){
                                filtered_items.push(item);
                                add_item = true;
                            }
                        }

                        if( range.min && !range.max ){
                            if( price >= range.min ){
                                filtered_items.push(item);
                                add_item = true;
                            }
                        }

                        if( !range.min && range.max ){
                            if( price <= range.max ){
                                filtered_items.push(item);
                                add_item = true;
                            }
                        }
                    }
                });

            });
            return filtered_items;
        }
        return list;
    }
});





/**
 * Checkout
 */
app.controller(
    'CheckoutFormController',
    ['$scope', '$http', '$httpParamSerializerJQLike',
        function($scope, $http, $httpParamSerializerJQLike){
            $scope.subtotal     = 0;
            $scope.shipping     = 0;
            $scope.sales_tax    = 0;
            $scope.old_zipcode  = null;

            $scope.total = function(){
                var output = 0;
                output = output + $scope.subtotal;
                output = output + $scope.shipping;
                output = output + $scope.sales_tax;

                return output;
            };


            $scope.setShipping = function(shipping){
                $scope.shipping = shipping;
            };

        }

    ]);


/**
 * Create Review
 */
app.controller(
    'CreateReviewController',
    ['$scope', '$http', '$httpParamSerializerJQLike',
        function($scope, $http, $httpParamSerializerJQLike){
            $scope.errors   = {};
            $scope.form     = {};

            $scope.addError = function(key, error){
                $scope.errors[key] = error;
            };
            $scope.removeError = function(key){
                delete $scope.errors[key];
            };
            $scope.resetErrors = function(){
                $scope.errors = {};
            };

            $scope.validate = function(){
                var validated = true;

                $scope.resetErrors();

                if( !$scope.form.title ){
                    $scope.addError('title', 'Title is required');
                    validated = false;
                }

                if( !$scope.form.detail ){
                    $scope.addError('detail', 'Review is required');
                    validated = false;
                }

                if( !$scope.form.rating
                    || $scope.form.rating == 0 ){
                    $scope.addError('rating', 'Rating is required');
                    validated = false;
                }

                return validated;
            };


            $scope.submit = function(){
                console.log('submit');

                $scope.disabled = false;


                if( $scope.validate() ){
                    $scope.disabled = true; //disabled multiple submission if validation passes

                    var data = {
                        CreateReviewForm : $scope.form
                    };

                    var store_path = document.location.pathname.split('/')[1];

                    $http({
                        method          : 'POST',
                        url             : '/'+store_path+'/shop/review-submission',
                        headers         : {'Content-Type': 'application/x-www-form-urlencoded'},
                        data            : $httpParamSerializerJQLike(data)
                    }).then(function(response){
                        console.log(response);
                        $scope.disabled = false;
                        $scope.success  = true;
                    }, function(error){
                        console.error(error);
                        $scope.disabled = false;
                    });


                }

            };
        }

    ]);


/**
 * Product Grid
 */
app.controller(
    'ProductGridController',
    ['$scope', '$http', '$filter',
        function($scope, $http, $filter){
            /**
             * Variables
             */
            $scope.loading  = true;
            $scope.products = [];   //DO NOT modify `products` directly
            $scope.filtered_products = [];
            $scope.categories = [];
            $scope.brands   = [];
            $scope.filters  = {};
            $scope.hidden_filters = [];

            $scope.sort_options = {
                //position: 'Position',
                name    : 'Name',
                price   : 'Price'
            };
            $scope.selected = {
                categories  : [],
                brands      : [],
                attributes  : {}
            };
            $scope.price_ranges = [
                {
                    'id'    : 0,                        //for easier mapping ranges to filters
                    'label' : '$0.00 - $299.99',        //for displaying on view
                    'min'   : 0,                        //for >= calc
                    'max'   : 299.99                    //for <= calc
                },
                {
                    'id'    : 1,
                    'label' : '$300.00 - $499.99',
                    'min'   : 300,
                    'max'   : 499.99
                },
                {
                    'id'    : 2,
                    'label' : '$500.00 and above',
                    'min'   : 500
                }
            ];

            $scope.sort_type    = null;
            $scope.sort_order   = false;

            $scope.per_page     = 30;
            $scope.current_page = 1;



            /**
             * Toggle checkbox selection
             */
            $scope.toggleCategorySelection = function(value){
                var index = $scope.selected.categories.indexOf(value);

                if( index > -1 ){
                    $scope.selected.categories.splice(index, 1);
                }else{
                    $scope.selected.categories.push(value);
                }

                $scope.updateFilters();
            };
            $scope.toggleBrandSelection = function(value){
                var index = $scope.selected.brands.indexOf(value);

                if( index > -1 ){
                    $scope.selected.brands.splice(index, 1);
                }else{
                    $scope.selected.brands.push(value);
                }

                $scope.updateFilters();
            };
            $scope.toggleAttributeSelection = function(key, value){
                var index = $scope.selected.attributes[key].indexOf(value);

                if( index > -1 ){
                    $scope.selected.attributes[key].splice(index, 1);
                }else{
                    $scope.selected.attributes[key].push(value);
                }

                $scope.updateFilters();
            };
            $scope.togglePriceRangeSelection = function(price_range){
                price_range.selected = !price_range.selected;
                $scope.updateFilters();
            };
            $scope.isChecked = function(key, value){
                if( $scope.selected.attributes[key] )
                    return $scope.selected.attributes[key].indexOf(value) > -1;
                return false;
            };

            /**
             * Get Products
             */
            $scope.getProducts = function(){
                var path = document.location.origin + document.location.pathname;
                $http.get(path).then(function(response){
                    $scope.products = response.data;
                    $scope.updateFilters(true);
                    $scope.loading = false;
                }, function(error){
                    console.error(error);
                    if(error.data){
                        $scope.products = error.data;
                        $scope.updateFilters(true);
                        $scope.loading = false;
                    }
                });
            };


            /**
             * Calculate Filtered Products
             * @returns {*}
             */
            $scope.filteredProducts = function(){
                var products = $scope.products;

                //sort
                if( $scope.sort_type == 'price' || $scope.sort_type == 'name'){

                    function compare(a,b){
                        var compare_a = a.attributes[$scope.sort_type].value;
                        var compare_b = b.attributes[$scope.sort_type].value;

                        if( $scope.sort_type == 'price' ){
                            compare_a = parseFloat(compare_a);
                            compare_b = parseFloat(compare_b);
                        }


                        if( $scope.sort_order ){
                            if( compare_a > compare_b )
                                return -1;
                            if( compare_a < compare_b )
                                return 1;
                            return 0;
                        }else{
                            if( compare_a < compare_b )
                                return -1;
                            if( compare_a > compare_b )
                                return 1;
                            return 0;
                        }
                    }

                    products.sort(compare);

                }

                //filters
                products    = $filter('categoryFilter')(products, $scope.selected.categories);
                products    = $filter('brandFilter')(products, $scope.selected.brands);
                products    = $filter('attributeFilter')(products, $scope.selected.attributes);
                products    = $filter('priceFilter')(products, $filter('filter')($scope.filters.price_ranges, {selected:true}) );

                return products;
            };

            $scope.pagedProducts = function(){
                //pagination
                var pageStart   = ($scope.per_page * $scope.current_page) - $scope.per_page;
                var pageEnd     = $scope.per_page * $scope.current_page;

                //set the params as a last step
                $scope.setParams();

                $scope.filtered_products = $scope.filteredProducts().slice(pageStart, pageEnd);
            };

            /**
             * Create Categories filter
             */
            $scope.createCategoriesFilter = function(cb){
                var products = $scope.filteredProducts();
                // $scope.brands = []; //empty the filterable brands
                if( angular.isArray(products) ){

                    angular.forEach(products, function(v, i){
                        if( $scope.categories.indexOf(v.category) == -1 ){
                            $scope.categories.push(v.category);
                        }
                    });

                    $scope.categories.sort();

                    if(cb && typeof cb == "function") cb();
                }
            };

            /**
             * Create Brands filter
             */
            $scope.createBrandsFilter = function(cb){
                var products = $scope.filteredProducts();
                // $scope.brands = []; //empty the filterable brands
                if( angular.isArray(products) ){

                    angular.forEach(products, function(v, i){
                        if( $scope.brands.indexOf(v.brand) == -1 ){
                            $scope.brands.push(v.brand);
                        }
                    });

                    $scope.brands.sort();

                    if(cb && typeof cb == "function") cb();
                }
            };

            /**
             * Create Attributes filters
             */
            $scope.createAttributesFilters = function(cb){
                var products = $scope.filteredProducts();

                // $scope.filters = {};   //empty the filterable objects


                if( angular.isArray(products) ){

                    if( products.length == 0 ){
                        if(cb && typeof cb == "function") cb();
                    }

                    angular.forEach(products, function(v, index){
                        angular.forEach(v.attributes, function(v, i){
                            if( i != 'price' ){
                                if(v.filterable == 1){
                                    //create initial filter object if it does not already exist
                                    if( !$scope.filters[i] ){
                                        $scope.filters[i] = {
                                            label : v.label,
                                            value : []
                                        };
                                    };


                                    //create initial selected object if it does not already exist
                                    if( !$scope.selected.attributes[i] ){
                                        $scope.selected.attributes[i] = [];
                                    }


                                    //insert filter value if it does not exist yet
                                    if( v.value ){
                                        if( $scope.filters[i].value.indexOf(v.value) == -1 ){
                                            $scope.filters[i].value.push(v.value);
                                        }
                                    }

                                    $scope.filters[i].value.sort();
                                }
                            }
                        });

                        if( index+1 == products.length )
                            if(cb && typeof cb == "function") cb();
                    });
                }
            };
            /**
             * Create Price ranges
             */
            $scope.createPriceRanges = function(cb){
                var products = $scope.filteredProducts();

                if( angular.isArray(products) ){
                    var temp_arr = [];

                    angular.forEach(products, function(v, i){
                        var price = parseFloat(v.attributes.price.value);

                        angular.forEach($scope.price_ranges, function(range, index){
                            if( range.min && range.max ){
                                if( price >= range.min
                                    && price <= range.max ){
                                    temp_arr[index] = range;
                                }
                            }

                            if( range.min && !range.max ){
                                if( price >= range.min ){
                                    temp_arr[index] = range;
                                }
                            }

                            if( !range.min && range.max ){
                                if( price <= range.max ){
                                    temp_arr[index] = range;
                                }
                            }
                        })

                    });

                    $scope.filters.price_ranges = temp_arr.filter(Boolean);

                    if(cb && typeof cb == "function") cb();
                }
            };

            /**
             * Update filters
             */
            $scope.updateFilters = function(filter_by_params){
                $scope.createBrandsFilter(function(){
                    $scope.createAttributesFilters(function(){
                        $scope.createPriceRanges(function(){
                            if( filter_by_params ){
                                $scope.loadParams();
                            }else{
                                $scope.go_to_page(1);   //filters are changing? go back to the first page
                            }
                        });
                    });
                });
            };

            /**
             * Update sort options
             */
            $scope.updateSortType = function(type){
                $scope.sort_type = type;
                $scope.go_to_page(1);   //filters are changing? go back to the first page
            };
            $scope.updateSortOrder = function(){
                $scope.sort_order = !$scope.sort_order;
                $scope.go_to_page(1);   //filters are changing? go back to the first page
            };


            /**
             * Pagination functions
             */
            $scope.page_count = function(){
                return Math.ceil($scope.filteredProducts().length / $scope.per_page);
            };
            $scope.pages = function(){
                var pages = [];
                for(var i=0; i < $scope.page_count(); i++){
                    pages.push(i+1);
                }
                return pages;
            };
            $scope.min_page = function(){
                if( $scope.current_page <= 1 )
                    return true;
                else
                    return false;
            };
            $scope.max_page = function(){
                if( $scope.current_page >= $scope.page_count() )
                    return true;
                else
                    return false;
            };
            $scope.go_to_page = function(page_number){
                $scope.current_page = page_number;
                $scope.pagedProducts();
            };
            $scope.page_back = function () {
                $scope.current_page--;
                $scope.go_to_page($scope.current_page);
            };
            $scope.page_forward = function () {
                $scope.current_page++;
                $scope.go_to_page($scope.current_page);

            };


            /**
             * GET params functions
             */
            function getQueryParams(){
                if( location.search ){
                    var search = location.search.substring(1);
                    return JSON.parse('{"' + decodeURI(search).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}')
                }
                return null;
            }
            $scope.loadParams = function(){
                var query_params = getQueryParams();

                if( query_params ){
                    //sort orders
                    if( query_params.sort_order )
                        $scope.sort_order = query_params.sort_order;
                    if( query_params.sort_type )
                        $scope.sort_type = query_params.sort_type;

                    //categories
                    if( query_params.categories ){
                        var values = query_params.categories.split('|');
                        for(i=0; i < values.length; i++){
                            values[i] = decodeURI(values[i]).split('+').join(' ');
                        }
                        $scope.selected.categories = values;
                    }

                    //brands
                    if( query_params.brands ){
                        var values = query_params.brands.split('|');
                        for(i=0; i < values.length; i++){
                            values[i] = decodeURI(values[i]).split('+').join(' ');
                        }
                        $scope.selected.brands = values;
                    }

                    //attributes
                    angular.forEach($scope.filters, function(attribute, key){
                        if( key != 'price_ranges' ){
                            if( query_params[key] ){
                                var values = query_params[key].split('|');
                                for(i=0; i < values.length; i++){
                                    values[i] = decodeURI(values[i]).split('+').join(' ');
                                }
                                $scope.selected.attributes[key] = values;
                            }
                        }
                    });

                    //price_ranges
                    if( query_params.price_ranges ){
                        var values = query_params.price_ranges.split('|');
                        angular.forEach(values, function(value){
                            angular.forEach($scope.price_ranges, function(price_range, i){
                                if(price_range.id == value)
                                    $scope.price_ranges[i].selected = true;
                            });
                        });
                    }

                    //pagination & trigger event
                    if( query_params.page )
                        $scope.current_page = query_params.page;
                }

                $scope.go_to_page($scope.current_page);
            };
            $scope.setParams = function(){
                var path = document.location.pathname;

                //create path
                // var path_obj = getQueryParams();
                // path_obj = path_obj ? path_obj : {};
                var path_obj = {};
                if( $scope.sort_order )
                    path_obj.sort_order = $scope.sort_order;
                if( $scope.sort_type )
                    path_obj.sort_type = $scope.sort_type;

                //categories
                if( $scope.selected.categories.length > 0 ){
                    path_obj.categories = $scope.selected.categories.join('|');
                }

                //brands
                if( $scope.selected.brands.length > 0 ){
                    path_obj.brands = $scope.selected.brands.join('|');
                }

                //attributes
                angular.forEach($scope.selected.attributes, function(values, key){
                    if( values.length > 0 ){
                        path_obj[key] = values.join('|');
                    }
                });

                //price_ranges
                var price_ranges = $filter('filter')($scope.filters.price_ranges, {selected:true});
                if( price_ranges && price_ranges.length > 0 ){
                    var temp_arr = [];
                    angular.forEach(price_ranges, function(price_range){
                        temp_arr.push(price_range.id);
                    });
                    path_obj.price_ranges = temp_arr.join('|');
                }

                //pagination
                if( $scope.current_page > 1 ){
                    path_obj.page = $scope.current_page;
                }

                var query_params = $.param(path_obj);
                if( query_params ){
                    path = path+"?"+query_params;
                }

                history.replaceState(null, null, path);
            };

            $scope.hideFilter = function(filter_name){
                return $.inArray(filter_name, $scope.hidden_filters) > -1;
            };


            /**
             * Initialization commands
             */
            $scope.init = function(){
                $scope.getProducts();
            };
            $scope.init();

        }
    ]);