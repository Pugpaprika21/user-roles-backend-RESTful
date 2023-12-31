<?php

namespace App\Controller\Master;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;
use R;

class MasterUserRoleSettingController
{
    public function __construct(private ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index(Request $request, Response $response): Response
    {
        $this->container->get(R::class);

        $limit = !empty($params['page']) ? esc($params['page']) : 1;
        $offset = !empty($params['limit']) ? esc($params['limit']) : R::count('master_user_roles');

        $roleSettings = R::findAndExport('master_user_roles_settings');
        R::close();
        return json($response, ['msg' => $roleSettings]);
    }

    public function create(Request $request, Response $response): Response
    {
        $this->container->get(R::class);

        $body = $request->getParsedBody();

        $roleList = [];
        $roleSettings = $body['role_settings'] ?? [];
        foreach ($roleSettings as $role) {
            $mRole = R::findOne('master_user_roles', 'id = ?', [$role['roleId']]);
            $roleList[] = $mRole;
        }
        R::close();
        return json($response, ['msg' => $roleList]);
    }

    public function delete(Request $request, Response $response): Response
    {
        $this->container->get(R::class);

        $body = $request->getParsedBody();

        $roleList = [];
        $roleSettings = $body['role_settings'] ?? [];
        foreach ($roleSettings as $role) {
            $mRole = R::findOne('master_user_roles', 'id = ?', [$role['roleId']]);
            $roleList[] = $mRole;
        }
        R::close();
        return json($response, ['msg' => $roleList]);
    }
}

/* 
    {
        "userId": 10,
        "role_settings": [
            {
                "roleId": 1
            },
            {
                "roleId": 2
            }
        ]
    }
*/