<?php

declare(strict_types=1);

namespace App\Middleware;

use DateTimeZone;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FirePHPHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class LoggingMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $logger = new Logger('access');
        $streamHandler = new StreamHandler(APP_LOG_DIR . '/access.log', Level::Info);

        $logger->pushHandler($streamHandler);
        $logger->pushHandler(new FirePHPHandler());

        $logger->setTimezone(new DateTimeZone('America/Toronto'));

        $output = "%datetime% > %level_name% > %message% %context% %extra%\n";
        $dateFormat = "Y n j, g:i a";

        $formatter = new LineFormatter($output, $dateFormat);

        $streamHandler->setFormatter($formatter);

        $uri = $request->getUri()->getPath();
        $http_method = $request->getMethod();
        $ip = $_SERVER["REMOTE_ADDR"];
        $extra = $request->getQueryParams();
        $logger->info(sprintf('%s %s %s', $http_method, $uri, $ip), $extra);

        $data = array(
            'about' => 'This is to test info logging',
            'message' => "Marko's logger is now ready",
            'uri' => $request->getUri()->getPath(),
            'http_method' => $request->getMethod(),
            'ip' => $_SERVER["REMOTE_ADDR"],
            'extra' => $request->getQueryParams()
        );

        $response = $handler->handle($request);
        return $response;
    }
}
