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
     * @var string See: https://openid.net/specs/openid-connect-core-1_0.html#rfc.section.3.1.2.1
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
