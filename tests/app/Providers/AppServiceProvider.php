<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // echo "accepted";exit;
        // Artisan::call('config:clear');
        // Artisan::call('cache:clear');
        // Artisan::call('dump-autoload');
        // Artisan::call('view:clear');
        // Artisan::call('route:clear');
        // $_ENV['MAIL_HOST'] = "test";
        // echo env('MAIL_HOST');exit;
    }
}
