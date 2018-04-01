<?php

use Illuminate\Http\Request;
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
    Route::post('/register', 'RegisterController@store');

    Route::prefix('auth')->group(function () {
        Route::post('/token', 'AuthController@issueToken');
        Route::post('/token/revoke', 'AuthController@revokeToken')->middleware('auth:api');
    });

    Route::prefix('events')->group(function () {
        Route::get('/', 'EventController@index');
        Route::post('/', 'EventController@store')->middleware('auth:api');
        Route::get('/{event}', 'EventController@show');
    });

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return new UserResource($request->user());
    });
});
