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

Route::group(['middleware' => ['auth:api', 'cors']], function () {
    Route::get('users/logout', 'Auth\LoginController@logout');
    Route::get('users/check_token', 'UserController@checkLogin');
    Route::resource('users', 'UserController');
    Route::post('restaurants/{id}/opening-hours', 'RestaurantController@updateWorkHours');
    Route::get('restaurants/{id}/status/{date}/{hour}', 'RestaurantController@OpenStatus');
    Route::get('restaurants/{id}/availableTables/{class}/{peoplecount}/{date}/{hour}', 'RestaurantController@getAvailableTables');
    Route::resource('restaurants', 'RestaurantController');
    Route::resource('orders', 'OrderController');
    Route::resource('tables', 'TableController',  ['only' => ['store', 'show', 'update', 'destroy']]);
    Route::resource('foods', 'FoodController',  ['only' => ['store', 'update', 'destroy']]);
});
Route::post('users/register', 'Auth\RegisterController@register');
Route::post('users/login', 'Auth\LoginController@login');
Route::fallback(function () {
    return response()->json(['message' => 'Not Found!'], 404);
})->name('fallback');