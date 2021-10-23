<?php

namespace LaravelOIDCAuth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use OpenIDConnectClient\OpenIDConnectProvider;

class CallbackController extends Controller
{
    protected $signer;

    public function __construct(Sha256 $signer)
    {
        $this->signer = $signer;
    }

    public function callback(Request $request)
    {
        $provider = new OpenIDConnectProvider(
            array_merge(
                config('oidc-auth.provider'),
                [ 'redirectUri' => route('oidc-auth.callback') ]
            ),
            [ 'signer' => $this->signer ]
        );
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $request->get('code'),
        ]);
        $claims = $token->getIdToken()->getClaims();
        session(['oidc-auth.claims' => $claims]);
        Auth::login(new OIDCUser($claims));
        return redirect()->intended();
    }
}
