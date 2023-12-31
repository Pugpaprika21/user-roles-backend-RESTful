<?php

use App\Controller\Master\MasterUserRoleController;
use App\Controller\Master\MasterUserRoleSettingController;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

$app->group('/master', function (Group $group) {
    $group->group('/user-roles', function (Group $group) {
        $group->get('/', [MasterUserRoleController::class, 'index']);
        $group->post('/create', [MasterUserRoleController::class, 'create']);
        $group->get('/edit/{id}', [MasterUserRoleController::class, 'edit']);
        $group->put('/update/{id}', [MasterUserRoleController::class, 'update']);
        $group->delete('/delete/{id}', [MasterUserRoleController::class, 'delete']);
    });
    $group->group('/user-roles-setting', function (Group $group) {
        $group->get('/', [MasterUserRoleSettingController::class, 'index']);
        $group->post('/create', [MasterUserRoleSettingController::class, 'create']);
        $group->post('/delete', [MasterUserRoleSettingController::class, 'delete']);
    });
});