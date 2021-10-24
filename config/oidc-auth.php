<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OIDC Provider Config
    |--------------------------------------------------------------------------
    |
    | This options to pass to OpenIDConnectClient\OpenIDConnectProvider.
    | `redirectUri` will be determined automatically by `callback_route` below.
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
];
