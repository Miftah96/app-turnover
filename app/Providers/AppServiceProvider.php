<?php

namespace App\Providers;

use App\Helpers\PermissionHelper;
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
        // //
        // view()->composer('*', function ($view) {
        //     $view->with('permissionAccess', PermissionHelper::permissionAccess())
        //     ->with('currentRoute', request()->route()->getName());
        // });
    }
}
