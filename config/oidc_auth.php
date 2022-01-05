<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OIDC Provider Config
    |--------------------------------------------------------------------------
    |
    | This options to pass to OpenIDConnectClient\OpenIDConnectProvider.
    | `redirectUri` will be determined automatically by `callback_route` below.
    | `urlResourceOwnerDetails` is unused by us.
    | `publicKey` is in PEM format, either the content or `file://` to read
    |  from file.
    |
     */
    'provider' => [
        'clientId' => 'example',
        'clientSecret' => 'example',
        'idTokenIssuer' => 'example.com',
        'urlAuthorize' => 'http://example.com/authorize',
        'urlAccessToken' => 'http://example.com/token',
        'urlResourceOwnerDetails' => 'http://example.com/owner',
        'scopes' => ['openid'],
        'publicKey' => 'file:///key.pem',
    ],

    /*
    |--------------------------------------------------------------------------
    | Callback Route
    |--------------------------------------------------------------------------
    |
    | Callback route used by Authrization Code flow.
    |
     */
    'callback_route' => '/oidc/callback',

    /*
    |--------------------------------------------------------------------------
    | Authenticatable Factory
    |--------------------------------------------------------------------------
    |
    | Factory to get an Illuminate\Contracts\Auth\OIDCAuthenticatable to use, see
    | LaravelOIDCAuth\Contracts\OIDCAuthenticatableFactory.
    | For example, you can use an Eloquent model as OIDCAuthenticatable to store
    | user information in DB.
    | A OpenIDConnectClient\AccessToken will be passed to authenticatable()
    |
     */
    'authenticatable_factory' => \LaravelOIDCAuth\UserFactory::class,

    /*
    |--------------------------------------------------------------------------
    | Required Claims
    |--------------------------------------------------------------------------
    |
    | JWT claims in id_token required to authenticate. Arrays set required
    | elements in an array. Other values are matched exactly.
    |
    | This can also be a Closure to check id_token. id_token will be passed as
    | the first parameter. Return true to indicate a pass or false for a fail.
    |
     */
    'required_claims' => [
        //'name' => 'value',
        //'array' => ['required', 'elements'],
    ],
];
