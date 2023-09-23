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
}
