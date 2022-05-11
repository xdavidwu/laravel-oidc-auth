<?php

namespace LaravelOIDCAuth;

use LaravelOIDCAuth\Contracts\OIDCAuthenticatable;
use LaravelOIDCAuth\Contracts\OIDCAuthenticatableFactory;
use OpenIDConnectClient\AccessToken;

class UserFactory implements OIDCAuthenticatableFactory
{
    public function authenticatable(AccessToken $token): OIDCAuthenticatable
    {
        return new OIDCUser($token);
    }
}
