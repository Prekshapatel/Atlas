Atlas is a single page appliation built on Laravel as PHP MVC framework and Angular as a frontend MVC framework.

Flow:
- Homepage renders the product list with product id and product name using angular.
- Angular calls api/products which calls the Products controller Index method.
- Through Products controller index, calling REST API for ATDW Atlas.
- REST API calls are made using GuzzleHTTP library loaded via composer and added function into BaseContoller which is shared among all the controllers.
- REST API config/environment variables are set in config/local/app.php as we are using local environment.