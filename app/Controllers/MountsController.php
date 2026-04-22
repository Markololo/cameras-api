<?php

namespace App\Controllers;

use App\Domain\Models\MountsModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Exceptions\HttpInvalidInputsException;
use Slim\Exception\HttpException;
class MountsController extends BaseController
{
    public function __construct(private MountsModel $mountsModel)
    {
    }

    /**
     * For /mounts/{mount_id}/cameras
     *Gets a list of zero or more camera resources that use the specified mount
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @return Response
     */
    public function handleGetMountsCamera(Request $request, Response $response, array $uri_args): Response
    {
        $mount_id = (int) $uri_args["mount_id"];

        if (is_int($mount_id) === false || empty($mount_id)) {

            throw new HttpException($request, "The received ID os invalid, please pass a valid integer.", 400);
        }

        $filters = $request->getQueryParams();

        $pagination = $this->validatePaginationParams($filters);

        $this->mountsModel->setPaginationOptions($pagination['page'], $pagination['page_size']);

        $data = $this->mountsModel->getMountsCameras($filters, $mount_id);

        if ($data === false)
        {
            throw new HttpInvalidInputsException($request, sprintf("There was no matching record found for the specified mount id: %s", $mount_id));
        }
        return $this->renderJson($response, $data);
    }
    /**
     * for /mounts/{mount_id}/lenses
     * lens resources compatible with the specified mount
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @return Response
     */
    public function handleGetMountsLenses(Request $request, Response $response, array $uri_args): Response
    {
        $mount_id = (int) $uri_args["mount_id"];
        if (is_int($mount_id) === false || empty($mount_id)) {

            throw new HttpException($request, "The received ID os invalid, please pass a valid integer.", 400);
        }

        $filters = $request->getQueryParams();

        $pagination = $this->validatePaginationParams($filters);

        $this->mountsModel->setPaginationOptions($pagination['page'], $pagination['page_size']);

        $data = $this->mountsModel->getMountsLenses($filters, $mount_id);

        if ($data === false)
        {
            throw new HttpInvalidInputsException($request, sprintf("There was no matching record found for the specified mount id: %s", $mount_id));
        }
        return $this->renderJson($response, $data);
    }

}
