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

    /*
    |--------------------------------------------------------------------------
    | Required Claims
    |--------------------------------------------------------------------------
    |
    | JWT claims in id_token required to authenticate. Arrays set required
    | elements in an array. Other values are matched exactly.
    |
    | For customized validation rules, supply a \Closure that takes id_token as
    | its input, and returns true for successful validation, false for failure.
    | 
    | When caching the config with artisan, wrap the closure in a serialized 
    | \Laravel\SerializableClosure\SerializableClosure instance. Be sure to set
    | secret key via `SerializableClosure::setSecretKey(config("app.key"));`
    |
     */
    'required_claims' => [
        // 'name' => 'value',
        // 'array' => ['required', 'elements'],
    ],
    // Provide custom validation logic with a Closure
    // 'required_claims' => fn ($claims) =>
    //     $claims->has('name') && $claims->get('name') === 'value',
    // In case you need to cache the config, make the closure serializable
    // 'required_claims' => serialize(new SerializableClosure(
    //     fn ($claims) => $claims->has('name') && $claims->get('name') === 'value')),

    /*
    |--------------------------------------------------------------------------
    | Default Redirect URL after Authentication
    |--------------------------------------------------------------------------
    |
    | The callback route, after successful authentication, redirects user to
    | previously intended location. This is set when user is redirected from
    | Authenticate middleware of Laravel and this library.
    |
    | This config sets the default URL when the intended URL was not set, which
    | may happen, like, when you, instead of using Authenticate middleware of
    | this library, build an intermediate login page that leads the user to
    | OIDC Authorization Endpoint, and user manually visit that login page.
    |
    | DEPRECATED: Just link to an auth-protected page on the intermediate page
    | instead of building OIDC Authrization Endpoint URL, and let the auth
    | middleware do the work for you as usual. Adds an extra round-trip but
    | simplifies consumer code.
    |
     */
    'default_redirect_after_auth' => '/',
];
