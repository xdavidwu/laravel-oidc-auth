<?php

namespace LaravelOIDCAuth;

use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthenticationException extends HttpException
{
    public function __construct($message, $code = 400)
    {
        parent::__construct($code, $message);
    }
}
