<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::tokensCan([
            'overview_locations' => 'overview_locations',
            'create_locations' => 'create_locations',
            'view_locations' => 'view_locations',
            'update_locations' => 'update_locations',
            'delete_locations' => 'delete_locations',
            'search_locations' => 'search_locations',
            'overview_cars' => 'overview_cars',
            'create_cars' => 'create_cars',
            'view_cars' => 'view_cars',
            'update_cars' => 'update_cars',
            'delete_cars' => 'delete_cars',
            'search_cars' => 'search_cars',
        ]);

        Passport::routes();
    }
}
