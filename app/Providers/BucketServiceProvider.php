<?php

namespace App\Providers;

use App\Services\Bucket\BucketService;
use App\Services\Bucket\Grabar;
use Illuminate\Support\ServiceProvider;

class BucketServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind("bucket", function ($app) {
            return new BucketService(new Grabar());
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
