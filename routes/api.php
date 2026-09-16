<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/products',ProductController::class);
Route::post('/orders', [OrderController::class, 'store']);

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function()
{
    Route::post('logout',[AuthController::class,'logout']);
    Route::get('/user',[AuthController::class,'user']);

});
