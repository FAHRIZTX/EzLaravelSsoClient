<?php

namespace Fahriztx\EzLaravelSsoClient\Providers;

use Illuminate\Support\ServiceProvider;
use Fahriztx\EzLaravelSsoClient\Core\EzLaravelSsoClient;

class EzLaravelSsoClientServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ez_laravel_sso_client', EzLaravelSsoClient::class);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->publish();
    }

    protected function publish()
    {
        $this->publishes(
            [
                __DIR__ . '/../config/ezlaravelssoclient.php' => config_path('ezlaravelssoclient.php'),
            ],
            'ssoclient'
        );
    }
}
