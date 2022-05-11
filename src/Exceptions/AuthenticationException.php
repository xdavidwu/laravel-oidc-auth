<?php

namespace LaravelOIDCAuth\Exceptions;

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

    public function getStatusCode(): int
    {
        return $this->code;
    }

    public function getHeaders(): array
    {
        return [];
    }
}
