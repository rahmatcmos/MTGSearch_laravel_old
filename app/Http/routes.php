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

$app->get('/', function(){
	return \Illuminate\Support\Facades\File::get('index.html');
});

$app->group(['prefix' => 'app'], function($app){
	$app->get('/', 'App\Http\Controllers\MTGController@index');
	$app->get('/names', 'App\Http\Controllers\MTGController@names');
	$app->get('/{name}', 'App\Http\Controllers\MTGController@show');
});