<?php

namespace LaravelOIDCAuth;

use LaravelOIDCAuth\Contracts\OIDCAuthenticatable;
use OpenIDConnectClient\AccessToken;

class OIDCUser implements OIDCAuthenticatable
{
    use HasAccessToken, RefreshesAccessToken;

    public function __construct(AccessToken $token)
    {
        $this->accessToken = $token;
    }

    public function getAuthIdentifierName(): string
    {
        return 'sub';
    }

    public function getAuthIdentifier()
    {
        return $this->accessToken->getIdToken()->getClaim('sub');
    }

    public function getAuthPassword(): string
    {
        throw new \LogicException('Not applicable for OIDC auth.');
    }

    public function getRememberToken(): string
    {
        return '';
    }

    public function setRememberToken($value)
    {
        throw new \LogicException('Not implemented.');
    }

    public function getRememberTokenName(): string
    {
        throw new \LogicException('Not implemented.');
    }
}
