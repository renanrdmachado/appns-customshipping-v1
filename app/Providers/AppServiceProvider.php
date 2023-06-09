<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
	
use Illuminate\Support\Facades\View;
use App\Models\App;

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
        view()->composer('*', function ($view) 
        {


            $storeActive=App::storeActive();
            View::share('storeActive',$storeActive); 

            $isFreeTrial=App::isFreeTrial();
            View::share('isFreeTrial',$isFreeTrial); 
            
            $nuvemshopActive=App::nuvemshopActive();
            View::share('nuvemshopActive',$nuvemshopActive); 
            
            $hasCeps=App::hasCeps();
            View::share('hasCeps',$hasCeps); 

        });
    }
}
