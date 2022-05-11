<?php

namespace LaravelOIDCAuth\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Throwable;

/**
 * Represent the event when OpenID Connect provider trying to get the token but failed
 */
class OIDCAuthorizationFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Request|null The request returned by OpenID Connect provider after authorization redirected
     */
    public ?Request $callbackRequest;

    /**
     * @var Throwable|null The error
     */
    public ?Throwable $error;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(?Request $callbackRequest = null, ?Throwable $error = null)
    {
        $this->callbackRequest = $callbackRequest;
        $this->error = $error;
    }
}
