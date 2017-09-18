<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1'], function() {


	Route::any('/sign-in', ['uses' => '\Api\Http\Controllers\Auth\AuthController@signIn']);
	Route::any('/sign-up', ['uses' => '\Api\Http\Controllers\Auth\RegistrationController@index']);

    Route::any('/oauth/{name}/access_token', ['uses' => '\Api\Http\Controllers\Auth\SignInController@accessToken']);
    Route::any('/oauth/{name}/exchange_token', ['uses' => '\Api\Http\Controllers\Auth\SignInController@exchangeToken']);

    Route::group(['middleware' => 'auth:api'], function() {
        Route::any('/user', ['uses' => '\Api\Http\Controllers\User\UserController@index']);

    });
});
