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

        $factory = app(config('oidc-auth.authenticatable_factory'));

        Auth::login($factory->authenticatable($token));

        return redirect()->intended();
    }
}
