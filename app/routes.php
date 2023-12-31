<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    $app->options('/{routes:.*}', fn (Request $request, Response $response) => $response);

    define('ROUTES_PATH', __DIR__ . '../../routes/');

    require ROUTES_PATH . '@@resource.php';
    require ROUTES_PATH . 'user.php';
    require ROUTES_PATH . 'master.php';
};
