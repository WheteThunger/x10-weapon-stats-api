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

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);


Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('/', ['as' => 'adminIndex', 'uses' => 'AdminController@getIndex']);

    Route::model('weapons', 'X10WeaponStatsApi\Models\Weapon');
    Route::resource('weapons', 'WeaponsController');

    Route::model('attributes', 'X10WeaponStatsApi\Models\Attribute');
    Route::resource('attributes', 'AttributesController');

    Route::model('people', 'X10WeaponStatsApi\Models\Person');
    Route::resource('people', 'PeopleController');

    Route::model('weaponInstance', 'X10WeaponStatsApi\Models\WeaponInstance');
    Route::resource('weapon-instance', 'WeaponInstancesController');

    Route::model('config', X10WeaponStatsApi\Models\Config::class);
    Route::resource('configs', 'ConfigsController');

});

Route::group(['prefix' => 'api/v1', 'namespace' => 'Api\v1'], function () {
    Route::get('/graph', [
        'as'   => 'api.graph.list',
        'uses' => 'ApiController@getGraphList'
    ]);
    
    Route::get('/attributes', [
    	'as' => 'api.attributes.list',
    	'uses' => 'ApiController@getAttributeList'
    ]);
    
    Route::get('/weapons', [
    	'as' => 'api.weapons.list',
    	'uses' => 'ApiController@getWeaponList'
    ]);
    
    Route::get('/sample_get_schema_output', [
    'as'   => 'api.sample.output',
    'uses' => 'ApiController@sampleOutput'
        ]);
});














