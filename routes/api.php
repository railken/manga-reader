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



Route::group(['middleware' => ['errors'], 'prefix' => 'v1'], function() {


	Route::post('/sign-in', ['as' => 'login', 'uses' => '\Api\Http\Controllers\Auth\SignInController@signIn']);
	Route::post('/sign-up', ['uses' => '\Api\Http\Controllers\Auth\RegistrationController@index']);
    Route::post('/confirm-email', ['uses' => '\Api\Http\Controllers\Auth\RegistrationController@confirmEmail']);


    Route::post('/oauth/{name}/access_token', ['uses' => '\Api\Http\Controllers\Auth\SignInController@accessToken']);
    Route::post('/oauth/{name}/exchange_token', ['uses' => '\Api\Http\Controllers\Auth\SignInController@exchangeToken']);

    Route::group(['middleware' => ['auth:api']], function() {
        Route::get('/user', ['uses' => '\Api\Http\Controllers\User\UserController@index']);

        Route::get('/manga', ['uses' => '\Api\Http\Controllers\Manga\MangaController@index']);
        Route::get('/manga/{key}', ['uses' => '\Api\Http\Controllers\Manga\MangaController@show']);
        Route::get('/library', ['uses' => '\Api\Http\Controllers\Manga\LibraryController@index']);
        Route::post('/library/{key}', ['uses' => '\Api\Http\Controllers\Manga\LibraryController@addManga']);
        Route::delete('/library/{key}', ['uses' => '\Api\Http\Controllers\Manga\LibraryController@removeManga']);

    });
});
