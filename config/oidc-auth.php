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
    | Factory to get a Illuminate\Contracts\Auth\Authenticatable to use, see
    | LaravelOIDCAuth\UserFactoryInterface.
    | For example, you can use a Eloquent model as Authenticatable to store
    | user information in DB.
    | A OpenIDConnectClient\AccessToken will be passed to authenticable()
    |
     */
    'authenticatable_factory' => \LaravelOIDCAuth\UserFactory::class,
];
