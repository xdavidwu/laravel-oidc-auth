<?php

namespace LaravelOIDCAuth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use LaravelOIDCAuth\Events\OIDCAuthenticated;
use LaravelOIDCAuth\Events\OIDCAuthenticationFailed;
use LaravelOIDCAuth\Events\OIDCAuthenticationStarted;
use LaravelOIDCAuth\Events\OIDCAuthorizationFailed;
use LaravelOIDCAuth\Events\OIDCAuthorized;
use LaravelOIDCAuth\Events\TokenRefreshed;
use LaravelOIDCAuth\Exceptions\AuthenticationErrorException;
use LaravelOIDCAuth\Exceptions\AuthenticationException;
use LaravelOIDCAuth\Exceptions\InvalidStateException;
use OpenIDConnectClient\AccessToken;
use OpenIDConnectClient\OpenIDConnectProvider;

class AuthorizationCodeFlow implements AuthorizationCodeFlowInterface
{
    protected OpenIDConnectProvider $provider;

    public function __construct(OpenIDConnectProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Start the authorization code flow
     *
     * The 'state' is maintained automatically
     * @return RedirectResponse
     */
    public function redirectToAuthorize(): RedirectResponse
    {
        $url = $this->provider->getAuthorizationUrl();
        $state = $this->provider->getState();
        OIDCAuthenticationStarted::dispatch($url, $state);
        session()->flash('oidc-auth.state', $state);
        return response()->redirectTo($url);
    }

    /**
     * After the OpenID Connect provider returned an Authentication Response, call this function with Request
     * will get access token
     *
     * @param Request $callbackRequest
     * @return AccessToken
     * @throws AuthenticationErrorException if the authorization failed
     * @throws InvalidStateException if state in session doesn't match
     * @throws AuthenticationException if no authorization code receive
     * @throws \OpenIDConnectClient\Exception\InvalidTokenException if the returned token is invalid
     * @throws \Exception any other exception
     */
    public function getAccessToken(Request $callbackRequest): AccessToken
    {
        try {
            $this->validateCallbackRequest($callbackRequest);
        } catch (\Exception $exception) {
            OIDCAuthenticationFailed::dispatch($callbackRequest, $exception);
            throw $exception;
        }

        OIDCAuthenticated::dispatch($callbackRequest);

        try {
            $token = $this->provider->getAccessToken('authorization_code', [
                'code' => $callbackRequest->get('code'),
            ]);
        } catch (\Exception $exception) {
            OIDCAuthorizationFailed::dispatch($callbackRequest, $exception);
            throw $exception;
        }

        OIDCAuthorized::dispatch($token, $callbackRequest);

        return $token;
    }

    protected function validateCallbackRequest(Request $request)
    {
        $this->checkAuthenticationError($request);
        $this->checkState($request);
        $this->checkAuthorizationCodeExists($request);
    }

    protected function checkAuthenticationError(Request $request)
    {
        $error = $request->get('error');
        if (!is_null($error)) {
            throw new AuthenticationErrorException($error);
        }
    }

    protected function checkState(Request $request)
    {
        if ($request->get('state') !== session('oidc-auth.state')) {
            throw new InvalidStateException();
        }
    }

    protected function checkAuthorizationCodeExists(Request $request)
    {
        if (!$request->has('code')) {
            throw new AuthenticationException('No authorization code received');
        }
    }

    /**
     * Refresh access token
     *
     * @return AccessToken New access token
     * @throws AuthenticationErrorException if the authorization failed
     * @throws AuthenticationException if no authorization code receive
     * @throws \OpenIDConnectClient\Exception\InvalidTokenException if the returned token is invalid
     */
    public function refreshAccessToken(AccessToken $oldAccessToken): AccessToken
    {
        $newAccessToken = $this->provider->getAccessToken('refresh_token', [
            'refresh_token' => $oldAccessToken->getRefreshToken()
        ]);
        TokenRefreshed::dispatch($oldAccessToken, $newAccessToken);
        return $newAccessToken;
    }
}
