<?php

namespace LaravelOIDCAuth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class SessionUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        if (session()->has('oidc-auth.claims')) {
            return new OIDCUser(session('oidc-auth.claims'));
        }
    }

    public function retrieveByToken($identifier, $token)
    {
        throw new \LogicException('Not implemented');
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        throw new \LogicException('Not implemented');
    }

    public function retrieveByCredentials(array $credentials)
    {
        throw new \LogicException('Not applicable for OIDC auth.');
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        throw new \LogicException('Not applicable for OIDC auth.');
    }
}
