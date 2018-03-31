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

Route::prefix('v1')->group(function () {
    Route::post('/register', 'RegisterController@store');

    Route::prefix('auth')->group(function () {
        Route::post('/token', 'AuthController@issueToken');
        Route::post('/token/revoke', 'AuthController@revokeToken')->middleware('auth:api');
    });

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });
});
