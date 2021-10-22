<?php

namespace App\Providers;

use App\Auth\Guards\OAuthGuard;
use Illuminate\Container\Container;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider {
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() {
        Auth::extend('portal', function (Container $app) {
            return new OAuthGuard($app['request']);
        });

        $this->registerPolicies();

        //
    }
}
