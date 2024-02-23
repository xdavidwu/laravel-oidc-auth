<?php

namespace LaravelOIDCAuth;

use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected $oidcService;

    public function __construct(Auth $auth, OIDCService $service)
    {
        $this->oidcService = $service;
        parent::__construct($auth);
    }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return $this->oidcService->buildAuthorizationUrl();
        }
    }
}
