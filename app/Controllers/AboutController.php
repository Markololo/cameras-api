<?php

declare(strict_types=1);

namespace App\Controllers;

use DateTimeZone;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FirePHPHandler;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
class AboutController extends BaseController
{
    private const API_NAME = 'Cameras-Api';

    private const API_VERSION = '1.0.0';

    public function handleAboutWebService(Request $request, Response $response): Response
    {

        $data = array(
            'api' => self::API_NAME,
            'version' => self::API_VERSION,
            'about' => 'Welcome! This i a Web service that provides this and that...',
            'authors' => 'FrostyBee',
            'resources' => '/blah'
        );

        return $this->renderJson($response, $data);
    }

    public function handleLoggingTest(Request $request, Response $response): Response
    {
        $logger = new Logger('access');
        $handler = new StreamHandler(APP_LOG_DIR .'/access.log', Level::Info);

        $logger->pushHandler($handler);

        $logger->setTimezone(new DateTimeZone('America/Toronto'));

        $uri = $request->getUri()->getPath();
        $http_method = $request->getMethod();
        $ip = $_SERVER["REMOTE_ADDR"];
        $extra = $request->getQueryParams();


        $output = "%datetime% > %method% > %uri% %ip% %extra%\n";
        $dateFormat = "Y n j, g:i a";

        $formatter = new LineFormatter($output, $dateFormat);

        $handler->setFormatter($formatter);

        $logger->info(sprintf('%s %s %s', $http_method, $uri,$ip), $extra);

        $data = array(
            'about' => 'This is to test info logging',
            'message' => "Mariam's logger is now ready",
            'uri' => $request->getUri()->getPath(),
            'http_method' => $request->getMethod(),
            'ip' =>$_SERVER["REMOTE_ADDR"],
            'extra' => $request->getQueryParams()
        );
        return $this->renderJson($response, $data);
    }
}
// http://10.1.3.127/
//https://www.slimframework.com/docs/v4/
