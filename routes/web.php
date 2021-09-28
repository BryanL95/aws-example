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

$router->get('/shop', 'ShopController@index');
$router->post('/images', 'ShopController@upload');
$router->get('/my-image', 'ShopController@download');
$router->get('/move', 'ShopController@move');
$router->get('/list', 'ShopController@getObjects');
$router->get('/my-object', 'ShopController@getObject');
