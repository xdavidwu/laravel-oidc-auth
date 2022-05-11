<?php

namespace LaravelOIDCAuth\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\Factory as Auth;
use LaravelOIDCAuth\AuthorizationCodeFlowInterface;

class Authenticate extends Middleware
{
    protected AuthorizationCodeFlowInterface $flow;

    public function __construct(Auth $auth, AuthorizationCodeFlowInterface $flow)
    {
        $this->flow = $flow;
        parent::__construct($auth);
    }

    protected function redirectTo($request): ?string
    {
        if (!$request->expectsJson()) {
            return $this->flow->redirectToAuthorize()->getTargetUrl();
        }
        return null;
    }
}
