<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
	return \Illuminate\Support\Facades\File::get('index.html');
});

Route::group(['prefix' => 'app'], function () {
	Route::get('/', 'MTGController@index');
	Route::get('/names', 'MTGController@names');
	Route::get('/{name}', 'MTGController@show');
});