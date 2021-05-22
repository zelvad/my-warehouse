<?php


namespace Zelvad\MyWarehouse;


class MyWarehouseServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        /**
         * Publish config
         */
        $this->publishes([
            __DIR__ . '/All/config/my-warehouse.php' => config_path('my-warehouse.php'),
        ]);

        /**
         * Publish routes
         */
        $this->loadRoutesFrom(__DIR__ . '/All/routes/api.php');

        /**
         * Publish migrations
         */
        $this->loadMigrationsFrom(__DIR__.'/All/migrations');
    }
}
