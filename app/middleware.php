<?php

declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Twig\TwigFunction;

return function (App $app) {
    $app->add(SessionMiddleware::class);

    $twig = Twig::create(__DIR__ . '../../resource/public', [
        'auto_reload' => true,
        'debug' => true,
        'strict_variables' => true,
        'autoescape' => 'html',
        'optimizations' => -1
    ]);

    // $twig->getEnvironment()->addFunction(new TwigFunction('asset', function ($asset) {
    //     global $env;

    //     if (strpos($asset, '..') !== false || strpos($asset, '/') === 0) {
    //         throw new InvalidArgumentException("Invalid routePath");
    //     }

    //     $encodedRoutePath = urlencode($asset);
    //     return $env['APP_URL'] . 'resource/public/' . $asset;
    // }));

    $app->add(TwigMiddleware::create($app, $twig));
};
