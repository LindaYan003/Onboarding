<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group(['prefix' => 'parcels'], function () use ($router) {
    $router->get('/', 'ParcelController@index');
    $router->get('/{id}', 'ParcelController@show');
    $router->post('/', 'ParcelController@store');
    $router->put('/{id}', 'ParcelController@update');
    $router->delete('/{id}', 'ParcelController@destroy');
});
