<?php

namespace LaravelOIDCAuth\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Represent the event when client were redirected to OpenID Connect provider
 */
class OIDCAuthenticationStarted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string The URL for OIDC authorization redirection
     */
    public string $authorizationUrl;

    /**
     * @var string Opaque value used to maintain state between the request and the callback.
     */
    public string $state;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $authorizationUrl, string $state)
    {
        $this->authorizationUrl = $authorizationUrl;
        $this->state = $state;
    }
}
