<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

// specialized http exception for invalid data
// TODO This is for future iterations (such as POST or PUT methods. not needed for now)
class HttpValidationException extends HttpSpecializedException
{
    protected $code = 400;
    protected $message = 'Validation failed.';
    protected string $title = '400 Bad Request';
    protected string $description = 'The request body was not able to be verified :( ';
}
