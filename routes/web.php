<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->post('/register','AuthController@register');
$router->post('/login','AuthController@login');
$router->get('/logout','AuthController@logout');

$router->group(['prefix'=>'user', 'middleware'=>'auth'], function() use($router) {
	$router->get('/all/{p}','UserController@index');
	$router->get('/all','UserController@index');
	$router->get('/show/{id}','UserController@show');
	$router->post('/filter/{p}','UserController@filter');
	$router->post('/filter','UserController@filter');

	$router->post('/add','UserController@store');
	$router->post('/update/{id}','UserController@update');
	$router->post('/delete/{id}','UserController@destroy');
});

$router->group(['prefix'=>'kategori'], function() use($router) {
	$router->get('/all/{p}','KategoriController@index');
	$router->get('/all','KategoriController@index');
	$router->get('/show/{id}','KategoriController@show');
	$router->post('/filter/{p}','KategoriController@filter');
	$router->post('/filter','KategoriController@filter');
	
	$router->group(['middleware'=>'auth'], function() use($router) {
		$router->post('/add','KategoriController@store');
		$router->post('/update/{id}','KategoriController@update');
		$router->post('/delete/{id}','KategoriController@destroy');
	});
});

$router->group(['prefix'=>'news'], function() use($router) {
	$router->get('/all/{p}','NewsController@index');
	$router->get('/all','NewsController@index');
	$router->get('/show/{id}','NewsController@show');
	$router->post('/filter/{p}','NewsController@filter');
	$router->post('/filter','NewsController@filter');
	
	$router->group(['middleware'=>'auth'], function() use($router) {
		$router->post('/add','NewsController@store');
		$router->post('/update/{id}','NewsController@update');
		$router->post('/delete/{id}','NewsController@destroy');
	});
});
