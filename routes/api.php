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



Route::group(['namespace' => 'Api'], function () {
    Route::group(['prefix'=>'auth'], function () {
        Route::post('register', 'AuthController@register');
        Route::post('login', 'AuthController@login')->name('login');
        Route::post('logout', 'AuthController@logout')->middleware('auth:api');
        Route::middleware('auth:api')->get('user', function (Request $request) {
            return $request->user();
        });
    });
    Route::group(['middleware'=>'auth:api'], function () {
        Route::apiResource('author', 'AuthorController')->except(['update']);
        Route::apiResource('category', 'CategoryController')->except(['update']);
        Route::apiResource('book', 'BookController');
    });
});
