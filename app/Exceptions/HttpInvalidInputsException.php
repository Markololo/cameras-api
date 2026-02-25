<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpInvalidInputsException extends HttpSpecializedException
{
    protected $code = 400;
    protected $message = 'Validation failed.';
    protected string $title = '400 Bad Request';
    protected string $description = 'The request input is invalid.';
}
