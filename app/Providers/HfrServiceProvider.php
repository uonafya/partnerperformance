<?php

namespace App\Providers;

use App\Commons\Commons;
use App\Commons\controller_trait;
use App\Commons\divisions_callback;
use App\Commons\get_callback;
use App\Commons\get_hfr_sum;
use App\Commons\get_hfr_sum_prev;
use App\Commons\get_joins_callback_weeks_hfr;
use App\Commons\testingServiceRoutine;
use App\HfrSubmission;
use App\Http\Controllers\Former\Controller;
use App\Lookup;
use Illuminate\Support\Facades\Cache;
use DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

use App\Http\Controllers\HfrController;







class HfrServiceProvider extends ServiceProvider
{
    

    // use Commons, get_hfr_sum, 
	// 	get_hfr_sum_prev, get_joins_callback_weeks_hfr, 
	// 	get_callback, divisions_callback;
	// use testingServiceRoutine;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Etl\Contracts\EtlContract',
        );

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
