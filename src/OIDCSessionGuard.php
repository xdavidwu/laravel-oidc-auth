<?php

namespace LaravelOIDCAuth;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Events\Dispatcher;
use LaravelOIDCAuth\Contracts\OIDCAuthenticatable;
use LaravelOIDCAuth\Contracts\OIDCAuthenticatableFactory;
use OpenIDConnectClient\AccessToken;

class OIDCSessionGuard implements Guard
{
    use GuardHelpers;

    protected AuthorizationCodeFlowInterface $flow;

    protected OIDCAuthenticatableFactory $userFactory;

    protected string $name;

    protected Dispatcher $events;

    private string $sessionKey = 'oidc-auth';
    private string $tokenKey = 'oidc-auth.access_token';
    private string $autoRefreshKey = 'oidc-auth.auto_refresh';

    public function __construct(
        string $name,
        AuthorizationCodeFlowInterface $flow,
        OIDCAuthenticatableFactory $userFactory
    ) {
        $this->name = $name;
        $this->flow = $flow;
        $this->userFactory = $userFactory;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return Authenticatable|null
     */
    public function user(): ?Authenticatable
    {
        if ($this->tryRefreshAccessToken()) {
            if (is_null($this->user)) {
                $this->user = $this->userFactory->authenticatable($this->getAccessToken());
                $this->fireAuthenticatedEvent($this->user);
            }
            return $this->user;
        }
        return null;
    }

    protected function tryRefreshAccessToken(): bool
    {
        if ($this->hasAccessToken()) {
            if ($this->getAccessToken()->hasExpired()) {
                if ($this->getAutoRefresh()) {
                    try {
                        $this->refreshAccessTokenAndUpdateUser();
                        return true;
                    } catch (\Exception $e) {
                        $this->clearSession();
                        return false;
                    }
                } else {
                    $this->clearSession();
                }
            } else {
                return true;
            }
        }
        return false;
    }

    protected function hasAccessToken(): bool
    {
        return session()->has($this->tokenKey);
    }

    protected function getAccessToken(): ?AccessToken
    {
        return session($this->tokenKey);
    }

    protected function getAutoRefresh(): bool
    {
        return session($this->autoRefreshKey, false);
    }

    /**
     * Refresh the access token of current authenticated user and update the user
     *
     * @return void
     * @throws \OpenIDConnectClient\Exception\InvalidTokenException
     */
    public function refreshAccessTokenAndUpdateUser()
    {
        $token = $this->getAccessToken();
        $newToken = $this->flow->refreshAccessToken($token);
        $this->setAccessToken($newToken);
        $this->user = $this->userFactory->authenticatable($newToken);
        $this->fireAuthenticatedEvent($this->user);
    }

    protected function setAccessToken(AccessToken $token): void
    {
        session()->put($this->tokenKey, $token);
    }

    /**
     * Fire the authenticated event if the dispatcher is set.
     *
     * @param Authenticatable $user
     * @return void
     */
    protected function fireAuthenticatedEvent(Authenticatable $user)
    {
        if (isset($this->events)) {
            $this->events->dispatch(new Authenticated(
                $this->name, $user
            ));
        }
    }

    protected function clearSession()
    {
        session()->forget($this->tokenKey);
        session()->forget($this->autoRefreshKey);
    }

    /**
     * Validate a user's credentials.
     *
     * DO NOT call this function
     *
     * @param array $credentials
     * @return bool
     */
    public function validate(array $credentials = []): bool
    {
        throw new \LogicException('This is not applicable for OIDC');
    }

    /**
     * Log a user into the application.
     *
     * @param Authenticatable $user
     * @param bool $auto_refresh if $auto_refresh is true, the access token will be refreshed automatically.
     * @return void
     */
    public function login(Authenticatable $user, bool $auto_refresh = false)
    {
        $this->fireLoginEvent($user, $auto_refresh);
        $this->setUser($user);
        $this->setAutoRefresh($auto_refresh);
    }

    /**
     * Fire the login event if the dispatcher is set.
     *
     * @param Authenticatable $user
     * @param bool $auto_refresh
     * @return void
     */
    protected function fireLoginEvent(Authenticatable $user, bool $auto_refresh = false)
    {
        if (isset($this->events)) {
            $this->events->dispatch(new Login(
                $this->name, $user, $auto_refresh
            ));
        }
    }

    /**
     * Set the current user.
     *
     * @param Authenticatable $user
     * @return $this
     */
    public function setUser(Authenticatable $user): Guard
    {
        if (!$user instanceof OIDCAuthenticatable) {
            throw new \LogicException('OIDCSessionGuard got an object that is not an OIDCUserInterface');
        }
        $this->setAccessToken($user->getAccessToken());
        $this->user = $user;
        $this->fireAuthenticatedEvent($user);

        return $this;
    }

    protected function setAutoRefresh(bool $value)
    {
        session()->put($this->autoRefreshKey, $value);
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        $this->clearSession();
        if (isset($this->events)) {
            $this->events->dispatch(new Logout($this->name, $this->user));
        }
        $this->user = null;
    }

    /**
     * Get the event dispatcher instance.
     *
     * @return Dispatcher
     */
    public function getDispatcher(): Dispatcher
    {
        return $this->events;
    }

    /**
     * Set the event dispatcher instance.
     *
     * @param Dispatcher $events
     * @return void
     */
    public function setDispatcher(Dispatcher $events)
    {
        $this->events = $events;
    }
}
