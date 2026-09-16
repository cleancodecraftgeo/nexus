<?php

use App\Exceptions\InsufficientStockException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Exceptions\OutOfStockException;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
         api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
          $middleware->redirectGuestsTo(function (Request $request) {
        if ($request->is('api/*')) {
            return null;
        }

        return route('login');
    });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(
            function (InsufficientStockException $e){
                return response()->json([
                    'success'=>false,
                    'code' => 'INSUFFICIENT_STOCK',
                    'message'=>$e->getMessage(),
                    'details'=>[
                        'requested'=>$e->requested,
                        'available'=>$e->available,
                    ]
                ],409);
            }
        );
        $exceptions->render(function(OutOfStockException $e) {
            return response()->json([
                'success'=>false,
                'code'=>'OUT_OF_STOCK',
                'message'=>$e->getMessage(),
                'details'=>null,
            ],409);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                ], 401);
            }
        });
    })->create();

