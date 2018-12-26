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



Route::group(['middleware' => [], 'prefix' => 'v1'], function() {
    Route::get('/releases', ['uses' => '\Api\Http\Controllers\Manga\MangaController@releases']);
    Route::get('/manga', ['uses' => '\Api\Http\Controllers\Manga\MangaController@index']);
    Route::get('/manga/{key}', ['uses' => '\Api\Http\Controllers\Manga\MangaController@show']);
    Route::get('/manga/{key}/chapters', ['uses' => '\Api\Http\Controllers\Manga\MangaChaptersController@index']);
    Route::post('/manga/{key}/chapters', ['uses' => '\Api\Http\Controllers\Manga\MangaController@request']);
    Route::get('/manga/{key}/chapters/{number}', ['uses' => '\Api\Http\Controllers\Manga\MangaChaptersController@show']);
    Route::get('/chapters', ['uses' => '\Api\Http\Controllers\Manga\ChaptersController@index']);
    Route::get('/chapters/{key}', ['uses' => '\Api\Http\Controllers\Manga\ChaptersController@show']);
        
    Route::group(['middleware' => ['auth:api']], function() {
        Route::get('/library', ['uses' => '\Api\Http\Controllers\Manga\LibraryController@index']);
        Route::post('/library/{key}', ['uses' => '\Api\Http\Controllers\Manga\LibraryController@addManga']);
        Route::delete('/library/{key}', ['uses' => '\Api\Http\Controllers\Manga\LibraryController@removeManga']);
        Route::get('/library/{key}', ['uses' => '\Api\Http\Controllers\Manga\LibraryController@showManga']);

    });
});
