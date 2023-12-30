<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    $app->options('/{routes:.*}', fn (Request $request, Response $response) => $response);

    require __DIR__ . '../../routes/@@resource.php';
    require __DIR__ . '../../routes/user.php';
};
