<?php

namespace LaravelOIDCAuth;

use Illuminate\Support\Facades\Auth;
use LaravelOIDCAuth\Contracts\OIDCAuthenticatable;

/**
 * Add refreshAccessToken function to an OIDCAuthenticatable
 *
 * This trait must be added to a class with HasAccessToken trait.
 * If the default guard is an OIDCSessionGuard, refreshAccessToken() will automatically to call Auth::setUser()
 *
 * @see OIDCAuthenticatable
 * @see HasAccessToken
 * @see OIDCSessionGuard
 */
trait RefreshesAccessToken
{
    /**
     * Refresh the access token of this user and set to the guard
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \OpenIDConnectClient\Exception\InvalidTokenException
     */
    public function refreshAccessToken()
    {
        /** @var AuthorizationCodeFlowInterface $flow */
        $flow = app()->make(AuthorizationCodeFlowInterface::class);

        $newAccessToken = $flow->refreshAccessToken($this->getAccessToken());

        $this->accessToken = $newAccessToken;

        if (Auth::guard() instanceof OIDCSessionGuard && $this instanceof OIDCAuthenticatable) {
            // Update the access token in the guard is necessary.
            // The new access token must be saved into the session for user next time login
            Auth::setUser($this);
        }
    }
}
