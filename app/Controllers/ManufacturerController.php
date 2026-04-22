<?php

namespace App\Controllers;

use App\Domain\Models\ManufacturerModel;
use App\Exceptions\HttpInvalidInputsException;
use Slim\Exception\HttpException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\PaginationHelper;

class ManufacturerController extends BaseController
{
    public function __construct(private ManufacturerModel $manufacturerModel) {}

    //* called from GET /manufacturers
    public function handleGetManufacturers(Request $request, Response $response): Response
    {
        $filters = $request->getQueryParams();

        $pagination = $this->validatePaginationParams($filters);

        $this->manufacturerModel->setPaginationOptions($pagination['page'], $pagination['page_size']);
        // dd($filters);
        //* JSON representation of the manufacturers collection rsc
        $manufacturers = $this->manufacturerModel->getManufacturers($filters);

        // $data = array(
        //     'manufacturers' => $manufacturers
        // );
        //* Make json representation of the collection resource
        $jsonData = json_encode($manufacturers);
        //*write the JSON data into the response body
        $response->getBody()->write($jsonData);

        //* Set the status code (default 200 OK), and set HTTP resonate headers. Associate the web content with its actual format/media type.


        return $response->withAddedHeader(
            'Content-Type',
            'application/json'
        )->withStatus(200);
        // return $this->renderJson($response, $data); This is a helper method that does exactly what we
    }

    public function handleGetManufacturerId(Request $request, Response $response, array $uri_args): Response
    {
        // dd($uri_args["manufacturer_id"]);
        // return $this->renderJson($response, []);
        $id = (int) $uri_args["manufacturer_id"];

        //! Alternative Path:
        //? Validate ID
        if (is_int($id) === false || empty($id)) {
            //! Option 1:
            // $error_info = [
            //     "status"=>"error",
            //     "message"=> "The received ID os invalid, please pass a valid integer."
            // ];
            // return $this->renderJson($response, $error_info, 400);

            //! Option 2: throw HTTP Specialized exception
            throw new HttpException($request, "The received ID os invalid, please pass a valid integer.", 400);
        }

        //! Ideal Path:
        $manufacturer = $this->manufacturerModel->getManufacturer($id);

        if ($manufacturer === false)
        // if(empty($manufacturer))
        {
            //? We try to use our custom exception class
            throw new HttpInvalidInputsException($request, sprintf("There was no matching record found for the specified manufacturer id: %s", $id));
        }

        return $this->renderJson($response, $manufacturer);
    }

    public function handleGetManufacturerLens(Request $request, Response $response, array $uri_args): Response
    {
        $filters = $request->getQueryParams();
        // dd($uri_args["manufacturer_id"]);
        // return $this->renderJson($response, []);

        $id = (int) $uri_args["manufacturer_id"];
        $lenses = $this->manufacturerModel->getManufacturerLenses($id, $filters);


        //! Alternative Path:
        //? Validate ID
        if (is_int($id) === false || empty($id)) {
            throw new HttpException($request, "The received ID os invalid, please pass a valid integer.", 400);
        }

        //! Ideal Path:
        $manufacturer = $this->manufacturerModel->getManufacturer($id);

        if ($manufacturer === false) {
            //? We try to use our custom exception class
            throw new HttpInvalidInputsException($request, sprintf("There was no matching record found for the specified manufacturer id: %s", $id));
        }

        return $this->renderJson($response, $lenses);
    }
}
