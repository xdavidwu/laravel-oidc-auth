<?php

namespace LaravelOIDCAuth;

use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use OpenIDConnectClient\OpenIDConnectProvider;

class Authenticate extends Middleware
{
    protected $signer;

    public function __construct(Auth $auth, Sha256 $signer)
    {
        $this->signer = $signer;
        parent::__construct($auth);
    }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            $provider = new OpenIDConnectProvider(
                array_merge(
                    config('oidc-auth.provider'),
                    [ 'redirectUri' => route('oidc-auth.callback') ]
                ),
                [ 'signer' => $this->signer ]
            );
            return $provider->getAuthorizationUrl();
        }
    }
}
