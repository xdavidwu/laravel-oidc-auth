<?php

namespace LaravelOIDCAuth\Exceptions;

class InvalidStateException extends AuthenticationException
{
    public function __construct()
    {
        parent::__construct('state mismatch or missing');
    }
}
