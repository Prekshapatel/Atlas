<?php

class ProductController extends BaseController {

	/**
	 *
   * ProductLists index method
   * Route::resource('products', 'ProductController', 
	 * array('only' => array('index')));
	 * });
	 */

	public function index()
	{
	  $this->api_key = Config::get('api_key', '123456789101112'); // Get the api key
	  // query parameters to get products list 
	  $this->$query = array(
		'key' => $this->api_key,
		'cats' => 'ACCOMM',
		'st' => 'NSW'
	  );
	  // send request to get productLists for accommodation options in the New South Wales.
		$response = $this->Request('GET','api/atlas/products', $this->$query);
		$productList = $response.products;
	  return $productList; // return productList to angular
	}
}
