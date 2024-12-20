<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'orders', 'namespace' => '\App\Http\Controllers\API'], function () {
    Route::get('/', 'OrderController@index');
    Route::post('/', 'OrderController@store');
    Route::get('/{orderNumber}', 'OrderController@show');
});

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');
