<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
// use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;

Route::apiResource('/products',ProductController::class);
Route::post('/orders', [OrderController::class, 'store']);
