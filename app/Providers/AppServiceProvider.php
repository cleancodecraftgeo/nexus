<?php

namespace App\Providers;

use App\Contracts\InventoryServiceInterface;
use App\Models\Product;
use App\Observers\ProductObserver;
use App\Policies\ProductPolicy;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Product\ProductRepository;
use App\Services\CounterService;
use App\Services\InventoryService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );
        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());
        Product::observe(ProductObserver::class);
        Gate::policy(Product::class, ProductPolicy::class);

        $this->app->bind(
            InventoryServiceInterface::class,
            InventoryService::class
        );

        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class
        );

        $this->app->singleton(
            CounterService::class,
            CounterService::class
        );
    }
}
