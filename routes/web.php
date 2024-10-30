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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('tasks', ['uses' => 'TaskController@store']);
    $router->get('tasks', ['uses' => 'TaskController@index']);
    $router->get('tasks/{id}', ['uses' => 'TaskController@show']);
    $router->put('tasks/{id}', ['uses' => 'TaskController@update']);
    $router->delete('tasks/{id}', ['uses' => 'TaskController@destroy']);
});

$router->get('/', function () use ($router) {
    return $router->app->version();
});
