<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;

abstract class BaseController
{

    public function __construct() {}
    protected function renderJson(Response $response, array $data, int $status_code = 200): Response
    {
        // var_dump($data);
        $payload = json_encode($data, JSON_UNESCAPED_SLASHES |    JSON_PARTIAL_OUTPUT_ON_ERROR);
        //-- Write JSON data into the response's body.
        $response->getBody()->write($payload);
        return $response->withStatus($status_code)->withAddedHeader(HEADERS_CONTENT_TYPE, APP_MEDIA_TYPE_JSON);
    }
    protected function validatePaginationParams(array $filters): array
    {
        $page = isset($filters['page']) ? (int)$filters['page'] : 1;
        $page_size = isset($filters['page_size']) ? (int)$filters['page_size'] : 10;

        // Validate ranges
        if ($page < 1) {
            $page = 1;
        }
        if ($page_size < 1 || $page_size > 100) { // Example max limit
            $page_size = 10;
        }

        return ['page' => $page, 'page_size' => $page_size];
    }
}
