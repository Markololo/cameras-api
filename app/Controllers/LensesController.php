<?php

namespace App\Controllers;

use App\Domain\Models\LensesModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Exceptions\HttpInvalidInputsException;
use Slim\Exception\HttpException;
class LensesController extends BaseController
{
    public function __construct(private LensesModel $lensesModel)
    {
    }

    ///* lenses/{lens_id}/specs
    public function handleGetLensesSpecs(Request $request, Response $response, array $uri_args): Response
    {
        $id = (int) $uri_args["lens_id"];

        if (is_int($id) === false || empty($id)) {

            throw new HttpException($request, "The received ID os invalid, please pass a valid integer.", 400);
        }

        $filters = $request->getQueryParams();

        $pagination = $this->validatePaginationParams($filters);

        $this->lensesModel->setPaginationOptions($pagination['page'], $pagination['page_size']);

        $data = $this->lensesModel->getLensSpecs($filters, $id);

        if ($data === false)
        {
            throw new HttpInvalidInputsException($request, sprintf("There was no matching record found for the specified lens id: %s", $id));
        }

        return $this->renderJson($response, $data);
    }

}
