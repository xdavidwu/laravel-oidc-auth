<?php

namespace LaravelOIDCAuth\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

/**
 * Represent the event when OpenID Connect provider authenticated and sent the authorization code
 */
class OIDCAuthenticated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Request|null The request returned by OpenID Connect provider after authorization redirected
     */
    public ?Request $callbackRequest;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Request $callbackRequest)
    {
        $this->callbackRequest = $callbackRequest;
    }
}
