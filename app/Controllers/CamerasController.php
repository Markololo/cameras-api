<?php

namespace App\Controllers;

use App\Domain\Models\CamerasModel;
use App\Domain\Models\ManufacturerModel;
use App\Exceptions\HttpInvalidInputsException;
use Slim\Exception\HttpException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CamerasController extends BaseController
{
    public function __construct(private CamerasModel $cameras_model) {}
    public function handleGetCameraId(Request $request, Response $response, array $uri_args): Response
    {
        $id = (int) $uri_args["body_id"];

        if (is_int($id) === false || empty($id)) {

            throw new HttpException($request, "The received ID os invalid, please pass a valid integer.", 400);
        }

        //! Ideal Path:
        $cameras = $this->cameras_model->getCamera($id);

        if ($cameras === false)
        {
            throw new HttpInvalidInputsException($request, sprintf("There was no matching record found for the specified camera id: %s", $id));
        }

        return $this->renderJson($response, $cameras);
    }

    public function handleGetCameras(Request $request, Response $response): Response
    {
        $filters = $request->getQueryParams();

        $pagination = $this->validatePaginationParams($filters);

        $this->cameras_model->setPaginationOptions($pagination['page'], $pagination['page_size']);

        $cameras = $this->cameras_model->getAllCameras($filters);
        return $this->renderJson($response, $cameras);
    }

    public function handleGetCameraBatteries(Request $request, Response $response,array $uri_args): Response
    {
        $id = (int) $uri_args["camera_id"];
        if (is_int($id) === false || empty($id)) {

            throw new HttpException($request, "The received ID os invalid, please pass a valid integer.", 400);
        }

        $filters = $request->getQueryParams();

        $pagination = $this->validatePaginationParams($filters);

        $this->cameras_model->setPaginationOptions($pagination['page'], $pagination['page_size']);

        $data = $this->cameras_model->getCameraBatteries($id, $filters);

        if ($data === false)
        {
            throw new HttpInvalidInputsException($request, sprintf("There was no matching record found for the specified camera id: %s", $id));
        }
        return $this->renderJson($response, $data);
    }

}
