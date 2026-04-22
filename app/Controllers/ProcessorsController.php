<?php

namespace App\Controllers;

use App\Domain\Models\ProcessorsModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpException;
class ProcessorsController extends BaseController
{
    public function __construct(private ProcessorsModel $processorsModel)
    {
    }
    public function handleGetProcessors(Request $request, Response $response, array $uri_args): Response
    {
        $filters = $request->getQueryParams();

        // if (isset($filters['manufacturer'])) {
        //     if (is_int($$filters['manufacturer']) === false) {
        //     throw new HttpException($request, "The received manufacturer ID is invalid, please pass a valid integer.", 400);
        //     }
        // }

        // if (isset($filters['generation_min'])) {
        //     if (is_int($$filters['generation_min']) === false) {
        //     throw new HttpException($request, "The received generation_min ID is invalid, please pass a valid integer.", 400);
        //     }
        // }

        // if (isset($filters['generation_max'])) {
        //     if (is_int($$filters['generation_max']) === false) {
        //     throw new HttpException($request, "The received generation_max ID is invalid, please pass a valid integer.", 400);
        //     }
        // }
        $pagination = $this->validatePaginationParams($filters);

        $this->processorsModel->setPaginationOptions($pagination['page'], $pagination['page_size']);

        $data = $this->processorsModel->getAllProcessors($filters);
        return $this->renderJson($response, $data);
    }
}
/*
manufacturer String
generation_min Integer
generation_max Integer
 */
