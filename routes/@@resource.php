<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

$app->group('/resource', function (Group $group) {
    $group->get('/public/[{folder}[/{filename}]]', function (Request $request, Response $response) {
        $params = $request->getQueryParams();

        $folder = !empty($params['folder']) ? esc($params['folder']) : "-";
        $filename = !empty($params['filename']) ? esc($params['filename']) : "-";

        $path = $this->get('resource_path') . "public/{$folder}/{$filename}";
    
        return fileResponse($response, $path);
    });
});