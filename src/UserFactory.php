<?php

namespace LaravelOIDCAuth;

use OpenIDConnectClient\AccessToken;

class UserFactory implements UserFactoryInterface
{
    public function authenticatable(AccessToken $token)
    {
        return new OIDCUser($token);
    }
}
