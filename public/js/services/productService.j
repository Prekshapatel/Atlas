angular.module('productService', [])

.factory('Product', function($http) {
    return {
        // get all the products
        get : function() {
            return $http.get('/api/products');
        },
    }
});
