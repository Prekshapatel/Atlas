<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('index');
});

//Route::get('/', 'HomeController@showWelcome');

// API ROUTES for productLists
Route::group(array('prefix' => 'api'), function() {
	
  // since we will be using this just for index list, we won't need create, edit,store, delete
  // this ensures that a user can't access api/create, api/edit, api/delete, api/store when there's nothing there
  Route::resource('products', 'ProductController', 
	array('only' => array('index')));
	
});
