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
        $error = $request->get('error');
        if (!is_null($error)) {
            throw new AuthenticationErrorException($error);
        }

        if ($request->get('state') !== session('oidc-auth.state')) {
            throw new InvalidStateException();
        }

        if (!$request->has('code')) {
            throw new AuthenticationException('No authorization code received');
        }
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $request->get('code'),
        ]);

        $required = config('oidc-auth.required_claims');
        if (is_array($required)) {
            $idToken = $token->getIdToken();
            foreach ($required as $key => $value) {
                if (!$idToken->hasClaim($key)) {
                    abort(403);
                }
                $claim = $idToken->getClaim($key);

                if (is_array($value)) {
                    if (!is_array($claim)) {
                        abort(403);
                    }

                    if (array_intersect($value, $claim) !== $value) {
                        abort(403);
                    }
                } elseif ($claim !== $value) {
                    abort(403);
                }
            }
        } elseif ($required instanceof \Closure) {
            if (!$required($token->getIdToken())) {
                abort(403);
            }
        }

        session(['oidc-auth.access_token' => $token]);

        $factory = app(config('oidc-auth.authenticatable_factory'));

        Auth::login($factory->authenticatable($token));

        return redirect()->intended();
    }
}
