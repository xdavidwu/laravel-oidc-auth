<?php

namespace LaravelOIDCAuth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use OpenIDConnectClient\AccessToken;

interface AuthorizationCodeFlowInterface
{
    /**
     * Start the authorization code flow
     *
     * The 'state' is maintained automatically
     * @return RedirectResponse
     */
    public function redirectToAuthorize(): RedirectResponse;

    /**
     * After the OpenID Connect provider returned an Authentication Response, call this function with Request
     * will get access token
     *
     * @param Request $callbackRequest
     * @return AccessToken
     * @throws \LaravelOIDCAuth\Exceptions\AuthenticationErrorException if the authorization failed
     * @throws \LaravelOIDCAuth\Exceptions\InvalidStateException if state in session doesn't match
     * @throws \LaravelOIDCAuth\Exceptions\AuthenticationException if no authorization code receive
     * @throws \OpenIDConnectClient\Exception\InvalidTokenException if the returned token is invalid
     */
    public function getAccessToken(Request $callbackRequest): AccessToken;

    /**
     * Refresh access token
     *
     * @return AccessToken New access token
     * @throws \LaravelOIDCAuth\Exceptions\AuthenticationErrorException if the authorization failed
     * @throws \LaravelOIDCAuth\Exceptions\AuthenticationException if no authorization code receive
     * @throws \OpenIDConnectClient\Exception\InvalidTokenException if the returned token is invalid
     */
    public function refreshAccessToken(AccessToken $oldAccessToken): AccessToken;
}
