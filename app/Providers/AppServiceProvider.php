<?php

namespace App\Providers;

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
        $this->bootPortalSocialite();
    }

    private function bootPortalSocialite()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');

        $socialite->extend(
            'portal',
            function ($app) use ($socialite) {
                $config = $app['config']['ncu.portal'];
                return $socialite->buildProvider(\Ncucc\Portal\PortalBaseProvider::class, $config);
            }
        );
    }
}
