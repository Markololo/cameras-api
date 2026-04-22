<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpInvalidMethodException extends HttpSpecializedException
{
    protected $code = 405;
    protected $message = 'Validation failed.';
    protected string $title = '405 Method Not Allowed';
    protected string $description = 'HTTP method not supported for this endpoint';
}
