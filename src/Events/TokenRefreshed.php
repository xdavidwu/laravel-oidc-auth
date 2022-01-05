<?php

namespace LaravelOIDCAuth\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use OpenIDConnectClient\AccessToken;

/**
 * Represent the event when the access token was refreshed
 */
class TokenRefreshed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public AccessToken $oldAccessToken;

    public AccessToken $newAccessToken;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AccessToken $oldAccessToken, AccessToken $newAccessToken)
    {
        $this->oldAccessToken = $oldAccessToken;
        $this->newAccessToken = $newAccessToken;
    }
}
