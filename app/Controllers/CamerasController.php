<?php

namespace App\Controllers;

use App\Domain\Models\CamerasModel;
use App\Domain\Models\ManufacturerModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CamerasController extends BaseController
{
    public function __construct(private CamerasModel $cameras_model) {}
    public function handleGetCameraId(Request $request, Response $response, array $uri_args): Response
    {
        $id = (int) $uri_args["body_id"];

        $cameras = $this->cameras_model->getCamera($id);
        return $this->renderJson($response, $cameras);
    }
}
