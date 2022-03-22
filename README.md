# Laravel OIDC Auth

OpenID Connect authentication for Laravel.

Save access token to session storage, and integrate with Laravel `Auth`.

Based on steverhoades/oauth2-openid-connect-client.

## Usage

Publish config with artisan command `vender:publish` for provider `LaravelOIDCAuth\OIDCAuthServiceProvider`, and fill
it.

Use `LaravelOIDCAuth\Middleware\Authenticate` as auth middleware to redirect directly to OIDC login automatically. Alternatively,
you can get login url by yourself and store state string into session storage `oidc-auth.state`, see that middleware.

### `config/auth.php`

Set guard driver to `oidc-auth-session` to remove tokens from session storage on logout.

Remove key `providers`, user provider is not used.

Alternatively, you can implement a user factory (see `LaravelOIDCAuth\Contracts\OIDCAuthenticatableFactory`) for things
like creating user DB model and use provider driver like `eloquent`.
See [User Model & User Factory](#user-model--user-factory)

## Documentation

### Tokens

You can use authenticated user object to get tokens.

```php
// Get login user, returns \LaravelOIDCAuth\Contracts\OIDCAuthenticatable|null
Auth::user();
// Get the saved access token, returns \OpenIDConnectClient\AccessToken
Auth::user()->accessToken();
// Get the saved id token, returns \Lcobucci\JWT\Token|null
Auth::user()->accessToken()->getIdToken();
// Get the saved refresh token, returns string|null
Auth::user()->accessToken()->getRefreshToken();
```

### ID Token

You can get id token claims like this:

```php
Auth::user()->accessToken()->getIdToken()->getClaims();
```

### Refresh the access token

If you are using `oidc-auth-session` guard with `OIDCUser`, you can easily check the expiry and refresh access token
like following:

```php
if (Auth::user()->accessToken()->hasExpired()) {
    Auth::user()->refreshAccessToken();
}
```

#### Auto refresh

You can also turn the auto refresh feature on instead of refreshing manually.
If you need to automatically validate expiration and refresh tokens if possible, enable `auto_refresh` in
`config/oudc_auth.php`.

The `oidc-auth-session` guard checks expiry timestamp in the access token every request.
If the access token is expired, the guard will refresh the access token.

### User model & user factory

If a custom user implementation is desired (for example, as an Eloquent model), it should implement
`LaravelOIDCAuth\Contracts\OIDCAuthenticatable` interface.

In your custom user implementation, the `LaravelOIDCAuth\RefreshesAccessToken` trait is required if you use token
refreshing feature (automatically and manually).  And the `LaravelOIDCAuth\HasAccessToken` trait contains
`$accessToken`'s setter and getter.

Next, you can implement a user factory `LaravelOIDCAuth\Contracts\OIDCAuthenticatableFactory` to get user from your
storage (for example, as an database).

### Customization

#### Handle OIDC callback

If you want to handle the callback request manually, you can disable the option `register_default_callback_route` in
`config/oidc_auth.php`.

Next, you can create your own route & controller to handle OIDC callback, and the route must be named as
`oidc-auth.callback`.
