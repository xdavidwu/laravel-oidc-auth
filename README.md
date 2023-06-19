# Laravel OIDC Auth

OpenID Connect authentication for Laravel

Save access token to session storage, and integrate with Laravel `Auth`.

Based on steverhoades/oauth2-openid-connect-client.

## Usage

Publish config with artisan command `vender:publish` for provider `LaravelOIDCAuth\OIDCAuthServiceProvider`, and fill it.

Use `LaravelOIDCAuth\Authenticate` as auth middleware to redirect directly to OIDC login automatically.

### `config/auth.php`

Set guard driver to `oidc-auth-session` to remove tokens from session storage on logout.

Set provider driver to `oidc-auth-session` to make `Auth::user()` return a `LaravelOIDCAuth\OIDCUser` authenticable from saved token.

Alternatively, you can implement a user factory (see `LaravelOIDCAuth\UserFactoryInterface`) for things like creating user DB model and use provider driver like `eloquent`.
