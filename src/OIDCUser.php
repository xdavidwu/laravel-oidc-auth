<?php

namespace LaravelOIDCAuth;

use Illuminate\Contracts\Auth\Authenticatable;

class OIDCUser implements Authenticatable
{
    protected $claims;

    public function __construct($claims)
    {
        $this->claims = $claims;
    }

    public function getClaims()
    {
        return $this->claims;
    }

    public function getAuthIdentifierName()
    {
        return 'sub';
    }

    public function getAuthIdentifier()
    {
        return $this->claims['sub'];
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
