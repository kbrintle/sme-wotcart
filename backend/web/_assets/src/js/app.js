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
app.run([
    '$http',
    function run($http){
        $http.defaults.headers.common['X-Requested-With']   = 'XMLHttpRequest';
        $http.defaults.headers.put['X-Requested-With']      = 'XMLHttpRequest';
        $http.defaults.headers.post['X-Requested-With']     = 'XMLHttpRequest';
        $http.defaults.headers.patch['X-Requested-With']    = 'XMLHttpRequest';
        $http.defaults.headers.common['X-CSRF-Token']   = $('meta[name="csrf-token"]').attr("content");
        $http.defaults.headers.put['X-CSRF-Token']      = $('meta[name="csrf-token"]').attr("content");
        $http.defaults.headers.post['X-CSRF-Token']     = $('meta[name="csrf-token"]').attr("content");
        $http.defaults.headers.patch['X-CSRF-Token']    = $('meta[name="csrf-token"]').attr("content");
    }
]);