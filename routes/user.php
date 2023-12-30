<?php

use App\Controller\User\UserController;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

$app->group('/user', function (Group $group) {
    $group->get('', [UserController::class, 'index']);
    $group->post('/create', [UserController::class, 'create']);
});
