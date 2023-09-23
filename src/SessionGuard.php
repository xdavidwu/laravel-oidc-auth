<?php

namespace LaravelOIDCAuth;

use Illuminate\Auth\SessionGuard as BaseGuard;

class SessionGuard extends BaseGuard
{
    public function logout()
    {
        parent::logout();
        app(OIDCService::class)->clearToken();
    }
}
