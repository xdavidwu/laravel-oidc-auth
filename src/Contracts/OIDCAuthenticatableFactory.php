<?php

namespace LaravelOIDCAuth\Contracts;

use OpenIDConnectClient\AccessToken;

interface OIDCAuthenticatableFactory
{
    /**
     * Get an Authenticatable by access token.
     *
     * @param AccessToken $token
     * @return \LaravelOIDCAuth\Contracts\OIDCAuthenticatable
     */
    public function authenticatable(AccessToken $token): OIDCAuthenticatable;
}
