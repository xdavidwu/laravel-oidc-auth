<?php

namespace LaravelOIDCAuth;

use Lcobucci\JWT\Signer\Rsa\Sha256;
use OpenIDConnectClient\OpenIDConnectProvider;

class OIDCService
{
    protected const TOKEN_SESSION_KEY = 'oidc-auth.access_token';

    protected $signer;
    protected $provider;

    public function __construct(Sha256 $signer)
    {
        $this->signer = $signer;
        $this->provider = new OpenIDConnectProvider(
            array_merge(
                config('oidc-auth.provider'),
                [ 'redirectUri' => route('oidc-auth.callback') ]
            ),
            [ 'signer' => $this->signer ]
        );
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function storeToken($token)
    {
        session([self::TOKEN_SESSION_KEY => $token]);
    }

    public function clearToken()
    {
        session()->forget(self::TOKEN_SESSION_KEY);
    }

    public function getStoredToken()
    {
        return session(self::TOKEN_SESSION_KEY);
    }

    public function getToken()
    {
        $token = $this->getStoredToken();
        if ($token->hasExpired()) {
            $newToken = $this->provider->getAccessToken('refresh_token', [
                'refresh_token' => $token->getRefreshToken(),
            ]);
            /**
             * TODO steverhoades/oauth2-openid-connect-client currently assumes
             * id_token is present on every grant, but oidc spec allow it to be
             * absent on refresh.
             *
             * after steverhoades/oauth2-openid-connect-client#40 lands, this
             * may breaks our built-in oidc-auth-session auth provider. we may
             * hack around it by detecting and patch in old id_token. we may
             * also consider using this in oidc-auth-session to always ensure a
             * valid token? (actually, considering id_token expiration for that
             * is more correct)
             */
            $this->storeToken($newToken);

            return $newToken;
        } else {
            return $token;
        }
    }
}
