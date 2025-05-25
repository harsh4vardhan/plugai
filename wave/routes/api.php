<?php

use App\Http\Controllers\GenerationController;
use Illuminate\Support\Facades\Route;

Route::post('login', '\Wave\Http\Controllers\API\AuthController@login');
Route::post('register', '\Wave\Http\Controllers\API\AuthController@register');
Route::post('logout', '\Wave\Http\Controllers\API\AuthController@logout');
Route::post('refresh', '\Wave\Http\Controllers\API\AuthController@refresh');
Route::post('token', '\Wave\Http\Controllers\API\AuthController@token');

Route::middleware('token_api')->post('/generate', [GenerationController::class, 'generate']);
