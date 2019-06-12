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

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('users/logout', 'Auth\LoginController@logout');
    Route::resource('users', 'UserController');
    Route::resource('restaurants', 'RestaurantController');
    Route::resource('orders', 'OrderController');
});
Route::post('users/register', 'Auth\RegisterController@register');
Route::post('users/login', 'Auth\LoginController@login');
Route::fallback(function () {
    return response()->json(['message' => 'Not Found!'], 404);
})->name('fallback');