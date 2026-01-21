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
    return response()->json(['hi' => 'funcionou']);
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/me', [
        'middleware' => 'auth',
        'uses' => 'UsuarioController@getUsuarioData'
    ]);
    $router->post('/login', ['uses' => 'UsuarioController@validateLogin']);

    $router->post('/register', ['uses' => 'UsuarioController@createRegister']);

    $router->put('/update-ficha', [
        'middleware' => 'auth',
        'uses' => 'UsuarioController@updateFicha'
    ]);
});
