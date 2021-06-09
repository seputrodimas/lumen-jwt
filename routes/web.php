<?php

use Illuminate\Support\Facades\Route;
use Auth0\Login\Auth0Controller;
use App\Http\Controllers\Auth\Auth0IndexController;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//$router->get('/auth0/callback',  ['uses' => 'Auth0Controller@callback'])->name('auth0-callback');
//Route::get('/auth0/callback', [Auth0Controller::class, 'callback'])->name('auth0-callback');

$router->group(['prefix' => 'token'], function () use ($router) {
    $router->get('handle', ['uses' => 'TokenController@handle']);
    $router->get('read', ['uses' => 'TokenController@read']); 
});

$router->group(['prefix' => 'post', 'middleware' => 'auth'], function () use ($router) {
    $router->get('all',  ['uses' => 'PostController@all']);
    $router->post('create', ['uses' => 'PostController@create']);
    $router->get('read/{id}', ['uses' => 'PostController@read']);
    $router->put('update/{id}', ['uses' => 'PostController@update']);
    $router->delete('delete/{id}', ['uses' => 'PostController@delete']);
});