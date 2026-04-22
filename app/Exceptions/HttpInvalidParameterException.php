<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;


// specialized http exception for bad parameters
class HttpInvalidParameterException extends HttpSpecializedException
{
    protected $code = 400;
    protected $message = 'Bad parameter';
    protected string $title = '400 Bad Request';
    protected string $description = 'Query Parameters are not valid!';
}
