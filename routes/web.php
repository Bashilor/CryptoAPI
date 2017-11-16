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
    return view('home');
});

$router->group(['prefix' => 'api/v1', 'middleware' => 'auth'], function($app)
{
    $app->get('account/balance','AccountController@balance');
    $app->post('account/withdraw','AccountController@withdraw');
    $app->get('account/token','AccountController@token');

    $app->get('cryptocurrency','CryptocurrencyController@index');
    $app->get('cryptocurrency/{symbol}','CryptocurrencyController@get');
    $app->get('cryptocurrency/list/{symbols}','CryptocurrencyController@getlist');

    $app->post('payments/payment', 'PaymentController@create');
    $app->get('payments/status/{payment_uuid}', 'PaymentController@status');
    // $app->post('payments/status/{payment_uuid}', 'PaymentController@update');
    $app->get('payments/payment', 'PaymentController@getlist');
});