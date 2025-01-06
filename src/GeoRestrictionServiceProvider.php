<?php

namespace Laravel\GeoRestriction;

use Illuminate\Support\ServiceProvider;

class GeoRestrictionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'georestriction');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/config.php'=> config_path('georestriction.php')
        ],'georestriction-config' );

        $router = $this->app['router'];
        $router->aliasMiddleware('geo.restriction', \Laravel\GeoRestriction\Middleware\GeoRestrictionMiddleware::class);
    }
}
