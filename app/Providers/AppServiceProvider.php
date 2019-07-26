<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // if(env('DB_HOST') != '10.231.111.110'){
        //     \Illuminate\Support\Facades\URL::forceScheme('https');
        // }
        \Illuminate\Support\Facades\URL::forceScheme('https');
        // \Illuminate\Support\Facades\URL::forceRootUrl(url(''));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
