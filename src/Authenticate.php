<?php

namespace LaravelOIDCAuth;

use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected $provider;

    public function __construct(Auth $auth, OIDCProviderService $service)
    {
        $this->provider = $service->getProvider();
        parent::__construct($auth);
    }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return $this->provider->getAuthorizationUrl();
        }
    }
}
