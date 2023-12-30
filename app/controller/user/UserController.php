<?php

namespace App\Controller\User;

use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;

class UserController
{
    public function __construct(private ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index(Request $request, Response $response): Response
    {
        $db = $this->container->get('rb');

        $params = $request->getQueryParams();

        $limit = !empty($params['page']) ? esc($params['page']) : 1;
        $offset = !empty($params['limit']) ? esc($params['limit']) : $db->count('users');

        $userList = [];
        if ($users = $db->findAll("users", "ORDER BY created_at LIMIT ?, ?", [$limit, $offset])) {
            foreach ($users as $user) {
                $fileStos = $db->exportAll($db->findOne(esc('file_storage_system', true), 'ref_id = ? AND ref_field = ?', [$user->id, 'profile']))[0];
                $userList[] = [
                    'Id' => (int)$user['id'],
                    'Username' => $user['username'],
                    'Email' => $user['email'],
                    'Profile' => !empty($fileStos) ? public_path("folder=upload&filename={$fileStos['file_name']}") : "",
                    'Roles' => [],
                    'CreatedAt' => (new DateTime($user['created_at']))->format('d-m-Y'),
                ];
            }

            $db->close();
            return json($response, ['users' => $userList, 'rows' => count($userList)]);
        }

        return json($response, ['users' => []]);
    }

    public function create(Request $request, Response $response): Response
    {
        $db = $this->container->get('rb');
        $directory = $this->container->get('resource_path');

        $body = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();

        $username = esc($body['username']);
        $password = esc($body['password']);

        if ($db->findOne('users', 'username = ? OR password = ?', [$username, $password])) {
            return json($response, ['msg' => 'username or password exiting..']);
        }

        $user = $db->dispense('users');
        if ($user->isEmpty()) {
            $user->username = $username;
            $user->password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
            $user->first_name = '';
            $user->last_name = '';
            $user->email = $username . "@gmail.com";
            $user->address = '';
            $user->phone_number = '';
            $user->profile = 'profile';
            $user->remember_token = '';
            $user->created_at = date('Y-m-d');
            $user->updated_at = date('Y-m-d');
            $id = $db->store($user);
            $db->close();

            if (!empty($uploadedFiles['files'])) {
                $uploadedFile = $uploadedFiles['files'];
                if ($uploadedFile->getError() === UPLOAD_ERR_OK) {

                    $fSize = $uploadedFile->getSize();
                    $fMd = $uploadedFile->getClientMediaType();
                    $fExt = pathinfo(esc($uploadedFile->getClientFilename()), PATHINFO_EXTENSION);
                    $filename = fileUploaded($directory . "public/upload/", $uploadedFile);

                    $fileSto = $db->dispense(esc('file_storage_system', true));
                    $fileSto->file_name = $filename;
                    $fileSto->file_size = $fSize;
                    $fileSto->file_type = $fMd;
                    $fileSto->file_extension = $fExt;
                    $fileSto->content = '';
                    $fileSto->ref_id = $id;
                    $fileSto->ref_table = 'users';
                    $fileSto->ref_field = 'profile';
                    $fileSto->created_at = date('Y-m-d');
                    $fileSto->updated_at = date('Y-m-d');
                    $db->store($fileSto);
                    $db->close();
                }
            }

            return json($response, ['msg' => 'Create User Success.'], 201);
        }

        return json($response, ['msg' => 'Create User Error.']);
    }
}
