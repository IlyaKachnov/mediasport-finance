<?php

namespace App\Providers;
use App\Models\League;
use App\Models\Gym;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         if ( env( 'APP_ENV', 'local' ) !== 'local' )
    {
        \DB::connection()->disableQueryLog();
    }
        Schema::defaultStringLength(191);
       
         view()->composer('layouts.sidebar', function($view)
    { 
            
        $view->with('allLeagues',  League::with('matches')->get())
                ->with('allGyms', Gym::with('matches')->get());
    });
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
