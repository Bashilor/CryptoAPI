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

$router->group(['prefix' => 'api/v1', 'middleware' => 'auth'], function($app)
{
    $app->get('account/balance','AccountController@balance');

    $app->get('account/withdraw','AccountController@withdraw');

    $app->get('cryptocurrency','CryptocurrencyController@index');

    $app->get('cryptocurrency/{symbol}','CryptocurrencyController@get');

    $app->get('cryptocurrency/list/{symbols}','CryptocurrencyController@getlist');

    $app->post('payments/payment', 'PaymentController@create');

    $app->get('payments/payment', 'PaymentController@getlist');
});