<?php

namespace LaravelOIDCAuth;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class AuthenticationException extends \RuntimeException implements HttpExceptionInterface
{
    protected $code;
    protected $message;

    public function __construct($message, $code = 400)
    {
        $this->code = $code;
        parent::__construct($message);
    }

    public function getStatusCode()
    {
        return $this->code;
    }

    public function getHeaders()
    {
        return [];
    }
}
