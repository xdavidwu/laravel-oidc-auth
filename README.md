# Laravel OIDC Auth

OpenID Connect authentication for Laravel

Save access token to session storage, and integrate with Laravel `Auth`.

Based on steverhoades/oauth2-openid-connect-client.

## Usage

Publish config with artisan command `vender:publish` for provider `LaravelOIDCAuth\OIDCAuthServiceProvider`, and fill
it.

Use `LaravelOIDCAuth\Middleware\Authenticate` as auth middleware to redirect directly to OIDC login automatically. Alternatively,
you can get login url by yourself and store state string into session storage `oidc-auth.state`, see that middleware.

### `config/auth.php`

Set guard driver to `oidc-auth-session` to remove tokens from session storage on logout.

Set provider driver to `none`, user provider is not suitable.

Alternatively, you can implement a user factory (see `LaravelOIDCAuth\Contracts\OIDCAuthenticatableFactory`) for things
like creating user DB model and use provider driver like `eloquent`.
See [User Model & User Factory](#user-model--user-factory)

## Documentation

### Tokens

You can use authenticated user object to get tokens.

```php
// Get login user, returns \LaravelOIDCAuth\Contracts\OIDCAuthenticatable|null
Auth::user();
// Get the saved access token, returns \OpenIDConnectClient\AccessToken|null
Auth::user()->accessToken();
// Get the saved id token, returns \Lcobucci\JWT\Token|null
Auth::user()->accessToken()->getIdToken();
// Get the saved refresh token, returns string|null
Auth::user()->accessToken()->getRefreshToken();
```

### ID Token

You can get id token claims like this:

```php
Route::get('/user', function () {
    return Auth::user()->accessToken()->getIdToken()->getClaims();
});
```

### Refresh the access token

If you are using `oidc-auth-session` guard with `OIDCUser`, you can easily refresh the access token:

```php
use LaravelOIDCAuth\Facades\OIDC;
// ...
Auth::user()->refreshAccessToken();
```

Typically, you don't need to refresh the access token manually, the following doc shows an automatic way to do this.

#### Auto refresh

Sometimes, you want the `oidc-auth-session` to refresh the access token automatically every request, please edit
`config/oidc_auth.php`:

```php
    // ...
    'auto_refresh' => true,
    // ...
```

Next time the user logged in, the `CallbackController` will set `rememberMe` option from
`config('oidc_auth.auto_refresh')` to the guard's `login` function.

### User Model & User Factory

If you need an authenticated user to be an eloquent model with OIDC features, your user model must implement
`LaravelOIDCAuth\Contracts\OIDCAuthenticatable`:

```php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use LaravelOIDCAuth\Contracts\OIDCAuthenticatable;
use LaravelOIDCAuth\HasAccessToken;
use LaravelOIDCAuth\RefreshesAccessToken;
use Illuminate\Foundation\Auth\User as Authenticatable;use OpenIDConnectClient\AccessToken;

class User extends Authenticatable implements OIDCAuthenticatable
{
    use Notifiable, HasAccessToken, RefreshesAccessToken;

    // ...
}
```

Two trait `HasAccessToken`, `RefreshesAccessToken` are added.

Next, you can implement a user factory `LaravelOIDCAuth\Contracts\OIDCAuthenticatableFactory` to get user from database:

```php
namespace App;

use LaravelOIDCAuth\Contracts\OIDCAuthenticatable;
use LaravelOIDCAuth\Contracts\OIDCAuthenticatableFactory;
use OpenIDConnectClient\AccessToken;

class UserFactory implements OIDCAuthenticatableFactory
{
    public function authenticatable(AccessToken $token): OIDCAuthenticatable
    {
        $user = User::firstOrCreate(/* ... */);
        $user->setAccessToken($token);
        return $user;
    }
}
```

### Customization

#### Handle OIDC callback

When the OIDC Provider authenticated the user, the browser will be redirected to the callback route on your own web
application. If you want to handle the callback request manually, you can disable the registration of default callback
route in `config/oidc_auth.php`:

```php
    'register_default_callback_route' => false,  // default value is true, set to false
```

Next, you can create your own route & controller to handle OIDC callback, and the route must be named as
`oidc-auth.callback`.

Here is an example of your callback controller:

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LaravelOIDCAuth\CallbackController;

class OidcController extends CallbackController
{
    public function callback(Request $request)
    {
        // Do something
        return parent::callback($request);
    }
    // You can overwrite other method from parent class LaravelOIDCAuth\CallbackController
}
```

Add a route in `route/web.php`:

```php
Route::get(config('oidc_auth.callback_route'), [\App\Http\Controllers\OidcController::class, 'callback'])
    ->name('oidc-auth.callback');  // MUST be named as 'oidc-auth.callback'
```
