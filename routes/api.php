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

Route::resource('user', 'UserController')->only(['store']);

Route::post('login', 'UserController@login');

Route::resource('beasiswa', 'BeasiswaController')->only(['index', 'show']);

Route::group(['middleware' => ['user.isloggedin']], function () {
    Route::resource('user', 'UserController')->only(['index', 'show', 'update']);
    Route::resource('pendaftaran', 'PendaftaranController')->only(['show', 'destroy']);

    Route::group(['prefix' => 'beasiswa/{id_beasiswa}'], function () {
        Route::resource('pendaftaran', 'PendaftaranController')->only(['store']);
    });

    Route::group(['middleware' => ['user.isadmin']], function () {
        Route::resource('user', 'UserController')->only(['index']);

        Route::resource('beasiswa', 'BeasiswaController')->only(['store', 'update', 'destroy']);
    });
});
