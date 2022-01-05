<?php

namespace LaravelOIDCAuth;

use OpenIDConnectClient\AccessToken;

/**
 * Add a accessToken function to an Authenticatable
 *
 */
trait HasAccessToken
{
    protected AccessToken $accessToken;

    public function getAccessToken(): AccessToken
    {
        return $this->accessToken;
    }

    public function setAccessToken(AccessToken $token)
    {
        $this->accessToken = $token;
    }
}
