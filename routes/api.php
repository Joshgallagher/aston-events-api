<?php

use App\Http\Resources\UserResource;

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

Route::prefix('v1')->group(function () {
    Route::prefix('register')->group(function () {
        Route::post('/', 'RegisterController@store');
        Route::post('/confirm', 'EmailConfirmationController@index');
    });

    Route::prefix('auth')->group(function () {
        Route::post('/token', 'AuthController@issueToken');
        Route::post('/token/revoke', 'AuthController@revokeToken')->middleware('auth:api');
    });

    Route::prefix('search')->group(function () {
        Route::get('/', 'SearchController@show');
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', 'CategoryController@index');
        Route::get('/{category}', 'CategoryEventController@index');
    });

    Route::prefix('events')->group(function () {
        Route::get('/', 'EventController@index');
        Route::post('/', 'EventController@store')->middleware('auth:api');
        Route::get('/{event}', 'EventController@show');
        Route::patch('/{event}', 'EventController@update')->middleware('auth:api');
        Route::delete('/{event}', 'EventController@destroy')->middleware('auth:api');

        Route::post('/{event}/favorites', 'FavoriteController@store')->middleware('auth:api');
        Route::delete('/{event}/favorites', 'FavoriteController@destroy')->middleware('auth:api');

        Route::post('/{event}/media', 'EventMediaController@store')->middleware('auth:api');
        Route::delete('/media/{media}', 'EventMediaController@destroy')->middleware('auth:api');
    });

    Route::middleware('auth:api')->get('/user', function () {
        return new UserResource(auth()->user());
    });
});
