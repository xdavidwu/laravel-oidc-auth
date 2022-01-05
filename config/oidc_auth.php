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
        'urlAuthorize' => 'https://example.com/authorize',
        'urlAccessToken' => 'https://example.com/token',
        'urlResourceOwnerDetails' => 'https://example.com/owner',
        'scopes' => ['openid'],
        'publicKey' => 'file:///key.pem',
    ],

    /*
    |--------------------------------------------------------------------------
    | Callback Route
    |--------------------------------------------------------------------------
    |
    | Callback route used by Authorization Code flow.
    |
     */
    'callback_route' => '/oidc/callback',

    /*
    |--------------------------------------------------------------------------
    | Register the default OIDC callback route
    |--------------------------------------------------------------------------
    |
    | If you want to customize the callback route, set this option to false.
    | Next, you can create your own route & controller to handle OIDC callback,
    | and the route must be named as 'oidc-auth.callback'.
    |
     */
    'register_default_callback_route' => true,

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
    | Auto Refresh
    |--------------------------------------------------------------------------
    |
    | This option determines whether to refresh the access token automatically.
    | This option is effective on \LaravelOIDCAuth\CallbackController.
    |
     */
    'auto_refresh' => false,

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
