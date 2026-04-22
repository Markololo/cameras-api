<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use App\Exceptions\HttpUnsupportedMediaTypeException;

class ContentNegotiationMiddleware implements MiddlewareInterface
{
    // Making sure only application/json is accepted
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        if ($request->hasHeader('Accept')) {
            $acceptHeader = $request->getHeaderLine('Accept');

            if (str_contains($acceptHeader, 'application/json') || str_contains($acceptHeader, '*/*')) {
                return $handler->handle($request);
            } else {
                throw new HttpUnsupportedMediaTypeException(
                    $request,
                    'Unsupported Resource Representation'
                );
            }
        }

        return $handler->handle($request);
    }
}
