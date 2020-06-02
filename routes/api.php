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

Route::post('/login', 'AuthController@login');

Route::post('/register', 'RegisterController@register');

Route::middleware(['auth:sanctum'])->group(function () {

    Route::apiResource('users', 'UserController')->except('store');

    Route::apiResource('products', 'ProductController');

    Route::apiResource('wishlists', 'WishlistController');

});
