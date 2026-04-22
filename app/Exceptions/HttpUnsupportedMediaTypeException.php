<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;


// specialized http exception for bad media type
class HttpUnsupportedMediaTypeException extends HttpSpecializedException
{
    protected $code = 415;
    protected $message = 'Unsupported media type';
    protected string $title = '415 Unsupported Media Type';
    protected string $description = 'We do not support the requested media type :/';
}
