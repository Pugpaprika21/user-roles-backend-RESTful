<?php

namespace App\Controller\Master;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;
use R;

class MasterUserRoleController
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

        $roles = R::findAndExport('master_user_roles');
        R::close();
        return json($response, ['roles' => $roles]);
    }

    public function create(Request $request, Response $response, array $agre): Response
    {
        $this->container->get(R::class);

        $body = $request->getParsedBody();

        $roleCode = esc($body['role_code']);
        $roleName = esc($body['role_name']);

        if (R::findOne('master_user_roles', 'role_code = ? OR role_name = ?', [$roleCode, $roleName])) {
            return json($response, ['msg' => 'role_code or role_name exiting..']);
        }

        $mUserRoles = R::xdispense('master_user_roles');
        $mUserRoles->role_code = $roleCode;
        $mUserRoles->role_name = $roleName;
        $mUserRoles->created_at = date('Y-m-d');
        $mUserRoles->updated_at = date('Y-m-d');
        R::store($mUserRoles);
        R::close();

        return json($response, ['msg' => 'Create Roles Success.'], 201);
    }

    public function edit(Request $request, Response $response, array $agre): Response
    {
        $this->container->get(R::class);

        $id = esc($agre['id']);

        if (!R::findOne('master_user_roles', 'id = ?', [$id])) {
            return json($response, ['msg' => 'Role not Found..']);
        }

        $role = R::findOne('master_user_roles', 'id = ?', [$id]);
        R::close();
        return json($response, ['role' => $role]);
    }

    public function update(Request $request, Response $response, array $agre): Response
    {
        $this->container->get(R::class);

        $body = $request->getParsedBody();

        $id = esc($agre['id']);
        $roleCode = esc($body['role_code']);
        $roleName = esc($body['role_name']);

        if (!R::findOne('master_user_roles', 'id = ?', [$id])) {
            return json($response, ['msg' => 'Role not Found..']);
        }

        if (R::findOne('master_user_roles', 'role_code = ? OR role_name = ?', [$roleCode, $roleName])) {
            return json($response, ['msg' => 'role_code or role_name exiting..']);
        }

        $mUserRoles = R::load('master_user_roles', $id);
        $mUserRoles->role_code = $roleCode;
        $mUserRoles->role_name = $roleName;
        $mUserRoles->updated_at = date('Y-m-d');
        R::store($mUserRoles);
        R::close();

        return json($response, ['msg' => 'Update Roles Success.']);
    }

    public function delete(Request $request, Response $response, array $agre): Response
    {
        $this->container->get(R::class);

        $id = esc($agre['id']);

        if (!R::findOne('master_user_roles', 'id = ?', [$id])) {
            return json($response, ['msg' => 'Role not Found..']);
        }

        R::trash('master_user_roles', $id);
        R::close();
        return json($response, ['msg' => 'Delete Roles Success.']);
    }
}
