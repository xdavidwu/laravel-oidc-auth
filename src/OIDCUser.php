<?php

namespace LaravelOIDCAuth;

use Illuminate\Contracts\Auth\Authenticatable;

class OIDCUser implements Authenticatable
{
    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function getAccessToken()
    {
        return $this->token;
    }

    public function getAuthIdentifierName()
    {
        return 'sub';
    }

    public function getAuthIdentifier()
    {
        return $this->token->getIdToken()->getClaim('sub');
    }

    public function getAuthPassword()
    {
        throw new \LogicException('Not applicable for OIDC auth.');
    }

    public function getRememberToken()
    {
        return [];
    }

    public function setRememberToken($value)
    {
        throw new \LogicException('Not implemented.');
    }

    public function getRememberTokenName()
    {
        throw new \LogicException('Not implemented.');
    }
}
