<?php

namespace LaravelOIDCAuth;

use OpenIDConnectClient\AccessToken;

interface UserFactoryInterface
{
    /**
     * Get a Illuminate\Contracts\Auth\Authenticatable from access token.
     *
     */
    public function authenticatable(AccessToken $token);
}
