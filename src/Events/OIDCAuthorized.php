<?php

namespace LaravelOIDCAuth\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use OpenIDConnectClient\AccessToken;

/**
 * Represent the event when OpenID Connect provider retrieve the token successfully
 */
class OIDCAuthorized
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public AccessToken $accessToken;

    /**
     * @var Request|null The request returned by OpenID Connect provider after authorization redirected
     */
    public ?Request $callbackRequest;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AccessToken $accessToken, ?Request $callbackRequest = null)
    {
        $this->accessToken = $accessToken;
        $this->callbackRequest = $callbackRequest;
    }
}
