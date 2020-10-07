<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use View;
use Auth;

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
        //
        Schema::defaultStringLength(191);

        date_default_timezone_set('Asia/Bangkok');
        
        View::composer('*', function($view){
            $view->with('userData', Auth::user());
        });

        View::composer('*', function($view){
            $view->with('configData', DB::connection('mongodb')->collection("config")->where('config','=','payment_currencycode')->get());
        });
    }
}
