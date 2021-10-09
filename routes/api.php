<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['jwt.verify'], 'prefix' => 'v1'], function() {
  Route::post('/auth/refresh','UserController@refreshToken');
  Route::get('/auth/expire','UserController@expireToken');
});

Route::group(['middleware' => [], 'prefix' => 'v1'], function() {
  Route::post('/auth/login','UserController@login');
});
