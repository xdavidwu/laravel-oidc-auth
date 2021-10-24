<?php

namespace LaravelOIDCAuth;

use Illuminate\Auth\SessionGuard as BaseGuard;

class SessionGuard extends BaseGuard
{
    public function logout()
    {
        parent::logout();
        session()->forget('oidc-auth.access_token');
    }
}
