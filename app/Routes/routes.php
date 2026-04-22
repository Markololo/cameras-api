<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Controllers\AuthController;
use App\Controllers\CamerasController;
use App\Controllers\LensesController;
use App\Controllers\ManufacturerController;
use App\Controllers\MountsController;
use App\Controllers\ProcessorsController;
use App\Helpers\DateTimeHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Middleware\LoggingMiddleware;
use Slim\Routing\RouteCollectorProxy;
use App\Middleware\AuthMiddleware;

return static function (Slim\App $app): void {

    // Routes without authentication check: /login, /token
    $app->get("/token", [AuthController::class, "handleGenerateToken"]);

    $app->group('', function (RouteCollectorProxy $group) {
        $group->get("/manufacturers", [ManufacturerController::class, "handleGetManufacturers"],);
    })->add(AuthMiddleware::class);

    //* ROUTE: GET /
    $app->get('/', [AboutController::class, 'handleAboutWebService']);

    // $app->get('/manufacturers', [ManufacturerController::class, 'handleGetManufacturers']);
    $app->get('/manufacturers/{manufacturer_id}', [ManufacturerController::class, 'handleGetManufacturerId']);
    $app->get('/manufacturers/{manufacturer_id}/lenses', [ManufacturerController::class, 'handleGetManufacturerLens']);

    $app->get('/cameras', [CamerasController::class, 'handleGetCameras']);
    $app->get('/cameras/{body_id}', [CamerasController::class, 'handleGetCameraId']);
    $app->get('/cameras/{camera_id}/batteries', [CamerasController::class, 'handleGetCameraBatteries']);


    $app->get('/processors', [ProcessorsController::class, 'handleGetProcessors']);

    $app->get('/lenses/{lens_id}/specs', [LensesController::class, 'handleGetLensesSpecs']);

    $app->get('/mounts/{mount_id}/cameras', [MountsController::class, 'handleGetMountsCamera']);
    $app->get('/mounts/{mount_id}/lenses', [MountsController::class, 'handleGetMountsLenses']);
    $app->get('/ping', function (Request $request, Response $response, $args) {
        $payload = [
            "greetings" => "Reporting! Hello there!",
            "now" => DateTimeHelper::now(DateTimeHelper::Y_M_D_H_M),
        ];
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR));
        return $response;
    });

    $app->get('/error', function (Request $request, Response $response, $args) {
        throw new \Slim\Exception\HttpNotFoundException($request, "Something went wrong");
    });

    $app->get('/phpinfo', function (Request $request, Response $response, $args) {
        ob_start();
        phpinfo();
        $info = ob_get_clean();
        $response->getBody()->write($info);
        return $response->withHeader('Content-Type', 'text/html');
    });

    $app->get('/log-test', [AboutController::class, 'handleLoggingTest'])->add(LoggingMiddleware::class);

    // $app->get('/log-test', function (Request $request, Response $response): Response {
    //     //TODO:  Your logging code will go here (Steps 2 and 3).
    //     $logger = new Logger('access');
    //     $logger->pushHandler(new StreamHandler(APP_LOG_DIR .'/info_logs.log', Level::Debug));
    //     $logger->info('My logger is now ready');

    //     //TODO: Return a simple JSON response for testing purposes.
    // });
};
 // add new data
//  $logger->info('adding new user', ['user'=>'Marko']);
