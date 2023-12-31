<?php

declare(strict_types=1);

use App\Application\Middleware\LoggerMiddleware;
use App\Application\Middleware\SessionMiddleware;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Twig\TwigFunction;

return function (App $app) {
    $app->add(SessionMiddleware::class);
    $app->add(LoggerMiddleware::class);
    
    $twig = Twig::create(__DIR__ . '../../resource/public', [
        'auto_reload' => true,
        'debug' => true,
        'strict_variables' => true,
        'autoescape' => 'html',
        'optimizations' => -1
    ]);

    $app->add(TwigMiddleware::create($app, $twig));
};
