<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
});
$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->group(['prefix' => 'parcels'], function () use ($router) {
        $router->get('/', 'ParcelController@index');
        $router->get('/{id}', 'ParcelController@show');
        $router->post('/', 'ParcelController@store');
        $router->put('/{id}', 'ParcelController@update');
        $router->delete('/{id}', 'ParcelController@destroy');

        });$router->group(['prefix' => 'auth'], function () use ($router) {

        $router->get('/me', 'AuthController@me');
        $router->post('/logout', 'AuthController@logout');
    });
});

