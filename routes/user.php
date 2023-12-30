<?php

use App\Controller\User\UserController;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

$app->group('/user', function (Group $group) {
    $group->get('/', [UserController::class, 'index']);
    $group->post('/create', [UserController::class, 'create']);
    $group->get('/show/{id}', [UserController::class, 'show']);
    $group->delete('/delete/{id}', [UserController::class, 'delete']);
});
