<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;






    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);


Route::middleware('auth:sanctum')->group(function()
{
    Route::post('logout',[AuthController::class,'logout']);
    Route::get('/user',[AuthController::class,'user']);

    Route::apiResource('/products',ProductController::class);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/my-orders',[OrderController::class,'myOrders']);
    Route::get('/orders/{order}',[OrderController::class,'show']);
    Route::put('/profile',[ProfileController::class,'update']);
});
