<?php

namespace LaravelOIDCAuth;

use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected $provider;

    public function __construct(Auth $auth, OIDCService $service)
    {
        $this->provider = $service->getProvider();
        parent::__construct($auth);
    }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            $url = $this->provider->getAuthorizationUrl();
            session()->flash('oidc-auth.state', $this->provider->getState());
            return $url;
        }
    }
}
