<?php

namespace LaravelOIDCAuth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CallbackController extends Controller
{
    protected $provider;

    public function __construct(OIDCProviderService $service)
    {
        $this->provider = $service->getProvider();
    }

    public function callback(Request $request)
    {
        if ($request->get('state') !== session('oidc-auth.state')) {
            return response('state does not match', 400);
        }

        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $request->get('code'),
        ]);

        session(['oidc-auth.access_token' => $token]);

        Auth::login(new OIDCUser($token));

        return redirect()->intended();
    }
}
