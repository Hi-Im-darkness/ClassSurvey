<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Route;
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

        /* Passport::tokensCan([ */
        /*     'ADMIN' => 'admin role', */
        /*     'STUDENT' => 'student role', */
        /*     'TEACHER' => 'teacher role' */
        /* ]); */
        Passport::routes();

        Route::group(['middleware' => 'oauth.providers'], function () {
            Passport::routes(function ($router) {
                return $router->forAccessTokens();
            });
        });
    }
}
