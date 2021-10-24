<?php

namespace LaravelOIDCAuth;

use Lcobucci\JWT\Signer\Rsa\Sha256;
use OpenIDConnectClient\OpenIDConnectProvider;

class OIDCProviderService
{
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
}
