<?php

namespace App\Providers;
use Illuminate\Pagination\Paginator;
use App\Observers\AssetObserver;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use LdapRecord\Laravel\LdapUserProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    protected $policies = [
        Asset::class => AssetPolicy::class,
        AssetCategory::class => AssetCategoryPolicy::class,
        Department::class => DepartmentPolicy::class,
    ];

    


    public function register(): void
    {
        $this->app->bind('App\Models\Asset', function ($app) {
            return new Asset();
        });
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Asset::observe(AssetObserver::class);

        LdapRecord\Laravel\LdapServiceProvider::class;

        Maatwebsite\Excel\ExcelServiceProvider::class;
        
        
        // Gate::define('view-asset', function(User $user) {
        //     return $user->is_admin == 1;
        // });
        // Gate::define('view-asset_category', function(User $user) {
        //     return $user->is_admin == 2;
        // });
        // Gate::define('view-asset_category', function(User $user) {
        //     return $user->is_admin == 1;
        // });
        // Gate::define('view-department', function(User $user) {
        //     return $user->is_admin == 1;
        // });
        // Gate::define('view-region', function(User $user) {
        //     return $user->is_admin == 1;
        // });
        // Gate::define('view-location', function(User $user) {
        //     return $user->is_admin == 1;
        // });
        // Gate::define('view-vendor', function(User $user) {
        //     return $user->is_admin == 1;
        // });
        // Gate::define('view-report', function(User $user) {
        //     return $user->is_admin == 1;
        // });
        
        // Gate::define('create-asset', function(User $user) {
        //     return $user->is_technician == 2;
        // });
        // Gate::define('create_asset_category', function(User $user) {
        //     return $user->is_technician == 2;
        // });
        // Gate::define('create-department', function(User $user) {
        //     return $user->is_technician == 2;
        // });
        // Gate::define('create_region', function(User $user) {
        //     return $user->is_technician == 2;
        // });
        // Gate::define('create-location', function(User $user) {
        //     return $user->is_technician == 2;
        // });
        // Gate::define('create-vendor', function(User $user) {
        //     return $user->is_technician == 2;
        // });
        // Gate::define('view-report', function(User $user) {
        //     return $user->is_admin == 2;
        // });
    }
}
