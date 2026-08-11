<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Models\Product;
use App\Models\User;
use App\Notifications\ProductCreatedNotification;
use App\Services\CounterService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/testd', function () {
    app(ProductService::class)->create([
        'name'        => 'Test Məhsul',
        'slug'        => 'test-mehsul',
        'description' => 'Test description',
        'price'       => 99.99,
        'sku'         => 'PRD-TEST01',
        'is_active'   => true,
        'category_id' => 1,
    ]);
});

Route::get('/di-test',function(ProductService $productService){
    dd($productService);
});



Route::get('/notification-test',function(){
        $user = User::first();

        $product = Product::first();

        $user->notify(
            new ProductCreatedNotification($product)
        );

        return 'Notification sent!';
});


Route::get('/mail-test', function () {

    Mail::raw('Hello Elnur! This is your first Laravel email 🚀', function ($message) {

        $message->to('elnurtaken@gmail.com')
                ->subject('Laravel Mail Test');

    });

    return 'Mail sent!';
});




Route::get('/cache-test', function () {



    $products = Cache::remember(
        'products',
        2600,
        function () {
            logger('Database soruşuldu!');
            return Product::all()->toArray();
        }
    );

    return response()->json($products);
});

Route::get('counter', function (CounterService $a) {
    $b = app(CounterService::class);
    $c = app(CounterService::class);
dd($a === $b && $b === $c);
});

Route::get('/yoxla', function():string{
    return 'worked';
});


// Route::apiResource('products',ProductController::class);
// Route::post('/orders', [OrderController::class, 'store']);
