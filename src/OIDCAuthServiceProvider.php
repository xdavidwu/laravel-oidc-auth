<?php

namespace LaravelOIDCAuth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use OpenIDConnectClient\OpenIDConnectProvider;

class OIDCAuthServiceProvider extends ServiceProvider
{
    /**
     * Register OIDC Auth services.
     *
     * Please override other functions in this class instead of boot() to customize.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/oidc_auth.php' => config_path('oidc_auth.php'),
        ]);
        if (config('oidc_auth.register_default_callback_route', true)) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/oidc_auth.php');
        }

        $this->app->bind(OpenIDConnectProvider::class, function ($app) {
            return new OpenIDConnectProvider(
                array_merge(
                    config('oidc_auth.provider'),
                    ['redirectUri' => route('oidc-auth.callback')]
                ),
                ['signer' => $app->make(Sha256::class)]
            );
        });
        $this->app->bind(AuthorizationCodeFlowInterface::class, AuthorizationCodeFlow::class);
        $this->setupGuard();
    }

    protected function setupGuard()
    {
        Auth::extend('oidc-auth-session', function ($app, $name, array $config) {
            return new OIDCSessionGuard(
                $name,
                $app->make(AuthorizationCodeFlowInterface::class),
                $app->make(config('oidc_auth.authenticatable_factory', UserFactory::class)),
            );
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/oidc_auth.php', 'oidc_auth');
    }
}
