<?php

namespace LaravelOIDCAuth\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use OpenIDConnectClient\AccessToken;

interface OIDCAuthenticatable extends Authenticatable
{
    /**
     * Get the OIDC access token
     *
     * @return AccessToken
     */
    public function getAccessToken(): AccessToken;
}
