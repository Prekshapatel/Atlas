angular.module('mainCtrl', [])

// inject the product service into our controller
.controller('mainController', function($scope, $http, Product) {
  // loading variable to show the spinning loading icon
  $scope.loading = true;

  // get all the products and bind it to the $scope.products object
  Product.get()
    .success(function(data) {
      $scope.products = data; // assign data to scope
      $scope.loading = false; // set loading to false once success
  });
});
