<?php

namespace LaravelOIDCAuth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class OIDCAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/oidc-auth.php' => config_path('oidc-auth.php'),
        ]);
        $this->loadRoutesFrom(__DIR__ . '/../routes/oidc-auth.php');

        Auth::provider('oidc-auth-session', function ($app, array $config) {
            return new SessionUserProvider();
        });
        Auth::extend('oidc-auth-session', function ($app, $name, array $config) {
            $provider = Auth::createUserProvider($config['provider'] ?? null);
            $guard = new SessionGuard($name, $provider, $app['session.store']);
            $guard->setCookieJar($this->app['cookie']);
            return $guard;
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/oidc-auth.php', 'oidc-auth');
    }
}
