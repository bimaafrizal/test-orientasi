<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/datas', 'ExampleController@index' );

$router->group(['prefix' => 'api',], function () use ($router) {
    $router->group(['middleware' => 'auth'], function () use ($router) {   
        $router->group(['prefix'=> 'admin'], function () use ($router) {
            $router->get('/',  ['uses' => 'AdminController@showAllDatas']);
            
            $router->get('/{id}', ['uses' => 'AdminController@showOneData']);
            
            $router->post('/', ['uses' => 'AdminController@create']);
            
            $router->delete('/{id}', ['uses' => 'AdminController@delete']);
            
            $router->put('/{id}', ['uses' => 'AdminController@update']);
        });    
        $router->post('/logout', ['uses' => 'AuthController@logout']);
    });

    $router->post('/login', ['uses' => 'AuthController@login']);
  });