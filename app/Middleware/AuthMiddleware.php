<?php

namespace App\Middleware;

use App\Helpers\Core\AppSettings;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    // private AppSettings $app_settings;

    public function __construct(private AppSettings $appSettings)
    {
        $this->appSettings = $appSettings;
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $secret = $this->appSettings->get("jwt")['secret'];
        $bearer = $request->getHeaderLine("Authorization");
        $jwt = str_replace("Bearer ", "", $bearer);
        // dd($jwt);
        return $handler->handle($request);
    }
}
