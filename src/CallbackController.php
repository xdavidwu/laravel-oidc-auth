<?php

namespace LaravelOIDCAuth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use OpenIDConnectClient\AccessToken;

class CallbackController extends Controller
{
    protected AuthorizationCodeFlowInterface $flow;

    public function __construct(AuthorizationCodeFlowInterface $flow)
    {
        $this->flow = $flow;
    }

    /**
     * The entry for authentication success redirected
     *
     * This function will:
     *  - get the token from request
     *  - validate id token claims by config('oidc_auth.required_claims')
     *  - make a OIDCAuthenticatableFactory as UserFactory
     *  - make an Authenticatable as user
     *  - login this user
     *  - return a response
     *
     * @param Request $request
     * @return mixed
     * @throws \OpenIDConnectClient\Exception\InvalidTokenException
     * @throws ValidationException
     */
    public function callback(Request $request)
    {
        $token = $this->flow->getAccessToken($request);
        $this->validateRequiredClaims($token);
        $user = app(config('oidc_auth.authenticatable_factory'))->authenticatable($token);
        $this->loginUser($user);

        return $this->getCallbackResponse($request, $token, $user);
    }

    /**
     * Validate the id token claims
     *
     * @param AccessToken $token
     * @return void
     * @throws ValidationException
     */
    protected function validateRequiredClaims(AccessToken $token)
    {
        $required = config('oidc-auth.required_claims');
        if (is_array($required)) {
            $idToken = $token->getIdToken();
            foreach ($required as $key => $value) {
                if (!$idToken->hasClaim($key)) {
                    throw ValidationException::withMessages([$key => "The '$key' field is required."]);
                }
                $claim = $idToken->getClaim($key);

                if (is_array($value)) {
                    if (!is_array($claim)) {
                        throw ValidationException::withMessages([$key => "The '$key' must be an array."]);
                    }

                    if (array_intersect($value, $claim) !== $value) {
                        throw ValidationException::withMessages([$key => "The '$key' is invalid."]);
                    }
                } elseif ($claim !== $value) {
                    throw ValidationException::withMessages([$key => "The '$key' is invalid."]);
                }
            }
        } elseif ($required instanceof \Closure) {
            if (!$required($token->getIdToken())) {
                throw ValidationException::withMessages(['id_token' => "The id token is invalid."]);
            }
        }
    }

    /**
     * Login the user
     *
     * @param Authenticatable $user
     */
    protected function loginUser(Authenticatable $user)
    {
        Auth::login($user, config('oidc_auth.auto_refresh', false));
    }

    /**
     * Return a response for callback route, default version is redirect user to intended url
     *
     * @param Request $request
     * @param AccessToken $token
     * @param Authenticatable $user
     * @return mixed
     */
    protected function getCallbackResponse(Request $request, AccessToken $token, Authenticatable $user)
    {
        return redirect()->intended();
    }
}
