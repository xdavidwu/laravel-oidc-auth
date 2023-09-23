<?php

namespace LaravelOIDCAuth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class SessionUserProvider implements UserProvider
{
    protected $oidcService;

    public function __construct(OIDCService $service)
    {
        $this->oidcService = $service;
    }

    public function retrieveById($identifier)
    {
        if ($token = $this->oidcService->getStoredToken()) {
            $user = new OIDCUser($token);
            if ($user->getAuthIdentifier() === $identifier) {
                return $user;
            }
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
