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
	return View::make('hello');
});

Route::controller('admin', 'AdminController');

// Route::group(['prefix' => 'admin'], function() {
// 	Route::get('/', [
// 		'as' => 'adminIndex',
// 		'uses' => 'AdminController@showIndex'
// 	]);
	
// 	Route::get('/', array('as' => 'profile', function() {
// 		return 'this is a test';
// 	}));
// });

