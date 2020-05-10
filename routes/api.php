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

Route::post('login', 'API\UserController@login');
Route::post('mobileLogin', 'API\UserController@mobileLogin');
Route::post('otpVerification', 'API\UserController@otpVerification');
Route::post('register', 'API\UserController@register');

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'API\UserController@details');
    Route::get('restaurant/items/{rest_id}', 'RestaurantController@Items');
    Route::resource('restaurant', 'RestaurantController');
    Route::resource('items', 'ItemsController');
});
