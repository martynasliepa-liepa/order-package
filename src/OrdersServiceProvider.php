<?php

namespace Praktika\Orders;

use Illuminate\Support\ServiceProvider;

class OrdersServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // nurodomi paketo failu keliai
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'orders');
        $this->publishes([__DIR__.'/../config/orders.php' => config_path('orders.php'),], 'orders-config');

    }
}